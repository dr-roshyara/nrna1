# conflict-taxonomy-vs-contradiction

**Scope(s):** THEORY-LEVEL · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `CONFLICT != CONTRADICTION`, `Logical Contradiction != Semantic Incompatibility != Temporal Difference != Contextual Difference != Normative Conflict != Decision Conflict` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0019, scope THEORY-LEVEL): A six-member taxonomy distinguishing kinds of apparent conflict between assertions, motivated by the Bhishma grandfather/teacher/opponent case: only true logical contradiction (P and not-P) is a falsification-relevant conflict; the rest (semantic incompatibility, temporal/contextual difference, normative conflict, decision conflict) can coexist as simultaneously true/valid without either assertion being false.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0776] §"Bhīṣma is on the opposing side... I would not call that a contradiction... Grandfather \land Opponent is not contradictory. Rather: FamilyDuty vs. WarDuty may produce a normative conflict."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0779] §"Logical contradiction... Semantic incompatibility... Temporal coexistence... Contextual coexistence... Normative conflict... Decision conflict ... Logical Contradiction \neq Semantic Incompatibility \neq Temporal Difference \neq Contextual Difference \neq Normative Conflict \neq Decision Conflict"
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0966. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0846 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0966 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0776, S0779, S0807, S0844, S0846 |
| dependencies | PRESENT | S0776, S0779, S0782, S0807, S0844, S0846 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0776, S0779, S0807, S0844, S0966 |
| examples | PRESENT | S0776 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0966 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Establishes Conflict ⇏ Reject: two conflicting assertions (Nexus.version=3.69 vs 3.70) can BOTH be admitted, coexisting with a Conflict record {A_1,A_2,C} rather than forcing the system to pick one value — 'this preserves reality rather than destroying evidence'. When later investigation resolves the conflict, the superseded assertion is NOT erased but marked Superseded while the new one is Current and the conflict marked Resolved, giving KnowledgeState+History ≠ CurrentSnapshotOnly, connecting to the earlier transition model H_{t+1}=H_t‖e_t. Epistemic transition chain: P_c --AdmissionPolicy--> A; A_t --Evidence--> A_{t+1}; A_t,A'_t --ConflictRule--> C_t; C_t --Resolution--> C_{t+1} — 'a genuine state-transition system' [S0846].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0776] types=[CORRECTION, DISTINCTION, EXAMPLE] scope=CROSS-OBJECT — "Rejects treating 'Bhishma is grandfather' + 'Bhishma is opponent' as a logical contradiction (both propositions are simultaneously compatible); the real conflict is a normative one at the Knower's decision/goal level (family duty vs war duty), requiring Zero to distinguish Logical Contradiction != Normative Conflict != Emotional Conflict != Decision Conflict." (anchor: "Bhīṣma is on the opposing side... I would not call that a contradiction... Grandfather \land Opponent is not contradictory. Rather: FamilyDuty vs. WarDuty may produce a normative conflict.")
- [S0779] types=[CONCEPT, DISTINCTION, EXTENSION] scope=THEORY-LEVEL — "Establishes a six-member conflict taxonomy that must not be collapsed: Logical Contradiction (P and not-P), Semantic Incompatibility (values that cannot coexist under a dimension's definition), Temporal coexistence (no conflict once time resolves it), Contextual coexistence (no conflict once context/scope resolves it, e.g. dev vs prod version), Normative conflict (competing duties, both true), and Decision conflict (competing objectives the Knower cannot jointly satisfy)." (anchor: "Logical contradiction... Semantic incompatibility... Temporal coexistence... Contextual coexistence... Normative conflict... Decision conflict ... Logical Contradiction \neq Semantic Incompatibility \neq Temporal Difference \neq Contextual Difference \neq Normative Conflict \neq Decision Conflict")
- [S0779] types=[CONSTRAINT, EXTENSION] scope=OBJECT — "Extends the Zero Lens constitutional principle set with CONFLICT != CONTRADICTION (alongside the earlier UNKNOWN != FALSE): Zero must report that two assertions cannot currently be reconciled without concluding either is false, distinguishing 'no logical contradiction detected, potential normative/decision conflict detected' from an outright falsification." (anchor: "Zero should report: 'These two assertions cannot currently be reconciled under the selected model.' But it should not automatically conclude: 'One of them is false.' ... CONFLICT \neq CONTRADICTION")
- [S0782] types=[CORRECTION] scope=OBJECT — "Corrects the naive move of marking both conflicting-looking assertions Conflict=Active in place; instead a normative/decision conflict should be represented as a higher-level DDD relation (Conflict(P1,P2,NormativeContext) or DecisionConflict(Knower,{P1,P2},Context)) layered above the unchanged factual assertions." (anchor: "the update should not be: A1 conflict=Active, A2 conflict=Active. Instead, it should create or reveal a higher-level relation: Conflict(P1,P2,NormativeContext) or DecisionConflict(Knower,{P1,P2},Context)")
- [S0806] types=[CONCEPT, EXTENSION] scope=OBJECT — "Introduces a six-member Conflict taxonomy (Logical: p and ¬p; Epistemic: evidence sources disagree; Normative: duties/principles conflict; Temporal: values differ by time; Contextual: true in one context false in another; Semantic: two interpretations of the same expression), preventing Zero from treating every disagreement as the same phenomenon." (anchor: "Conflict = \{ Logical, Epistemic, Normative, Temporal, Contextual, Semantic \}")
- [S0807] types=[EXTENSION, DISTINCTION] scope=OBJECT — "Validates 'Conflict ∉ Σ_A' (Conflict is relational: (A_i,A_j,Interpretation,Context,NormativeFramework,Status)) using Arjuna's perceived Knowledge/Intelligence-vs-Action/Warfare conflict, and adds PerceivedConflict ≠ ActualConflict — Arjuna sees two instructions as contradictory but Krishna's response explains their relation. Refines Conflict into {Apparent, Actual, Resolved, Unresolved} with ConflictAssessment:(A_i,A_j,C) -> {Apparent,Actual,Undetermined}." (anchor: "PerceivedConflict \neq ActualConflict")
- [S0844] types=[VALIDATION, DISTINCTION] scope=OBJECT — "Test Case 7 (same statement, different contexts: Nexus 3.69 in production vs 3.70 in test) shows ¬Conflict(P_1,P_2) unless the conflict rule requires identical contexts, validating that Context must survive the entire pipeline. Test Case 8 (same source at different times: 2025 vs 2026) shows the correct resolution is Validity(P_1)=Expired / Validity(P_2)=Current rather than a logical conflict, giving TemporalChange ≠ LogicalConflict." (anchor: "TemporalChange \neq LogicalConflict")
- [S0846] types=[ARGUMENT, CORRECTION] scope=OBJECT — "Establishes Conflict ⇏ Reject: two conflicting assertions (Nexus.version=3.69 vs 3.70) can BOTH be admitted, coexisting with a Conflict record {A_1,A_2,C} rather than forcing the system to pick one value — 'this preserves reality rather than destroying evidence'. When later investigation resolves the conflict, the superseded assertion is NOT erased but marked Superseded while the new one is Current and the conflict marked Resolved, giving KnowledgeState+History ≠ CurrentSnapshotOnly, connecting to the earlier transition model H_{t+1}=H_t‖e_t. Epistemic transition chain: P_c --AdmissionPolicy--> A; A_t --Evidence--> A_{t+1}; A_t,A'_t --ConflictRule--> C_t; C_t --Resolution--> C_{t+1} — 'a genuine state-transition system'." (anchor: "Conflict \not\Rightarrow Reject")
- [S0966] types=[RESTATEMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Re-derives that a temporally- or contextually-scoped pair (Healthy(10:00) and Failed(14:00); Available(Germany) and not-Available(Switzerland)) is not a logical contradiction once scope is made explicit -- ApparentContradiction != LogicalContradiction. Experiment 1: p@10:00 and not-p@14:00 yields NoLogicalContradiction -- PASS. Experiment 2: Available(Germany) and not-Available(Switzerland) yields Compatible -- PASS. Defines true contradiction as requiring identical subject, predicate, context, time, and scope. Experiment 3 (direct contradiction): E1=>p and E2=>not-p under the same context/time yields Conflict(p), not Delete(E1) or Delete(E2) -- PASS." (anchor: "ApparentContradiction != LogicalContradiction (temporal and contextual scoping); experiments 1-2 and 4")
- [S0966] types=[DEFINITION, EXPERIMENTAL-RESULT] scope=OBJECT — "Gives a conflict taxonomy: TemporalConflict, ContextConflict, MeasurementConflict, SourceConflict, ModelConflict, SemanticConflict, LogicalConflict, each with different resolution mechanisms. Experiment 10: Healthy(10:00)/Failed(14:00) correctly classifies as TemporalChange, not LogicalConflict -- PASS. Experiment 11 (ubiquitous-language collision): Context A's Customer=Purchaser merged with Context B's Customer=ContractHolder into one universal concept yields SemanticConflict -- PASS, reinforcing Concept = Name + Context (SameWord ⇏ SameConcept) as 'classic DDD thinking, now reinforced mathematically'." (anchor: "Seven-item conflict taxonomy (Temporal/Context/Measurement/Source/Model/Semantic/Logical); ubiquitous-language semantic conflict")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
