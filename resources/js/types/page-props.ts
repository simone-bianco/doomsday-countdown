import type { SeoPageData } from './generated';

export interface DoomsdayPageProps {
    readonly locale: string;
    readonly rendered_at: string;
    readonly seo: SeoPageData;
    readonly [key: string]: unknown;
}
