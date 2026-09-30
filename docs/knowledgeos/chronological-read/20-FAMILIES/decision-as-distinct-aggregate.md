# decision-as-distinct-aggregate

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Determination(P) != Decision(D)`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope OBJECT: "The finding that a governance Decision (e.g. UpgradeNexus) is a distinct object/aggregate from the Determination(P) it may reference, not a further state of P itself; part of the P -> Determination(P) -> Decision(D) reframing in Step 189."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1390 §"Determination(P)\\neq Decision(D)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1392. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a recency heuristic, not a confirmed retirement — the second source (S1392, Step 190) explicitly reconfirms the first (S1390, Step 189) rather than superseding it.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1390, S1392 (×2) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE); the row set is DISTINCTION/VALIDATION/PRINCIPLE material. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1390] types=[DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN — "A governance decision (e.g. the Architecture Board deciding 'UpgradeNexus') is not a state of the proposition P that was determined; it is a state/event of a separate decision object D that may merely reference P. Reframes the naive chain Supported->Determined->Decided as P -> Determination(P) -> Decision(D), and further as Evidence->EpistemicAssessment->Determination, and Determination+Governance->Decision." (anchor: "Determination(P)\\neq Decision(D).")
- [S1392] types=[DISTINCTION, VALIDATION] scope=OBJECT — "Reconfirms via Case B that Determination (e.g. 'Nexus is an approved software component') and Decision (e.g. 'Upgrade to version 3.70') are distinct objects related by, not identical to, a Determination->Decision relationship." (anchor: "Determination -> Decision is a relationship. They are not one object.")
- [S1392] types=[PRINCIPLE, VALIDATION] scope=THEORY-LEVEL — "Authority test: an AI recommendation, even with strong evidence and a calculated Risk=0.97, has Auth(AI,ArchitectureDecision)=0, so Recommendation_AI does not imply Decision; the board must explicitly cross the authority boundary -- validating that statistical/AI confidence cannot manufacture authority." (anchor: "Statistical or AI confidence cannot manufacture authority.")

## Notes for P3

- The founding row (S1390) is `label_confidence: UNCERTAIN` while the reconfirming row (S1392) is SURE — worth noting that the label's own second source explicitly validates ("Reconfirms via Case B") the first, which is a stronger-than-usual same-thread lineage even though no formal `lineage_claims` entry was captured for either row.
- This label's third row (the AI-authority-boundary test, "Statistical or AI confidence cannot manufacture authority") thematically overlaps with material captured elsewhere in this batch under `adversarial-epistemology-integrity-trust-manipulation-algebra` (the S0993/Step-94 "AI cannot manufacture authority" principle) — no group_id links them, but P3 may want to check whether this Step-190 statement is an earlier occurrence of the same architectural principle.
