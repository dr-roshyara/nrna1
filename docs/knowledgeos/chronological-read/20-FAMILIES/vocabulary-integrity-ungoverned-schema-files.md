# vocabulary-integrity-ungoverned-schema-files

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** G-10, G-33 · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0042, scope OBJECT: The ten schema vocabulary files (e.g. statuses.yaml, authorities.yaml) that define every status and authority meaning in the running governed-knowledge system carry zero knowledge cards, no owner, no review and no lint rule; editing statuses.yaml silently changes the meaning of every governed document with no ADR. The structural profile's dedicated vocabulary-integrity check is 132/132 INCONCLUSIVE because its config file (schema/vocabulary-integrity.yaml) does not exist, so the check that would police exactly this gap is itself non-functional (G-10, G-33).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1732 §"'superseded' and 'archived' are given HIGHER order numbers than 'baseline'
  and 'frozen'."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1732. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
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
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1732 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1732] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=OBJECT — "EXP-13: statuses.yaml's single `order` integer conflates progression (1-6) and retirement (7-8) — under a monotone-order transition rule, draft→frozen (skipping 4 states) is allowed while the legitimate un-supersession superseded→approved is rejected; a covering relation (adjacency), not a single integer, is required and does not exist." (anchor: "'superseded' and 'archived' are given HIGHER order numbers than 'baseline'
  and 'frozen'.")
- [S1732] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "EXP-14: of 16 rule identifiers knowledge-lint.php can emit, it enforces referential integrity, cycle detection, single-authoritative-per-topic, orphan detection and link resolution, but has zero rules for status-transition legality and zero rules mentioning evidence or epistemic support." (anchor: "=> The linter enforces STRUCTURE")
- [S1732] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=OBJECT — "EXP-15: 4 of 5 tested theory invariants (supersedes acyclic; superseded_by converse; status=superseded implies superseded_by non-empty; single-authoritative-per-topic-context) pass only VACUOUSLY because the real data contains zero instances of the construct (population=0); only I5 (all relation targets resolve, population=52) genuinely HOLDS. The supersession/implementation relation family (adr, depends_on, implements, reviewed_by, superseded_by, supersedes, verified_by) is entirely unexercised in the only running instance available." (anchor: "=> the whole  supersession/implementation relation family is unexercised")

## Notes for P3
None beyond what is recorded above.
