# step185-assertion-tuple-and-two-time-dimensions

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** A=(p,c,t,s,e): proposition,context,temporal scope,semantic scope,evidence, RealityTime x KnowledgeTime, T_valid (when proposition was true) vs T_known (when organization recorded/discovered it) · **Aliases:** assertion tuple; validity time vs knowledge time
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch BATCH-PLACEHOLDER, scope THEORY-LEVEL: Step 185 formalizes an assertion as A=(p,c,t,s,e) (proposition, context, temporal scope, semantic scope, evidence/provenance), requiring two assertions be classified by comparing all dimensions, not just whether p1=p2 (SameName != SameConcept -- 'Customer' can mean different things across bounded contexts, so semantic identity cannot be established from lexical similarity alone, particularly important for AI retrieval). Introduces a major two-dimensional temporal refinement: Validity time T_valid (when the proposition was actually true) vs Knowledge time T_known (when the organization recorded/discovered it), which can differ (worked security-vulnerability example: RealityState(Jan)=Vulnerable but KnowledgeState(Jan)=Unknown, only becoming KnowledgeState(Mar)=Known after March discovery -- 'we must not rewrite January as the organization knew it was vulnerable. It didn't.'), generalized to RealityTime x KnowledgeTime as a two-dimensional model where a proposition has both TruthAt(t_r) and KnownAt(t_k), called a major mathematical refinement over a single Knowledge(t).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1379 §"A = (p,c,t,s,e) where p = proposition; c = context; t = temporal scope; s = semantic scope; e = evidence/provenance. ... cannot be classified merely by comparing: p_1 = p_2. We must compare their dimensions. ... SameName ≠ SameConcept. ... T_{valid}. ... T_{known}. These can differ. ... RealityState(Jan)=Vulnerable but KnowledgeState(Jan)=Unknown. ... We must not rewrite January as: 'The organization knew it was vulnerable.' It didn't. ... RealityTime × KnowledgeTime."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1379 §"A = (p,c,t,s,e) where p = proposition; c = context; t = temporal scope; s = semantic scope; e = evidence/provenance. ... cannot be classified merely by comparing: p_1 = p_2. We must compare their dimensions. ... SameName ≠ SameConcept. ... T_{valid}. ... T_{known}. These can differ. ... RealityState(Jan)=Vulnerable but KnowledgeState(Jan)=Unknown. ... We must not rewrite January as: 'The organization knew it was vulnerable.' It didn't. ... RealityTime × KnowledgeTime."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1379. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1379 |
| type_signature | PRESENT | S1379 |
| invariants | PRESENT | S1379 |
| dependencies | PRESENT | S1379 |
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

## All rows
- [S1379] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Formalizes an assertion tuple A=(p,c,t,s,e) requiring dimension-by-dimension comparison (not just proposition equality) to classify transitions, since SameName != SameConcept ('Customer' can differ across bounded contexts, so lexical similarity alone cannot establish semantic identity, particularly for AI retrieval). Introduces the two-dimensional temporal model RealityTime x KnowledgeTime: Validity time T_valid (when a proposition was actually true) vs Knowledge time T_known (when the organization recorded/discovered it), worked through a security-vulnerability example where RealityState(Jan)=Vulnerable but KnowledgeState(Jan)=Unknown, only becoming Known in March -- 'we must not rewrite January as the organization knew it was vulnerable. It didn't.'" (anchor: "A = (p,c,t,s,e) where p = proposition; c = context; t = temporal scope; s = semantic scope; e = evidence/provenance. ... cannot be classified merely by comparing: p_1 = p_2. We must compare their dimensions. ... SameName ≠ SameConcept. ... T_{valid}. ... T_{known}. These can differ. ... RealityState(Jan)=Vulnerable but KnowledgeState(Jan)=Unknown. ... We must not rewrite January as: 'The organization knew it was vulnerable.' It didn't. ... RealityTime × KnowledgeTime.")

## Notes for P3
None beyond what is recorded above.
