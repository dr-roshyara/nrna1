# Model C1/C2 — Evidence Base

**732 C1 primary candidates** (719 main-corpus, `PRIMARY` tier, `initial_primary == engineering_
knowledgeos`; 13 math-lane, `model.primary == "c1"`) and **1 C2 primary candidate** (seq 2330), per
`00_index.md`'s independently re-derived population table. Organized into eight evidentiary clusters,
sequence-ordered within each. Per the methodology note in `00_index.md`, this population's own
tier2-triggered rate (≈83% of the main-corpus rows) is far higher than Model A/B's evidence bases, so
clusters are treated at representative depth rather than file-by-file — every named formalism below
still carries its source `seq`.

---

## Cluster 1 — EKS/PKS/AI-Engineering-Platform baseline (seq 0047–0128)

The corpus's earliest engineering-lineage material: the five-responsibility governance model, the
six-role model, four-session role-model refinement, three successive KnowledgeOS product/DDD
architecture revisions (v2.0 → v3.0, the second an explicit same-thread self-correction of the
first), the state-durability/DDD-boundary review, a "modular monolith kernel" POA-vs-DDD hierarchy, an
event-driven-architecture refinement, and the first two explicit Kernel-definition attempts: the
"Linux Analogy" Kernel/OS model (seq 0071, `knowledgeos_kernel_v01`) and "How to Change EKS into
KnowledgeOS Kernel" (seq 0075, a six-criterion kernel-membership test). Seq 0079's "Complex Numbers as
a KnowledgeOS Modeling Tool" is the first candidate mathematical representation for a knowledge state
(complex-valued and vector candidates). Seq 0081 delivers a ten-candidate cross-system kernel list
(C-1..C-10, evidence-graded per system).

## Cluster 2 — The multi-round AI Kernel-Boundary-Discovery cycle (seq 0129–0170)

A dense, adversarial, multi-AI-system cycle explicitly searching for a minimal Kernel: eight named
Kernel capacities K1-K8 (seq 0144, "the strongest kernel-reduction candidate yet"); a distinct
Six-Pillar reduction (Identity/Evidence/Context/Provenance/Contradiction/History, seq 0145); a
Constitutional DSL with quantitative Evidence Weighting (seq 0146, later **explicitly rejected** by
seq 0147's DDD interrogation); the first formal ADR, `ADR-KOS-KERNEL-001` (PROPOSED, seq 0167); ten
full rounds of named, in-text-confirmed AI systems (DeepSeek, Perplexity, Kimi) each proposing and
adversarially testing a different Kernel hypothesis — Admission Boundary, Epistemic Decision Core,
Knowledge Consistency Boundary, Transformation Boundary, Epistemic Accountability Core — culminating
in seq 0157's **first genuine falsification** (the six-part aggregate invariant falsified via a
many-to-many evidence-sharing stress test) and seq 0159's 28-row consolidated status table
(FALSIFIED/STRONGLY SUPPORTED/STRONGLY REJECTED per hypothesis).

## Cluster 3 — External-literature epistemology/logic mining, first wave (seq 0165–0206)

A systematic, book-by-book extraction programme applying a formalized five-level source/hypothesis
classification ladder (seq 0191's "Research Extraction Agent" prompt template, retroactively
explaining the discipline behind every extraction since seq 0171): the Chinese-lens commission (seq
0169, 3836 lines); Tarka-sangraha Nyaya logic (`TARKA-KERNEL-FALSIFICATION-001`, seq 0170); Timothy
Williamson's *Knowledge and Its Limits* (W1-W9, seq 0171); Robert Audi's epistemology (A1-A10, seq
0172); Shieber (KOS-EPI-01..10, seq 0173); a critical-thinking guide (KOS-CT-01..12, seq 0174); a
decision-power text (KOS-DP-01..05, seq 0176); the primary Nyaya-sutra with Vātsyāyana's commentary
(seq 0177); Quine's *Word and Object* (twelve-lens "no new dimension" result, seq 0178); Wittgenstein's
*Tractatus* (seq 0179, later adjudicated at seq 0182 with eleven named candidate laws KOS-L1..L11);
Cavell's ordinary-language philosophy (the knowing-vs-acknowledging distinction, seq 0184); two
independent "Davidson Lens" formalizations (seq 0185/0186); and Chalmers's *The Conscious Mind* mined
twice for DDD/epistemic methodology, explicitly not for consciousness claims (seq 0195/0197).

## Cluster 4 — Causal inference, statistics, and Kernel-candidate proliferation (seq 0207–0294)

A second, more technical wave: EKS baseline reconciliation (seq 0207, an 8-level evidence-priority
hierarchy); causal-inference sources (Pearl-style *What If*, seq 0208-0209); "The Book of Evidence"
(Evidence Acquisition as its own domain, seq 0210); belief-revision reframing (seq 0212); Stigler's
*Seven Pillars of Statistical Wisdom* (seq 0213/0215); a Daoist Ziran-derived three-way challenge to
the Kernel Identity invariant (seq 0214); the "Epistemic Architecture Synthesis" senior-architect
consolidation naming K-1 = KnowledgeAggregate+ConflictRecord+VerificationPort (seq 0216, later flagged
"too large," seq 0218); Rescorla's Bayesian cognitive science (seq 0223); a typed mathematical
epistemic model rejecting a single scalar Knowledge Score (seq 0224); Hidden (Semi-)Markov Model
epistemic-state layers (seq 0225-0226, 0230, 0233-0245); and — starting at seq 0270 — a systematic
critique-and-proliferation cycle running McGinn, Merricks, O'Connor, Shannon/Weaver, and Floridi/
Dretske/Fagin-Halpern-Moses-Vardi through the Kernel question, each delivering a **numbered, distinct**
Kernel-structure candidate (the "eighth," "ninth" in the corpus's own running count) before the next
critique supersedes it — seq 0279's own words: *"I would read more before freezing the Knowledge Space
Kernel."*

## Cluster 5 — The `phase_measure_theory/` arc (seq ≈0283–0520+)

The single largest, most mathematically escalating research arc in the C1 population, produced under
the path prefix `phase_measure_theory/`. Opens with a formal critique of Fagin/Halpern-style Kripke
epistemic-state models (seq 0283-0289, "Fagin as Regime, Not Kernel"), then runs an extended,
repeatedly self-correcting formalization programme numbered by research "Question": Knowledge State
`K_t` is defined and redefined at least six distinct times with different component counts (six-
component seq 0093/0255-family echoes; eight-component seq 0479; the final six-component form
`K_t=(𝒜_t,ℛ_t,ℰ_t,ℋ_t,𝒵_t,ℒ_t)` at seq 0469); "Discrepancy" `Δ_t` replaces an earlier "Distance Vector"
after a **proof that Compare(K,I) is non-symmetric, hence not a metric** (seq 0485); Ideal State `I_t`
is split three ways (`I^K/I^U/I^D`, seq 0476); a full event-sourcing architecture is introduced
(`K_{t+1}=δ(K_t,e_t)`, seq 0481) alongside an explicit, self-caught internal contradiction between its
own transition rules (seq 0481's own record); "Knowledge Ātma" (𝒜_K) is introduced as a persistent-
identity concept distinct from the knowledge state itself (seq 0500), reconnected to the corpus's
earlier Kernel-discovery thread (seq 0504), then split into Human/Knower Ātma vs. Knowledge-OS Ātma
(seq 0505); "Moksha" is reframed from a quantity claim to an epistemic-regime phase transition (seq
0501). **This arc runs in explicit parallel with, and by seq 0513's own admission is never fully
reconciled against, the `kernel/` subdirectory's overlapping research** (Cluster 6) — the corpus's own
words: *"What is the true chronological relationship between the kernel/ and phase_measure_theory/
directories?"*

**A sub-thread within this cluster (seq 0429–0456) runs the Sañjaya/Sārathi/Krishna architecture in
explicit narrative parallel with Model A's own gita-primary evidence at the same approximate seq range
— but classified `engineering_knowledgeos`, not `gita`, and containing its own independently-developed
formalizations** (`Sanjaya_K` six-component epistemic status, seq 0431; an eleven-function Sārathi
role list, seq 0440; a three-tier retrospective status assessment of the whole architecture, seq
0445). **Per the authorization's independence rule, this cluster's content is described here strictly
on its own terms, from its own `engineering_knowledgeos`-tagged evidence — no claim from Model A's own
concept register is imported or compared against it.**

## Cluster 6 — The `kernel/` subdirectory lineage and the sole C2 candidate (seq ≈2296–2354)

A late, separately-organized research region (172 files on disk, of which seq 2330 alone is
`epistemic_knowledgeos`-tagged; the immediate surrounding seqs 2305–2321 are `meta_research`, 2322–
2324 are `cross_model`, 2328/2332+ are `engineering_knowledgeos`). Seq 0311's own "Kernel Corpus —
Dependency & Response-Chain Map" reports this subdirectory (141 documents at the time of that census)
as "almost entirely UNLINKED — only 1 of 141 documents cites another." Seq 2322 explicitly **names and
defines all four models** (C1 Engineering/EKS-PKS, C2 Epistemic KnowledgeOS, M Mathematical substrate,
G Gita lens) — a `cross_model`-tagged bridge document, not C1/C2 evidence itself, cited here only as
context. Seq 2323 is a genuine oddity: primary-corpus commentary written **about this very
`three_model_convergence/` reconstruction project**, snapshotted mid-pass. The sole C2 file, seq 2330
("Review: Relational Structure as the Mathematical Core..."), delivers a nine-component core structure
`𝒞=(D,P,T,C,I,E,R,H,Θ)`, regimes as structure-adding functors, Roberts's measurement-governance gate,
Shani's "no regime may redefine a core concept" invariant, and a further DDD Kernel definition — full
content given in `02_concept-register.md` §M. See `04_boundary-observations.md` for the bounded
C2-population investigation across this cluster's neighboring `meta_research`/`cross_model` rows.

## Cluster 7 — Reiter/situation-calculus formal-methods series, "Step-292" (seq 2332–2345)

A tight, `engineering_knowledgeos`-tagged formal-methods series applying Reiter's situation-calculus
apparatus (frame problem, successor-state axioms, regression/progression, Golog) against the corpus's
own already-tracked open questions (`Poss≠Qualify`, `δ` as a successor-state construction, an
operation registry with 8/22 signatures defined and 0/22 bodies). Delivers a consolidated negative
result at seq 2344: nine of ten mandatory negative tests (P1–P10) refuted, "none because Reiter is
wrong" — i.e. the framework survives its own falsification attempts, but does not by itself resolve
the corpus's open questions.

## Cluster 8 — Math-lane C1 candidates (13 files)

Thirteen `c1`-tagged rows within the separately-reconciled 401-file mathematical lane (Model B's own
`04_boundary-observations.md` already named these as out-of-Model-B-scope; opened here for the first
time): an early four-tag provenance-taxonomy correction (M0013); a run of external-epistemology-source
extractions structurally parallel to Cluster 3 above but occurring inside the math lane's own
numbering — Plato via White (M0024), Audi (M0025), Rescorla-style Bayesian cognitive science (M0026),
a gap-driven reading roadmap (M0027), a second Plato pass (M0028), Davidson (M0029); a major
cross-lineage bridge reviewing the main corpus's own "Theory v1.1" (M0050); two worked-example "Theory
Part XXI-A" documents (M0343/M0344, one revising the other into a full decision-outcome extension); an
architectural formulation distinguishing Knowledge Graph (representation) from KnowledgeOS Theory
(epistemic computation) (M0352); a four-level Zoom-In/Zoom-Out architecture correction (M0373); and —
notably — **M0377, explicitly Gītā Chapter-3 content ("Dual Fact-Finding Architecture: State vs
Action"), tagged `c1` rather than `g` within the math lane's own convention**, formally synthesizing
two files (M0375/M0376) that are themselves `g`-tagged Gita content per Model B's own boundary
accounting.

---

## Traceability summary (independently derived, not estimated)

- **Main-corpus C1 primary**: 719 rows, seq 0047–2354 (non-contiguous — interleaved throughout with
  `meta_research`/`gita`/`cross_model`/`mathematics`/blank rows belonging to other lineages).
  Tier2-triggered: 600/719 (≈83%). Importance: 509 critical · 96 high · 22 medium · 60 low · 11 minor ·
  7 none · 6 moderate · 4 important · 2 skip · 2 standard.
- **Math-lane C1 primary**: 13 rows, M0013–M0377 (non-contiguous).
- **C1 secondary-tagged elsewhere**: 2 main-corpus (seq 0052, 0060 — both early role-separation/
  DDD-verification files) + 37 math-lane rows (not opened as C1 evidence — see
  `04_boundary-observations.md`).
- **C2 primary**: 1 row (seq 2330), read in full from raw source, not digested.
- **C2 secondary-tagged elsewhere**: 0, in either corpus.

No duplicate/near-duplicate row was double-counted as an independent finding in the cluster narrative
above — every explicitly-flagged duplicate encountered during digest reading (e.g. seq 0059, 0149,
0181, 0198, 0217, 0236, 0246, 0248, 0250, 0290-0291, 0367, 0379, 0381, 0387-0388, 0394, 0404, 0412,
0422, 0426, 0432, 0448, 0465, 0473, 0495, 0502, and others) is named once, against its canonical
source, per the `repeats`/`supersedes` fields native to this schema generation (MD-009's own
discipline for `source_role: DUPLICATE_REPRODUCTION`-equivalent rows in the pre-0024 and post-0024
schema alike).
