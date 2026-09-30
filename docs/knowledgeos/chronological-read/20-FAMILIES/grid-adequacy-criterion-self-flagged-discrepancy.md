# grid-adequacy-criterion-self-flagged-discrepancy

**Scope(s):** METHODOLOGICAL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `span >= gate width`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope METHODOLOGICAL: "A self-caught discrepancy between a frozen methodological rule and its actual code implementation, flagged rather than silently resolved."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2812 §"I softened my own frozen criterion in code. KR-ZOOM-OUT-02 §6.2 froze 'a grid whose span on the gated quantity is smaller than the gate's own width is not a grid' -- literally span >= 0.50. My sensitivity.py applied half the gate width per parameter and printed RULE SATISFIED: True. Those are not th[e same test]"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2812 (single row/single source). Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`) — notably, despite the row's own content being a self-flagged rule-vs-implementation discrepancy, no `contested_by_own_contradiction_type` flag was set for this label; see Notes for P3.

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
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

Note: every dimension shows NOT-EVIDENCED-IN-CAPTURE per the mechanically-derived `completeness` dict, even though the single row is itself rich (a CORRECTION/LIMITATION type row with a substantive self-critique) — this reflects the completeness classifier's dimension criteria rather than an actual absence of content; see the row text below and Notes for P3.

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2812] types=[CORRECTION, LIMITATION] scope=METHODOLOGICAL — "Self-catches a silent softening of a previously frozen methodological rule: KR-ZOOM-OUT-02 section 6.2 literally required a grid's joint span on the gated quantity to be at least the gate's own width (>=0.50), but the sensitivity code actually applied and passed a softer per-parameter half-gate-width test -- these are not the same test, and the softening was introduced silently in code; the discrepancy is explicitly surfaced rather than let stand: under the strict literal reading the new 27-point grid FAILS (joint span 0.2767<0.50), under an operational reading (whether the grid reaches inside the band at all) it PASSES (24/27 points in band); the decision on which reading to adopt is explicitly deferred to the human owner rather than resolved unilaterally." (anchor: "I softened my own frozen criterion in code. KR-ZOOM-OUT-02 §6.2 froze 'a grid whose span on the gated quantity is smaller than the gate's own width is not a grid' -- literally span >= 0.50. My sensitivity.py applied half the gate width per parameter and printed RULE SATISFIED: True. Those are not th")

## Notes for P3

- This label documents a genuine, self-caught methodological discrepancy — a frozen rule (KR-ZOOM-OUT-02 §6.2, requiring joint span ≥ gate width) was silently applied more weakly in actual code (a per-parameter half-gate-width test). The row itself reports the strict reading FAILS (0.2767 < 0.50) while the operational reading PASSES (24/27 points in band), and explicitly defers the choice of reading to the human owner rather than resolving it. P3 should treat this as an open governance decision, not a settled DORMANT/ACTIVE classification — the mechanical `lifecycle_evidence.contested_by_own_contradiction_type: false` appears not to have picked up this self-contradiction (a CORRECTION-type row describing a rule/implementation mismatch), which may indicate a gap in that heuristic's detection scope worth flagging upstream.
- The anchor text is truncated mid-word ("Those are not th") in the source capture — P3 or a later pass may want to verify the full sentence against the original document (`docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-074100_kr-zoom-out-02-sensitivity-demonstration-and-final-disposition.md`) if greater precision is needed, though per this phase's rules that file was not re-read here.
