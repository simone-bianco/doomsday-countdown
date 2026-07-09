export type BackofficeSection = 'dashboard' | 'countdowns' | 'users' | 'openai-keys';

export interface BackofficeCounts {
    readonly users: number;
    readonly apiKeys: number;
    readonly countdowns: number;
}
