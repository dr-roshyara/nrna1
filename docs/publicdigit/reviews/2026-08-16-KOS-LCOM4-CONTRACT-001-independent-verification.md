# KOS-LCOM4-CONTRACT-001 — **independent verification of the corrected contract**

**Date:** 2026-08-16 · **Verifying:** `286cad1e` against the approved contract (`decision-registration`) · **Assignment:** `S1-verification-lcom4-corrected` · **Grant:** `G-KOS-LCOM4-REVERIFY`
**The implementer's checks were not repeated as proof. The contract — not the previous implementation, and not the fixtures — was used as the authority for expected behaviour.**

---

## 1 · Authority and the R-34 limitation, stated plainly

`identity` → role `verification`, **state `ACTIVE`**, predecessor `S3-implementation-lcom4-contract-apply`, linkage `G-KOS-LCOM4-REVERIFY`.

The assignment's `executionContext` requires *"a process OTHER THAN `claude-code-session:fbc084f0`"* and itself declares the constraint **`DECLARED and NOT ATTESTABLE (INV-ATTR-2)`**.

**What I can evidence:** this process performed **no LCOM4 work**. Its last act was `b6b8b0bd` (session-discovery corrective verification); `286cad1e` applied the LCOM4 correction afterwards, and nothing in this process's history drafts or applies it. **What I cannot evidence:** that my session id is not `fbc084f0`. **The record says that is unattestable and rests on where the PO/ARB started the session.** *(`Observed` for the first; `Unknown` for the second — recorded rather than glossed.)*

## 2 · Verification scope

Contract read first and used as the oracle · implementation read (`Lcom4Collector::observeClass`, `namesThisClass`, `isFirstClassCallable`) · **24 purpose-built falsification cases** in two probes · the documented namespace limitation · the three approved fixtures inspected for semantic honesty, not just numeric match · full 10-fixture golden set · both existing suites.

## 3 · Contract conformance

| Approved rule | Verdict | Evidence |
|---|---|---|
| `$this->m()` includes | ✅ **PASS** | `IncThis` → 1 |
| `self::m()` includes | ✅ **PASS** | `IncSelf` → 1 |
| `static::m()` includes | ✅ **PASS** | `IncStatic` → 1 |
| `OwnClass::m()` includes | ✅ **PASS** | `IncOwn` → 1 · `OwnExact` → 1 |
| Relationship is semantic, not syntactic | ✅ **PASS** | all four forms yield the identical component count |
| Direction-independence (inst→inst, inst→static, static→static) | ✅ **PASS** | `MixInstToStatic` → 1 · `MixStaticToStat` → 1 |
| `parent::m()` **out of frame** | ✅ **PASS** | `ExcParent` → 2 · `MixedParent` → 1 *(the internal call still counts alongside a `parent::`)* |
| Dynamic/computed **not determinable** | ✅ **PASS** | `DynVar` (`$this->$n()`) → 2 · `DynCuf` (`call_user_func`) → 2 · `DynStat` (`$c::b()`) → 2 |
| First-class callables excluded — **method-call path** | ✅ **PASS** | `FccThis` (`$this->b(...)`) → 2 |
| First-class callables excluded — **static-call path** | ✅ **PASS** | `FccSelf` (`self::b(...)`) → 2 |
| …and still excluded when subsequently invoked | ✅ **PASS** | `FccInvoked` → 2 |
| Static methods are nodes | ✅ **PASS** | `StaticIsolated` → 2 |
| Static isolated unless `$this` **or** an internal relationship | ✅ **PASS** | `StaticIsolated` → 2 vs `StaticJoined` → 1 |
| Namespaced/aliased own-name **unresolved** (documented limitation) | ✅ **PASS** | `NsFq` (`\App\Deep\NsFq::b()`) → 2, as the limitation states; `NsBare` → 1 |

**24 of 24 falsification cases conform. No rule failed, and none was `NOT TESTABLE`.**

## 4 · Falsification attempts — the negative space

Cases built specifically to make the implementation over-count:

| Case | Risk probed | Result |
|---|---|---|
| `OtherInstance` — `$o->b()` where `$o` is the **same class** | naive "same class ⇒ edge" | ✅ 2 — correctly not `$this` |
| `CallsForeign` — a foreign class with a **same-named** method | name-based matching | ✅ 2 |
| `StaticProp` — `self::$p` | property fetch mistaken for a call | ✅ 2 |
| `ConstFetch` — `self::C` | constant fetch mistaken for a call | ✅ 2 |
| `Chained` — `$this->b()->other()` | first hop missed | ✅ 1 |
| `InClosure` — `$this->b()` inside a closure | scope-walk gap | ✅ 1 |
| `AsArgument` — `var_dump($this->b())` | non-statement position missed | ✅ 1 |

> **The two exclusion categories remain distinguishable in behaviour and in code.** `namesThisClass()` returns **false for `parent`** by an explicit branch (*out of frame*), while dynamic targets never reach that function at all — they fail the `instanceof Node\Name` / `Node\Identifier` guards (*not determinable*). **They are excluded by two different mechanisms, not collapsed into one generic "ignore".** *(`Observed` in code and behaviour.)*

## 5 · The three approved fixtures — verified, and checked for honesty

`self-call.php` → **2** · `static-call-chain.php` → **1** · `parent-call.php` → **2** — all match.

**And they test what they claim** — I read the bodies rather than trusting the numbers: `self-call` joins `one`+`two` via `self::` while `three` stays isolated on `$this->p` (so 2 is the *right* 2, not an accidental one); `static-call-chain` is two statics joined only by `self::`, which is precisely the case that would regress if statics were treated as inherently isolated; `parent-call` separates a `parent::`-only method from a `$this->q` method. **The negative fixture genuinely constrains a future implementation, as the PO/ARB intended.**

## 6 · Regression

**`Lcom4CollectorTest` + `Lcom4VerificationSuiteTest`: 10 tests · 56 assertions · OK.** **Golden set: 10 fixtures, class names and values compared element-wise — 0 mismatches**, including the 7 pre-existing expectations, unchanged.

⛔ **Disclosure — my own tooling error.** My first comparison script reported *"15 fixtures, 15 mismatch"*. That was **my** bug: `expected.json` contains metadata keys prefixed `_` and stores a **list of `{class, value}` objects**, which I flattened with a scalar comparison. Corrected reader: **10 fixtures, 0 mismatch.** **No defect existed; I nearly reported one.** *(The same class of error I have catalogued repeatedly — an unvalidated ad-hoc reader, caught by the result being implausible against a green suite.)*

## 7 · Findings

**No implementation defect. No contract ambiguity encountered.** Two limitations are *documented and behaved as documented* — namespaced/aliased own-class spellings, and dynamic targets — so neither is a finding.

**`Inferred`, not asserted as fact:** the implementation's conformance rests on `nikic/php-parser` node shapes; a parser upgrade altering `VariadicPlaceholder` or `Node\Name` handling could silently change first-class-callable or own-name behaviour. **No evidence of a problem today** — recorded as a durability consideration for whoever ports this to Python, since the contract is declared language-neutral.

## 8 · Final verdict

> ## ✅ **PASS — corrected implementation conforms to the approved contract**

**This verdict does not rest on the fixtures being green.** It rests on 24 purpose-built cases derived from the contract text — including every approved inclusion form, both exclusion categories tested through *both* parsing paths, and seven negative-space cases built to make the implementation over-count. **The implementation refused all of them.**

**Answering the single question posed:** yes — on this evidence the corrected PHP reference measures LCOM4 according to the approved semantic contract, rather than merely passing the available fixtures. **The one thing I cannot certify is process separation itself (§1), which the record already declares unattestable.**

---

**VERIFICATION COMPLETE · STOPPING**
**Nothing modified: no contract, fixture, `expected.json`, `Lcom4Collector.php`, test or record. No Python. No Stage 2. `KOS-ARCH-BASELINE-001` untouched. Election untouched. No self-certification.**

**Traceability:** `286cad1e` · contract §§ approved wording, inclusion/exclusion table, `D-1`/`D-2`, static-method meaning · `Lcom4Collector.php:66-90` (`MethodCall`/`StaticCall` guards), `:113-124` (`namesThisClass`), `:131-140` (`isFirstClassCallable`) · probes `probe.php` (15) + `edge.php` (9) + `ns.php` (2) in scratchpad · golden set 10/10 · suites 10 tests/56 assertions · `identity` on `S1-verification-lcom4-corrected`
