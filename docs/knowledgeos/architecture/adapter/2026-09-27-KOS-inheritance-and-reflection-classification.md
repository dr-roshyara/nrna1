# Inheritance-without-override & reflection — classification against the recovered analytical contract

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Read-only research. No production code, no `expected.json`, no adapter change made in
> this report. One prior conclusion of mine is corrected below, in the direction the
> evidence actually points, not the direction I originally guessed.

---

## 1 · Formal rule recovered

"The class is analyzed in isolation" is stated, verbatim, as the **shared, repeated,
foundational principle** across three separate pinned decisions — `trait_methods`,
`inherited_methods`, `own_class_name_resolution` — not a single implementation's local
choice. **"Target is declared in the unit's own body" means exactly**: present in the
`DeclaredUnit`'s own `methods` list, full stop — not "reachable via any inheritance chain,"
not "resolvable via the language's own runtime lookup." An inherited method is real,
callable, and exists in the running program; it is **deliberately** not a node in the
*analysed unit's* graph, by explicit, repeated architectural choice, independent of language.

## 2 · Characterization, both adapters (unchanged from the earlier experiment, re-examined)

Both PHP and Python correctly conclude "no edge" for `Child.f()`'s call to an inherited
(non-overridden) `helper()`. They reach it by **different internal L3 states**:
- PHP: `targetUnitRelation=DenotesAnalysedUnit`, `determinability=Determinable` — the
  binding never checks membership; `GraphBuilder`'s node-matching fallback catches the
  mismatch, reason `TargetNotDeclaredHere`.
- Python: `targetUnitRelation=Undetermined`, `determinability=NotDeterminable` — my adapter
  *does* check membership at construction time; `EdgeRules`' early `NotDeterminable` branch
  catches it first, reason `NotDeterminable`.

## 3 · Does the difference matter to the analytical contract — **corrected conclusion**

**Yes, at the audit-trail (Level-2 evidence) layer — and my earlier framing had the
direction backwards.** `AnalyseCohesion`'s own docblock states Decision 13.7 requires
**three** evidence levels, not just the metric: unit set, **node/edge set plus every
excluded reference with its reason**, and the final value. Exclusion *reason* is part of the
declared conformance specification, not an internal implementation detail — so getting it
imprecise is not merely cosmetic.

`ExclusionReason`'s own docblock states the general principle (illustrated by, but not
limited to, `NotDeterminable` vs. `NotTheAnalysedUnit`): *"These are DISTINCT VALUES so that
the distinctions... cannot be merged... a reference that is seen and excluded must remain
distinguishable from one never seen."*

**Re-examining my own prior conclusion against this principle: `NotDeterminable` and
"target not declared in this unit" are different claims, and my Python adapter's
construction-time membership check merges them.** `helper`'s *name* is completely
unambiguous here — there is no runtime-computed or dynamic element at all, unlike genuine
`D-1`. The target's *identity* is perfectly determinable; it simply belongs to a different,
out-of-frame unit. `TargetNotDeclaredHere` — which already exists, for exactly this — is the
more precise reason. **PHP's existing, accepted behavior is the more contract-faithful one
here; my Python adapter's extra membership check, while well-intentioned, is the imprecise
one.** This reverses my earlier tentative framing (which called this a PHP binding-precision
issue) — I had the direction backwards, and the fully-recovered contract text is what
corrects it, not a preference for parity.

## 4 · `L4`/`L5` consequences
None — both converge on "no edge," `LCOM4` unaffected either way. The correction is scoped
entirely to Level-2 evidence precision, not to graph or metric correctness.

## 5 · Real corpus frequency — substantially higher stakes than `D-1`

| Pattern | Occurrences in `app/` |
|---|---|
| `extends` (class inheritance) | **485** |
| `parent::` (explicit parent dispatch — already validated architecture) | **67** |
| Trait `use` statements | **441** |

Unlike `D-1` (1 occurrence), inheritance and traits are **pervasive** in this corpus. The
Level-2 evidence-precision question this report raises is not a rare-edge-case concern.

## 6 · Classification: **(D) Adapter limitation** — in the Python adapter, not PHP

Not (C) canonical insufficiency: `TargetNotDeclaredHere` already exists and is exactly apt.
Not (B) intentional abstraction: nothing pinned decides "collapse these two claims" —
`inherited_methods` decides the *graph* outcome (no edge), not the *exclusion-reason*
precision. The fix, if authorized, would be **removing** my adapter's membership pre-check
for this case (report `Determinable`/`DenotesAnalysedUnit` unconditionally for ordinary
self-referential calls, exactly as PHP already does, and let `GraphBuilder`'s existing
fallback assign the correct, already-existing reason) — smaller than adding anything.
**Not implemented here** — reported, per the standing "characterize, don't patch" discipline.

## 7 · Reflection — closed, decisively negative

Checked precisely, not assumed: **zero** occurrences of `->invoke(`, `->invokeArgs(`, or
`ReflectionMethod` anywhere in `app/`. All 22 real `ReflectionClass` call sites use
`getProperty()`/`setAccessible()` — **property introspection, never dynamic method
dispatch.** Reflection is **not** a `D-1`-adjacent category in this corpus at all — it doesn't
exercise `intra_class_calls` territory. No further characterization needed; nothing to
classify against `D-1`'s boundary because the relevant construct doesn't occur.

## What this report does not do
Does not modify `PythonSemanticFactProvider`, `PhpFactExtractor`, `EdgeRules`, or
`expected.json`. The Level-2-precision correction in §6 is reported as a finding, with the
smallest corrective change identified, not applied — a separate authorization would be
needed, exactly as with every prior production-adjacent finding in this investigation.

**Traceability:** `expected.json._variant_decisions_pinned` (`trait_methods`,
`inherited_methods`, `own_class_name_resolution`) · `ExclusionReason.php`,
`AnalyseCohesion.php` docblocks · `2026-09-27-KOS-D1-analytical-definition-decision.md` ·
`InheritanceCrossLanguageExperimentTest.php`/`InheritanceOverrideControlExperimentTest.php`
(prior experiments, same directory) · fresh corpus grep, this report.
