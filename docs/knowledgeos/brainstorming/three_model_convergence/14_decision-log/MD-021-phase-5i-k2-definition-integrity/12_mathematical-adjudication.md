# Phase 5I — Mathematical Adjudication (equivalence-relation axioms, per the authorization's §13)

## Testing whether the corpus's own "=_semantic" is a genuine equivalence relation

The corpus's own `t285_equality.py` treats "=_semantic" as if it were an equivalence relation between
K-1 and K-2. Testing the three required axioms, **as this phase's own independent check** (not
performed by the corpus's own code):

- **Reflexivity** ($X =_{semantic} X$): **Not tested by the corpus's own code** — `eq_semantic` in
  `e_equality.py` is only ever invoked on two *different* states (`K1`,`K2`); no self-comparison is
  shown. By construction (a projection `proj(k)`, then Python equality), reflexivity would trivially
  hold if tested — this phase does **not** manufacture a stronger claim than "would trivially hold if
  tested," per the authorization's own instruction not to infer results the corpus itself never ran.
- **Symmetry** ($X=_{semantic}Y \Rightarrow Y=_{semantic}X$): **Not tested**, but the underlying
  operation (Python `==` on projected sets) is symmetric by construction — same caveat as above.
- **Transitivity**: **Not tested anywhere, and NOT trivial** — `=_semantic` involves a lossy projection
  (discarding `id,e,Pi`); a chain of three states could each pairwise satisfy `=_semantic` under
  different discarded values without all three being pairwise identical in every other respect. **This
  phase does not assume transitivity holds** — it is left `NOT EVIDENCED`.

**Verdict**: `=_semantic`, as actually implemented, is **very likely a genuine equivalence relation
under Python's own set-equality semantics** (reflexive/symmetric are structural properties of the
comparison mechanism used), **but this was never verified by the corpus's own research, and this
phase does not manufacture that verification** — it is reported as a reasonable inference (research
interpretation, level 4), not direct evidence (level 1).

## Totality/partiality, re-verified per the two-projection finding (`09`)

$\pi_1$ (D1/D3 target): **NOT total** — has no image for `Observation` at all (not merely
non-computable; genuinely absent from the target field set).
$\pi_2$ (D2/D4 target): **Partial, as already established** (Phase 5H) — total over 7 of 8 primitives
(all but `Observation`, which is conditional on `Qualify`), non-total overall.

## What information cannot be recovered — stated precisely, per the authorization's own requirement

For $\pi_2$: given a $K_2$ instance, one cannot recover `Event`, `Policy` (unambiguously — see the
`Π` discrepancy), or `Action` — these are not encoded anywhere in the target. `State` cannot be
recovered because its own target field is undefined, so it is not even meaningful to ask whether it
"survives" the projection.

## Verdict on "mathematical closure from successful script execution" (per the authorization's own explicit warning)

**Not inferred.** All three scripts execute successfully (confirmed: no runtime errors would occur
given their own hardcoded/well-typed inputs) — but successful execution establishes only that the
*code as written* runs without crashing, **not** that its hardcoded assumptions (Test 3 in
`t285_equality.py`; the `pi` dict in `t285_reconcile.py`; `ASSERTION_CONTAINS`/`UNPACK`'s own
correctness) are mathematically justified. This distinction is the throughline of `03`/`04`/`05`.
