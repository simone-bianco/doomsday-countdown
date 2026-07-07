import type { KyInstance } from 'ky';

declare global {
    interface Window {
        ky: KyInstance;
    }
}

export {};
