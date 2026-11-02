# Window C76 (S0318–S0320): settlement

*2026-11-02. The Professor. Object: §8.69 draft (`e5ceead69`). Reviewer W (Opus): step-1 inventory with sha256 (in `e5ceead69`); report `37b96b25b` (`SHA256-REVIEW.txt`). 12 findings (6 high, 6 normal), none critical. All accepted. Result: §8.69 v2 (filename kept; title restated). The write of v2 was confirmed before this commit.*

## Theory first

**Held:**
- the facts of C1–C4 (the three maps, the empty record, the unregistered creating act, the placeholder and the blocked REGISTER);
- C5 as agreements within one lineage (VALIDATED).

**Corrected:**
- **C1 (W-01).** The rulings follow their registrars: one line adopts S0315's self-declared map, the other repeats S0316's. Nothing bears on the provenance of S0315's writing act. **The draft's R3 on §8.67 C1 is withdrawn, and §8.67 C1 is unchanged.**
- **C2 (W-03, W-04, W-05, W-11).**
  - Not a cycle: an obligation whose only means requires a reserved decision, which is a deadlock between gates, dissolved by the holder of the reservation.
  - The ruling is a root act (§1.14.1 L161, L164). T1-17 (genesis, an evidence prerequisite) is not used.
  - The pointer is corrected to §8.62 C4 (W-15).
  - "No lane" is stated only for `b51dba91`. The creating act is a third case.
- **C3 (W-02).** T9-20 declined evidence as *necessary* for standing. S0319 refuses evidence as *sufficient* for a role. (c2), which T9-20 kept, excludes a report as a basis.
- **C4 (W-06 to W-08, W-12).**
  - "Verified" is better read as the existing MATCH check. Under that reading, verification can only follow the REGISTER it is meant to unblock: a second ordering gap.
  - The grant cannot be registered without a holder.
  - The citation is corrected to S0316 L23.
  - Bars: role or session is not said.
- **C5 (W-09, W-10).** Analogies are marked. The ruling presupposes the store as the record, which bears on §8.67 C5's antecedent.

**New open point (recorded, not routed):** whether "verified" in S0320 L34 can precede REGISTER (C4).

## Dispositions

| W | Disposition | Where |
|---|---|---|
| W-01 | accepted; proposed R3 withdrawn | §8.69 PL1, C1; index; STEP8 |
| W-02 | accepted | §8.69 PL3, C3 |
| W-03 | accepted | §8.69 PL2, C2; STEP8 |
| W-04 | accepted; title restated | §8.69 title, PL2, C2; index |
| W-05 | accepted | §8.69 C2; STEP8 |
| W-06 | accepted | §8.69 PL4, C4; index |
| W-07 | accepted | §8.69 C4 |
| W-08 | accepted | §8.69 C4 |
| W-09 | accepted | §8.69 PL5, C5 |
| W-10 | accepted | §8.69 C5 |
| W-11 | accepted | §8.69 C2 |
| W-12 | accepted | §8.69 C4; index |

**Coverage items marked MISSING by W** are added to §8.69 C5 and §8.69.3:
- governed ≠ adopted;
- the rulings ratify §8.67 C2–C3;
- one canonical name;
- no second engine;
- V-8 closed to reopening;
- durability unmet, with a disputed actor;
- the second ordering gap.

## R3

No change to §8.67 C1 (the draft proposal is withdrawn). §8.69 is not cited elsewhere. No register change.

## Process

**Effort (phase.py; waiting apart):**

| Category | Time (UTC) |
|---|---|
| Reading | 01:48:56–01:49:49 |
| Drafting | 01:49:49–01:52:20 |
| Waiting | 01:52:20–01:59:00 |
| Rework | 01:59:00 to this file |
| Settlement | logged at commit |
