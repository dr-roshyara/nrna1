# 1ap: reconciliation. What makes a ruling in force: its issuer or its status?

| | |
|---|---|
| Frozen | `PREREG.md` + bundle (`58f202115`); rows R-70, R-81, R-86, R-89, R-95 |
| Result | A and the reviewer agree on every verdict |

## Verdicts

| Model | Verdict | Deciding evidence |
|---|---|---|
| **M_issuer** | **FALSIFIED** (non-circular) | R-81..R-85: the same issuer (ARB Chief). NOT-IN-FORCE while PREPARED (derived non-circularly) vs IN-FORCE after R-86 (**stated**: "THIS RULING IS GOVERNING"; "These become governing rulings effective immediately") |
| **M_adopter** | **FALSIFIED** | equal adopter value ("none") with opposite force (R-86 vs R-81..85 before R-86) |
| **M_status** | **UNDETERMINED** | consistent: PREPARED is never in force; ADOPTED is in force. No deciding pair. R-89's NOT-IN-FORCE was **circular** (derived from status) and recoded UNK (D1, D2) |
| **M_chain** | **does not survive by the frozen definition**, because M_status is UNDETERMINED (D6, a label). The **attribution condition is met**: the status change PREPARED → ADOPTED is performed by the Decision Authority (R-86) |

## Reconciled model impact
- **Authority does not determine force directly.** The same issuer's rulings go from not in force to in force through an **adoption act by another authority**.
- The chain *issuer prepares → DA adopts → status ADOPTED → in force* is **consistent and attributed**, but M_status is not established: there is no case of the same status with opposite force, and no independent status contrast outside the R-81..85 episode.
- For AUTHORIZE-IMPL (F-LOG-0160 D10): **a is not a direct guard.** It is at most the capability to change a ruling's status. The guard family should replace a by *ruling status*, as a model-relative candidate (single episode).
- Other: D4 (source interpretation): R-70's issuer is the human authority = the DA, not "ARB". D3 and D9: R-88 and R-95 force stay UNK.
- Sealed expectation: M_issuer FALSIFIED ✓; M_status SURVIVES ✗ (it is UNDETERMINED); "force mostly derived" ✓.
