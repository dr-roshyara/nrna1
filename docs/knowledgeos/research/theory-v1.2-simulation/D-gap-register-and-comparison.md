# D — Gap Register and v1.1 → v1.2 Comparison

## Theory gaps under v1.2

| ID | Gap | Class | Status | Severity |
|---|---|---|---|---|
| **TG12-1** | `Zero ⟺ Δ=∅` is ambiguous under three-valued `Sat` — it names 3 predicates that disagree | G1 mathematical | **NORMATIVE decision required** | **CRITICAL** |
| **TG12-2** | `Zero` is unevaluable while `governance`, `temporal`, `operational` classes are open | G1 / G9 | TECHNICALLY OPEN | **CRITICAL** |
| **TG12-3** | Factivity (CE-1) — carried over, and its premise *weakened* by v1.2 §9 | G3 epistemological | TECHNICALLY OPEN | **CRITICAL** |
| **TG12-4** | No evidence-retirement relation (CE-3) — §VIII names, does not define | G2 semantic | TECHNICALLY OPEN | HIGH |
| **TG12-5** | `⪰` on epistemic status is used by `Sat_status` and defined nowhere | G1 | TECHNICALLY OPEN | HIGH |
| **TG12-6** | The equality refinement order is λ-relative; λ is unfixed | G1 | BOUNDED | HIGH |
| **TG12-7** | `Observation` is OPEN, and corroboration results depend on its source component | G2 | TECHNICALLY OPEN | HIGH |
| **TG12-8** | `Zero` may be a name rather than a construct | G2 | HYPOTHESIS | MEDIUM |
| **TG12-9** | Exhaustiveness of the 8 requirement classes unproved | G1 | UNKNOWN | MEDIUM |
| **TG12-10** | `δ` undefined (Step 290) ⇒ every operational requirement is permanently `U` | G6 computational | TECHNICALLY OPEN | MEDIUM |
| **TG12-11** | The status vocabulary cannot express "refuted as reading A, surviving as reading B" | G2 | UNKNOWN | LOW |

## Comparison: what the weakening bought and cost

| Dimension | v1.1 | v1.2 | Verdict |
|---|---|---|---|
| **Diagnostic resolution** | one undifferentiated "gap" | violated / undetermined / satisfied, by class | **v1.2 strictly better** |
| **Absence vs negation** | collapsed — `¬Sat` covers both | separated: `U` vs `⊥` | **v1.2 strictly better** |
| **`Zero` well-definedness** | apparently definite | **ambiguous — 3 readings** | **v1.2 exposes a defect v1.1 concealed** |
| **Factivity** | contradiction found (CE-1) | unchanged, premise weakened | **v1.2 no better; arguably worse** |
| **Revision** | gap found (CE-3) | unchanged, section named | **v1.2 no better** |
| **Equality** | not modelled | five relations, order computed | **v1.2 strictly better** |
| **Observation** | assumed `(x,t,c,s)` | OPEN, and shown load-bearing | **v1.2 strictly better** |
| **Claim grading** | prose hedging | status vocabulary | **v1.2 strictly better** |
| **Executability** | full lifecycle runs | full lifecycle runs (same code) | unchanged |

> `[EXP]` **The weakening was productive.** v1.2 did not repair a single v1.1 failure — but it made
> two of them *better diagnosed*, exposed one defect v1.1's phrasing had hidden (`Zero`'s ambiguity),
> and gave the programme a vocabulary in which "we do not know" is a first-class result rather than a
> hedge.

## The one thing v1.2 made worse

`E_t ≠ K_t` was a **correction** in v1.1 and is a **research distinction** in v1.2 (§9). CE-1 depends
on `Γ` being a function of `E` alone. Weakening the status of that equation does not make the
contradiction go away — the witness still runs — but it does mean **v1.2 has no ratified statement
that the contradiction contradicts.** `[INF]` The failure is now homeless: real, reproducible, and
attached to a claim the theory no longer asserts firmly.
