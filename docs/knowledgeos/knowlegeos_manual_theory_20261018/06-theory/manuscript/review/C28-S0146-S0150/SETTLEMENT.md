# Settlement of window C28 (S0146–S0150)

*2026-10-23. By the Professor. One window reviewer W (Opus): a blind inventory first, then steps 2–3. The last window before the S0150 consolidation.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| Step 1, blind inventory | `STEP1-INVENTORY.md` | `71c87c033b9a82048c3aac59f3367b1168225ad76f4a578afbc5fb9c7390f9b2` (re-checked by the reviewer) |
| Steps 2–3, review report | `REVIEW-REPORT.md` | `f860e3676a4b3aa40247092b0bc09fd06279fc113977f11009c6512810df6423` |

**Disclosed by the reviewer:** a grep brought up the header lines of a later-chain source (the DV-correction independent review, L7–27). The reviewer used nothing from it.

## 0. Per-window report

| Item | Value |
|---|---|
| **Sources and lines** | S0146 (279), S0147 (PNG), S0148 (165), S0149 (822), S0150 (602); none added |
| **Professor's harness time** | 18:50:38 → 19:05:37 UTC. Reading and writing took 230 s (to the draft commit, 18:54:28) |
| **Reviewer's harness time** | step 1: 182 s · steps 2–3: 535 s · total 717 s |
| **Depth, Professor** | S0146 full · S0147 skim · S0148 full · S0149 skim · S0150 full |
| **Depth, reviewer (blind)** | S0146 full · S0147 skim · S0148 full · **S0149 full** · S0150 skim (with the second turn full) |
| **Findings by severity** | critical: 3. W-01 by F1. W-04 and W-05 **critical by F4 only, no dependants**. · high: 5 (W-02, 03, 06, 07, 08) · normal: 8 (W-09 to W-16) |
| **§3 safeguard** | **the S0149 skim lost theory** (W-07): the corpus evidence for the R_K fork, and the source's DDD defects. The S0147 skim lost a little (W-12) |

## 1. Findings and their disposition

All 16 findings were accepted.

| W | Disposition | Where |
|---|---|---|
| 01, 02, 03 | CON-0255 INVALID as stated; restated as CON-0256 (two conditions, the premise stated, ordering as an executability note, a new rival and falsifier, scope one lineage) | §8.21 C1; register |
| 04, 05 | CON-0085 and CON-0045 marked INVALID where cited; not homes. *Critical by F4 only, no dependants* | §8.21 C13, C15 |
| 06 | C13's mappings corrected (necessary vs sufficient; product vs execution state) | §8.21 C13 |
| 07 | S0149's lost theory added; THEORY-MAP R_K cites S0149 | §8.21 C13; THEORY-MAP |
| 08 | C9 and the S0148 notes qualified (self-issued, in-sample, quiet within scope) | §8.21 S0148 |
| 09 | C8: the truth order; aggregation across slices only; "independently" withdrawn | §8.21 C8 |
| 10 | C14: "not assured"; the source's own "Evidence ≠ Proof" | §8.21 C14 |
| 11 | cross-reference corrected | §8.21 C1 |
| 12 | S0147: acceptance in two places; role/actor merge; "assures" attack weakened | §8.21 S0147 |
| 13 | the second artifact; S0146 section-order and count defects | §8.21 S0146 |
| 14 | S0148 count and line-count defects | §8.21 S0148 |
| 15 | S0150's second turn corrects the first | §8.21 C15 |
| 16 | CON-0250 remnant removed (C27 W-04 completed); **a defect found later in settled work** | register |

## 2. Verdicts

| CON | After |
|---|---|
| 0255 | **INVALID as stated** → CON-0256 (PROVISIONAL) |
| 0250 | PROVISIONAL, remnant fixed |

## 3. Notes for the S0150 evaluation

- **R5:** two findings were critical by F4 only, with no dependants (W-04, W-05), rated by the rule.
- **Skim:** of two skims, one lost theory (S0149).
- **Rival and falsifier, recorded for the first time (from C28):** the falsifier was immune to refutation (W-03).
- **The adversarial pass:** its C1 attack tested the wrong direction of necessity. This is the fourth window running in which the pass's own reasoning failed under review.

## 4. Checks

- **Quote check:** §8.21 5/0; the whole book 3, the baseline.
- **R1:** 232 rows with a home, 1 inherited flag.
- **Register:** 256 concepts (40 VALIDATED, 118 PROVISIONAL, 98 INVALID).

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| §8.21.3 kernel questions | Professor | for a theory turn; not normative |
| the S0150 consolidation | the Rule Reviewer, then the Professor | **not started.** It waits for the Rule Reviewer to freeze the book copy and seal the planted-gap key |
