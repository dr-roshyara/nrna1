# step175-anchoring-invariant-and-three-query-types

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A consequential artifact must remain anchored to the epistemic state against which it was created`, `Explain(D,t)`, `WhatIsTrueNow(x) / WhyWasDecisionMade(d,t) / WhatChanged(x,t1,t2)` · **Aliases:** `anchoring invariant`, `current vs historical vs change queries`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 175 states the key invariant 'a consequential artifact must remain anchored to the epistemic state against which it was created' -- stronger than 'keep audit logs' because anchoring is semantic, audit logs are technical; illustrated by Decision1->Determination1->KnowledgeSnapshot_1 remaining fixed even as KnowledgeSnapshot_2 later exists (Decision1 must never silently become anchored to Current(K)). Defines a historical-explanation query Explain(D,t) returning {EvidenceState,KnowledgeState,Determination,Decision,Authority,Context}, distinguishing three fundamentally different query types the architecture must support: WhatIsTrueNow(x) (current-state query), WhyWasDecisionMade(d,t) (historical explanation query), and WhatChanged(x,t1,t2) (change/diff query) -- illustrated by the governance example where 'because the architecture is approved' is a circular non-answer to 'why did the Board approve this?'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1368 §"A consequential artifact must remain anchored to the epistemic state against which it was created. This is stronger than: Keep audit logs. ... Explain(D,t). ... WhatIsTrueNow(x)? ... WhyWasDecisionMade(d,t)? ... WhatChanged(x,t1,t2)?"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1368 §"A consequential artifact must remain anchored to the epistemic state against which it was created. This is stronger than: Keep audit logs. ... Explain(D,t). ... WhatIsTrueNow(x)? ... WhyWasDecisionMade(d,t)? ... WhatChanged(x,t1,t2)?"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1368. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1368 |
| dependencies | PRESENT | S1368 |
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
- [S1368] types=['INVARIANT', 'CONCEPT'] scope=THEORY-LEVEL — "States the anchoring invariant: a consequential artifact must remain anchored to the epistemic state against which it was created -- stronger than 'keep audit logs' because anchoring is semantic, audit logs are technical (Decision1->Determination1->KnowledgeSnapshot_1 must never be silently re-pointed to Current(K)). Defines a historical-explanation query Explain(D,t) returning {EvidenceState,KnowledgeState,Determination,Decision,Authority,Context}, and three distinct query types the architecture must conceptually support: WhatIsTrueNow(x) (current-state), WhyWasDecisionMade(d,t) (historical explanation), WhatChanged(x,t1,t2) (transition/diff)." (anchor: "A consequential artifact must remain anchored to the epistemic state against which it was created. This is stronger than: Keep audit logs. ... Explain(D,t). ... WhatIsTrueNow(x)? ... WhyWasDecisionMade(d,t)? ... WhatChanged(x,t1,t2)?")

## Notes for P3
(none beyond what is noted above)
