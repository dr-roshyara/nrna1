# Decision Registration — Language scope: PHP as the sole implementation language

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18

## 1 · The decision

> **Proceed with PHP as the sole implementation language for the current `KOS-CONTRACT-NEUTRALITY-001` implementation.**
> **The language-neutral L3 Fact Model and L4/L5 Cohesion Engine remain language-neutral architectural boundaries.**
> **Java and other future language bindings are explicitly OUT OF CURRENT IMPLEMENTATION SCOPE.**
> **This act does not authorize implementation yet.**

**The principle Architecture must incorporate, verbatim:**

> **"PHP is the first language binding, not the definition of the language-neutral cohesion model."**

**And its consequence, as stated:** a future Java or other binding requires **a separate binding and conformance work item**; it does **not** require redesigning the L4/L5 model **unless new evidence demonstrates a genuine model gap**.

## 2 · What this changes about how neutrality is evidenced — surfaced, not assumed

The work item's founding purpose was: *"determine whether the EXISTING engineering capability contract is genuinely language-neutral, **by implementing ONE existing capability independently in Python and comparing its results** against the existing implementation and the pinned expectations."*

**With PHP as the sole implementation language, that comparison is no longer the method.** Neutrality is now claimed **architecturally** — by the stratified L3→L5 boundary and the binding/model separation this act names — rather than **empirically**, by a second implementation agreeing.

**Governance states this plainly rather than letting it pass silently**, because the estate has already established that *agreement ≠ neutrality* (`N-2`, `O-1`, `O-2`) — and the converse now applies: **removing the second implementation removes the differential evidence, so the neutrality claim rests on architecture and on declared expected evidence.** Decision 13.7's node+edge+metric requirement and 13.5's *declared expected evidence* clause for nullsafe become **the** conformance mechanism, not a supplement to differential comparison.

*This is a coherent position — the breadth verification found the divergences were extraction defects below the neutrality boundary, not cohesion disagreements — but it is a change of method, and it is recorded as one.*

## 3 · Effect on the open OQs

| | Effect |
|---|---|
| **OQ-4** *(may the Python implementation invoke a PHP runtime?)* | ⚠️ **ITS PREMISE IS REMOVED, NOT ITS ANSWER RECORDED.** OQ-4 asks about *the Python implementation*; with Python out of current implementation scope there is no such implementation to carry a runtime dependency. **Governance does not mark OQ-4 decided** — the act did not address it. **Is OQ-4 (a) moot and closed, or (b) deferred to the future binding work item where its premise returns?** *A future Java or Python binding would face the same question.* |
| **OQ-2** *(analysis scope)* | ⬜ **UNAFFECTED AND STILL OPEN.** This act decides **language scope**; OQ-2 asks the **analysis scope** (single file · repository/module · other). Different questions — Governance does not conflate them. Architecture still cannot complete the final design without it. |
| **OQ-1** *(identity)* | Unaffected — constrained and routed to Architecture as before. |
| **OQ-3** *(artifact update)* | Unaffected — standing authorization, unexercised. |

## 4 · Boundaries

⛔ **Does not authorize implementation.** ⛔ Does not modify the collectors, contract, fixtures or `expected.json`. ⛔ Does not retire the existing Python collector as an artifact — it becomes **out of current implementation scope**, which is not the same as deleted, and any disposition of it is a separate act. ⛔ Does not reopen Architecture D, the qualifier rules, or any decided silence.

**Traceability:** the PO/ARB act 2026-08-18 · founding grant `G-KOS-CONTRACT-EXP` (the differential method) · implementation architecture `d2f2e5a4` (`OQ-1`…`OQ-4`) · Decisions 13.1/13.3/13.5/13.7 · breadth report `17e4f066` (`N-2`) · `O-1`/`O-2`
