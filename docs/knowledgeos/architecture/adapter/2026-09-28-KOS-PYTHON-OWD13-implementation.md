# OWD-13 — GREEN implementation, TDD, Python-only, frozen

**Context:** OWD-13 follow-up · **Date:** 2026-09-28
**Sequence:** characterization + classification D (`...-OWD13-async-method-invisibility.md`)
→ **this: RED→GREEN, one-line filter extension, zero `Domain`/PHP change.**

---

## RED

`AsyncMethodVisibilityCorrectionTest.php`, 5 tests, written before any change: 4 failed
exactly as predicted (isolated async method invisible; sync→async call wrongly
excluded; async method with `@property` untouched by the type check either way; an
`await self.other()` call not recognized at all since `other` never existed as a
method). The regression guard (ordinary class, no async methods) already passed.

## GREEN — a one-line filter extension in `extract_facts.py`

```python
method_defs = [n for n in class_node.body if isinstance(n, (ast.FunctionDef, ast.AsyncFunctionDef))]
```
Everything downstream — decorator detection, the property-target map, `_walk_own_scope`
(which already pruned `ast.AsyncFunctionDef` as a nested-scope boundary since OWD-5),
`_is_call_target`'s inner walk, lifecycle-role tagging — operates generically on any
AST node with the right shape and needed zero further changes, confirmed by the full
test pass on the first GREEN run. Three type hints updated for accuracy
(`_is_property_or_setter`, `_build_property_targets`, `_is_call_target`) — cosmetic,
not behavioral.

## Falsification held, all cases correct on the first GREEN run

`await self.other()` resolves as an ordinary call (the `Await` node is not a scope
boundary and was never treated as one). An async method decorated with `@property`
is classified exactly like a sync one. Ordinary, fully-synchronous classes are
completely unaffected.

## Materiality — confirmed at both the fixture and the real-corpus scale, in the same slice

```
Fixture (isolated async method):      method count 1 → 2, correctly connects via shared state
Fixture (sync→async call):            wrongly-excluded edge → real edge, LCOM4 unaffected but evidence now honest
Real corpus (10 subpackage files):    asyncio/base_events.py: raw/adapter method count 100/71 → 100/100
                                       all 10 files: exact match, 0 mismatches
```

## Full test-suite result

```
Before this slice: 177 tests green
After:             182 tests green (177 + 5 new correction tests)
```
One pre-existing test file updated (disclosed, not silent, same convention as every
prior slice): `AsyncMethodInvisibilityCharacterizationTest.php`.

## Classification confirmed

**D, corrected — Python-only, one-line fix.** `git status` scoped to `Domain`/PHP:
unchanged from before this slice. The smallest fix of any correction in this
investigation, despite characterizing as the most foundational finding — confirms the
D classification precisely: a pure recognition gap, not a missing concept.

## Architecture consequence

Closes the widest-reaching gap this investigation has found: an entire category of
Python method, not a construct variant. Every other correction (R1, R3, R5, OWD-3,
OWD-5, OWD-7/4, OWD-10) fixed how a *specific, already-visible* construct was
represented; this fixed whether a construct was *visible at all*. That its correction
turned out to be the smallest (one line) rather than the largest is itself informative
about where this codebase's real risk concentrated: recognition completeness, not
representation precision.

**OWD-13 is now frozen.**

**Traceability:** `extract_facts.py` (modified) · `AsyncMethodVisibilityCorrectionTest.php`
(new, 5/5 green) · `AsyncMethodInvisibilityCharacterizationTest.php` (updated, history
preserved) · `asyncio/base_events.py` and 9 other real subpackage files (re-validated,
0 mismatches) · `2026-09-28-KOS-PYTHON-OWD13-async-method-invisibility.md`
(characterization, same investigation).
