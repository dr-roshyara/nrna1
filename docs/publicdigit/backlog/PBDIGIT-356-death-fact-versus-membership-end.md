# PBDIGIT-356: the death of a person versus a Membership End: ownership and naming

**Status:** Open (found in PBDIGIT-354 S2-4d-Q5, 2026-11-15; not in that story's scope).
**Source:** D-240, its death-fact distinction.

## Finding

- **D-240 distinguishes two different things:**
  - recording a person's **death as a fact about the person**;
  - recording a **Membership End because of death**.
- **In the code today only the second exists.**
  - `RecordDeath` / `RecordDeathHandler` (S2-3b) records `End(death)` with an evidence reference in the Membership ledger.
  - No operation anywhere records a person's death as a person fact. Searched `app/Contexts`; the only hit is an unrelated Election vacancy ground.
- **The command name `RecordDeath` suggests the person fact,** which is exactly the conflation D-240 warns against.

## What is needed (to be modelled before any code)

1. **Ownership of the death fact.** Which context records a person's death: Organisation's person register (D-129 area), or Identity? Decide that first; Membership does not own it.
2. **Naming.** Consider renaming the Membership command to a name that says Membership End (e.g. "end membership because of death"), keeping `record-death` as the authority act (D-208 §2), or record why the name stays. A rename is a separate, reviewed change.
3. **How the two relate.** Does the person fact trigger, or only inform, a Membership End? Under D-240 the End must still respect a pending scheduled resignation.

## Not in scope of PBDIGIT-354

D-240 is implemented on the Membership End (`End(death)`), which is the only death-related operation that exists.
