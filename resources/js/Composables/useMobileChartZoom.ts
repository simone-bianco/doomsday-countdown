import { computed, onBeforeUnmount, onMounted, ref, type Ref } from 'vue';

type ChartGesture =
    | { readonly mode: 'pinch'; readonly distance: number; readonly scale: number }
    | { readonly mode: 'pan'; readonly x: number; readonly y: number; readonly panX: number; readonly panY: number }
    | { readonly mode: 'tap'; readonly x: number; readonly y: number };

const MIN_CHART_SCALE = 1;
const MAX_CHART_SCALE = 4;
const CHART_ZOOM_STEP = 0.5;

export function useMobileChartZoom(mobile: Readonly<Ref<boolean>>) {
    const chartSurface = ref<HTMLElement | null>(null);
    const isZoomed = ref(false);
    const chartScale = ref(MIN_CHART_SCALE);
    const chartPanX = ref(0);
    const chartPanY = ref(0);
    const chartTransformStyle = computed(() => ({
        transform: `translate3d(${chartPanX.value}px, ${chartPanY.value}px, 0) scale(${chartScale.value})`,
        transformOrigin: 'center center',
    }));
    let previousDocumentOverflow: string | null = null;
    let chartGesture: ChartGesture | null = null;
    let lastChartTapAt = 0;

    function restoreDocumentScroll(): void {
        if (typeof document === 'undefined' || previousDocumentOverflow === null) {
            return;
        }

        document.documentElement.style.overflow = previousDocumentOverflow;
        previousDocumentOverflow = null;
    }

    function clamp(value: number, minimum: number, maximum: number): number {
        return Math.min(Math.max(value, minimum), maximum);
    }

    function touchDistance(first: Touch, second: Touch): number {
        return Math.hypot(second.clientX - first.clientX, second.clientY - first.clientY);
    }

    function clampChartPan(x: number, y: number, scale = chartScale.value): { readonly x: number; readonly y: number } {
        if (scale <= MIN_CHART_SCALE) {
            return { x: 0, y: 0 };
        }

        const bounds = chartSurface.value?.getBoundingClientRect();
        const viewportWidth = bounds?.width ?? (typeof window === 'undefined' ? 0 : window.innerWidth);
        const viewportHeight = bounds?.height ?? (typeof window === 'undefined' ? 0 : window.innerHeight);
        const maxX = (viewportWidth * (scale - 1)) / 2;
        const maxY = (viewportHeight * (scale - 1)) / 2;

        return {
            x: clamp(x, -maxX, maxX),
            y: clamp(y, -maxY, maxY),
        };
    }

    function setChartScale(nextScale: number): void {
        const scale = clamp(nextScale, MIN_CHART_SCALE, MAX_CHART_SCALE);
        chartScale.value = scale;

        const pan = clampChartPan(chartPanX.value, chartPanY.value, scale);
        chartPanX.value = pan.x;
        chartPanY.value = pan.y;
    }

    function resetChartMagnification(): void {
        chartScale.value = MIN_CHART_SCALE;
        chartPanX.value = 0;
        chartPanY.value = 0;
        chartGesture = null;
        lastChartTapAt = 0;
    }

    function zoomChartIn(): void {
        setChartScale(chartScale.value + CHART_ZOOM_STEP);
    }

    function zoomChartOut(): void {
        setChartScale(chartScale.value - CHART_ZOOM_STEP);
    }

    function toggleChartMagnification(): void {
        if (chartScale.value > MIN_CHART_SCALE) {
            resetChartMagnification();
            return;
        }

        setChartScale(2);
    }

    function handleChartTouchStart(event: TouchEvent): void {
        if (!isZoomed.value) {
            return;
        }

        event.stopPropagation();
        event.preventDefault();

        const first = event.touches.item(0);
        const second = event.touches.item(1);

        if (first && second) {
            chartGesture = {
                mode: 'pinch',
                distance: Math.max(touchDistance(first, second), 1),
                scale: chartScale.value,
            };
            return;
        }

        if (!first) {
            chartGesture = null;
            return;
        }

        chartGesture = chartScale.value > MIN_CHART_SCALE
            ? { mode: 'pan', x: first.clientX, y: first.clientY, panX: chartPanX.value, panY: chartPanY.value }
            : { mode: 'tap', x: first.clientX, y: first.clientY };
    }

    function handleChartTouchMove(event: TouchEvent): void {
        if (!isZoomed.value || chartGesture === null) {
            return;
        }

        event.stopPropagation();
        event.preventDefault();

        const first = event.touches.item(0);
        const second = event.touches.item(1);

        if (chartGesture.mode === 'pinch' && first && second) {
            setChartScale(chartGesture.scale * (touchDistance(first, second) / chartGesture.distance));
            return;
        }

        if (chartGesture.mode === 'pan' && first) {
            const pan = clampChartPan(
                chartGesture.panX + first.clientX - chartGesture.x,
                chartGesture.panY + first.clientY - chartGesture.y,
            );
            chartPanX.value = pan.x;
            chartPanY.value = pan.y;
        }
    }

    function handleChartTouchEnd(event: TouchEvent): void {
        if (!isZoomed.value) {
            return;
        }

        event.stopPropagation();
        event.preventDefault();

        const remainingTouch = event.touches.item(0);
        if (remainingTouch && chartScale.value > MIN_CHART_SCALE) {
            chartGesture = {
                mode: 'pan',
                x: remainingTouch.clientX,
                y: remainingTouch.clientY,
                panX: chartPanX.value,
                panY: chartPanY.value,
            };
            return;
        }

        const changedTouch = event.changedTouches.item(0);
        if (chartGesture?.mode === 'tap' && changedTouch) {
            const movement = Math.hypot(changedTouch.clientX - chartGesture.x, changedTouch.clientY - chartGesture.y);
            const now = Date.now();
            if (movement < 12 && now - lastChartTapAt < 320) {
                toggleChartMagnification();
                lastChartTapAt = 0;
            } else if (movement < 12) {
                lastChartTapAt = now;
            }
        }

        chartGesture = null;
    }

    function resetChartGesture(): void {
        chartGesture = null;
    }

    function openZoom(): void {
        if (!mobile.value || isZoomed.value) {
            return;
        }

        isZoomed.value = true;
        resetChartMagnification();
        if (typeof document === 'undefined') {
            return;
        }

        previousDocumentOverflow = document.documentElement.style.overflow;
        document.documentElement.style.overflow = 'hidden';

        const surface = chartSurface.value;
        if (surface?.requestFullscreen && document.fullscreenElement === null) {
            void surface.requestFullscreen().catch(() => undefined);
        }
    }

    function closeZoom(): void {
        if (!mobile.value) {
            return;
        }

        if (typeof document !== 'undefined' && document.fullscreenElement === chartSurface.value) {
            void document.exitFullscreen().catch(() => undefined);
        }

        isZoomed.value = false;
        resetChartMagnification();
        restoreDocumentScroll();
    }

    function handleFullscreenChange(): void {
        if (typeof document !== 'undefined' && isZoomed.value && document.fullscreenElement !== chartSurface.value) {
            isZoomed.value = false;
            resetChartMagnification();
            restoreDocumentScroll();
        }
    }

    function handleEscape(event: KeyboardEvent): void {
        if (event.key === 'Escape' && isZoomed.value) {
            closeZoom();
        }
    }

    function stopZoomedTouchPropagation(event: TouchEvent): void {
        if (isZoomed.value) {
            event.stopPropagation();
        }
    }

    onMounted(() => {
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('keydown', handleEscape);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('fullscreenchange', handleFullscreenChange);
        document.removeEventListener('keydown', handleEscape);
        resetChartMagnification();
        restoreDocumentScroll();
    });

    return {
        chartSurface,
        isZoomed,
        chartScale,
        chartPanX,
        chartPanY,
        chartTransformStyle,
        minChartScale: MIN_CHART_SCALE,
        maxChartScale: MAX_CHART_SCALE,
        openZoom,
        closeZoom,
        zoomChartIn,
        zoomChartOut,
        resetChartMagnification,
        toggleChartMagnification,
        handleChartTouchStart,
        handleChartTouchMove,
        handleChartTouchEnd,
        resetChartGesture,
        stopZoomedTouchPropagation,
    };
}
