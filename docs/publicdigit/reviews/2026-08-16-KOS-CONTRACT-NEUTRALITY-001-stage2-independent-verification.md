# KOS-CONTRACT-NEUTRALITY-001 Stage 2 — **independent verification**

**Date:** 2026-08-16 · **Assignment:** `S1-verification-python-stage2` (`ACTIVE`) · **Grant:** `G-KOS-CONTRACT-STAGE2-VERIFY`
**The implementer's 34-case self-check was not repeated as proof. New cases were constructed against the contract.**

---

## 1 · Verdict

> ## 🔴 **FAIL — implementation defect**
>
> **Three divergences found in contract-DEFINED territory**, distinct in kind from the known anonymous-class gap. **The 34-case agreement is real but not representative: it did not probe the constructs where a hand-written scanner and an AST parser part company.**

## 2 · Authority and the R-34 limitation

`identity` → role `verification`, **`ACTIVE`**, predecessor `S3-implementation-python-stage2`, linkage `G-KOS-CONTRACT-STAGE2-VERIFY`. The `executionContext` requires a process other than `claude-code-session:fbc084f0`, *"disqualified twice over"*, and declares separation **`DECLARED, not attestable (INV-ATTR-2)`**. **Evidenced:** this process neither drafted the contract nor implemented either collector. **Not evidenced:** my session id. *(`Observed` / `Unknown`.)*

## 3 · (a) Was Python ported from the PHP? — **NO, and the evidence is structural**

| | PHP reference | Python |
|---|---|---|
| Parsing | **`nikic/php-parser` — a real AST** | **`re` + a hand-written `blank_noise` scanner** |
| Method discovery | `findInstanceOf(Node\Stmt\ClassMethod)` | `METHOD_RE` over brace-matched blocks |
| Call detection | node types `MethodCall` / `StaticCall` | `PROP_OR_CALL_RE` / `STATIC_CALL_RE` |
| First-class callable | `Node\VariadicPlaceholder` | `FIRST_CLASS_CALLABLE_RE` on the argument text |
| Length | 180 lines | 279 lines |

> **These are not the same program in two languages.** The Python makes genuinely independent implementation choices — it had to invent brace matching and noise blanking that the AST gave PHP for free. **Objective (a): the port hypothesis is refuted by observation, not merely unsupported.**
>
> **And that independence is precisely what produced the failures below** — which is the experiment working, not failing.

## 4 · (c) Are the two exclusions still distinguishable in Python? — **YES**

`parent::` is excluded by an explicit name test (*out of frame*); dynamic targets are excluded by **failing to match** `STATIC_CALL_RE`/`PROP_OR_CALL_RE`'s identifier requirement (*not determinable*). **Two different mechanisms, as in the PHP and as the contract requires. The distinction survived translation.**

## 5 · (d)/(e) 🔴 The divergences — measured

**Probe: four classes, each contract-defined. A string literal is not an invocation; a method is a method.**

| Case | PHP (AST) | Python | Contract requires |
|---|---:|---:|---|
| **`HeredocCase`** — `$this->b()` inside a **heredoc** | **2** | 🔴 **1** | **2** — a string is not an invocation |
| **`NowdocCase`** — same inside a **nowdoc** | **2** | 🔴 **1** | **2** |
| **`AttrSameLine`** — `#[SomeAttribute] public function a() {…}` on one line | **2** | 🔴 **1** | **2** — the method exists |
| `AttrOwnLine` — attribute on its own line (control) | 2 | 2 | 2 ✅ |

**Mechanisms confirmed in the scanner, not inferred:**

1. **`blank_noise` does not handle heredoc/nowdoc.** Dumped output shows the heredoc body **surviving verbatim**, so `$this->b` matches `PROP_OR_CALL_RE` and fabricates an internal edge. **Python reports cohesion that does not exist.**
2. **`#` is treated as a line comment, but PHP 8 attributes begin `#[`.** The dumped region shows `#[SomeAttribute] public function a() { $this->x = 1; }` **blanked to spaces in its entirety** — the method vanishes from analysis. **Python silently analyses a class with fewer methods than it has.** The control case proves the trigger is same-line placement.

**Classification: `IMPLEMENTATION DEFECT`, not contract gap.** The contract defines what an invocation is and what a method is; neither case is a silent area. **Both are Python-side; the PHP reference is correct in all four.**

⚠️ **Severity note, stated as consequence not judgement:** defect 2 is the more dangerous of the two. A missed *call* perturbs a number; a missed *method* removes a node, so **any metric built on this scanner silently under-counts**, and attributes are common in modern PHP.

## 6 · (e) Is the anonymous-class gap the only silent area? — **NO, and it is not even the same category**

The known anonymous-class divergence is **contract under-specification** — the contract says nothing about anonymous classes, so neither implementation can be wrong. **The three findings above are different in kind:** the contract *does* define them, and Python violates it. **So the answer to the commissioned question is that anonymous classes were the first gap found, not the only one — and the others are defects rather than gaps.**

## 7 · What is proven, and what is not

**Proven:** Python was independently implemented, not ported (§3) · the two exclusion semantics survived (§4) · three contract-defined divergences exist with confirmed mechanisms (§5) · both PHP and Python are correct on the control case.
**Not proven:** that these three are exhaustive. **I probed heredoc/nowdoc and attributes; enums, interfaces, traits-as-analysed-units, readonly/promoted properties and nested declarations remain untested by me.** Given that the first sustained probe of scanner-vs-AST territory found three defects immediately, **the prior that more exist is high — but that is `Inferred`, not measured.**
**Contract-defined:** invocation forms, exclusions, static-method meaning, method identity. **Contract-silent:** anonymous classes; and — newly visible — **whether a "class" for this contract includes enums, interfaces and traits**, which nobody has ruled on.
**Requires a future human decision:** the anonymous-class rule; the enum/interface/trait scope question; and — after remediation — whether contract neutrality can be claimed on a hand-written scanner at all, or whether the contract should require an AST-grade parser as a conformance precondition.

## 8 · Repairs — none

**Nothing modified:** no Python, no PHP, no contract, no fixture, no `expected.json`, no test, no record. **No interpretation chosen, no adoption/retirement recommended, no Stage 3 begun.** The defects are returned as evidence.

---

**VERIFICATION COMPLETE · STOPPING**

**Answering the commissioned question directly:** **No — Python cannot yet be trusted as evidence of contract neutrality.** Not because the experiment failed, but because it succeeded: **an independent implementation surfaced three genuine defects the shared-fixture evidence could not.** The contract-neutrality claim needs a corrected Python and a broader probe before it can be made.

**Traceability:** `identity` on `S1-verification-python-stage2` · `lcom4_collector.py:59-98` (`blank_noise`), `:121-157` (regexes) · `Lcom4Collector.php` (AST) · probe `t1.php` (4 classes) with both collectors' outputs · `blank_noise` dumps for the heredoc and attribute regions · Stage-2 evidence report (34 cases, not re-run as proof)
