# reachability-safety-horizon-model

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Reach_H(S,A) ∩ S_unsafe = ∅`; `Safety(d,H)`
**Aliases:** "Horizon-dependent reachability safety"
**Candidate group membership (NOT an identity claim):**
- G0198: links this to `decision-contract-admissibility-model` — explicit agent-stated uncertainty: 'reachability-safety-horizon-model' POSSIBLY relates to 'decision-contract-admissibility-model' (batch B0023). Note: Step 44's formalization of safety as horizon-relative reachability-set exclusion of unsafe states, extending (not replacing) the Step 42 decision-contract/safety-gate model with an explicit time horizon H and the second-order decision contract DC(d)=(Pre,Inv,Auth,Evidence,Post,Effects).

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0023, scope OBJECT: "Step 44's formalization of safety as horizon-relative reachability-set exclusion of unsafe states, extending (not replacing) the Step 42 decision-contract/safety-gate model with an explicit time horizon H and the second-order decision contract DC(d)=(Pre,Inv,Auth,Evidence,Post,Effects)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0939 §"Second-order decision contract DC(d)=(Pre,Inv,Auth,Evidence,Post,Effects); horizon-dependent reachability safety; delayed effects"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0939 §"Second-order decision contract DC(d)=(Pre,Inv,Auth,Evidence,Post,Effects); horizon-dependent reachability safety; delayed effects"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0939. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S0939), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0939 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0939 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0939] types=[FORMALIZATION, EXTENSION] scope=CROSS-OBJECT, label_confidence UNCERTAIN, also labeled `decision-contract-admissibility-model` — "Extends the Step 42 decision contract to DC(d) = (Pre, Inv, Auth, Evidence, Post, Effects), where Effects(A) = {E_1,...,E_n} covers both ExpectedEffect and PotentialSideEffects, so the system should evaluate not only the immediate postcondition but what important downstream states an action could create. Defines horizon-dependent reachability safety: an action A is safe only if Reach_H(S,A) intersect S_unsafe = empty for horizon H -- safety cannot usually be proven for t->infinity, so a finite horizon (e.g. 24h or 30 days) must be chosen, making Safety(d,H) horizon-specific (an action safe for H=1 day may be unsafe for H=2 years). Some actions have Lag(A,Y)>0 (e.g. ArchitectureChange -> MaintenanceCost manifesting months later); represents delayed causality as Y_{t+tau}=f(A_t,...) with delay tau as part of the causal model; warns that a controller reacting before a previous action's effects become visible (A_t -> S_{t+tau} -> Decision_{t+tau+1}) can overcorrect, producing oscillation (Increase->Decrease->Increase->Decrease) even when each local decision appears rational -- 'the problem lies in the dynamic system', so the decision engine should consider EffectDelay, not merely EffectExists." (anchor: "Second-order decision contract DC(d)=(Pre,Inv,Auth,Evidence,Post,Effects); horizon-dependent reachability safety; delayed effects") — lineage claim: SOURCE-CLAIMED-EXTENSION of the Step 42 decision contract DC(d)=<P,I,A,E,Q,T,O>
- [S0939] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL, label_confidence UNCERTAIN, also labeled `dynamic-causal-system-model`, also labeled `feedback-loop-stability-model`, also labeled `causal-cascade-mediation-model` — "Runs twelve falsification tests, all PASS: (1) an action changes state and future available actions => future action space recomputed; (2) a feedback loop amplifies errors => potential instability detected; (3) a feedback loop dampens errors => stabilizing behavior recognized; (4) an action has a delayed effect => causal model preserves the delay; (5) a causal cascade contains multiple paths => direct/indirect effects remain distinguishable; (6) two failures share a common cause => not treated as independent failures; (7) an action is safe immediately but unsafe over a longer horizon => safety evaluation depends on horizon; (8) an action creates an unsafe reachable state => decision gate blocks it under the relevant safety policy; (9) an intervention produces an unexpected second-order effect => outcome recorded and causal model updated, original history unchanged; (10) a hidden state is inferred from observations => state estimate retains uncertainty; (11) two causal models produce different long-term predictions => model uncertainty remains explicit; (12) a delayed feedback controller repeatedly overreacts => oscillation/instability detected as a system-level phenomenon rather than attributed to isolated decisions." (anchor: "Twelve falsification experiments for Step 44 dynamic-causal model (all PASS)")

## Notes for P3

- 2 of this label's 2 rows carry `label_confidence: UNCERTAIN` (S0939 (×2)) — treat those rows' membership in this label as provisional.
