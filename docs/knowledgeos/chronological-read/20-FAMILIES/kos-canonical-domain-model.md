# kos-canonical-domain-model

**Scope(s):** THEORY-LEVEL · **Row count:** 87 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority,Decision,Policy,Exception,Knowledge,Claim,Observation,Evidence,FitnessRule,Verification,Finding,Agent,Session,Recommendation,Authorization,Action,Artifact,RuntimeState`
**Aliases:** "Step 137 KnowledgeOS Domain Model"
**Candidate group membership (NOT an identity claim):**
- G0284: co-occurs with `bc7-domain-model` — explicit agent-stated uncertainty: 'kos-canonical-domain-model' POSSIBLY relates to 'bc7-domain-model' (batch B0025). Note: Step 137's consolidated canonical KnowledgeOS domain model: per-object conceptual schemas for all 18 candidate objects, aggregate candidates, mutable/immutable classification, domain events, and the closed-loop engineering-platform redefinition of KnowledgeOS.
- G0882: co-occurs with `kos-domain-model-preview` — labels share the alias 'Step 137 KnowledgeOS Domain Model'  ## SHARED-NOTATION (144 groups)  _MODERATE, NOISY FOR SHORT/GENERIC CODES — a shared symbol like 'C-1' or 'K_t' may be a real citation or pure corpus-wide numbering-scheme collision (see 09-ORCHESTRATOR-FLAGS.md's many logged numbering-collision findings); longer/distinctive notations are more trustworthy_
- G0896: co-occurs with `kos-domain-model-preview` — working_label token overlap Jaccard=0.60 (shared tokens: ['domain', 'kos', 'model'])
- G0897: co-occurs with `kos-domain-model-reduction-preview` — working_label token overlap Jaccard=0.50 (shared tokens: ['domain', 'kos', 'model'])

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0025, scope THEORY-LEVEL: "Step 137's consolidated canonical KnowledgeOS domain model: per-object conceptual schemas for all 18 candidate objects, aggregate candidates, mutable/immutable classification, domain events, and the closed-loop engineering-platform redefinition of KnowledgeOS." (relation_to_existing: POSSIBLY:bc7-domain-model)

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1038 §"Domain Model ≠ Database Model ≠ API Model"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1038 §"Domain Model ≠ Database Model ≠ API Model"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1039 §"Authorization Context = TO BE CONFIRMED."]

## Lifecycle

last_seen: S1048. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1048), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1047 (×2), S1048 (×2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1038 (×14), S1039 (×14), S1047 (×12), S1048 (×10) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1038, S1039 (×3), S1047 (×5) |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1038 (×10), S1039 (×8), S1046 (×2), S1047 (×16), S1048 (×4) |
| examples | PRESENT | S1038, S1047 (×3), S1048 (×4) |
| warnings | PRESENT | S1038 (×2), S1047, S1048 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Lists fifteen candidate typed-ID value objects, arguing strong typing prevents accidental cross-type assignment (e.g. EvidenceID=RuleID) at compile time and makes APIs self-documenting; adds ValidityPeriod (validFrom, validUntil, enforcing validFrom<validUntil), Scope (organization, system, environment, resource — dimensions domain-specific), and Provenance (source, capturedBy, capturedAt, reference) as further value-object candidates, the latter conceptually shared across contexts but not necessarily one shared implementation class [S1047]. Additionally, Raises the unresolved question of Execution's context ownership, argues for treating it as belonging to the engineering environment (Kubernetes/Nexus authoritative for what happened operationally, KnowledgeOS recording only the reference and evidence), and gives the recommended interim interpretation: Action belongs to Agent/Action management, ExecutionFact is produced by the Engineering integration [S1047]. Further, Argues domain events and integration events must be distinguished (e.g. DecisionBecameEffective internally vs EffectiveDecisionPublished across the boundary) to avoid consumers coupling to internal implementation details; exposes only meaningful cross-context contracts [S1048]. Relatedly, Describes query-side composition as a natural place for CQRS-style separation while warning against overengineering — the architecture needs separation of concerns, not fashionable infrastructure, achievable within a modular application; proposes a specialized ContextIndex read model (subject, applicable decisions, applicable rules, current claims, recent evidence, open findings) to improve agent latency, with a ContextCache explicitly Cache≠Authority, carrying Validity/SourceVersion, and stale-context protection requiring ContextAge<threshold or matching ContextVersion before high-risk actions [S1048].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

### Formalizations & axioms (48 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1038, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1039, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048

- [S1038] States the governing discipline for the canonical model (concepts, not tables/REST resources/classes) and presents the full canonical diagram: Authority-authorizes->Decision-governs->{Policy,Exception}->Expected State-evaluatedBy->Fitness Rule->Verification... (anchor: "Domain Model ≠ Database Model ≠ API Model")
- [S1038] Separates the model into three fundamental axes (Authority, Knowledge, Reality) connected by Assurance (Expected<->Observed), with Governance deciding what happens when they differ; defines the three-state model ExpectedState/ObservedState/EffectiveState wh... (anchor: "Authority: Who may decide? Knowledge: What do we know/believe? Reality: What actually happened? Assurance connects Expected<->Observed.")
- [S1038] Gives Authority's conceptual schema (id, type, scope, validity, delegation) representing the legitimate source of a governance decision (governance body, responsible role, delegated authority, or approved process). (anchor: "Authority ├── id ├── type ├── scope ├── validity └── delegation")
- [S1038] Gives Decision's schema (identity, title, rationale, authority, status, effective period, supersedes, governed objects) and lifecycle Draft->Reviewed->Approved->Effective->Superseded. (anchor: "Decision: Draft → Reviewed → Approved → Effective → Superseded.")
- [S1038] Defines Exception=AuthorizedDeviation, schema (scope, reason, authority, validFrom, validUntil, affectedPolicy, conditions), with expiration particularly important — an exception should not silently become permanent. (anchor: "Exception = AuthorizedDeviation.")
- [S1038] Gives Evidence's schema, answering 'what supports this assertion,' referencing the actual source rather than duplicating it. (anchor: "Evidence ├── identity ├── source ├── capturedAt ├── capturedBy ├── content/reference ├── integrity └── provenance")
- [S1038] Gives FitnessRule's schema (identity, version, expression, scope, severity, source policy, checker) with stable identity independent of checker implementation; formalizes Rule ≠ Checker (rule = what must be true, checker = how we determine it), one rule pot... (anchor: "Rule ≠ Checker. Rule R42 → implementedBy → Checker C17.")
- [S1038] Defines Verification as a particular rule execution with four possible verdicts (PASS/FAIL/UNKNOWN/NOT_APPLICABLE); states the essential assurance invariant that an absent result must not automatically become PASS — e.g. 'we could not inspect the production... (anchor: "Verification = Rule + Subject + ObservedState + Time + Result. PASS|FAIL|UNKNOWN|NOT_APPLICABLE.")
- [S1038] Gives a candidate six-type Finding taxonomy (an assurance issue requiring disposition), explicitly to be derived from the existing assurance implementation before being frozen. (anchor: "NON_CONFORMANCE, EVIDENCE_GAP, CONFLICT, STALE_KNOWLEDGE, UNAUTHORIZED_CHANGE, ARCHITECTURE_DRIFT")
- [S1038] Defines Authorization (answers 'may this action be executed?') and Action (an actual execution, e.g. DeployArtifactA, linked to Actor/Authorization/Target/Timestamp/Evidence); gives Agent's schema (identity, implementation, version, capabilities, trust/poli... (anchor: "Authorization = Actor + Action + Scope + Authority + Validity, distinct from the agent's capability.")
- [S1038] Lists nine candidate domain events as domain semantics first (messaging technology comes later), assembled into the operational-assurance-loop event chain ActionAuthorized->ActionExecuted->EvidenceCaptured->VerificationCompleted. (anchor: "DecisionApproved, PolicyChanged, ExceptionGranted, KnowledgeVerified, EvidenceCaptured, VerificationCompleted, FindingRaised, ActionAuthorized, ActionExecuted.")
- [S1038] Assembles the ideal complete chain and the closed-loop architecture diagram GOVERNANCE->EXPECTATION->ASSURANCE->AGENT->EXECUTION->REALITY->EVIDENCE->VERIFICATION->{PASS,FAIL->GOVERNANCE}, called 'the KnowledgeOS closed engineering loop.' (anchor: "Authority → Decision → Policy → Rule → Context → Recommendation → Authorization → Action → Evidence → Verification, with Finding feeding back into Governance.")
- [S1039] Defines the Decision aggregate: core invariant (no effectiveness without valid authority), five-state lifecycle, invalid transitions (DRAFT->EFFECTIVE, DRAFT->SUPERSEDED) unless explicitly permitted, seven candidate commands (ProposeDecision..WithdrawDecisi... (anchor: "A Decision cannot become effective without valid authority. DRAFT→UNDER_REVIEW→APPROVED→EFFECTIVE→SUPERSEDED.")
- [S1039] Defines Policy as a separate aggregate (not embedded in Decision) with its own lifecycle, five candidate commands, four candidate events; defines Exception's invariant Exception=>Authorized+Scoped+TimeBound, five candidate commands, five candidate events wi... (anchor: "Decision → establishes → Policy. E(t) = Policy(t) + ApplicableExceptions(t).")
- [S1039] Defines the KnowledgeClaim aggregate lifecycle, six candidate commands, five candidate events; clarifies AttachEvidence does not imply VerifyClaim — evidence supports a claim, it does not automatically establish it. (anchor: "PROPOSED→SUPPORTED→VERIFIED→AUTHORITATIVE→SUPERSEDED, with DISPUTED reachable from multiple states.")
- [S1039] Defines the Evidence aggregate as generally immutable once captured, a three/four-state lifecycle, four candidate commands, four candidate events — a later interpretation creates new domain state rather than rewriting the original evidence; treats Observati... (anchor: "CAPTURED → VALIDATED → RETAINED, potentially REJECTED. Evidence should not be rewritten because interpretation changes.")
- [S1039] Defines the FitnessRule aggregate (Identity+Version+Scope+Expression+Applicability), four-state lifecycle, five candidate commands, requiring a checker implementation to reference the rule rather than define authoritative semantics; the event FitnessRuleAct... (anchor: "DRAFT→APPROVED→ACTIVE→SUPERSEDED. A checker should not define the authoritative semantics itself.")
- [S1039] Defines Verification as an immutable execution record (not a mutable configuration object), the command ExecuteVerification with actual checker execution occurring outside the domain (Application->Verification Request->Checker Port->External Checker->Eviden... (anchor: "V = (Rule, Subject, Time, Evidence, Verdict). ExecuteVerification.")
- [S1039] States not every verification FAIL necessarily requires a persistent Finding (policy-dependent, Policy(FAIL)->Disposition); defines Finding's lifecycle OPEN->ACKNOWLEDGED->REMEDIATION->VERIFIED->CLOSED (or OPEN->ACCEPTED_RISK if governance permits), and the... (anchor: "FAIL ⇏ Finding universally. Policy(FAIL) → Disposition.")
- [S1039] Defines the AgentSession aggregate as operational (referencing rather than copying authoritative decisions), six candidate commands, five candidate events providing execution history; defines Recommendation as usually not requiring extensive lifecycle manag... (anchor: "Session ├── agent ├── task ├── context reference ├── recommendations ├── actions └── session status — no copies of authoritative decisions, only references.")
- ... plus 28 further rows in this theme (statements not individually quoted here; see `03-CONTRIBUTIONS.jsonl`): S1039, S1039, S1039, S1039, S1039, S1039, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048, S1048

### Extensions, distinctions & restatements (19 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1038, S1038, S1038, S1038, S1038, S1039, S1039, S1039, S1046, S1046, S1046, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1048

- [S1038] Defines Policy as an enforceable expectation established/changed by a Decision (Decision->establishes->Policy) while remaining a separate concept. (anchor: "Decision → establishes → Policy.")
- [S1038] Warns against a generic 'Knowledge' aggregate containing everything; defines Claim's schema (subject, predicate, object/value, status, validity, provenance, supporting evidence) letting a machine distinguish Claim from Evidence. (anchor: "Knowledge is better understood as a category/root concept around specific claims, not a generic Knowledge aggregate.")
- [S1038] Distinguishes Observation ('at 10:32, the Nexus API returned version 3.69.0' — temporal, a specific event) from Claim ('Nexus runs 3.69.0' — a proposition), with Observation->supports->Claim. (anchor: "Observation is temporal: Observation(Nexus,version,3.69.0,2026-08-28T...); Observation → supports → Claim.")
- [S1038] Defines Recommendation as a proposed course of action generated by agent/human/automated analysis, referencing Finding or Knowledge, but never equal to Decision. (anchor: "Recommendation ≠ Decision.")
- [S1038] Assembles the complete canonical relationship graph and clarifies it does not mean one bounded context owns all nodes — the graph is 'the relationship model across contexts,' with each concept-group owned by a distinct bounded context per the mapping given. (anchor: "Governance→Decision/Policy/Exception/Authority; Knowledge→Claim/Knowledge; Evidence→Observation/Evidence; Assurance→Rule/Verification/Finding; Agent→Agent/Session/Recommendation; Execution→Action; External→Artifact/Runtime State.")
- [S1039] Formalizes the command-vs-event distinction (ApproveDecision is a command, DecisionApproved is an event) and states the agent may issue commands but cannot manufacture authoritative events; requires an event to be emitted only by the component that owns the... (anchor: "Command: 'please perform this operation.' Event: 'this operation happened.'")
- [S1039] States the subtle but foundational distinction between agent transcript statements and domain events, concluding the architecture prevents an agent from creating authority through language alone — 'natural language assertion ≠ domain state transition,' a fo... (anchor: "Claude transcript 'I approve this decision' is an AgentStatement, not DecisionApproved. Natural language assertion ≠ Domain state transition.")
- [S1039] Step 138 verdict: the domain model now answers four critical questions (bounded context / authorized command / evidence / domain event-projection) and boxes six behavioral-DDD principles — one authoritative owner per concept, commands request changes, only ... (anchor: "Who owns a concept? Who can change it? What proves the change? How does the platform learn about it?")
- [S1046] Lists twelve concepts the Golden Trace exercise exposed as needing explicit treatment, added to the semantic model; presents an updated, considerably more complete KnowledgeOS semantic model organized under Governance{Decision,Policy,Exception,Disposition},... (anchor: "Task, Context, Recommendation, Action, Authorization, Execution, Observation, Evidence, Verification, Finding, Disposition, Trace.")
- [S1046] States two strict five-stage lifecycle distinctions that must remain strict — the observation-to-finding chain and the task-to-execution chain — 'essential for governed agents.' (anchor: "Observation ≠ Evidence ≠ Verification ≠ Finding, forming Observation→Evidence→Verification→Finding. Task ≠ Recommendation ≠ Action ≠ Authorization ≠ Execution, forming Task→Recommendation→Action→Authorization→Execution.")
- ... plus 9 further rows in this theme (statements not individually quoted here; see `03-CONTRIBUTIONS.jsonl`): S1046, S1047, S1047, S1047, S1047, S1047, S1047, S1047, S1048

### Definitions (2 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1038, S1038

- [S1038] Defines Artifact (GitCommit, PullRequest, ContainerImage, NexusRepository, KubernetesDeployment — referenced through integration contracts, not owned) and Runtime State (observed from ExternalSystem, e.g. Kubernetes->RuntimeObservation, with KnowledgeOS rec... (anchor: "Artifact refers to an engineering object managed elsewhere; Runtime State is not owned by KnowledgeOS.")
- [S1038] Redefines KnowledgeOS precisely (rejecting 'a repository of documents,' 'an AI assistant platform,' 'an architecture governance tool' as insufficient) and gives the semantic center equation GovernedExpectation<->ObservedReality<->Evidence with agents as con... (anchor: "KnowledgeOS is a governed engineering knowledge and assurance platform that connects organizational intent, engineering decisions, agent actions, runtime observations, and evidence.")

### Other concept notes (5 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1038, S1039, S1047, S1047, S1047

- [S1038] Proposes initial aggregate candidates per bounded context, explicitly candidates not final implementation decisions. (anchor: "Governance aggregates: Decision, Policy, Exception. Knowledge: Claim. Evidence: EvidenceRecord. Assurance: FitnessRule, Verification, Finding. Agent: Session. Execution: Action.")
- [S1039] States the source-of-truth rule — for each semantic concept, exactly one authoritative owner, with all else (projections, caches, indexes, references, evidence) subordinate; worked examples — Decision_Governance is authoritative, a copied Decision_AgentMemo... (anchor: "Exactly one authoritative owner.")
- [S1047] Defines the ActionAggregate lifecycle (REQUESTED->AUTHORIZED->EXECUTING->EXECUTED, failure paths DENIED/FAILED/CANCELLED) and five action invariants A-INV-001..005. (anchor: "A-INV-001..005: stable identity; references originating context; cannot execute without required authorization; execution result cannot be represented as authorization; execution produces traceable evidence where applicable.")
- [S1047] Presents a consolidated aggregate ownership map, explicitly still a candidate model, not yet final. (anchor: "GOVERNANCE{DecisionAggregate,PolicyAggregate,ExceptionAggregate}; KNOWLEDGE{ClaimAggregate}; EVIDENCE{EvidenceAggregate}; ASSURANCE{RuleAggregate,VerificationAggregate,FindingAggregate}; AGENT{AgentAggregate,SessionAggregate,TaskAggregate,RecommendationAggregate,ActionAggregate,AuthorizationAggregate}; EXECUTION{ExecutionRecord}.")
- [S1047] Lists six core domain invariants DM-002..DM-007 emerging from the model. (anchor: "DM-002: agent recommendation is never itself an authorization. DM-003: authorization is never evidence an action succeeded. DM-004: an execution fact is never equivalent to a governance decision. DM-005: verification must identify the exact rule version. DM-006: evidence used by assurance must remain historically reconstructable. DM-007: cross-context relationships do not imply aggregate ownership.")

### Governance, principles & constraints (10 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1038, S1039, S1039, S1047, S1047, S1047, S1047, S1047, S1048, S1048

- [S1038] Argues Verification should probably be immutable after execution (preserving auditability via new records rather than edits), extends the same append-only principle to Evidence (new evidence created rather than versioning content in place) and to Action (na... (anchor: "Verification_1 remains historical; a new execution creates Verification_2.")
- [S1039] States the governing tactical-DDD principle and presents the candidate aggregate structure per bounded context (Governance{Decision,Policy,Exception}, Knowledge{KnowledgeClaim}, Evidence{EvidenceRecord}, Assurance{FitnessRule,Verification,Finding}, Agent Pl... (anchor: "Aggregate boundaries follow invariants, not nouns.")
- [S1039] Defines Authorization as May(Actor,Action,Scope,t), possibly produced by Governance or a dedicated authorization service, explicitly deferring the aggregate-boundary decision (Authorization Context=TO BE CONFIRMED) rather than prematurely creating another b... (anchor: "Authorization Context = TO BE CONFIRMED.")
- [S1047] States the first principle for this step and lists the candidate aggregate map (Governance{Decision,Policy,Exception,Disposition}, Knowledge{Claim,KnowledgeState}, Assurance{Rule,Verification,Finding}, Evidence{Evidence,Observation}, Agent{Agent,Session,Tas... (anchor: "Aggregate = ConsistencyBoundary. Entity ≠ Aggregate.")
- [S1047] States the DDD rule for cross-aggregate and cross-context references — by identity/contract, never by embedding the full object. (anchor: "Reference other Aggregates by identity (Action.authorizationId=AUTH-42, not embedding the Authorization object). Reference other Bounded Contexts through stable contracts/IDs, not shared domain object graphs (Verification → RuleID, not Governance.RuleEntity).")
- [S1047] Warns Governance and Assurance should not share rich domain classes merely because of similar words; recommends a Published Language contract (e.g. DecisionReference: id, status, validity, authority) for cross-context consumption instead of importing intern... (anchor: "Resist a large SharedKernel — a tiny one might hold only primitive identity conventions/timestamps/correlation identifiers. Prefer Published Language (e.g. DecisionReference) over importing Governance's internal entity.")
- [S1047] Formalizes the practical aggregate-boundary test as invariant DM-001, applied to four worked pairs: Action+Authorization (separate — different lifecycle/authority/expiration/audit requirements, Action->AuthorizationID); Rule+Verification (separate — Rule pe... (anchor: "DM-001: An Aggregate exists to protect a consistency boundary, not to model every semantic relationship. What invariant would be broken if these objects were updated separately?")
- [S1047] Recommends transactions scoped to one aggregate, with cross-context propagation via events (Governance transaction commits Decision, then DecisionBecameEffective propagates, Knowledge and Assurance react independently) yielding eventual consistency without ... (anchor: "Transaction □ one Aggregate, not Transaction □ {Governance, Knowledge, Evidence, Assurance, Agent} — the latter destroys bounded-context autonomy.")
- [S1048] Requires specific, agent-legible failure reasons when CanExecute=false rather than a generic error, giving the agent a domain-level explanation instead of a raw status code. (anchor: "AUTHORIZATION_DENIED, CONTEXT_EXPIRED, ASSURANCE_FAILED, REQUIRED_EVIDENCE_MISSING, SUBJECT_UNAVAILABLE — not a bare ERROR. 'Execution was denied because authorization expired' vs 'API returned 403.'")
- [S1048] Worked graph-projection example (VerificationCompleted(V42) creating V42-evaluates->R17, -appliesTo->Nexus, -supportedBy->E81) and the graph-staleness-safety argument — the core domain remains correct even if the graph is temporarily stale, 'exactly why the... (anchor: "Graph consumer listens to DecisionApproved/PolicyActivated/EvidenceRegistered/VerificationCompleted/FindingRaised/ActionAuthorized/ActionExecuted and creates GraphNodes/GraphEdges. If projection fails, authoritative state remains safe and the graph is only temporarily stale.")

### Rationale: arguments, analysis & alternatives (3 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S1047, S1048, S1048

- [S1047] Raises the unresolved question of Execution's context ownership, argues for treating it as belonging to the engineering environment (Kubernetes/Nexus authoritative for what happened operationally, KnowledgeOS recording only the reference and evidence), and ... (anchor: "Is Execution part of Agent/Action, or an Engineering Execution context? Action → requests → Engineering System → produces → Execution Fact → Evidence.")
- [S1048] Argues domain events and integration events must be distinguished (e.g. DecisionBecameEffective internally vs EffectiveDecisionPublished across the boundary) to avoid consumers coupling to internal implementation details; exposes only meaningful cross-conte... (anchor: "Governance -> Domain Event -> Integration Event -> Knowledge. Without the distinction, the event bus becomes a dumping ground of SomethingHappenedEvent.")
- [S1048] Describes query-side composition as a natural place for CQRS-style separation while warning against overengineering — the architecture needs separation of concerns, not fashionable infrastructure, achievable within a modular application; proposes a speciali... (anchor: "GetNexusGovernedContext composing Governance/Knowledge/Evidence/Assurance read models + Graph → ContextPackage — a natural CQRS separation, but do not overengineer (CQRS+EventSourcing+Microservices are not required merely because events exist).")


## Notes for P3

- This is my own observation: this label participates in 4 candidate groups (G0284, G0882, G0896, G0897) — a comparatively dense cross-linkage that may be worth prioritizing in P3 reconciliation.
