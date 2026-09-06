---
artifact: D285-1 · STATE ONTOLOGY MATRIX
status: RESEARCH · all definitions reconstructed from primary sources
format: 7-section D285-x template (HPA review, 2026-08-31) · equality specified per the frozen protocol
---

# D285-1 · State Ontology Matrix

## 1. Every competing definition of `K`

| # | Formulation | Source | Authority | Primitives |
|---|---|---|---|---|
| K-1 | **`K_t` = state over 8 primitives** `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` | `step-049`; C-022 in `claim-registry` | **RATIFIED** — FA-4 D-FA-6 *"qualified naming"*; *"50 attack classes, no counterexample"*, **COMPUTATIONALLY TESTED** | 8 |
| K-2 | **`K = (𝒜, ℛ)`** | verification lane | **NOT RATIFIED** — 0 occurrences in any ratified artifact (GN-75) | 2 |
| K-3 | `K = (A, R, Σ, E_L)` | Step 272B/273 | research; `Authority: HPA` self-attested, **no ledger entry** | 4 |
| K-4 | `K_t` 10-tuple | `…complete-mathematical-model` | non-authoritative; superseded by ~120 steps | 10 |
| K-5 | `K(t) = M(t) + iA(t)` complex | ALT-09 | **REJECTED** (FA-4) | — |
| K-6 | `𝒦 = (K, H)` | provenance track | research | 2 layers |
| K-7 | `Zero(K_t) = Ω \ Represented(K_t)` | Zero lens | research; presupposes `Ω`, `D_t`, `Z_t` | — |

**Measured: ~28 distinct `K` definitions across the corpus in 7 shape-families.** The seven above are
the ones with a live claim.

## 2. Primitive-level comparison — executed

```
ratified K_t (8) : Action · Entity · Event · Observation · Policy · Proposition · Relation · State
verification (2) : Assertion · Relation
  ...Assertion expands to: Proposition · Entity · Evidence · Context · Time · Provenance

SHARED            (3): Entity · Proposition · Relation
RATIFIED-ONLY     (5): Action · Event · Observation · Policy · State
VERIFICATION-ONLY (4): Context · Evidence · Provenance · Time
```

**The five ratified-only primitives split in two, and the split is the whole finding:**

| Ratified-only | Verification lane's own position |
|---|---|
| `Action`, `Event`, `Policy` | **declared EXTERNAL to `K`** — a deliberate exclusion |
| `Observation`, `State` | ~~simply ABSENT~~ → **REVISED 2026-08-31**: `Observation` is **not** absent from the corpus — it is the **Sañjaya layer** (`W --Ω--> O`, 2026-08-26). It is absent only from the *verification lane's* import. `State`-as-primitive remains unimported |

> ⚠️ **NARROWED (reviewer A, 2026-08-31).** Previously: *"the lanes do not disagree… they agree."* **Too
> strong.** What is established is that the verification lane **declares** them external to its `K`;
> that does **not** establish it shares the ratified architecture's *semantic interpretation* of their
> externality. **Restated:** *the lanes agree **operationally** that `Action/Event/Policy` are outside
> the verification lane's epistemic `K`; this does not establish semantic equivalence of their
> treatment across the two models.* — the equality discipline, applied to a prose claim.
>
> They **do** differ on `Observation`, which the ratified model has as a primitive and the
> verification lane never introduced.** That asymmetry is the load-bearing one, and it is the same
> hole that makes `Qualify` unimplementable.

## 3. The vocabulary measurement that frames everything

`CORPUS`, GN-75 (2026-08-31), measured over ratified artifacts (`v0.2` AUTHORIZED; FA-1/3/6 RATIFIED):

> **`𝒜 · ℛ · Σ · Q_t · 𝒪 · Provenance · Replay · Measurement` = 0 occurrences each.**
> **Exactly one construct on the registry carries an explicit governance act: `Policy`.**

**This is not evidence that the lanes conflict. It is evidence that they have never been placed in a
shared vocabulary** — which is why Blocker 1 could persist unresolved for so long.

## 4. What `Knowledge` is not

`CORPUS`, `concern-map.md`: **`Knowledge` is absent from the 8 primitives while `Observation` and
`Proposition` are present.**

> The ratified model deliberately does **not** name `Knowledge` a primitive. `K_t` is therefore
> **not** "the knowledge state" in the verification lane's sense — it is a **state over a
> knowledge-bearing vocabulary**. **Two different objects wearing one letter.**

---

## Template conformance (HPA mandate, 2026-08-31)

### 1 · Property Statement
The two lanes' `K` formulations differ at primitive level: 8 primitives vs 2, with 3 shared **by name**.

### 2 · Trivial vs Substantive
**Trivial:** that the two lists differ. **Substantive:** *how* they differ — and whether name-sharing implies concept-sharing.

### 4 · Qualification
The matrix compares **primitive names**. It does **not** establish that equally-named primitives denote the same concept.

### 5 · Equality Specification ⭐
**Set equality on primitive NAMES.** ⚠️ **This is a weak relation, and naming it exposes a limit.** GN-75 measured `𝒜·ℛ·Σ·Q_t·𝒪` at **0 occurrences** in ratified artifacts — the lanes **do not share vocabulary**. So the 3 "shared" primitives are shared *by name*; **conceptual identity is a separate, untested claim.** The matrix therefore establishes *vocabulary disjointness*, not *conceptual* disjointness.

### 6 · Independence
**No Gītā input.** Pure measurement over FA-4, `claim-registry` C-022 and `concern-map`. `INDEPENDENT`.

### 7 · Classification (7-way, per the frozen protocol)
**mathematical:** `R5` on the name-level comparison · **architectural:** identifies the ratified/verification split · **DDD:** none · **analogy:** none · **corroboration:** none · **unresolved:** ⚠️ **conceptual identity of the 3 shared names** · **governance:** none
