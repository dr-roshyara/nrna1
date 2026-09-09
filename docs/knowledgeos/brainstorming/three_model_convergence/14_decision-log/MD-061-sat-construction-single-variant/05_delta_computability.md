# MD-061 §05 — Consequence for `Δ_t` (§9 of the authorizing prompt)

`Sat*` reaches **Gate C (CONDITIONAL CANDIDATE)**, not A or B (`06`) — per the authorizing prompt's
own instruction: "If C, show exactly what additional assumption is required."

## The required assumptions, exhaustively listed (from `03`)

1. `K_t` is restricted to instances possessing a `Σ_t` component (i.e. V7 specifically).
2. `Req(EC_t)` is restricted to its `Σ_t`-shaped subtype, `Req_Σ`.
3. `Σ_t`'s enumerated domains are treated as flat sets, not ordered scales (no order relation
   invented).

## Given these three assumptions, what becomes computable

$$\Delta_t^{Σ} := \{r ∈ Req_Σ : Sat^*(K_t,r)=0\}$$

is **genuinely well-typed and computable** for any concrete V7-shaped `K_t` instance and any
concrete finite set of `Σ_t`-shaped requirements — a real, mechanical computation (given a `Σ_t`
value and a list of `(component,Accept)` pairs, evaluating membership for each is immediate).

**This is the first concretely computable slice of `Δ_t` produced anywhere in this reconstruction's
own F4 work** (MD-059/060 both found `Sat`'s body missing entirely; this phase produces one, at the
cost of the three disclosed narrowings above).

## What remains NOT computable

**Full `Δ_t`** — over all of `Req(EC_t)`, for any `K_t` variant — remains **not computable**. The
smallest missing input to extend this result: **typed semantics for V7's other ten components**
(`A_t,R_t,E_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t`) — none of which has ever been given a value domain
anywhere in the corpus (MD-060 `01`). Extending `Δ_t^Σ` to cover requirements over any of these ten
would require exactly the same kind of typing work `Σ_t` alone already received once, for each
remaining component — named precisely, not invented here.
