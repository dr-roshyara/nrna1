# kos-persistence-architecture

**Scope(s):** THEORY-LEVEL · **Row count:** 20 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** ContextFingerprint, DATA-001..006, KnowledgeOS Ledger · **Aliases:** Step 149 Persistence & Data Architecture
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0025`, scope `THEORY-LEVEL`: Step 149's persistence/data model: bounded-context-owned transactional state, projections (graph/search/cache) as non-authoritative and rebuildable, six DATA-nn invariants, evidence metadata/payload separation, agent-memory persistence, and the 'KnowledgeOS Ledger' semantic audit concept.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1051] §"Each bounded context owns its authoritative transactional state. One authoritative owner + four representations, not five authorities."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1051] §"Transactional State + Evidence Store + Read Models → Events → Graph → Context."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1051. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1051), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1051 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051, S1051 |
| examples | PRESENT | S1051 |
| warnings | PRESENT | S1051, S1051 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1051] (FORMALIZATION/ARGUMENT) Presents both physical options and recommends the lowest-risk starting point for the Golden Trace, arguing clean bounded-context boundaries let a Governance Module->Integration Event->Assurance Module later become Governance Service->Message Broker->Assurance Service without changing the semantic architecture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1051] types=[PRINCIPLE, EXAMPLE] scope=THEORY-LEVEL — "Rejects KnowledgeOS=OneDatabase and KnowledgeOS=GraphDatabase in favor of per-bounded-context transactional ownership; worked example — the same Decision appearing in Governance DB/Graph/Search index/Cache/Document is one authority plus four representations, storage is not authority." (anchor: "Each bounded context owns its authoritative transactional state. One authoritative owner + four representations, not five authorities.")
- [S1051] types=[FORMALIZATION] scope=THEORY-LEVEL — "Presents the persistence topology, lists transactional-store contents (Decision, Policy, Rule, Claim, Verification, Finding, Action, Authorization)." (anchor: "Transactional State + Evidence Store + Read Models → Events → Graph → Context.")
- [S1051] types=[PRINCIPLE, WARNING] scope=METHODOLOGICAL — "Recommends a relational database as the strong default for the first Golden Trace (transactional consistency, constraints, lifecycle state, audit references, structured relationships, deterministic queries), explicitly not prohibiting other stores later, and warns against premature polyglot infrastructure." (anchor: "Relational persistence should be the default transactional foundation. Do not start with multiple databases (Postgres+MongoDB+Neo4j+Elasticsearch+Redis+Kafka from day one).")
- [S1051] types=[FORMALIZATION] scope=OBJECT — "Sketches an initial persistence architecture, and a candidate bounded-context storage-ownership matrix across sixteen concepts (Decision/Policy/Exception->Governance; Claim->Knowledge; Rule/Verification/Finding->Assurance; Evidence->Evidence; Agent/Session/Task/Recommendation->Agent; Action->Action/Agent; Authorization->Authorization; Execution fact->Engineering integration/execution context; Graph relationship->Graph projection), pending validation against the existing system." (anchor: "PostgreSQL → {Governance, Assurance, Agent/Action} → Outbox → Projection Layer → {Graph, Search, Cache}.")
- [S1051] types=[PRINCIPLE] scope=THEORY-LEVEL — "States why the graph consumes rather than sources domain state (keeps graph availability from becoming a domain consistency dependency) and recommends proving the relationship model with a simple relationship table (source_id, source_type, relationship_type, target_id, metadata) in PostgreSQL before introducing specialized graph infrastructure — avoiding technology-driven architecture." (anchor: "DomainState → Events → GraphProjection, not Graph → DomainState. Graph semantics first, graph technology second.")
- [S1051] types=[FORMALIZATION, PRINCIPLE] scope=OBJECT — "Separates evidence metadata from payload to avoid turning KnowledgeOS into a universal data lake; requires evidence to be append-oriented (E1->E2->E3, never overwritten) and proposes content-addressable evidence via a ContentHash for integrity checking, stating 'integrity requirement ≠ blockchain requirement' — cryptographic hash plus controlled provenance suffices without distributed-ledger infrastructure." (anchor: "EvidenceMetadata (evidenceId, source, subject, capturedAt, provenance, externalReference) vs EvidencePayload (external system/object storage/log system/artifact repository). KnowledgeOS → EvidenceReference → ExternalArtifact.")
- [S1051] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Defines agent-memory persistence as separate from authoritative knowledge, with a memory record referencing but never redefining a ClaimID; restates the memory-promotion pipeline (Agent Memory->Candidate Claim->Evidence->Verification->Governed Claim) as the persistent form of the earlier promotion architecture." (anchor: "Agent Memory Store (session context, working notes, preferences, candidate knowledge). Memory ≠ GovernedKnowledge. Memory: 'Nexus uses version X' points toward, does not establish, Claim: 'Nexus version = X'.")
- [S1051] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Treats Search and Cache as non-authoritative projections fed by the domain and events, with event-driven invalidation preferred over time-to-live alone." (anchor: "Search Index is a projection, not authoritative; may be EventuallyConsistent. Cache ≠ SourceOfTruth; high-risk actions must validate freshness; event stream invalidates caches (PolicyActivated → invalidate affected contexts) rather than relying on TTL alone.")
- [S1051] types=[DISTINCTION, PRINCIPLE] scope=THEORY-LEVEL — "Distinguishes needing to reconstruct important historical actions from requiring every aggregate to be event-sourced, recommending the lighter combination as default." (anchor: "EventHistory ≠ EventSourcing. StateModel + DomainEvents + Outbox + AuditHistory as the recommended initial approach; full event sourcing only where a specific Aggregate genuinely benefits.")
- [S1051] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Defines the audit-record schema and three pairwise distinctions (audit vs evidence, audit vs event) — an audit record may become evidence in some contexts but the concepts remain separate." (anchor: "AuditRecord{actor,operation,subject,before,after,timestamp,traceId,correlation}. Audit = who changed system state. Evidence = what was observed or produced. Event = domain fact. Audit = accountability record.")
- [S1051] types=[FORMALIZATION] scope=OBJECT — "Presents a fourteen-row persistence consistency matrix mapping each data type to its authority and consistency model, and the resulting AUTHORITATIVE->{Governance DB, Assurance DB, Action DB}->Events->{Graph, Search, Context Projections} architecture diagram." (anchor: "| Data | Authority | Consistency | Decision: Governance DB, Strong/local; Graph/Search/Context cache: Projection, Eventual; Agent memory: Agent-local, Contextual.")
- [S1051] types=[FORMALIZATION, ARGUMENT] scope=METHODOLOGICAL — "Presents both physical options and recommends the lowest-risk starting point for the Golden Trace, arguing clean bounded-context boundaries let a Governance Module->Integration Event->Assurance Module later become Governance Service->Message Broker->Assurance Service without changing the semantic architecture." (anchor: "Modular monolith (one PostgreSQL with per-context schemas) vs distributed deployment (separate DBs) — both implement the same domain architecture; recommended first implementation: modular monolith + relational persistence.")
- [S1051] types=[WARNING] scope=THEORY-LEVEL — "Lists four persistence anti-patterns to avoid: a single giant shared entity; making every transaction a graph transaction; automatic (ungoverned) memory promotion; treating search relevance as authority (search tells what appears relevant, authority tells what is governed/valid)." (anchor: "KnowledgeOSEntity{governance,knowledge,evidence,assurance,agent,action,graph} — the giant shared model, forbidden. Graph→all domain state — forbidden. Agent memory→automatic promotion→authoritative knowledge — forbidden. Search index→authoritative answer — forbidden.")
- [S1051] types=[FORMALIZATION] scope=THEORY-LEVEL — "States the rebuildability property for graph and search: authoritative state + event history allow full reconstruction, so their loss does not entail knowledge loss." (anchor: "GraphLoss ⇏ KnowledgeLoss. SearchIndexLoss ⇏ KnowledgeLoss.")
- [S1051] types=[FORMALIZATION] scope=THEORY-LEVEL — "Extends the Golden Trace's context-fingerprint idea into a persistence-level reproducibility goal: given the same governed inputs and temporal context, KnowledgeOS can reconstruct the exact context an agent received (not needed in v1, but a valuable capability) — foundational for answering 'why did the agent recommend this on [date]' by reconstructing Context at that specific time, not today's context." (anchor: "ContextID should be explainable as a projection of Authority+Knowledge+Evidence+Rules+Time+Scope. ContextFingerprint = Hash(RelevantKnowledgeVersions,Rules,Governance,Evidence,Scope,Time). Context(t,S,A) = f(Governance,Knowledge,Evidence,Rules,Scope,Time,Actor).")
- [S1051] types=[FORMALIZATION] scope=THEORY-LEVEL — "Presents the full audit chain enabled by the persistence architecture." (anchor: "Governance State → Context Version → Agent Recommendation → Authorization → Action → Execution → Evidence → Verification — much stronger than a conventional application log.")
- [S1051] types=[DEFINITION] scope=THEORY-LEVEL — "Introduces the 'KnowledgeOS Ledger' term with components (Domain state, Domain events, Audit records, Evidence references, Authorization decisions, Trace relationships), the graph being a projection of this information; argues the platform's long-term differentiator may be reconstructable governed engineering history rather than the LLM itself, with AI as the actor using this substrate." (anchor: "KnowledgeOS Ledger — not a blockchain, but the set of authoritative state transitions, evidence references, events and provenance necessary to reconstruct governed engineering activity. A semantic audit concept, not necessarily a physical database.")
- [S1051] types=[RESTATEMENT] scope=THEORY-LEVEL — "One-sentence persistence-model summary." (anchor: "Authoritative bounded-context state + append-oriented evidence/events + rebuildable projections + governed context.")
- [S1051] types=[RESTATEMENT] scope=THEORY-LEVEL — "Step 149 verdict: recommends the conservative starting architecture and states the key governing invariant." (anchor: "Modular relational core + Outbox + Evidence + Projection, introducing specialized graph/search/storage technologies only when scale/query characteristics justify them. Storage technology must follow semantic ownership, not define it.")
- [S1051] types=[FORMALIZATION] scope=THEORY-LEVEL — "Opens Step 150 (KnowledgeOS Runtime Architecture): constructs the runtime request path and previews a three-way separation of KnowledgeOS Platform, Agent Harness, and Engineering Environment — 'especially important for the existing .claude/.codex architecture.'" (anchor: "Agent → API → Application → Domain → Persistence → Events → Projection, connected to External Engineering Systems. KnowledgeOS Platform vs Agent Harness vs Engineering Environment.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
