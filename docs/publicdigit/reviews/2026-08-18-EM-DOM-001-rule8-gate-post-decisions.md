# `EM-DOM-001` — Rule-8 authorization / dependency gate **after** D1–D4

**Run by:** the governance recording session · 2026-08-18 · **Trigger:** the authorization state **actually changed** — D1 places Act B in scope.
**Read-only.** Nothing implemented, nothing prepared. Domain core byte-identical to `1f4b4c5f`; `app/` and `tests/` untouched.

## Verdict

> # 🟡 PARTIALLY UNBLOCKED — one act authorized, **nothing executable today**, and `GREEN-5` is now blocked by D3's own deferral.

## Findings

| # | Question | Finding |
|---|---|---|
| **G-1** | Does defining the contract require changing any **existing** frozen file? | ✅ **No.** `ElectionOperationalStatus` is semantically complete (7 public members) and needs no change. **The authorized scope is coherent: Act B adds, it does not modify.** |
| **G-2** | Can a retrieval contract be defined **without** selecting a boundary, as D1 and D4 both require? | ✅ **Yes** — all three `BND-3` candidates *(distinct aggregate · part of a lifecycle aggregate · projection)* share the **same retrieval key (`ElectionId`)** and the **same already-frozen return type**. ⚠️ **BUT SEE G-2a.** |
| **G-2a** | ⚠️ **Placement / naming hazard** | 🔴 **All three files in `Domain/OperatingCore/Repository/` carry aggregate semantics (`AG-1`/`AG-2`/`AG-3`) in their docblocks.** **A fourth `…Repository` placed there would inherit that vocabulary and thereby assert aggregate standing — which D1 ("not … selection of the overlay's aggregate or persistence boundary") and D4 ("must not use D1 as authorization to select the overlay's aggregate boundary") both forbid.** ⇒ **Derived constraint: the contract must not be named or placed so as to encode a boundary claim.** *(Consistent with ADR-1 §6(c), which deliberately does not prescribe "a repository method".)* |
| **G-3** | Is **DEP-5b** now dischargeable? | 🟡 **DEFINABLE, not DISCHARGEABLE.** The contract may be defined; making recorded status **retrievable in practice** requires **act C (persistence/adapter)**, which D1 **expressly does not authorize**. |
| **G-4** | Is **DEP-6** unblocked? | 🔴 **No.** It needs **R-2** (a producer policy stating the permission invariant) and **R-3** (an event able to express no-target). **D2 selects no representation and authorizes neither.** |
| **G-5** | Is **DEP-2** dischargeable? | 🔴 **No.** D3 defers `BND-1`, so the *phase* discriminator has **no owner**. Per ADR-1 constraint ⑥, *"if the required domain contract does not exist, implementation stops."* |
| **G-6** | ⚠️ **Consequence the decisions do not state** | 🔴 **D3's deferral keeps the ENTIRE `ADR-1` §6(c) normalization slice blocked, and therefore `GREEN-5` stopped — even if D1's contract work completes.** §6(c) authorizes **ONE** slice over UC-1/UC-2/UC-3 whose criterion is ***"each handler conforms to the governed domain meaning of EVERY absent reference it encounters"***, and it names **UC-2 and UC-3 as requiring `AcceptanceDecision` absence to conform.** That conformance is `DEP-2` → `BND-1` → **deferred.** ⇒ **`GREEN-5` cannot be reached through D1 alone.** *(This follows from ADR-1 §6(c) + constraint ⑥. It is a dependency reading, not a new decision, and it is surfaced because it is not evident from D1–D4 read alone.)* |
| **G-7** | **Who may execute Act B?** | 🔴 **No one today.** `EM-DOM-001` still reads **⏸️ NOT STARTED**; Obligation 4 requires a **FRESH Domain lane**; none is designated; and a standing **STOP** directive is in force. **Authorized ≠ started.** |
| **G-8** | DEP-1 · DEP-3 · DEP-4 · DEP-10 | **Unchanged.** DEP-4's core invariant already exists (`PeriodKind` + P-6). **DEP-10 remains class A and PROTECTED** — and its protection is now ratified in the authorization itself by **Amendment 1** (obligation (9) corrected to exclude DEP-10). |

## What the four decisions changed, precisely

| | Before | After |
|---|---|---|
| **Act B** (domain-side identity/retrieval contract) | ⚠️ ambiguous — the lane refused to read it | ✅ **IN SCOPE** |
| Acts **A**, **C**, **D**, **E** | unauthorized | ⛔ **still unauthorized — D1 names each one explicitly** |
| **`w8` semantics** | signed but framed on *"without a prior `RecoveryProcess`"* | ✅ **clarified: `Restoration ≠ Resumption`; the halt-absent path is covered by ADR-2 §6(b)/(h)** — a clarification, **not** an amendment |
| **`w8` representation** | undecided | ⛔ **still undecided; D2 authorizes no representation** |
| **`BND-1`** | open | ⏸️ **explicitly DEFERRED — open, not resolved, not rejected, not merged with `EM-OPEN-055`** |
| **`BND-3`** | open | ⏸️ **explicitly DEFERRED — no boundary selected; *identity + persistence ≠ aggregate* preserved** |
| **Appendix Q** | conditional pre-constraint | ⚠️ **now LIVE** — its eight-item negative list is operative, and D1's own text restates it |

## Standing constraints, re-verified against the new state

⛔ **An ADR signature is not layer-wide implementation authorization.** ⛔ **A scope authorization is not solution authorization** — D1 places Act B *in scope*; it selects no solution. ⛔ **A recommendation is not a decision** — Appendix R remains recommendation only and **contributed nothing** to D1–D4, which were supplied verbatim by the PO/ARB.

**Traceability:** decision surface D1–D4 *(verbatim, byte-verified)* · Appendix Q *(now live)* · Appendix R *(recommendation only)* · ADR-1 §6(c) + constraints ①–⑨ · ADR-2 §6(b)/(f)/(h) · `EM-DOM-001` authorization + Annotations A/B + **Amendment 1** · prior gate `c4828cdd` · `EM-GOV-059(b)`/`062` · P-6 · P-7 · `1f4b4c5f`.
