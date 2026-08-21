# KnowledgeOS Epistemic Architecture Investigation

> **STATUS: INVESTIGATION · EVIDENCE-BASED · PROPOSED · NON-AUTHORITATIVE · NOT ADOPTED.**
> This document **investigates** whether the epistemic principles surfaced in two external research discussions (a multi-dimensional political-performance framework and the CAPPI v21 framework) have evidence-supported value for a future KnowledgeOS kernel-boundary decision. It is **NOT** a kernel design, a target architecture, a bounded-context decision, a technology decision, an implementation plan, an ADR, or a proposal to rewrite EKS or PKS.
>
> **Deliverable:** an HPA-commissioned investigation, executed by a fresh isolated session. **Inputs:** the three current-architecture baselines (EKS Stage 1 · PKS Stage 2 · AIP Stage 3) and the approved EP-01 plan `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md`.
> **Date:** 2026-08-21 23:44 · **Lane:** HPA investigation (distinct from EP-01 P1–P6 phases; no phase gate consumed).
> **Evidence hierarchy (binding):** repository evidence (EKS/PKS/AIP baselines + their underlying artifacts) is **PRIMARY**. The CAPPI / political-performance discussions are **SECONDARY** research input that generates hypotheses; they are **NEVER treated as architectural evidence**.
> **P3-F1 lesson applied (§19 of the mandate):** the EKS baseline is **BROKEN / INCOMPLETE** (ends at §20; §21–§25 absent; U-/X- registers missing). Therefore **"EKS does not evidence X" is recorded as UNKNOWN (incomplete corpus), never as NOT-PRESENT**, unless a tier-1 absence was measured directly. NOT FOUND ≠ NOT PRESENT ≠ NOT EVIDENCED ≠ UNKNOWN. The AIP baseline is corrected by erratum P3-F1 (commit `6034db5c`); the PKS baseline is corrected by erratum P2-F1.

---

## 01 · Executive Summary

Two external research discussions — a **multi-dimensional political-performance framework** (multidimensional representation, explicit uncertainty, temporal validity, hierarchical evidence, hypothesis/falsification, Bayesian reasoning, graph/topological analysis, simulation, diagnostic analytics) and the **CAPPI v21 framework** (μ = calibrated signal · τ = instance uncertainty · w = reliability/domain-fit weight → calibrated model vector → Bayesian fusion → derived quality → diagnostic analytics; the **double-dampening** warning; **"orthogonal concerns must remain orthogonal"**; CalibrationContext → BayesianFusionContext → SabermetricContext) — supply a set of **elegant epistemic principles**. The central question is whether those principles are **supported by EKS/PKS/AIP evidence** before any future KnowledgeOS kernel decision.

**Headline findings:**

1. **The observation → evidence → claim → confidence → authority → decision → knowledge pipeline is only partially evidenced.** OBSERVATION, EVIDENCE, AUTHORITY, DECISION exist as implemented or governed concepts in all three systems — but with **different semantics in each system**, and with several crucial steps (**CLAIM/INTERPRETATION**, **UNCERTAINTY/CONFIDENCE**) **NOT PRESENT** as named concepts anywhere.

2. **CONFIDENCE ≠ AUTHORITY is the strongest-supported external principle.** EKS (AP-1: *"knowledge feeds authority, it never holds it"*; assessments confer no authority; verdicts have a machine-unemittable subset), PKS (AP-1; promotion is a distinct Authority act; MCR-5 confidence ceiling is separate from authority acts) and AIP (produced records ≠ owned authority; invariant asymmetry) all implement or imply the separation of **analytical confidence from organizational authority**. **Classification: ESTABLISHED** (EKS/PKS) and **PARTIALLY ESTABLISHED** (AIP — authority is separated from assurance, but "confidence" is not modelled as a concept).

3. **CAPPI's numeric epistemic stack (μ/τ/w → Bayesian fusion → quality score) has ZERO current-architecture support.** None of EKS/PKS/AIP uses a continuous calibrated confidence signal, a Bayesian fusion step, or a scalar quality score. All three use **discrete verdict vocabularies** and, at their best, **honestly record "UNKNOWN" instead of computing a probability**. This is the single strongest case of *"an elegant mathematical model that is NOT supported by EKS/PKS/AIP evidence"* — and per the mandate's Rule 4 it must be recorded as such, not promoted.

4. **The "orthogonality" principle (reliability ≠ uncertainty) is NOT-EVIDENCED, not contradicted.** The current systems model **neither** reliability **nor** instance uncertainty as separate axes, so the CAPPI double-dampening risk cannot be assessed against them. The systems' actual double-evaluation risks are different: **structural validation standing in for semantic correctness**, **multiple independent implementations of the same measurement** (three LCOM4 implementations), and **authority/status conflation by absence of a time dimension**.

5. **The anti-fallback design of the current systems CONTRADICTS CAPPI's fallback-chain idea.** EKS's resolver **refuses** `UNRESOLVABLE` rather than falling back; PKS's CAP-001 is **fail-closed** (*"absence of evidence is never PASS"*); AIP has no fallback. The closest real-world analog of the CAPPI fallback risk is **not in any system's code but in the P3-F1 incident**: an unread evidence artifact was declared "not needed" — a *silent epistemic-status change* that the §19 discipline exists to prevent.

6. **Temporal knowledge is the least-supported element of the proposed kernel boundary.** EKS authority is not temporally reconstructable (2/218 transitions carry any date); AIP has the same measured limitation; PKS has forward-only supersession as a governed concept but no timestamp dimension. `effective_from`/`effective_until`/`superseded_at` exist **nowhere**. The proposal that the kernel protects "temporal state" is therefore a **PROPOSED candidate with the weakest evidence** of all seven proposed kernel concerns.

7. **"Consistency is determined by invariant ownership, not by whether communication is event-driven" is supported in the synchronous/shared-file context.** All three systems achieve their strongest consistency boundaries **without any event-driven architecture** (EKS: one-file-per-work-item + fold; PKS: capability vertical slices + governed discipline; AIP: registry-first + workflow engine). Whether invariant ownership alone suffices under distribution remains **UNKNOWN**.

8. **Seven cross-system kernel candidates emerge** — each with real evidence in ≥2 systems, none promoted: authority-as-recorded-reference-to-a-human-act · state-as-fold (append-only, forward-only) · closed-verdict-vocabulary-as-published-language · assessment-conferring-no-authority · forward-only-supersession · per-kind-register-scoped-identity · regenerable-non-authoritative-projection. **KERNEL-CANDIDATE** means only *"enough cross-system evidence to deserve explicit kernel-boundary investigation"* (AMENDMENT 6; §15).

9. **The proposed kernel boundary** (*kernel protects identity · evidence · provenance · lifecycle · authority · invariants · temporal state; higher layers provide assurance · analytics · inference · simulation · AI · visualization · delivery*) is **supported in outline** by the existing separation of authoritative-record concerns from advisory/analytical concerns in all three systems — with the important caveat that **provenance and temporal state are the two weakest-supported items**, and the split is a **PROPOSED KERNEL BOUNDARY CANDIDATE**, not an established fact.

10. **The external discussions teach reusable principles but must stay secondary.** They are best used as a **hypothesis checklist** (H-register §17) and as **warnings** (orthogonality, double-evaluation, fallback risk) to test against EKS/PKS/AIP — not as architecture.

---

## 02 · Investigation Scope

**In scope:**
- Determine whether the epistemic principles from the two external research discussions are **supported, partially supported, contradicted, or unknown** against the EKS/PKS/AIP current-architecture baselines.
- Apply the **DDD test** (§21) and the **orthogonality / double-evaluation** analysis (§13) to each candidate concept.
- Produce the **hypothesis register**, **evidence register**, **contradiction register**, **UNKNOWN register**, and the **kernel-boundary assessment**.
- Classify every candidate as KERNEL-CANDIDATE · DOMAIN-CONTEXT-CANDIDATE · PLATFORM-CAPABILITY · ANALYTICS-CAPABILITY · ADAPTER/INTEGRATION · PRODUCT-HYPOTHESIS · NOT-SUPPORTED.

**Out of scope (do-not-decide, do-not-design):**
- ⛔ **No kernel design, no kernel classes/modules/APIs, no technology choices, no implementation code, no migration plan, no ADR adopting the kernel.**
- ⛔ No bounded-context or aggregate decision.
- ⛔ No proposal to rewrite, merge, or replace EKS or PKS.
- ⛔ No comparison that resolves the EKS/PKS/AIP identity ambiguity (U-02/X-01) — that is Stage 4 territory, which this investigation does **not** perform.
- ⛔ No completion of missing EKS baseline sections (§21–§25) and no opportunistic repair of the broken P1 file.
- ⛔ No review-model / protocol / methodology evolution (methodology freeze respected).

**Boundary:** This investigation reads the three baselines (and their cited underlying artifacts where load-bearing) as evidence. It does **not** re-run the archaeology.

---

## 03 · Evidence Sources

### 03.1 Primary repository evidence (hierarchy: strongest first)

| ID | Source | Tier | Status |
|---|---|---|---|
| S-01 | EP-01 plan `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` | governed plan | APPROVED |
| S-02 | EKS baseline `docs/knowledgeos/architecture/20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md` | tiers 1–8 | ⛔ **BROKEN / INCOMPLETE** — ends at §20; §21–§25 absent; U-/X- registers missing; **partial evidence only** |
| S-03 | PKS baseline `docs/knowledgeos/architecture/20260821-2229-PKS-Current-Architecture-Baseline-Stage-2.md` | tiers 1–8 | PROPOSED · HPA PASS WITH FINDINGS · erratum P2-F1 applied |
| S-04 | AIP baseline `docs/knowledgeos/architecture/20260821-2259-AIP-Current-Architecture-Reconstruction-Stage-3.md` | tiers 1–8 | PROPOSED · HPA PASS WITH FINDINGS · erratum P3-F1 applied (commit `6034db5c`) |
| S-05 | HPA review P3 `docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P3-aip-reconstruction-hpa-review.md` | governance record | PASS WITH FINDINGS (P3-F1..F4) |
| S-06 | Independent verification P3-F1 `docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P3-findings-independent-verification.md` | governance record | P3-F1 CONFIRMED (BLOCKING); erratum applied |
| S-07 | HPA review P2 `docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P2-pks-baseline-hpa-review.md` | governance record | PASS WITH FINDINGS (P2-F1..F3) |
| S-08 | `.claude/CONTEXT.md` rows (MANDATORY FRAMEWORK · LANDSCAPE-FIRST · AMENDMENTS 4–6 · NEXT) | session-state | binding |
| S-09 | `.claude/sessions/2026-08-21.md` (AMENDMENTS 2–6; P1–P3 execution records) | session-state | binding |
| S-10 | Underlying artifacts as cited by the baselines (`.claude/runtime/workflow/*.json`, `scripts/lib/EngineeringKnowledge/`, `scripts/observations/`, `docs/knowledge/schema/*.yaml`, `registry.yaml`, `MIGRATION-PLAN.md` §1, PKS Phase II/III artifacts) | tiers 1–5 | re-verified only where load-bearing |

### 03.2 Secondary research input (hypotheses only, NEVER architecture)

| ID | Source | Role |
|---|---|---|
| S-11 | Discussion A — multi-dimensional political-performance framework (multidimensional representation, explicit uncertainty, temporal validity, hierarchical evidence, hypothesis/falsification, Bayesian reasoning, graph/topological analysis, simulation, diagnostic analytics) | generates hypotheses; secondary |
| S-12 | Discussion B — CAPPI v21 (μ/τ/w → calibrated model vector → Bayesian fusion → derived quality → diagnostics; CalibrationContext / BayesianFusionContext / SabermetricContext; double-dampening; "orthogonal concerns must remain orthogonal"; epistemic status · uncertainty · version lineage · fallback chains · diagnostics separate from primary results) | generates hypotheses; secondary |

**Evidence-hierarchy rule (binding):** a principle that is elegant but not supported by S-02/S-03/S-04 evidence is recorded as NOT-SUPPORTED / CONTRADICTED / UNKNOWN — never promoted.

---

## 04 · External Principle Extraction

### 04.1 Discussion A — multi-dimensional knowledge (secondary)

| A# | Principle (as stated externally) | Potential KnowledgeOS analogy |
|---|---|---|
| A-01 | A complex phenomenon should not necessarily be collapsed into one scalar value | Observation ≠ Evidence ≠ Inference ≠ Confidence ≠ Authority ≠ Decision |
| A-02 | Explicit uncertainty is representable | τ (instance uncertainty) |
| A-03 | Temporal validity matters | what-is-true-now vs what-was-believed-at-T |
| A-04 | Evidence is hierarchical | evidence tiers / epistemic classes |
| A-05 | Hypotheses are falsifiable | hypothesis status, not truth |
| A-06 | Bayesian reasoning can combine signals | calibrated model vector → fusion |
| A-07 | Graph/topological analysis reveals structure | contradiction/topology analysis |
| A-08 | Simulation tests dynamics | what-if / predictive analytics |
| A-09 | Diagnostic analytics explain, not decide | diagnostics separate from primary results |

### 04.2 Discussion B — CAPPI v21 (secondary)

| B# | Principle (as stated externally) | Architectural content |
|---|---|---|
| B-01 | μ = calibrated signal | a continuous calibrated measure |
| B-02 | τ = instance uncertainty | per-instance uncertainty |
| B-03 | w = reliability/domain-fit weight | a separate reliability/weight axis |
| B-04 | CalibrationContext → BayesianFusionContext → SabermetricContext | three separated concerns with distinct ownership |
| B-05 | **Double-dampening**: reliability penalty + uncertainty penalty can measure the same concern twice | orthogonality warning |
| B-06 | **"Orthogonal concerns must remain orthogonal"** | the meta-principle |
| B-07 | Epistemic status is tracked | status of a knowledge element |
| B-08 | Version lineage is explicit (v17→v18→…→v21) | evolution / lineage |
| B-09 | Fallback chains exist (v21→v20→v18.1→expert floor) | degradation semantics |
| B-10 | Diagnostics are kept outside the primary result (WAE outside primary score) | diagnostic ≠ authoritative |
| B-11 | Open research questions are explicitly maintained | unresolved questions are first-class |

### 04.3 Reusable principle candidates (before evidence check)

From A and B, the candidate principles worth testing against EKS/PKS/AIP:

1. **P1 — Separation of epistemic stages** (Observation · Evidence · Claim · Confidence · Authority · Decision). *(A-01, B-07)*
2. **P2 — Confidence ≠ Authority.** *(A-01, B-01/02/03)*
3. **P3 — Reliability ≠ Uncertainty (orthogonality).** *(B-05/06)*
4. **P4 — Temporal validity: now vs at-time-T.** *(A-03)*
5. **P5 — Version lineage and forward-only supersession.** *(B-08, A-03)*
6. **P6 — Fallback semantics must not silently change epistemic status.** *(B-09)*
7. **P7 — Diagnostics must not mutate authoritative state.** *(B-10, A-09)*
8. **P8 — Invariant ownership, not communication style, determines consistency.** *(B-04, CAPPI context ownership)*

Each is tested in the H-register (§17) against primary evidence.

---

## 05 · EKS Findings (primary evidence — ⚠️ incomplete corpus, UNKNOWN-preserving)

**Note on evidence completeness:** the EKS baseline is **BROKEN / INCOMPLETE** (ends at §20; the U-/X- registers that §11.4, §16.6, §17.2 cross-reference are absent). Per mandate §19, **"EKS does not evidence X" is UNKNOWN unless a tier-1/3 absence was directly measured**. The findings below that rely on direct measurement are tagged; the rest are PARTIAL-EVIDENCE.

### 05.1 What EKS establishes relevant to the epistemic pipeline

| Epistemic stage | EKS evidence | What it is actually called | Class |
|---|---|---|---|
| **Observation** | `Observation` = a stated fact about code: metric · subject · value · evidence refs. *"No verdict, no threshold, no policy."* | Observation | **A** (tier 1) |
| **Evidence** | **Overloaded** (5 senses): (1) mandatory string on an `Assessment`; (2) `evidence_refs` file list; (3) the `engineering/verification/` corpus; (4) the transition log ("historical evidence"); (5) a commit sha. | Evidence | **F/D** (overloaded) |
| **Claim / Interpretation** | No named concept. Interpretations are **derived** (fold, verdict, resolver verdicts) and never persisted. | — | **E/NOT PRESENT** → **UNKNOWN** |
| **Uncertainty / Confidence** | No confidence field, no probability, no continuous signal. Verdicts are a **closed discrete enum**. The resolver returns `UNKNOWN`/`AMBIGUOUS` as first-class, non-error answers. | — (deliberately absent) | **E/NOT PRESENT** → **UNKNOWN** (absence is deliberate; see §09) |
| **Authority** | `Grant` = a registered reference to a human authorizing act; six fields; **records** authority, does not grant or enforce it. Authority ≠ lifecycle (Inv G). | Grant · Authority State | **A** (recorded) / **D** (scope is prose, unanswerable) |
| **Decision** | Overloaded (developer response to a recommendation; ADR; registry D-2; PO/ARB act). | Decision | **F** (overloaded) |
| **Organizational knowledge** | Governed corpora under `docs/knowledgeos/`, `docs/knowledge/`, `engineering/`; injected into AI processes at session start. | knowledge corpus · MEMORY · CONTEXT | **A** |

### 05.2 EKS-specific epistemic properties (measured)

1. **State = fold.** Append-only transition log; the fold is the only state authority; no derived state is persisted. — *A* (tier 1/3). This is the strongest design property in EKS.
2. **Two never-merged records:** `transitions[]` (evidence) ≠ `grants[]` (authority), different writers, different lifecycles. — *A*. **This is a structural separation of evidence from authority.**
3. **Assessment confers no authority (AP-1):** *"knowledge feeds authority, it never holds it."* `PASS AFTER CORRECTION`/`EMERGENT`/`CERTIFIED` are structurally unemittable by machine. — *A* (tier 1/2). **This is the cleanest EKS evidence for CONFIDENCE ≠ AUTHORITY.**
4. **Read purity of discovery:** `session-resolve.php` is structurally write-free (byte-verified, pinned by test T-11). — *A*. **Diagnostics do not mutate authoritative state.**
5. **Single interpretation authority:** the resolver subprocesses `workflow-state.php` and refuses `UNRESOLVABLE` rather than re-implementing interpretation. — *A* (tier 1, T-13). **Anti-fallback by construction.**
6. **Recommendation ≠ decision:** `RecommendationEngine` has no decision path; decisions are a separate CLI writing a separate file. — *A*. **Diagnostic output is structurally separated from the decision act.**
7. **Authority is not temporally reconstructable:** 2/218 transitions carry any date field; ordering is by `seq` alone; the authority record is gitignored. — *E* (tier 3). **Temporal validity is the weakest area.**
8. **No terminal work-item state:** 34 `COMPLETE` transitions; 17/18 work items still fold to `OPEN`. — *E* (tier 3). **Lifecycle closure is incomplete.**
9. **Invariant ownership:** 11/11 workflow-record invariants have a single identifiable owner and an executable test; every invariant outside the workflow record has no owner or an advisory-only owner. — *A* vs *C/E*. **Ownership asymmetry.**
10. **Overloaded vocabulary:** `Session` (4 senses), `Assessment`, `Decision`, `Evidence`, `Verdict`, `Rule` (≥7 senses), `Capability`, `Workflow` — 8 overloaded terms. — *F/D*. **Name-collision is a DDD vocabulary problem, not a naming problem.**

### 05.3 EKS contribution to the investigation

EKS supplies the **strongest implemented evidence** for:
- **evidence ≠ authority** (two never-merged records; AP-1);
- **diagnostic ≠ decision** (recommendation/decision separation; read purity);
- **state = fold** (append-only, forward-only, no in-place rewrite);
- **invariant ownership** as the actual consistency mechanism (11 owned+tested invariants, no events).

EKS supplies **no evidence** for: continuous confidence/calibration, Bayesian fusion, fallback chains, temporal authority, or a scalar quality score.

---

## 06 · PKS Findings (primary evidence)

### 06.1 What PKS establishes relevant to the epistemic pipeline

| Epistemic stage | PKS evidence | What it is actually called | Class |
|---|---|---|---|
| **Observation** | `Observation` (G-10) = recorded evidence carrying an **epistemic class**. Epistemic-class discipline: **Observed · Measured · Derived · Synthesized · Recommendation · Assembly** (+ Phase III `Hypothesis`). | Observation · epistemic class | **A** (governed concept) |
| **Evidence** | Central concept — the PKS *"models engineering knowledge, governance, **evidence**, and their relationships."* Evidence is the substrate AC-1 records and evaluates. | Evidence | **A** (governed) |
| **Claim / Interpretation** | No first-class "claim." `Statement` statuses exist; derived assessments (M3: conformance = "a derived assessment producing a Verdict"). Epistemic classes `Derived`/`Synthesized` cover interpretation-like outputs. | Derived · Synthesized · Verdict | **B/C** |
| **Uncertainty / Confidence** | No per-instance confidence. A **confidence ceiling** (MCR-5): every grade rests on one corpus, one lineage → at most **Medium–High**, never citable as independently confirmed. `INCONCLUSIVE` is a verdict value. | confidence ceiling · INCONCLUSIVE | **A** (as an assurance ceiling, not an instance metric) |
| **Authority** | DA/PA is the sole authority; ARB reviews/recommends, never decides. **AP-1: Knowledge feeds authority; it never holds authority.** Promotion is a distinct Authority act. Authority ⊥ status ⊥ maturity ⊥ adoption (four orthogonal axes). | Authority · Ruling · Charter grant (G-17) · promotion | **A** (governed) |
| **Decision** | `Decision` (G-1) = a selection among alternatives under explicit trade-offs, recorded with rationale, individually citable, **superseded only forward**. | Decision | **A** (governed) |
| **Organizational knowledge** | The knowledge corpus (bifurcated: product-specific content + domain-free methodology; one-corpus-vs-three formally open, OQ-PKS-7). | corpus · knowledge specifications | **A** |

### 06.2 PKS-specific epistemic properties

1. **AP-1 / AP-2 / AP-9 (prohibitive):** knowledge feeds authority never holds it · projections regenerable and non-authoritative · path toward projection one-way. **The strongest invariant set in PKS is negative.** — *A* (tier 4).
2. **Closed verdict vocabulary (AP-8):** `PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED` is the **interchange language**; CAP-001's emittable subset excludes the human-only verdicts. — *A* (tier 4 + tier 1). **A discrete, governed epistemic vocabulary — not a scalar.**
3. **Forward-only supersession (AP-3):** supersession-shaped, never deletion-shaped; predecessor keeps identity as history. Constitutional status UNDETERMINED. — *A* (corpus-observed) / *UNKNOWN* (constitutional status).
4. **M4 identity/lifecycle model:** three state classes (Pre-authority · Authoritative · Superseded-class terminal) × four orthogonal axes. **Authority is one axis; it never merges with status, maturity, or adoption.** — *A* (tier 4).
5. **Fail-closed validation (CAP-001):** *"absence of evidence is never PASS."* — *A* (tier 1/5).
6. **Warn-only advisory gates:** *"collectors never judge; developers decide."* — *A*.
7. **Confidence ceiling (MCR-5):** at most Medium–High; *"the architecture cannot be more certain than the model it derives from."* Operational Evidence = **ZERO-INDEPENDENT**. — *A* (tier 4). **PKS is explicitly honest that its assurance is internally sound and externally unproven.**
8. **No events/messaging:** AD-1 §9.2 — *"the governed model contains no timing, coupling, delivery, or ordering evidence whatsoever."* — *A* (explicit negative).
9. **Governance Invariant:** one primary responsibility per artifact; authority/operational-mandate/procedure/execution-governance/activity/evaluation/certification explicitly separated. — *A* (tier 4). **A single-responsibility / ownership invariant, not a communication property.**

### 06.3 PKS contribution to the investigation

PKS supplies the **strongest governed conceptual evidence** for:
- **confidence-ceiling honesty** (MCR-5, ZERO-INDEPENDENT) — a meta-level recognition that analytical confidence is bounded and separate from authority;
- **forward-only supersession and identity retention** (M4, AP-3) — version-lineage semantics;
- **orthogonal axes** (authority ⊥ status ⊥ maturity ⊥ adoption) — an explicit orthogonality discipline;
- **the anti-elegant-partition discipline** — refusing to draw what evidence does not support.

PKS supplies **no evidence** for continuous confidence, Bayesian fusion, or fallback chains. Its `Hypothesis` epistemic class (Phase III) is a governed *status*, not a numeric confidence.

---

## 07 · AIP Findings (primary evidence)

### 07.1 What AIP establishes relevant to the epistemic pipeline

| Epistemic stage | AIP evidence | What it is actually called | Class |
|---|---|---|---|
| **Observation** | The observation runtime (`scripts/observations/`) is **KnowledgeOS-named and outside the AIP corpus** (PKS U-02; AIP §0.5). AIP itself records capability failures EKS-01..04 as self-observations. | observations (as self-recorded failures) | **A** (self-recorded) / **UNKNOWN** (ownership of the runtime) |
| **Evidence** | Evidence classes A/B/C (GOVERNED · UNGOVERNED · EXTERNAL/CORROBORATIVE). Verification reports/findings/decision records/acceptance records are **produced** as governance evidence. | evidence classes · verification records | **A** |
| **Claim / Interpretation** | No named concept. | — | **E/NOT PRESENT** → **UNKNOWN** |
| **Uncertainty / Confidence** | No confidence model. Guard verdicts are discrete (blocking gate = db-safety). | verdicts (guard) | **E/NOT PRESENT** → **UNKNOWN** |
| **Authority** | G-2: Authority State has exactly one writer (recorded). Grants reference a human act; *"the record never manufactures authority"* (G-2/R5b). Registry-first authority (R-17: five questions every asset must answer). I-4 registry ≠ authority; I-10 record outranks prose. Producer ≠ acceptor (R-34). | Grant · Authority State · registry-first | **A** (recorded) / **D** (not authenticated; write-capable substitution path per P3-F1 E-2) |
| **Decision** | Decisions recorded as grants/humanActRef. **D1/D2 decision state is CONTRADICTORY** across the corpus (runtime grant SELECT B′/ADOPT vs registration review NOT SELECTED). | Decision act · grant | **F → UNKNOWN** |
| **Organizational knowledge** | **Consumes** governed knowledge (loading order: knowledge before rules); **does NOT own** knowledge governance (BC-1 NOT BUILT, CMP-003 deferred). | BC-1 Knowledge Governance (NOT BUILT) | **E** |

### 07.2 AIP-specific epistemic properties

1. **Invariant asymmetry (headline):** every BC-7 (orchestration) invariant is mechanically enforced (I-1, I-2, I-3, G-1, G-2 — contract-pinned); almost every invariant outside BC-7 is declared-only prose (R-34, INV-ATTR-2, AIP-11, AIP-14, I-4, I-10). — *A vs A*. **Assurance is strong where executable, weak where prose.** (Evidence-scoped per P3-F3.)
2. **Durability inversion:** the authority record lives in the **only gitignored** `.claude/` subdirectory. — *D*.
3. **D1/D2 contradiction surfaced, not reconciled.** — *F → UNKNOWN*.
4. **Consumes knowledge ≠ owns knowledge ≠ is KnowledgeOS** (P3-F4 carry-forward). — binding annotation.
5. **Operates/maintains/produces records ≠ authoritative domain ownership** (P3-F2 carry-forward). — binding annotation.
6. **Four unowned capabilities form one control loop** (policy/knowledge DEFINED → C-10 DELIVERS → C-14 GATES → C-19 EXPRESSES ← C-5 ATTESTS); C-10/C-14 existence UNDECIDED. — *G* (proposal).
7. **Six-role operating model** adopted; role ≠ bounded context ≠ capability ≠ agent ≠ platform service ≠ org position. — *A* (adopted).

### 07.3 AIP contribution to the investigation

AIP supplies evidence for:
- **the mechanical/declared enforcement asymmetry** — the same "strong where executable, weak where prose" pattern as EKS;
- **consumption ≠ ownership** — knowledge is consumed, not owned (relevant to the "organizational knowledge" stage);
- **contradictory decision state surviving un-reconciled** — the systems' discipline is to surface, not hide, epistemic conflict;
- **the primary-writer-not-structural-guarantee correction** (P3-F1 E-2) — a §19 lesson: a load-bearing claim ("exactly one writer") was corrected only after the corpus was read in full.

AIP supplies **no evidence** for continuous confidence, Bayesian fusion, fallback chains, or temporal authority (2/218 dated, same as EKS).

---

## 08 · Observation / Evidence / Claim / Inference Analysis

The CAPPI/PPI-inspired separation **Observation ≠ Evidence ≠ Claim/Interpretation ≠ Confidence ≠ Authority ≠ Decision** was tested per-stage.

### 08.1 Stage-by-stage test

| Stage | EKS | PKS | AIP | Verdict |
|---|---|---|---|---|
| **Observation** | `Observation` = fact without verdict (A) | `Observation` (G-10) = recorded evidence with epistemic class (A) | observation runtime outside corpus (UNKNOWN) | **RELATED CONCEPTS** — both separate observation from judgment; different domains (code facts vs governed epistemic records) |
| **Evidence** | overloaded 5 senses (F/D) | central, governed concept (A) | evidence classes A/B/C (A) | **RELATED CONCEPTS** — each has distinct semantics; shared core = evidence is input to judgment, never judgment itself |
| **Claim / Interpretation** | no named concept; interpretations are derived, never persisted (E→UNKNOWN) | `Derived`/`Synthesized` epistemic classes; `Verdict` as derived assessment (B/C) | no named concept (E→UNKNOWN) | **NOT PRESENT as a first-class concept anywhere.** Whether KnowledgeOS needs a CLAIM stage is **UNKNOWN** |
| **Confidence** | no confidence concept; discrete verdicts; honest `UNKNOWN` (E/NOT PRESENT, deliberate) | confidence ceiling MCR-5; `INCONCLUSIVE` verdict (A as ceiling, not instance metric) | no confidence concept (E/NOT PRESENT) | **NOT PRESENT as an instance-level scalar anywhere.** Absence is partly deliberate (EKS refuses to guess) |
| **Authority** | Grant = recorded reference to human act (A/D) | DA/PA sole authority; AP-1 (A) | G-2 single authority writer; registry-first (A/D) | **SAME CONCEPT? — evidence of shared invariant ("records reference a human act; never manufactures authority") across all three; BUT identity ambiguity (U-02/X-01) unresolved → classify RELATED / shared-invariant, NOT confirmed SAME until Stage 4** |
| **Decision** | overloaded (F) | `Decision` G-1 forward-only supersession (A) | decision acts via grants; D1/D2 contradictory (F→UNKNOWN) | **RELATED CONCEPTS** — different granularities and lifecycles |
| **Organizational knowledge** | governed corpora (A) | knowledge corpus (A) | consumes, does NOT own (E as owner) | **RELATED CONCEPTS** — corpora exist in EKS/PKS; AIP is a consumer |

### 08.2 Analysis

- **The separation is partially supported.** Observation ≠ Evidence ≠ Authority ≠ Decision are all real, distinct concepts in at least one system, and the systems **do not collapse them into a single scalar**. This is the strongest alignment with the external principle A-01.
- **Claim/Interpretation and Confidence are NOT PRESENT as named concepts.** The systems use **discrete verdicts and honest UNKNOWN** instead of a confidence scalar. Whether the KnowledgeOS pipeline needs these stages is **UNKNOWN** — the current architecture neither establishes nor contradicts them (with the nuance that EKS's honest-UNKNOWN is a *philosophical* contradiction of probabilistic confidence).
- **Terminological overloading is a live risk.** `Evidence`, `Decision`, `Verdict`, `Session`, `Assessment` each carry multiple meanings within a single system and across systems. **Do not normalize different concepts merely because they have similar names** (mandate §5) — this investigation preserves the distinctions.

---

## 09 · Confidence / Uncertainty / Reliability / Authority Analysis

### 09.1 CAPPI's four scalar axes vs current systems

| CAPPI axis | EKS | PKS | AIP | Evidence status |
|---|---|---|---|---|
| **μ — calibrated signal** | **absent** — discrete verdict enums; no continuous measure | **absent** — closed verdict vocabulary; no calibration | **absent** — guard verdicts discrete | **NOT PRESENT** (all three) |
| **τ — instance uncertainty** | **absent** — `UNKNOWN`/`AMBIGUOUS` are structural answers, not probabilities | **absent** — `INCONCLUSIVE` is a verdict value; no per-instance τ | **absent** | **NOT PRESENT** (all three) |
| **w — reliability/domain-fit weight** | **absent** — evidence tiering (1–8) is a *classification*, not a weighted score | **absent** — MCR-5 is an assurance *ceiling*, not a weight | **absent** | **NOT PRESENT** as a weight; **PRESENT as a classification/ceiling** (EKS tiers; PKS MCR-5) |
| **Authority** | Grant = recorded human-act reference (A/D) | DA/PA sole authority; AP-1 (A) | G-2 single writer; registry-first (A/D) | **PRESENT** — the one axis with cross-system evidence |

### 09.2 The confidence ≠ authority test (mandate §8)

**Claim:** analytical confidence ≠ organizational authority.

- **EKS — ESTABLISHED.** AP-1 (*"knowledge feeds authority, it never holds it"*) is enforced by construction: an `Assessment` *"confers no authority"*; three verdict values are structurally unemittable by machine; authority lives in a separate record (`grants`) from any assessment. The resolver returns a plain boolean `authorized` and six AuthorizationFacts, three of which are permanently UNKNOWN by construction. **EKS implements "an AI analysis can have high confidence (324 passing tests) and no governance authority; a governance decision can have authority with low analytical confidence (prose scope, unanswerable query)."**
- **PKS — ESTABLISHED.** AP-1; promotion is a distinct Authority act separate from review/verification; MCR-5's confidence ceiling is explicitly separate from authority acts.
- **AIP — PARTIALLY ESTABLISHED.** The separation of authority from assurance is present (registry-first authority; produced verification records ≠ owned authority; P3-F2 annotation). But "confidence" is not a modelled concept, so the full confidence≠authority distinction is only implied.

**Classification: ESTABLISHED (EKS, PKS) · PARTIALLY ESTABLISHED (AIP).** This is the single strongest external principle validated by primary evidence.

### 09.3 Reliability vs Uncertainty (mandate §7)

The CAPPI double-dampening concern (reliability penalty + uncertainty penalty measuring the same thing twice) **cannot be assessed against the current systems because neither axis is modelled**. The systems' actual orthogonality risks are different:

1. **Structural validation standing in for semantic correctness** — all three systems' executable assurance is **structural** (identifiers, references, vocabulary, schema, cards, cohesion). None evaluates semantic correctness. The risk is not that the systems double-penalize; it is that **structural validation is the only executable assurance and may be over-weighted** (EKS §17.3 gating gap; PKS §2.3 C — "deterministic guidance" is C/partial; AIP invariant asymmetry).
2. **Multiple independent implementations of one measurement** — EKS has **three independent LCOM4 implementations** (D-class duplication, §20). This is *redundant evaluation of one concern*, not double-penalization, but it is a real orthogonality defect.
3. **Historical vs current authority conflated by absence of time** — EKS/AIP authority has no time dimension, so current vs historical authority cannot be distinguished. This is *conflation by absence*, the inverse of the CAPPI double-counting.
4. **Execution state vs governance state** — EKS Inv G (ACTIVE ≠ AUTHORIZED) and AIP I-4 (registry ≠ authority) are explicit separations. **No double-ownership here.**
5. **Provenance reliability counted twice** — **NOT EVIDENCED** in any system.

---

## 10 · Temporal and Version Lineage Analysis

### 10.1 Temporal concepts (mandate §9)

| Concept | EKS | PKS | AIP |
|---|---|---|---|
| `observed_at` | ✅ ISO-8601 per observation row (tier 3) | ✅ observations carry epistemic class + timestamps in governed records | ✅ self-recorded observations (EKS-01..04) |
| `created_at` | ⚠️ filename dates + registry `verified.date`; work-item records carry NO timestamp | ⚠️ date-named majority; register rows appended (minting act) | ⚠️ filename dates |
| `decided_at` | ❌ **2/218 transitions carry any date** | ❌ no timestamp dimension | ❌ **2/218 dated** |
| `effective_from` / `effective_until` | ❌ **not present anywhere** | ❌ **not present** (AP-3 forward-only, but no dates) | ❌ **not present** |
| `superseded_at` | ❌ **not present** (no supersession mechanism at all) | ⚠️ supersession is a governed evolution op (M2 canon) with predecessor-identity retention — but **no timestamp** | ❌ **not present** (AIP-11 declared) |

**Determination:** the systems establish `observed_at` (as ISO-8601 row timestamps) and a *weak* `created_at` (filenames). They do **NOT** establish `decided_at`, `effective_from`, `effective_until`, or `superseded_at`. The KnowledgeOS need to distinguish **"what is true now"** from **"what was believed/authoritative at time T"** is **NOT ESTABLISHED** by current evidence — indeed EKS/AIP cannot answer it today (authority is un-timed). **UNKNOWN whether KnowledgeOS needs a richer temporal model.**

### 10.2 Version lineage (mandate §10)

| Question | EKS | PKS | AIP |
|---|---|---|---|
| Can knowledge artifacts evolve? | ✅ (docs evolve; git history) | ✅ (M2 evolution canon: 14 operational ops) | ✅ (registry assets; docs) |
| Can claims be superseded? | ❌ no supersession mechanism | ✅ (supersede op; superseded-class terminal) | ⚠️ AIP-11 "supersede-never-in-place" declared, unenforced |
| Can decisions be revised? | ⚠️ append-only; no revision path; a recommendation can be re-decided (measured duplicate) | ✅ forward-only; supersede-not-revise | ⚠️ contradictory D1/D2 state unresolved |
| Old knowledge remain historically valid? | ✅ (git history for tracked corpus; ❌ for untracked authority record) | ✅ (predecessor keeps identity as history) | ⚠️ unproven |
| Reconstruct why a knowledge state changed? | ❌ no rationale-for-change record (transitions carry no reason except STOP) | ⚠️ review/disposition chain records reasons at governance level | ⚠️ contradictory |
| Provenance preserved? | ⚠️ partial — `humanActRef` prose, 63/126 unresolvable | ⚠️ partial — `verified{date,method}` in registry; X-08 "reconstructed not recovered" | ⚠️ partial — self-declared process identity, NOT attestable (INV-ATTR-2) |
| Lineage explicit or implicit? | implicit (seq chains; git) | explicit at governance level (M-series chain) | implicit |

**Determination:** **Supersession** is a genuine PKS concept (M2/M4/AP-3) and a declared AIP principle (AIP-11); **KnowledgeVersion** and **KnowledgeLineage** as first-class primitives are **NOT PRESENT** anywhere; **Provenance** is **partial** in all three. CAPPI's explicit model evolution (v17→…→v21) has a **governance-level analog** in PKS (review → disposition → change control → promotion) but **no artifact-level version primitive**.

---

## 11 · Fallback Analysis

### 11.1 Fallback mechanisms in current systems

| System | Fallback chain? | Evidence |
|---|---|---|
| EKS | **NO — anti-fallback.** The resolver **refuses** `UNRESOLVABLE` rather than degrading to a weaker interpretation; single-interpretation-authority (T-13). | EKS §18.2, §16.5 |
| PKS | **NO — fail-closed.** CAP-001: *"absence of evidence is never PASS."* | PKS §12, §20 |
| AIP | **NO.** No fallback; one blocking gate (db-safety) + advisory guards; V-3 fails safe (over-reports "not ready"). | AIP §9.3, §7.2 |

**Determination:** CAPPI's fallback chain (v21→v20→v18.1→expert floor) is **CONTRADICTED in design philosophy** by the current systems' fail-closed / refuse-rather-than-degrade stance. The systems' *only* mechanism-substitution is EKS's `KOS_MECHANISM_PATH` — which is explicitly *"NOT a hardened boundary"* and is disclosed, not concealed.

### 11.2 The fallback epistemic-status risk (mandate §11)

**Can a fallback mechanism silently change epistemic status?** The current systems avoid the *code-level* fallback risk by refusing to fall back. But the **documentation-level** analog is real and was observed in **P3-F1**: the AIP baseline asserted the unread MIGRATION-PLAN was *"not needed for current-state reconstruction"* — i.e., **unavailable evidence was treated as irrelevant**, which is a silent epistemic-status change. The correction (E-3) withdrew the clause. **Risk documented:** whenever evidence is unavailable, the fallback to "it doesn't matter" is a quiet shift from *known* to *assumed*; the §19 discipline (NOT FOUND ≠ NOT PRESENT ≠ NOT EVIDENCED ≠ UNKNOWN) is the countermeasure. **No solution proposed** — the discipline already exists and is binding.

---

## 12 · Diagnostic vs Authoritative State

### 12.1 What is authoritative vs diagnostic in each system

| System | Authoritative state | Diagnostic / derived (advisory) | Separation |
|---|---|---|---|
| EKS | `grants[]` + `transitions[]` (fold-derived state) | `DASHBOARD.md`, `knowledge-graph.md`, lint/check output, recommendation/decision/outcome/assessment JSONL, all resolver verdicts | **A — clean.** *"The record holds facts; every interpretation is recomputed on demand."* Derived state is never persisted. Read purity is structural (T-11). |
| PKS | governed registers + knowledge corpus | projections (AC-2), knowledge graph, validation reports, conformance/completeness (derived assessments) | **A — explicit.** AP-2 projections regenerable and non-authoritative; AC-2 is a terminal sink (DR-1); derived assessments carry no identity (AP-6). |
| AIP | authority record (grants, transitions) + registry | verification reports, findings, decision records, acceptance records (produced governance evidence), guard verdicts | **B/C — partial.** Produced records are distinguished from owned state (P3-F2); but the D1/D2 contradiction shows a produced record can conflict with the authoritative record. |

### 12.2 CAPPI's "WAE outside primary score" analog

CAPPI keeps WAE (a diagnostic) outside the primary score. The analogous separation in the current systems is **fully established**: diagnostics (dashboards, graphs, reports, projections, recommendations) are structurally separated from authoritative state in EKS and PKS, and partially so in AIP. **The principle "authoritative state vs analytical diagnostics" is ESTABLISHED (EKS/PKS) · PARTIALLY ESTABLISHED (AIP).**

### 12.3 Classification of potential diagnostic capabilities

| Candidate | EKS | PKS | AIP | Classification |
|---|---|---|---|---|
| Contradiction analysis | ⚠️ contradictions recorded (F) but not automated | ⚠️ X-register; not automated | ⚠️ D1/D2 surfaced; not automated | **ANALYTICS-CAPABILITY** (candidate) — none automated |
| Topology analysis | ❌ (cohesion graph is code-level) | ⚠️ C4 views are projections, non-runtime | ❌ | **ANALYTICS-CAPABILITY** (candidate) |
| Temporal analysis | ❌ (no time dimension) | ❌ (no timestamps) | ❌ | **ANALYTICS-CAPABILITY** (candidate) |
| Confidence analysis | ❌ (no confidence) | ⚠️ MCR-5 ceiling only | ❌ | **ANALYTICS-CAPABILITY** (candidate) |
| Impact analysis | ❌ | ❌ | ❌ | **ANALYTICS-CAPABILITY** (candidate) |
| Similarity | ❌ | ❌ | ❌ | **ANALYTICS-CAPABILITY** (candidate) |
| Predictive analytics | ❌ (recommendations are rule×fact, not predictive) | ❌ | ❌ | **ANALYTICS-CAPABILITY** (candidate) |

**Determination:** none of the analytics capabilities is implemented. They are **ANALYTICS-CAPABILITY candidates** and must **not be promoted into the kernel** (§16).

---

## 13 · Orthogonality / Double-Evaluation Analysis

### 13.1 CAPPI's orthogonality principle vs KnowledgeOS analog risks

CAPPI's principle: *"orthogonal concerns must remain orthogonal"* — reliability ≠ uncertainty, or the same concern is penalized twice. The KnowledgeOS analog risks (mandate §7) tested against evidence:

| Risk | Evidence | Assessment |
|---|---|---|
| Assurance evaluates something already evaluated elsewhere | EKS: 3× LCOM4 implementations (D) — same concern, 3 evaluators, no canonical one | **DOUBLE/TRIPLE EVALUATION — real** (EKS §20) |
| Governance evaluates something already owned by evidence | EKS: authority query unanswerable (prose scope, string equality); governance and evidence records separate | **NOT double-evaluation** — the two are *disjoint*; the defect is the missing join key (D-2), not double-counting |
| AI confidence treated as authority | EKS: AP-1 machine-unemittable verdicts; AIP: producer ≠ acceptor (R-34); AI process identity NOT attestable (EKS-07) | **RISK AVOIDED by construction** (EKS/PKS/AIP) |
| Structural validation treated as semantic correctness | All three: executable assurance is structural-only; PKS catalog explicitly structural | **RISK — structural is the only executable assurance; over-weighting possible** (C-partial) |
| Provenance reliability counted twice | no provenance metric exists | **NOT EVIDENCED** |
| Historical and current validity conflated | EKS/AIP authority un-timed (2/218); PKS forward-only supersession distinguishes them conceptually | **CONFLATION BY ABSENCE** (EKS/AIP); **distinguished** (PKS concept) |
| Execution state and governance state conflated | EKS Inv G ACTIVE ≠ AUTHORIZED; AIP I-4 registry ≠ authority | **EXPLICIT SEPARATION** — no conflation |
| Diagnostic analytics mutate authoritative state | EKS read purity (T-11); PKS projections regenerable (AP-2); AIP produced ≠ owned (P3-F2) | **RISK AVOIDED by construction** |

### 13.2 Findings

1. **The specific CAPPI double-dampening (reliability + uncertainty on the same axis) is NOT-EVIDENCED** in the current systems because neither axis is modelled. It is a **hypothesis for KnowledgeOS**, not a finding.
2. **The real double-evaluation defect in current systems is EKS's three LCOM4 implementations** — one semantic concern evaluated by three independent mechanisms with no canonical choice.
3. **The real orthogonality risk for a future KnowledgeOS** is that structural validation, provenance reliability, and "confidence" could each be introduced as separate axes and then partially overlap. The CAPPI warning is therefore a **valuable design warning**, even though the current systems neither support nor contradict it.

---

## 14 · Invariant Ownership Analysis

### 14.1 The principle under test

> **"Consistency is determined by invariant ownership, not by whether communication is event-driven."**

### 14.2 Evidence per system

| System | Consistency boundary | Communication style | Who owns the invariants | Evidence |
|---|---|---|---|---|
| EKS | work-item record (one file, atomic write, fold) | **no events** — shared-file + synchronous in-process calls | the `governance` role (recorded) + `assertTransitionAllowed` (single free function); 11/11 owned + tested | **SUPPORTED** (EKS §8, §7.8, §14) |
| PKS | capability vertical slices; governed registers | **no events** — CLI/CI + governance process | the Governance Invariant (one responsibility per artifact); DA/PA for authority | **SUPPORTED** (PKS §14, §12) |
| AIP | workflow/authority record | **no events** — registry-first + hooks | BC-7 (workflow engine) mechanically; everything else declared-only | **SUPPORTED** (AIP §7.2/7.4) |

### 14.3 Analysis

The principle is **supported** in the synchronous/shared-file context: all three systems achieve their strongest consistency **without any event-driven communication**, and the invariant owners are identifiable (governance role, workflow engine, governance discipline). **However**, none of the systems is distributed or event-driven, so the evidence establishes only that *event-driven communication is not necessary for these systems' consistency* — it does **not** establish that *invariant ownership alone is sufficient under distribution*. The generalization to the KnowledgeOS context (where event-driven integration is proposed in the Review Set) is **UNKNOWN**.

**Classification: PARTIALLY ESTABLISHED** (established for the non-distributed current context; generalization UNKNOWN).

---

## 15 · Candidate Cross-System Kernel Concepts

**Method:** only concepts with evidence in ≥2 systems, a shared (or plausibly shared) invariant, and a stable lifecycle were admitted. Each is a **candidate**; **none is promoted** (AMENDMENT 6: a kernel candidate must be demonstrated independent of the domain semantics of ≥1 concrete domain AND have evidence of reuse/justified reuse across >1 knowledge domain before promotion).

| Candidate | Existing evidence | Systems | Shared invariant | Shared lifecycle | Shared ownership | Shared authority semantics | Shared consistency requirement | Evidence strength | Classification |
|---|---|---|---|---|---|---|---|---|---|
| **C-1 Authority-as-recorded-reference-to-a-human-act** | EKS grant (126/126 humanActRef, registeredBy=governance) · PKS Charter grant (G-17) + promotion-as-Authority-act · AIP grant (G-2/R5b) | EKS · PKS · AIP | *the record references a human act; it never manufactures authority* (EKS §16.3; PKS AP-1; AIP G-2) | register → authorize → (revoke); forward-only | governance/DA/PA | knowledge feeds authority, never holds it | single authority writer; per-item record | **STRONG** (A in all three; same invariant stated in all three) | **KERNEL-CANDIDATE** |
| **C-2 State-as-fold / append-only forward-only log** | EKS transitions + fold (degenerate event sourcing) · AIP same engine · PKS register-row appends | EKS · PKS · AIP | state = fold; no derived state persisted; append-only; no delete path | append → fold → (supersede) | mechanism (workflow-state.php) / governance | n/a (mechanism) | atomic per-write (EKS tmp+rename); ⚠️ not atomic read-modify-write (P3-F1 E-1) | **STRONG** (EKS/AIP A; PKS register-appends A) | **KERNEL-CANDIDATE** |
| **C-3 Closed-verdict-vocabulary as published language** | EKS Verdict enum + machine-unemittable subset · PKS AP-8 + CAP-001 emittable subset · AIP guard verdicts | EKS · PKS · AIP | a finite, governed vocabulary is the only interchange; some values human-only | closed set; emittable subset | the emitting mechanism | verdicts confer no authority | vocabulary enforced at the boundary | **STRONG** (A in EKS/PKS; B in AIP) | **KERNEL-CANDIDATE** |
| **C-4 Assessment-conferring-no-authority** | EKS AP-1 · PKS AP-1 · AIP produced-records ≠ ownership (P3-F2) | EKS · PKS · AIP | an assessment/evaluation never grants authority; evidence feeds authority | per-run; no stateful lifecycle | the assessment mechanism | authority is external to assessment | assessment cannot change authority state | **STRONG** (A in EKS/PKS) | **KERNEL-CANDIDATE** |
| **C-5 Forward-only supersession / no in-place revision** | EKS append-only + no delete path · PKS AP-3 forward-only, superseded-class terminal · AIP AIP-11 (declared) | EKS · PKS · AIP | supersede-never-in-place; predecessor retains identity | supersede → predecessor-as-history | PKS governance; EKS mechanism | supersession is a governance act (PKS) | no in-place mutation | **STRONG** (EKS/PKS A; AIP declared-only) | **KERNEL-CANDIDATE** |
| **C-6 Per-kind register-scoped identity** | EKS registry CMP/AST + work-item ids · PKS AP-4 + M4 identity modes · AIP registry-first (R-17) | EKS · PKS · AIP | identity is per-kind and register-scoped; ids stable, paths change | mint → register → (retire); forward-only | registry governance | identity is not authority | uniqueness within register(ns); checked before minting (CAP-001) | **MODERATE** (EKS C; PKS A-governed/partial; AIP A) | **KERNEL-CANDIDATE** |
| **C-7 Regenerable non-authoritative projection** | EKS derived dashboards/graphs (regenerated) · PKS AP-2 + AC-2 terminal sink · AIP generated docs from registry | EKS · PKS · AIP | a projection is a function of its sources; never authoritative; no return path | regenerate → publish → discard | the projection mechanism | projections hold no authority | regenerable from sources | **MODERATE** (EKS A as pattern; PKS A-governed/not-realized; AIP A) | **KERNEL-CANDIDATE** (or PLATFORM-CAPABILITY) |
| **C-8 Epistemic-class discipline** | PKS Observed/Measured/Derived/Synthesized/Recommendation/Assembly/Hypothesis · EKS Observation≠Verdict | PKS (full) · EKS (partial) | every artifact carries an epistemic class; derived can never be Observed | class assigned at creation; not reclassified | PKS governance | classes feed authority, never hold it | a derivation commission may not classify anything Observed | **MODERATE** (PKS A; EKS B) | **DOMAIN-CONTEXT-CANDIDATE** (PKS-owned; not demonstrated EKS/AIP) |
| **C-9 Advisory vs blocking enforcement** | EKS warn-only hooks + non-blocking CI · PKS warn-only gates · AIP one blocking guard + advisory rest | EKS · PKS · AIP | advisory instrumentation never blocks; a small set may block | per-invocation | the platform | advisory ≠ authority | non-blocking by default | **MODERATE** (A in all three) | **PLATFORM-CAPABILITY** |
| **C-10 Honest-UNKNOWN as first-class answer** | EKS resolver UNKNOWN/AMBIGUOUS/UNRESOLVABLE (exit 0, tested) · PKS fail-closed · AIP U-register | EKS · PKS · AIP | *"I cannot know" is a valid, non-error outcome* | — | the interpreting mechanism | unknown ≠ unauthorized ≠ denied | absence of evidence never PASS | **STRONG in EKS; MODERATE elsewhere** | **KERNEL-CANDIDATE** (discipline, not mechanism) |

### 15.1 Summary of classifications

- **KERNEL-CANDIDATE (deserve explicit kernel-boundary investigation):** C-1 authority-as-recorded-reference · C-2 state-as-fold · C-3 closed-verdict-vocabulary · C-4 assessment-conferring-no-authority · C-5 forward-only-supersession · C-6 per-kind-register-scoped-identity · C-7 regenerable-non-authoritative-projection · C-10 honest-UNKNOWN.
- **DOMAIN-CONTEXT-CANDIDATE:** C-8 epistemic-class discipline (PKS-owned; needs a second domain).
- **PLATFORM-CAPABILITY:** C-9 advisory-vs-blocking enforcement.
- **NOT admitted:** any continuous-confidence / Bayesian / fallback concept (no cross-system evidence).

⚠️ **None of these is approved.** The AMENDMENT 6 progression (EKS capability → candidate primitive → cross-system equivalent → same invariant/lifecycle/authority/ownership → reusable abstraction → KERNEL CANDIDATE → DDD validation → kernel decision) is **not yet executed**. This investigation only marks which candidates have *enough* cross-system evidence to warrant that progression.

---

## 16 · Concepts That Should Remain Outside the Kernel

| Concept | External source | Evidence in current systems | Classification |
|---|---|---|---|
| Bayesian algorithms / calibrated-signal fusion | CAPPI | **none** (no continuous confidence anywhere) | **ANALYTICS-CAPABILITY** — must stay above the kernel |
| TDA / topological data analysis | Discussion A | **none** (cohesion graph is code-level, not kernel) | **ANALYTICS-CAPABILITY** |
| Machine-learning / NLP models | — | **none** in EKS/PKS/AIP core | **ANALYTICS-CAPABILITY / AI** — above kernel |
| Political-performance formulas | Discussion A | **none** (product-specific; not applicable to KnowledgeOS domain) | **PRODUCT-HYPOTHESIS** — outside |
| Scoring / quality formulas | CAPPI | **none** | **ANALYTICS-CAPABILITY** |
| Kafka / event bus / event sourcing as infrastructure | Review Set (proposed) | **NOT PRESENT** (EKS §14: no events; PKS §14: explicit negative; AIP: none). Degenerate event-sourcing *pattern* exists (C-2), but Kafka-style infra is **not** current | **INFRASTRUCTURE** — not current; excluded by plan §4 |
| Event sourcing as a *pattern* | — | present as state-as-fold (C-2) — but that is the append-only-log pattern, not a bus | **KERNEL-CANDIDATE** (C-2) / **INFRASTRUCTURE** for the transport |
| Rust / Spring Boot / Gradle / Cargo | — | **none** (EKS/PKS = PHP CLI; AIP = PHP + bash hooks) | **INFRASTRUCTURE / TECHNOLOGY** — explicitly out of scope (plan §4) |
| AI providers | — | EKS consumes AI-harness hooks; no provider abstraction | **ADAPTER/INTEGRATION** |
| UI / reporting / visualization | — | dashboards/graphs are derived projections | **DELIVERY / PLATFORM-CAPABILITY** (PKS AC-2) |
| Product / commercial logic | — | Product Primacy (AIP-14) keeps product outside platform | **PRODUCT** — outside |
| Confidence / uncertainty scalars (μ/τ/w) | CAPPI | **NOT PRESENT**; if introduced, they are analytical, not kernel | **ANALYTICS-CAPABILITY** (PROPOSED) |

**Do-not-reject-but-classify discipline:** none of these is automatically rejected; each is classified by evidence. Only C-2's *pattern* (append-only log) has kernel relevance; the *transport/infrastructure* forms do not.

---

## 17 · Hypothesis Register

| ID | Hypothesis | Evidence | Affected systems | DDD interpretation | Status | Falsification condition | What would change the conclusion |
|---|---|---|---|---|---|---|---|
| H-01 | Epistemic stages (Observation·Evidence·Claim·Confidence·Authority·Decision) should be separable | Observation/Evidence/Authority/Decision are distinct concepts in EKS/PKS/AIP; Claim and Confidence are NOT PRESENT | EKS · PKS · AIP | distinct concepts exist; boundary ownership unresolved | **PARTIALLY ESTABLISHED** | a system where two stages are provably the same concept | a second domain demonstrating the separation as a shared invariant |
| H-02 | Confidence ≠ Authority | EKS AP-1 + machine-unemittable verdicts; PKS AP-1 + promotion-as-Authority-act; AIP produced ≠ owned | EKS · PKS · AIP | two different ownership/authority semantics | **ESTABLISHED** (EKS/PKS) · **PARTIALLY ESTABLISHED** (AIP) | a case where a verdict grants authority | a counter-example in any current system |
| H-03 | Reliability ≠ Uncertainty (orthogonality) | neither axis modelled; no double-dampening evidence; EKS triple-LCOM4 is a different defect | none current | axes not modelled → orthogonality is a design warning, not a finding | **NOT-EVIDENCED** | a current system with both axes conflated | introducing both axes in KnowledgeOS and testing overlap |
| H-04 | Temporal validity: now vs at-time-T | EKS/AIP authority un-timed; PKS forward-only supersession without timestamps | EKS · PKS · AIP | temporal state is an owned invariant, currently unowned | **UNKNOWN** (needed?) · **ESTABLISHED** (that current systems lack it) | — | evidence that a temporal question is currently answerable |
| H-05 | Forward-only supersession / version lineage | EKS append-only; PKS AP-3/M2 canon; AIP AIP-11 | EKS · PKS · AIP | identity survives supersession as history | **PARTIALLY ESTABLISHED** (supersession: PKS/AIP; version primitive: none) | in-place revision observed | an implemented version/lineage primitive |
| H-06 | Fallback semantics must not silently change epistemic status | no code-level fallbacks (anti-fallback); P3-F1 shows the documentation-level risk | EKS · PKS · AIP | fallback is a degradation policy, not a state machine | **CONTRADICTED in design** (anti-fallback) · **RISK CONFIRMED at documentation level** (P3-F1) | a legitimate fallback in current systems | a knowledge domain where fail-closed is demonstrably wrong |
| H-07 | Diagnostics must not mutate authoritative state | EKS read purity (T-11); PKS projections regenerable (AP-2); AIP produced ≠ owned (P3-F2) | EKS · PKS · AIP | authoritative state and diagnostics are different consistency boundaries | **ESTABLISHED** | a diagnostic that mutates authoritative state | an observed violation |
| H-08 | Consistency is determined by invariant ownership, not event-driven communication | all three systems achieve consistency without events; invariant owners identifiable | EKS · PKS · AIP | invariant ownership is the actual consistency mechanism | **PARTIALLY ESTABLISHED** (non-distributed context only) | a distributed system where invariant ownership alone fails | a distributed KnowledgeOS deployment test |
| H-09 | A knowledge phenomenon should not collapse into one scalar | all systems use discrete verdicts, not scalars; no quality score exists | EKS · PKS · AIP | scalar collapse is absent by design | **SUPPORTED BY ABSENCE** (not a positive finding) | a system that correctly needs a scalar | evidence that a scalar is required and correct |
| H-10 | A kernel should protect identity·evidence·provenance·lifecycle·authority·invariants·temporal-state | identity/evidence/lifecycle/authority/invariants are implemented concerns in EKS/PKS/AIP; provenance partial; temporal-state weakest | EKS · PKS · AIP | the split matches existing authoritative-vs-advisory separation | **PROPOSED KERNEL BOUNDARY CANDIDATE** (outline supported; provenance+temporal weak) | a concern in the list that is better owned by a higher layer | evidence that provenance/temporal state are fully owned elsewhere |

---

## 18 · Evidence Register

Evidence types: CURRENT IMPLEMENTATION (T1) · CURRENT DOCUMENTATION (T5) · PERSISTED ARTIFACT (T3) · GOVERNANCE RECORD (T4) · HISTORICAL RECORD (T7) · ANALYTICAL INFERENCE (AN) · EXTERNAL RESEARCH (ER). Primary = repository; Secondary = ER.

| ID | Claim | Source | Evidence location | Evidence type | Confidence | Interpretation |
|---|---|---|---|---|---|---|
| E-01 | EKS implements Observation as fact-without-verdict and separates it from judgment | S-02 §6.1, §16.5 | `scripts/observations/`; collectors | CURRENT IMPLEMENTATION (primary) | high | Observation ≠ verdict is a designed, tested property |
| E-02 | EKS assessment confers no authority (AP-1) | S-02 §17.5 | `scripts/lib/EngineeringKnowledge/Shared/Domain/Verdict.php`; AP-1 | CURRENT IMPLEMENTATION (primary) | high | confidence ≠ authority, enforced by construction |
| E-03 | EKS authority is not temporally reconstructable (2/218 dated) | S-02 §11.3, §7.2 | `.claude/runtime/workflow/*.json` measured | PERSISTED ARTIFACT (primary) | high | decided_at/effective_from absent; temporal kernel claim weakest |
| E-04 | EKS has no events; shared-file integration; consistency via invariant ownership | S-02 §14, §18 | code absence (tier 1) | CURRENT IMPLEMENTATION (primary) | high | consistency achieved without event-driven communication |
| E-05 | PKS AP-1/AP-2: knowledge feeds authority; projections regenerable and non-authoritative | S-03 §9.1, §12 | AD-1 AP-1..AP-10 | GOVERNANCE RECORD (primary) | high | the separation of authority from knowledge/projection is governed |
| E-06 | PKS MCR-5 confidence ceiling; Operational Evidence ZERO-INDEPENDENT | S-03 §7, §16.3 | AD-1 §13.8; EAD-1 | GOVERNANCE RECORD (primary) | high | analytical confidence is bounded and separate from authority |
| E-07 | PKS forward-only supersession; predecessor retains identity (AP-3/M4) | S-03 §6.2, §13.2, §20 | M4; M2 canon | GOVERNANCE RECORD (primary) | medium | supersession is real at the concept level; no timestamps |
| E-08 | AIP invariant asymmetry: BC-7 mechanical; outside declared-only | S-04 §7.4, §17 | AIP §7.2/7.3 | CURRENT IMPLEMENTATION + GOVERNANCE RECORD (primary) | high (evidence-scoped, P3-F3) | assurance strong where executable, weak where prose |
| E-09 | AIP consumes knowledge ≠ owns knowledge ≠ is KnowledgeOS | S-04 §13.2/13.4; S-05 P3-F4 | AIP §13 | CURRENT DOCUMENTATION (primary) | high | the "organizational knowledge" stage is not AIP-owned |
| E-10 | P3-F1: density ≠ completeness; one-writer not structural | S-04 erratum; S-06 | MIGRATION-PLAN §1.3/§1.5 | CURRENT DOCUMENTATION (primary) | high | §19 lesson: corpus completeness must be established, not asserted |
| E-11 | None of the three systems models continuous confidence / Bayesian fusion / scalar quality | S-02 §16.5, S-03 §14, S-04 §4 | absence verified in baselines | ANALYTICAL INFERENCE from primary (primary-derived) | medium-high | CAPPI's numeric epistemic stack has zero current support |
| E-12 | CAPPI proposes μ/τ/w → fusion → quality → diagnostics; double-dampening warning | S-12 | external discussion | EXTERNAL RESEARCH (secondary) | n/a | generates hypotheses; never architecture |
| E-13 | Political-performance framework proposes multidimensionality, temporal validity, simulation, diagnostics | S-11 | external discussion | EXTERNAL RESEARCH (secondary) | n/a | generates hypotheses; never architecture |
| E-14 | EKS has three independent LCOM4 implementations (triple evaluation) | S-02 §20 | scripts/observations, lcom4_collector.py, Capabilities/Cohesion | CURRENT IMPLEMENTATION (primary) | high | a real double/triple-evaluation defect — the closest current analog to the CAPPI double-dampening risk |
| E-15 | The EKS baseline is BROKEN/INCOMPLETE (ends at §20; registers absent) | S-02 banner | file structure measured | CURRENT DOCUMENTATION (primary) | high | "EKS does not evidence X" = UNKNOWN, not NOT-PRESENT |

---

## 19 · Contradiction Register

| ID | Concepts | Evidence | Nature of contradiction | Impact | Resolution |
|---|---|---|---|---|---|
| X-01 | EKS `Session` (4 senses) vs PKS `Decision`/`Verdict` vs AIP `role` (3 senses) | S-02 §6.2; S-03 §5; S-04 §4 | EKS semantics ≠ PKS semantics ≠ AIP semantics; same word, different concepts | cross-system comparison risk; name-collision is a DDD vocabulary problem | **OPEN** — must not normalize (Stage 4 discipline) |
| X-02 | "EKS authority" vs "PKS authority" vs "AIP authority" | S-02 §16; S-03 §15; S-04 §6 | shared invariant (records reference human act) but different implementations (prose scope vs governed DA/PA vs registry-first) | kernel-candidate C-1 rests on shared invariant; identity ambiguity U-02 unresolved | **REQUIRES FUTURE VALIDATION** (Stage 4 + AMENDMENT 6) |
| X-03 | Confidence ≠ Authority: established (EKS/PKS) vs not-modelled (all three have no confidence concept) | E-02/E-05/E-06/E-11 | the separation is established by *absence* of confidence, not by a modelled confidence axis | the H-02 status is partly by-absence | **OPEN** — whether a confidence axis should exist is UNKNOWN |
| X-04 | Evidence ≠ Knowledge: EKS `Evidence` 5 senses vs PKS `Evidence` as governed concept | S-02 §6.2; S-03 §5 | EKS semantics ≠ PKS semantics | cross-system evidence concept is not single | **OPEN** |
| X-05 | Density/completeness claim vs P3-F1 correction | S-02 §11.1 (density as detectability) vs S-04 E-1 | current implementation ≠ earlier documentation/inference | the EKS baseline's density claim is over-stated | **RESOLVED** (P3-F1 erratum on AIP; EKS baseline unchanged/broken) |
| X-06 | "Exactly one writer" vs write-capable substitution path | S-04 §8.1/§9.3 pre-erratum vs MIGRATION-PLAN §1.5 P-4 | current implementation ≠ architecture documentation | authority model is primary-writer, not structural single-writer | **RESOLVED** (P3-F1 E-2) |
| X-07 | D1/D2 decision state: SELECT B′/ADOPT vs NOT SELECTED | S-04 §6.4 | governance record conflicts with tracked registration | operative recorded state vs registration review; surfaced, not reconciled | **OPEN** — surfaced, not reconciled |
| X-08 | PKS "not software" vs implemented capability layer | S-03 §2.2 vs §9.2; PKS X-02 | current documentation ≠ current implementation | both true at different levels; no source reconciles | **OPEN** (recorded in PKS baseline) |
| X-09 | PROMOTED logical architecture (AC-1/AC-2) vs implemented capability layer | S-03 §9.1 vs §9.2; PKS X-10 | architecture proposal ≠ implementation | conformance specified-but-absent | **OPEN** |
| X-10 | Diagnostic ≠ Decision: established in EKS/PKS vs the P3-F1 documentation-level fallback | E-02/E-07 vs E-10 | principle held in code; violated in the documentation process (unread artifact called "not needed") | the §19 discipline is the countermeasure | **OPEN** — a process risk, not a code defect |
| X-11 | Historical ≠ Current: EKS/AIP un-timed authority vs PKS forward-only supersession | S-02 §11.3; S-03 §13.2; S-04 §8.1 | EKS/AIP conflate by absence; PKS distinguishes conceptually | temporal kernel claim weakest | **REQUIRES FUTURE VALIDATION** |
| X-12 | Reliability ≠ Uncertainty: CAPPI orthogonality vs current systems' absence of both axes | E-11/E-12/E-14 | external elegant model vs zero current support; EKS's real defect is triple-evaluation, not double-penalization | the orthogonality warning is a hypothesis, not a finding | **OPEN** |
| X-13 | EKS baseline's own §21–§25 cross-references vs their absence | S-02 banner | file's own structure contradicts its claims | P1 deliverable invalid; "EKS not found" = UNKNOWN | **OPEN** — HPA must rule (producing session supplies or HPA rules absence) |

---

## 20 · UNKNOWN Register

| ID | Unknown | Why unknown | Evidence basis |
|---|---|---|---|
| UNK-01 | Whether KnowledgeOS needs a CLAIM/INTERPRETATION stage | not a named concept in any system; derived outputs exist but are not first-class | S-02 §6.3; S-03 §6.1; S-04 §4 |
| UNK-02 | Whether KnowledgeOS needs instance-level CONFIDENCE (τ) or reliability weight (w) | not present anywhere; the systems' honest-UNKNOWN suggests a deliberate anti-probabilistic stance | E-11 |
| UNK-03 | The EKS/PKS/AIP identity relationship (same/different/overlapping) | U-02/X-01 unresolved; Stage 4 territory | S-03 U-02, X-01 |
| UNK-04 | Whether the shared authority invariant (C-1) is SAME or RELATED across systems | identity ambiguity + different implementations | X-02 |
| UNK-05 | What the EKS baseline's missing §21–§25 contain | broken/incomplete P1 file; registers absent | S-02 banner |
| UNK-06 | Whether effective_from/effective_until/superseded_at are needed | not present anywhere; supersession concept exists (PKS) without timestamps | H-04; E-07 |
| UNK-07 | Whether invariant-ownership-sufficiency generalizes to distributed/event-driven KnowledgeOS | current systems are non-distributed | H-08 |
| UNK-08 | Whether fallback semantics should ever enter KnowledgeOS | current systems are anti-fallback; only the documentation-level risk was observed | H-06 |
| UNK-09 | Whether the six-role model maps to any platform service/agent | role ≠ agent ≠ service (binding) | S-04 U-4 |
| UNK-10 | Whether a scalar quality score is ever correct for a knowledge element | no scalar exists; the external principle says avoid scalar collapse | H-09; A-01 |
| UNK-11 | Who owns whole-system conformance (PKS), identity discipline (PKS G-3), and the four unowned AIP capabilities | ownership vacuums recorded as absent, never filled | S-03 §4, §22; S-04 §2.3 |
| UNK-12 | The act sequence that took AIP D1/D2 from NOT SELECTED to SELECT B′/ADOPT | contradiction surfaced, not reconciled | S-04 U-3 |

---

## 21 · DDD Interpretation

### 21.1 DDD test applied to the epistemic pipeline stages

| Concept | Language | Invariants | Lifecycle | Ownership | Authority | Consistency boundary | Persistence responsibility | Change frequency | Dependency direction | Classification |
|---|---|---|---|---|---|---|---|---|---|---|
| Observation | EKS-specific + PKS-specific | no verdict; fact-only (EKS); epistemic class (PKS) | stateless per-change (EKS); per-record (PKS) | EKS: unregistered; PKS: governed | none | per-stream | EKS JSONL; PKS governed records | high (observation streams) | facts → consumers | **RELATED CONCEPTS** (different domains) |
| Evidence | overloaded (EKS); governed (PKS); classed (AIP) | input-to-judgment (all) | per-artifact | partial | none by itself | per-record | EKS/PKS git-tracked; AIP produced | medium | evidence → assessment | **RELATED CONCEPTS** |
| Claim/Interpretation | **absent** | — | — | — | — | — | — | — | — | **UNKNOWN** |
| Confidence | **absent** | — | — | — | — | — | — | — | — | **UNKNOWN** |
| Authority | Grant (EKS/PKS/AIP) | records reference a human act; never manufactures authority | register → authorize → (revoke); forward-only | governance / DA/PA | is the authority | per-work-item record | EKS/AIP gitignored (D); PKS governed | low | authority → record | **SAME CONCEPT?** shared invariant; **RELATED** pending Stage 4 |
| Decision | overloaded (EKS); G-1 forward-only (PKS); grant-acts (AIP) | recorded rationale; forward-only supersession (PKS) | decide → record → (supersede) | human authority | derived from authority | per-record | varies | low | decision → record | **RELATED CONCEPTS** |
| Organizational knowledge | corpus (EKS/PKS); consumed-not-owned (AIP) | governed placement (ES-005.1) | PROPOSED→…→FROZEN (PKS); lifecycle.md (EKS) | EKS/PKS; AIP NOT | knowledge feeds authority | corpus | git-tracked | medium | knowledge → authority | **RELATED CONCEPTS** |

**Do not merge concepts merely because names match:** `confidence`, `reliability`, `authority` are **NOT** one thing — `authority` has real cross-system evidence; `confidence` and `reliability` have none. `Evidence` in EKS (5 senses) is not the same concept as `Evidence` in PKS (governed substrate). `Decision` in EKS (developer response to a recommendation) is not the same as `Decision` in PKS (a governed, forward-only-supersedable selection). **The DDD discipline confirms: name-collision ≠ concept-equality.**

### 21.2 Bounded-context warnings

- **No bounded context is inferred from the existence of a named concept.** EKS's bounded contexts are NOT ESTABLISHED (candidates only); PKS has two ACCEPTED contexts (CBC-1 Knowledge Assessment, CBC-2 Knowledge Projection) from its own governed disposition; AIP's BC-1..BC-7 are declared candidates, most not built.
- **The "confidence ≠ authority" separation is a candidate boundary, not an established one.** The systems separate them by record structure (EKS two-never-merged records; PKS four orthogonal axes), which is *consistent with* distinct bounded contexts but does not prove them.

---

## 22 · Kernel-Boundary Assessment

### 22.1 The proposed boundary under test

```
KNOWLEDGEOS KERNEL protects:  identity · evidence · provenance · lifecycle · authority · invariants · temporal state
higher layers provide:        assurance · analytics · inference · simulation · AI · visualization · delivery
```

### 22.2 Evidence check per concern

| Kernel concern | EKS | PKS | AIP | Supported? |
|---|---|---|---|---|
| **Identity** | registry CMP/AST, work-item ids (C) | AP-4/M4 identity model (A-governed) | registry-first R-17 (A) | ✅ **supported** |
| **Evidence** | transition log + verification corpus (A) | governed evidence concept (A) | evidence classes A/B/C (A) | ✅ **supported** |
| **Provenance** | partial — humanActRef prose, 63/126 unresolvable (D) | partial — verified{date,method}; X-08 reconstruction claims (C) | partial — self-declared, NOT attestable (D) | ⚠️ **PARTIAL — weakest-supported alongside temporal** |
| **Lifecycle** | work-item lifecycle incomplete (E for closure) | M4 three-state classes + M2 canon (A-governed) | workflow lifecycle mechanical (A) | ✅ **supported** (with EKS closure gap) |
| **Authority** | grants, recorded (A) | DA/PA + AP-1 (A) | G-2 + registry-first (A) | ✅ **strongest-supported** |
| **Invariants** | 11 owned+tested workflow invariants (A) | AP-1..10 + Governance Invariant (A-governed) | I-1..G-2 mechanical (A) | ✅ **strongest-supported** |
| **Temporal state** | 2/218 dated; not reconstructable (E) | no timestamps; forward-only concept (E as data) | 2/218 dated (E) | ❌ **NOT supported — weakest concern** |

### 22.3 Assessment

- **The split is supported in outline:** all three systems already separate authoritative-record concerns (identity, evidence, lifecycle, authority, invariants) from advisory/analytical concerns (assurance, analytics, visualization, delivery). This is the EKS "record holds facts; interpretations recomputed" property, the PKS "projections regenerable and non-authoritative" property, and the AIP "produced records ≠ owned authority" property.
- **Two of the seven kernel concerns are weakly supported:** **provenance** (partial in all three) and **temporal state** (essentially absent as data). A future kernel that claims to protect "provenance" and "temporal state" must first demonstrate where those invariants are owned today — they largely are **unowned**.
- **The higher-layer list is consistent with evidence:** assurance, analytics, inference (recommendations), AI, visualization (dashboards/projections), delivery (AC-2 terminal sink) are all present *above* the authoritative core in at least one system.
- **Conclusion: PROPOSED KERNEL BOUNDARY CANDIDATE.** The evidence supports the *shape* of the boundary (authoritative core vs advisory higher layers) but does **not** establish the kernel itself. The boundary remains a **candidate for a future kernel-boundary decision** — not a decision.

---

## 23 · What Is Established

1. **Confidence ≠ Authority** is established (EKS, PKS) and partially established (AIP) as a separation of analytical assurance from organizational authority. **(H-02 ESTABLISHED)**
2. **Diagnostics must not mutate authoritative state** is established: EKS read purity (structural), PKS regenerable non-authoritative projections, AIP produced ≠ owned. **(H-07 ESTABLISHED)**
3. **State-as-fold / append-only / forward-only** is established as an implemented pattern in EKS/AIP and a governed discipline in PKS. **(C-2)**
4. **Consistency is achieved without event-driven communication** in all three systems; invariant ownership is the actual consistency mechanism in the non-distributed context. **(H-08 PARTIALLY ESTABLISHED)**
5. **The epistemic stages Observation · Evidence · Authority · Decision are distinct concepts** in at least one system each — but with system-specific semantics. **(H-01 PARTIALLY ESTABLISHED)**
6. **Authority is recorded, never manufactured, and not enforced** — the cross-system invariant with the strongest evidence. **(C-1)**
7. **A closed verdict vocabulary** is the interchange language in EKS and PKS, with machine-unemittable human-only values. **(C-3)**
8. **Forward-only supersession** is a real PKS concept (and a declared AIP principle). **(C-5)**
9. **Per-kind register-scoped identity** is governed in PKS and registry-first in AIP. **(C-6)**
10. **The current systems are anti-fallback and fail-closed** — the opposite of a fallback-chain design. **(H-06 CONTRADICTED in design)**
11. **No continuous confidence / calibration / Bayesian fusion / scalar quality exists anywhere.** **(H-09 SUPPORTED BY ABSENCE; E-11)**
12. **The P3-F1 lesson**: corpus completeness must be established, not asserted; "not needed" is an epistemic-status change. **(E-10)**

## 24 · What Is Proposed

1. The **epistemic pipeline** (Observation → Evidence → Claim → Confidence → Authority → Decision → Organizational Knowledge) as a *candidate* separation for a future KnowledgeOS — **PROPOSED**, with Claim and Confidence unestablished.
2. The **orthogonality principle** (reliability ≠ uncertainty) as a *design warning* for when/if confidence axes are introduced — **PROPOSED**, NOT-EVIDENCED today.
3. The **temporal model** (observed_at · created_at · decided_at · effective_from · effective_until · superseded_at) as a *future* KnowledgeOS concern — **PROPOSED**, none of the latter five exists in current systems.
4. The **version-lineage primitive** (KnowledgeVersion · KnowledgeLineage) — **PROPOSED**; only governance-level lineage (PKS review chain) exists.
5. The **kernel-boundary candidate** (kernel protects identity·evidence·provenance·lifecycle·authority·invariants·temporal-state; higher layers provide assurance·analytics·inference·simulation·AI·visualization·delivery) — **PROPOSED KERNEL BOUNDARY CANDIDATE**, outline supported, provenance+temporal weak.
6. The **seven KERNEL-CANDIDATES** (C-1..C-7, C-10) — **PROPOSED candidates for the AMENDMENT 6 progression**, none promoted.

## 25 · What Is Rejected

1. **CAPPI's numeric epistemic stack as a kernel primitive** (μ/τ/w → Bayesian fusion → quality score): **NOT-SUPPORTED** — zero current-architecture evidence; the systems' honest-UNKNOWN philosophy is its inverse. (Best case of *"an elegant principle not supported by EKS/PKS/AIP."*)
2. **Fallback chains** (CAPPI v21→v20→expert floor) as a KnowledgeOS mechanism: **CONTRADICTED in design** by the anti-fallback, fail-closed stance of all three systems.
3. **Event-driven / Kafka / event sourcing as infrastructure**: **NOT-PRESENT** in current systems; the append-only-log *pattern* (C-2) is distinct from the transport.
4. **TDA / Bayesian / ML / NLP / scoring formulas as kernel**: **ANALYTICS-CAPABILITY**, above the kernel.
5. **Political-performance formulas**: **PRODUCT-HYPOTHESIS**, outside the knowledge domain.
6. **Rust / Spring Boot / Gradle / Cargo / AI providers / UI / reporting / product-commercial logic**: **INFRASTRUCTURE / ADAPTER / DELIVERY / PRODUCT** — outside the kernel by evidence and by plan §4.
7. **Name-based concept merging**: any inference that `confidence` = `reliability` = `authority`, or `evidence`(EKS) = `evidence`(PKS), is **REJECTED** by the DDD discipline.

## 26 · What Remains Open

1. Whether KnowledgeOS needs **Claim/Interpretation** and **Confidence** stages at all. (UNK-01/02)
2. The **EKS/PKS/AIP identity relationship** and whether the shared authority invariant is SAME or RELATED. (UNK-03/04; U-02/X-01)
3. What the **broken EKS baseline's missing §21–§25** contain, and whether "EKS not found" conclusions survive an exhaustive corpus check. (UNK-05; §19)
4. Whether **effective_from/effective_until/superseded_at** are needed, and who would own them. (UNK-06)
5. Whether **invariant-ownership-sufficiency** generalizes to distributed/event-driven KnowledgeOS. (UNK-07)
6. Whether **fallback semantics** should ever enter KnowledgeOS, and under what epistemic-status safeguards. (UNK-08)
7. Who owns the **unowned invariants** today (conformance, identity discipline, four AIP capabilities, provenance, temporal state). (UNK-11)
8. The **D1/D2 act sequence** in AIP. (UNK-12)
9. Whether a **scalar quality score** is ever correct for a knowledge element. (UNK-10)

## 27 · Recommended Next Investigation

Dependency-ordered research agenda (each step presupposes the previous; all outputs remain PROPOSED · NON-AUTHORITATIVE):

1. **R-1 — Complete the EKS evidence corpus (UNBLOCKING).** Resolve the broken P1 file: either supply §21–§25 (UNKNOWN register + contradiction register + special questions) or have the HPA explicitly rule their absence. **Without this, every "EKS does not evidence X" is UNKNOWN (§19).** [precondition for R-2..R-6]
2. **R-2 — HPA re-review of the corrected P3 baseline** and, on approval, **Stage 4: EKS–PKS–AIP Landscape** (the proper home for the identity question U-02/X-01 and for the SAME/RELATED classification of C-1..C-10). This is already the EP-01 P4 gate. [consumes R-1]
3. **R-3 — AMENDMENT 6 kernel-candidate progression for C-1..C-10.** For each candidate, execute the eight gate questions and the promotion rule (domain-independence ≥1 domain + reuse evidence >1 knowledge domain). Output: KERNEL-CANDIDATE / OPEN / NOT KERNEL. [consumes R-2]
4. **R-4 — Orthogonality impact study.** Before any confidence/reliability axis is ever designed, model the orthogonal axes that *do* exist (authority ⊥ status ⊥ maturity ⊥ adoption in PKS; lifecycle ≠ authority in EKS) and test where a new axis would overlap. Use the CAPPI double-dampening warning as a checklist, not a design. [consumes R-2]
5. **R-5 — Temporal-state investigation.** Establish what "what is true now" vs "what was authoritative at time T" would require, given that current authority is un-timed. Do **not** design the temporal model; determine which current invariants would be violated by adding time. [consumes R-2/R-3]
6. **R-6 — Fallback-epistemic-status study.** Investigate the documentation-level fallback risk observed in P3-F1 across the wider corpus (where else is unavailable evidence treated as irrelevant?), before any code-level fallback is contemplated. [consumes R-1]
7. **R-7 — DDD validation phase** (the plan's deferred bounded-context/aggregate validation), using C-1..C-10 and the epistemic-pipeline analysis as input — **after** R-2/R-3. [consumes R-3]

---

## Final Discipline

This investigation does not define the KnowledgeOS kernel.

It identifies evidence-supported candidates and unresolved questions for a future kernel-boundary decision.

No kernel classes, kernel modules, kernel APIs, technology choices, implementation code, migration plans, or ADRs adopting the kernel were created. The purpose was to improve understanding before the eventual KnowledgeOS architecture decision. The guiding principle observed throughout:

**DISCOVER → COMPARE → VALIDATE → CLASSIFY → ONLY THEN DESIGN.**

---

## Traceability

- **Commission:** HPA-commissioned DeepSeek investigation (verbatim mandate, §1–§23).
- **Primary inputs:** EP-01 plan `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` · EKS baseline `20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md` (BROKEN/INCOMPLETE) · PKS baseline `20260821-2229-PKS-Current-Architecture-Baseline-Stage-2.md` · AIP baseline `20260821-2259-AIP-Current-Architecture-Reconstruction-Stage-3.md` (P3-F1 corrected, commit `6034db5c`) · HPA reviews P2/P3 (`…P2-pks-baseline-hpa-review.md`, `…P3-aip-reconstruction-hpa-review.md`) · independent verification `…P3-findings-independent-verification.md`.
- **Secondary inputs (hypotheses only):** CAPPI v21 discussion · multi-dimensional political-performance discussion.
- **Boundaries honored:** no kernel design · no bounded-context decision · no technology decision · no implementation plan · no ADR · no EKS/PKS rewrite proposal · no Stage-4 landscape construction · EKS incompleteness recorded as UNKNOWN · AIP read-only · methodology freeze respected.
- **Status:** **INVESTIGATION · EVIDENCE-BASED · PROPOSED · NON-AUTHORITATIVE · NOT ADOPTED.** Next actor: Human Principal Architect.
