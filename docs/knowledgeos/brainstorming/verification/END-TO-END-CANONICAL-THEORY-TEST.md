---
artifact: 10 · END-TO-END-CANONICAL-THEORY-TEST
mandate: 20260830_1931 §14, §17
date: 2026-08-30
status: **EXECUTED on REAL EKP DATA — with two self-corrections retained**
---

# End-to-End Canonical Theory Test

**Nine stages. Real production data. Concrete inputs and outputs at every stage.**

## The run

```
STAGE 1-2  OBSERVATION -> EVIDENCE                        (37 real governed documents)
  observation: KNOWLEDGE-CONSTITUTION status=frozen authority=authoritative
  observation: META-LIFECYCLE status=baseline authority=authoritative
  observation: META-NAMING status=approved authority=authoritative
  qualified -> refs ['a7be2ab5','549c903f','d6217b10']    (Evidence = QualifiedObservation)

STAGE 3    PROPOSITION  P = (E, D, V)
  D_status = (status, {draft,discovery,reviewed,approved,baseline,frozen,superseded,archived}, ORDINAL)
  D_auth   = (authority, {authoritative,derived,generated,historical,provisional}, ORDINAL)
  built 74 propositions from 37 documents
  WellFormed: 74/74      ill-formed: 0
  example: (KNOWLEDGE-CONSTITUTION, status, frozen)

STAGE 4-5  KNOWLEDGE STATE + TRANSFORMATION
  T: assert x37  -> accepted 37, rejected 0
  |𝒜| = 37

STAGE 6-7  POLICY -> ASSESSMENT
  Policy=lenient  -> {True: 24, False: 13}
  Policy=strict   -> {True: 24, False: 13}

STAGE 8-9  Σ and Γ
  Σ=(Supporting, Weak)   Γ=Committed      24
  Σ=(Neutral, None)      Γ=Uncommitted    12
  Σ=(Neutral, None)      Γ=Committed       1
```

## Two self-corrections — retained per §5

**ERROR 1 — I printed "SAME K, DIFFERENT POLICY, DIFFERENT ASSESSMENT" and my own numbers were identical.**
```
documents where the two policies differ: 0
gates that ever fail: lenient={Authorization}  strict={Authorization, JustifStrength}
(status,authority) co-occurrence:
  (frozen,authoritative):1  (baseline,authoritative):1  (approved,authoritative):13
  (draft,provisional):12    (approved,derived):9        (approved,generated):1
```
> **CORRECTED: the two policies are EXTENSIONALLY EQUAL on this dataset.** Every document with
> authority ∈ {authoritative, derived} also has an approved-class status, so the differing gate is never
> exercised.
>
> **The corrected finding is better than the claim it replaced:** `status` and `authority` are **declared
> independent** in `authorities.yaml` and are **almost perfectly COLLINEAR in the actual data.**
> **Orthogonality is a design property of the EKP, not an observed property of its content.**
> **Methodological rule extracted: a policy comparison on non-discriminating data proves nothing** — and
> would have been reported as a PASS had I not checked the numbers against my own prose.

**ERROR 2 — I claimed both off-diagonal quadrants occur. Only one does.**
```
(Supporting,Committed)=24  (Neutral,Uncommitted)=12  (Neutral,Committed)=1
(Supporting,Uncommitted)=0   <- NOT OBSERVED
the single off-diagonal document: GRAPH-FULL (approved, generated)
```
> **CORRECTED: one off-diagonal cell, not two.** One counterexample still refutes *"Σ determines Γ"*.
> But the empirical support is weaker than stated, and the strong evidence for `Σ ⊥ Γ` remains
> `authorities.yaml`'s declaration and the corpus's executed `10^6-evidence` experiment.

## §17 — Final stopping questions

### Mathematical closure
| Object | Answer |
|---|---|
| **K** | `(𝒜, ℛ)`; `Assertion = (id, P, e, c, t, Π)`; `e = Set(ref × polarity × state)` |
| **P** | `(E, D, V)` = Entity, Dimension, Value — Q14 |
| **T** | `𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome`, partial, deterministic |
| **Policy** | `(id, version, Gates, ValidityInterval, ResolutionBehavior)` — 57.47 + 42.9 |
| **Assessment** | `P × Evidence × Context × Policy → Σ` — **now computable** |
| **Σ** | `(dir, str)`, `str` ORDINAL — **no arithmetic** |
| **Validation** | four predicates: Structural, Semantic, Epistemic, Governance |
| **Governance** | `Policy × Transformation → Admissible`; `Γ` orthogonal to `Σ` |

### Computability
**YES for every foundational operation.** Two documented boundaries: `GovernanceValid` requires History
(a boundary, not a defect), and **semantic policy equality is undecidable in general** — which is *why*
policies carry versions.

### Empirical grounding
**YES.** The full pipeline ran on 37 real governed documents. The corpus's own executable reference
(`ladder_dc_reference.py`) was run and passes. `knowledge-lint` and `knowledge-graph` were executed.
**And two of my own conclusions were falsified by the data.**

### Engineering correspondence
**EKP implements:** identity, referential integrity, typed relations, controlled vocabularies, context,
determinism, `Σ⊥Γ` as declared config, policy representation/interpretation/enforcement/evaluation.
**EKP does not implement:** proposition structure, evidence, temporal validity, provenance, `Σ`,
contradiction, `T` as a guarded transition, policy composition.
**Implementation exceeds theory:** `single_authoritative`, `boundary_consistency`,
`frozen_changed_without_adr`, symmetric `related_to`.
**Theory constrains implementation:** acyclicity of `supersedes` must be an **error** over three relation
families, not a warning over two.

### Semantic closure
**NO — three terms still carry two meanings:** `Provenance` (three objects), `Authority` (competence vs
trust-rank), `E`/`V` (Entity/Events, Value/Vertices). **Recorded, not reconciled.**

### Falsifiability
**Four concrete falsifiers:**
1. A stored `Σ` that diverges from the recomputed one → refutes "Σ is derived" (G-5).
2. Evidence volume alone crossing a commitment boundary → refutes `Σ ⊥ Γ`.
3. A supersession cycle that leaves "which is current?" well-defined → refutes the acyclicity invariant.
4. A policy change altering historical verdicts with no authority record → confirms G-P1 is load-bearing.

### Smallest remaining unresolved foundational question
> **Who authorises a change to Policy?** — G-P1. **Derivable** by applying the assertion pattern one level
> up; the regress terminates at a constitutional policy that is adopted rather than derived, and the EKP
> already has exactly one such document.

## Verdict

**FORMALLY INCOMPLETE → the foundational object graph is now CLOSED, but the theory is not complete.**
It does not yet govern itself, and three terms remain overloaded. **No "Theory Complete" artifact is
written, because the dependency audit does not demonstrate completeness — only foundational closure.**
