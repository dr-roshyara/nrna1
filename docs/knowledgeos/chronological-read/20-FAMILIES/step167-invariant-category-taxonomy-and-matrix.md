# step167-invariant-category-taxonomy-and-matrix

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `structural/temporal/epistemic/governance/authorization/operational/lineage invariants`, `verification matrix` · **Aliases:** `seven invariant categories (step 167)`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 167's seven-category invariant taxonomy with worked formal examples and a verification matrix (category x example invariant x candidate evidence x verification method): Structural (forall e in Evidence: Provenance(e)!=empty, verified via schema/type-system/static-or-runtime); Temporal (DecisionMade => DeterminationExists_Before(Decision); expressed via a simplified always-operator G(AuthorizationGranted -> Previously(DecisionApproved)) -- architectural guarantees can be about time, not just state); Epistemic (Established(K) => exists E: Supports(E,K) and Valid(E), i.e. Generation!=Validation, with AIOutput not implying KnowledgeEstablished -- AI output must pass Evaluation->Evidence/Verification->KnowledgeStateTransition); Governance (Approved(d) => Authority(a,d,t) for actor/decision/scope/time); Authorization (ActionExecuted => AuthorizationValid, requiring ValidAuthorization(a,action,t_execution) at the specific execution time -- 'Authorization is temporal'); Operational (ExecutionCompleted => OutcomeRecorded => ObservationCaptured); Lineage (ConsequentialState => ReconstructibleBasis, e.g. Decision->DeterminationVersion->KnowledgeVersion->Evidence), with Auditability!=Lineage (an audit log records that something happened; lineage records the full reconstructible basis of why).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1360 §"forall e in Evidence: Provenance(e) ≠ ∅. ... G(AuthorizationGranted → Previously(DecisionApproved)). ... Established(K) ⇒ ∃E: Supports(E,K) ∧ Valid(E). ... Generation ≠ Validation. ... Approved(d) ⇒ Authority(a,d,t). ... ActionExecuted ⇒ AuthorizationValid ... ValidAuthorization(a,action,t_execution). Authorization is temporal. ... ExecutionCompleted ⇒ OutcomeRecorded. ... ConsequentialState ⇒ ReconstructibleBasis. ... Auditability ≠ Lineage."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1360 §"forall e in Evidence: Provenance(e) ≠ ∅. ... G(AuthorizationGranted → Previously(DecisionApproved)). ... Established(K) ⇒ ∃E: Supports(E,K) ∧ Valid(E). ... Generation ≠ Validation. ... Approved(d) ⇒ Authority(a,d,t). ... ActionExecuted ⇒ AuthorizationValid ... ValidAuthorization(a,action,t_execution). Authorization is temporal. ... ExecutionCompleted ⇒ OutcomeRecorded. ... ConsequentialState ⇒ ReconstructibleBasis. ... Auditability ≠ Lineage."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1360. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1360 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1360 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
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
- [S1360] types=['FORMALIZATION', 'INVARIANT'] scope=THEORY-LEVEL — "Establishes a seven-category invariant taxonomy with worked formal examples: structural (forall e in Evidence: Provenance(e)!=empty), temporal (DecisionMade=>DeterminationExists_Before(Decision), expressible via a temporal-logic-like always-operator G(AuthorizationGranted -> Previously(DecisionApproved))), epistemic (Established(K)=>exists E: Supports(E,K) and Valid(E), i.e. Generation!=Validation), governance (Approved(d)=>Authority(a,d,t)), authorization (ActionExecuted=>AuthorizationValid, requiring ValidAuthorization at the specific execution time -- 'authorization is temporal'), operational (ExecutionCompleted=>OutcomeRecorded=>ObservationCaptured), lineage (ConsequentialState=>ReconstructibleBasis). Distinguishes Auditability (an audit log entry) from Lineage (the full reconstructible chain of decision->determination version->knowledge version->evidence) -- Auditability != Lineage, auditability being only part of the larger lineage problem." (anchor: "forall e in Evidence: Provenance(e) ≠ ∅. ... G(AuthorizationGranted → Previously(DecisionApproved)). ... Established(K) ⇒ ∃E: Supports(E,K) ∧ Valid(E). ... Generation ≠ Validation. ... Approved(d) ⇒ Authority(a,d,t). ... ActionExecuted ⇒ AuthorizationValid ...…")

## Notes for P3
- Single-source label (n=1 row) — evidence base is thin, no internal cross-corroboration within this capture.
