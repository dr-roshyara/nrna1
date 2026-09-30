# kos-graph-edge-taxonomy

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `G(t)`, `authorizes,governs,establishes,supersedes,excepts,states,describes,derivedFrom,validDuring,supports,observedBy,capturedFrom,evaluates,verifiedBy,producesFinding,performedBy,authorizedBy,changes,produces` · **Aliases:** `Assurance Graph edge vocabulary`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0025, scope THEORY-LEVEL: "Step 137's preliminary edge-vocabulary taxonomy for the Assurance Graph grouped by owning context, temporal-edge validity G(t), and the context-generation algorithm G_T=RelevantSubgraph(G,T,t)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1038 §"Governance: authorizes/governs/establishes/supersedes/excepts. Knowledge: states/describes/derivedFrom/validDuring. Evidence: supports/observedBy/capturedFrom. Assurance: evaluates/verifiedBy/producesFinding. Execution: performedBy/authorizedBy/changes/produces."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1038 §"Governance: authorizes/governs/establishes/supersedes/excepts. Knowledge: states/describes/derivedFrom/validDuring. Evidence: supports/observedBy/capturedFrom. Assurance: evaluates/verifiedBy/producesFinding. Execution: performedBy/authorizedBy/changes/produces."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1038. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1038 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1038 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1038] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines a preliminary edge vocabulary grouped by owning context (Governance/Knowledge/Evidence/Assurance/Execution edge verbs); requires each important edge to carry enough metadata (Source, Evidence, EstablishedAt, EstablishedBy) to answer 'why does this relationship exist' (e.g. Decision D42 -governs-> Service S17)." (anchor: "Governance: authorizes/governs/establishes/supersedes/excepts. Knowledge: states/describes/derivedFrom/validDuring. Evidence: supports/observedBy/capturedFrom. Assurance: evaluates/verifiedBy/produces…")
- [S1038] types=[FORMALIZATION] scope=THEORY-LEVEL — "States relationships themselves need temporal validity (edges, not just nodes, change over time), giving the temporal graph G(t) = the set of relationships valid at time t, enabling HistoricalTrace; worked historical-query example (RuntimeState(t)->Verification(t)->Rule(t)->EffectivePolicy(t)->Decision(t) answering why a configuration was valid on a given date) and current-query example (Service->CurrentDecision->CurrentPolicy->CurrentRules), 'the temporal engine selects the currently effective relationships.'" (anchor: "D42 governs ServiceA; later D57 supersedes D42 — the old relationship becomes historically inactive. G(t).")
- [S1038] types=[FORMALIZATION] scope=THEORY-LEVEL — "Gives a principled agent-context-generation algorithm (task T -> relevant subgraph G_T at time t, filtered by authority/validity/scope/evidence quality/permissions -> ContextPackage), with a ContextPackage schema (Task, Applicable decisions, Applicable policies, Relevant knowledge, Current observations, Supporting evidence, Applicable rules, Existing findings, Exceptions, Agent permissions) as the agent's governed working context; requires SourceID retention on every context item so the agent can cite provenance rather than rely on its own model memory." (anchor: "G_T = RelevantSubgraph(G,T,t), filtered by authority/validity/scope/evidence quality/permissions → ContextPackage.")
- [S1038] types=[EXAMPLE] scope=OBJECT — "Worked provenance examples for agent output (Recommendation R17 explicitly listing its basedOn references, and Action A81 recording agent/session/recommendation/authorization/target/evidence), yielding accountability, called 'explainable AI engineering.'" (anchor: "Recommendation R17 basedOn: Decision D42, Knowledge K17, Evidence E91, Finding F31.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
