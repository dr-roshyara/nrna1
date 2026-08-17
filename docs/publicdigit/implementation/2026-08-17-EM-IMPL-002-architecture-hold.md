# `EM-IMPL-002` — ARCHITECTURE HOLD before GREEN-5 (PO/ARB, 2026-08-17)

**Type:** Governance registration · **GREEN-4 ACCEPTED and NOT reverted.** ⏸️ **GREEN-5/6/7 ON HOLD** until three items close.

## 1 · The acceptance, and why it stands

> **The PO's ground, registered because it is the reusable standard:** *"The handler stopped where the authorized model stopped. It did not invent missing domain concepts."* — **and: the two STOP items are "exactly the kind of architectural discoveries that DDD implementation should reveal. Do not fix them inside UC-3."**

Credited in the review: no `ServicePolicySnapshot` on UC-3 (restoration is not a new period) · **W-8 honoured by re-derivation, never by an act** · idempotence by verdict comparison with no flag/cache/table · protocol maps domain outcomes, never application guesses.

## 2 · The four findings, with the PO's severities

| # | Finding | Severity |
|---|---|---|
| **1** | **Aggregate absence taxonomy** — UC-1 inline throw · UC-2 named method · **UC-3 raw null dereference.** ⚠️ **The PO's decisive framing: the problem is not that the exceptions differ but that THE MEANING DIFFERS ACCIDENTALLY.** Absence may mean **caller error · infrastructure inconsistency · legitimate not-yet-established lifecycle state** — three different concepts, and *"the application layer must not silently choose."* | 🔴 **HIGH — close before GREEN-5** |
| **2** | **`Q-RESTORE` provenance** — the model knows *recovery exists*, not *recovery started because gate X halted*. **The missing concept is CAUSAL PROVENANCE.** The proxy is exact only while one acceptance decision exists; ⚠️ **the danger named by the PO is not a test failure today but "silent future semantic corruption."** | 🔴 **ARCHITECTURAL — the more important of the two** |
| **3** | **GREEN-3's guard is unpinned** — conceptually present, mechanically unprotected. | 🟡 MEDIUM |
| **4** | **The denominator rule is now reused**, so it is **PROMOTED from a GREEN-2 decision to an APPLICATION BOUNDARY RULE.** | 🟡 governance |

## 3 · 🏛️ The promoted rule — registered as a standing application boundary rule

> ## **"Application consumes established constitutional values. Application never derives constitutional mathematics."**

**This generalises `GREEN2-001` and the AG-2 denominator ruling into one sentence, and it is the DDD principle the increment exists to protect.** It binds every present and future handler and query of this subsystem.

## 4 · Provenance options as the PO framed them (none chosen; ⛔ one needs domain authorization)

**A — `RecoveryProcess` owns `originatingGate`** *(the PO's preference)*: no new port, causality stays with the domain, strongest DDD fit — ⛔ **a DOMAIN change, and the domain is FROZEN: it needs its own PO authorization.**
**B — provenance carried on the `RecoveryPeriodStarted` fact**: immutable history derives it — also rated good.
**C — a protocol read port**: ⛔ **the PO would avoid it** — *"it expands architecture only to compensate for missing domain state."*

## 5 · The hold's own order (Steps A–D, then authorization)

**A** close the failure taxonomy (ADR **candidate** → PO ruling → *then* normalize UC-1/2/3) → **B** rule `Q-RESTORE` ownership (decide, do not implement) → **C** add the RED pin **as its own commit** → **D** re-run application suite · frozen suite · structural guards → **only then GREEN-5 authorization.**

⚠️ **Governance's clarifications sent with the dispatch:** the ADR is a **candidate with a blank decision block** — the lane presents the three meanings and may not choose · item 2 is **investigate-and-propose only**, since the preferred direction is a frozen-domain change · item 3 is a **RED amendment and therefore its own commit before any GREEN** · and the still-unpinned GREEN-3 guard may be *proposed* for the same slice but never silently folded in.

**Traceability.** GREEN-4 verification record · commits `1f4b4c5f`→`d2a0fe7c`→`8ea13835`→`cdc45f99`→`4651a3e7` · the five registered conditions · A-9.
