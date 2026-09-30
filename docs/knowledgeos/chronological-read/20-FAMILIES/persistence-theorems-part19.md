# persistence-theorems-part19

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Theorem 19.1-19.4 · **Aliases:** Contractual Reconstruction, History Preservation Theorem, Projection Adequacy, Semantic Migration Theorem
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0067 · scope THEORY-LEVEL: Part XIX's four theorems on reconstruction, projection adequacy, history-preserving replay, and semantic-preserving migration.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"Theorem 19.1 — Contractual Reconstruction ... Theorem 19.2 [Projection Adequacy] ... Theorem 19.3 [History Preservation] ... Theorem 19.4 [Semantic Migration]"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782 §"Theorem 19.1 — Contractual Reconstruction ... Theorem 19.2 [Projection Adequacy] ... Theorem 19.3 [History Preservation] ... Theorem 19.4 [Semantic Migration]"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (ACTIVE) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2782 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
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
- [S2782] types=[FORMALIZATION] scope=THEORY-LEVEL — "19.77-19.80: Theorem 19.1 (Contractual Reconstruction) -- if persistence preserves all EC-required distinctions/provenance/temporal/version/history information, a reconstruction function Rec_EC exists with Rec_EC(P(K_t))≡_EC K_t (semantic equivalence, not structural identity -- Rec(P(K))=K is 'unnecessarily strong'); Theorem 19.2 (Projection Adequacy) -- a query-Q projection R_Q(K) is contractually adequate for Q iff Loss_Q∩Dist_EC(Q)=∅, i.e. a projection need only preserve what its query contract requires; Theorem 19.3 (History Preservation) -- if persistence stores a transition o with all semantic replay dependencies, Replay(H_t,V_t)≡_Gamma K_t under the applicable replay contract; Theorem 19.4 (Semantic Migration) -- a migration M:R1->R2 is semantically preserving iff Sem(R1)≡_Gamma Sem(R2) for all required contracts, otherwise it represents semantic change or loss, so SchemaMigration must be evaluated separately from SemanticMigration." (anchor: "Theorem 19.1 — Contractual Reconstruction ... Theorem 19.2 [Projection Adequacy] ... Theorem 19.3 [History Preservation] ... Theorem 19.4 [Semantic Migration]")

## Notes for P3
(none beyond what is captured above)
