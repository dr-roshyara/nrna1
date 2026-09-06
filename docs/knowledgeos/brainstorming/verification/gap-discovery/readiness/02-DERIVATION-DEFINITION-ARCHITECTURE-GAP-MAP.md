# 02 — Derivation · Definition · Architecture Gap Map

Answers mandate **§10** (is KnowledgeOS an architecture?), **§11** (what is genuinely derived?),
**§12** (is there exactly one usable definition?).

---

## §10 — Is KnowledgeOS a coherent architecture?

$$\boxed{\textbf{No. It is TWO bodies of work with one construct in common.}}$$

**Verified, C1:** the eight central verification-lane symbols — `𝒜 ℛ Σ Q_t 𝒪 Provenance Replay
Measurement` — have **0 occurrences each** across the entire 52,353-character ratified surface.

| | Lane A — **ratified architecture** | Lane B — **verification-lane formal theory** |
|---|---|---|
| State | `K_t` over **8 primitives** | `K = (𝒜, ℛ)` |
| Content | 41 invariants · 17 primitives · 39 authority refs · 7 prohibitions | 25 constructs · 22 executable tests · 9 with L5 evidence |
| Operation signatures | **0** (C2) | 19 candidate ops in 5 families (272A); **6 rival minimal registries** (GN-84) |
| Postconditions | **0** (C3) | in the reference implementations only |
| Governance acts | the lane *is* the governed record | **1** construct (Policy) |
| Shared vocabulary | — | **∅** |

**GN-75, verbatim:** *"The ratified `K_t` (state over the 8 primitives) is **a different object** from
the verification lane's `K=(𝒜,ℛ)`."*

### The precise architectural defect

The two lanes are **not inconsistent** — they are **disjoint**. There is no contradiction to
adjudicate because there is no shared term to disagree about. That is worse than a contradiction: a
contradiction is detectable by the lint, a disjunction is not.

$$\boxed{\text{The ratified canon says when a transition is ILLEGAL (41 invariants, 7 prohibitions). It never says what a transition IS (0 signatures, 0 postconditions).}}$$

**Classification: `A` — ARCHITECTURE GAP, on 16 of 25 constructs. `BLOCKED — REQUIRES GOVERNANCE.`**

---

## §11 — What is genuinely derived?

**Rule applied:** no `FORMALLY-DERIVED` status assigned because an artifact says "derived". Each row
below is checked against the artifact's own stated basis.

### Provenance graph

```
FIRST PRINCIPLES
  │
  ├─ Congruence criterion (259.15)            FORMALLY-DERIVED   proof in-artifact
  ├─ Provenance t=0-safe (265.2)              FORMALLY-DERIVED   base-case argument, re-executed
  ├─ Σ ≅ {0,1}² (272B)                        FORMALLY-DERIVED*  *falsification-tested, minimality NOT proven
  ├─ Σ derived-not-stored (retract/OR-merge)  FORMALLY-DERIVED   in GN-75 record
  ├─ Missingness outside Σ (281)              FORMALLY-DERIVED   executed 7/7, GN-75
  ├─ Q_t minimal repair (281)                 COMPUTATIONALLY-VERIFIED  Level 4 only, self-certified
  ├─ Sufficient(K,𝒪,ℐ) (this lane)            FORMALLY-DERIVED   conjunct-independence executed
  │
INHERITED / RECONSTRUCTED
  ├─ 5-level strength scale                   PROPOSED  — inherited from Q14, never derived (G-61)
  ├─ 8 primitives (v0.2)                      RATIFIED  — governance act, not a derivation
  ├─ 14-forced/18-upper bound                 **WITHDRAWN** — AF-F-33: PROPOSED, not DERIVED
  │
GOVERNANCE-DECIDED
  ├─ Policy stratification I-11 + R-1         RATIFIED  GN-19, 2026-08-28
  ├─ Authority exogenous (187.29)             NORMATIVE — a stipulation, implemented at 132/132
  ├─ T-3 probability not required             NORMATIVE — Step 282 ruling, no governance act
  │
NOT DERIVED, NOT RATIFIED, IN USE
  └─ 𝒪_core membership                        **OPEN** — GN-84: exists, NOT unique, NOT selectable, NOT ratified
```

**The load-bearing observation:** the items with the strongest derivations (`Σ`, `Q_t`, missingness,
`Sufficient`) are **all in Lane B**, and Lane B has **one governance act**. The items with governance
force (8 primitives, Policy stratification) are **all in Lane A**, and Lane A has **0 operation
signatures**.

$$\boxed{\text{Derivation strength and governance force are in disjoint lanes.}}$$

---

## §12 — Is there exactly one usable definition?

| Construct | Definitions in play | One usable? |
|---|---|---|
| **Knowledge** | not defined; **not required** — no operation needs it | **N/A** — correctly out |
| **K** | ratified `K_t`/8 primitives · `K=(𝒜,ℛ)` · `K=(D_t,𝒜,ℛ,Σ_c,E_L)` | **NO — 3** |
| **Proposition** | `(E,D,V)` · `(S,ρ,O,Γ)` | **NO — 2** (D-6) |
| **Assertion** | `(id,P,e,c,t,Π)` | **YES** |
| **Evidence** | 9-field tuple; **no identity**, claim index `q` dropped | **NO** — TG-08 |
| **Assessment** | `P×Evidence×Context×Policy→Σ` · `f(Evidence,ClaimType,Model,Assumptions)` | **NO — 2** TG-13 |
| **Σ** | `(D,S)` D≅{0,1}² · `Σ=(D,S)` 3×5 (275) · 5-dim 2240-state (Q14) | **NO — 3**, and **blind to `ℛ`** TG-10 |
| **Q_t** | `Q_t ⊆ P` | **YES** — but `unask` open |
| **Relationship `ℛ`** | 3-field `𝒜×Type×𝒜` · corpus 8-tuple n-ary | **NO — 2** (D-5) |
| **Lineage** | `Π ∘ ℛ_der*` | **YES** |
| **History** | `𝕂 → Histories`, external | **YES** |
| **Policy** | `(id,ver,Gates,Validity,Resolution)` | **YES + RATIFIED** |
| **Authority** | actor competence · standing · trust-grade · **4th sense** TG-07 | **NO — 4** |
| **Authorization** | `c_t = Authorize(N_t,a_t,Policy_t)` | **YES** — runtime absent |
| **Determination** | `(conclusion,method,context,rationale,inputRefs)` · 8-field aggregate (157.22) | **NO — 2** |
| **Contradiction** | evidential vs relational — **an open canonical question** (GN-84) | **NO** |
| **Missingness** | 7 states via `Q_t` | **YES** |
| **Replay** | `fold(T,∅,H)` | **YES** — depends on `T` |
| **Supersession** | relation · lifecycle status | **NO — 2** |
| **Operation** | 19 candidates / 5 families · 6 rival minimal registries | **NO — 6** |
| **Transformation** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome`; **`δ` has no body** TG-09 | **NO — 0 usable** |

### Count of constructs with exactly one usable definition

**8 of 21**: Assertion · Q_t · Lineage · History · Policy · Authorization · Missingness · Replay.

**And of those 8, only Policy carries a governance act.**

### Undefined symbols still outstanding

`ℐ` (inferential procedure) · `𝒩` (Knower space) — TG-register: *"undefined symbols the 30-symbol
audit never enumerated"*; the handoff searched and **found no occurrences**. Recorded as **reported,
not independently confirmed.**

Plus `ℐ` in *this lane's* sense — **the mandated invariant set** (`12-SUFFICIENCY-DEFINITION` G-67) —
**never enumerated.** *(Name collision: two different `ℐ`. Recorded, not resolved.)*

---

## Blocking classification

| Gap | Class | Block type |
|---|---|---|
| Two rival `K`; no shared vocabulary | **A** | **BLOCKED — REQUIRES GOVERNANCE** |
| `𝒪_core` membership | **O** | **BLOCKED — REQUIRES GOVERNANCE** (GN-79 in flight) |
| `δ` has no body; 0 postconditions in canon | **T** | **BLOCKED — REQUIRES DERIVATION** |
| `Qualify` has no body | **D** | **BLOCKED — REQUIRES DERIVATION** |
| `Σ.str` has no rule; Σ blind to `ℛ` | **D/S** | **BLOCKED — REQUIRES DERIVATION** |
| `id` hashes a mutable field | **D** | **BLOCKED — REQUIRES DERIVATION** |
| Evidence has no identity | **D** | **BLOCKED — REQUIRES DERIVATION** |
| `ℐ` never enumerated | **D** | **BLOCKED — REQUIRES DERIVATION** |
| GC-1 policy-loop | **G** | **BLOCKED — REQUIRES GOVERNANCE** |
| `Authorize` runtime · measurement executor | **I** | **BLOCKED — REQUIRES IMPLEMENTATION** |
| Q_t, Missingness, 16 constructs | **EC** | **BLOCKED — REQUIRES EMPIRICAL OBSERVATION** |
