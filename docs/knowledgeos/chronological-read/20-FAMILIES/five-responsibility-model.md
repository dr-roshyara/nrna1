# five-responsibility-model

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `five responsibilities` · **Aliases:** `Governance/Architecture/Implementation/Independent Verification/Governance Communication`
**Candidate group membership (NOT an identity claim):**
- **G0029**: [`five-responsibility-model` · `six-role-model`] — explicit agent-stated uncertainty: 'five-responsibility-model' POSSIBLY relates to 'six-role-model' (batch B0003). Note: An earlier five-responsibility framing (brainstorming, S0098) distinct from the later six-role model in that it has no separate Knowledge responsibility; relation to the six-role model is not resolved in this batch.

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0003, scope THEORY-LEVEL: "An earlier five-responsibility framing (brainstorming, S0098) distinct from the later six-role model in that it has no separate Knowledge responsibility; relation to the six-role model is not resolved in this batch."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0098 §"There is an important distinction between the role model and the workflow state machine. The roles tell us who may act; the lifecycle tells us what happens and in what order."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0098 §"The five responsibilities you are actually operating with: 1. Governance ... 2. Architecture ... 3. Implementation ... 4. Independent Verification ... 5. Governance Communication"]
- CANDIDATE-FORMAL-BIRTH: [S0098 §"The five responsibilities you are actually operating with: 1. Governance ... 2. Architecture ... 3. Implementation ... 4. Independent Verification ... 5. Governance Communication"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0098. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0098 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0098 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0098 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
An alternative four-layer framing is proposed: Authority -> Governance -> Workflow -> (Architecture and Implementation in parallel) -> Verification -> Human Acceptance, with Knowledge/Evidence and Communication/Context modelled as cross-cutting concerns rather than as roles. [S0098] The advisory persona discloses an operating limitation: it cannot observe the Claude Code terminal automatically and depends on the human relaying reports, establishing a manual human-in-the-loop workflow for driving the master lifecycle sequence. [S0098]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0098] types=[DISTINCTION] scope=THEORY-LEVEL — "A foundational distinction is drawn between the role model (who may act) and the workflow state machine (what happens and in what order), presented as two separate but related layers of the governed AI engineering lifecycle." (anchor: "There is an important distinction between the role model and the workflow state machine. The roles tell us who may act; the lifecycle tells us what happens and in what order.")
- [S0098] types=[CONCEPT, FORMALIZATION] scope=THEORY-LEVEL — "Five operational responsibilities are proposed for the governed AI engineering lifecycle as actually exercised: Governance (authority/lifecycle), Architecture (model creation), Implementation (approved-architecture-only), Independent Verification (falsify, do not supply replacement), and Governance Communication (next-actor, current-state, handoff instructions) -- explicitly stating Communication 'should not become another architecture role. It is a coordination capability around the lifecycle.'" (anchor: "The five responsibilities you are actually operating with: 1. Governance ... 2. Architecture ... 3. Implementation ... 4. Independent Verification ... 5. Governance Communication")
- [S0098] types=[FORMALIZATION, ALTERNATIVE] scope=THEORY-LEVEL — "An alternative four-layer framing is proposed: Authority -> Governance -> Workflow -> (Architecture and Implementation in parallel) -> Verification -> Human Acceptance, with Knowledge/Evidence and Communication/Context modelled as cross-cutting concerns rather than as roles." (anchor: "I would now define your whole system around four layers: AUTHORITY -> GOVERNANCE -> WORKFLOW -> (ARCHITECTURE, IMPLEMENTATION) -> VERIFICATION -> HUMAN ACCEPTANCE. And across all of them: KNOWLEDGE / …")
- [S0098] types=[LIMITATION, EXPLANATION] scope=METHODOLOGICAL — "The advisory persona discloses an operating limitation: it cannot observe the Claude Code terminal automatically and depends on the human relaying reports, establishing a manual human-in-the-loop workflow for driving the master lifecycle sequence." (anchor: "I cannot directly watch your Claude Code terminal or receive its messages automatically ... Claude performs the assigned step -> Claude produces its report -> you paste/upload it here -> I determine t…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
