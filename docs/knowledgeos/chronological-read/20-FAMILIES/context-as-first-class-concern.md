# context-as-first-class-concern

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Meaning(Action)=f(Action,Context)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 204's elevation of Context from metadata to a first-class architectural concern, since the same action/event name can carry different valid meanings in different contexts.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1423 §"Valid(Action\mid Context_1)\neq Valid(Action\mid Context_2). ... Context is therefore not metadata. ... Meaning(Action)=f(Action,Context)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1423. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1423 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1423 |
| examples | PRESENT | S1423 |
| warnings | PRESENT | S1423 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Applies the Gita Chapter-4 lens architecturally: the same physical action can have different semantic validity in different contexts, so Context must be elevated from 'extra fields' to a first-class architectural concern -- Meaning(Action)=f(Action,Context), and similarly Meaning(Assessment)=f(Assessment,Model,Context). [S1423]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1423] types=['PRINCIPLE', 'ARGUMENT'] scope=THEORY-LEVEL — "Applies the Gita Chapter-4 lens architecturally: the same physical action can have different semantic validity in different contexts, so Context must be elevated from 'extra fields' to a first-class architectural concern -- Meaning(Action)=f(Action,Context), and similarly Meaning(Assessment)=f(Assessment,Model,Context)." (anchor: "Valid(Action\mid Context_1)\neq Valid(Action\mid Context_2). ... Context is therefore not metadata. ... Meaning(Action)=f(Action,Context).")
- [S1423] types=['WARNING', 'EXAMPLE'] scope=THEORY-LEVEL — "Warns that a bare event name like 'Approved' is dangerously ambiguous without its owning bounded context and contract -- context, not the event name alone, determines semantic meaning." (anchor: "EventName alone does not determine semantic meaning. Its bounded context and contract do. ... 'Approved' ... could mean: business approval; technical approval; governance approval; financial approval; security approval.")

## Notes for P3
(none beyond what is noted above)
