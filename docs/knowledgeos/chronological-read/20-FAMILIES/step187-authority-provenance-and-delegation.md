# step187-authority-provenance-and-delegation

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority subseteq Actor x Action x Context x Time (contextual and temporal, not intrinsic)`; `AuthorityClaim=(Actor,Scope,Rule,Validity,Provenance)`; `Delegates(Board,A,x)=1 required for Auth(A,x)=1 under valid delegation constraints`
**Aliases:** "authority requires provenance and is contextual/temporal like knowledge"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Discovers authority requires provenance just as knowledge does: an authority claim without its basis is epistemically incomplete, formalized AuthorityClaim=(Actor,Scope,Rule,Validity,Provenance). Formalizes delegation: Auth(Board,ApproveMigration)=1 delegated via Delegates(Board,A,ApproveMigration)=1 gives Auth(A,ApproveMigration)=1 only under valid delegation constraints, requiring AuthorityEvidence rather than a bare unexplained assertion 'A can approve.' Confirms authority is temporal (e.g. 'Person A is Board chair' only within a validity interval, Auth(A,r,t)=0 outside it, connecting directly to Step 185's T_valid!=T_known) and contextual (Auth(A,r,C1)=1 while Auth(A,r,C2)=0 for the same actor), concluding authority is not an intrinsic property of a person but a relation Authority subseteq Actor x Action x Context x Time -- 'an important DDD result.'"

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1383 §"AuthorityClaim = (Actor,Scope,Rule,Validity,Provenance). An authority assertion without its basis is epistemically incomplete. ... Delegates(Board,A,ApproveMigration)=1. Then: Auth(A,ApproveMigration)=1 only under the valid delegation constraints. ... Authority ⊆ Actor × Action × Context × Time."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1383 §"AuthorityClaim = (Actor,Scope,Rule,Validity,Provenance). An authority assertion without its basis is epistemically incomplete. ... Delegates(Board,A,ApproveMigration)=1. Then: Auth(A,ApproveMigration)=1 only under the valid delegation constraints. ... Authority ⊆ Actor × Action × Context × Time."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1383. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1383), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
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

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1383] types=[FORMALIZATION, VALIDATION] scope=THEORY-LEVEL — "Discovers authority requires provenance just as knowledge does: AuthorityClaim=(Actor,Scope,Rule,Validity,Provenance), an assertion without its basis is epistemically incomplete. Formalizes delegation (Delegates(Board,A,x)=1 required for Auth(A,x)=1 under valid delegation constraints, requiring AuthorityEvidence, not a bare assertion). Confirms authority is temporal (only valid within an interval, connecting to Step 185's T_valid!=T_known) and contextual (Auth(A,r,C1)!=Auth(A,r,C2)), concluding authority is a relation Authority subseteq Actor x Action x Context x Time, not an intrinsic personal property." (anchor: "AuthorityClaim = (Actor,Scope,Rule,Validity,Provenance). An authority assertion without its basis is epistemically incomplete. ... Delegates(Board,A,ApproveMigration)=1. Then: Auth(A,ApproveMigration)=1 only under the valid delegation constraints. ... Authority ⊆ Actor × Action × Context × Time.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
