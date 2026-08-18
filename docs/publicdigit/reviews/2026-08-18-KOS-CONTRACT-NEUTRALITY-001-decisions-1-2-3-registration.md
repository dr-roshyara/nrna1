# Decision Registration — Contract-neutrality Decisions 1, 2 and 3

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
*(Signature date left blank in the act; Governance records the delivery date and does not fill it.)*

---

## Decision 1 — `NEW-5` contract semantics · **DECIDED: CLARIFY THE CONTRACT**

> **"Clarify the contract definition for fully-qualified own-class references such as `\Fq::b()`. The current PHP implementation shall NOT be treated as automatically authoritative. No implementation repair shall target NEW-5 until the contract semantics are explicitly decided."**

**What is decided:** the disposition — **the contract is clarified**, rather than either implementation being declared correct by default.
**What is NOT decided:** the semantic rule itself. *What* the contract will say about `\Fq::b()` remains open and requires its own act.

**Two standing prohibitions, now in force:**
- ⛔ **the PHP reference is not automatically authoritative** — it is a party to the divergence, not the arbiter of it;
- ⛔ **no implementation repair may target `NEW-5`** until the semantics are explicitly decided.

*Why this matters beyond one construct: the breadth report found that on the stricter reading of the pinned decision it is the **PHP reference** that diverges — so "repair Python" would have silently encoded an undecided semantic as though it were settled.*

---

## Decision 2 — conformance evidence model · **DECIDED AND ADOPTED**

> **Conformance evidence must compare: (1) intermediate node set · (2) intermediate edge set · (3) final LCOM4 metric.**
>
> **"Final metric equality alone is insufficient evidence of contract conformance because compensating analysis errors can produce equal final values."**

**In force for all future contract-neutrality conformance evidence.** This is a **standing evidence requirement**, not a one-off instruction for the next verification.

*Evidence base: the compensating-error finding, and `O-2` — "a probe that can only observe the final number can fail to notice that it did fail." Together with `O-1` ("a probe that cannot fail is not evidence") this completes the pair: a probe must be able to fail, **and** able to notice that it did.*

---

## Decision 3 — parsing architecture · **NOT SELECTED; ARCHITECTURE COMMISSIONED TO EVALUATE**

> **"Do not yet select a parsing architecture."**

**Architecture is commissioned to evaluate four options:** **A** incremental repair of the current scanner · **B** AST-grade parser replacement or augmentation · **C** explicit contract lexical limitations · **D** another explicitly justified architecture.

**The analysis must evaluate the observed defect surface**, including the scanner-vs-AST failures **and** the `NEW-5` contract ambiguity.

⛔ **No implementation is authorized by this act.**

*Governance note: options C and D are not fallbacks. C would make the contract state the limits of lexical analysis — a legitimate answer that changes the contract rather than the code, and one that Decision 1 has already opened the door to.*

---

## The binding sequence, registered

```
PO/ARB decisions
      ↓
Architecture analysis / implementation architecture
      ↓
Implementation authorization
      ↓
Implementation
      ↓
Independent Verification
      ↓
PO/ARB acceptance
```

Each arrow is a gate requiring its own act. **Implementation may not begin until Architecture has produced the approved implementation architecture and implementation has been separately authorized.**

## What these decisions do not do

No contract text written (Decision 1 decided the *disposition*, not the rule) · no repair · no parsing architecture chosen · no implementation authorized · the breadth lane not closed · no claim that the defect surface is closed — *the discovery rate has not fallen.*

**Traceability:** the PO/ARB act 2026-08-18 · breadth report `17e4f066` (nine mechanisms, 76 probes, `NEW-5`, `O-2`) · Stage-2 FAIL `4d4738db` · completion audit `f1ec1821` (`N-1`…`N-4`, `O-1`) · review registration `e12a04e7`
