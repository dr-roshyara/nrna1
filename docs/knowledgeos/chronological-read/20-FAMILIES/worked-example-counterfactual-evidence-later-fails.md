# worked-example-counterfactual-evidence-later-fails

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `HistoricalValidity != CurrentValidity`, `Revision != Erasure` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0839**: [`reasoning-revision-and-governance` · `worked-example-counterfactual-evidence-later-fails`] — labels share the notation 'HistoricalValidity != CurrentValidity'

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "New counterfactual: post-release discovery of a payment-ledger error invalidates the current determination while preserving the historical proof."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2787 §"Proof_{t1}(pi_release)=Valid. But the current evidence may become PaymentConfirmed(S)=Retracted. ... Status_current(pi_release) = Invalidated ... pi_release in History(K). ... HistoricalValidity != CurrentValidity. And: Revision != Erasure."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2787 §"Proof_{t1}(pi_release)=Valid. But the current evidence may become PaymentConfirmed(S)=Retracted. ... Status_current(pi_release) = Invalidated ... pi_release in History(K). ... HistoricalValidity != CurrentValidity. And: Revision != Erasure."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2787. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the ACTIVE classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

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
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2787 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2787 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2787] types=[EXAMPLE, EXPERIMENT] scope=OBJECT — "21A.28 (new counterfactual): after release, discovery of a payment-ledger error retracts PaymentConfirmed(S) while the historical proof Proof_t1(pi_release)=Valid is not erased -- instead Status_current(pi_release)=Invalidated (or another contract-defined status), pi_release remains in History(K), and Determination_current(ReleasePermitted(S))=RequiresRevision, jointly demonstrating HistoricalValidity≠CurrentValidity and Revision≠Erasure." (anchor: "Proof_{t1}(pi_release)=Valid. But the current evidence may become PaymentConfirmed(S)=Retracted. ... Status_current(pi_release) = Invalidated ... pi_release in History(K). ... HistoricalValidity != Cu…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
