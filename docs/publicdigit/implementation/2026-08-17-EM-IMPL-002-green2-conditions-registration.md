# Registration — the five GREEN-2 conditions, binding from GREEN-3 onward

**Type:** Governance registration (Session 2 — authored by the verifying party, as the lane correctly declined to author its own ruling record) · **Date:** 2026-08-17

## 1 · Provenance

The five conditions were given by the PO/ARB in the direct channel and recorded by the implementing lane in `developer_guide/election_operating_core/03_step_application_layer_green2_uc1.md`. **Governance registers them here as the governed source; the guide is the developer-facing restatement.** Overrulable as always.

## 2 · The five, as registered

| # | Ruling | Binding consequence |
|---|---|---|
| **1** | **`EM-IMPL-002-GREEN2-001`** — where the frozen domain API exposes only the primary operation result and no secondary protocol fact, the Application layer **may append the authorized protocol facts derived from the returned domain classification**, and **must not calculate those classifications.** *Allowed:* `DecidedPass → GateSatisfied`. *Forbidden:* `votes >= threshold → GateSatisfied`. | ✅ **This settles the clause-5 tension the lane raised — and it settles it the same way Governance's independent adjudication (`b3f6f478`) did.** Two paths, one answer: the accepted RED contract's `g4` guard distinguishes primary act facts from outcome facts, and `GREEN2-001` states that distinction as a rule. **The tension is closed, not merely tolerated.** |
| **2** | **Idempotence by verdict comparison is permitted** — the Application may detect whether a domain invocation produced a **protocol-relevant state change**; it may not determine whether the transition is legally valid, nor derive its consequence. | UC-2/UC-3 reuse the before/after enum comparison; ⛔ **never a stored "already emitted" flag — that would be W-4.** |
| **3** | ⚠️ **The unknown-aggregate lookup shape stays OPEN.** UC-1's `InvalidArgumentException` is **analogy only**, pending the final refusal taxonomy. ⛔ **Do not propagate it as a pattern.** | GREEN-3 **raises the question again** rather than copying UC-1. |
| **4** | **`HistoryKind` assignment is Application protocol mapping** — the handler applies the RED-pinned table using the domain enum and defines no business meaning. | UC-2's four facts are all `Lifecycle`, per the pinned table. |
| **5** | The **W-2 guard's lexical scan is stronger than the architectural rule** (it forbids the tokens in comments too). **GREEN obeys the guard as written**; normalization is a later hardening item. | ⛔ **Never weaken a guard to make production code pass.** → `PBDIGIT-71` |

**Plus the standing invariant, unchanged:** the application **may** receive · load · translate · delegate · append; it **may not** interpret · calculate · decide · govern.

## 3 · Governance's own notes

* **`PBDIGIT-71` scope verified:** its own text says it changes ***"where the scanner looks, never what is forbidden."*** ✅ Correctly bounded — a guard-weakening item would have been refused.
* **Guide 02's acceptance note is committed with this set:** *"GREEN-1 does not certify application behaviour"* — it certifies namespace existence · dependency boundary · DTO shape · entry points · port wiring · structural compliance, and nothing more. **Recorded so no later reader mistakes "16 guards passing" for behavioural certification.** *(Provenance note: the note's wording is attributed to the PO; the lane recorded it believing it Governance's. Governance did not author it — flagged so attribution stays accurate.)*
* **The lane's pre-GREEN-3 flag is correct conduct and is endorsed:** it will read `b3f6f478` before starting and report any divergence between the PO's message and the registered adjudication **rather than assuming they agree.** They do agree — §2 row 1 records why — but the check was the right instinct.
* **UC-2 is the dangerous one, as the lane says:** `vacancy → inoperative → recovery period` is exactly where a handler drifts into a lifecycle engine, and it additionally trips **`I-15`** (one allowance per election — resume, never a second start) and the **non-retroactive clock pause**. Both are places where "convenient" logic would silently become policy.

**Suites at registration (Governance's own runs):** application **28 failed / 24 passed** · frozen core **42 passed / 2434**.

**Traceability.** Guide 03 · `b3f6f478` (independent adjudication) · commit 2 `8ea13835` · `PBDIGIT-71` · A-9 · the accepted RED contract's `g4` guard.
