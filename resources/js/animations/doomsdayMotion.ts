import { useReducedMotion } from 'motion-v';
import type { Ref } from 'vue';

type DoomsdayEase = readonly [number, number, number, number] | 'linear';
type DoomsdayMotionValue = number | string;

export interface DoomsdayMotionTarget {
    readonly [key: string]: DoomsdayMotionValue;
}

export interface DoomsdayMotionTransition {
    readonly duration: number;
    readonly ease?: DoomsdayEase;
    readonly delay?: number;
    readonly type?: 'spring';
    readonly stiffness?: number;
    readonly damping?: number;
    readonly mass?: number;
}

export interface DoomsdayMotionPreset {
    readonly initial: DoomsdayMotionTarget;
    readonly animate: DoomsdayMotionTarget;
    readonly exit?: DoomsdayMotionTarget;
    readonly transition: DoomsdayMotionTransition;
}

export const doomsdayEase = [0.22, 1, 0.36, 1] as const;
export const doomsdayEaseSoft = [0.16, 1, 0.3, 1] as const;

export const fastTransition = {
    duration: 0.18,
    ease: doomsdayEase,
} satisfies DoomsdayMotionTransition;

export const panelTransition = {
    duration: 0.28,
    ease: doomsdayEaseSoft,
} satisfies DoomsdayMotionTransition;

export const staggerTransition = {
    duration: 0.24,
    ease: doomsdayEase,
} satisfies DoomsdayMotionTransition;

export const fadeIn = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition: fastTransition,
} satisfies DoomsdayMotionPreset;

export const fadeUp = {
    initial: { opacity: 0, y: 14 },
    animate: { opacity: 1, y: 0 },
    exit: { opacity: 0, y: -8 },
    transition: panelTransition,
} satisfies DoomsdayMotionPreset;

export const panelReveal = {
    initial: { opacity: 0, y: 12 },
    animate: { opacity: 1, y: 0 },
    exit: { opacity: 0, y: 8 },
    transition: panelTransition,
} satisfies DoomsdayMotionPreset;

export const cardReveal = {
    initial: { opacity: 0, y: 10, scale: 0.996 },
    animate: { opacity: 1, y: 0, scale: 1 },
    exit: { opacity: 0, y: 6, scale: 0.998 },
    transition: staggerTransition,
} satisfies DoomsdayMotionPreset;

export const tabContent = {
    initial: { opacity: 0, y: 8 },
    animate: { opacity: 1, y: 0 },
    exit: { opacity: 0, y: -6 },
    transition: fastTransition,
} satisfies DoomsdayMotionPreset;

export const skeletonFade = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition: { duration: 0.14, ease: doomsdayEase },
} satisfies DoomsdayMotionPreset;

export const selectedAccentPulse = {
    initial: { opacity: 0.55, scaleX: 0.72 },
    animate: { opacity: 1, scaleX: 1 },
    transition: { duration: 0.22, ease: doomsdayEaseSoft },
} satisfies DoomsdayMotionPreset;

export const ctaPress = {
    y: 1,
    scale: 0.992,
} satisfies DoomsdayMotionTarget;

export const ctaHover = {
    y: -1,
    scale: 1.006,
} satisfies DoomsdayMotionTarget;

export const cardHover = {
    y: -2,
    scale: 1.004,
} satisfies DoomsdayMotionTarget;

export const cardPress = {
    y: 0,
    scale: 0.998,
} satisfies DoomsdayMotionTarget;

export const selectedCardActive = {
    opacity: 1,
    scale: 1.003,
} satisfies DoomsdayMotionTarget;

export const selectedCardIdle = {
    opacity: 1,
    scale: 1,
} satisfies DoomsdayMotionTarget;

export const reducedMotionFallback = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition: { duration: 0.12, ease: 'linear' },
} satisfies DoomsdayMotionPreset;

export function useDoomsdayReducedMotion(): Ref<boolean> {
    return useReducedMotion();
}

export function resolveMotionPreset(
    preset: DoomsdayMotionPreset,
    prefersReducedMotion: boolean,
    fallback: DoomsdayMotionPreset = reducedMotionFallback,
): DoomsdayMotionPreset {
    return prefersReducedMotion ? fallback : preset;
}

export function withMotionDelay(preset: DoomsdayMotionPreset, delay: number): DoomsdayMotionPreset {
    return {
        ...preset,
        transition: {
            ...preset.transition,
            delay,
        },
    };
}

export function cardStaggerDelay(index: number): number {
    return Math.min(index * 0.045, 0.18);
}

export function disabledMotionTarget(target: DoomsdayMotionTarget, prefersReducedMotion: boolean): DoomsdayMotionTarget {
    return prefersReducedMotion ? selectedCardIdle : target;
}
