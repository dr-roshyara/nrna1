# 02 — Test-Presence Collector

## Purpose
Observe whether production behavior changed without accompanying test changes,
over a git range. Emits the observation; never judges it.

## Where it fits
Engineering observation tooling. The observation is the input a future
governance rule (e.g. a configured `TDD_FIRST` policy) would consume —
**the collector itself is policy-free by design and by test.**

## Key files
- `scripts/observations/TestPresenceCollector.php` — pure `classify(array $changedFiles): array`
- `scripts/observations/test-presence-observer.php` — runner (git range → observation)
- `engineering/verification/observations/test-presence.jsonl` — append-only evidence
- `tests/Unit/TestPresenceCollectorTest.php` — 5 tests / 15 assertions (TDD-first: RED was shown before implementation)

## Design decisions
- **Verdict-free is test-enforced:** `test_observation_is_verdict_free` asserts no `violation`/`passed`/`blocked` keys can appear.
- Classification scope: `app/**.php` = production behavior (`.blade.php` excluded — templates aren't behavior); `tests/**.php` = tests; everything else out of scope.
- `OBS_DIR` env override so tests never pollute evidence (built in from birth).

## How it works
```
php scripts/observations/test-presence-observer.php HEAD~1..HEAD
```
`git diff --name-only <range>` → `classify()` → console observation
(⚠ production-without-tests, ✓ accompanied, · no production change) →
JSONL snapshot with commit hash.

## How to use / extend
Run per commit or per PR range. Extend classification rules only in the pure
class, with a test first. ⛔ Never add "then fail the build" — advisory→enforcing
is an evidence-gated governance promotion, not a code change.

## Testing
`php vendor/phpunit/phpunit/phpunit tests/Unit/TestPresenceCollectorTest.php`

## Pitfalls
- Windows: the runner's env override uses `putenv()` inheritance — inline `VAR=x cmd` does not exist in cmd.exe.
- A docs-only commit correctly reports "no production behavior changed" — that's an honest zero, not a bug.

## Traceability
Built 2026-08-04 (verification-institutionalization analysis, Layer 4/5 slice) ·
first observation appended same day · EV-1 candidate evidence (second artifact class).
