# Window C37: settlement

*2026-10-28. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`: `563aa333…6686`). Draft `3709dd4b6`; brief `690abfec3`. Changed theory reviewed: `2a532de22` (the C36 settlement). Rulebook v1.4. This is the last window before the S0200 consolidation.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on the reviewer's reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | **V (Professor):** §8.19 L42 ("23 of 30 trials"), CON-0245 | Accepted. C2 is re-homed to §8.19 C1 as one measurement relayed twice; §8.18 C4 (a) kept as the same mechanism at another site. *The step-8 challenge answer rested on §8.18 C4 (a) and is corrected here* | §8.30 C2; homes table |
| W-02 | high | **V (Professor):** register CON-0240 ("a migration is verified on bytes…") and CON-0241 ("a positional conflict…") | Accepted. The attack is restated: S0200's A holds status values; ΔK needs an interval scale; nothing complex-specific is used; the breach is of S0200's ordering rule, not of L394. The citation is corrected to CON-0241 and scoped to one record | §8.30 C5 |
| W-03 | high | R (git times as reported; S0198 L79 read) | Accepted. C4 is restated: S0199 strengthened F1 and agreed on F4; its independence is declared, not attested | §8.30 C4 |
| W-04 | normal | R | Accepted. C1 agrees with v1.4 §3 without testing it (n = 1); the CON-0120 extension is marked Int; S0199's narrowing is a non sequitur | §8.30 C1 |
| W-05 | normal | R | Accepted. CON-0132 added; the "own" homonym recorded | §8.30 C3 |
| W-06 | normal | **V (Professor):** §8.19 L74 | Accepted. Pointer added; the surplus is one at every measurement | §8.30.3 |
| W-07 | normal | **V (Professor):** S0300 L1203 is the transitions row | Accepted. The line is corrected and S0300 labelled as read at turn 8. The relay is marked as checked by the reviewer. §8.18 C12 added | §8.30 lineage, C2, §8.30.3 |
| W-08 | normal | R | Accepted. Changes: the §8.29 C5 header; "claimed orthogonal (unshown)" in the map; "in the window" restored in the map and index | §8.29; map; index |
| W-09 | normal | R | Accepted. §8.29 C6 restated under CON-0162 | §8.29 C6 |
| W-10 | normal | R (git subjects as reported) | Accepted at subject strength. `61219780e` is not read | §8.30 C4 |
| W-11 | normal | R | Accepted. One line each: UNKNOWN from not reading; the corpus-boundary must-do; S0200's framings to T0-03 and T0-03b; the falsifier to §1.4; the file name to §8.29 C1 | §8.30 C1, C5, §8.30.3 |
| W-12 | normal | **V (Professor)** | Accepted. The header now names C36 | §8.30 |

**Claim verdicts (reviewer):**

| Claim | Verdict |
|---|---|
| C1, C3 | VALIDATED within scope |
| C2, C4, C5 | PROVISIONAL; their sub-claims INVALID as stated, restated |
| C36 rows; §8.29 C5 restatement; the single-commit fact | match / VALIDATED |
| §3.2 applied to M4 | PROVISIONAL, restated (W-09) |

## What this settlement establishes, and what it does not

- **Established (V):**
  - the 23-of-30 measurement is already in the book (§8.19 C1);
  - CON-0241, not CON-0240, is the positional-conflict concept;
  - the grant-identifier surplus is one at each measurement.
- **Not established:**
  - whether a structured skim would have sufficed in the P3 case (it is argued, not tested);
  - the content of the later re-review ruling;
  - any magnitude for S0200's axes.

## R3

§8.30's claims have no inbound lines outside §8.30, its index row and the map. §8.29 C5's header now matches its body.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.30 2/0; §8.29 4/0 |

## Measures

| Measure | Value |
|---|---|
| Findings | 12: 0 critical, 3 high, 9 normal; all accepted |
| On the previous change (`2a532de22`) | 2 (W-08, W-09); on §8.30, 10 |
| Professor, reading | 49 s by start/stop records (bookkeeping; understates) |
| Reviewer W | step 1: 99.2 s; steps 2–3: 465.4 s (harness) |
| Elapsed window time | from the first commit `3709dd4b6` (2026-10-28T02:52:29Z) to this settlement's commit (see the session log) |
