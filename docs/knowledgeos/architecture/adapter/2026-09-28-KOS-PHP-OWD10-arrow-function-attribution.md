# OWD-10 — PHP arrow function (`fn() => expr`) attribution: real, material, more common than any prior finding

**Context:** the PHP gap OWD-5 explicitly named out of scope · **Date:** 2026-09-28
**Phase:** characterization only. **No production code changed.**

---

## Recovered meaning

PHP arrow functions (`fn(params) => expr`, 7.4+) auto-capture the enclosing scope by
value, including `$this`, with no explicit `use()` clause — otherwise the same closure
semantics OWD-5 already characterized and corrected for `function(){}`. OWD-5's fix
(skip a nested closure's entire token range) explicitly special-cases `T_FUNCTION`
only; `T_FN` (the arrow-function token) was named, not silently missed, as out of that
slice's scope.

## Real corpus evidence — this project's own code, not third-party

`grep` of `app/` (the actual PHP application this repo develops, not a stdlib or
third-party corpus): **298 arrow-function occurrences**, **57 referencing `$this->`
on the same line**. This is a materially larger real-corpus footprint than any prior
finding in this whole investigation — R1–R6 and OWD-1–9 all drew on CPython's
standard library or hand-built fixtures; this is the first finding grounded in the
project's own production PHP.

Representative real example, `app/Models/Election.php:389`:
```php
$base = fn () => $this->memberships()->withoutGlobalScopes();
```
inside a method that constructs and returns `$base`, never invoking it directly itself
— exactly OWD-5's "define and return, never call it here" shape.

## Current behavior, confirmed by direct execution on a fixture modeled on this exact line

`PhpFactExtractor::factsIn()` has no `T_FN` handling at all. The enclosing method
(`scopeBase` in the fixture) is wrongly reported as directly calling `memberships()`,
when in its own direct execution it never does — only the arrow function it constructs
would, if and when invoked later by whoever holds it.

## Materiality — real, not theoretical

```
Current:  edges=[[scopeBase,memberships,behaviour]]   LCOM4=2
Correct:  scopeBase should have no facts of its own     LCOM4=3
```
Same class of materiality as OWD-5 (R1/R3/R5/OWD-3/OWD-5/OWD-7 for comparison), on a
fixture modeled directly on this project's own real, current code.

## Classification: **E**

Shared analysis/pipeline limitation in the sense OWD-5 already established the general
principle for — this is not a new kind of defect, it is the identical defect OWD-5
fixed for `function(){}`, recurring for the one PHP closure syntax that fix didn't
cover. Not C: the existing `StateAccess`/`BehaviourReference` vocabulary is already
sufficient (OWD-5 proved this). Not D: there is no "PHP already handles it, only
Python doesn't" asymmetry here — this is purely a PHP-side gap.

## What a correction would look like (sketched, not implemented)

Directly parallel to OWD-5's `T_FUNCTION` handling in `factsIn()`: on encountering a
`T_FN` token, skip past the arrow function's body. The complication OWD-5's
brace-matching approach doesn't directly handle: an arrow function's body is a single
**expression**, not a brace-delimited block — there is no `{`/`}` to find and match.
The body's end must instead be determined by PHP's own expression-boundary rules
(terminated by the enclosing context: a comma at the same nesting depth, a closing
`)`/`]`/`;`, whichever comes first) — a different, if related, algorithm from
`findBodyBrace()`/`matchBrace()`, not a direct reuse of them. This is more delicate
than OWD-5's fix and deserves its own careful RED→GREEN treatment, not an assumption
that it is a trivial copy-paste of the existing mechanism.

## Recommendation

Materially significant — more so than any prior finding, given the real occurrence
count (298, 57 with `$this->`) is in this project's own production code, not
third-party or stdlib evidence. Recommend authorizing correction under the same
RED→GREEN discipline as every prior slice — but **not implemented here**, per the
standing rule for every new finding in this investigation, and given the arrow-function
body-boundary algorithm needs its own careful design, not a copy of OWD-5's
brace-matching approach.

**No production change made. Stopping here — no implementation without explicit
authorization.**

**Traceability:** `app/` (real corpus, grepped directly — 298 occurrences, 57 with
`$this->`) · `app/Models/Election.php:389` (representative real example) ·
`PhpFactExtractor.php` (confirmed no `T_FN` handling) ·
`ArrowFunctionAttributionCharacterizationTest.php` (new, 1/1 green, full Cohesion suite
165/165) · `2026-09-27-KOS-PYTHON-OWD5-nested-closure-attribution.md`,
`2026-09-27-KOS-PYTHON-OWD5-implementation.md` (the identical defect, already fixed for
`function(){}`, that this finding is the `T_FN` counterpart of).
