# step176-orthogonal-access-authority-lattice

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Access !=> Authority`, `CanRead(AI,Decision) !=> CanChange(AI,Decision); CanChange(K) !=> CanAuthorize(Action)`, `Owns(a,K)/CanAccess(a,K)/HasInContext(a,K)/CanUse(a,K)` · **Aliases:** `four knowledge relationships; access does not imply authority`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 176 distinguishes four relationships between an actor and a knowledge artifact -- Owns(a,K), CanAccess(a,K), HasInContext(a,K), CanUse(a,K) -- e.g. a human may own a decision while an AI can access but not use it for a given action. Adds a new epistemic dimension, KnowledgeAccess, alongside the existing Identity/Capability/Responsibility/Authority/Accountability five-dimensional model, explicitly not promoted to a sixth governance role but a distinct concern. States Access does not imply Authority as a non-linear lattice (Authority sits above both Capability and Responsibility, which sit above Knowledge, which sits above Access -- though the dimensions are largely orthogonal, not a strict chain), with worked examples: CanRead(AI,Decision_1) does not imply CanChange(AI,Decision_1); CanChange(K) does not imply CanAuthorize(Action); an AI with Access+CodeModification capability can still lack ProductionDeployment authority, while a human release authority can have that authority without code-modification capability -- justifying explicit authorization rather than relying on technical permissions alone.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1369 §"Owns(a,K) CanAccess(a,K) HasInContext(a,K) CanUse(a,K). These are different. ... Access ⇏ Authority. ... CanRead(AI,Decision_1)=true. does not imply: CanChange(AI,Decision_1)=true. And: CanChange(K)=true does not imply: CanAuthorize(Action)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1369 §"Owns(a,K) CanAccess(a,K) HasInContext(a,K) CanUse(a,K). These are different. ... Access ⇏ Authority. ... CanRead(AI,Decision_1)=true. does not imply: CanChange(AI,Decision_1)=true. And: CanChange(K)=true does not imply: CanAuthorize(Action)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1369. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1369) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1369 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
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

- `[S1369]` types=[FORMALIZATION, INVARIANT] scope=THEORY-LEVEL — "Distinguishes four actor-to-knowledge relationships -- Owns(a,K), CanAccess(a,K), HasInContext(a,K), CanUse(a,K) -- e.g. a human owns a decision while an AI can access but not use it for an action. Adds a new orthogonal epistemic dimension KnowledgeAccess alongside Identity/Capability/Responsibility/Authority/Accountability, not promoted to a sixth governance role. States Access does not imply Authority as a non-strict lattice (Authority above Capability/Responsibility above Knowledge above Access, largely orthogonal), with worked non-implications CanRead(AI,Decision1) does not imply CanChange(AI,Decision1), and CanChange(K) does not imply CanAuthorize(Action); worked AI/human example: AI with Access+CodeModification capability lacks ProductionDeployment authority, while a human release authority has that authority without code-modification capability." (anchor: "Owns(a,K) CanAccess(a,K) HasInContext(a,K) CanUse(a,K). These are different. ... Access ⇏ Authority. ... CanRead(AI,Decision_1)=true. does not imply: CanChange(AI,Decision_1)=true. And: CanChange(K)=true does not imply: CanAuthorize(Action).")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
