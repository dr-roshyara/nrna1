# uncertainty-authority-interaction

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DecisionAllowed if Uncertainty<=U_max`, `I_66` · **Aliases:** `Authority does not imply Certainty`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 199's connection between authority and uncertainty: an authorized actor may decide under residual uncertainty via explicit governance policy (threshold or risk-acceptance), but acceptance of uncertainty must never be represented as its elimination (invariant I_66); the elevation of a probability into an accepted certainty must be an explicit domain-policy threshold, never a silent mathematical claim.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1417 §"Authority does not imply: Certainty. A governance rule may explicitly say: DecisionAllowed if Uncertainty<=U_max."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1417. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1417) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1417 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1417 |
| examples | PRESENT | S1417 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1417]` types=[INVARIANT, EXTENSION] scope=THEORY-LEVEL — "Connects authority (Step 196) to uncertainty: an authorized actor may decide despite uncertainty (Authority does not imply Certainty); governance rules can gate decisions on an uncertainty threshold (DecisionAllowed if Uncertainty<=U_max), or explicitly permit residual-risk acceptance (DecisionAllowed = RiskAccepted AND AuthorityValid), fundamentally different from claiming certainty." (anchor: "Authority does not imply: Certainty. A governance rule may explicitly say: DecisionAllowed if Uncertainty<=U_max.")
- `[S1417]` types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_66, called extremely important for governance: accepting a risk under uncertainty must never be represented as having eliminated the uncertainty." (anchor: "I_{66}: Acceptance of uncertainty must not be represented as elimination of uncertainty.")
- `[S1417]` types=[PRINCIPLE, EXAMPLE] scope=OBJECT — "An AI's posterior (e.g. P(H|E)=0.87) must never be silently elevated to True; any elevation (e.g. P(H|E)>=0.95 triggering CandidateAcceptance) must be an explicit policy decision, not a mathematical truth, and the acceptable threshold is domain-specific (0.9 might suffice for a low-risk recommendation but 0.9999 could be insufficient for a safety-critical decision) -- MathematicalModel != BusinessDecisionRule." (anchor: "The system must not silently transform: 0.87 into: True. There must be an explicit policy threshold if such an elevation is permitted. ... Threshold = DomainPolicy.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
