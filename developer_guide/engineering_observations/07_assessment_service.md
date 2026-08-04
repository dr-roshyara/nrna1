# 07 — Assessment Service v1 (deterministic)

## Purpose
The interpretation stage: did the OUTCOME support the RECOMMENDATION?
Consumes raw outcome records; produces verdicts from a CLOSED set:
`SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE`.

## Where it fits
The stage after Outcome, before Learning: Observation → Recommendation →
Decision → Rationale → Outcome → **Assessment** → (Learning, future).
Deterministic thresholds only — Python arrives when hundreds of assessments
exist, not before. ⚠️ **Scope note:** this is recommendation-EFFECTIVENESS
assessment — distinct from the parked Engineering Assessment Commission
(code-quality assessment) and from ES-003/CAP-001 verdict sets: a scoped
vocabulary, not a third collision.

## Key files
- `scripts/observations/AssessmentService.php` — pure `evaluate(outcome)`; thresholds as named constants (≥20% improvement = SUPPORTED · ≥2% = PARTIAL · worsened = NOT · else/min-commits = INCONCLUSIVE)
- `scripts/observations/assess.php` — runner: `php scripts/observations/assess.php <REC-id>` → `assessments.jsonl`
- `tests/Unit/AssessmentServiceTest.php` — 6 tests / 13 assertions (TDD-first; closed verdict set pinned)

## Design decisions
- **INCONCLUSIVE exists because the first REAL record demanded it** — Election's Δ+0 after 1 commit fits neither supported nor unsupported; the evidence added the fourth verdict before the code was written.
- Min-commits guard (3): improvement measured before the subject could change is noise.
- Direction v1: lower-is-better metrics (LCOM4/CBO class); extend per metric on need.
- Every verdict carries a `basis` — assessments explain themselves.

## Pitfalls
- Never let assessment write back into outcomes (stages stay separate).
- Never tune thresholds from taste — tune from accumulated assessment-vs-reality comparisons.
- INCONCLUSIVE is a real verdict, not a failure — early assessments will mostly be it, correctly.

## Traceability
Authorized 2026-08-04 ("build Assessment next — deterministic, no AI") · first
real assessment same day: REC-eaf245474a · Election → INCONCLUSIVE (1 commit
< min 3) · the pipeline now executes end-to-end through interpretation.
