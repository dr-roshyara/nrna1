# mutation-ownership-invariant

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0002`, scope `OBJECT`: The invariant/finding that mutation ownership is exactly one at a time and is a fold projection, never stored.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0071] §"mutationOwner stored in records | 0 occurrences — never persisted ... The single most consequential measurement is the last pair: mutation ownership exists only as a computation."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0071] §"WorkItem [aggregate root] ... LIFECYCLE PART ... AUTHORITY PART ... DERIVED BY FOLD — never stored, never settable"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0234. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S0234), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0071, S0073, S0078 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0234 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0072 |
| experiments | PRESENT | S0071 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0071] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "The proposal's single most consequential measurement: mutationOwner is never stored in any of 15 work-item records (0 occurrences) yet is referenced 9 times in the mechanism source and observed appearing live from a fold computation immediately after this session's own START." (anchor: "mutationOwner stored in records | 0 occurrences — never persisted ... The single most consequential measurement is the last pair: mutation ownership exists only as a computation.")
- [S0071] types=[FORMALIZATION] scope=OBJECT — "The proposed tactical model formalizes WorkItem as the aggregate root with a never-merged lifecycle part (transitions, assignments) and authority part (grants), plus derived-by-fold-only fields (workItemState, assignmentState, mutationOwner) that must never be stored or settable." (anchor: "WorkItem [aggregate root] ... LIFECYCLE PART ... AUTHORITY PART ... DERIVED BY FOLD — never stored, never settable")
- [S0072] types=[WARNING] scope=OBJECT — "Concern C-2: T-1's claim that a derived value is 'never accepted as input' is not actually demonstrated by any probe showing the mechanism refuses a submitted derived key; absence in observed practice is not the same as refusal by contract." (anchor: "C-2 | T-1 is Proposed yet stated in absolute form ... The 'accepted as input' half is not demonstrated")
- [S0073] types=[CORRECTION] scope=OBJECT — "Verification #2 falsifies one of the refinement's own high-confidence Observed measurements (claimed 0 non-canonical keys; actual 20, e.g. `note` on 16 COMPLETE transitions the mechanism never reads), while judging the correction strengthens rather than weakens the T-1b invariant it was meant to support." (anchor: "'Non-canonical keys anywhere in the estate: 0' is wrong; there are 20 ... the falsified measurement in fact strengthens T-1b.")
- [S0073] types=[FORMALIZATION] scope=OBJECT — "T-1 is independently confirmed as valid but restated in three parts of different evidential strength: T-1a (submitted transitions never supply the state they are validated against, structural) and T-1b (a derived value carried on a transition has no standing, structural) are Observed; T-1c (no derived projection is ever written) is a Proposed writer obligation evidenced only by practice (0/130)." (anchor: "Is 'derived state has exactly one producer — the fold' a valid domain invariant? YES ... T-1a ... T-1b ... T-1c")
- [S0078] types=[FORMALIZATION] scope=OBJECT — "T-1 is restated as three explicitly separated parts of differing strength (T-1a/T-1b structural and Observed; T-1c a Proposed writer obligation evidenced only by practice), replacing an absolute claim that was actually false as stated." (anchor: "T-1a — a submitted transition never supplies the state against which it is validated ... T-1c — a writer obligation, not a refusal")
- [S0234] types=[INVARIANT] scope=OBJECT — "Grants: a grant without a humanActRef is refused by the engine ('the record never manufactures authority -- G-2/R5b'). Exactly one mutation owner per work item is mechanically enforced (I-1). Closure is a governance act (G-1): COMPLETE refuses any writer but governance/human. Producer != acceptor (R-34, INV-ATTR-2): a process cannot verify/accept its own work; separation is declared, not attestable." (anchor: "Exactly one mutation owner per work item is a mechanically enforced invariant (I-1)")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
