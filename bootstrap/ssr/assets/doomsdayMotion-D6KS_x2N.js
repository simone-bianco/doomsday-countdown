import { useReducedMotion } from "motion-v";
const doomsdayEase = [0.22, 1, 0.36, 1];
const doomsdayEaseSoft = [0.16, 1, 0.3, 1];
const fastTransition = {
  duration: 0.18,
  ease: doomsdayEase
};
const panelTransition = {
  duration: 0.28,
  ease: doomsdayEaseSoft
};
const staggerTransition = {
  duration: 0.24,
  ease: doomsdayEase
};
const fadeIn = {
  initial: { opacity: 0 },
  animate: { opacity: 1 },
  exit: { opacity: 0 },
  transition: fastTransition
};
const fadeUp = {
  initial: { opacity: 0, y: 14 },
  animate: { opacity: 1, y: 0 },
  exit: { opacity: 0, y: -8 },
  transition: panelTransition
};
const panelReveal = {
  initial: { opacity: 0, y: 12 },
  animate: { opacity: 1, y: 0 },
  exit: { opacity: 0, y: 8 },
  transition: panelTransition
};
const cardReveal = {
  initial: { opacity: 0, y: 10, scale: 0.996 },
  animate: { opacity: 1, y: 0, scale: 1 },
  exit: { opacity: 0, y: 6, scale: 0.998 },
  transition: staggerTransition
};
const tabContent = {
  initial: { opacity: 0, y: 8 },
  animate: { opacity: 1, y: 0 },
  exit: { opacity: 0, y: -6 },
  transition: fastTransition
};
const selectedAccentPulse = {
  initial: { opacity: 0.55, scaleX: 0.72 },
  animate: { opacity: 1, scaleX: 1 },
  transition: { duration: 0.22, ease: doomsdayEaseSoft }
};
const cardHover = {
  y: -2,
  scale: 1.004
};
const cardPress = {
  y: 0,
  scale: 0.998
};
const selectedCardActive = {
  opacity: 1,
  scale: 1.003
};
const selectedCardIdle = {
  opacity: 1,
  scale: 1
};
const reducedMotionFallback = {
  initial: { opacity: 0 },
  animate: { opacity: 1 },
  exit: { opacity: 0 },
  transition: { duration: 0.12, ease: "linear" }
};
function useDoomsdayReducedMotion() {
  return useReducedMotion();
}
function resolveMotionPreset(preset, prefersReducedMotion, fallback = reducedMotionFallback) {
  return prefersReducedMotion ? fallback : preset;
}
function withMotionDelay(preset, delay) {
  return {
    ...preset,
    transition: {
      ...preset.transition,
      delay
    }
  };
}
function cardStaggerDelay(index) {
  return Math.min(index * 0.045, 0.18);
}
function disabledMotionTarget(target, prefersReducedMotion) {
  return prefersReducedMotion ? selectedCardIdle : target;
}
export {
  cardReveal as a,
  fadeIn as b,
  cardStaggerDelay as c,
  selectedCardIdle as d,
  disabledMotionTarget as e,
  fadeUp as f,
  selectedCardActive as g,
  cardHover as h,
  cardPress as i,
  fastTransition as j,
  panelReveal as p,
  resolveMotionPreset as r,
  selectedAccentPulse as s,
  tabContent as t,
  useDoomsdayReducedMotion as u,
  withMotionDelay as w
};
