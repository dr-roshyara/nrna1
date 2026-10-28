# Window C36: settlement

*2026-10-28. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`: `8cae0ca1…2fa6`). Draft `5f6f2a6a8`; brief `b5aaefb80`. Changed theory reviewed: `028a24599` (the C35 settlement). Rulebook v1.4.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on the reviewer's reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | R (S0196 L349 and L548 match the Professor's reading notes) | Accepted. C4 now reads "in the PKS corpus (whether PKS is a second system is open)". Evidence items (a)–(d) are routed to T0-03b | §8.29 C4, §8.29.3; T0-03b |
| W-02 | high | **V (Professor):** S0193 L20, L33 | Accepted. C1 is qualified: independent only as far as a search shows; declared, not attested | §8.29 C1 |
| W-03 | high | **V (Professor):** S0192 L13 (same process as S0190), L118 | Accepted. Restated as one line with S0190 L174, pointing to §8.28 C3 | §8.29.3 |
| W-04 | high | R | Accepted. C5 is now an analogy to §9.17, which detects such writes and does not close them. Re-homed to §1.5 and §1.9. *The step-8 challenge record's "closes structurally" is wrong; corrected here, and the record is not rewritten* | §8.29 C5; homes table |
| W-05 | normal | R (the reviewer read `db9154664` in git; the same allowed path at an earlier commit, recorded as an access point) | Accepted. The block-counting explanation is attributed to the erratum, and S0195 is noted as conditional. One measurement, not two | §8.29 C3 |
| W-06 | high | **V (Professor):** `git show --stat 999ede26b` (2026-08-21 22:46, the review plus the S0196 erratum, "P3 gate OPEN"); `db9154664` 22:37 | Accepted. Recorded as fact: no separate disposition record exists in the window. An instance for §8.28 C5 and §8.26 C3 | §8.29 C3 |
| W-07 | normal | R | Accepted. The scope loss is placed inside S0194 (L42 → L122), and the self-correction worked. A second instance added (X-01 resting on a four-document grep) | §8.29 C2 |
| W-08 | normal | R (§3.2 table read at the C37 draft) | Accepted. §3.2's test is applied: a conflict with the rejected INTENT-STATUS axis, and agreement with CON-0003. The repository label is dropped | §8.29 C6 |
| W-09 | normal | R | Accepted. §8.25 C7 is the primary home. Both of S0193's sentences are quoted | §8.29 C1 |
| W-10 | normal | R | Accepted. One line each at the named homes | §8.29.3 |
| W-11 | normal | **V (Professor):** the four sites | Accepted. "T0-03b" in §8.28 C1; "the owner is constant" in the map; (c′)'s "prevented" qualified; the §8.29 header names C35 | §8.28; map; §9.17 (c′); §8.29 |

**Claim verdicts (reviewer):**

| Claim | Verdict |
|---|---|
| C1 | VALIDATED within scope |
| C2 | VALIDATED |
| C7 | VALIDATED within scope |
| C3, C6 | PROVISIONAL, restated |
| C4, C5 | PROVISIONAL; their sub-claims INVALID as stated, restated |
| C35 settlement rows; v20 limits; §8.28 C8 | match / VALIDATED |
| C35 W-04, the reasoned exclusion | adequate as a deferral, now that T0-03b is fed |

## What this settlement establishes, and what it does not

- **Established (V):**
  - S0195 and S0196's erratum landed in one commit, eight minutes after the baseline;
  - S0192 and S0190 are by one process;
  - S0193 calls its identity not attestable.
- **Not established:**
  - whether PKS and EKS are one system (T0-03b);
  - whether M4's axes are orthogonal;
  - why the baseline counted 1/1/1.

## R3

§8.29's inbound lines (§8.30 L17, L20, L64; the map) cite C1's refusal and C3's "checkable is not checked". Both stay true.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.29 4/0; §8.28 9/0; §9.17 7/0 |

## Measures

| Measure | Value |
|---|---|
| Findings | 11: 0 critical, 6 high, 5 normal; all accepted |
| On the previous change (`028a24599`) | 1 (W-11 (a)–(c)); on §8.29, 10 |
| Professor, reading | 02:34:50Z–02:36:01Z by start/stop records (bookkeeping; understates) |
| Reviewer W | step 1: 116.5 s; steps 2–3: 414.6 s (harness) |
| Elapsed window time | from the first commit `5f6f2a6a8` to this settlement's commit (see the session log) |
