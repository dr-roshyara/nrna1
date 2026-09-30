# OWD-8 — combined OWD-4/OWD-7 correction: GREEN, Python-only, frozen

**Context:** the Canonical L3 Sufficiency Audit identified OWD-4 and OWD-7 as sharing
one axis (read/write property target resolution) · **Date:** 2026-09-28
**Sequence:** OWD-4 (characterized, frozen) + OWD-7 (characterized, frozen) →
**this: RED→GREEN, combined, Python-only, zero `Domain`/PHP change.**

---

## RED

`PropertyTargetPrecisionCorrectionTest.php`, 8 tests, written before any change: 5
failed exactly as predicted (call-form resolution; decorator read-only-getter;
decorator write-only-setter; write-to-getter-only-property emits nothing; call-form
getter-only write emits nothing). The other 3 (getter-only read regression; lambda
exclusion; ordinary-attribute regression) already passed, correctly.

## GREEN — entirely in `extract_facts.py`

New `_build_property_targets()` unifies both idioms into one `property_name → {"get":
target, "set": target}` map:

- **Decorator idiom**: counts occurrences of a name that are `@property`- or
  `@<name>.setter`-decorated. **Key insight, not an assumption**: Python's own syntax
  *guarantees* the getter is declared first — writing `@x.setter` requires `x` to
  already be a property, so the `@property`-decorated def with that name must
  textually precede it. One occurrence → bare name (unchanged, no collision). Two
  occurrences → `name#0` (getter) / `name#1` (setter) — **exactly** the label scheme
  `GraphBuilder::build()` already computes independently, from the same declaration
  order, on the PHP side (OWD-3). This is why **zero `Domain` changes were needed**:
  the adapter now emits labels that were always going to match what `GraphBuilder`
  produces, once both are keyed by the same occurrence order.
- **Call-form idiom** (OWD-7): scans class-body `Assign` nodes for `NAME =
  property(...)`; accepts only bare-`Name` positional/keyword `fget`/`fset` referring
  to already-declared methods — lambdas, module-level monkey-patching, and the empty
  `property()` call are recognized and deliberately excluded, exactly as scoped in the
  OWD-7 report.
- At each property access, `node.ctx` (`ast.Load` vs. `ast.Store`) selects "get" or
  "set" from the map. If the needed side doesn't exist (e.g., writing a read-only
  property — not valid Python at runtime), no fact is emitted.

## Falsification held

Getter-only properties are completely unaffected (bare label, unchanged). Lambda-valued
`fget` remains unrecognized. Ordinary, non-property attributes are untouched. Outgoing
calls from a getter/setter's own body were already unambiguous before this slice and
remain so.

## Materiality — both original findings now resolved with precision, not a fallback

```
OWD-7 (calendar.Calendar-modeled): LCOM4 2 → 1, iterweekdays now correctly connects to getfirstweekday
OWD-4 (decorator collision):       reader connects ONLY to the getter; writer ONLY to the setter
                                    (previously: connected to both, per OWD-3's honest-under-uncertainty fallback)
```
This is a stronger result than either report's own recommendation anticipated — OWD-3's
"connect to every candidate" fallback is no longer needed for the decorator collision
case; it is fully superseded by precise resolution.

## Full test-suite result

```
Before this slice: 156 tests green
After:             164 tests green (156 + 8 new correction tests)
```
Four pre-existing test files updated (disclosed, not silent, same convention as every
prior slice): `PropertyCallFormCharacterizationTest.php`,
`PropertyIdentityCorrectionTest.php` (one falsification test),
`PropertyTargetResolutionCharacterizationTest.php` (both original characterization
tests) — all previously asserted the old imprecise/ambiguous behavior, now assert the
corrected, precise resolution.

## Classification confirmed

**D, corrected — Python-only.** `git status` scoped to `Domain`/PHP: identical to the
state before this slice. Confirms the audit's own prediction: this correction did not
require touching the shared canonical layer at all, because the existing occurrence-
based node-identity scheme (OWD-3) already generalizes to exactly what read/write
resolution needed.

## Architecture consequence

This closes both of the two open items the Canonical L3 Sufficiency Audit identified as
linked. It also demonstrates the audit's central empirical claim directly: the L3/L4
vocabulary needed *zero* further extension to absorb this — the fix was entirely a
matter of the adapter computing the same occurrence-based identity `GraphBuilder`
already used, and reading one more piece of already-available AST information
(`ctx`) that had simply never been consulted before.

**OWD-8 is now frozen.** Stopping here — no further experiment started automatically.

**Traceability:** `extract_facts.py` (modified; `GraphBuilder.php`/`Lcom4.php`/PHP
adapter confirmed untouched) · `PropertyTargetPrecisionCorrectionTest.php` (new, 8/8
green) · `PropertyCallFormCharacterizationTest.php`, `PropertyIdentityCorrectionTest.php`,
`PropertyTargetResolutionCharacterizationTest.php` (updated, history preserved) ·
`2026-09-28-KOS-CANONICAL-L3-SUFFICIENCY-AUDIT.md` (identified this as the combined
next step) · `2026-09-27-KOS-PYTHON-OWD4-property-target-resolution.md`,
`2026-09-27-KOS-PYTHON-OWD7-property-call-form.md` (the two characterizations this
closes).
