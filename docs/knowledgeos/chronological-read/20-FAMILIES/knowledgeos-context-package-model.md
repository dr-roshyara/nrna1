# knowledgeos-context-package-model

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Context=Projection(Knowledge,Evidence,Governance,CurrentState)`, `ContextHash`, `ContextPackage`
**Aliases:** "Agent context as projection"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0025, scope OBJECT: "Step 126's ContextPackage schema and Context=Projection formalization for scoping agent context to a task, with freshness (GeneratedAt/KnowledgeVersion) and integrity (ContextHash) fields enabling audit of which knowledge context an agent acted upon."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1025 §"Context = Projection(Knowledge, Evidence, Governance, CurrentState)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1025 §"Context = Projection(Knowledge, Evidence, Governance, CurrentState)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1025. Candidate lifecycle: DORMANT. Evidence: no retraction, supersession, or self-contradiction recorded — heuristic based on how long ago (by source_id) this label was last used, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1025, S1025 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty; both rows are typed FORMALIZATION).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1025] types=[FORMALIZATION] scope=THEORY-LEVEL — "Formalizes agent context as a projection (Context = Projection(Knowledge, Evidence, Governance, CurrentState)) so the agent need not have direct unrestricted access to every underlying object; gives a worked agent query example ('give me the current authoritative architecture for Nexus, the decisions governing it, relevant exceptions, current observed state, and unresolved findings') that KnowledgeOS answers by constructing a ContextPackage." (anchor: "Context = Projection(Knowledge, Evidence, Governance, CurrentState).")
- [S1025] types=[FORMALIZATION] scope=OBJECT — "Defines a candidate ContextPackage schema (task, authoritative_knowledge, applicable_decisions, active_exceptions, current_observations, relevant_evidence, unresolved_findings, constraints, provenance) as the bridge between the semantic model and the AI layer; requires GeneratedAt/KnowledgeVersion so the agent can detect staleness, and proposes hashing the package (H(ContextPackage)) so an action can reference a ContextHash, letting one reconstruct which knowledge context an agent acted upon; the complete audit chain becomes Context->Recommendation->Authorization->Action->Evidence, called potentially one of the most valuable audit paths in the AI engineering platform." (anchor: "ContextPackage ├── task ... └── provenance")

Both rows come from `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-125024_step-126-knowledgeos-information-model.md`.

## Notes for P3
Both rows come from the same file (S1025, "Step 126") and are complementary rather than in tension: the first states the abstract formula (`Context = Projection(...)`), the second gives the concrete schema and the audit-chain claim (`Context → Recommendation → Authorization → Action → Evidence`). This audit-chain claim looks like it could matter to other governance/audit-trail labels in the corpus (not decided here — no group_id links were mechanically detected for this label). The schema is explicitly called a "candidate" in its own source text, so despite `completeness: COMPLETE` on both rows, it should still be read as a proposal, not an adopted implementation.
