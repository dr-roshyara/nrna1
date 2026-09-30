# independent-evidence-combination-rule

**Scope(s):** OBJECT · **Row count:** 31 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A1..A10`; `InferenceResult=(H,Probability,Model,ModelVersion,InputKnowledgeSnapshot,Assumptions)`; `LR_1=9.5, LR_2=4.5, LR_3=0.0625`; `P(H|E1)~0.905, P(H|E1,E2)~0.977, P(H|E1,E2,E3)~0.728`; `ReasoningContract` · **Aliases:** Step 25C.2: Independent Evidence Combination
**Candidate group membership (NOT an identity claim):**
- G0150: co-listed with `candidate-evidence-algebra-comparison` — explicit agent-stated uncertainty (batch B0021): "S0894's Step 25C.2: a fully worked numerical Bayesian likelihood-ratio experiment... hands off to Step 25C.3 (Evidence Dependence and Information Value)." (full note in Sources below). Relationship not yet decided (P3).
- G0151: co-listed with `evidence-dependence-information-value` — explicit agent-stated uncertainty (batch B0021): "S0895's Step 25C.3: formalizes InformationGain via Shannon entropy reduction and ValueOfInformation via a decision-theoretic expected-utility formula... hands off to Step 25D Formal Zero Algebra." Relationship not yet decided (P3).
- G0751: co-listed with `audi-epistemic-grounding-model` and `epistemic-acceptance-commitment` — labels share the notation "A1..A10". Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0021, scope OBJECT, relation_to_existing="POSSIBLY:candidate-evidence-algebra-comparison" — "S0894's Step 25C.2: a fully worked numerical Bayesian likelihood-ratio experiment (0.5->0.905->0.977->0.728 across E1/E2/E3) grounding independence-vs-identity, idempotency, EvidenceCount!=EvidenceStrength, ArtifactMultiplicity!=InformationMultiplicity, non-monotonic-inference/cumulative-history, Inference-as-projection-of-KnowledgeState, a reproducible InferenceResult record, NotEveryPropositionRequiresStatisticalInference with four reasoning modes and a governed ReasoningContract, NumericalPrecision!=EpistemicValidity, A1-A10 properties, ModelPlurality, and a candidate evidence algebra E_H feeding Assessment then Inference(...); hands off to Step 25C.3 (Evidence Dependence and Information Value)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0894 §"When several pieces of evidence support the same proposition, how should KnowledgeOS combine them? We must avoid the very tempting but incorrect rule: more sources \\Rightarrow more certainty. That is only true under specific assumptions."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0894 §"The architecture now becomes very clean ... Evidence ↓ Evidence Graph ↓ Epistemic Assessment ├──► Rule Engine ├──► Statistical Engine ├──► Causal Engine ├──► Logical Engine └──► Semantic/Fuzzy Engine ↓ Inference ↓ Decision..."]
- CANDIDATE-FORMAL-BIRTH: [S0894 §"A surprising consequence ... the real input to inference is not \\{E_1,E_2,\\ldots,E_n\\}. It is closer to Evidence Graph where we know relationships such as E_1\\perp E_2 or E_2=f(E_1)..."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0894 §"Start with a controlled proposition ... H=\"Rollback will succeed if executed.\" P(H)=0.5. This is an experimental prior, not a KnowledgeOS principle..."]
- CANDIDATE-GOVERNANCE-BIRTH: [S0894 §"What has 25C.2 actually established? A1 Duplicate idempotency E\\oplus E=E. A2 Independent evidence can accumulate..."]

## Lifecycle
last_seen: S0894. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, and contested_by_own_contradiction_type is false. All 31 rows trace to a single source document (S0894, Step 25C.2). DORMANT is a heuristic based on how long ago that source was last used, not a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0894 (x4) |
| informal_meaning | PRESENT | S0894 (x3) |
| formal_definition | PRESENT | S0894 (x4) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0894 (x2) |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0894 (x5) |
| examples | PRESENT | S0894 (x2) |
| warnings | PRESENT | S0894 (x3) |
| experiments | PRESENT | S0894 (x9) |
| open_questions | PRESENT | S0894 (x3) |

## Rationale
This object addresses how KnowledgeOS should combine multiple pieces of evidence supporting the same proposition, explicitly warning against the tempting-but-incorrect rule "more sources ⇒ more certainty" [S0894]. Four distinct arguments ground the rationale: (1) source reliability belongs inside the statistical likelihood model (P(E|H), P(E|¬H)) rather than as an arbitrary bolt-on score, closing the gap of how to handle an uncalibrated evidence source without simply discarding it [S0894]; (2) a full reproducible InferenceResult record (snapshot + model + assumptions) is argued to be strictly stronger than a bare stated probability, because it lets KnowledgeOS answer "why did it say 72.8%?" precisely rather than appeal to "the AI estimated it" [S0894]; (3) NotEveryPropositionRequiresStatisticalInference — a normative question (e.g. mandatory Architecture Board approval) should use RuleEvaluation, not a computed probability, rejecting the idea of a universal Bayesian kernel and motivating four distinct reasoning modes (Deductive/Normative, Statistical, Causal, Semantic/Fuzzy) [S0894]; (4) two different priors receiving identical evidence can legitimately produce different posteriors, so a probability is not a property of evidence alone but of Inference(E,Model,Prior,Assumptions) — motivating ModelPlurality (preserving multiple parallel inference results) rather than treating one model's output as universal truth [S0894].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
All 31 rows are `[S0894]`, `docs/knowledgeos/brainstorming/phase_measure_theory/20260827-182835_step-025c-2-independent-evidence-combination.md`, scope=OBJECT:
1. types=[WARNING, OPEN-QUESTION] — "Frames Step 25C.2's question, warning against the tempting-but-incorrect rule 'more sources ⇒ more certainty', true only under specific assumptions."
2. types=[EXPERIMENT] — "Works a fully numerical Bayesian likelihood-ratio calculation (LR1=9.5) yielding P(H|E1)≈90.5% under an explicit experimental prior, explicitly not a KnowledgeOS principle."
3. types=[RESTATEMENT] — "Confirms via the numerical example that Probability≠KnowledgeState — a computed posterior is statistical inference under model assumptions, not epistemic truth, validating the layered architecture from 25C.1."
4. types=[EXPERIMENT] — "Computes the legitimate independent-evidence-combination case: LR12=LR1·LR2=42.75 yielding P(H|E1,E2)≈97.7%, demonstrating substantial legitimate confidence increase under genuine independence."
5. types=[WARNING, COUNTEREXAMPLE] — "Shows that applying LR1·LR2 to a copied (E2=f(E1)) evidence pair manufactures false certainty mathematically — IndependentEvidence must be established, not assumed."
6. types=[EXPERIMENT] — "Numerically confirms the idempotency requirement: a perfect duplicate provides zero additional posterior shift."
7. types=[FORMALIZATION] — "States that the true input to inference is the Evidence Graph (relationships, not a bare set), classifying three information-gain cases (duplicate=0, dependent=less than naive, independent=>0) as the beginning of an evidence-combination theory."
8. types=[EXPERIMENT, EXPERIMENTAL-RESULT] — "Computes contradictory-evidence-adjusted posterior P(H|E1,E2,E3)≈72.8% while requiring the epistemic Support/Challenge structure be retained alongside the scalar — the critical result that the statistical result and qualitative epistemic state must coexist."
9. types=[EXAMPLE] — "Constructs a worked full record combining EpistemicAssessment and StatisticalInference (evidence roles, conflict flag, model, dependency assumptions), far richer than a bare confidence percentage."
10. types=[ARGUMENT] — "States that source reliability belongs inside the statistical likelihood model (P(E|H), P(E|¬H)) rather than as an arbitrary bolt-on score."
11. types=[EXPERIMENT] — "Reaffirms EvidenceCount ≠ EvidenceStrength using a strong-single-test-vs-ten-weak-statements experiment."
12. types=[EXPERIMENT] — "Contrasts a common-cause-dependent evidence set (three reports from one dashboard, one underlying source) with a genuinely independent evidence set (three separate physical systems), showing only the latter legitimately increases support."
13. types=[DISTINCTION] — "Distinguishes ArtifactMultiplicity from InformationMultiplicity as an epistemic identity problem, not merely database deduplication."
14. types=[EXPERIMENTAL-RESULT] — "Traces the full sequential posterior evolution (0.5→0.905→0.977→0.728), confirming non-monotonic inference alongside cumulative evidence history — InferenceState is revisable, EvidenceHistory is cumulative, matching 25A.3's earlier result."
15. types=[EXPERIMENT] — "Works a retraction experiment where E3 is invalidated (not erased), triggering a recomputed posterior returning toward 97.7% while the history records why."
16. types=[INVARIANT] — "States the invariant Inference is a projection of KnowledgeState, not the KnowledgeState itself — requiring recalculation whenever the underlying KnowledgeState is revised (e.g. via retraction)."
17. types=[DEFINITION] — "Defines a full reproducible InferenceResult=(H,Probability,Model,ModelVersion,InputKnowledgeSnapshot,Assumptions) record instead of storing a bare probability."
18. types=[ARGUMENT] — "Argues the InferenceResult record enables answering 'why did KnowledgeOS say 72.8%?' with a precise reconstruction (snapshot+model+assumptions), far stronger than 'the AI estimated it'."
19. types=[FORMALIZATION] — "Defines the inference contract Inference(K,H,M,C)→R as a clean DDD/application boundary."
20. types=[ARGUMENT, DEFINITION] — "Argues NotEveryPropositionRequiresStatisticalInference (a normative approval requirement should use RuleEvaluation, not probability), reinforcing the rejection of a universal Bayesian kernel, and defines four reasoning modes (Deductive/Normative, Statistical, Causal, Semantic/Fuzzy)."
21. types=[DEFINITION, CONSTRAINT] — "Defines a governed ReasoningContract (six fields) preventing an LLM from casually selecting whichever reasoning mechanism produces its desired answer."
22. types=[WARNING, PRINCIPLE] — "States NumericalPrecision ≠ EpistemicValidity: an LLM-stated percentage with no model/inputs/assumptions/calculation is merely an unsupported assertion, requiring semantic provenance on every generated number or it remains CandidateInference."
23. types=[INVARIANT, GOVERNANCE] — "Consolidates ten properties A1-A10 established by Step 25C.2: duplicate idempotency, independent accumulation (conditional), non-double-counting of dependent evidence, visible contradiction, model/assumption/snapshot-dependence of inference, recomputation on retraction, invalid unmodeled numerical output, and not-all-questions-are-statistical."
24. types=[FORMALIZATION] — "Consolidates the candidate evidence algebra E_H=(Support,Challenge,Unknown,Dependency,Temporal,Provenance)_H feeding Assessment_H=A(E_H,C) then Inference_H=M(Assessment_H,K_t,C) under an explicit model M."
25. types=[CONCEPT], completeness=INFORMAL-ONLY — "Diagrams the clean five-engine architecture (Rule/Statistical/Causal/Logical/Semantic-Fuzzy) all feeding a common Inference→Decision path, with the kernel agnostic to which mathematics is used as long as the contract is explicit."
26. types=[DISTINCTION] — "Distinguishes commutative EvidenceAggregation from order-sensitive WorldEventComposition — another reason the temporal model must remain separate from the evidence-aggregation algebra."
27. types=[ARGUMENT, PRINCIPLE] — "Shows two different priors on identical evidence legitimately produce different posteriors, requiring KnowledgeOS preserve multiple parallel inference results (ModelPlurality) rather than treat one as universal truth."
28. types=[OPEN-QUESTION] — "Identifies ModelSelection (choosing which reasoning engine applies to which question type) as a distinct next problem, essential to Zero/Lord/Sārathi."
29. types=[EXPERIMENTAL-RESULT] — "Verdicts Step 25C.2 PASS with the qualification that Bayesian mathematics is an excellent specialized inference engine only after dependency/provenance/temporal-scope/assumptions are modeled — never the epistemic kernel itself."
30. types=[FORMALIZATION] — "Consolidates the current architecture KnowledgeOS=EpistemicKernel+EvidenceGraph+InferenceEngines+DecisionMathematics with each layer's concrete inputs/outputs specified."
31. types=[FUTURE-RESEARCH, OPEN-QUESTION] — "Proposes Step 25C.3 (Evidence Dependence and Information Value): formalize conditional information I(E2;H|E1) and connect to VOI(a), aiming for a mathematically rigorous bridge Evidence→Information→Investigation→Zero/Lord." Lineage claim: SOURCE-CLAIMED-CONTINUATION targeting "step-025c-3 (S0895)."

## Notes for P3
This is a single-document, densely-worked numerical case study (all 31 rows from S0894) that builds a complete argument arc: problem framing → worked Bayesian experiments (legitimate combination, manufactured-certainty counterexample, idempotency, common-cause dependence, retraction) → consolidated A1-A10 invariant list → candidate evidence algebra → architecture diagram → explicit handoff to Step 25C.3. Group G0150 directly connects this label to its own explicit successor step (`evidence-dependence-information-value`, S0895), already anticipated in row 31's lineage claim — P3 should treat these two labels as a strongly-linked sequential pair. Group G0751's connection via shared "A1..A10" notation to `audi-epistemic-grounding-model` and `epistemic-acceptance-commitment` is purely notational (same numbering scheme reused for a different property list) — P3 should verify whether these are truly related or simply reuse a common enumeration convention, since a shared A1-A10 label scheme does not by itself imply shared content. `family.files_touching` includes S0895, which does not appear in this label's own 31 rows (S0895 is the anticipated successor document, consistent with row 31's forward reference).
