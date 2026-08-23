# `REPAIR-001` — implementer **candidate-declaration-only** prompt

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role considered:** `implementation` (fresh) · **Subject:** `AST-019`
**Document type:** declaration-only kickoff prompt. **⚠️ This is NOT the repair commission.** The repair commission is issued only *after* appointment and human `START`.
**Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; NOT the appointee — barred from implementing the slice it scoped)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **Why this prompt is deliberately thin.** `84c0f6f6` was handed the *full commission* at first start and, following it in order, read ~120 lines of the subject before declaring — recorded as its disclosed §4 ordering deviation, and traced to **prompt selection, not process error**. This prompt exists so that cannot recur. **Step 0 is first for that reason.**

---

## Candidate declaration — `REPAIR-001` implementer

You are a **candidate** implementation actor. You are **not appointed**, **not registered**, and **not authorized**.

**0. Declare before you orient.** Before reading any source file, any test, any report, or any workflow record, do step 1 and step 2. **Do not read `activate-commissioned-fresh-session.php` or its contract test yet.** If you have already read either, **say so in your declaration** — disclosure costs nothing; a concealed deviation costs the appointment.

1. Determine your process identity from `CLAUDE_CODE_SESSION_ID` — from the runtime **only**. Never from this prompt, never a CLI argument, never copied from a document.
2. Confirm you are **not**:
   - `84c0f6f6-795e-4c89-a382-733f2c7b7caf` — the independent verifier. **It must not repair what it verified** (`R-34`/`EP-02`).
   - `5928b9f9-b4d5-46e9-8c71-c295dace18f8` — the Governance process that scoped this slice.
   - PO/ARB.

   *Note, stated so you do not over-bar yourself:* `1899d8bf-2688-4bf3-9787-b4114ddaeec8` — the original `AST-019` producer — **is not barred from implementing.** Repairing one's own code is ordinary engineering. If you are that process, **say so plainly**; it does not disqualify you here, and it *does* bar you from the later re-verification.
3. Read **only** the authoritative work-item state:
   `php .claude/scripts/workflow-state.php fold KOS-OPERATING-MODEL-001-AMENDMENT-001`
   and your own bootstrap:
   `php .claude/scripts/session-bootstrap.php --work-item=KOS-OPERATING-MODEL-001-AMENDMENT-001 --process-label=<your bare session id> --role=implementation`
   *(the label is the **bare** id — a `claude-code-session:` prefix never matches.)*
4. **Do not** create a workflow record · **do not** `REGISTER` yourself · **do not** `HANDOFF` · **do not** `START` · **do not** `CONTINUATION`.
5. **Do not** modify any file — not `AST-019`, not its tests, not the adopted layers, not the workflow record.
6. **Do not** invoke `AST-019` (`activate` or `check`) against a real work item.
7. **Do not** begin the repair. Authorization to repair is not granted by this prompt and cannot be inferred from it.

**What you should know about the state you are joining** (facts, so you are not surprised — not an invitation to act):
the work item is `OPEN` with an `AUTHORIZED` grant `G-REPAIR-001`; the only registered lane is the verifier's, `ACTIVE` **only** as a mechanical consequence of the continuation that reopened the item, so `AST-018 next-actor` will tell you *"no new actor is needed"* — **that advice is wrong in this state** (observation `O-4`). Your appointment is the intended next act.

Return exactly:

```
PROCESS IDENTITY:
INDEPENDENCE:            (against the bars in step 2, with any prior reading disclosed)
PRIOR PARTICIPATION:     (in full — this work item, AST-019, anywhere in this estate)
ELIGIBILITY:             ELIGIBLE / NOT ELIGIBLE
WORK ITEM STATE:         (as the fold reports it)
CURRENT AUTHORIZATION:   NOT AUTHORIZED
```

Then **STOP.** Governance appoints your declared identity via `AST-018 appoint`, a person records the `START`, an `EP-01` plan is approved — and only then does the repair commission reach you.

---

**Traceability:** grant `G-REPAIR-001` (`AUTHORIZED`) · PO/ARB act 2026-08-23 · `…-AMENDMENT-001-REPAIR-001-AUTHORIZATION.md` · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL) · `…-AMENDMENT-001-FAIL-DISPOSITION-AND-REPAIR-PATH.md` · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (`ASD-001`) · `84c0f6f6` declaration §4 (the deviation this prompt prevents) · `INV-ATTR-1/2` · `G-3` · `R-34`/`EP-02` · `ES-006.1`
