# OWD-3 — GREEN implementation, TDD, `Domain`-level, frozen

**Context:** OWD-3 follow-up · **Date:** 2026-09-27
**Sequence:** OWD-1 (corpus discovery) → OWD-2 (characterization, two compounding bugs
located) → OWD-3 characterization (Model A resolved from existing text) → **this:
RED→GREEN.**

> First `Domain`-level correction motivated entirely by Python evidence — unlike R1
> (adapter-populated L3 field) or R5 (adapter extraction gap), this fixes the shared
> `GraphBuilder` connectivity logic itself. PHP is unaffected by construction, not by
> testing alone: it structurally cannot produce the colliding input.

---

## RED

`PropertyIdentityCorrectionTest.php`, 6 tests, written from the OWD-3 predictions table
before any change: 5 failed exactly matching OWD-2's documented pre-fix numbers
(fixture 3: LCOM4 1 not 2; fixture 6: 2 not 3; fixture 2: no edge; fixture 7: 1 edge not
2; ambiguous-reference test: 1 edge not 2 — the last one initially reported for the
wrong reason, my own test's mistake, corrected before GREEN). The regression guard
(ordinary, non-colliding property) already passed.

## GREEN — entirely in `GraphBuilder.php`

- Node labels are computed once, up front: a name occurring exactly once keeps its bare
  identity (every PHP input; the overwhelming majority of Python input); a name
  occurring more than once is disambiguated by occurrence (`name#0`, `name#1`, ...).
- Property-ownership tracking and the union-find-feeding node list are both keyed by
  this label, not the bare name — the exact fix OWD-2 located.
- Behaviour-reference target matching now resolves against every label matching the
  target name (bare or disambiguated) — unambiguous in the common case (exactly one
  match, identical behaviour to before); when genuinely ambiguous (a colliding target
  name), connects to every candidate rather than silently guessing which occurrence a
  read vs. write should target — the question OWD-3 §6 explicitly left open, still
  left open, now handled honestly rather than accidentally.
- **`Lcom4.php`: zero changes.** More precisely stated than "always correct": the
  union-find algorithm was correct **under its previously implicit unique-node-identity
  invariant**; OWD-2 demonstrated that invariant was not language-neutral — the
  `Domain` contract never enforced or represented uniqueness, and Python exposed the
  gap. The algorithm itself was never the defect; the identity it was fed was.
  Confirmed by `git diff` (0 lines).

## Falsification held

Ordinary, non-colliding properties/methods report byte-identical labels to before this
change (regression test, plus the entire existing 139-test baseline, unmodified). The
ambiguous-target case connects to every matching occurrence rather than picking one
arbitrarily.

## Predictions vs. actual, all four decisive fixtures

```
Fixture 3 (disjoint state):        predicted LCOM4=2, edges=[]           → confirmed
Fixture 6 (empty pair + unrelated): predicted LCOM4=3, edges=[]           → confirmed
Fixture 2 (shared field, 2 methods): predicted LCOM4=1, 1 real edge       → confirmed
Fixture 7 (shared field, 3 methods): predicted LCOM4=1, 2 real edges      → confirmed
```

Every OWD-3 prediction, made before touching any code, matched exactly.

## Full test-suite result

```
Before this slice: 139 tests green
After:             145 tests green (139 + 6 new correction tests)
```
Three pre-existing test files updated (disclosed, not silent, same convention as every
prior slice): `OpenWorldCorpusDiscoveryCharacterizationTest.php` (one assertion),
`PropertyIdentityCharacterizationTest.php` (docblock marked historical, three
assertions corrected in place, kept as a compact historical record rather than
duplicated/deleted).

## Classification confirmed

**D, corrected.** `git diff --stat`: `GraphBuilder.php` only (+65/-19); `Lcom4.php`
untouched (0 lines) — direct confirmation that the characterization's diagnosis
(connectivity bookkeeping, not the union-find algorithm itself) was exactly right.

## Architecture consequence

The first fix in this entire investigation that is not "adapter A vs. adapter B" at
all — it corrects an invariant in the one shared `Domain` layer both languages feed,
motivated entirely by a construct that only Python's language semantics can produce.
Confirms, concretely, the distinction the last several turns established: PHP is
comparative/historical evidence here, not the design authority — the fix was derived
from Python evidence, verified against the existing contract's own documented words
("node = declared method"), and is safe for PHP by logical necessity (PHP cannot
violate the invariant being generalized away from), not by re-running the PHP suite
alone (though that also passed, unchanged, as confirmation).

## Remaining open question (explicitly not addressed, same boundary as OWD-3 §6)

Read-vs-write target disambiguation for a colliding property name (should `self.value`
resolve to the getter specifically, not the setter, when both exist) remains
unresolved. The current behavior (connect to every candidate) is honest about the
ambiguity, not a resolution of it.

**OWD-3 is now frozen.** Stopping here — no further experiment started automatically.

**Traceability:** `GraphBuilder.php` (modified, `Lcom4.php` confirmed untouched) ·
`PropertyIdentityCorrectionTest.php` (new, 6/6 green) ·
`PropertyIdentityCharacterizationTest.php`,
`OpenWorldCorpusDiscoveryCharacterizationTest.php` (updated, history preserved) ·
`...-OWD2-property-identity.md`, `...-OWD3-identity-model-resolution.md`
(characterization, same investigation).
