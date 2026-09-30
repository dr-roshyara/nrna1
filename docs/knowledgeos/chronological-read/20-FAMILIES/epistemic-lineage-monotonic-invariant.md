# epistemic-lineage-monotonic-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I_37` · **Aliases:** `EpistemicState non-monotonic, Lineage monotonic`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 192's invariant I_37: updating epistemic state must never destroy previously valid lineage; current epistemic status can move non-monotonically but the historical record only grows.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1395 §"CurrentKnowledge \neq CompleteEpistemicHistory."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1395 §"I_37: Updating epistemic state must not destroy previously valid lineage."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1395. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1395 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1395, S1395 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1395 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1395] types=['INVARIANT'] scope=THEORY-LEVEL — "Applies the recurring Chapter-4 lens: K_{t+1}(p) does not imply the current knowledge state contains all information from K_{0:t}(p); CurrentKnowledge != CompleteEpistemicHistory, so history must remain separately reconstructable." (anchor: "CurrentKnowledge \neq CompleteEpistemicHistory.")
- [S1395] types=['DISTINCTION', 'PRINCIPLE'] scope=THEORY-LEVEL — "The beautiful distinction underlying I_37: current epistemic status S_t->S_{t+1} can move non-monotonically, but the historical record only grows (H_{t+1}=H_t union {event_{t+1}}) -- EpistemicState is non-monotonic while Lineage is monotonic, framed as possibly one of the deepest KnowledgeOS invariants." (anchor: "EpistemicState is non-monotonic; Lineage is monotonic.")
- [S1395] types=['INVARIANT', 'FORMALIZATION'] scope=THEORY-LEVEL — "Formal invariant I_37: if S1=Supported at t1 and later S2=Refuted at t2, the system must preserve both (S1,t1,E1) and (S2,t2,E2) rather than overwriting the earlier record." (anchor: "I_37: Updating epistemic state must not destroy previously valid lineage.")

## Notes for P3
(none beyond what is noted above)
