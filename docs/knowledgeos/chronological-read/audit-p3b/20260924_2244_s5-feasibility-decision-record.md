# S5 Feasibility Decision Record (stop-the-line, §19.4 / §23)

| Field | Value |
|---|---|
| Trigger | the measured S5 load under core v1.6.6 is not feasible at S4-audited quality; stop-the-line invoked by the human 2026-09-24 (G-LOG-0031) |
| Constraint | the frozen protocol is the constraint. Nothing is thinned, parallelized or altered ad hoc. The H-19 hold-out and S5c are **untouched** |
| Status | **decision input only.** No option is adopted. No sample size is fixed. The human chooses the S5 architecture before S5 starts; any amendment then follows §26 (draft → freeze check → independent confirm → human freeze) |
| Gated behind this record | H-12, OMQ-16, H-11a–d, OMQ-07, H-16 (before S5) · H-15, H-17, H-18, OMQ-17, OMQ-18 (before S5a/S5b) |

## 1. Measurement basis

- **Population:** the S3b discovery population. 1,975 Tier-U/Z labels: 898 U and 1,077 Z (`P3B-SAMPLE-PLAN.jsonl`). The hold-out is already excluded (HS-3d32dd44d162).
- **Cost model:** computed per label by `scripts/p3b_s5_feasibility_cost.py` (seed 1, 2,000 draws; deterministic), from committed artifacts only, with no corpus content read.
  - Stage-1 hits come from `P3B-DISCOVERY-SEARCH.jsonl`.
  - File sizes come from the git blob sizes of `P3B-IDENTITY-MANIFEST.jsonl`.
  - **Dispositions** = distinct hit keys × the label's absence dimensions. This is the R2.2 unit, one per distinct hit per dimension.
  - **Stage-2 bytes** = the summed size of the label's unique hit files, each read whole once.
- **Calibration against S4 R2.2** (20 labels):
  - dispositions: the model predicts **6,081**; the actual count is **6,081**;
  - stage-2 bytes: the model predicts 12.6 MB; the actual step-7 reads were 14.6 MB (re-reads for paging).
- **Not in the model:**
  - Step-1 reading (row files, timelines): the pilot averaged about 0.12 MB per label, extrapolated below and marked (est.).
  - Research-checklist work, which applies only to the 567 checklist labels (§9C).
  - Stage 2B short-term occurrence review, which adds work beyond the per-hit count for labels with 1–2-character notations.
- **Audit unit:** R2.2 used 4 batches of 5 labels (about 1,500 dispositions per batch) and one independent audit. The audit burden scales with batch count. Batch size is an H-12 choice.

### Load concentration (the central fact)

| Labels, ranked by dispositions | Share of dispositions | Share of stage-2 bytes |
|---|---|---|
| top 10 | 56.1% | 24.2% |
| top 50 | 89.0% | 64.5% |
| top 100 | 94.2% | 78.4% |
| top 200 | 97.0% | 86.5% |

- **Distribution:** dispositions per label are 20 at the median, 548 at the 90th percentile and 40,093 at the 99th percentile. The maximum is 675,488 (`three-candidate-kernel-architectures`). 592 labels have no stage-1 hits.
- **What the heaviest labels are:** labels whose search terms are generic ("evidence", "KnowledgeOS", candidate/axis vocabularies).
- **Why they are the least informative:** S4 measured about 80% false hits, and that rate is highest for such terms.

## 2. Options

Numbers for random designs are medians, with the 90th percentile in brackets, over 2,000 stratified proportional draws. The spread is wide because a draw may include hub labels.

| | O1 Full census | O2 Stratified random sample, full procedure | O3 Screening + stratified validated sample | O4 Existing S3b sample plan (567) | O5 Load-threshold census + declared hub stratum |
|---|---|---|---|---|---|
| **Labels, full procedure** | 1,975 | n = 100 / 200 / 400 | n (as O2), allocation from screening | 567 (367 purposive + 200 random, seed 20260924) | 1,875 below the threshold (example: top 100 excluded, threshold > 1,830 dispositions) |
| **Stage-2 bytes** | 1.45 GB | 0.07 [0.12] / 0.14 [0.21] / 0.29 [0.38] GB | as O2 for the same n | 0.51 GB (random part 0.09) | 0.31 GB |
| **Step-1 bytes (est.)** | 0.24 GB | 0.01 / 0.02 / 0.05 GB | as O2 | 0.07 GB | 0.23 GB |
| **Stage-2 file reads** | 60,860 | 3.1k [5.5k] / 6.0k [9.3k] / 12.2k [16.6k] | as O2 | 21,480 | 9,810 |
| **Dispositions** | 3.45 M | 133k [366k] / 282k [774k] / 622k [1.25M] | as O2, lower if the hub stratum is sampled separately | 0.98 M (purposive part 0.76 M) | 0.20 M |
| **Batches at 5 labels (audits at 1 per 4)** | ~395 (~99), with the hub batches individually enormous | 20 / 40 / 80 (5 / 10 / 20) | as O2 | ~113 (~28) | ~375 (~94), but mostly light: batching by load (H-12) could cut this several-fold |
| **Can claim** | the P3b terminal predicate (§23); per-label results for every label; full recurrence density | population estimates (status, birth, absence proportions) with known inclusion probabilities; recurrence **among sampled labels** | as O2, plus more power for recurrence by oversampling labels that share screening signals (weighted estimates stay unbiased with known inclusion probabilities) | descriptive results for 567 labels; population estimates only from the 200 random labels | per-label results for about 95% of the population; recurrence density near-complete (about 90% of label pairs observable) |
| **Cannot claim** | — | per-label results for unsampled labels. Only (n/1,975)² of label pairs are observable (n = 200: about 1%), so structures rare in the population will mostly be missed | screening results are **not** S5 evidence. Screened strata built from S5a-type signals must be flagged DESCRIPTIVE (§9E), because of circularity | population inference from the purposive part. That part was chosen for research value and carries 77% of the plan's load | census absence results for the hub stratum. What it gets depends on the hub treatment (below) |
| **Protocol amendment** | none | yes: §23 terminal predicate items 1 and 3, the §19.1 S5 scope, §19.4 | as O2, plus a screening specification | as O2. The plan exists and is frozen, but it defines the §9C checklist scope, not the S5 batch scope | Hub treatment **(a)**: each hub label BLOCKED with an open, recorded load escalation. §23 item 1 already admits "BLOCKED with an open escalation", so this *may* need only a run-level ruling. Whether BLOCKED may carry a load reason is a governance judgment, not asserted here. Hub treatment **(b)**: seeded hit sampling in stage 2 with an explicit new status (e.g. CENSUS-SAMPLED), which changes §11.4 |
| **H-19 / S5c** | untouched | untouched; the frame already excludes the hold-out | untouched, if the screening scripts read no sealed artifact | untouched | untouched |
| **Reproducibility** | deterministic | seeded; frozen frame; Python version recorded (§19.5) | seeded, with the screening scripts frozen and hashed before the draw | already seeded and committed | the threshold is computed from S3 stage-1 data only, fixed before any S5 result (no outcome leakage); deterministic |

Common to O2–O5:
- **S5c needs the hold-out labels.** Under the addendum, S5c runs the per-label procedure on the 45 hold-out labels after the human unseal. Their load is unknown by design, because the sealed data are not read.
- **S5c scoring depends on the S5 design.** Checked from the addendum text (v1.5 §7) only, with no sealed data read. The S5c baseline is computed from the **discovery labels' S5 records** persisted before the unseal, in the following steps.
  1. Stratify the labels by tier × row_count band × source-count band × the frozen analog of O.
  2. Every stratum needs n_s ≥ 10. A smaller stratum is collapsed; the analog is never dropped.
  3. A component with any P-label still below 10 has no baseline.
  4. If more than 20% of P-components have no baseline, the result is **NOT-SCOREABLE**.

  Consequences:
  - **O2 and O4:** a sample shrinks every n_s, so the risk of NOT-SCOREABLE rises as n falls. n = 100 is especially exposed.
  - **O3:** the same, unless the screening preserves the analog strata.
  - **O1 and O5:** they keep n_s near the full population. Under O5(a), the BLOCKED hub labels have no S5 records and leave their strata. They are high-row-count, generic-term labels, while the hold-out is described as deliberately peripheral, so the effect on baseline strata should be small. It cannot be verified without the sealed data and must be **stated as a limitation**, not corrected.

  An S5 design that makes S5c NOT-SCOREABLE would spend the one-time hold-out for nothing. **This favours designs that keep the discovery records near-complete.**
- **Repeated-run stability:** the G-LOG-0028 note says to evaluate stability across repeated runs. That can be added to any option as a seeded re-run subsample, costed at the per-label rates above.

## 3. Assessment

- **O1** is the protocol as frozen. 3.45 M dispositions is the non-feasibility §19.4 anticipates: about 568× the whole audited R2.2 run (6,081 dispositions), spread over roughly 400 batches. *(Corrected 2026-09-24: an earlier wording of this sentence had no measured basis.)* The hub labels alone are infeasible at S4 quality.
- **O2 and O4** are cheap enough but weak for the programme's actual question, **recurrence across labels** (S5a/S5b). A random sample of 200 observes about 1% of label pairs.
  - O2's cost is also dominated by whichever hub labels happen to be drawn (see the 90th-percentile column).
  - O4's purposive part is load-heavy and supports no population claim.
- **O3** helps recurrence power over O2, but adds a screening design whose circularity must be managed. That design is new methodology.
- **O5** keeps the frozen per-label procedure **unchanged** for about 95% of labels. It costs fewer dispositions than O2 at n = 200 (median), and preserves the recurrence density S5a needs. Its open questions are:
  - the threshold rule;
  - the hub treatment, (a) or (b);
  - whether (a) needs a core amendment or only a ruling.

  The hub labels it sets aside are the ones where lexical search is least informative. Of all the sampled designs, it is also the one that best preserves the S5c baseline strata (§2, common notes).

**Recommendation (for the human's decision): O5.** Decisions it needs:
1. The threshold rule. Define it by a pre-registered S3-only criterion, such as a disposition-load or percentile rule, rather than a hand-picked k.
2. The hub treatment. Recommended: **(a)**, BLOCKED with a recorded load escalation, which keeps §11.4 intact. (b) only if hub-label absence results are needed.
3. Whether (a) proceeds by run-level ruling or by v1.7.
4. A seeded repeated-run stability subsample.

After that, the S5 plan carries H-12 (with load-based batching) and the gated decisions.

## 4. What this record does not do

- It fixes no sample size or threshold.
- It adopts no option.
- It changes no frozen text.
- It reads no corpus content and no sealed artifact.
- It starts no S5 work.

---

## 5. After the architecture choice: O5 threshold sensitivity and hub semantics (G-LOG-0032)

The human chose **O5 with hub treatment (a)** as the architecture. The threshold is not chosen. It is frozen before any S5 result exists.

**Figure check.** Stage-2 reading for the full census is **1.45 GB** (1,448,842,597 bytes), as in §2 and G-LOG-0031. No committed artifact contains a different figure.

### 5.1 Candidate threshold rules

Each rule is derived from S3 stage-1 data, or from S4 R2.2 audited capacity measured before S5. None uses the hold-out. Output: `audit-p3b/S5-FEASIBILITY-COST.json` (`O5_sensitivity`, including every hub label), from `scripts/p3b_s5_feasibility_cost.py`.

| Rule (a label is a hub if its predicted dispositions exceed…) | Threshold | Hubs | Processed | Stage-2 bytes | File reads | Dispositions | Dispositions excluded | Hubs in checklist |
|---|---|---|---|---|---|---|---|---|
| T1: the population's 99th percentile | 40,093 | 19 (1.0%) | 1,956 | 0.88 GB | 32,270 | 939,363 | 72.8% | 6 |
| T2: the population's 95th percentile | 1,839 | 98 (5.0%) | 1,877 | 0.32 GB | 10,112 | 202,269 | 94.1% | 37 |
| T3: the whole audited R2.2 run load | 6,081 | 60 (3.0%) | 1,915 | 0.46 GB | 15,247 | 314,020 | 90.9% | 22 |
| **T4: the largest single-label load processed and audited in S4 R2.2** (glyph-register) | **4,081** | **66 (3.3%)** | **1,909** | **0.43 GB** | **13,946** | **283,576** | **91.8%** | **24** |
| T5: the mean audited R2.2 batch load | 1,520 | 105 (5.3%) | 1,870 | 0.31 GB | 9,546 | 190,398 | 94.5% | 38 |

**Remaining S5c baseline strata.** The addendum §7 cells are tier × row_count band × source-count band, over discovery labels only; the O-analog dimension is unknown until O is fixed. 18 cells exist and all 18 have n ≥ 10 under **every** rule, so no cell is lost. The analog split could still thin cells; that is not computable now and is stated as a limitation. O5 improves the conditions for S5c. It does not predetermine S5c's result.

**Recommendation: T4.** It is the only rule anchored in demonstrated capacity: every processed label stays within a per-label load that S4 actually processed and audited.
- **T1** admits labels up to about 10× anything demonstrated.
- **T5** is stricter than demonstrated capacity for no evidential reason.
- **T2 and T3** are defensible, but they are anchored to a percentile or to a run total rather than to per-label capacity.

### 5.2 Hub semantics: what the frozen text permits

- In §22, `BLOCKED` is prescribed for exactly one condition, a missing file. The table has **no load condition**.
- §11.4 requires every stage-1 hit to be read whole.
- §19.4 names the route for infeasible load itself: *"a revised annex is presented."*

**Finding: existing semantics are not sufficient, and the protocol-correct route is a minimal v1.7.** This corrects the "may need only a run-level ruling" remark in §2.

### 5.3 A variant of hub treatment the human should see: (a) vs (a′)

| | (a) whole label BLOCKED-LOAD | (a′) label processed; stage 2 ESCALATED-LOAD |
|---|---|---|
| What the hub gets | nothing: no object record, a load escalation only | the full per-label procedure (timeline, births, statuses, research where checklisted, stage 1), but **no stage-2 reading**. Every absence dimension with stage-1 hits resolves `ESCALATED` with a load escalation. NEGATIVE-CENSUS dimensions still resolve GENUINELY-UNDEFINED |
| Hits thinned? | no | no: stage 2 is not performed at all for the hub, and this is recorded per dimension |
| §23 terminal predicate | holds via "BLOCKED with an open escalation" | holds via item 3 ("… or ESCALATED") |
| S5a/S5b | hubs absent from recurrence | hubs present with everything except absence results |
| S5c baseline | hubs contribute no records | hubs contribute records to every stratum except absence-based O |
| Cost | none | per-label fixed work for about 66 labels, many with ≥ 10 rows |
| Amendment | §22 row + §19.4/§23 scope | §22 row + a §11.4 exception + §19.4/§23 scope |

(a′) keeps more evidence than (a) at a small fixed cost, and it is not partial processing of hits. It is a different choice from the one made, so it is presented, not substituted.

### 5.4 Permitted claims under O5 (any rule)

- **A (observed population):** "among the N labels processed under the frozen procedure …". Always permitted.
- **B (bounded):** "across the processed population; the listed hub labels remain unresolved (load)". Permitted, with the hub list attached.
- **C (all 1,975 labels):** **not established by O5.** It would need additional methodology showing that the hubs cannot alter the conclusion.

### 5.5 Hub record (every hub, either treatment)

- label;
- predicted dispositions, stage-2 bytes and file reads;
- the rule and threshold;
- the reason (`LOAD`);
- whether any processing occurred;
- the evidence left unavailable;
- the governance entry.
