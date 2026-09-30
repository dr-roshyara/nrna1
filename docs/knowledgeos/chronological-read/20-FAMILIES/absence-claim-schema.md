# absence-claim-schema

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AbsenceClaim` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0006`, scope `OBJECT`: S0231's proposed graph-native schema for an absence relation: absent_entity, locus, relationship, scope, reason, temporal_boundary -- modeled on Navya-Nyaya's four components of absence (pratiyogin/anuyogin/avacchedaka/sambandha).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0231 §"thing absent (*pratiyogin*), location (*anuyogin*), scope (*avacchedaka*), relation mode (*sambandha*)"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0231 §"thing absent (*pratiyogin*), location (*anuyogin*), scope (*avacchedaka*), relation mode (*sambandha*)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1276. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1276 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0231 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0231, S1276 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The Tarka-derived four-way absence typology (absolute, prior, subsequent, locus-relative absence, each requiring its pratiyogi/counter-correlate) supplies a principled vocabulary for S2-F024 Candidate B's finding that S1-F009's source treats "produce nothing" and "produce a rejection event" as interchangeable outcomes: without its counter-correlate, "this was never proposed" (prior absence) collapses into indistinguishability from "this was proposed and refused" (subsequent absence), which is a malformed negative claim under the typology; this connection is the reviewer's own inference, not drawn by either Session 1 or the source, and strengthens but does not change Candidate B's verdict. [S1276]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0231] types=['FORMALIZATION'] scope=OBJECT — "Proposes making absence a graph-native node ('absence-of-relation' between a Subject and Object) rather than a boolean tuple value (e.g. (Customer, owns, Contract) = false becomes an Absence Object with Subject/Relation/Object children), grounded in Navya-Nyaya's four-part analysis of absence (pratiyogin/anuyogin/avacchedaka/sambandha), formalized as an AbsenceClaim schema: absent_entity, locus, relationship, scope, reason, temporal_boundary." (anchor: "thing absent (*pratiyogin*), location (*anuyogin*), scope (*avacchedaka*), relation mode (*sambandha*)")
- [S1276] types=['EXTENSION', 'ANALYSIS'] scope=OBJECT — "The Tarka-derived four-way absence typology (absolute, prior, subsequent, locus-relative absence, each requiring its pratiyogi/counter-correlate) supplies a principled vocabulary for S2-F024 Candidate B's finding that S1-F009's source treats "produce nothing" and "produce a rejection event" as interchangeable outcomes: without its counter-correlate, "this was never proposed" (prior absence) collapses into indistinguishability from "this was proposed and refused" (subsequent absence), which is a malformed negative claim under the typology; this connection is the reviewer's own inference, not drawn by either Session 1 or the source, and strengthens but does not change Candidate B's verdict." (anchor: "Four kinds: absolute . prior . subsequent . locus-relative, each requiring its pratiyogi... a negative claim without its counter-correlate is not well-formed. "This was never proposed" and "this was proposed and refused" have different counter-correlates and a…")

## Notes for P3
- Thin evidence base (n=2 rows) — treat conclusions here as provisional.
