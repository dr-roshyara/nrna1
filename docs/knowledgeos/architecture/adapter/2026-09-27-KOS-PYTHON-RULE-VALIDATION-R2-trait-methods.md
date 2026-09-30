# R2 — `trait_methods`: characterization, no architecture change

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 2 · **Date:** 2026-09-27
**Result:** characterization only — 3 new tests, 0 production files touched, 104/104
Cohesion tests green.

---

## 1. Analytical meaning of `trait_methods`

Recovered from `expected.json` and its golden fixture (`trait-user.php`), not inferred:
> "NOT resolved — only methods declared in the class body are analyzed (known limitation)"

The fixture:
```php
trait SomeBehavior { public function traitMethod() { return $this->hidden; } }
class TraitUserExample {
    use SomeBehavior;
    private $own;
    public function ownMethod() { return $this->own; }
}
// -> TraitUserExample LCOM4 = 1
```
Directly confirmed against `PhpFactExtractor` (unmodified): `SomeBehavior` is its own
analysed unit (`TraitUnit`) with its own method `traitMethod`; `TraitUserExample`'s own
`methods` list contains only `ownMethod` — `use SomeBehavior;` is a statement inside the
class body, not a `T_FUNCTION` token, so it produces nothing the token-scanning extractor
sees as a method. **No trait-flattening happens anywhere in this pipeline, for PHP itself.**

## 2. Semantic property being protected

Not a trait-specific property. It is the *same* property `inherited_methods` already names
("the class is analyzed in isolation"): **an analysed unit's own method set is exactly what
is lexically declared inside its own body — no composition mechanism, of any kind, is
resolved or flattened into it.** PHP happens to expose two syntactic composition
keywords (`extends`, `use`); the pinned decision set names both, but they are one
underlying invariant, not two. Confirmed by reading `PhpFactExtractor`, `GraphBuilder`, and
`EdgeRules`: none of them distinguish "excluded because inherited" from "excluded because
from a trait" — both reach `ExclusionReason::TargetNotDeclaredHere` through the identical
node-matching fallback already characterized and frozen in the earlier inheritance branch.

## 3. PHP trait characterization

`SomeBehavior::traitMethod` — own unit, own method, never visible to `TraitUserExample`.
`TraitUserExample::ownMethod` — sole node, `$this->own` sole access, LCOM4 = 1 (matches the
pinned golden value exactly; re-verified this session, unmodified code).

## 4. Python mixin characterization

Two fixtures, both against the unmodified, already-frozen `PythonSemanticFactProvider`:
- Single mixin (`class A(Mixin)`, `Mixin.helper`, `A.f` calling `self.helper()`): `A`'s own
  method set is `['f']` only — `helper` is invisible, for the same structural reason
  already established in the frozen inheritance branch (`class_node.body` is never
  inheritance-aware; `bases` is never inspected).
- Multiple mixins (`class A(MixinOne, MixinTwo)`): identical shape — both external calls
  excluded independently, same reason, **confirming the extractor's behaviour is
  unaffected by how many bases exist**, because it never looks at `bases` at all.

## 5. L3 comparison

Both languages: the using/child unit's own `methods` list contains only its directly,
lexically declared methods. Neither adapter needed a new field, a new value, or any
change to produce this — the existing `methodIdentity`/`behaviourReferences` facts already
suffice, exactly as the dependency matrix already established for the inheritance case.

## 6. L4 comparison

Both: the external call becomes a `behaviourReference` with `targetUnitRelation =
DenotesAnalysedUnit`, `determinability = Determinable` (PHP natively; Python via the R1-era
evidence-precision correction, already frozen) — then excluded by `GraphBuilder`'s existing
node-matching fallback with reason `TargetNotDeclaredHere`. No branch anywhere asks "is this
target from a trait, a mixin, or a base class" — the graph shape is identical because the
*code path* is identical, not coincidentally similar.

## 7. L5 comparison

PHP `TraitUserExample`: LCOM4 = 1. Python single-mixin `A`: LCOM4 = 1. Python
multiple-mixin `A`: LCOM4 = 1. All three: one lone node, all external references excluded,
matching shape confirmed field-by-field, not only at the scalar.

## 8. Falsification results

- **H1** (trait ≡ mixin literally): not mechanically true (PHP traits are compile-time
  composition; Python mixins are runtime MRO dispatch) — but this distinction never
  reaches L3/L4/L5, so it is irrelevant to the question actually being asked.
- **H2** (different mechanism, same relevant KnowledgeOS semantics) — **CONFIRMED**,
  directly, by evidence. This is the operative result.
- **H3** (contract intentionally excludes both) — **CONFIRMED**: both are excluded by the
  same pre-existing, frozen mechanism; nothing trait-specific or mixin-specific exists or
  was added.
- **H4** (genuine missing L3 concept, → C) — **REJECTED**: zero production code needed
  changing; all three characterization tests passed against the unmodified implementation
  on the first run.
- **H5** (Python adapter limitation, → D) — **REJECTED**: same reason as H4.
- Multiple-inheritance variant (explicitly requested check) — confirmed to change nothing.

## 9. Classification: **A/B**

**B** in the strict sense (the exclusion is the same already-pinned, already-accepted
"analysed in isolation" abstraction, not a fresh coincidence) — **and A** in the stronger
sense the falsification actually demonstrates: two independently-built adapters, for two
mechanically different language composition features, converge on identical L3/L4/L5
output *without either adapter or the canonical layer needing to know the words "trait" or
"mixin" exist*. Unlike R1, this is not a correction — it is a confirmation that the
existing frozen architecture already generalizes here, for free.

## 10. Whether the existing contract survives

Yes, unchanged and unextended. `trait_methods` is not, on this evidence, a semantically
distinct decision from `inherited_methods` — it was always the same invariant, restated for
PHP's second composition keyword. Nothing here requires editing `expected.json`.

## 11. Whether L3 requires any new concept

No. Confirmed by evidence, not by assumption: no `TraitRole`, `MixinRole`,
`InheritanceRole`, or `CompositionRole` is justified. Introducing one would be architecture
for its own sake, contrary to the standing discipline in this branch.

## 12. Whether an adapter change is required

No. Both adapters already produce the correct shape with zero modification.

## 13. Architecture consequence

Strengthens, rather than adds to, the precedent from the inheritance branch: "unit analysed
in isolation" is not PHP-inheritance-specific — it is a general **lexical-membership
invariant** that transparently absorbs *any* language's composition mechanism (inheritance,
traits, mixins, multiple inheritance) without per-mechanism vocabulary, because the
invariant is defined structurally ("declared in this unit's own body"), not by naming every
mechanism that could violate it. This is a stronger, more general statement of the
architecture's neutrality than R1 produced, obtained at zero implementation cost.

## 14. One highest-information next question

Per your reframed criterion (information gain about the theory, not "no direct analogue"):
**`own_class_name_resolution`** — "`OwnClass::m()` is recognized only when the name matches
the class's own declared name AS WRITTEN; namespaced or aliased spellings are not
resolved." This is the one remaining pinned key that is about a *language-neutral
spelling-resolution* question rather than a structural-membership question (unlike
`trait_methods`/`inherited_methods`, now understood as one invariant) — it asks whether
"the class's own name, as the analysis can see it" is a robust cross-language concept, which
is a different kind of claim than anything R1 or R2 tested and could expose a genuine L3
gap (e.g., Python has no equivalent of PHP's `self`/static-name aliasing surface, or it has
a different one) rather than confirm an existing invariant again.

**No production change justified. Research branch closed.**

**Traceability:** `expected.json` (`trait_methods`, `inherited_methods` entries),
`trait-user.php` golden fixture, `PhpFactExtractor.php`, `PythonSemanticFactProvider.php`,
`GraphBuilder.php` (all read directly, unmodified) ·
`TraitMixinSemanticExperimentTest.php` (new, 3/3 green) ·
`InheritanceCrossLanguageExperimentTest.php`, `PythonEvidencePrecisionCorrectionTest.php`
(prior frozen evidence this result depends on) ·
`2026-09-27-KOS-PYTHON-RULE-VALIDATION-R1-implementation.md` (prior slice, same branch).
