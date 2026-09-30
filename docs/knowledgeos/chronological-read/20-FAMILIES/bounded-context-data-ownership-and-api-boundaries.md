# bounded-context-data-ownership-and-api-boundaries

**Scope(s):** CROSS-OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SharedKernel != SharedDatabase`, `View_A(K) != K` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope CROSS-OBJECT): Data ownership per bounded context, shared-database vs shared-kernel distinction, domain-semantic APIs over raw persistence schemas, and participant-relative knowledge views under access restriction and privacy.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"Shared infrastructure should not be confused with shared domain semantics. ... SharedKernel != SharedDatabase ... DomainContract > PersistenceSchema ... View_A(K) != K"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S2782) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2782 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2782]` types=[CONSTRAINT, DISTINCTION] scope=CROSS-OBJECT — "19.65-19.72: a database table shared directly by two bounded contexts (BC_A -> SharedTable <- BC_B) risks each context interpreting the same field differently -- SharedStorage->SharedMeaning without actual semantic agreement, so shared infrastructure ≠ shared domain semantics; a DDD shared kernel (intentional semantic agreement) is not the same as a shared database (merely technical coupling) -- SharedKernel≠SharedDatabase; every canonical concept should have an explicit domain-semantics owner (e.g. EvidenceContext owns evidence, DecisionContext owns decision; a decision context should not directly rewrite evidence semantics), with persistence ownership following domain ownership (SemanticOwnership->PersistenceBoundary); APIs should expose domain operations (retrieveEvidence, evaluateProposition, retractAssertion, determine) rather than raw persistence structures (DomainContract>PersistenceSchema); security metadata (access authority, classification, confidentiality, consent, legal basis, integrity status) affects Visibility(K,Gamma) but AccessRestriction≠NonExistence -- an object unavailable to participant A may still exist, giving participant-relative Knowledge Views View_A(K)≠K, View_A(K)≠View_B(K) possibly without contradiction (authorization/confidentiality/scope/purpose-limitation); provenance graphs can conflict with privacy (revealing identities/sources/relationships/access patterns), so ProvenancePreservation is itself subject to a PrivacyContract." (anchor: "Shared infrastructure should not be confused with shared domain semantics. ... SharedKernel != SharedDatabase ... DomainContract > PersistenceSchema ... View_A(K) != K")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
