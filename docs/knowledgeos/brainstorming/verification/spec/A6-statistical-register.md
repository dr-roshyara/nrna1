# A6 — Statistical Validity Register (Candidate KnowledgeOS Theory)

**Status:** raw-track statistical layer EXTRACTED (full-read sweep, 2026-08-29); Level-1-Statistical verdicts NOT yet assigned (post-checkpoint work).
**Sources read in full:** step-027 (uncertainty calculus, 2027 ln) · step-082 (uncertainty propagation, 2309 ln) · conditional-evidence v1/v2 (+synthesis) · Step 199 (raw-titled, 1754 ln) · SNF measurement framework (1680 ln) · Doignon–Falmagne extraction (2665 ln) · Freedman extraction (1369 ln).
**Discipline:** everything below is *what the corpus claims*, with locations. Verifier observations are marked ⚑OBS. Nothing repaired.

---

## 1. Structural facts about the source files (recorded)

- Conditional-evidence **"v2-expanded" is not a revision**: v2 = v1 byte-identical + a concatenated third document (the Kernel Problem file). Two halves of v2 disagree (K14 below).
- The **SNF file contains two voices**: a "Senior Mathematical Review" (duplicated twice) and a critique that materially overrides it — both retained with no supersession mark (K6).
- The KST extraction file also self-duplicates (extraction twice + commentary).

## 2. Uncertainty representations claimed (per file)

The corpus's thesis, repeated across files: **do not collapse Truth / Evidence / Probability / Belief / Confidence / Uncertainty into one scalar** (step-027 §27.1, §27.14 `UniversalConfidenceScore = ArchitecturalAntiPattern`; Step 199 §199.19).

Representations actually defined (13 in step-027 alone): contextual probability `P(H|C,M,E,t)` · 8-component uncertainty vector `U(H)` (component domains UNDEFINED) · intervals/imprecise probability `P(H)∈𝒫` (𝒫's membership criterion UNDEFINED) · CI vs credible interval (correctly distinguished) · categorical evidence status · epistemic status enums · evidence-quality vector `Q(E,q)` (`q` UNDEFINED) · D-S set support · additive decomposition `U_D=U_E+U_M+U_P+U_C+U_S` (the `+` stated then withdrawn as arithmetic).

**Key recorded fact:** `Belief` is boxed as distinct (027 §27.1), then only ever *used* as Bayes-updated probability (027 §27.20; 082 §82.21–23), and finally **banned from the kernel vocabulary** by the KST extraction (lines 2422, 2604). No non-probabilistic definition of Belief exists anywhere. (K3)

## 3. Probability model — what is actually defined

- **No probability space `(Ω,𝓕,P)` is constructed anywhere.** The triple is mentioned once (v1 §30) as a conditional ("becomes useful when…").
- Bayes' theorem appears in 7 places, 3 forms (plain; context-conditioned `P(H|E,C)`; interpretation-posterior in SNF). Likelihood correctly distinguished from posterior (027 §27.22); Bayes factor defined (027 §27.24).
- Model-relativity asserted as a law: `P(H|E,M₁) ≠ P(H|E,M₂)` in general; "probability must be interpreted relative to its declared model" (199 §199.23).
- Model averaging `P(D)=Σ_m P(D|M_m)P(M_m)` (082 §82.36) — **prior over models `P(M_m)` used, never justified** ⚑OBS.
- Decision theory: `EU(a)=Σ_s P(s|E)U(a,s)`, maximin over 𝒫; `Decision = Optimization + Constraints + Governance` (027 §27.55–57).
- ⚑OBS **Load-bearing gap:** every `P(E|H)` assumes a likelihood is obtainable for organizational/architectural evidence; no file says where it would come from.

## 4. Evidence combination rules

- **Master rule (v1):** `S_{t+1} = U_R(S_t, E|C)` — regime-indexed update. **`U_R` has no axioms, no type signature beyond this, no existence/uniqueness conditions**; characterized only by instances (Bayesian, D-S, fuzzy, non-monotonic, argumentation, epistemic logic, KST).
- **The only explicit multi-evidence combination formula in the whole layer is Dempster's rule** (synthesis §2.2) — presented **without its independence-of-sources precondition and without the Zadeh high-conflict pathology**, while the same corpus makes source-dependence its central hazard ⚑OBS (K7, K10-adjacent). The synthesis itself labels the rule "controversial".
- Fuzzy combination: "arbitrary … no single standard rule" (synthesis).
- **Epistemic Representation Invariance** (v1 §28–29): task-equivalence of regimes via semantics-preserving φ — self-declared "**candidate mathematical definition**, not a theorem we have proved".
- v2 kernel-substrate claim: `S_min = ⋂_ℛ Requirements(ℛ) = (D, E_{≤t}, Source, Time, Uncertainty)` — self-declared research hypothesis; ⚑OBS the intersection is asserted, not derived, over incommensurable requirement sets, and none of the five members appears literally in any per-regime tuple.
- Recorded open question (synthesis §6): *"What is invariant across different conditional-evidence combination mechanisms applied to the same epistemic substrate?"* — declared unanswered.

## 5. Independence & dependence

- Prohibition, repeated and strong: `EvidenceCount ≠ IndependentEvidenceCount` (027 §27.25); ten agents echoing one source ≠ ten pieces of evidence (199 §199.11); invariant **I₆₄** "correlated or derivative evidence must not be counted as independent confirmation without justification" (199 §199.13); `Independence cannot be assumed automatically` (082 §82.9).
- Dependent-evidence *computation* exists only for variances (082 §82.9–82.12, covariance terms). For evidence weighting under correlation, "must be weighted accordingly" — **scheme unspecified** (199 §199.12).
- KST's local independence `r(R,K)` (β_q careless error, η_q lucky guess) is adopted into the reconstruction schema **while its own ASSUMPTION list is not carried over with it** ⚑OBS (K7).

## 6. Uncertainty propagation (step-082)

Rules as stated: shift/scale (σ invariance, |a|σ) · sum with and without covariance · delta method (⚑OBS differentiability and evaluation-at-E[X] unstated; §82.14 evaluates at the observed value) · nonlinear asymmetry (`e^X`) · distributional `P(Y)=∫P(Y|X)P(X)dX` · Monte Carlo (⚑OBS i.i.d./sampling-access unstated, no SE reported) · uncertainty budget with unspecified "interaction terms" · **explicit prohibition of naive confidence chaining** (0.9⁴ forbidden without semantics, §82.59).
Governing law: *uncertainty must never disappear merely because the representation changed* (§82.71 clarifies: not monotone increase — **semantic preservation**).
**Seven new invariants** (§82.72): I_UncertaintyPreservation · I_Dependence · I_ModelUncertainty · I_Calibration · I_EpistemicPrecision · I_DecisionSensitivity · I_UncertaintyImpact.
⚑OBS All 33 "falsification experiments" are detection *expectations* — nothing was executed; PASS = author's expectation matches author's prescription.

## 7. Epistemic status vs probability (Step 199 — final position)

- Status ladder 𝔼₃ → 𝔼₄ → 𝔼₅ = {Supported, Refuted, Unknown, Ambiguous, Conflicted}; ⚑OBS precise semantics required by the file itself but supplied for only 2 of 5.
- **Probability is subordinate, optional, model-relative**; may be entirely absent (deterministic rules, §199.51); elevating probability to truth is a **policy act** (`P≥0.95 → CandidateAcceptance` is DomainPolicy, not mathematics, §199.33–35).
- Two-dimensional verdict: `DecisionCertainty ≠ EpistemicCertainty`; `Uncertain --explicit rule--> AcceptedForAction`, never silent.
- Invariants I₆₄–I₆₈ (independence, provenance of estimates, uncertainty/authority separation, Conservation(Uncertainty), no silent certainty-elevation).
- ⚑OBS No mapping between 𝔼₅ and any P is given anywhere; the two sit as independent tuple slots.

## 8. SNF measurement framework

- Measures **SNF-mapping behavior**, not knowledge/truth: metrics `C_snf` (convergence), `NC_snf` (non-collapse), `T_snf` (transformation stability), `H_sem^norm` (interpretation entropy), abstention accuracy, collision rate `CR` (false-merge rate; "semantic collision is more dangerous than divergence"; `CR→0` a safety objective).
- **The file's own critique half demotes the review half**: `d` is structural, not semantic distance; entropy is model-relative (`H_model`); composite `Q_snf` REJECTED (unjustified weights; worked FAIL case); thresholds demoted to "initial experimental, subject to calibration"; review's "all metrics have known statistical properties" contradicted by header "MATHEMATICALLY UNDERSPECIFIED" (K6).
- Statistical properties: consistency claim `C_snf →^p C_snf^true` with **estimand `C_snf^true` never defined independently of the estimator** ⚑OBS; no scale-type/representation-theorem analysis despite the corpus's own Roberts standard ⚑OBS; arithmetic means over a Jaccard-style `d` assume interval scale, unargued ⚑OBS.
- Experiments: v0.1 synthetic pilot only (non-collapse 0/20 at threshold; CR 25% — on deliberately collapsed synthetic data); v0.2/v0.3 produced no exposed numbers, and the file explicitly refuses to invent them.

## 9. Identifiability & estimation

- **Strongest positive claim:** KST reconstruction `O_{≤t} → P(K_t|O_{≤t}) → K̂_t` with convergence "under conditions" (Theorem 10.24 cited) — conditional on knowledge structure known/correct, response model known/correct, fixed state during assessment. ⚑OBS The KnowledgeOS analogue of the structure 𝒦 has never been constructed; the schema transferred without its precondition.
- Adopted three-level distinction: `𝒦 ≠ K_t^A ≠ K̂_t^A` (space ≠ latent state ≠ estimate) — "probabilistic extraction of a deterministic latent state".
- **Strongest negative discipline (Freedman):** `NOT_IDENTIFIABLE` as first-class successful outcome; causal identification gate (IDENTIFIED / PARTIALLY / NOT / UNKNOWN); diagnostics cannot validate model assumptions from data alone.
- Selection bias treated seriously (027 §27.43–46): logging bias, MNAR hazard for AI-generated corpora ("corpus may overrepresent failure cases").
- ⚑OBS 027 §27.18 sets up the base-rate/Bayes worked example and never computes the posterior.

## 10. Missingness

- Richest vocabulary (027 §27.41): `Missing / Unknown / NotApplicable / NotObserved / Withheld / Contradictory` — **none of the six individually defined**; MCAR/MAR/MNAR named, not defined.
- Strong laws: `Unknown ≠ 0.5` ("one of the strongest conclusions of Step 27") · `Unknown ≠ 0` · `Unknown ≠ False` (199, "critical software invariant") · `Conflict ≠ Unknown` · ignorance ≠ equiprobability (D-S set support).
- ⚑OBS The proposed kernel tuple `S_Kernel=(D,𝓔,𝓢,𝓣,𝓤)` has **no slot for absence** — non-observation/withheld/inapplicable have nowhere to live.
- ⚑OBS KST adoption internalizes `q ∉ K` = "not mastered" — exactly the unknown-vs-zero conflation 027 forbids; unflagged in the file.

## 11. The Freedman constraints (adopted discipline)

Master: **"No inference without an explicit inferential basis"** — `Data → Observation → Interpretation → Inference → Claim`, never `Data → AI says X → Knowledge = X`. First-class objects demanded: Inference, Model (with epistemic contract), **Assumption (+AssumptionLedger)**, RivalExplanation, ResearchDesign, qualified EvidenceReference. Ten invariants verbatim (evidence≠inference≠truth; model can't validate own assumptions; association≠causation; method success≠conclusion validity; unresolved alternatives reduce strength; Unknown valid; qualitative legitimate; domain knowledge inference-bearing; a gate establishes only what it was designed to establish).
⚑OBS Internal tension: the same file's Inference schema carries a bare `confidence:` field its own §7/§13 argue against (K16).

## 12. Hidden assumptions (⚑OBS — verifier-identified, candidate checkpoint §E entries)

22 items extracted; highest-risk subset:
1. **HA-S01** Dempster's rule used without independence-of-sources precondition — the sharpest: the only written combination rule omits the assumption the corpus elsewhere treats as the central hazard.
2. **HA-S02** Likelihoods `P(E|H)` for organizational evidence assumed obtainable; source never addressed.
3. **HA-S03** `±` notation carries unstated distributional commitments; σ vs interval readings toggled unmarked (082).
4. **HA-S04** Delta method: differentiability/small-σ/evaluation point unstated (082 §82.13–14).
5. **HA-S05** Model prior `P(M_m)`: exhaustive+exclusive model set and prior existence unstated (082 §82.36).
6. **HA-S06** Regression `Y=β₀+β₁X+ε` with `SE(β̂₁)` and no ε assumptions — the exact omission the corpus's own Freedman standard centres on (027 §27.8).
7. **HA-S07** Additive uncertainty decompositions assume common scale + non-overlap; stated then arithmetically withdrawn (027 §27.52).
8. **HA-S08** Cardinal, cross-state-comparable utility assumed by `EU` (027 §27.55) — unexamined against the corpus's own measurement-theory standard.
9. **HA-S09** SNF means over d assume interval scale; identity axiom of `d` unverified on 𝒮.
10. **HA-S10** `U_R` assumed total/deterministic and φ_R assumed to exist for each regime — the entire regime architecture proceeds on an unproved existence claim.
11. **HA-S11** Hypothesis space closure assumed by `|H_compatible(E)|>1` (199 §199.7) — contradicted in spirit by Freedman Invariant 6.
12. **HA-S12** All PASS verdicts presuppose an executed system; none exists.

## 13. Recorded contradictions (K-series — feed checkpoint §F; NOT reconciled)

| K | Contradiction (locations in extraction report, retained in register) |
|---|---|
| K1 | Scalar confidence banned as anti-pattern (027/199) vs `confidence:` field in Freedman schema and `𝓤` kernel slot (v2) |
| K2 | **Five different uncertainty taxonomies** (027 §27.13 eight-way; 027 §27.52 five-way; 199 §199.53 seven-way incl. governance; 082 epistemic/aleatory; SNF three-way) — none reconciled |
| K3 | Belief: boxed distinct → used as probability → banned from kernel |
| K4 | D-S: recommended for ignorance vs its combination rule labeled controversial; no adjudication |
| K5 | Imprecise probability declined as universal representation, then used (maximin, robustness sets) |
| K6 | SNF file's two halves materially disagree (metric status, entropy naming, composite, thresholds, "known statistical properties") — no supersession mark |
| K7 | Independence prohibited in principle, assumed silently in the one written rule and in KST adoption |
| K8 | Kernel content: uncertainty slot in `S_Kernel` vs no-uncertainty kernel in KST commentary; provenance simultaneously outside KST substrate and inside proposed kernel |
| K9 | `Unknown` operationalized 4+ incompatible ways (status enum · 𝔼₅ member · absent from decision vocabulary · entropy threshold · split into INCONCLUSIVE/INSUFFICIENT) |
| K10 | **Eight distinct "the epistemic object is a tuple" definitions** (arity 5–13) with no mapping between any two |
| K11 | Systematic notation collisions (𝓔, S, R, U, C, E, 𝒜) — including `U` as utility *and* uncertainty within step-027 |
| K12 | "Conservation of uncertainty" vs explicit denial of monotonicity — conservation language for a non-erasure requirement; no conserved quantity stated |
| K13 | 027's base-rate example demands Bayes and never computes it (internal incompleteness) |
| K14 | v2 is not a revision of v1; its appended half contradicts the v1 half on scalar confidence in the substrate |
| K15 | Synthesis and v2 agree on the KST limitation, then v2 builds the substrate claim the limitation undercuts |
| K16 | Freedman file's schema vs its own anti-confidence-score passages |

## 14. Consolidated undefined terms (statistical layer)

`U(H)` component domains · `q` in `Q(E,q)` · `VOI` (two informal, different forms) · `𝒫` membership · `𝓤` (both the uncertainty set and the kernel slot) · interaction terms (082 §82.44) · MCAR/MAR/MNAR · the six null semantics individually · `f` and `U(B|A)` in 199 §199.36 · full 𝔼₅ semantics · `Combine` (synthesis) · `θ`, `c_{q,r}` (KST updates) · `C_snf^true` · `Requirements(ℛ)` universe · `≈`/`≡` in reconstruction equivalence · `U_R` beyond instance list.

---

*Checkpoint feed: §12 → report §E (hidden assumptions); §13 → report §F (contradictions); §§3–6,8 → report §G (statistical risks). Level-1-Statistical verdict assignment is post-checkpoint work.*
