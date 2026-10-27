# Window C31: settlement

*2026-10-27. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`). Draft `e95dfeb0a`; brief `0c4f37efb`. Rulebook v1.4.*

"V (Professor)" marks evidence the Professor re-checked; "R" marks evidence that is a reading of the text, accepted on that reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | critical | **V (Professor):** the reviewer's probe (`alt_probe.py`) read. It takes the old store's entries to be "everything before d" by position, so a new-store entry y counts as an uncited maximal entry in some orders. | **Accepted for the C30 text:** "would remove the premise and keep merge-order independence" (R3 (c), T9-09, CON-0264) overclaimed. **Answered by the Principal's ruling (`7ecf5a024`), applied in the next commit as changed theory for C32.** The answer rests on one premise, the Professor's technical choice, *marked for review (v1.4 §5)*: **each entry records the store the kernel appended it to**. Store membership is then a recorded field, not a position. The omission is refused in the append step itself (K6), and D reads no position for designations. **Model check (scratchpad copy of v17, to be committed with the ruling):** over all 38 citation-respecting orders of the reviewer's case {r, gD, g, x1, x2, d, y}, one result: D, every value, the closed members, and the old store's maximal entries. 40 of 40 random records with a designation give one result over 6 orders each. **Limit (the Principal's):** entries that bypass the kernel are not seen by this check; they stay with the reference-list loss check (CON-0243). | next commit: §9.13.2, §9.17 R3 (c), CON-0264, T9-09; model v17 check 29 |
| W-02 | high | R (S0166 L1019–1038, L888–901; §9.17 R1) | Accepted. C7 restated to the weaker form: "requires a designation entry". The S0166 reading is marked [I]. R1's label is split: the Principal's words versus K2-d. | §8.24 C7, §8.24.1 item 3, title; §9.17 R1 |
| W-03 | high | R (§9.17 L159; §9.15 K2 cell) | **Accepted; resolved by the next commit.** The ruling replaces the reader-position premise, so no premise is left on readers. Check 29 (1) covers merge orders with designations, so check 18b's merge independence is no longer limited to records without designations. | next commit |
| W-04 | high | **V (Professor):** S0165 L26, L233–235, L392, L399, L703 read | Accepted. C4 restated; the open tension is recorded in §8.24.3. | §8.24 C4, §8.24.3 |
| W-05 | normal | R | Accepted. C1 rehomed to §1.14.2-1 and §1.14.3-2; CON-0239 kept as an analogy. | §8.24 C1, home table |
| W-06 | normal | **V (Professor):** S0168 L898–921, L966 | Accepted; evidence for T9-08 (f). | §8.24 C9 |
| W-07 | normal | **V (Professor):** CORPUS-ALLOWED row 1426 (S0165: 2026-08-14); S0166 L333, L665–675 | Accepted. Lineage joined with C30's line; S0165 dated by the allowed list. | §8.24 lineage |
| W-08 | normal | **V (Professor):** the CON-0264 cell | Accepted. Self-citation removed. **Correction of the C30 settlement, recorded here; the C30 record is not rewritten:** its W-01 row answered "citation down-closure" as if alone; the C30 reviewer had proposed "cite every maximal entry and membership the down-closure". | register CON-0264; this row |
| W-09 | normal | **V (Professor):** 337 identical lines | Accepted. | §8.24 C12 |
| W-10 | normal | R (S0167 L1412–1422) | Accepted; K2-r added. | §8.24 C8 |

**Coverage items the reviewer marked MISSING**, now recorded in §8.24:
- S0165: the Governance A/B/C tension, liveness with no START, and scoped qualification;
- S0166: the ADR change over time;
- S0167: knowledge object versus knowledge product, lifecycle drift, and governance in both editions (all to T0-03), and its event-sourcing evidence for §8.23 C2;
- S0168: role assignment versus authorization, assurance levels, a third admission test, and distributed stewardship.

**Claim verdicts (reviewer):**
- C2, C3, C5, C6, C8, C10, C11, C12: VALIDATED.
- C1, C4, C9: PROVISIONAL, then restated.
- C7: INVALID as stated, then restated in the weaker form, which the reviewer judges valid from R1, K2-d and K2-w.

## What this settlement establishes, and what it does not

- **Established (V):** the C30 settlement's alternative carried an unstated premise. The ruling's form, with a recorded store per entry and refusal at append, gives one result over every merge order in the cases tested.
- **Not established:**
  - that bypassing writes are handled; by the ruling, they are not seen by this check;
  - that the recorded-store premise holds for the estate's real record;
  - that check 29 covers more than the cases it builds. 29 (6) passes by construction, since the evaluator reads only adoptions.
- The model passing is not evidence that the rule is right for KnowledgeOS. It shows that the rule as stated has the properties checked.

## R3 (changed claims)

- **§8.24 C1, C4, C7, C9:** 13 inbound lines, all within §8.24 itself, its index row and the map. Each is updated in this settlement.
- **§9.17 R1, label only:** the meaning is unchanged, so no inbound line changes.
- **CON-0264:** the self-citation is removed; the meaning is unchanged.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.24 8/0; §9.17 7/0 |
| R1 | 241 rows with a section home, 1 flag (CON-0133, information; it predates this window) |
| R2 | no tier A, B or C term in §8.24 |

## Measures

| Measure | Value |
|---|---|
| Findings | 10: 1 critical, 3 high, 6 normal; 10 accepted. One (W-01) is a defect found after a review passed, in the C30 settlement |
| Of these, on the previous settlement (`68b90a77e`) | 3 (W-01, W-03, W-08); on §8.24, 7 |
| Professor, reading | 1 min 40 s by start/stop records (17:13:41Z–17:15:21Z); plus the two missed last lines, read at about 17:17Z |
| Reviewer W | step 1: 131.3 s; steps 2–3: 240.8 s (harness; cumulative 372.1 s) |
| Elapsed window time | first commit `e95dfeb0a` (17:20:56Z) to this settlement's commit (see the session log) |
