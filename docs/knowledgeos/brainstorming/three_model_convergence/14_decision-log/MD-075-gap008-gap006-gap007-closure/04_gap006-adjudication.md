# MD-075 §04 — GAP-006 Adjudication: `phase_measure_theory` `K_t`/`Δ_t` vs. F4 `K_t`/`Δ_t`

## Method

A bounded structural comparison, per the mission's §5 — not a new reading pass. Drawn from: (a)
MD-072's own nine-batch census, which already produced full evidence packets for the
`phase_measure_theory`-lane's most mature `K_t` formulations (`step-155A`'s recursion; the `step-292/`
crosswalk renaming `K_t→A_t` under governance decision "R1"); (b) this session's own detailed,
already-in-hand knowledge of F4's own `K_t`/`Δ_t` definitions across their full version history
(MD-068's Theory Object Registry; MD-069's `T0`–`T23` timeline).

## Structural comparison, dimension by dimension

| Dimension | `phase_measure_theory` `K_t` (most mature form, `step-155A`) | F4 `K_t` (T5, `[00-47]`) |
|---|---|---|
| Definition | `K_{t+1}=F(K_t,I_t,C_t,E_t,D_t,Δ_t,A_t,O_t,V_t,G_t)` — 9 named argument streams (Inquiry, Context, Evidence, Determination, Decision, Action, Observation, Verification, Governance) | `K_t∈𝕂` — **deliberately abstract**, "type left open" (MD-069 T5, a `GENERALIZATION` retreating from earlier concrete substrates v3/v4) |
| Arguments | 9, explicitly named and typed at a conceptual level | none — F4's own canonical source states the object's internal type is not fixed |
| Domain/codomain | `𝕂×...→𝕂` (an explicit recursive update over 9 co-evolving streams) | `K_t∈𝕂` only; no recursive update formula is given for `K_t` itself in the canonical source — the closest analogue is `Sat(K,r,Γ)`'s own dependence on `K` |
| Components | Extensively componentized across the lane's own history: 6-tuple → 10-tuple → 11-tuple (`step_276`, `K_t=(A_t,R_t,E_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t)`) | None — by design |
| Temporal role | Explicit recursion, `K_{t+1}` computed from `K_t` plus 9 streams | `K_t` is time-indexed but its own transition is never formalized in the canonical source — `Sat`/`Δ_t`/`Zero` are the formalized transitions, not `K_t` itself |
| Requirement/gap semantics | `Δ_t` appears as one of the 9 argument streams feeding `K_{t+1}` — an *input*, not an output computed from `K_t` and a requirement set | `Δ_t={r∈Req(EC_t):¬Sat(K_t,r)}` — an *output*, computed FROM `K_t` and `EC_t` |
| Provenance | This lane's own governance event: `K_t` formally renamed `A_t` under decision "R1" (`step-292/`, Sep 2) | No rename; `K_t` remains `K_t` throughout T0–T23 |
| Implementation role | `KR-SIM`-adjacent, never executed in this specific recursive form (per MD-072's own census, no evidence this exact formula was ever run) | `KR-SIM-2026-09-02` operationalizes the T5/T7 formulas (T8), not this lane's own recursion |

## Adjudication (six-level ladder)

1. **Lexical**: yes — identical symbol `K_t`, identical symbol `Δ_t`.
2. **Conceptual**: partial — both are "the current epistemic state, as a function of time," at the
   loosest possible level of description.
3. **Functional**: **not demonstrated**. F4's `K_t` functions as the *argument* `Sat(K,r,Γ)` is
   evaluated over — a role that requires no internal structure. `phase_measure_theory`'s `K_t`
   functions as a *richly structured container* whose own components (`Determination`, `Decision`,
   `Governance` streams) are folded directly into its own recursive definition. These are different
   functional roles.
4. **Structural**: **the comparison is ill-posed, not merely unresolved.** F4's own source explicitly
   declines to give `K_t` internal structure (a deliberate `GENERALIZATION`, not an oversight — MD-069
   records this as a considered retreat from earlier concrete forms). There is no F4-side structure to
   compare `phase_measure_theory`'s own 6–11-tuple forms against. A structural-correspondence claim
   cannot be evaluated when one side of the comparison has, by design, no structure.
5. **Formal equivalence**: not established, and not establishable given point 4.
6. **Demonstrated identity**: no.

## Verdict

**`HOMONYM` (same name, structurally and functionally distinct objects), sharper than MD-072's own
framing.** MD-072 recorded this as `UNRELATED_HOMONYM, UNWITNESSED bridge` — correct, but framed
purely as an absence of citation. This adjudication adds a **positive** reason beyond absence of
citation: even if a citation existed, F4's own `K_t` has no internal structure for `phase_measure_
theory`'s own elaborate tuple forms to correspond to. The two objects are not "unresolved pending more
evidence" — they are answering different design questions (F4: what must `K_t` provide to `Sat`? vs.
`phase_measure_theory`: what does a knowledge state actually contain?).

**`Δ_t`**: the same pattern, more sharply. F4's `Δ_t` is a *computed output* (the requirement-failure
set). `phase_measure_theory`'s `Δ_t` (in `step-155A`'s own recursion) is an *input stream* to
`K_{t+1}`. An object that is computed-from-`K` cannot be the same object as one that is fed-into-`K`'s
own update without at minimum a stated conversion — none exists. **`HOMONYM`.**

## What this does not do

Does not merge either `K_t` or `Δ_t` across the two lanes. Does not retroactively change F4's own
`K_t` v1–v5 version history (MD-068) or `phase_measure_theory`'s own internal proliferation record
(MD-072 §01/§04). Does not assert the `phase_measure_theory`-lane's own `K_t` is "wrong" or "worse" —
only that it is not evidenced as the same object as F4's.
