# R5 — receiver-resolution generalization to `StateAccess`: symmetric gap found, STOP before correction

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 5 · **Date:** 2026-09-27
**Status:** characterization only. **No production code changed.** Classification and a
correction sketch given; **not authorized, not implemented** — and for the first time in
this branch, a correction here would touch **both** adapters, not Python alone.

---

## Hypothesis

R3's fix (recognizing a call receiver that names the analysed class itself or an alias
for it) only touched `ast.Call`; the same recognition was never added to the plain-
attribute (state-access) branch. Tested whether this is (H1) a general receiver-
resolution limitation spanning both fact categories, or (H2) specific to the call form
R3 fixed.

## Experiment

Four fixtures, both languages, run against the real, unmodified (post-R3) implementation:
`self.value`/`$this->value` (control), `A.value`/`self::$value`/`static::$value`/
`A::$value` (the tested construct), `Other.value`/`Other::$value` (negative control, a
genuinely different class), and a two-method materiality fixture sharing only the
class-level property.

**Decisive, unanticipated result, established by direct execution before forming any
conclusion**: this is **not** simply H1-as-originally-framed. `PhpFactExtractor` —
unmodified, the very reference implementation R3 measured Python against — produces
**zero** `StateAccess` facts for `self::$value`, `static::$value`, or `A::$value` alike.
This is confirmed by reading `factsIn()` directly: its only state-access branch requires
literal `$this->`; its only qualifier-based branch (`<qualifier>::name(`) requires a
following `(` — i.e., it exists **exclusively for calls**, never for a bare property.
**PHP itself never modeled this construct.** R3's premise ("PHP already has a correct
mechanism Python lacks") does not hold here.

## L3

Neither language produces any fact for the tested construct today. Both correctly
produce nothing for the negative control (a genuinely different class stays
unrecognised, in both languages — confirmed).

## L4 / L5

Directly measured, both languages, same shape: a two-method fixture sharing a
class-level property only through the tested construct reports `edges = []` in both, and
**LCOM4 = 2 in both** — a symmetric potential undercount if class-level property sharing
is cohesion-relevant (a separate, prior question — see Theory).

## Runtime vs. static distinction

Python's runtime resolves `A.value` (and `self.value`, when no instance attribute
shadows it) by falling back to the class's `__dict__` — a real, well-defined semantic
relationship exists. PHP's runtime resolves `self::$value`/`static::$value` similarly
(static property storage, with `static::` adding late static binding). Both runtimes
**do** establish a real relationship here; what's being characterized is that **neither
static extractor represents it**, not that the relationship is semantically absent. This
sharpens the R4 correction the same way: absence of representation ≠ absence of meaning.

## Classification: **E — analysis/pipeline limitation**

Not **D**: R3's defining feature was "the canonical vocabulary and the OTHER language's
adapter already handle this correctly; only Python doesn't." That premise fails here —
PHP doesn't handle it either. Not **C**: nothing suggests the existing `StateAccess`
vocabulary (`propertyName`, `accessMode`) is insufficient to REPRESENT a class-level
property read once one is recognized — the gap is in extraction, not in the canonical
type. Not **B/F**: no pinned decision, in either the eight `expected.json` entries or any
prior report in this branch, addresses class-level/static property access at all — this
was never a deliberate scope decision, just an unexercised construct in both
historically-PHP-instance-property-centric extractors.

## Addendum — write-access follow-up (authorized separately, 2026-09-27)

The named next-experiment was run before any fix decision, as instructed. Result: **writes
fail identically to reads, for the same underlying reason.** `self::$v = 1` /
`A.v = 1` produce zero facts in both languages — confirmed directly. This is not a new,
second gap: neither `StateAccess` nor either extractor's existing `$this->`/`self.` branch
has ever distinguished read from write context (an instance-property *write* already
produces the identical fact shape as a read, today, in both languages — verified as a
control). The failure is purely about qualifier recognition, not about read/write
semantics, so writes don't reveal anything read-access hadn't already shown.

**Strongest materiality case, now measured**: a "one method writes, another reads" pattern
— the canonical shared-mutable-state pattern `LCOM4` exists to detect, and exactly the
shape the accepted `call-chain.php`/`self-call.php` golden fixtures already treat as
connected for *instance* properties — is **still invisible, symmetrically, in both
languages**: `edges = []`, `LCOM4 = 2` in both, where an instance-property equivalent
would correctly report `LCOM4 = 1`. This is the clearest materiality evidence yet for this
gap, and it does not change the classification (still **E**) — it strengthens the case
that the gap is real and non-trivial, without yet answering whether class-level property
sharing *should* count as cohesion-relevant (still a separate, unresolved question).

**No new production change justified by this addendum alone** — it sharpens the existing
E finding with stronger evidence; it does not introduce a second finding requiring its own
authorization. The fix-or-hold decision for R5 as a whole is unchanged: still pending.

## Production changes

**None.** `git status`, scoped: identical to the state after R4.

## Corpus frequency

Real PHP corpus (`app/`, this repo — no Python production corpus exists, stated as a
limitation, not fabricated): `grep -c '(self|static)::\$\w+'` → **38 occurrences**, a raw
syntactic count (reads, writes, and any false positives not individually audited), not a
verified-relevant count — offered as an order-of-magnitude signal, not a precise figure.
Non-trivial: an order of magnitude more common than the `magic_methods` R4 evidence (39
`__toString`, 1 each of three others), though this count has not been filtered to
cohesion-relevant reads specifically.

## Theoretical consequence

R3 was too narrow a precedent to generalize from directly: it was genuinely
"Python-behind-PHP." R5 is genuinely "both-adapters-behind-a-construct-neither-was-ever-
built-for." The broader, now twice-confirmed principle: **an adapter's blind spots are
not automatically language-specific just because they were found by comparing languages
— some gaps are shared, discovered symmetrically, and belong to neither adapter alone.**
This also means: **any correction here is not a Python-only change** — for the first time
in this branch, closing this gap would mean editing `PhpFactExtractor.php` as well as
`extract_facts.py`, which is a different, larger-scoped decision than any prior R-slice
required.

## Remaining uncertainty

Whether class-level/static property sharing between two methods *should* count as
cohesion-relevant is not established by this experiment and was deliberately not assumed
— it is a prior, separate analytical question (arguably closer to a `static_methods`-style
"known, pinned, revisitable" territory) that would need its own authorization before any
`StateAccess` extraction is added for this construct.

## Next experiment (exactly one, not started)

**Is the same symmetric gap present for static-property *writes* (`self::$v = 1`), or
only reads?** A write is arguably more clearly cohesion-relevant (it is exactly the
shared-mutable-state relationship `LCOM4` was built to detect) and would sharpen whether
this is worth authorizing as a correction at all before deciding the "should this count"
question above.

**No production change justified without further authorization — and note explicitly:
any future correction here would be the branch's first cross-adapter (PHP + Python)
change, not a Python-only one. Research branch closed for now.**

**Traceability:** `PhpFactExtractor.php::factsIn()` (read directly, decisive evidence) ·
`extract_facts.py` (`_receiver_qualifier`, unchanged since R3) ·
`ReceiverResolutionStateAccessExperimentTest.php` (new, 5/5 green, 119/119 full Cohesion
suite) · `2026-09-27-KOS-PYTHON-RULE-VALIDATION-R3-implementation.md`,
`...-R4-magic-methods.md` (prior slices, same branch).
