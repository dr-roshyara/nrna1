# `KOS-OPERATING-MODEL-001` — FOLLOW-UP observation: governed continuation & fresh-session self-activation capability (`ActivateCommissionedFreshSession`)

**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity; identity disclosed — CORRECTION-001 author. Recording ≠ implementing/verifying/accepting/adopting/appointing/deciding.)*
**Source acts:** PO/ARB in-session analysis 2026-08-22 (three messages): (1) narrating the fresh independent verifier `claude-code-session:fc59bb0a-98df-4c3f-8819-06bd1adb92f4` that correctly stopped instead of self-authorizing — *"the next actor is Governance"*; (2) the design refinement — *"Yes. That is the missing architectural piece. The fresh session's identity is inherently unknown before the session exists, so the workflow must support **self-registration of the newly created runtime identity — but only for the already-authorized role and only under governed conditions**."* — the new explicitly commissioned capability `ActivateCommissionedFreshSession`, with the invariant: *"A fresh session may self-bind identity; it may never self-choose role, scope, work item, or authority."*; (3) the symmetry refinement — *"A fresh session does not need to know its identity or role in advance. It discovers its runtime identity, and its requested role comes from the human's business instruction."* — the **unified binding model**: *"Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two"* — one mechanism for the FIRST session (*"I want Governance Engineer."*) and every subsequent fresh session (*"I want a Verification session for KOS-OPERATING-MODEL-001."*); the old rule *"A session must never register itself"* is corrected as too strong.
**Placement derived:** `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)

> ⛔ **This observation is recorded, NOT decided, NOT implemented.** It proposes a **follow-up / amendment** to `KOS-OPERATING-MODEL-001`. It creates no authority, no lane, no grant, no state change, no new capability, no modification to any asset.

---

## 1 · The observed gap (mechanism evidence)

- **Fold `KOS-OPERATING-MODEL-001`:** `workItemState: STOPPED` · session `259c1966-b18e-4759-8afe-b46627dd5a2f` = implementation → **STOPPED** (seq 4, `recordedBy: implementation`) · **no verification lane** (transitions are only seq 1 REGISTER → 2 HANDOFF → 3 START → 4 STOP).
- **Fresh verifier `fc59bb0a` (declared, session log 2026-08-22):** PROCESS IDENTITY `claude-code-session:fc59bb0a-98df-4c3f-8819-06bd1adb92f4` (from runtime) · INDEPENDENCE distinct from producer `259c1966`, `5c0e13c1`, `8a525719`, `b51dba91`, `8deac5de`, `d1612e03`, `b64828fe`, `7c2690ae`, PO/ARB — **passed**.
- **Bootstrap `fc59bb0a` on the work item:** `UNRESOLVED · operable: false · lane: none · role: none · authorized_to_act: false · recommended next actor: governance` — *"Fail-closed: no single lane resolved."*

**Correct behavior, confirmed:** the fresh verifier checked reality (the record has no continuation → no REGISTER(verification) → no HANDOFF → no human START), concluded *"I am a valid fresh candidate, but I am not yet authorized,"* and **stopped**. It did not create a lane, register itself, hand itself work, start itself, verify, or change the workflow. **Nothing went wrong.** This was the correct behaviour under the **old flow** — self-registration was prohibited.

## 2 · The refined architecture (PO/ARB 2026-08-22) — self-registration, not manual identity transport

**The problem the capability removes:** the fresh session's identity is **inherently unknown before the session exists**, so the workflow cannot rely on Governance knowing a session UUID in advance. The manual transport ("`I appoint claude-code-session:fc59bb0a...`") was unnecessary. The workflow must instead support **self-registration of the newly created runtime identity — but only for the already-authorized role and only under governed conditions**.

**The distinction (verbatim):**

> **The fresh session may register itself as the actor for the role it was explicitly commissioned to perform. It may not choose the role, appoint itself to arbitrary work, or create its own authority.**

**The desired flow:**

```
Current session
    ↓
"The next step requires a fresh Verification session."
    ↓
Human starts fresh session
    ↓
Fresh session discovers:  CLAUDE_CODE_SESSION_ID = fc59bb0a...
    ↓
Prompt already says:      "You are the Verification actor for KOS-OPERATING-MODEL-001"
    ↓
Fresh session verifies:
    - this is its real runtime identity
    - requested role = verification
    - it is eligible / independent
    - work item permits a verification actor
    ↓
SELF-REGISTER
    ↓
HANDOFF
    ↓
HUMAN START
    ↓
VERIFICATION ACTIVE
```

**The human never provides the UUID.** The human starts a fresh session and pastes the prepared prompt; the session discovers `CLAUDE_CODE_SESSION_ID` automatically.

### The unified binding model — the symmetry (PO/ARB refinement #2, 2026-08-22)

**The revelation (verbatim):** *"A fresh session does not need to know its identity or role in advance. It discovers its runtime identity, and its requested role comes from the human's business instruction."* The old statement *"A session must never register itself"* was **too strong**. The correct rule:

> **A fresh session may register itself only when the desired role and work context are already established by the human's business instruction or an existing governed commission.**

**The unified invariant:**

> **Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two.**

**The symmetry — one mechanism, every role.** The human chooses the role in **business language**, never a process ID; the runtime supplies the process identity automatically:

```
HUMAN BUSINESS INTENT
        ↓
"I want <ROLE>."   ← Governance Engineer · Architecture · Implementation · Verification · Review · Communication
        ↓
FRESH SESSION
        ↓
discover runtime ID
        ↓
GOVERNED BINDING   ← validates work context · requested role · eligibility · identity
        ↓
REGISTER {session = own runtime identity, role = requested role}
        ↓
HANDOFF / activation mechanics
        ↓
<ROLE> ACTIVE
```

**First session (Governance Engineer):**
```
Human → opens fresh session → "I want Governance Engineer."
session discovers its own runtime identity
session says: "I am <runtime-id>. You requested Governance Engineer."
Governance bootstrap validates: work context · requested role · eligibility · identity
REGISTER itself as Governance Engineer
HANDOFF / activation mechanics
Governance Engineer ACTIVE
```
The human does not say *"I appoint fc59…"* — the session performs `runtime identity = fc59…`, `requested role = Governance Engineer`, and the governed bootstrap establishes `REGISTER {session = fc59…, role = governance}`.

**Subsequent session (existing work item):**
```
"I want a Verification session for KOS-OPERATING-MODEL-001."
```
The fresh session gets its own runtime identity and binds itself to the **already requested/authorized role**, subject to the workflow rules.

**The key architectural distinction:**

```
✅ self-discover identity
✅ self-bind to explicitly requested/commissioned role
✅ self-register through canonical workflow mechanism

❌ choose its own role
❌ choose arbitrary work item
❌ invent scope
❌ invent authority
❌ bypass Governance
```

**The mechanics are the same for both cases; only the business-intent source differs** — the human's business instruction for a first session (*"Hello Governance Engineer…"*) versus an existing governed commission for a subsequent session (*"I am the fresh Verification session for …"*).

## 3 · The proposed capability `ActivateCommissionedFreshSession` (candidate, NOT implemented)

**Name (candidate):** `ActivateCommissionedFreshSession` — *"the key use case name"* (PO/ARB). Alternative phrasing given by the PO/ARB: `ActivateMyCommissionedRole`. The capability is **NOT** an unrestricted `REGISTER any-role`.

**Constrained contract** (all conjuncts must hold):

```
current runtime identity
    +
commissioned work item
    +
commissioned role
    +
valid fresh-session declaration
    +
eligibility / independence checks
    +
no existing conflicting assignment
        ↓
the ONLY allowed write:
    REGISTER
        session = current runtime identity
        role    = commissioned role
        ↓
governed HANDOFF
        ↓
human START (G-3)
```

**The critical safety rule:** the role must come from the **authoritative commissioned work item / prepared governance instruction** — never from whatever the prompt happens to claim:

```
prompt says Verification
    + workflow commission says Verification
    + runtime identity = me
    + eligibility passes
    → self-registration ALLOWED

prompt says Architecture / workflow says Verification
    → MISMATCH → STOP
```

**The invariant (verbatim):**

> **A fresh session may self-bind identity; it may never self-choose role, scope, work item, or authority.**

## 4 · Governance / Communication split

- **Governance Engineer decides:** *"This work requires a fresh independent Verification actor"* — it commissions the **role**, not a process ID.
- **Communication Engineer / fresh-session bootstrap performs the routine mechanics** on the already-authorized commission: `discover own identity → validate commissioned role → self-register → handoff → human start`.

## 5 · Boundaries (binding)

- **AST-015 remains the SINGLE WORKFLOW AUTHORITY** — the REGISTER (and HANDOFF) execute only through the canonical mechanism; no duplicate fold, no second state machine, no direct record modification, no parallel store, no second workflow engine.
- **Consumes AST-017** (read-only responsibility resolution) **and AST-018** (next-actor orchestration presentation) — does not replace either.
- **No identity fabrication** — the identity is **discovered** from the runtime by the fresh process (INV-ATTR-1/2); never invented, never chosen from a human-entered UUID, never inherited by a subagent.
- **No self-role-choice, no self-appointment to arbitrary work, no self-created authority** — the role is the commissioned role, validated against the authoritative commission.
- **Human START is never manufactured** (G-3); the human approves in business language, not by typing workflow mechanics.
- **No automatic adoption** — verification ≠ adoption; adoption follows the governance path + PO/ARB decision.

**Placement (per PO/ARB):** a **new explicitly commissioned capability / amendment to the operating model** — integrated by the **Governance Engineer** (owns the substantive continuation decision and the transition into the next governed role); the **Communication Engineer** executes the routine mechanics. **NOT** added to the verifier; **NOT** a silent patch to AST-015/016/017/018 "just to make it happen." No unauthorized scope expansion (§37).

## 6 · Relation to the earlier design (continuity, not contradiction)

The earlier in-session message prescribed the governed sequence **CONTINUATION → REGISTER(verification) → HANDOFF → human START**. The refined design **keeps the same governed sequence** but replaces the **manual identity transport** (Governance needing to know the session UUID to REGISTER it) with the **fresh session's self-registration of its own discovered identity**. Both preserve: the human START (G-3) is a genuine human act; the fresh session does not self-authorize its role; the role comes from the authoritative commission. **The re-interpretation of `fc59bb0a`'s STOP:** it stopped correctly under the old flow because self-registration was prohibited — *"But under the improved operating model, this is precisely the capability we should add: a genuinely fresh, pre-commissioned session may bind its own discovered runtime identity to its already-authorized role, then enter the normal REGISTER → HANDOFF → human START sequence."*

## 7 · Classification & status

| Item | Value |
|---|---|
| Classification | **FOLLOW-UP observation on `KOS-OPERATING-MODEL-001`** — operating-model amendment / new explicitly commissioned capability |
| Status | **RECORDED · NOT DECIDED · NOT IMPLEMENTED** — no authority, no lane, no grant created |
| Owner (proposed) | Governance Engineer (integration); Communication Engineer (routine mechanics) |
| Does not reopen | EKS-07 · migration · AST-015/AST-016/AST-017 redesign · Phase 3 · Phase 5 |

## 8 · Next governed steps (recorded, not executed)

- **(A) The human decision on the CURRENT verification of `KOS-OPERATING-MODEL-001`** (operating-model §13 OUTCOME B — permission required; the PO/ARB's script, updated with the fresh verifier's declared identity):

  > The implementation is complete and a fresh independent verifier is ready (identity declared: `fc59bb0a-98df-4c3f-8819-06bd1adb92f4`). The work item is currently stopped.
  > I need your permission to continue the work into independent verification.
  > **1. Yes**  ·  **2. Write a prompt for the verification step**  ·  **3. Stop**

  On the human's **"1"**, the governed sequence executes (CONTINUATION → REGISTER(verification) → HANDOFF → human START) → verification ACTIVE → **independent verification** → governance adoption review → **PO/ARB adoption decision** (NOT automatic).

- **(B) The PO/ARB commissioning `ActivateCommissionedFreshSession`** as a **new explicitly commissioned capability / operating-model amendment** (per the design in §2–§5, now unified — §2.1: the same binding mechanism serves the FIRST session *"I want Governance Engineer."* and every subsequent fresh session *"I want a Verification session for KOS-OPERATING-MODEL-001."*) — a separate governed slice, integrated by the Governance Engineer. **This observation does not substitute for either decision** — it records the gap and the refined design so the gap is governed, not silently patched.

## 9 · session_completion

```yaml
session_completion:
  status:            # FOLLOW-UP OBSERVATION RECORDED (refined design) — work item STOPPED; continuation
                     #   decision pending human; capability NOT decided / NOT implemented
  completed_work:    # confirmed the PO/ARB's analysis against the mechanism (fold STOPPED; fc59bb0a
                     #   UNRESOLVED fail-closed, next actor governance); recorded the PO/ARB's refined
                     #   architecture: the missing capability = ActivateCommissionedFreshSession (fresh
                     #   session self-binds its discovered identity to the commissioned role; never
                     #   self-chooses role/scope/work-item/authority); contract + safety rule + invariant +
                     #   Governance/Communication split + boundaries; refinement #2: the unified binding
                     #   model — Human declares intended responsibility / runtime declares process
                     #   identity / governed bootstrap binds the two — one mechanism for the FIRST session
                     #   and every subsequent fresh session; old "a session must never register itself"
                     #   corrected as too strong
  evidence:          # fold KOS-OPERATING-MODEL-001 (STOPPED, 259c1966, no verification lane); AST-017
                     #   bootstrap fc59bb0a (UNRESOLVED/authorized_to_act=false/next=governance); session log
                     #   2026-08-22 (fc59bb0a declaration, independence bars passed); PO/ARB in-session
                     #   analysis 2026-08-22 (three messages, verbatim)
  open_items:        # human continuation decision (A) pending; capability commissioning (B) pending;
                     #   verification lane not registered; ActivateCommissionedFreshSession not decided/
                     #   implemented; adoption not claimed

next_actor:
  recommended_role:  # governance — presents the human continuation decision (A); on the human's "1",
                     #   records the governed sequence into verification; separately the PO/ARB decides
                     #   whether to commission the new capability (B)
  reason:            # the fresh verifier cannot and must not continue the stopped work itself (self-activation
                     #   is forbidden under the old flow; under the improved model its self-registration is
                     #   conditional on the new capability being commissioned); the PO/ARB holds the authority
                     #   decision; the human START (G-3) is a genuine human act; Governance owns the transition
                     #   into the next governed role
  blocking_condition: # human approval ("1. Yes") then governed sequence into verification; and/or PO/ARB
                     #   commissioning ActivateCommissionedFreshSession as a separate governed slice

authorization:
  current_session_can_continue:   # false (b51dba91 bootstrap on this work item: UNRESOLVED,
                                  #   authorized_to_act=false — fail-closed)
  authorized_to_act:              # false — this process does not continue/register/start/verify/adopt/commission
  requires_human_decision:        # true — the continuation is the PO/ARB's decision; the capability
                                  #   commissioning is the PO/ARB's decision; recording only
```

---

**Traceability:** PO/ARB in-session analysis 2026-08-22 (two messages, verbatim) · fold `KOS-OPERATING-MODEL-001` (STOPPED, seq 4) · AST-017 bootstrap `fc59bb0a` (UNRESOLVED) · verifier-appointment registration (`…-KOS-OPERATING-MODEL-001-VERIFIER-APPOINTMENT-registration.md`) · candidate-declaration prompt (`…-VERIFIER-CANDIDATE-DECLARATION-prompt.md`) · implementation prompt (verbatim, 40 sections, §8/§13/§31–§32 role-transition mechanics) · implementation + session-completion (lane `259c1966`, STOPPED) · six-role operating model (2026-08-19) · `AST-015` · `AST-017` · `AST-018` · `G-3` · `R-34`/`EP-02` · `INV-ATTR-1/2` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
