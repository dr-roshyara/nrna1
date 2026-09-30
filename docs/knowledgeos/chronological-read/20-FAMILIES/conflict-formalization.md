# conflict-formalization

**Scope(s):** OBJECT · **Row count:** 12 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Conflict_A(A1,A2), Conflict_I(I1,I2|P,K,C), Reframe(K,C)->K' · **Aliases:** Question 9
**Candidate group membership (NOT an identity claim):** G0215: shares an explicit agent-stated uncertainty with `kos-paraconsistency-and-revision` (batch B0023) — Step 68's paraconsistency/belief-revision layer (four-valued epistemic state, structured Conflict object with provenance, Authority≠Truth, non-destructive revision with mandatory RevisionReason, Retracted≠NeverExisted, EpistemicState≠DecisionPolicy) is noted as possibly extending this label's B0019 Question-9 material with explosion-avoidance/paraconsistent-logic framing and DDD-aligned revision machinery — relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0019, scope OBJECT: "Defines Conflict with five initial types later corrected to a content-conflict-plus-applicable-frame model, splits assertion-level from implication-level conflict, and introduces Reframe as a third mode (beside evidence acquisition and dimension discovery) by which KnowledgeOS resolves epistemic tension without picking a winning assertion."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0787 §"we already discovered at least four possible forms: Conflict_logical: A1 perp A2; Conflict_epistemic: E1 not-equiv E2; Conflict_normative: V1 not-implies V2; Conflict_decision: A1 implies D1, A2 implies D2"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0787 §"we already discovered at least four possible forms: Conflict_logical: A1 perp A2; Conflict_epistemic: E1 not-equiv E2; Conflict_normative: V1 not-implies V2; Conflict_decision: A1 implies D1, A2 implies D2"]
- CANDIDATE-FORMAL-BIRTH: [S0788 §"A conflict is a situation in which two or more assertions cannot both be fully accepted within the same scope, context, and temporal framework, or where they generate incompatible implications for the Knower's purpose ... Conflict \\neq Incoherence"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0806. Candidate lifecycle: DORMANT. Evidence: retracted_by is empty, superseded_by is empty, contested_by_own_contradiction_type is false — no row in this label's own family claims retraction or supersession of this object, and no internal contradiction-type row was found. The DORMANT tag is a heuristic based on how recently (by source_id) this label was last used (S0806, batch B0020, versus a corpus that continues well beyond), not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0788, S0789 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0788, S0788 |
| type_signature | PRESENT | S0788 |
| invariants | PRESENT | S0788, S0788, S0788, S0796, S0796 |
| dependencies | PRESENT | S0787, S0788 (x7), S0789, S0796 (x2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0788, S0788, S0796 |
| examples | PRESENT | S0789 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0787 |

## Rationale
The rationale evidence centers on why Conflict cannot be resolved by simple assertion-elimination. S0788 introduces `Reframe(K,Context)->K'` as a third distinct KnowledgeOS operation (alongside evidence acquisition and dimension discovery) for reducing uncertainty: the original assertions remain true, but their meaning or relationship within the model changes — illustrated by Krishna resolving Arjuna's dilemma not by picking family-over-kingdom but by reframing duty itself [S0788]. This addresses a gap the model would otherwise have: without Reframe, "some conflicts are resolved by knowledge transformation, not merely by evidence selection" would have no home [S0788]. S0789 extends the rationale by showing Gap and Conflict are distinct-but-coupled phenomena — an apparent epistemic conflict between two sources can disappear once a missing dimension is discovered, but that same discovery can reveal a new gap, so the two concepts must not be treated as interchangeable or as one subsuming the other [S0789]. rationale_truncated_count is 0, so no further rationale-bearing rows exist beyond what's shown.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0787] types=[OPEN-QUESTION, CONCEPT] scope=THEORY-LEVEL — "Previews four conflict forms (logical, epistemic, normative, decision) as the starting point for the upcoming Question 9, reiterating that Bhishma-as-grandfather/opponent is not a logical contradiction but produces a higher-order conflict about what Arjuna should do." (anchor: "we already discovered at least four possible forms: Conflict_logical: A1 perp A2; Conflict_epistemic: E1 not-equiv E2; Conflict_normative: V1 not-implies V2; Conflict_decision: A1 implies D1, A2 implies D2")
- [S0788] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines Conflict with five types (Logical Contradiction, Normative, Epistemic, Temporal, Contextual), each formalized as a predicate over assertions/evidence, plus a Conflict structure (Type,Assertions,Status,Evidence,Resolution,tau) and per-type resolution strategy tables; states Conflict != Incoherence and conflicts must be explicitly represented in a coherent Knowledge State." (anchor: "A conflict is a situation in which two or more assertions cannot both be fully accepted within the same scope, context, and temporal framework, or where they generate incompatible implications for the Knower's purpose ... Conflict \\neq Incoherence")
- [S0788] types=[CORRECTION, DISTINCTION] scope=OBJECT — "Splits Conflict into Conflict_A(A1,A2) (the assertions themselves cannot jointly be accepted) versus Conflict_I(I1,I2|P,K,C) (their derived implications create competing requirements for the Knower); grandfather+opponent assertions are jointly satisfiable, so only the implication-level conflict is real — this should become a fundamental invariant." (anchor: "A1 land A2 is perfectly satisfiable. The conflict arises when we derive I1='Protect grandfather' and I2='Fight opponent'. Conflict(A1,A2) \\neq Conflict(Implication(A1),Implication(A2))")
- [S0788] types=[CORRECTION] scope=OBJECT — "Demotes Temporal and Contextual conflict from independent conflict types to conditions of an applicable frame F=(Scope,Context,Time,Purpose) under which a genuine content conflict either does or does not appear: Conflict(A1,A2|F)." (anchor: "Temporal and Contextual conflict are not necessarily independent conflict types. They may actually be conditions under which another conflict appears ... Conflict = ContentConflict + ApplicableFrame")
- [S0788] types=[CORRECTION, CONCEPT] scope=OBJECT — "Corrects the assumption that resolving a conflict always means eliminating it by picking a winner: introduces six distinct resolution outcomes (Eliminated, Decided, Reframed, Accepted, Deferred, Irreducible)." (anchor: "ConflictResolution \\neq ConflictElimination ... Eliminated, Decided, Reframed, Accepted, Deferred, Irreducible")
- [S0788] types=[EXTENSION, ARGUMENT] scope=OBJECT — "Introduces Reframe(K,Context)->K' as a distinct KnowledgeOS operation: original assertions remain true but their meaning/relationship within the model changes; establishes three fundamentally different ways KnowledgeOS reduces uncertainty — evidence acquisition, dimension discovery, and reframing." (anchor: "Krishna does not merely choose between Family vs. Kingdom. He changes the conceptual frame through which Arjuna understands the problem ... Reframe(K,C) -> K' ... Some conflicts are resolved by knowledge transformation, not merely by evidence selection.")
- [S0788] types=[CONSTRAINT] scope=METHODOLOGICAL — "Warns against a DDD modelling error of flattening conflict subtypes and epistemic capabilities into one undifferentiated list of domain entities; separates Knowledge Domain objects from Epistemic Operations/capabilities, with Sarathi as the orchestrating navigation role." (anchor: "Do not make LogicalConflict, NormativeConflict, Zero, Sarathi, Lord all equal-level domain entities. That would flatten the ontology.")
- [S0788] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Defines a complete conflict-handling transition system (Detect -> Classify -> Navigate -> Apply) producing K_{t+1}, and states the architectural principle that KnowledgeOS must not assume every conflict resolves by choosing a winning assertion." (anchor: "K_t \\xrightarrow{Detect} C_t \\xrightarrow{Classify} C_t^* \\xrightarrow{Navigate} e_t \\xrightarrow{Apply} K_{t+1} ... KnowledgeOS must not assume that every conflict is resolved by choosing between competing assertions.")
- [S0789] types=[ARGUMENT, EXAMPLE] scope=THEORY-LEVEL (also labeled gap-formalization) — "Shows Gap and Conflict can transform into each other: an apparent epistemic conflict disappears once a missing dimension is discovered, but that discovery can reveal a new gap — Gap and Conflict are distinct but dynamically coupled phenomena." (anchor: "Conflict -> New Dimension/Context -> Conflict disappears -> Gap revealed ... Gap and Conflict are distinct but dynamically coupled.")
- [S0796] types=[CORRECTION] scope=OBJECT (also labeled epistemic-logic-formalization) — "Removes Conflict from the per-assertion epistemic-state vector: a conflict relates two (or more) assertions, so it must be modelled as an external relational object C=(A_i,A_j,Rule,Context,Status), not a field of Sigma_A." Carries a lineage_claims entry of kind SOURCE-CLAIMED-CORRECTION targeting "Sigma=(A,S,R,V,C) with C=Conflict field", quote "Remove Conflict C from the assertion's state vector." (anchor: "These five dimensions do not belong to the same conceptual axis... Conflict notin Sigma_A ... C_{ij}=(A_i,A_j,rho,kappa,sigma) ... Conflict is not intrinsically a property of one assertion.")
- [S0796] types=[DISTINCTION, EXTENSION] scope=OBJECT — "Separates Contradiction (a purely logical relation under a context) from Conflict (the broader epistemic/domain issue), and generalizes Conflict from pairwise to n-ary: Conflict=(A_C,Rule,Context,Status,tau)." (anchor: "Contradicts(A_i,A_j|Context) ... Conflict(A_C,Rule,Context) ... Contradiction != Conflict ... Bhishma-grandfather and Bhishma-opponent are not contradictory. They may create a normative tension, but there is no logical contradiction.")
- [S0806] types=[CONCEPT, EXTENSION] scope=OBJECT, label_confidence=UNCERTAIN (unknown_candidate: candidate_of both conflict-formalization and conflict-taxonomy-vs-contradiction) — "Introduces a six-member Conflict taxonomy (Logical, Epistemic, Normative, Temporal, Contextual, Semantic), preventing Zero from treating every disagreement as the same phenomenon." (anchor: "Conflict = \\{ Logical, Epistemic, Normative, Temporal, Contextual, Semantic \\}")

## Notes for P3
- Own observation: S0796's row explicitly claims a correction against an earlier `Sigma=(A,S,R,V,C)` model that placed Conflict as a per-assertion field — this earlier model does not itself appear among this label's own rows, so its full context is outside this file's evidentiary base; P3 may want to check whether that prior Sigma formulation has its own working_label.
- Own observation: S0806 is the only row in this family flagged `label_confidence: UNCERTAIN` with an explicit `unknown_candidate` (dual candidacy between `conflict-formalization` and `conflict-taxonomy-vs-contradiction`) — its six-member taxonomy is evidentially the last-seen row and also the least certain attribution to this specific label; worth flagging for reconciliation.
- Own observation: the row sequence shows a clear internal evolution (pairwise -> frame-conditioned -> n-ary; per-assertion field -> external relational object) rather than a contradiction; this reads as legitimate theory refinement across S0787→S0788→S0796→S0806, not instability.
