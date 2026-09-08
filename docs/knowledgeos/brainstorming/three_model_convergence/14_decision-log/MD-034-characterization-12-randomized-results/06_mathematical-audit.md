# Mathematical Audit

## Question

Does `12-randomized-results.md` give enough information to alter any part of MD-033's minimal
source-grounded `Validate` object?

```
Validate : D ⇀ C
D = { {Claim, Evidence}, {Hypothesis, Evidence} }     (MD-033, unchanged for V0/baseline)
C = { Verdict }
```

## Domain/codomain — unchanged for the baseline

**No.** `12` never restates or modifies `06`'s own baseline derivation rule; every failure-rate/
ablation result in this file is computed against the baseline (or a leave-one-out variant of it),
never against `V6`. The `V0`/baseline `D`/`C` established in MD-033 stands exactly as before.

## A second, explicitly *alternative* formal object, for V6 — new, but not a replacement

`12` supplies enough to state a second, distinct partial function:

```
Validate_V6 : D_V6 ⇀ C
D_V6 = { {Claim, Evidence, Defeater}, {Hypothesis, Evidence, Defeater} }
        (per MD-030's own reconstruction of variants.py's V6; independently corroborated here by
        12's own line 199: "V6 (verdict requires surviving a defeater): no [Verdict reachable
        without Challenge]")
C = { Verdict }
```

This is **not** offered by the source as a replacement for the baseline object — it is offered as an
*alternative model*, explicitly framed as such by the document's own methodology ("Seven alternative
reasonable models," line 132; "A conclusion that survives every variant is robust; one that flips is
an artifact of a modelling choice," matching `variants.py`'s own docstring almost verbatim, per
MD-030 `03`). Two distinct, both source-grounded (once `12` is admitted), partial functions now
coexist in the narrative lane — this is a genuine multiplicity, not a contradiction requiring
resolution before either can be stated correctly.

## Does the file establish equivalence or non-equivalence between the variants?

**Non-equivalence, for one specific property.** The causal-criticism table (line 196–201)
demonstrates the two rules are **not extensionally equivalent** — they differ on whether a `Verdict`
is reachable in a world where `Challenge` (and therefore any `Defeater`) is entirely absent: `V0`
says yes, `V6` says no. This is a genuine, source-stated semantic difference, not the "same
semantics, different notation" case ruled out in MD-031's own six-way vocabulary for the
narrative-vs-executable comparison (a different question than this one, since here both sides are
narrative).

## Partiality / totality

Both objects remain partial, honestly represented (no invented "else" clause for either) — consistent
with MD-033's own discipline, extended here to the second object.

## Does this change MD-033's own "happy-path contract is fully closed" finding for the baseline?

**No.** MD-033's finding concerned the baseline object specifically, and remains correct. What
changes is the *broader picture*: there is now a second, narratively-documented candidate object,
with a stated (if informal) argument for why it might be preferable — a fact MD-033 could not have
known, since it was correctly scoped to a population that did not include this file.
