import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { BackofficeCounts } from '@/Components/Backoffice/Layout/types';

type SharedPageProps = {
    readonly app?: {
        readonly backoffice_path?: string;
        readonly backoffice_counts?: Partial<BackofficeCounts>;
    };
};

export function useBackofficePath() {
    const page = usePage<SharedPageProps>();
    const backofficePath = computed(() => page.props.app?.backoffice_path ?? '');
    const normalizedBackofficePath = computed(() => backofficePath.value.replace(/\/+$/g, ''));
    const counts = computed<BackofficeCounts>(() => ({
        users: page.props.app?.backoffice_counts?.users ?? 0,
        apiKeys: page.props.app?.backoffice_counts?.apiKeys ?? 0,
        countdowns: page.props.app?.backoffice_counts?.countdowns ?? 0,
    }));

    return { backofficePath, normalizedBackofficePath, counts };
}
