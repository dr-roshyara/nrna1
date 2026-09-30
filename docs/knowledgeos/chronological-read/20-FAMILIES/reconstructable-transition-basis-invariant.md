# reconstructable-transition-basis-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I_34`; `Transition(tau)=>Basis(tau)!=empty`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 190's candidate constitutional invariant I_34: no semantically meaningful state transition may occur without a reconstructable basis (evidence, rule, authority, observation, or explicit correction)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1392 §"Historical transitions must be reconstructable."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1392 §"I_34: No semantically meaningful state transition without a reconstructable transition basis."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1396. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1396), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1392, S1396 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1392, S1396 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1392 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1392 (×2), S1396 (×2) |
| examples | PRESENT | S1396 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Explicitly resists jumping to 'therefore KnowledgeOS should be event sourced': the actual architectural requirement is the more abstract 'historical transitions must be reconstructable' (Reconstructability); event sourcing is one possible implementation, not itself an architectural invariant -- keeping the requirement separate from any specific technology. [S1392] Distinguishes StateView (Projection(H_t), what a current observer sees) from LineageView (full H_t, a privileged historical observer's access), noting this maps naturally onto authorization -- not every actor needs complete lineage access. [S1396]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1392] types=[ARGUMENT, DISTINCTION] scope=THEORY-LEVEL — "Explicitly resists jumping to 'therefore KnowledgeOS should be event sourced': the actual architectural requirement is the more abstract 'historical transitions must be reconstructable' (Reconstructability); event sourcing is one possible implementation, not itself an architectural invariant -- keeping the requirement separate from any specific technology." (anchor: "Historical transitions must be reconstructable.")
- [S1392] types=[PRINCIPLE] scope=THEORY-LEVEL — "Deeper cross-case principle: every meaningful state transition must preserve the semantic reason (evidence, rule, authority, delegation, observation, correction, or temporal validity) why the transition was allowed, and that reason must remain reconstructable." (anchor: "Every meaningful state transition must preserve the semantic reason why the transition was allowed.")
- [S1392] types=[INVARIANT, FORMALIZATION] scope=THEORY-LEVEL — "Promotes a new candidate major architectural invariant I_34: Transition(tau) => Basis(tau) != empty-set, where Basis(tau) = Evidence OR Rule OR Authority OR Observation OR ExplicitCorrection depending on transition type." (anchor: "I_34: No semantically meaningful state transition without a reconstructable transition basis.")
- [S1396] types=[PRINCIPLE, EXAMPLE] scope=OBJECT — "A correction discovered later must never rewrite the original observation's timestamp; instead a new CorrectionEvent references the original ObservationEvent, preserving both records -- the temporal instance of the corpus's general correction-preserves-history principle." (anchor: "CorrectionEvent(T_c=12:00) references: ObservationEvent(T_o=09:00). This preserves history.")
- [S1396] types=[FORMALIZATION, RESTATEMENT] scope=THEORY-LEVEL — "Makes the recurring Chapter-4 'new state doesn't know the old state' insight temporally precise: given transition history H_t={tau_1..tau_n}, the current state S_t=Projection(H_t) is information-lossy relative to H_t; restated without theological framing as 'a state representation need not be sufficient to reconstruct its own causal history.'" (anchor: "S_t = Projection(H_t). But: S_t \not\Rightarrow H_t. Therefore: CurrentState is information-lossy relative to full history.")
- [S1396] types=[EXTENSION, ARGUMENT] scope=OBJECT — "Distinguishes StateView (Projection(H_t), what a current observer sees) from LineageView (full H_t, a privileged historical observer's access), noting this maps naturally onto authorization -- not every actor needs complete lineage access." (anchor: "StateView from: LineageView. This maps naturally to authorization as well. Not every actor needs access to complete lineage.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
