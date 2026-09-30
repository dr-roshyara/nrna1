# KNOWLEDGEOS — C-M REGISTRATION & FALSIFICATION REVIEW

| | |
|---|---|
| **Kind** | **PROPOSED registration** of the synthetic method-validation experiment EXP-C-M, plus an adversarial review of that registration. ⚠ authority: generated |
| **Commission** | human, 2026-09-26: *"write the C-M Registration & Falsification Review"* |
| **Design it registers** | `prompts/KNOWLEDGEOS-MINIMUM-SCIENTIFIC-RESEARCH-CYCLE.md` (`139b0bae8`, "the Design"), §5 |
| **State** | **NOT FROZEN · NOT EXECUTED.** No world has been generated, no code written, no corpus read. The registration becomes binding only when frozen (Design §3 step 5). That happens only after a release scope covers C-M (L0-DEC-27; the current release L0-DEC-30 does not). Any value here may be changed **before** the freeze; after it, a change is a new revision `-R1` on new seeds |
| ⚠ **Superseded in part (2026-09-26)** | `KNOWLEDGEOS-C-M-FINAL-ADVERSARIAL-REVIEW.md` §12 supersedes this registration's generator, cells, criteria, multiplicity and N. The positive regime here is below the BH detection boundary (z ≈ 2.47 < 3.50), and the chain/fork correction X-1 is itself wrong under this renderer. R-USE (§1), D0 (§2.3), the scorer (§2.4) and the verifier rules (§5) remain in force |
| **Validates** | the composite **procedure** (Design §5), within the declared synthetic scope. ⛔ Never KnowledgeOS theory |
| **Reuses** | S2 `Experiment` record, the four-stage experimental sequence, §13B, §10.2, §11.0. No new record type, level, context, gate or protocol |

---

## 0. Corrections to the Design found while registering

| # | Design text | Problem | Registration |
|---|---|---|---|
| X-1 | confounded worlds: *"a chain u→v→w vs a common cause v←u→w"* | **wrong:** those two graphs have *different* skeletons (u–v, v–w vs u–v, u–w), so they are distinguishable from pairwise data | the confounded pair is a **Markov-equivalent** pair: chain **u→v→w** vs fork **u←v→w**. Same skeleton u–v–w; direction not identifiable from co-occurrence alone. *Correct the Design text* (flagged, §8) |
| X-2 | detector: *"permutation-calibrated threshold … BH on permutation p-values"* | with m candidate pairs, BH's smallest threshold is q\*/m. A permutation p-value with B permutations cannot be below 1/(B+1). For K = 30 (m = 435) and q\* = 0.10, BH needs p ≤ 0.00023, i.e. B + 1 ≥ m / q\*, so B ≥ 4,349; for K = 50 (m = 1,225), B ≥ 12,249. With a small B, **D could never report any edge**, and the experiment would test the p-value resolution, not the method | use **exact one-sided hypergeometric p-values** for document co-occurrence (§2.3); no permutation resolution limit |
| X-3 | null criterion (c): *"null-world mean reported edges ≤ k₀"* | k₀ would be an arbitrary number | replaced by a criterion **derived from what D claims**: under the complete null, BH at level q\* bounds P(any reported edge) by q\* when the p-values are valid and independent or PRDS. The null criterion tests exactly that claim (§4, C-N) |
| X-4 | corruption grid includes **date corruption** | D uses no dates, so date corruption can have no effect on it; including it adds tests with no information | **removed** from C-M. It belongs to a later chronology-method experiment (deferred) |

---

## 1. Stated requirement (the source of every threshold)

Per the Design's registration constraint, the thresholds come from a **use requirement**, not from D's performance.

> **R-USE.** D is intended as a **Phase-1 candidate generator** whose proposals a human verifies by complete-file reading (MP §8, §27). It is useful only if:
> - **(U1)** it does not miss most true relationships, so that the absence of a candidate stays informative: recall ≥ 0.80;
> - **(U2)** it does not bury the verifier in false proposals: at most 1 false proposal per 4 true, i.e. FDR ≤ 0.20;
> - **(U3)** on material with no structure, it almost never reports structure, at the error rate it itself declares: P(any edge in a null world) ≤ q\* = 0.10;
> - **(U4)** it never asserts a direction the data cannot identify.

These four numbers (0.80, 0.20, 0.10, and the U4 tolerance 0.01) are **requirement choices**. They are proposed here and open to human revision before the freeze. They were set **without any D run**; none has happened.

---

## 2. Registration — population and instrument

### 2.1 Vocabulary

Tokens `c001 … c{K}`, plus alias tokens `a001 …` (for synonymy). No natural-language content except the fixed lineage template *"`<v>` builds on `<u>`"*.

⛔ No KnowledgeOS term, concept or structure (S2 OQ-11).

### 2.2 Generator G_θ (positive worlds)

1. **Latent DAG.** Draw a random order of K concepts. Each forward pair (i < j) is an edge i → j with probability p.
2. **Documents.** n_doc documents. Each document has a **centre** concept v, drawn uniformly. It contains:
   - v;
   - each parent of v independently with probability ρ;
   - Poisson(λ) noise concepts, drawn uniformly from the others.
3. **Lineage sentences.** For each parent u of v included in the document, a sentence *"v builds on u"* is written with probability s.
4. **Ground truth exposed.** The world file carries its DAG, alias table and corruption log as a separate `truth.json`, hashed. **The detector never reads it.**

| Parameter | dev / main value | unseen (synthetic hold-out) value |
|---|---|---|
| K | 30 | **50** |
| p | 0.10 (≈ 44 edges for K = 30) | 0.06 (≈ 74 edges for K = 50; similar mean degree) |
| n_doc | 200 | 300 |
| ρ | 0.7 | 0.7 |
| λ | 2 | 2 |
| s | 0.5 | 0.5 |

### 2.3 Detector D (frozen specification)

- **Presence.** A concept counts as present in a document if its token, or any of its alias tokens, appears. D does **not** know the alias table: an alias is a separate token to D.
- **Adjacency score.** For each unordered token pair {x, y}: n_x = the number of documents containing x, n_y likewise, n_xy = the number containing both. The p-value is the one-sided hypergeometric upper tail, p_xy = P[X ≥ n_xy], X ~ Hypergeom(n_doc, n_x, n_y).
- **Adjacency decision.** Benjamini–Hochberg at q\* = 0.10 over all token pairs in the world.
- **Direction.** For a reported pair, count lineage sentences x→y and y→x. If one direction strictly exceeds the other, assert it; otherwise, or if there are none, direction = **UNWITNESSED**. Lineage sentences never create adjacency.
- **Output.** A set of reported pairs, each with {p, BH-rank, direction ∈ {x→y, y→x, UNWITNESSED}}.

### 2.4 Scorer (frozen)

- Each token is mapped to its concept via `truth.json` alias table (**scorer only**). A true concept edge counts as **recovered** if any token pair representing it was reported. Each reported token pair maps to one concept pair; duplicates collapse.
- **Adjacency metrics** are computed on concept pairs (the undirected skeleton).
- **Direction metrics** are computed on recovered true edges with an asserted direction.

---

## 3. Registration — cells, splits, N, stopping

### 3.1 Corruption levels

The levels are joint: all four corruption types move together.

| Level | synonymy a (share of concepts aliased; each mention uses the alias with prob. ½) | duplication d (share of documents duplicated) | missing lineage m (share of lineage sentences dropped) | contradiction r (share of lineage sentences reversed) |
|---|---|---|---|---|
| L0 | 0 | 0 | 0 | 0 |
| L1 | 0.10 | 0.05 | 0.2 | 0.02 |
| L2 = **c₀** | 0.20 | 0.10 | 0.4 | 0.05 |
| L3 | 0.40 | 0.20 | 0.6 | 0.10 |

**Claims are made for levels ≤ c₀ = L2 only.** L3 is reported descriptively, as a robustness curve, and is outside the falsifier.

### 3.2 Cells

| Cell | World type | Parameters | Used in the falsifier? |
|---|---|---|---|
| P0, P1, P2 | positive | main, L0 / L1 / L2 | yes |
| P3 | positive | main, L3 | no (descriptive) |
| U0, U2 | positive, **unseen setting** | K = 50 row, L0 / L2 | yes (synthetic hold-out) |
| N | **null** | main K / n_doc / λ; concepts placed with the same per-concept document frequencies as a paired positive world, but independently across documents; **no lineage sentences** | yes |
| C | **confounded** | worlds drawn in Markov-equivalent pairs (a DAG and a member of its equivalence class with at least one reversed edge, e.g. chain vs fork); rendered with **s = 0** (no lineage) at L0 | yes |

### 3.3 Splits

| Split | Construction | Rule |
|---|---|---|
| **dev** | seeds `dev-1 … dev-200` per cell | free use for building and debugging G, D and the scorer, and for the generator self-tests (§3.5). ⛔ **Not** for choosing thresholds (§1) |
| **test** | seeds `HMAC-SHA256(key = freeze_hash, msg = "<cell>-<i>")`, i = 1…N | generated **only after** the freeze commit; analysed once |

The unseen K = 50 setting is **never** generated on dev seeds. That is what makes U0/U2 a hold-out of the generator setting, not only of the seeds.

### 3.4 N and stopping

- **N = 400 worlds per test cell**, fixed. That is 8 cells × 400 = 3,200 test worlds.
- No optional stopping, no interim look, one analysis.
- **Why 400:** worst-case precision without using any D performance. A per-world proportion lies in [0, 1], so its SD is ≤ 0.5. With Bonferroni-adjusted one-sided intervals over the 13 criteria (α = 0.05 / 13, z ≈ 2.66), the worst-case half-width is 2.66 × 0.5 / √400 ≈ 0.067. For the null proportion near 0.10, it is ≈ 2.66 × √(0.1 × 0.9 / 400) ≈ 0.040.
- Real SDs are expected to be smaller. The bound is conservative on purpose, because N may not depend on D.

### 3.5 Generator self-tests

Required **before** the freeze, on dev seeds only. **These test G, not D.**

| Test | Pass condition |
|---|---|
| ground-truth round trip | every edge in `truth.json` is realizable by the renderer; alias and corruption logs reproduce the rendered text exactly |
| null marginals | per-concept document frequencies in N match the paired positive worlds exactly |
| Markov-equivalence indistinguishability | the pair-count distributions of the two members of each C pair are not distinguishable (a two-sample test at α = 0.05 over 200 dev pairs). If they are distinguishable, **G is defective for cell C** and must be fixed before the freeze, **not** tuned against D |
| no-lineage in N and C | zero lineage sentences |

---

## 4. Registration — hypothesis, null, criteria, decision rule

### 4.1 Hypothesis template, filled (Design §4.1)

```yaml
hypothesis:
  id:               # namespace [O] (MP §4A / GIA-6); working label EXP-C-M-H1
  statement:        "Procedure D (as frozen) meets R-USE U1–U4 on worlds from G_θ at corruption ≤ L2, including the unseen K=50 setting."
  origin:           "[E]"          # expert-constructed method claim (S2 §5C.2)
  evidence:         [Design §5, this review §1]   # motivation, not support
  assumptions:      [hypergeometric null approximates the placement null; lineage template is parseable; alias tokens are distinct strings]
  formalization:    "empirical; the formal objects G_θ, D and the scorer are fully specified in §2 (the measurement analogue of F2)"
  rivals:           ["R0: D reports structure at chance (the null)", "R1: D recovers adjacency but asserts unidentifiable directions", "R2: D passes only because the generator is easy (addressed descriptively by P3, §6)"]
  prediction:       "criteria C-P1…C-C2 (§4.2) all on the passing side"
  falsifier:        {type: STATISTICAL_FALSIFIER, rule: "§4.3 REFUTED clause"}
  metric:           "§4.2"
  population:       "worlds from G_θ over the §3.2 cells only"
  data_split:       "§3.3"
  stopping_rule:    "N = 400 per cell, one look"
  freeze_hash:      # set at freeze
  validation_method: "SELF + independent re-computation at a declared class (§5)"
  status:           REGISTERED   # → FROZEN → TESTED(SURVIVED|REFUTED|INCONCLUSIVE)
```

### 4.2 Criteria

The 13 criteria form **one family**. Every estimate is a mean or a proportion **over worlds**.

| # | Cells | Estimand | Passing side | Threshold source |
|---|---|---|---|---|
| **C-P1a…c** | P0, P1, P2 | mean per-world adjacency **recall** | ≥ 0.80 | U1 |
| **C-P2a…c** | P0, P1, P2 | mean per-world adjacency **FDR** (0 when nothing is reported) | ≤ 0.20 | U2 |
| **C-U1a, b** | U0, U2 | mean recall | ≥ 0.80 | U1 |
| **C-U2a, b** | U0, U2 | mean FDR | ≤ 0.20 | U2 |
| **C-N** | N | proportion of null worlds with **≥ 1 reported edge** | ≤ 0.10 (= q\*) | U3; the BH claim (X-3) |
| **C-C1** | C | **direction-assertion rate** on recovered edges (share of recovered edges given a direction) | ≤ 0.01 | U4: with s = 0, D's spec implies 0; any assertion means the procedure is unsound |
| **C-C2** | C | mean adjacency recall | ≥ 0.80 | U1: the skeleton **is** identifiable, so it must be recovered |

That is 3 + 3 + 2 + 2 + 1 + 1 + 1 = **13 criteria**, and they form **one Bonferroni family of size 13**: α_adj = 0.05 / 13 ≈ 0.0038 per one-sided interval (z ≈ 2.66).

### 4.3 Decision rule

Fixed now, applied mechanically.

For each criterion, compute the one-sided (1 − α_adj) confidence bound on the **failing side**:
- a mean: a percentile bootstrap over worlds, 10,000 resamples, seed = HMAC(freeze_hash, "boot");
- a proportion: the Clopper–Pearson bound.

| Outcome | Rule |
|---|---|
| **REFUTED** | any criterion whose bound lies **entirely on the failing side**. Examples: recall's *upper* bound < 0.80; FDR's *lower* bound > 0.20; C-N's lower bound > 0.10; C-C1's lower bound > 0.01 |
| **SURVIVED** | every criterion's bound lies **entirely on the passing side**. Examples: recall's *lower* bound ≥ 0.80; FDR's *upper* bound ≤ 0.20; C-N's upper bound ≤ 0.10; C-C1's upper bound ≤ 0.01 |
| **INCONCLUSIVE** | otherwise (some interval straddles its threshold and none is refuted) |

**Holm is not used.** With a three-way rule, simultaneous Bonferroni intervals give the same conservative guarantee in a simpler, verifiable form.

### 4.4 Null model, stated separately

| Question | How C-M answers it |
|---|---|
| Can D recover "structure" from no-structure worlds? | cell N. C-N measures the proportion of worlds with any false edge; also reported descriptively are the reported-edge rate (edges / candidate pairs), the largest spurious component and p-value uniformity (KS vs U(0, 1)) |
| Matched null for positive worlds | descriptive: D applied to each P0 world's frequency-preserving shuffle, scored against that world's truth. It is expected to recover ≈ 0 true edges, which shows recall is not an artefact of density |
| Calibration | descriptive: the empirical FDR per BH rank band vs nominal q\*; KS uniformity in N |

### 4.5 Descriptive, outside the falsifier

- P3 (L3) curves;
- direction accuracy on P0–P2 (with contradictions, where direction is witnessed);
- SHD;
- the matched null;
- calibration curves.

They are reported and **never** used to change the outcome. This protects against a "rescue by secondary metric".

---

## 5. Registration — independence requirement

| Level | What the verifier does | Class |
|---|---|---|
| **V-min (mandatory)** | receives the frozen spec (§2–§4) and the hashed test-world files only. **Independently writes** the presence parsing, the hypergeometric p-values, BH, the direction rule, the scorer and the interval / decision computations. Recomputes all 13 criteria and the outcome | `SECONDARY_REVIEW` if the verifier shares the developer's model family; `INDEPENDENT` otherwise |
| **V-gen (optional, stronger)** | also re-implements G from the spec, regenerates the test worlds from the seeds, and checks byte-level equality of the world files | same classes |
| **Forbidden inputs** | dev code, dev notes, dev results, the developer's reasoning or prompts | — |
| **Disagreement** | any criterion's outcome differs, or any estimate differs beyond 1e-9 (the numbers are deterministic given the seeds) ⇒ **DISCREPANCY**, and the outcome is **not** reported as SURVIVED/REFUTED until resolved **by record** | — |

**Status rule:**
- `METHOD_VALIDATED` requires SURVIVED **and** V-min at class `INDEPENDENT`.
- At `SECONDARY_REVIEW`, the result is recorded as `SURVIVED`, with `independence_class: SECONDARY_REVIEW`. It is **not** METHOD_VALIDATED (S2 §11.0: independence is not redefined for convenience).
- Whether an INDEPENDENT verifier is available is a resource decision (S2 §11.0), and **open**.

---

## 6. Falsification review — attacks on this registration

| # | Attack | Could H1 survive while the procedure is poor? | Mitigation in the registration | Residual |
|---|---|---|---|---|
| A-1 | **Easy generator:** ρ = 0.7 makes co-occurrence strong, so passing is trivial | yes, for this regime | the claim is scoped to G_θ (validation_scope); P3 and the U cells probe harder settings; R2 is a named rival | an easy regime means a weak claim. **Stated, not hidden** |
| A-2 | **Thresholds tuned to D** | yes, if set from dev runs | §1: set from R-USE, before any run | the choice of R-USE numbers is itself a judgment (human-revisable pre-freeze) |
| A-3 | **p-value validity:** the hypergeometric null assumes exchangeable documents, but documents with a centre concept are not exactly exchangeable | C-N could fail for this reason, not because D is poor, and would then be REFUTED | C-N **tests** exactly this, and KS uniformity is reported. A failure is a true finding about the composite procedure (Design §5) | the attribution of a failure to "p-value model" vs "detector" needs interpretation (stage 3) |
| A-4 | **BH under dependence:** pair p-values share tokens and are dependent; BH's guarantee needs independence or PRDS | C-N may exceed q\* | C-N tests it directly. A REFUTED outcome is informative (it says the BH-FDR claim does not hold for this D) | the BY correction is a possible `-R1`, never a post-hoc switch |
| A-5 | **Alias handling:** D treats aliases as separate tokens, so synonymy lowers recall; is that fair? | lower recall in P1/P2 is **the intended measurement** | the scorer credits an edge if any alias pair is reported | none |
| A-6 | **Duplication inflates co-occurrence**, so FDR could rise | yes, at P2 | measured by C-P2c; a failure REFUTES | none |
| A-7 | **Unidentifiable direction:** D asserts no direction with s = 0 **by construction**, so C-C1 is nearly tautological | it passes by spec | kept deliberately: it tests the **implementation** (a bug would assert directions). The substantive confound test is C-C2 plus the generator self-test (§3.5) | C-C1 carries little scientific weight; stated |
| A-8 | **Freeze circumvention:** worlds generated before the freeze | an invalid run | HMAC(freeze_hash) seeds make pre-freeze test worlds impossible to produce | the freeze commit must precede generation in git history (checkable) |
| A-9 | **Second look** after a disappointing result | an invalid run | one analysis; a re-run needs an `-R1` + new seeds (a new freeze hash ⇒ new seeds) | detectable in the commit history |
| A-10 | **Verifier shares the spec error** | both agree on a wrong world | V-gen (optional) + generator self-tests; §5 states the limit | a spec error remains shared by construction (Design §16) |
| A-11 | **External validity:** synthetic co-occurrence ≠ real corpus relationships | a SURVIVED result says nothing about real files | the scope explicitly says synthetic; C-M never yields HYPOTHESIS_TESTED or THEORY_CANDIDATE_SURVIVED | intrinsic; that is why C-H exists |
| A-12 | **Metric definition when nothing is reported:** FDR = 0/0 | undefined | registered as 0 (no false discoveries), with the count of empty-report worlds disclosed | none |

---

## 7. What the registration still needs before the freeze

| # | Item | Owner |
|---|---|---|
| F-1 | human review of R-USE numbers (§1) and the parameters (§2–§3), which may change pre-freeze | human |
| F-2 | a release scope covering C-M (L0-DEC-27; currently not granted by L0-DEC-30) | L0 |
| F-3 | implementation of G, D and the scorer, and the generator self-tests passing on dev (§3.5) | the implementer; lab-code classification is [O] (Design §12) |
| F-4 | a decision on the verifier class available (INDEPENDENT vs SECONDARY_REVIEW) | resource decision (S2 §11.0) |
| F-5 | a hypothesis-id namespace | MP §4A / GIA-6 collision check [O] |
| F-6 | the freeze commit: this registration's final text + code hashes → `freeze_hash` | the implementer, after F-1…F-5 |

---

## 8. Changes this review implies for the Design

| Design location | Change | Status |
|---|---|---|
| §5 "Confounded worlds" | chain u→v→w vs **fork u←v→w** (Markov-equivalent), not v←u→w | **factual error — corrected in the Design in the same commit as this review** |
| §5 detector | permutation p-values → **hypergeometric** p-values (X-2) | registration-level; recorded here |
| §5 criterion (c) | mean reported edges ≤ k₀ → **proportion of null worlds with ≥ 1 edge ≤ q\*** (X-3) | registration-level; recorded here |
| §5 corruption | date corruption **removed** from C-M (X-4) | registration-level; recorded here |

---

## 9. Outcome statement templates

Fixed now, so that the result cannot be phrased after the fact.

- **SURVIVED + INDEPENDENT:** "EXP-C-M-H1 SURVIVED. `status: METHOD_VALIDATED`; `validation_scope: {domain: synthetic, generator_family: G_θ (§2.2), cells: P0–P2, U0, U2, N, C, corruption_range: ≤ L2, independence_class: INDEPENDENT}`. The specified procedure discriminated the declared synthetic regimes under the declared generator assumptions. **This says nothing about KnowledgeOS theory or about real corpus files.**"
- **SURVIVED + SECONDARY_REVIEW:** as above, but "SURVIVED (independence_class: SECONDARY_REVIEW); **not** METHOD_VALIDATED."
- **REFUTED:** "EXP-C-M-H1 REFUTED on criterion <id> (bound <value> vs threshold <t>). The cycle executed correctly; the procedure failed R-USE <U#> in cell <cell>." Stage 3 states the candidate causes (e.g. A-3 / A-4); **no rescue by a secondary metric.**
- **INCONCLUSIVE:** "EXP-C-M-H1 INCONCLUSIVE at N = 400: criteria <ids> straddle their thresholds." A larger N requires `-R1` on new seeds.
- **INSTRUMENT fault:** "run void (reason). No outcome."

---

*No world generated, no code written, no corpus read, nothing frozen, no decision taken.*
