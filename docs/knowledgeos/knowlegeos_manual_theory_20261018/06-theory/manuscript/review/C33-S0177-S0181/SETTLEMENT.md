# Window C33: settlement

*2026-10-27. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`). Draft `f0fb39c29`; brief `caaea7f20`. Changed theory reviewed: `167e5a7b8` (the C32 settlement, model v18). Rulebook v1.4. Access: `ACCESS-NOTE.md`.*

"V (Professor)" marks evidence the Professor re-checked; "R" marks evidence that is a reading of the text, accepted on that reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | R (the probes as reported; G4's K6/K4 line in the book) | Accepted. Both statements are scoped to one serialised kernel. Across merged applications, a fork is detected (outside D), not prevented, and a concurrently accepted entry is classed foreign, the safe direction. Whether one coordination point per carrier is needed goes to T9-10 and the consolidation. **Not established:** the P1b case is not yet a model check | §9.17 R3 (c), §9.13.2, CON-0264, T9-10 |
| W-02 | high | R (`in_D`) | Accepted. The three D clauses are labelled as the Professor's additions. The consequence is stated: a bypass entry with an unknown store, or a bypass designation, takes the record out of D. Whether that is intended goes to T9-10 | §9.17 R3 (c), §9.13.2, T9-10 |
| W-03 | normal | R | Accepted: "maximal among themselves" in D; maximal in the store only at append | §9.17 R3 (c), §9.13.2 |
| W-04 | normal | R (mutants as reported) | Accepted. The chain-reach clause is stated as derived for guarded appends and as not exercised by check 30 | §9.17 R3 (c) |
| W-05 | normal | **V (Professor):** the decision record, line 428 | Accepted. The 2026-10-25 quote is labelled as the Principal's message, the record's wording is given, and "last entry" is marked superseded at both places. §8.19 says "replaces" | §9.17 R1, R3 (c); §8.19 |
| W-06 | high | **V (Professor):** index and allowed list (S0155 = `123902`; S0161 = `204018`; S0156 = `115444`; S0157, S0170, S0174); S0148 is a different file | Accepted. Lineage corrected. **Content resting on S0155 (exposed, never used) removed** from §8.25 C7 and §8.26 C1. The instance resting on S0161 removed. Nothing the summaries say about the six files is used. Recorded in `ACCESS-NOTE.md` and **reported to the Principal** | §8.25, §8.26, ACCESS-NOTE |
| W-07 | high | **V (Professor):** S0179 L115, L205–206 | Accepted. C3 restated: an artifact that is not the act's record; an act decides only what it names. DC-2 is recorded as a real contradiction | §8.26 C3 |
| W-08 | normal | R | Accepted. C1 scoped; S0177 L108 added; D1 removed under W-06 | §8.26 C1 |
| W-09 | normal | R (the reviewer read S0154) | Accepted. The caveat is dropped; C2 is now labelled as verified by the reviewer against S0154 | §8.26 C2 |
| W-10 | normal | **V (Professor):** S0180 L74–81 tagged PROPOSED | Accepted | §8.26 C5 |
| W-11 | normal | R | Accepted. Restated as one concept at two statuses (specified, not built); the third use is recorded | §8.26 C6 |

**Coverage items marked MISSING:** recorded only where they bear on theory:
- DC-2 (in C3);
- S0177 L108 (in C1).

Left as source defects, recorded in the review report and not in the book:
- the warn-only checker against "a warning script is monitoring" (this is §8.24 C11 / §8.22 C3 already);
- the three-way split;
- the grant counts;
- DRAFT;
- adapters;
- S0180's dangling pointer and attribution drift.

**R-CONFLICT** is not homed in the chapters. Noted for the consolidation, with T9-10.

**Claim verdicts (reviewer):**
- C1: VALIDATED for the kept instances; PROVISIONAL for the relayed principles.
- C2, C4: VALIDATED.
- C3: INVALID as stated, restated.
- C5, C6: PROVISIONAL, restated.
- The v18 completion: PROVISIONAL (sound within K6).

## What this settlement establishes, and what it does not

- **Established (V):**
  - the six files are not on the allowed list, by the index and the allowed list;
  - two book passages had used content resting on S0155, and both are removed;
  - checks 1–28 of v18 are unchanged (the reviewer's run).
- **Not established:**
  - that the designation rule holds across merged applications (T9-10);
  - whether the exposure through allowed summaries affects the hold-out test. **That is the Principal's question.**

## R3

- **§8.26 C1, C3, C5, C6:** no inbound lines outside §8.26, its index row and the map.
- **§8.25 C7:** inbound lines in §8.26 (the C1 agreement line), still true.
- **CON-0264 and §9.17 R3 (c):** the scope is added; the meaning is narrowed, not reversed; the inbound lines of the C32 R3 stay true.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.25 3/0; §8.26 0/0; §9.17 7/0; §9.13 10/0; §8.19 34/0 |

## Measures

| Measure | Value |
|---|---|
| Findings | 11: 0 critical, 4 high, 7 normal; all accepted |
| Of these, on the previous change (`167e5a7b8`) | 5 (W-01–W-05); on §8.26, 6 |
| Professor, reading | 59 s by start/stop records |
| Reviewer W | step 1: 119.1 s; steps 2–3: 1,057.8 s (harness; cumulative 1,176.9 s) |
| Elapsed window time | first commit `f0fb39c29` (17:46:58Z) to this settlement's commit (see the session log) |
