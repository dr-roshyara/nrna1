# Registration — Breadth verification reviewed; three PO/ARB decisions opened

**Registered by:** Governance, on the delivered PO/ARB review act · 2026-08-18
**Subject:** `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-breadth-verification-report.md` (`17e4f066`)
**⚠ This registration records the review and OPENS three decisions. It decides none of them, and authorizes no implementation.**

## 1 · What the breadth verification establishes — verified against the report

| Claim in the act | Report evidence |
|---|---|
| The original three divergences are **not exhaustive** | ✅ **nine new divergences in nine distinct mechanisms**, from **76 probes — 54 agree, 22 diverge** |
| Nine additional mechanisms found | ✅ §5 |
| Defect surface spans fabricated observations, missing observations, fabricated identities, lost relationships, **compensating errors** | ✅ as classified |
| 10/10 golden fixtures pass but are **insufficient** evidence of neutrality | ✅ baseline control: fixtures agree with each other *and* `expected.json` — *"the harness is not manufacturing divergence"* — yet the divergences cluster in the **scanner-vs-AST seam, exactly where `N-2` predicted the fixture population was blind** |
| Equal final LCOM4 values can conceal different intermediate analyses | ✅ `O-2`: *"a probe that can only observe the final number can fail to notice that it did fail"* |

**Three further report statements registered because they bear directly on the decisions:**

- **Three of the six named families are CLEAN** — enums, interfaces, traits, readonly/promoted, match. The divergences cluster elsewhere.
- **⚠ At least one item is not a Python defect.** *"`NEW-5` turns on a contract ambiguity, and on the stricter reading of the pinned decision it is **the PHP reference** that diverges. A repair confined to the Python collector cannot close the surface."*
- **Exhaustiveness is still not established.** *"A second sustained probing pass found nine new mechanisms after the first found three. The rate of discovery has not fallen"* — the signal that would justify believing the surface closed. Marked `Inferred`: more remain.
- **Where the contract is silent, no observed agreement can be called conformance** (anonymous classes · enum/interface/trait as "class" · nullsafe `?->` · `\Own::m()`).

## 2 · Decision 1 — `NEW-5` contract ambiguity · **OPEN**

**Question:** how does the contract define fully-qualified own-class references such as `\Fq::b()`?

**Binding instructions registered:** ⛔ **do not assume the current PHP reference is automatically correct** · ⛔ **do not repair Python until the semantic rule is decided.**

**Options, as stated:** clarify the contract · declare the PHP reference authoritative for this case · declare the Python behaviour correct · explicitly define a third contract rule.

*Governance note: this is a contract-semantics decision, not a defect triage. The report's own reading is that the PHP reference may be the divergent party — so "fix Python" would encode an undecided semantic.*

## 3 · Decision 2 — conformance evidence model · **OPEN, with an ARB recommendation on record**

**Question:** must acceptance evidence for contract neutrality compare **final LCOM4 values only**, or **intermediate node set + edge set + final metric**?

**ARB recommendation (recorded as a recommendation):** require the intermediate node/edge analysis **plus** the final metric.

*Supporting evidence: `O-2` and the compensating-error finding — a differential suite comparing only the metric can be defeated by two errors that cancel.*

## 4 · Decision 3 — parsing architecture question · **RECORDED AS AN ARCHITECTURE DECISION QUESTION**

> **Can contract neutrality be credibly demonstrated using the current hand-written scanner approach?** — evaluated against the observed defect surface.

**Options:** **A** repair scanner incrementally · **B** replace/augment with AST-grade parsing · **C** amend the contract to define lexical limitations · **D** another explicitly justified architecture.

*Evidence the evaluation must weigh: the divergences cluster in the scanner-vs-AST seam; the discovery rate has not fallen; and `NEW-5` shows at least one item is a contract question rather than a scanner defect.*

## 5 · Sequencing, registered as binding

```
PO/ARB decides 1, 2, 3
        ↓
the chosen architectural consequence is ROUTED TO ARCHITECTURE for design
        ↓
Architecture produces the approved implementation architecture
        ↓
only then may Implementation be asked to begin
```

⛔ **No implementation is authorized by this record.** ⛔ **Implementation must not be asked to begin until Architecture has produced the approved implementation architecture.**

## 6 · Not done

No decision taken on 1, 2 or 3 · no contract change · no repair · no Architecture commissioned (that follows the decisions) · the breadth lane not closed · no claim that the defect surface is closed.

**Traceability:** the PO/ARB review act 2026-08-18 · breadth report `17e4f066` (§5 nine mechanisms · §NEW-5 · `O-2` · the 76-probe totals · the exhaustiveness assessment) · Stage-2 FAIL `4d4738db` · completion audit `f1ec1821` (`N-1`…`N-4`, `O-1`) · grant `G-KOS-CONTRACT-STAGE2-BREADTH`
