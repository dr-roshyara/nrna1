# step183-preserve-absence-and-uncertainty-transitions

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `S0(P)=Unknown -> S1=Candidate -> S2=Supported -> S3=Determined -> S4=Superseded, transitions must have provenance`, `Unknown != Rejected != False` · **Aliases:** `reconstruction must not manufacture completeness`
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 183 requires reconstruction to preserve absence via the Zero lens: if evidence E3 was simply never gathered, the reconstruction must not imply 'E3 was considered and found irrelevant' -- Unknown != Rejected != False, restated as one of the strongest invariants. Similarly requires reconstruction to preserve uncertainty over time honestly: if an architect recorded NexusMigrationRisk=Unknown and someone later claims 'migration was considered low risk', the system must not silently conclude the original state was Risk=Low -- the transition Unknown->AssessedLow itself requires its own evidence. Formalizes reconstruction as a state-transition problem over a proposition P: S0(P)=Unknown -> S1=Candidate -> S2=Supported -> S3=Determined -> possibly S4=Superseded, with each transition itself needing provenance. Also: the reconstruction graph 'becomes interesting precisely because not every path must exist' -- an unresolved conflict, a rejected alternative, or an unevidenced assumption must be preserved as such, never manufactured into false completeness.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1377] §"the reconstruction must not imply: 'E3 was considered and found irrelevant.' It may simply have been absent. Thus: Unknown ≠ Rejected ≠ False. ... the transition itself requires evidence. ... S_0(P)=Unknown then: S_1(P)=Candidate then: S_2(P)=Supported then: S_3(P)=Determined possibly: S_4(P)=Superseded. The transitions must themselves have provenance."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1377] §"the reconstruction must not imply: 'E3 was considered and found irrelevant.' It may simply have been absent. Thus: Unknown ≠ Rejected ≠ False. ... the transition itself requires evidence. ... S_0(P)=Unknown then: S_1(P)=Candidate then: S_2(P)=Supported then: S_3(P)=Determined possibly: S_4(P)=Superseded. The transitions must themselves have provenance."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1377`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1377 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1377 |
| dependencies | PRESENT | S1377 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1377]` types=[INVARIANT, FORMALIZATION] scope=THEORY-LEVEL — "Requires reconstruction to preserve absence: unrecorded evidence E3 must not be implied to have been 'considered and found irrelevant' -- Unknown != Rejected != False, restated as one of the strongest invariants. Requires uncertainty transitions to carry their own evidence (a later claim 'migration was considered low risk' cannot be assumed to overwrite an original NexusMigrationRisk=Unknown without evidence that the transition actually happened), formalized as a proposition state machine S0=Unknown -> S1=Candidate -> S2=Supported -> S3=Determined -> possibly S4=Superseded, each transition itself needing provenance. Also notes the reconstruction graph 'becomes interesting precisely because not every path must exist' -- unresolved conflicts, rejected alternatives, and unevidenced assumptions must be preserved as such, never manufactured into false completeness." (anchor: "the reconstruction must not imply: 'E3 was considered and found irrelevant.' It may simply have been absent. Thus: Unknown ≠ Rejected ≠ False. ... the transition itself requires evidence. ... S_0(P...")

## Notes for P3
- Single-row label — thin evidentiary base by construction; any relationship claims beyond this one row would be unsupported.
