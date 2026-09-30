# dhatu-extraction-pipeline-research-design

**Scope(s):** `OBJECT` · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Akshara decomposition -> Morphology -> Statistical inference -> Dhatu candidate` · **Aliases:** `Swar Gyan orthographic decomposition + Paninian statistical hybrid`
**Candidate group membership (NOT an identity claim):**
- **G0682**: [`dhatu-extraction-pipeline-research-design` · `sanskrit-grammar-lens`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0013, source S0495) named these as alternative candidates for one piece of evidence. why_uncertain: Proposes that a Dhatu may capture transformation/procedure/relationship/constraint/pattern/inference rather than a noun/fact, which may be the same underlying Dhatu research direction indexed under dhatu-extraction-pipeline-research-design or sanskrit-grammar-lens, or a distinct conceptual point about what Dhatu extraction is 'for'; not confirmed.
- **G0683**: [`dhatu-extraction-pipeline-research-design` · `sanskrit-grammar-lens`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0013, source S0496) named these as alternative candidates for one piece of evidence. why_uncertain: Reframes Dhatu as a generative-relation/process concept via the kama discussion; relationship to the indexed Dhatu NLP-pipeline object or Sanskrit-grammar lens is not established by this file.

**Single-candidate uncertainty flags:**
- `S0499` (batch B0013): Invokes the 'Dhatu/generative lens' to argue knowledge is process not object; relationship to the indexed Dhatu NLP-pipeline object is only thematic.
- `S0501` (batch B0013): Applies the Dhatu lens to distinguish state knowledge from transformation knowledge; thematically related to but not identical to the indexed Dhatu NLP-pipeline object.
- `S0501` (batch B0013): Same Dhatu-lens exploration thread.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): A concrete NLP research design (not itself a KnowledgeOS architecture claim) using the Hindi primer 'Swar Gyan' as a source of deterministic orthographic decomposition (akshara/vyanjan/svar/matra/samyuktakshar/ardhakshar), explicitly distinguished from actual dhatu (Sanskrit verbal root) extraction which requires morphology/lexical knowledge/grammar/derivational history/semantic compatibility/contextual disambiguation; proposes a layered hybrid pipeline (deterministic grammar layer -> statistical/Bayesian/CRF/clustering/dimensionality-reduction/supervised-ML layer -> linguistic validation), a four-model comparison experiment (surface-word-only vs akshara-decomposition vs +phonology/morphology vs +context/semantics, measured by accuracy/precision/recall/F1/calibration/error-by-morphological-class), and a graph/topological reframing where a dhatu is a latent structural center of a transformation family (kri -> karoti/krita/karana/karya) rather than a dictionary lookup; the guiding principle is that the ML model proposes candidates while the deterministic linguistic system validates -- 'probabilistic inference should not silently become authoritative knowledge.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0480] §"The method illustrated in Swar Gyan is not actually dhatu extraction in the classical Sanskrit grammatical sense. It is primarily orthographic decomposition / compositional extraction. ... exactly the kind of pre-processing layer we could use before applying statistical or machine-learning methods."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0480] §"Can we discover families of words without telling the algorithm their roots? ... clustering should produce candidates, not authoritative roots."
- CANDIDATE-FORMAL-BIRTH: [S0480] §"W = A1 A2 ... An where each Ai is an akshara unit, itself decomposable into consonant + vowel + matra + virama/halant + conjunct structure. This gives us a deterministic feature extractor."
- CANDIDATE-OPERATIONAL-BIRTH: [S0480] §"Model A: surface word -> dhatu ... Model D: akshara + morphology + context + semantic features -> dhatu. Then measure: accuracy, top-1/top-5 accuracy, precision, recall, F1, calibration, uncertainty, error by morphological class. This would tell us whether the Swar Gyan/Paninian decomposition actually contributes predictive information."
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0492`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0480, S0480, S0480 |
| type_signature | PRESENT | S0492 |
| invariants | PRESENT | S0480 |
| dependencies | PRESENT | S0480, S0480, S0480, S0480, S0480, S0480, S0480, S0480, S0481, S0492 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0480, S0480 |
| examples | PRESENT | S0480, S0480, S0480, S0480 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0480, S0481 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0480]` types=[CORRECTION, DISTINCTION] scope=OBJECT — "Explicit source-fidelity correction: Swar Gyan teaches orthographic decomposition (character/matra/conjunct construction) as a deterministic pre-processing layer, not dhatu extraction itself, which additionally requires morphology, lexical knowledge, grammar, derivational history, semantic compatibility and contextual disambiguation." (anchor: "The method illustrated in Swar Gyan is not actually dhatu extraction in the classical Sanskrit grammatical sense. It is primarily orthographic decomposition / compositional extraction. ... exactly ...")
- `[S0480]` types=[FORMALIZATION] scope=OBJECT — "Formalizes a word as a sequence of akshara units each decomposed into consonant/vowel/matra/virama/conjunct structure, giving a deterministic feature extractor to feed downstream statistical models." (anchor: "W = A1 A2 ... An where each Ai is an akshara unit, itself decomposable into consonant + vowel + matra + virama/halant + conjunct structure. This gives us a deterministic feature extractor.")
- `[S0480]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Bayesian dhatu-inference model P(Dhatu|Word,Context) yielding a probability distribution over candidate roots rather than a single deterministic answer, described as more epistemically appropriate for KnowledgeOS." (anchor: "P(D | W,C) proportional to P(W | D,C) P(D | C) ... Instead of Root = root-gam, we can have: root-gam 0.91, root-gai 0.05, other 0.04. That is much more appropriate for KnowledgeOS.")
- `[S0480]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "CRF/HMM sequence-labeling proposed for morphological segmentation (character sequence -> ROOT/TRANSFORM/SUFFIX labels), worked through a concrete example segmenting a Sanskrit verb form." (anchor: "A Conditional Random Field (CRF) would be a very natural classical statistical approach. ... gachchhati -> [gachchh][ti] -> [gachchh] root/stem candidate, [ti] verbal ending.")
- `[S0480]` types=[CONCEPT, EXAMPLE] scope=OBJECT — "Unsupervised clustering of morphologically-related word forms (gachchhati/agachchhat/gamishyati/gatah/gamanam) proposed to discover latent root families, explicitly bounded as producing candidates only, never authoritative roots." (anchor: "Can we discover families of words without telling the algorithm their roots? ... clustering should produce candidates, not authoritative roots.")
- `[S0480]` types=[EXPERIMENT] scope=OBJECT — "Formal four-model ablation experiment isolating whether akshara decomposition, phonology/morphology, and context/semantics each contribute measurable predictive value for dhatu recovery, with a full evaluation-metric list." (anchor: "Model A: surface word -> dhatu ... Model D: akshara + morphology + context + semantic features -> dhatu. Then measure: accuracy, top-1/top-5 accuracy, precision, recall, F1, calibration, uncertaint...")
- `[S0480]` types=[PRINCIPLE] scope=THEORY-LEVEL — "Restates the batch's recurring propose-vs-validate boundary in this linguistic context: statistical models propose candidate dhatus; a deterministic linguistic/knowledge system validates them, never the reverse." (anchor: "statistical model -> CANDIDATE -> NOT AUTHORITY. The ML model should propose. The deterministic linguistic/knowledge system should validate. ... Probabilistic inference should not silently become a...")
- `[S0480]` types=[CONCEPT, EXAMPLE] scope=OBJECT — "Topological reframing: a dhatu (e.g. root kri, generating karoti/krita/karana/karya) is modeled as a latent structural center of a graph of surface-form transformations, analyzable via graph clustering/community detection/embeddings/centrality/manifold analysis rather than treated as a static dictionary lookup." (anchor: "What structural features remain invariant across the transformation family? ... The dhatu becomes a latent structural center, rather than merely a dictionary lookup.")
- `[S0481]` types=[EXPERIMENT] scope=OBJECT — "Proposes a deliberately label-blind experiment: give the algorithm only raw characters/positions/boundaries, let it discover latent word families statistically (frequency/co-occurrence/transition matrices -> clustering -> latent units), then compare the independently discovered structure against traditional dhatu theory as a hypothesis test, rather than assuming dhatu labels from the start." (anchor: "Give the algorithm only: Unicode characters + character positions + word boundaries + document/page boundaries. Then ask: Can the structure of the language be rediscovered statistically? ... Does t...")
- `[S0492]` types=[CONCEPT, EXTENSION] scope=THEORY-LEVEL — "Combines three research lenses into a single pipeline: the Dhatu/linguistic lens (structural meaning expressed by language), the critical-thinking lens (which claims/evidence/assumptions/inferences are actually justified), and the Zero lens (what remains after removing language/rhetoric/terminology/representation), yielding Text -> SemanticStructure -> EpistemicStructure -> InvariantStructure, described as an actual 'Knowledge Extraction Engine'." (anchor: "Now we have three powerful operations. Dhatu / linguistic lens: What is the structural meaning expressed by the language? Critical-thinking lens: What claims, evidence, assumptions and inferences a...")

## Notes for P3
- This label carries 2 candidate-group memberships beyond G0759 (see group list above) — a comparatively dense set of mechanical cross-links, which may make it a useful anchor point for P3 reconciliation, but none of these links are identity claims and each must be assessed on its own evidence.
- 3 single-candidate uncertainty flag(s) recorded against this label by earlier passes (see header) — these are explicit agent-stated uncertainty about a possible relationship to another object, not a finding of this file; P3 should adjudicate them directly.
