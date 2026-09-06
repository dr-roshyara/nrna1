# PHASE 3A · Canonical Architecture — KnowledgeOS Synthesis Model v0.1

> **STATUS ANNOTATION (2026-08-28, final): SUPERSEDED by `canonical-architecture-v0.2.md`** under the
> explicit HPA ruling GN-19 (ACCEPT F-1…F-4; exactly R-1…R-4 applied). This v0.1 text is retained
> unmodified as the pre-falsification record.

**Status (GN-10, constitutional):** *architecturally coherent and substantially evidence-supported;
formal synthesis and implementation conformance remain to be established.* **Never "validated."**
**Working thesis (GN-11):** ⟦C-gate⟧ *"KnowledgeOS is not primarily a knowledge-storage architecture.
It is an architecture for moving from partial observation to warranted state, decision and authorized
action under an explicit purpose and epistemic contract."*
**Epistemic grades used per element:** `TESTED` (recorded test artifact) · `READ` (read-verified
source) · `COMPOSITION` (all links read-verified; chain assembled by review) · `SPECIFIED`
(⟦TITLE⟧-level) · `HYPOTHESIS`.

---

## 1 · Canonical concepts (ubiquitous language, with provenance)

| Concept | Definition (evidence-derived) | Grade | Source |
|---|---|---|---|
| **X_t** | the actual world state; never fully observed: `X_t ≠ Observed(X_t)` | READ | Step 66; GN-09 lineage from Ω-c |
| **Knower** | owner of problem, purpose, Ideal State, final authority | READ (3-regime invariant) | 151244, Q11/Q18, 008-A6 |
| **Purpose / Goal G** | Knower-owned intent inducing knowledge requirements | READ | 151244, 025d |
| **IdealState** | the epistemic understanding the Knower considers sufficient; Knower-authorized changes only | READ | Q11, Q18 |
| **EpistemicContract EC** | goal-derived requirement set: *what must be known or satisfied*; carrier of Ω-a | READ | 025d/025e |
| **SourceObservation ≠ SemanticObservation** | *what was observed ≠ what was understood* | READ, untested | 125403 (Option 3) |
| **Evidence** | carries dependency structure and roles; duplicates must not amplify; corroboration must | **TESTED** | EXP-01 + CSV |
| **AcceptancePolicy** | determines what may be admitted as accepted knowledge (≠ what evidence supports) | READ | 008 |
| **Epistemic statuses** | `Candidate → Supported → Accepted → Committed` | READ | 008 |
| **Determination** | the `Supported → Accepted` transition under AcceptancePolicy (R1's warrant-establishment, hosted) | READ (2D-2) | 234405 ↦ 008 |
| **KnowledgeState K_t** | state over the 8 primitives `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` | TESTED (within scope) | 049/050 |
| **Zero / Z_t** | `Zero(K,G,EC)` — typed discrepancy (unknown/conflicting/missing/invalid), retains *why* | READ | 025d (CON-01 lineage noted) |
| **Proposal (selector)** | next epistemic action from `(K_t, Z_t, G)`; **no authority** | READ | 025g (naming RESERVED, GN-04) |
| **DecisionContract DC** | `DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence)` | READ | 042 |
| **Authorization** | precondition constraint filled via governance, never a processing step | COMPOSITION | 025h+042+Q18+008-A6 |
| **Governance** | separate concern (own conflict algebra) whose invariants cut across | READ | 025f, 042, 121 |
| **Action / feedback loop** | authorized action → new observation | READ head / SPECIFIED | 025z, 056 |
| **Ω** | **historical term only** (GN-09) — decomposed Ω-a→EC · Ω-b bounded away · Ω-c→X_t | ruling | 2D-3 |

## 2 · The seven canonical non-collapse distinctions (gate-ratified)

`observation ≠ evidence` · `evidence ≠ determination` · `determination ≠ commitment` ·
`proposal ≠ decision` · `decision ≠ authorization` · `authorization ≠ execution` · `model ≠ reality`
— plus the governing triple ⟦C⟧ **`SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction`** (042).

## 3 · Canonical flow and boundaries

```
                        X_t  (reality — never fully observed)          [EPISTEMIC BOUNDARY]
                         │ partial observation
   KNOWER ── purpose ──► G ──► IdealState ──► EC                       [DECISION + COMPUTATIONAL]
     │                                    │
     │ authority (via governance)         ▼
     │            SourceObs ≠ SemanticObs ──► EVIDENCE (dep-first, role-aware)   [TESTED layer]
     │                                             │
     │                                             ▼
     │                    DETERMINATION: Supported ──AcceptancePolicy──► Accepted
     │                                             │
     │        A6: authority determines commitment  ▼
     └────────────────────────────────► Committed / K_t ──► Zero(K,G,EC)=Z_t
                                                            │
                                                            ▼
                                              PROPOSAL (selector, no authority)
                                                            │
                                                            ▼
                                     DECISION under DC(d); Auth ⇐ BC_Governance ⇐ Knower
                                                            │
                                                            ▼
                                                    AUTHORIZED ACTION ──► new observation
   cross-cutting: VALIDATION (3 loci) · GOVERNANCE (025f/042/121)
```

Three boundary kinds carried explicitly: **epistemic** (`X_t ≠ Observed`) · **decision**
(Knower→governance→DC.Auth) · **computational** (EC, finite-state restrictions).

## 4 · Canonical invariants (each with grade)

| # | Invariant | Grade |
|---|---|---|
| I-1 | **The Knower owns problem, purpose, Ideal State and final authority** | READ, 3-regime chain — the strongest |
| I-2 | **Proposal ≠ decision**; the selector holds no authority | READ (Q17 + 025g/h) |
| I-3 | **SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction** | READ (042) |
| I-4 | **Authority determines commitment, not evidential truth** | READ (008-A6) |
| I-5 | **Duplicate evidence must not increase confidence; independent corroboration must** | **TESTED** (EXP-01) |
| I-6 | **Dependency resolution precedes aggregation** | **TESTED** (EXP-01) |
| I-7 | **`X_t ≠ Observed(X_t)`** — model ≠ reality, structurally | READ (Step 66) |
| I-8 | **No architectural assertion without evidence** (method invariant) | READ (109), partially executed |
| I-9 | Zero's four-way non-satisfaction typology must not collapse to Boolean | READ (025d) |
| I-10 | Constitution changes require approval (governance) | READ (042) — **violated in the real repo per Step 121**: live finding |

## 5 · Mathematical objects (inventory, not endorsement)

`Zero(K,G,EC)` · status ladder as ordered set · `DC(d)` 6-tuple · evidence aggregation operators
(saturating `1−∏(1−sᵢ)` and Bayesian-like — **two survivors, selection open, OQ-11**) · M₄₉ primitive
set · dependency calculus (steps 001–007) · `X_t`/observation model (66). Grades: EXP-01 objects
TESTED; M₄₉ TESTED-within-scope; the rest READ/SPECIFIED. ⚠ The three named algebras (Zero/Lord/
Sārathi) carry their lineage caveats (CON-01/02, GN-04) into any Phase-3B formalization.

## 6 · DDD mapping — **CANDIDATES ONLY** (3B/3C must confirm)

| Bounded-context candidate | Contents | Basis |
|---|---|---|
| **Governance** | approval invariants, conflict algebra, constitution versioning | **corpus-named** (`BC_Governance`, 042) — the only explicitly named BC |
| Intake/Observation | source vs semantic observation | Option 3 separation |
| Evidence | dependency, roles, aggregation | TESTED layer; kernel-corpus Entity/VO question **still open** |
| Epistemic Core | statuses, AcceptancePolicy, K_t, Zero | 008 + 049 cohesion |
| Agency | proposal, decision contracts, authorization interface | 025g/h + 042 |
| Execution/Runtime | action loop, reference machine | SPECIFIED only |

⚠ These six are ⟪SUGGESTS⟫-level. The corpus formally names **one** (Governance). No aggregate is
declared here — the kernel corpus's falsification (atomicity-not-relatedness, 6 arrivals) stands as
a warning against premature aggregation.

## 7 · What this model explicitly does NOT claim

Not validated (GN-10) · not the proven-minimal architecture · no Kernel membership ruling · Lord
naming unresolved (GN-04) · Ātma-Kernel untested (C-012 OPEN) · self-verification suite unexecuted
(EG-05 residue) · Ω-b adequacy open (GN-07) · operator selection open (OQ-11) · action/execution
joint soft.

**Every element above traces to a registry entry or a read source. STOP — Phase 3B (formal
consistency) not started.**
