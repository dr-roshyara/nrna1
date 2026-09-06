# J — Proof-Obligation Register (§27)

| Claim | Status | Basis |
|---|---|---|
| `Zero(K,EC) ⟺ Δ = ∅` | **ESTABLISHED BY DEFINITION** | DEF-22; P4 restates it |
| `Reject(H1) ≠ Accept(H2)` | **ESTABLISHED BY DEFINITION** | `Reject` removes one member; P3 is definitional |
| `Adequate ≡ Zero` | **ESTABLISHED BY DEFINITION — and this is the problem** | CIRC-3: they have one extension |
| `K_t ⊆ E_t` | **ESTABLISHED BY DEFINITION** | v1.1 correction; `Γ` selects |
| **No total `Γ : (E,Q,C,EC) → K` that attributes anything is factive** | **DERIVED PROPOSITION, witness-supported** | CE-1 + policy sweep. The general argument is one line — `Γ`'s domain excludes truth — but it is **not yet written as a theorem in the theory document** |
| Determination is set-valued; `\|A\|>1` is a legitimate outcome | **SIMULATION-SUPPORTED** | C, F, AD-9; 0 fabrications in 10 000 trials |
| Evidence dependence must be declared or it is double counted | **SIMULATION-SUPPORTED** | G, AD-3; naive vs aware arms differ in `independent_sources` |
| Model fit does not license a causal claim | **SIMULATION-SUPPORTED** | H: `β=1.852`, `R²=0.899`, true effect `0`, `causal_status=not-identified` |
| Epistemic standards do independent work | **SIMULATION-SUPPORTED** | D: identical reality + evidence, different determination |
| `Zero` and adequacy are inquiry-relative | **SIMULATION-SUPPORTED** | E, P20: same `K_t`, `Zero_1 ≠ Zero_2` |
| `K_t ≠ K_{t+1}` with identity stable | **SIMULATION-SUPPORTED** | I, P19 |
| Revision requires an evidence-retirement relation | **DERIVED FROM A NEGATIVE RESULT** | CE-3 — the theory has none, and the omission is load-bearing |
| `≡_sem` is well defined | **STILL REQUIRING MATHEMATICAL WORK** | CIRC-5 — currently circular |
| The four-way unknown taxonomy is exhaustive | **STILL REQUIRING MATHEMATICAL WORK** | four kinds were sufficient here; completeness unproved |
| Requirement kinds are exhaustive | **STILL REQUIRING MATHEMATICAL WORK** | five kinds chosen by the experimenter (SMUG-3) |
| Any of this describes real knowledge | **STILL REQUIRING EMPIRICAL VALIDATION** | the world is synthetic; nothing here is evidence about actual epistemic practice |
| Kernel minimality | **NOT ADDRESSED — deliberately** | §13 of the protocol; see K |
