# `KOS-NEXT-ACTOR-ORCHESTRATION-001` — AMENDMENT-001: add use case `PrepareNextActorSession` (Next Session Kickoff Prompt)

**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity; recording ≠ implementing/verifying/accepting/adopting)*
**Act:** PO/ARB 2026-08-22 — architectural decision to **extend the commissioned `KOS-NEXT-ACTOR-ORCHESTRATION-001` capability** with a third use case. Explicitly **not** a new work item and **not** an EKS-07 reopen.
**Scope relationship:** the original implementation prompt (`…-implementation-prompt.md`, verbatim) remains authoritative and unchanged (`ES-004.3` — history is never rewritten). **This amendment adds to it.** The implementation session must implement the original Use Cases 1–2 + advisory branch **and** this new Use Case 3.

> ⛔ **This amendment records the PO/ARB's decision. It creates no authority, no lane, no record, no grant, no state change.** The capability is **NOT implemented**, **NOT adopted**, **NOT authorized for future use**. The appointee identity remains the placeholder `claude-code-session:<ACTOR-ID>`; this recording does not appoint anyone.

---

## 1 · The gap this closes (PO/ARB's rationale, preserved)

The current commissioned behavior **stops one step too early**. It correctly determines:

> "A fresh process is required."

but then tells the human:

> "You start a fresh session."

and leaves the human to figure out **what to tell that new session**. That is the remaining usability gap. The adopted handoff protocol exists to reduce exactly this reconstruction burden; the implementation prompt already says the system should explain what is needed and what the human can choose. The candidate-declaration artifact (`…-CANDIDATE-DECLARATION-prompt.md`) is a good starting point, but today the human has to discover it manually. **The architecture should produce a "Next Session Kickoff Prompt"** — so the human has a **complete next action**, not an instruction to act with a manual reconstruction burden.

## 2 · New use case 3 — `PrepareNextActorSession`

**Responsibility:**

```text
authoritative state
      ↓
determine next actor
      ↓
does actor require a new OS session?
      ↓
YES
      ↓
construct context-safe kickoff prompt
      ↓
present to human
```

**The prompt is generated from governed facts, not copied from a static template.** It must dynamically include:

```text
work item
role
scope
why the actor is needed
independence exclusions
current state
what the new session must do first
what it must NOT do
where it must stop
next governed step
```

## 3 · Target output shape (PO/ARB's example, preserved)

When the current session reaches a boundary like *"Next actor: fresh Architecture implementation session"*, it should automatically produce:

```text
NEXT ACTOR
Fresh independent Architecture implementation session

WHAT YOU NEED TO DO
Open a new session and paste the following prompt:

────────────────────────────────────
You are the candidate Architecture implementation actor for
KOS-NEXT-ACTOR-ORCHESTRATION-001.

Your first task is NOT implementation.

1. Determine your actual process identity from the runtime mechanism.
2. Verify that you are independent of:
   - AST-017 producer: 8a525719
   - current correction author: b51dba91
   - verifier: 8deac5de
   - prior verifier: d1612e03
   - Governance: b64828fe
3. Read the authoritative governance state.
4. Report:

   PROCESS IDENTITY:
   INDEPENDENCE:
   ELIGIBILITY:
   CURRENT AUTHORIZATION:

5. Do not modify code.
6. Do not create a workflow record.
7. Do not REGISTER.
8. Do not HANDOFF.
9. Do not START.

STOP after the declaration.
────────────────────────────────────

After the new session returns its identity, Governance Architecture
will continue the governed appointment process.
```

## 4 · Human choice (business interaction)

The session should ask:

> **A fresh Architecture implementation session is required. Should I prepare the new-session prompt for you?**

```text
1. Yes — give me the exact prompt to paste into the new session.
2. Show me the prompt and explain what it will do.
3. Stop.
```

This is strictly better than *"Start a fresh session."* because the human now has a **complete next action**.

## 5 · Important DDD boundary — NOT a "session creator"

The domain distinction must remain:

```text
Human:
    starts the new process

Governance Architecture:
    prepares the handoff instructions

New session:
    declares its identity

Governance:
    activates the governed lane

Workflow Engine:
    enforces authority
```

**The capability does NOT try to manufacture a new process identity.** This is exactly what the empirical probe proved cannot be safely done: a spawned subagent inherits the parent's `CLAUDE_CODE_SESSION_ID` (probe 2026-08-22: subagent reports `b51dba91-6fc4-4110-b2f2-ac76ad0f3808`, the parent's identity; distinct OS PID only). Manufactured identity would violate `INV-ATTR-1/2` (identity evidence-only) and the fresh-actor independence bars.

## 6 · The improved chain (PO/ARB, preserved)

```text
Current session completes
        ↓
AST-017 determines next actor
        ↓
Next Actor Orchestration
        ↓
"Fresh session required"
        ↓
PrepareNextActorSession
        ↓
Human gets exact copy/paste prompt
        ↓
Human opens new session
        ↓
New session declares identity
        ↓
Governance Architecture continues
        ↓
REGISTER → HANDOFF → START
        ↓
New actor works
```

## 7 · Relationship to the existing commission (unchanged boundaries hold)

| Item | Status under this amendment |
|---|---|
| Use Case 1 `DetermineNextActorAction` | unchanged — still commissioned |
| Use Case 2 `AppointReviewer` | unchanged — still commissioned |
| Advisory branch `PrepareReviewerPrompt` | unchanged — still commissioned |
| **Use Case 3 `PrepareNextActorSession`** | **ADDED by this amendment** |
| AST-017 boundary | unchanged: READ-ONLY · ON_DEMAND · responsibility/resolution only |
| AST-015 boundary | unchanged: SINGLE WORKFLOW AUTHORITY; no second fold/engine/state reconstruction |
| Transition execution | unchanged: all writes through the canonical governed mechanism; no bypass |
| EKS-07 | **NOT reopened** — this is a narrow extension of the commissioned capability, not autonomous coordination/marketplace/scheduler/authority-transfer |
| Migration | unchanged: NOT touched |
| Adoption | unchanged: `implemented` ≠ `adopted` ≠ `authorized for future use`; no adoption claim |

## 8 · Non-actions honored by this recording

⛔ no implementation · ⛔ no lane · ⛔ no workflow record · ⛔ no REGISTER/HANDOFF/START · ⛔ no transition · ⛔ no grant · ⛔ no appointment (appointee identity still placeholder) · ⛔ no EKS-07 work · ⛔ no migration · ⛔ no adoption claim · ⛔ no manufacturing of a process identity (empirical probe binding).

## 9 · session_completion

```yaml
session_completion:
  status:            # AMENDMENT-001 RECORDED — capability NOT implemented, NOT adopted
  completed_work:    # captured the PO/ARB's decision (add Use Case 3 PrepareNextActorSession), the
                     #   gap rationale, target output shape, human-choice interaction, DDD boundary
                     #   (NOT a session creator), improved chain, scope relationship to the original
                     #   commission; placement derived
  evidence:          # PO/ARB architectural decision 2026-08-22 (preserved verbatim); original
                     #   implementation prompt (…-implementation-prompt.md); empirical identity probe
                     #   (subagent inherits parent CLAUDE_CODE_SESSION_ID) — the design justification
                     #   for "NOT a session creator"; appointment registration; candidate-declaration
                     #   prompt
  open_items:        # capability not implemented/adopted; appointee identity placeholder; workflow
                     #   record not created; lane not registered; AST-017 adoption review still awaiting
                     #   a governed Governance reviewer

next_actor:
  recommended_role:  # implementation — the appointed fresh session (unchanged from the sequence)
  reason:            # AMENDMENT-001 extends the same commissioned work item; it does not change who
                     #   implements it (fresh independent Architecture implementation session, identity
                     #   placeholder, appointed by PO/ARB) or the governed sequence (declare identity →
                     #   eligibility → record init + REGISTER → HANDOFF → human START → implement → STOP)
  blocking_condition: # appointee identity declaration; then governed REGISTER → HANDOFF → human START

authorization:
  current_session_can_continue:   # false (bootstrap UNRESOLVABLE / UNRESOLVED for this process)
  authorized_to_act:              # false — this process (b51dba91) does not implement, appoint, register,
                                  #   transition, or adopt this work item
  requires_human_decision:        # true — PO/ARB starts the fresh session; the human START must be recorded
```

---

**Traceability:** PO/ARB architectural decision 2026-08-22 (add `PrepareNextActorSession`; preserved verbatim) · original commission prompt `…-implementation-prompt.md` (verbatim, unchanged — ES-004.3) · commission registration · appointment registration · candidate-declaration prompt · empirical identity probe (subagent inherits parent `CLAUDE_CODE_SESSION_ID`) · `AST-017` · `AST-015` · `G-3` · `P-3` · `INV-ATTR-1/2` · `EP-01` · `EP-02`/`R-34` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
