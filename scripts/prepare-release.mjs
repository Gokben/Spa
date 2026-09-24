import { execFileSync } from 'node:child_process';
import { readFile, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import { nextReleaseVersion } from './release-version.mjs';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const releasePath = path.join(root, 'public', 'release.json');

let previous = {};
try {
    previous = JSON.parse(await readFile(releasePath, 'utf8'));
} catch {
    previous = {};
}

const commit = process.env.GITHUB_SHA?.slice(0, 8)
    || execFileSync('git', ['rev-parse', '--short=8', 'HEAD'], { cwd: root, encoding: 'utf8' }).trim();
const release = {
    commit,
    version: nextReleaseVersion(previous, commit),
    builtAt: new Date().toISOString(),
};

await writeFile(releasePath, `${JSON.stringify(release)}\n`, 'utf8');
console.log(`SPA sürümü hazırlandı: ${release.version} (${release.commit})`);
