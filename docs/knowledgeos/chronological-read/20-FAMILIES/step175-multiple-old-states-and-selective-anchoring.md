# step175-multiple-old-states-and-selective-anchoring

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ObservationState/EvidenceState/KnowledgeState/PolicyState/DecisionState/AuthorizationState/ExecutionState`, `Preserve the information necessary to reconstruct the meaning of consequential transitions, not every byte ever produced`, `RetentionRequirement = f(Risk,Law,Governance,Traceability)` · **Aliases:** `selective retention principle`, `which old state matters`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 175 refines 'remember the old state' by noting there is no single old state -- there are separate ObservationState/EvidenceState/KnowledgeState/PolicyState/DecisionState/AuthorizationState/ExecutionState, and a given decision may need only some anchored (e.g. Decision1->Determination1->KnowledgeVersion_4 and Decision1->PolicyVersion_7, not a full system snapshot). States the resulting architectural principle: 'preserve the information necessary to reconstruct the meaning of consequential transitions', explicitly not 'preserve every byte ever produced', formalized as RetentionRequirement=f(Risk,Law,Governance,Traceability) across three graded retention levels (Level 1 current-state-only for low-risk transient info; Level 2 version history where evolution matters; Level 3 full provenance/decision lineage where consequential decisions must be reconstructed)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1368 §"ObservationState EvidenceState KnowledgeState PolicyState DecisionState AuthorizationState ExecutionState. A decision may require only some of these to be preserved. ... Preserve the information necessary to reconstruct the meaning of consequential transitions. Not: Preserve every byte ever produced…"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1368 §"ObservationState EvidenceState KnowledgeState PolicyState DecisionState AuthorizationState ExecutionState. A decision may require only some of these to be preserved. ... Preserve the information necessary to reconstruct the meaning of consequential transitions. Not: Preserve every byte ever produced…"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1368. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1368 |
| dependencies | PRESENT | S1368 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1368 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1368] types=[PRINCIPLE, CONCEPT] scope=THEORY-LEVEL — "Refines 'remember the old state' by distinguishing seven separate state dimensions (Observation/Evidence/Knowledge/Policy/Decision/Authorization/Execution State), of which a given decision may need only some anchored (e.g. KnowledgeVersion_4 and PolicyVersion_7, not a full system snapshot). States the resulting architectural principle: preserve the information necessary to reconstruct the meaning of consequential transitions, not every byte ever produced, formalized as RetentionRequirement=f(Risk,Law,Governance,Traceability) across three graded retention levels (current-state-only / version-history / full-provenance-lineage)." (anchor: "ObservationState EvidenceState KnowledgeState PolicyState DecisionState AuthorizationState ExecutionState. A decision may require only some of these to be preserved. ... Preserve the information neces…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
