# V2 — Theory Dependency Graph & Load-Bearing Propositions (Checkpoint 3 deliverable)

**Status:** VERIFIER INFERENCE (class C) · 2026-08-29 · built on K0 and the extraction registers. Purpose (mandate 1459 §24): make visible what depends on what, what is circular, what is unsupported, what is dead, and what is contradictory.

## 1. The dependency spine

```text
LEVEL 0  PRIMITIVES (K0 frames)
  W, O, Ω          𝒦(opaque), R, 𝒮_gap, ev        E, ~, ≺, s        ℰ, δ, H        status order ⋖, Act_auth        Auth/Adm predicates
     │                     │                          │                 │                   │                          │
LEVEL 1  DEFINITIONS
  Identifiable(g,Ω)   Zero(K,EC)={(r,ev(K,r,EC))}   N (normalize)   Replay(K₀,H)     ladder+A6 boundary         3-valued conjunction
     │                     ▲                          │                 │                   │                          │
     │                     │ EC = η(G, IdealState) ←──┼── Knower, G, IdealState [INFORMAL SORTS]                       │
     │                ██ η UNCONSTRUCTED ██           │                 │                   │                          │
LEVEL 2  THEOREM CANDIDATES (T-K1…T-K10)              │                 │                   │                          │
  T-K1 impossibility  T-K3 Zero total/term        T-K6 I-5/I-6     T-K8 replay        T-K4 no-skip/A6-inert       T-K5 no-averaging
  T-K2 identifiability      │                     T-K7 scalar-imposs.   │                   │                          │
     │                      │                          │                 │                   │                          │
LEVEL 3  DERIVED MODEL LAYER                          │                 │                   │                          │
  Underdetermined     Proposal(Lord) ← Z_t        Determination ←──┐    history/audit   layered state model      DC admissibility
  as valid outcome         │                      (AcceptancePolicy)│    invariants      (FA D-FA-1)                   │
     │                     ▼                          ▲             │        │                 ▲                       ▼
     │                Decision (Sārathi) ██UNRATIFIED██│             │        │       ██ transition calculus ██   Committed (authority act)
     │                     │        ██ MV-F-6 type gap ██            │        │       ██ ABSENT (AF-F-3)     ██        │
     │                     ▼                                         │        │                                        │
     │                Authorized Action ──► new Observation ─────────┴────────┴──► (the loop closes ONLY through      │
     │                     ▲                                                        unconstructed Learn/δ-content)    │
     │            Policy-in-force ── I-11 ── ██ uniqueness (MV-F-7) + genesis (MV-F-8) UNSUPPORTED ██                 │
LEVEL 4  INVARIANT LAYER: 8+ registries ── ██ joint satisfiability UNARGUED (𝒱≠∅ open) ██
LEVEL 5  DDD / ARCHITECTURE: bounded contexts, aggregates (203/205/162), L1–L5 layers (FA-1) — derivable only AFTER levels 2–4 stabilize
```

`██…██` = missing/unsupported dependency. Every path from the primitives to the *full* claimed system passes through at least one such block.

## 2. Load-bearing propositions (checkpoint §C)

Ranked by how much of the theory falls if they fail:

| LB | Proposition | Everything that rests on it | Current status |
|---|---|---|---|
| LB-1 | **η exists and is total** (`EC=η(G,IdealState)`) | Zero's input; the whole reference layer; the Knower→contract→gap→action loop | RULED ASSUMPTION (OQ-1); several requirement origins non-computational — *totality will at best be relative to oracles* |
| LB-2 | **An identity calculus exists** (`~`, state equality, requirement identity, frame equivalence) | I-5, N, idempotency, replay determinism, Γ↔ρ_A typing, any K_t comparison | UNCONSTRUCTED (MV-F-22); corpus admits Hash(Text) insufficient |
| LB-3 | **The invariant system is jointly satisfiable** (∃ state satisfying all claimed invariants) | the entire invariant layer; "Valid Transition ⇒ Invariant Preservation" is vacuous on 𝒱=∅ | UNARGUED anywhere (197 §2; A3X §3) |
| LB-4 | **δ deterministic + policy/version references retained** | Replay (T-K8), reproducibility, audit, no-hindsight (048 I-3/I-11) | CONDITIONAL — stated as proviso; HumanAuthorization/LLM evaluator inputs threaten it (C-040) |
| LB-5 | **"The in-force policy" is unique, and a first one exists** | I-11, stratification loop, the governance gate on every Determination and Decision | NOT ESTABLISHED (MV-F-7/8) |
| LB-6 | **Normalization precedes aggregation** (pipeline discipline) | I-5/I-6 — the only TESTED invariants; all corroboration semantics | PROVEN AT PIPELINE LEVEL (witnessed) — but conditional on LB-2 |
| LB-7 | **Covering-relation ladder + A6 boundary** | admission semantics; layered state model; commitment discipline | witnessed; sound as finite order theory (T-K4) |
| LB-8 | **Identifiability criterion + impossibility** | legitimacy of Underdetermined/NOT_IDENTIFIABLE outcomes; the epistemic-honesty layer; anti-hallucination stance | standard mathematics correctly stated — strongest candidates for PROVEN |
| LB-9 | **Scalar impossibility (contradiction preservation)** | I-9, non-scalar Zero, (S⁺,S⁻) retention, rejection of universal confidence scores — the corpus's signature design decisions | provable (T-K7, executed counterexample) |
| LB-10 | **Finite representability of states/transitions/invariants** | all computability claims (069's "theorem candidate") | plausible but stated, not proven; K_t finiteness is an assumption (AS-R04) |

## 3. Circular dependencies exposed (checkpoint feed)

| ⭕ | Cycle | Corpus awareness |
|---|---|---|
| ⭕-1 | **Policy genesis:** policy changes require an in-force policy authorizing them; the first in-force policy has no ratified origin — induction without base case | flagged (AF-F-14/MV-F-8), undisposed |
| ⭕-2 | **Goal→Policy→Requirement recursion** ("recursively knowledge-governed", 025d §29–30) — no well-foundedness argument | stated approvingly, never grounded |
| ⭕-3 | **Zero-in-K self-reference:** Q15-revised keeps 𝒵_t inside K_t while Zero is computed *from* K_t (C-023) — `Zero(K)` would then read its own prior output | unflagged in Q15; resolved differently in Q20 (unreconciled) |
| ⭕-4 | **Circular justification A→M→V→A** | flagged in-corpus (031 §59, "PASS conceptually — formal implementation remains to be specified"); AcyclicDerivationGraph only PROPOSED (025a-5) |
| ⭕-5 | **Identity bootstrap:** aggregation needs evidence identity; identity criteria are to be governed by policy; policy admission runs on aggregated evidence | ⚑verifier-identified; not in corpus |

## 4. Unsupported branches (claims with no path from K0)

SNF measurement metrics (scale legitimacy unestablished; estimand undefined) · uncertainty-taxonomy unification (5 taxonomies, no math relating them) · POMDP framing (explicitly refused in-corpus) · model-averaging prior `P(M_m)` · any positive aggregation-operator selection · "computable on a normal PC" as a *system* claim (holds only for the K0 core under KA1–KA7) · kernel *minimality* claims of any registry (049 disclaims; 120's reduction test asymmetric, C-044).

## 5. Dead branches (recorded, no live role)

Measure-theoretic foundation (deferred day 1; cascade refuted v0.1; "formalisms are regimes" — with an undisposed meta-refutation tail, D-07) · Ω as ambient object (decomposed GN-09) · `K(t)=M(t)+iA(t)` complex model (ALT-09, rejected) · Lord=Ω analogy (vacuous by Ω's departure) · weighted scoring as kernel (REJECTED, 025c-1) · `Believed` as canonical state (rejected, 189 §5) · `Preserve` as domain operation (declared unnecessary; diagrams remain — C-025-adjacent).

## 6. Mutually contradictory branches (must not be merged silently)

Monotonicity axiom vs non-monotonic revision (C-020) · product state space vs federation (C-027) · Assessment vs Assertion object language (C-009) · flat vs 4-dimensional epistemic status (C-006) · snapshot-history vs event-log (C-011) · the two Policy signatures (C-013) · Zero in/out of state (C-023, four-way, I.18).

## 7. Reading of the graph (checkpoint synthesis)

The corpus has a **provable rim** (T-K1…T-K10: identifiability, gap evaluation, order/boundary semantics, pipeline evidence laws, replay, decidability imports) and an **unconstructed middle** (η, identity, transition calculus, Learn, policy uniqueness/genesis). The rim does not depend on the contested representations (K_t tuples, uncertainty taxonomies); the middle blocks every path from the rim to the full claimed system. **The architecture-facing layers (DDD contexts, aggregates, five-layer model) currently hang from the middle, not from the rim.**
