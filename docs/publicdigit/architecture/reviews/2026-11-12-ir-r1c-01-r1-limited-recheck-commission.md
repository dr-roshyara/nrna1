# IR-R1C-01-R1: limited re-check of the IR-R1C-01 corrections (blueprint revision 3)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-12 |
| **Reviewer** | GPT-6 in ChatGPT (author of IR-R1C-01) |
| **Subject (frozen)** | release-1 blueprint **revision 3**, `983d9cfea15fb174588ead482a069811b46c211f92e694e7a163a42e220db70f` (file 03) |
| **Scope** | **Only:**<br>- whether revision 3 resolves **A-01 … A-06** of IR-R1C-01;<br>- whether the added proofs (KC1-2, T1-5 … T1-7, T2-5, T4-2, T4-3) cover B-01 … B-04;<br>- whether the B-05 verifications (G-1c, G-1d, G-3) are correctly stated;<br>- **new high-severity contradictions introduced by these edits.**<br>**Nothing else** |
| **Already decided** | **The strategic baseline v1.1 rev 4 is RATIFIED (D-214).** Not in scope |
| **Status** | proposed; T-1, T-2, T-4 and K-C1 await ARB acceptance. No implementation authorised; no proof executed |

## Files

| # | File |
|---|---|
| 01 | this commission |
| 02 | your IR-R1C-01 report |
| 03 / 04 / 05 | blueprint rev 3 · rev 2 (`ca5fad2b…`) · unified diff rev 2 → 3 |
| 06 | decision record (through D-214) |
| 07 | Governance completion rev 5 (D-208): **§3.2 GD-11 / GD-12**, the DecisionLog the new boundary extends |
| 08 | Governance thin slice (D-157): **§6.1, §6.2 AuthorityCheck "as known at τ"** |
| 09 | ledger-clock amendment rev 2 (D-209): the pattern A-01 follows |
| 10 | governance model file with **Organisation §49.8** (the same pattern for Organisation) |
| 11 | CR-01 (the meaning of `closedThrough`, line 28) |
| 12 | M-TDD-01 rev 4 (D-210): **§4 K-4; §7 delivery protocol items 3, 5–9** |
| 13 / 14 | code evidence for G-1d: `DecideAndAppend.php` · `AppendOutcome.php` |

## Questions

1. **A-01:**
   - Is the proposed Governance boundary (`closedThrough` on the log head; one added refusal condition on recording; a NOWAIT closing step with a regression guard; refusal beyond it) sound?
   - Does it **extend GD-12 by addition** without changing D-208's meaning?
   - Is the noted consequence correct, that **AuthorityCheck and K-7 "as known at τ" need the same boundary**? Is that a change to an accepted contract or only its missing mechanism?
2. **A-02:** `validTenures` only?
3. **A-03:** is the date binding to K-4 stated without changing K-4?
4. **A-04, A-05:**
   - Do `DeliveryContactRefsAsAt` (snapshot) and `ResolveDeliveryContact` (delivery) match D-210 §7 items 3 and 9?
   - Are the `NoContact` reasons limited to profile states?
   - Is `Unavailable` (stays queued, retried) consistent with items 5–6?
5. **A-06:** is the cadence labelled correctly?
6. **Proofs and B-05:** are KC1-2, T1-5 … T1-7, T2-5, T4-2 and T4-3 adequate? Are the G-1c, G-1d and G-3 statements supported by the evidence given?
7. **New high-severity contradictions** introduced by these edits only.

## Report

**Title:** "IR-R1C-01-R1 — Limited Re-check".
- Integrity: verify the manifest and the subject fingerprint.
- Answers to 1–7.
- **Verdicts:** T-1, T-2, T-4, K-C1 each **READY FOR ARB ACCEPTANCE** or **NOT READY** (only with a specific defect and its minimum correction).

**Rules:** stopping rule as IR-R1C-01; modify nothing; decide nothing; **stop after the report.**
