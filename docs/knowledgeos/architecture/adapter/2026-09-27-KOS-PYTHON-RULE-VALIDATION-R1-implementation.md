# R1 constructors — GREEN implementation, TDD, frozen

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 1 · **Date:** 2026-09-27
**Sequence:** characterization (`...-R1-constructors.md`) → placement determination
(`...-R1-placement-determination.md`) → **this: RED→GREEN implementation.**

> This is a real, tested `L3` change — the first this whole investigation ever made. Scope
> held to exactly the one slice authorized: `MethodRole::{Ordinary, Lifecycle}`. No other
> Domain code, `EdgeRules`, `expected.json`, or `deptrac.yaml` touched.

---

## 1. RED tests and exact failure

`tests/Unit/Cohesion/MethodRoleLifecycleNormalizationTest.php`, written before any
production change, run against the unmodified repo:

```
5 tests, 5 errors: Class "EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodRole" not found
```

(Test B, run separately as a value-level check rather than a class-not-found check, would
have failed on `assertSame(2, ...)` receiving `1` — the exact reproduction from the
characterization report — but the missing class made every test fail at construction time
first, which is the stronger RED.)

## 2. Minimal L3 design

```php
enum MethodRole { case Ordinary; case Lifecycle; }
```
Added as a 5th, defaulted (`= MethodRole::Ordinary`) constructor parameter on `MethodFacts`
— every one of the ~25 pre-existing call sites across `tests/Unit/Cohesion/` needed zero
changes. Exactly two cases, matching the pinned decision's own single undifferentiated
exclusion bucket (`constructors`) — no `Constructor`/`Destructor`/`Finalizer`/`MagicMethod`
split, as directed.

## 3. GREEN implementation

- `Domain/MethodRole.php` — new, 2-case closed enum.
- `Domain/MethodFacts.php` — new defaulted param, 1 line.
- `Domain/GraphBuilder.php` — `EXCLUDED_METHODS` constant deleted; both `in_array($method->methodIdentity, ...)` checks replaced with `$method->methodRole === MethodRole::Lifecycle`. Zero string comparison of any kind remains.
- `Infrastructure/Php/PhpFactExtractor.php` — new `PHP_LIFECYCLE_METHOD_NAMES` constant (`__construct`/`__destruct`) **in the adapter**, sets `$role` per method, passed into `MethodFacts`.
- `Infrastructure/Python/extract_facts.py` — new `_LIFECYCLE_METHOD_NAMES = {"__init__", "__del__"}` **in the adapter**; emits `"methodRole"` per method.
- `Infrastructure/Python/PythonSemanticFactProvider.php` — deserializes `methodRole` via the existing `enumCase()` helper, unchanged in kind.
- `tests/Unit/Cohesion/CohesionSemanticsTest.php` — one existing test
  (`test_constructors_are_excluded_from_the_node_set`) updated, not silently: it hand-builds
  L3 facts directly (no adapter), so it now passes `MethodRole::Lifecycle` explicitly, with a
  docblock explaining the name `'__construct'` is now incidental fixture flavour, not the
  trigger.

## 4. PHP behavior

Unchanged in every existing PHP test (94 pre-existing + this session's new ones, all still
green). Directly re-confirmed: `__construct` → `Lifecycle` → excluded from nodes;
`__destruct` → `Lifecycle` → excluded identically (new Test A variant).

## 5. Python behavior

Corrected. `__init__` → `Lifecycle` → excluded from nodes; no more spurious
`__init__→f`/`__init__→g` state edges.

## 6. Field-by-field L3 convergence

Test D asserts directly: PHP `__construct` and Python `__init__` both yield
`MethodRole::Lifecycle`; ordinary methods (`f`, `g`) yield the identical role value across
languages. Convergence is asserted at L3, before L4/L5 are even built — the validation
order the refinement asked for.

## 7. L4 graph convergence

Same test: `$phpObserved[0]['nodes'] === $pythonObserved[0]['nodes']` and same for `edges`
— both `['f','g']` / `[]`, byte-identical, not just numerically coincidental.

## 8. L5 metric convergence

Independently re-confirmed outside the test suite too, on the exact original fixture from
the characterization report, real repo code, throwaway probe deleted after use:
```
PHP    LCOM4: 2  nodes: f,g
Python LCOM4: 2  nodes: f,g
CONVERGED
```
(Previously: PHP 2 vs. Python 1 — the material divergence this whole slice exists to fix.)

## 9. Full test-suite result

```
Before (baseline):  95 tests green
After (this slice): 101 tests green (95 + 6 new: 5 RED/GREEN + 1 falsification)
0 failures, 0 errors, 0 skipped
```
No other Cohesion test needed modification beyond the one disclosed in §3. Grep-confirmed:
no code outside the Cohesion capability references `MethodFacts`, `GraphBuilder`, or
`PhpFactExtractor` — no blast radius beyond this capability.

## 10. Remaining PHP-specific language leaks in the canonical layer

None found as a side effect of this slice. Every remaining `__construct` string in
`Domain/` (grepped, listed) is PHP's own constructor-method *keyword*, required by the
language itself to declare any of these classes' own constructors (`EdgeRules`,
`GraphBuilder`, `Lcom4`, `MethodFacts`, `StateAccess`, `BehaviourReference`, etc.) — not a
comparison against a target method's spelling. Zero of these are decision logic. This is
not a systematic sweep of the whole repository (explicitly out of scope for this slice) —
only of `Domain/`, which is what this slice touched.

## 11. Classification of the original finding

**C — canonical representation insufficiency**, confirmed and now closed by correction
(not merely characterized). The placement determination (B: a general closed-vocabulary
method-role fact) is validated by the implementation succeeding with zero collateral
change to `EdgeRules`, `expected.json`, or any unrelated Domain type.

## 12. Architecture consequence

The general rule the refinement named is now demonstrated, not just argued:
*language-specific spelling terminates at the adapter boundary.* `GraphBuilder` — the
consuming L4 logic — now contains no PHP-specific or Python-specific token anywhere. Both
adapters independently reduce their own language's lifecycle convention to the same
closed-vocabulary fact, exactly parallel to how `QualifierKind` already worked for
behaviour references. This is now the second real L3/L4 architectural precedent in this
investigation (the first being the pre-existing `QualifierKind`/`EdgeRules` design itself)
— not a one-off patch.

## 13. Whether this rule is now frozen

**Yes.** R1 (constructor/destructor exclusion) is closed: characterized, placed,
implemented, tested (RED, GREEN, cross-language L3/L4/L5 convergence, falsification), and
regression-proofed. No further work authorized on R1 specifically.

## 14. The single highest-information next research question

Per explicit instruction, **not pursued now, only named**: of the eight pinned-decision
keys (`constructors` [closed], `intra_class_calls`, `first_class_callables`,
`static_methods`, `trait_methods`, `inherited_methods`, `magic_methods`,
`own_class_name_resolution`), the highest-information next candidate is **`trait_methods`**
— the only one with no direct Python language analogue at all (Python has no `trait`
construct), forcing an explicit choice between a considered analogue (e.g. mixins) and an
honest out-of-scope classification, rather than a straightforward adapter mapping like this
slice's `__init__`↔`__construct`. Every other untested key maps onto an existing Python
construct fairly directly; `trait_methods` is the one that tests whether the *contract
vocabulary itself* (not just an adapter) has an analogue-shaped gap.

**Stopping here, per instruction. No further slice started.**

**Traceability:** `MethodRole.php`, `MethodFacts.php`, `GraphBuilder.php`,
`PhpFactExtractor.php`, `extract_facts.py`, `PythonSemanticFactProvider.php`,
`MethodRoleLifecycleNormalizationTest.php`, `CohesionSemanticsTest.php` (all read/modified
directly, this session) · `...-R1-constructors.md`, `...-R1-placement-determination.md`
(prior reports, same directory, same slice).
