# event-taxonomy-technical-observational-epistemic-governance-operational

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E_A`; `E_E`; `E_G`; `E_O`; `E_T`
**Aliases:** "five-part event taxonomy"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 191's taxonomy of five event kinds (Technical/Observational/Epistemic/Governance/Operational), the Event!=State distinction, the TransitionHistory=>Replayability principle, and the semantic event contract schema (EventID,EventType,Subject,Actor,Context,Time,PreviousState,NewState,Reason,Witness)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1394 §"### Technical event ... ### Governance event"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1394 §"### Technical event ... ### Governance event"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1394. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1394), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S1394 |
| formal_definition | PRESENT | S1394 (×2) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1394 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1394 (×2) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1394] types=[DEFINITION, FORMALIZATION] scope=THEORY-LEVEL — "Defines a five-part event taxonomy: Technical event E_T (e.g. container restarted), Observational event E_O (e.g. Nexus reports version 3.69), Epistemic event E_E (e.g. assessment changed supported->conflicted), Governance event E_G (e.g. Architecture Board approved migration), and Operational event E_A (e.g. Nexus was upgraded), chained as E_O->E_E->E_G->E_A->E_O." (anchor: "### Technical event ... ### Governance event")
- [S1394] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "An event (e.g. NexusUpgraded) records that a transition happened; the resulting state (e.g. Version=3.70) is a different object -- the event records the transition, the state represents the result." (anchor: "Event\neq State. Likewise: DecisionApproved \neq DecisionState.")
- [S1394] types=[PRINCIPLE] scope=THEORY-LEVEL — "If only the final state S_n is preserved, the transition path is lost; preserving S_0 plus the transition sequence tau_1..tau_n gives reconstructability, so TransitionHistory implies Replayability -- again framed as a requirement, with event sourcing as merely one possible implementation." (anchor: "TransitionHistory \Rightarrow Replayability.")
- [S1394] types=[DEFINITION, FORMALIZATION] scope=OBJECT, completeness PARTIAL (missing: storage/serialization specification) — "Proposes a semantic event contract schema with fields EventID, EventType, Subject, Actor, Context, Time, PreviousState, NewState, Reason, Witness; not every field need literally be stored this way, but the transition must carry enough information to be reconstructed." (anchor: "E=(EventID,EventType,Subject,Actor,Context,Time,PreviousState,NewState,Reason,Witness).")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
