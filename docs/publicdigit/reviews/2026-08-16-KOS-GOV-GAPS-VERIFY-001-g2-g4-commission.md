# KOS-GOV-GAPS-VERIFY-001 — G-2 / G-4 Commission

## The outstanding half of the governance-gap verification

**Work item:** `KOS-GOV-GAPS-VERIFY-001` — **the same work item, a new assignment.** Not a new record.
**Assignment:** `S1-verification-gov-gaps-g2g4` (role `verification`), predecessor `S1-verification-governance-gaps`
**Grant:** `G-KOS-GOVGAPS-G2G4`
**Status:** grant AUTHORIZED · assignment REGISTERED · HANDOFF recorded · **START NOT performed**

---

## 1 · The human act

> **"authorize : G-2 / G-4"** — PO/ARB, 2026-08-16

This is the authorization act that `G-KOS-GOVGAPS-VERIFY-AMD2` said would be required:

> *"G-2 and G-4 REMAIN OUTSTANDING and require a separate assignment under a separate process. NO SUCH ASSIGNMENT IS CREATED BY THIS AMENDMENT — it needs a human authorization act."*

---

## 2 · Why a new assignment and not a new work item

`R8` requires a fresh `SessionAssignment` — the previous lane is `COMPLETED` and a role is immutable per assignment.

But **the work item is the governance-gap verification commission**, whose original grant covers all six gaps. **A second work item would fragment one commission across two records — the exact failure reconciled earlier today**, when two records were created for this same commission and one human START act ended up recorded in both.

**The precedent is deliberate and now twice applied:** Stage 2 of the contract-neutrality experiment was registered as a new assignment in its existing work item for the same reason.

---

## 3 · Scope

**INDEPENDENT READ-ONLY VERIFICATION of `G-2` and `G-4` only**, against the running mechanism.

| Gap | Claim to verify or refute |
|---|---|
| **G-2** | No process attribution — the record cannot establish which process performed an assignment. |
| **G-4** | `humanAct` authenticity is unverifiable, **and `recordedBy` is unvalidated at three of five gated transitions.** |

**Each gets its own verdict.** Classify every statement as **Observed / Declared / Inferred / Unknown**.

**Specifically required for G-4 — check the arithmetic.** The claim asserts *three of five*. Determine independently which transitions are gated, how many there are, and at how many `recordedBy` is unvalidated. **The report's own count is a claim under test, not a premise.**

**Specifically required for G-2 — relate, do not duplicate.** `KOS-GOV-ATTRIBUTION-001` already holds a delivered Architecture Decision Proposal on process attribution with `P-1`…`P-6` undecided. **Determine whether G-2 is the same question already under governance there.** If it is, say so — **do not open a parallel line of work on it.** Given today's duplication incident, this instruction is not a formality.

**Also report:** whether G-2 and G-4 are independent of each other, and whether either reduces to the other or to `G-1`. The earlier pass found `G-6` to be an instance of `G-1` rather than a separate gap.

---

## 4 · Method

Verify against `.claude/scripts/workflow-state.php`, `.claude/scripts/session-resolve.php`, the records under `.claude/runtime/workflow/`, and `tests/Unit/Platform/WorkflowEngine/`.

**Attempt falsification.** Try to show each claim **false**. Probe against throwaway records in a scratchpad directory via `--dir` — **never against a production record.**

---

## 5 · Read-only

Change nothing: no code, contract, fixture, workflow record, grant, assignment, or the report under review. **Write the report and nothing else.**

**Out of scope:** repair · redesign · remediation · new architecture · target architecture · EKS work · creating remediation work items · deciding any remedy · self-certification · closing this assignment · any change to `KOS-CONTRACT-NEUTRALITY-001` or `KOS-ARCH-BASELINE-001`.

**No architecture or implementation lane may be registered under this work item without a further human act.** **This is convention, not enforcement** — the role set is fixed at `init` and this record declares all four roles. Stated so the limit is known rather than assumed.

---

## 6 · Independence — the reason this assignment exists separately

**REQUIRED: a process OTHER THAN the one that performed the `G-1`/`G-3`/`G-5`/`G-6` pass.**

The PO/ARB's reason, verbatim:

> *"G-2 and G-4 are excluded from this verification because the current process previously produced measurements that materially underpin those claims."*

and:

> *"Do not treat the split into two verification processes as a weakening of the commission. It is an independence control."*

**Separation is DECLARED and NOT ATTESTABLE (`INV-ATTR-2`)** — compliance rests on where the PO/ARB starts the session. **That inability is `G-2` itself**, so this assignment is subject to the gap it verifies. The artifact **MUST disclose which process performed it**, and must state plainly that the disclosure is self-declared.

---

## 7 · Outcome

**Findings only.** The PO/ARB decides what they mean; Governance then records them. **The verifier does not promote its own findings into governance state, does not create remediation work, and does not close its own assignment.**

On completion of this assignment, `G-KOS-GOVGAPS-VERIFY` — which covers all six gaps — is discharged.

---

## 8 · Traceability

`2026-08-16-governance-topology-verification-report.md` §C · `G-KOS-GOVGAPS-VERIFY` (all six) · `AMD1` (read-only) · `AMD2` (the split, and the requirement for this act) · `2026-08-16-KOS-GOV-GAPS-VERIFY-001-verification-G1-G3-G5-G6.md` (`4ec22bc4`) · `2026-08-16-governance-gap-verification-record-reconciliation.md` · `KOS-GOV-ATTRIBUTION-001` (`P-1`…`P-6` open) · `R8` · `INV-ATTR-2`
