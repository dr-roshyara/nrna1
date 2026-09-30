# epistemic-vs-practical-decision-separation

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** EPISTEMIC_STATUS != PRACTICAL_DECISION · **Aliases:** Cook Wilson's act-on-a-hypothesis distinction
**Candidate group membership (NOT an identity claim):**
- **G0083** [`epistemic-vs-practical-decision-separation` · `inference-decision-separation-principle`] — explicit agent-stated uncertainty: 'inference-decision-separation-principle' POSSIBLY relates to 'epistemic-vs-practical-decision-separation' (batch B0012). Note: Bishop's inference-vs-decision-theory separation (posterior estimation vs loss-driven action choice) reframed as eight architectural principles: P1 Inference!=Decision; P2 Reject is a valid outcome (formalized as Decision=argmin_a E[L(a,theta)|evidence] with a=REJECT as an available action, giving KnowledgeOS a first-class ABSTAIN/ESCALATE/ACCEPT_WITH_UNCERTAINTY/INSUFFICIENT_EVIDENCE vocabulary instead of forced TRUE/FALSE); P3 structure before sophistication; P4 complexity must earn its cost via model evidence; P5 approximation must be observable (bound+convergence+validation); P6 model uncertainty is first-class and distinct from state uncertainty; P7 preserve posterior distributions until decision time rather than premature point estimates; P8 use heterogeneous experts (mixture-of-experts) where domains differ, aligning BoundedContext ~= InferenceExpertBoundary.
- **G0303** [`epistemic-vs-practical-decision-separation` · `wisdom-action-guidance-function`] — explicit agent-stated uncertainty: 'wisdom-action-guidance-function' POSSIBLY relates to 'epistemic-vs-practical-decision-separation' (batch B0026). Note: Gita-Chapter-4-derived proposal that Wisdom is a cross-cutting domain function mapping Knowledge/Context/Evidence/Rules/Authority to an ActionGuidance value drawn from a deontic vocabulary (Required/Permitted/Forbidden/Unknown, Do/Refrain/Defer/Escalate/Investigate), explicitly not to be reified as a literal Wisdom class; parallels but is not identical to the prior epistemic-vs-practical-decision-separation object.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0012`, scope `THEORY-LEVEL`: John Cook Wilson's distinction (an investigator may need to act as if a hypothesis were true despite insufficient evidence) motivates keeping a Decision Boundary strictly outside the Knowledge Core: NOT_PROVEN does not imply DO_NOT_ACT, and VALIDATED does not imply MUST_ACT.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0462] §"a person may have insufficient evidence to establish a proposition, yet still need to act as if one hypothesis were true for practical or theoretical purposes. ... Claim: 'Hypothesis A is true' / Epistemic status: NOT_PROVEN / Decision: 'Proceed using A temporarily.' There is no contradiction."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0463. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S0463), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0462 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0463 |
| dependencies | PRESENT | S0462, S0463 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0462 |
| examples | PRESENT | S0462 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0462] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Cook Wilson's epistemic-vs-practical-decision distinction, worked as an example: acting on a NOT_PROVEN hypothesis is not contradictory, strengthening the justification for keeping a Decision Boundary strictly outside the Knowledge Core (NOT_PROVEN does not imply DO_NOT_ACT; VALIDATED does not imply MUST_ACT)." (anchor: "a person may have insufficient evidence to establish a proposition, yet still need to act as if one hypothesis were true for practical or theoretical purposes. ... Claim: 'Hypothesis A is true' / Epistemic status: NOT_PROVEN / Decision: 'Proceed using A temporarily.' There is no contradiction.")
- [S0462] types=[DEFINITION, CORRECTION] scope=THEORY-LEVEL — "Sharpens the previous KnowledgeOS definition to explicitly include questions/beliefs/inquiry/action and the non-collapse of epistemic status into practical decision or representation." (anchor: "KnowledgeOS preserves the governed relationships among questions, beliefs, claims, evidence, inquiry, assessment, epistemic standing, authority, revision and action without collapsing epistemic status into practical decision or representation.")
- [S0463] types=[CONSTRAINT, LIMITATION] scope=THEORY-LEVEL — "Pragmatic encroachment (higher stakes demand more evidence) and moral encroachment are both explicitly kept outside the Kernel as decision/governance policy rather than redefinitions of epistemic truth: Epistemic status != decision threshold." (anchor: "Higher stakes demand greater assurance for rational action. ... Epistemic status != decision threshold. Outside Kernel: Risk policy, Decision threshold, Safety policy, Regulatory assurance, Business impact. ... Moral considerations ... should influence decision/governance policies, not redefine the meaning of epistemic truth.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
