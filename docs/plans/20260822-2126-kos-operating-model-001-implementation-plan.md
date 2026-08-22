# `KOS-OPERATING-MODEL-001` — Implementation Plan (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL)

**Work item:** `KOS-OPERATING-MODEL-001` · **Lane:** `claude-code-session:259c1966-b18e-4759-8afe-b46627dd5a2f` (role `implementation`) · **Date:** 2026-08-22
**Governing plan (approved by PO/ARB, verbatim):** `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md` (40 sections). This document is its **operational breakdown** — it creates no new scope and no new authority.
**Authority chain (recorded):** PO/ARB appointment act 2026-08-22 (name confirmed + all-three-layers authorized + fresh session appointed) → REGISTER/HANDOFF/START (seq 1–3) → AST-017 bootstrap folds **RESOLVED · attribution=MATCH · authorized_to_act=true** → PO/ARB in-session direction *"now start implementing."*

---

## 1 · Objective

Deliver all three authorized layers of the final Governance + Communication operating model, per the verbatim prompt:

1. **Operating model** (primary deliverable — documentation): the human-facing operating layer — dedicated **Governance Engineer** + transferable **Communication Engineer**, role-transition boundary, fresh-session handling, review lifecycle, adoption lifecycle, and the rule that **the human never operates workflow mechanics** (§1, §29, §30, §39).
2. **Minimal supporting capability** (software): a thin, read-only presenter that consumes the existing canonical mechanisms (AST-018 `next-actor-orchestration.php` · AST-017 `session-bootstrap.php`) and renders the **exactly-one business outcome** (§11) and the **six human cases** (§29) in business language — never a second workflow engine.
3. **Verification / acceptance**: ≥ 30 named tests (§36, RED→GREEN, scoped naming) + a §38 completion report distinguishing IMPLEMENTED ≠ VERIFIED ≠ ADOPTED ≠ AUTHORIZED.

## 2 · Background

PO/ARB created `KOS-OPERATING-MODEL-001` (Option B) while preserving `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` as a separate slice. The workflow estate already provides: AST-015 (single workflow authority, `workflow-state.php`), AST-016 (read-only assignment resolver, `session-resolve.php`), AST-017 (read-only session bootstrap, `session-bootstrap.php`), AST-018 (next-actor orchestration + business-language handoff, `next-actor-orchestration.php`). The operating model **consumes** these; it does not re-open them.

## 3 · Canonical Discovery — what already exists (consumed, never duplicated)

| Need (§ prompt) | Existing canonical asset | This slice's relationship |
|---|---|---|
| Next-actor decision rendering (§28) | AST-018 `next-actor` | consumed as subprocess |
| Fresh-session kickoff prompt (§14/§16) | AST-018 `prepare-next-session` | consumed as subprocess |
| Appointment / REGISTER→HANDOFF→START (§8/§18/§32) | AST-018 `appoint` (human-act required, G-3) | consumed — delegated, never re-implemented |
| Process identity / eligibility / attribution (§15/§16/§19) | AST-017 `session-bootstrap.php` | consumed as subprocess |
| Workflow state / mutation owner / predecessor (§17/§18) | AST-015 `fold` (via AST-017/AST-018) | consumed transitively |
| Governance Engineer / Communication Engineer (§3–§5) | adopted six-role operating model (2026-08-19) | rendered as responsibilities, not roles/services/positions |
| Review independence (§22) | `REVIEW_INDEPENDENCE_POLICY` | policy placeholder — never hard-coded, never invented |

**The genuinely new, additive content:** the **§11 five-business-outcome classification** (`CONTINUE / PERMISSION_REQUIRED / FRESH_SESSION_REQUIRED / GOVERNANCE_DECISION_REQUIRED / STOP`), the **§29 six-case rendering**, and the **§19/§20 session MATCH / MISMATCH / candidate presentation** — all rendered over AST-018/AST-017 outputs.

## 4 · Scope

### Layer 1 — Operating model document
`docs/knowledgeos/governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md` (placement derived: `scripts/doc-placement.php` → `docs/knowledgeos`, exit 0).
Covers: core business principle (§1) · Governance Engineer (§2–§3, §5–§7) · Communication Engineer (§4–§5) · initial governance analysis (§6) · role transitions & human START boundary (§7–§9, §32) · current actor work (§10) · the five business outcomes (§11–§17) · fresh-session handling (§14, §16–§20, §31) · review lifecycle (§21–§24) · final adoption (§25) · boundaries (§26–§28) · human-friendly contract & six cases (§29) · hard acceptance criteria (§30) · DDD responsibility model (§33) · local governance rule (§34) · failure/stop model (§35) · testing (§36) · no scope expansion (§37) · success criterion (§39) · final architectural principle (§40).

### Layer 2 — Minimal supporting capability
`.claude/scripts/operating-model.php` — read-only presenter. Two commands:
- `outcome <workItem>` — classify the current governed state into **exactly one** of the five business outcomes (§11) from AST-018 `next-actor` (+ `prepare-next-session` when FRESH_SESSION_REQUIRED), render the matching §29 case in business language. Exit 0 for a produced report.
- `session <workItem>` — render the fresh-session presentation from AST-017 bootstrap verdict + AST-018 next-actor: MATCH (§19 "This is the assigned session. You may continue."), MISMATCH (§20 / CASE 4 + recovery), candidate (§18 "A fresh eligible actor is available."), or governance escalation (CASE 5). Read-only.

No write commands. Appointment, prompt preparation, and stop are AST-018's commands, invoked directly per the operating-model document.

### Layer 3 — Verification / acceptance
`tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php` — ≥ 30 named tests `test_om_*` (distinct from existing `n/r/p/s/t/usage` prefixes), hermetic temp records built **through** AST-015, RED by absence first. Plus the §38 completion report as a review artifact.

### Developer guide
`developer_guide/ai_platform/05_operating_model.md` (+ update `00_index.md`) per the Definition-of-Done rule.

## 5 · Design decisions

- **D-1** The operating model is primarily a **documented operating procedure**. The capability is the minimal software that renders it.
- **D-2** The five business outcomes are a **rendering of the existing result model** (§11) — never a second workflow-state vocabulary, never a new state machine.
- **D-3** `operating-model.php` knows **no store path**, performs **no fold**, **no raw-record read/write**, invokes AST-015 only transitively via AST-017/AST-018 subprocesses, and is **read-only** (pinned by tests).
- **D-4** `session` **renders reported facts only** — it never derives identity, eligibility, or authorization; it maps AST-017's `attribution` (MATCH/MISMATCH/UNKNOWN) and verdicts to §19/§20/§18 business language.
- **D-5** Governance Engineer / Communication Engineer are **operating responsibilities** (adopted six-role model 2026-08-19), never new workflow roles, services, or positions (P-3).
- **D-6** `REVIEW_INDEPENDENCE_POLICY` stays a **policy placeholder** — the implementation supports the concept, never invents final policy.
- **D-7** Human START (G-3) is **never fabricated**; every write path delegates to AST-018 which requires a recorded `--human-act`.
- **D-8** Human-facing output hides mechanics by default; `--show-mechanics` is the only way technical detail surfaces (mirrors AST-018 N-15).
- **D-9** Deterministic output for identical inputs; byte-identical JSON across runs.

## 6 · Task checklist (RED → GREEN → VERIFY)

- [x] 0. Governed lane recorded + bootstrap RESOLVED (authorized_to_act=true) — completed earlier this session
- [x] 1. Write this plan (ES-004.2)
- [x] 2. Write RED test `OperatingModelContractTest` (43 `test_om_*`, ≥ 30 required) — hermetic
- [x] 3. Run RED — confirm failures by absence
- [x] 4. Implement `.claude/scripts/operating-model.php`
- [x] 5. Run GREEN — 43 tests / 409 assertions; AST-015/016/017/018 regression suites 78 tests / 876 assertions still green; canonical assets byte-unchanged (test_om_37)
- [x] 6. Write the operating-model document (Layer 1)
- [x] 7. Write developer guide (`developer_guide/ai_platform/05_operating_model.md`) + index
- [x] 8. Update plan progress, CONTEXT, session log (ES-004.3)
- [x] 9. Write §38 completion report (IMPLEMENTED ≠ VERIFIED ≠ ADOPTED ≠ AUTHORIZED)
- [x] 10. Commit (one story · one commit · subject carries `(KOS-OPERATING-MODEL-001)`) — `39e953dd`
- [x] 11. Record lane STOP (seq 4 via AST-015, per the START act's declared sequence) — `workItemState: STOPPED`; bootstrap fail-closes to UNRESOLVED → next `governance`; AST-018 `WORK_ITEM_STOPPED`

## 7 · Boundaries (binding — from §26–§28, §37)

⛔ no AST-015/016/017/018 modification · ⛔ no second workflow engine · ⛔ no EKS-07 reopening · ⛔ no autonomous session creation · ⛔ no automatic actor replacement · ⛔ no automatic adoption · ⛔ no migration · ⛔ no new authority model · ⛔ no second workflow-state vocabulary · ⛔ no adoption claim (`implemented ≠ adopted ≠ authorized`).

## 8 · Risks

- **Scope drift into AST-018 territory** — mitigated by D-3 (consume-only subprocess calls) and the test suite pinning no-store-path/no-raw-access/no-write.
- **Duplicating AST-018 rendering** — mitigated by rendering only the §11 outcomes and §29 cases over AST-018/AST-017 payload fields, never re-deriving decisions.
- **Colliding test identifiers** — mitigated by the `test_om_*` prefix (surveyed: existing suite uses `n/r/p/s/t/usage`).

## 9 · Progress

- 2026-08-22: plan written (this file); lane ACTIVE; bootstrap RESOLVED.
- 2026-08-22: RED written + confirmed by absence → `.claude/scripts/operating-model.php` implemented.
- 2026-08-22: **PO/ARB corrections applied before GREEN** — (1) FRESH_SESSION_REQUIRED = "Start a new session and paste this prompt:" + `<generated prompt>` + "You may use this prompt unchanged or edit it." — never an appointment invitation; human never asked for a session UUID. (2) `session` candidate status is NEVER inferred from UNRESOLVED + NEXT_ACTOR_REQUIRED — it rests on authoritative eligibility/appointment facts. Both pinned in tests (test_om_04, 10, 16, human_03, human_05, 38).
- 2026-08-22: **GREEN** — OperatingModelContractTest 43 tests / 409 assertions; AST-015/016/017/018 regression suites (78 tests / 876 assertions) still green; test_om_37 proves all four canonical assets byte-unchanged vs HEAD.
- 2026-08-22: Layer 1 operating-model document written; developer guide 05 + index updated.
- 2026-08-22: **STOP recorded** (seq 4 via AST-015) — `workItemState: STOPPED`; bootstrap fail-closes to UNRESOLVED (operable=false, authorized_to_act=false, recommended next role = governance); AST-018 reports `WORK_ITEM_STOPPED` (options CONTINUE / LEAVE_STOPPED). Commit `39e953dd`.

## 10 · Open questions / next actions

- Independent verification (after STOP) — **not** performed by this session (R-34/EP-02).
- Adoption — governance path, **NOT** claimed here.
- **Implementation STOPPED (seq 4).** Next actor: **PO/ARB appoints a FRESH INDEPENDENT VERIFIER** (≠ `5c0e13c1` · `8a525719` · `b51dba91` · `8deac5de` · `d1612e03` · `b64828fe` · `7c2690ae`) → Governance REGISTER(verification) → HANDOFF → human START → independent verification → governance adoption review → PO/ARB adoption decision (NOT automatic). This is outside this session's authority.
