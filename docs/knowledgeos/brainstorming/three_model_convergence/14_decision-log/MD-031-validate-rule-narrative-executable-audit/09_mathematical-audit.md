# Mathematical Audit

## Formal statement

`V_N : D_N ⇀ C_N` (narrative, `06`, chained through `04`'s atom assignment):

- `D_N = { {Claim, Evidence}, {Hypothesis, Evidence} }` (two admissible input-carrier-sets;
  `⇀` because the rule is partial — undefined for any other input-set combination)
- `C_N = { Verdict }`
- `V_N(x) = Verdict` for `x ∈ D_N`; undefined otherwise (no "else" clause anywhere in `06`)

`V_E : D_E ⇀ C_E` (executable, `kr/carriers.py`, per MD-030):

- `D_E = { {Claim, Evidence}, {Hypothesis, Evidence} }` (baseline `V0`) — **note**: under `V6`,
  `D_E` changes to `{ {Claim,Evidence,Defeater}, {Hypothesis,Evidence,Defeater} }`, a *different*
  domain — so `V_E` is not single-valued across the whole executable lane; it is baseline-`V0`-
  specific.
- `C_E = { Verdict }`
- `V_E(x) = Verdict` for `x ∈ D_E` (baseline); undefined otherwise

## Domain correspondence

**`D_N = D_E` (baseline `V0` only)** — exact set equality, both members, no more and no less.
`D_N ≠ D_E^{V6}` — the alternative executable domain is strictly larger (adds `Defeater`). This is
not a contradiction between `06` and `V0`; it is a genuine open question about whether `06`'s domain
is meant to be exhaustive of the true `Validate` contract, or only of the baseline model — `06` gives
no textual signal either way (per `07`).

## Codomain correspondence

**`C_N = C_E = {Verdict}`** — exact match, both sides, no ambiguity found anywhere.

## Rule correspondence

**`V_N(x) = V_E(x)` for all `x` in the shared domain** — follows directly from both sources' own
stated definitions (`06` line 41; `kr/carriers.py` lines 65–66), not from any further derivation this
study performed. This is a direct textual match, not a proof requiring intermediate steps.

## Partiality

Both `V_N` and `V_E` (baseline) are partial functions with the **identical** domain of definition and
the **identical** undefined region (any input-carrier combination other than the two listed pairs is
outside both functions' domains). Neither source states what happens for an out-of-domain input
(no "else," no error carrier, no `⊥` symbol) — this study does not invent one; both are recorded as
`NOT SPECIFIED ENOUGH TO TEST` for the out-of-domain case, per the authorization's own explicit
instruction not to call a rule "ill-defined" merely because a source omits details.

## Well-definedness

**Well-defined on its stated domain, both sources.** No source states or implies that
`{Claim,Evidence}` and `{Hypothesis,Evidence}` could ever simultaneously produce two different
outputs — the rule maps every element of its domain to the single value `Verdict`, consistently, in
both `06` and `carriers.py`.

## Invariants

Neither source states an invariant preserved *by* `Validate` specifically (e.g., no claim that
`Verdict`'s own internal structure preserves some property of `Claim`/`Evidence`). This study does not
infer one — per the authorization's own instruction, and consistent with MD-029's own prior finding
that P-3's only candidate invariant was itself an unestablished hypothesis (not reopened here).

## Summary judgment

Formally, `V_N` and `V_E` (restricted to the executable lane's own declared baseline, `V0`) are the
**same partial function** — same domain, same codomain, same mapping, same undefined region. This is
a mathematical identity of the two *stated rules*, not a claim about which (if either) correctly
describes Model B's true `Validate` operator — that remains a specification-authority question (§`08`,
§`13`), not a mathematics question.
