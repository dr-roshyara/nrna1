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

---
# BATCH 003 — `025f` (08-27 18:35) and `025k` (08-28 09:39) read completely; `025g–025z` surveyed mechanically

## CHRONICLE-003 continued · `EC` gains a FIFTH arity

| # | when | doc § | form | arity |
|---|---|---|---|---|
| 8 | 08-27 18:35 | `025f` head | **`EC = (R, Γ, A, S, T, D, X, V)`** — requirements, rules, authority, scope, time, dependencies, exceptions, version | **8** |

⛔ `025e` §25E.37 gave **9** components *two minutes earlier*; `025f` gives **8**. **`Provenance` is
dropped, silently.** `EC` arity history: **7 → 2 → 4 → 9 → 8**, all within 2 h 10 min, and the
document that drops a component does not mention doing so. `[EMP]`

## CHRONICLE-008 · `⪰` — ⭐ three types, all born in this series (the historical root of `CR-2`)

| when | doc § | form | ranges over |
|---|---|---|---|
| 08-27 18:35 | `025f` §25F.14 | **`s₁ ⪰_C s₂`** | **authority sources**, *"context-dependent"*, explicitly a **partial order**, explicitly *not* a linear hierarchy |
| 08-28 09:23 | `025h` | `d₂ ⪰ d₁` | **decisions** |
| 08-28 09:38 | `025j` | `A₂ ⪰ A₁` | **assertions** |

`[EMP]` The open conflict record `CR-2` states that `⪰` has *"three readings of different types"* and
that *"two independent paths must stay independent."* **All three readings are now located, dated,
and shown to be born within 19 hours of each other.** `CR-2` is a live consequence of this series;
it is not a later confusion. **No merge performed** — recorded as three objects.

## CHRONICLE-009 · the conflict predicate — an early arity-4 witness

| when | doc § | form |
|---|---|---|
| 08-27 18:35 | `025f` §25F.6 | **`Conflict(s₁, s₂, C, t)`** — over **sources**, gated by `Applicable(s,C,t)` on both |
| 08-27 18:35 | `025f` §25F.2 | **`Conflict ≠ Error`** — seven legitimate causes enumerated |
| 08-27 18:35 | `025f` §25F.8 | `HistoricalConflict ≠ CurrentConflict` |
| 08-28 09:39 | `025k` §25K.10–11 | `UnresolvedConflict → UnresolvedConflict`; **`NoImplicitConflictResolution`** |
| 08-28 10:09 | `025u` | `Conflict = FirstClassKnowledgeState`; `Resolution = (Conflict, Method, Evidence, Decision, Authority, ResidualUncertainty)` |

`[EMP]` `CR-1` lists **seven** signatures for `Contr`. This is an eighth shape and the earliest —
and it ranges over **governance sources**, not propositions. Recorded, **not merged** with `Contr`.

## CHRONICLE-010 · `Zero` continued · governance resolution

`025f` §25F.16: `GovernanceResolve(C, S, t) → GR = (EffectiveRules, Conflicts, Exceptions, Supersessions, UnresolvedItems)`.
§25F.20: three outcomes `{Resolved, Unresolved, Invalid}`.
§25F.18–19 ⭐ **`UnresolvedGovernanceConflict → HumanGovernance`**, and the principle:

> *"Knowing that something cannot be determined is itself a valid computed result."*

`025z` (08-28 10:13): `Zero = Insufficient epistemic basis for the next permitted action`.
`025v` (10:10): `Zero_semantic = MeaningInsufficientlyDetermined`.

## CHRONICLE-011 · ⭐ `Semantic unresolved` vs `Computational unresolved`

`025f` §25F.36–37 classifies every open issue as one or the other, and concludes the programme's
problems are **overwhelmingly semantic**: *"`Satisfied(K,r)` is trivial once its predicate is
defined. The difficult part is defining the predicate correctly."* `[EMP]`

**This is the ancestor of the 08-30 closure vocabulary** (`Defined ≠ Derived ≠ Demonstrated ≠
Closed`; the `⟨FC,CC,EC,GC⟩` vector). Three days earlier, two categories instead of four.
Link `[PROPOSED]` — no citation.

⚠️ Note `025f` §25F.36 writes **`Satisfied(K, r)` — arity 2**, four minutes after `025d` §25D.12
wrote `Satisfied(K, r, EC)` — arity 3. Inconsistent inside one afternoon. `[EMP]`

## CHRONICLE-012 · `K` and the transition function — ⛔ two arguments lost by 08-30

| when | doc § | form |
|---|---|---|
| 08-28 09:39 | `025k` §25K.1 | **`K_{t+1} = Update(K_t, E_t, Ω, EC)`** — `Ω` = domain ontology/rules, `EC` = applicable contract |
| 08-28 09:39 | `025k` §25K.38 | **`T : (K, E, Ω, EC) → K'`** with six invariants |
| 08-28 09:39 | `025k` §25K.35 | **`K_t = Derive(H_{≤t}, Ω_v, EC_v, M_v)`** — the reproducibility equation, four *versioned* parameters |
| 08-30 21:59 | `276-final` §276.20 | `K_{t+1} = δ(K_t, e_t)` — **`Ω` and `EC` GONE** |

$$\boxed{T:(K,E,\Omega,EC)\to K' \;\;\longrightarrow\;\; \delta(K_t,e_t) \qquad [\textbf{UNWITNESSED}]}$$

`[EMP]` for both endpoints. **The ontology and the contract — the two things that make the
derivation reproducible and auditable — are dropped from the signature, with no document recording
the removal.** This is structurally the same failure as `Sat`'s lost `EC` argument
(`CHRONICLE-001` entry 8) and the operation index lost from `272A.17` (`P-96` §9.3): **the
parameter that supplies the semantics is the parameter that disappears.**

### Invariants established `025k` §25K.48

`✅` state transition · provenance · history · duplicate handling · conflict preservation ·
retraction · correction · expiration · model versioning · replay · deterministic derived state
`❌` **universal monotonicity · universal commutativity · universal associativity** — and §25K.49
argues the three ❌ are a *success*: `History monotonic; CurrentState non-monotonic`.

### ⚠️ An 11-component coincidence — flagged, NOT claimed

```
025k §25K.2   (08-28)  K   = (Assertions, Evidence, Provenance, Relations, Assessments,
                              Validity, TemporalState, Conflicts, Versions, Contracts, Policies)   11
276-final §276.6 (08-30) K_t = (A, R, E, Σ, H, Z, L, T, G, C, M)                                   11
persistence kernel        |K| = 11  [REC]  UNFROZEN
```

**Three unrelated-looking objects with cardinality 11.** The component *names* do not align
(`025k` has Contracts/Policies/Versions; `276-final` has H/L/Z/M), no document cites another, and
the persistence kernel is a different construct entirely.

$$\boxed{\text{Recorded as an OBSERVATION requiring verification. } [\textbf{OPEN}] \text{ — NOT a correspondence, NOT a merge.}}$$

Same-cardinality reasoning is precisely what `P-97` §4 had to be careful about for `Σ₀`, where the
match *was* real; here the internal structure does **not** match, so the burden is unmet.

## CHRONICLE-006 continued · Σ-like formulations now number EIGHT

| when | doc | form | shape |
|---|---|---|---|
| 08-27 15:20 | `009` §8 | `𝔹 = {00,10,01,11}` | 4-valued lattice |
| 08-27 15:20 | `009` §10 | `Σ_A = (Acquisition, Support, Uncertainty, Validity)` | 4-tuple |
| 08-27 16:25 | `023` §9 | `Status(r_i) ∈ {Satisfied, Unsatisfied, Unknown, Conflicted, NotApplicable}` | 5-valued |
| 08-27 18:31 | `025d` §25D.4 | `𝒮` (+`Missing` §25D.7) | 9→10-valued |
| 08-28 09:39 | `025k` §25K.20 | `𝓔 = {Unknown, Supported, Refuted, Conflicted, Expired, Retracted}` | 6-valued |
| 08-28 09:39 | `025k` §25K.21 | `EpistemicState = (SupportStatus, ValidityStatus, ConflictStatus, TemporalStatus)` | 4-dim tuple |
| 08-28 09:42 | `025o` | `EpistemicStatus = (Support, Conflict, Validity, Freshness, Authority, Uncertainty)` | 6-tuple |
| 08-30 21:59 | `276-final` | `Σ = (A,S,R,V,C)` | 5-tuple |
| 08-30 22:50 | `272b` | `Σ₀ ≅ {0,1}²` | 4-valued |
| 08-30 23:24 | `step-280/exec` | `Direction × Strength` | 4×5, executed |

⛔ **Ten distinct Σ-formulations.** `025k` §25K.20 itself refuses to call `𝓔` a lattice —
*"Some states are orthogonal dimensions"* — and proposes the 4-dimensional form instead, in the
same section. No document reconciles the ten.

## CHRONICLE-013 · typed epistemic objects, and the statistical boundary

`025k` §25K.44: **`KnowledgeState ⊃ StatisticalState`**, explicitly *not* `=`.
§25K.45: `P(H)=0.8 ⇏ H=True`, and `H=True` in a rule system `⇏ P(H)=1`.
§25K.46: `𝒦 = {Fact, Hypothesis, Observation, Assertion, Rule, Constraint, Prediction, Decision, …}`
— *"this typing prevents category errors."* `[EMP]`

---
# BATCH 004 — `025l` (08-28 09:40) and `025m` (09:41) read completely; `025n` (09:42) read partially

## CHRONICLE-014 · distributed knowledge, merge and convergence — `025l`

| § | result | class |
|---|---|---|
| 25L.7 | `H_merge = H_A ∪ H_B`; `K_merge = Derive(H_merge, Ω, EC, M)` — merge the **histories**, never the opaque states | `[EMP]` |
| 25L.6 | ⭐ **`Semantic deduplication must not mean provenance deduplication`** | `[EMP]` |
| 25L.9 | `Independent(E₁,E₂) = False` when `E₂` is a replica — *"replication is not new evidence"* | `[EMP]` |
| 25L.11 | `E₁ ≺ E₂` causal order, **stronger than** `Timestamp(E₁) < Timestamp(E₂)` | `[EMP]` |
| **25L.14** | ⭐ **convergence candidate:** `H_A=H_B ∧ Ω_A=Ω_B ∧ EC_A=EC_B ∧ M_A=M_B ⟹ Derive(…) ≡ Derive(…)` | `[PROPOSED]` — stated as a *candidate property*, not proved |
| 25L.17–18 | ⭐ **`Convergence ≠ Consensus`** — convergence is `AgreementOnEpistemicState`, **not** `AgreementOnTruth`; two nodes correctly converge on *"these conflict and no resolution exists"* | `[DERIVED]` |
| 25L.20–21 | `H₁ ⊔ H₂ = H₁ ∪ H₂` is commutative, associative, idempotent — **CRDT-like**; but `K = Derive(H)` is **not** a CRDT | `[EMP]` |
| 25L.25 | `LocalConsistency ≠ GlobalCompleteness` | `[EMP]` |
| 25L.29 | **no universal ontology** — `Ω_Infrastructure`, `Ω_Security`, `Ω_Architecture`, with explicit cross-context maps | `[EMP]` |
| 25L.30–31 | `Map : Ω_A → Ω_B` is **itself an epistemic object** needing provenance → *"Knowledge about Knowledge"* | `[EMP]` |
| 25L.32 | ⭐ independence must be derived **from provenance, not organizational ownership** — two bounded contexts can consume one source | `[EMP]` |
| 25L.44 | conditional: `Same inputs + same semantics ⟹ same derived state` — explicitly **not** *"all nodes → same knowledge"* | `[QUALIFIED]` |

⛔ **`⊔` collision:** here `⊔` is **history union** (`25L.20`); in `272b` §272A.21 it is the
**epistemic join** `σ₁ ⊔ σ₂ = (s₁∨s₂, r₁∨r₂)`. Two meanings, three days apart. Recorded, not merged.

⚠️ `KAID` — **Knowledge Meaning Identity**, distinct from record identity (`25L.4–5`), so that
independent corroboration unifies *meaning* while preserving *two records*. New tracked object;
its own birth is earlier in the series (`025i`/`025s`) and is **not yet read**.

## CHRONICLE-015 · revision, error and non-monotonicity — `025m`

$$\boxed{RevisionType = \{Evolution,\ Correction,\ Retraction,\ Refutation,\ Reinterpretation,\ ModelRevision\}}$$

Six kinds of "wrong", each with a worked case and a distinct required response. `[EMP]`

| § | distinction | class |
|---|---|---|
| 25M.2 | `Evolution ≠ Correction` — the old assertion **was true when made** | `[EMP]` |
| 25M.4 | ⭐ `Retraction ≠ Refutation` — *"losing support for a hypothesis is not proving it false"* | `[EMP]` |
| 25M.6 | `ObservationValid ∧ AssertionInvalid` — the command ran against the wrong host | `[EMP]` |
| 25M.7–8 | `Ω₁ → Ω₂` changes `K` with `H` unchanged ⟹ **`Interpretation is versioned`** | `[EMP]` |
| 25M.9 | **`Revision = EventAddition + StateReDerivation`**, never destructive update | `[EMP]` |
| 25M.11 | `Assertion ≠ Assessment` — method unreliability weakens support without refuting | `[EMP]` |
| 25M.12 | ⭐ **`Probability revision ≠ Logical refutation`** — `P(H\|E₁)=0.95 → P(H\|E₁,E₂)=0.40` refutes nothing; *"the system must not invent priors"* | `[EMP]` |
| 25M.14 | **`Revision propagation = Support recomputation, NOT cascading deletion`** | `[EMP]` |
| 25M.15 | `RF(E)` — the **revision frontier**, giving incremental recomputation | `[EMP]` |
| 25M.17 | ⭐ **`Zero is dynamic`** — a satisfied requirement can become unsatisfied when evidence is invalidated | `[EMP]` |
| 25M.18–19 | a decision becomes `DecisionAffected`, **never auto-reversed**; `DecisionStatus = {Valid, Superseded, Questioned, Invalidated, Executed, Reversed}` | `[EMP]` |
| 25M.23 | four dimensions: **World change · Knowledge change · Assessment change · Model change** | `[EMP]` |

## CHRONICLE-016 · evidence aggregation — `025n` `READ-PARTIAL` (§1–15, §26–28, §44–46)

$$E = (Content, Source, Provenance, Method, Time, Context, Reliability, Independence, Authority, Uncertainty)$$

A **ten-component** evidence object (`25N.1`), introduced against the explicit anti-pattern
*"`confidence = 0.87`, then add them up — that has no generally valid statistical interpretation."*

⭐ **§25N.2 — six concepts that must remain separate:**

$$\boxed{Support \;\neq\; Reliability \;\neq\; Authority \;\neq\; Probability \;\neq\; Confidence \;\neq\; Independence}$$

⚠️ **This is very likely what the forward programme plan records as `TG-02`: "independence defined
as a six-component vector."** It is not a six-component vector — it is **six concepts held apart**,
with `Independence` one *field* of a ten-component evidence object. Recorded as a correction
candidate for the plan; **`[OPEN]`** pending a read of the plan's own source.

| § | result |
|---|---|
| 25N.10 | ⭐ **`EvidenceCount ≠ InformationCount`** — one vendor document read by a human and an LLM is **not** three sources |
| 25N.11–12 | evidence lineage graph `G_E`; **evidence clusters** with `InformationUnits(C₁) ≈ 1` |
| 25N.13 | independence rests on the **acquisition mechanism**, not on *"they came from different people"* |
| 25N.14 | `ConditionalIndependence(E₁, E₂ \| H, C)` — binary independence is refused as too weak |
| **25N.44** | ⭐⭐ **the conservative evidence principle** — if `Independence = Unknown`, then `LR_combined = LR₁·LR₂·LR₃` **must not be computed** |
| 25N.45 | aggregation **need not produce a number**; `unresolved` + `NeedIndependentEvidence` feeds back into `Zero` and `Lord` |

$$\boxed{\textbf{§25N.44 is the origin of the blocker on Dempster's } \oplus\textbf{, dated 2026-08-28 09:42.}}$$

The forward plan records `TG-02` as open *"which is why Dempster's `⊕` stays blocked."* **The reason
is not missing — it is a deliberate safety invariant, stated with its justification, five days
before the plan's window.** `[EMP]`
