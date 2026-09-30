# retention-integrity-content-addressing-immutability

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `h(x)=Hash(x)`, `id=h(content)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Migration semantic-preservation condition, retention-vs-reconstruction conflict, cryptographic-hash-equality vs semantic-identity, content-addressing vs domain-identity, and the five distinct meanings of 'immutable'."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"A valid migration should satisfy Sem(R_1) ≡_Gamma Sem(R_2) ... RetentionContract ... HashEquality != SemanticIdentity ... ContentIdentity != DomainIdentity ... ImmutableRepresentation != ImmutableMeaning"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the ACTIVE classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
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
- [S2782] types=[CONSTRAINT, DISTINCTION] scope=THEORY-LEVEL — "19.43-19.48: distinguishes technical schema migration (Schema1->Schema2 preserving semantics) from semantic migration (Meaning1->Meaning2, which is domain evolution not mere migration); a migration is lossless under contract EC iff Loss_M∩Dist_EC(K)=∅, and UnknownLoss is itself a risk; legal/security/retention deletion requirements can conflict with historical reconstruction, requiring an explicit RetentionContract (what may be deleted/must remain/may be anonymized/what provenance and reconstruction survive) since DeletionPolicy≠EpistemicPolicy; cryptographic hash equality h(x)=h(y) establishes hashed-representation equality only, not semantic identity (HashEquality≠SemanticIdentity; semantically equivalent x≡_sem y can have h(x)≠h(y)); content-addressable id=h(content) is useful for immutable artifacts but ContentIdentity≠DomainIdentity; 'immutable' has at least 5 distinct referents (bytes/records/facts/events/claims) so ImmutableRepresentation≠ImmutableMeaning (a document can stay immutable while its interpretation changes)." (anchor: "A valid migration should satisfy Sem(R_1) ≡_Gamma Sem(R_2) ... RetentionContract ... HashEquality != SemanticIdentity ... ContentIdentity != DomainIdentity ... ImmutableRepresentation != ImmutableMean…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
