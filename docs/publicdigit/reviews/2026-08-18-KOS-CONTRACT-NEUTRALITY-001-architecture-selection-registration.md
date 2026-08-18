# Decision Registration — Architecture D selected (Stratified Contract / Fact-Model Boundary)

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
*(Signature date left blank in the act; Governance records the delivery date and does not fill it.)*

## 1 · The selection

> **Architecture D — Stratified Contract / Fact-Model Boundary.**
> **The contract-neutrality boundary is L3 → L5. The language-specific extraction layer is below the neutrality boundary.**

**Realization, as selected:**
- **Language extraction: B3** — a grammar-exact PHP lexer/token layer feeding the language-neutral fact model;
- **Contract completion: C-as-completion** — explicitly define the language-binding preconditions, the contract silences, and the declared conformance expectations.

```
Language-specific extraction   (below the neutrality boundary)
        ↓
L3  language-neutral fact model   ← the conformance boundary
        ↓
L4  cohesion semantics
        ↓
L5  LCOM4 metric
```

**This closes the gate that Decisions 13.3 and 13.5 guarded.** The evaluation recommended D with B and C-as-completion and rejected A as an architecture; the PO/ARB has selected on that analysis, as the standing decision reserved.

## 2 · The fact model must preserve at least

analysed-unit kind · **stable unit identity** · method identity · target method · **qualifier kind** · **callable vs invocation** · access mode · **determinability** · property access information · required name identity information.

**Each entry traces to a decided rule**, which is why the list is a requirement and not a wish: *stable unit identity* discharges 13.5's anonymous-class ruling · *qualifier kind* is what 13.3's stratified rule interprets · *determinability* is what keeps *"not the own class"* separable from *"not determinable"* — the distinction 13.3 insisted must never be merged · *callable vs invocation* carries the first-class-callable exclusion.

## 3 · Boundaries stated in the act

⛔ **Does not authorize implementation.**
⛔ **Does not modify** the collectors · the contract · fixtures · `expected.json` · any implementation artifact.

*Governance note: the downstream work these decisions imply remains substantial and unauthorized — `fully-qualified: INCLUDE` and `relative: INCLUDE` are not what the PHP reference uniformly does, so both implementations are in scope for eventual change, and every such change needs its own governed act.*

## 4 · Next step, as directed

**Architecture is commissioned to produce the implementation architecture for D / B3 / C-as-completion** — registered as `G-KOS-CONTRACT-IMPL-ARCH` on a new assignment in this work item. **START not performed.**

**Traceability:** the PO/ARB act 2026-08-18 · parsing evaluation `d2859e91` (D recommended, A rejected) · Decisions 13.1 (L3→L5), 13.3 (qualifier rules, bucket ruling), 13.5 (four silences), 13.7 (node+edge+metric in the spec) · Decision 1 (PHP not authoritative) · semantic proposal + AMD1 (`N13`/`N13-c`)
