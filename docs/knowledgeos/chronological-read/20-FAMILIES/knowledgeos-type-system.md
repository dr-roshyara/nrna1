# knowledgeos-type-system

**Scope(s):** OBJECT · **Row count:** 13 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Entity,Dimension,Value,Proposition,Evidence,EpistemicState,Assertion,Relationship,KnowledgeState,KnowledgeSpace`, `embedded-in / member-of / subset-of` · **Aliases:** `Question 7A`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0019, scope OBJECT): Full ten-type formal type system for the KnowledgeOS epistemic model, resolving the containment-vs-set-inclusion category error from Question 7 by giving every prior object (Observation, Dimension, Proposition, Assertion, Evidence, EpistemicState, Relationship, KnowledgeState, KnowledgeSpace) an explicit type and typed operation signatures for Zero/Lord/Sarathi/Compare/Challenge/Update/Preserve.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0785] §"An assertion contains a proposition. That does not mean P subset A. ... Containment \neq Set Inclusion ... P \xrightarrow{embedded in} A \xrightarrow{member of} K_t"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0785] §"Entity | Dimension | Value | Proposition | Evidence | Epistemic State | Assertion | Relationship | Knowledge State | Knowledge Space ... a type system defines: what kinds of objects exist, what properties each has, how types relate, what operations are valid"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0794] §"Content != EpistemicStatus != Evaluation ... Ontology -> Formal Semantics -> State Model -> Evaluation Algebra -> DDD Model -> Implementation, not Graph Schema -> Call it Ontology."

## Lifecycle
last_seen: S0794. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0785, S0794 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0785, S0785, S0794, S0794, S0794 |
| Dependencies | PRESENT | S0785, S0785, S0785, S0785, S0787, S0794, S0794, S0794, S0794, S0794, S0794, S0794 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0794 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0785] types=[CORRECTION] scope=THEORY-LEVEL — "Rejects the K_P subset K_A subset K_J subset K_t subset Omega chain as a category error: propositions, assertions and knowledge states are different types of object, and containment (a proposition being embedded in an assertion) is not the same relation as set inclusion (an assertion being a member of a knowledge state's assertion set); replaces it with a typed composition chain (embedded-in / member-of / subset-of)." (anchor: "An assertion contains a proposition. That does not mean P subset A. ... Containment \neq Set Inclusion ... P \xrightarrow{embedded in} A \xrightarrow{member of} K_t")
- [S0785] types=[CORRECTION, EXTENSION] scope=OBJECT — "Proposes moving Evidence out of the Assertion tuple into an explicit typed relation (Supports/Contradicts/Qualifies/Weakens/Contextualizes between Evidence and Assertion) rather than embedding it as a field, since one assertion may be supported or contradicted by several distinct evidence items with different relation types." (anchor: "Instead of A=(P,Sigma,E,tau,Pi), use Supports(E,A) or EvidenceRelation(E,A,r) where r in {supports,contradicts,qualifies,weakens,contextualizes}")
- [S0785] types=[CORRECTION] scope=OBJECT — "Reaffirms and generalizes the earlier circularity concern (from S0784): Zero and Lord must be typed as operations Zero:K->Z and Lord:K->L over the Knowledge State type K, never as stored fields of K itself; their outputs may be persisted as derived artifacts but are not foundational components of knowledge." (anchor: "Zero and Lord should not be components of Knowledge... Zero is an operation: Zero: K -> Z. Lord is an operation: Lord: K -> L.")
- [S0785] types=[FORMALIZATION, VALIDATION] scope=THEORY-LEVEL — "Answers the posed Question 7A by producing a complete ten-type system for KnowledgeOS (Entity, Dimension, Value, Proposition, Evidence, EpistemicState, Assertion, Relationship, KnowledgeState, KnowledgeSpace) with formal set-builder definitions, a type hierarchy diagram, per-type well-formedness constraints, and operation signatures (Zero/Lord/Sarathi/Compare/Challenge/Update/Preserve, all typed as maps between these object types)." (anchor: "Entity | Dimension | Value | Proposition | Evidence | Epistemic State | Assertion | Relationship | Knowledge State | Knowledge Space ... a type system defines: what kinds of objects exist, what properties each has, how types relate, what operations are valid")
- [S0787] types=[CORRECTION, EXTENSION] scope=OBJECT — "Corrects WellTyped from a pure object-typing predicate to also require relationship typing and cardinality validity (e.g. Supports must relate Evidence to Assertion, not Person to Assertion), since syntactically valid objects can still violate relationship-type rules." (anchor: "WellTyped(K) = WellTypedObjects(K) and WellTypedRelationships(K) and ValidCardinalities(K) ... Supports(Evidence,Assertion) valid; Supports(Person,Assertion) invalid")
- [S0794] types=[FORMALIZATION] scope=THEORY-LEVEL — "Proposes a 13-type universe (Entity, Dimension, Value, Proposition, Assertion, Relationship, Evidence, EpistemicState, History, ZeroFinding, LordCandidate, KnowledgeState, IdealState) with formal set-builder definitions, well-formedness predicates for Proposition/Assertion/Relationship, and an explicit epistemic lattice claim over the 2240-state Sigma space." (anchor: "U = {E, D, V, P, A, R, E_v, Sigma, H, Z, L, K, I} ... this ontology is the foundation for all KnowledgeOS operations.")
- [S0794] types=[CORRECTION] scope=OBJECT — "Corrects the Entity definition (which included 'relationship' as an example subject) for creating an ontological ambiguity where a Relationship could also be typed as an Entity; a relationship may become the subject of a proposition without itself being classified as an Entity." (anchor: "You later define Relationship separately... Relationship in Entity and Relationship in Relationship could become possible. Entity != Relationship.")
- [S0794] types=[CORRECTION, EXTENSION] scope=OBJECT — "Corrects the Entity+Dimension+Value proposition model as too narrow to represent relational/meta propositions (assertion-contradicts-assertion, evidence-supports-assertion); generalizes Proposition to a subject-predicate-object-qualifiers form P=(S,rho,O,Gamma), with the earlier attribute proposition recovered as the special case rho=hasDimension, so AttributeProposition becomes a subtype of Proposition rather than the whole of it." (anchor: "P=(E,D,V) ... too restrictive for the complete KnowledgeOS theory ... doesn't naturally represent 'Bhishma supports the Kaurava side' or 'Assertion A contradicts Assertion B' or 'Evidence E supports Assertion A' ... P=(S,rho,O,Gamma) ... AttributeProposition subset Proposition")
- [S0794] types=[CORRECTION, LIMITATION] scope=OBJECT — "Rejects the claim that the epistemic state space (Sigma=A x S x R x V x C with a proposed preceq order) forms a mathematical lattice, since the required least-upper-bound/greatest-lower-bound structure was never established and the Resolution component (Open/InProgress/Resolved/Unresolvable) is not naturally ordered; downgrades the claim to an unstructured product state space pending proof of any lattice properties." (anchor: "To call something a lattice mathematically, you need a partial order such that every pair has lub and glb. You haven't defined that... Replace Transition Lattice with Epistemic State Space or Epistemic Transition System.")
- [S0794] types=[CORRECTION] scope=OBJECT — "Rejects the signed [-1,1] evidence-support scalar (0 could mean neutral, irrelevant, insufficient, unknown, or not-assessed -- these are not equivalent), replacing it with a categorical Bearing (Support/Contradict/Neutral/Irrelevant/Unknown) plus an optional separate numeric Strength." (anchor: "Supports(E_v,P) in [-1,1] ... epistemically dangerous... I recommend replacing the primitive scalar with Bearing(E,P) in {Support,Contradict,Neutral,Irrelevant,Unknown} and separately Strength(E,P) in [0,1].")
- [S0794] types=[CORRECTION] scope=OBJECT — "Corrects the Assertion well-formedness rule that required a non-empty Evidence field, which would make MissingEvidence (an established Gap type) unrepresentable; an assertion's Evidence set must be allowed to be empty." (anchor: "An Assertion without evidence must be representable, otherwise Zero cannot represent a missing-evidence condition... Evidence(A) subseteq E_v and |Evidence(A)| >= 0.")
- [S0794] types=[CORRECTION] scope=OBJECT — "Flags an actual notation error (the symbol ~ used for two different relationship types, Consistent and Normative Conflict, in the same table) and a symbol-overload error (=> used for causal implication when it conventionally denotes logical implication), recommending distinct notations and the explicit invariant Causation != LogicalEntailment." (anchor: "You use ~ for both Consistent (line 480) and Normative Conflict (line 488). That is unacceptable in a formal theory... A1 => A2 as causal... but => is conventionally logical implication. Causation != LogicalEntailment.")
- [S0794] types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — "States the batch's summary methodological principle: Content, Epistemic Status, and Evaluation are three different mathematical objects that must never be collapsed, and warns against letting a convenient implementation/graph-database representation silently substitute for genuine formal ontology work (Ontology->Semantics->StateModel->EvaluationAlgebra->DDD->Implementation, never the reverse)." (anchor: "Content != EpistemicStatus != Evaluation ... Ontology -> Formal Semantics -> State Model -> Evaluation Algebra -> DDD Model -> Implementation, not Graph Schema -> Call it Ontology.")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. No internal tension noticed across this label's own rows for this batch.
