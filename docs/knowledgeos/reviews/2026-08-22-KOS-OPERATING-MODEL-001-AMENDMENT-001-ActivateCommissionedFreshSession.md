# `KOS-OPERATING-MODEL-001` — AMENDMENT-001: capability `ActivateCommissionedFreshSession` (AST-019)

**Work item:** `KOS-OPERATING-MODEL-001` (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL) — FOLLOW-UP/AMENDMENT slice · **Component:** `CMP-004` (workflow_engine) · **Asset ID:** `AST-019`
**Document type:** L1 operating-model **AMENDMENT** — additive (ES-004.3: history never rewritten; the verified operating model + the two START GATE REFUSALs stand unchanged)
**Date:** 2026-08-22
**Recorded by:** the implementing session (GO-series producer) — *(disclosed capacity: ENGINEERING-IMPLEMENTATION + GOVERNANCE-RECORDING; recording/implementing ≠ verifying ≠ accepting/adopting ≠ deciding)*
**Placement derived:** `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0) · `reviews/` subfolder per the approved plan (`ES-004.2` filename convention)
**Canonical plan:** `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` (EP-01 approved 2026-08-22)
**PoA-commission ref:** `G-KOS-OPERATING-MODEL-001-AMENDMENT-001` — PO/ARB follow-up amendment commission 2026-08-22

> ⛔ **This amendment records the PO/ARB's commission and the engineering slice's implementation. It creates no authority, no lane, no record, no grant, no state change.** The capability is **IMPLEMENTED** (GO-01..GO-25 GREEN) but **NOT VERIFIED** (producer bar, R-34/EP-02 — a fresh independent verifier is the next governed step), **NOT ADOPTED** (no governance adoption review performed), **NOT AUTHORIZED** (no PO/ARB adoption decision claimed). **§38: IMPLEMENTED ≠ VERIFIED ≠ ADOPTED ≠ AUTHORIZED.**

---

## 1 · The gap this closes (PO/ARB's rationale, preserved)

The PO/ARB commission (2026-08-22, verbatim) commissions the missing capability that makes the human-facing Governance model **executable**:

> **HUMAN GIVES BUSINESS ORDER → GOVERNANCE ENGINEER LISTENS → ANALYZES HONESTLY → DETERMINES LAWFUL EXECUTION PATH → CURRENT/FRESH SESSION BINDS ITS REAL RUNTIME IDENTITY TO THE REQUESTED RESPONSIBILITY → CANONICAL WORKFLOW MECHANICS → GOVERNANCE ENGINEER ACTIVE → HUMAN ORDER EXECUTED.**

Under the current flow a genuinely fresh session checks reality, concludes *"I am a valid fresh candidate, but I am not yet authorized,"* and stops — self-registration is prohibited and the human cannot transport a pre-known session UUID. The **success criterion (§4 of the commission)**: the human order *"Start the Governance adoption review of KOS-OPERATING-MODEL-001"* must **not** produce *"no lane therefore refuse"* — a fresh eligible governance session must be able to bind its discovered runtime identity to the commissioned role through the canonical mechanism.

## 2 · The corrected invariant (the refinement)

The refined architecture (PO/ARB 2026-08-22) corrects the old rule *"a session must never register itself"* as **too strong**:

> **A fresh session may register itself only when the desired role and work context are already established by the human's business instruction or an existing governed commission.**
>
> **Unified invariant: Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two.**

**Unified binding model (§16 of the commission):**

| Declaration | Declared by | Mechanism |
|---|---|---|
| intended responsibility (work item + role) | **HUMAN** (business order / existing governed commission) | `--work-item` + `--requested-role` (+ `--human-act` = the recorded human business instruction, G-3) |
| process identity | **RUNTIME** (never the human, never a CLI arg) | `CLAUDE_CODE_SESSION_ID` env, read mechanically (INV-ATTR-2) |
| the binding | the governed bootstrap (this capability) | REGISTER → HANDOFF → START through **AST-015 `append`** |

## 3 · The constrained contract (the ONLY allowed write)

```
current runtime identity                    ← CLAUDE_CODE_SESSION_ID (env, never CLI)
    +  commissioned work item
    +  commissioned role
    +  valid fresh-session declaration
    +  eligibility / independence checks
    +  no existing conflicting assignment
        ↓
the ONLY allowed write:  REGISTER { session = own runtime identity, role = commissioned role }
        ↓  governed HANDOFF
        ↓  human START (G-3)
   <commissioned role> ACTIVE
```

**Invariant (verbatim):** *"A fresh session may self-bind identity; it may never self-choose role, scope, work item, or authority."*

**Safety rule (prompt ≠ commission):** the role must come from the authoritative commission (AST-018 `next-actor`), never from the prompt alone — `prompt says Architecture ∧ commission says Verification → MISMATCH → STOP` (GO-08).

## 4 · Commission resolution (the authoritative role source)

The authoritative commission is consumed **read-only** from AST-018 `next-actor <workItem>`:

| `next-actor.result` | Commission source | Capability behaviour |
|---|---|---|
| `NEXT_ACTOR_REQUIRED` | `role` field = the authoritative next role | require `--requested-role === role`, else **MISMATCH → STOP** (GO-08) |
| `HUMAN_DECISION_REQUIRED` + **empty session set** | **first-binding case** — the commission is the human business order itself | require the requested role named in `--human-act` (bounded case-insensitive word-boundary lexical check) — the mechanical encoding of prompt≠commission for the FIRST session (GO-01) |
| `HUMAN_DECISION_REQUIRED` + `options` contains `DECIDE` | adoption is the human's decision | no fresh binding implied → refuse (GO-21) |
| `WORK_ITEM_STOPPED` | — | exit 65, honest Inv-E blocker; **the capability NEVER writes CONTINUATION** (GO-21) |
| `LANE_ACTIVE` / `ACTIVATION_PENDING` | — | conflicting assignment → exit 65 (GO-13) |
| `AMBIGUOUS` | — | exit 65, governance escalation |

Fresh-session declaration via AST-017 (`session-bootstrap --work-item=<wi> --process-label=<identity>`): must be `UNRESOLVED` (GO-01/GO-03/GO-05). `RESOLVED`/`AMBIGUOUS` → already bound/conflict → `NOT_ELIGIBLE`/`AMBIGUOUS_COMMISSION` (GO-12). `UNRESOLVABLE` → honest capability-gap report (GO-20).

## 5 · L1 operating-model amendments (additive — §16–§20, §31, §30)

These amendments are **additive** to the verified operating model (§21–§25, §38) — nothing is rewritten (ES-004.3).

**§16–§20 · fresh-session handling (replaces the old "must never register itself" rule):** a fresh session may bind its runtime identity to a responsibility when the responsibility (work item + role) is established by (a) the human's business instruction (first binding — role named in the instruction) or (b) an existing governed commission (AST-018 `next-actor` NEXT_ACTOR_REQUIRED). Self-selection of role, scope, work item, or authority remains forbidden; separation of duties (producer ≠ verifier ≠ adoption reviewer ≠ PO/ARB) is preserved — an identity that already holds a lane on the work item is `NOT_ELIGIBLE` (GO-07/GO-12).

**§31 · role transition:** REGISTER's `role` is the commissioned role (validated V3 ∈ declared role set, V4 == authoritative commission); the role is immutable once recorded (R8). START is recorded `recordedBy: human` with the **verbatim** human act (G-3; GO-14) — never fabricated.

**§30 · hard-acceptance GO-series:** the GO-01..GO-25 contract suite (Layer 3) is the executable acceptance evidence for AST-019, minted via grep-collision + `identifier-check.php GO-01` → `INCONCLUSIVE` exit 1 (GO is test-scoped, not a governed register — consistent with OM/N/R/S/P). **Registry-first (BINDING):** AST-019 registered in `.claude/platform/registry.yaml` at `adoption: planned` BEFORE implementation, flipped to `verify` at slice close. **ADOPTED only via the governance path after INDEPENDENT verification.**

## 6 · AST-019 — Layer-2 capability record

| Fact | Value |
|---|---|
| Asset ID | `AST-019` (`CMP-004`'s FIFTH implementation asset; AST-015/016/017/018 unchanged) |
| Path | `.claude/scripts/activate-commissioned-fresh-session.php` |
| Domain concept | `BindRuntimeToRequestedResponsibility` (commission §16) |
| Capability name | `ActivateCommissionedFreshSession` (recorded follow-up + commit `c9762a90`; ES-005.4 never-create-a-second) |
| CLI | `activate` (write: REGISTER→HANDOFF→START) · `check` (read-only validation sibling) |
| Identity | `CLAUDE_CODE_SESSION_ID` env ONLY — never a CLI arg (GO-02); any `--session=` rejected, exit 64 |
| Writes | **ONLY** through AST-015 `append`: REGISTER (predecessor key present) → HANDOFF (token + tokenRef; from null only while mutationOwner null) → START (verbatim humanAct, recordedBy human). **Never CONTINUATION, never AST-018 `appoint`, never a direct record write** (GO-15/17/18/21) |
| Mechanism discipline | AST-015 = sole interpreter + sole writer · AST-017 = read-only fresh declaration · AST-018 = read-only commission · no store-path knowledge (`/runtime/workflow` absent from source) |
| Exit codes | 0 activated / produced check report · 64 usage · 65 refused / INCOMPLETE_SEQUENCE |
| Runtime moments | `ON_DEMAND` — no hook, no SESSION_START wiring |
| Governance tier | 2 (subprocess-only capability) |
| Contract | GO-01..GO-25 (`tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php`) — **GREEN: 25 passed (270 assertions)** |

## 7 · What this amendment is NOT (boundaries)

⛔ **NOT** a modification of AST-015/016/017/018, `operating-model.php`, the verified implementation assets, or the authoritative workflow record (all byte-unchanged; GO-15/16/17/25).
⛔ **NOT** a second workflow engine / direct record write (GO-15/GO-18).
⛔ **NOT** a CONTINUATION writer — a STOPPED work item is an honest Inv-E blocker (GO-21).
⛔ **NOT** an adoption or authorization of anything — activation reports `ACTIVATED`, never ADOPTED/AUTHORIZED (GO-22).
⛔ **NOT** a self-appointment engine — the capability never invokes AST-018 `appoint`; the role comes from the commission (GO-17).
⛔ **NOT** an EKS-07 reopen (no autonomous session creation / automatic actor replacement / automatic adoption).
⛔ This recording does **not** register any lane, grant any authority, or change any workflow state.

## 8 · What was delivered (evidence — §38 four states)

| State | Status | Evidence |
|---|---|---|
| **IMPLEMENTED** | ✅ | `.claude/scripts/activate-commissioned-fresh-session.php` (lint clean) + `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` + this amendment + dev guide + registry `adoption: verify` + session log/CONTEXT |
| **VERIFIED** | ⛔ NOT by this slice | GO-01..GO-25 GREEN (270 assertions) = the engineering slice's **self-verification**; independent verification is a separate governed step (producer bar R-34/EP-02) |
| **ADOPTED** | ⛔ NOT | no governance adoption review performed |
| **AUTHORIZED** | ⛔ NOT | no PO/ARB adoption decision claimed |

**Regression:** full WorkflowEngine suite **147 passed (1577 assertions)** — baseline 122 + 25 new GO tests; AST-015/016/017/018 + `operating-model.php` `git diff --quiet HEAD` clean.

## 9 · What happens next (advisory — assigns nothing, creates no authority)

1. **Fresh independent verifier** — a process that is NOT this producing session verifies AST-019 independently (producer bar; the producing session cannot verify its own work).
2. **Governance adoption review** — the amended operating model's adoption review is a separate governed step: a fresh eligible governance session binds via **AST-019 itself** (the very capability this slice builds) → performs the review → STOP → PO/ARB adoption decision (NOT automatic).

## 10 · Traceability

Work item `KOS-OPERATING-MODEL-001` · PO/ARB follow-up amendment commission 2026-08-22 (`G-KOS-OPERATING-MODEL-001-AMENDMENT-001`; §16 domain concept `BindRuntimeToRequestedResponsibility`; §4 success criterion; §28 GO-01..GO-25; §38 four states) · EP-01 plan `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` (explicitly approved) · registry-first `.claude/platform/registry.yaml` AST-019 (planned → verify) · RED→GREEN `ActivateCommissionedFreshSessionContractTest` GO-01..GO-25 · verified operating model (fc59bb0a VERIFIED · NOT ADOPTED) · START GATE REFUSALs `…-b51dba91.md` / `…-fc59bb0a.md` (the gap this amendment closes) · §21–§25 review/independence · §30 GO-series · §31 role transition · G-3 · Inv C/D/E · R8 · R-34/EP-02 · INV-ATTR-1/2 · ES-004.2/3 · ES-005.4 · P-3 · AST-015/016/017/018 (unchanged) · developer guide `developer_guide/ai_platform/06_activate_commissioned_fresh_session.md` · placement `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)
