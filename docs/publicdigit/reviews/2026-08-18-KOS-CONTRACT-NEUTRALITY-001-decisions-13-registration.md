# Decision Registration — Decisions 13.1, 13.3, 13.5, 13.7 · and the parsing-architecture sequencing

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Precondition verified:** the parsing-architecture evaluation exists (`d2859e91`), recommending **D** with **B** for the language layer and **C-as-completion**, **A rejected as an architecture** — and it **selected nothing**, returning the choice to the PO/ARB.

**⚠ Two of the four items DECIDE; two REQUIRE decisions that are not made by this act. Governance distinguishes them rather than treating the block as uniformly decided.**

---

## ✅ Decision 13.1 — Contract-neutrality scope · **DECIDED**

> **The contract-neutrality claim is defined as L3 → L5: the contract defines the language-neutral fact model, cohesion semantics, and final metric. Language-specific parsing/extraction remains a binding specific to the source language and is not itself the contract-neutral semantic layer.**

**Reason, as given:** the accepted contract specifies **cohesion semantics, not PHP lexical/grammar semantics**, and the breadth evidence shows the observed failures are **concentrated in the unspecified language-extraction layer**.

**Consequence — this reframes every prior finding.** The architecture evaluation measured the same thing independently: *"the contract's seven pinned decisions live entirely in L4–L5… nothing in the contract text describes L1 or L2 at all — and L1/L2 is where every code-side defect sits."* ⇒ **the defect surface was never a cohesion disagreement.** The Stage-2 FAIL verdict stands; what changes is *what it was a failure of*.

⛔ **Does not authorize implementation.**

## ⬜ Decision 13.3 — `NEW-5` semantic rule · **REQUIRED, NOT MADE BY THIS ACT**

The act **requires** that the semantics of `\Fq::b()` be explicitly decided, and constrains how:

- **resolve the precedence** between *behavioural dependency ("not syntax")* and *own-class name matching "AS WRITTEN"*;
- **define the rule for each relevant PHP name kind**;
- ⛔ **do not use current PHP or Python behaviour as the source of truth**;
- ⛔ **no implementation repair may target `NEW-5` until this semantic decision is recorded.**

**The rule itself is not stated in the act and is not invented here.** *Governance note: this is the same shape as Decision 1 — the disposition is settled, the semantics are not. If a drafted proposal is wanted before the ruling, commissioning that draft is a separate act; Architecture's evaluation §8 states it shows how the recommended architecture makes these decidable "once, explicitly, in one place — which is a different thing from deciding them."*

## ⬜ Decision 13.5 — Contract silences · **REQUIRED, NOT RESOLVED BY THIS ACT**

Four silences to resolve: **anonymous classes · nullsafe `?->` · enum/interface/trait analysed-unit semantics · fully-qualified first-class callable form.**

**Mandatory output distinction:** each must be marked **DECIDED** or **UNDECIDED / OUT OF SCOPE** — *no silence may remain merely unmentioned.* **None is resolved by this registration.**

*Why this matters, from the breadth report: "where the contract is silent, no observed agreement can be called conformance." Until these are marked, agreements in those areas are not evidence.*

## ✅ Decision 13.7 — Contract evidence rule · **DECIDED (promotion)**

> **The already-adopted evidence requirement is PROMOTED INTO THE CONFORMANCE SPECIFICATION: node set + edge set + final LCOM4 metric. Final metric equality alone is insufficient.**

**What changes:** Decision 2 made this a standing governance requirement; 13.7 makes it **part of the specification itself** — binding on any conformance claim, not only on verification practice.

---

## Parsing architecture selection — **SEQUENCED, NOT MADE**

> *"After the above semantic decisions: select the architecture for the next design phase. Evaluate the Architecture proposal's D/B3 recommendation, but keep PO/ARB as the decision authority."*

**The selection is explicitly downstream of 13.3 and 13.5.** The Architecture proposal recommends **D** (stratify the contract, conformance boundary at the fact model) with **B** for the language layer and **C-as-completion**, **rejecting A as an architecture** — and it selected nothing, exactly as its grant required.

⛔ **No implementation is authorized by this act.**

## The binding sequence, registered

```
PO/ARB semantic decisions (13.3, 13.5)
      ↓
PO/ARB parsing architecture selection
      ↓
Architecture implementation-architecture design
      ↓
separate implementation authorization
      ↓
Implementation → Independent Verification → PO/ARB acceptance
```

## Not done

The `NEW-5` rule is not written · the four silences are not resolved · no architecture selected · no implementation authorized · no repair · the architecture-evaluation lane not closed · no claim the defect surface is closed.

**Traceability:** the PO/ARB act 2026-08-18 · parsing-architecture evaluation `d2859e91` (recommendation; the L1–L5 stratification; §8) · breadth report `17e4f066` · Decisions 1/2/3 registration · Stage-2 FAIL `4d4738db`
