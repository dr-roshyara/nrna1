# OWD-5 — GREEN implementation, TDD, cross-adapter, frozen

**Context:** OWD-5 follow-up · **Date:** 2026-09-27
**Sequence:** characterization + classification E (`...-OWD5-nested-closure-attribution.md`)
→ **this: RED→GREEN, both adapters, zero `Domain` change.**

> Second cross-adapter correction in this branch (after OWD-3's write-access follow-up
> in R5) — this time because the bug was symmetric from the start (confirmed via direct
> PHP execution during characterization), not because one language's fix generalized to
> the other.

---

## RED

`NestedClosureAttributionCorrectionTest.php`, 6 tests, written before any change, run
against the unmodified (post-OWD-4) adapters: 4 failed exactly as predicted (the real
`contextlib`-modeled fixture; the simple `f`/`inner` case; async nested function;
lambda). The other 2 (comprehension falsification; ordinary-method regression guard)
already passed, correctly — the fix must not touch either.

## GREEN — one adapter-local change each, symmetric in intent, different in mechanism

**Python** (`extract_facts.py`): new `_walk_own_scope()`, a breadth-first traversal
(mirroring `ast.walk`'s own order, to avoid incidental behavior changes beyond the fix)
that yields every descendant except it does not descend past a nested `FunctionDef`,
`AsyncFunctionDef`, or `Lambda` node. Comprehensions/generator expressions are correctly
**not** scope boundaries here (verified by falsification test) — their body executes
immediately as part of the enclosing method, unlike a genuinely deferred nested `def`.
One-line change to use it in place of `ast.walk(m)`.

**PHP** (`PhpFactExtractor::factsIn()`): a new check, structurally mirroring
`methodsOf()`'s own existing "skip the whole method body" pattern — on encountering a
`T_FUNCTION` token inside the method's own token range, find its body brace and matching
end (reusing the existing `findBodyBrace()`/`matchBrace()` helpers unchanged) and jump
past it. Arrow functions (`T_FN`) are **deliberately not handled** — a separate, smaller,
explicitly out-of-scope gap, named but not pursued.

**Zero `Domain` changes.** Both `StateAccess` and `BehaviourReference` already had
everything needed — confirming the E classification: this was purely a scope-boundary
question in extraction, never a canonical-vocabulary one.

## Falsification held, both languages

- A comprehension containing a `self.` reference is still correctly attributed to the
  enclosing method (not treated as a scope boundary) — confirmed directly.
- An `async def` and a `lambda` are both pruned identically to a plain nested `def`.
- An ordinary method with no nested closure at all is completely unaffected in both
  languages (regression guard).

## Materiality — the correction closes exactly the gap measured

```
Real-corpus-modeled fixture (contextlib.ContextDecorator.__call__ shape):
  Before: edges=[[__call__,_recreate_cm,behaviour]]  LCOM4=2 (wrong)
  After:  edges=[]                                    LCOM4=3 (correct)
  Both languages, identical before and after.
```

## Full test-suite result

```
Before this slice: 149 tests green
After:             155 tests green (149 + 6 new correction tests)
```
Two pre-existing test files updated (disclosed, not silent, same convention as every
prior slice): `NestedClosureAttributionCharacterizationTest.php` (docblock marked
historical, assertions corrected in place) and
`OpenWorldCorpusDiscoveryCharacterizationTest.php` (one assertion corrected).

## Classification confirmed

**E, corrected.** Unlike every prior D-classified fix (R3, R5's first half), this bug
was never "Python behind PHP" or vice versa — it was independently present in both,
for structurally different reasons (`ast.walk` recursion vs. a flat token scan), and
required two independently-designed but symmetric-in-intent corrections, not one fix
generalized to a second language.

## Architecture consequence

The second correction in this branch (after R5's state-access fix) that required
touching production code in both languages simultaneously — but for a genuinely
different reason than R5: R5 generalized one recovered principle to both adapters
because the SAME construct existed in both; this bug was discovered independently
affecting both from the start, confirmed by direct symmetric testing during
characterization, not assumed. Both are now real precedents for "not every fix is
single-adapter," each for its own distinct reason.

**OWD-5 is now frozen.** Stopping here — no further experiment started automatically.

**Traceability:** `extract_facts.py`, `PhpFactExtractor.php` (both modified) ·
`NestedClosureAttributionCorrectionTest.php` (new, 6/6 green) ·
`NestedClosureAttributionCharacterizationTest.php`,
`OpenWorldCorpusDiscoveryCharacterizationTest.php` (updated, history preserved) ·
`...-OWD5-nested-closure-attribution.md` (characterization, same investigation).
