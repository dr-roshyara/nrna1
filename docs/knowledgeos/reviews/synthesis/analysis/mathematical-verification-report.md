# MATHEMATICAL / STATISTICAL / COMPUTATIONAL VERIFICATION REPORT
## GN-46 (GN-43 extension) · Independent verifier · 2026-08-28

**Commission:** `docs/knowledgeos/brainstorming/phase_measure_theory/how_to_combine/20260828_2332_prompt.md`
(READ / VERIFY / FALSIFY — DO NOT REPAIR). **Verifier:** independent agent; produced no book,
model, FA, or source content; modified nothing outside `analysis/mathematical-verification-report.md`
and `analysis/mathematical-tests/`. **Standing rule honored:** every defect below is REPORTED, never
repaired; every possible-repair sketch is explicitly NON-AUTHORITATIVE. "Validated" is used nowhere.

**The mandatory distinction (§26 of the commission), applied throughout:** *mathematical
correctness* ≠ *mathematical completeness* ≠ *computational possibility* ≠ *computational
realization* ≠ *statistical validity* ≠ *architectural authorization* ≠ *book-level explanation*.
Each section states which of these it is judging.

---

## 1 · Executive Summary

1. **The ratified architecture's mathematical content is honest but thin.** Its load-bearing
   mathematics consists of: two pipeline-level evidence invariants (I-5, I-6), a three-element
   covering relation plus one boundary edge (I-12/A6), a conjunction law for decision
   admissibility, a structured (non-scalar, non-metric) gap operator, and a stratification loop.
   All of these are **mathematically sound as stated**, and my independent reference
   implementations reproduce every one of them (§17). None of them is deep mathematics; none of
   them pretends to be.
2. **The formal-object layer is signatures without constructions.** η (EC derivation), Learn
   (state dynamics), the ladder's transition calculus, the policy-version transition calculus,
   the r↔P typing map, and identity/equality criteria for the model's own objects (evidence ~,
   state equality, frame equivalence) are all **MATHEMATICALLY UNDER-SPECIFIED**: named, typed at
   the arrow level, never constructed. The book says so wherever it is governed to (OQ-1, OQ-3,
   OQ-4, AF-F-3/4); this audit adds members the registers do not yet carry (§20).
3. **EXP-01 independently rechecked (§14): the historical negative verdict is CONFIRMED — and is
   in fact provable** (no scalar in [0,1] can retain (S⁺,S⁻); duplicate/dependency safety is a
   property of the normalization pipeline, not of any raw operator). The CSV-vs-verdict cell
   discrepancies are **re-derived**: the CSV mixes raw-operator and normalized-pipeline semantics
   across cells, exactly matching GN-27's "inconsistent under any single reading." **The GN-27
   adjudication stands**; my recheck corroborates it and adds the missing associativity outcomes.
4. **PF-4 determination (§14): the executed 7-column matrix alone does NOT entail the published
   conclusion** — SATURATING passes all seven executed columns. The conclusion follows only from
   the matrix PLUS the narratively-argued contradiction criterion (E), the pre-operator
   dependency/duplicate requirement, and the calibration caution. The historical record itself
   contains all of these, so the experiment is **honestly represented**; but "the matrix
   established it" would be false, and nothing audited says that.
5. **The compression criterion (§15) confirms the hostile pass's Cluster-1 split** and adds two
   unregistered members: the EC's source-final 8/9-component tuple (compressed past PF-5a's
   pair), and the source-used statuses `Missing`-in-vector-before-definition and `Failed`
   (outside the nine-set). Formally load-bearing losses: **Insufficient / Stale / Prohibited**
   (PF-1), **Q — epistemic sufficiency** (PF-7, plus the ratified six-tuple has *no ratified
   admissibility law at all* — §20 MV-F-5), and **the governance algebra's
   applicability/precedence structure** (PF-9 — without it, "the in-force policy" in I-11 is not
   established to be unique).
6. **Statistics: the architecture's strongest statistical act is a refusal**, and the refusal is
   correct: no epistemic status is converted to probability, uncertainty is not assumed
   probabilistic, calibration is named as the gate any probabilistic reading must pass and has
   never been executed. No statistical claim requiring a probability space is made anywhere in
   the ratified/book material. **STATISTICALLY SOUND by abstention.**
7. **Metric/measure/entropy/topology/category audits close as not-invoked:** grep-verified, no
   such structure is claimed by v0.2, the FA, or Edition-2 Part III (§§8–13). The historical
   measure-theoretic material is decomposed (GN-09) and held [H]; where the historical corpus
   used such language, it is analogy, and the architecture does not rely on it. Zero is
   **correctly not called a metric** — the source's own refusal (25D.32) is mathematically right.
8. **Book epistemic strength (§19): essentially clean.** The strength-word sweep over all eight
   chapters found exactly one borderline phrase ("internally complete as ruled", III.6 §9 — LOW)
   and one in-principle computability phrase worth a qualifier (III.2 §1). Every "proves/proven/
   minimal/unique" occurrence is a *negative* usage ("not proven minimal"). Grade-carriage in
   definition blocks is scrupulous. This corroborates the GN-43 hostile pass from the
   mathematical side.

**Top-level verdicts (§25 vocabulary):** see §23–§25 and the verdict block at the end.

---

## 2 · Scope and Authority

- **Audited:** `model/canonical-architecture-v0.2.md` + `canonical-architecture.md` (v0.1);
  `final-architecture/FA-1…FA-9`; Edition-2 Part III chapters III.1–III.8 + evidence maps;
  `book-edition-2/production-log.md` (PF-1…PF-9); `analysis/architecture-findings.md`
  (AF-F-1…AF-F-23, systemic analysis, GN-45 dispositions); sources 025d, 025e, 025f, 025g, 025h,
  025z, step-007, step-008, step-042, step-048 (via 049's recap), step-049, step-050, step-121;
  EXP-01 design (20260827-134514) + verdict (20260827-135038) +
  `tests/experiments/knowledgeos_evidence_calculus_property_tests.csv`.
- **Authority:** verification only. Nothing was repaired. v0.2, FA, BA, Edition-2 chapters,
  production log, findings register, sources, CSV: all byte-untouched by this audit.
- **Written:** this report, and `analysis/mathematical-tests/` (3 Python reference scripts +
  their captured outputs — testing tools only, not architecture, not repository code).
- **Method:** DISCOVER → FORMALIZE → CHECK → ATTACK → CLASSIFY → TRACE → REPORT → GOVERNANCE
  DISPOSITION, per the commission.

---

## 3 · Mathematical Object Inventory

| # | Object | Where ratified/taught | Source form(s) |
|---|---|---|---|
| O-1 | `EC = η(G, IdealState)` | v0.2 §1 (R-2); III.2 §7 | 025d §25D.3 pair `(R_G,Γ_G)`; 025e §25E.5 `(R,Γ,A,V)`; 025e §25E.37 **9-tuple**; 025f recap **8-tuple** |
| O-2 | `Zero(K, EC)` | v0.2 §1 (R-2); III.4 | 025d: `Zero(K,G,EC)` (title), `{(rᵢ,Zᵢ)}` vector (25D.6), `Zero(K,K*,EC)` (25D.31), **binary `Z_t=Zero(K_t,EC_t)` (25D.34)** |
| O-3 | Satisfaction set 𝒮 | I-9 ("four-way"); III.4 §6 | 025d §25D.4 nine values; + `Missing` (25D.7); + `Failed` used at 25D.23, never added |
| O-4 | Zero item | III.4 §5 | 25D.9 five fields; 25D.34 seven fields (adds Criticality, NextActions) |
| O-5 | `K_t` over 8 primitives | v0.2 §1; III.3 | 049 §49.30 `𝒦=(E,S,T,O,P,R,Π,A)`; practical `K=(V,E,R,Metadata)` (49.32) |
| O-6 | `K_{t+1}=Learn(K_t,…)` | III.3 §1 | 049 §49.92 (boxed, with `A_t=Decision(…)`, `S_{t+1}=F(…)`) |
| O-7 | Status ladder + boundary | v0.2 (R-3/R-4, I-12); III.6 | 008 §2 branched chain; §9 `α_ρ: EA×P×C→𝒮_A` (6 values); §10 `Ω_A` 4-dim |
| O-8 | `DC(d)` six-tuple | v0.2 §1; III.7 §3 | 042 §42.2; refined 7-tuple `⟨P,I,A,E,Q,T,O⟩` §42.40 |
| O-9 | Admissibility conjunction | III.7 §4 | 042 §42.9 (4 conjuncts); §42.41 (6 conjuncts over the 7-tuple) |
| O-10 | Proposal selector `(K_t,Z_t,G)` | v0.2 §1; III.7 §2 | 025g `Lord(K,Z,G,C)` §25G.1; `L(K,Z,G,C,P)` §25G.40 |
| O-11 | Decision selector | not ratified (PF-8) | 025h `(K,G,D,C,P)→d` §25H.2; `S(K,G,D,M,C)` §25H.37; `Sārathi(K,D,DecisionModel)` §25H.10 |
| O-12 | Governance algebra | one ratified row; III.8 | 025f: `⪰_C`, `Applicable`, `Conflict`, `Resolve`, three outcomes, escalation law |
| O-13 | Evidence tuple + operators | I-5/I-6 TESTED; III.5 | EXP-01 design `e=(P,π,ρ,κ,τ,δ,…)`; A₁–A₄; pipeline `E→E/~→Q→EA→Conclusion` |
| O-14 | Stratification loop + I-11 | v0.2 §3 (R-1); III.8 | 121 §§121.46–49; 042 §42.15 |
| O-15 | Version-transition schema | FA/III.8 §6 | 121 §121.47 (5 mandatory fields) |
| O-16 | Action loop | v0.2 (READ/SPECIFIED); III.7 §9 | 025z §25Z.1; 025g §25G.51 |

No metric, measure, probability distribution, entropy, KL divergence, topology, Hilbert-space,
functor, or projection-operator claim appears in any ratified or Edition-2 artifact
(grep-verified over the chapters, v0.1/v0.2, FA-1…9). Historical-corpus occurrences are [H].

---

## 4 · Definition Audit

*(Judging: mathematical completeness of definitions. "Complete" = domain, codomain, semantics,
and identity conditions all specified.)*

| Object | Domain | Codomain | Definition complete? | Variables typed? | Assumptions | Computable? | Evidence |
|---|---|---|---|---|---|---|---|
| η | (G, IdealState) | EC | **NO — signature only; no construction** | G/IdealState internally unmodeled (deliberate) | totality ASSUMED (OQ-1) | NOT ESTABLISHED | 025d §25D.39 (ContractDerivation open); book says so (III.2 §9) |
| EC | — | (R,Γ) / 8- or 9-tuple | PARTIAL — the pair is defined; source-final tuple unratified; Γ's rule language unspecified | R finite set; Γ untyped rules | requirements finite | representable | 25D.3; 25E.5/25E.37; **MV-F-4** |
| Zero | (K_t, EC) | structured gap set {(r, status, …)} | YES at vector level; status-set closure NOT ESTABLISHED (source: "initially I propose") | r ∈ R; status ∈ 𝒮⁺ | finite R; evaluators given | **YES relative to evaluators** — witness `zero_reference.py` | 25D.4–25D.37 |
| 𝒮 statuses | — | 9 named + Missing (+ `Failed` in use) | NO — not closed; membership drifts inside the source | enumerated | — | trivially | **MV-F-3** |
| K_t | — | "state over 8 primitives" | PARTIAL — contents typed (049); **state equality never defined** | primitives typed per 049 | finiteness at any t (49.33) | representable (49.32) | **MV-F-22** |
| Learn | (K_t, Obs, Events, Policies, Outcomes) | K_{t+1} | **NO — uninterpreted function symbol** | argument sorts named only | governed incorporation | NOT ESTABLISHED | 049 §49.92; **MV-F-11** |
| Ladder + I-12 | statuses | statuses | YES — covering relation on a 3-chain + boundary edge; well-defined | — | ruled axiom (RC) | YES — witness `ladder_dc_reference.py` | 008 §23; R-3/R-4 |
| Transition calculus (what moves P) | — | — | ABSENT (deliberate: policy content) | — | — | n/a | AF-F-3; III.6 §9 |
| α_ρ | EA×P×C | 𝒮_A (6) | ill-typed as stated (outputs like Supported+Contested ∉ 𝒮_A); superseded in-source by Ω_A | — | — | — | 008 §§9–10; **MV-F-13** |
| DC six-tuple | decision d | 6 components | components glossed; **no ratified admissibility predicate over exactly these 6** | components as predicates over (K,t) implied | — | YES given component evaluators | 042 §42.2 vs §§42.9/42.41; **MV-F-5** |
| Admissibility | (d,K,t) | {T,F(,Unknown)} | YES for the source's two forms; conjunction semantics exact | — | three-valued handling per policy (42.12) | YES — witness script | 042 §§42.9–42.14 |
| Proposal | (K_t,Z_t,G) | epistemic action | signature-level; selection semantics = policy (deliberate) | C and P dropped from source (AF-F-11) | bounded action space 𝒜(K_t) | per-step computable; **loop termination not guaranteed without budget/deadline policy** (25G.31 says so itself) | 025g |
| Decision selector | (K,G,D,C,P) | d / DecisionResult | unratified (PF-8); source gives 3 signature variants | — | DecisionModel given | conceptually computable (25H.32) | 025h; **MV-F-6** |
| ⪰_C authority order | sources × sources × contexts | precedence | **postulated as partial order; axioms (refl/trans/antisym) never stated or argued** | — | explicit modeling of semantics | conflict detection computable given semantics | 025f §§25F.14–15; **MV-F-12** |
| GovernanceResolve | (C,S,t) | (EffectiveRules, Conflicts, Exceptions, Supersessions, Unresolved) | algorithm sketched (25F.22); outcome vocabulary defined | — | "semantics explicitly modeled" | YES under that assumption | 025f |
| I-11 / in-force | policies × versions | {in-force, not} | **PARTIAL — uniqueness of "the in-force policy" under multiple applicable authorities NOT ESTABLISHED; no base case (genesis)** | — | — | — | **MV-F-7, MV-F-8** |
| Evidence e | — | (P,π,ρ,κ,τ,δ,…) | design-level tuple; **~ (epistemic equivalence) undefined — by the source's own admission** | fields named | — | representable | EXP-01 verdict §9; PF-3 |
| Aggregators A₁–A₄ | finite multisets of [0,1] | [0,1] | YES as formulas; **BAYES undefined on {0,1} jointly; WM undefined on ∅** | sᵢ ∈ [0,1] | normalization precedes (for the claimed properties) | YES — witness `exp01_recheck.py` | **MV-F-18** |
| r ↔ P map | R → P (or 2^P) | — | **ABSENT** — required by the III.4↔III.5 interlock and by any Γ↔ρ_A identification | — | — | — | AF-F-13; **MV-F-9** |
| Adjudication of CONFLICTED | conflicted items × governed acts | statuses | **ABSENT** — consumed, never typed | — | — | — | AF-F-15; **MV-F-10** |
| X_t world state; `S_{t+1}=F(S_t,A_t,U_t)` | — | — | F uninterpreted; world model deliberately outside (I-7) | — | — | n/a | 049 §49.92 |

---

## 5 · Derivation Audit

*(Status vocabulary: VALID DERIVATION / VALID BUT INCOMPLETE / RECONSTRUCTABLE / HEURISTIC /
INVALID / NOT ESTABLISHED.)*

| Derivation | Premises → conclusion | Status | Notes |
|---|---|---|---|
| `Sat ≠ Boolean` (25D.5) | one False bit conflates ≥6 operationally distinct cases → richer status set needed | **VALID DERIVATION** | counterexample-driven; reproduced |
| Zero never a scalar (25D.17) | gap items incomparable; 5+1≠6 | **VALID DERIVATION** | executed as code (zero_reference.py, scalar-collapse counterexample) |
| Zero not a metric (25D.32) | directedness breaks symmetry | **VALID** | see §9 |
| "Zero cannot be computed from the goal alone" (25D.2) | goals are intent-shaped, not check-shaped | RECONSTRUCTABLE | book III.2 §5 reconstructs it and marks the reconstruction |
| No-averaging (42.10) | 3 True + 1 False conjunct → False | **VALID DERIVATION** | trivially; executed |
| EXP-01 negative verdict | matrix + criterion E + dependency-first + calibration → no scalar operator suffices as foundation | **VALID BUT INCOMPLETE as recorded** — the matrix alone does not entail it; with the narrative premises it is valid, and this audit strengthens it to a provable claim (§14, §16) | **the key PF-4 determination** |
| I-5/I-6 as "TESTED" | one recorded adversarial run (prose record; no surviving executable) | historical grade accurate as a record-claim; **independently corroborated here** (exp01_recheck.py) — as *pipeline* properties | OQ-5 (replication) remains accurate |
| Eight-primitive reduction (049) | per-concept constructibility → candidate kernel | **RECONSTRUCTABLE** (the book's own grade — confirmed) | completeness/minimality/uniqueness NOT ESTABLISHED, as the source and III.3 §13 say |
| 050 fifty-attack audit | 50 conceptual attack arguments, all "PASS" | **HEURISTIC** (argument-level, not computational); the source's own caveat "not a proof" is the correct status | "TESTED (within scope)" is acceptable ONLY with III.3 §16's on-paper gloss, which the book supplies |
| 25D.38 / 42.49–60 / 25G.42–47 "falsification tests" | declared PASS, no executables | **HEURISTIC as recorded**; subsets now executed and passing (zero_reference.py: 25D.38 T1–T8; ladder_dc_reference.py: 42.49–51) | independent supplement, not a repair |
| Escalation law (25F.18–19) | no precedence/supersession/exception → Unresolved → human | **VALID as a design rule**; "correct computational result" is a stance, not a theorem; decidability of "resolvable" holds because resolvability is defined syntactically | |
| I-9's embedded "four-way" | claimed READ from 025d | **INVALID as a source description** (the cited source's set is nine-valued + Missing (+ Failed)); the invariant's *normative* content (no Boolean collapse) is unaffected | AF-F-9 confirmed; **MV-F-2** |
| R-2 simplification `Zero(K,EC)` | "no corpus evidence of a G-residual" | VALID and, additionally, **positively source-attested** — 25D.34 and 025e already use the binary form; the ruled resolution is stronger than the ruling recorded | **MV-F-14** (positive) |
| Stratification severs F-1 | content/force split + I-11 | VALID for *changes*; **NOT ESTABLISHED for genesis** (no base case) — AF-F-14 confirmed formally | **MV-F-8** |

---

## 6 · Computability Audit

*(Judging: computational possibility and realization, kept apart.)*

| Object | In principle | Algorithm | Termination | Complexity | Realized (L4)? |
|---|---|---|---|---|---|
| Zero(K,EC) | **COMPUTABLE relative to per-requirement evaluators** (rules/statistics/authority are oracles — 25D.12; HumanAuthorization is not a computation) | yes — 25D.36 sketch; witness `zero_reference.py` | yes (finite R) | O(|R|·cost(eval)) | **NO** (CF-015) |
| η | **COMPUTABILITY NOT ESTABLISHED** — no construction exists (OQ-1; 25D.39 lists eight requirement origins incl. law and human instruction) | none | — | — | NO |
| Learn | NOT ESTABLISHED — uninterpreted | none | — | — | NO |
| Ladder + I-12 + A6 | COMPUTABLE (finite state machine) | witness `ladder_dc_reference.py` | yes | O(1)/transition | NO (nearest running relative: session-bootstrap authorization, boundary only) |
| DC admissibility | COMPUTABLE given component evaluators; three-valued semantics needed for Unknown | witness script | yes | O(#components) | NO |
| GovernanceResolve | COMPUTABLE **provided semantics explicitly modeled** (the source's own proviso — correct) | 25F.22 sketch | yes | claimed O(n log n) with indexing — plausible engineering estimate, HEURISTIC not derived | NO |
| Selector loop (25G.51) | per-step computable; **loop termination NOT guaranteed** without budget/deadline/termination policy — the source itself demands a "termination algebra" (25G.31) | 25G.36 sketch | conditional | — | NO |
| Aggregators A₁–A₄ | COMPUTABLE; numerical caveats (§4 last row; MV-F-18) | witness `exp01_recheck.py` | yes | O(n) | NO (no store, no resolver, no operator code — III.5 §12, accurate) |
| ~ / dependency resolution N | NOT ESTABLISHED — ~ is undefined (PF-3); N computable only once ~ is | — | — | — | NO |
| Reachability/verification over K-states | source correctly flags undecidability/intractability limits (49.43–49.58) — statements accurate | — | — | state explosion 2ⁿ noted | NO |

**Overall:** the deterministic skeleton is **COMPUTABLE UNDER RESTRICTIONS** (finiteness,
explicit semantics, evaluator oracles); the semantic layer (η, ~, Γ language, transition calculi)
is **COMPUTABILITY NOT ESTABLISHED**; the whole is **NOT COMPUTATIONALLY REALIZED** (3C CF-015 —
and every chapter says so). The source's own summary "the difficult part is formalization, not
computation" (25F.39) is verified as accurate.

---

## 7 · Statistical Audit

- **No probability space, prior, likelihood, posterior, or distribution is claimed by any
  ratified or book artifact.** 008 §12's `Pr(P|E) ≥ 0.95` is expressly a *policy example*, "not
  a universal law." Legitimate.
- **`P(Prop) = Support/total support` (the commission's watch formulation) appears nowhere** in
  the audited material. Had it appeared, it would fail the probability tests (no sample space, no
  exclusivity/exhaustiveness); the corpus's own calibration caution ("a number pretending to be
  probability") preemptively blocks it. The refusal to convert epistemic states into
  probabilities (design doc; 049 §49.10 `Probability ≠ EpistemicConfidence`) is **statistically
  correct**.
- **Bayesian audit (§11 of the commission):** the BAYES_LIKE operator is a naive combiner with a
  toy likelihood mapping; the verdict itself says it "is not something we should put into the
  constitution." Verified: conditional independence is the load-bearing assumption; my numeric
  demonstration (exp01_recheck.py, CALIBRATION block) shows treating the API→LLM1/LLM2 chain as
  independent inflates 0.80 → 0.985 — the double-counting failure the design predicted.
  `Bayesian inference ∈ reasoning policies, not kernel` is the statistically sound placement.
- **Calibration:** designed as "the most important statistical test"; **never executed**
  (PF-4). My demonstration: SATURATING over ten independent weak items (s = 0.3) yields 0.972 —
  a number with no calibration license. The unexecuted status is correctly taught (III.5 §5).
- **Duplicate/dependence effects:** quantified independently (100 duplicates: raw SAT/BAYES
  saturate to 1.0; pipeline returns 0.7). I-5/I-6 are statistically well-motivated and now
  independently corroborated as pipeline properties.
- **Step-050's statistical attacks** (significance≠effect size, multiple testing, selection
  bias, MNAR) make correct textbook statements; they are design obligations, not established
  system properties.

**Verdict: STATISTICALLY SOUND (by correct abstention), with the calibration suite
NOT EXECUTED and therefore no positive statistical property established.**

---

## 8 · Measure / Integration Audit

No measurable space, σ-algebra, measure, or integral is invoked by v0.2, the FA, or Edition-2
Part III (grep-verified). The measure-theoretic era is historical ([H]; GN-09 decomposition;
FA-4's Ω row). The distinction the commission demands — "can be represented as an integral" vs
"measure theory is required by the architecture" — is decided: **measure theory is not required
by, present in, or relied on by the ratified architecture.** Nothing to audit further; no
finding. (Any future re-import would need the full §10 checklist from scratch.)

## 9 · Metric Audit

The only "distance"-like object is Zero. Tested against the four metric axioms:
non-negativity — inapplicable (codomain is a structured set, not ℝ≥0); identity of
indiscernibles — untestable (state equality undefined, MV-F-22); symmetry — **fails by design**
(directed: state → contract); triangle inequality — inapplicable. The source itself performed
this audit (25D.32) and refused the term "metric," classifying Zero as a **directed,
purpose-relative, structured epistemic discrepancy operator** — the correct classification.
Ratified and book texts never call it a metric; derived numeric projections (GapCount, Coverage)
are explicitly labeled projections. **PASS — no misclassification found.** `EpistemicDistance`
(25D.31) is a naming residue in the source only; the book does not import it.

## 10 · Probability / Bayesian Audit

Covered in §7. Additional cell-level result: the BAYES_LIKE combiner is undefined (0/0) when the
evidence multiset contains both s=1 and s=0 — any implementation needs an explicit convention
(MV-F-18). The "Bounded [0,1]" matrix column is true of it only away from that edge.

## 11 · Entropy / KL Audit

No entropy, KL divergence, information measure, or "knowledge gap = divergence" identification
appears in any audited ratified/book artifact. **Closed — nothing invoked.**

## 12 · Topology Audit

No topology, open/closed sets, boundary operator, or continuity claims in the audited
ratified/book artifacts. Historical-corpus formulations of the shape `Zero: Ω → ∂Ω` (measure-
theory era) are, per this audit's classification rule, **mathematical analogy/hypothesis, not
formal results** — and they hold no architectural role (Ω decomposed, GN-09). The two vestigial
Ω-symbol reuses inside sources (049 §49.8 sample-space shorthand; 008 §10's Ω_A tuple name) are
naming hazards only, both already registered by the book/production log. **Closed.**

## 13 · Operator Audit

| Claimed operator | Verdict |
|---|---|
| Zero as operator (K_t, EC) → gap set | genuine partial map over model states; total for finite R relative to evaluators; deterministic/replayable AS SPECIFIED (25D.27) — a design obligation, not a theorem |
| A₁–A₄ aggregation operators | genuine functions on finite multisets of [0,1] (with the §4 definedness caveats); ⊕ (SAT) and odds-product (BAYES) are commutative, associative, bounded — verified numerically and algebraically; naive binary mean is NOT associative (state-carrying variant is) |
| α_ρ | ill-typed as stated (source-acknowledged); superseded by Ω_A |
| ω: Ω_A×Event×Policy ⇀ Ω_A (008 §27) | honest partial-function signature; no construction; NOT ESTABLISHED |
| Learn / F / η | uninterpreted symbols (see §4) |
| "Authority lattice" (25F.14 heading) | **misnomer in the source heading**: content asserts a partial order (correctly argued against linearity), never establishes lattice structure (no joins/meets), and never verifies the partial-order axioms for ⪰_C. The book carries "partial order" only — correct restraint. MV-F-12 |
| Category/functor/projection/kernel(math) claims | none made anywhere in scope; "kernel" is used only in the architectural sense and is explicitly layered (D-FA-4) — no collision with the mathematical term |

---

## 14 · EXP-01 Independent Recheck

**Method:** independent reference implementation of all four operators
(`mathematical-tests/exp01_recheck.py`, output `exp01_recheck_output.txt`), run under two
explicit semantics — RAW (operator alone) and PIPE (dependency/equivalence collapse E→E/~ plus
relevance filtering before the operator) — against the commission's adversarial set
(100 duplicates; API→LLM1/LLM2; independent corroboration; contradiction; staleness;
boundedness; order; associativity; irrelevance; plus calibration demonstrations).

**Results (full matrix in the output file):**

1. **Historical verdict table REPRODUCED** under the reading its own prose specifies: every
   PASS cell my RAW run fails (SAT/duplicates, SAT/dependency, SAT/irrelevance, BAYES/duplicates,
   BAYES/dependency, BAYES/irrelevance) is true of the **pipeline**, and the verdict's §§2–3, 8
   place the collapse before the operator. The prose record is internally consistent. MAX's
   corroboration failure, WM's duplicate/corroboration/dependency failures, order invariance for
   all four, and boundedness (with edge caveats) all reproduce exactly.
2. **CSV discrepancies re-derived, not merely re-read:** WM/duplicates "True" is what a
   singleton-base duplication test produces (mean(s,s)=s — my subtest confirms); MAX/irrelevance
   "True" is the normalized reading; BAYES/irrelevance "False" is the raw reading. **The CSV
   mixes semantics across cells** — precisely GN-27's "inconsistent under any single reading."
   **The GN-27 adjudication (prose prevails; CSV unreliable-standalone) STANDS and is hereby
   independently corroborated.** Neither historical artifact was modified.
3. **New outcomes for the never-executed designed criteria** (supplement, not repair):
   D (associativity): MAX ✓, SAT ✓ (algebraically: 1−(1−s)(1−t) is associative), BAYES-odds ✓,
   naive binary mean ✗ (state-carrying mean ✓). E (contradiction): **no scalar operator can
   satisfy it** — counterexample executed: {0.9 for, 0.9 against} and {0.1 for, 0.1 against}
   net to the same scalar while being epistemically distinct; retention of (S⁺,S⁻) is a
   representation-layer property. I/J (context/policy dependence) and Calibration remain
   unexecuted as *system* tests; calibration's warning is demonstrated numerically.
4. **Irrelevance clarification:** handling Relevance=0 is a normalization-layer property for
   **all four** operators, not only MAX/mean (the historical footnote's scope was too narrow —
   an internal imprecision of the historical verdict, severity LOW; it does not affect the
   negative conclusion).

**PF-4 determinations (as commissioned):**

- *Is the historical experiment correctly represented?* **YES** in the book (III.5 §5 states
  each criterion's actual evidentiary status; §7 quotes the verdict at exact strength; §8
  discloses the CSV affair and the no-surviving-executable wound). The production log's PF-4
  entry is accurate: executed columns = designed {A,B,C,F,G,H} + the undesigned "Bounded [0,1]";
  D, E (matrix), I, J, Calibration have no recorded outcome.
- *Is the executed subset sufficient for the published conclusion?* **NO — not alone.**
  SATURATING passes all seven executed columns; from the matrix alone, "no simple scalar
  operator is sufficient" does not follow.
- *Does the negative conclusion follow from the tested properties only?* **NO** — it follows
  from the tested properties PLUS the narrative premises (criterion E's scalar-impossibility,
  the pre-operator dependency/duplicate requirement, the calibration caution), all present in
  the historical verdict. With those premises the conclusion is VALID — and this audit shows it
  is *provable* (E alone defeats every scalar).
- *Would any stronger claim be invalid?* **YES.** Invalid strengthenings would include: "the
  matrix established the negative result," "all ten designed criteria were tested,"
  "calibration was tested," "an operator was selected" (OQ-3 is correctly open), or "the two
  survivors were experimentally distinguished." **None of these appears in the audited
  artifacts.**
- The "Bounded [0,1]" column: an operator-hygiene check added at execution; harmless, but its
  presence with three designed criteria absent is exactly the design-to-execution drift PF-4
  records. Its own content verifies (with the definedness caveats of MV-F-18).

---

## 15 · Source-vs-Synthesis Compression Audit

**Criterion applied (HPA/GN-45):** lost information is a formal problem **only if** a ratified
invariant, boundary, transition, or observable behavior requires it. Each member, analyzed
mathematically (SOURCE FORM → RATIFIED SYNTHESIS → what the loss breaks, if anything):

| Member | Lost content | Formally load-bearing? | Determination |
|---|---|---|---|
| **PF-1** (9→4 statuses) | `Insufficient` | **YES** — the ratified Determination transition (Supported→Accepted under AcceptancePolicy) is exactly a sufficiency judgment; a gap vocabulary without `Insufficient` cannot type "evidence exists, bar unmet," the very input state Determination discriminates. Executable demonstration: `zero_reference.py` (Insufficient expressible only because Γ is carried) | **(b) defect candidate — CONFIRMED** |
| | `Stale` | **YES** — temporal validity is one of the seven executed properties of the TESTED evidence layer ("stale retained ≠ current"); the interlock III.4↔III.5 (Stale ⇔ criterion H lapse) is stated in the ratified model's own TESTED basis and is untypeable in four arms | **(b) — CONFIRMED** |
| | `Prohibited` | **YES** — a requirement blocked by governance is neither unknown nor missing; I-10/I-11-adjacent behavior (governance can block while evidence is complete) requires the arm | **(b) — CONFIRMED** |
| | `PartiallySatisfied` | no ratified consumer identified | (e)/benign — NOT ESTABLISHED as required |
| | `Satisfied`, `NotApplicable` | non-gap statuses; a gap report naturally omits them | (a) natural projection |
| **PF-5a** (EC pair → "requirement set") | Γ (sufficiency rules) | **narrowly YES** — Γ's plausible re-homing into AcceptancePolicy is **ill-typed as it stands**: Γ judges requirements, ρ_A judges propositions; the identification needs the undefined r↔P map (AF-F-13). Until φ: R→P exists, Γ has no ratified home | (b) narrow + (e) — CONFIRMED, and formally COUPLED to AF-F-13 |
| **PF-5b** (Z_W dropped) | operational Zero | NO — OQ-4 holds the action side open by ratified decision; no ratified invariant consumes Z_W | (a) deliberate — CONFIRMED |
| **PF-6** (branched/4-dim → linear + layered) | branches | content re-homed by D-FA-1 (ratified) | (a) by ratified decision |
| | `Accepted ∧ Contest:Active` | expressibility residue is REAL (executable probe: `ladder_dc_reference.py` — no single layered state carries both), but no ratified invariant requires representing simultaneous institutional acceptance + live epistemic contest; Art. 8's coexistence is satisfiable via CONFLICTED suspension | (e) research — CONFIRMED |
| **PF-7** (DC 6 vs 7; Q lost) | Q = epistemic sufficiency | **YES** — ratified I-3's first disjunct (SufficientKnowledge ≠ ValidDecision) has no carrier in the ratified contract: no six-tuple component is typed as epistemic sufficiency, and the source's own refinement judged Q ≠ E necessary. Sharpened by **MV-F-5**: the ratified 6-tuple has NO ratified admissibility conjunction at all (42.9's four conjuncts omit Temporal; 42.41's six belong to the 7-tuple) | **(b) — CONFIRMED and SHARPENED** |
| **PF-8** (two selectors → one) | decision selector, DecisionModel, DecisionResult | mostly (a); **but sharpened by MV-F-6**: the ratified canonical flow's edge `PROPOSAL → DECISION under DC(d)` is not type-correct — Proposal returns an epistemic action, DC consumes a decision d, and nothing ratified produces or selects d. The compressed selector is the missing arrow-filler, which upgrades one sliver of PF-8 from exposition to a formal gap in a ratified transition diagram | (a) + one (b)-sliver, ENLARGED |
| **PF-9** (governance algebra → one row) | ⪰_C, applicability, three outcomes, escalation, transition schema, recursion | **YES — the strongest, CONFIRMED with a precise mechanism**: I-11 presupposes a well-defined, unique "in-force policy"; with multiple concurrently applicable authorities and no ratified precedence/applicability structure, uniqueness of the in-force rule is NOT ESTABLISHED, so I-11's guard condition is evaluable only in the single-authority case. Escalation law and AI-non-override have no ratified objects (I-4 covers commitment only) | **(b) — CONFIRMED** |
| **AF-F-9** (compression inside I-9's text) | "four-way" | **(b) by construction** — see §5 (INVALID as source description; normative content intact) | CONFIRMED |
| **AF-F-11** (selector C dropped) | context argument | no ratified L2 invariant requires context-dependent selection (Art. 1's context law is L1); note the source also carries P (policy) which the ratified selector equally lacks — the ratified Proposal cannot be policy-indexed, in tension with the model's own everything-answers-to-policy theme, but no ratified statement requires it | (a), with the P-argument noted for the same register entry |
| **NEW — MV-F-4** (EC final tuple unregistered) | A,S,T,D,X,(Prov),V components; 025e §25E.37 nine vs 025f eight (source-internal inconsistency) | no ratified invariant requires the extra components (V resonates with I-11 but EC versioning is not ratified) | (a)/register — NEW MEMBER for the PF family disposition |
| **NEW — MV-F-3** (`Failed` status; `Missing` used before defined) | status-set closure | no ratified consumer | LOW/register — NEW MEMBER |

**Systemic confirmation:** the hostile pass's Cluster-1 verdict survives independent mathematical
scrutiny unchanged, with two members added and two members (PF-7, PF-8) sharpened.

---

## 16 · Counterexample Results

| Attack | Result |
|---|---|
| Empty evidence set | WM undefined (0/0); MAX needs a convention; SAT→0, BAYES→prior. Any implementation must fix conventions (MV-F-18) |
| Empty contract (R=∅) | Zero=∅ → ExecutionEligible trivially true *subject to authorization* (25D.21's own proviso); the authorization conjunct still guards — no defect, but a trivial IdealState yields a trivial epistemic guard by design (owned standard) |
| Singleton | all operators behave; duplicate-invariance on singleton bases is where WM spuriously "passes" (the CSV-cell explanation) |
| 100 duplicates | raw SAT/BAYES saturate to 1.0 (counterexample to duplicate invariance as an *operator* property); pipeline returns the original 0.7 — **I-5 holds at pipeline level only** |
| Dependent evidence (API→LLM1/LLM2) | raw operators inflate (SAT 0.8→0.997; BAYES 0.8→0.985); pipeline exact — **I-6 confirmed as an ordering law** |
| Contradictory evidence | every scalar output conflates strong-conflict with weak-conflict (executed counterexample) — criterion E unsatisfiable by scalars; (S⁺,S⁻) retention is representation-level |
| Missing values / Unknown | Unknown→Block semantics verified three-valued in the DC witness; Unknown vs Missing distinction executable (zero_reference) |
| Conflicting statuses | Conflicted preserved, not silently Unknown (executed) |
| Zero probabilities | BAYES NaN on {0,1} jointly — numerical counterexample to unqualified boundedness |
| Malformed input | 050 Attack 1 is conceptual only; no executable ever guarded it — implementation obligation |
| Policy change mid-flight | **no calculus exists** (AF-F-4 confirmed; 050 §50.50 itself demands a PolicyBindingRule and none is ratified); the book's running example deliberately dodges it via a clean Temporal clause and says so |
| Multiple equally valid decisions | source handles via `a₁∼a₂ → HumanDecisionRequired` (25G.20/25G.46) — sound; unratified |
| Skip transition | rejected by I-12 witness; boundary uncrossable by evidence volume (A6 witness) |
| Genesis (first in-force policy) | **no base case** — AF-F-14 confirmed: the induction that makes I-11 protective has no ratified initial step (§20 MV-F-8) |

No counterexample was found against any *ratified invariant as scoped*. Counterexamples were
found against **universal readings** the artifacts themselves do not assert (operator-level
duplicate invariance; matrix-only sufficiency of the EXP-01 conclusion; unqualified boundedness).

---

## 17 · Computational Reference Tests

All artifacts in `analysis/mathematical-tests/` (testing tools only; each output captured):

| Script | What it witnesses | Outcome |
|---|---|---|
| `exp01_recheck.py` (+ `exp01_recheck_output.txt`) | four operators, RAW vs PIPE, criteria A–H + Bounded + adversarial + calibration demos + verdict/CSV comparison | reproduces the historical verdict under its own stated reading; re-derives the CSV's mixed semantics; supplies D and E outcomes; confirms the negative conclusion |
| `zero_reference.py` (+ output) | source-level Zero: nine-status + Missing evaluation, 25D.8 worked example (exact match), 25D.38 T1–T8 executed (8/8 PASS), Insufficient-via-Γ, scalar-collapse counterexample | Zero computable relative to evaluators; PF-1's load-bearing loss shown as executable semantics |
| `ladder_dc_reference.py` (+ output) | I-12 covering relation (skip rejected), A6 (evidence volume inert; act crosses), governed adjacent states, Ω_A expressibility probe, 042 conjunction/no-averaging/Unknown→Block, 42.49–51 executed | all ratified boundary semantics execute as specified; PF-6 residue and PF-7/MV-F-5 mismatch demonstrated |

Assumptions and numerical caveats are stated inside each script. Nothing was written into
repository code paths; nothing here is authorized architecture.

---

## 18 · Architecture Alignment Matrix

*(§20 categories, not collapsed.)*

| Claim | Classification |
|---|---|
| I-5 / I-6 (pipeline-level) | MATHEMATICALLY CORRECT + ARCHITECTURALLY ALIGNED (and independently corroborated) |
| I-12 covering relation; A6 boundary | MATHEMATICALLY CORRECT + ARCHITECTURALLY ALIGNED |
| No-averaging admissibility conjunction | MATHEMATICALLY CORRECT + ALIGNED — but the exact conjunction for the ratified six-tuple is MATHEMATICALLY INCOMPLETE (MV-F-5) |
| Zero as structured, directed, non-metric operator | MATHEMATICALLY CORRECT + ALIGNED; status-set closure MATHEMATICALLY INCOMPLETE |
| I-9's normative content | ALIGNED; its embedded "four-way" source description MATHEMATICALLY INVALID as description (MV-F-2) |
| η totality; Learn; transition calculi; r↔P; adjudication typing; state equality | MATHEMATICALLY INCOMPLETE (all held open — mostly with governed OQ/AF homes; new members in §20) |
| Zero / ladder / DC computation | COMPUTATIONALLY POSSIBLE BUT UNSPECIFIED at system level; NOT COMPUTATIONALLY REALIZED at L4 (CF-015 — accurately taught) |
| Saturating/Bayes operator selection | correctly NOT claimed (OQ-3 open); any selection now would be ARCHITECTURALLY UNAUTHORIZED |
| Source governance algebra (⪰_C etc.) | MATHEMATICALLY plausible postulates + ARCHITECTURALLY ONLY INTERPRETIVE (unratified; PF-9) |
| 049 reduction; 050 audit | RECONSTRUCTABLE / HEURISTIC arguments + ALIGNED at the exact strength the book teaches |
| Measure/topology/entropy material | historical only — no live claim to classify |

---

## 19 · Book Epistemic-Strength Audit

Sweep of III.1–III.8 for the commission's word list (67 hits examined in context):

- "therefore/hence" (9): all inside reproduced source derivations marked [E]/RECONSTRUCTABLE, or
  meta-discursive. No unsupported inferential leap found.
- "must" (48): normative citations of ratified invariants, constitutional articles, source-boxed
  laws, or GN-42 constraints — appropriate in every sampled instance.
- "proves/proven/minimal/unique/uniqueness": **all negative usages** ("no evidence proves
  totality," "not proven minimal," "not a proven closure," completeness/minimality/uniqueness
  listed as NOT ESTABLISHED). Exemplary.
- "is a metric / is a probability / is Bayesian / guarantees / optimal / is equivalent to": zero
  occurrences.
- **Two flags:** (i) III.6 §9 "the ratified model is internally complete as ruled" — the intended
  sense is authority-closure (the ruling closed the question), but "internally complete" can be
  read as a formal completeness claim nothing establishes (AF-F-3's transition calculus is absent
  from that same chapter) — LOW (MV-F-19). (ii) III.2 §1 "gaps can be computed" — true only
  relative to evaluators (§6); a qualifier would be exact — LOW, same finding.
- Verified against sources in detail: III.4's 025d quotations, III.6's 008 quotations, III.7's
  042/025h/025z quotations, III.8's 025f/121 quotations, III.5's design/verdict quotations, and
  III.1 §3's corrected census (invariants 2 TESTED / 8 READ / 2 RC — recounted against the v0.1
  table: exact). **No misquotation found**; this independently corroborates the hostile pass's
  "unusually accurate" assessment on the mathematical content specifically.

**Verdict: essentially no epistemic inflation; the book teaches the mathematics at or below its
established strength.**

---

## 20 · Findings Register (MV-F-1 … MV-F-22)

*(Class per the AF taxonomy; severity per §23 of the commission. "CONFIRMS" = independent
confirmation of an existing registered finding; "NEW" = not previously registered anywhere.)*

| ID | Sev | Class | Finding | Trace |
|---|---|---|---|---|
| MV-F-1 | MEDIUM | AF-3 | CONFIRMS PF-1 as (b) for {Insufficient, Stale, Prohibited} with executable demonstrations; PartiallySatisfied not established as required | §15; zero_reference.py |
| MV-F-2 | MEDIUM | AF-7/AF-1 | CONFIRMS AF-F-9: I-9's "four-way" is INVALID as a description of its READ source | §5 |
| MV-F-3 | LOW | AF-2 | NEW: source status set not closed — `Missing` used (25D.6) before defined (25D.7); `Failed` used (25D.23) and never a member of 𝒮; registered nowhere | 025d |
| MV-F-4 | MEDIUM | AF-3 | NEW compression member: EC's source-final 9-tuple (25E.37) / 8-tuple recap (025f) compressed past PF-5a's registered pair; plus the 8-vs-9 source-internal inconsistency | 025e/025f |
| MV-F-5 | MEDIUM | AF-2/AF-8 | NEW: no ratified admissibility law matches the ratified DC six-tuple (42.9 omits Temporal; 42.41 is the 7-tuple's) — the ratified contract has components but no conjunction of exactly those components | 042; ladder_dc_reference.py |
| MV-F-6 | MEDIUM | AF-2/AF-8 | NEW (sharpens PF-8): the ratified flow edge `PROPOSAL → DECISION under DC(d)` is not type-correct — nothing ratified produces or selects the decision d | v0.1 §3 flow; 025g/025h |
| MV-F-7 | MEDIUM | AF-3/AF-9 | CONFIRMS PF-9 with mechanism: uniqueness of "the in-force policy" under multiple applicable authorities NOT ESTABLISHED; I-11's guard evaluable only in the single-authority case | 025f; I-11 |
| MV-F-8 | MEDIUM | AF-9 | CONFIRMS AF-F-14 formally: the stratification loop's induction has no base case (genesis of the first in-force policy is exempt-or-circular). *Non-authoritative repair options:* (i) an explicit genesis axiom (initial force conferred by a recorded external human act — which is what L5 practice, e.g. GN-19-style rulings, actually does); (ii) typing the first act as an L1/L5 event outside L2's loop. Options only; decided nowhere | III.8; 121 |
| MV-F-9 | MEDIUM | AF-2 | CONFIRMS AF-F-13 formally: the interlock and any Γ↔ρ_A identification are well-defined only relative to an undefined map φ: R→P (or R→2^P). *Non-authoritative options:* r as a distinguished proposition; or r as a predicate over K with an induced satisfaction proposition. Options only | III.4 §10; 025d §25D.12 |
| MV-F-10 | MEDIUM | AF-8/AF-9 | CONFIRMS AF-F-15 formally: adjudication of CONFLICTED is an undefined operator (no signature, no authority typing, unexamined I-4 relation); note 025f's Resolve does NOT fill it — it resolves governance-source conflicts, a different domain | III.6 §10 |
| MV-F-11 | LOW | AF-2 | Learn, F (world dynamics), and η are uninterpreted function symbols; only η's openness is governed (OQ-1) — Learn/F carry no analogous flag (AF-F-3-adjacent) | 049 §49.92 |
| MV-F-12 | LOW | AF-7 (source-level) | ⪰_C's partial-order axioms never stated/argued; source heading "authority lattice" claims structure never established; book correctly carries "partial order" only | 025f §25F.14 |
| MV-F-13 | OBS | — | α_ρ's typing defect (outputs outside its codomain) — source-acknowledged, superseded in-source by Ω_A; book does not import it | 008 §§9–10 |
| MV-F-14 | OBS (positive) | — | The binary `Zero(K,EC)` is directly source-attested (25D.34; 025e); R-2's evidence basis is stronger than the ruling recorded | 025d/025e |
| MV-F-15 | OBS | — | Signature instability across sources (Zero ×4, Sārathi ×3, Lord ×2 forms); synthesis chose consistently; a register note would prevent future mis-citation | 025d/g/h |
| MV-F-16 | MEDIUM | AF-7-adjacent (historical) | PF-4 determination: executed matrix alone does not entail the published negative conclusion (SATURATING passes all 7 columns); conclusion valid only with the narrative premises — which the record contains. Book/log represent this honestly; the finding attaches to the historical verdict's §1 framing ("the experiment confirms…") read without §§2–6 | §14 |
| MV-F-17 | MEDIUM | AF-7-adjacent | EXP-01 has no surviving executable; I-5/I-6's TESTED grade describes a prose record of a claimed run. Independent re-implementation now corroborates their mathematical content at pipeline level; OQ-5 (replication of the *system* experiment) remains open and accurate | §14, §17 |
| MV-F-18 | LOW | AF-10-adjacent | Definedness/numerical caveats: BAYES undefined on {0,1} jointly; WM undefined on ∅; MAX needs empty-set convention; "Bounded [0,1]" true only away from these edges | exp01_recheck.py |
| MV-F-19 | LOW | D-2-class (book) | "internally complete as ruled" (III.6 §9) and "gaps can be computed" (III.2 §1) — two phrases that read one notch above their support; the only strength issues the sweep found | §19 |
| MV-F-20 | OBS | — | I-12's covering relation is well-defined, consistent with the FA layered extension, and executes; AF-F-3 (what moves an item) unaffected and still open | ladder_dc_reference.py |
| MV-F-21 | OBS | — | Metric refusal (25D.32) is mathematically correct; classification "directed epistemic discrepancy operator" is the right one; carried correctly everywhere | §9 |
| MV-F-22 | MEDIUM | AF-2 (systemic) | NEW consolidation: the model lacks identity/equality criteria for its own objects — evidence ~ (PF-3), frame equivalence (III.2 watch item), **K_t state equality (nowhere defined — new member)**, requirement identity (needed by φ). Four instances of ONE missing foundation; I-5 and replay/determinism (25D.27) are well-defined only relative to it | Cluster 2, extended |

---

## 21 · Severity Assessment

- **CRITICAL: none.** No major claim is mathematically invalid *as scoped by its own artifact*,
  and no ratified invariant fails under test. The architecture never rests on an invalid formula
  — chiefly because it asserts so few formulas and grades them honestly.
- **HIGH: none individually.** The aggregate of Cluster 2 (missing transformations/identities:
  MV-F-8, -9, -10, -22 + AF-F-3/4/5) would become HIGH the moment any implementation is attempted
  without resolving them; today, with L4 empty and every gap flagged, each is MEDIUM.
- **MEDIUM (10):** MV-F-1, -2, -4, -5, -6, -7, -8, -9, -10, -16, -17, -22 (12 entries; -16/-17
  are historical-record findings, the rest formal gaps).
- **LOW (5):** MV-F-3, -11, -12, -18, -19.
- **OBSERVATION (5):** MV-F-13, -14, -15, -20, -21.

---

## 22 · Open Questions Created or Deepened

- **Deepened OQ-1:** η's non-construction is now traced to 25D.39 + the requirement-origin list
  (constitution, policy, standards, ADRs, risk, human instruction, law, convention) — several of
  these origins are non-computational, so η-totality, if ever resolved affirmatively, will be
  totality *relative to oracles*.
- **Deepened OQ-3:** the successor experiment must be designed at **pipeline level** (the
  operator alone can never carry A/F/G) and must include D, E, I, J, and calibration; the scripts
  in `mathematical-tests/` are a non-authoritative starting skeleton.
- **Deepened OQ-5:** the two TESTED invariants now have an independent mathematical
  corroboration (this audit) but still no *system-level* replication.
- **New (via MV-F-22):** an **identity calculus** — one research object covering evidence ~,
  state equality, frame equivalence, requirement identity — rather than four scattered watch
  items.
- **New (via MV-F-5/6):** ratified admissibility law for the ratified DC; typed decision-
  alternative provenance in the canonical flow.
- **New (via MV-F-8):** genesis/base-case axiom for the stratification loop.
- **New (via MV-F-4/3):** register the EC final tuple and the `Failed` status as compression-
  family members.

---

## 23 · What Passed

- Both TESTED invariants (I-5 duplicate/corroboration, I-6 dependency-first) — **independently
  corroborated as pipeline-level mathematical facts**, with executed counterexamples showing why
  the pipeline level is the only level at which they can hold.
- The EXP-01 negative verdict — confirmed and strengthened to a provable claim.
- The GN-27 CSV adjudication — corroborated by re-derivation of the discrepancy mechanism.
- I-12 covering relation, A6 boundary semantics, no-averaging conjunction, Unknown→Block — all
  execute exactly as specified.
- Zero's computability-in-principle (relative to evaluators), determinism of the aggregation,
  no-invented-gaps, Missing≠Unknown, Conflicted-preservation — 25D.38 T1–T8 now executed, 8/8.
- The metric refusal, the probability refusal, the non-monotonicity acknowledgment (42.36–38),
  the undecidability/intractability boundary statements (49.43–49.58) — all mathematically
  correct.
- Book grade-carriage and quotation fidelity on every mathematical item checked.

## 24 · What Failed

- **I-9's embedded source-description ("four-way")** — false of its cited source (AF-F-9/MV-F-2).
- **The executed EXP-01 matrix as sole support for the published conclusion** (MV-F-16) — the
  conclusion needs the narrative premises (it has them; the failure is of the matrix-only
  reading, which no audited artifact asserts but which the ratified "7 properties" phrase
  invites).
- **Operator-level readings of I-5/duplicate-safety** — raw SATURATING and BAYES-LIKE fail
  duplicates, dependency, and irrelevance outright (100-duplicate saturation to 1.0).
- **Scalar contradiction-preservation** — impossible; executed counterexample.
- **The historical verdict's irrelevance footnote scope** — normalization is doing the work for
  all four operators, not two.
- **Unqualified boundedness/definedness of the operators** at edge inputs (MV-F-18).
- **The 25F.14 "lattice" heading** — structure never established (content's "partial order"
  itself is a postulate).

## 25 · What Remains NOT ESTABLISHED

η's construction and totality (OQ-1) · the evidence-equivalence relation ~ and the whole identity
calculus (PF-3/MV-F-22) · the r↔P typing map (AF-F-13/MV-F-9) · ladder transition calculus
(AF-F-3) · policy-version transition calculus incl. in-flight semantics (AF-F-4) · stratification
base case (AF-F-14/MV-F-8) · adjudication typing (AF-F-15/MV-F-10) · uniqueness of the in-force
policy under multiple authorities (MV-F-7) · ratified admissibility law for the ratified DC
(MV-F-5) · decision-alternative provenance in the flow (MV-F-6) · operator selection (OQ-3) ·
calibration and criteria D/E/I/J as *system* tests (PF-4/OQ-5) · action/execution semantics
(OQ-4) · kernel completeness/minimality/uniqueness (PF-2/OQ-2) · status-set closure (MV-F-3) ·
Learn/F constructions (MV-F-11) · selector-loop termination without budget policy · partial-order
axioms for ⪰_C (MV-F-12) · L4 realization of any L2 formal object (CF-015).

---

## 26 · Recommended Governance Actions

*(Recommendations only; this verifier decides nothing.)*

1. **Route MV-F-3 and MV-F-4 into the pending PF-compression-family disposition** (GN-45.2/GN-46
   scope) as newly registered members; note 025e-vs-025f's 9-vs-8 EC inconsistency in the same
   entry.
2. **Adopt the §15 load-bearing/benign split as the formal half of the compression disposition**:
   defect candidates = PF-1{Insufficient, Stale, Prohibited}, PF-5a(coupled to AF-F-13),
   PF-7(+MV-F-5), PF-9(+MV-F-7), AF-F-9; benign/deliberate = PF-5b, PF-6(core), PF-8(core),
   AF-F-11, PF-1{Satisfied, NotApplicable}; research residue = PF-6's Ω_A expressibility,
   PF-1{PartiallySatisfied}, PF-8's flow-typing sliver (MV-F-6).
3. **Treat AF-F-13/14/15 with the formal analyses of MV-F-9/-8/-10 as their research inputs**;
   the sketched options are explicitly non-authoritative.
4. **Consolidate the identity questions (MV-F-22) into one research object** (identity calculus)
   rather than four watch items — it underlies I-5, replay, and frame comparison simultaneously.
5. **For OQ-3's successor experiment**: design at pipeline level; include D/E/I/J and
   calibration; require a surviving executable + generator provenance (the precise defects of
   the first run); the `mathematical-tests/` scripts may serve as a non-authoritative skeleton.
6. **Keep GN-27's adjudication as is** (corroborated); optionally annotate the experiment
   registry with this audit's mechanism-level explanation of the CSV (mixed semantics), which
   converts "unreliable-standalone" from a verdict into an explained verdict.
7. **AF-F-9 disposition** (I-9 wording vs its source) now has the full independent confirmation
   it was waiting for; the text decision is governance's.
8. **A signature register** (MV-F-15) — one table fixing the canonical arities of Zero/Lord/
   Sārathi and their historical variants — would close a recurring mis-citation hazard cheaply.
9. **Book side:** the two LOW phrasings (MV-F-19) are candidates for the same bounded-correction
   class GN-45.1 used; no other book-side mathematical correction is indicated by this audit.

---

## FINAL VERDICTS (commission §25 vocabulary; the §26 distinctions kept separate)

| Dimension | Verdict |
|---|---|
| Ratified architecture (v0.2 + FA), boundary/invariant layer | **MATHEMATICALLY SOUND WITH QUALIFICATIONS** (the qualifications are §20's MEDIUM findings — all of the incompleteness kind, none of the invalidity kind except I-9's embedded source description) |
| Ratified architecture, formal-object layer (η, Learn, calculi, identities) | **MATHEMATICALLY UNDER-SPECIFIED** (openly, with governed homes for most gaps) |
| Zero / ladder / DC / governance-resolve computation | **COMPUTABLE UNDER RESTRICTIONS** (finite structures, explicit semantics, evaluator oracles) |
| The architecture as a system | **NOT COMPUTATIONALLY REALIZED** (CF-015 — accurately represented everywhere) |
| Statistical content | **STATISTICALLY SOUND** (by correct abstention; no positive statistical property established; calibration unexecuted) |
| EXP-01's two invariants | **MATHEMATICALLY SOUND at pipeline level; independently corroborated; system-level replication still open (OQ-5)** |
| Source governance algebra, decision-theory layer (025f/g/h/z) | **ARCHITECTURALLY INTERPRETIVE** (unratified; mathematically plausible postulates with the §20 caveats) |
| Edition-2 Part III (mathematical explanation layer) | **MATHEMATICALLY SOUND WITH QUALIFICATIONS · ARCHITECTURALLY ALIGNED** (two LOW phrasings; quotation and grade fidelity independently confirmed) |
| Any claim of operator selection, matrix-only sufficiency, kernel minimality, η-totality, or implemented formal machinery | would be **MATHEMATICALLY UNSUPPORTED / ARCHITECTURALLY UNAUTHORIZED** — and none is made by the audited artifacts |

**STOP.** Audit complete. Nothing rewritten, nothing repaired, no OQ resolved, no v0.3, no
mathematical theory approved. All findings await governance disposition.
