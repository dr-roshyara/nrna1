# natural-forced-transition-distinction

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** ChosenTransition != ForcedTransition, EventOrigin in {Agent,System,External,Natural,Scheduled,...} · **Aliases:** natural/exogenous vs agent-chosen events
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Candidate (not-yet-primitive) distinction between agent-chosen transitions and forced/natural transitions occurring when their conditions are met, raising the open governance question whether delta requires an actor/authority for every transition or can be exogenous.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2526] §"natural actions—events that occur when their conditions are satisfied and are not under the agent's free choice ... ChosenTransition \neq ForcedTransition ... EventOrigin\in\{Agent,System,External,Natural,Scheduled,...\} ... Does delta require an actor/authority for every transition, or can some transitions be exogenous?"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2527] §"Nature does not have the free will to withhold her actions; if the time and circumstances are right for a falling ball to bounce against the floor, it must bounce. ... EventOrigin \in \{Agent, External, Natural, Scheduled\}"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2536. Candidate lifecycle: ACTIVE.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2527, S2536 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2536 |
| dependencies | PRESENT | S2526 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2526 |
| examples | PRESENT | S2527 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S2526 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S2526] types=['DISTINCTION', 'OPEN-QUESTION'] scope=OBJECT — "Candidate (not-yet-primitive) distinction ChosenTransition != ForcedTransition with a candidate EventOrigin enumeration {Agent, System, External, Natural, Scheduled, ...}, raising the open governance question whether delta requires an actor/authority for every transition or admits exogenous transitions." (anchor: "natural actions—events that occur when their conditions are satisfied and are not under the agent's free choice ... ChosenTransition \neq ForcedTransition ... EventOrigin\in\{Agent,System,External,Natural,Scheduled,...\} ... Does delta require an actor/authority for every transition, or can some transitions be exogenous?")
- [S2527] types=['EXAMPLE', 'FORMALIZATION'] scope=OBJECT — "Gives the full formal executability condition distinguishing agent actions from natural actions (natural actions must occur once their preconditions hold and cannot be preempted by later agent actions), illustrated by the falling-ball example, and restates EventOrigin ∈ {Agent, External, Natural, Scheduled} (dropping 'System' from S2526's five-way enumeration). Status: [PROP]." (anchor: "Nature does not have the free will to withhold her actions; if the time and circumstances are right for a falling ball to bounce against the floor, it must bounce. ... EventOrigin \in \{Agent, External, Natural, Scheduled\}")
- [S2536] types=['FORMALIZATION', 'VALIDATION'] scope=OBJECT — "Formalizes natural actions (occurring deterministically once their preconditions/predicted times hold) with an explicit precondition example (a falling object's bounce time derived from physics), a Natural-World Assumption (all actions are natural), and a determinism theorem: under NWA, two executable concurrent-action-sets following the same situation must be identical." (anchor: "Natural actions are those that must occur at their predicted times. ... The Natural-World Assumption: (\forall a)natural(a) ... In natural worlds, the evolution is deterministic: executable(do(c,s))\land executable(do(c',s))\land NWA\supset c=c'")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
