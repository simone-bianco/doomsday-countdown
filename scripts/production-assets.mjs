import { existsSync, rmSync, statSync } from 'node:fs';
import { resolve } from 'node:path';
import process from 'node:process';

const root = process.cwd();
const hotFile = resolve(root, 'public/hot');
const requiredArtifacts = [
    resolve(root, 'public/build/manifest.json'),
    resolve(root, 'bootstrap/ssr/ssr-manifest.json'),
    resolve(root, 'bootstrap/ssr/ssr.js'),
];

const mode = process.argv[2];

if (mode === 'prepare') {
    rmSync(hotFile, { force: true });
    console.log('Removed public/hot before the production build.');
    process.exit(0);
}

if (mode === 'verify') {
    const failures = [];

    if (existsSync(hotFile)) {
        failures.push('public/hot must not exist in a production release');
    }

    for (const artifact of requiredArtifacts) {
        if (!existsSync(artifact) || statSync(artifact).size === 0) {
            failures.push(`missing or empty production artifact: ${artifact}`);
        }
    }

    if (failures.length > 0) {
        for (const failure of failures) {
            console.error(failure);
        }
        process.exit(1);
    }

    console.log('Production frontend artifacts verified; no Vite hot file is present.');
    process.exit(0);
}

console.error('Usage: node scripts/production-assets.mjs <prepare|verify>');
process.exit(2);
