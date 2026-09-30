# B1 v2: experiment-design FREEZE CANDIDATE for S5 under R7 (for human approval)

| | |
|---|---|
| **Kind** | Freeze candidate. ⚠ authority: generated. **It freezes, draws and activates nothing.** Approval of this text, the seed draw and the pre-registration hash form one later human act, before any S5 output exists |
| **Supersedes** | B1 v1 (`536caaaf0`), which G-LOG-0093 approved conceptually with conditions (1)–(7). Each condition is answered in the section named in §0 |
| **Base** | EP-01 (`audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`): population, strata, sampling, estimation, failed units, ML separation. Consumed, not repeated |
| **Contract** | R7 v2.5 (`63fdda65…`): v2.4 + F-01 + RC-03 (accepted, G-LOG-0092) + v2.5-S (exact bound primary, G-LOG-0093) |
| **Researcher's rule** | the corpus is brainstorming material. S5 must make a **robust** theory possible, or show that there is none. Four questions are kept apart: **observation** (R7) · **reproducibility** (θ_D, R1, R2) · **correctness** (θ_A, θ_E) · **theory** (Track B, then H-19) |

## 0. Conditions of G-LOG-0093 → where answered

| # | Condition | Answer |
|---|---|---|
| 1 | θ_D / θ_A / θ_E terminology | §1 |
| 2 | Model 0, operational and per hypothesis | §3 and `research/prereg_v1.py` (null generators property-tested) |
| 3 | R1 (record) vs R2 (structural) reproducibility | §2 |
| 4 | Model 0 + reconstruction-artifact models + competing models | §3, §4 |
| 5 | Confirmatory FWER vs exploratory FDR | §5 |
| 6 | H-19 strictly last | §6 |
| 7 | A small theory-candidate registry | `research/RESEARCH-LEDGER.md` (fields and status path) |

## 1. Estimands (never combined into one number)

| Symbol | Name | Definition | Instrument | Reported as |
|---|---|---|---|---|
| **θ_D** | reconstruction **disagreement** | share of labels whose S5 object differs substantively, in a decision field (EP-01 §3), from an **independent same-protocol re-analysis** | the ≈ 231 blind re-analyses (EP-01 §4) | HT point estimate + **exact v2.5-S upper bound** (primary) |
| **θ_A** | disagreement with **independent adjudication** | share of labels where the S5 decision differs from a blind **human adjudication** | the human subsample: MULTIROW census + ≥ 10 SINGLE (EP-01 Q-S5) | same estimator, separately |
| **θ_E** | reconstruction **error relative to a declared reference standard R\*** | disagreement with R\*, where R\* has an explicit, frozen **justification** for being closer to the source than S5 | declared **only if** the justification below is accepted by a human act; otherwise **θ_E = NOT-ESTABLISHED** and only θ_A is reported | — |

**The hierarchy is kept: reproducibility (θ_D) ≠ agreement (θ_A) ≠ truth.** No criterion below proves that an adjudicator is correct.

**Reference-quality criteria (ENGINEERING criteria, not a truth criterion)**, all required before θ_A may even be *proposed* as θ_E:
- (a) The adjudicator is blind to which reading is S5; sides are labelled A/B by a separate seed.
- (b) The adjudication rules are frozen with this document.
- (c) A double-adjudicated subsample of ≥ 20 labels, by two independent humans, reaches agreement ≥ 0.90 on the decision fields, and every disagreement is resolved by a frozen rule. **Limitation:** inter-rater agreement measures the *reliability* of the adjudication instrument, not its *validity*. Two raters can share a misreading.
- (d) The adjudicator may read the source files but not the S5 read log.

**The justification that R\* is closer to the source** (the *validity* argument; it must be written and accepted before θ_E is used):
- It is *why* R\* reads differently from S5: full, unbounded human reading of the decision-relevant sources, versus S5's bounded, context-limited agent reading.
- It lists R\*'s known failure modes: fatigue, the shared framing of the decision-field list, the same A.10 metadata.

Even then, θ_E means **error relative to R\***, never "truth". If the justification is not accepted, the word "error" is not used.

## 2. Reproducibility at two levels

- **R1, record reproducibility:** θ_D per decision field (EP-01 E-2, label-cluster bootstrap), on the replicated labels.
- **R2, structural invariance:** for each pre-registered hypothesis H, compute its statistic T_H on reconstruction A (S5) and on reconstruction B (the re-analyses), restricted to the replicated labels.
  - **R2-INVARIANT** iff the test decision agrees **and** the 95% label-cluster bootstrap CI of the paired effect difference contains 0.
  - Otherwise **R2-FRAGILE**, and a fragile candidate cannot rise above EXPLORATORY.

  This is the direct test of "structure in the corpus" versus "structure made by one reading".
- The two levels are reported separately. A theory needs both.

## 3. Pre-registered hypotheses and their Model 0 (operational; code hash frozen with this document)

| H | Claim | Statistic T | **Model 0** (preserves exactly the method + frequency information) | Reconstruction-artifact models | Competing model |
|---|---|---|---|---|---|
| H1 | change classes cluster in runs (development is sequentially structured) | # adjacent equal classes | within-object permutation of positions ≥ 2 (FIRST and each object's class multiset kept) | stratify by batch and by agent run; control for timeline length | a first-order Markov chain vs exchangeability (likelihood ratio) |
| H2 | contradiction runs forward in A.10 time | # forward dated relations | random orientation per dated relation | **partly method-constrained** (the contract orders `contradicted_by` by timeline predecessor): tested only on relations whose A.10 dates differ | chronology-independent contradiction |
| H3 | the label dependency graph is ordered (acyclic beyond chance) | # 2-cycles + 3-cycles | directed degree-preserving edge swaps | batch co-membership; hub selection | a random graph with the same degrees |
| H4 | birth kinds are ordered in time | Kendall S (ties excluded; reported) | within-object exchange of dated positions | file-date granularity (ties) | unordered kinds |
| H5 | FOUND absence dimensions have closure/implication structure | # exact implications, support ≥ 3 | curveball: uniform over matrices with the observed row and column sums | label size (R(L) bins), hub, path type | a **floor-dimension model** (one near-universal dimension + independent others), compared by BIC |

- **The pilot is development evidence only.** H1–H5 were formulated during pilot exploration (pass 0). They are now **fixed**: the pass-0b pilot results (H1 p = 0.62, H5 p = 0.84, H2/H3 untestable, H4 ties only) are **not** evidence for or against any S5 hypothesis, and they changed no hypothesis, statistic or threshold. Their only role is to show that the instrument works (Model 0 removed a false H4 signal).
- **Vacuity guard:** a hypothesis with too little data (H3: < 10 internal edges; H2: < 5 dated relations) is **UNTESTABLE**, never "supported".
- **Instrument status:** the null generators are property-tested; the pilot smoke test (exploratory only) shows that nothing survives Model 0 on the 20 pilot objects (`research/RESEARCH-LEDGER.md`, pass 0b).
- **Permutation parameters:** B = 9,999 permutations per test; a seed drawn at the freeze from the OS CSPRNG, recorded with the instrument's sha256.

## 4. Three-way comparison (every candidate)

A candidate advances only if it beats **all three**:
1. **Model 0** (§3 column) at the confirmatory level (§5);
2. **every listed reconstruction-artifact model**: the effect persists within artifact strata (stratified permutation) and is not explained by the covariate;
3. **the competing model**, by a pre-declared criterion (likelihood ratio or BIC, stated per H in §3).

## 5. Multiple testing

| Family | Procedure | Level |
|---|---|---|
| **Confirmatory** H1–H5 (one primary statistic each) | **Holm–Bonferroni** over the permutation p-values: FWER ≤ α under any dependence | α = 0.05 |
| **Exploratory** (individual implication pairs, transition cells, covariate splits, anything not in §3) | **Benjamini–Hochberg** | q = 0.05; reported as exploratory; never cited as confirmation |
| **Statistical audit** (θ_D, θ_A) | the v2.5-S exact bound: per stratum at α/H′, Bonferroni-combined | α = 0.05 |

## 6. H-19 is strictly last

The sealed hold-out is the **corroboration set**. It stays sealed through S5, the audit, R2, Model 0, formalization and the counterexample attack. It is opened **only** after a candidate theory is **frozen** (statement, formal object, predictions, falsifiers, thresholds, all hashed), under its own future human act. No H-19 information may influence candidate generation, parameter or threshold selection, or model choice. S5c stays PROHIBITED until then.

## 7. Carried from B1 v1 (unchanged)

- **D1:** keep R7's stratum precedence (HUB > EMPTY > MULTIROW > DECOMPOSED > SINGLE); verify the hub/EMPTY/MULTIROW overlap count from metadata at the freeze and report it as a domain.
- **EP-01 Q-S1:** the DECOMPOSED audit instrument is an independent-partition re-analysis (declared same-mechanism) plus the human subsample.
- **Q-S2:** the sample sizes, ≈ 231.
- **Q-S4:** the decision-field list, frozen here.
- **Failed units:** AUDIT-FAILED and NOT-ASSESSABLE use the worst case in the primary bound (EP-01 §7).
- **Canary:** 2 batches first. Stop if ≥ 50% of the runs FAIL on W8 or on a systematic pattern. The repair goes to the runbook or dispatch prompt, not the verifier.
- **ML:** none before adjudicated S5 data. Afterwards, candidate generation only, against lexical/TF-IDF, graph and frequency baselines. It never decides a status, a verdict or a weight.

## 8. Two freezes, in order (never merged)

**Freeze 1: SCIENTIFIC DESIGN** (next human act; needs no corpus read). It fixes:
- this text (sha256);
- the instrument `research/prereg_v1.py` (sha256);
- the estimands θ_D, θ_A and the θ_E conditions; R1 and R2;
- H1–H5 with Model 0, the artifact models and the competing models;
- the multiple-testing families, α and q; failed / NOT-ASSESSABLE handling; the decision fields.

**Nothing learned later, including from the binary read, may change any of these.**

**Binary read** (bounded corpus-touching step, only after Freeze 1):
- the 12 binary files, read through the seal-aware resolver, for `binary_preclassify` only;
- no content is printed, and nothing is explored;
- its only output is a per-file frame classification (FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT).

**Freeze 2: FINAL FRAME, SAMPLE AND SEED** (after the 12 human binary decisions):
- the frame (the conditional census stratum if any EXTRACT) and its hash;
- the 128-bit seed from the OS CSPRNG, drawn at the act;
- the sample file (`S.freeze`, α = 0.05) and its hash.

**Then:** R7 activation → canary → S5.

**Traceability:** G-LOG-0093 conditions (1)–(7) · EP-01 · activation gate steps 5–11 · addendum v2.5 §8 (DR-19) · `research/prereg_v1.py` · `research/RESEARCH-LEDGER.md`.
