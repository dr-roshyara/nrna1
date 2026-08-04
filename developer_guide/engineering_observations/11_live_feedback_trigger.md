# 11 — Live Feedback (ObservationTrigger + ChangeSet + Runtime)

## Purpose
Advisory engineering feedback within seconds of saving a file — no commit
required. Implemented as the commission specified: a new trigger adapter on
the existing pipeline; collectors, engine, dashboard all untouched.

## Where it fits
The ObservationTrigger capability (UL: the capability sits above any single
trigger; commit/file-save/PR/CI are adapters). Every adapter produces a
`ChangeSet`; the runtime consumes only `ChangeSet` and never knows how it
was triggered.

## Key files
- `scripts/observations/ChangeSet.php` — technology-neutral change object (NOT GitCommit, NOT VSCodeFile)
- `scripts/observations/ObservationRuntime.php` — `run(ChangeSet, rules)` → observations + recommendations; changed-files-only; reuses `Lcom4Collector`/`TestPresenceCollector`/`RecommendationEngine` unchanged
- `scripts/observations/FileSaveTrigger.php` — pure mtime-snapshot change detection; delegates, no logic
- `scripts/observations/watch.php` — poller runner (2s), prints advisories; `--once` for a single cycle
- `.vscode/tasks.json` — "KnowledgeOS: watch" task, installed by environment-aware `init`
- Tests: `ChangeSetTest` · `ObservationRuntimeTest` · `FileSaveTriggerTest` (9 tests / 14 assertions, RED shown)

## Design decisions
- **Trigger-independence pinned by test:** identical ChangeSet + identical sources → identical recommendations (minus timestamps). FileSave and Commit cannot disagree about the same code.
- **Publication belongs to the trigger, not the runtime:** live results are EPHEMERAL — displayed in the terminal, never appended to the evidence streams. A save is not yet an engineering event worth recording; the commit trigger remains the stream writer. This keeps stream hygiene while satisfying "identical recommendations".
- **Changed files only, by construction:** the runtime reads only `ChangeSet.phpClassFiles()` via an injectable reader (pinned by test — the reader records what was requested).
- **Polling, not a daemon framework:** a 2s `sleep` loop in a foreground CLI. No queues, no bus, no async infrastructure (commission constraint).
- Environment-adaptive install: `init` plans the VS Code task only when `.vscode/` exists — adapters are never forced onto foreign environments.

## How it works / use
```
php scripts/observations/dev.php            # THE dev session: verify → prove chain → own watch loop
php scripts/observations/watch.php          # watch loop only (Ctrl+C to stop)
php scripts/observations/watch.php --once   # single poll cycle
php scripts/observations/observe.php --json <file>   # the JSON API (IDE adapters call this)
```
`dev.php` owns the session (review 2026-08-04: the developer never asks "did I
start the watcher?"): doctor first (refuses an unready environment), then a
chain self-test with per-stage traces (the first failing stage IS the defect),
then the watch loop. Every live event also emits a one-line chain trace:
`trace: event → changeset → runtime → collectors → recommendations → presented`.
Or in VS Code: Run Task → "KnowledgeOS: watch (live advisory feedback)".
Verified live on real code: saving `app/Models/Election.php` produces the R1
cohesion advisory (LCOM4 29) and R2 missing-tests advisory in under a second.

## Testing
`php vendor/bin/phpunit tests/Unit/ChangeSetTest.php tests/Unit/ObservationRuntimeTest.php tests/Unit/FileSaveTriggerTest.php`

## Pitfalls
- Don't make the watcher write to the streams — recording every save would flood the funnel with non-events and destroy the decision metrics.
- Don't add recommendation logic to any trigger; triggers detect and delegate.
- The watcher covers `app/**/*.php` only — extending scope is a config question, not a code fork.

## Traceability
Chief-Architect commission 2026-08-04 (ObservationTrigger extension) · register Transition log entry #2 (authority re-ruling, not criterion fire) · PR/CI adapters remain staged · UL correction: capability = ObservationTrigger.
