# step176-exists-knows-canretrieve

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** CanRetrieve(a,K,t), Exists(K,t), KnowledgeOS not-subset-of Actor, Knows(a,K,t) · **Aliases:** knowledge exists vs actor knows vs actor can retrieve
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 176's three-way distinction for organizational knowledge: Exists(K,t) (the knowledge exists in KnowledgeOS), Knows(a,K,t) (actor a currently knows it, e.g. a newly-started AI agent may have Knows=false), CanRetrieve(a,K,t) (actor a can retrieve it via the platform, ideally true even when Knows is false) -- these are not equivalent. States the required architectural property KnowledgeOS not-subset-of Actor (organizational knowledge sits outside any single actor -- human, AI, or system -- which merely accesses it) and generalizes the Chapter-4 theme beyond AI to humans (a new employee not knowing why a decision was made is the same IndividualKnowledge!=OrganizationalKnowledge problem), concluding 'ActorMemory may be incomplete; OrganizationalMemory must preserve governed knowledge where required.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1369] §"Exists(K,t)=true ... Knows(a,K,t)=true ... CanRetrieve(a,K,t)=true ... KnowledgeOS ⊄ Actor. The actor accesses organizational knowledge. The actor does not constitute organizational memory. ... IndividualKnowledge ≠ OrganizationalKnowledge."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1369. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1369 |
| type_signature | PRESENT | S1369 |
| invariants | PRESENT | S1369 |
| dependencies | PRESENT | S1369 |
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
- `[S1369]` types=[DEFINITION/EXTENSION] scope=THEORY-LEVEL — "Formalizes three distinct propositions for organizational knowledge K, actor a, time t: Exists(K,t), Knows(a,K,t), CanRetrieve(a,K,t) -- not equivalent (worked example: a Board decision Exists=true while a newly-started AI agent has Knows=false but ideally CanRetrieve=true). States the architectural requirement KnowledgeOS not-subset-of Actor: the actor accesses organizational knowledge but does not constitute it. Generalizes beyond AI to human organizational memory (a new employee not knowing why a past decision was made is the same IndividualKnowledge!=OrganizationalKnowledge problem), concluding 'ActorMemory may be incomplete; OrganizationalMemory must preserve governed knowledge where required.'" (anchor: "Exists(K,t)=true ... Knows(a,K,t)=true ... CanRetrieve(a,K,t)=true ... KnowledgeOS ⊄ Actor. The actor accesses organizational knowledge. The actor does not constitute organizational memory. ... IndividualKnowledge ≠ OrganizationalKnowledge.")

## Notes for P3
- Thin evidentiary base (1 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
