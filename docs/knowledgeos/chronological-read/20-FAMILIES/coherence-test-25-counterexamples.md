# coherence-test-25-counterexamples

**Scope(s):** THEORY-LEVEL · **Row count:** 13 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `25 tests, Step 200` · **Aliases:** `senior mathematician + statistician + DDD architect review`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 200's twenty-five deliberate counterexample tests against the accumulated formal architecture (states, transitions, knowledge, authority, evidence, uncertainty, process, lineage), all passing without a fundamental contradiction."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1418 §"Consistency+Composability+Falsifiability+DDD viability ... K=(S,T,E,H,A,U,G,L,I). ... Now we try to break it."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1418. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1418 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1418 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1418 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1418 |
| examples | PRESENT | S1418 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Assembles the candidate formal system K=(States,Transitions,Evidence,Hypotheses,Authority,Uncertainty,Governance,Lineage,Invariants) from all preceding steps and sets the evaluation standard as Consistency+Composability+Falsifiability+DDD-viability (not elegance), then deliberately attempts to break it with counterexamples. [S1418]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1418] types=[EXPLANATION, DEFINITION] scope=THEORY-LEVEL — "Assembles the candidate formal system K=(States,Transitions,Evidence,Hypotheses,Authority,Uncertainty,Governance,Lineage,Invariants) from all preceding steps and sets the evaluation standard as Consistency+Composability+Falsifiability+DDD-viability (not elegance), then deliberately attempts to break it with counterexamples." (anchor: "Consistency+Composability+Falsifiability+DDD viability ... K=(S,T,E,H,A,U,G,L,I). ... Now we try to break it.")
- [S1418] types=[VALIDATION, EXAMPLE] scope=OBJECT — "Test 1 (pass): initial states can exist without a prior transition, but creation should itself normally be represented as a governed transition (empty-set -create-> S0)." (anchor: "State\not\Rightarrow Transition. ... InitialState = ResultOfInitialTransition where appropriate.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Test 2 (pass): a transition (e.g. a Review) can occur without changing domain state, since process state and lineage still change -- reconfirms Step 198's I_62." (anchor: "S_{t+1}=S_t does not imply: \tau=\varnothing. This passes.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Tests 3-5 (all pass): knowledge can exist without certainty (P(H|E)=0.72 is still useful knowledge), knowledge can exist without authority (an engineer's observation/evidence/assessment confers no governance authority), and authority can exist without knowledge (an executive can hold decision authority while relying on others for evidence) -- explicitly guards against accidentally equating organizational authority with epistemic superiority." (anchor: "Knowledge \supseteq UncertainKnowledge. ... Knowledge\neq Certainty. Pass. ... Knowledge \not\Rightarrow Authority. Pass. ... Authority \not\Rightarrow Knowledge. Pass.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Test 6 (pass): uncertainty greater than zero does not forbid action; ActionAllowed is a function of Risk, Authority, Policy, and Uncertainty jointly." (anchor: "ActionAllowed = f(Risk,Authority,Policy,Uncertainty). Pass.")
- [S1418] types=[VALIDATION, INVARIANT] scope=THEORY-LEVEL — "Test 7 (pass, called essential): an authorized decision can be wrong, since Authorized(d) implies neither True(d) nor GoodOutcome(d) -- Authorization != Correctness, preventing a major category error." (anchor: "Authorized(d) does not imply: True(d). Nor does it imply: GoodOutcome(d). Therefore: Authorization\neq Correctness.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Test 9 (pass): evidence can contradict evidence (E1 implies H, E2 implies not-H); the architecture must permit this Conflict state without forcing a premature resolution." (anchor: "Conflict(E_1,E_2)=True. The architecture must permit inconsistent observations without immediately forcing a false resolution. Pass.")
- [S1418] types=[VALIDATION, DISTINCTION] scope=OBJECT — "Tests 12-13: unbounded lineage growth is not mathematically problematic but is operationally costly, requiring lifecycle/retention policies that must never silently violate domain obligations; distinguishes OperationalState (may be compacted) from HistoricalRecord (may require stronger retention) -- Compaction != HistoricalDeletion." (anchor: "Lineage requires lifecycle policies. However: RetentionPolicy must not silently violate domain obligations. ... Compaction \neq HistoricalDeletion.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Tests 14-15 (pass): a proposition's identity persists while its epistemic status changes over time (Candidate->Supported->Refuted); and the same fixed evidence E can legitimately yield different assessments under different models M1 vs M2." (anchor: "Identity(H) must remain distinct from: Status(H,t). Pass. ... P(H\mid E,M_1) \neq P(H\mid E,M_2). This is mathematically legitimate.")
- [S1418] types=[VALIDATION, RESTATEMENT] scope=THEORY-LEVEL — "Tests 17-18 (pass): an AI's output does not automatically qualify as evidence (requires validation), and AI can technically make a decision but only if explicitly granted Authority(AI,d)=True -- reconfirming AI capability does not imply AI authority." (anchor: "AIOutput \not\Rightarrow Evidence. Instead: AIOutput \xrightarrow{Validation} Evidence when the context permits it. ... AI capability does not imply AI authority.")
- [S1418] types=[VALIDATION] scope=OBJECT — "Tests 19-20 (pass): delegated authority stays scope/duration-bounded by its source (reconfirms I_52), and revocation stops future validity while preserving historical authorization (reconfirms I_53/I_56)." (anchor: "Scope(A_2)\subseteq Scope(A_1). And: Duration(A_2)\subseteq Duration(A_1). Pass. ... Historical authorization remains. Pass.")
- [S1418] types=[VALIDATION, RESTATEMENT] scope=OBJECT — "Tests 21-23 (pass): process composition can partially fail without invalidating an already-successful earlier transition; a failed process still generates knowledge as part of the ordinary knowledge lifecycle; and compensation preserves both the original transition and its compensation in lineage (reconfirms I_59)." (anchor: "Failure -> Observation -> Evidence -> Assessment. Therefore failure is not merely an exception path. It is part of the knowledge lifecycle. ... Compensation \neq Erasure. Thus: \tau and: Compensation(…")
- [S1418] types=[VALIDATION, RESTATEMENT] scope=OBJECT — "Tests 24-25 (pass): an actor's local knowledge can legitimately be a proper subset of system knowledge (a projection), and a new state can operate without complete historical knowledge provided its transition contract preserves invariants -- directly validating the Chapter 4 lens; twenty-five deliberate counterexamples produced no fundamental contradiction." (anchor: "K_a\subseteq K_{system}. This is expected. The actor sees a projection: \pi_a(K_{system}). Pass. ... OperationalSufficiency \neq HistoricalCompleteness. This directly validates the Chapter 4 lens")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
