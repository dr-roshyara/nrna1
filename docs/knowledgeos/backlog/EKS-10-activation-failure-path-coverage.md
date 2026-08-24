# EKS-10 — Activation failure-path coverage: the live-owner combination is unprotected by a committed test

**Status:** **BACKLOG · ENGINEERING** — registered 2026-08-24 on the PO/ARB act *"RV-F2 → open follow-up"*. ⛔ **Not commissioned; starting requires a human authorization act.**
**Class:** assurance-coverage problem (`CMP-004` contract suite).
**Not:** a defect · a regression · a finding against the adopted `AST-019`.
**Registered by:** Governance (`5928b9f9`). ⛔ **Commissions nothing and does not reopen `AST-019`.**
**Source finding:** `RV-F2`, independent re-verification of `REPAIR-001`, 2026-08-24. **NON-BLOCKING**, carried by the adoption/authorization of the same date.

> ### ⭐ Why this is worth a ticket even though nothing is broken
> **The behaviour is correct. The *protection* for it is not committed.** The assurance currently lives in a verification report; reports do not run in CI.

---

## 1 · Problem

`GO-27`, `GO-28` and `GO-30` exercise the failure paths using a **null-owner fixture** (a `COMPLETED` predecessor). Only `GO-26` uses `armedOwnerFixture()`. The intersection —

> **partial write *with* a live owner**

— which is **the precise condition the original blocking defect `F-1` inhabited** — is **not covered by the committed suite.**

## 2 · Evidence, and what it does and does not show

- Fixture inspection of `ActivateCommissionedFreshSessionContractTest.php`.
- The re-verifier **independently exercised that combination** and all four failure paths **passed** with correct `OWNER` provenance.

**So there is no defect.** What is missing is that the correctness of that combination rests on **one report at one point in time** rather than on a test that re-runs. It is a **future-regression risk**, not a present fault.

## 3 · Why it matters more than an ordinary coverage gap

This is the residue of **`O-1`** — the finding that 25 green contract tests were **structurally unable to see `F-1`**, because every one of them exercised the write path where the owner was legitimately `null`. `O-1` was closed by `GO-26`…`GO-30`. **This item is the last uncovered corner of that same class**, which is precisely why leaving it implicit is unwise.

## 4 · Candidate requirement (remedy is known and small)

Extend the `GO-27` / `GO-28` / `GO-30` fixtures to `armedOwnerFixture()`, so each failure path is asserted **with a live `mutationOwner`** and the `OWNER` provenance of `REGISTER.predecessor` / `HANDOFF.from` is pinned on the failure paths as well as the success path. **`GO-01`…`GO-30` must not be weakened to achieve it** — the `+293/−0` discipline of `REPAIR-001` is the standard.

## 5 · Non-actions

Nothing implemented · no test changed · `AST-019` not reopened or modified · its `ADOPTED`/`AUTHORIZED` states untouched · the contract's pinned range not altered.

**Traceability:** `RV-F2` in `../reviews/2026-08-24-KOS-OPERATING-MODEL-001-AMENDMENT-001-REPAIR-001-INDEPENDENT-RE-VERIFICATION.md` §14 · `O-1` in `../reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` · authorization `../governance/2026-08-24-AST-019-AUTHORIZATION-DECISION.md` §4 · repair `d8a5ee93`
