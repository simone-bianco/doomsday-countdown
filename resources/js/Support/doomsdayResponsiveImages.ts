export type ResponsiveImageAsset = {
    readonly width: number;
    readonly height: number;
    readonly widths: readonly number[];
};

export type ResolvedResponsiveImage = ResponsiveImageAsset & {
    readonly originalSrc: string;
    readonly avifSrcset: string;
    readonly webpSrcset: string;
};

export const doomsdayResponsiveImages = {
    doomsday_hero_background_desktop: { width: 1536, height: 1024, widths: [640, 960, 1280, 1536] },
    doomsday_hero_background_mobile: { width: 1440, height: 2560, widths: [480, 720, 1080, 1440] },
    ai_job_apocalypse: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
    antibiotic_apocalypse: { width: 1535, height: 1024, widths: [480, 768, 1200, 1535] },
    europe_war_countdown: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
    extreme_heat_breakpoint_separate: { width: 1671, height: 941, widths: [480, 768, 1200, 1671] },
    taiwan_invasion: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
    uninhabitable_earth_separate: { width: 1536, height: 1024, widths: [480, 768, 1200, 1536] },
    society_collapse_separate: { width: 536, height: 473, widths: [320, 536] },
} as const satisfies Record<string, ResponsiveImageAsset>;

const doomsdayPngPattern = /^(.*\/images\/doomsday\/)([^/?#]+)\.png(?:[?#].*)?$/;

function srcset(base: string, name: string, widths: readonly number[], format: 'avif' | 'webp'): string {
    return widths.map((width) => `${base}responsive/${name}-${width}.${format} ${width}w`).join(', ');
}

export function resolveDoomsdayResponsiveImage(src: string): ResolvedResponsiveImage | null {
    const match = src.match(doomsdayPngPattern);
    if (!match) {
        return null;
    }

    const [, base, name] = match;
    const asset = doomsdayResponsiveImages[name as keyof typeof doomsdayResponsiveImages];
    if (!asset) {
        return null;
    }

    return {
        ...asset,
        originalSrc: src,
        avifSrcset: srcset(base, name, asset.widths, 'avif'),
        webpSrcset: srcset(base, name, asset.widths, 'webp'),
    };
}
