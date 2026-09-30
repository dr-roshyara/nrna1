# S5 plan: independent red-team review (round 1)

| Field | Value |
|---|---|
| Object | `audit-p3b/20260924_2330_s5-plan.md` (G-LOG-0035) |
| Reviewer | independent read-only agent (claude-opus-5-5). No sealed material read (only the seal `state` field); no corpus files |
| Verdict | **DEFECTS (3 blockers, 18 majors, 17 minors)** |

## Figures that reproduce exactly

- **Non-hub cost model:** 283,576 dispositions; p50 20, p90 352, p99 2,169, max 4,081; 0.427 GB; 13,946 file reads.
- **Step-1 proxy:** 88.26 MB, reproducing R2.2 at 2.446 MB.
- **R2.2 anchors:** 4,306 dispositions and 8,633,127 B (PB02); 1,801,465 B step-1 (PB05).
- **Packing:** 191 / 239 / 382 batches, plus 14 hub batches.
- **Checklist labels:** 24 hub / 543 non-hub.
- **Tracks:** 990 / 776 / 127 / 110, with 28 MIXED.
- **Degree over 1,975 labels:** p90 4, p95 6, p99 13, max 35; files above caps = 126 / 50 / 29 / 12 / 10.
- **P2a preview:** 324 / 261 / 138 / 69 (9) / 22 (1) / 5.
- **Register:** 71 / 10 / 6.
- **Baseline cells:** 18, all with n ≥ 10.

## Blockers

- **B1, audit design.** Sampling 1 object and 4 register records per batch contradicts §21 items 1 and 5, which make every CONTESTED and HOMONYM-SPLIT label and every H / SC / SCHEMA-LIMITATION / METHODOLOGICAL-DEFICIENCY record mandatory. That is about 11–12 per 10-label batch at the R2.2 rate. The design also omits the OMQ-09 rule (G-LOG-0017: 10% of records per batch, minimum 5).
- **B2, hard budget caps.** Making the caps a verifier failure adds gates to §20's closed list. The caps sit at 1.00× the *predicted* anchors, while actual reads page (PB02 read 1.24× its model). The heaviest batches (47 of them at ≥ 4,000 dispositions) would fail by construction, fail again on re-run, and reach the §23 stop-the-line. This also contradicts the 1.25× gate.
- **B3, false [MEASURED] anchor.** "Labels per audited batch: 5, all checklist labels" is false: R2.2 had 8 of 20 labels in the checklist (2 / 2 / 0 / 4). So "checklist ≤ 5" exceeds the measured maximum of 4, and S4 did process 12 non-checklist labels.

## Majors (by decision)

- **H-12:**
  - The calibration gate's remedy (L_max = 5) cannot cure its own byte or disposition triggers. Regenerating the manifest after dispatch conflicts with §24.1.
  - The 30 S4 pilot labels (re-run, relation to S4-accepted records, PB01's divergent FOUNDs) are not addressed.
  - There is no model_id or generation-parameter rule (§18).
  - The [MEASURED] figures come from uncommitted code. "49 batches bound by disposition" does not reproduce: 47 batches, and label count binds in 190 of 191.
  - 09-ORCHESTRATOR-FLAGS is not re-decided.
- **OMQ-16:**
  - "Lower, never raise" is not mechanized. It needs a seeded order frozen before testing, lowering only by prefix truncation, and a governance act blind to the outcomes.
  - The test pass has no read budget: census-scope B/D searches for generic terms recreate the hub load. It also has no contract, verifier, run ids or §13.10a manifest step.
- **H-15:**
  - Budget per "generator kind" is really per generator. G-SHARED-GROUP has 719 distinct sets, and the table double-counts dual-role sets.
  - There is no cross-object cost estimate (§19.4).
  - θ = 0.5 is degenerate: 6 of 20 R2.2 labels have no bigram, 11% of pairs reach J ≥ 0.5 through the generic (FIRST, EXTENDS) bigram, and there is no rule for empty sequences.
  - Vocabulary definitions are missing. The mapping is incomplete or inconsistent (G-NOTATION × IDENTITY-STATE-CONFLATION misread; EQUIVALENCE-CLASS cells; the "at the co-change source" qualifier; the §9E.1 P3a-pair rule and RC-13 not reflected).
  - The freeze is deferred until after ACCEPTED records exist, so it could be tuned on outcomes.
  - There are no statistical or logic classes.
- **H-17:**
  - It omits G-TYPE-SIM matching on has-formal-rows, and puts arity in the relaxation list, although arity is exact and never relaxed.
  - Fisher's validity is overstated: controls can share labels, and the pooled 2×2 table ignores 1:r matching.
  - Actual multiplicity is 65–77 cells, not ~50. Power at m = 20, r = 2 is 0.38 at α = 0.05 and 0.06–0.15 under BH. m ≥ 20 cells reported UNDIFFERENTIATED hide low power. k = 3 cells are UNDERPOWERED with certainty.
  - Hub exclusion per cell is undefined and would break the frozen test family.
- **H-18:**
  - The population is mislabelled: 1,975 is the S5 population, not the 2,452-label discovery population, where the figures equal OMQ-21 (50 files above cap 10).
  - "Removes every documented registry" is false (S2523 / S2524 / S2528 kept).
  - The S2819 statement is wrong.
  - Degree is undefined for timeline sources no row cites.
- **§K stability:**
  - Allocation over 24 strata is impossible, because 348 purposive non-hub labels have no stratum. 5% of the stratified labels is about 78, not 96.
  - The baseline (P3B-R1 vs R2.2, n = 20) is inter-instrument, not test–retest, so STABLE-AT-BASELINE is near-vacuous.
  - No baseline exists for absence resolutions, structure classes or κ.
  - UNSTABLE is essentially uncomputable at 5% (P ≈ 0.0025 that both members of a pair are re-run).
  - The model is not held constant, and the acceptance route for reruns is unspecified.
- **H-19 protection:**
  - There is no quarantine scanner (addendum §6).
  - There is no assertion that S5a inputs from `_derived.json` / 03 / 02 / 31 are discovery-filtered.
  - The grep misses base-ContentResolver use, `P3B-ABSENCE-SEARCH.jsonl`, `bundle_index.jsonl` and `P3B-WORKLOAD.json`.
  - The test pass's B/D population is not pinned to discovery.
- **§O:** missing deliverables:
  - the test-pass contract and verifier;
  - the G-13 pass verifier;
  - the §21 item 6 cross-scale audit plan;
  - the quarantine scanner;
  - a hashed freeze of the discovery record set before the unseal;
  - the rerun rules;
  - the model-id rule.

## Minors (17)

- **H-12:** tier-wise packing (§9.4); re-run naming (`R2.2` per §20); contract filename (§19.2); per-batch H-06 within group acceptance; OMQ-14 citation; Stage 2B load outside the caps.
- **OMQ-16:** step-10 reads of hub hit files; `DEFERRED (BUDGET)` is not a register value.
- **H-11:** H-11c consequence (iii) misstated; H-11d option (iii) truncated; H-11a note string.
- **OMQ-07:** rationale one-sided; counts non-hub only (1,975: 138 UNCONFIRMED / 31 MIXED); MIXED count depends on the resolution (16 if only PHASE-MEASURE counts as A).
- **H-16:** evidence cites instrument instability, not source dependence; the mtime-block field is undefined for no-block files, and BULK blocks are copy artefacts.
- **OMQ-17:** "2 of 6" should be 4 of 6; ACCEPTED must be defined as ledger records with an H-06 entry.
- **H-19:** baseline records are "persisted as PROPOSED" (addendum §7); primary vs rerun records not specified.

## Sound as proposed

H-11a, H-11b, H-11c, H-11d, OMQ-17, OMQ-18, the N_support value of H-16, and OMQ-07 once its rationale is corrected.
