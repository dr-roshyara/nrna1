# MD-061 §04 — Falsification Tests (T1–T8) and Representation-Independence (§7)

| Test | Result | Detail |
|---|---|---|
| **T1 — Type correctness** | **PASS** | Domain `\{K_t \text{ w/ } Σ_t\}×Req_Σ`, codomain `\{0,1\}`, both well-defined given `03`'s disclosed narrowing. |
| **T2 — Requirement sensitivity** | **PASS** | Concrete demonstration: for a fixed `K_t` with `Σ_t(K_t).Support="Strong"`, `r_1=(Support,\{Strong,VeryStrong\})` gives `Sat^*=1`; `r_2=(Support,\{None,Weak\})` gives `Sat^*=0`. Two different requirements distinguish the same state. |
| **T3 — State sensitivity** | **PASS** | Concrete demonstration: for a fixed `r=(Support,\{Strong,VeryStrong\})`, `K_t` with `Support="Strong"` gives `Sat^*=1`; `K_t'` with `Support="Weak"` gives `Sat^*=0`. `Sat^*` genuinely depends on `K_t`, not only on `r`. |
| **T4 — Representation dependence** | **CONDITIONAL — marked explicitly, per the authorizing prompt's own instruction** | "Same intended state" is not corpus-defined across variants (MD-060). **Cross-variant**: `Sat^*` cannot even be evaluated on 11 of the 12 census variants (none has a `Σ_t` component) — representation dependence in the strongest possible sense, not merely untested. **Within-V7**: `Sat^*` reads by field *name*, not tuple position, so it is invariant under any reordering of `Σ_t`'s own five fields — a narrow, genuine positive sub-result. |
| **T5 — Vacuity** | **FLAGGED, not a defect** | `Accept_r=\text{Domain}(\text{component}_r)` makes any single requirement trivially satisfied; `Accept_r=\varnothing` makes it trivially unsatisfied. This is a property of *how a requirement `r` is chosen*, not of `Sat^*`'s own rule — any predicate framework admits trivial requirements. Policing non-triviality would be `Req(EC_t)`'s own job, out of scope here. |
| **T6 — Contradiction with any frozen Model-B definition** | **NO CONTRADICTION FOUND; one sub-test UNTESTABLE** | `Sat^*`'s `\{0,1\}` codomain matches `Δ_t`'s own `¬Sat(...)` usage exactly (`[DEF-21]`). Interaction with M0132's own frozen `Progress: Δ_{t+1}⊆Δ_t` **cannot be tested** — no transition (`δ`/`Orgasm_t`) was constructed for V7 in this phase (explicitly out of scope, §00 §6) — flagged as untestable given current scope, not scored pass/fail. |
| **T7 — `Δ` consistency** | **PASS, within the disclosed scope narrowing** | `Δ_t^Σ:=\{r∈Req_Σ:Sat^*(K_t,r)=0\}` is well-typed and computable given T1/T8's own well-formedness — see `05` for the full consequence. |
| **T8 — Counterexample search** | **SEARCHED, NOT FOUND** | Searched M0125, M0126, M0132, M0043, M0048 directly for any worked example of `Sat` evaluated against `Σ_t` for a concrete case. **None exists anywhere in the corpus** — a genuine absence, not evidence of correctness. Per the authorizing prompt's own instruction, absence of a counterexample is **not** interpreted as proof. |

## Representation-independence (§7), classified precisely

| Transformation class | Classification |
|---|---|
| Component splitting/merging/deletion/reordering of `Σ_t`'s own five fields | **NOT APPLICABLE** — not tested; would require inventing a new decomposition, explicitly out of scope |
| Reordering `Σ_t`'s five fields (name-based access) | **PROVEN REPRESENTATION-INVARIANT**, within V7 (T4) |
| Cross-variant representation change (V7 → any other of the 12) | **REPRESENTATION-DEPENDENT** — `Sat^*` is undefined outside V7, a hard dependency, not a soft one |
| "Same intended state, different representation" in the abstract | **UNTESTABLE FROM CURRENT CORPUS** — the notion itself is undefined (MD-060) |

No admissibility rule is manufactured to force any of the above into a cleaner verdict.
