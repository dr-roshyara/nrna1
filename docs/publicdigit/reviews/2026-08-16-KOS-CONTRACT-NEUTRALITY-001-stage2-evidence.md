# KOS-CONTRACT-NEUTRALITY-001 — Stage 2 evidence
# **PASS on every contract-ruled case · one residual gap found in a silent area**

**2026-08-16 · `S3-implementation-python-stage2` · grant `G-KOS-CONTRACT-STAGE2`**
**Startup gate passed:** role `implementation` · ACTIVE · linkage `G-KOS-CONTRACT-STAGE2` · resolver `RESOLVED / operable: true`.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #15
 Responsibility : implementation
 Operator       : S3-implementation-python-stage2        [declared]
 Approver       : PO/ARB — Stage-2 authorization 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

> ## 🛑 **EVIDENCE ONLY. This is not a verification.** `R-34` reserves that act to a different process; the lane is registered and waiting. **No language adopted, no gate opened, nothing decided about PHP.**

---

## 1 · ⚠️ Read this before the result — a declared weakness in the evidence

> ### **This process drafted the corrected contract. It has now implemented from it. A PASS is therefore weaker evidence than it appears.**

Governance recommended Stage 2 be routed to a process that did **not** draft the contract, precisely because *"implementing from a contract you authored tests your memory of what you meant, not whether the words are sufficient for someone who wasn't there."* **The PO/ARB did not redirect, so the experiment proceeded here — and the consequence is recorded with the result rather than omitted.**

**What is and is not weakened by this:**

| | |
|---|---|
| **The PASS** | 🔻 **Weakened.** Agreement may reflect remembered intent rather than sufficient wording |
| **The DIVERGENCE (§4)** | ✅ **NOT weakened — arguably strengthened.** There was no remembered intent to rely on, because the contract says nothing about that case. The gap survived an author who *wanted* the contract to be sufficient |

**What was done to limit the weakness:** the implementation was written **from the pinned decisions**, and uses a **deliberately different parsing strategy** — the PHP reference uses a full AST parser (`nikic/php-parser`); the Python uses a hand-written scanner, since no PHP parser is available and installing one is forbidden. **Two strategies this different agreeing is evidence about the contract, not about a shared library.**

## 2 · What was built

**One file:** `scripts/observations/lcom4_collector.py` — no package, no `pyproject`/`requirements`/`setup`, no framework, no repository change. **First Python in the repository** (previously 0 files).

**Implemented from the seven pinned decisions**, each traceable in the source: nodes = class-body methods minus `__construct`/`__destruct`; statics included; traits unresolved; inheritance excluded; edges = shared `$this->` property **or** internal behavioural relationship (`$this->m()`, `self::m()`, `static::m()`, `Own::m()`); `parent::` **out of frame**; dynamic targets **not determinable**; first-class callables excluded.

> **The two exclusions are kept apart in the code as they are in the contract** — `OUT_OF_FRAME` and the not-determinable checks are separate branches with separate comments, not one "skip" list. **The distinction survived the translation.**

## 3 · Result — every case the contract rules on

**All ten golden fixtures: `PHP == Python == expected`.**

| | |
|---|---|
| Fixtures agreeing | **10 / 10** |
| Mismatches | **0** |

**Twenty-four further cases beyond the fixtures — all agree:**

`$this->m()` · `self::m()` · `static::m()` · `Own::m()` · instance→static · static→static · `parent::` alone · `parent::` alongside an internal call · dynamic `$this->$n()` · `call_user_func([$this,…])` · `$var::m()` · first-class callable on the object path · on the class path · **and still excluded when subsequently invoked** · namespaced own-name (unresolved, per the limitation) · static isolated vs static joined by a call · constructor excluded · call to an undeclared method · **`self::` inside a string** · **`self::` inside a comment** · closure touching `$this->prop` · two classes in one file · class with no methods → `0`.

**34 cases total. 34 agree.**

## 4 · 🔴 The finding — one divergence, in an area the contract does not rule on

**Anonymous class declared inside a method:**

```php
class AA {
    private $p;
    function a(){ return new class { private $p; function inner(){ return $this->p; } }; }
    function b(){ return $this->p; }
}
```

| | Observations emitted |
|---|---|
| **PHP reference** | **two** — `AA = 1` **and** `(anonymous) = 1` |
| **Python** | **one** — `AA = 1` |

**Neither is wrong against the contract, because the contract does not address it.** Searched: `_variant` and all seven pinned decisions are **silent on anonymous classes** — whether they are observed at all, and whether a nested `$this` belongs to the enclosing class or the anonymous one.

> ### ⭐ **This is a second under-specification, found at a different point than Stage 1's — and it could only have been found by a second implementation.**
> Stage 1 found a gap by reading the contract against the reference. **This gap was invisible to that method**, because the reference behaves consistently; it appears only when a second implementation makes a different reasonable choice. **That is precisely what Stage 2 exists to detect.**

**Scope of the impact, measured:** no fixture contains an anonymous class, and **no value for any named class differs**. The divergence is in **how many observations are emitted**, not in any pinned number. **It is a real neutrality gap and a small practical one.**

## 5 · Classification — and why the grant's taxonomy does not quite fit

The grant requires exactly one of: `pass` · `fail-by-contract-ambiguity` · `fail-by-implementation-defect`.

| Option | Fit |
|---|---|
| `fail-by-implementation-defect` | ❌ **No.** Both conform to every ruled case |
| `fail-by-contract-ambiguity` | ❌ **Too strong.** Every case the contract *rules on* agreed, across 34 cases |
| `pass` | ⚠️ **True but incomplete** — reporting it alone would bury the finding |

> ## **Reported as: PASS on the contract as written, WITH one residual under-specification (anonymous classes).**
>
> **Governance should note the taxonomy gap itself:** the three options assume a contract is either sufficient or defective. **This result is a third thing — sufficient for everything it addresses, silent on something it did not anticipate.** A future experiment of this shape will hit the same reporting problem.

## 6 · What this does and does not establish

**Establishes:** the corrected contract is **sufficient for every behaviour it rules on** — two implementations with entirely different parsing strategies agree on all 34 such cases, including the `self::` gap that stopped Track 1.

**Does not establish:** that the contract is complete. **It is not** — §4 is a live counter-example. Nor does it establish that Python should be adopted, that PHP should be retired, or anything about the platform's direction. **None of that was asked, and none of it follows.**

## 7 · What was NOT done

**No verification act** (`R-34`) · no package, framework, `pyproject`, `requirements` or repository change · **no modification** to the contract, fixtures, expectations or the PHP reference — verified 0 changes · no Stage 3, migration or adoption · no target-architecture work · `KOS-ARCH-BASELINE-001` and `KOS-LCOM4-CONTRACT-001` untouched · no Election artifact touched · **this lane did not complete its own assignment (`G-1`) and does not self-certify.**

**Recommended next act — not taken here:** the anonymous-class gap is a **contract question**, and by the precedent this programme has now set twice, it belongs in a **separate governed act**, not in this lane and not in the verification lane.

---

*Technical references: `G-KOS-CONTRACT-STAGE2` · `scripts/observations/lcom4_collector.py` (new, single file) · corrected contract `286cad1e` · independent verification of the reference `9894febf` · 10 fixtures + 24 beyond-fixture cases + 7 silent-area probes · `R-34` · `G-1` · `INV-ATTR-2` · routing recommendation recorded at `a1b9486c`.*
