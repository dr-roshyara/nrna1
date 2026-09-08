# Phase 5H — K-1 Operator Census

## Method

Searched conceptually, not only lexically, per the authorization's §4: `phase_measure_theory/`'s own
"step-049" origin document (seq 0630, 2,232 lines, re-read in full this phase — Phase 5F/5G had only
read through §49.34); its own §49.90 "Step 49 — PASS" verdict and §49.75 final candidate-primitive-set
restatement; D285-1/D285-6/D285-7; and the three executable scripts (`t285_reconcile.py`,
`t285_equality.py`, `e_equality.py`).

## Census table

| Candidate | Source | Input | Output | Preconditions | Postconditions | Invariants | K-1 primitive(s) | Status |
|---|---|---|---|---|---|---|---|---|
| `L: K_t → K_{t+1}` (Learning) | seq 0630 §49.31 | $K_t$ | $K_{t+1}$ | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | State (implicitly all 8, as the whole state) | **INDIRECTLY EVIDENCED** — named and typed, no body |
| `D: (K,Π) → A` (Decision) | seq 0630 §49.31 | State, Policy | Action | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | State, Policy, Action | **INDIRECTLY EVIDENCED** — named and typed, no body |
| `Identity: E → ID` | seq 0630 §49.31 | Entity | Identity | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Entity | **INDIRECTLY EVIDENCED** |
| `Evidence ⊆ O × Context` | seq 0630 §49.31 | Observation, Context | Evidence (subset) | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Observation | **INDIRECTLY EVIDENCED** — a set-membership relation, not itself a transition operator |
| `Claim ⊆ P`, `Prov ⊆ R`, `Cause ⊆ R` | seq 0630 §49.31 | Proposition/Relation | (subsets) | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Proposition, Relation | **INDIRECTLY EVIDENCED** — same caveat |
| `Evidence = QualifiedObservation` | seq 0630 §49.76 | Observation | Evidence | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Observation | **INDIRECTLY EVIDENCED** — a defining equation, not a computable body (see `04`) |
| `Decision = PolicyConstrainedActionSelection` | seq 0630 §49.76 | Policy, candidate actions | Action | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Policy, Action | **INDIRECTLY EVIDENCED** — same caveat |
| `Outcome = PostActionObservation` | seq 0630 §49.76 | Action | Observation | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | Action, Observation | **INDIRECTLY EVIDENCED** |
| `Learning = KnowledgeStateTransition` | seq 0630 §49.76 | (restates `L` above) | — | — | — | — | State | Duplicate of `L`, not a new operator |
| `δ` (delta, state-transition function) | D285-7, `t285_reconcile.py`'s `delta(K,o)` | State, operation | New state | `NOT EVIDENCED` formally; **example instances given in code** (`e_equality.py`'s `delta(K0,o1)`) | — | — | State (whole) | **RECONSTRUCTABLE** — a worked code example exists, but no general formal definition over the 8 primitives |
| `Qualify` | D285-6, seq 0630 §49.76, seq 0795 §170.4 | Observation (+ Policy, per D285-6's own type) | Evidence | Named conditions only (seq 0795): source identified, timestamp available, integrity preserved, context known, provenance recorded | `NOT EVIDENCED` | `NOT EVIDENCED` | Observation, Policy, Proposition (Evidence) | **RECONSTRUCTABLE (type only)** — full treatment in `04` |
| An explicit enumeration of operations against all 8 primitives jointly | — | — | — | — | — | — | all 8 | **NOT EVIDENCED — confirmed absent, not merely unfound** (D285-7's own explicit finding, independently confirmed this phase by re-reading seq 0630 in full: exactly one occurrence of the word "operator" in 2,232 lines, a passing remark at §49.29, not an enumeration) |

## Verdict on the census

**No complete K-1 operator system exists anywhere in the corpus.** A **partial, informally-typed
system** exists — the derived-structure equations of seq 0630 §49.31/§49.76 name several
transformations with input/output types, but none carries preconditions, postconditions, or
invariants, and none is collected into a single governed register the way the 8 primitives themselves
were (seq 0630 §49.29/§49.75) or the way K-2's own 19-operation `𝒪_sem` was (Step 272A). This is
**Case B** (`03`): partial operator system, reconstruct only the evidenced subset.
