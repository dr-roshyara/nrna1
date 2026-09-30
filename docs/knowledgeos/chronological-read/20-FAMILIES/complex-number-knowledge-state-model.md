# complex-number-knowledge-state-model

**Scope(s):** OBJECT · **Row count:** 18 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K = S + iA
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1490: co-occurs with `zero-neutral-state-lens` in the same contribution's labels[] 3 times across the corpus.
- G1492: co-occurs with `zero-lord-sarathi-guidance-cycle` in the same contribution's labels[] 2 times across the corpus.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0005, scope OBJECT: "A research hypothesis modeling two orthogonal knowledge dimensions (e.g. semantic state and authority state, or confidence and trust) as the real/imaginary parts of a complex number, extended to a difference operator and a five-dimensional vector generalization; explicitly not to be equated with 'knowledge' itself."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0200 §"K = S + iA where S = semantic state, A = authority state ... What something says and whether the organization is authorized to treat it as true are different dimensions."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0200 §"K = S + iA where S = semantic state, A = authority state ... What something says and whether the organization is authorized to treat it as true are different dimensions."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1116 §"K_t / KnowledgeState row"]

## Lifecycle
last_seen: S1147. Candidate lifecycle: DORMANT.
Evidence: `retracted_by` and `superseded_by` are both empty and `contested_by_own_contradiction_type` is `false` — mechanically this is DORMANT, not a confirmed retirement. However, this label's own rows contain a strong textual claim that the object was abandoned in substance: S1113 states it "was a modeling-tool exploration never carried into kernel or Step eras" and that its notation (K_t) "was later re-coined with a different meaning," registering it as ALT-09 (an alternative, not an adopted path). This textual content reads like a retraction/non-adoption even though the mechanical `lifecycle_evidence.retracted_by` field is empty — see Notes for P3.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1113 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0200 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0200, S1114, S1116, S1121, S1122, S1129, S1142, S1147 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0200, S1142 |
| experiments | PRESENT | S0201 |
| open_questions | PRESENT | S0200 |

## Rationale
The model was introduced (S0200) to keep two knowledge dimensions — "what something says" (semantic state) and "whether the organization is authorized to treat it as true" (authority state) — from being collapsed into a single scalar, using the real/imaginary split of a complex number as the representational device; the same row is explicit that this is a modeling *tool*, not a claim that knowledge *is* a complex number, and that mathematical representation must be derived last (after domain discovery, invariants, and knowledge concepts), never chosen first and used to invent the domain model [S0200].

Its subsequent rationale is almost entirely retrospective/archaeological rather than forward-developing: S1113 records that the model was never carried forward into the kernel or Step eras, that the same notation (K_t) was later independently re-coined with an unrelated, formal meaning, and that this naming collision was caught and recorded during a later delta-pass reconciliation (GN-28). The remaining rows (S1114 onward) are governance/terminology-reconciliation entries whose purpose is to keep the later, adopted formal K_t distinguished in prose from this earlier, historical, never-adopted complex-number sense — i.e., the rationale for this label's continued presence in the corpus is now almost entirely disambiguation, not active development.

`rationale_truncated_count` is 0, so no further rationale-bearing rows are reported as omitted beyond S1113.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (`assumption_register` is empty in the derived data for this label).

## All rows (source_id order)
- [S0200] HYPOTHESIS/FORMALIZATION, scope=OBJECT — Proposes K = S + iA (semantic state as real part, authority state as imaginary part) so the two dimensions are never collapsed into one scalar. (anchor: "K = S + iA where S = semantic state, A = authority state...")
- [S0200] HYPOTHESIS/EXTENSION, review_flag=MATH-QUESTION — Extends the model with a difference operator ΔK = ΔS + iΔA separating semantic conflict from authority conflict as distinct governance problems; noted as compatible with the existing R-CONFLICT concept (lineage: SOURCE-CLAIMED-EXTENSION of R-CONFLICT). (anchor: "ΔK = ΔS + iΔA ... They disagree semantically. from: They agree semantically, but their authority states conflict...")
- [S0200] WARNING/CONSTRAINT, scope=THEORY-LEVEL — Explicitly warns against equating knowledge itself with a complex number; math is a supporting analytical mechanism layered above the domain model, never the domain model itself. (anchor: "Do not make 'complex number = knowledge'...")
- [S0200] EXTENSION/HYPOTHESIS — Generalizes to a 5-dimensional vector K=[S,E,A,P,T]ᵀ (semantic validity, evidence strength, authority, provenance integrity, temporal validity), with any complex-number model as a special two-axis projection; completeness PARTIAL (missing a derivation of which axis pairs are architecturally meaningful). (anchor: "K = [S, E, A, P, T]^T ... complex numbers become a special two-dimensional projection...")
- [S0200] PRINCIPLE/CONSTRAINT, scope=METHODOLOGICAL — States a DDD-priority ordering rule: math representation is derived last, after architecture evidence/domain discovery/invariants/knowledge concepts, never chosen first to invent the model. (anchor: "EKS PKS AIP -> Current architecture -> Domain discovery -> Invariants -> Knowledge concepts -> Mathematical properties -> Mathematical representation...")
- [S0200] FUTURE-RESEARCH/HYPOTHESIS, scope=METHODOLOGICAL — Proposes the first falsifiable experiment: test K=S+iA against real EKS/PKS artifacts; discard the model if domain meaning is lost. (anchor: "I would record this as a research hypothesis, not an architecture decision...")
- [S0201] EXPERIMENTAL-RESULT/LIMITATION, scope=CROSS-OBJECT, also labelled knowledgeos-epistemic-architecture-investigation — Reports that CAPPI's numeric epistemic stack (continuous confidence, Bayesian fusion, scalar quality score) has zero current-architecture support in EKS/PKS/AIP, whose discrete/UNKNOWN vocabularies are philosophically the inverse. (anchor: "CAPPI's numeric epistemic stack (mu/tau/w -> Bayesian fusion -> quality score) has ZERO current-architecture support...")
- [S1113] ALTERNATIVE/RETRACTION, scope=OBJECT — ALT-09: the complex-number model was a modeling-tool exploration never carried into kernel or Step eras; K_t later re-coined with a different meaning; no v0.2 correspondence; collision recorded in GN-28 delta pass. (anchor: "ALT-09 · Complex-number epistemic model")
- [S1114] CORRECTION/DISTINCTION, scope=OBJECT — Identifies three senses of KnowledgeState/K_t: (a) engineering event-flow sense, (b) this complex-number model (ALT-09), (c) the formal Step-era K_t; the formal sense dominates by volume (228/233 carrier files) but is youngest. (anchor: "11 · ADDENDUM (GN-28 delta pass, 2026-08-28) — KnowledgeState/K_t, three senses")
- [S1115] EXTENSION, scope=OBJECT — Adds a K_t naming register for the three senses, graded [RC], grounded in concept-evolution §11 and ALT-09. (anchor: "FA-delta-8 · K_t naming register (three senses)")
- [S1116] DISTINCTION/GOVERNANCE, scope=OBJECT — K_t/KnowledgeState row: L2 sense = state over 8 primitives; L3 = event-flow state; historical sense = this complex-number model; reconciliation D-FA-6: qualified naming. (anchor: "K_t / KnowledgeState row")
- [S1121] GOVERNANCE/DISTINCTION, scope=OBJECT — Decision: unqualified K_t = L2 formal state; this complex-number model is [H] (ALT-09); event-flow usage is L3/engineering; needs ratification. (anchor: "D-FA-6 · Naming-collision register extended to K_t")
- [S1122] DISTINCTION/GOVERNANCE, scope=OBJECT, also labelled zero-neutral-state-lens — Binds K_t terminology alongside the analogous 'Zero' terminology rule; naming similarity is never semantic identity [R — GN-29 rule 6]. (anchor: "4 · Terminology register — collisions bound (D-FA-3, D-FA-6)")
- [S1124] CONSTRAINT, scope=OBJECT, also labelled zero-neutral-state-lens, zero-lord-sarathi-guidance-cycle — Binds book-wide terminology: unqualified K_t = formal state; this model and engineering usage always qualified; terminology conformance table ships with every chapter. (anchor: "7 · Terminology rules (binding, from FA-4)")
- [S1129] CONSTRAINT/DISTINCTION, scope=OBJECT, also labelled zero-neutral-state-lens, knowledge-atma-identity-concept, zero-lord-sarathi-guidance-cycle — Fixes the binding terminology register from ratified FA-4, including this model's required qualified phrasing ("the complex-number K(t) model (ALT-09)"). (anchor: "1 · The binding register")
- [S1142] WARNING/DISTINCTION, scope=OBJECT — A naming caution in the book text itself: warns readers that the book's K_t always means the formal state, distinct from this earlier five-days-prior complex-number model and from engineering "knowledge state." (anchor: "A naming caution")
- [S1147] RESTATEMENT, scope=METHODOLOGICAL — Evidence-map table row: "K(t)=M(t)+iA(t) earlier sense -> ALT-09, 20260821-2325 [H]" among 9 tabulated claim-source-grade rows for book chapter III.3. (anchor: "Evidence map table")

## Notes for P3
- Mechanical `lifecycle_candidate` is DORMANT, but the label's own row S1113 uses language ("was a modeling-tool exploration never carried into kernel or Step eras") that reads, in plain prose, like a non-adoption/retraction — yet `lifecycle_evidence.retracted_by` is empty. This looks like a case where the mechanical retraction-detector may not fire on this phrasing pattern (it may look for an explicit "RETRACTS S0200"-style citation rather than a narrative non-adoption). Worth a P3 check on whether the retraction-detection heuristic should be broadened, or whether DORMANT is in fact the more conservative and correct mechanical call here.
- This label's later history (S1114 through S1147, all batch B0027) is entirely archaeology/terminology-governance work reconciling a notation collision (K_t used for both this early complex-number idea and the later, unrelated formal Step-era K_t) — none of it develops the complex-number idea further. If P3 is tracking "which labels are live design objects vs. which are now purely historical/disambiguation entries," this one belongs firmly in the latter category.
- The group memberships (G1490, G1492) both note mere co-occurrence counts with Zero-lens and Lord/Sarathi labels; given the terminology-register rows above (S1122, S1124, S1129) explicitly bundle K_t's naming rule together with the Zero naming rule in the same governing documents, the co-occurrence is well explained by shared terminology-governance context, not by any deeper conceptual link — flagging this as a plausible explanation for P3, not a decision.
