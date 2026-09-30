# e20-calibration-reclassification-not-applicable

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "BLOCKED->NOT APPLICABLE"; "E20"; "T-3"
**Aliases:** "E20 reclassification"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0046, scope OBJECT: "Proposed amendment (not yet applied to the immutable Step 280 record) reclassifying test E20 (statistical calibration) from BLOCKED (theory defect, no probability space) to NOT APPLICABLE at the core theory level once Step 282's T-3 ruling holds probability is not required, with a DEFERRED status at the Assessment layer pending any future numeric semantics; argues a test blocked for want of a construct the theory has since declared unnecessary is a stale test, not evidence of incompleteness. Net effect: blocked-count 1->0, unobserved-count unchanged at 15, EC verdict unchanged (NOT ACHIEVED)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1889 §"Then E20 is not BLOCKED. It is NOT APPLICABLE."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1910 §"This document does not modify `verification/step-280/`. That package is another session's executed report and is left intact; amending an executed record retroactively is the pattern this investigation has criticized."]

## Lifecycle
last_seen: S1910. Candidate lifecycle: DORMANT. Evidence: no retraction, no superseding row, no self-contradiction flag; DORMANT here is a heuristic based on how long ago (by source_id ordering) this label was last touched, not a confirmed abandonment of the proposed reclassification (which the rows themselves describe as not yet applied/ratified).

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1889, S1910 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1910, S1910, S1910 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The reclassification addresses a stale-test problem: test E20 (statistical calibration) had been recorded BLOCKED because the theory lacked a probability space, but once Step 282's ruling T-3 holds that probability is not required at the core theory level, nothing is any longer "owed" for E20 to check — blocking presupposes the missing construct is owed, so a test blocked for want of something the theory has since declared unnecessary is not evidence of incompleteness, it is simply stale [S1889, S1910]. The proposal is explicitly only a candidate amendment, not an in-place correction: it does not modify the immutable Step 280 executed record, and is offered as a separate, dated amendment for ratification — a governance discipline the same investigation had elsewhere criticized violations of [S1910]. It also draws an explicit boundary against over-reading T-3: T-3 removes the probability-space requirement but does not license arithmetic over the ordinal "strength" scale, so a narrower, sharper open gap (referred to as G-12, concerning 176 portfolio/threshold decision rules flipping under order-preserving re-encoding) survives at the Assessment layer even after the reclassification [S1910]. Net stated effect: blocked-count 1→0, unobserved-count unchanged at 15, overall EC verdict unchanged (NOT ACHIEVED) [source: node_metadata OBJECT-INDEX note].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1889] types=[ARGUMENT, EXTENSION] scope=OBJECT — "Draws the consequence Step 282 itself had not drawn: since Step 282 rules probability not required, the previously-recorded 'E20 BLOCKED (no probability space)' verdict is stale — a test blocked for want of a construct the theory has since declared unnecessary is evidence of a stale test, not incompleteness; recommends reclassifying E20 before the closure matrix is finalized." (anchor: "Then E20 is not BLOCKED. It is NOT APPLICABLE.")
- [S1910] types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — "States an explicit governance discipline: an executed prior record (Step 280's test results) must not be retroactively modified; a later correction is offered as a separate, dated amendment record for ratification, not applied silently in place — named as a pattern this investigation has previously criticized when found elsewhere." (anchor: "This document does not modify `verification/step-280/`. That package is another session's executed report and is left intact; amending an executed record retroactively is the pattern this investigation has criticized.")
- [S1910] types=[ARGUMENT, PRINCIPLE] scope=OBJECT — "Core argument for the reclassification: blocking presupposes the missing thing is 'owed'; once T-3 rules probability out of core scope, nothing is owed, so E20 is not blocked — it is not applicable at the core level, while remaining DEFERRED at the Assessment layer (applicable iff Assessment ever declares numeric semantics)." (anchor: "A test blocked for want of a construct the theory has since declared unnecessary is not evidence of incompleteness. It is a stale test.")
- [S1910] types=[DISTINCTION, CONSTRAINT] scope=OBJECT — "Explicit warning that T-3's closure must not be over-read: it removes the probability-space requirement but does not license arithmetic over the ordinal 'strength' scale; the surviving constraint (from an earlier document '07' §4) that 176 portfolio/threshold decision rules flip under order-preserving re-encoding remains a live, open gap (G-12) at the Assessment layer, sharper and narrower than the original E20 framing." (anchor: "T-3 removes the requirement for a probability space. It does not license arithmetic on an ordinal scale.")

## Notes for P3
Four rows across two source documents: S1889 (`.../step-272/10-GAP-UPDATE-STEPS-281-282.md`) and S1910, three rows (`.../step-272/11-E20-RECLASSIFICATION.md`). `family.files_touching` additionally lists `S1918` and `S2315`, neither of which has a row body captured under this label — flagged for a data-quality check by P3 (my own observation, not asserted as content). The label is explicitly self-aware about its own governance status: it is a proposed amendment, not yet applied to the immutable Step 280 record [S1910], so its DORMANT lifecycle-candidate reading should not be mistaken for the proposal having been rejected — the source material itself never states a ratification outcome one way or the other. The row-level scope for the second row (S1910, PRINCIPLE/GOVERNANCE) is METHODOLOGICAL while node_metadata.scopes for the whole label is OBJECT — the same kind of scope-granularity mismatch noted in `discrepancy-resolved-as-design-difference`, worth a general check by P3 across labels.
