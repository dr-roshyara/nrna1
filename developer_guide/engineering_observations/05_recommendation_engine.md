# 05 — Recommendation Engine v1

## Purpose
Turn observations into ADVISORY recommendations and capture developer
decisions with reasons — the first intervention layer, and the data source
the future learning layer needs. **The developer decides, always.**

## Where it fits
Today's implementation of the Recommendation capability (business sense —
see the plan's term-collision note). Spike-level ownership: engineering
observation tooling. Contract chain: Observation → Recommendation →
Decision → Rationale → Outcome (five separate record types, never merged).

## Key files
- `scripts/observations/RecommendationEngine.php` — pure `evaluate(facts, rules)` (knows no tool, file, or threshold)
- `scripts/observations/recommendation-rules.yaml` — **rules are DATA** (R1 LCOM4>20 · R2 untested production change · R3 rising CBO · R4 persistent hotspot · R5 WARN+hotspot)
- `scripts/observations/recommendation-observer.php` — runner: streams → facts → evaluate → dedup → `recommendations.jsonl`
- `scripts/observations/recommendation-decide.php` — decision capture: `<REC-id> accepted|ignored|deferred <REASON_CODE> [comment]` → two records (decision + rationale) in `decisions.jsonl`
- `tests/Unit/RecommendationEngineTest.php` — 6 tests / 17 assertions (TDD-first; advisory-only pinned: no decision/applied/blocked keys possible)

## How to use
```
php scripts/observations/recommendation-observer.php     # issue new recommendations
php scripts/observations/recommendation-decide.php REC-eaf245474a accepted ALREADY_PLANNED "next sprint"
php scripts/observations/dashboard-renderer.php          # counters update
```
Reason codes: `ALREADY_PLANNED · IMMEDIATE_VALUE · DEADLINE_PRESSURE · FALSE_POSITIVE · DUPLICATE · WAITING_DEPENDENCY · OTHER(comment required)`.

## Design decisions
- Stable IDs = sha1(rule|subject) → dedup: one open recommendation per (rule, subject).
- Decision ≠ Rationale: two records (the response vs the why) — reasons are the future learning layer's most valuable data.
- R3/R4 no-op gracefully until enough snapshot history exists (honest silence).

## Known v1 approximations (deliberate — decisions refine rules, not pre-tuning)
- **R5 uses a flat CBO≥25 bar, ignoring stereotype bands → ServiceProviders get flagged.** Marking those `IGNORED: FALSE_POSITIVE` is the designed feedback, not a workaround — rule refinement follows the evidence.
- R4 needs two v3+ snapshots (v2 snapshots carry no hotspots key).

## Testing
`php vendor/phpunit/phpunit/phpunit tests/Unit/RecommendationEngineTest.php`

## Pitfalls
- Never add auto-apply. Never let a rule block anything. Advisory is load-bearing.
- Don't tune thresholds from taste — tune from decision+rationale data.
- Issuance counts are vanity; the dashboard prints acceptance for a reason.

## Traceability
Plan `docs/plans/20260804-1200-recommendation-engine-v1-spike-plan.md` (EP-01A
amended, approved 2026-08-04) · first run: 10 issued (R1: Election LCOM4=29 ·
R5: 9 hotspots incl. 2 known provider false-positive candidates) · feeds the
Assessment Commission trigger and the dashboard outcome cells.
