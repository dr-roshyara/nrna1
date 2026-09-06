---
artifact: R5 · RESEARCH-TO-ARCHITECTURE REPORT
status: RESEARCH · survivors only · **no governance decision taken, one raised**
---

# R5 · Research → Architecture

Only the survivors are carried forward — **six after the revised register** (2026-08-31). Each is traced
`Hypothesis → formal consequence → DDD consequence → architecture consequence → governance question`.

## H-K03 · Field ≠ Knower — `R5`

| Stage | Consequence |
|---|---|
| **Formal** | `Knower ∉ K`; `𝒩` persists across `K_t → K_{t+1}`; `K` definable without `𝒩` |
| **DDD** | `Knower` is an **external actor**, not an entity inside the aggregate. Goal `G`/IdealState is **owned outside** the state it constrains |
| **Architecture** | already enforced: `Zero/Lord/Sārathi ∉ State Mutation Boundary` |
| **Governance** | **none — it is already ratified** (FA-4, grade `[E]`) |

⚠️ **Consequence for the programme, and it is a caution:** because this concept entered via the
Sanskrit lineage and is now ratified, **any further Gītā mapping will be tempted to cite it as
precedent.** It is not precedent. It is one ratified convergence, and R4 shows it is the *only*
non-independent survivor.

## H-K06 · Action ≠ Result — `R6`

| Stage | Consequence |
|---|---|
| **Formal** | `δ(K,o₁) =_semantic δ(K,o₂) ⇏ o₁ = o₂`. **The equality must be named or the property is vacuous** |
| **DDD** | operations are **commands**; results are **domain events**; the state is neither. `Command ≠ Transformation` already boxed at §256.21 |
| **Architecture** | an operation registry must key on operation identity, **not** on resulting state |
| **Governance** | **none.** ⚠️ **WITHDRAWN 2026-08-31:** I previously wrote that this *strengthens* the case for typing `humanActRef`. **It does not** — that is a different identity layer (`E6`). The `humanActRef` question stands on its own implementation evidence |

## H-K11 · Outcome independence — `R6`

| Stage | Consequence |
|---|---|
| **Formal** | `Valid(o,K) ⊥ Outcome(o)`; no conjunct of `Admissible` reads the outcome |
| **DDD** | validation is a **precondition service**, not a post-hoc assessment |
| **Architecture** | a failed execution must not retroactively invalidate the authorization that permitted it |
| **Governance** | **none** |

## H-K08 · Guidance ≠ Authority — `R6`

| Stage | Consequence |
|---|---|
| **Formal** | `Guidance ≠ Authority ≠ Decision ≠ Execution ≠ Transition` — a 5-way chain, each boundary separately witnessed |
| **DDD** | guidance is a **domain service** returning a recommendation; decision is an **external actor's act**; execution is a **command handler** |
| **Architecture** | **implemented**: assessments *"confer no authority"*; three verdict values **structurally unemittable by machine**; 132/132 grants carry `humanActRef` |
| **Governance** | ⚠️ **one real question — see §Governance below** |

## H-K09 · Four epistemic stages — `R4`

`Observation → Evidence → Decision → Action` are distinct. **`Knowledge` vs `Discrimination` is not
separately grounded** — `Knowledge` is not among the 8 ratified primitives. **Four stages, not five.**
Formal consequence: the `Qualify` and `Assess` operations are the two stage-boundaries that need
bodies. **No governance question.**

## H-K16 · Sañjaya = the observation layer — `R5`

| Stage | Consequence |
|---|---|
| **Formal** | `W --Ω--> O`: `W` = Domain reality, `Ω` = state-observation capability, `O` = State knowledge. Supplies the domain and codomain that `Identifiable(g,Ω)` (`31.20`) needs and that no claimed `K` model had |
| **DDD** | `Sañjaya` is an **anti-corruption / observation layer** at the context boundary, not an entity in the aggregate. `Knower ≠ Observer` makes them **two external actors, not one** — a refinement of `GK-04` |
| **Architecture** | `Arjuna_K ≠ Sañjaya_K`, *"built on top of, never replacing"* ⇒ a **two-layer** epistemic stack. `K_A(Reality) ≠ K_B(Reality)` ⇒ multiple provenance-traced observer states must co-exist |
| **Governance** | **none** — corpus-native since 2026-08-26; `Observation` is already a ratified primitive |

⚠️ **Consequence for `Σ`:** `Sañjaya_K = (Observed, Inferred, Reported, Unknown, Conflicting,
Unresolved)` is an **observation-layer** status set; `Σ₀` is an **assertion-layer** one. **They were
never rivals, and the six values do not belong in `Σ₀`.** This dissolves — rather than answers — the
question of whether `Σ₀` is "too small": it is the wrong layer to ask at.

## H-K13 · Jñāna — `R4`, a correction rather than an addition

`Jñāna` maps to **knowing/transformation**, not to `Knowledge`. `Knowledge` is absent from the 8
ratified primitives by design. **Formal consequence:** `Jñātā → Knower`, `Jñeya → Proposition`,
`Jñāna → δ`. **Architecture consequence: none — it reinforces an existing ratified decision.**
**Governance: none.**

## Governance question raised (exactly one)

> **The `Guidance ≠ Authority` boundary is architecturally and computationally sound and is
> *implemented*. But the referent of `humanActRef` is untyped free text, and two distinct authority
> acts have already shared one `grantId` — a realised loss of an authority act.**
>
> **Question:** does the authority act acquire an identity of its own, or does the boundary continue
> to be maintained by discipline alone?
>
> **This is not a Gītā question.** It arrives here because H-K08's architecture consequence exposes
> it. It is already on the estate's register (`D-2`), and **nothing in this research resolves it.**

## What this research did NOT do

Did not redefine `K` · did not choose the canonical state · did not define `δ` · did not introduce
`Knowledge Ātma` (**destroyed, `RX`**) · did not reintroduce `Knowledge` as a primitive (`GK-13`
**mis-mapping corrected**) · did not promote `Sañjaya` (**recorded as corpus-native, not proposed**) · did not make `Ω` canonical · did not equate Kṛṣṇa with `Ω`
(**blocked by E-10**) · did not equate Dharma with Policy · did not equate Buddhi with AI · did not
declare Arjuna the Knower (**flagged as a two-role conflation**) · did not modify the Constitution ·
did not resolve Blocker 1 **by assumption** — it resolved it **by projection**, formally, and named
the one function that blocks its computation.
