# twenty-review-dimensions

**Scope(s):** METHODOLOGICAL · **Row count:** 17 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** dimensions 1-20 · **Aliases:** review instrument
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0034 · scope METHODOLOGICAL: Step 201's twenty-dimension review checklist (semantic integrity, ontological necessity, mathematical necessity, causal validity, temporal integrity, historical-vs-current state, epistemic integrity, uncertainty/authority/lineage preservation, counterfactual analysis, composition, boundary integrity, aggregate integrity, event integrity, idempotency, failure semantics, Unknown-as-first-class-result) plus a twelve-row concept review matrix, used to audit every accumulated architectural concept.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1419 §"| Concept | Mathematical role | DDD role | Governance role | Evidence role | Status |"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1419. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1419 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1419 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1419 |
| examples | PRESENT | S1419 |
| warnings | PRESENT | S1419 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Review dimension 1 (semantic integrity): challenges every term to have exactly one meaning, using 'State' as the running example of a word overloaded across at least five senses that must be split (DomainState, ProcessState, EpistemicState, etc.) where required [S1419]. Review dimension 2 (ontological necessity): distinguishes DomainConcept from TechnicalRepresentation, warning that neither a database record nor a message-bus message automatically qualifies as a domain concept or domain event [S1419]. Review dimension 4 (causal validity): every arrow A->B in the architecture must be interrogated for whether it is causal, temporal, logical, probabilistic, correlational, or merely sequential -- e.g. Evidence->Decision should not be read as causation but as Decision=f(Evidence,Policy,Authority,Context) [S1419]. Review dimension 5 (temporal integrity): every important predicate should default to being time-indexed (Truth(x,t), Authority(a,d,t), Assessment(H,E,M,t)) rather than timeless [S1419]. Review dimension 6 (historical vs current state): every aggregate must be tested to ensure its current-state projection does not accidentally destroy the ability to reconstruct governance-required history [S1419]. Review dimensions 8-10 (uncertainty, authority, and lineage preservation): deliberately trace constructed cases through Evidence->Assessment->Decision->Action and demand that uncertainty stay visible, authority remain reconstructible, and the full chain remain traceable backward from Outcome to Evidence -- any silent disappearance is labeled an architecture defect, governance defect, or traceability gap respectively [S1419]. Review dimension 13 (boundary integrity): every concept must have a clear owning bounded context; concepts appearing 'everywhere' (like Decision) should be suspected of hiding distinct domain semantics per context, even while sharing one mathematical abstraction [S1419].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1419] types=[DEFINITION, EXTENSION] scope=METHODOLOGICAL — "Proposes a twelve-row review-instrument matrix (Identity, State, Transition, Evidence, Proposition, Assessment, Uncertainty, Authority, Decision, Action, Lineage, Invariant) crossing four role columns (mathematical, DDD, governance, evidence) with a review status column, explicitly not the final ontology but the review instrument to produce it." (anchor: "| Concept | Mathematical role | DDD role | Governance role | Evidence role | Status |")
- [S1419] types=[EXPLANATION, EXTENSION] scope=METHODOLOGICAL — "Review dimension 1 (semantic integrity): challenges every term to have exactly one meaning, using 'State' as the running example of a word overloaded across at least five senses that must be split (DomainState, ProcessState, EpistemicState, etc.) where required." (anchor: "Does this word have exactly one meaning? ... State can mean: domain state; process state; epistemic state; infrastructure state; UI state.")
- [S1419] types=[EXPLANATION, EXTENSION] scope=METHODOLOGICAL — "Review dimension 2 (ontological necessity): distinguishes DomainConcept from TechnicalRepresentation, warning that neither a database record nor a message-bus message automatically qualifies as a domain concept or domain event." (anchor: "Is this actually an entity/concept in the domain, or merely an implementation artifact? ... DatabaseRecord does not automatically belong in the conceptual ontology. ... KafkaMessage does not automatically become a domain event.")
- [S1419] types=[PRINCIPLE] scope=METHODOLOGICAL — "Review dimension 3 (mathematical necessity): every introduced formula must actually constrain the architecture; forcing a probability P(x) onto every object merely because it sounds sophisticated is mathematically artificial and must be rejected." (anchor: "Mathematics must constrain the model, not decorate it.")
- [S1419] types=[EXPLANATION, EXAMPLE] scope=METHODOLOGICAL — "Review dimension 4 (causal validity): every arrow A->B in the architecture must be interrogated for whether it is causal, temporal, logical, probabilistic, correlational, or merely sequential -- e.g. Evidence->Decision should not be read as causation but as Decision=f(Evidence,Policy,Authority,Context)." (anchor: "We must challenge every statement of the form: A\rightarrow B. Is this: causal? temporal? logical? probabilistic? merely correlated? merely sequential? ... Decision = f(Evidence,Policy,Authority,Context).")
- [S1419] types=[EXPLANATION] scope=METHODOLOGICAL — "Review dimension 5 (temporal integrity): every important predicate should default to being time-indexed (Truth(x,t), Authority(a,d,t), Assessment(H,E,M,t)) rather than timeless." (anchor: "Truth(x,t) is often more accurate than: Truth(x). Likewise: Authority(a,d,t) rather than: Authority(a,d).")
- [S1419] types=[EXPLANATION] scope=METHODOLOGICAL — "Review dimension 6 (historical vs current state): every aggregate must be tested to ensure its current-state projection does not accidentally destroy the ability to reconstruct governance-required history." (anchor: "We need to test every aggregate against: CurrentState versus: HistoricalState. ... CurrentKnowledge \neq CompleteHistoricalKnowledge.")
- [S1419] types=[CONSTRAINT] scope=METHODOLOGICAL — "Review dimension 7 (epistemic integrity): every knowledge claim must be traceable back to evidence or an explicitly declared assumption/model; Claim->Truth as a direct architectural shortcut is rejected in favor of Claim->assessment->EpistemicStatus." (anchor: "Why do we believe this? ... We should reject: Claim\rightarrowTruth as an architectural shortcut. Instead: Claim \xrightarrow{assessment} EpistemicStatus.")
- [S1419] types=[EXPLANATION, CONSTRAINT] scope=METHODOLOGICAL — "Review dimensions 8-10 (uncertainty, authority, and lineage preservation): deliberately trace constructed cases through Evidence->Assessment->Decision->Action and demand that uncertainty stay visible, authority remain reconstructible, and the full chain remain traceable backward from Outcome to Evidence -- any silent disappearance is labeled an architecture defect, governance defect, or traceability gap respectively." (anchor: "Does uncertainty remain visible? If it disappears without justification: Architecture defect ... Can we reconstruct why this actor was allowed to perform this transition at that point in time? If not: Governance defect ... If not, we have a traceability gap.")
- [S1419] types=[EXTENSION] scope=METHODOLOGICAL — "Review dimension 11 (counterfactual analysis): for every major causal claim, apply the potential-outcomes counterfactual frame Y(A=1) vs Y(A=0), which does not itself prove causation but forces a clean CausalClaim/CorrelationClaim distinction." (anchor: "Y(A=1) versus: Y(A=0). This does not prove causality by itself, but it forces the architecture to distinguish: CausalClaim from: CorrelationClaim.")
- [S1419] types=[RESTATEMENT] scope=METHODOLOGICAL — "Review dimension 12 (composition): restates and generalizes Step 198's composition-is-not-automatic result as an ongoing review discipline for the whole architecture." (anchor: "Suppose P_1 is valid and: P_2 is valid. Does: P_1\circ P_2 remain valid? Not automatically. This becomes a major theme of the next phase.")
- [S1419] types=[EXPLANATION, EXAMPLE] scope=METHODOLOGICAL — "Review dimension 13 (boundary integrity): every concept must have a clear owning bounded context; concepts appearing 'everywhere' (like Decision) should be suspected of hiding distinct domain semantics per context, even while sharing one mathematical abstraction." (anchor: "Which bounded context owns this meaning? We should be suspicious of concepts that appear everywhere. ... Decision may have different meanings in: Architecture Governance; Incident Management; Product Management; Compliance; Deployment.")
- [S1419] types=[CONSTRAINT, RESTATEMENT] scope=METHODOLOGICAL — "Review dimension 14 (aggregate integrity): restates the corpus's recurring DDD test for aggregate boundaries -- a shared-invariant justification is required, 'they belong together' is explicitly insufficient." (anchor: "What invariant must this boundary protect? ... 'Because these objects belong together.' that is insufficient. The stronger answer is: 'They must change atomically because invariant I spans them.'")
- [S1419] types=[DISTINCTION, EXAMPLE] scope=METHODOLOGICAL — "Review dimension 15 (event integrity): distinguishes Fact, Command, Decision, and Event as four separate concepts, illustrated by ApproveElection (command) vs ElectionApproved (event/fact), warning that confusing them produces serious architectural ambiguity." (anchor: "Fact from: Command from: Decision from: Event. ... ApproveElection is a command. ... ElectionApproved is an event/fact.")
- [S1419] types=[EXTENSION] scope=METHODOLOGICAL — "Review dimension 16 (idempotency): every important transition must be tested against repeated execution and classified along five possible outcomes (equivalent, duplicate, invalid, compensating, state-changing), especially important for AI agents and distributed systems." (anchor: "If: \tau(x) is executed twice, what happens? We need to classify: \tau^2 as: equivalent; duplicate; invalid; compensating; state-changing.")
- [S1419] types=[DEFINITION, EXTENSION] scope=METHODOLOGICAL — "Review dimension 17 (failure semantics): a mature architecture must classify failure into distinct non-interchangeable categories (Technical, Business Rejection, Authorization, Validation, Epistemic Insufficiency, Conflict), not just describe Success." (anchor: "TechnicalFailure ... BusinessRejection ... AuthorizationFailure ... ValidationFailure ... EpistemicInsufficiency ... Conflict. These are not interchangeable.")
- [S1419] types=[WARNING, EXTENSION] scope=METHODOLOGICAL — "Review dimension 18: a binary SUCCESS/FAILURE model likely hides epistemic states; Unknown must be deliberately tested as a first-class workflow result, with its handling (retry, escalation, or risk-accepted decision) determined by domain policy." (anchor: "A system that has only: SUCCESS / FAILURE is probably hiding epistemic states. ... Unknown \rightarrow Retry? Unknown \rightarrow Escalation? Unknown \rightarrow DecisionWithRiskAcceptance? The answer is domain-specific.")

## Notes for P3
(none beyond what is captured above)
