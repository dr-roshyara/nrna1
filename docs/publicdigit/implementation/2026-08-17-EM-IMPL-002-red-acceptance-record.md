# `EM-IMPL-002` RED Acceptance Record — authored by the VERIFYING party (Governance)

**Date:** 2026-08-17 · **Commit 1:** `1f4b4c5f` (tests only, 16 files, +2085) · **Authored by Governance because its evidentiary value is that the verifying party, not the implementing lane, attests it.**

## 1 · Independently observed (Governance's own runs, not the lane's reports)

| Check | Observed |
|---|---|
| RED suite | **52 failed, 0 passed** — behavioural tests error on the absent `App\Contexts\Election\Application\OperatingCore\` namespace; structural guards fail on the granted-surface anchor (cannot pass vacuously) |
| Frozen core suite | **42 passed (2420 assertions)** — unchanged |
| Namespace absence | `app/Contexts/Election/Application/OperatingCore` **does not exist** at commit 1 |
| W-10 in the suite's own doubles | the only authority mentions are the prohibition text and the guard's scan patterns; **no implements/mock/stub anywhere** |
| Footprint | exactly the new test directory; nothing under `app/` touched; no existing test modified |

> **RED is ACCEPTED: the suite fails by absence, pins the granted contract, and ordering is git-proven — commit 1 precedes all production code.**

## 2 · A-5 refusal taxonomy — provisionally accepted under the delegated review

The proposed principle — **well-formed-but-rule-refused = recorded refusal (with the domain reason); references-nothing-in-the-record = caller error, propagates unrecorded; a refusal's `HistoryKind` follows the refused act's fact kind** — is **consistent with the record** (`P-2H` refusal-with-reason; the protocol describes reality). **Provisionally accepted for GREEN; final confirmation at independent verification and PO acceptance.**

## 3 · Open questions carried (registered, unresolved)

**Q-UC5** the constitution fact type — the frozen domain defines no event class for `constitute()`. **Phase-2 instruction: realize the pinned "one Lifecycle entry" through the EXISTING protocol surface if the contract allows; if a domain event class is genuinely required, STOP and raise — a domain extension needs its own PO authorization; the freeze is not silently widened.** · **Q-UC4** duplicate-expiry idempotency deferred (A-2/persistence increment) · **Q-REF** refusal propagation shape fixed at GREEN, then the pin tightens · **Q-RESTORE** `returnsToGate` in no-halt restoration pinned to the fixture's only gate — flagged for the verifier.

## 4 · Phase-2 entry (the lane's own restatement, confirmed)

GREEN = the application skeleton **exactly matching the RED contract** (the granted classes, pinned constructor port orders, one public entry point per handler) — no redesign, no new abstractions, no convenience services; anything underdetermined is **raised, not improvised**. The lane commits nothing; Governance verifies and makes commit 2.

**Traceability.** Grant + signature (§3/§3a/§3b of the boundary registration) · commit 1 `1f4b4c5f` · Governance verification runs this record · A-3.
