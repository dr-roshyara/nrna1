# step187-four-relations-cap-perm-auth-resp

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Auth = f(Role,Scope,Delegation,Rule,t,c), f belongs to governance, not the kernel, Cap(a,x); Perm(a,x,c,t); Auth(a,d,c,t); Resp(a,d,c,t), Cap=>Perm=>Auth=>Resp implication chain is FALSE · **Aliases:** capability/permission/authority/responsibility as four independent relations
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0033 · scope THEORY-LEVEL: Step 187 formalizes the four Step-186-proposed concepts as distinct parameterized relations: Cap(a,x) (technical capability, e.g. Cap(AI,WriteKnowledgeRecord)=1), Perm(a,x,c,t) (access-control permission, e.g. an AI permitted to CreateCandidate but not ApproveDecision), Auth(a,d,c,t) (legitimate domain/governance authority over a determination d, with Perm(a,x) not implying Auth(a,d) -- worked DBA example: write permission without architecture-approval authority), Resp(a,d,c,t) (accountability, with Auth(a,d) not implying Resp(a,d) -- a board may hold authority while a named role holds operational responsibility). Explicitly rejects the common architectural mistake of assuming a linear implication chain Cap=>Perm=>Auth=>Resp as FALSE -- these may correlate but must never be inferred from one another. Formalizes Auth(a,r,c,t)=f(Role,Scope,Delegation,Rule,t,c), stating the function f belongs to the governance model, not the mathematical kernel itself, and that a Role alone (e.g. Role(A)=Architect) does not imply blanket authority.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1383 §"Cap(a,x) ... Perm(a,x,c,t) ... Auth(a,d,c,t) ... Resp(a,d,c,t) ... Cap ⇒ Perm ⇒ Auth ⇒ Resp. This implication chain is false. ... Auth(a,r,c,t) = f(Role,Scope,Delegation,Rule,t,c). The function f belongs to the governance model, not the mathematical kernel itself."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1383 §"Cap(a,x) ... Perm(a,x,c,t) ... Auth(a,d,c,t) ... Resp(a,d,c,t) ... Cap ⇒ Perm ⇒ Auth ⇒ Resp. This implication chain is false. ... Auth(a,r,c,t) = f(Role,Scope,Delegation,Rule,t,c). The function f belongs to the governance model, not the mathematical kernel itself."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1383. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1383 |
| type_signature | PRESENT | S1383 |
| invariants | PRESENT | S1383 |
| dependencies | PRESENT | S1383 |
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
- [S1383] types=[FORMALIZATION, INVARIANT] scope=THEORY-LEVEL — "Formalizes Capability, Permission, Authority, Responsibility as four independent parameterized relations Cap(a,x), Perm(a,x,c,t), Auth(a,d,c,t), Resp(a,d,c,t), with worked examples (a DBA with write permission lacking approval authority; a board holding authority while a role holds operational responsibility). Explicitly rejects the implication chain Cap=>Perm=>Auth=>Resp as FALSE. Formalizes Auth(a,r,c,t)=f(Role,Scope,Delegation,Rule,t,c), with f belonging to the governance model, not the mathematical kernel." (anchor: "Cap(a,x) ... Perm(a,x,c,t) ... Auth(a,d,c,t) ... Resp(a,d,c,t) ... Cap ⇒ Perm ⇒ Auth ⇒ Resp. This implication chain is false. ... Auth(a,r,c,t) = f(Role,Scope,Delegation,Rule,t,c). The function f belongs to the governance model, not the mathematical kernel itself.")

## Notes for P3
(none beyond what is captured above)
