const SECONDS_PER_YEAR = 31_536_000;
const SECONDS_PER_DAY = 86_400;
const SECONDS_PER_HOUR = 3_600;
const SECONDS_PER_MINUTE = 60;

export function parseRenderedAt(renderedAt) {
    const timestamp = Date.parse(renderedAt);

    if (!Number.isFinite(timestamp)) {
        throw new Error(`Invalid rendered_at page prop: ${String(renderedAt)}`);
    }

    return timestamp;
}

export function countdownParts(targetDate, now, compact = false) {
    const target = targetDate === null ? now : new Date(targetDate).getTime();
    const totalSeconds = Math.max(0, Math.floor((target - now) / 1000));
    const years = Math.floor(totalSeconds / SECONDS_PER_YEAR);
    const days = Math.floor((totalSeconds % SECONDS_PER_YEAR) / SECONDS_PER_DAY);
    const hours = Math.floor((totalSeconds % SECONDS_PER_DAY) / SECONDS_PER_HOUR);
    const minutes = Math.floor((totalSeconds % SECONDS_PER_HOUR) / SECONDS_PER_MINUTE);
    const seconds = totalSeconds % SECONDS_PER_MINUTE;

    return compact
        ? [
            { label: 'YRS', value: years },
            { label: 'DAYS', value: days },
            { label: 'HRS', value: hours },
            { label: 'MIN', value: minutes },
            { label: 'SEC', value: seconds },
        ]
        : [
            { label: 'YEARS', value: years },
            { label: 'DAYS', value: days },
            { label: 'HOURS', value: hours },
            { label: 'MIN', value: minutes },
            { label: 'SEC', value: seconds },
        ];
}
