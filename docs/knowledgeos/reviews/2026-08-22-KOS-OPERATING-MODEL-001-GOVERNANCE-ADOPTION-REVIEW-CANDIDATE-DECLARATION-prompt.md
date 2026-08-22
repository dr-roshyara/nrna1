# `KOS-OPERATING-MODEL-001` — candidate Governance adoption-review identity declaration prompt (as commissioned by PO/ARB, 2026-08-23)

**Work item:** `KOS-OPERATING-MODEL-001` (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL) · **Role:** Governance (fresh, independent — the adoption-review role, declared sequence step 5)
**Document type:** the PO/ARB's candidate-declaration prompt — **verbatim**, preserved for the fresh Governance adoption-review session to consume on first start
**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity for this act; recording ≠ implementing/verifying/appointing/accepting/adopting. This process is explicitly **NOT** the appointee and cannot fill the reviewer role.)*
**Placement derived:** `scripts/doc-placement.php` → `docs/knowledgeos` (product-specific · knowledgeos; exit 0)

> ⛔ **This document preserves the prompt only. It creates no authority, no lane, no record, no grant, no state change.** The fresh Governance adoption-review session is a **separate process the PO/ARB starts**; it is **NOT** a subagent of this session. Empirical probe 2026-08-22 (recorded in the AST-018 candidate-declaration precedent): a subagent spawned from a parent session reports the **parent's** `CLAUDE_CODE_SESSION_ID` (distinct OS PID only) — so a subagent is the parent session and fails the independence bar. The fresh reviewer therefore exists **only when the PO/ARB starts a new session** and it declares its own identity. The work item is **STOPPED (seq 9)** — an explicit **CONTINUATION** (recordedBy governance/human) must precede any REGISTER(governance); this prompt creates no activation.

---

# Candidate declaration — KOS-OPERATING-MODEL-001 (Governance adoption review)

You are the candidate independent Governance adoption-review actor for:

    KOS-OPERATING-MODEL-001  (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL)

Before doing any review:

1. Determine your actual process identity from `CLAUDE_CODE_SESSION_ID`.
2. Verify that you are not:
   - `259c1966`  (implementation producer — producer bar, R-34/EP-02)
   - `fc59bb0a`  (independent verifier)
   - `5c0e13c1`  (AST-018 implementation actor)
   - `8a525719`  (AST-017 producer)
   - `b51dba91`  (CORRECTION-001 author; also the recording process of this appointment)
   - `8deac5de`  (re-verifier)
   - `d1612e03`  (prior verifier)
   - `b64828fe`  (Governance)
   - `7c2690ae`  (would-be verifier)
   - PO/ARB
3. Read the authoritative project/workflow state.
4. Do not create a workflow record.
5. Do not REGISTER yourself.
6. Do not HANDOFF.
7. Do not START.
8. Do not modify the operating model, the implementation, or the workflow record.

Notes on the commissioned binding mechanism (read-only):

- The work item is **STOPPED (seq 9)** — verified, **NOT ADOPTED**. Only an explicit **CONTINUATION** (recordedBy governance/human) exits the sticky STOPPED state. AST-019 (`ActivateCommissionedFreshSession`, `.claude/scripts/activate-commissioned-fresh-session.php`, AMENDMENT-001, commit `98575324`) **never writes a CONTINUATION** (GO-21) — `check` on this item returns NOT POSSIBLE while STOPPED.
- AST-019 is **IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED** (§38 four states). Once the CONTINUATION is recorded, a fresh eligible governance session binds its discovered runtime identity to the commissioned role via AST-019 `activate` (`REGISTER → HANDOFF → START` through AST-015 `append`), or by Governance. The role comes from the authoritative commission (AST-018 `next-actor`), never from this prompt alone — if `prompt says governance ∧ commission says something else → MISMATCH → STOP` (GO-08).
- You may self-bind identity; you may **never** self-choose role, scope, work item, or authority.

Return:

    PROCESS IDENTITY:
    INDEPENDENCE:
    ELIGIBILITY: ELIGIBLE / NOT ELIGIBLE
    CURRENT AUTHORIZATION: NOT AUTHORIZED

STOP.

---

**Traceability:** PO/ARB in-session direction 2026-08-22/23 (verbatim: *"appoint a fresh governance adoption reviewer"*) · governance adoption-review appointment registration (`…-KOS-OPERATING-MODEL-001-GOVERNANCE-ADOPTION-REVIEW-APPOINTMENT-registration.md`) · verifier candidate-declaration prompt (`…-KOS-OPERATING-MODEL-001-VERIFIER-CANDIDATE-DECLARATION-prompt.md`) · verifier appointment registration (`…-VERIFIER-APPOINTMENT-registration.md`) · implementation appointment registration (`…-APPOINTMENT-registration.md`) · AMENDMENT-001 / AST-019 (`…-AMENDMENT-001-ActivateCommissionedFreshSession.md`, commit `98575324`) · START act seq 3 (declared sequence) · STOP seq 9 (AST-015) · AST-018 `next-actor` (WORK_ITEM_STOPPED) · AST-019 `check` (NOT POSSIBLE) · candidate-declaration precedent (`…-KOS-NEXT-ACTOR-ORCHESTRATION-001-CANDIDATE-DECLARATION-prompt.md`) · empirical identity probe (subagent inherits parent `CLAUDE_CODE_SESSION_ID`) · `INV-ATTR-1/2` · `G-3` · `P-3` · `EP-02`/`R-34` · `ES-004.3` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
