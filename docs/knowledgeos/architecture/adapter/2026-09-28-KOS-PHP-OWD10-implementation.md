# OWD-10 — GREEN implementation, TDD, PHP-only, frozen

**Context:** OWD-10 follow-up · **Date:** 2026-09-28
**Sequence:** characterization + classification E (`...-OWD10-arrow-function-attribution.md`)
→ **this: RED→GREEN, PHP-only, zero `Domain`/Python change.**

---

## RED

`ArrowFunctionAttributionCorrectionTest.php`, 7 tests, written before any change: 5
failed exactly as predicted (the real `Election.php`-modeled fixture; arrow function as
a call argument; explicit return-type declaration; nested arrow functions; arrow
function as the final expression in a `return`). The other 2 (ordinary-method
regression; OWD-5's own `function(){}` mechanism unaffected) already passed, correctly.

## GREEN — entirely in `PhpFactExtractor.php`

Two new methods, mirroring OWD-5's `T_FUNCTION` handling in *intent* but necessarily
different in *mechanism*, since an arrow function's body has no matching braces:

- **`findArrowFunctionBounds()`**: from the `T_FN` token, skips the balanced
  `(params)` list, then any `: ReturnType` tokens (a return-type declaration cannot
  itself contain `=>`), and delegates to the body-end finder once the arrow's own `=>`
  is found.
- **`findArrowFunctionBodyEnd()`**: tracks bracket/paren depth from just after `=>`;
  stops at the first comma/semicolon at depth 0 (an outer call's argument separator,
  or a statement terminator), or at the first closing `)`/`]`/`}` that would take
  depth negative (a bracket opened *outside* the arrow function, e.g. an enclosing
  call's closing paren) — exactly where PHP's own parser considers the expression to
  end.

`factsIn()` gains one new `if ($kind === T_FN) { ... skip ... }` block, structurally
parallel to the existing `T_FUNCTION` block.

## Falsification held, all cases handled correctly on the first GREEN run

Arrow function as a call argument (ends at the comma), with an explicit return-type
declaration, nested (arrow returning arrow, fully skipped by the outer skip), as the
final expression in a `return` statement (ends at end-of-tokens/statement correctly),
and OWD-5's own `function(){}` mechanism confirmed unaffected.

## Materiality — real, at both the fixture and the full-production-corpus scale

```
Fixture (modeled on real Election.php:389): edges 1→0, LCOM4 2→3.
```
**Full real corpus re-run**: all 1,629 `.php` files in this project's actual
application (`app/`) — **0 crashes**, confirming robustness at full production scale,
not merely on the fixture. This is the first correction in the whole investigation
validated against this project's own complete, real codebase in the same slice it was
implemented, rather than a separate follow-up validation pass.

## Full test-suite result

```
Before this slice: 165 tests green
After:             172 tests green (165 + 7 new correction tests)
```
One pre-existing test file updated (disclosed, not silent, same convention as every
prior slice): `ArrowFunctionAttributionCharacterizationTest.php`, which asserted the
pre-fix misattribution — now asserts the corrected behaviour.

## Classification confirmed

**E, corrected — PHP-only.** No `Domain` change, no Python adapter change: `git status`
scoped to those areas is identical to before this slice.

## Architecture consequence

Closes the one item OWD-5 explicitly named out of scope when it fixed the identical
defect for `function(){}`. Also the first finding and correction in this whole
investigation grounded entirely in this project's own real production code (298
occurrences, 57 with `$this->`) rather than CPython's standard library or hand-built
fixtures — and the first correction validated against a FULL real corpus (1,629 files)
in the same slice as its implementation, not a deferred follow-up.

**OWD-10 is now frozen.** Stopping here — no further experiment started automatically.

**Traceability:** `PhpFactExtractor.php` (modified; `Domain` and Python adapter
confirmed untouched) · `ArrowFunctionAttributionCorrectionTest.php` (new, 7/7 green) ·
`ArrowFunctionAttributionCharacterizationTest.php` (updated, history preserved) ·
`app/` (real corpus, 1,629 files, 0 crashes post-fix) ·
`2026-09-28-KOS-PHP-OWD10-arrow-function-attribution.md` (characterization, same
investigation) · `2026-09-27-KOS-PYTHON-OWD5-implementation.md` (the identical defect,
already fixed for `function(){}`, this closes the counterpart of).
