# kos-boundary-violation-catalog

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `MaterialTransition=>Owner+Authority+Evidence+Verification`, `V1..V10`
**Aliases:** `Boundary violation catalog`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0025, scope OBJECT: Step 136's ten named boundary-violation types (V1 Agent Authority Violation .. V10 Historical destruction), the 'no execution mechanism may implicitly acquire authority' invariant, the accountability dimension/engineering-accountability-graph reframing, and the MaterialTransition invariant.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1037 §"V1 Agent Authority Violation ... V10 Historical destruction"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1037 §"Actor → Action → {authorizedBy→Authority, basedOn→Knowledge, changes→Artifact, produces→Evidence, verifiedBy→Verification}"]
- CANDIDATE-FORMAL-BIRTH: [S1037 §"V1 Agent Authority Violation ... V10 Historical destruction"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1037. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1037), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1037 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1037 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1037 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1037] types=['FORMALIZATION'] scope=OBJECT — "Defines a ten-item boundary-violation catalog: V1 Agent Authority Violation (agent directly creates authoritative decisions), V2 Memory Authority Violation (agent memory overrides governed knowledge), V3 Evidence Authority Violation (observation automatically treated as policy), V4 Assurance Authority Violation (checker grants/removes governance exceptions), V5 External Model Leakage (external system model becomes the KnowledgeOS domain model), V6 Cross-context mutation (one context directly changes another's state), V7 Missing provenance (knowledge without supporting origin/evidence), V8 Missing authorization (material action with no authorization record), V9 Missing verification (material change with no post-change assurance), V10 Historical destruction (superseded governance state deleted rather than preserved)." (anchor: "V1 Agent Authority Violation ... V10 Historical destruction")
- [S1037] types=['INVARIANT', 'PRINCIPLE'] scope=THEORY-LEVEL — "States the fundamental invariant covering agents, scripts, hooks, CI pipelines, runtime systems, and databases; reframes the central risk question as KnowledgeOS grows more automation-capable, from 'can AI understand the architecture?' to 'can AI act without accidentally changing the authority model?' — the architecture must answer this before autonomous execution is expanded." (anchor: "No technical execution mechanism may implicitly acquire organizational authority.")
- [S1037] types=['FORMALIZATION'] scope=OBJECT — "Presents an eleven-row action-authority target matrix (read knowledge/inspect repository/run tests = propose+execute, no approval; create branch/modify local code/create PR = policy-dependent execute; merge protected branch/production deployment = usually approval; change architecture rule/grant exception = propose only, governance required; approve governance decision = propose, execute only if explicitly delegated, governance required), explicitly to be established from actual governance rather than invented; yields the safety-architecture chain Capability->Policy->Authorization->Execution, never Capability->Execution directly." (anchor: "| Action | Agent may propose | Agent may execute | Governance approval |")
- [S1037] types=['INVARIANT'] scope=THEORY-LEVEL — "States the most important invariant of the step: every material state transition must have Owner+Authority+Evidence, with Verification added for changes affecting production or governance." (anchor: "MaterialTransition ⇒ Owner + Authority + Evidence + Verification.")
- [S1037] types=['FORMALIZATION', 'CONCEPT'] scope=THEORY-LEVEL — "States the Assurance Graph must represent this full accountability structure per action, elevating it from 'merely a knowledge graph' to an 'engineering accountability graph'; adds Accountability as a new semantic dimension requiring KnowledgeOS to eventually answer, for any material change: who did it, under whose authority, based on what knowledge, what changed, what proves it, and was it verified." (anchor: "Actor → Action → {authorizedBy→Authority, basedOn→Knowledge, changes→Artifact, produces→Evidence, verifiedBy→Verification}")

## Notes for P3
(none beyond what is noted above)
