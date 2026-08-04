# 03 — LCOM4 Collector & Conformance Suite

## Purpose
Observe class cohesion (LCOM4, Hitz & Montazeri: connected components over the
method graph). LCOM4 looks INWARD (do my parts belong together?) where CBO
looks OUTWARD — together they triangulate suspects (e.g. `Election`: CBO 57 ·
WMC 257 · 30 changes · LCOM4 29).

## Where it fits
Second independent observation collector. Its arrival was deliberately the
architecture experiment: what reuses, what duplicates (result: observation
shape reused; runner boilerplate duplicated ×3 — recorded as extraction-trigger
evidence, plan REV 6).

## Key files
- `scripts/observations/Lcom4Collector.php` — pure `collect(string $phpSource): array` (php-parser reused, no custom parser)
- `scripts/observations/lcom4-observer.php` — runner (file/dir → observations)
- `scripts/observations/examples/lcom4/` — **the conformance contract**: 7 golden fixtures + `expected.json`
- `tests/Unit/Lcom4CollectorTest.php` — 8 tests / 24 assertions (TDD-first)
- `tests/Unit/Lcom4VerificationSuiteTest.php` — conformance + completeness (no fixture without expectation)

## Design decisions (pinned as DATA in `expected.json`, not folklore)
- constructors/destructors EXCLUDED (they touch everything, mask splits)
- statics INCLUDED as isolated nodes (inflates LCOM4 — pinned, revisitable)
- trait methods NOT resolved · inherited methods NOT included
- **"Validate the metric before validating the code":** real-class values carry these caveats — observations, never verdicts.

## The birth convention (binding for every future collector)
Every observation specification carries four artifacts from day one:
specification (`expected.json`) · golden fixtures · reference implementation ·
conformance test. The specification owns the truth; implementations prove
conformance — a future Python/Java collector must produce identical values on
identical fixtures.

## How it works
```
php scripts/observations/lcom4-observer.php app/Models/Election.php
php scripts/observations/lcom4-observer.php app/Contexts/Membership/Domain 300
```
Union-find over methods; edges = shared `$this->prop` or `$this->call()`.
Output: `{metric, class, value, interpretation}` per class → console top-10 +
JSONL snapshot (`OBS_DIR` override for tests).

## How to use / extend
To change a variant decision: change `expected.json` + fixtures FIRST (a
conscious, versioned spec change), then make the implementation conform.
Never the other way around.

## Testing
`php vendor/phpunit/phpunit/phpunit tests/Unit/Lcom4CollectorTest.php tests/Unit/Lcom4VerificationSuiteTest.php`

## Pitfalls
- High LCOM4 on Eloquent models partly reflects statics/magic (pinned decisions) — triangulate with CBO/WMC/churn before drawing conclusions.
- Anonymous classes report as `(anonymous)`.
- Don't grep pdepend output for "lcom" — you'll match `WeLCOMeDashboardController`.

## Traceability
Plan REV 6–7 · observations in `engineering/verification/observations/lcom4.jsonl` ·
spec-first pattern watch (n=1) · OBS-1 (Observation as first-class concept) pressure noted.
