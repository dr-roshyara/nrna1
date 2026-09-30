# candidate-aggregate-boundaries-lifecycle

**Scope(s):** `OBJECT` · **Row count:** 14 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Evidence/Epistemic Assessment/Determination/Decision/Lineage aggregates` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 189's five candidate (not-yet-validated) aggregate boundaries for the epistemic/governance/operational lifecycle, each with the invariant(s) it protects, to be tested in Step 190's DDD Aggregate Invariant Test against three concrete cases.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1390] §"Instead, there are likely separate bounded contexts or aggregates around: evidence/observation; epistemic assessment; determination; governance decision; operational execution; lineage/provenance."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1390] §"### Evidence Aggregate ... ### Lineage"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1477] §"DDD-2 | Aggregate adjudication (189's five vs 205's lists): apply 203 section34's own criterion (Same Aggregate iff shared transactional invariant requires atomicity) to the KI catalogue per candidate"

## Lifecycle
last_seen: `S1477`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1390, S1392 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1390, S1392, S1392 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1392, S1392, S1392 |
| dependencies | PRESENT | S1477 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1392, S1392, S1392, S1392, S1392 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1392 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1390 |

## Rationale
DDD interpretation of the three-machine result: the mathematics warns against one giant KnowledgeAggregate; instead the domain likely decomposes into separate bounded contexts/aggregates for evidence/observation, epistemic assessment, determination, governance decision, operational execution, and lineage/provenance -- with exact boundaries still to be validated. [S1390] Cross-case comparison table: Nexus discovery -> Evidence/Provenance; Architecture Board -> Governance/Authority; AI proposition -> Epistemic origin/Attribution -- but all three require Identity+Time+Context+Provenance+Witness in common. [S1392]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1390]` types=[ARGUMENT] scope=THEORY-LEVEL — "DDD interpretation of the three-machine result: the mathematics warns against one giant KnowledgeAggregate; instead the domain likely decomposes into separate bounded contexts/aggregates for evidence/observation, epistemic assessment, determination, governance decision, operational execution, and lineage/provenance -- with exact boundaries still to be validated." (anchor: "Instead, there are likely separate bounded contexts or aggregates around: evidence/observation; epistemic assessment; determination; governance decision; operational execution; lineage/provenance.")
- `[S1390]` types=[EXTENSION, FORMALIZATION] scope=THEORY-LEVEL — "Proposes five candidate aggregate boundaries and the invariants each protects: Evidence Aggregate (EvidenceIdentity+Provenance+Integrity), Epistemic Assessment (Assessment+EvidenceReference+Model/Rule), Determination (Determination+Authority+Witness), Decision (Decision+Authority+DecisionContext), Lineage (Transition+Witness+TemporalOrdering) -- explicitly flagged as not yet the final aggregate map and requiring testing against real cases." (anchor: "### Evidence Aggregate ... ### Lineage")
- `[S1390]` types=[FUTURE-RESEARCH] scope=THEORY-LEVEL — "Proposes Step 190: a DDD Aggregate Invariant Test using three concrete cases (Nexus infrastructure discovery; Architecture Board decision; AI-generated KnowledgeOS proposition), reconstructing each through Observation->Evidence->Assessment->Determination->Decision->Action->Outcome and asking which invariant must be protected at which aggregate boundary, as evidence for whether the mathematical model actually describes the built architecture." (anchor: "The next step should therefore be a DDD Aggregate Invariant Test.")
- `[S1392]` types=[INVARIANT, DEFINITION] scope=OBJECT — "Case A (Nexus discovery) derives invariant I_E: evidence must retain its origin and provenance, e.g. E1=(source,actor,timestamp,context,content,integrity), so later users can answer 'where did this assertion come from?'." (anchor: "I_E: Evidence must retain its origin and provenance.")
- `[S1392]` types=[VALIDATION] scope=OBJECT — "First concrete DDD validation from Case A: the Evidence boundary should protect EvidenceIdentity and EvidenceIntegrity, but must not itself decide whether e.g. 'Nexus 3.69 is architecturally acceptable' -- that decision belongs to a separate Decision aggregate." (anchor: "EvidenceAggregate \not= DecisionAggregate.")
- `[S1392]` types=[INVARIANT, DEFINITION] scope=OBJECT — "Case B (Architecture Board) derives invariant I_D: a decision D=f(E,Rules,Authority,Context) must have identifiable authority, context, rationale, and evidence basis -- meaning the decision must be legitimate and reconstructable, not necessarily correct." (anchor: "I_D: A decision must have an identifiable authority, context, rationale, and evidence basis.")
- `[S1392]` types=[ANALYSIS, RESTATEMENT] scope=THEORY-LEVEL — "Cross-case comparison table: Nexus discovery -> Evidence/Provenance; Architecture Board -> Governance/Authority; AI proposition -> Epistemic origin/Attribution -- but all three require Identity+Time+Context+Provenance+Witness in common." (anchor: "| Case | Primary concern | Critical invariant |")
- `[S1392]` types=[PRINCIPLE, RESTATEMENT] scope=METHODOLOGICAL — "DDD restatement: an aggregate should not be defined merely because concepts feel related; it should be defined where consistency (Invariant(Aggregate)=True) must be protected." (anchor: "An aggregate exists to ensure: Invariant(Aggregate)=True.")
- `[S1392]` types=[INVARIANT] scope=OBJECT — "Candidate boundary #2 (Determination) invariant I_Det: no determination without valid determination authority, with Determination=(Proposition,EvidenceSet,Assessment,Authority,Context,Time,Witness); flagged as needing validation against actual governance processes." (anchor: "No determination without valid determination authority.")
- `[S1392]` types=[CONSTRAINT] scope=OBJECT — "Candidate boundary #3 (Decision) with schema (intent,context,authority,rationale,evidenceRefs,time,status): the Decision aggregate should reference evidence rather than duplicate it, to avoid aggregate explosion/duplication." (anchor: "It should reference evidence. This avoids aggregate explosion and duplication.")
- `[S1392]` types=[WARNING, DISTINCTION] scope=OBJECT — "Candidate boundary #4 (Lineage): Lineage may not be a traditional business aggregate at all but a cross-cutting infrastructure/domain capability responsible for Witness(tau) and Trace(tau); the file explicitly warns against prematurely calling it an aggregate, framed as an important DDD discipline." (anchor: "We should therefore not prematurely call Lineage an aggregate.")
- `[S1392]` types=[PRINCIPLE] scope=OBJECT — "Candidate boundary #5 (Operational state): OperationalState (e.g. NexusVersion) belongs to the actual infrastructure/application domain, not to KnowledgeOS; KnowledgeOS only observes it -- 'KnowledgeOS records knowledge about reality; it does not become reality.'" (anchor: "KnowledgeOS records knowledge about reality; it does not become reality.")
- `[S1392]` types=[CONSTRAINT, RESTATEMENT] scope=THEORY-LEVEL — "Aggregate test result across all three cases: the consistent pattern Evidence->Assessment->Determination->Decision->Action->Outcome, with separate authority and provenance constraints, must never be silently owned by one object, or the model's semantic distinctions collapse." (anchor: "No single object should silently own the entire chain.")
- `[S1477]` types=[GOVERNANCE] scope=OBJECT — "DDD-2 adjudicates between step 189's five candidate aggregates and step 205's aggregate lists by applying step 203 section 34's own same-aggregate criterion (Same Aggregate if and only if a shared transactional invariant requires atomicity) to the KI catalogue per candidate; produces a per-candidate verdict showing the criterion's evaluation explicitly, resolving Lineage and Determination disagreements by the criterion or marking them UNRESOLVED." (anchor: "DDD-2 | Aggregate adjudication (189's five vs 205's lists): apply 203 section34's own criterion (Same Aggregate iff shared transactional invariant requires atomicity) to the KI catalogue per candidate")

## Notes for P3
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.
