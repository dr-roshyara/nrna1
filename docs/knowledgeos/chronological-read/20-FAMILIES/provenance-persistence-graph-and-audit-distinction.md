# provenance-persistence-graph-and-audit-distinction

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `G_P=(V_P,E_P)`, `pi = <Origin,Agent,Method,Time,Transformation,ParentObjects,RuleVersion,Context>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Provenance as a typed directed graph and its distinction from mere audit logging.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782] §"pi = <Origin,Agent,Method,Time,Transformation,ParentObjects,RuleVersion,Context> ... Audit != Provenance ... Timestamp+User != CompleteProvenance"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782] §"pi = <Origin,Agent,Method,Time,Transformation,ParentObjects,RuleVersion,Context> ... Audit != Provenance ... Timestamp+User != CompleteProvenance"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2782 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2782 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2782] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "19.11-19.13: provenance tuple pi=<Origin,Agent,Method,Time,Transformation,ParentObjects,RuleVersion,Context> must survive persistence where required by contract -- storing only created_by/created_at (Timestamp+User) is insufficient (≠CompleteProvenance); provenance naturally forms a typed directed graph G_P=(V_P,E_P) with edges DerivedFrom/ObservedFrom/GeneratedBy/EvaluatedUsing/SupportedBy/TransformedFrom/Supersedes, where a generic parent_id may not express the relationship's meaning; audit logging ('who changed this record') answers a narrower question than provenance ('from what source/observation/transformation/model/rule/context/prior-state did this arise'), so Audit⊆PotentialProvenance in some systems but Audit≠Provenance in general." (anchor: "pi = <Origin,Agent,Method,Time,Transformation,ParentObjects,RuleVersion,Context> ... Audit != Provenance ... Timestamp+User != CompleteProvenance")

## Notes for P3
Very thin evidentiary base (1-2 rows) — classification here is provisional and should be revisited if more contributions surface.
