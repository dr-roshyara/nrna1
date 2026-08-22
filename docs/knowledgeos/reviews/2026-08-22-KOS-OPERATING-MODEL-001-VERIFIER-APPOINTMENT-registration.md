# `KOS-OPERATING-MODEL-001` — verification appointment **RECORDED** (identity placeholder) · FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL

**Recorded by:** Governance-recording — `claude-code-session:259c1966-b18e-4759-8afe-b46627dd5a2f` *(the implementation session, disclosed GOVERNANCE-RECORDING capacity for this act; identity disclosed. Recording ≠ implementing/verifying/accepting/adopting/appointing — this process is the producing implementation session and is explicitly **NOT** the appointee.)*
**Act:** PO/ARB 2026-08-22 — in-session direction, verbatim: *"appoint a fresh independent verifier"* — the appointment of the independent verification actor for `KOS-OPERATING-MODEL-001`, per the START act's declared sequence (seq 3): `implement → STOP → fresh independent verifier → verification → Governance adoption review → PO/ARB adoption decision`.

> ⛔ **This registration records the PO/ARB's act. It creates no authority, no lane, no grant, no state change.** The appointee's identity is **NOT declared** — it is the placeholder `claude-code-session:<ACTOR-ID>` until the appointed actor declares it from the runtime mechanism. **REGISTER(verification) is blocked pending that declaration** (Governance does not self-appoint, adopt, or fabricate identity — INV-ATTR-1/2). The work item is **STOPPED (seq 4)** — an explicit **CONTINUATION** (recordedBy governance/human) must precede REGISTER(verification). The work is **NOT verified, NOT adopted**.

---

## 1 · What the PO/ARB decided (2026-08-22)

| Decision | Value |
|---|---|
| Work item | **`KOS-OPERATING-MODEL-001`** (name confirmed; all three layers implemented; lane STOPPED seq 4) |
| Act | **Appoint a fresh independent verifier** (in-session direction, verbatim) |
| Role appointed | **Verification** (fresh, independent) |
| Sequence context | START act seq 3 declared: `implement → STOP → fresh independent verifier → verification → Governance adoption review → PO/ARB adoption decision` |
| Appointee identity | **`claude-code-session:<ACTOR-ID>`** — placeholder, NOT yet declared |
| This process (`259c1966`) | **NOT the appointee** — it is the producing implementation session (producer bar, R-34/EP-02); it does not self-appoint and cannot verify its own output |

## 2 · Governed state of the work item (read-only, 2026-08-22, post-STOP)

| Field | Value |
|---|---|
| `workItemState` (AST-015 fold) | **`STOPPED`** (seq 4 STOP — recordedBy `implementation`) |
| AST-018 `next-actor` result | **`WORK_ITEM_STOPPED`** — options `CONTINUE` / `LEAVE_STOPPED`; `freshIndependentActorRequired: false` (no next actor implied until an explicit CONTINUATION) |
| AST-018 `prepare-next-session` | **`NO_FRESH_ACTOR_REQUIRED`** — *"Preparing a kickoff prompt now would invite you to start a new process for work that is already assigned."* |
| AST-017 bootstrap | **`UNRESOLVED`** — fail-closed; `authorized_to_act: false`; recommended next role **`governance`** |
| `continuation.current_session_can_continue` | `false` |

**Meaning:** the producing session delivered all three layers and recorded STOP. The item is **sticky-stopped**: no automatic restart, no automatic actor replacement, no automatic adoption (EKS-07 not reopened). The fresh verifier cannot be *registered* and the kickoff prompt cannot be *machine-prepared* while STOPPED — the continuation decision and the identity declaration come first.

## 3 · The governed sequence to independent verification (recorded, not executed)

```
PO/ARB appoints a fresh independent verifier                          ✅ (this act — recorded, no authority)
   → PO/ARB (or Governance on recorded direction) records CONTINUATION  ← NEXT (exits the sticky STOPPED; recordedBy governance/human)
   → appointed session DECLARES identity (CLAUDE_CODE_SESSION_ID)       ← NEXT
   → eligibility / independence / non-participation verification
   → REGISTER (role = verification)                                     ← Governance, pending identity declaration
   → HANDOFF
   → human START (G-3 conjunction)
   → fresh independent verifier verifies the three layers (producer bar: NOT 259c1966)
   → Governance adoption review → PO/ARB adoption decision (NOT automatic)
```

**Identity/independence bars for the appointee:** ≠ implementation producer `259c1966-b18e-4759-8afe-b46627dd5a2f` · ≠ AST-018 implementation actor `5c0e13c1` · ≠ AST-017 producer `8a525719` · ≠ CORRECTION-001 author `b51dba91-6fc4-4110-b2f2-ac76ad0f3808` · ≠ re-verifier `8deac5de-605f-429b-9092-11264457cec8` · ≠ prior verifier `d1612e03` · ≠ Governance `b64828fe` · ≠ would-be verifier `7c2690ae` · ≠ PO/ARB. A **subagent is disqualified by construction** — an empirical probe (2026-08-22, recorded in the AST-018 candidate-declaration precedent) showed a subagent inherits the parent's `CLAUDE_CODE_SESSION_ID`; it is not a fresh, independent process.

## 4 · Non-actions honored by this recording

⛔ no self-appointment by `259c1966` · ⛔ no spawn of a subagent as the "fresh" verifier (subagent inherits this session's identity — not fresh, not independent) · ⛔ no workflow-record mutation · ⛔ no REGISTER/HANDOFF/START · ⛔ no CONTINUATION · ⛔ no transition · ⛔ no grant · ⛔ no verification · ⛔ no adoption claim · ⛔ no change to `AST-015` / `AST-016` / `AST-017` / `AST-018` · ⛔ no EKS-07 · ⛔ no migration.

## 5 · Next actor

**The PO/ARB** — records the explicit **CONTINUATION** (the STOPPED state is sticky; nothing proceeds until a person decides to continue it — AST-018 `WORK_ITEM_STOPPED`, options CONTINUE / LEAVE_STOPPED), then starts the fresh independent verification session using the preserved candidate-declaration prompt (`…-KOS-OPERATING-MODEL-001-VERIFIER-CANDIDATE-DECLARATION-prompt.md`). **Blocked until:** (a) continuation is recorded, and (b) the appointee exists as a separate process and declares its identity from the runtime mechanism. `259c1966` cannot and must not fill that role.

## 6 · session_completion

```yaml
session_completion:
  status:            # APPOINTMENT RECORDED — appointee identity placeholder; work item STOPPED (seq 4)
  completed_work:    # recorded the PO/ARB act ("appoint a fresh independent verifier", verbatim);
                     #   captured the governed state (STOPPED / WORK_ITEM_STOPPED / NO_FRESH_ACTOR_REQUIRED
                     #   while stopped / bootstrap UNRESOLVED fail-closed); recorded the sequence to
                     #   independent verification + identity bars + non-actions; preserved the candidate-
                     #   declaration prompt; placement derived
  evidence:          # PO/ARB in-session act 2026-08-22 (verbatim); AST-015 fold (STOPPED, seq 4);
                     #   AST-018 next-actor (WORK_ITEM_STOPPED) + prepare-next-session
                     #   (NO_FRESH_ACTOR_REQUIRED); AST-017 bootstrap (UNRESOLVED); candidate-declaration
                     #   precedent (…-KOS-NEXT-ACTOR-ORCHESTRATION-001-CANDIDATE-DECLARATION-prompt.md);
                     #   implementation-appointment registration (…-KOS-OPERATING-MODEL-001-APPOINTMENT-registration.md)
  open_items:        # appointee identity not declared; CONTINUATION not recorded (sticky STOPPED);
                     #   REGISTER(verification) blocked pending identity declaration; verification,
                     #   governance adoption review, and PO/ARB adoption decision all pending; producer
                     #   bar (R-34/EP-02) disqualifies 259c1966 from verifying

next_actor:
  recommended_role:  # PO/ARB (human) — record CONTINUATION; then Governance REGISTER(verification)
                     #   → HANDOFF → human START once the fresh verifier has declared its identity
  reason:            # the PO/ARB appointed a fresh independent verifier per the START act's declared
                     #   sequence; STOPPED is sticky and only an explicit CONTINUATION (recordedBy
                     #   governance/human) exits it; identity must be declared by the appointee and
                     #   verified through the mechanism (INV-ATTR-1/2); the producing session does not
                     #   self-appoint and cannot verify its own output (P-3; EP-02/R-34)
  blocking_condition: # explicit CONTINUATION recorded; then appointee identity declaration;
                     #   then governed REGISTER → HANDOFF → human START

authorization:
  current_session_can_continue:   # false (AST-017 bootstrap: current_session_can_continue=false)
  authorized_to_act:              # false — this process (259c1966) does not verify, appoint, register,
                                  #   transition, continue, or adopt this work item
  requires_human_decision:        # true — continuation is a human decision (G-3); adoption is a human
                                  #   decision; the appointee must declare identity before any REGISTER
```

---

**Traceability:** PO/ARB in-session direction 2026-08-22 (verbatim: *"appoint a fresh independent verifier"*) · implementation registration (`…-KOS-OPERATING-MODEL-001-APPOINTMENT-registration.md`) · commission registration (`…-commission-registration.md`) · implementation prompt (`…-KOS-OPERATING-MODEL-001-implementation-prompt.md`, verbatim, 40 sections) · START act seq 3 (declared sequence) · STOP act seq 4 (AST-015) · AST-018 `next-actor` / `prepare-next-session` · AST-017 bootstrap · candidate-declaration precedent (`…-KOS-NEXT-ACTOR-ORCHESTRATION-001-CANDIDATE-DECLARATION-prompt.md`) · `G-3` · `P-3` · `EP-02`/`R-34` · `INV-ATTR-1/2` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
