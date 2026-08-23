# `REPAIR-001` — re-verifier **candidate-declaration-only** prompt

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role considered:** `verification` (fresh, independent) · **Subject:** `AST-019` after repair `d8a5ee93`
**⚠️ This is NOT the verification commission.** The commission is issued only after appointment. Do not begin verifying.
**Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(NOT the appointee — barred; see step 2)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **Why this prompt is thin.** A previous candidate was handed the full commission at first start, followed it in order, and read ~120 lines of the subject before declaring — a disclosed ordering deviation traced to **prompt selection, not process error**. Step 0 exists so it cannot recur.

---

## Candidate declaration — `REPAIR-001` re-verifier

You are a **candidate**. You are not appointed, not registered, and not authorized.

**0 · Declare before you orient.** Before reading **any** source file, test, report, plan, developer guide, or commit diff, complete steps 1–3. **Do not read `activate-commissioned-fresh-session.php`, its contract test, the repair plan, the developer guide, or `d8a5ee93`'s diff.** If you have already read any of them, **say so** — disclosure costs nothing; concealment costs the appointment.

1. **Identity.** Determine your `CLAUDE_CODE_SESSION_ID` from the **runtime only** — never from this prompt, never a CLI argument, never copied from a document.

2. **Independence.** Confirm you are none of:
   - `84e5c1f7-bd51-42fd-83fc-1b58c7d90dd3` — the `REPAIR-001` **implementer** (wrote both the fix and the tests that certify it)
   - `84c0f6f6-795e-4c89-a382-733f2c7b7caf` — the **first verifier** (authored the findings under remediation)
   - `1899d8bf-2688-4bf3-9787-b4114ddaeec8` — the `AST-019` **producer**
   - `5928b9f9-b4d5-46e9-8c71-c295dace18f8` — the **Governance** process that scoped this repair
   - PO/ARB

   **A fresh UUID is not independence.** Two facts apply together: a **subagent reports its PARENT's `CLAUDE_CODE_SESSION_ID`** (probe 2026-08-22), so a collision with any id above disqualifies; and **`CLAUDE_CODE_CHILD_SESSION=1` is non-discriminating** — this Governance session has it too (`O-5`), so **disclose it and do not treat it as either a disqualification or a clearance.**

3. **Prior participation — assess it, from evidence.** Search for your own id and report the results: `git grep`, `git log --all --grep`, and `grep -rl` over `.claude/runtime/`, `.claude/sessions/`, `docs/`. Then read **only** the authoritative state:
   `php .claude/scripts/workflow-state.php fold KOS-OPERATING-MODEL-001-AMENDMENT-001`
   and your own bootstrap (`--process-label=<your BARE id>` — a `claude-code-session:` prefix never matches):
   `php .claude/scripts/session-bootstrap.php --work-item=KOS-OPERATING-MODEL-001-AMENDMENT-001 --process-label=<bare id> --role=verification`

4. **Do not:** create a workflow record · `REGISTER` · `HANDOFF` · `START` · `CONTINUATION` · modify any file · invoke `AST-019` against a real work item · begin verification · form or hint at a verdict.

**State facts you will meet, so nothing surprises you** (not an invitation to act): the item is `OPEN` with `G-REPAIR-001` `AUTHORIZED`; the **implementation lane is still `ACTIVE`** and its `STOP` may not yet be recorded, so `AST-018 next-actor` may tell you *"no new actor is needed"* — **that advice is unreliable in this state** (`O-4`). Your appointment is the intended next act once the human decides.

Return exactly:

```
PROCESS IDENTITY:
REQUESTED ROLE:          verification
INDEPENDENCE:            (against the five bars; disclose CLAUDE_CODE_CHILD_SESSION and any prior reading)
PRIOR PARTICIPATION:     (in full, with the evidence you ran — this work item, AST-019, anywhere in this estate)
ELIGIBILITY:             ELIGIBLE / NOT ELIGIBLE
WORK ITEM STATE:         (as the fold reports it)
CURRENT AUTHORIZATION:   NOT AUTHORIZED
```

Then **STOP.** Governance assesses your declaration, and appointment happens via `AST-018 appoint` on a recorded human act. *(Note `O-6`: that one act writes `REGISTER` + `HANDOFF` + `START` together — your lane becomes active at appointment, not by a separate later step.)*

**What you will be asked to verify, once appointed** — listed so you can judge your own fitness, **not** to be started now: live `mutationOwner` handling · `REGISTER.predecessor` · `HANDOFF.from` · failure result shape · `transitionWritten` · `--json` integrity under `display_errors=On` · `GO-26`…`GO-30` · `GO-01`…`GO-25` regression · `AST-015`/`016`/`017`/`018` integrity · adopted `L1`/`L2`/`L3` integrity. **Out of scope and not yours to touch:** `F-5` · `ASD-001` · `O-4` · `O-6` · `Q-1` · `Q-2` · §22.

---

**Traceability:** PO/ARB order 2026-08-24 · determination `…-REPAIR-001-RE-VERIFICATION-DETERMINATION.md` · binding condition `AUTHORIZATION-DECISION.md:15` · grant `G-REPAIR-001` · previous verdict `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL) · repair `d8a5ee93` · `O-4` · `O-5` · `O-6` · `INV-ATTR-1/2` · `G-3` · `R-34`/`EP-02`
