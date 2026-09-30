# authority-scoped-temporal-delegation-model

**Scope(s):** THEORY-LEVEL · **Row count:** 9 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority(a,C,O,t)`, `AuthorityGrant`, `G_A=(V,E_A)` · **Aliases:** `authority graph`, `delegation chain`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 196's model of authority as scoped (per bounded context), temporal (can expire, invariant I_53 on non-retroactive revocation), delegated (chains bounded by invariant I_52), and provenance-bearing (a governed AuthorityGrant fact with grantedBy lineage), plus a separate typed Authority Graph and the PersonIdentity != InstitutionalIdentity extension.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1414] §"Authority(a,C,O,t) ... Authority_A(a)\neq Authority_B(a). ... This is especially important in DDD."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1414] §"Authority(a,C,O,t) ... Authority_A(a)\neq Authority_B(a). ... This is especially important in DDD."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1414. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1414 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1414 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1414 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1414] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Authority is scoped, not global: Authority(actor,context,operation,time) can be true in one bounded context and false in another for the same actor -- a DDD-critical distinction." (anchor: "Authority(a,C,O,t) ... Authority_A(a)\neq Authority_B(a). ... This is especially important in DDD.")
- [S1414] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Authority can expire (a piecewise time-indexed function tied to a mandate end time T_e) and is modeled as a governed AuthorityGrant fact with fields Actor, Scope, Capability, ValidFrom, ValidUntil, GrantingAuthority -- 'not merely an RBAC table, a governed fact', connecting authority to the temporal model of Step 193." (anchor: "Authority(a,o,t)= True if t<T_e, False if t>=T_e unless renewed. ... AuthorityGrant = (Actor,Scope,Capability,ValidFrom,ValidUntil,GrantingAuthority).")
- [S1414] types=[EXTENSION, DEFINITION] scope=OBJECT — "Authority itself must have provenance -- every AuthorityGrant traces via grantedBy to an AuthoritySource, which itself may require its own authority, forming an explicit delegation chain (e.g. Board -> ChiefArchitect -> Architect), giving rise to an authority lineage." (anchor: "AuthorityGrant \xrightarrow{grantedBy} AuthoritySource. ... This creates a delegation chain.")
- [S1414] types=[CONSTRAINT] scope=OBJECT — "Delegation must be scope-monotone: a delegated authority's scope must be a subset of its source authority's scope, or a structural inconsistency arises where the delegate has more authority than the delegator." (anchor: "Scope(DelegatedAuthority) \subseteq Scope(SourceAuthority). Otherwise: Delegate would acquire more authority than the delegator possesses.")
- [S1414] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_52: delegated authority must never exceed the scope, duration, or nature of its source authority." (anchor: "I_52: Delegated authority must not exceed the scope, duration, or nature of the authority from which it derives.")
- [S1414] types=[DISTINCTION, EXTENSION] scope=OBJECT — "Applies the Chapter-2 continuity lens to institutions: an institutional role (e.g. 'the Board') can persist across membership turnover (Board_2025 -> Board_2026), so PersonIdentity != InstitutionalIdentity; individuals act on behalf of an institution via an actsFor relation that itself needs temporal validity." (anchor: "PersonIdentity \neq InstitutionalIdentity. ... Actor \xrightarrow{actsFor} Institution.")
- [S1414] types=[INVARIANT, PRINCIPLE] scope=THEORY-LEVEL — "Knowledge transmission and authority transmission are categorically different: Knowledge(A)->Knowledge(B) never implies Authority(A)->Authority(B); a new generation/successor can inherit knowledge without automatically inheriting authority, or vice versa, and delegation must always be explicit -- named a possibly strongest Chapter-4 architectural lesson." (anchor: "Transmission(Knowledge) \neq Transmission(Authority). If authority is delegated, that delegation must be explicit.")
- [S1414] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines a separate typed Authority Graph G_A with edge types grants, delegates, actsFor, revokes, constrains -- a semantic graph, not necessarily a graph database, extending the corpus's multi-graph architecture." (anchor: "G_A=(V,E_A) where edges represent: grants, delegates, actsFor, revokes, constrains.")
- [S1414] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_53: revoking an authority affects only future transitions; past decisions that were validly authorized when executed remain historically valid -- revocation is never retroactive historical erasure by default." (anchor: "Revocation \neq HistoricalErasure. ... I_53: Authority changes affect future authorization but do not retroactively invalidate historically authorized transitions unless an explicit domain rule requires it.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
