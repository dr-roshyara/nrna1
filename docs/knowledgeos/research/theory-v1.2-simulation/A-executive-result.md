# A — Executive Result · `KR-SIM-2026-09-02-B`

**Model** `KnowledgeOS-Simulation-v1.2` · **Status** `[EXP]`
**Theory under test** v1.2 = v1.1 **deliberately weakened**, plus class-indexed three-valued
`Sat : 𝒦 × ℛ → {⊤, ⊥, U}`.

> Not canonical architecture · not a production implementation · not a final kernel · not a proven
> theory · not a validated ontology.

## What v1.2 changes — and it is a *downgrade of certainty*, not an extension

| v1.1 | v1.2 |
|---|---|
| `Observation = (x,t,c,s)` | **OPEN CONCEPT**; that tuple is only a *candidate model* |
| `Zero` a construct with a definition | **candidate epistemic-boundary construct** — 7 possible readings |
| `E_t ≠ K_t` a correction | **research distinction, not a ratified theorem** *(weaker)* |
| `Sat` two-valued | **three-valued, class-indexed over 8 requirement classes** |
| identity / equality used naturally | **five relations that may never substitute** |
| `(K_t,O_t) →ᴮ D_t →ᵀ K_{t+1}` | same shape, marked **MODEL / HYPOTHESIS** |
| — | an **epistemic status vocabulary** on every claim |

## Overall result

> `[EXP]` **The single most consequential change is three-valued `Sat`: it makes v1.1's sentence
> "`Zero ⟺ Δ_t = ∅`" ambiguous. That one sentence names three different predicates, and they
> disagree on the most important case.**

## The five results

**1. The three `Zero` readings disagree — on a fully determined state.** `[EXP]` `ESTABLISHED`

| case | `Zero_strict` | `Zero_weak` | Kleene `Zero` | violated | undetermined |
|---|---|---|---|---|---|
| **determined & corroborated** | **false** | **true** | **U** | — | `rg, rt, ro` |
| underdetermined | false | false | ⊥ | `rs` | 6 |
| no evidence | false | false | ⊥ | `rs` | 6 |
| weak evidence | false | false | ⊥ | `rs` | 5 |

A state where everything the agent *could* determine **is** determined and corroborated is
**not Zero** (strict), **is Zero** (weak), and **undetermined** (Kleene). The three undetermined
requirements are `governance`, `temporal` and `operational` — **exactly the three classes v1.2 itself
marks open** (no authority, no temporal semantics, `δ` undefined).

> **`Zero`'s evaluability is gated by the theory's own open items.** It cannot be computed until
> governance, temporal and operation semantics are closed — which is `Step 261`'s meta-gate,
> reappearing from a completely independent direction.

**2. Of `Zero`'s seven candidate readings, two are refuted.** `[EXP]`

| reading | verdict |
|---|---|
| state | **REFUTED** — Zero is computed from `(K, Req)`; no `K` carries it |
| **missingness representation** | **REFUTED** — all four unknown kinds collapse to `U`; **Zero is coarser than the missingness taxonomy** |
| relation | PARTIAL — contract-relative, not state-to-state |
| predicate | SUPPORTED — but arity 2 and **value set contested**: `{⊤,⊥}` or `{⊤,⊥,U}` |
| boundary | SUPPORTED **only under three-valued Sat** — two-valued `Sat` collapses absence into negation |
| derived view | SUPPORTED |
| metaphor | NOT REFUTED — Zero may be a *name* for a derived view; no outcome depends on the name |

**3. The five relations form an implication order — so the blanket prohibition is too strong.** `[EXP]`

13 of 20 ordered pairs are separable by an explicit witness. The 7 that are not form a refinement
order:

```
struct_eq (=)  ⟹  prov_equiv (≅_λ)  ⟹  sem_equiv (≡)  ⟹  obs_equiv (≈)
struct_eq (=)  ⟹  identity
```

v1.2 says *"no one of these may silently substitute for another."* The experiment refines this:
**substitution is illegitimate in 13 of 20 directions and legitimate in 7** — and the legitimate ones
are exactly the implications of the order. **The prohibition should be directional, not blanket.**

**But the order is λ-relative.** `sem ⟹ obs` holds for `λ=(status,value)` and `λ=(A)`, and **fails**
for `λ` containing `provenance` or `weight`. So the lattice moves with the observation window —
which is v1.2's own point that `≈` is parameterizable, now with a witness.

**4. Both v1.1 failures persist — and v1.2 makes one of them *better reported*.** `[EXP]` `[NEG]`

* **CE-1 factivity: unchanged.** `attributed os = RHEL9.8`, truth `RHEL8.6`. v1.2 §9 *downgrades*
  `E_t ≠ K_t` to a research distinction and adds no factivity mechanism, so the witness is untouched.
  If anything the claim is now *less* supported than in v1.1.
* **CE-3 revision: persists**, `A = {RHEL9.8, RHEL8.6}` underdetermined. v1.2 §VIII **names**
  revision/supersession/contradiction/retraction/history as a section but defines no retirement
  relation. Structure named; semantics `TECHNICALLY OPEN`.
* **Diagnostic gain:** what v1.1 reported as one undifferentiated "gap", v1.2 splits into
  **1 violated (`rs`) + 6 undetermined**. The failure is identical; the *report* is strictly more
  informative.

**5. Results are hostage to the OPEN observation concept.** `[EXP]`

| observation model | determination | independent sources | attributed | `Zero_weak` |
|---|---|---|---|---|
| candidate `(x,t,c,s)` | unique | 2 | yes | true |
| **drop `s` (source)** | **cannot-determine** | **1** | **no** | **false** |
| drop `c` (context) | unique | 2 | yes | true |

Dropping the **source** component collapses corroboration and flips every downstream result.
Dropping **context** changes nothing here — only because the token happened to be unambiguous.

> **Every corroboration-dependent conclusion in this programme rests on a component of a concept
> v1.2 declares OPEN.**

## Major unresolved questions

1. Which `Zero` reading is the theory's? A **`NORMATIVE`** decision, not an experimental one.
2. Is `Zero` a predicate at all, or just a name for `GapPartition(K, Req)`?
3. What retires evidence? (CE-3, still open across two theory versions)
4. Factivity (CE-1) — v1.2 did not address it and weakened its premise.
