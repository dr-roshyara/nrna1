# evidence-gated-hybrid-temporal-inference-hypothesis

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EG-HTI`, `HMM subset HSMM` · **Aliases:** `inference ladder`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope THEORY-LEVEL): Research hypothesis (Evidence-Gated Hybrid Temporal Inference) proposing KnowledgeOS use a coarse-to-fine inference ladder (Rules -> HMM -> HSMM -> Deep Bayesian/temporal inference -> Human/Governance) rather than one universal model, with lenses (DDD/Zero/Nyaya/etc.) acting as complexity-reduction and escalation-trigger mechanisms; HMM is treated as a special case of HSMM (geometric duration), used as the cheap default, escalating to HSMM only for states with demonstrably non-geometric duration, and to deep/human review only when risk justifies the cost.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0467 §"Use the cheapest lens/model that can answer the current question with sufficient accuracy, and escalate only when the evidence shows that the cheap model is inadequate. ... coarse-to-fine, evidence-gated, hybrid temporal inference architecture."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0467 §"Use the cheapest lens/model that can answer the current question with sufficient accuracy, and escalate only when the evidence shows that the cheap model is inadequate. ... coarse-to-fine, evidence-gated, hybrid temporal inference architecture."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0467. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0467) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0467 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0467 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0467 |
| dependencies | PRESENT | S0467 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0467 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Reframes the corpus's existing philosophical lenses (DDD/Zero/Vani/Panini/Karaka/Nyaya/Navya-Nyaya/Godel/Escher/Siva-Sakti/Ganesha/Leonardo/Moksha/Negative-Epistemology/Isnad/Witness/Dharma/Wisdom) as a table of computational roles (state-space reduction, missing-prerequisite detection, normalization, feature extraction, justification validation, truth-collapse prevention, invariant preservation, admission gating, context completeness, staleness detection, forbidden-inference-path rejection, provenance quality, escalation decision) -- DDD's bounded-context state reduction alone is quantified as roughly two orders of magnitude before duration modelling is even considered. [S0467] Final compressed formula for the whole hybrid architecture (Gate then Normalize then Select among Deterministic/HMM/HSMM/Deep) bounded by Truth!=Inference!=Evidence!=Authority; the genuinely novel claim is not 'use HSMM' but that KnowledgeOS becomes an adaptive epistemic computation system with an inference ladder (Rules->HMM->HSMM->Deep->Human/Governance) where lenses determine when and why the system climbs it, frozen as the research hypothesis Evidence-Gated Hybrid Temporal Inference (EG-HTI) with four defining properties. [S0467]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0467]` types=[HYPOTHESIS, FORMALIZATION] scope=THEORY-LEVEL — "Core hybrid-architecture thesis: Deterministic Core + Fast Probabilistic Model (HMM) + Selective HSMM + Deep Offline Inference, rather than making HSMM the universal substrate; explicit pipeline Normalize/Filter -> Deterministic Gate -> {Fast Model | HSMM Model} -> Assurance Gate -> {Accept | Deep Inference/Review}." (anchor: "Use the cheapest lens/model that can answer the current question with sufficient accuracy, and escalate only when the evidence shows that the cheap model is inadequate. ... coarse-to-fine, evidence-gated, hybrid temporal inference architecture.")
- `[S0467]` types=[ARGUMENT, EXAMPLE] scope=METHODOLOGICAL — "Reframes the corpus's existing philosophical lenses (DDD/Zero/Vani/Panini/Karaka/Nyaya/Navya-Nyaya/Godel/Escher/Siva-Sakti/Ganesha/Leonardo/Moksha/Negative-Epistemology/Isnad/Witness/Dharma/Wisdom) as a table of computational roles (state-space reduction, missing-prerequisite detection, normalization, feature extraction, justification validation, truth-collapse prevention, invariant preservation, admission gating, context completeness, staleness detection, forbidden-inference-path rejection, provenance quality, escalation decision) -- DDD's bounded-context state reduction alone is quantified as roughly two orders of magnitude before duration modelling is even considered." (anchor: "the lenses themselves become a complexity-reduction mechanism. ... DDD reduces the mathematical state space ... Reducing M from 100 to 10 gives a theoretical transition-space reduction of roughly 100 (before even optimizing duration).")
- `[S0467]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Zero Lens becomes the first computational optimization gate: absence of a constitutive prerequisite (identity/context/evidence/justification/candidate/agreement) short-circuits to INSUFFICIENT_BASIS at near-constant cost, avoiding expensive HSMM computation entirely for cases that fail this cheap check." (anchor: "if prerequisite == absent: return INSUFFICIENT_BASIS. This is O(1) or close to linear validation cost, instead of O(TM^2D). ... Zero becomes the first optimization gate.")
- `[S0467]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Empirical HSMM-activation criterion via KL divergence between observed and geometric-implied state-duration distributions; state-specific hybrid model where only some states (e.g. Proposed/Assured/Operational) get explicit-duration HSMM treatment and others keep the cheaper geometric HMM assumption, reducing computation versus applying HSMM to the whole state space." (anchor: "For each state s, estimate D_s^observed and compare it with the geometric duration implied by the HMM. If KL(...) < epsilon then HMM is probably adequate. ... Only Proposed, Assured, Operational have highly non-geometric durations. Then use: Hybrid: HMM states + explicit-duration states.")
- `[S0467]` types=[FORMALIZATION] scope=OBJECT — "Semantic-normal-form caching optimization (reuse prior inference for semantically equivalent observations) refined by a provenance-aware CacheKey (semantic hash, context, model version, evidence class) so semantically identical but differently-provenanced observations are not invalidly conflated." (anchor: "Once SNF(x)=s we cache x -> s. ... CacheKey = (SemanticHash, Context, ModelVersion, EvidenceClass). ... This prevents invalid cross-context reuse.")
- `[S0467]` types=[FORMALIZATION, ANALYSIS] scope=THEORY-LEVEL — "Final compressed formula for the whole hybrid architecture (Gate then Normalize then Select among Deterministic/HMM/HSMM/Deep) bounded by Truth!=Inference!=Evidence!=Authority; the genuinely novel claim is not 'use HSMM' but that KnowledgeOS becomes an adaptive epistemic computation system with an inference ladder (Rules->HMM->HSMM->Deep->Human/Governance) where lenses determine when and why the system climbs it, frozen as the research hypothesis Evidence-Gated Hybrid Temporal Inference (EG-HTI) with four defining properties." (anchor: "Inference = Gate_{DDD,Zero,Nyaya} o Normalize_{Vani,Panini,SNF} o Select[Deterministic, HMM, HSMM, Deep] ... final acceptance constrained by: Truth != Inference != Evidence != Authority. ... KnowledgeOS becomes an adaptive epistemic computation system ... inference ladder.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
