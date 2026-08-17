# `EM-IMPL-002` — File 1 Acceptance Record (`OperatingCoreApplicationTestCase`)

**Type:** RED-review acceptance (PO/ARB verdicts, registered by Governance) · **Date:** 2026-08-17 · **File @ commit `1f4b4c5f`**

## 1 · Status: 🟢 **APPROVED for the RED baseline**

**The PO's stated ground, registered because it is the reusable principle:** the file *"does not create architecture accidentally"* — it separates **record-mandated constraints** · **implementation conventions** · **open decisions requiring later review**, *"exactly what prevents an AI-assisted implementation from turning test scaffolding into hidden architecture."*

## 2 · The six dispositions

| # | Item | Verdict |
|---|---|---|
| 1 | Constructor parameter **order** | 🟢 **fixture convention only.** ⛔ **It must NOT become an ADR.** GREEN may preserve it because RED uses it, but the architectural meaning is: **dependency SET = governed · dependency ORDER = accidental.** |
| 2 | UC-2 sources `RequiredVotes` via the **AG-2 repository** | 🟢 **APPROVED** — the application consumes an **already-established** denominator and never recreates constitutional mathematics. |
| 3 | UC-4's repository access | 🟢 **APPROVED with a GREEN gate condition** (§3) — highest-risk area; the handler stays an orchestrator. |
| 4 | `handle()` / `execute()` | 🟢 accept — application vocabulary, not domain vocabulary; no ADR. |
| 5 | Domain **value objects in commands** | 🟢 **accept and PREFER** — the application transports domain values rather than primitives it would have to re-mean; the DTO carries values and **validates no business rule**. |
| 6 | Handler **return shapes** unpinned | 🟢 **keep open** — ⛔ invent no `HandlerResult`/`ApplicationResponse`/`CommandOutcome` until a real need appears; *record fact or record refusal* is the governed behaviour, and transport shape is not yet a domain concern. |

## 3 · GREEN review obligations — BINDING, checked at GREEN review and by the verification lane

1. **UC-2 may READ the established denominator only.** ⛔ It may not validate, calculate or reinterpret it.
2. **UC-4 acceptance criterion, verbatim:** *"`ReportPeriodExpiryHandler` contains no P-6 predicate logic. It only gathers state and delegates consequence meaning."* Allowed: load committee/gate/recovery → hand to P-6. **Forbidden: any `if` chain over smallness/failure/success/expiry that CHOOSES a consequence.**
3. **The application must never reproduce domain predicates** — the generalisation of G-1/G-5, now with a named inspection point.

## 4 · Architectural standing of the file

**BOUNDING RULES (governed):** closed port universe · command/query separation · DTO restrictions · **no identity surface · no time surface** · no authority dependency · no domain-rule implementation.
**NON-ARCHITECTURAL CONVENTIONS (not governed, not ADR material):** constructor order · `handle()`/`execute()` naming · fixture helper arrangement.

## 5 · Confirmed sequence

**Phase 1B — the six Support doubles** *(the correct next gate: they define the testing world in which GREEN behaviour is judged)* → **handler-skeleton reviews** → **GREEN-2 (`ExpressCommitteePositionHandler`)** → **one use case at a time** → **the frozen suite (42/2420) re-run after each increment.**

**Traceability.** Commit 1 `1f4b4c5f` · RED acceptance record · grant G-1…G-5 / Q-1…Q-3 · authorized boundary (A-3 IN · A-4 OUT · A-7 query form) · A-3.
