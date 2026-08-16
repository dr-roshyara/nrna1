# KOS-LCOM4-CONTRACT-001 — application record
# The approved correction applied · re-verification handed to a different process

**2026-08-16 · `S3-implementation-lcom4-contract-apply` · grant `G-KOS-LCOM4-CONTRACT-APPLY`**
**Startup gate passed:** role `implementation` · ACTIVE · linkage `G-KOS-LCOM4-CONTRACT-APPLY` · resolver `RESOLVED / operable: true`.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #13
 Responsibility : implementation
 Operator       : S3-implementation-lcom4-contract-apply  [declared]
 Approver       : PO/ARB — decision package 2026-08-16     [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

> ## ⚠️ **THIS IS NOT A VERIFICATION.** Everything below is the **implementer's self-check** — evidence that what was approved is what was applied. **`R-34` reserves the verification act to a different process**, and the lane for it is registered and waiting.

---

## 1 · What was applied — five items, each traced to an approval

| # | Change | Approval |
|---|---|---|
| **1** | `expected.json` gains **`intra_class_calls`** — the sixth pinned decision, **in the PO/ARB's approved wording** | `D-1` |
| **2** | `expected.json` gains **`first_class_callables`: EXCLUDED**, in the approved wording | `D-2` |
| **3** | `static_methods` amended: *"a static that neither touches `$this` **NOR participates in an internal behavioural relationship**"* | `D-4` |
| **4** | Three fixtures added with their approved values — `self-call.php` **2** · `static-call-chain.php` **1** · `parent-call.php` **2** | `D-3` |
| **5** | `Lcom4Collector.php` conformed: `StaticCall` handling (`self`/`static`/own name; **`parent` excluded**), first-class-callable exclusion on **both** paths, header docblock restated | `D-1`,`D-2` |

**One limitation recorded that the draft had flagged:** `own_class_name_resolution` — `OwnClass::m()` is matched **as written**; namespaced/aliased spellings are not resolved, consistent with class-in-isolation analysis.

### 1.1 · The two exclusions were kept distinguishable — as the decision required

The applied wording states them as **different claims**, and says so explicitly:

> *"**THESE TWO EXCLUSIONS ARE DIFFERENT CLAIMS AND MUST NOT BE MERGED:** the first says there is nothing to connect to; the second admits we cannot see it."*

**This was the binding constraint most at risk of being flattened during application.** It was not.

## 2 · ⚠️ A consequence of `D-2` the application had to handle

**First-class callables parse as `VariadicPlaceholder` on *both* call paths.** Measured:

```
$this->b(...)  → MethodCall  args[0] = VariadicPlaceholder
self::b(...)   → StaticCall  args[0] = VariadicPlaceholder
```

> **So `D-2` did not only constrain the new static path — it required excluding first-class callables from the EXISTING `$this->` path, which previously counted them as edges.**
>
> **This was not visible in the decision text and would have been easy to miss.** It changes no fixture value (none uses `(...)`), so nothing would have failed — the contract would simply have been implemented inconsistently with `D-2` while appearing correct.

## 3 · Implementer's self-check — not verification

**All ten fixtures against the corrected contract:**

| | |
|---|---|
| Seven pre-existing | **unchanged**, all PASS |
| `self-call.php` | expected **2** → **2** ✅ |
| `static-call-chain.php` | expected **1** → **1** ✅ |
| `parent-call.php` | expected **2** → **2** ✅ |
| **Total** | **10 passed, 0 failed** |

**Behavioural probes (no fixture covers these):**

| Case | Expected by decision | Result |
|---|---|---|
| `$this->b(...)` reference | no edge → 2 | **2** ✅ |
| `self::b(...)` reference | no edge → 2 | **2** ✅ |
| `self::b()` invocation | edge → 1 | **1** ✅ |
| `parent::x()` | out of frame → 2 | **2** ✅ |
| `OwnClass::b()` | edge → 1 | **1** ✅ |

**Existing suites:** `Lcom4CollectorTest` · `Lcom4VerificationSuiteTest` · `ObservationRuntimeTest` → **11 passed, 58 assertions**, including *"every fixture on disk is covered by an expectation"* — so the three new fixtures are covered by the completeness check.

**No pre-existing expected value changed** — as the draft predicted and the decision assumed.

## 4 · What was NOT done

**No verification act** — §3 is a self-check, and `R-34` reserves verification to another process · **no Python** · no Stage 2 · no target-architecture work · `KOS-ARCH-BASELINE-001` untouched · no Election artifact touched · **no expected value redefined beyond the approvals** · no call form added that the PO/ARB did not decide · **this lane did not complete its own assignment (`G-1`) and does not self-certify.**

## 5 · Handed to independent re-verification

| | |
|---|---|
| **Assignment** | seq **8** — `S1-verification-lcom4-corrected`, role `verification` |
| **Grant** | **`G-KOS-LCOM4-REVERIFY`** — `AUTHORIZED` |
| **Handoff** | seq **9** — from the implementation lane; ownership released |
| **START** | ⛔ **NOT performed — the PO/ARB's act** |

> **🔒 `R-34`, written into the assignment's `executionContext` where a startup check will read it:**
> *"REQUIRED: a process OTHER THAN `claude-code-session:fbc084f0`. That process **DRAFTED and APPLIED** this correction and is therefore **disqualified from verifying it**."*
>
> **This process drafted the wording, chose the fixtures, and wrote the implementation. It is the worst possible verifier of its own work** — and unlike the earlier baseline case, `R-34` makes this a **rule**, not a preference.

**The grant requires falsification, not repetition:** *"ATTEMPT FALSIFICATION — do not merely re-run what the implementer ran."* It also asks specifically whether the **two exclusions were flattened** during application, because that is where the decision could have been silently undone.

**Then, and only then:** Stage 2 of `KOS-CONTRACT-NEUTRALITY-001` may be re-commissioned — under a **new** assignment (`R8`; the old one is `HANDED_OFF`).

---

*Technical references: `G-KOS-LCOM4-CONTRACT-APPLY` · decision package `90232508` · approved draft `8af921ff` · Stage 1 verdict `29b3280f` · `KOS-LCOM4-CONTRACT-001` seq 7–9 · `expected.json` (7 pinned decisions after amendment; 10 expectations) · `Lcom4Collector.php` (`StaticCall`, `namesThisClass`, `isFirstClassCallable`) · suites 11 passed / 58 assertions · `R-34` · `R8` · `G-1` · `INV-ATTR-2`.*
