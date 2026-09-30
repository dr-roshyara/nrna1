# R5 — GREEN implementation, TDD, cross-adapter, frozen

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 5 · **Date:** 2026-09-27
**Sequence:** characterization + classification E (`...-R5-receiver-resolution-state-access.md`,
including the write-access addendum) → **this: RED→GREEN, both adapters, zero `Domain`
change.**

> First cross-adapter correction in this branch: `PhpFactExtractor.php` (PHP) and
> `extract_facts.py` (Python) both changed. No prior R-slice required this.

---

## RED

`ClassLevelStateAccessCorrectionTest.php`, 11 tests, written before any change, run
against the unmodified (post-R4) adapters: 4 failed exactly as predicted (PHP/Python
own-name reads, writes, materiality); the other 7 (falsification: `parent::`, aliased,
different-class, dynamic-call-via-variable; regression: instance access unaffected)
already passed, correctly, since the fix must not touch any of them.

## GREEN — symmetric, minimally scoped

**PHP** (`PhpFactExtractor::factsIn()`): new branch recognising `<qualifier> :: $variable`
(not followed by `(`, which would be a dynamic call through a variable holding a method
name — left unrecognised, matching D-1's existing determinability boundary). Reuses the
existing `classifyQualifier()` resolution; only three of its possible results are honoured
as "own state" — `SelfKeyword`, `StaticKeyword`, and `UnqualifiedName` where
`TargetUnitRelation::DenotesAnalysedUnit` — deliberately excluding `AliasedName`,
`QualifiedName`, `FullyQualifiedName`, `RelativeName`, and `ParentKeyword`, matching the
pre-existing, already-accepted policy for method calls exactly (parent is out-of-frame,
aliases are a stated limitation).

**Python** (`extract_facts.py`): new `_state_access_qualifier()`, layered directly on top
of the existing `_receiver_qualifier()` (self/cls, unchanged) — adds recognition of the
analysed class's own bare name only, **deliberately not** its aliases (matches PHP's
`AliasedName` exclusion; `_own_class_name_qualifier` — which does recognise aliases, for
calls — was NOT reused here for exactly that reason). `super().x` remains unrecognised
unchanged (its receiver is a `Call`, never a bare `Name`).

**Zero `Domain` changes.** `StateAccess`'s existing shape (`propertyName`, `accessMode`)
already sufficed — confirming the E classification: this was purely an extraction gap,
not a canonical-vocabulary one.

## Falsification held, all four cases

`parent::$value` / aliased spellings / a genuinely different class's property / a dynamic
call through a variable (`self::$m()`) all remain unrecognised after the fix, confirmed by
dedicated tests — the fix's boundary is exactly what was authorized, nothing broader.

## Collateral consistency (not in the original measurement, added during GREEN)

Reusing the Python adapter's existing property/method-name classification logic for the
new own-name qualifier means `A.some_property` (a `@property`-decorated attribute) and
`A.helper` (a plain method named but not called) reach that same logic through one more
qualifier — verified directly to classify identically to the `self.`-equivalent forms
(`Invocation` and `CallableReference` respectively), not left as an untested side effect.

## Materiality — the correction closes the exact gap measured

```
Read-only shared static property:   PHP LCOM4 2→1, Python 2→1, now equal
Write-then-read (strongest case):   PHP LCOM4 2→1, Python 2→1, now equal
```

## Full test-suite result

```
Before this slice: 121 tests green
After:             132 tests green (121 + 11 new correction tests)
```
Five pre-existing R5-characterization tests updated (disclosed, not silent — same
convention as every prior slice): they asserted the pre-fix symmetric limitation; now
assert the corrected behaviour.

## Classification confirmed

**E — analysis/pipeline limitation, corrected.** Scoped `git diff`: only
`PhpFactExtractor.php` (+33/-1) among tracked files, plus `extract_facts.py` (untracked
dir) — no `Domain` file touched, exactly as the characterization report predicted.

## Architecture consequence

The first correction in this branch that was never a "Python catches up to PHP" story —
both adapters were extended together, symmetrically, from the same recovered principle
(own-name/self/static qualifiers denote the analysed unit's own state; aliases, qualified
names, and parent stay excluded, matching existing policy). This is a stronger form of
cross-language validation than R1–R4 produced: the correction was derived once, from the
shared gap, and applied to each language's adapter in its own idiom — not derived from one
language and ported to the other.

**R5 is now frozen.** Stopping here — no R6 started automatically.

**Traceability:** `PhpFactExtractor.php`, `extract_facts.py` (both modified) ·
`ClassLevelStateAccessCorrectionTest.php` (new, 11/11 green) ·
`ReceiverResolutionStateAccessExperimentTest.php` (updated, history preserved) ·
`...-R5-receiver-resolution-state-access.md` (characterization, same branch).
