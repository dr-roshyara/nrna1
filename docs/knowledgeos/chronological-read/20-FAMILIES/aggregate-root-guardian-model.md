# aggregate-root-guardian-model

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AggregateRoot = Guardian of local invariants · **Aliases:** Command != Event
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 205's framing of the AggregateRoot as guardian of local invariants (never a repository facade, CRUD controller, or workflow engine), plus the Command->Transition->Event->State pipeline and candidate domain commands per aggregate.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1424 §"AggregateRoot = Guardian of local invariants. It is not: RepositoryFacade. It is not: CRUDController. And it is not: WorkflowEngine."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1424 §"Command \neq Event. ... ApproveDecision is intent. If successful: DecisionApproved is the resulting event. ... Command\rightarrow Transition\rightarrow Event\rightarrow State."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1424. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1424 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1424 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S1424]** types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Defines the AggregateRoot as the sole externally addressable guardian of local invariants (ExternalCommand->AggregateRoot->InvariantValidation->StateTransition), explicitly not a repository facade, CRUD controller, or workflow engine." (anchor: "AggregateRoot = Guardian of local invariants. It is not: RepositoryFacade. It is not: CRUDController. And it is not: WorkflowEngine.")
- **[S1424]** types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Derives candidate domain commands per aggregate (CreateProposition, RegisterEvidence, CreateAssessment, ProposeDecision/ApproveDecision/RejectDecision, IssueAction, etc.) and formalizes Command->Transition->Event->State, with Command (intent, e.g. ApproveDecision) explicitly distinct from Event (the resulting fact, e.g. DecisionApproved)." (anchor: "Command \neq Event. ... ApproveDecision is intent. If successful: DecisionApproved is the resulting event. ... Command\rightarrow Transition\rightarrow Event\rightarrow State.")

## Notes for P3
- Own observation: only 2 row(s) touch this label — a thin evidentiary base; treat any generalization from it with caution.
- Own observation: completeness is thin — only formal_definition, semantics is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
