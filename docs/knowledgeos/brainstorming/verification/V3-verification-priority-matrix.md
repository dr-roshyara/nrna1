# V3 — Verification Priority Matrix & Level-1 Attack Plan

**Status:** VERIFIER PLAN (class C) · 2026-08-29. Ranking dimensions per mandate: foundational importance · mathematical risk · statistical risk · computational risk · architectural dependency. Scale 1–5 (5 = highest).

## 1. Priority matrix

| P | Target | Found. | Math risk | Stat risk | Comp risk | Arch dep. | Rationale |
|---|---|---|---|---|---|---|---|
| 1 | **Invariant joint satisfiability** (LB-3) | 5 | 5 | 2 | 3 | 5 | If 𝒱=∅ (or even if unprovable), the entire invariant layer is vacuous; 8 registries hang on it; cheapest decisive test: construct one explicit model state satisfying a consolidated core set — or find two invariants that conflict |
| 2 | **K0 rim proofs** (T-K1…T-K10) | 5 | 2 | 1 | 1 | 3 | Low risk, high value: converts the corpus's real mathematics from "witnessed/claimed" to PROVEN/PROVEN-UNDER-ASSUMPTIONS; fixes the floor the rest stands on |
| 3 | **Monotonicity collision C-020** | 4 | 5 | 4 | 1 | 3 | Direct axiom-vs-counterexample conflict at the heart of the evidence calculus; resolution candidates exist (scope split) but are unstated — must be adjudicated as finding + proposed refinement |
| 4 | **Identity calculus prerequisites (LB-2)** | 5 | 4 | 3 | 3 | 4 | Determine the *minimal* identity structure I-5/replay/idempotency actually need (equivalence? partial equivalence? hash-independence?) — a precise statement of what's missing, not an invention of it |
| 5 | **η status (LB-1)** | 5 | 4 | 2 | 4 | 5 | Establish formally what η-totality-relative-to-oracles can and cannot mean given the 025d §39 requirement-origin list; classify OQ-1 as (a) constructible, (b) oracle-relative only, or (c) category error |
| 6 | **Policy uniqueness + genesis (LB-5, ⭕-1)** | 4 | 4 | 1 | 2 | 5 | I-11's guard; multi-authority uniqueness; base case — governance layer's mathematical floor |
| 7 | **Composition law C-024 + transition-model unification (δ vs τ vs ℛ)** | 4 | 4 | 1 | 3 | 4 | Two stated directions of the composition law can't both stand; the three transition developments must be classified equivalent/compatible/incompatible |
| 8 | **Zero closure + status-set discipline (KA5, C-014)** | 3 | 3 | 2 | 2 | 3 | Closed 𝒮_gap is assumed by typing; corpus drifts; finite fix, decisive for Zero's codomain |
| 9 | **Statistical layer floor** (Dempster precondition C-039; likelihood availability HA-S02; calibration status) | 3 | 3 | 5 | 2 | 3 | The corpus's abstention discipline is its strength — verify the *few* positive statistical commitments actually made |
| 10 | **Scalar-impossibility generalization (LB-9/T-K7)** | 3 | 2 | 3 | 1 | 4 | Underwrites I-9 + non-scalar Zero + anti-confidence-score doctrine — worth a clean general proof |
| 11 | **Replay under oracle inputs (LB-4, C-040)** | 3 | 3 | 2 | 3 | 4 | Determine exact determinism boundary (which event sources are replay-safe) |
| 12 | **Ladder dynamics gap (AF-F-3)** | 3 | 3 | 1 | 2 | 4 | State precisely what a transition calculus must supply; no invention |
| 13 | K_t representation adjudication (C-001) — *after* 1–12 | 2 | 2 | 1 | 2 | 5 | Kernel finding says tuples are representation-layer; adjudicate only once the rim+middle status is fixed |
| 14 | Uncertainty-taxonomy comparison (C-007) | 2 | 2 | 4 | 1 | 2 | Compare the 5 taxonomies for equivalence/orthogonality; unification is a refinement proposal at most |
| 15 | SNF measurement audit | 2 | 3 | 4 | 2 | 1 | Self-demoted in-corpus; audit scale-legitimacy claims only |

## 2. Proposed Level-1 attack sequence (checkpoint §L)

**Wave 1 (decisive, independent):** P1 satisfiability probe · P2 rim proofs · P3 monotonicity adjudication.
**Wave 2 (the missing middle, as *precise gap statements*):** P4 identity prerequisites · P5 η classification · P6 policy floor · P7 transition unification.
**Wave 3 (closure & floors):** P8 status closure · P9 statistical floor · P10 scalar generalization · P11 replay boundary · P12 ladder-dynamics specification.
**Wave 4 (representation & comparison):** P13–P15.
Each wave: findings TV-F-nnn; no repair; per-claim verdicts in the mandate vocabulary; source re-checks of the underlying K/I/X entries folded into the wave that touches them (mandate 1459 §27).

## 3. Standing constraints

No mass rewriting of registers during waves — findings accrete in `findings/`; registers update only their STATUS columns. Wave outputs feed the Parts A–S final document directly (Part I invariants ← P1; Part L computational ← P2/P11; Part J derivations ← P2/P3/P7/P10; Part K statistics ← P9; Part P gaps ← P4/P5/P6/P12).
