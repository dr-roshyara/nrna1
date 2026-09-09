# THEORY CHRONICLE — append-only

Earlier interpretations are never overwritten. Semantic evolution is appended.
Object identity is **semantic role + typing**, never spelling.

`[EMP]` in corpus · `[DERIVED]` · `[PROPOSED]` · `[RE-DERIVATION]` · `[RETROFIT]`
`[REFUTED]` · `[WITHDRAWN]` · `[SUPERSEDED]` · `[CONTRADICTED]` · `[UNWITNESSED]` · `[OPEN]`

---

## CHRONICLE-001 · `Sat` — the satisfaction construct

| # | when | doc §  | exact form | type / codomain | event | status |
|---|---|---|---|---|---|---|
| 1 | 08-27 16:25 | `023` §10 | `Sat(K, r_i)` | domain `KnowledgeState × Requirement`; codomain **ambiguous** — §10 says *"the degree/status"*, §9 supplies a 5-valued `Status(r_i) ∈ {Satisfied, Unsatisfied, Unknown, Conflicted, NotApplicable}`, §59 uses `Sat_i` numerically in a weighted mean | `INTRODUCE` + `DEFINE` | `[PROPOSED]` |
| 2 | 08-27 16:26 | `025` | `Sat(K_0, R_i)` · `Sat(K_1, Backup) = True` | used as a **Boolean** in an executable simulation | `TEST` | `[EMP]` |
| 3 | 08-27 18:31 | `025d` §25D.4 | **`Sat(K_t, r_i)`** | ⭐ **time-indexed `K_t` appears**; codomain `𝒮` = **9 values** `{Satisfied, PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid, Prohibited, NotApplicable}` | `REFINE` + `RETYPE` | `[EMP]` |
| 4 | 08-27 18:31 | `025d` §25D.5 | **`Sat ≠ Boolean`** (boxed) | six operationally distinct readings of `Sat=False` enumerated | `REFUTE` of entry 2 | `[REFUTED]` |
| 5 | 08-27 18:31 | `025d` §25D.7 | `Missing` added, distinct from `Unknown` | codomain → **10 values** | `REFINE` | `[EMP]` |
| 6 | 08-27 18:31 | `025d` §25D.11–12 | **`Satisfied(K, r, EC)`** | ⭐ **arity 3** — the contract is an argument; *"`Satisfied = ContractSpecific`"*; may dispatch to `RuleEngine` / `StatisticalCriterion` / `HumanAuthorization` | `RETYPE` | `[EMP]` |
| 7 | 08-30 | — | `Sat` **absent from `phase_measure_theory/` for three days** | — | silence | `[EMP]` |
| 8 | 09-02 | `math_ideas` batch | `Sat(K_t, r)` · *"three-valued sat predicate"* | **arity 2 — `EC` argument LOST**; codomain **3 values** | `RE-ENTRY` | `[EMP]`; bridge `[UNWITNESSED]` |

**Codomain cardinality history: 5 → (Boolean) → 9 → 10 → 3.**
**Arity history: 2 → 3 → 2.**

> ⛔ `Sat` is born **under-determined** and every later codomain dispute continues an ambiguity
> present in its defining sentence. The 09-02 three-valued reading is **not** a narrowing of the
> nine-valued `𝒮` by any witnessed argument.

---

## CHRONICLE-002 · `Requirements` / `R` / `ℛ` — the requirement basis

| # | when | doc | exact form | type | event | status |
|---|---|---|---|---|---|---|
| 1 | **08-25 22:29** | session-1-not-executing-continuously | `⋂_{R ∈ Regimes} Requirements(R)` | function → **set** (it is intersected); parameter = a *regime* | `INTRODUCE` | `[EMP]` |
| 2 | 08-26 18:02 | question-11-what-is-the-ideal-state | `I^D ⇒ Requirements(I^K)` · `I^K ⇒ Requirements(I^U)` | function; parameter = an *ideal-state layer* | `REFINE` | `[EMP]` |
| 3 | 08-27 15:37 | `013` | `Satisfies`, `Sufficiency`, `Ready`, `Coverage`, `Criticality` | vocabulary assembles | `INTRODUCE` | `[EMP]` |
| 4 | 08-27 16:25 | `023` §5 | **`ℛ(P)`** = *"the relevant information requirements for purpose `P`"* | **set parameterized by PURPOSE** | `INTRODUCE` | `[EMP]` |
| 5 | 08-27 16:25 | `023` §8, §49 | `R(P) = {r_1,…,r_n}`; `KnowledgeRequirement` = 8-field domain object with `Criticality`, `SatisfactionState` | set + object | `REFINE` | `[EMP]` |
| 6 | 08-27 18:31 | `025d` §25D.2 | `R_G = {r_1,…,r_n}` | set parameterized by **GOAL `G`**, not purpose | `RETYPE` (parameter changes) | `[EMP]` |
| 7 | 08-27 18:33 | `025e` §25E.14 | five requirement **kinds**: Knowledge · Evidence · Validation · Governance · Operational | typed partition | `SPECIALIZE` | `[EMP]` |
| 8 | 08-30 21:59 | `276-final` B §276.1 | `Requirements(K)` | parameter = the **knowledge state** | `RE-ENTRY` | `[EMP]` |
| 9 | 08-30 22:01 | `277` §277.30 | `R_mandatory` | elements are **transitions** | `RETYPE` | `[EMP]` |
| 10 | 08-30 22:42 | `272a` §272A.16–17 | `D_mandatory`; 19 `⟨distinction, operation⟩` pairs | elements are **capabilities**, operation-indexed | `RETYPE` | `[EMP]` |
| 11 | 09-02 18:20 | `182016` | `ℛ_req ⊆ 𝒟`, 12 elements | elements are **equivalence relations `∼_d`**; operation index **gone** | `RETYPE` | `[EMP]` |

> ⛔ The parameter changes four times — **regime → ideal-layer → purpose → goal → knowledge-state →
> question+context** — and the element type changes four times — **requirement → transition →
> capability → equivalence relation.** No document performs any of these conversions.

---

## CHRONICLE-003 · `EC` — the Epistemic Contract

| # | when | doc § | form | arity |
|---|---|---|---|---|
| 1 | 08-27 16:25 | `023` §14 | `(Purpose, Requirements, EvidenceRules, UncertaintyLimits, ConflictRules, TemporalRules, AuthorityRules)` | **7** |
| 2 | 08-27 16:25 | `023` §48 | DDD object, 9 fields | **9** |
| 3 | 08-27 18:31 | `025d` §25D.3 | **`EC_G = (R_G, Γ_G)`** | **2** |
| 4 | 08-27 18:31 | `025d` §25D.34 | **`Z_t = Zero(K_t, EC_t)`** — ⭐ `EC_t` born | — |
| 5 | 08-27 18:33 | `025e` §25E.5 | `EC = (R, Γ, A, V)` | **4** |
| 6 | 08-27 18:33 | `025e` §25E.37 | `(Requirements, Rules, Authority, Scope, Time, Dependencies, Exceptions, Provenance, Version)` | **9** |
| 7 | 09-01 22:50 | `math_ideas` | `EC_t` | index's first hit — **five days late** |

`025e` §25E.4: **`EC ∈ KnowledgeState`** — *"the system needs knowledge to define what knowledge is
required."* `[EMP]` A reflexivity result, not yet reconciled with `EC` as an external parameter.
⛔ **`EC` is redefined twice inside `025e` alone** (§25E.5 arity 2→4, §25E.37 arity 9).

---

## CHRONICLE-004 · `Γ` — ⛔ a glyph that swapped meaning

| when | doc § | `Γ` denotes |
|---|---|---|
| **08-27 18:31** | `025d` §25D.3 | **rules determining SUFFICIENCY** — `EC_G = (R_G, Γ_G)` |
| 08-27 18:33 | `025e` §25E.5 | **SATISFACTION RULES** — `EC = (R, Γ, A, V)` |
| 09-02 18:20 | `182019` etc. | **CONTEXT** — `ℛ_req(Q, Γ)`, `Adequacy(R, Q, Γ)` |

At 08-27 *context* is written **`C`** or **`Ctx`**, never `Γ`:
`EvalRequirement(K, r, C)` · `Applicable(r, Ctx)` · `r | Context` (`025e` §25E.26–27).

$$\boxed{\Gamma:\ \textbf{sufficiency rules (08-27)} \longrightarrow \textbf{context (09-02)} \qquad [\textbf{UNWITNESSED}]}$$

⚠️ This is more dangerous than an ordinary collision because **`Γ` sits beside a requirements set in
both eras**: `(R_G, Γ_G)` and `ℛ_req(Q, Γ)`. A reader will read the later pair through the earlier
one and conclude `Γ` still carries the satisfaction rules. **It does not.**

---

## CHRONICLE-005 · `Zero` — the discrepancy operator

| when | doc § | result | status |
|---|---|---|---|
| 08-27 18:31 | `025d` §25D.1 | `Zero ≠ simple subtraction` | `[EMP]` |
| 08-27 18:31 | `025d` §25D.6 | `Z_i = Status(K_t, r_i, EC_G)`; `Zero = {(r_i, Z_i)}` — a **Zero vector** | `[EMP]` |
| 08-27 18:31 | `025d` §25D.16 | `Zero = (Z_world, Z_knowledge, Z_governance, Z_decision)` | `[EMP]` |
| 08-27 18:31 | `025d` §25D.17 | ⭐ **`Zero` is a structured object, NOT a scalar** — *"`Z_knowledge`=5 and `Z_governance`=1 … Can we say `Zero`=6? **No.**"* | `[DERIVED]` |
| 08-27 18:31 | `025d` §25D.32 | ⭐ all four metric axioms tested and **rejected**; renamed *"directed epistemic discrepancy operator"* | `[DERIVED]` |
| 08-27 18:31 | `025d` §25D.37 | `Z_t = ⨁_{r∈R_G} Evaluate(K_t, r, EC_G)`, ⊕ = **structured collection, not numerical addition** | `[EMP]` |
| 08-27 18:31 | `025d` §25D.18 | metrics such as `Coverage`, `WeightedGapScore` are **projections of Zero, not Zero** | `[EMP]` |
| 08-27 18:31 | `025d` §25D.28 | ⭐ **`Zero` must not invent gaps** — compares only against derived requirements, not AI expectation | `[EMP]` |

### ⛔ CANDIDATE CONTRADICTION `C-1` — scalarisation

```
08-27 025d §25D.17-18, §25D.32   the gap MUST NOT be a scalar; metric axioms fail; weighted
                                  scores are projections, never the object
09-02                             Loss_{ℛ_req}(π) = Σ_i w_i · 𝟙(Collapse(d_i, π))     a SCALAR
```

**Not asserted as a contradiction.** The objects differ — `Zero` ranges over *requirements*,
`Loss` over *distinctions* — so this is the same architectural question answered oppositely in two
lanes that do not cite each other. **`[OPEN]`.** Retained unadjudicated per the branch rule.

⚠️ It also bears on `023` §59's own `Coverage = Σ wᵢ Satᵢ / Σ wᵢ`: `025d` demotes that very
functional to "a projection" **two hours after `023` introduced it.**

---

## CHRONICLE-006 · the four-valued epistemic status

| when | doc § | form | event |
|---|---|---|---|
| **08-27 15:20** | `009` §8 | `𝔹 = {00,10,01,11}` from *P supported* × *¬P supported*; rows Unknown / Supported true-side / Supported false-side / Conflicted | **`INTRODUCE`** |
| 08-27 15:20 | `009` §9 | `Four-valued epistemic status ≠ Four-valued truth` | `[EMP]` |
| 08-27 15:20 | `009` §10 | `Σ_A = (Acquisition, Support, Uncertainty, Validity)` — a **4-tuple, NOT the 4-valued lattice** | ⛔ distinct object, same document |
| 08-30 22:50 | `272b` §272A.12/.20 | `Σ₀ ≅ {0,1}²`, identical rows, identical caveat, **no citation of `009`** | **`RE-DERIVATION`** |

Established in `P-97`. `272b`'s deletion-test proof is new; **the object is three days old.**

---

## CHRONICLE-007 · notation collisions — register

| glyph | meanings | earliest |
|---|---|---|
| `Sat` | ① satisfaction `Sat(K,r)` ② **Sanskrit Vedānta** *being/reality* (`Sat`/`Asat`/`Mithya`) | ② 08-26, ① 08-27 → `EKS-43` |
| `ℛ` | ① requirements-for-purpose `ℛ(P)` ② representation functions ③ `Req(EC_t)` ④ `ℛ_req(Q,Γ)` | ① 08-27 → `EKS-41` App. B |
| `Γ` | ① sufficiency rules ② satisfaction rules ③ context | ① 08-27 → **new, CHRONICLE-004** |
| `R` | ① requirement set `R(P)`/`R_G` ② a Regime ③ refutation bit in `(S,R)` | ② 08-25 |
| `Σ` | ① 4-valued lattice `𝔹` ② `Σ_A` 4-tuple ③ `(A,S,R,V,C)` 5-tuple ④ `Σ₀ ≅ {0,1}²` ⑤ `Direction × Strength` as executed | ① 08-27 |
| `Π` / `π` | provenance · Policy · policy-as-set | 08-30 |
