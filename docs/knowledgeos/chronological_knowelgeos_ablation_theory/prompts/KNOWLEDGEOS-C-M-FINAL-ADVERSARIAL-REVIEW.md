# KNOWLEDGEOS — C-M FINAL ADVERSARIAL REVIEW

| | |
|---|---|
| **Kind** | an adversarial review of **DES** = `prompts/KNOWLEDGEOS-MINIMUM-SCIENTIFIC-RESEARCH-CYCLE.md` and **REG** = `prompts/KNOWLEDGEOS-C-M-REGISTRATION-AND-FALSIFICATION-REVIEW.md` (`facd9cc55`). ⚠ PROPOSED, authority: generated |
| **Commission** | human, 2026-09-26, "KNOWLEDGEOS — FINAL C-M SCIENTIFIC ADVERSARIAL REVIEW" |
| **Effect** | **where this review differs from REG, it supersedes REG's registration** (REG stays unchanged as a record, apart from a pointer). Nothing is frozen, generated, implemented or read. The architecture-minimization decision stays frozen; this only shrinks C-M |
| **Method** | all quantitative statements below use **generator arithmetic and the BH multiplicity boundary only**. The detector was never run, so no threshold, regime or N is tuned to detector performance |

---

## Headline findings

| # | Finding | Consequence |
|---|---|---|
| **K-1** | **REG's positive regime cannot pass:** its per-edge co-occurrence signal is z ≈ **2.47** standard deviations above chance, **below** the z ≈ **3.50** a per-pair test needs to clear BH's first threshold at q\* = 0.10 over m = 435 pairs. The experiment would almost surely REFUTE because the *regime* is below the multiplicity detection boundary, not because the procedure is faulty | the regime is redefined by a **stated signal-to-noise target** (§3.3) |
| **K-2** | **Chain vs fork is not observationally equivalent under REG's renderer.** Pairwise co-mention rates are equal, but marginal document frequencies differ: chain (1.7, 1.7, 1.0) vs fork (1.0, 2.4, 1.0), in units of 1/K, with ρ = 0.7. The renderer puts a centre concept's *parents* into its documents, so a node's frequency encodes its number of children. Markov equivalence is a property of distributions factorizing over a DAG; this renderer is not such a distribution. **REG X-1's correction was itself wrong** | replaced by a construction that is non-identifiable **by construction** (§4) |
| **K-3** | 13 primary criteria are more than the four scientific questions need | **3 primary claims (5 components) + 1 deterministic check** (§6) |
| **K-4** | N = 400 everywhere was a worst-case width, not a power derivation | N derived per cell: **200 / 200 / 400 / 50** (§7) |

---

## 1. The exact claim C-M can establish

Let **𝒫 = (G, R, D0, S, A)** be the frozen composite procedure:

| Symbol | Component |
|---|---|
| G | the generator (§3) |
| R | the skeleton renderer (§3.1) |
| D0 | the lexical detector (§12): hypergeometric p-values + BH at q\* = 0.10 |
| S | the scorer |
| A | the analysis and decision rule (§6) |

If C-M **SURVIVES**, it establishes, with the stated one-sided 95 % confidence and at the recorded independence class, **exactly this proposition**:

> For worlds drawn from G with parameters θ\* (§3.3: K = 30, edge probability 0.10, n_doc = 800, η = 0.7, λ = 1, design signal z ≈ 5):
> - **(C1) calibration:** under the fixed-margin independent-placement null, 𝒫 reports ≥ 1 edge in at most 10 % of worlds;
> - **(C2) recovery:** at corruption L0, 𝒫's mean per-world adjacency recall is ≥ 0.80 and its mean per-world false-discovery proportion is ≤ 0.20;
> - **(C3) robustness:** the same two bounds hold at corruption L2 (synonymy 0.20, duplication 0.10);
> - **(check) restraint:** on orientation-unobservable worlds, 𝒫 asserted no edge direction (a deterministic implementation property of D0).
>
> The computation was reproduced by a verifier of class X.

## 2. Claims C-M can never establish

- anything about real corpus files, or about whether co-occurrence corresponds to a KnowledgeOS relationship (external validity is intrinsic to C-H);
- anything about KnowledgeOS theory (no SA-level, S2 L-level or M-level change);
- that D0 works at other θ, at lower signal, at K ≠ 30, or with other dependence structures;
- that BH controls FDR in general (only: in this null and these positive regimes, empirically);
- that direction can be inferred (the NI cell makes direction unobservable by construction);
- that the *generator* is scientifically adequate (verification reproduces computation, not adequacy);
- that any ML or LLM detector would do better or worse;
- that "the method works", or that "the detector is correct".

---

## 3. Mathematical audit of the generator

### 3.1 The renderer — replaced by a skeleton renderer

REG's child-centred renderer leaks orientation through marginals (K-2). **Replacement R:**
- A latent undirected skeleton S (Erdős–Rényi, K concepts, edge probability p), with a latent orientation drawn independently and **used only for lineage sentences**.
- Each document is an **edge document** with probability η: an edge {u, v} of S drawn uniformly, and both endpoints included. Otherwise it is a **singleton document**: one concept drawn uniformly.
- Then Poisson(λ) noise concepts, drawn uniformly.
- In an edge document, with probability s, a lineage sentence follows the latent orientation.
- The observation distribution is a function of **(S, orientation-through-lineage-only)**.

### 3.2 Null generator — made exact

REG's null ("same frequencies, independently across documents") is sharpened to **fixed-margin independent placement**:
- Take the margins n_x from a paired positive world.
- Place each concept in a **uniformly random n_x-subset** of documents, **independently across concepts**, with no lineage sentences.

**Distinguishing the assumptions:**

| Property | Holds in this null? | Why it matters |
|---|---|---|
| marginal frequency preservation | yes, by construction | the null is as dense as the positive world, so it is not trivially empty |
| document exchangeability | yes, for each concept's set given n_x (uniform subsets) | required for the hypergeometric null |
| pairwise independence of concept sets | yes, by construction | required |
| conditional independence given margins | yes: |S_x ∩ S_y| given (n_x, n_y) ~ Hypergeom(n_doc, n_x, n_y) **exactly** | ⇒ **the p-value is exact** (discrete, hence conservative: P(p ≤ α) ≤ α) |
| independence *across pairs* | **no**: p_xy and p_xz share S_x | BH's FDR guarantee needs independence or PRDS; **PRDS is not established here.** C1 is therefore a **genuine empirical test**, not a restatement of a theorem |
| dependence induced by a document-centre generator | none: the null has no centres (REG's centre-induced dependence is gone) | removes the REG A-3 attack for the null |

**In positive worlds, non-edge pairs are not exactly hypergeometric** (edge documents couple concept sets). There the FDR is measured directly against the truth, so no p-value exactness is claimed.

### 3.3 Positive regime — set by a stated signal target, not by detector performance

**Detector-free signal quantity.** With |E| ≈ p·C(K, 2) edges and concept frequency f = (2η + (1 − η) + λ)/K:
- per-edge excess co-occurrence μ_E = n_doc·η/|E|;
- chance co-occurrence μ_0 = n_doc·f²;
- **z = μ_E/√μ_0.**

**Detection boundary (any per-pair test at this multiplicity):** z_BH = Φ⁻¹(1 − q\*/m) ≈ **3.50** for m = 435.

| Setting | z |
|---|---|
| REG (child-centred, n_doc = 200, λ = 2) | **2.47**, below the boundary ⇒ K-1 |
| skeleton R, λ = 1, n_doc = 200 / 400 / 800 / 1000 | 2.53 / 3.58 / **5.06** / 5.65 |
| skeleton R, λ = 2, n_doc = 800 | 3.69 |

**Registered θ\*:** K = 30, p = 0.10, n_doc = 800, η = 0.7, λ = 1, s = 0.5 ⇒ **z ≈ 5.06**, well above the boundary.

**This regime is intentionally easy, and is declared so.** C-M's purpose is to demonstrate **one leakage-controlled, falsifiable, independently reproducible cycle**. It is not a stress test of D0. A pass at z ≈ 5 says only that the pipeline works where any sound detector should succeed. **The validation scope carries z.**

**Secondary diagnostic** (never decisive): recall/FDR curves over z ∈ {2.5, 3, 3.5, 4, 5, 6}, obtained by varying n_doc. These show where D0 breaks relative to the boundary.

**The approximation is stated.** z is a design average; degree heterogeneity makes per-edge z vary. The scope names the design z, not a per-edge guarantee.

---

## 4. Identifiability audit

**Principle:**

> different latent explanations + the same observable distribution = a non-identifiable distinction.

- **Construction (NI cell).** R with **s = 0**. The observation kernel is then a function of the skeleton S **only**. Hence **any two orientations of S induce identical observation distributions, exactly, by construction.** This needs no appeal to Markov equivalence.
- **What it tests.** Whether the procedure asserts a direction it cannot know.
- **For D0**, directions are asserted only from lineage sentences, so with s = 0 the assertion rate is **0 by specification**. The NI cell is therefore a **deterministic implementation check** (rate must equal 0; otherwise an INSTRUMENT fault), not a statistical claim.
- **For a future detector that may infer direction** (D1–D4, §9), the NI cell becomes a **primary statistical question** with a registered tolerance. The harness keeps the cell for that reason.
- **Consequence for positive worlds.** Under R, orientation information exists **only** in lineage sentences. Direction metrics are secondary for D0.

---

## 5. Null-model audit

| Question | Answer |
|---|---|
| Can the machinery recover "structure" from no-structure worlds? | tested by **C1**: the proportion of null worlds with ≥ 1 reported edge. Under the complete null, FDR = P(V ≥ 1), so **C1 is exactly the FDR claim D0 makes** |
| Is the p-value exact? | yes, in the null (§3.2); not claimed in positive worlds |
| Why can C1 fail although each p-value is exact? | BH over **dependent** pair p-values (shared sets) lacks a proven guarantee here. A failure is a real finding about 𝒫 |
| What is deferred | the BY correction (valid under arbitrary dependence; it lowers power by ≈ Σ1/i ≈ 6.65 at m = 435). If wanted, it is a **separate frozen hypothesis**, never a post-hoc switch |
| Secondary diagnostics | reported-edge rate · largest spurious component · KS uniformity of null p-values · a matched-null (shuffle) comparison for P0 |

---

## 6. Statistical audit — reduced to three primary claims

**The four scientific questions map as follows:**

| Question | Form in C-M | Components |
|---|---|---|
| false structure under the null | **C1 (calibration)** — a separate claim from recovery (commission §7: logically distinct; instrument behaviour under H0 vs recovery under H1) | 1: P(≥ 1 edge) ≤ 0.10 |
| known-structure recovery | **C2** at L0 | 2: mean recall ≥ 0.80 **and** mean FDP ≤ 0.20 |
| robustness under corruption | **C3** at L2 | 2: the same at L2 |
| restraint under non-identifiability | **deterministic check** for D0 (§4) | 0 statistical components |

**Everything else is SECONDARY DIAGNOSTIC:**
- L1 and L3;
- the z sweep;
- SHD;
- direction accuracy;
- calibration curves;
- the matched null;
- the spurious-component size;
- the unseen-parameter setting — **REG's U cells are dropped**. D0 fits no parameter on dev (q\* is fixed), so a parameter hold-out tests nothing about D0. It is reinstated for fitted detectors (§9).

**Estimands** are means or proportions **over worlds** (the unit). FDP = 0 when nothing is reported. Bounds: a percentile bootstrap over worlds (seeded from the freeze hash) for means; Clopper–Pearson for C1.

---

## 7. N / power assessment

**N is derived from the minimum practically meaningful failure δ = 0.05 below (or above) each threshold, power 0.80, one-sided α = 0.05 on the survival side, and the §8 adjustment on the refutation side.**

| Cell | Derivation | N |
|---|---|---|
| **P0, P2** (C2, C3) | per-world SD σ ≤ 0.2 assumed. Recall and FDP are averages over ≈ 44 edges, so within-world variance ≈ 0.25/44 is small, and 0.2 allows substantial between-world heterogeneity. Survival side: ((1.645 + 0.842)·0.2/0.05)² ≈ 99. Refutation side with the adjusted z = 2.326: ((2.326 + 0.842)·0.2/0.05)² ≈ **161** | **200** each |
| **NULL** (C1) | survival when the true rate is 0.05: ≈ 183. **Refutation of a true 0.15** vs 0.10 at the adjusted level: ≈ **399** | **400** |
| **NI** (check) | deterministic; enough to exercise the code path | **50** |
| total | vs REG's 3,200 | **850** |

**Pre-freeze assumption check (not a performance look):** the σ ≤ 0.2 assumption is checked on **dev** worlds.
- This check runs **after** the thresholds are fixed (they are, §12) and before the freeze.
- If the dev SD exceeds 0.2, N is recomputed from the dev SD by the same formula, and the recomputation is recorded.
- The dev *means* are not used for anything.

**A larger N is also defensible** (computation is cheap). The derived N is registered because the commission optimizes for the **smallest sufficient** design.

---

## 8. Multiplicity assessment

| Question | Answer |
|---|---|
| What is one scientific claim? | C1, C2, C3, each separately reportable |
| Is an adjustment needed to declare a claim, or all three, **SURVIVED**? | **no.** "Recall ≥ r₀ **and** FDP ≤ q₀" (and C1 ∧ C2 ∧ C3) are **intersection-union** claims: testing each component at α gives a level-α test of the conjunction (Berger 1982). No Bonferroni is needed for survival |
| Is an adjustment needed to declare **REFUTED**? | yes: "some component fails" is a union claim over **5 components** → Bonferroni α/5 = 0.01 per failing-side bound (z = 2.326) keeps P(any false refutation) ≤ 0.05 |
| Holm vs Bonferroni? | Holm is uniformly at least as powerful, but with 5 components and a bound-based three-way rule the gain is marginal. **Bonferroni is kept for auditability** (one fixed per-component level) |
| Hierarchical / gatekeeping? | not needed: C2/C3 measure FDP against truth directly, so their interpretation does not depend on C1 passing |
| A single composite rule? | yes (below) |

**Decision rule (the whole analysis):**

| Outcome | Rule |
|---|---|
| **SURVIVED** (per claim) | every component's one-sided 95 % bound lies on the passing side |
| **REFUTED** (per claim) | some component's one-sided **99 %** bound lies on the failing side |
| **INCONCLUSIVE** | otherwise |
| **C-M cycle SURVIVED** | C1 ∧ C2 ∧ C3 SURVIVED **and** the NI check passes |

---

## 9. ML boundary (documented interface only; not implemented)

```text
Detector.fit(dev)      # D0: no-op (no learned parameters; q* fixed)
Detector.freeze()      # returns the detector hash, included in freeze_hash
Detector.predict(world) -> {pair: (p, rank, direction)}
Scorer.score(predictions, truth) -> per-world metrics
```

- **Future detectors:** D1 classical statistical/ML · D2 graph method · D3 embeddings · D4 LLM-assisted.
- **Each is a separately frozen hypothesis** of the form *"Dk meets R-USE on the frozen harness"* and/or *"Dk beats D0 by Δ"*. Each is on new seeds, with its own freeze.
- **Fitted detectors reinstate the unseen-parameter hold-out** (§6), and treat pretraining leakage as a registered threat.
- **The harness stays detector-neutral.** No ML is in C-M.

## 10. Logic boundary

C-M is **empirical / statistical only**. No SAT, SMT, model checking or theorem proving. The logic pathway starts only with an **F2** KnowledgeOS hypothesis that makes a deductive claim (DES §7). `NONE_WITHIN_BOUND ≠ PROVEN` stands for that later stage.

## 11. DDD boundary

- C-M is **Phase-2 laboratory research software** (S2 §3A/§3B; test type T7) [D]. It is not F-Series execution-assurance machinery. Whether governance agrees is **[O]** (H-14a-type question; not decided here).
- **No bounded context, no aggregate.** Records: S2 `Experiment` (with the registration in `design`), the stage-2 raw result, and the verification record.
- The only invariant is ordering: freeze commit < test-world generation < result < verification. It is enforced by the seed commitment and git order.

---

## 12. Minimal final C-M specification (supersedes REG where different)

| Element | Value |
|---|---|
| **Generator** | the skeleton renderer R (§3.1); θ\* = {K = 30, p = 0.10, n_doc = 800, η = 0.7, λ = 1, s = 0.5}; design z ≈ 5.06 |
| **Cells** | **P0** positive L0 (N = 200) · **P2** positive L2 = {synonymy a = 0.20, duplication d = 0.10} (N = 200) · **NULL** fixed-margin independent placement, no lineage (N = 400) · **NI** R with s = 0 (N = 50) |
| **Lineage corruption** (missing, contradiction) | secondary only: it affects direction, which is not primary for D0 |
| **Detector D0** | REG §2.3, unchanged (hypergeometric one-sided p-values, BH q\* = 0.10, direction from the lineage-sentence majority, otherwise UNWITNESSED) |
| **Scorer** | REG §2.4, unchanged |
| **Claims** | C1 (P(≥ 1 edge) ≤ 0.10, NULL) · C2 (recall ≥ 0.80 ∧ FDP ≤ 0.20, P0) · C3 (the same, P2) · NI check (direction-assertion count = 0) |
| **Thresholds** | from R-USE (REG §1), unchanged, fixed before any run |
| **Decision** | §8 |
| **Splits** | dev seeds `dev-1…dev-100` per cell (harness debugging and the σ check only); test seeds = HMAC-SHA256(freeze_hash, "<cell>-<i>") |
| **Stopping** | the fixed N above; one analysis |
| **Pre-freeze** | generator self-tests: round trip; null margins exact; NI kernel depends on the skeleton only (check that orientation flips leave rendered-document distributions identical, verified by construction and by a byte-level test with a fixed seed) + the σ check |
| **Verifier** | REG §5, unchanged: V-min mandatory; `METHOD_VALIDATED` only at class INDEPENDENT |
| **Scope attached to any status** | `{domain: synthetic, renderer: R, θ*: …, design_z: 5.06, cells: P0, P2, NULL, NI, corruption: {L0, L2}, independence_class: …}` |

**Dropped from REG:**
- cells P1, P3, U0, U2 (P1 and P3 become secondary diagnostics; U waits for fitted detectors);
- 8 of the 13 criteria;
- the child-centred renderer;
- the chain/fork claim;
- the global N = 400.

---

## 13. Remaining blockers

| Blocker | Kind |
|---|---|
| a release scope covering C-M (L0-DEC-27; L0-DEC-30 does not) | **HUMAN DECISION** |
| human review of R-USE numbers and θ\* | **HUMAN DECISION** (pre-freeze) |
| lab-code classification (Phase-2 research software vs execution-assurance machinery) | **[O] / governance** |
| verifier class availability (INDEPENDENT vs SECONDARY_REVIEW) | **resource decision** (S2 §11.0) |
| hypothesis-id namespace | [O] (MP §4A / GIA-6) |
| implementation of G/R, D0, scorer, analysis; self-tests; σ check | **IMPLEMENTATION REQUIRED** (after release) |

---

## 14. What directly advances C-H

| C-M output | Helps C-H? |
|---|---|
| a working freeze → sealed-seed → one-look → independent-recomputation procedure | **yes:** the same harness discipline applies to a real hypothesis |
| the S2 `Experiment` usage pattern and the verifier protocol | **yes** |
| the C1 result (BH + hypergeometric calibration under dependence) | **conditionally:** it informs whether D0 is a trustworthy *candidate generator*, **only if** corpus co-occurrence resembles the null model, which is itself unknown |
| C2/C3 recall and FDR at z ≈ 5 | **no** for theory; weakly for method choice (the corpus's actual signal level is unknown) |
| anything about KnowledgeOS theory | **no** |

**The bottleneck is unchanged: a genuine F2 KnowledgeOS hypothesis** (S2 §9.1: *"Nothing in the window reaches F2"*). The S2 path is:
- §13A.1 pointer recovery;
- closing open terms (for example `OQ-9`, the undefined `bar`);
- F1 → F2.

**[F]** The currently released scope, L0-DEC-30 Critical Attack Pass 01, already works on the recorded `[E]-01…04` HYPOTHESIS records (no corpus reading). It is the existing path on which hypothesis attack can proceed. This is stated as a fact, not as a recommendation.

C-M must not displace that work. It is optional method infrastructure, not a prerequisite for theory discovery.

**Dependency map:**

```text
NOW
 ├── governance release for C-M ................................. HUMAN DECISION / BLOCKED
 ├── C-M registration review (this document + REG) ............... DESIGN COMPLETE (pending human review)
 ├── implementation (G/R, D0, scorer, analysis, self-tests) ...... IMPLEMENTATION REQUIRED (after release)
 ├── freeze ...................................................... BLOCKED on the three rows above
 ├── synthetic experiment (one look) ............................. BLOCKED on freeze
 └── independent verification .................................... BLOCKED on experiment; verifier class = resource decision

IN PARALLEL
 └── identify / formalize the strongest candidate toward F2 ........ SCIENTIFICALLY OPEN
     (within the released Critical Attack Pass scope, or a new release)

LATER
 └── C-H real-hypothesis cycle ................................... BLOCKED (no F2 item; corpus reading L0-DEC-30; C-7)

DEFERRED
 └── D1–D4 detectors, BY correction, U-hold-out, direction inference, logic tooling
```

---

## 15. Permanently deferred (never part of C-M)

| Item | Reason |
|---|---|
| any KnowledgeOS vocabulary or structure in the generator | benchmark contamination (S2 OQ-11) |
| the corpus-resident benchmark designs as generator input | a corpus read, and theory-derived contamination |
| direction-inference claims in NI worlds | impossible by construction |
| date or chronology corruption | D0 ignores dates; chronology methods get their own experiment |
| LLM or embedding detectors inside C-M-001 | they belong to separately frozen D3/D4 hypotheses |
| SAT/SMT/ITP | C-M has no deductive claim |
| treating C-M as a prerequisite for C-H or for theory discovery | it is optional method infrastructure |
| any reading of C-M results as evidence for KnowledgeOS theory | §2 |

---

> **FINAL ADVERSARIAL REVIEW COMPLETE — NO IMPLEMENTATION OR CORPUS EXECUTION AUTHORIZED.**
