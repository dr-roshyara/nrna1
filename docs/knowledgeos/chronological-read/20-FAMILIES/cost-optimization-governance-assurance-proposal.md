# cost-optimization-governance-assurance-proposal

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EKS-06`, `X-1..X-8`, `how_to_optimize_cost.md` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0004, scope OBJECT): The brainstorming proposal to automate governance-assurance work and its review (S0145), splitting deterministic assurance (startable now) from assurance-class routing (frozen under the 2026-08-01 methodology freeze).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0145 §"a finding is M (mechanical) if a deterministic checker over the artifact could have produced it with no architectural judgement... 29 % → 46 % → 58 %"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0145. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0145) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0145 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0145 |
| dependencies | PRESENT | S0145 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0145 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0145 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Classifying every new finding across the AMD3/AMD4/AMD5 independent reviews as mechanical (M) or judgement (J) shows the mechanical share rose from 29% (4/14) to 46% (6/13) to 58% (7/12) as the migration-plan design stabilised — a measured trend across three independent reviewers, none of whom was looking for it, showing an increasing share of scarce independent-architecture review capacity was spent on machine-catchable defects. [S0145] Reframes the cost model: a mechanical defect caught late costs a full amendment cycle plus a full re-review, not merely reviewer minutes; measures the concrete chain volume (4,315 lines of governed artifact, 5 amendment commits, 4 independent reviews, 12 registered commission corrections) for a migration that had, at that point, not executed a single line of implementation. [S0145]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0145]` types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=METHODOLOGICAL — "Classifying every new finding across the AMD3/AMD4/AMD5 independent reviews as mechanical (M) or judgement (J) shows the mechanical share rose from 29% (4/14) to 46% (6/13) to 58% (7/12) as the migration-plan design stabilised — a measured trend across three independent reviewers, none of whom was looking for it, showing an increasing share of scarce independent-architecture review capacity was spent on machine-catchable defects." (anchor: "a finding is M (mechanical) if a deterministic checker over the artifact could have produced it with no architectural judgement... 29 % → 46 % → 58 %")
- `[S0145]` types=[ANALYSIS] scope=OBJECT — "Reframes the cost model: a mechanical defect caught late costs a full amendment cycle plus a full re-review, not merely reviewer minutes; measures the concrete chain volume (4,315 lines of governed artifact, 5 amendment commits, 4 independent reviews, 12 registered commission corrections) for a migration that had, at that point, not executed a single line of implementation." (anchor: "The cost is not reviewer minutes — it is AMENDMENT CYCLES ... a mechanical defect found by an independent reviewer does not cost a review. It costs a whole amendment plus a whole re-review.")
- `[S0145]` types=[PRINCIPLE, HYPOTHESIS] scope=THEORY-LEVEL — "Proposes a candidate invariant governing all future assurance automation: automation may reduce the cost of assurance work but must never manufacture authority, stated as the machine-facing case of the estate's existing G-2/R5b rule ('a grant registers a human act by reference — the record never manufactures authority'); explicitly marked a candidate from one track's evidence, not yet promoted." (anchor: "Automation may reduce the COST of assurance and must never manufacture AUTHORITY.")
- `[S0145]` types=[CORRECTION, DISTINCTION] scope=OBJECT — "Corrects the proposal's over-claim about the automatability of a four-layer trace: VERIFYING that a declared trace's identifiers all resolve is deterministic and would have caught prior dangling-reference defects, but DERIVING the act list in the first place (noticing that an act was never declared at all) is judgement and is not touched by any checker; the biggest correction the review makes is refusing to conflate the two." (anchor: "X-5 ... a checker reduces dangling-reference defects to zero and reduces missing-act defects (RD-1, RD-7, RD-3·b) by NOTHING. Only a human notices an act the author never declared.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
