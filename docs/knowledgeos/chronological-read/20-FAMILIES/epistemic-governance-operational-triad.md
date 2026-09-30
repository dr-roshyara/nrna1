# epistemic-governance-operational-triad

**Scope(s):** THEORY-LEVEL · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E(p)`, `E_t->G_t->O_t->E_{t+1}`, `G(p)`, `O(p)` · **Aliases:** `three parallel state machines`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 189's core architectural correction: the epistemic lifecycle of a proposition requires three separate, interacting state machines (Epistemic/Governance/Operational) rather than one linear chain; includes the epistemic state set E, the Believed-is-dangerous warning, Observed!=True, Decision!=Action!=Outcome, Knowledge!=Action, and the feedback loop formula.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1390 §"these are not all the same kind of state"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1390 §"$$\mathcal E=\{Unknown,Observed,Hypothesized,Supported,Conflicted,Refuted,Determined,Superseded\}.$$"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1390. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1390 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1390, S1390 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1390, S1390, S1390 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1390, S1390, S1390, S1390 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1390 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Step 189 verdict: the test succeeded but corrected the original model -- the improved architecture is three parallel state machines (Epistemic, Governance, Operational) connected by explicit domain events and typed relations, not one linear Unknown->...->Decided chain. [S1390]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1390] types=['CORRECTION'] scope=THEORY-LEVEL — "The naive trajectory Unknown->Observed->Believed->Supported->Determined->Decided->Refuted->Corrected mixes different semantic dimensions; the earlier single-chain state machine model is wrong and must be split." (anchor: "these are not all the same kind of state")
- [S1390] types=['DISTINCTION', 'INVARIANT'] scope=THEORY-LEVEL — "A proposition's lifecycle requires at minimum three distinct state dimensions -- Epistemic state E(p), Governance state G(p), and Operational state O(p) -- which must never be collapsed into one state machine; 'Decided' is a governance/action event about a proposition, not a stronger degree of knowledge about it." (anchor: "EpistemicState != GovernanceState != OperationalState")
- [S1390] types=['FORMALIZATION'] scope=OBJECT — "Defines the epistemic state machine's state set E = {Unknown, Observed, Hypothesized, Supported, Conflicted, Refuted, Determined, Superseded}; transitions need not move monotonically toward certainty (e.g. Supported->Conflicted or Supported->Refuted are both allowed)." (anchor: "$$\mathcal E=\{Unknown,Observed,Hypothesized,Supported,Conflicted,Refuted,Determined,Superseded\}.$$")
- [S1390] types=['WARNING'] scope=OBJECT — "'Believed' is rejected as a canonical KnowledgeOS state because it conflates at least four distinct concepts (subjective belief, organizational working assumption, statistically supported hypothesis, officially accepted proposition); recommends explicit typed alternatives Belief_actor(p), WorkingAssumption(p), or Supported(p) instead, in service of DDD's precise ubiquitous language." (anchor: "Therefore I would not use `Believed` as a canonical KnowledgeOS state.")
- [S1390] types=['INVARIANT'] scope=OBJECT — "Observation is an epistemic event, not a truth claim: Observed(P)=1 does not imply True(P)=1, because the observation itself may be wrong." (anchor: "Observed(P)\neq True(P).")
- [S1390] types=['INVARIANT'] scope=THEORY-LEVEL — "After a governance Decision (e.g. Upgrade), executing an Action changes operational reality (e.g. Nexus 3.69->3.70); Decision, Action, and Outcome are three distinct objects, not synonyms -- validating an (unspecified) earlier invariant." (anchor: "Decision\neq Action\neq Outcome.")
- [S1390] types=['FORMALIZATION'] scope=THEORY-LEVEL — "The complete causal/epistemic lifecycle is not one state machine but three interacting machines (Epistemic E_t, Governance G_t, Operational O_t) forming a feedback loop E_t -> G_t -> O_t -> E_{t+1}, diagrammed as Proposition -> Observation -> Evidence -> Epistemic Assessment -> {Determination | Resolution} -> Governance Decision -> Action -> Outcome -> New Observation." (anchor: "E_t\rightarrow G_t\rightarrow O_t\rightarrow E_{t+1}.")
- [S1390] types=['PRINCIPLE'] scope=THEORY-LEVEL — "When later evidence E2 demonstrates not-P after Supported(P), the state becomes Refuted(P,t2), but the original Supported(P,t1) remains historically valid -- refutation is a status change at t2, not a historical erasure." (anchor: "Refutation changes current epistemic status; it does not erase historical support.")
- [S1390] types=['PRINCIPLE'] scope=THEORY-LEVEL — "The transition Knowledge->Action requires a Governance/NormativeRule; correct knowledge does not by itself imply correct action, which is why the architecture must not reduce organizational reasoning to statistical inference alone." (anchor: "Knowledge\neq Action. And: CorrectKnowledge\neq CorrectAction without a normative layer.")
- [S1390] types=['RESTATEMENT', 'ARGUMENT'] scope=THEORY-LEVEL — "Step 189 verdict: the test succeeded but corrected the original model -- the improved architecture is three parallel state machines (Epistemic, Governance, Operational) connected by explicit domain events and typed relations, not one linear Unknown->...->Decided chain." (anchor: "Epistemic State Machine \parallel Governance State Machine \parallel Operational State Machine")

## Notes for P3
(none beyond what is noted above)
