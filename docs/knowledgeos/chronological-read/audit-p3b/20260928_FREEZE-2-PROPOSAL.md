# Freeze 2: final frame, strata and sample (PROPOSAL, for human approval)

| | |
|---|---|
| **Kind** | Freeze proposal. ⚠ authority: generated. **No seed was generated and no sample was drawn.** |
| **Rests on** | Freeze 1 (G-LOG-0094; B1 v2 `d6b23886…`, `prereg_v1.py` `eb6291ab…`), unchanged · the 12 binary decisions (G-LOG-0096; `audit-p3b/20260928_BINARY-DECISIONS.json`) |
| **Method** | metadata and file sizes only: prepare's quarantine-aware Context; `r5.all_blob_sizes` (git sizes + preserved-audit-copy sizes); the byte counts recorded by the authorized binary read. No new corpus read |

## 1. IV-2 resolved

- **Cause.** Prepare's size map (`blob_sizes`) covers GIT-OBJECT blobs only, so 8 files are unsized in it: the 7 NUL files plus 1 more. `r5.all_blob_sizes` (revision-5 item 4) adds the preserved-audit-copy sizes.
- **The production R7 plan is not affected by the gap:** `plan_derive` sizes each required file by its **resolver byte length**, and the verifier re-derives the plan the same way.
- **Consistency:** for all 12 binaries, `all_blob_sizes` equals the bytes read.
- **Scope, population-wide:** R(L) differs for **23 labels**, all touching one of the 12 binaries. **Exactly one label crosses 600,000 bytes.**
- **Consequence:** the corrected path totals, **SINGLE 1,789 / DECOMPOSED 178 / EMPTY 8**, are **identical to the revision-5 dry run** that EP-01 used. The R5 record already noted "one more label crosses 600 KB once preserved copies are sized".
- **So:** the frozen design's inputs were already correct. **Only the historical brief's byte total (1,731,013 → 1,898,792) was wrong.**

| Label | Historical size | Corrected size | Old path | New path | Changed? |
|---|---:|---:|---|---|---|
| cr2-ordering-falsified-fr001-withdrawn | 3,951,030 | 3,997,522 | DECOMPOSED | DECOMPOSED | no |
| decision-authorization-action-triple | 3,642,231 | 3,749,380 | DECOMPOSED | DECOMPOSED | no |
| decision-contract-admissibility | 3,466,443 | 3,573,592 | DECOMPOSED | DECOMPOSED | no |
| e20-calibration-reclassification-not-applicable | 1,140,564 | 1,157,617 | DECOMPOSED | DECOMPOSED | no |
| effect-floor-a5-vs-delta-loss-split | 3,132,822 | 3,179,314 | DECOMPOSED | DECOMPOSED | no |
| engineering-with-knowledgeos-chapter | 2,145,018 | 2,252,167 | DECOMPOSED | DECOMPOSED | no |
| epistemic-operating-system-research | 2,908,407 | 2,969,064 | DECOMPOSED | DECOMPOSED | no |
| executable-kos-kernel | 66,226 | 77,853 | SINGLE | SINGLE | no |
| glyph-register-v3-collision-inventory-2026-09 | 8,310,036 | 8,382,320 | DECOMPOSED | DECOMPOSED | no |
| k-attack-right-shape-wrong-relation-verdict | 1,293,085 | 1,303,005 | DECOMPOSED | DECOMPOSED | no |
| kernel-corpus-dependency-map | 1,170,164 | 1,216,656 | DECOMPOSED | DECOMPOSED | no |
| mcginn-logical-properties-lens | 4,076,544 | 4,137,201 | DECOMPOSED | DECOMPOSED | no |
| orphan-state-structural-not-epistemic | 3,636,278 | 3,694,397 | DECOMPOSED | DECOMPOSED | no |
| s1614-corpus-derived-transformation-ontology | 250,088 | 267,141 | SINGLE | SINGLE | no |
| s1621-coarsest-sufficient-state-abstraction-and-counterexample-catalogue | 300,594 | 312,221 | SINGLE | SINGLE | no |
| s1623-operation-registry-schema-and-dependency-matrix | 590,300 | 607,353 | SINGLE | DECOMPOSED | **YES** |
| s1626-four-valid-predicates-and-twelve-operation-algebra | 799,803 | 816,856 | DECOMPOSED | DECOMPOSED | no |
| s1629-four-operations-identity-precedes-equality | 348,049 | 365,102 | SINGLE | SINGLE | no |
| second-order-congruence-correction-of-first-order-exp3 | 110,916 | 120,836 | SINGLE | SINGLE | no |
| select-correction-kept-open-not-prematurely-external | 2,802,841 | 2,863,498 | DECOMPOSED | DECOMPOSED | no |
| status-ladder-committed-boundary | 7,099,965 | 7,207,114 | DECOMPOSED | DECOMPOSED | no |
| step281-missingness-repair-inquiry-register-selection | 800,140 | 805,059 | DECOMPOSED | DECOMPOSED | no |
| worked-example-proof-verification-and-reasoning-result | 1,903,996 | 2,056,936 | DECOMPOSED | DECOMPOSED | no |

**Effect on the frame.** `s1623-operation-registry-schema-and-dependency-matrix` moves from SINGLE to **DECOMPOSED**, and it is not MULTIROW. This changes N_SINGLE by −1 and N_DECOMPOSED by +1 relative to the historical sizing. No other assignment, sample size, inclusion probability or estimand changes.

## 2. Final frame and strata (R7 precedence HUB > EMPTY > MULTIROW > DECOMPOSED > SINGLE; B1 v2 D1)

| Stratum | N_h | Treatment | Rate | n_h (realized, `round_half_up`) | π_h | Exact U_h at d = 0 (α/H′ = 0.05/3) |
|---|---:|---|---|---:|---:|---:|
| HUB | 66 | SRSWOR | 0.5 | 33 | 0.500 | 5 |
| EMPTY | 7 | census | CENSUS | 7 | 1 | 0 |
| MULTIROW | 4 | census | CENSUS | 4 | 1 | 0 |
| DECOMPOSED | 173 | SRSWOR | 0.5 | 87 | 0.503 | 5 |
| SINGLE | 1,725 | SRSWOR | 100/1725 | 100 | 0.058 | 67 |
| **Total** | **1,975** | | | **231** | | **U = 77 (θ_D ≤ 3.9% at 95% if d = 0 everywhere)** |

- **Hub overlap (reported as a domain):** 1 hub label is EMPTY and 1 is DECOMPOSED-MULTIROW. By precedence both are sampled in HUB, not taken as a census.
- **Frame sha256** (sorted label list): `d0c972bf53207dc581cf094298d273209e833ca009351af5f083912329857d0d`.
- **The cost/precision trade-off stays with you:**
  - SINGLE n = 60 gives U_SINGLE = 111;
  - n = 150 gives U_SINGLE = 44.
- **θ_A subsample (B1 v2 §1):** the MULTIROW census (4) + 10 SINGLE labels, drawn from the SINGLE sample with a separate seed; plus a double-adjudicated subsample of ≥ 20 labels for the reference-quality criterion.

## 3. Treatment of the 12 binary files

- **They stay in R(L), and the frame and strata do not depend on the decisions.** A FALSE-HIT or NOT-CONSUMED-ESCALATED decision is a *disposition class* for the hit, not a removal of the file.
- **FALSE-HIT (5 files):** the hit is recorded as FALSE-HIT with its offset and region.
- **NOT-CONSUMED-ESCALATED (7 files):** every dimension is ESCALATED, and a CONTRACT-DEVIATION escalation names the file. G-04 is never thinned.

## 4. Two items that must be settled before activation (they do not change the frame)

- **AG-1 (activation gap):** **no code path delivers the 12 decision records to agents or to the R7 verifier.**
  - The reader refuses binary content, so none of the 12 can be read.
  - Under R7's reading rule, even the 5 FALSE-HIT files would then need a CONTRACT-DEVIATION escalation.
  - Wiring the decisions into the slices, or into I(run) as DECLARED-SYNTHESIS-INPUT-like governed input, **and** into the verifier's reading rule is an implementation step. It needs its own bounded authorization, tests first.
- **ORD-1 (ordering):** `estimate_v7` REJECTs a frozen membership that differs from the plan-derived one. The strata above come from the metadata dry run; the frozen production plan is written at activation. Two ways to handle it:
  - **(a)** Freeze now, with a hard check at activation that the production plan reproduces this membership exactly. On any mismatch, STOP: Freeze 2 is void, and no S5 output exists yet.
  - **(b)** Activate first (manifest + frozen plan), derive the strata from the frozen plan, then Freeze 2 (seed + sample), all before any S5 dispatch. Both satisfy EP-01's rule that the draw precedes any S5 output.
  - **Recommended: (b)**, because it removes the source of mismatch.

## 5. The Freeze 2 act (when approved)

1. Approve this text (sha256), the frame hash and the strata.
2. Resolve ORD-1; with (b), the stratum membership is re-derived from the frozen production plan and must equal §2.
3. The seed: 128-bit, from the OS CSPRNG (`secrets.randbits(128)`), generated **at the act**. Record it with the Python version and the `random` module source hash.
4. The draw: `S.freeze(frame, rates, seed, spec_text, alpha=0.05)`. The sample file and its sha256 are committed, and the record hash is anchored in the G-LOG.
5. The θ_A subsample and the double-adjudication subsample: a separate seed, generated at the same act.

**Traceability:** G-LOG-0094/0095/0096 · EP-01 · B1 v2 · `p3b_s5_r5.all_blob_sizes` · `p3b_s5_r6.plan_derive` · `p3b_s5_r7_stats`.
