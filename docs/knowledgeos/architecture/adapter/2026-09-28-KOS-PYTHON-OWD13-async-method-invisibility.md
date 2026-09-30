# OWD-13 — async method invisibility: the most foundational finding in this investigation, STOP before correction

**Context:** discovered by expanding the Python real-corpus audit beyond OWD-1's
top-level-only scope · **Date:** 2026-09-28
**Phase:** characterization only. **No production code changed.**

---

## Why this, now

OWD-1 explicitly scoped to the 153 top-level stdlib files, naming subpackage exclusion
as "a scope decision, not a completeness claim." OWD-12 audited the PHP corpus
broadly and found nothing new; the natural higher-information move was to extend the
Python side into that named-but-unexplored territory rather than re-scan files
already covered by OWD-1/6/9. Ten representative, complexity-diverse subpackage
modules were run through the real, unmodified, fully-fixed adapter: `unittest.mock`,
`xml.etree.ElementTree`, `email.message`, `importlib.abc`, `asyncio.base_events`,
`http.client`, `urllib.request`, `logging` (top-level `__init__.py`), `multiprocessing
.managers`, `wsgiref.handlers`.

## The discovery

All ten: 0 crashes. But `asyncio/base_events.py` showed something never seen before in
this investigation — a **discrepancy between a raw AST method count (100) and the
adapter's own reported method count (71)**. Every prior file, across OWD-1's original
153 and every synthetic fixture in R1–R6, these numbers always matched.

Root cause, confirmed by direct code reading:
```python
method_defs = [n for n in class_node.body if isinstance(n, ast.FunctionDef)]
```
This filter **never matches `ast.AsyncFunctionDef`**. Every `async def` method inside a
class is invisible to the adapter — not a node, not in `method_names`, not analysed in
any way.

## Why this is more foundational than R1–R6/OWD-1–12

Every prior finding was a construct *nuance*: a specific spelling, a name collision, a
scope boundary, a call form. This is a **wholesale category exclusion** — an entire
kind of Python method (`async def`, fundamental to `asyncio`, and increasingly common
in ordinary modern Python via async web frameworks) has never been recognized at all,
in any prior slice of this entire investigation.

## Materiality — confirmed in both directions, by direct execution

**Case 1 — an async method's own facts vanish entirely:**
```python
class A:
    async def fetch(self):
        return self.data
    def sync_method(self):
        return self.data
```
→ `method count reported: 1` (only `sync_method`; `fetch` does not exist in the
extracted facts at all).

**Case 2 — a legitimate sync→async call is wrongly excluded as out-of-frame:**
```python
class A:
    def sync_caller(self):
        return self.async_worker()
    async def async_worker(self):
        return 1
```
→ `sync_caller`'s call is excluded with reason `TargetNotDeclaredHere` — the SAME
exclusion reason used for a genuinely different class's method, even though
`async_worker` is declared right there in the same class. Not merely unrecognized —
actively misclassified as belonging to a different frame.

**Real-corpus signal**: `asyncio/base_events.py` alone shows 29 real methods (out of
100) silently missing from the adapter's view — in a single file, not a contrived
count.

## Classification: **D**

Python-only adapter limitation. PHP has no `async`/`await` concept to compare against
— there is no D-shaped "PHP already handles it" story here, but the classification
still fits D's definition precisely: the existing canonical vocabulary
(`MethodFacts`/`BehaviourReference`/`GraphBuilder`) is already fully sufficient in
shape — nothing about `GraphBuilder`, `EdgeRules`, or `Lcom4` cares whether a method
is sync or async. This is purely a recognition gap in one `isinstance()` check, not a
missing `Domain` concept.

## What a correction would look like (sketched, not implemented)

Extend `method_defs`'s filter to `isinstance(n, (ast.FunctionDef, ast.AsyncFunctionDef))`.
This is a smaller, more surgical change than any prior correction in this
investigation — a one-line filter extension, not a new algorithm (unlike OWD-3's
occurrence-keying or OWD-10's expression-boundary detection). The main risk to verify
during implementation: every OTHER place in `_extract_one()` that currently assumes
`ast.FunctionDef` specifically (decorator detection, lifecycle-role tagging,
`_is_call_target`'s inner walk, the `_walk_own_scope` nested-scope-boundary check in
OWD-5, which already includes `ast.AsyncFunctionDef` in its prune list) needs
re-auditing for whether it also needs to accept `ast.AsyncFunctionDef` symmetrically.

## Recommendation

This is materially significant — confirmed in two directions, on a real, substantial
file (`asyncio/base_events.py`), and structurally the most foundational gap this
investigation has found (a whole method-kind, not a construct variant). Recommend
authorizing correction under the same RED→GREEN discipline as every prior slice — but
**not implemented here**, per the standing rule for every new finding.

**No production change made. Stopping here — no implementation without explicit
authorization.**

**Traceability:** `unittest/mock.py`, `xml/etree/ElementTree.py`, `email/message.py`,
`importlib/abc.py`, `asyncio/base_events.py`, `http/client.py`, `urllib/request.py`,
`logging/__init__.py`, `multiprocessing/managers.py`, `wsgiref/handlers.py` (real
corpus, all ten run through the real adapter, 0 crashes, method-count discrepancy
found in one) · `extract_facts.py` (`method_defs` filter, read and confirmed directly)
· `AsyncMethodInvisibilityCharacterizationTest.php` (new, 2/2 green, full Cohesion
suite 177/177) · `2026-09-27-KOS-PYTHON-OPEN-WORLD-CORPUS-DISCOVERY.md` (OWD-1, the
original top-level-only scope decision this report extends beyond).
