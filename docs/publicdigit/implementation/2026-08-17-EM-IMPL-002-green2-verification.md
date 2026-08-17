# `EM-IMPL-002` GREEN-2 — Governance verification record (UC-1)

**Date:** 2026-08-17 · **Commit 2:** `8ea13835` (body-only, one file) · **Verified by Governance's own runs and scans, not the lane's report.**

## 1 · Observed

| Check | Result |
|---|---|
| Footprint | ✅ **exactly one modified file** (the UC-1 handler); no new file, no test touched |
| Domain core | ✅ **byte-identical** (empty diff) |
| Other bodies | ✅ four handlers + four queries **still throw** |
| Application suite | **36/16 → 28/24** — the **8** that moved are UC-1's six + its two refusal-taxonomy tests. **Nothing outside UC-1 moved.** |
| Structural guards | ✅ **16/16** still passing |
| Frozen suite | ✅ **42 passed / 2434 assertions** |
| Forbidden calculations (gate ¶2) | ✅ **none** — no `count()`, majority, threshold comparison, quorum, `ceil`/`intdiv`, `acceptCount`/`objectCount`; the single textual match is a doc comment asserting their absence |
| Overlay consulted? (RED-2/Meaning-1) | ✅ **never** — no `unableToFunction`/`OperationalCondition`/`Inoperative` reference: Meaning-1 holds **structurally** |
| Primary fact | ✅ **domain-returned** (`expressPosition()`), never constructed |

## 2 · ⚠️ The raised clause-5 tension — ADJUDICATED, overrulably

**The lane's issue:** gate clause 5 says `GateSatisfied` must be emitted *"only through domain behaviour"*, yet the frozen domain exposes **no operation returning it** — `expressPosition()` returns the position fact and P-2 returns a `GateIntervalState` enum. Emitting it "through domain behaviour" would need a domain change, which is forbidden.

**Governance's reading — the RED suite settles it:** the accepted RED suite contains the guard **`g4 no application source constructs a primary ACT fact`**, and it **passes**. That guard deliberately distinguishes **primary act facts** (forbidden to construct) from **outcome facts** (permitted). **The PO accepted that suite. A strict clause-5 reading would make the accepted RED contract unsatisfiable without a domain extension — so the record already chose.** This is also exactly what **A-9** pre-registered at the boundary review: *F-2 has no P-4-analogous onset policy, so Increment 2 realizes it as handler orchestration, traceable to approved F-2, acceptable only if RED pins it — and RED does.*

**Clause 5's purpose is satisfied:** the **meaning** is entirely the domain's — P-2 decides the classification and the handler only branches on the domain's verdict. **⛔ If the PO reads clause 5 strictly, this commit's outcome-fact emission must be reverted and UC-1 awaits a domain slice with its own authorization. Say so and it is undone.**

## 3 · Registered from the lane (accepted as reported)

**Q-REF = record + rethrow**, narrow catch (only `SeatAlreadyExpressedPosition`), so a caller error propagates **unrecorded** — the protocol holds no entry about a subject that never existed (`EM-GOV-005` negative half). **The pin can now be tightened.**
**Baseline restatement:** the frozen suite is **42/2434** for Increment 2 (the +14 is its own D-1 scan covering GREEN-1's files; **the lane flagged this rather than reporting "unchanged"** — correct).
**Three choices flagged for the pin-tightening list:** the idempotence mechanism (classification-change comparison — reused by UC-2/UC-3, worth an explicit ruling) · `InvalidArgumentException` for aggregate-not-found (analogy with the frozen core's caller-error style) · `HistoryKind` passed by the handler per §9b and the RED-pinned table.
**New finding (accepted, guard untouched):** the **W-2 guard is a raw text scan**, so it forbids the overlay's type names **even inside comments** — stricter than the architectural rule. The lane rephrased its own source and **did not touch the accepted guard**, which is correct conduct. **Candidate refinement: strip comments before scanning.** Until then the UC-1 file cannot document the overlay by name.

**Traceability.** RED `1f4b4c5f` · GREEN-1 `d2a0fe7c` · GREEN-2 `8ea13835` · A-9 · the accepted RED contract's g4 guard · gate ¶1–¶6.
