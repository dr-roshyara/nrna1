# step176-actorcontext-projection-of-knowledge

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** ActorContext is a projection of OrganizationalKnowledge, not its source of truth, OrganizationalKnowledge must be actor-independent, Projection(K,Task,Actor,Policy), RelevantKnowledge = f(Task,Actor,Authority,Time) · **Aliases:** context projection per role, organizational-knowledge actor-independence invariant
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 176 states two new invariants: 'OrganizationalKnowledge must be actor-independent' (existence/authoritative status of knowledge must not depend on whether a particular human or AI currently remembers it) and 'ActorContext is a projection of OrganizationalKnowledge, not its source of truth' -- an agent receives Projection(K,Task,Actor,Policy), never uncontrolled access to everything, formalized as RelevantKnowledge=f(Task,Actor,Authority,Time). Illustrates role-specific projections from one governed repository (Architect Agent -> architecture decisions+constraints; Developer Agent -> implementation rules+relevant ADRs; Governance Agent -> decisions+authority+evidence; Operations Agent -> authorized actions+execution constraints), and derives the enforceable chain Task->RelevantKnowledge->AllowedTools->AllowedActions, i.e. KnowledgeAccess->Capability->Authorization->Execution.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1369 §"OrganizationalKnowledge must be actor-independent. ... ActorContext is a projection of OrganizationalKnowledge, not its source of truth. ... Projection(K,Task,Actor,Policy). ... RelevantKnowledge = f(Task,Actor,Authority,Time). ... Task → RelevantKnowledge → AllowedTools → AllowedActions."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1369 §"OrganizationalKnowledge must be actor-independent. ... ActorContext is a projection of OrganizationalKnowledge, not its source of truth. ... Projection(K,Task,Actor,Policy). ... RelevantKnowledge = f(Task,Actor,Authority,Time). ... Task → RelevantKnowledge → AllowedTools → AllowedActions."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1369. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1369 |
| type_signature | PRESENT | S1369 |
| invariants | PRESENT | S1369 |
| dependencies | PRESENT | S1369 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1369] types=['INVARIANT', 'FORMALIZATION'] scope=THEORY-LEVEL — "States two invariants: 'OrganizationalKnowledge must be actor-independent' (existence/authoritative status must not depend on whether a particular human or AI currently remembers it) and 'ActorContext is a projection of OrganizationalKnowledge, not its source of truth' (an agent receives Projection(K,Task,Actor,Policy), never uncontrolled full access), formalized as RelevantKnowledge=f(Task,Actor,Authority,Time). Illustrates role-specific projections from one governed source (Architect Agent gets decisions+constraints; Developer Agent gets implementation rules+ADRs; Governance Agent gets decisions+authority+evidence; Operations Agent gets authorized actions+execution constraints), deriving the enforceable chain Task->RelevantKnowledge->AllowedTools->AllowedActions (i.e. KnowledgeAccess->Capability->Authorization->Execution) and extending the full loop to K_t->Context_t->Reasoning_t->Decision_t->Authorization_t->Execution_t->Outcome_t->K_{t+1}." (anchor: "OrganizationalKnowledge must be actor-independent. ... ActorContext is a projection of OrganizationalKnowledge, not its source of truth. ... Projection(K,Task,Actor,Policy). ... RelevantKnowledge = f(Task,Actor,Authority,Time). ... Task → RelevantKnowledge → AllowedTools → AllowedActions.")

## Notes for P3
None beyond what is recorded above.
