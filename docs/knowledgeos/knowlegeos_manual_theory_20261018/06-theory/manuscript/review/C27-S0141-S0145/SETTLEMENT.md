# Settlement of window C27 (S0141–S0145)

*2026-10-23. By the Professor. One window reviewer W (Opus): a blind inventory first, then steps 2–3.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| Step 1, blind inventory | `STEP1-INVENTORY.md` | `9624a6cb03e19cf58f5dfe431c1839b80592a44d7215445cd52baa3082f3c8b0` (re-checked by the reviewer) |
| Steps 2–3, review report | `REVIEW-REPORT.md` | `6abbd80bc94d5b4aee5a2f118feea07c3470c4048817ad24417e9615c0b797a3` |

## 0. Per-window report

| Item | Value |
|---|---|
| **Sources and lines** | S0141 (428), S0142 (583), S0143 (114), S0144 (80), S0145 (289): 1,494 lines; none added |
| **Professor's harness time** | 18:31:14 → 18:50:27 UTC. Reading and writing took 420 s (to the draft commit, 18:38:14); the rest is the review wait and the settlement |
| **Reviewer's harness time** | step 1: 199 s · steps 2–3: 596 s · total 795 s |
| **Depth, Professor** | full on all five |
| **Depth, reviewer (blind)** | full on all five |
| **Findings by severity** | critical: 1 (W-01, by F1: C8 and CON-0252 cited it; not F4) · high: 8 (W-02, 03, 04, 07, 08, 09, 11, 13) · normal: 9 *(count corrected when the S0150 evaluation facts were prepared: the draft said 9 high and 8 normal)* |

## 1. Findings and their disposition

All 18 findings were accepted.

| W | Disposition | Where |
|---|---|---|
| 01 | Premise "no shared order source" added; "if" from K2; C8 and CON-0254 rest on the restatement | §8.20 C2; CON-0251 |
| 02 | CON-0252 INVALID as stated; restated as CON-0254 (hash self-commitment; evidence-to-the-leader) | §8.20 C6; register |
| 03, 04, 05 | CON-0250 restated: D records removals but forbids none; an explicit hold state, location-independent, with a gate on moves; preservation separated from blocking | §8.20 C1, C9; CON-0250 |
| 06 | Head-naming scoped to the named prefix | §8.20 C2, C8; CON-0251 |
| 07, 08, 09 | CON-0253 restated on three axes; missing acts caught when cited; S3 fixture-fed; quiet-row scope | §8.20 C12, C15; CON-0253 |
| 10 | CON-0243's operand clause labelled "not from K1", with its condition | CON-0243 |
| 11 | The narrowed exit criterion recorded; counted for S0150 | §8.20 C12 |
| 12 | Recount corrected; no trend established; the inconsistency stated, not a trend | §8.20 C19 |
| 13 | Remedy text from the reviewer recorded | S0141 lineage |
| 14 | The "verbatim" DA text and Phase 0's authority shift recorded | S0143, S0145 lineage |
| 15 | H3b "agreement" corrected; CON-0071 scoped; C16's two mechanisms named | §8.20 C16, C20, C22 |
| 16 | C17 scoped; C14 qualified | §8.20 C14, C17 |
| 17 | §8.2 cited; "rediscovery" qualified | §8.20 C23 |
| 18 | small points fixed | §8.20 |

## 2. Verdicts

| CON | After |
|---|---|
| 0250 | PROVISIONAL, restated |
| 0251 | PROVISIONAL, premise added |
| 0252 | **INVALID as stated** → CON-0254 (PROVISIONAL) |
| 0253 | PROVISIONAL, restated (three axes) |
| 0243 | PROVISIONAL, amended |

## 3. Notes for the S0150 evaluation

- **R5:** no finding was critical by F4 only. W-01 is critical by F1, a missing premise with dependants.
- **The adversarial pass:**
  - It narrowed two claims; one narrowing was still wrong (W-03).
  - It scoped C6 in a way that was refuted (W-02).
  - This is the third window running in which an amendment made in the pass did not survive review.
  - The reviewer also notes that no new concept recorded a rival explanation and a falsifier (entry 55 (c), (d)).
- **Defects found later in reviewed work:** none against settled sections in C27. Within the sources, S0143 narrowed its exit criterion without a record (W-11), and S0145's trend contradicts its own boundary (C19).
- **Depth:** the reviewer notes that the harness times (43–86 s, writing included) cannot distinguish a full read from a skim.

## 4. Checks

- **Quote check:** §8.20 14/0; the whole book 3, the baseline.
- **R1:** 230 rows with a home, 1 inherited flag.
- **Register:** 254 concepts (40 VALIDATED, 117 PROVISIONAL, 97 INVALID).

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| §8.20.3 kernel questions (a positive hold state; cross-record order and its premise; the closing-record rule) | Professor | for a theory turn; not normative |
| pending S0300; OPEN-M6, M7; the design's acceptance | Professor | settle when the sources arrive |
| reading | Professor | C28 (S0146–S0150), then stop |
