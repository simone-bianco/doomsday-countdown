<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { AnimatePresence, motion } from 'motion-v';
import { Button, Card, Image, SkeletonLoader } from '@simone-bianco/vue-ui-components';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { currentLanguage, t } from '@/i18n';
import { useDoomsdayReducedMotion } from '@/animations/doomsdayMotion';
import type { LatestNewsItemData } from '@/types/generated';

const props = withDefaults(defineProps<{
    readonly items: readonly LatestNewsItemData[];
    readonly autoplayIntervalMs?: number;
}>(), {
    autoplayIntervalMs: 7000,
});

type SlideDirection = -1 | 1;

const carouselRoot = ref<HTMLElement | null>(null);
const currentIndex = ref(0);
const navigationDirection = ref<SlideDirection>(1);
const hoverPaused = ref(false);
const focusPaused = ref(false);
const documentHidden = ref(false);
const mounted = ref(false);
const reducedMotion = useDoomsdayReducedMotion();
let autoplayTimer: ReturnType<typeof setInterval> | null = null;

const slideViewportClass = 'relative min-h-[32rem] overflow-hidden sm:min-h-[35.5rem]';
const slideSurfaceClass = 'grid min-h-[32rem] grid-rows-[auto_auto] overflow-hidden rounded-b-xl outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-ui-primary sm:min-h-[35.5rem]';
const slideContentClass = 'grid grid-rows-[1.25rem_3.5rem_4.5rem_1.5rem] gap-3 p-5 sm:p-6';
const paginationClass = 'flex min-h-11 items-center justify-center gap-0.5 overflow-x-auto px-4 py-2';

const activeItem = computed(() => props.items[currentIndex.value] ?? null);
const hasMultipleItems = computed(() => props.items.length > 1);
const hasPendingSlide = computed(() => props.items.length > 0 && activeItem.value === null);
const autoplayEnabled = computed(() => (
    mounted.value
    && hasMultipleItems.value
    && !reducedMotion.value
    && !hoverPaused.value
    && !focusPaused.value
    && !documentHidden.value
));
const ariaLive = computed(() => autoplayEnabled.value ? 'off' : 'polite');
const activeExternalUrl = computed(() => activeItem.value ? externalSourceUrl(activeItem.value) : null);
const slideInitial = computed(() => reducedMotion.value
    ? { opacity: 0 }
    : { opacity: 0, x: navigationDirection.value * 56 });
const slideAnimate = computed(() => ({ opacity: 1, x: 0 }));
const slideExit = computed(() => reducedMotion.value
    ? { opacity: 0 }
    : { opacity: 0, x: navigationDirection.value * -56 });
const slideTransition = computed(() => reducedMotion.value
    ? { duration: 0.12 }
    : { duration: 0.32, ease: [0.22, 1, 0.36, 1] as const });

function clearAutoplay(): void {
    if (autoplayTimer === null) {
        return;
    }

    clearInterval(autoplayTimer);
    autoplayTimer = null;
}

function navigateTo(index: number, direction: SlideDirection): void {
    if (!hasMultipleItems.value || index === currentIndex.value) {
        return;
    }

    navigationDirection.value = direction;
    currentIndex.value = index;
}

function advance(step: SlideDirection): void {
    if (!hasMultipleItems.value) {
        return;
    }

    const nextIndex = (currentIndex.value + step + props.items.length) % props.items.length;
    navigateTo(nextIndex, step);
}

function restartAutoplay(): void {
    clearAutoplay();
    if (!autoplayEnabled.value) {
        return;
    }

    autoplayTimer = setInterval(() => advance(1), props.autoplayIntervalMs);
}

function previous(): void {
    advance(-1);
    restartAutoplay();
}

function next(): void {
    advance(1);
    restartAutoplay();
}

function goTo(index: number): void {
    if (!hasMultipleItems.value || index === currentIndex.value) {
        return;
    }

    const forwardDistance = (index - currentIndex.value + props.items.length) % props.items.length;
    const backwardDistance = (currentIndex.value - index + props.items.length) % props.items.length;
    navigateTo(index, forwardDistance <= backwardDistance ? 1 : -1);
    restartAutoplay();
}

function handleVisibilityChange(): void {
    documentHidden.value = document.hidden;
}

function handleFocusOut(event: FocusEvent): void {
    const nextTarget = event.relatedTarget;
    if (nextTarget instanceof Node && carouselRoot.value?.contains(nextTarget)) {
        return;
    }

    focusPaused.value = false;
}

function externalSourceUrl(item: LatestNewsItemData): string | null {
    if (!item.source_url) {
        return null;
    }

    try {
        const source = new URL(item.source_url);
        return source.protocol === 'https:' ? source.toString() : null;
    } catch {
        return null;
    }
}

function primaryLinkLabel(item: LatestNewsItemData, opensExternally: boolean): string {
    return `${item.title}. ${opensExternally ? t('openSource') : t('viewCountdown')}`;
}

function formatPublishedAt(value: string): string {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat(currentLanguage.value || 'en', { dateStyle: 'medium' }).format(date);
}

watch(autoplayEnabled, restartAutoplay);
watch(() => props.autoplayIntervalMs, restartAutoplay);
watch(() => props.items.map((item) => item.id), (ids, previousIds) => {
    const previousId = previousIds[currentIndex.value] ?? null;
    const retainedIndex = previousId === null ? -1 : ids.indexOf(previousId);
    navigationDirection.value = 1;
    currentIndex.value = retainedIndex >= 0 ? retainedIndex : 0;
    restartAutoplay();
});

onMounted(() => {
    mounted.value = true;
    handleVisibilityChange();
    document.addEventListener('visibilitychange', handleVisibilityChange);
    restartAutoplay();
});

onBeforeUnmount(() => {
    mounted.value = false;
    clearAutoplay();
    document.removeEventListener('visibilitychange', handleVisibilityChange);
});
</script>

<template>
    <Card :ui="{ root: 'doomsday-card min-w-0 overflow-hidden rounded-xl', header: 'flex min-h-[4.25rem] items-center border-b border-white/10 px-5 py-4', body: 'min-w-0 p-0' }">
        <template #header>
            <SkeletonLoader v-if="hasPendingSlide" variant="text" width="42%" height="1rem" :animated="!reducedMotion" aria-hidden="true" />
            <h2 v-else class="doomsday-display min-w-0 text-ui-primary">{{ t('latestNews') }}</h2>
        </template>

        <section
            ref="carouselRoot"
            class="min-w-0 outline-none"
            role="region"
            aria-roledescription="carousel"
            :aria-label="t('newsCarouselLabel')"
            tabindex="0"
            @mouseenter="hoverPaused = true"
            @mouseleave="hoverPaused = false"
            @focusin="focusPaused = true"
            @focusout="handleFocusOut"
            @keydown.left.prevent="previous"
            @keydown.right.prevent="next"
        >
            <p v-if="!items.length" class="min-h-32 px-5 py-8 text-sm leading-relaxed text-ui-muted-foreground">{{ t('latestNewsEmpty') }}</p>

            <div v-else-if="hasPendingSlide" aria-hidden="true">
                <div :class="slideViewportClass">
                    <div :class="slideSurfaceClass">
                        <div class="relative aspect-video overflow-hidden bg-black">
                            <SkeletonLoader variant="rect" width="100%" height="100%" :animated="!reducedMotion" class="absolute inset-0" />
                        </div>
                        <div :class="slideContentClass">
                            <SkeletonLoader variant="text" width="46%" height="0.75rem" :animated="!reducedMotion" />
                            <SkeletonLoader variant="text" :lines="2" height="1rem" :animated="!reducedMotion" />
                            <SkeletonLoader variant="text" :lines="3" height="0.8rem" :animated="!reducedMotion" />
                            <SkeletonLoader variant="text" width="38%" height="0.75rem" :animated="!reducedMotion" />
                        </div>
                    </div>
                </div>
                <div :class="paginationClass">
                    <SkeletonLoader v-for="item in items" :key="item.id" variant="circle" width="0.5rem" height="0.5rem" :animated="!reducedMotion" />
                </div>
            </div>

            <div v-else class="relative min-w-0" :aria-live="ariaLive">
                <div :class="slideViewportClass">
                    <AnimatePresence mode="wait" :initial="false">
                        <motion.article
                            v-if="activeItem"
                            :key="activeItem.id"
                            class="min-w-0"
                            role="group"
                            aria-roledescription="slide"
                            :aria-label="`${t('newsSlide')} ${currentIndex + 1} / ${items.length}`"
                            :initial="slideInitial"
                            :animate="slideAnimate"
                            :exit="slideExit"
                            :transition="slideTransition"
                        >
                            <component
                                :is="activeExternalUrl ? 'a' : Link"
                                :href="activeExternalUrl ?? activeItem.countdown_url"
                                :target="activeExternalUrl ? '_blank' : undefined"
                                :rel="activeExternalUrl ? 'noopener noreferrer' : undefined"
                                :aria-label="primaryLinkLabel(activeItem, Boolean(activeExternalUrl))"
                                :class="slideSurfaceClass"
                            >
                                <div class="relative aspect-video overflow-hidden bg-black">
                                    <Image
                                        :src="activeItem.image_url"
                                        :alt="activeItem.title"
                                        aspect-ratio="56.25%"
                                        loading-type="skeleton"
                                        rounded="none"
                                        :ui="{ root: 'absolute inset-0 h-full w-full rounded-none bg-black', image: 'h-full w-full object-cover object-center' }"
                                    />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10" />
                                </div>

                                <div :class="slideContentClass">
                                    <p class="flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground">
                                        <span v-if="activeItem.source_name" class="min-w-0 truncate text-ui-primary">{{ activeItem.source_name }}</span>
                                        <span v-if="activeItem.source_name" aria-hidden="true">•</span>
                                        <time class="shrink-0" :datetime="activeItem.published_at">{{ formatPublishedAt(activeItem.published_at) }}</time>
                                    </p>
                                    <h3 class="doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white">{{ activeItem.title }}</h3>
                                    <p class="line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground">{{ activeItem.excerpt }}</p>
                                    <p class="line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55">{{ activeItem.countdown_title }}</p>
                                </div>
                            </component>
                        </motion.article>
                    </AnimatePresence>
                </div>

                <div v-if="hasMultipleItems" class="pointer-events-none absolute inset-x-0 top-0 z-20 aspect-video" aria-hidden="false">
                    <Button
                        variant="secondary"
                        size="sm"
                        :icon="ChevronLeft"
                        :aria-label="t('previousNews')"
                        :ui="{ root: 'pointer-events-auto absolute left-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black' }"
                        @click="previous"
                    />
                    <Button
                        variant="secondary"
                        size="sm"
                        :icon="ChevronRight"
                        :aria-label="t('nextNews')"
                        :ui="{ root: 'pointer-events-auto absolute right-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black' }"
                        @click="next"
                    />
                </div>

                <div :class="paginationClass" role="group" :aria-label="t('newsCarouselLabel')">
                    <template v-if="hasMultipleItems">
                        <Button
                            v-for="(item, index) in items"
                            :key="item.id"
                            variant="secondary"
                            size="sm"
                            :aria-label="`${t('newsSlide')} ${index + 1}`"
                            :aria-current="index === currentIndex ? 'true' : undefined"
                            :ui="{ root: index === currentIndex ? 'h-6 min-h-0 w-6 min-w-0 rounded-full border-ui-primary/60 bg-ui-primary/10 p-0 focus-visible:ring-2 focus-visible:ring-ui-primary' : 'h-6 min-h-0 w-6 min-w-0 rounded-full border-transparent bg-transparent p-0 hover:bg-white/5 focus-visible:ring-2 focus-visible:ring-ui-primary' }"
                            @click="goTo(index)"
                        >
                            <span :class="['block h-2 w-2 rounded-full transition-colors', index === currentIndex ? 'bg-ui-primary' : 'bg-white/30']" aria-hidden="true" />
                        </Button>
                    </template>
                </div>
            </div>
        </section>
    </Card>
</template>
