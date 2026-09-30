# aggregate-derivation-program

**Scope(s):** METHODOLOGICAL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Vocabulary->Relations->State->Transitions->Invariants->Aggregates->BoundedContexts · **Aliases:** Step 205
**Candidate group membership (NOT an identity claim):**
- **G0891**: [`aggregate-derivation-criteria` · `aggregate-derivation-program`] — working_label token overlap Jaccard=0.50 (shared tokens: ['aggregate', 'derivation'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope METHODOLOGICAL): Step 204's closing research program, taken up as Step 205: deriving DDD aggregate boundaries from atomicity/invariant-ownership/transaction-boundary/identity/lifecycle/concurrency, and the mandated seven-stage derivation order to be preserved in the book as evidence of how the architecture was actually built.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1423 §"Vocabulary -> Relations -> State -> Transitions -> InvariantPreservation. The next step should therefore be not another philosophical layer. It is time to derive the actual DDD structures."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1423 §"Which objects must change atomically, and therefore belong to the same Aggregate? ... Atomicity+InvariantOwnership+TransactionBoundary+Identity+Lifecycle+Concurrency. ... Vocabulary -> Relations -> State -> Transitions -> Invariants -> Aggregates -> BoundedContexts."]

## Lifecycle
last_seen: S1477. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1423 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1477 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1423 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1423 |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[RESTATEMENT/ARGUMENT]** [S1423]: Names the architecture's maturity progression (Concepts->Relations->StateSpaces->Transitions->InvariantPreservation) as complete, and declares readiness to move from theory-building to deriving actual DDD structures.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1423] types=[RESTATEMENT, ARGUMENT] scope=THEORY-LEVEL — "Names the architecture's maturity progression (Concepts->Relations->StateSpaces->Transitions->InvariantPreservation) as complete, and declares readiness to move from theory-building to deriving actual DDD structures." (anchor: "Vocabulary -> Relations -> State -> Transitions -> InvariantPreservation. The next step should therefore be not another philosophical layer. It is time to derive the actual DDD structures.")
- [S1423] types=[FUTURE-RESEARCH, GOVERNANCE] scope=THEORY-LEVEL — "Opens Step 205: derive candidate Aggregate boundaries using Atomicity, InvariantOwnership, TransactionBoundary, Identity, Lifecycle, and Concurrency, testing each candidate against 'why does this object own this invariant?' and 'what must be immediately vs eventually consistent?'; explicitly names and mandates preserving the full seven-stage derivation order (Vocabulary->Relations->State->Transitions->Invariants->Aggregates->BoundedContexts) in the book itself, to demonstrate how the architecture was actually derived rather than presenting a finished design as if it had been upfront." (anchor: "Which objects must change atomically, and therefore belong to the same Aggregate? ... Atomicity+InvariantOwnership+TransactionBoundary+Identity+Lifecycle+Concurrency. ... Vocabulary -> Relations -> State -> Transitions -> Invariants -> Aggregates -> BoundedContexts.")
- [S1477] types=[GOVERNANCE] scope=OBJECT — "DDD-2 adjudicates between step 189's five candidate aggregates and step 205's aggregate lists by applying step 203 section 34's own same-aggregate criterion (Same Aggregate if and only if a shared transactional invariant requires atomicity) to the KI catalogue per candidate; produces a per-candidate verdict showing the criterion's evaluation explicitly, resolving Lineage and Determination disagreements by the criterion or marking them UNRESOLVED." (anchor: "DDD-2 | Aggregate adjudication (189's five vs 205's lists): apply 203 section34's own criterion (Same Aggregate iff shared transactional invariant requires atomicity) to the KI catalogue per candidate")

## Notes for P3
(Own observation) This label participates in 1 candidate group(s) (G0891); P3 should assess whether any represent the same underlying object as this label, per the reasons recorded in _LABEL-NORMALIZATION.md.
