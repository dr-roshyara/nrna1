# step179-authority-is-contextual-temporal-and-delegated

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority(a,x,t,C)`, `AuthorityBasis = Role ∧ Scope ∧ Policy ∧ Time ∧ Delegation`, `B authorized because A delegated (not simply B authorized)`, `a historical decision must be evaluated against the authority state applicable when it was made` · **Aliases:** `authority as a contextual, temporal, delegated relation`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 179 formalizes Authority(a,x,t,C) over actor/action/time/organizational-context, explicitly warning simplistic RBAC is an implementation mechanism, not the complete business semantics. Requires Authorized(a,x,t) to depend on a valid AuthorityBasis(a,x,t) = Role AND Scope AND Policy AND Time AND Delegation. Introduces Delegation as a first-class concept when needed: Authority(B,x) can become true under Delegation(A,B,x), but the provenance must record 'B authorized BECAUSE A delegated', never simply 'B authorized', else the historical authority chain disappears (Authorization -> AuthorityBasis -> Delegation -> Actor). States the temporal invariant that a historical decision must be evaluated against the authority state applicable when the decision was made, not today's organizational structure, requiring Actor+Role+AuthorityState+Time together rather than ActorIdentity alone, and models emergency authority as an explicit alternate governance path (EmergencyChange->EmergencyAuthorization->Execution->RetrospectiveReview), never a silent skip (never approval=skipped, since that destroys semantic meaning).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1372] §"Authority(a,x,t,C) ... RBAC may be an implementation mechanism. It is not necessarily the complete business semantics. ... AuthorityBasis = Role ∧ Scope ∧ Policy ∧ Time ∧ Delegation. ... B authorized because: A delegated. Not simply: B authorized. Otherwise the historical authority chain disappears."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1372] §"Authority(a,x,t,C) ... RBAC may be an implementation mechanism. It is not necessarily the complete business semantics. ... AuthorityBasis = Role ∧ Scope ∧ Policy ∧ Time ∧ Delegation. ... B authorized because: A delegated. Not simply: B authorized. Otherwise the historical authority chain disappears."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1372. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1372 |
| Type signature | PRESENT | S1372 |
| Invariants | PRESENT | S1372 |
| Dependencies | PRESENT | S1372 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1372 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1372] types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Formalizes Authority(a,x,t,C) over actor/action/time/organizational-context, warning RBAC is only an implementation mechanism, not complete business semantics. Requires a valid AuthorityBasis(a,x,t) = Role AND Scope AND Policy AND Time AND Delegation for Authorized(a,x,t) to hold. Introduces Delegation as a first-class concept: Authority(B,x) becomes true under Delegation(A,B,x), but provenance must preserve 'B authorized BECAUSE A delegated' (Authorization->AuthorityBasis->Delegation->Actor), never the bare fact 'B authorized', else the historical authority chain disappears." (anchor: "Authority(a,x,t,C) ... RBAC may be an implementation mechanism. It is not necessarily the complete business semantics. ... AuthorityBasis = Role ∧ Scope ∧ Policy ∧ Time ∧ Delegation. ... B authorized because: A delegated. Not simply: B authorized. Otherwise the historical authority chain disappears.")
- [S1372] types=[INVARIANT, RESTATEMENT] scope=THEORY-LEVEL — "States the strong governance principle that a historical decision must be evaluated against the authority state applicable when the decision was made, not today's organizational structure -- requiring Actor+Role+AuthorityState+Time together, since a person's authority can change as they move roles while past decisions remain attributable to the authority state at the time. Restates the emergency-governance pattern from earlier steps (EmergencyChange->EmergencyAuthorization->Execution->RetrospectiveReview), never a silent 'approval=skipped' since that destroys semantic meaning." (anchor: "A historical decision must be evaluated against the authority state applicable when the decision was made. Not merely today's organizational structure. ... Actor + Role + AuthorityState + Time. ... EmergencyChange → EmergencyAuthorization → Execution → RetrospectiveReview. ... Not: approval = skipped because that destroys semantic meaning.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
