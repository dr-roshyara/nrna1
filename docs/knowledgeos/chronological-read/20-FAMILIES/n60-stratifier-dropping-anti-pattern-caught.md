# n60-stratifier-dropping-anti-pattern-caught

**Scope(s):** METHODOLOGICAL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0067`, scope `METHODOLOGICAL`: A self-caught residual data-dependence violation in a proposed sparse-cell stratifier-dropping rule, replaced with stratifier immutability.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2811 §"cells with n<60 cause the stratifier to be dropped before the run ... but cell sizes are not known until data generation. ... The design would have depended on generated data even with no analysis run. ... All declared stratifiers remain fixed once pre-registered. No stratifier may be dropped after "]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2811. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2811] types=['CORRECTION'] scope=METHODOLOGICAL — "Catches a second residual data-dependence violation: a proposed rule to drop any stratifier whose cells fall below n=60 'before the run' is incoherent, since cell sizes are only knowable after data generation, meaning the design would still depend on generated data even though no analysis code had run; withdraws it and replaces it with an immutability rule -- all declared stratifiers remain fixed once pre-registered, no stratifier may be dropped after data generation, and sparse cells are simply reported as sparse rather than triggering redesign." (anchor: "cells with n<60 cause the stratifier to be dropped before the run ... but cell sizes are not known until data generation. ... The design would have depended on generated data even with no analysis run. ... All declared stratifiers remain fixed once pre-registe…")

## Notes for P3
- Single-source label (n=1 row) — evidence base is thin, no internal cross-corroboration within this capture.
