# KOS-LCOM4-CONTRACT-001 — DRAFT contract correction
# The sixth pinned decision: what counts as an internal behavioural relationship

**2026-08-16 · `S4-architecture-lcom4-contract` · ACTIVE · grant `G-KOS-LCOM4-CONTRACT-DRAFT` (draft only)**
**Startup gate passed:** role `architecture` · ACTIVE · linkage `G-KOS-LCOM4-CONTRACT-DRAFT` · resolver `RESOLVED / operable: true`.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #11
 Responsibility : architecture
 Operator       : S4-architecture-lcom4-contract          [declared]
 Approver       : PO/ARB — START + cohesion-semantics decision 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

> ## ⛔ **DRAFT — NOTHING MODIFIED.** The contract, `expected.json`, the fixtures and `Lcom4Collector.php` are untouched. **This proposes wording for approval; it applies nothing.**

---

## 1 · The question each syntax must answer

Per the PO/ARB's clarification, every form is judged by **one business question**, never by whether a parser node exists:

> ### **Does this represent a meaningful dependency between two behaviours declared in the same class, for the purpose of measuring cohesion?**

**The accepted direction is about measuring meaningful internal behavioural relationships — not about making every PHP call syntax equivalent.** Two forms therefore come out **excluded**, and the reasons differ in kind (§3).

## 2 · Proposed sixth pinned decision — the wording

> **`intra_class_calls`:** *An **edge** exists between two methods when one **uses the other as a behaviour of the same class**. What matters is the behavioural relationship, **not the syntax that expresses it** — a method that invokes another method declared in this class creates the same cohesion relationship whether it is reached through the object, through the class, or through late static binding.*
>
> *Included: `$this->m()` · `self::m()` · `static::m()` · `<OwnClassName>::m()`, where `m` is declared in this class body.*
>
> *Excluded **as out of frame**: `parent::m()` — the target is not declared in this class, and the class is analysed in isolation (see `inherited_methods`).*
>
> *Excluded **as not determinable**: calls whose target cannot be resolved from the class in isolation — dynamic method names (`$this->$name()`), callable arrays (`call_user_func([$this, 'm'])`), and any call whose target is computed at runtime. **These may well be real relationships; they are omitted because they cannot be observed, not because they do not exist.***

## 3 · Reasoning, form by form — in business terms

| Form | Meaningful internal behavioural relationship? | Why | Verdict |
|---|---|---|---|
| **`$this->m()`** | **Yes** | One behaviour of the object invokes another behaviour of the same object. The plainest possible case | ✅ **edge** |
| **`self::m()`** | **Yes** | It names and invokes a behaviour declared in this class. **Static binding is a resolution detail, not a difference in whether one behaviour depends on another.** This is the case the decision was taken to fix | ✅ **edge** |
| **`static::m()`** | **Yes** | Same as `self::` within this analysis. *Caveat: late static binding may resolve to a subclass override at runtime — already out of frame under `inherited_methods` (class analysed in isolation)* | ✅ **edge** |
| **`<OwnClassName>::m()`** | **Yes** | Semantically identical to `self::`; the author simply spelled the class name. *Limitation: recognised only when the name matches the class's own declared name **as written**; namespaced or aliased spellings may not resolve in isolation* | ✅ **edge**, with a recorded limitation |
| **`parent::m()`** | **No** | It invokes a behaviour of the **parent** class. That behaviour **is not a node in this analysis** — `inherited_methods` already excludes it. **There is nothing to connect to.** Excluding it follows from the existing frame; it is not a new syntax preference | ⛔ **no edge — out of frame** |
| **Dynamic / computed targets** | **Undeterminable** | The relationship may be entirely real, but **the class in isolation cannot say what is called.** Excluded for the same reason traits and inheritance are: **the analysis frame, not the syntax** | ⛔ **no edge — recorded limitation** |

> ⭐ **The two exclusions are not the same kind of thing, and the contract should say so.** `parent::` is excluded because **there is no node** — a structural consequence. Dynamic calls are excluded because **we cannot see the target** — an honest admission of blindness. **Collapsing them into one "excluded" list would hide that difference from the next implementer**, which is precisely how the original gap arose.

### 3.1 · The one genuinely debatable case — flagged, not decided

**First-class callable syntax** (PHP 8.1+): `$this->m(...)` · `self::m(...)`. This **references** a behaviour rather than invoking it.

| | |
|---|---|
| **Argument for an edge** | Handing your own behaviour to something else is a real internal dependency: if `m` changes, the referencing method is affected. On the accepted direction — *meaningful internal relationships* — this qualifies |
| **Argument against** | It is a *reference*, not a *use*; cohesion classically measures invocation and shared state. Including it broadens the metric's meaning |

> **Architecture recommends INCLUDING it, but marks this as the one case where the direction does not settle the answer.** It is put to the PO/ARB explicitly rather than resolved by preference. **No existing fixture uses it**, so either answer is additive today.

## 4 · Proposed fixtures — none exists that exercises this

**Measured:** across the seven golden fixtures, `self::`, `static::`, `parent::`, `call_user_func`, `(...)` and `$this->$var()` appear **zero** times. **Only `$this->m()` is exercised** (in `call-chain.php`).

**Proposed additions — source shown for approval; NOT created:**

**`self-call.php`** — the decisive case from Stage 1
```php
<?php
// Golden fixture: an instance method calls a method of this class via self::
// -> intra-class call edge -> {one,two} and {three} -> LCOM4 = 2
class SelfCallExample {
    private $p;
    public function one()   { return self::two(); }
    public static function two() { return 42; }
    public function three() { return $this->p; }
}
```
**Expected: `2`.** *Derived from the wording:* `one` uses `two` as a behaviour of this class → one edge; `three` shares nothing → its own component.

**`static-call-chain.php`** — statics connected by an intra-class call
```php
<?php
// Golden fixture: two statics linked by an intra-class call -> LCOM4 = 1
class StaticChainExample {
    public static function alpha() { return self::beta(); }
    public static function beta()  { return 42; }
}
```
**Expected: `1`.** *Derived from the wording:* the call is the relationship; that neither touches `$this` does not isolate them once a call edge exists.

**`parent-call.php`** — pins the **exclusion**, so it cannot silently drift
```php
<?php
// Golden fixture (PINNED EXCLUSION): parent:: targets a behaviour NOT declared
// in this class, which is out of frame -> no edge -> LCOM4 = 2
class ParentCallExample extends SomeBase {
    private $q;
    public function one() { return parent::inherited(); }
    public function two() { return $this->q; }
}
```
**Expected: `2`.** *Derived from the wording:* `parent::inherited()` connects to nothing analysable, so `one` stands alone; `two` stands alone.
*Note for the applying lane: this fixture references an undeclared `SomeBase`. The analysis reads the class body only, so it need not exist — but if self-containment is preferred, a stub may be added **without changing the expected value**.*

> **A fixture that pins an exclusion is as valuable as one that pins an edge.** The original gap survived precisely because nothing tested the negative space.

## 5 · Which existing expectations change

> ### **No existing expected VALUE changes. The correction is additive.**

Verified: no current fixture uses any newly-included form, so all seven keep their pinned values.

**But one existing pinned decision's WORDING must be amended:**

| | |
|---|---|
| **Current** | `static_methods`: *"INCLUDED as nodes; **a static touching no `$this` is an isolated component** (inflates LCOM4 — known, pinned, revisitable)"* |
| **Problem** | Once an intra-class call is an edge, **that sentence becomes false**: `StaticChainExample::alpha()` touches no `$this` yet is **not** isolated |
| **Proposed** | *"INCLUDED as nodes; a static that neither touches `$this` **nor participates in an intra-class call** is an isolated component (inflates LCOM4 — known, pinned, revisitable)"* |

> ⭐ **This is the item most easily missed.** The values do not move, so a change applied without reading decision 2 would leave the contract **internally contradictory** — stating both that statics without `$this` are isolated and that calls connect them.

## 6 · What this draft does NOT do

**Nothing applied.** Contract · `expected.json` · fixtures · `Lcom4Collector.php` — **all untouched** (0 changes measured). No Python. No re-verification. No Stage 2. The wording is **derived from the accepted direction**, not from the current implementation — the inversion the correction exists to fix. **This lane does not complete its own assignment (`G-1`) and does not self-certify.**

**Forward constraint restated:** per `R-34`, **the process that drafted this must not perform the later re-verification** of the corrected reference.

## 7 · Decisions required from the PO/ARB

1. **Approve the sixth pinned decision's wording** (§2) — or amend it.
2. **Decide the first-class-callable case** (§3.1) — Architecture recommends *include*; it is the one case the direction does not settle.
3. **Approve the three proposed fixtures and their expected values** (§4) — including the `parent::` fixture that pins an exclusion.
4. **Approve the amended wording of the `static_methods` decision** (§5) — without it the contract becomes self-contradictory.

**On approval:** application is a separate act, then re-verification by a different process, then Stage 2 may be re-commissioned under a **new** assignment (`R8`).

---

*Technical references: `G-KOS-LCOM4-CONTRACT-DRAFT` · PO/ARB decision and clarification 2026-08-16 · Stage 1 verdict `29b3280f` · `scripts/observations/examples/lcom4/expected.json` (`_variant`, five pinned decisions) · seven golden fixtures (call-form census: only `$this->` present) · `Lcom4Collector.php:61` · `R-34` · `R8` · `G-1` · `INV-ATTR-2`.*
