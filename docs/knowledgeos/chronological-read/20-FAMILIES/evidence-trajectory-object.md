# evidence-trajectory-object

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EvidenceTrajectory`
**Aliases:** "change as evidence-bearing structure"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Proposed object (observations, temporal ordering, transitions, environmental changes, inferred pattern) treating a sequence of observations over time as evidence-bearing in a way a single accurate snapshot is not (example: 99.9% overall availability masking a declining monthly trend 99.99%->99.4%)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0452 §"A snapshot can be perfectly accurate and still be misleading. ... January -> 99.99% ... April -> 99.4% ... change as evidence-bearing structure."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0452 §(same anchor)]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0452. Candidate lifecycle: DORMANT. Evidence: no retraction, supersession, or self-contradiction recorded — this is a heuristic based on how long ago (by source_id) this label was last used, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0452 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0452 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty; the row is typed CONCEPT/EXAMPLE).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0452] types=[CONCEPT, EXAMPLE] scope=OBJECT — "Treats change itself as evidence-bearing structure, not merely a sequence of snapshots, illustrated by a true 99.9% overall-availability snapshot masking a declining monthly trend; proposes an EvidenceTrajectory object (observations, temporal ordering, transitions, environmental changes, inferred pattern), explicitly flagged as a conceptual discovery, not an implementation proposal." Declared dependency: `evidence-trajectory-object` (self-referential in the row data). (anchor as above, file `20260824-033614-zero-and-chinese-lenses-on-minimum-structure-before-evidence.md`)

## Notes for P3
Single-row label. The row's own `dependencies` field lists the label itself (`evidence-trajectory-object`) as a dependency, which is a self-reference in the derived data rather than a link to a distinct object — likely a data artifact of the extraction pipeline rather than a genuine dependency; flagging for P3 rather than silently dropping it. The source explicitly states this was "a conceptual discovery, not an implementation proposal" [S0452] — worth preserving that framing since it bears on how much operational weight this object should be given.
