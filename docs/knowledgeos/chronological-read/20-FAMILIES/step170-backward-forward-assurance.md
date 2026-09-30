# step170-backward-forward-assurance

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Action→Authorization→Decision→Determination→Knowledge→Evidence`, `Action→Outcome→Observation→Evidence→Knowledge'`, `Assurance = BackwardTraceability + ForwardLearning`, `Superseded != Deleted`, `ValidNow(K) != WasValidAt(K,t)` · **Aliases:** `knowledge versioning and temporal justification`, `two directions of assurance`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 170's central architectural insight: assurance has two directions -- backward traceability answering 'why did this happen?' (Action->Authorization->Decision->Determination->Knowledge->Evidence) and forward learning answering 'what did we learn from what happened?' (Action->Outcome->Observation->Evidence->Knowledge'), combined as Assurance = BackwardTraceability + ForwardLearning. Requires knowledge versioning: superseding knowledge (K^n -> K^{n+1}) must retain the old version where a past decision depended on it (Superseded != Deleted; deleting K^3 after Decision_1 used it would destroy the ability to explain why Decision_1 was reasonable at the time). Distinguishes ValidNow(K) from WasValidAt(K,t) (a claim can be InvalidNow while having been ValidAt(t0)), formalizes decision Justification(D)=<K_v,D_t,Policy_v,Authority,Context> using versions applicable AT DECISION TIME (not today's versions), giving the invariant Decision(D)=>Basis(D,t_D) -- 'temporal justification', stronger than generic auditability -- and a Reconstruct(D) test for whether the epistemic/governance basis of D at t_D can be recovered.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1363 §"Assurance = BackwardTraceability + ForwardLearning. ... Backward traceability: Action → Authorization → Decision → Determination → Knowledge → Evidence. Forward learning: Action → Outcome → Observation → Evidence → Knowledge'. ... Why did this happen? ... What did we learn from what happened?"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1363 §"Superseded ≠ Deleted. ... If K^3 is deleted, we may no longer be able to explain: Why was Decision 1 reasonable at the time? ... ValidNow(K) from: WasValidAt(K,t). ... Justification(D) = <K_v, D_t, Policy_v, Authority, Context>. A historical decision should be explainable using the versions actually applicable at decision time. Not today's versions. ... Decision(D) ⇒ Basis(D,t_D) ... temporal justification. ... Reconstruct(D) ... Can we reconstruct the relevant epistemic and governance basis of D at t_D?"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1363. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1363) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1363 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1363 |
| dependencies | PRESENT | S1363 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1363 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1363]` types=[PRINCIPLE, RESTATEMENT] scope=THEORY-LEVEL — "States what the file calls perhaps its most important architectural insight so far: Assurance = BackwardTraceability (Action->Authorization->Decision->Determination->Knowledge->Evidence, answering 'why did this happen?') + ForwardLearning (Action->Outcome->Observation->Evidence->Knowledge', answering 'what did we learn from what happened?')." (anchor: "Assurance = BackwardTraceability + ForwardLearning. ... Backward traceability: Action → Authorization → Decision → Determination → Knowledge → Evidence. Forward learning: Action → Outcome → Observation → Evidence → Knowledge'. ... Why did this happen? ... What did we learn from what happened?")
- `[S1363]` types=[INVARIANT, FORMALIZATION] scope=THEORY-LEVEL — "Requires knowledge versioning where superseding a claim (K^n -> K^{n+1}) must retain the older version if a past decision depended on it (Superseded != Deleted, else the reasonableness of that past decision becomes unexplainable). Distinguishes ValidNow(K) from WasValidAt(K,t) (a claim can be InvalidNow while ValidAt(t0)), formalizes decision Justification(D)=<K_v,D_t,Policy_v,Authority,Context> using the versions applicable at decision time (not today's), giving the invariant Decision(D)=>Basis(D,t_D) -- termed 'temporal justification', stronger than generic auditability -- and defines Reconstruct(D) as whether the epistemic/governance basis of D at t_D can be recovered." (anchor: "Superseded ≠ Deleted. ... If K^3 is deleted, we may no longer be able to explain: Why was Decision 1 reasonable at the time? ... ValidNow(K) from: WasValidAt(K,t). ... Justification(D) = <K_v, D_t, Policy_v, Authority, Context>. A historical decision should be explainable using the versions actually applicable at decision time. Not today's versions. ... Decision(D) ⇒ Basis(D,t_D) ... temporal justification. ... Reconstruct(D) ... Can we reconstruct the relevant epistemic and governance basis of D at t_D?")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
