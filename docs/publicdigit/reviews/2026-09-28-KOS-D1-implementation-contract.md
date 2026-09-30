# `D-1` implementation contract — minimal, scoped slice

**Date:** 2026-09-28 · **Scope:** `$this->$m()` only (an explicit narrowing, not a deferral
— see readiness check §2-B for why `self::`/`static::` are out of this slice).
**Status: PROPOSED PLAN, awaiting explicit human authorization. Nothing below is implemented.**

## 1 · Semantic rule
An observed method invocation whose receiver is `$this` (or, in Python, `self`) but whose
method-name is a computed expression, not a literal, must be recorded as **seen** and
excluded as **not determinable** — never silently dropped.

## 2 · `L3` type
New, distinct closed-vocabulary fact kind: `IndeterminateBehaviourReference`. Not a variant
of `BehaviourReference` (barred by `INV-L3-5`, independently re-derived).

## 3 · Required fields
None beyond the type's own identity. No `targetMethodName`. No stored `Determinability`
(derivable from the type). No provenance/`factId` (not required by this slice; independent of
the separate exclusion-evidence-record question).

## 4 · Python emission rule
In `extract_facts.py`, where a `self.<name>()` call's `<name>` is a computed expression
(e.g. an `ast.Name`/`ast.Attribute` value used as the attribute, not an `ast.Constant`
method-name literal) rather than a literal identifier — emit one
`IndeterminateBehaviourReference` instead of silently skipping.

## 5 · PHP emission rule
In `PhpFactExtractor.php`'s `$this->` branch (currently `factsIn()`, lines ~519-544), where
the member token is `T_VARIABLE` (fails today's `T_STRING` guard, currently skipped with no
fact) — emit one `IndeterminateBehaviourReference` instead of skipping.

## 6 · `L4` rule
`EdgeRules` gains one new, unconditional branch: any `IndeterminateBehaviourReference` →
`EdgeVerdict::exclude(ExclusionReason::NotDeterminable)`. No new `ExclusionReason` case (the
existing one already fits, confirmed by its own docblock).

## 7 · Cross-language invariant
The `L4` rule and the `L3` type are identical for both languages — no `qualifierKind`, no
source-language branch anywhere in the new logic. Only the two emission *sites* (§4, §5) are
language-specific, as with every other fact kind in this capability.

## 8 · RED tests
- Constructing `IndeterminateBehaviourReference` and passing it to `EdgeRules::verdict()`
  fails today (no accepting signature) — prove this first.
- `PhpFactExtractor`/`extract_facts.py` currently produce zero facts for `$this->$m()`/
  `self.<computed>()` — prove this first (characterization of the current gap).

## 9 · GREEN implementation scope
New `Domain/IndeterminateBehaviourReference.php` · one new `EdgeRules` branch · the two
emission-site changes (§4, §5) · `PythonSemanticFactProvider.php` deserialization support if
the new type crosses the PHP↔Python boundary (mirrors the existing `enumCase()` pattern).

## 10 · Regression tests
Full existing suite (182+ tests) must remain green, unmodified in assertions — this slice
adds facts where none existed before; it must not change any existing construct's output.

## 11 · Real-corpus validation
PHP: `DebugVoterSlug.php:67` (`$this->$color(...)`) — the one known, already-measured real
site — must now produce exactly one `IndeterminateBehaviourReference`, excluded, and the
unit's `LCOM4` value must be **unchanged** (metric-neutrality is a falsifiable prediction,
not an assumption). Python: no real corpus has been checked yet for this construct at scale
(flagged, not blocking — the P2 item from the backlog report).

## 12 · Falsification condition
If any real or fixture case produces a *different* `LCOM4` value, a *different* node set, or
a *different* edge set than before this slice — for **any** unit, not only ones containing
the new construct — the metric-neutrality claim this entire thread rests on is false, and
this slice must be reverted, not patched forward.

---

**Next actor:** the human — authorize this plan (as written, or amended), or decline. No
implementation proceeds without that authorization, per this repository's standing Engineering
Process rule.
