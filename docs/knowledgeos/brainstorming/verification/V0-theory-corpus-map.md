# V0 — THEORY CORPUS MAP

**Programme:** KnowledgeOS Theory Verification (Master Mandate v1.0, §3 / §16)
**Session:** VERIFY SESSION · **Date:** 2026-08-29 · **Status:** V0 DELIVERED
**Method:** four parallel read-only inventory sweeps over (1) `brainstorming/phase_measure_theory/` (~460 files), (2) the book/model/final-architecture trees under `reviews/synthesis/`, (3) `brainstorming/kernel/` + `reviews/` (~350 files), (4) `architecture/` + `governance/` + `backlog/` + the `kos-v11-ddd` worktree. Sweeps used exhaustive listing + grep for formal density, with selective reading. **This map records where the Candidate KnowledgeOS Theory (CKT) exists and what role each artifact plays. It reconciles nothing. Disagreements are recorded in §5.**

All paths relative to `docs/knowledgeos/` unless stated.

---

## 1. Headline structure of the corpus

The CKT exists in **four strata with different authority**, plus witnesses and provenance:

```text
STRATUM 1  RAW RESEARCH RECORD (non-authoritative, declared)
           brainstorming/phase_measure_theory/ (460 files)  ← primary mathematical mass
           brainstorming/kernel/ (163)  ·  brainstorming/ top-level (102)

STRATUM 2  CONSOLIDATED CANDIDATE MODEL (authorized as canonical model, not as verified theory)
           reviews/synthesis/model/canonical-architecture-v0.2.md (GN-19)
           reviews/synthesis/final-architecture/FA-1..FA-9 (ratified GN-31)

STRATUM 3  CONSTITUTIONAL / GOVERNANCE LAYER (accepted; prose-normative, essentially math-free)
           architecture/…KOS-EP01-Constitution-v1.0.md + Constitutional Invariant Map (INV-KOS-*)

STRATUM 4  EXPOSITION (derived representation, forbidden from exceeding its sources)
           reviews/synthesis/book/ (Edition 1, frozen)  ·  book-edition-2/ (Parts III+II complete)

WITNESSES  reviews/synthesis/analysis/mathematical-tests/*.py (3 scripts + captured outputs)
           tests/experiments/knowledgeos_evidence_calculus_property_tests.csv (repo root)

PRIOR VERIFICATION  reviews/synthesis/analysis/mathematical-verification-report.md (MV-F-1..22, GN-46)
           reviews/kernel/session1 (S1-F001..F040) + session2 (S2 review, verdict DEFER)
```

**Fact with direct consequence for this programme:** the corpus contains **no axiom register** (`AX-*`: zero hits corpus-wide), **no unified transition function** (~25 distinct right-hand sides for `K_{t+1}`), and — despite the folder name — **no measure theory** (deferred by recorded decision on day 1 of `phase_measure_theory` and never resumed; σ-algebra appears once in 460 files, as a bullet).

---

## 2. THEORY-CORPUS-MAP (mandated table)

Evidence-role vocabulary per Master Mandate §2; authority status as **declared by the corpus itself**, not judged by me.

### 2.1 Candidate theory — core mathematical artifacts

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-001 | `reviews/synthesis/model/canonical-architecture-v0.2.md` | The consolidated formal model the book explains (states, ladder, invariants, kernels) | candidate theory | **AUTHORIZED canonical model** (GN-19); md5-pinned | **PRIMARY L1 target** — the single most authoritative statement of the CKT |
| T-002 | `reviews/synthesis/model/invariant-map.md` | Classifies each invariant as invariant vs definition vs analogy vs implementation choice | candidate theory | model-layer, ratified set | Entry point for §J invariant extraction — the only artifact making this distinction |
| T-003 | `reviews/synthesis/model/formalization-fidelity.md` | Tests existence of invariant set I_C with I_C(C)=I_C(F(C)) for Zero/Lord/Sārathi | mathematical derivation | model-layer | L1 derivation-check target |
| T-004 | `reviews/synthesis/model/logical-reconstruction.md` | Logical reconstruction of the model | candidate theory | self-declared **"CANDIDATE MODEL — explicitly NOT the final architecture"** | L1 target; status honesty already on record |
| T-005 | `reviews/synthesis/final-architecture/FA-1-final-architecture-baseline.md` (+FA-2..FA-9) | Ratified architecture baseline with per-claim grades [E]/[IN]/[RC]/[H]/[R]/[U] | candidate theory + governance | **RATIFIED** (GN-31); OQ-1..12 open **by ruling** (D-FA-7) | Grade vocabulary = existing per-claim epistemic status; FA-4 holds `Zero(K,EC)`, `K_t`, and the **rejected** complex model ALT-09 |
| T-006 | `brainstorming/phase_measure_theory/20260826-174215_knowledgeos-the-complete-mathematical-model.md` | Self-titled "Complete Mathematical Model": types, operations, lenses, state, evolution equation, invariants | candidate theory | non-authoritative raw record; **superseded by ~120 later steps** | Best-structured early consolidation; source of the 10-tuple `K_t` |
| T-007 | `phase_measure_theory/# Step 201 — KnowledgeOS Canonical Vocab` + `# Step 202 — Formal Relation Matrix` (raw-titled files) | Canonical Vocabulary Freeze v0.1 (19 definitions with DDD role + invariant) + 22 typed relations | candidate theory | non-authoritative; most recent consolidation attempt | De-facto current canonical **core** of the raw track — vocabulary+relations only, **no theorems, no proofs** |
| T-008 | `phase_measure_theory/20260828-102251_step-031-mathematical-formalization-and-consistency-audit…` | 45 numbered definitions (31.1–31.45): universe, assertion, evidence, derivation, K-state/transition, consistency predicate, **impossibility result**, identifiability, probability layer | candidate theory + mathematical derivation | non-authoritative audit | Most axiom-like artifact in existence; carries its own impossibility result — L1 must check it |
| T-009 | `phase_measure_theory/20260826-1814…181441_question-13` … `question-20` (Q-series Q2–Q24) | `K_t` tuple structure, type system (Q14), transition K_t→K_{t+1} (Q15, densest), lens interaction (Q17), system state S_t (Q20), ideal state + distance (Q11/Q12/Q18/Q19) | candidate theory | non-authoritative | Densest single mathematical stratum; **source of the competing tuple/transition definitions** recorded in §5 |
| T-010 | `phase_measure_theory/20260827-…step-025a…025k` series | Minimal formal state+type system (025a-1), property-based falsification (025a-5), evidence-aggregation algebra (025c/-1/-2/-3), **Zero algebra (025d)**, contract algebra (025e), Lord algebra (025g), Sārathi algebra (025h), state algebra+closure (025k) | candidate theory + mathematical derivation | non-authoritative | The operator-algebra layer of the CKT; 025d is the source of the executed witness T-040 |
| T-011 | `phase_measure_theory/20260827-142250_step-004-the-evidence-aggregation-axioms.md` + step-005 | Evidence aggregation axioms; object-of-aggregation | candidate theory | non-authoritative | Closest thing to stated axioms anywhere; feeds §I evidence model |
| T-012 | `phase_measure_theory/20260828-…step-048` (global invariants), `step-049` (model reduction+computability), `step-051` (executable reference model), `step-146` (golden trace→domain model) | Global invariants with sparse `INV-` IDs; safety vs liveness; primitive identification | candidate theory | non-authoritative | §J invariant extraction + L2 computability claims |
| T-013 | `phase_measure_theory/# Step 158…205` series (raw-titled): 162 invariants · 167 verification obligations · 168 verification lattice · 169 falsification · 186 mathematical validation (10 tests) · 187 authority as math object · 189 formal epistemic state (3 state machines) · 197 conservation laws · 198 composition · 199 uncertainty/probability · 200 coherence test · 203 state-space decomposition · 204 transition algebra · 205 aggregate derivation | Late-stage formal consolidation of state machines, transition algebra, conservation laws | candidate theory + mathematical derivation | non-authoritative | The most recent formal statements; Steps 203–205 are the closest to a transition calculus; **filename hygiene hazard** (no `.md`, `#` and spaces in names) |
| T-014 | `phase_measure_theory/20260828-101458_step-027` + `step-082` + `20260826-000209/000339_conditional-evidence…v1/v2` | Uncertainty calculus: probability, confidence, belief, evidence weight; uncertainty propagation; conditional evidence combination (highest Bayesian density) | statistical model | non-authoritative | **PRIMARY L1-Statistical target** — the surviving uncertainty layer (Bayesian/belief-function, not measure-theoretic) |
| T-015 | `phase_measure_theory/20260828-1204…step-067` (epistemic type system), `step-060` (knowledge-state ordering), `step-069`+`step-089` (computability, decidability limits) | Type system; partial order on knowledge states; computability/decidability claims | candidate theory | non-authoritative | §B type checking + L2 computability claims |
| T-016 | `brainstorming/20260822-154923-snf-measurement-framework-mathematical-review.md` + `kernel/20260822-161933-snf-formula-…` + `reviews/20260822-1415-KOS-EP01-Sanskrit-Word-Order-Invariance-Semantic-Normal-Form.md` | SNF (Semantic Normal Form) formula and measurement framework — the corpus's only explicit formula-with-components measurement construct | candidate theory + statistical model | non-authoritative (research); EP01 lens extraction ratified as lens only | L1-Statistical: measurement-theoretic validity (links to Roberts extraction T-025) |
| T-017 | `brainstorming/20260823-103606-kernel-eight-capacities-k1-k8…` + `20260823-104148-constitutional-knowledge-engine-six-pillars-and-state-machine.md` | K1–K8 kernel capacities; six pillars + state machine | candidate theory | non-authoritative | Kernel-side counterpart of the model's "three kernels" (M₄₉/OQ-2) — cross-artifact consistency target |
| T-018 | `phase_measure_theory/gita_chapter4/# KnowledgeOS Mathematical Derivation fr.md` | Verse-by-verse formal derivation; "Complete Mathematical System"; "Central Equation (Gita 4.42)" | candidate theory + historical evidence | non-authoritative | Provenance of the three-lens operators; L1 must separate the derivation from its inspirational frame |

### 2.2 Existing verification & falsification assets (prior art this programme builds on, and must independently re-check)

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-020 | `reviews/synthesis/analysis/mathematical-verification-report.md` (58 KB) | **MV-F-1…MV-F-22 register.** Verdicts on record: load-bearing math (I-5/I-6, covering relation I-12/A6, conjunction law, non-scalar gap operator, stratification loop) "sound as stated, none deep"; **"the formal-object layer is signatures without constructions"** — η, Learn, ladder transition calculus, policy-version calculus, r↔P typing map **UNDER-SPECIFIED**; EXP-01 negative verdict proven; statistics "**STATISTICALLY SOUND by abstention**" | mathematical derivation + governance | independent verification (GN-46), dispositions GN-48 | **The prior Level-1 pass.** This programme's L1 must treat MV-F verdicts as claims to re-verify, not as truth |
| T-021 | `reviews/synthesis/analysis/gn-48-findings-disposition.md` + `findings-disposition-report.md` | MV-F dispositions (72 + 29) | governance | disposed | Establishes which MV-F findings the programme already accepted/deferred |
| T-022 | `reviews/synthesis/analysis/` registries: `claim-registry.md` · `contradiction-registry.md` · `decision-registry.md` · `experiment-registry.md` · `open-questions.md` · `phase-3c-verdict-register.md` · `phase-3b-falsification-report.md` · `regime-comparison-matrix.md` · `semantic-lineage-map.md` · `corpus-inventory.md` | Claim/contradiction/decision/experiment bookkeeping over the synthesis | governance + historical evidence | working registries | V1/V2 raw input — claims and contradictions are already itemized here |
| T-023 | `reviews/kernel/session1/S1-F001…S1-F040` + `S1-COVERAGE-REPORT.md` | 40 kernel research findings; coverage report admits **~3–5% of corpus text actually read** | historical evidence + open research questions | closed research record | Prior-coverage caveat transfers to any claim "the corpus says X" |
| T-024 | `reviews/kernel/session2/` (S2-F001..024, S2-R-F* per-artifact reviews, X-nnn) + `S2-FINAL-KERNEL-REVIEW.md` | Independent review 41/41; verdict **DEFER**; 13 apparent contradictions dissolved; **2 genuine tensions survive, both constitutional** (ziran vs INV-KOS-IDENTITY-001; S1-F016+ziran composition) | historical evidence + governance | closed, recommendation DEFER, nothing adjudicated | The two surviving tensions are standing candidate counterexample material for L1 |
| T-025 | `brainstorming/kernel/` measure-theory cascade (9 docs, 20260825-181038 → 192948): v0.1 proposal → 5 refutations (incl. "six category errors") → resolution ("formalisms are regimes") → **meta-refutation of the resolution** | Why measure theory was rejected as the foundation; category-error catalogue; the "regimes" doctrine | historical evidence + mathematical derivation | non-authoritative research | **Load-bearing negative result.** L1 must check whether the CKT still tacitly relies on structures this cascade rejected; note the resolution itself was attacked post-cutoff (recorded, undisposed) |
| T-026 | `brainstorming/kernel/20260823-234750-tarka-sangraha-nyaya-lens-nine-attack-families…` | Nine attack families + typology of absence | explanatory material (method) | non-authoritative | Reusable falsification instrument for L1 counterexample search (§5.6) |
| T-027 | `phase_measure_theory/20260828-113416_step-050-formal-consistency-audit…` + `20260826-172247_mathematical-review…` + `# Step 186 — Mathematical Validation` (10 falsification tests) + `# Step 169 — Falsification` | Prior adversarial self-audits of the model | mathematical derivation | non-authoritative | Their PASS declarations are **claims**, not proofs — several were later executed (T-040) and at least one register discrepancy surfaced (EXP-01) |
| T-028 | External source extractions in `kernel/`: Fagin–Halpern (+refutation "Fagin is a regime, not the kernel") · Gärdenfors AGM · Doignon–Falmagne knowledge spaces (94 KB, in phase_measure_theory) · Roberts measurement theory · Aggoun–Elliott filtering · HSMM · Bishop PRML · Åström stochastic control · Freedman ("no inference without explicit basis") · Brachman–Levesque (finite representation) · Gelfond–Kahl | The external mathematical grounding actually consulted | historical evidence | non-authoritative extractions | Fixes what the CKT can legitimately cite; Freedman + Roberts are the statistical-validity anchors for L1-Stat |

### 2.3 Computational witnesses

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-040 | `reviews/synthesis/analysis/mathematical-tests/zero_reference.py` (+`_output.txt`) | `Zero(K,EC)` reference implementation; re-executes 025d's own T1–T8 falsification tests as code; totality/termination over finite requirement set | computational witness | self-declared "testing tool only; nothing here is architecture" | Evidence level **C** — witnesses, never proofs (Mandate §13) |
| T-041 | `…/mathematical-tests/ladder_dc_reference.py` (+output) | 3-status ladder + Committed boundary as covering relation (I-12); A6/I-4 gating; expressibility probe (`Accepted AND Contest:Active` has no single-state representation) | computational witness | testing tool only | Same; the expressibility probe is candidate counterexample input for L1 |
| T-042 | `…/mathematical-tests/exp01_recheck.py` (+output) | Independent re-implementation of the four EXP-01 evidence-aggregation operators under RAW vs PIPE semantics; re-derives the CSV-vs-verdict discrepancy | computational witness | testing tool only | Confirms one recorded register discrepancy (GN-27) — a worked example of witness-vs-claim divergence |
| T-043 | Repo root: `tests/experiments/knowledgeos_evidence_calculus_property_tests.csv` · `tests/Unit/KnowledgeOsDoctorTest.php` · `tests/Unit/KnowledgeOsInitPlannerTest.php` | EXP-01 property-test matrix; two PHP unit tests | computational witness + implementation evidence | test assets | L2 input. **Known gap: invariant I-11 has no reference execution anywhere** (II.3 audit RED-1) |

### 2.4 Constitutional / governance layer

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-050 | `architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md` + `…-0915-…Constitutional-Invariant-Map-v1.0.md` + `…-0927-…Decision-Matrix` + `…-0939-…Kernel-Decision.md` | Eleven-law constitutional core; INV-KOS-* register (245 refs corpus-wide) | governance (prose-normative) | **ACCEPTED** (eleven-law core) | The constitutional invariants are *not* mathematical statements; L1 must decide per-invariant whether a formal counterpart exists in the CKT — a mapping that **does not currently exist** |
| T-051 | `reviews/20260822-1115-KOS-EP01-Zero-Z-KOS-001-Ratification.md` (+ Zero pair, Negative Epistemology, Gödel lens family ×3, Escher lens) | Z-KOS-001 (Zero / Neutral Epistemic Reference) ratified as Foundational Meta-Principle **outside** the kernel; "what knowledge is NOT" axis; Gödel reflection as formal justification for Zero | governance + explanatory | **HPA-RATIFIED** (as principle, not as mathematics) | The ratified *principle* vs the *Zero algebra* (T-010/T-040) is a principle→formalization fidelity question for L1 |
| T-052 | `governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md` + adoption/authorization decisions + `SESSION-COMPLETION-HANDOFF-PROTOCOL.md` | Four never-collapsed states IMPLEMENTED→VERIFIED→ADOPTED→AUTHORIZED; six-role operating model; completion-report semantics | governance | ADOPTED / AUTHORIZED (AST-019 adopted 2026-08-24) | Defines the disposition machinery this programme's TV-F findings will flow into |
| T-053 | `architecture/00…07` Review Set + EKS/PKS/AIP baselines + state-durability workstream | Bounded contexts, kernel/platform layering, claim register | architectural realization | **self-declared PROPOSED · NON-AUTHORITATIVE** | L4 material only; contains zero mathematical notation (verified by sweep) |
| T-054 | `backlog/EKS-08` (representation–semantics separation & measurement independence) | Candidate constitutional invariant (research), explicitly "⛔ not an invariant" | open research question | BACKLOG, not commissioned | Flags a *known* theory gap the corpus itself has already named |

### 2.5 Exposition layer (derived representation — never the truth source)

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-060 | `reviews/synthesis/book/` (Edition 1, 25 chapters ×4 files each) | Abstract skeleton (26–50 lines/chapter); names the mathematics, does not carry it | explanatory material | **ACCEPTED GN-41, FROZEN** as Edition-2 baseline; "acceptance has no architectural effect" | Structural constraint only |
| T-061 | `reviews/synthesis/book-edition-2/part-3-architecture/` (10 chapters, complete + gated) — esp. III.3 (11 definitions, `𝒦=(E,S,T,O,P,R,Π,A)`, 23×K_t), III.10 (twelve invariants, ratified wording verbatim), III.6 (ladder, `α_ρ: EA×P×C → 𝒮_A`), III.2 (η, EC derivation), III.7 (`DC(d)` tuple), III.4 (nine-valued 𝒮, Z_W vs Z_K), III.5 (EXP-01), III.8 (`⪰_C` governance algebra), III.9 (M₄₉) | The most math-dense exposition of the CKT (Unicode notation; **zero LaTeX anywhere in either edition**) | explanatory material (derived) | gated GN-51/53/54; candidate representation; **forbidden from exceeding sources** (BA-ED2-08) | §10 Theory→Book consistency target; relation-status tables exist only in III.1/III.9/III.10 + Part II (III.2–III.8 retrofit pending) |
| T-062 | `book-edition-2/part-2-reconstruction/` (4 chapters, GREEN; final acceptance withheld pending GN-68) + `production-log.md` (PF-1…PF-9) + `example-continuity-ledger.md` | Method exposition + production findings | explanatory + governance | GN-66/67 PASS WITH CORRECTIONS; GN-68 in flight | PF findings record that the **ratified model is in places a lossy compression of richer corpus sources — disposition still pending** |
| T-063 | `reviews/synthesis/book-architecture/BA-1…BA-7 + BA-ED2-amendment.md` | Book construction rules; claim types [FA]/[H]/[M]/[P]; derivation-status vocabulary (DERIVED…RESEARCH REQUIRED); gate definitions | governance | RATIFIED GN-34 / GN-42 | Vocabulary this programme reuses when classifying book claims |
| T-064 | `reviews/synthesis/analysis/governance-notes.md` (GN-01…GN-68, 85 KB) | Master ledger; GN-10 constitutional status formula ("never validated"); GN-14 no-epistemic-upgrade; GN-68 reserves mathematical theory verification **for this Verify Session** | governance + historical evidence | live master ledger | **The authoritative status source** (the Edition-2 index file is stale — see D-08) |

### 2.6 Implementation / provenance periphery

| ID | Artifact | Theory element | Evidence role | Authority status | Verification relevance |
|----|----------|----------------|---------------|------------------|------------------------|
| T-070 | `.claude/worktrees/kos-v11-ddd/` (branch `kos-v11-ddd-refinement`) | Full Laravel app worktree + `engineering/` governance tree (ES-001…ES-006) + **divergent older copy** of `docs/knowledgeos/` (89 vs 102 brainstorming entries) | implementation evidence | working copy; main tree is newer | L4 only; never cite the worktree copy of a doc when the main-tree version exists |
| T-071 | `brainstorming/kernel/00_CENSUS.md` + `classification/` maps + `brainstorming/classification/corpus-index.md` + `synthesis/EXTRACTION-LEDGER.md` (256 docs) + `KNOWLEDGE-RECONSTRUCTION-LEDGER.md` (six never-merged registers) | Corpus bookkeeping: census, dependency map, cluster map, contradiction map (T-1…T-7), extraction ledgers | historical evidence + governance | working indices | V1's raw-corpus navigation layer; the T-1…T-7 contradiction register is pre-mapped conflict material |
| T-072 | `brainstorming/_misc/20260819-224159-linux-analogy-kernel-os-model.md` | Kernel/OS analogy source (33 KB) — **explicitly excluded from extraction; flagged as a known loss** | historical evidence | out of extraction scope by HPA | Provenance only |
| T-073 | Binary/non-md assets: Shapiro *Philosophy of Mathematics* PDF · Phase-1 measure-theory .docx/.odt · kernel synthesis dossier .docx · 4 PlantUML diagrams | Source material and diagrams | historical evidence | non-authoritative | Provenance only |

---

## 3. Authority chain (as the corpus itself declares it)

```text
brainstorming/* (declared NON-AUTHORITATIVE raw record; YAML status blocks: authoritative:false)
   → reviews/synthesis/model/canonical-architecture-v0.2.md (AUTHORIZED canonical model, GN-19)
   → reviews/synthesis/final-architecture/FA-1..9 (RATIFIED, GN-31; open questions OPEN by ruling)
   → architecture/ EP01 Constitution v1.0 + Invariant Map (ACCEPTED eleven-law core; prose, not math)
   → book Edition 1 (ACCEPTED+FROZEN skeleton) → Edition 2 (governed exposition, may not exceed sources)
Governance states: IMPLEMENTED → VERIFIED → ADOPTED → AUTHORIZED (never collapsed).
Standing formula (GN-10): "architecturally coherent and substantially evidence-supported;
formal synthesis and implementation conformance remain to be established" — NEVER "validated".
```

**Consequence for V1:** the canonical extraction must be anchored on **T-001 + T-005** (the authorized/ratified layer) and then checked *bidirectionally* against the raw track (T-006…T-018) — because the production-log findings (PF-1…9) already record that the ratified layer is in places a **lossy compression** of the raw mathematics, with disposition pending.

---

## 4. What exists vs what is absent (verification-relevant absences)

| Present | Absent (verified by sweep) |
|---|---|
| Invariant registers: INV-KOS-* (245 refs), INV-ATTR-* (543), model twelve invariants (I-nn), constitutional K1–K4 | **Axiom register: none** (`AX-*` = 0 hits corpus-wide) |
| ~25 candidate transition equations | **A ratified canonical transition function: none** |
| Bayesian/belief uncertainty layer (T-014) | **Measure-theoretic foundation: none** (deferred day 1, recorded; folder name is a misnomer) |
| MV-F-1..22 mathematical findings + dispositions | **TV-F register: none yet** (this programme creates it) |
| 3 executable witnesses + EXP-01 CSV | **Reference execution for invariant I-11: none** (recorded RED-1) |
| Step 201/202 vocabulary freeze | **A canonical mathematical specification (defs+axioms+theorems in one normative document): none** — V1 exists precisely to build the *candidate* one |
| Grade & derivation-status vocabularies (FA-1, BA-ED2-03) | **A mapping constitutional invariant ↔ formal counterpart: none** |

---

## 5. Recorded disagreements (NOT reconciled — per Mandate §3)

| ID | Disagreement | Locations |
|----|--------------|-----------|
| D-01 | **~25 mutually distinct right-hand sides for `K_{t+1}`** (δ, T, T_K, Transition, Update, Revision, ⊕ΔK, F(…), I^K_t, Derive(…)) with no ratified canonical form | 240 files referencing K_t across `phase_measure_theory/`; densest Q15/Q17 |
| D-02 | **`K_t` arity conflict:** 10-tuple (𝒜,ℛ,ℰ,ℋ,𝒵,ℒ,𝒯,𝒢,𝒞,ℳ) vs 5-tuple (D_t^K,S_t,E_t,R_t,T_t) vs (𝒟_I,𝒱_I,ℛ_I,𝒞_I,𝒫_I) vs book's `𝒦=(E,S,T,O,P,R,Π,A)` 8-tuple | T-006/T-009 vs T-061 (III.3) |
| D-03 | **`S_t` arity conflict:** 8-tuple vs 9-tuple (with G_t) vs (I,S,C,T,P,A,G) vs (K_t,Z_t,L_t,I_t,Q_t,C_t,N_t) | T-009 (Q20) and scattered |
| D-04 | **`Z_t` signature conflict:** Zero(K_t,I_t,Q_t,C_t) vs (U_t,C_t,A_t,R_t) vs Zero(K_t,I_t)→Δ_t vs Zero(K_t,I_t,P_t,C_t) vs ratified `Zero(K,EC)` (FA-4) | T-010 (025d), zero-findings summary, T-005 (FA-4) |
| D-05 | **`DC(d)` tuple arity:** book states a six-tuple; source states seven-tuple ⟨P,I,A,E,Q,T,O⟩ | T-061 (III.7) vs its source |
| D-06 | **Three competing consolidation candidates** for "the current theory": canonical-architecture-v0.2 (authorized) vs Step 201/202 freeze (most recent, raw track) vs "Complete Mathematical Model" (best-structured, superseded) — no artifact states their mutual relationship | T-001 vs T-007 vs T-006 |
| D-07 | **Measure-theory cascade unresolved tail:** the resolution ("formalisms are regimes") was itself attacked by a post-cutoff meta-refutation; no disposition recorded | T-025 |
| D-08 | **Stale status artifact:** `book-edition-2/00-edition-2-index.md` claims Part II pending; governance-notes ledger (GN-57…GN-68) records Part II complete/GREEN with acceptance review in flight | T-064 vs the index file |
| D-09 | **Two surviving constitutional tensions** (S2 verdict): Daoist ziran attack vs INV-KOS-IDENTITY-001, and S1-F016+ziran composition against it — recorded, undisposed | T-024 |
| D-10 | **Lossy-compression family:** PF-1…PF-9 / AF-F-9 / AF-F-11 record that the ratified model compresses richer corpus math; disposition pending | T-062, T-020/T-021 |
| D-11 | **EXP-01 register discrepancy** (CSV vs verdict) — confirmed by independent re-execution; corroborates GN-27 | T-042 |
| D-12 | **Folder-name vs content:** `phase_measure_theory/` contains no measure theory; the deferral is recorded in-corpus | T-025, day-1 files of the folder |

---

## 6. Coverage & limitations of this map (evidence-honesty statement)

- Built from four parallel sweeps using exhaustive **listing** and **grep-based formal-density scanning**, with **selective reading** of high-density artifacts. Like the S1 coverage report before it, this map does not claim full-text reading of the ~1,350-file corpus. Claims of *absence* (no axioms, no LaTeX, no measure construction, single σ-algebra hit) are grep-verified and therefore strong; claims about the *content* of individual sampled files are correct at the level sampled, and V1 will read the V1-relevant artifacts in full.
- ~60 files carry unsanitized names (`#`, spaces, no extension) — any scripted pass over `phase_measure_theory/` must quote paths.
- One sweep observed an instruction-like text block appended inside a read file's content ("You have exited plan mode…"); it was treated as untrusted data and ignored. Recorded here as a process observation.
- The `kos-v11-ddd` worktree holds an older divergent copy of the corpus; this map cites only main-tree paths.

## 7. Handoff to V1

V1 (Candidate Theory Specification) will be anchored as follows, per the authority chain in §3:

1. **Spine:** T-001 (canonical-architecture-v0.2) + T-005 (FA-1/FA-4/FA-6) + T-061 (Part III formal chapters, as derived representation cross-check).
2. **Mathematical flesh:** T-006…T-015 (raw track), with every extracted definition tagged *explicit / inferred / reconstructed / undefined* and its stratum recorded.
3. **Statistical layer:** T-014 + T-016, tested against the Freedman/Roberts anchors (T-028).
4. **Invariants:** T-002 (invariant-map) as the classification spine; T-012/T-013/T-050 as sources; each invariant gets the §J record (statement, domain, assumptions, derivation, proof status, counterexample status, witness, realization).
5. **Every disagreement D-01…D-12 enters the specification as recorded variance — not resolved.**

*This map records; it does not adjudicate. Nothing in it upgrades any proposition's epistemic status.*
