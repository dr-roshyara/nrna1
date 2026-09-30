# ar-replay-differentiator-requires-history

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `(A,R) cannot express replay`, `(A,R)+H restores replay`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0459: [`ar-replay-differentiator-requires-history` · `state-transition-formalization`] — explicit agent-stated uncertainty: 'ar-replay-differentiator-requires-history' POSSIBLY relates to 'state-transition-formalization' (batch B0051). Note: Finding that replay is a decisive differentiator between the epistemic projection (A,R) and the full ratified K_t: (A,R) alone cannot express replay, but adding the append-only history H restores it — used as evidence that the (A,R) projection is genuinely lossy.

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0051, scope OBJECT (relation_to_existing: POSSIBLY:state-transition-formalization): Finding that replay is a decisive differentiator between the epistemic projection (A,R) and the full ratified K_t: (A,R) alone cannot express replay, but adding the append-only history H restores it — used as evidence that the (A,R) projection is genuinely lossy.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2089 §"**Replay is a decisive differentiator**: `(𝒜,ℛ)` alone cannot express replay; adding history `H` restores it."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2096 §"| **K-6 `𝒦=(K,H)`** | + replay invariants | + `Replay` (class-4) | `fold(δ,H,K_0)` | ✅ **restores replay** — the minimal repair to K-2 |"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2096. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S2096), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2089 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2096 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2089 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2089] (ARGUMENT, DISTINCTION): Identifies replay as a decisive differentiator between the projected epistemic sub-state (A,R) and the full ratified K_t: (A,R) alone cannot express replay, but adding the append-only history H restores replay capability — offered as evidence for why (A,R) is genuinely lossy relative to K_t, not merely differently organized.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2089] types=['ARGUMENT', 'DISTINCTION'] scope=OBJECT — "Identifies replay as a decisive differentiator between the projected epistemic sub-state (A,R) and the full ratified K_t: (A,R) alone cannot express replay, but adding the append-only history H restores replay capability — offered as evidence for why (A,R) is genuinely lossy relative to K_t, not merely differently organized." (anchor: "**Replay is a decisive differentiator**: `(𝒜,ℛ)` alone cannot express replay; adding history `H` restores it.")
- [S2096] types=['FORMALIZATION', 'VALIDATION'] scope=OBJECT — "K-6 (K=(K,H), state plus append-only history) adds replay invariants and a Replay operation (class-4), with state reconstructed as fold(delta,H,K_0); verdict: restores replay and is identified as the minimal repair needed to fix K-2's replay deficiency." (anchor: "| **K-6 `𝒦=(K,H)`** | + replay invariants | + `Replay` (class-4) | `fold(δ,H,K_0)` | ✅ **restores replay** — the minimal repair to K-2 |")

## Notes for P3
(none beyond what is noted above)
