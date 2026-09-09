# MD-061 §03 — The Computational Question, and the Weakest Candidate `Sat*`

## The precise question (§3 of the authorizing prompt)

> What information must be available to determine whether a particular requirement `r` is satisfied
> by a particular state `K_t`?

**Minimal dependency set**: given the evidence ledger (`02`), the only genuinely corpus-typed
information available anywhere in the census is `Σ_t`'s own five enumerated fields. Therefore:
`Sat(K_t,r) ← Σ_t(K_t)`'s relevant field value, `∧ r`'s own stated acceptance condition.

| Required input | Status |
|---|---|
| `K_t` possessing a `Σ_t` component | corpus-defined **only for V7** — missing for the other 11 census variants |
| `Σ_t`'s five field values, per instance | corpus-typed (domain), but no concrete instance value is ever given in any source — this construction reasons about the *type*, not a worked example |
| a requirement `r`'s own shape | **missing** — `Req(EC_t)` is never itself typed beyond "a set of requirements" (`02`) |
| an order relation on any `Σ_t` field | **missing** — not stated; **newly introduced or avoided by this research**, decided below |
| the other ten V7 components (`A_t,R_t,E_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t`) | **missing**, untyped — deliberately NOT consulted (see below) |

## Constructing the weakest non-trivial candidate

**Design choice 1 (disclosed)**: since `Req(EC_t)` is never typed, this construction introduces the
narrowest possible requirement shape that `Σ_t`'s own structure can support without inventing new
semantics:

$$r = (\text{component}_r \in \{A,S,R,V,C\},\ \text{Accept}_r \subseteq \text{Domain}(\text{component}_r))$$

— a requirement names one `Σ_t` field and an acceptable subset of its stated enumerated domain.
**This is narrower than `Req(EC_t)` in general** — it covers only "`Σ_t`-shaped" requirements, not
every possible epistemic requirement. Marked `DESIGN CHOICE`, not presented as corpus-native.

**Design choice 2 (disclosed, deliberately weak)**: `Σ_t`'s enumerated domains (e.g. `Support∈
{None,Weak,Moderate,Strong,VeryStrong}`) are treated as **flat sets**, not ordered scales — even
though the chosen names visually suggest an order. **No source states an order relation**, and
inventing one (so that a requirement could say "`Support≥Moderate`") would be exactly the kind of
substantive, unstated modelling choice this phase must avoid. Using set membership instead
(`Accept_r` is an arbitrary, requirement-specified subset) is strictly weaker and requires no
invented structure.

## The candidate

$$\boxed{Sat^*(K_t,r) := 1 \text{ if } \pi_{\text{component}_r}(\Sigma_t(K_t)) \in \text{Accept}_r,\ \text{else } 0}$$

where `π_c` projects field `c`'s value out of `Σ_t(K_t)` — `Σ_t(K_t)` denoting "the `Σ_t` component
of `K_t`," which **presupposes `K_t` is a V7-shaped instance** (a disclosed dependency on the variant
selection, not a general-`K_t` claim).

## The ten required questions, answered

1. **What exactly is a requirement?** `r=(\text{component}_r,\text{Accept}_r)` — DESIGN CHOICE 1.
2. **What exactly is the condition for satisfaction?** Set membership of the observed field value in
   the requirement's accepted set.
3. **Which components of `K_t` are consulted?** Only `Σ_t` — none of V7's other ten top-level
   components, and no component of any other variant.
4. **What is the role of `R_t`?** `R_t`/`Req(EC_t)` is `Sat*`'s formal domain in principle; `Sat*`
   as constructed only covers the `Σ_t`-shaped subset of it, `Req_Σ⊆Req(EC_t)` — a genuine, disclosed
   narrowing.
5. **What is the role of `Σ_t`?** The sole inspected structure — everything `Sat*` can determine, it
   determines from `Σ_t` alone.
6. **What is the role of evidence/provenance/context?** Not consulted as separate arguments;
   `Σ_t.Acquisition` is itself a provenance-flavored field, so provenance enters only indirectly,
   through `Σ_t`.
7. **Is satisfaction extensional, relational, probabilistic, categorical, or something else?**
   Categorical/set-membership — a direct lookup-and-test, not inferential or probabilistic.
8. **Is `Sat*` deterministic for fixed inputs?** Yes, by construction — a pure function.
9. **What is the domain and codomain?** Domain: `{K_t : K_t \text{ has a } Σ_t \text{ component}\} ×
   Req_Σ`. Codomain: `\{0,1\}`.
10. **Can `Δ_t` then actually be computed?** Only for the `Req_Σ` subset — see `05`.

## Strict separation table (§5 of the authorizing prompt)

| Element | Source | Status | Justification |
|---|---|---|---|
| domain (`K_t` restricted to V7) | this construction | **DESIGN CHOICE** | required because `Σ_t` only exists for V7 |
| domain (`Req_Σ`) | this construction | **DESIGN CHOICE** | `Req(EC_t)` is untyped; a `Σ_t`-shaped subtype is the narrowest usable restriction |
| codomain `\{0,1\}` | M0043's own `Sat(K,EC_t)` usage (a boolean-valued predicate throughout `[DEF-19]`–`[DEF-21]`) | **CORPUS-DERIVED** | matches the frozen `Δ_t` definition's own use of `¬Sat(...)` |
| predicate (set membership) | this construction | **DESIGN CHOICE**, deliberately the weakest available | avoids inventing an order relation `Σ_t`'s own domains do not state |
| component dependency (`Σ_t` only) | `Σ_t`'s own typed definition, M0125 | **CORPUS FACT** (the field exists and is typed) + **DESIGN CHOICE** (restricting `Sat*` to it alone) |
| threshold/rule | none — membership only, no threshold | — | avoided precisely to not invent an ordinal rule |
| missing assumption, named | an order relation on `Σ_t`'s ordinal-looking fields | **OPEN**, not assumed | would be required for any *stronger*, threshold-based `Sat*` — explicitly not attempted here |

**`Sat*` is not presented as corpus-native.** It is a `CONDITIONAL CANDIDATE` (Gate C, `06`) —
mathematically coherent, built only from stated corpus structure, but dependent on two disclosed,
explicit narrowing choices (the `Σ_t`-shaped requirement type; set-membership rather than an
invented ordinal rule).
