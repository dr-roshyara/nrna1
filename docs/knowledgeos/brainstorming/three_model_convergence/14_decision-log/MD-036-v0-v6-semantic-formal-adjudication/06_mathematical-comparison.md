# Mathematical Comparison

## Formal statement

```
V0 : D_0 ⇀ C          D_0 = { {Claim,Evidence}, {Hypothesis,Evidence} }        C = { Verdict }
V6 : D_6 ⇀ C          D_6 = { {Claim,Evidence,Defeater}, {Hypothesis,Evidence,Defeater} }
```

## Domain

**Different**, by construction — `D_6` is `D_0` with `Defeater` added to every member. Not equal.

## Codomain

**Same** — both `{Verdict}`, source-confirmed for both (`03`, `12`).

## Does V6 restrict the domain?

**This is where the admissible narrative text itself leaves a genuine ambiguity, and this study
does not resolve it by inventing an answer.** `06`'s own derivation-table mechanism ("a carrier kind
is derived from (multiset of input kinds, atoms introduced by this step) → kind, so any operator set
able to introduce the required atoms over the required inputs produces the carrier," `06` lines
20–29) states that a rule fires when the required inputs and atoms are available — but **does not
explicitly state, anywhere in the admissible text this study examined, whether "available" means
"exactly these and no more" or "at least these, possibly more."**

- If matching is **superset-based** ("at least these"): then whenever `V6`'s requirement is met
  (all of `Claim`/`Hypothesis`, `Evidence`, `Defeater` present), `V0`'s own requirement (a subset of
  that) is *automatically* also met — so `V0` would fire in every world `V6` fires in, *plus*
  additional worlds where `Defeater` is absent. This reading is **consistent with** the reachability
  table (`12` lines 196–199: `V0` reaches `Verdict` without `Challenge`, `V6` does not) — under this
  reading, `V0`'s domain is a proper superset of `V6`'s own "triggering" cases.
- If matching is **exact-set-based** ("exactly these"): then a world with `Claim`, `Evidence`, *and*
  `Defeater` all present would satisfy *neither* `V0`'s exact two-element requirement nor a
  three-element exact match unless `V6`'s own rule is separately, exactly stated — which `12` does
  give (`{Claim,Evidence,Defeater}` per this study's own reconstruction, `03`). Under this reading,
  `D_0` and `D_6` would be **disjoint** rather than nested.

**Both readings are consistent with `12`'s own stated reachability conclusion** (in a world with no
`Defeater` at all, only `V0` can fire, under either matching convention) — the ambiguity does not
affect the one directly-stated fact this study relies on, but it does mean the *general* domain
relationship (nested vs. disjoint) cannot be fully formally established from the admissible
narrative text's own explicit statements alone. **Classified: `NOT FORMALLY TESTABLE FROM
ADMISSIBLE EVIDENCE`** for the general matching rule, while the specific reachability conclusion
itself remains `SOURCE-CLOSED` (directly stated, not dependent on resolving the ambiguity).

## Rule

For the domain each function is defined on, both map every element to the single value `Verdict`
— no branching, no alternative outputs stated for either.

## Partiality/totality

Both remain partial, honestly represented — neither source states behavior for inputs outside its
own stated domain.

## Does V6 add a predicate?

**Yes, in the loose sense of "requires an additional carrier to be present"** — but not in the
stricter sense of a separately-stated Boolean precondition-predicate over the inputs (e.g., no
"IF `defeater.survived == true` THEN..." conditional is given; the requirement is expressed purely
as an additional required *carrier*, in the same table format as every other rule).

## Does V6 change the meaning of `Verdict`?

**Not stated.** `Verdict` remains the same named carrier kind in both rules' output column; no
source text states that a `Verdict` produced under `V6` differs internally, structurally, or in
interpretation from one produced under `V0`. The difference the sources establish is entirely about
*when* a `Verdict` is reachable, not about what a `Verdict`, once produced, *is*.
