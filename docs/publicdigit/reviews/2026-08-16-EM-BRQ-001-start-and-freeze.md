# Registration — `EM-BRQ-001` STARTED (gate ④) · CORPUS FROZEN · one Governance premise error corrected

**Type:** Governance registration + execution record (Session 2) · **Date:** 2026-08-16

## 1 · The act, verbatim

> **"I START EM-BRQ-001 on the Governance performing lane."** — PO/ARB, 2026-08-16

All four gates are now passed: exception ① · assignment ② · grant `G-EM-BRQ-QUAL` ③ · START ④.

## 2 · THE FROZEN CORPUS

| | |
|---|---|
| **Repository state** | commit `affddca67f6d85698e75799f04455e77e5249fe5` |
| **Rule corpus** | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` · sha256 `5172d3e2043416c57b1d3f07d84d8fe1972a34d41f2d1104de428d9f1b9110b4` |
| **What counts as a rule** | rows marked **ADOPTED** in the frozen file, including `EM-VOC-*`, `EM-VOT-*`, `P-2H`, `SCB` ruled parts, and `EM-GOV-063` |
| **What does NOT** | PENDING rows (e.g. `EM-GOV-015`, `EM-GOV-037`) · PROPOSED frameworks (`C-1`/`C-2`/`C-3`) · all `EM-OPEN` rows |

**The first pass and the future Verification pass both run against exactly this hash.** *(The execution artifacts committed after this point do not modify the Manifesto; if any registration later must touch it, the pass still cites the frozen hash.)*

## 3 · ⚠️ CORRECTION registered before execution — a Governance premise error found while freezing

**In `2026-08-16-halt-classification-proposal.md` §0.2 I asserted: *"No such condition exists in the Manifesto — there is no minimum-candidate rule anywhere in the adopted set."* THAT WAS WRONG.**

> **`EM-VOT-002` (ADOPTED, SD-14, 2026-08-13): *"An election must have at least one approved candidate before voting may be opened."* `EM-VOT-003` adds the admitted-voter floor. Both evaluated when the Chief requests progression (`EM-VOT-004`).**

**Cause:** my search used *"at least one candidate"*; the rule says *"at least one **approved** candidate."* **Consequence:** the PO's Example B was correctly premised all along — a zero-candidate election IS halted by an adopted rule, and the example is a genuine unsatisfiable-under-the-rules case rather than a hypothetical. **The classification model itself is unaffected** (it never depended on the example's status). Corrections applied: dated annotation in the proposal artifact · CONTEXT row corrected · this registration. **Found by the freeze discipline itself — enumerating the corpus before simulating against it.**

## 4 · Execution order from here

**① this registration → ② scenario suite constructed and FROZEN (own commit; contains NO outcomes, so the Verification pass stays blind) → ③ Governance first pass (separate artifact, separate commit).**

**Traceability.** Gates ①–④ registrations · `G-EM-BRQ-QUAL` · assignment · A-3.
