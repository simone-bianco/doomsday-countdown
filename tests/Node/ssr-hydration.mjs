import assert from 'node:assert/strict';
import { countdownParts, parseRenderedAt } from '../../resources/js/Components/Doomsday/countdownClock.js';

const renderedAt = '2026-07-12T12:00:00+00:00';
const renderedAtMs = parseRenderedAt(renderedAt);
const target = new Date(renderedAtMs + 31_536_000_000 + 86_400_000 + 3_600_000 + 60_000 + 1_000).toISOString();
const serverParts = countdownParts(target, renderedAtMs, false);
const firstClientParts = countdownParts(target, parseRenderedAt(renderedAt), false);

assert.deepEqual(firstClientParts, serverParts);
assert.deepEqual(serverParts.map(({ value }) => value), [1, 1, 1, 1, 1]);
assert.equal(countdownParts(null, renderedAtMs, true).every(({ value }) => value === 0), true);
assert.throws(() => parseRenderedAt('not-a-timestamp'), /Invalid rendered_at/);

console.log('SSR hydration clock contract passed');
