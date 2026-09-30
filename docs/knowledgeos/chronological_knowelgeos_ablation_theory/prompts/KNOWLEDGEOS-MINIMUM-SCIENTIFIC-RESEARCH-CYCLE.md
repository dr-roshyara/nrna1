# KNOWLEDGEOS — MINIMUM SCIENTIFIC RESEARCH CYCLE

| | |
|---|---|
| **Kind** | design minimization. ⚠ **PROPOSED — authority: generated.** Reviews and minimizes `prompts/KNOWLEDGEOS-SCIENTIFIC-RESEARCH-ARCHITECTURE-OPTIMIZATION.md` (`294d22b31`, "OPT") |
| **Commission** | human, 2026-09-25, "KNOWLEDGEOS — MINIMUM SCIENTIFIC RESEARCH CYCLE" |
| **Reuses, never replaces** | ARCH (two bounded contexts; RRP/ACL) · MP (Phase 1) · S2 (L0–L5 L391–396; F0–F3 L1742–1745; M0–M5 L1904; T1–T8 L2019–2028; `Experiment` L2030–2050; experimental sequence L2051–2069; provenance classes L1155–1160; §13B L2111–2124) · Research Release Check (L0-DEC-27) |
| **Adds** | no ladder, no bounded context, no gate, no control plane, no validation context, no protocol |
| **Corpus** | ⛔ none read. The first cycle below uses **synthetic data only** |
| **Tags** | EXISTING · REQUIRED DELTA · OPTIONAL · DEFER · `AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION` |

Paths are under `docs/knowledgeos/knowledgeos_theory_chronological_extraction/` unless stated.

---

## 1. Executive conclusion

**Two different "first cycles" exist, and they have different blockers.**

| Cycle | What it demonstrates | Status |
|---|---|---|
| **C-M — method-validation cycle** (synthetic worlds) | the machinery can take one hypothesis about **a method** through freeze → sealed hold-out → experiment → independent verification → SURVIVED / REFUTED / INCONCLUSIVE, and can tell known structure from null structure under corruption | **designable now.** Needs **no corpus** and **no protocol amendment.** Blocked only by the **release scope** (L0-DEC-30 covers Critical Attack Pass 01 only; any other research scope needs a new release, L0-DEC-27) |
| **C-H — real-hypothesis cycle** (KnowledgeOS material) | a real candidate formulation survives its pre-registered falsification on held-out evidence | **blocked on evidence and governance:** no hypothesis is at **F2** (S2 L1759); a temporal hold-out needs corpus reading beyond the formation window (L0-DEC-30; chronological progression also needs C-7); an independent reader must be available (S2 §11.0) |

**The minimum** is C-M, built from:
- **existing** records (S2 `Experiment`);
- **one** synthetic generator;
- **one** simple detector;
- **one** freeze-plus-seed-commitment convention;
- **one** independent re-computation.

Everything else in OPT is OPTIONAL or DEFER for the first cycle (§2, §13).

C-M validates the **method**, never KnowledgeOS theory.

---

## 2. Architecture minimization table

| Proposed mechanism (OPT §) | Needed for the first falsifiable cycle (C-M)? | Why | Existing equivalent | Required change | Can defer? |
|---|---|---|---|---|---|
| hypothesis freeze (§13) | **YES** | without it the hypothesis can be fitted to the result | S2 `Experiment.falsification_condition` before execution (L2045); §13.0a order | **REQUIRED DELTA:** a *convention*: put split, metric, threshold and stopping rule in `Experiment.design`, and commit its hash **before** test data exists (§3 step 5). Making the fields mandatory = `AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION` (S2) | no |
| temporal hold-out (§12) | **NO** for C-M · **YES** for C-H | C-M has no history; C-H needs formation ≠ test | S2 M3→M4 *"corroboration from corpus outside the seeding window"* | none for C-M | yes, until C-H |
| structural hold-out (§12) | **NO** | a transfer claim is not needed to show one falsifiable cycle | — | none | **DEFER** |
| synthetic hold-out (§12) | **YES** | the test worlds must be unseen during development, or the method is fitted to them | S2 T7 | **REQUIRED DELTA:** sealed test seeds (§3 step 5) | no |
| active learning (§5) | **NO** | C-M has generated ground truth; no labels needed | MP §8 human verification | none | **DEFER** (§10) |
| embeddings (§4) | **NO** | the simplest sufficient detector is lexical (§6); embeddings add pretraining leakage | — | none | **DEFER** |
| clustering (§4) | **NO** | not needed to test edge recovery | — | none | **DEFER** |
| graph embeddings (§4) | **NO** | the self-fulfilling-loop risk; no need | — | none | **DEFER** |
| evidence graph (§7) | **NO** | a derived view; a flat record chain suffices for one cycle | MP §41 Tier 3 | none | **DEFER** |
| six clocks (§6) | **PARTLY** | C-M needs only `t_freeze < t_test-data-exists < t_result`; the rest serve C-H | S2 `iteration_id` (Q32); git commit times | the freeze commit time and the result commit time | the remaining four clocks: yes |
| `FormalModel` (§8) | **NO** as a new record | C-M's hypothesis is empirical; its formal object is the **generator**, specified in the design | S2 F-stages | none | yes |
| SAT/SMT (§9) | **NO** | the C-M hypothesis makes no deductive claim | S2 T2/T4 | none | **DEFER** until an F2 hypothesis claims consistency or derivability |
| theorem proving (§9) | **NO** | same | — | none | **DEFER** |
| statistical controls (§10) | **YES** (minimum package, §8) | a claim about FDR and recall is statistical | S2 §13B six-part statement | the §8 package in `Experiment.design` | no |
| multiplicity control (§10) | **YES, small** | C-M tests one hypothesis over a declared grid of corruption levels | — | one declared family; Holm | no |
| repeated-look control (§10) | **YES** | a second look at the test seeds contaminates them | — | one look; the seed commitment makes a second look detectable | no |
| synthetic benchmark (§11) | **YES** (it *is* C-M) | the method must be tested against known structure and a null | S2 T7 + OQ-11 (falsifier first) | **IMPLEMENTATION REQUIRED:** generator + scorer | no |
| independent reader (§11 of S2) | **YES** | without it the result is SELF only, which S2 calls *"demonstrably insufficient"* (L1797) | S2 §10.2 INDEPENDENT; §11.0 | an independent re-computation (§10 here) | no |
| audit (Review §5.4) | **NO**, beyond the independent verification | one cycle needs verification, not an audit programme | — | none | **DEFER** |
| provenance hashing | **YES, minimal** | the freeze, seeds and results must be bound | git commits + sha256 (EXISTING tooling) | hash the design, seed commitment and result | no |

**Count:**
- **YES:** 9 — freeze, synthetic hold-out, statistics, multiplicity, repeated-look, benchmark, independent reader, hashing, clocks (partly);
- **NO for C-M:** 11.

---

## 3. Minimum Scientific Viable Cycle (C-M)

| # | Step | Existing artifact | New artifact | Role | Input | Output | Invariant | Failure condition |
|---|---|---|---|---|---|---|---|---|
| 1 | **Candidate** | S2 `SI-`/seed item; here, a **method claim** | none | researcher | the OPT §11 design | a candidate method claim | stated as a claim about a **method**, not about KnowledgeOS | phrased as a theory claim ⇒ reject |
| 2 | **Hypothesis** | S2 seed item with `falsifiable_as` (§5.2) | the §4 template | researcher | the candidate | H1 / H0 in the §4 schema | typed falsifier (`STATISTICAL_FALSIFIER`) | falsifier `FALSIFIABILITY_NOT_YET_SPECIFIED` ⇒ not runnable |
| 3 | **Formalization** | S2 F-stages | the generator specification (in `design`) | researcher + reviewer | H1 | the generator G and the detector D, fully specified | every parameter closed (the F2 analogue for the *generator*) | an open parameter ⇒ stop |
| 4 | **Falsifier** | `Experiment.falsification_condition` | none | researcher | H1, G, D | the refutation thresholds (§5) | written before any test world exists | written after test data ⇒ run invalid |
| 5 | **Freeze** | git + sha256 | **freeze convention:** `design` + thresholds + seed-commitment rule committed; `freeze_hash` = sha256 of that design | researcher | steps 2–4 | commit `c_freeze`, `freeze_hash` | **test seeds = HMAC-SHA256(key = freeze_hash, msg = "test-" + i)**, so the test worlds are unknowable before the freeze | any test world generated before `c_freeze` ⇒ INSTRUMENT fault, run void |
| 6 | **Hold-out** | — | the test-seed list derived after the freeze; the dev seeds fixed in the design | automated | `freeze_hash` | sealed test worlds | the dev seeds are disjoint from the test seeds; test worlds are generated once | a test seed reused in development ⇒ void |
| 7 | **Experiment** | S2 `Experiment` + the 4-stage sequence | none | automated run; researcher writes stages 3–4 | D, the test worlds | stage-2 RAW RESULT (metrics per world) | one look; stage 2 is observational only | a second look / re-run with changes ⇒ a new hypothesis revision on new seeds |
| 8 | **Independent verification** | S2 §10.2 INDEPENDENT; §11.0 | a verification record (§10) | verifier (separated) | the frozen design + the test seeds only | recomputed metrics + a verdict | the verifier re-implements the scorer from the spec, without the dev code or dev reasoning | mismatch ⇒ DISCREPANCY, not SURVIVED |
| 9 | **Outcome** | S2 §1.1 outcomes | none | rule-based, from the frozen thresholds | stage 2 + verification | **SURVIVED / REFUTED / INCONCLUSIVE** for H1 | decided only by the pre-registered rule | an outcome chosen by judgment ⇒ invalid |

---

## 4. Required artifacts

### 4.1 Generic hypothesis template

A neutral template that chooses no theory. Every field has an epistemic purpose. For C-M it is carried inside S2 `Experiment.design`, so no schema amendment is needed.

```yaml
hypothesis:
  id:               # identity; namespace choice [O] (MP §4A / GIA-6 collision check)
  statement:        # the claim, in one sentence, testable as written
  origin:           # [C]/[S]/[E]/[T] — where the statement came from (S2 §5C.2); C-M: [E]
  evidence:         # refs that motivated it (not that support it); C-M: OPT §11
  assumptions:      # everything the test takes as given; exposes what a result does NOT test
  formalization:    # F-stage + the formal object (C-M: generator G, detector D); open terms ⇒ ≤ F1
  rivals:           # competing explanations the test must discriminate (C-M: "D finds structure in noise")
  prediction:       # the observable consequence if true, stated before data
  falsifier:        # typed (S2 §5.2) + the exact refutation condition
  metric:           # the estimand and how it is computed
  population:       # what the result generalizes to (C-M: worlds from families G_θ, θ in the declared grid)
  data_split:       # dev / test construction and the seed-commitment rule
  stopping_rule:    # a fixed N (no optional stopping)
  freeze_hash:      # binds all fields above before test data exists
  validation_method: # SELF + INDEPENDENT (S2 §10.2), with the independence class (§10)
  status:           # REGISTERED → FROZEN → TESTED(SURVIVED|REFUTED|INCONCLUSIVE); revisions = new id-Rn
```

### 4.2 Records

| Record | Existing? | Identity | Lifecycle | Invariants | Commands → events | Provenance |
|---|---|---|---|---|---|---|
| **Hypothesis** | S2 seed item (+ the template in `design`) | `id` (+ `-Rn`) | REGISTERED → FROZEN → TESTED | an edit after FROZEN is impossible; a change ⇒ a new `-Rn` | Register → HypothesisRegistered | origin, evidence refs |
| **FormalModel** | not needed for C-M | — | — | — | — | — |
| **Experiment** | **S2 `EXP-`** (L2030) | `EXP-####` | designed → executed | `falsification_condition` precedes execution (Q35); stages in order (§13.0a) | Run → ExperimentExecuted | the commit of the design; the model/code version |
| **Freeze** | **new convention, no new record type**: the commit of `design` | `freeze_hash` | created once | hash immutable; `c_freeze` precedes any test-world generation | Freeze → HypothesisFrozen | git commit |
| **Result** | Experiment stage 2 (RAW RESULT) | `EXP-#### / stage-2` | written once | observational only; cites `freeze_hash` | Record → RawResultRecorded | the result commit, after `c_freeze` |
| **Verification** | S2 §10.2 record (INDEPENDENT / SECONDARY_REVIEW) | `EXP-#### / verification` | written once | independence class disclosed (§10); the verifier had no dev code or reasoning | Verify → VerificationRecorded | verifier identity + inputs |

**No aggregate is required.** The only cross-record invariant is ordering (`c_freeze` < test-world generation < result < verification), and that is enforced by commit order plus the seed commitment, not by a transaction.

---

## 5. Synthetic method-validation experiment (EXP-C-M, neutral)

**Purpose:** show that the machinery can distinguish a known structure from a null structure under controlled corruption, while keeping provenance and hold-out integrity. **It validates the METHOD, not KnowledgeOS theory.**

| Element | Specification |
|---|---|
| **Vocabulary** | arbitrary tokens `c001 … c200`. ⛔ **No KnowledgeOS term, concept or structure** (S2 OQ-11) |
| **Positive worlds** | a latent DAG over K concepts (random order + edge probability p); documents rendered in time order; an edge u→v appears as co-mention of u and v plus, with probability s, an explicit lineage sentence *"v builds on u"* |
| **Negative (null) worlds** | the same K, document count, length and token frequencies; **no latent edges**: tokens placed independently (a degree-preserving shuffle of a positive world's mentions) |
| **Confounded worlds** | two latent structures with the **same pairwise co-mention rates**: a **Markov-equivalent** pair, the chain u→v→w vs the fork u←v→w. *(Corrected 2026-09-26: the earlier text "common cause v←u→w" has a different skeleton, and so is distinguishable; see `KNOWLEDGEOS-C-M-REGISTRATION-AND-FALSIFICATION-REVIEW.md` X-1.)* *(Superseded again 2026-09-26: under the child-centred renderer even chain vs fork differ in marginal frequencies. The final construction is a skeleton-only renderer with no lineage, which makes orientation unobservable by construction; see `KNOWLEDGEOS-C-M-FINAL-ADVERSARIAL-REVIEW.md` §4.)* The detector may recover the adjacency; it must report **direction** as INCONCLUSIVE where the data cannot identify it |
| **Corrupted worlds** | positive worlds plus the corruption grid: synonymy (a token aliased to a second token with rate a) · duplication (d % documents copied) · missing information (lineage sentences dropped with rate m) · contradiction (a reversed lineage sentence with rate r) · date corruption (a cited date replaces the authorship date, rate t — the MP §3 P3A error pattern) |
| **Detector D (the method under test)** | the **lexical baseline**: per concept pair, a co-mention count + a lineage-sentence count → a score; a permutation-calibrated threshold (edges kept at a per-world FDR target q* via Benjamini–Hochberg on permutation p-values); direction assigned only from lineage sentences, otherwise UNWITNESSED |
| **Splits** | **dev:** declared seeds, used freely to build D (tune nothing after the freeze). **test:** seeds = HMAC(freeze_hash, "test-i"), i = 1…N per world type × corruption level. **Synthetic hold-out:** the test grid includes one **generator parameter setting absent from dev** (e.g. an unseen K or p) |
| **Hypothesis H1** | on test worlds at corruption level ≤ c₀: mean per-world edge **recall ≥ r₀** and **FDR ≤ q₀** in positive worlds, **and** in null worlds the mean number of reported edges **≤ k₀** |
| **Null H0** *(revised 2026-09-26: recall is undefined in null worlds, which have no true edge set)* | **D finds "structure" at chance.** Operationally, the two parts are tested separately. **(i) Positive worlds:** D's edge recall and precision are no better than those of the same D applied to each world's degree-preserving shuffle and scored against the *original* world's true edges (a matched-null comparison). **(ii) Null worlds:** D's reported-edge rate is no lower than would be expected if its calibrated scores ignored the absence of structure. Concretely, under a valid calibration the permutation p-values in null worlds are uniform, and the reported-edge count is ≈ q* × (candidate pairs) or fewer |
| **Metrics, per world type** *(revised 2026-09-26)* | **Positive worlds:** recall · precision / FDR · structural Hamming distance (SHD) · calibration (ECE of edge scores vs truth). **Null worlds:** false-edge count · reported-edge rate (reported edges / candidate pairs) · maximum spurious structure (the size of the largest reported connected component) · null calibration (uniformity of the permutation p-values, e.g. a KS statistic). **Confounded worlds:** adjacency recovery (recall / precision on undirected edges) · direction-assertion rate · wrong-direction rate · INCONCLUSIVE-direction rate. **Corrupted worlds:** the positive-world metrics per corruption level, plus robustness = metric slope vs corruption level |
| **Unit / population** | the **world** is the unit (edges within a world are dependent). The population is the worlds from the declared generator grid only |
| **Stopping rule** | a fixed N worlds per cell, declared in the design; no optional stopping; one look |
| **Falsifier (REFUTED if any)** | (a) the upper 95 % bound of mean FDR > q₀ in positive worlds at ≤ c₀; (b) the lower 95 % bound of mean recall < r₀; (c) the null-world mean reported edges > k₀ (the lower bound exceeds k₀); (d) the confounded-world wrong-direction rate > w₀ |
| **INCONCLUSIVE** | the confidence intervals straddle a threshold at the pre-declared N |
| **SURVIVED** | none of (a)–(d), with the intervals clear of the thresholds |
| **Expected output** | an S2 `Experiment` record with stages 1–4; a verification record; an outcome ∈ {SURVIVED, REFUTED, INCONCLUSIVE} for H1; a statement of **METHOD_VALIDATED** only if SURVIVED and independently verified, **always carried with its `validation_scope`** (§16) |
| **Run-invalidity (INSTRUMENT fault, not REFUTED)** | a test world generated before `c_freeze`; a dev / test seed overlap; a second look; a `freeze_hash` mismatch; verifier access to dev code |

The numerical thresholds r₀, q₀, k₀, w₀, c₀ and N are **fixed in the design before the freeze.** They are left open here on purpose. Choosing them is part of the experiment's registration, not of this architecture.

**Registration constraint (added 2026-09-26).** The thresholds must be justified by a **stated requirement**: what a detector must achieve to be useful as a Phase-1 candidate generator. They must **not** be set from D's own performance on the dev worlds. Setting a threshold just below observed dev performance makes H1 near-certain to survive, and so unfalsifiable in practice. If dev results are consulted at all, the registration records that they were, and how.

**What C-M actually tests (added 2026-09-26).** The object under test is the **composite procedure**: generator specification + detector + scorer + freeze + hold-out + analysis, not the detector alone. A SURVIVED outcome reads: *"the specified research procedure discriminated the declared synthetic regimes under the declared generator assumptions."* It never reads *"the method works"* in general.

---

## 6. ML decision

| Role | Meaning | In C-M? |
|---|---|---|
| **Discovery acceleration** | ML proposes candidates (MP §8, §27) | **no** |
| **Scientific validation of an instrument** | the method is evaluated against ground truth | **yes — this is C-M**, with a non-ML method |
| **Theory validation** | a theory is tested against independent evidence | **no** (that is C-H) |

**Conceptual comparison of first models:**

| Model | Sufficient for C-M? | Leakage | Complexity |
|---|---|---|---|
| **lexical baseline** (counts + permutation threshold) | **yes**: the synthetic signal is lexical by construction | none (no pretraining) | minimal |
| classical vector model (TF-IDF + cosine) | yes, but adds nothing the counts lack in token worlds | none | low |
| embedding model | no advantage on arbitrary tokens; imports pretraining priors | **pretraining leakage** | medium |
| supervised classifier | needs labels, a train / test protocol and calibration | label leakage | medium |

**Decision [D]:** the **first model is the lexical baseline.**
- ML proper is **DEFERRED** until the C-M pipeline exists.
- A later ML method enters as a **second, separately frozen** hypothesis of the same C-M form: *"method M' beats the baseline under the frozen protocol"*.
- No large language model is part of the first cycle.

---

## 7. Logic decision (with the mathematical threshold)

**When a hypothesis becomes mathematically or logically testable (S2 §9):**

| Stage | Meaning | What it can support |
|---|---|---|
| **F0** | an observed pattern | only corpus search (T1) and descriptive counts (§13B) |
| **F1** | a candidate structure; **terms may be open** | *not* consistency, derivability or counterexample tests: an open term has no fixed interpretation, so `T ∧ ¬P` has no determinate truth conditions, and a solver would test an arbitrary completion chosen by the encoder (an `[E]` artifact), not the hypothesis |
| **F2** | every term defined; conditions stated | consistency (SAT/UNSAT), derivability, counterexample search, model checking |
| **F3** | F2 + a test executed and survived | reporting as validated (S2 L4), never canonical |

**Genuinely empirical hypotheses** (for example C-M's H1) need no F2 theorem. They need a closed **measurement** specification (the generator, the metric, the thresholds), and that is the F2 analogue for an empirical claim.

**When logic tools become necessary [D]:**

| Hypothesis type | Tool | Status vocabulary |
|---|---|---|
| "these axioms are consistent" | SAT/SMT, or a finite model finder | `SAT` · `UNSAT` · `UNKNOWN` |
| "P follows from T" | SMT (`T ∧ ¬P`) / ITP | `DERIVABLE` · `NOT_DERIVABLE_IN_ENCODING` · `UNKNOWN` |
| "P has no counterexample" | model finding | `COUNTEREXAMPLE(model)` · `NONE_WITHIN_BOUND(bound)` |
| "an invariant holds over a state machine" | model checking | `HOLDS` · `VIOLATED(trace)` · `UNKNOWN(bound)` |

⛔ **`NONE_WITHIN_BOUND` is never converted into `PROVEN`.** Only an unbounded proof (ITP) or a decision procedure over a decidable fragment yields `DERIVABLE` / valid.

**C-M needs no logic tool.** The first logic tool (one SMT solver) is needed only when an F2 hypothesis makes a deductive claim; theorem proving is deferred.

---

## 8. Statistical decision (the minimum package, C-M)

| Element | C-M value |
|---|---|
| population | worlds generated from the declared grid of G_θ (no generalization beyond it) |
| unit of analysis | one world |
| estimand | mean per-world FDR, mean recall, mean null-world false-edge count, wrong-direction rate |
| H0 / H1 | §5 |
| metric | §5 |
| dependence | edges are nested in worlds ⇒ inference at the world level; confounded and corrupted cells analysed separately |
| split | dev / test via the seed commitment |
| stopping rule | a fixed N per cell; one look |
| uncertainty | 95 % bootstrap CI over worlds (or an exact binomial CI for the proportion of worlds meeting a criterion) |
| multiplicity | one family = the cells of the declared corruption grid × criteria (a)–(d); Holm-adjusted |
| effect size | recall and FDR themselves, plus the difference from the null-world baseline |

**For a purely deductive F2 hypothesis:** none of this applies. The test is the §7 logic result.

---

## 9. Hold-out design

| Hold-out | C-M | C-H |
|---|---|---|
| **Synthetic** | **required:** test seeds derived from `freeze_hash`; one unseen generator setting | — |
| **Temporal** | not applicable | **required:** a `t_hist` cut; hypotheses formed ≤ cut, tested > cut (S2 M3→M4). Blocked by corpus reading today |
| **Structural** | deferred | optional |

**Access rule:** a hold-out is spent after one confirmatory look. A new look requires a new hypothesis revision **and** new seeds, or new held-out material.

---

## 10. Independence model

**Shared components must be disclosed.** "A different process" does not mean independent.

| Component | Shared between the developer and the verifier in C-M? | Consequence |
|---|---|---|
| data | **yes, necessarily** (the same test worlds, regenerated from the seeds) | the verifier checks the *computation*, not the *data* |
| generator spec | **yes** (the frozen design) | a spec error is shared, so both can agree on a wrong world; this is mitigated by generator self-tests (ground-truth round trip) |
| detector spec | **yes** | the verifier re-implements from the spec, so implementation bugs are independent while spec errors are shared |
| code | **must NOT be shared** | an independent re-implementation of the scorer (and ideally of the generator) |
| model family (if an AI writes both) | **often shared** | classify the verification as S2 `SECONDARY_REVIEW`, **not** INDEPENDENT (S2 §11.0: *"'Independent' is NOT redefined…"*) |
| prompt / reasoning | **must NOT be shared** | the verifier gets the design and the seeds only |
| parser / tooling | disclose | shared numeric libraries are accepted and disclosed |
| mathematical encoding | shared spec | as above |

**Independence classes, recorded on the verification record:**

| Class | Meaning | Evidential value |
|---|---|---|
| `SELF` | the developer re-runs | none beyond reproducibility |
| `SECONDARY_REVIEW` | a fresh context of the same model family, with separate code | catches implementation errors; **not** M2 |
| `INDEPENDENT` | a separate reader with no access to the development reasoning or code (S2 §11.0), e.g. a human or a different organisation / model family | qualifies for S2 M2 |

**Minimum for evidential value in C-M:** `SECONDARY_REVIEW` catches implementation bugs. **`INDEPENDENT` is required before any result is reported as METHOD_VALIDATED with verification.** Its availability is an *"experimental resource decision"* (S2 §11.0), not assumed here.

---

## 11. Leakage threat model

| Threat | Relevant to | Smallest preventive control |
|---|---|---|
| future corpus leakage | C-H | temporal hold-out; MP §29 hindsight rule; formation records cite only `t_hist ≤ cut` |
| model pretraining leakage | C-H (and C-M if embeddings are used) | the lexical baseline first; embeddings deferred; the model id recorded |
| active-learning label leakage | deferred | none needed now (no active learning) |
| repeated-look leakage | C-M, C-H | one look; the seed commitment makes any pre-freeze test world impossible to produce, and any second look is visible in the commit history |
| hold-out leakage | C-M | test seeds derived from `freeze_hash`, so they are unknowable before the freeze |
| researcher degrees of freedom | C-M, C-H | every threshold, N, metric, grid and exclusion in the frozen design |
| hypothesis revision after results | C-M, C-H | a revision = `-Rn` with new seeds; the old result stays |
| benchmark contamination | C-M | an arbitrary token vocabulary; the generator family fixed without KnowledgeOS concepts (S2 OQ-11); the corpus benchmark files unread |

---

## 12. Dependency DAG

```text
GOVERNANCE ─────────── a release scope that includes C-M research            [GOVERNANCE BLOCKED: L0-DEC-30 scope; L0-DEC-27]
   ↓
EXISTING PROTOCOL ──── S2 Experiment, §13.0a, §13B, §10.2, §11.0; F-stages   [EXISTING]
   ↓
MINIMUM SCHEMAS ────── §4 template inside Experiment.design; freeze convention [DESIGN REQUIRED — done here, needs review]
   ↓                   (mandatory freeze fields in S2 = AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION; optional for C-M)
SYNTHETIC METHOD TEST ─ generator G, detector D, scorer                      [IMPLEMENTATION REQUIRED]
   ↓
HYPOTHESIS FREEZE ──── thresholds + N fixed; freeze commit + hash            [EXPERIMENT REQUIRED]
   ↓
HOLD-OUT ───────────── test seeds from freeze_hash                           [IMPLEMENTATION + EXPERIMENT REQUIRED]
   ↓
FIRST FALSIFIABLE CYCLE ─ EXP-C-M run, stages 1–4                            [EXPERIMENT REQUIRED]
   ↓
INDEPENDENT VERIFICATION ─ re-implemented scorer; independence class          [EXPERIMENT REQUIRED; INDEPENDENT reader = resource decision]
   ↓ (later)
C-H ───────────────── an F2 hypothesis + temporal hold-out + corpus access    [GOVERNANCE BLOCKED: L0-DEC-30; C-7 for chronological; no F2 item exists]
```

**Ownership [D, flagged O]:**
- C-M's code is Phase-2 laboratory software (S2 §3A/§3B, T7), not F-Series execution-assurance machinery. H-14a therefore does not obviously govern it.
- Whether governance agrees is [O]. This document does not decide it.

---

## 13. DEFERRED

| Mechanism | Why deferred |
|---|---|
| large ML infrastructure; LLM-based detectors | the lexical baseline suffices on synthetic tokens; pretraining leakage; S2 §3B.7 *"do not over-engineer"* |
| embeddings, clustering, graph embeddings, community detection | not needed to test known-vs-null recovery; each adds degrees of freedom and leakage |
| active learning / annotation platform | C-M has generated ground truth; the real-data reference-set owner is UNRESOLVED |
| evidence graph (read model) | one cycle is a linear record chain |
| four of the six clocks | only the freeze / generation / result order matters for C-M |
| a `FormalModel` record type | no F2 hypothesis exists; C-M is empirical |
| SAT/SMT, model checking, theorem proving; multiple solver families | no deductive hypothesis in the first cycle |
| structural hold-out | a transfer claim is not needed for one falsifiable cycle |
| a large ontology; additional bounded contexts; complex orchestration; a production platform | none is needed, and ARCH forbids new contexts |
| an audit programme; KOS-G-047 definition | one verification suffices; gates are governance's (L0-DEC-15) |

---

## 14. Implementation prerequisites

1. A reviewed design for G, D and the scorer (this document §5), with thresholds and N chosen and **committed before the freeze**.
2. Generator self-tests: the ground truth round-trips; null worlds preserve the declared marginals.
3. A seed-commitment utility (HMAC of the freeze hash) and dev / test disjointness check.
4. A single entry point that writes the S2 `Experiment` record stages in order.
5. A separated verifier with the design and seeds only, and an independently written scorer.

## 15. Governance prerequisites

| Prerequisite | Status |
|---|---|
| a Research Release (L0-DEC-27) whose scope includes the C-M synthetic experiment | **not granted.** L0-DEC-30 scope = Critical Attack Pass 01 only |
| who may write the laboratory code (S2 §3A/§3B research software vs governance machinery) | [O]; see §12 |
| making the freeze fields mandatory in S2 | `AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION` (optional for C-M) |
| an INDEPENDENT reader (human or separate organisation / model family) | a resource decision (S2 §11.0) |
| for C-H: corpus reading; C-7 for chronological progression; an F2 hypothesis | blocked (L0-DEC-30; L0-DEC-05/07) |

---

## 16. Definition of "first scientific success"

**Three independent dimensions, never collapsed:**

| Status | Meaning | First achievable by |
|---|---|---|
| **METHOD_VALIDATED** | the **composite procedure** (§5) recovers known synthetic structure, rejects null structure and respects the confound, **within the declared scope only**, under a frozen protocol with independent verification. **No new status vocabulary.** The status is inseparable from its scope (revised 2026-09-26): `status: METHOD_VALIDATED` + `validation_scope: {domain: synthetic, generator_family: G_θ, parameter_grid: <declared>, corruption_range: "≤ c₀", independence_class: <SECONDARY_REVIEW or INDEPENDENT>}`. A statement of this status without its scope is malformed | C-M |
| **HYPOTHESIS_TESTED** | a real hypothesis underwent its pre-registered falsification test (outcome of any kind) | C-H |
| **THEORY_CANDIDATE_SURVIVED** | a candidate KnowledgeOS formulation survived its independent tests (S2 L4 / M3+) | C-H, repeated (M4 needs ≥ 2 independent tests) |

**First scientific success** = EXP-C-M reaches a pre-registered outcome with an intact freeze and hold-out, **and** is verified at a disclosed independence class.

- A **REFUTED** outcome also counts as success of the *cycle*: the machinery produced a falsification.
- **It is not** HYPOTHESIS_TESTED or THEORY_CANDIDATE_SURVIVED for KnowledgeOS.
- **An independent verification establishes that the computation was reproduced**, not that the generator specification is scientifically adequate. Specification errors are shared by construction (§10).

---

## 17. Open questions

| # | Question |
|---|---|
| OQ-A | Which thresholds (r₀, q₀, k₀, w₀, c₀) and N? These are the experiment's registration, not the architecture's |
| OQ-B | Is an INDEPENDENT reader available, or will C-M report at `SECONDARY_REVIEW`? |
| OQ-C | Does governance classify the C-M laboratory code as research software (S2 §3A/§3B) or as execution-assurance machinery (H-14a)? |
| OQ-D | Namespace for hypothesis ids (`HYP-` needs the MP §4A / GIA-6 collision check) |
| OQ-E | Should the seed-commitment convention become an S2 rule? (`AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION`) |
| OQ-F | For C-H: which recorded Phase-2 item could first reach F2, and what would its falsifier type be? |

---

## Final answer

> **What is the smallest amount of new machinery we need before we can run one scientifically defensible, independently verifiable, falsifiable research experiment?**

**Minimum (for the method-validation cycle C-M):**
1. **one synthetic generator** with exposed ground truth, and positive, null, confounded and corrupted worlds, over an arbitrary vocabulary;
2. **one lexical-baseline detector** with a permutation-calibrated threshold, and **one scorer**;
3. **one freeze convention** in the existing S2 `Experiment.design`: thresholds, N, metric, grid and the seed rule, committed and hashed **before** test data exists;
4. **one seed commitment**: test seeds = HMAC(freeze_hash, i);
5. **one independent re-computation** by a separated verifier, with its independence class recorded.

**No new record type, level, context, gate or protocol.**

**Dependencies:**
- the existing S2 `Experiment` / §13.0a / §13B / §10.2 / §11.0;
- git and sha256;
- a reviewed design (§5), with its thresholds registered.

**Blockers:**
- **a release scope for C-M** (L0-DEC-30 permits only Critical Attack Pass 01; L0-DEC-27);
- the **availability of an INDEPENDENT reader** (otherwise the result is `SECONDARY_REVIEW`);
- [O] **the ownership classification** of the lab code;
- **for a real KnowledgeOS hypothesis (C-H):** corpus reading (L0-DEC-30), C-7 for chronological progression, and an F2 hypothesis (none exists).

**Deferred:** §13 — ML beyond the baseline, embeddings, clustering, graph embeddings, active learning, the evidence graph, the extra clocks, `FormalModel`, all solvers and provers, the structural hold-out, audits, platforms, new contexts.

---

> **DESIGN MINIMIZED — NO IMPLEMENTATION OR CORPUS EXECUTION AUTHORIZED.**
