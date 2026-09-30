# OWD-5 — nested closure attribution: real, symmetric, material, STOP before correction

**Context:** OWD-1's other HIGH-priority finding, investigated to the same depth as the
property-identity thread (OWD-2/3/4) · **Date:** 2026-09-27
**Phase:** characterization only. **No production code changed.**

---

## Why this, now

OWD-1 flagged two HIGH-priority candidate omissions. The property-identity thread
(OWD-2→3→4) resolved one fully. This is the other, not yet given its own deep
investigation: `ast.walk(m)` recurses into a nested `FunctionDef`/`AsyncFunctionDef`/
`Lambda`, folding its own `self`/`cls` references into the *outer* method's facts.

## Recovered meaning

No pinned decision (`expected.json`'s eight entries) addresses nested functions or
closures at all. This is genuinely unexamined territory, not a documented, deliberate
scope boundary.

## Grounding in the real corpus, before guessing at synthetic fixtures

Pulled every real nested-function-in-method occurrence from `contextlib.py` (one of the
35 real files OWD-1 flagged). Six found, **all six the same shape**:
```python
def __call__(self, func):
    @wraps(func)
    def inner(*args, **kwds):
        with self._recreate_cm():
            return func(*args, **kwds)
    return inner
```
`__call__` **defines and returns** `inner` — it never calls `inner()` itself. The
closure's `self._recreate_cm()` reference belongs to code that runs *later*, when
whatever holds the returned closure eventually calls it — not to `__call__`'s own
direct execution. Every one of the six real examples found (`ContextDecorator.__call__`,
`AsyncContextDecorator.__call__`, `_BaseExitStack._create_cb_wrapper`,
`ExitStack.__exit__`'s `_fix_exception_context`, `AsyncExitStack
._create_async_cb_wrapper`, `AsyncExitStack.__aexit__`) is this "decorator/wrapper
factory" idiom — zero examples of an immediately-invoked nested closure were found.

## Candidate models

- **Model X (current)**: attribute the closure's references to the enclosing method.
  Rejected by the real evidence above — it makes `__call__` appear to directly touch
  `self._recreate_cm()`, when it provably never does in its own execution.
- **Model Y**: exclude nested function/lambda subtrees from the enclosing method's own
  fact-walk entirely. Matches the existing "declared in the unit's own body" principle
  (already established, R2/OWD-3) at one more level of nesting — a nested `def` is not
  a class member and not part of the enclosing method's own top-level body.
- **Model Z**: treat the nested closure as its own graph node. Rejected: it isn't a
  declared class member (the existing contract's own definition of "node"), and
  real-world closures are typically unnamed/anonymous or locally-scoped, unlike the
  named getter/setter case OWD-2/3 addressed.

**Model Y is favored by the real evidence** — not chosen a priori.

## Confirmed symmetric, not Python-specific

Direct execution, PHP:
```php
class A {
    function f() { return function() { return $this->y; }; }
    function g() { return $this->x; }
}
```
→ `f: stateAccesses=1 [y]` — **identical misattribution**. `PhpFactExtractor::factsIn()`
is a flat token scan over the enclosing method's brace-matched range; a nested closure's
tokens are textually inside that range regardless of scope, so `$this->y` is picked up
and attributed to `f`. This is not "Python lacks what PHP has" (R3's shape) — **both
independently-implemented adapters share the identical blind spot**, for structurally
different reasons (`ast.walk` recursion vs. flat token range) converging on the same
wrong result.

## Materiality — real, not merely theoretical

Fixture modeled directly on the real `contextlib.ContextDecorator.__call__` shape, both
languages:
```
PHP:    edges=[[__call__,_recreate_cm,behaviour]]  LCOM4=2
Python: edges=[[__call__,_recreate_cm,behaviour]]  LCOM4=2
```
`__call__` never calls `_recreate_cm()` in its own direct execution — this edge is
false. **The correct value, if the closure's reference were not misattributed, would be
3** (three genuinely independent methods, none touching anything in their own body) —
a real `LCOM4` value change, not merely a noisier edge trail, exactly the same shape of
materiality as R1/R3/R5/OWD-3.

## Classification: **E**

Shared analysis/pipeline limitation, present independently in both adapters, matching
neither "Python behind PHP" (D, R3's shape) nor "canonical vocabulary insufficient" (C)
— the existing `StateAccess`/`BehaviourReference` types are perfectly adequate; the bug
is entirely in what gets walked before those facts are constructed.

## What a correction would look like, sketched, not implemented

- **Python**: stop `ast.walk(m)` from descending into nested `FunctionDef`/
  `AsyncFunctionDef`/`Lambda` subtrees when collecting `m`'s own facts (walk the
  method body but prune at each such nested node, rather than the current unconditional
  recursion).
- **PHP**: `PhpFactExtractor::factsIn()` would need to detect and skip nested closure
  token ranges (`function(...) use (...) { ... }` / arrow functions), analogous to how
  `insideNestedUnit()` already skips anonymous classes — the same kind of range-skipping
  mechanism already exists in this file for a structurally similar problem.
- Both are adapter-local; **no `Domain` change anticipated** (parallel to R3/R5, not
  OWD-3).

## Remaining uncertainty, explicitly not resolved here

The rare, real-corpus-unconfirmed "immediately-invoked nested closure" case (e.g. a
closure defined and called within the same statement) was not found in this corpus pull
and is not characterized here — if it exists elsewhere, Model Y would make that closure's
references invisible to the outer method too, which may or may not be the intended
result. Not pursued further, consistent with not manufacturing synthetic cases the real
evidence doesn't support.

## Recommendation

This is materially significant (a real `LCOM4` value error, confirmed on a fixture
modeled on real, common stdlib code — decorator/wrapper factories are a widespread
Python idiom) and symmetric (affects PHP too, previously unnoticed because no PHP
fixture in this entire investigation ever exercised a closure). Recommend authorizing
correction, same RED→GREEN discipline as every prior slice, in both adapters — but
**not implemented here**, per the standing "report and stop before correction" rule for
every new C/D/E finding in this investigation.

**No production change made. Stopping here — no implementation without explicit
authorization.**

**Traceability:** `contextlib.py` (real corpus, `/home/nab-raj.roshyara@dg-nexolution.de/
.pyenv/versions/3.13.2/lib/python3.13/contextlib.py`, six real examples extracted) ·
`extract_facts.py`, `PhpFactExtractor.php` (both read, confirmed symmetric) ·
`NestedClosureAttributionCharacterizationTest.php` (new, 1/1 green, full Cohesion suite
149/149) · `2026-09-27-KOS-PYTHON-OPEN-WORLD-CORPUS-DISCOVERY.md` (OWD-1, the original
finding this investigates in depth).
