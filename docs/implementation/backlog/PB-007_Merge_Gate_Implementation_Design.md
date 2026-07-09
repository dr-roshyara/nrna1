# PB-007 (Greenfield Merge Gate) — Implementation Design Document (IDD)

**Status:** Design — awaiting ARB review. **No code/config until approved.** **2026-07-10.**
**Grounds:** Discovery `docs/implementation/PB-007_Discovery_Findings.md` (4 ARB rulings applied: gate taxonomy Architecture/Behaviour/Engineering/Improvement/Reporting · Local/CI per gate · single entry point · DoD-warning classified with evidence).

## 1. Purpose
Harden the engineering process that protects the qualified architecture: one executable **Greenfield Merge Gate** where **Architecture + Behaviour + Engineering gates block** and **Improvement/Reporting gates trend**. PB-007 proves conformance protection — it changes no product behaviour.

## 2. Slices (each: evidence → stop at the boundary)
- **7A — Deptrac (Architecture gate):** install the binary (**A-1**: `composer require --dev deptrac/deptrac` — lockfile + network, explicit approval requested); widen `deptrac.yaml` scope to **Election** (a complete hexagonal context since PB-004); add a **Shared-Messaging layering decision** (**A-2** proposal: Shared is a platform layer that contexts' Application/Infrastructure may depend on; Shared's own purity is covered by fitness tests, so Deptrac rules for Shared = context-isolation only). First run in **report mode**; genuine violations become **classified ARB findings — never silent fixes**; then flip to fail mode.
- **7B — CorrelationId-minting fitness test (deferred from PB-006):** executable rule — only chain-origin code may call `EventProvenance::start()`; reacting handlers/adapters must use `fromConsumed()`. Hosted in the Messaging fitness suite (owner-hosts-the-guard, ADR-MP-03). RED-first.
- **7C — Infection (Engineering Improvement):** `infection.json` scoped to the greenfield Core (Contestation/Adjudication/Election + Shared Inbox/Outbox/Messaging); one run → record the **baseline MSI**; **A-3** ratchet policy (raise deliberately at milestones; never retroactive; no enforcement inside PB-007).
- **7D — Single entry point + CI:** composer scripts **`merge-gate`** (fitness suites → Deptrac → greenfield PHPStan → widened regression; fail-fast; one PASS/FAIL) and **`quality-gate`** (Infection, coverage). New workflow **`greenfield-merge-gate.yml`** running `composer merge-gate` on PRs; quality tier separate/scheduled; existing workflows untouched.
- **7E — Qualification + Completion Review:** Architecture + DDD + Trustworthiness (the gate must not weaken any invariant — it only enforces) · EP-02 → **STOP** (the retrospective follows, not started).

## 2a. ARB refinements (binding, applied 2026-07-10)
- **R1 — Rules from architecture, not filesystem:** Deptrac rules are derived from the APPROVED architectural model (bounded contexts, hexagonal layers, approved dependencies) — never from the current package layout alone. The tool must not codify a temporary implementation detail.
- **R2 — Ownership:** PB-007 only HOSTS the CorrelationId-minting fitness test; the invariant remains OWNED by the Messaging Platform architecture (ADR-MP-06; owner-hosts-the-guard ADR-MP-03).
- **R3 — Stable interface:** `composer merge-gate` is a STABLE PUBLIC INTERFACE to the engineering system — internal tooling may change; the command contract must not.
- **Slice reorder (ARB):** the CI workflow orchestrates FINISHED gates, so it moves last: **7A Deptrac → 7B minting fitness → 7C Infection → 7D composer entry point → 7E CI workflow → Qualification/Completion**.
- **Approvals granted:** A-1 ✅ (report mode first, lockfile change isolated to this slice) · A-2 ✅ conditionally (Shared = infrastructure/platform layer; Deptrac enforces only APPROVED dependency rules, infers no new architecture) · A-3 ✅.
- **Rollout order (never inverted):** Install → Report → Classify violations (ARB findings, never silent fixes) → Fix intentionally → Fail mode.

## 3. Boundaries
No domain/aggregate/platform-logic changes · pre-existing findings recorded, not fixed (7A report-mode findings go to the ARB) · no new patterns · the frozen `.claude` platform untouched.

## 4. Approvals requested with this IDD
- **A-1:** `composer require --dev deptrac/deptrac` (lockfile + network change).
- **A-2:** the Shared-layer Deptrac proposal (§7A) — or rule Shared differently.
- **A-3:** Infection baseline-then-ratchet policy (no enforcement in PB-007 itself).

## 5. Evidence at completion
Single-command `composer merge-gate` PASS output · Deptrac report (violations classified or zero) · minting fitness test RED→GREEN · baseline MSI recorded · CI workflow green · widened regression exact numbers · triple qualification · Completion Review.

## STOP — awaiting ARB review of this IDD (+ approvals A-1..A-3) before any implementation.
