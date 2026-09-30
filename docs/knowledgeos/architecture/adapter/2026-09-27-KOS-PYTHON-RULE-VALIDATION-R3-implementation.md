# R3 — GREEN implementation, TDD, frozen

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 3 · **Date:** 2026-09-27
**Sequence:** characterization + classification D (`...-R3-own-class-name-resolution.md`)
→ **this: RED→GREEN implementation**, entirely adapter-local, zero `Domain` change.

---

## RED

`OwnClassNameResolutionCorrectionTest.php`, written before any change, run against the
unmodified adapter: 2 of 4 failed as predicted (bare own-name call: expected `edges =
[[a,b,behaviour]]`, got `[]`; aliased call: expected a real fact, got none). The other two
(out-of-scope bare name to a *different* class; `self`/`cls` regression guard) already
passed, correctly, since the fix must not touch either.

## GREEN — entirely in `extract_facts.py`, confirming the D classification

- `_build_own_name_alias_map(tree)` — new, module-level `Alias = Name` bindings only
  (single target, single value, both bare `Name` nodes) — the bounded Python analogue of
  `PhpFactExtractor::$aliases`.
- `_own_class_name_qualifier(node, own_name, own_aliases)` — new: receiver equals the
  enclosing class's own name → `UnqualifiedName`; receiver is an alias for it →
  `AliasedName`; anything else → `None` (unrecognised, unchanged from before — no general
  cross-unit resolution introduced).
- One new `elif` branch in the per-method walk, mirroring the existing `self`/`cls`
  branch exactly in shape.
- **Zero changes** to `Domain/` (`QualifierKind`, `TargetUnitRelation`, `EdgeRules` already
  had everything needed) and **zero changes** to `PythonSemanticFactProvider.php` (the new
  qualifier-kind strings are existing enum cases; `enumCase()` resolves them unchanged).

## Cross-language convergence, field-by-field

Bare own-name call: Python now reports `UnqualifiedName`/`DenotesAnalysedUnit`, identical
to PHP's own facts for the mechanically equivalent source — asserted directly, not only at
the metric. Graph: `[[a,b,behaviour]]` both languages. Metric: LCOM4 = 2 both languages
(previously 3 vs. 2).

Aliased call: now produces real L3 evidence (`AliasedName`/`DenotesAnalysedUnit`) instead
of silence, and is excluded at L4 with `AliasedSpelling` — the same stated limitation PHP
already applies to its own aliased spellings. This is an evidence-precision improvement,
not a metric change (both before and after: no edge) — the same shape as the earlier
frozen inheritance evidence-precision correction.

## Falsification held

A bare name denoting a genuinely *different* class (`Other.b(self)`, `Other` is neither the
analysed class's name nor an alias for it) remains unrecognised — confirmed by a dedicated
test. The fix's scope is exactly "does this receiver denote the analysed unit itself,"
nothing broader.

## Full test-suite result

```
Before this slice: 107 tests green
After:             111 tests green (107 + 4 new correction tests)
```
Two pre-existing R3-characterization tests updated (disclosed, not silent — history
preserved in a docblock, same convention as R1's `CohesionSemanticsTest` update): they
asserted the pre-fix limitation; now assert the corrected behaviour.

## Classification confirmed

**D — adapter limitation, corrected.** `git diff`, scoped: only `extract_facts.py`
changed among production files; `Domain/` and `PythonSemanticFactProvider.php` untouched —
direct confirmation that the placement judgment in the characterization report was
correct, not merely convenient.

## Architecture consequence

Third distinct outcome shape in three slices (R1: genuine `Domain` gap; R2: no gap;
R3: genuine adapter-only gap) — further evidence the classification discipline
distinguishes real cases rather than defaulting to one answer.

**R3 is now frozen.** Stopping here — no R4 started automatically.

**Traceability:** `extract_facts.py` (modified), `OwnClassNameResolutionCorrectionTest.php`
(new), `OwnClassNameResolutionExperimentTest.php` (updated, history preserved) ·
`...-R3-own-class-name-resolution.md` (characterization, same branch).
