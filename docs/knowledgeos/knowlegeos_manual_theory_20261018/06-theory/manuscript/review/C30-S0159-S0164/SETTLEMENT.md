# Window C30: settlement

*2026-10-25. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`). Draft `7d5066676`; brief `02559b76c`. Rulebook v1.4.*

**Verified, refuted or reported.** "V (Professor)" means the Professor re-checked the evidence. "R" means the evidence is a reading of the text, and the Professor accepts it on that reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | critical | **V (Professor):** the reviewer's probe P1 was rerun against `ce_model.py` v16. The same entries in two merge orders give `in_D` (True, None) and (False, "…not the last entry before it…"). The rule reads `rec.pos[closes] == k-1` (model L208–210). | **Accepted, partly applied.** The premise is stated: "last" is read in the old carrier's own append order (K6), which a re-merge must keep. The Principal's words are kept apart from the Professor's application. **Not accepted:** the reviewer's citation down-closure as the **membership** rule in place of the prefix. That loses concurrent entries the kernel accepted into the old carrier (x2 in P1), against CON-0264 (membership by acceptance). Citing **every** maximal entry keeps them, and is recorded as the alternative. **The choice between the stated premise and that alternative is the Principal's (T9-09 (a)).** | §9.13.2; §9.17 R3 (c); CON-0264; T9-09 |
| W-02 | high | **V (Professor):** P2 and P4 rerun: in_D True, value F. | Accepted. The effect of an F or U designation is recorded as **not ruled**; normative, for the Principal (T9-09 (b)). The precedent is a removal (refused unless T, v9). | §9.13.2; §9.17 R3 (c); T9-09 |
| W-03 | high | R (§9.15 K1; CON-0265) | Accepted. The citation fixes the boundary, not the members. The closing list is CON-0265's reference, not "the same information". | §9.17 R3 (c); CON-0264; §8.19 L236 |
| W-04 | high | R (§9.17 R1 scope) | **Accepted in part.** Following designation entries from the root **is** a resolution in R1's own sense ("a predicate that may also read the location is a resolution"), so it does not contradict R1 once the root is named as the outside reference. The text is scoped to say so. §8.23 C5 now says "compatible with", not "the same claim". | §8.22 C2; §8.23 C5; CON-0261 history |
| W-05 | normal | **V (Professor):** check 28's cases read in `ce_model.py` | Accepted; **scope stated**, no checks added. Model checks for merge orders and for F/U designations wait for T9-09's rulings, since they would test a rule not yet chosen. | §9.17 R3 (c) |
| W-06 | normal | R (`git show 57785a947`) | Accepted. Labels split: the Principal's words; the Professor's application (Ded). | §9.13.2; §9.17 R3 (c); CON-0264 |
| W-07 | normal | **V (Professor):** §8.19 L236 and the K2-w lines read | Accepted. | §8.19 L236; §9.17 K2-w; §9.15 K2 cell |
| W-08 | high | R (S0160 L59–63; §9.17 R1, R2; CON-0240) | Accepted. C4 restated: INVALID as stated. | §8.23 C4 |
| W-09 | high | **V (Professor):** S0163 L766–1241 == S0150 L17–492 (True); S0163 L656–765 vs S0150 L493–603, 109 of 110 lines; S0150 L490 (proposal) before L571 (deferral) | Accepted. C10 restated: INVALID as stated. | §8.23 C10 |
| W-10 | high | **V (Professor):** S0164 L1293, L1303, L1632 (AMD1 quoted), L1694 read | Accepted. C12 restated: the scope was ruled, and the open choice was returned undecided; AMD1 added. | §8.23 C12; T8-04 |
| W-11 | normal | **V (Professor):** §8.15 C4–C5 and W-1; map L278–280 | Accepted. | §8.23 C11; THEORY-MAP |
| W-12 | normal | R (S0159 L52–61, L338–386) | Accepted. | §8.23 C2 |
| W-13 | normal | **V (Professor):** S0159 L462 ("maintain an idempotency boundary") | Accepted. C3 restated: INVALID as stated. | §8.23 C3 |
| W-14 | normal | **V (Professor):** S0162 L56 (Delivery: "Own resolution, authorization, and delivery contracts") | Accepted, recorded only. | §8.23.3 |

**The reviewer's verdicts on §8.23 (no new concept):** C1, C6, C7, C9 VALIDATED. C2, C5, C8, C11, C12 PROVISIONAL. C3, C4, C10 INVALID as stated, and restated. On C6 the Professor's reading holds: "freeze" is used in two senses.

## What this settlement establishes, and what it does not

- **Established (V):** the turn-8 rule as committed in `57785a947` depends on recording position. Its text claimed order by citation. The text now states the premise it actually uses.
- **Not established:** that the stated premise is the right rule. The citation-based alternative has not been modelled. Check 28 still tests only three cases. No model check covers two merge orders with designations, or an F/U designation. **The model passing is not evidence that the rule is right.**
- **Not established:** that every reader of the estate's record keeps a designation's position. That is a premise, not a result.

## R3 (changed claims; every line dispositioned)

- **CON-0264 (13 concept-level lines):**
  - 08.19 L236: updated (W-07).
  - 08.23 L21, L63: cite R1 and CON-0264 for "carrier"; unaffected by the order premise. Kept.
  - 09.00 L27: the 9.17 row names R1 only. Kept.
  - 09.17 L128: tag. Kept.
  - 09.17 L160: restated in this settlement.
  - 09.17 L268: CON-0262's history. Kept, history is not rewritten.
  - 09.99 L66: history. Kept.
  - 09.99 L69: this settlement's row.
  - TODO T9-09: new.
  - Register 263 (CON-0262): unaffected. Register 265: this settlement.
  - Map L363: quotes the Principal's words, which are unchanged. Kept; the premise is in the C30 map line.
- **§8.22 C2 (3 claim-level lines):** 08.23 L69 (restated, W-04); the 08.99 row; register 262 (history added). The 19 section-only lines cite other claims of §8.22. Kept.
- **§9.17 R3:** no claim-level or concept-level inbound lines.
- **§8.23 itself:** two stale table rows in its home table (C4, C12) found by R3 and corrected.

## Checks after settlement

See `STEP8-AND-CHECKS.md` for the draft's checks. After settlement: quote check, §8.23 14/0, §8.22 11/0 (unchanged against HEAD), §9.17 7/0, §9.13 10/0, §8.19 34/0. R1: 241 rows with a section home, 1 flag (CON-0133 H4, information; it predates this window). R2: no tier A, B or C term in §8.23; 14 tier-D lines, all ordinary words.

## Measures

| Measure | Value |
|---|---|
| Findings | 14: 1 critical, 7 high, 6 normal. 13 accepted, 1 accepted in part (W-04) |
| Of these, on the previous turn's change (`57785a947`) | 7 (W-01–W-07); on §8.23, 7 (W-08–W-14) |
| Reviewer W, step 2–3 | about 315 s (harness; cumulative 453.1 s minus step 1's 138.6 s), 20 tool uses |
| Professor | settlement from about 02:06Z (report received) to 02:20Z. The Professor's harness time is not available to the Professor |

## For the Principal (normative)

1. **T9-09 (a):** which order fixes the old carrier's "last entry"? Either the carrier's own append order (as applied, with the premise that re-merges keep it), or citation of every maximal entry (merge-independent)?
2. **T9-09 (b):** does an F or U designation change the carrier, or is it refused at append, like an unauthorized removal?
3. **T8-04:** may the incumbent serve as the specification for choices the specification leaves open?
