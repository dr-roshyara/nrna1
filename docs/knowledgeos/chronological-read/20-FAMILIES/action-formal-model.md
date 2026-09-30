# action-formal-model

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Action=(Actor,Operation,Purpose,Authority,Context,Execution,Outcome,Attribution)" · **Aliases:** "Action model"
**Candidate group membership (NOT an identity claim):**
- G1062: [`action-disposition-model` · `action-formal-model`] — working_label token overlap Jaccard=0.50 (shared tokens: ['action', 'model'])

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0020, scope OBJECT: "Refined Action tuple separating execution from agency/attribution/responsibility, and ObservableAction≠SemanticAction / Intention≠Purpose distinctions plus 'Knowledge does not determine Action by itself' and 'Action does not prove Understanding' invariants."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0807 §"ActionExecution \neq AgencyAttribution"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0844. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S0844), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0808, S0844 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0807, S0807, S0807, S0808, S0808 |
| dependencies | PRESENT | S0808, S0808, S0844 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0807, S0807, S0807 |
| examples | PRESENT | S0808 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Identifies the strongest mathematical insight of the chapter (verses 25-26): the learned and ignorant may perform outwardly similar actions with different orientation, giving ObservedAction ≠ MeaningfulAction ≠ ActionIntent, and ActionIdentity ≠ ActionObservation, illustrated by two engineers both running deploy() with identical observable events but different authorization/intent (authorized correct change vs unauthorized personal shortcut) — 'the operational event may look identical, the semantic event is not'. [S0808] Test Case 9 (a constitutional rule 'all production software must have an approved ADR') shows the pipeline must produce a NormativeProposition distinct in semantic type from a FactualProposition, exposing that a single undifferentiated Proposition model cannot serve both. Test Case 10 (a human instruction 'upgrade Nexus to 3.70') shows an instruction must NOT become Assertion:Nexus.version=3.70 directly — it produces Instruction:Upgrade(Nexus,3.70), which may later produce an Action, which may produce a new Observation, which may then produce an Assertion. This establishes two fundamentally different flows: Epistemic acquisition (Input->Observation->Interpretation->Assertion) and Action/effect (Instruction->Action->Observation->Knowledge) — flagged as important for later defining Sārathi. [S0844]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0807] types=[DISTINCTION, EXTENSION] scope=OBJECT — "Verse 27's doer/agency discussion (explicitly not encoded as a universal engineering fact) reveals ActionExecution ≠ AgencyAttribution, requiring separation of Action, Actor, Execution, Cause, Responsibility, AgencyAttribution. Action is reformalized from (Actor,Operation,Result) to Action=(Actor,Operation,Purpose,Authority,Context,Execution,Outcome,Attribution), distinguishing who performed vs authorized vs caused vs is responsible for an action." (anchor: "ActionExecution \neq AgencyAttribution")
- [S0807] types=[DISTINCTION, EXTENSION] scope=OBJECT — "Chapter 3's contrast of attachment-to-results vs action-without-attachment shows the same observable action can carry different semantic status depending on orientation: ObservableAction ≠ SemanticAction, ActionMeaning=f(Operation,Actor,Intention,Purpose,Norm,Context). Adds Intention ≠ Purpose (example: Purpose=fulfil duty vs Intention=obtain personal gain, same Action=perform operation X), giving Action=(Operation,Actor,Intention,Purpose,Context). Also adds ExemplaryBehavior/NormPropagation (Actor_A --Example--> Actor_B, verse 21) as a 'Governance/Normative Influence' bounded context, and OptimalGuidance ≠ MaximumInformation (verse 26: do not disturb the learner), giving Guidance=f(CurrentUnderstanding,Capability,Readiness,Purpose,Risk) and introducing LearningReady(K,U,Actor) / Readiness(Actor,Knowledge,Context) distinct from DecisionReady." (anchor: "ObservableAction \neq SemanticAction")
- [S0807] types=[PRINCIPLE, INVARIANT] scope=THEORY-LEVEL — "Two new constitutional invariants: 'Knowledge does not determine Action by itself' formalized as Action=f(Knowledge,Understanding,Norms,Role,Purpose,Intention,Authority,Context,Decision); and 'Action does not prove Understanding' — someone may execute an instruction without understanding it, and Understanding ⇏ Action because authority/role/constraints/decision criteria may intervene, giving the non-implication chain Knowledge ⇏ Understanding ⇏ Decision ⇏ Action, each transition requiring its own semantics." (anchor: "Knowledge does not determine Action by itself.")
- [S0808] types=[CORRECTION, EXTENSION] scope=OBJECT — "Corrects the generic 'Purpose' field as too coarse: Goal (what is ultimately sought) ≠ Duty (what is prescribed) ≠ Intention (why the actor acts) ≠ Result ≠ Orientation, all distinct. Revises the Action tuple to A=(Actor,Role,Operation,Duty,Intention,Orientation,Context,Outcome), noting not every field must always be populated." (anchor: "Goal \neq Duty \neq Intention")
- [S0808] types=[ARGUMENT, EXAMPLE] scope=OBJECT — "Identifies the strongest mathematical insight of the chapter (verses 25-26): the learned and ignorant may perform outwardly similar actions with different orientation, giving ObservedAction ≠ MeaningfulAction ≠ ActionIntent, and ActionIdentity ≠ ActionObservation, illustrated by two engineers both running deploy() with identical observable events but different authorization/intent (authorized correct change vs unauthorized personal shortcut) — 'the operational event may look identical, the semantic event is not'." (anchor: "Same external action \not\Rightarrow same semantic action")
- [S0808] types=[CORRECTION, EXTENSION] scope=OBJECT — "Argues Chapter 3 supplies something more interesting than 'decision readiness': Arjuna's question is 'given my situation, what course of action should I follow?', requiring ApplicableNorms+ActorRole+Context+Understanding -> CandidateActions -> Decision, judged a better formulation than simply introducing 'DecisionGap' (which is later demoted, see below)." (anchor: "ApplicableNorms + ActorRole + Context + Understanding \rightarrow CandidateActions")
- [S0844] types=[ARGUMENT, CORRECTION] scope=OBJECT — "Test Case 9 (a constitutional rule 'all production software must have an approved ADR') shows the pipeline must produce a NormativeProposition distinct in semantic type from a FactualProposition, exposing that a single undifferentiated Proposition model cannot serve both. Test Case 10 (a human instruction 'upgrade Nexus to 3.70') shows an instruction must NOT become Assertion:Nexus.version=3.70 directly — it produces Instruction:Upgrade(Nexus,3.70), which may later produce an Action, which may produce a new Observation, which may then produce an Assertion. This establishes two fundamentally different flows: Epistemic acquisition (Input->Observation->Interpretation->Assertion) and Action/effect (Instruction->Action->Observation->Knowledge) — flagged as important for later defining Sārathi." (anchor: "Instruction \rightarrow Action \rightarrow Observation \rightarrow Knowledge")

## Notes for P3
- This label participates in 1 candidate group(s) (listed above) — none decided here; each is a candidate relationship for P3 to adjudicate.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
