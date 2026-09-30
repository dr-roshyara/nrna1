# theory-object-dependency-graph-layered-model

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `(E,D,V_D) as true foundational layer`, `Layer 0..8` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1692**: [`policy-provenance-reflexivity-gap` · `theory-object-dependency-graph-layered-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0040`, scope `THEORY-LEVEL`: The nine-layer, nineteen-object dependency graph establishing (Entity,Dimension,Value-space) rather than K as the theory's true foundational layer, dissolving the T->K->Invariants->T circularity and confirming Policy->T->Policy as the theory's one genuine (governance-level) cycle.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1665 §"K is not foundational. It is derived, three levels up. The true foundational layer is the typed primitive triple (ℰ, 𝒟, V_D) from Q14 ... The earlier circularity T → K → Invariants → T was an artefact of treating K as primitive. Once K is derived and invariants are typed as predicates over 𝕂 rather "]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1665 §"LAYER 0 PRIMITIVE ℰ (entities) 𝒟 (dimensions) V_D (value spaces) Time Origin ... LAYER 1 CONTENT P = (E,D,V) Observation ... LAYER 4 STATE 𝒜 = Set(Assertion) ℛ ⊆ 𝒜×𝒜×Type K = (𝒜,ℛ) ... LAYER 6 DYNAMICS T ... LAYER 7 DERIVED Assessment → Σ Validation Γ Invariants ... LAYER 8 RECORD History Lineage. E"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1665. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1665, S1665 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1665, S1665, S1665, S1665 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1665, S1665, S1665 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1665 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1665] types=['CORRECTION', 'PRINCIPLE'] scope=THEORY-LEVEL — "Headline correction: K is NOT the foundational layer of the theory — it is derived, three layers above the true base, which is the typed primitive triple (Entity universe, Dimension universe, dimension-indexed value spaces) from Q14; the previously suspected circularity T->K->Invariants->T is dissolved once invariants are correctly re-typed as predicates OVER K rather than components stored inside it — 'predicates over a structure are not part of the structure'." (anchor: "K is not foundational. It is derived, three levels up. The true foundational layer is the typed primitive triple (ℰ, 𝒟, V_D) from Q14 ... The earlier circularity T → K → Invariants → T was an artefact of treating K as primitive. Once K is derived and invariants are typed as predicates over 𝕂 rather")
- [S1665] types=['FORMALIZATION'] scope=THEORY-LEVEL — "A strict nine-layer dependency stack is derived for the whole theory: Layer 0 Primitive (Entities, Dimensions, Value spaces, Time, Origin), Layer 1 Content (Proposition, Observation), Layer 2 Qualified (Evidence, Context, Identity), Layer 3 Claim (Assertion), Layer 4 State (Assertion set, Relations, K), Layer 5 Norm (Policy, Authority), Layer 6 Dynamics (Transformation T), Layer 7 Derived (Assessment/Sigma, Validation, Gamma, Invariants), Layer 8 Record (History, Lineage) — every dependency arrow points strictly upward, with no arrow returning to a lower layer." (anchor: "LAYER 0 PRIMITIVE ℰ (entities) 𝒟 (dimensions) V_D (value spaces) Time Origin ... LAYER 1 CONTENT P = (E,D,V) Observation ... LAYER 4 STATE 𝒜 = Set(Assertion) ℛ ⊆ 𝒜×𝒜×Type K = (𝒜,ℛ) ... LAYER 6 DYNAMICS T ... LAYER 7 DERIVED Assessment → Σ Validation Γ Invariants ... LAYER 8 RECORD History Lineage. E")
- [S1665] types=['FORMALIZATION'] scope=THEORY-LEVEL — "All nineteen theory objects (Time, Origin, Content/primitives, Observation, Evidence, Context, Provenance, Identity, Assertion, K, Policy, Authority, Invariant, Transformation, Assessment, Sigma, Gamma, Decision, History, Lineage) are tabulated with type, primitive-or-derived status, kind (metadata/state/policy/assessment/event/operation/relation), dependencies, whether a base case exists, and computability — every object has a base case, and Authority is flagged with its binding as UNDEFINED even though it is otherwise primitive." (anchor: "Time PRIMITIVE ... Content (ℰ,𝒟,V_D) PRIMITIVE ... K (𝒜,ℛ) DERIVED ... Policy 5-tuple primitive at layer 5 — but see G-P1 ... Authority competence primitive — binding UNDEFINED ... Σ (dir,str) DERIVED ... Lineage Π ∘ ℛ_der* DERIVED O(n+m)")
- [S1665] types=['EXPERIMENTAL-RESULT', 'VALIDATION'] scope=THEORY-LEVEL — "A six-cycle circularity audit finds five suspected cycles resolved/absent (T-K-Invariants dissolved by re-typing; Sigma->K not present since Sigma is layer 7 and never enters K; contradicts->R->contradicts is not a cycle since contradicts only reads the stored R; Valid->contradicts->K->Valid not present since StructuralValid never invokes contradicts; Policy->T->Policy not present for ordinary policy evaluation) and confirms exactly ONE genuine loop, independently re-deriving the G-P1 reflexivity finding: a policy CHANGE is itself a transformation requiring a policy to authorize it, terminating only at an adopted constitution." (anchor: "T → K → Invariants → T DISSOLVED ... Σ → K NOT PRESENT ... contradicts → ℛ → contradicts NOT A CYCLE — contradicts reads ℛ; ℛ is stored, never computed from contradicts ... Valid → contradicts → K → Valid NOT PRESENT ... Policy → T → Policy NOT PRESENT for evaluation. BUT: a policy CHANGE is a trans")
- [S1665] types=['RESTATEMENT'] scope=THEORY-LEVEL — "Capstone circularity-audit conclusion: across all nineteen theory objects, exactly one genuine cycle exists, located at the governance level (Policy->T->Policy) rather than the semantic level — every other relationship in the theory is a strict, acyclic order." (anchor: "Exactly one genuine loop exists in the entire theory, and it is at the governance level, not the semantic level: Policy → T → Policy. Everything else is a strict order.")
- [S1665] types=['PRINCIPLE'] scope=THEORY-LEVEL — "Explains why the whole theory is computable at all: every derived object bottoms out at the finite, enumerable Layer 0 primitives, which is what makes WellFormed, StructuralValid, membership and equality reduce to decidable finite-set membership; the single most consequential structural fact in the entire theory is that V in V_D is decidable specifically because Q14 attached ValueSpace to Dimension — without that single design decision, WellFormed(P) would be undecidable and T would have no structural guard at all." (anchor: "Every derived object bottoms out at layer 0, and layer 0 is finite and enumerable ... That is what makes WellFormed, StructuralValid, membership and equality all decidable — they reduce to finite set membership. The single most consequential structural fact: V ∈ V_D is decidable because V_D is decla")

## Notes for P3
- This label participates in 1 candidate group(s) (G1692) — per R5/R12 this is not an identity claim; P3 should review whether any group member denotes the same underlying object as this label.
