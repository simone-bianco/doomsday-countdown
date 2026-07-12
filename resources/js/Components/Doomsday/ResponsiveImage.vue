<script setup lang="ts">
import { computed } from 'vue';
import { resolveDoomsdayResponsiveImage } from '@/Support/doomsdayResponsiveImages';

type ImageLoading = 'eager' | 'lazy';
type FetchPriority = 'high' | 'low' | 'auto';
type ImageDecoding = 'async' | 'sync' | 'auto';

const props = withDefaults(defineProps<{
    readonly src: string;
    readonly mobileSrc?: string | null;
    readonly alt: string;
    readonly sizes?: string;
    readonly mobileSizes?: string | null;
    readonly breakpoint?: number;
    readonly media?: string | null;
    readonly inactiveMedia?: string | null;
    readonly loading?: ImageLoading;
    readonly fetchPriority?: FetchPriority;
    readonly decoding?: ImageDecoding;
    readonly pictureClass?: string;
    readonly imgClass?: string;
    readonly width?: number | null;
    readonly height?: number | null;
    readonly ariaHidden?: boolean;
}>(), {
    mobileSrc: null,
    sizes: '100vw',
    mobileSizes: null,
    breakpoint: 768,
    media: null,
    inactiveMedia: null,
    loading: 'lazy',
    fetchPriority: 'auto',
    decoding: 'async',
    pictureClass: 'block',
    imgClass: '',
    width: null,
    height: null,
    ariaHidden: false,
});

const desktop = computed(() => resolveDoomsdayResponsiveImage(props.src));
const mobile = computed(() => props.mobileSrc ? resolveDoomsdayResponsiveImage(props.mobileSrc) : null);
const mobileMedia = computed(() => `(max-width: ${props.breakpoint}px)`);
const desktopMedia = computed(() => props.mobileSrc ? `(min-width: ${props.breakpoint + 1}px)` : (props.media ?? undefined));
const transparentPixel = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
const resolvedWidth = computed(() => props.width ?? desktop.value?.width);
const resolvedHeight = computed(() => props.height ?? desktop.value?.height);
const resolvedMobileSizes = computed(() => props.mobileSizes ?? props.sizes);
</script>

<template>
    <picture :class="pictureClass">
        <source v-if="inactiveMedia" :media="inactiveMedia" :srcset="transparentPixel" />

        <template v-if="mobile && mobileSrc">
            <source :media="mobileMedia" type="image/avif" :srcset="mobile.avifSrcset" :sizes="resolvedMobileSizes" />
            <source :media="mobileMedia" type="image/webp" :srcset="mobile.webpSrcset" :sizes="resolvedMobileSizes" />
            <source :media="mobileMedia" :srcset="mobileSrc" />
        </template>

        <source v-if="desktop" :media="desktopMedia" type="image/avif" :srcset="desktop.avifSrcset" :sizes="sizes" />
        <source v-if="desktop" :media="desktopMedia" type="image/webp" :srcset="desktop.webpSrcset" :sizes="sizes" />
        <source v-if="desktopMedia" :media="desktopMedia" :srcset="src" />

        <img
            :src="src"
            :alt="alt"
            :width="resolvedWidth"
            :height="resolvedHeight"
            :loading="loading"
            :fetchpriority="fetchPriority"
            :decoding="decoding"
            :aria-hidden="ariaHidden ? 'true' : undefined"
            :class="imgClass"
        />
    </picture>
</template>
