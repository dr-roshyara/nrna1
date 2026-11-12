# IR-R1C-01-R2: limited re-check of T-1 and T-4 (blueprint revision 4)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-12 |
| **Reviewer** | GPT-6 in ChatGPT (author of IR-R1C-01 and -R1) |
| **Subject (frozen)** | release-1 blueprint **revision 4**, `c08ebc3563db4d7da76d3182c42ffa405471cd869fdff2a42d3fa92fd8b87855` (file 03) |
| **Scope** | **Only:**<br>- **T-1:** the IR-R1C-01-R1 §3.4 corrections: G-Q1 (the common Governance query rule) and proofs T1-5, GQ-1 … GQ-3;<br>- **T-4:** the §6.3 corrections: contact slot, delivery-time resolution, `NoContact` / `Unavailable`, and proofs T4-2 … T4-5, the flow steps 9–11;<br>- **G-1d** (the implementation requirement and G1d-1) and **B-05** (the evidence file 06);<br>- new high-severity contradictions introduced by these edits.<br>**Nothing else** |
| **Already decided** | baseline v1.1 ratified (D-214); **T-2 and K-C1 accepted (D-215)**. Not in scope |
| **Status** | T-1 and T-4 proposed. No implementation authorised; no proof executed |

## Files

| # | File |
|---|---|
| 01 | this commission |
| 02 | your IR-R1C-01-R1 report |
| 03 / 04 / 05 | blueprint rev 4 · rev 3 (`983d9cfe…`) · unified diff rev 3 → 4 |
| 06 | **B-05 repository evidence** (raw `git grep` output, HEAD `a53ba8ea8`) |
| 07 | decision record (through D-215) |
| 08 | Governance thin slice (D-157): §6.2 AuthorityCheck (`Unknown` result) |
| 09 | Governance completion rev 5 (D-208): §3.2 GD-11, GD-12 |
| 10 | M-TDD-01 rev 4 (D-210): §4 K-4, K-7; §7 delivery protocol |
| 11 / 12 | `DecideAndAppend.php` · `AppendOutcome.php` |

## Questions

1. **G-Q1:**
   - Does it state one boundary for T-1, AuthorityCheck and K-7, with close-then-read?
   - Is each query's beyond-boundary result its **own accepted indeterminate result** (T-1 `Refused`; AuthorityCheck `Unknown`, with no `NotAuthorised` recorded; K-7 refused), with no change of business meaning?
   - Do T1-5 (NOWAIT busy, regression, concurrency) and GQ-1 … GQ-3 cover what -R1 §3.4 asked?
2. **T-4:**
   - Does the slot `(person, channel)` give the required replacement, addition and removal semantics?
   - Is no contact check made at the snapshot?
   - Is `NoContact` decided at delivery only, with its reason recorded?
   - Is `Unavailable` queued, never excluded?
   - Is no endpoint persisted?
   - Do T4-2 … T4-5 cover -R1 §6.3?
   - Is the `[UNR]` on several current contacts an honest gap rather than a defect?
3. **G-1d:** does the stated requirement (an explicit retryable outcome, fresh attempt, fail closed on exhaustion, no adjustment, idempotency) and G1d-1 match -R1 §8.5?
4. **B-05:** does file 06 evidence G-1c and G-3 as stated in rev 4 §6?
5. New high-severity contradictions from these edits only.

## Report

**Title:** "IR-R1C-01-R2 — Limited Re-check".
- Integrity: the manifest and the subject fingerprint.
- Answers to 1–5.
- **Verdicts: T-1, T-4 each READY FOR ARB ACCEPTANCE or NOT READY** (only with a specific defect and its minimum correction).

**Rules:** stopping rule as before; modify nothing; decide nothing; **stop after the report.**
