# step187-transition-specific-authority-requirement

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Allowed(a,r) = Cap(a,r) ∧ Perm(a,r) ∧ AuthReq(r,a), the system should ask 'is this actor authorized to cause THIS semantic transition in THIS context?' not 'does this user have access?', transition classes R={Record,Derive,Evaluate,Determine,Approve,Decide,Execute} each with a different typical authority requirement · **Aliases:** authority requirement is per-transition-type, not global
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 187 formalizes transition admission as Allowed(a,r) = Cap(a,r) AND Perm(a,r) AND AuthReq(r,a), noting AuthReq(r) must be transition-specific -- not every transition (e.g. an automated ObservationRecorded) requires human/domain authority, so authority cannot be attached uniformly to every operation. Enumerates seven transition classes {Record, Derive, Evaluate, Determine, Approve, Decide, Execute} each with a typically different authority requirement (technical/process, model/process, defined epistemic procedure, epistemic/domain authority, governance authority, decision authority, operational authority respectively), explicitly conceptual, not yet a fixed organizational RACI. States the crucial reframing: the question is not 'does this user have access?' but 'is this actor authorized to cause THIS semantic transition in THIS context?' -- formalized Auth(a,r,c,t) rather than a bare Role(a)=Architect check.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1383] §"Allowed(a,r) = Cap(a,r) ∧ Perm(a,r) ∧ AuthReq(r,a). ... AuthReq(r) must be transition-specific. ... Record Derive Evaluate Determine Approve Decide Execute. ... 'Is this actor authorized to cause this semantic transition in this context?' ... Auth(a,r,c,t) rather than simply: Role(a)=Architect."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1383] §"Allowed(a,r) = Cap(a,r) ∧ Perm(a,r) ∧ AuthReq(r,a). ... AuthReq(r) must be transition-specific. ... Record Derive Evaluate Determine Approve Decide Execute. ... 'Is this actor authorized to cause this semantic transition in this context?' ... Auth(a,r,c,t) rather than simply: Role(a)=Architect."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1383. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1383 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1383 |
| dependencies | PRESENT | S1383 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1383 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1383] types=['FORMALIZATION', 'PRINCIPLE'] scope=THEORY-LEVEL — "Formalizes Allowed(a,r) = Cap(a,r) AND Perm(a,r) AND AuthReq(r,a), requiring AuthReq(r) to be transition-specific (not every transition, e.g. an automated observation-recording, needs human authority). Enumerates seven transition classes (Record/Derive/Evaluate/Determine/Approve/Decide/Execute) with typically different authority requirements, conceptual not yet a fixed RACI. States the crucial reframing: ask 'is this actor authorized to cause THIS semantic transition in THIS context?', formalized Auth(a,r,c,t), rather than a coarse role check." (anchor: "Allowed(a,r) = Cap(a,r) ∧ Perm(a,r) ∧ AuthReq(r,a). ... AuthReq(r) must be transition-specific. ... Record Derive Evaluate Determine Approve Decide Execute. ... 'Is this actor authorized to cause this semantic transition in this context?' ... Auth(a,r,c,t) rather than simply: Role(a)=Architect.")

## Notes for P3
- Very thin evidentiary base (row_count=1) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
