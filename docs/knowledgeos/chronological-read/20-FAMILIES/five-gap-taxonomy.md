# five-gap-taxonomy

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Gap=(Epistemic,Understanding,Normative,Decision,Domain) · **Aliases:** Five gaps
**Candidate group membership (NOT an identity claim):** Ungrouped (`group_ids` empty) — no mechanical signal connected this label to any other label via group_ids in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0020, scope OBJECT: "Replaces the three-dimensional discrepancy Δ=(Δ_E,Δ_U,Δ_D) with a five-member Gap taxonomy adding Normative and Decision gaps, and rejects 'Δ=∅ ⇒ Success' in favor of DecisionReady=CriteriaSatisfied."

**Single-candidate flag (NOT a confirmed source):** source_id S1432, batch B0035 — why_uncertain: "A 14-category theory-gap taxonomy (Definition/Type/Assumption/Proof/Consistency/Completeness/Identifiability/Measurement/Statistical/Computational/Semantic/Governance/Architecture/Empirical gaps) with a 13-field gap record schema; distinct from the earlier five-member Gap taxonomy (Epistemic/Understanding/Normative/Decision/Domain) — unclear if this replaces, coexists with, or is unrelated to that object, and whether it matches the corpus's own 'existing G1-G14 taxonomy' the file references as already present." S1432 does not appear in this label's `family.rows` — it is flagged only as an uncertain possible successor/relative, not confirmed evidence for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0807 §"Gap = (Epistemic, Understanding, Normative, Decision, Domain)"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0811 §"UNKNOWN │ ├── Dimension unknown"]
- CANDIDATE-FORMAL-BIRTH: [S0807 §"Gap = (Epistemic, Understanding, Normative, Decision, Domain)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0810 §"Δ = Diff(CurrentState,ApplicableCriteria)"]

## Lifecycle
last_seen: S0824. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (no retracted_by, no superseded_by, not contested). DORMANT is a recency heuristic (all ten rows fall within batch B0020, 2026-08-26/27, relatively early in the corpus) — it is not a confirmed retirement. Note, however, that several of this label's own rows are themselves explicit *source-claimed retractions* of the original five-member Gap taxonomy (see rows below) — that internal retraction history is distinct from and not counted in the `lifecycle_evidence.retracted_by` field, which remains empty.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0811, S0815 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0807, S0808 |
| type_signature | PRESENT | S0808 |
| invariants | PRESENT | S0807, S0815, S0821 |
| dependencies | PRESENT | S0807, S0808 (x2), S0810, S0811 (x2), S0815, S0821, S0824 — 9 total |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0824 |

## Rationale
Two rows carry classified rationale evidence. First: Δ_t=Diff(K_t,I_t) should be structured (missing dimensions, unknown values, evidence deficiencies, conflicts, temporal issues, context issues, domain deficiencies) rather than a single score, so KnowledgeOS can answer "how far are we from a state in which this decision can responsibly be made?" — judged more meaningful than an AI confidence score [S0811]. Second, in a related but distinct discussion of Moksha: rejects "Moksha = nearly infinite knowledge" as too narrow, since an infinite subset can still miss infinitely much (the ℕ⊂ℝ analogy), giving Knowledge Quantity ≠ Knowledge Completeness, judged "exactly consistent with the Zero theory" [S0815]. `rationale_truncated_count` is 0, so no further rationale-bearing rows are known to exist beyond this capture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S0807] types=[FORMALIZATION, EXTENSION] scope=OBJECT` — "Extends Δ_U to add Δ_Normative and Δ_Decision, giving Δ=(Δ_E,Δ_U,Δ_N,Δ_D,Δ_Decision), and formalizes a five-member Gap taxonomy: Epistemic (we don't know), Understanding (know facts but not meaning), Normative (don't know what ought to be done), Decision (know facts but can't determine the decision), Domain (reality doesn't match desired state) — 'a major improvement over our earlier three-dimensional discrepancy'. Reworks the Arjuna example's initial discrepancy as Δ_0=(Δ_E,Δ_U,Δ_N,Δ_Decision,Δ_A) (adding affective conflict Δ_A) instead of the earlier (d_Dim,d_Value,d_Epistemic)." Lineage claim: SOURCE-CLAIMED-EXTENSION of "Q19's three-dimensional discrepancy model Δ_t=(Δ_E,Δ_U,Δ_D)".
- `[S0807] types=[CORRECTION, RETRACTION] scope=THEORY-LEVEL, label_confidence=UNCERTAIN (candidate_of: decision-readiness-stop-condition-model)` — "Rejects the previous Arjuna example's claim 'After Moral Resolution → Δ=∅' as a very important correction... Resolution ≠ Δ=∅; DecisionReady(Δ,P) may hold even though Δ≠∅. Generalized to abandon 'Δ=∅ ⇒ Success' entirely: DecisionReady=CriteriaSatisfied, not DecisionReady=NoRemainingGaps..." Dual-labeled with `decision-readiness-stop-condition-model` (not among this batch's 20 assigned labels); this row's `unknown_candidate` field marks it as an uncertain candidate attribution to that other label. Invariant: "DecisionReady=CriteriaSatisfied, not NoRemainingGaps". Lineage claim: SOURCE-CLAIMED-RETRACTION of "an earlier Arjuna worked example claiming 'After Moral Resolution → Δ = ∅'".
- `[S0808] types=[RETRACTION, FORMALIZATION] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "Retracts freezing the six-dimension discrepancy vector Δ=(Δ_E,Δ_U,Δ_N,Δ_D,Δ_Decision,Δ_A) from S0807, arguing it conflates three different categories (state dimensions, relations, deficiencies)... Replaces it with Δ(S,C,P)=Diff(S,Expected(C,P)) yielding a typed SET of findings Δ={d_1,...,d_n}..." Review flag: MATH-QUESTION. Type signature: domain "state x context x purpose", codomain "set of typed findings", arity 3. Lineage claim: SOURCE-CLAIMED-RETRACTION of "S0807's six-dimension discrepancy vector".
- `[S0808] types=[RETRACTION, CORRECTION] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "Demotes Δ_Decision from a first-class discrepancy dimension (proposed in S0807): Decision = f(Understanding,Norms,Role,Context,Evidence,Options,Criteria), with the decision engine instead reporting typed reasons (insufficient evidence, ambiguous norm, conflicting constraints, missing authority, uncertain outcome) rather than a dedicated dimension." Lineage claim: SOURCE-CLAIMED-RETRACTION of "S0807's Δ_Decision as a first-class discrepancy dimension".
- `[S0810] types=[LIMITATION, GOVERNANCE] scope=THEORY-LEVEL, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "States explicitly that Chapter 3 does NOT establish KnowledgeOS as a software architecture, nor prove S=(W,K,U,N,A,C), Δ=Diff(S,C), that Zero/Lord/Sārathi must be separate software components, or that discrepancy should be a fixed vector... after the second review the vector is explicitly not frozen, in favor of Δ=Diff(CurrentState,ApplicableCriteria) yielding a typed set of findings rather than a fixed vector."
- `[S0811] types=[CONCEPT, EXTENSION] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "Argues distinguishing kinds of 'I don't know' may be one of the most commercially important capabilities: a ten-item taxonomy — Dimension unknown, Value unknown, Evidence missing, Evidence insufficient, Evidence conflicting, Assertion stale, Context unclear, Assumption unvalidated, Relationship unknown, Consequence unresolved — replacing a collapsed 'I don't know' answer with 'what exactly is the epistemic condition of what we think we know?'."
- `[S0811] types=[CONCEPT, ARGUMENT] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — see Rationale section above for full statement (Δ_t=Diff(K_t,I_t) as structured, not scalar).
- `[S0815] types=[CORRECTION, ARGUMENT] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — see Rationale section above for full statement (Knowledge Quantity ≠ Knowledge Completeness). Dual-labeled with `moksha-epistemic-limit-concept` (not among this batch's 20 assigned labels). Review flag: MATH-QUESTION. Invariant: "Knowledge Quantity ≠ Knowledge Completeness". Lineage claim: SOURCE-CLAIMED-REFINEMENT of "a proposed reading of Moksha as 'a state where nearly infinite knowledge is gained'".
- `[S0821] types=[CORRECTION, INVARIANT] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "Confirms structured discrepancy d=(d_1,...,d_n) is easily computable, but a single scalar d(K,I) is NOT automatically mathematically justified... 'Vector discrepancy is fundamental' and 'Scalar distance is policy-dependent' should remain explicit architectural invariants." Review flag: MATH-QUESTION. Invariants: "Vector discrepancy is fundamental", "Scalar distance is policy-dependent".
- `[S0824] types=[EXTENSION, OPEN-QUESTION] scope=OBJECT, label_confidence=UNCERTAIN (candidate_of: five-gap-taxonomy)` — "Refines 'unknown' further into three radically different conditions (Unknown / Unknown-because-insufficient-information / Unknown-in-principle), giving a four-branch taxonomy (insufficient observation, inaccessible information, unresolved inference, theoretically unknowable), and poses a new research question: should KnowledgeOS represent not merely what is known but the epistemic status of what cannot currently be known — and why?"

## Notes for P3
- **Nine of the ten rows carry `label_confidence: UNCERTAIN`** with an `unknown_candidate.candidate_of` pointer back to `five-gap-taxonomy` (or, in one case, to `decision-readiness-stop-condition-model`) — only the very first row (S0807's original five-member formalization) is SURE. This is unusually low-confidence for a label with this many rows; P3 should weigh this label's entire evidentiary base accordingly, since most of the "evidence" here is itself provisional attribution, not confirmed content.
- This label's own rows document a rapid internal evolution/retraction chain within a single batch (B0020, 2026-08-26/27): S0807 proposes the five-member Gap taxonomy, then S0808 explicitly retracts freezing that vector (arguing category conflation and non-orthogonality) in favor of a typed-set model, and further demotes Δ_Decision specifically. This reads as a genuine, source-documented "the model evolved fast" case rather than a data-quality problem — but P3 should treat "five-gap-taxonomy" as historically significant/superseded-in-spirit by its own later rows (S0808, S0810) rather than as the corpus's settled position, even though no formal `lifecycle_evidence.retracted_by` entry captures this.
- `files_touching` lists four source_ids (S0820, S0829, S0839, S0847) that do not appear in any of this label's ten `family.rows` — flagged as a data point for P3, not resolved here.
- The single_candidate_flag (S1432, a later 14-category theory-gap taxonomy) explicitly raises the question of whether that later, richer taxonomy supersedes, coexists with, or is unrelated to this five-member one — a priority item for P3 to adjudicate given the clear thematic continuity (gap/taxonomy of unknowns).
