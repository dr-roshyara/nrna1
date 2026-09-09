# MD-062 §02 — Semantic Adequacy Test (Phase B) and Requirement Semantics (Phase C)

## Phase B — the ten required questions

| # | Question | Answer |
|---|---|---|
| 1 | Does the corpus define satisfaction as membership in an accepted value domain? | **NO** — `Sat(K,EC_t)` is a boolean-valued predicate with no stated computation rule anywhere (MD-059/060/061); "membership in `Accept_r`" is MD-061's own invention. |
| 2 | Does the corpus define `Accept_r`? | **NO** — the symbol appears nowhere outside MD-061's own text. |
| 3 | Does the corpus define how `EC_t` determines acceptable values? | **NO** — `EC_t=EC(S_t,G_t,Q_t,C_t)` is stated (`[DEF-19]`, M0043) but never computed for a concrete case, and no rule maps it to any acceptance criterion. |
| 4 | **Does `EC_t` actually determine the requirement-specific acceptance condition in `Sat*`?** | **NO — the decisive finding.** `Sat*(K_t,r)` takes `r=(component_r,Accept_r)` directly; `EC_t` is not an argument anywhere in the construction. |
| 5 | Is `Sat*` genuinely a function of `EC_t`, or only of `r` as newly constructed? | **Only of `r`** (and `K_t`). `EC_t` plays no computational role. |
| 6 | Does the construction preserve the purpose-relative character of `EC_t`? | **NO** — per (4)/(5), a demonstrable loss, not a simplification. |
| 7 | Does it capture all information the corpus says `Sat` must consult? | **NO** — M0132 states `Sat`'s computation requires `K_t`'s own component semantics generally; `Sat*` consults exactly 1 of 11 named V7 components. |
| 8 | Can two requirements with identical `Accept` sets but different epistemic meaning be distinguished? | **NO** — `r` carries no "why" channel (no Reason field); two requirements with the same accepted values but different justification are indistinguishable. |
| 9 | Can two states with the same `Σ_t` but different relevant context differ in satisfaction? | Under `Sat*` as literally defined: **NO, they cannot** (only `Σ_t` is consulted) — but the corpus's own `EC_t`/context apparatus (`Q_t,C_t`) suggests they plausibly *should* be able to. |
| 10 | If yes, does this expose missing inputs to `Sat*`? | **YES** — `EC_t` itself, and at minimum whichever of V7's other ten components a given requirement would need to consult. |

**Phase B verdict: `Sat*` is not a reconstruction of corpus `Sat` — it is a much narrower, EC_t-blind,
single-field value test.** This does not mean it is useless (`06`/`07`) — it means its relationship
to corpus `Sat` must be stated precisely, not assumed.

## Phase C — requirement structure, precisely sourced

| Element | Corpus-defined? | Introduced by |
|---|---|---|
| `r=(component_r,Accept_r)` | **NO** | MD-061 — `DESIGN CHOICE` |
| `Accept_r` | **NO** | MD-061 — `DESIGN CHOICE` |
| `Req_Σ` (the `Σ_t`-shaped subtype of `Req(EC_t)`) | **NO** | MD-061 — `DESIGN CHOICE` |
| The mapping `EC_t → Req_Σ` | **NO — and, per Phase B(4), not even attempted**; the mapping does not merely lack a computed value, it is absent from the construction entirely | — |

None promoted to a corpus concept. All four remain `DESIGN CHOICE`, exactly as MD-061 itself already
disclosed for the first three — Phase B sharpens the fourth from "uncomputed" to "structurally
absent."
