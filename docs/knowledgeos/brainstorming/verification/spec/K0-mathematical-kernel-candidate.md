# K0 — Mathematical Kernel Candidate (Checkpoint 2 deliverable)

**Status:** VERIFIER INFERENCE (source-discipline class C) · 2026-08-29 · derived from the full extraction base (A1/A3/A3W/A3X/A6) and the three executed witnesses. **Nothing here upgrades any corpus claim.** Per mandate 1459 §25: this is the *mathematical* kernel — the smallest set of definitions, assumptions and relations from which the non-trivial KnowledgeOS results actually follow.

## 0. Headline finding

> **A coherent minimal mathematical kernel CANNOT yet be established from the corpus**, because three kernel-adjacent constructions that the theory's own results presuppose are missing: (i) the identity/equivalence calculus (evidence `~`, state equality, requirement identity), (ii) the contract-derivation function η, and (iii) the ladder transition calculus. What follows is therefore **the smallest defensible candidate**, with every missing dependency named.

And one structural discovery of independent value:

> **None of the corpus's derivable or witnessed results depends on any specific `K_t` tuple.** Every executed witness and every sound derivation treats the knowledge state as an *opaque structure queried through evaluators* (`Status(K,r,EC)`, component predicates over `(K,t)`, `Replay` over an event history). The seven-way `K_t` tuple conflict (C-001) is therefore a **representation-layer dispute, not a kernel dispute** — the kernel needs `𝒦` only as an abstract sort. ⚑VERIFIER INFERENCE, to be adversarially checked at Level 1.

## 1. Kernel-sense disambiguation (mandated, §25/§23)

| Sense | Content | Relation to K0 |
|---|---|---|
| **K0 — mathematical kernel (this document)** | sorts + assumptions + relations from which the non-trivial results follow | — |
| 049 8-primitive set {Entity,State,Event,Observation,Proposition,Relation,Policy,Action} | *representational* reduction candidate ("everything expressible as Structure(𝒫)") — a data-modeling claim, explicitly not minimal/complete | overlaps P1/P6; NOT the mathematical kernel: most of its members do no deductive work |
| 162 DDD shared kernel {Identity,Provenance,Lineage,ContextReference,AuthorityReference,TemporalValidity,Integrity} | cross-context consistency vocabulary | architectural; token-disjoint from 049 (C-010) |
| Constitutional kernel (eleven laws / K1–K7) | prose-normative governance laws | not mathematics; maps onto K0's gate frame only informally |
| Kernel-track K1–K8 capacities; Step-201 frozen vocabulary | capability list; naming discipline | neither claims deductive sufficiency |

## 2. The candidate kernel

### 2.1 Sorts and primitive structure (7 frames)

| # | Frame | Primitives | Source anchor |
|---|---|---|---|
| P1 | **Observation frame** | sets `W` (world states), `O` (observations); function `Ω: W → O` | 031 §17–18 |
| P2 | **Property frame** | property functions `g: W → V_g`; definition *Identifiable(g,Ω) ⟺ ∀W₁,W₂: Ω(W₁)=Ω(W₂) ⇒ g(W₁)=g(W₂)* | 031 §20 |
| P3 | **Status-order frame** | finite poset of epistemic statuses with covering relation `Candidate ⋖ Supported ⋖ Accepted`, plus the boundary edge `Accepted ⋖ Committed` reachable only by an authority act (sort `Act_auth`) | v0.2 R-3/R-4; 008-A6 |
| P4 | **Gap frame** | abstract sort `𝒦` (knowledge states, *opaque*); finite requirement set `R`; **closed** gap-status set `𝒮_gap`; evaluator oracle `ev: 𝒦 × R × EC → 𝒮_gap`; `Zero(K,EC) := {(r, ev(K,r,EC)) : r ∈ R_EC}` | 025d; v0.2 R-2 |
| P5 | **Evidence frame** | evidence universe `E`; **given** equivalence `~ ⊆ E×E` and dependency preorder `≺ ⊆ E×E`; strength `s: E → [0,1]`; polarity `(S⁺,S⁻)` pair structure; normalization `N: Multiset(E) → Multiset(E)` (collapse by `~`, discount by `≺`) | EXP-01; 004 A₁–A₄ |
| P6 | **Dynamics frame** | event alphabet `ℰ`; **partial deterministic** transition `δ: 𝒦 × ℰ ⇀ 𝒦` with `Pre/Post`; histories `H = (e₀,…,e_{t−1})`; `Replay(K₀,H)` by recursion | Q15-revised T7/T8 |
| P7 | **Gate frame** | predicate families `Auth(d), Adm_i(d,K,t)` with three-valued conjunction (Unknown→Block default policy-supplied) | 042; 025h boundary |

### 2.2 Kernel assumptions (each indispensable to some derivation)

| # | Assumption | Consumed by |
|---|---|---|
| KA1 | `R_EC` finite; evidence multisets finite; status sets finite | Zero termination; aggregation well-definedness |
| KA2 | `ev` total on `𝒦 × R_EC` (evaluators exist as oracles; HumanAuthorization is an oracle, not a computation) | Zero totality "relative to evaluators" |
| KA3 | `~` and `≺` are given (an identity calculus exists) | I-5/I-6; idempotency; N well-defined — **currently UNCONSTRUCTED (MV-F-22)** |
| KA4 | `δ` deterministic on its domain; event history retains policy/version references | Replay theorem; reproducibility |
| KA5 | `𝒮_gap` closed (fixed membership) | Zero codomain typing — **corpus violates it in use (C-014)** |
| KA6 | Authority acts are external inputs (never derived from evidence volume) | A6 inertness |
| KA7 | The status order is exactly a finite covering-relation chain (no skip) | I-12 |

### 2.3 What actually FOLLOWS from K0 (candidate theorem list for Level-1 proof)

| # | Result | From | Expected verdict class |
|---|---|---|---|
| T-K1 | **Impossibility:** `Ω(W₁)=Ω(W₂) ∧ W₁≠W₂` ⇒ no function of `O` distinguishes W₁,W₂ | P1 | PROVEN (elementary; information-theoretic) |
| T-K2 | **Identifiability criterion** and the `Underdetermined` outcome as the correct negative | P1+P2 | PROVEN (definitional + T-K1) |
| T-K3 | Zero total + terminating, `O(|R|·cost(ev))`, deterministic **relative to `ev` determinism** | P4+KA1/2 | PROVEN UNDER ASSUMPTIONS (witnessed) |
| T-K4 | No-skip (I-12) and A6-inertness (evidence volume cannot cross the boundary) | P3+KA6/7 | PROVEN (finite order theory; witnessed) |
| T-K5 | Admissibility conjunction, no-averaging, Unknown→Block soundness | P7 | PROVEN (3-valued logic; witnessed) |
| T-K6 | **Pipeline-level I-5/I-6:** duplicate-invariance and dependency-first hold *of `N∘operator`*, and fail for raw operators (counterexamples exist) | P5+KA3 | PROVEN UNDER ASSUMPTIONS (witnessed both directions) |
| T-K7 | **Scalar-contradiction impossibility:** no map `Multiset(E)→[0,1]` preserves the (S⁺,S⁻) distinction | P5 | PROVEN (executed counterexample generalizes) |
| T-K8 | Replay determinism: `K_t = Replay(K₀,H_t)` under KA4 (induction on H) | P6 | PROVEN UNDER ASSUMPTIONS |
| T-K9 | Finite-state decidability imports (reachability, rule-set compliance, safety/liveness distinction) + undecidability boundary (no universal verification) | P6 finite restrictions | PROVEN (standard results, correctly imported per 089) |
| T-K10 | State-explosion bound `|S|=2ⁿ` ⇒ enumeration infeasible; symbolic methods required | P6 | PROVEN (arithmetic) |

### 2.4 Removal test (mandate §25) — necessity of each frame

P1/P2 removed → lose T-K1/T-K2 (the corpus's deepest results). P3 removed → lose T-K4. P4 removed → lose T-K3 (and Zero, the theory's central operator). P5 removed → lose T-K6/T-K7 (the only TESTED invariants). P6 removed → lose T-K8/T-K9/T-K10. P7 removed → lose T-K5 and the decision boundary. **Each frame is necessary for at least one non-trivial result. Nothing in the candidate is removable; the candidate is *irredundant*. Sufficiency for the FULL claimed theory is NOT asserted — see §3.**

Notably **excluded** by the removal test: any concrete `K_t` tuple (no result consumes one) · Entity/Identity as a constructed sort (presupposed via KA3, never constructed) · probability (the ratified layer abstains) · Lord/Sārathi selection semantics (policy) · uncertainty taxonomies (representation) · SNF metrics (unvalidated measurement layer) · the 8-primitive set as such.

## 3. What does NOT follow from K0 (the kernel-boundary gap list)

| Missing | Why it blocks | Register refs |
|---|---|---|
| η construction (`EC = η(G, IdealState)`) | Zero's *input* is unconstructed; totality is a ruled assumption (AS-R01/OQ-1); several requirement origins are non-computational oracles | D-R05 |
| Identity calculus (`~`, state equality, frame equivalence, requirement identity) | KA3 is assumed, not supplied; I-5, replay determinism, idempotency all conditional on it | MV-F-22, C-022 |
| Ladder transition calculus (what moves an item between statuses) | P3 gives the order, not the dynamics; Determination is policy-scoped but its trigger calculus is absent | AF-F-3 |
| Learn / F construction | dynamics of K and world uninterpreted beyond δ's abstract signature | MV-F-11 |
| In-force-policy uniqueness + genesis | gate frame presupposes a unique applicable policy; not established under multiple authorities; no base case | MV-F-7/8 |
| Aggregation-operator selection + calibration | P5 supports the impossibility results, not a positive choice; calibration never executed | OQ-3/5, C-039 |
| Adjudication of CONFLICTED | consumed by the layered state model, never typed | MV-F-10 |
| Joint satisfiability of the invariant system | no argument that any state satisfies all claimed invariants (𝒱 ≠ ∅ unargued) | A3X §3, C-042 |

## 4. Consequence for the final theory

The defensible chain today is:

```text
K0 (7 frames + KA1–KA7)
   ⊢ T-K1…T-K10                                  [the provable core — small but real]
   ⊬ η, Learn, identity calculus, transition calculus,
     policy uniqueness/genesis, operator selection      [the missing middle]
   ⇒ everything downstream of the missing middle (full state model, uncertainty
     unification, measurement, DDD derivation) is at most CONJECTURE/DESIGN CHOICE
     until the middle is supplied.
```

This matches, independently, the GN-46 verdict ("signatures without constructions") — arrived at here from the raw corpus rather than from the ratified layer. ⚑Class C; the concordance is evidence of robustness, not proof.
