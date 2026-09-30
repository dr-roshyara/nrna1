# EP-01: statistical specification of the pre-registered S5 probability audit (PROPOSAL, for human approval)

| | |
|---|---|
| **Kind** | EP-01 planning artifact. ⚠ authority: generated. **It authorizes nothing, draws nothing and freezes nothing.** |
| **Specifies** | addendum items 17–18 (decision H), which left the design open. The audit's §6 lists the gaps; its finding R5-16 names the code defects |
| **Frozen when** | the parameters and seed are frozen in a separate human act, at activation, **before any S5 output exists** (`20260926_S5-ACTIVATION-GATE.md`, step 8) |
| **Never** | a substitute for the S5 floor (R19: all 396 batches, 1,975 labels). An input to the machine verifier. Fed by any ML queue |

Numbers marked **[A]** come from the revision-5 record's metadata dry run. Numbers marked **[PENDING]** must be derived from metadata at activation (no corpus read) and entered into the frozen parameter file.

---

## 1. Population

**U = the 1,975 production labels of the S5 floor** (manifest: 396 batches), each with exactly one final S5 object. Every label of U is in the frame, including the 8 EMPTY labels, which item 17 left out (R5-16).

- **Unit:** the label. Its S5 object is the unit's outcome.
- **The frame is fixed before S5:** the label list from the activated manifest, with its hash recorded. It does not depend on S5 outcomes, so the design is not conditioned on results.
- **Labels that never obtain an accepted object** by the P4 hand-off (a FAILED label with no authorized re-run, or one still FAILED after it) stay in the frame and are classified **NOT-ASSESSABLE** (§7). They are never silently dropped.

## 2. Strata

**Rule:** strata are **disjoint and exhaustive**, assigned by a **precedence list**, so each label falls in exactly one stratum. A stratum exists only if it serves a stated statistical purpose. The purposes are:
- **(i)** risk heterogeneity that would make a pooled SRS inefficient;
- **(ii)** a domain whose estimate must be reported on its own;
- **(iii)** a census required for design reasons.

| Precedence | Stratum | N | Treatment | Purpose |
|---|---|---|---|---|
| 1 | **EMPTY** | 8 [A] | census | (iii) closes the frame gap. Outcomes are largely determined mechanically, and a census costs 8 audits of trivial objects |
| 2 | **MULTI-ROW-UNIT** (DECOMPOSED labels whose row sources span > 1 unit) | 5 [A] | census | (i) and (iii): the highest-risk mechanism (row-context loss; OB0018 mechanisms 5–6). The addendum already fixes a census |
| 3 | **HUB** (not already in 1–2) | ≤ 66 [PENDING exact, after precedence] | SRSWOR | (i) and (ii): hubs are processed with scoped absence claims and have no stage 2, so their outcome distribution differs, and they need their own reported domain |
| 4 | **DECOMPOSED-OTHER** | ≈ 170 [PENDING: 178 − 5 − hub overlap] | SRSWOR, high rate | (i): records-only synthesis is a different mechanism from single-context reading |
| 5 | **SINGLE-OTHER** | ≈ 1,720 [PENDING: 1,789 − hub overlap] | SRSWOR | the bulk. Its purpose is precision of the overall estimate |

**Evaluated and rejected as strata** (each is kept as a **domain variable**, recorded per sampled label and reported descriptively, never used to reweight):

| Candidate | Decision | Reason |
|---|---|---|
| Multi-unit (DECOMPOSED) | already strata 2 + 4 | — |
| **Tier-X** (H-02, PROVISIONAL-HELD) | not a stratum | Tier X concerns how the frozen pair verdict enters the roll-up, not how S5 reads R(L). There is no evidence of a different S5 discordance mechanism, so it fails purpose (i) |
| **High-boundary** (R(L) near 600 KB) | not a stratum | a plausible load effect, but unmeasured. The FL-2 robustness work (boundaries B, D, S, C) is future work under the decision-relevance test. It may be added only by a pre-registered amendment **before the seed freeze** |
| **Binary-containing** (41 labels touching the 12 files) | **conditional** | if the 12 decision records include any **EXTRACT**, the labels whose files are extracted become a **census stratum at precedence 3**, because extraction is a new, unvalidated mechanism (purpose i/iii). Otherwise they are a domain variable only: NOT-CONSUMED-ESCALATED and FALSE-HIT are mechanically checked states |

## 3. Estimand

Three quantities. **They are never combined into one number.**

| # | Quantity | Definition | How it is obtained |
|---|---|---|---|
| **E-1 (primary)** | **label-level adjudicated discordance share** θ = D / N | D = the number of labels in U whose S5 object is **DISCORDANT** with the blind audit re-analysis. **DISCORDANT** means at least one difference in a **decision field** (below) that the adjudicator classifies as substantive, not notational. **Who is wrong is not part of E-1** | estimated (§5) |
| E-2 (secondary) | field-family discordance | for each decision-field family f: the number of labels discordant in f, divided by N | estimated per family. Descriptive; no multiplicity claim |
| E-3 (census, not estimated) | **protocol failure rate** | labels or batches that FAILED verification, and re-runs, over the floor | **observed exactly** from the S5 floor itself; no sampling |

**Decision fields (PROPOSED; the list is frozen with the parameters):**
- `births` (5 kinds: value and cited sources);
- `absences` (resolution per dimension, and `supplied_by`);
- the set of timeline change points (source_id, change value);
- stage-2 disposition **classes** per hit key;
- `dependency_edges` of class R2-EVIDENCED;
- escalation reasons.

Wording, ordering, anchors and quotes are notational unless they change one of these fields.

**Interpretation limit (carried from addendum item 17 and the audit §6):**
- **Same family:** the audit instrument is a same-family re-analysis, so E-1 bounds **disagreement between two readings**. It is **not** an error rate and not a fidelity rate.
- **Human auditors:** where the audit instrument for a stratum is a human (Q-S1), that stratum's E-1 is closer to an error estimate and is reported as such, separately.

## 4. Sampling design

| Element | Specification |
|---|---|
| **Frame** | the activated manifest's label list, with its sha256; stratum assignment by the precedence list; the assignment file is hashed |
| **Selection** | stratified simple random sampling **without replacement**; census strata take π = 1 |
| **Inclusion probability** | π_h = n_h / N_h, the same for every label in stratum h. Each label belongs to exactly one stratum, so π is well-defined (this removes the R5-16 overlap defect) |
| **Sample sizes (PROPOSED; the human fixes them)** | EMPTY 8/8 · MULTI-ROW-UNIT 5/5 · HUB **33** of ≤ 66 · DECOMPOSED-OTHER **85** of ≈ 170 · SINGLE-OTHER **100** of ≈ 1,720 · **total ≈ 231 audits** |
| **Why these sizes** | set by the zero-discordance case (§6), per stratum at α = 0.05: HUB n=33 bounds D ≤ 4 of 66 · DEC-OTHER n=85 bounds D ≤ 4 of 170 · SINGLE-OTHER n=100 bounds D ≤ 48 of 1,700 (2.8%). Halving the SINGLE sample (n = 60) gives 4.8%. **The cost/precision trade-off is the human's call** (table §6.2) |
| **n = 0 and rate 0** | a rate of 0 draws **n = 0**, not 1 (R5-16). A stratum with n_h = 0 is recorded as **UNSAMPLED**, and its contribution to the bound is its full N_h (§6) |
| **Seed** | a 128-bit integer generated by the human at the freeze act, from the operating system's CSPRNG, **after** the plan and frame hashes exist. It is recorded together with the Python version and `random` module source hash. **The drawn label list is the authority** (hashed and committed), so re-drawing is only a check |
| **Algorithm** | per stratum, in sorted stratum-name order: `random.Random(seed).sample(sorted(labels), n_h)`. One RNG stream, strata in a fixed order (as `draw_audit_sample`, with the fixes listed in §9) |
| **Replacement** | none. A sampled label is never swapped out, whatever its S5 outcome |
| **Timing** | drawn and frozen **before any S5 output exists**; audited **after** the label's object is accepted; complete **before the P4 hand-off** |
| **Blindness** | the auditor never sees the S5 object, its records or the S5 read log for the label. The adjudicator sees both objects, but not which one is S5 (sides labelled A/B at random, by a separate seed) |

## 5. Estimation

| Method | Used? | Where and why |
|---|---|---|
| **Horvitz–Thompson** (the stratified expansion estimator) | **yes, point estimate** | D̂ = Σ_h N_h · d_h / n_h (census strata contribute d_h exactly); θ̂ = D̂ / N. Unbiased under the design (the audit verified this by enumeration). Because π is equal within a stratum, HT and the stratified expansion estimator coincide |
| **Design-based variance** | **yes, reported** | V̂(D̂) = Σ_{h sampled} N_h² (1 − n_h/N_h) s_h² / n_h, with s_h² = n_h p_h (1 − p_h)/(n_h − 1) and p_h = d_h/n_h. Census strata contribute 0. It is shown with the point estimate; **no normal interval is claimed** unless every sampled stratum has d_h ≥ 5 and n_h − d_h ≥ 5 |
| **Exact hypergeometric bound, per stratum** | **yes, primary inference** | U_h(α_h) = the largest D_h with P(X ≤ d_h \| N_h, D_h, n_h) ≥ α_h. This generalizes the existing `zero_bound(n, N)` from d = 0 to any d. Finite-population exact; no approximation |
| **Overall upper bound** | **yes, primary** | U = Σ_{census} d_h + Σ_{sampled} U_h(α / H′), where H′ = the number of sampled strata (Bonferroni split). This gives P(D ≤ U) ≥ 1 − α by the union bound: conservative, exact per stratum and assumption-free. Report U/N as "the upper 95% confidence bound on θ" |
| **Wilson interval** | **no** (inference) | two-sided and binomial, which mismatches a one-sided finite-population bound (the audit §6). At most a per-stratum descriptive column, if the human wants one |
| **Exact binomial (Clopper–Pearson)** | **no** | the hypergeometric bound is exact for sampling without replacement from a finite stratum, and never looser |
| **Cluster bootstrap** | **only for E-2** | field families are clustered within labels, so E-2 uses the label as the resampling cluster, within strata. E-1 is label-level and needs no bootstrap |

## 6. The zero-disagreement case

### 6.1 Meaning

If d_h = 0, then U_h is the largest number of discordant labels in stratum h for which drawing 0 in n_h would still have probability ≥ α_h. It is a statement about **disagreement between the S5 reading and this audit instrument**, bounded above with confidence 1 − α. It is **not** proof of zero disagreement, and **not** evidence of zero error: two same-family readings can share a failure.

**Required wording in any report:** *"0 of n_h sampled labels were adjudicated discordant. With 95% confidence, at most U_h of the N_h labels in this stratum would be adjudicated discordant by this audit instrument."*

**Census strata** with d = 0 give D_h = 0 **for this instrument**, under the same interpretation limit.

### 6.2 Bounds at the proposed sizes (hypergeometric, d = 0; computed for this proposal)

| Stratum (N) | n | U_h at α = 0.05 | U_h at α = 0.05/3 (the split used in U) |
|---|---|---|---|
| HUB (66) | 20 / **33** | 7 / **4** | 10 / **5** |
| DEC-OTHER (170) | 50 / **85** | 8 / **4** | 11 / **5** |
| SINGLE-OTHER (1,700) | 60 / **100** / 150 | 81 / **48** / 32 | 110 / **66** / 43 |

**The proposed design with zero observed discordance gives U = 0 (census strata) + 5 + 5 + 66 = 76 labels, so θ ≤ 3.8% (95%).**

## 7. Failed and untestable labels

Each sampled label ends in exactly one class:

| Class | Meaning | In E-1 |
|---|---|---|
| CONCORDANT / DISCORDANT | adjudicated | yes |
| **AUDIT-FAILED** | the audit re-analysis did not complete, or broke its own rules | not in d_h. Reported with a **two-sided sensitivity**: all counted concordant, and all counted discordant. The primary bound uses the **worst case** (counted discordant) |
| **NOT-ASSESSABLE** | the S5 label has no accepted object by the P4 hand-off | as for AUDIT-FAILED (worst case in the primary bound). The count also appears in E-3 |

A replacement draw is **never** made for either class.

## 8. ML separation

- **Sealed sample.** The sample is drawn and hashed before any S5 output exists, and before any ML component exists for S5.
- **Code-level guard (exists):** `ht_estimate` refuses discordant labels outside the sample.
- **Code-level guard (to add, §9):** the estimator accepts adjudication records only if they carry the **sample-file hash**, so a label entering through an ML queue cannot be counted even if it also happens to be sampled.
- **Procedural rule (new):**
  - adjudicators of sampled labels must not see any ML priority, anomaly or uncertainty flag for those labels;
  - a sampled label that an ML queue also flags is adjudicated under the audit protocol only.

  This prevents unequal adjudication intensity, which would bias E-1 (the audit §7).
- **Boundary.** ML output never changes the frame, the strata, the sizes, the seed, the classification of an outcome or the estimator.

## 9. Code consequences (they belong to the repair slice; nothing is implemented here)

In `p3b_s5_r5.py`:
- **`draw_audit_sample`:**
  - reject overlapping or incomplete strata (the frame must equal the union of the strata, exactly);
  - rate 0 → n = 0;
  - return the frame hash.
- **`zero_bound` → a generalized `exact_upper_bound(d, n, N, alpha)`:** guard n = 0 (bound = N); cover d > 0.
- **`ht_estimate`:**
  - add the stratified variance;
  - add the overall union bound U;
  - classes AUDIT-FAILED and NOT-ASSESSABLE with the worst-case rule;
  - sample-hash binding.
- **`wilson`:** kept, used descriptively only.

These close R5-16. They carry a MUST disposition before the seed freeze (repair plan §6).

## 10. Open questions for the human

| # | Question | Why it matters | Recommendation |
|---|---|---|---|
| **Q-S1** | **The audit instrument for DECOMPOSED labels.** Addendum item 17 names an "independent single-context re-analysis". But a DECOMPOSED label has R(L) > 600 KB, **more than one context by definition**, so a single-context re-analysis of strata 2 and 4 is infeasible as specified | a specification gap: 90 of the ≈ 231 proposed audits have no feasible instrument | a **human auditor** for MULTI-ROW-UNIT (5) and a human subsample of DEC-OTHER; or a re-analysis under an **independent partition** (e.g. reversed packing), declared as same-mechanism and reported separately. **Human decision** |
| Q-S2 | The sample sizes (§4, §6.2) | cost ≈ one re-analysis per audited label | the proposed 231 (0-discordance bound 3.8%); or SINGLE n = 60 (≈ 191 audits) |
| Q-S3 | α = 0.05 and the Bonferroni split across the sampled strata | the level of the primary statement | yes |
| Q-S4 | The decision-field list (§3) | defines DISCORDANT | as proposed, frozen with the parameters |
| Q-S5 | The human-review subsample (addendum item 17 names it but does not size it) | converts part of E-1 from disagreement to error | at least the MULTI-ROW-UNIT census, plus 10 SINGLE-OTHER labels |

**Traceability:** addendum items 17–18 · audit §6 and R5-16 · `audit-p3b/r5-audit/stats.py` / `stats.out` (the HT enumeration, the zero bounds and the overlap defect) · repair plan `20260926_R5-MATERIAL-FINDINGS-REPAIR-PLAN.md` §6.
