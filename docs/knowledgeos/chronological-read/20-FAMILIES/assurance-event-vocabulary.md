# assurance-event-vocabulary

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope OBJECT): The 13-event disposition (domain facts / internal / removed / read-side) for the Assurance domain.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0049] §"F-2 (MAJOR): rev 1 proposed thirteen events, rev 2 dispositioned ten ('ten' itself a miscount); three events silently dropped, one likely a fifth domain fact"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0051. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0051 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0049]` types=[CORRECTION] scope=OBJECT — "Finding F-2: the event-vocabulary disposition is incomplete and miscounted; of thirteen originally proposed events only ten were dispositioned (itself a miscount), with AssuranceClaimSuperseded, EvidenceSuperseded, and AssessmentRejected silently dropped, the first of which likely qualifies as a fifth domain fact under the approved lifecycle and event test." (anchor: "F-2 (MAJOR): rev 1 proposed thirteen events, rev 2 dispositioned ten ('ten' itself a miscount); three events silently dropped, one likely a fifth domain fact")
- `[S0049]` types=[CORRECTION] scope=OBJECT — "The reviewer self-found and disclosed an arithmetic error in its own F-2 projection (claimed counts summed to 14 instead of 13) and appended a correcting erratum rather than silently rewriting, explicitly noting it committed the same class of counting error its own finding reports." (anchor: "Erratum E-R1 ... F-2's projected disposition sums to 14; there are 13 events. Correct projection: five domain facts · SIX internal ...")
- `[S0050]` types=[DEFINITION] scope=OBJECT — "R-2 completes the event-vocabulary disposition to all thirteen originally proposed events: five domain facts (AssuranceClaimAsserted, AssuranceClaimSuperseded, AssessmentEstablished, AssessmentSuperseded, EscalationTriggerRaised), six internal, one removed as redundant (OutcomeClassified), one read-side projection (OutcomeDisclosed)." (anchor: "Result: five domain facts · six internal · one removed as redundant · one read-side — thirteen events, none dropped.")
- `[S0051]` types=[WARNING] scope=OBJECT — "Note V-1: the repair record's own supersession-accounting header does not explicitly name rev 2 §7 as superseded (only the traceability section does), creating a risk that a header-only reader could reintroduce the F-2 miscount error; flagged as a documentation-precision gap, not a substantive defect." (anchor: "V-1 ... the traceability asserts rev 2 §7's table superseded by R-2. ... only in the traceability. A reader relying on the header alone could believe rev 2 §7 still stands")

## Notes for P3
- No unusual internal tension observed across this label's 4 captured row(s); evidentiary base is proportionate to row count.
