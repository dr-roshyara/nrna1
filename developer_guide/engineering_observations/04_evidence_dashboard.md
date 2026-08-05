# 04 — Evidence Dashboard

## Purpose
One regenerable page answering "what does the evidence show so far?" —
observation-stream summaries plus the eight OUTCOME questions rendered as
honest empty cells until real work fills them. Visible gaps drive collection.

## Where it fits
A PROJECTION (derived, non-authoritative, regenerate at will — corrected-I-4
discipline). Measured subject: engineering behavior and outcomes — never
platform activity (the protected sentence, enforced by content).

## Key files
- `scripts/observations/EvidenceDashboardRenderer.php` — pure `render(array): string`
- `scripts/observations/dashboard-renderer.php` — runner (gathers streams → writes `engineering/verification/observations/DASHBOARD.md`)
- `tests/Unit/EvidenceDashboardRendererTest.php` — 9 tests / 32 assertions (TDD-first: RED shown; grown with each dashboard extension)

## Design decisions
- **Honest empty cells:** every outcome question appears from day one with `NO DATA YET` + the source that fills it — the dashboard asks; it never judges (verdict-free pinned by test).
- Data sources read-only: metrics `trend.jsonl` · `test-presence.jsonl` · `lcom4.jsonl` · OE register heading count. `OBS_DIR` override for tests.
- The page is overwritten on each run — projections are rebuilt, never edited.
- **v2 extensions (ARB-commissioned, from EXISTING timestamps only):** lifecycle funnel (unique recommendations per stage) · stage lead times (mean hours, `n` always shown) · per-rule effectiveness table (decision + verdict columns) · **Evidence Velocity** — the operational KPI: completed cycles (recommendation→decision→outcome→assessment, id present in all four streams) per week. A window under 7 days **withholds the rate** rather than extrapolating it — measurements, never projections.
- **Loop Completion (WP-Next):** per-recommendation stage-gap buckets — needs decision / needs outcome / needs assessment / complete / closed-by-IGNORED / deferred. Two semantics matter: the **latest** decision classifies a re-decided recommendation, and **IGNORED is closure, not an outcome gap** (its lifecycle legitimately ends at the decision; `needs_outcome` counts ACCEPTED decisions only). Pinned by test. The process itself is documented in `docs/knowledgeos/KnowledgeOS_Engineering_Improvement_Cycle.md` (a published domain process — not a BC).

## How it works / use
```
php scripts/observations/dashboard-renderer.php
```
Run after any collector run or OE entry. The outcome table changes only when
its sources (workflow log · usage log · Run-2 tracking) gain entries.

## Testing
`php vendor/phpunit/phpunit/phpunit tests/Unit/EvidenceDashboardRendererTest.php`

## Pitfalls
- An impressive streams section with an all-empty outcomes table is the CORRECT current state — resist filling cells with anything but evidence.
- Don't hand-edit DASHBOARD.md; it's regenerated.

## Traceability
Built 2026-08-04 on the phase-boundary review ("the one thing I would still
deliberately build"); Architecture Discovery Freeze v1.0 adopted same turn.
