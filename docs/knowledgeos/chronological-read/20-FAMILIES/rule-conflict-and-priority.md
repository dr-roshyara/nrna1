# rule-conflict-and-priority

**Scope(s):** OBJECT · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Conflict=<q,Support(q),Support(not q),Rules,Context,Provenance>, r1 > r2 · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0067 · scope OBJECT: Explicit conflict-object representation for conflicting rule firings, and the formal PriorityPolicy required before one rule may defeat another.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2785 §"Conflict = <q,Support(q),Support(not q),Rules,Context,Provenance>. ... KnowledgeOS must never infer priority merely from rule order, database insertion order, textual position, model confidence, or retrieval ranking."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2785 §"Conflict = <q,Support(q),Support(not q),Rules,Context,Provenance>. ... KnowledgeOS must never infer priority merely from rule order, database insertion order, textual position, model confidence, or retrieval ranking."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2785. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (ACTIVE) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2785 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
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
- [S2785] types=[FORMALIZATION, CONSTRAINT] scope=THEORY-LEVEL — "21.20-21.21: when rules r1:P⇒q and r2:P⇒¬q both fire, the engine must not infer arbitrary x (violating non-explosion q,¬q⊬x) but instead produce an explicit Conflict=<q,Support(q),Support(¬q),Rules,Context,Provenance> object; rule priority r1≻r2 is itself a semantic object requiring a PriorityPolicy=<Scope,Ordering,Authority,Version,Provenance>, and must never be inferred merely from rule storage order, insertion order, textual position, model confidence, or retrieval ranking." (anchor: "Conflict = <q,Support(q),Support(not q),Rules,Context,Provenance>. ... KnowledgeOS must never infer priority merely from rule order, database insertion order, textual position, model confidence, or retrieval ranking.")

## Notes for P3
(none beyond what is captured above)
