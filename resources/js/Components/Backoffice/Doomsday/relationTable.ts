import { router } from '@inertiajs/vue3';
import type { DataTableSort, PaginationLink, PaginationMeta, ServerDataTableUI } from '@simone-bianco/vue-ui-components';
import type { BackofficeRelationCollection } from '@/Components/Backoffice/Doomsday/types';

function isPaginatedRelation<T>(collection: BackofficeRelationCollection<T>): collection is Exclude<BackofficeRelationCollection<T>, readonly T[]> {
    return typeof collection === 'object' && collection !== null && !Array.isArray(collection) && Array.isArray(collection.data);
}

export function relationData<T>(collection: BackofficeRelationCollection<T>): readonly T[] {
    return isPaginatedRelation(collection) ? collection.data : collection;
}

export function relationRows<T extends object>(collection: BackofficeRelationCollection<T>): Record<string, unknown>[] {
    return relationData(collection).map((item) => ({ ...item })) as Record<string, unknown>[];
}

export function relationMeta<T>(collection: BackofficeRelationCollection<T>): PaginationMeta {
    if (isPaginatedRelation(collection)) {
        return collection.meta;
    }

    const total = collection.length;

    return {
        current_page: 1,
        last_page: 1,
        per_page: total,
        total,
        from: total > 0 ? 1 : null,
        to: total > 0 ? total : null,
    };
}

export function relationLinks<T>(collection: BackofficeRelationCollection<T>): PaginationLink[] {
    return isPaginatedRelation(collection) ? [...(collection.links ?? [])] : [];
}

export function relationSearch<T>(collection: BackofficeRelationCollection<T>, searchParam: string): string {
    if (isPaginatedRelation(collection)) {
        const search = collection.filters?.search;

        if (typeof search === 'string') {
            return search;
        }
    }

    if (typeof window === 'undefined') {
        return '';
    }

    return new URL(window.location.href).searchParams.get(searchParam) ?? '';
}

export function relationSort<T>(collection: BackofficeRelationCollection<T>, sortParam: string, directionParam: string): DataTableSort {
    const defaultSort: DataTableSort = { key: 'id', direction: 'asc' };

    if (isPaginatedRelation(collection)) {
        const sort = collection.filters?.sort;
        const direction = collection.filters?.direction;

        if (typeof sort === 'string' && (direction === 'asc' || direction === 'desc')) {
            return { key: sort, direction };
        }
    }

    if (typeof window === 'undefined') {
        return defaultSort;
    }

    const params = new URL(window.location.href).searchParams;
    const sort = params.get(sortParam);
    const direction = params.get(directionParam);

    return sort !== null && (direction === 'asc' || direction === 'desc')
        ? { key: sort, direction }
        : defaultSort;
}

function queryObject(params: URLSearchParams): Record<string, string> {
    const query: Record<string, string> = {};

    for (const [key, value] of params.entries()) {
        query[key] = value;
    }

    return query;
}

function currentParams(tab: string): URLSearchParams {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    params.set('tab', tab);

    return params;
}

export function updateRelationSearch(searchParam: string, pageParam: string, tab: string, query: string): void {
    if (typeof window === 'undefined') {
        return;
    }

    const params = currentParams(tab);
    const normalizedQuery = query.trim();

    if (normalizedQuery === '') {
        params.delete(searchParam);
    } else {
        params.set(searchParam, normalizedQuery);
    }

    params.delete(pageParam);

    router.get(window.location.pathname, queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

export function updateRelationSort(sortParam: string, directionParam: string, pageParam: string, tab: string, sort: DataTableSort): void {
    if (typeof window === 'undefined') {
        return;
    }

    const params = currentParams(tab);
    params.set(sortParam, sort.key);
    params.set(directionParam, sort.direction);
    params.delete(pageParam);

    router.get(window.location.pathname, queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

export function urlWithCurrentBackofficeQuery(url: string, tab: string): string {
    if (typeof window === 'undefined') {
        return url;
    }

    const query = currentParams(tab).toString();

    if (query === '') {
        return url;
    }

    return `${url}${url.includes('?') ? '&' : '?'}${query}`;
}

export const flatRelationTableUi = {
    toolbar: 'rounded-none border-0 bg-transparent px-0 pt-0 pb-4',
    root: 'space-y-0 rounded-none border-0 bg-transparent',
    header: 'border-b border-ui-border bg-ui-muted/30',
    row: 'hover:bg-ui-primary/20 focus-within:bg-ui-primary/20',
    footer: 'border-t border-ui-border bg-transparent px-0 py-4',
} satisfies ServerDataTableUI;

export function formatBackofficeDateTime(value: string | null | undefined): string {
    if (!value) {
        return '';
    }

    return value.split('.')[0].slice(0, 19).replace('T', ' ');
}
