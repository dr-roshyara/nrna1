# Review of comparator r12 (independent subagent, read-only; Claude family)

**Verdict: PASS_WITH_LIMITATIONS.**

## Findings
- **The change is minimal and correct.** K10.5 is the only check with a nested `{h, slot}` role, and the role added is the actual least witness.
- **Only one unit changed:** `R5/K10.5/witness`, DISAGREEMENT → AGREEMENT. All 2,296 units are otherwise identical.
- **Limitation 1: the slot is dropped.** A return with the right history but the wrong slot would still agree. TASK's single-history format carries no slot; this is disclosed.
- **Limitation 2: the "example" roles were accepted by anyof matching already before r12.** This weakness is pre-existing, not introduced by r12.
- **Correction to the analyst's wording.** NONLICENSING units *do* enter the licence condition: every scored unit must be AGREEMENT or NOT_APPLICABLE. A false NONLICENSING agreement could therefore help unblock the licence. The tier only means they never count as reproduction evidence.
- **Selftest Z1 was thin.** It was skipped silently if the fixture changed, and it had no negative test.

## Reconciliation (main analyst)
- The selftest is hardened: Z0 fails if the fixture loses its nested role, and Z2 is a negative control (a non-matching history must give DISAGREEMENT). Both pass.
- The slot-dropping and pre-existing example-role limitations are disclosed here.
- The NONLICENSING wording is corrected in F-LOG-0242.
