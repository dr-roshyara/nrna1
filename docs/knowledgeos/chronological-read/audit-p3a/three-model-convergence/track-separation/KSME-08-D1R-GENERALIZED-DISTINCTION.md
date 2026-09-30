---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-07-D4-D14-DERIVATION-LEDGER, D1]
derived_from: [mathematical_ideas_that_can_be_implemented D1, 20260826-173048_question-5-comparing-and-challenging-assertions]
cross_track_dependency: none
---

# KSME-08 — D1R: Generalized Distinction Structure

## What was wrong with D1's universal claim

D1 (`KSME-07`'s audit) correctly derives, for the equivalence case: a distinction criterion `d` is
represented by `∼_d`, preservation is `∼_ρ⊆∼_d`, and required-distinction intersection
`∼_req=⋂_{d∈R_req}∼_d` is itself an equivalence relation. **All of this is valid and is not revised
here.** What was falsified (`KSME-07`) is the *implicit universal claim* that *every* KnowledgeOS
distinction reduces to this shape — real corpus evidence
(`20260826-173048_question-5-comparing-and-challenging-assertions.md`) explicitly leaves open whether
comparing/challenging assertions needs a partial order, lattice, or bilattice, none of which are
equivalence relations.

## D1R: the generalized structure

$$
\mathfrak D = (E,\ \mathcal R_{\mathrm{eq}},\ \mathcal R_{\mathrm{ord}},\ \mathcal A)
$$

where:
- `E` — the candidate semantic state domain (unresolved which K-object, per `KSME-07`'s A1: 13
  competing candidates, no unification).
- `R_eq` — equivalence-type distinction criteria (D1's original, unrevised content lives entirely here).
- `R_ord` — directional/comparison-type relations (preorder/partial-order/lattice/bilattice, per the
  corpus's own unresolved list), **existence not assumed** — populated only where the corpus actually
  requires a comparison, never invented.
- `A` — any further algebraic operations (joins, meets) **only if independently justified**.

**Discipline, restated from the commissioning**: this document does not choose which of preorder /
partial order / lattice / bilattice is correct for `R_ord`. That remains `OPEN` — D1R only asserts that
`R_eq` and `R_ord` are *different kinds of object* and must not be forced into one relation.

## The central mathematical question this generalization raises

Does behavioral indistinguishability (`∼_B`, necessarily an equivalence relation — it partitions states
into "no admissible future computation tells them apart") interact safely with a state domain that also
carries `R_ord`? Two sub-questions, tested computationally in
`KSME-08-RELATION-SEPARATION-EXPERIMENT.md`:

1. Can `R_ord` and `∼_B` coexist as independent structures on the same `E`? (Yes, shown constructively.)
2. Under what condition does `R_ord` descend to a well-defined relation on the quotient `E/∼_B`? (A
   precise compatibility condition, shown to sometimes hold and sometimes fail — not automatic.)

## Status

| Statement | Status |
|---|---|
| D1's original equivalence-case content (`∼_ρ⊆∼_d`, intersection formula) | `DERIVED` (unchanged, `KSME-07`) |
| D1's implicit universal-equivalence claim | `FALSIFIED` (`KSME-07`) |
| `D1R = (E, R_eq, R_ord, A)` as the correct general shape | `DERIVED-CANDIDATE` (this document) |
| Which concrete structure `R_ord` takes (preorder/partial order/lattice/bilattice) | `OPEN`, unchanged |
| `R_eq` and `R_ord` can be studied independently for D4's purposes | `VERIFIED` (`KSME-08-D4-DEPENDENCY-AUDIT.md`) |
