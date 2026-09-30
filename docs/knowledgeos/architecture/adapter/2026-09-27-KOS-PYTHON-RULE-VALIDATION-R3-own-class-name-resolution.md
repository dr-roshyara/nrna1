# R3 — `own_class_name_resolution`: characterization, classification D, STOP before correction

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 3 · **Date:** 2026-09-27
**Status:** characterization + classification only. **No production code changed.** A
corrective sketch is offered at the end; not authorized, not implemented.

---

## 1. Analytical meaning of `own_class_name_resolution`

The pinned text alone ("OwnClass::m() is recognized only when the name matches the
class's own declared name AS WRITTEN; namespaced or aliased spellings are not resolved")
undersells what the code actually does — recovered by reading `PhpFactExtractor::
classifyQualifier()`/`relationTo()` and `EdgeRules::verdict()` directly, not by inference:

PHP resolves a written name to **five** distinct spelling kinds (`QualifierKind`:
`UnqualifiedName`, `FullyQualifiedName`, `RelativeName`, `QualifiedName`, `AliasedName`),
each reduced to a fully-qualified identity and compared against the analysed unit's own
qualified name. Existing, accepted tests confirm the actual split:
- **Honoured for inclusion** when they denote the unit: `UnqualifiedName`,
  `FullyQualifiedName`, `RelativeName` (`test_fully_qualified_and_relative_denoting_the_unit_are_included`, `test_a_fully_qualified_own_reference_creates_an_edge`).
- **Excluded BY KIND, unconditionally, regardless of actual denotation**: `QualifiedName`,
  `AliasedName` (`test_a_qualified_unaliased_reference_is_excluded_...`,
  `test_aliased_is_excluded_as_a_stated_limitation_even_when_it_denotes_the_unit`).

So "not resolved" means, precisely: for two of the five kinds, `EdgeRules` deliberately
never consults the already-computed `TargetUnitRelation` at all — a stated distrust of
those two spellings, not an inability to compute the relation.

## 2. Semantic property being protected

Two independent properties, previously conflated by the summary wording: (a) a
language-neutral capability — *can the analysis tell that a written name denotes the class
itself?* — and (b) a PHP-specific, deliberately conservative policy — *for two particular
spelling forms, don't trust that computation even when it succeeds.* R3 is really about (a);
policy (b) is orthogonal and, on the evidence below, doesn't need re-litigating for Python.

## 3/4. PHP vs. Python characterization (measured, real repo code)

**PHP** (bare unqualified own-name, the literal case the pinned text names):
```php
class Fq { function a(){ Fq::b(); } function b(){} function lonely(){ $this->z; } }
```
→ `Fq::b()` resolves to `QualifierKind::UnqualifiedName` / `TargetUnitRelation::
DenotesAnalysedUnit`, included → edge `a→b` → **LCOM4 = 2** (correct: `{a,b}`, `{lonely}`).

**Python**, mechanically equivalent fixture:
```python
class Fq:
    def a(self): Fq.b(self)
    def b(self): pass
    def lonely(self): return self.z
```
→ `Fq.b(self)` produces **zero facts of any kind** — confirmed directly:
`$methodA->behaviourReferences === []`. Root cause, read directly in `extract_facts.py`:
`_receiver_qualifier()` maps exactly `{"self": ..., "cls": ...}`; any other receiver name
(`Fq`, or an alias for it) returns `None`, so neither the `ast.Call` nor the `ast.Attribute`
branch (both gated on `_receiver_qualifier(...) is not None`) ever fires.

**Materiality — measured**: Python LCOM4 = **3** on this fixture (`a`, `b` wrongly appear as
two separate isolated components) vs. PHP's correct **2**. A second fixture
(`Alias = Fq` then `Alias.b(self)`) shows the identical blind spot — **confirming one root
cause** (any receiver ≠ `self`/`cls` is invisible), not five separate per-kind gaps the way
PHP's vocabulary might suggest.

## 5. L3 comparison

PHP already has the full closed vocabulary this needs (`UnqualifiedName`, `AliasedName`,
`TargetUnitRelation`) — nothing new. Python currently emits **no L3 fact whatsoever** for
this construct — not a wrong value in an existing field, an absent one, structurally
identical in shape to the disclosed D-1 boundary but for the opposite reason: **the target
here is a perfectly ordinary, statically-known name; PHP already resolves it correctly.**
This is not indeterminacy — it's adapter incompleteness.

## 6/7. L4 / L5 comparison

Not comparable directly — Python produces no edge to compare, only a wrong LCOM4 (measured
above). No `L4` rule change is implicated; `EdgeRules` already has the correct behaviour for
every one of the five kinds, PHP proves it daily.

## 8. Falsification results (adapted to this case's shape)

- Genuinely different mechanism, same relevant semantics? Not applicable here — this isn't
  a case of two adapters converging or diverging on a shared construct; Python's adapter
  simply never attempts this construct at all.
- **D — Python adapter limitation**: confirmed. The canonical vocabulary is already
  sufficient (unlike R1); the Python adapter cannot currently produce the required
  representation because its receiver recognition is hardcoded to two literal tokens.
- **C — canonical insufficiency**: rejected. Nothing in `Domain` needs to change;
  `QualifierKind`/`TargetUnitRelation`/`EdgeRules` already fully specify and correctly
  handle every one of the five kinds.

## 9. Classification: **D**

## 10. Whether the existing contract survives

Yes, unchanged. This is the first R-slice where the *contract* (pinned decision +
`EdgeRules`) is not in question at all — only the Python adapter's coverage of it.

## 11. Whether L3 requires any new concept

No.

## 12. Whether an adapter change is required

**Yes** — this is the first genuine, evidence-backed case in `KOS-PYTHON-RULE-VALIDATION`
where the correction belongs entirely in `extract_facts.py`, not in `Domain`. Sketch only,
not implemented: `_receiver_qualifier()` would need to also recognise a receiver whose name
equals the enclosing class's own declared name (→ `UnqualifiedName`) or a module-level
alias resolving to it (→ `AliasedName`), computing `TargetUnitRelation` the same way
`relationTo()` does for PHP — a same-class-name string comparison, no import-graph
resolution needed for the bare case, and a small alias table (analogous to `PhpFactExtractor
::$aliases`, built from top-level `Name = Name` assignments) for the aliased case. **This
is a proposal, not a plan under way — no code will change until explicitly authorized.**

## 13. Architecture consequence

Confirms, from the opposite direction of R1/R2, that this branch's method correctly
distinguishes *kinds* of finding: R1 was a genuine `Domain`/L3 gap; R2 found no gap at all;
R3 is a genuine gap that is entirely adapter-local. The same investigative method produces
three different, correctly-differentiated outcomes — evidence the classification discipline
itself is working, not just producing one answer by default.

## 14. One highest-information next question

Not selected yet, pending your decision on whether to authorize the R3 correction first.
Two live candidates for *after* R3 closes: (a) `magic_methods` — "no special handling beyond
constructor/destructor exclusion," now directly testable against R1's frozen `MethodRole`
mechanism; (b) whether the *same* root cause found here (receiver-qualifier restricted to
`self`/`cls`) also affects **state access**, not just behaviour references (untested in this
slice — the pinned decision is specifically about `OwnClass::m()`, method calls, so state
access was intentionally left out of this slice's scope, not overlooked).

## Required decision

**No production change made.** Per the standing rule for C/D findings: reporting and
stopping before correction. Please confirm whether to authorize the sketched
`extract_facts.py` fix (RED→GREEN, same discipline as R1) as the next step, or hold R3 as a
documented, bounded limitation for now.

**Traceability:** `PhpFactExtractor.php` (`classifyQualifier`, `relationTo`), `EdgeRules.php`,
`QualifierKind.php`, `TargetUnitRelation.php` (read directly) ·
`OwnClassNameResolutionExperimentTest.php` (new, 3/3 green, 107/107 full Cohesion suite) ·
`CohesionSemanticsTest.php`, `CohesionPipelineTest.php` (existing accepted tests this
report's PHP claims are grounded in) · prior R1/R2 reports, same directory.
