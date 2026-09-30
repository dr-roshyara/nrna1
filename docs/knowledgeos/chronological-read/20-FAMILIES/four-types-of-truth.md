# four-types-of-truth

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AuthorizedDecision(d)`; `Observed(x)`; `Recorded(x,t)`; `Supported(H|E)`
**Aliases:** "stop using 'truth' as one concept"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 200's proposal to replace a single notion of 'truth' with four distinct ones (observational, historical, epistemic, governance), grounding Provenance!=Truth and Auditability!=EpistemicCorrectness."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1418 §"Lineage proves: what the system recorded happened. It does not prove: that the recorded proposition was objectively true. ... Provenance \\neq Truth."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1418 §"Observational truth: Observed(x) ... Historical truth: Recorded(x,t) ... Epistemic assessment: Supported(H|E) ... Governance truth: AuthorizedDecision(d). These can differ."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1418. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a recency heuristic — both rows come from the same single source document (S1418, Step 200), so this reflects no later revisiting captured in this batch, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1418 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1418 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1418 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE); the row set is INVARIANT/DISTINCTION/DEFINITION/FORMALIZATION material. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1418] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "Test 13/17-18: historical information (lineage) can itself be wrong -- it proves what was recorded, not that the recorded proposition was objectively true; Provenance != Truth, and Auditability != EpistemicCorrectness, called one of the architecture's strongest results (a record can be perfectly auditable and still contain a false claim)." (anchor: "Lineage proves: what the system recorded happened. It does not prove: that the recorded proposition was objectively true. ... Provenance \\neq Truth.")
- [S1418] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Proposes stopping the use of 'truth' as a single architectural concept, replacing it with four distinct notions: Observational truth Observed(x), Historical truth Recorded(x,t), Epistemic assessment Supported(H|E), and Governance truth AuthorizedDecision(d); worked example -- an engineer's recorded 'deployment completed' can be historically True while infrastructure evidence shows DeploymentCompleted=False, which is not a system contradiction but precisely the intended distinction." (anchor: "Observational truth: Observed(x) ... Historical truth: Recorded(x,t) ... Epistemic assessment: Supported(H|E) ... Governance truth: AuthorizedDecision(d). These can differ.")

## Notes for P3

- This label is thin (2 rows, single source document) but the source itself calls its central finding (Provenance ≠ Truth / Auditability ≠ EpistemicCorrectness) "one of the architecture's strongest results" — worth flagging to P3 as a candidate for priority reconciliation against other truth/provenance-related labels in the wider corpus (e.g. any label built around `four-types-of-truth`'s sibling distinctions elsewhere), even though no group_id links it to anything in this batch.
- The four-way split (Observational/Historical/Epistemic/Governance truth) is a compact, self-contained formalization that reads as directly citable in later architecture work — P3 may want to check whether later steps (outside this batch) build on or reference this exact four-way taxonomy by name.
