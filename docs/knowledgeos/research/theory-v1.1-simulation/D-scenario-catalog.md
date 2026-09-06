# D — Scenario Catalog (§5)

Ten families, each with a semantic purpose. Scenario seed `20260902`.
Machine-readable outcomes: `results/scenarios.json`.

| # | Family | Construction | Observed result |
|---|---|---|---|
| **A** | Simple determination | two honest sources agree on `os=RHEL9.8`, `S-strict` (corroboration required) | `A={RHEL9.8}` **unique**, standard met, attributed. `K` is a *record with status, provenance, weight* — **not a copy of the observation** |
| **B** | Incomplete evidence | target `reachable`; **no channel** for it | `status=cannot-determine`, `A=∅`, `Zero=false`, `Δ≠∅`, **nothing invented** |
| **C** | Competing hypotheses | `Ubuntu22.04` rejected; evidence reads `rpm-family` → `{RHEL9.8, RHEL8.6}` | `A={RHEL9.8,RHEL8.6}` **underdetermined**, no attribution. `Reject(H1)` did **not** become `Accept(H2)` |
| **D** | Different standards | reality, observation, evidence, inquiry **identical**; only `S^epi` varies (one source) | `S-lenient` → **unique**; `S-strict` (corroboration) → **cannot-determine**. Evidence and truth verified byte-identical across arms |
| **E** | Different inquiries | one fixed `E_t`; `Q1={os}` vs `Q2={os, ram_gb}` | same `K_t`; `I_1 ≠ I_2`; `Δ_1=∅`, `Δ_2={r2}`; `Zero_1=true`, `Zero_2=false` |
| **F** | Conflicting evidence | source A says `8081`, source B says `8082`, equal reliability | `A={8081,8082}` **underdetermined** — **not averaged**, conflict preserved, no attribution |
| **G** | Dependent evidence | `e2` declared `derived_from e1` | dependence-aware: 1 assessment dropped, `independent_sources` falls, corroboration requirement unmet. Naive: counts both |
| **H** | Model mismatch | `Z→X`, `Z→Y`, **no** `X→Y`; n=4000 | `M1: β=1.852, R²=0.899`, `causal_status = not-identified`; true effect 0. **No causal knowledge attributed** |
| **I** | Retrospective revision | reality changes `RHEL9.8 → RHEL8.6`; new evidence arrives; `Revise` | `K_1 ≠ K_2`, identity stable, pre-revision determination stored in `history` |
| **J** | Unobservable property | `reachable` with no channel at all | classified **`UNOBSERVABLE`** — not observed-false, not probability zero, not a rejected hypothesis |

## The four-way unknown taxonomy did not collapse (§6)

| Kind | Condition | Where observed |
|---|---|---|
| `UNOBSERVED` | a channel exists, never queried | randomized layer |
| `UNINTERPRETED` | observation held, no semantic content assigned | randomized layer |
| `UNDERDETERMINED` | evidence present, `|A_t| > 1` | C, F |
| `UNOBSERVABLE` | no channel exists | B, J |

`P13` confirms `UNOBSERVABLE ≠ UNDERDETERMINED` in both the deterministic and randomized layers
(guard active in 37.4 % of 10 000 trials, 0 failures).

## Note on scenario D — the strongest of the ten

D is the only scenario that holds **reality, observation, evidence and inquiry fixed** and varies a
single theory object. It therefore isolates `S^epi` cleanly, and it is the one place the experiment
shows an epistemic standard doing real work rather than decorating the model.
