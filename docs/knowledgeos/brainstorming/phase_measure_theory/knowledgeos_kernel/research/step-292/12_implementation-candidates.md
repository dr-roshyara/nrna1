# 12 — Implementation candidates

**Only reached after the research.** Statuses per the mandate. **`CANONICAL` is not used** — no
governance act makes any of this canonical.

---

## IC-1 · Successor-state shape for `δ`

| field | |
|---|---|
| **Source basis** | Reiter SSA, `F(do(a,s)) ≡ γ⁺ ∨ (F ∧ ¬γ⁻)` |
| **Corpus basis** | `δ` is `OPEN`; no competing shape exists |
| **Formal definition** | `F_{t+1} = γ⁺_F(a,A_t) ∨ (F_t ∧ ¬γ⁻_F(a,A_t))` |
| **Inputs / Outputs** | `(A_t, a)` → `A_{t+1}` |
| **Preconditions** | `Poss(a, A_t)`; **and** contradiction already resolved |
| **Effects** | fluent-wise; unmentioned fluents persist |
| **Persistence** | by construction — verified `A2` |
| **Identity implications** | requires unique names for actions — **not established** |
| **Provenance implications** | **none carried** — a gap, not a feature |
| **Computability** | decidable per step; the *theory* is second-order |
| **Determinism** | verified deterministic `A2` |
| **Failure behaviour** | `Poss` false → history non-executable, rejected at the prefix `A4` |
| **Current evidence** | `A2`, `A3`, `A4` execute correctly |
| **Missing evidence** | behaviour over `(Standing, Boundary)` pairs rather than booleans; conflict detection; `DECISION-01` compliance |
| **Maturity / Status** | **BOUNDED** — blocked behind `Contr` and `DECISION-02` |

## IC-2 · Regression as a verification mechanism

| field | |
|---|---|
| **Source basis** | regression operator + regression theorem |
| **Corpus basis** | `KR-HISTORY`: history is written and never read — **regression is what would make it readable for audit** |
| **Formal definition** | `R[φ]` unwinding the SSA to `S₀` |
| **Computability** | terminates on regressable queries over a basic action theory |
| **Determinism** | yes |
| **Current evidence** | **`A3`: agrees with progression 12/12** |
| **Missing evidence** | KnowledgeOS has **no basic action theory**, so the *theorem's guarantee* is unavailable — only the mechanism's shape |
| **Maturity / Status** | **SUPPORTED** — the most transferable item, and still not adoptable |

## IC-3 · Operation registry entry shape

| field | |
|---|---|
| **Source basis** | basic action theory: per-action precondition + effect axioms |
| **Corpus basis** | **step 291: membership, signatures (8/22), bodies (0/22), identity (0/22), ratification (0/22) — ALL OPEN** |
| **Formal definition** | `(name, Poss, γ⁺, γ⁻)` |
| **Missing evidence** | **the members.** A shape without membership is not a registry |
| **Maturity / Status** | **RESEARCH ONLY** — Reiter does **not** close step 291 |

## IC-4 · Persistence-by-construction as a design method

| field | |
|---|---|
| **Source basis** | the frame solution's method |
| **Formal definition** | *state what changes a thing; everything else persists* |
| **Missing evidence** | whether it survives generalization from booleans to `(Standing, Boundary)` |
| **Maturity / Status** | **CORROBORATED** as a method · **OPEN** as a mechanism |

## IC-5 · The three-level equivalence distinction

| field | |
|---|---|
| **Source basis** | `A5` + unique names |
| **Corpus basis** | the corpus tracked **two** levels; the middle one was unnamed |
| **Formal definition** | `syntactic identity ≠ observational equivalence ≠ semantic equivalence` |
| **Maturity / Status** | **NEW SYNTHESIS** — naming only. **Resolves nothing**; `≡_sem` stays `OPEN` |

---

## Not candidates

`Golog` / `RGolog` — **unreachable** until `δ` resolves (`10`) · Reiter's `Knows` — **excluded by
`R1`** (`07`) · `Situation = A_t` — **refuted** (`03`) · `Poss` as `Qualify` — **refuted** (`07`).
