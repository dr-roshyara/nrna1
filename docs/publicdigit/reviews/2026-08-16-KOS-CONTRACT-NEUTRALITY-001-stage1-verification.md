# KOS-CONTRACT-NEUTRALITY-001 — Stage 1: reference verification
# **VERDICT: FAIL — contract ambiguity/defect. STAGE 2 MUST NOT PROCEED.**

**2026-08-16 · `S1-verification-lcom4-reference` · ACTIVE, mutation owner · grant `G-KOS-CONTRACT-STAGE1-VERIFY`**
**Startup gate passed:** `identity` → role `verification`, ACTIVE, linkage `G-KOS-CONTRACT-STAGE1-VERIFY` · resolver `RESOLVED / operable: true`.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #9
 Responsibility : verification
 Operator       : S1-verification-lcom4-reference        [declared]
 Approver       : PO/ARB — two-stage amendment 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

> ## 🛑 **STOP CONDITION MET.** Per the amendment: *"If Stage 1 fails, stop the experiment and report one of: reference implementation defect, contract ambiguity/defect, fixture/expectation defect."*
> ### **Classification: `contract ambiguity/defect`.** **No Python was written. Nothing was modified.**

---

## 1 · Conformance run — the reference passes all seven fixtures

| Fixture | Class | Expected | Actual | |
|---|---|---:|---:|---|
| `cohesive-class.php` | `CohesiveExample` | 1 | 1 | ✅ |
| `split-class.php` | `SplitExample` | 2 | 2 | ✅ |
| `call-chain.php` | `ChainExample` | 1 | 1 | ✅ |
| `constructor-glue.php` | `ConstructorGlueExample` | 2 | 2 | ✅ |
| `isolated-methods.php` | `IsolatedExample` | 2 | 2 | ✅ |
| `static-methods.php` | `StaticExample` | 2 | 2 | ✅ |
| `trait-user.php` | `TraitUserExample` | 1 | 1 | ✅ |

**7 passed, 0 failed.** Observation counts matched expectations on every fixture.

> **This is where a naïve Stage 1 would have stopped and reported PASS.** The amendment's instruction — *"must not silently treat the current PHP implementation as the definition of correctness"* — is what turned this from a green tick into the finding below.

## 2 · Verification against the contract — the gap

**The contract's `_variant` states:** *"connected components over methods; edges = **shared instance variable OR intra-class call**."*

**Probed — cases the fixture set does not cover:**

| Case | Source | Contract implies | Reference gives |
|---|---|---:|---:|
| **A** | two **static** methods, `one()` calls `self::two()` | link → **1** | **2** |
| **B** | same via `static::two()` | link → **1** | **2** |
| **C** | ⭐ **instance** method calls `self::two()`; a third method touches `$this->p` | `{one,two}`,`{three}` → **2** | **3** |
| **D** | *control* — two instance methods linked by `$this->two()` | link → **1** | **1** ✅ |

> ### ⭐ **Case C is decisive.** An **instance** method making an intra-class call via `self::` is **not** linked. No pinned decision excludes this, and *"intra-class call"* plainly covers it. **Cases A/B are arguably predicted by the static decision** (*"a static touching no `$this` is an isolated component"*) — **C is not covered by any pinned decision at all.**

## 3 · Why this is `contract ambiguity/defect`, not `reference implementation defect`

**Three legs, each measured:**

| # | Evidence |
|---|---|
| **1** | **The contract pins five decisions** — constructors · statics · traits · inheritance · magic methods. **NONE pins how an intra-class call is detected.** The contract is meticulous about five choices and silent on the sixth |
| **2** | **No fixture exercises `self::` or `static::`** — grep over all seven returns nothing. **The golden set never tests call-edge detection beyond `$this->`** |
| **3** | **The implementation detects only `Node\Expr\MethodCall`** (`Lcom4Collector.php:61`); **`StaticCall` appears nowhere.** It silently chose the narrow reading |

> **The implementation is not "wrong" against a rule the contract never states.** It picked one defensible interpretation of an undefined term. **The defect is that the term was left undefined while five neighbouring terms were carefully pinned** — and the fixtures could not reveal it, because none exercises the case.
>
> ### ⭐ **This is precisely the failure mode the experiment exists to detect: a second independent implementation would have had to guess, and could legitimately have guessed differently — producing a different LCOM4 for the same class while both passed all seven fixtures.**

## 4 · What this means for the language-neutrality question

**It does not answer it. It shows the question could not yet have been answered honestly.**

> **A contract that permits two conforming implementations to disagree is not yet language-neutral — it is under-specified in a dimension no existing fixture measures.** Had Stage 2 run first, a passing Python implementation would have been *weak* evidence (it might have guessed the same way), and a failing one would have been *misattributed* to Python.
>
> **The amendment's sequencing produced this finding. The original single-stage design would have obscured it.**

## 5 · Verdict

> ## **FAIL — `contract ambiguity/defect`**
> **Stage 2 must not proceed.** The contract must define what constitutes an *intra-class call* — as a **sixth pinned decision** — before a second implementation can be a fair test of neutrality.

**Recorded as a finding, not a fix.** Per the grant: *"Do not modify the PHP implementation, the contract, the fixtures or the expectations within this work item. Any correction requires a separate governed act."* **None was modified.**

**Scope of the required correction — stated for the ARB, not decided here:** whether `self::`/`static::` calls count as intra-class edges; and whether the fixture set gains a case that exercises it. **Both are contract-level decisions. Neither is architecture, and neither is Python.**

## 6 · What was NOT done

**No Python written** (0 files, tracked and on disk) · **no change** to the PHP implementation, the contract, the fixtures or the expectations (0 changes) · **Stage 2 not entered** · no correction proposed as an act · no target-architecture work · `KOS-ARCH-BASELINE-001` untouched · no Election artifact touched · **this lane did not complete its own assignment** (`G-1` — a governance act) and **does not self-certify**.

**All probing was performed inline** (`php -r`); **no file was created by this verification.**

---

*Technical references: grant `G-KOS-CONTRACT-STAGE1-VERIFY` · amendment `G-KOS-CONTRACT-EXP-AMD1` · seq 4 REGISTER · seq 5 HANDOFF · seq 6 human START · `scripts/observations/Lcom4Collector.php:61` (`MethodCall` only; no `StaticCall`) · `scripts/observations/examples/lcom4/expected.json` (`_variant`, five `_variant_decisions_pinned`) · seven golden fixtures · probe cases A–D, run inline · `R-34` (the collector was not written by this process — Stage 1 is not self-verification).*
