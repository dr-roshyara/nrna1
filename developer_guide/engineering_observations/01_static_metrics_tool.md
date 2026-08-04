# 01 — Static Metrics Tool (`composer metrics:collect`)

## Purpose
Give developers advisory coupling/complexity signals with trend history:
top-N risk per stereotype, band labels, hotspots. Never fails a build.

## Where it fits
Engineering tooling (ADVISORY tier) — beside, never inside, the `merge-gate`
(ARB R3 stable interface) and `quality-gate`. Runtime ~7.5 min → on-demand.

## Key files
- `scripts/metrics/metrics-report.php` — the runner (v3)
- `scripts/metrics/metrics-config.yaml` — weights · per-stereotype bands · watchlist floors · hotspot policy
- `engineering/verification/metrics/trend.jsonl` — append-only snapshots (evidence; never edit)
- `tests/Unit/MetricsReportTest.php` — characterization tests (4 tests / 24 assertions)
- `composer.json` → `metrics:collect` script + description

## Design decisions
- **Advisory only** — the output line "never fails the build" is TEST-ENFORCED.
- **Risk over raw CBO:** `0.4·CBO + 0.4·WMC (normalized within stereotype) + 0.2·instability` — a Provider's CBO is its job; classes compete only within their stereotype.
- **Feature-frozen** (spike plan REV 5): bug fixes + evidence only. The tool's destiny is the PHP *adapter* of a future Python platform — it never becomes the platform (plan REV 5, ADR Repository Separation REV 3).
- `METRICS_TREND_DIR` env override exists ONLY so tests never pollute the real trend file.

## How it works
```
composer metrics:collect
# = pdepend --summary-xml=build/metrics/pdepend-summary.xml app
#   + php scripts/metrics/metrics-report.php build/metrics/pdepend-summary.xml 10
```
The runner parses pdepend's class attrs (`cbo·ca·ce·wmc·dit`), groups by
stereotype (domain/model/controller/other/provider from namespace patterns),
computes risk, labels bands from the YAML, prints top-N, computes hotspots
(watchlist ∩ git change frequency over 300 commits), prints deltas vs the
previous snapshot, appends one JSONL snapshot.

## How to use / extend
Run it before/after refactoring a hot area and compare the `Δ` lines.
Tune thresholds in `metrics-config.yaml` (config change, no code).
⛔ Do NOT add metrics here — new metrics are new COLLECTORS (see guide 03's
birth convention); the growth budget of this tool is zero, permanently.

## Testing
`php vendor/phpunit/phpunit/phpunit tests/Unit/MetricsReportTest.php`
(uses a fixture XML; writes trend to a temp dir via `METRICS_TREND_DIR`).

## Pitfalls
- Providers top raw-CBO lists by design — read them under their own bands.
- One snapshot is not a trend; `Δ` output needs ≥2 snapshots.
- pdepend has no LCOM/RFC in class attrs (recorded limitation — LCOM4 is a separate collector).
- Eloquent statics/magic inflate some metrics — observations, never verdicts.

## Traceability
Spike plan `docs/plans/20260803-2130-static-engineering-metrics-spike-plan.md`
(REV 1–7) · trend evidence at commit `c3409d69f` onward · usage-phase
observation log lives in the plan.
