# actor-state-concept

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ActorState=(Role,Capabilities,KnowledgeAccess,Authority,Purpose,Commitments,AffectiveContext)`
**Aliases:** "Actor State"; "Knower State"
**Candidate group membership (NOT an identity claim):**
- G0943: token-overlap signal with `normative-state-concept` (Jaccard=0.50, shared tokens "concept", "state").
- G1435: co-occurs (in the same contribution's `labels[]`) with `knowledge-atma-identity-concept`, 2 separate times across the corpus.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0020, scope OBJECT: "A formal Actor/Knower state distinct from Knowledge State, making the acting subject (e.g. Arjuna) representable, with CognitiveCapability as a related but distinct faculty typology."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0807 §"State_{Arjuna} = (K,U,N,D,E)"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0807 §"State_{Arjuna} = (K,U,N,D,E)"]
- CANDIDATE-FORMAL-BIRTH: [S0807 §"ActorState = (Role, Capabilities, KnowledgeAccess, Authority, Purpose, Commitments, AffectiveContext)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1274. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a recency heuristic based on last use (S1274, batch B0031, later than the founding B0020 rows), not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0807, S0808, S1274 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0807, S0808, S1274 |
| dependencies | PRESENT | S0808 (×2), S0819, S0820 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0808, S0819 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE); the row set is CONCEPT/FORMALIZATION/DISTINCTION/LIMITATION material instead. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0807] types=[CONCEPT, EXTENSION] scope=OBJECT — "Arjuna's problem is not purely epistemic: KnowledgeGap+UnderstandingGap+NormativeGap+ActionConflict can occur simultaneously, giving State_Arjuna=(K,U,N,D,E) where E is emotional/psychological state, modeled as a separate AffectiveState rather than folded into the Knowledge State. Suggests a DDD separation of concerns." (anchor: "State_{Arjuna} = (K,U,N,D,E)")
- [S0807] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Formalizes Actor State (Role, Capabilities, KnowledgeAccess, Authority, Purpose, Commitments, AffectiveContext), making Arjuna formally representable and establishing KnowledgeState ≠ ActorState: Action = f(Knowledge, Actor, Role, Purpose, Norms, Authority)." (anchor: "ActorState = (Role, Capabilities, KnowledgeAccess, Authority, Purpose, Commitments, AffectiveContext)")
- [S0808] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN, also labeled `normative-state-concept` — "Strengthens Role's centrality: Duty=f(Role,Context,NormativeFramework) rather than Duty=f(Actor); models Actor--holds-->Role--activates-->Duty--constrained by-->Norm as a value-object chain (Actor ≠ Role ≠ Duty); warns against carelessly generalizing sva-dharma into 'every software actor has a role and therefore a duty'." (anchor: "Actor \\neq Role \\neq Duty")
- [S0808] types=[CONCEPT, CORRECTION] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[actor-state-concept] — "Retains a causal chain (Sense Objects->Attachment/Desire->Distortion->Impaired Knowledge->Action) showing AgentState can affect InterpretationOfKnowledge, but corrects S0807's 'AffectiveState' as too narrow, replacing it with a broader AgentCondition (cognitive/motivational/affective condition, capability, role, authority, commitments)." (anchor: "Sense Objects \\rightarrow Attachment/Desire \\rightarrow Distortion \\rightarrow Impaired Knowledge \\rightarrow Action") — lineage claim: SOURCE-CLAIMED-REFINEMENT of S0807's proposed AffectiveState.
- [S0819] types=[DISTINCTION, EXTENSION] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[knowledge-atma-identity-concept], also labeled `knowledge-atma-identity-concept` — "Distinguishes two Ātma concepts requiring different names: Human/Knower Ātma 𝒜_H (e.g. Arjuna, changing knowledge state K^H_t but persistent subject) and KnowledgeOS Ātma 𝒜_K (the persistent identity/principle of the KnowledgeOS epistemic system/kernel)." (anchor: "we can distinguish two Ātma concepts")
- [S0820] types=[LIMITATION, CONCEPT] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[actor-state-concept], also labeled `knowledge-atma-identity-concept`, completeness NAME-ONLY (missing: full formal definition of each field) — "Identifies the Knower itself as the biggest missing formally-completed piece despite 𝒜_H being named: proposes N=(Identity,Intent,Perspective,Context,Goals,Capabilities,Constraints,...), motivated by the principle that two Knowers observing the same reality can produce different Ideal States as a fundamental system property, not an error." (anchor: "N=(Identity,Intent,Perspective,Context,Goals,Capabilities,Constraints,...)")
- [S1274] types=[EXTENSION, FORMALIZATION] scope=OBJECT, also labeled `gita-chapter3-validation-exercise` — "Expands Action from (Actor, Operation, Result) into an eight-field structure (Actor, Role, Operation, Duty, Intention, Orientation, Context, Outcome), with new invariants ObservedAction ≠ ActionMeaning and CanAct ≠ ShouldAct ≠ WillAct." (anchor: "Action = (Actor, Role, Operation, Duty, Intention, Orientation, Context, Outcome) ... ObservedAction != ActionMeaning; CanAct != ShouldAct != WillAct") — lineage claim: SOURCE-CLAIMED-REFINEMENT of the prior three-field Action=(Actor,Operation,Result).

## Notes for P3

- Five of this label's seven rows carry `label_confidence: UNCERTAIN` (S0808 ×2, S0819, S0820) and three carry an `unknown_candidate` marker pointing at either this label or `knowledge-atma-identity-concept` — this is one of the more genuinely unsettled labels in this batch, with the corpus itself still negotiating field names (AffectiveState → AgentCondition) and even which of two closely related labels (`actor-state-concept` vs. `knowledge-atma-identity-concept`) a given row belongs to.
- P3 should note the explicit self-correction chain here: S0807 proposes `AffectiveState`; S0808 explicitly narrows/refines it to `AgentCondition`, citing S0807 by name ("I would not call the new thing simply AffectiveState. That was too narrow in my previous answer.") — this is strong same-thread lineage, not mere group co-occurrence.
- Given G0943's Jaccard overlap with `normative-state-concept` and G1435's repeated co-occurrence with `knowledge-atma-identity-concept`, and the fact that S0808/S0819/S0820 rows are literally double- or triple-labeled across these candidates, this cluster looks like a strong reconciliation priority for P3.
