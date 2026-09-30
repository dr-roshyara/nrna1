# temporal-role-refinement

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `TemporalRole in {Valid,Observed,Recorded,Decided,Executed,Corrected}` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 193's refinement of the generic Transition tuple's 'time' field into a typed TemporalRole, since not every transition instantiates every temporal role.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1396] §"\tau_i=(S_i^-,S_i^+,t_i,W_i,Rule_i,Actor_i)"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1396] §"\tau_i=(S_i^-,S_i^+,t_i,W_i,Rule_i,Actor_i)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1396. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1396 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1396] types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Refines the earlier generic Transition=(sourceState,targetState,rule,witness,time,actor) tuple: 'time' is not one universal scalar but has semantic dimensions, so Time is refined into TemporalRole, with a candidate role set {Valid,Observed,Recorded,Decided,Executed,Corrected} -- not every transition needs every role (an observation may carry T_v,T_o,T_k but no T_d; a decision carries T_d but does not itself establish T_v)." (anchor: "\tau_i=(S_i^-,S_i^+,t_i,W_i,Rule_i,Actor_i)")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. Single-row label: the evidentiary base is thin by construction (one contribution) — treat every dimension marked NOT-EVIDENCED-IN-CAPTURE above as simply unobserved in this capture, not as absent from the underlying idea.
