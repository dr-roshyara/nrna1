# 09 — Transformation Gap

**Mandate §14.** Executed evidence: `exec/kos_kernel.py`, `exec/exp_congruence.py`.

---

## 1. The census

Grep over the primary corpus for `K_{t+1} = …`:

- **45 distinct right-hand-side strings**
- **11 distinct named functions:** `T`, `T_t`, `T_K`, `Update`, `Revise`, `Revision`, `Recalculate`,
  `Derive`, `Transition`, `Learn`, `Improve` — plus the unnamed forms `δ`, `F`, `U`, `I^K_t`, `⊕ΔK`, `+`
- Step 240 independently reported *"approximately 25 materially different right-hand sides"*; the raw
  string count is higher because of notational variants.

Representative arities:

| Form | Arity | Source |
|---|---:|---|
| `K_{t+1} = T(K_t)` | 1 | Step 025k |
| `K_{t+1} = K_t ⊕ ΔK` | 2 | early |
| `K_{t+1} = Update(K_t, Input_t)` | 2 | 240 §240.2 (called "historically very strong") |
| `K_{t+1} = δ_K(K_t, o_t, ρ_t, Ω_t)` | 4 | temporal model |
| `K_{t+1} = Transition(K_t, a_t, o_t, e_t)` | 4 | Step 021 |
| `K_{t+1} = Revise(K_t, E_{t+1}, C, t+1)` | 4 | Step 025m |
| `K_{t+1} = F(K_t, I_t, C_t, E_t, D_t, …)` | ≥6 | Step 020 |
| `K_{t+1} = Learn(K_t, Obs_t, Events_t, Policies_t, Outcomes_t)` | 5 | Step 045 |

**TG-1 (`EXECUTED`, CRITICAL).** The transformation has **no settled signature**. Arities range from
1 to ≥6. Step 240's verdict — `OPEN — major contradiction` — stands, and this session's independent
census reproduces it.

---

## 2. Undefined symbols — the mandate's hard requirement

*"No undefined symbols are allowed."* Taking the most-cited form,
`K_{t+1} = δ_K(K_t, o_t, ρ_t, Ω_t)`:

| Symbol | Type given? | Domain given? |
|---|---|---|
| `K_t` | 28 competing tuples (`04-K-GAP-ANALYSIS.md`) | no |
| `o_t` | "observation" — primitive, `(source, method, time)` | partially |
| `ρ_t` | **never defined** — appears only in this equation family | **no** |
| `Ω_t` | in one reading the domain space `Ω_D`; in another the *whole* knowledge space; in a third the sample space of a probability model (`𝒦=(Ω,𝓕)`) | **no** |
| `δ_K` | not defined; contrasted with `δ_X` for domain evolution | no |

**TG-2 (`UNRESOLVED`, CRITICAL).** At least two symbols in the corpus's most-repeated transition
equation — `ρ` and `Ω` — have no declared type anywhere. `Ω` is additionally overloaded across three
incompatible readings (`04` KG-7).

---

## 3. The property the corpus does establish, and it is the important one

$$\boxed{\text{Domain evolution} \neq \text{knowledge evolution}}$$

$$X_{t+1} = \delta_X(X_t, e_t) \qquad\text{versus}\qquad K_{t+1} = \delta_K(K_t, o_t, \rho_t, \Omega_t)$$

The world changing and our record of it changing are different transitions with different inputs.

**TG-3 (`CORPUS ESTABLISHES`).** Step 240 §240.2 is right that this survives the signature
instability, and it is the single most robust result about `T` in the corpus.

---

## 4. EXECUTED: a transformation that actually runs

`exec/kos_kernel.py` implements `T : K × Op → K` over a four-operation set:

```python
𝒪 = { assert(A), relate(a,b,type), withdraw(source), restatus(id, σ) }
```

with a cascading withdrawal over `derives` edges, and `replay(K₀, ops)` as the fold.

Verified properties:

| Property | Result |
|---|---|
| **Total** on `𝒪` | yes (raises only on an unknown op name) |
| **Deterministic** | yes — pure functions over frozen sets |
| **Replay** = fold of `T` | yes, by construction |
| **Terminating** | yes; `withdraw` fixpoints in `≤ \|𝒜\|` iterations |
| **Preconditions** | **none implemented** — because the corpus specifies none (Step 266: `🟡 formal predicates` missing) |
| **Postconditions** | **none** — same reason |
| **Invertible** | **no**. `withdraw` destroys assertions; Step 266 §266.15 marks reversal `🔴 no invertibility established`. Confirmed: from `K'` alone the withdrawn assertions cannot be recovered. |

**TG-4 (`EXECUTED`).** A KnowledgeOS transformation *can* be made total, deterministic and
replayable — this is the first program in the research programme that does it. Five steps titled
"executable"/"execute"/"simulation" (`025a-4`, `025a-5`, `025b`, `051`, `056`) contain no program
(`01-THEORY-EVOLUTION-MAP.md` EV-F2). **This is what those steps asserted; here it is, and it works,
for a four-operation `𝒪`.**

**TG-5 (`EXECUTED`, HIGH).** But that is exactly the limit. The transformation is well-behaved
*relative to a four-operation set this session chose*. Every corpus property that quantifies over `𝒯`
— sufficiency (259), congruence (260.9), minimality (260, 266.19), state equality — is
**underdetermined until `𝒪` is enumerated**, and `exp_congruence.py` EXP-3 shows the answers actually
flip:

```
K=(A,R) sufficient under OPS_MINIMAL?   True
K=(A,R) sufficient under OPS_FULL?      True
but ever_contested(H_calm)=False, ever_contested(H_stormy)=True
on two histories reaching a byte-identical K
```

Add one history-sensitive operation — and escalation-on-prior-contestation is ordinary governance,
discussed in the corpus's own Steps 155/179/181 — and `K=(𝒜,ℛ)` stops being sufficient.

---

## 5. Failure semantics: absent

The mandate requires failure semantics. The corpus specifies none. Concretely, for
`withdraw(source)` there is no answer to:

- what if withdrawal would remove an assertion another authority has `Accepted`?
- what if the cascade would empty `K`?
- is a rejected transformation *recorded*? (If not, replay cannot reproduce a refusal — and a refusal
  is exactly the kind of thing a governance audit needs.)

**TG-6 (`UNRESOLVED`, HIGH).** Without failure semantics, `T` is a **partial function presented as
total**, and `replay` cannot reproduce histories containing refusals. This directly weakens the
provenance/audit claims in `11-PROVENANCE-LINEAGE-HISTORY.md`.

---

## 6. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **TG-1** | 45 distinct RHS strings, 11 named transition functions, arities 1–6. No settled signature. | `EXECUTED` | **CRITICAL** |
| **TG-2** | `ρ` and `Ω` in the most-repeated transition equation have no declared type; `Ω` is overloaded across three readings. | `UNRESOLVED` | **CRITICAL** |
| **TG-3** | *Positive:* `domain evolution ≠ knowledge evolution` survives the instability and is the most robust result about `T`. | `CORPUS ESTABLISHES` | — |
| **TG-4** | A total, deterministic, replayable `T` over a four-operation `𝒪` now exists and runs — the first in the programme. | `EXECUTED` | — |
| **TG-5** | Every property quantifying over `𝒯` is underdetermined until `𝒪` is enumerated, and the sufficiency answer demonstrably flips with a corpus-plausible added operation. | `EXECUTED` | **CRITICAL** |
| **TG-6** | No preconditions, no postconditions, no failure semantics. `T` is partial but presented as total; refusals are unrecordable, so replay cannot reproduce them. | `UNRESOLVED` | HIGH |
| **TG-7** | `T` is not invertible (`withdraw` is destructive); Step 266 §266.15 agrees, and this is confirmed by construction. | `EXECUTED` | MEDIUM |

---

**Next:** `10-GOVERNANCE-REFLEXIVITY-GAP.md`.
