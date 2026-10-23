# Settlement of window C26 (S0136–S0140)

*2026-10-23. By the Professor. One window reviewer W (Opus): a blind inventory first, then steps 2–3.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| Step 1, blind inventory | `STEP1-INVENTORY.md` | `04d52c41dbc52ae1c0cb252e975f1c22dde27ea88df0f91f93dd67dbf677bd9d` (re-checked by the reviewer) |
| Steps 2–3, review report | `REVIEW-REPORT.md` | `bcb7be1f77b1a4cee33ce13a703c7ad67b46bede596d7ab496882eb672dcce1d` |

## 0. Per-window report (the Principal's form)

| Item | Value |
|---|---|
| **Sources and lines** | S0136 (418), S0137 (471), S0138 (381), S0139 (115), S0140 (160): 1,545 lines; none added |
| **Professor's harness time** | 18:11:28 → 18:31:02 UTC. Reading and writing took 500 s (to the draft commit, 18:19:48); the rest is the review wait and the settlement |
| **Reviewer's harness time** | step 1: 136 s · steps 2–3: 494 s · total 630 s |
| **Depth, Professor** | full on all five |
| **Depth, reviewer (blind)** | S0136, S0137, S0138 FULL; S0139 SKIM; S0140 FULL (§2), SKIM (rest) |
| **Findings by severity** | critical by F4 only, no dependants: 2 (W-08, W-09) · high: 7 (W-01 to W-07) · normal: 10 (W-10 to W-19) |

**Depth, compared.** The reviewer judged that the full reading of S0139 added no theory beyond its skim. Its skim also found two self-contradictions that the full reading missed (W-10). *Recorded for the S0150 evaluation: here a full read cost about 25 s and produced nothing a skim did not.*

## 1. Findings and their disposition

| W | Disposition | Where |
|---|---|---|
| 01 | **Accepted.** CON-0247 INVALID as stated, restated as CON-0249 (sufficient conditions; non-trivial declaration; losses among declared writes; entry vs file granularity) | §8.19 C17, C25; register |
| 02 | **Accepted; the Professor's addition refuted.** The path form is withdrawn. CON-0246 INVALID as stated, restated as CON-0248 | §8.19 C4, C12; register |
| 03 | Accepted: "settles" changed to "bears on, pending S0300" twice | §8.19 C2, C11 |
| 04 | Accepted: C3's writer premise cited; α unreachable without write times | §8.19 C18 |
| 05 | Accepted: heading corrected | §8.19 C3 |
| 06 | Accepted: a source defect recorded; C27 restated with its unsatisfiable case | §8.19 C27 |
| 07 | Accepted: durability added; law 12 cited as the existing home; "checked" scoped | §8.19 C1; CON-0245 |
| 08 | Accepted: labelled an analogy. *Critical by F4 only, no dependants* | §8.19 C1 |
| 09 | Accepted: "outside D, hypothetical" label. *Critical by F4 only, no dependants* | §8.19 C13 |
| 10 | Accepted | §8.19 C23 |
| 11 | Accepted | §8.19.3 |
| 12 | Accepted | §8.19, S0138 header |
| 13 | Accepted; counted for S0150 (defects found later in reviewed work, within the estate) | §8.19 C11 |
| 14 | Accepted: a count, not a rate | §8.19 C10 |
| 15 | Accepted | §8.19 C1 |
| 16 | Accepted: §1.16.7 quoted from its own text, so the extension is no longer needed | §8.19 C19 |
| 17 | Accepted | CON-0240 |
| 18 | Accepted | CON-0243; §8.19 C11 |
| 19 | Accepted | §8.18 C21 note |

## 2. Verdicts

| CON | Before | After |
|---|---|---|
| 0245 | PROVISIONAL | PROVISIONAL, amended (durability; law 12; analogy) |
| 0246 | PROVISIONAL | **INVALID as stated** → CON-0248 (PROVISIONAL) |
| 0247 | PROVISIONAL | **INVALID as stated** → CON-0249 (PROVISIONAL) |
| 0240, 0243 | PROVISIONAL | PROVISIONAL, amended |

## 3. Notes for the S0150 evaluation

- **R5 calibration:** two findings were critical by F4 only, with no dependants (W-08, W-09). Both were rated by the rule.
- **The Professor's own additions:** of the two amendments made in the adversarial pass, one held (C3 narrowed) and one was refuted (the path form, W-02). This repeats C25's W-01 pattern: an amendment made in the pass can introduce an error the pass does not test.
- **Spot-check of the C25 settlement:** eight rows held.

## 4. Checks

- **Quote check:** §8.19 34/0; §8.18 69/0; the whole book 3, the baseline.
- **R1:** 225 rows with a home, 1 inherited flag.
- **Register:** 249 concepts (40 VALIDATED, 113 PROVISIONAL, 96 INVALID).

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| §8.19.3 kernel questions (L9 with K6 and K1; membership at write time; the declared-write claim) | Professor | for a theory turn; not normative |
| pending S0300; OPEN-M6; the design's acceptance; the residuals left unaddressed by AMD5 | Professor | settle when the sources arrive (S0141, S0300) |
| reading | Professor | C27 (S0141–S0145) |
