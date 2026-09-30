# knowledge-relation-algebra

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "(source,target,relation,context,time,witness)", "R={Supports,Contradicts,Refines,Corrects,Supersedes,Derives,Causes,Authorizes,Decides,Executes}" · **Aliases:** "typed relation algebra"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope OBJECT: "Step 189's proposed typed, non-interchangeable relation vocabulary for the knowledge graph, plus the argument that a graph edge must carry (source,target,relation,context,time,witness), not just source->target."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1390 §"\mathcal R=\{Supports,Contradicts,Refines,Corrects,Supersedes,Derives,Causes,Authorizes,Decides,Executes\}."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1390 §"\mathcal R=\{Supports,Contradicts,Refines,Corrects,Supersedes,Derives,Causes,Authorizes,Decides,Executes\}."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1395. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1395), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1390 |
| informal_meaning | PRESENT | S1390 |
| formal_definition | PRESENT | S1390, S1390, S1395 |
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
The relationship itself carries domain meaning ('a major DDD finding'): a knowledge graph edge is not merely source_id->target_id but the tuple (source, target, relation, context, time, witness) -- much closer to a real domain model. [S1390]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1390] types=[FORMALIZATION, DEFINITION] scope=THEORY-LEVEL — "Proposes a typed relation algebra for the knowledge graph with ten non-interchangeable relation types {Supports, Contradicts, Refines, Corrects, Supersedes, Derives, Causes, Authorizes, Decides, Executes}; explicitly Supports(e,p) != Causes(e,p), and Authorizes(a,d) != Supports(e,d)." (anchor: "\mathcal R=\{Supports,Contradicts,Refines,Corrects,Supersedes,Derives,Causes,Authorizes,Decides,Executes\}.")
- [S1390] types=[DEFINITION, ARGUMENT] scope=OBJECT — "The relationship itself carries domain meaning ('a major DDD finding'): a knowledge graph edge is not merely source_id->target_id but the tuple (source, target, relation, context, time, witness) -- much closer to a real domain model." (anchor: "It is: (source,target,relation,context,time,witness).")
- [S1395] types=[EXTENSION, FORMALIZATION] scope=THEORY-LEVEL — "Extends the corpus's typed-relation-algebra idea with a second concrete relation set for the truth/knowledge graph -- Supports(e,p), Assesses(a,p), Determines(d,p), Justifies(e,d), Authorizes(a,d), Causes(d,o), Observes(o,r) -- explicitly richer than a generic 'related-to' edge." (anchor: "Supports(e,p) ... Assesses(a,p) ... Determines(d,p) ... Justifies(e,d) ... Authorizes(a,d) ... Causes(d,o) ... Observes(o,r).")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
