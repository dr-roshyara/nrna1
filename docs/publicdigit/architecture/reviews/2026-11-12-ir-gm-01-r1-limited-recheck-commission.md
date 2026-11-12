# IR-GM-01-R1: limited re-check of the IR-GM-01 corrections

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-12 |
| **Reviewer** | GPT-6 in ChatGPT (author of IR-GM-01) |
| **Subjects (frozen)** | **S1** Governance completion **rev 5** `04e41258a24bd1af1d4d901be32b121604dc476129f225440ada4e5f3c96b9e4` · **S3** ledger-clock amendment **rev 2** `35739576bb2b8c7087233f24032805bf489f217242703bcd2ad5af6c249ef6d8` · **S2** M-TDD-01 **rev 4** `b4aba1687e3cfcbc3171bd731ef1248b01ce2b9d25400431fdb80659b790c137` |
| **Scope** | **Only** whether the corrections resolve **A-01** and **A-02** of IR-GM-01, whether the proof edits (GC-8, GC-17, LC-6, MI-11, MI-14) match the requested coverage, and whether the edits introduced any new **high-severity** contradiction. **Nothing else** |
| **Status** | proposed; not accepted; no implementation authorised; no proof executed |

## Files

| # | File |
|---|---|
| 01 | this commission |
| 02 | IR-GM-01 report (yours) |
| 03 / 04 / 05 | S1 rev 5 · S3 rev 2 · S2 rev 4 |
| 06 / 07 / 08 | previous versions: S1 rev 4 (`7903c028…`) · S3 rev 1 (`2e8cd2bc…`) · S2 rev 3 (`b0331276…`) |
| 09 / 10 / 11 | unified diffs (previous → current) for S1, S3, S2 |

## Questions

1. **A-01:** are the two Annul routes now separate? Is the tribunal remedy a separate applied fact, with no Resolution and no competence evaluation? Is RA §9 provenance preserved for Membership's Annul in both routes?
2. **A-02:** does the `closedThrough` path now refuse to advance when `t < last_recorded_at` (and `t < closed_through`), allow equality, and leave the boundary unchanged?
3. **Proof edits:** do GC-8, GC-17, LC-6, MI-11 and MI-14 cover what IR-GM-01 asked for?
4. **New contradictions** introduced by these edits only.

## Report

**Title:** "IR-GM-01-R1 — Limited Re-check".
- **Integrity:** verify `MANIFEST.sha256` and the three fingerprints.
- **Answers to 1–4.**
- **One verdict per subject:** READY FOR PRINCIPAL CONSIDERATION · NOT READY (only with a specific defect and its minimum correction).

**Stopping rule as in IR-GM-01.**
- Modify nothing.
- Decide nothing for the Principal.
- **Stop after the report.**
