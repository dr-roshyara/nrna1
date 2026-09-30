# kos-architecture-fitness-rules

**Scope(s):** OBJECT · **Row count:** 50 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AFR-01..AFR-10 · **Aliases:** Architecture Fitness Rules
**Candidate group membership (NOT an identity claim):**
- **G0276** [`architecture-baseline-001` · `kos-architecture-fitness-rules`] — explicit agent-stated uncertainty: 'kos-architecture-fitness-rules' POSSIBLY relates to 'architecture-baseline-001' (batch B0025). Note: Step 128's ten named Architecture Fitness Rules (AFR-01 no agent = system of record .. AFR-10 Unknown is a valid epistemic outcome), tested against worked context-map scenarios (.claude/memory/, AGENTS.md, Nexus config), forming the bridge from DDD context-map principles to Step 129's executable fitness model.
- **G0277** [`architecture-health-dashboard` · `kos-architecture-fitness-rules`] — explicit agent-stated uncertainty: 'kos-architecture-fitness-rules' POSSIBLY relates to 'architecture-health-dashboard' (batch B0025). Note: Step 128's ten named Architecture Fitness Rules (AFR-01 no agent = system of record .. AFR-10 Unknown is a valid epistemic outcome), tested against worked context-map scenarios (.claude/memory/, AGENTS.md, Nexus config), forming the bridge from DDD context-map principles to Step 129's executable fitness model.
- **G0278** [`kos-architecture-fitness-rules` · `kos-fitness-rule-model`] — explicit agent-stated uncertainty: 'kos-fitness-rule-model' POSSIBLY relates to 'kos-architecture-fitness-rules' (batch B0025). Note: Step 129's formal fitness-rule model: F_i(S,C)->verdict, governed FitnessRule/FitnessResult schemas and lifecycles, rule categories, three(+)-valued verdict states, severity/enforcement-policy mapping, and the Narrative Architecture -> Executable Architecture transformation, distinct from (but the formal home of) the AFR-nn rule catalogue.
- **G0282** [`kos-architecture-fitness-rules` · `kos-pointer-layer-principle`] — explicit agent-stated uncertainty: 'kos-pointer-layer-principle' POSSIBLY relates to 'kos-architecture-fitness-rules' (batch B0025). Note: Step 134's Pointer-Layer Principle PLP-01 (agent-specific artifacts, e.g. AGENTS.md/.claude/.codex/memory, may point to authoritative knowledge but must not silently redefine it) and the diagrammed pointer-layer architecture, operationalizing the same concern as AFR-11/AFR-12 with a distinct named principle.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0025, scope OBJECT): Step 128's ten named Architecture Fitness Rules (AFR-01 no agent = system of record .. AFR-10 Unknown is a valid epistemic outcome), tested against worked context-map scenarios (.claude/memory/, AGENTS.md, Nexus config), forming the bridge from DDD context-map principles to Step 129's executable fitness model. [relation_to_existing: POSSIBLY:architecture-baseline-001;POSSIBLY:architecture-health-dashboard]

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1028 §"AFR-01: No agent component may become the system of record for authoritative organizational knowledge."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1028 §"Architecture Principle → Fitness Rule → Automated Check → Evidence → Verdict"]
- CANDIDATE-OPERATIONAL-BIRTH: [S1028 §"Let the Claude .claude/memory/ directory become the primary source of architectural knowledge"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1057. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1029 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1028 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1028, S1029, S1032, S1036, S1037, S1041, S1042, S1044 (+7 more) |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1028, S1029 |
| warnings | PRESENT | S1029 |
| experiments | PRESENT | S1028, S1029 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- **[INVARIANT/ARGUMENT]** [S1029]: Introduces SemanticFitness beyond structural rules — code can compile perfectly while semantic integrity breaks (e.g. two contexts using 'Approved' to mean ApprovedDecision vs ApprovedDeployment, not the same concept), formalized as AFR-13, a Ubiquitous Language rule requiring one governed meaning per bounded context.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
50 rows across 15 source documents, spanning a long-running, incrementally-numbered catalogue of Architecture Fitness Rules (AFR-nn, then several parallel numbering series: RA-nn, KOS-ARCH-nnn, AF-nnn, IC-nnn, API-nnn, DATA-nnn, RT-nnn, REG-nnn, ASSURE-nnn). Grouped into 17 themes by document/rule-series, in chronological (source_id) order. Row counts per theme sum to 50.

### 1. The founding ten Architecture Fitness Rules, AFR-01..AFR-10 (S1028 — 10 rows)
AFR-01 protects the KnowledgeOS boundary against agent components becoming the authoritative system of record [S1028]. AFR-02 requires explicit provenance and authority for authoritative knowledge [S1028]. AFR-03 requires adapters/translation so external system models do not leak into KnowledgeOS domain semantics [S1028]. AFR-04 prevents technical verification from becoming organizational authority by separating detection (Assurance) from disposition (Governance) [S1028]. AFR-05 requires agent recommendations to remain distinguishable from authoritative decisions [S1028]. AFR-06 requires traceable authorization for material agent actions [S1028]. AFR-07 creates the closure mechanism by requiring sufficient evidence for post-action verification of every material change [S1028]. AFR-08 connects architecture governance to deterministic assurance by requiring constitutional rules to be independently testable where feasible [S1028]. AFR-09 requires historical authoritative states to remain reconstructable since current knowledge alone is insufficient for auditability [S1028]. AFR-10 requires the system never manufacture certainty merely because an agent is expected to provide an answer [S1028].

### 2. Context-map worked tests, and opening the executable fitness pipeline (S1028 — 3 rows)
A worked context-map/AFR-01 test: a proposal to make Claude's `.claude/memory/` the primary architectural-knowledge source FAILs AFR-01 since AgentLocalMemory ≠ AuthoritativeKnowledge — correct architecture has authoritative knowledge flowing KnowledgeOS -> Claude memory/cache, never the reverse [S1028]. Worked context-map tests for Codex (AGENTS.md acceptable only as a pointer/operating contract, never AGENTS.md = KnowledgeOS), Claude (`.claude/` owns agent behavior/hooks/workflow/pointers while KnowledgeOS owns authoritative engineering knowledge/governance/evidence/decisions, giving the desired symmetry), and Nexus (a Nexus config statement is operational evidence, not automatically the enterprise architecture decision, which comes instead from GovernanceDecision->ExpectedArchitecture) [S1028]. Opens Step 129 (KnowledgeOS Architecture Fitness Model): rather than merely documenting principles, defines an executable pipeline Architecture Principle -> Fitness Rule -> Automated Check -> Evidence -> Verdict able to inspect repository structure, dependencies, configuration, agent harnesses, KnowledgeOS APIs, governance records, evidence, and CI pipelines — the bridge from DDD architecture to deterministic architectural assurance [S1028].

### 3. Structural, semantic, and temporal fitness rules AFR-11..AFR-19, with worked tests (S1029 — 8 rows)
AFR-11 operationalizes the Claude/Codex symmetry principle as a structural/dependency-graph check (forbidden edge Agent->AuthorityStore); a worked agent-memory test — a `.claude/memory/` file independently asserting "ADR-42 is authoritative" FAILs, while one reading "see KnowledgeOS decision ADR-42" PASSes (a pointer, not an independent authority) [S1029]. AFR-12 restricts AGENTS.md's role, paralleling AFR-11 for the Codex harness [S1029]. Introduces SemanticFitness beyond structural rules — code can compile perfectly while semantic integrity breaks (e.g. two contexts using "Approved" to mean ApprovedDecision vs. ApprovedDeployment) — formalized as AFR-13, a Ubiquitous Language rule requiring one governed meaning per bounded context [S1029]. AFR-14: only the owning context may perform invariant-sensitive state transitions on its own aggregates (a classic DDD fitness rule) — enforced via ContextA->Contract->ContextB rather than direct aggregate mutation [S1029]. AFR-15 (forall d in Decisions: Authoritative(d) => ValidAuthority(d)) accounts for temporal authority validity; a worked experiment — a delegation valid 2026-01-01 to 2026-06-30 with a decision dated 2026-07-15 yields ValidAuthority=False, expected FAIL [S1029]. AFR-16 requires Exception->Authority, Exception->Scope, Exception->Validity, warning against a "temporary exception" record with no expiry (Expiry=null yields FAIL) [S1029]. AFR-17 requires traceable supporting evidence for evidence-requiring knowledge classes, with runtime assertions specifically needing ObservedAt [S1029]. AFR-18, a deterministically testable temporal-fitness rule; a worked stale-knowledge test — K1=Current, K2 supersedes K1, current query must return K2 (returning K1 is FAIL) [S1029].

### 4. AI-specific agent fitness rules AFR-19..AFR-26, with worked tests (S1029 — 4 rows)
AFR-19, without which PASS results cannot reliably be reproduced; a worked reproducibility test — V1=f(R,I), V2=f(R,I) run again, expected V1=V2, else InvestigationRequired [S1029]. Introduces five AI-specific agent fitness rules AFR-20 through AFR-24, requiring action attribution to Agent+Session+Task, and requiring a material recommendation to preserve Recommendation->Evidence, Recommendation->Producer, and potentially Recommendation->ContextHash so a recommendation remains explainable even after the architecture it was based on changes [S1029]. AFR-25 (Action->Authorization); a worked negative-action test — an agent attempts a production change without authorization, expected BLOCK, itself becoming assurance evidence [S1029]. AFR-26 (Action->Verification) — a change is not complete merely because execution succeeded; a Nexus configuration migration with exitCode=0 proves CommandSucceeded but not MigrationCorrect, so verification must inspect actual state [S1029].

### 5. Dependency and boundary fitness rules AFR-27..AFR-30 (S1032 — 4 rows)
AFR-27, a dependency fitness rule for the logical architecture forbidding domain dependence on agent-specific infrastructure [S1032]. AFR-28, forbidding direct domain dependence on external-system SDKs (Claude SDK, Kubernetes client, etc.), testable via forbidden-dependency checks [S1032]. AFR-29, requiring agent execution to pass through governed application boundaries rather than reaching the domain/infrastructure directly [S1032]. AFR-30, requiring material actions to pass through authorization enforcement, operationalizing the Policy Enforcement Point [S1032].

### 6. AFR-31, anticipating distribution (S1036 — 1 row)
AFR-31, particularly important if the system eventually becomes distributed [S1036].

### 7. AFR-32..AFR-36, converting boundary violations into rules (S1037 — 1 row)
Converts five of the boundary violations into executable Architecture Fitness Rules: AFR-32 (agent cannot directly create authoritative governance state), AFR-33 (agent local memory cannot override authoritative knowledge), AFR-34 (observation cannot automatically become governance policy), AFR-35 (assurance cannot silently grant governance exceptions), AFR-36 (cross-context state changes require explicit contract) [S1037].

### 8. LA-01 and cross-context dependency minimization via integration events (S1041 — 1 row)
States the healthy dependency direction (External Systems <- Adapters <- Application <-> Domain, hexagonal ports/interfaces) and formalizes fitness rule LA-01; extends this to cross-context dependencies inside KnowledgeOS — direct domain imports should be minimized (Assurance ↛ Governance.DecisionEntity; instead Assurance -> DecisionReference), with cross-context propagation via integration events (DecisionApproved, PolicyActivated, ExceptionApproved, EvidenceCaptured, VerificationCompleted, FindingRaised, ActionExecuted) where each consumer builds its own model [S1041].

### 9. Runtime Architecture invariants RA-01..RA-05, and the consolidated runtime diagram (S1042 — 5 rows)
States Runtime Architecture invariant RA-01 [S1042]. States Runtime Architecture invariant RA-02 [S1042]. States Runtime Architecture invariant RA-03 [S1042]. States Runtime Architecture invariant RA-04 [S1042]. States Runtime Architecture invariant RA-05, and the consolidated runtime architecture diagram (Human/Engineer -> Claude/Codex Agent Workstation -> governed context/actions -> KNOWLEDGEOS {Governance, Knowledge, Evidence, Assurance, Agent Execution, Authorization, Context Service, Registry, Graph Projection} -> adapters/events -> {Engineering: Git/CI/Build/Artifacts, Runtime: Kubernetes/Nexus/Services} -> Observations -> Evidence -> Assurance) [S1042].

### 10. Work-package formalizations KOS-ARCH-001..004 (S1044 — 4 rows)
Work Package 1-2 (Preserve the Agent Edge / Formalize the Pointer Layer): KEEP repository-local agent integration (`.claude/`, `.codex/`, AGENTS.md, local tooling, fast feedback) without centralizing it, establishing AGENTS.md's precise responsibility ("how does an agent enter and operate" not "what is the authoritative architecture") and formalizing the pointer-layer rule as KOS-ARCH-001, applying equally to Claude and Codex [S1044]. Work Package 11 (Formalize Action Authorization): examine existing authorization mechanisms, integrate where present, establish ActionRequest->Authorization->Execution where absent, formalized as KOS-ARCH-002, especially important for production/infrastructure/security/governance changes [S1044]. Work Package 13 (Establish Provenance): the minimum traceability chain Source->Evidence->Claim->Decision (or for agents, Context->Recommendation->Authorization->Action->Evidence), formalized as KOS-ARCH-003; clarifies "discoverable" does not require raw source stored inside KnowledgeOS — a reference may suffice [S1044]. Work Package 17 (Normalize Claude and Codex): both should implement the same GET CONTEXT->REASON->RECOMMEND->REQUEST ACTION->AUTHORIZE->EXECUTE->REPORT EVIDENCE interaction model, formalized as KOS-ARCH-004, "the formal version of the Claude/Codex symmetry principle" [S1044].

### 11. Executable architecture-fitness rules AF-001..007 (S1045 — 1 row)
Defines seven Architecture Fitness rules AF-001..007 (AgentID, AuthorizationID, ContextID, RuleVersion reference, evidence-or-recorded-unavailability, no agent-local authoritative source, no cross-context direct mutation), each intended to become an executable constraint (e.g. AF-003: Action.contextId IS NOT NULL) rather than a documentation rule, distinct from the earlier AFR-nn catalogue though overlapping in intent; diagrams the ultimate executable form: Architecture Constitution->Architecture Rules->Deterministic Checkers->Verification->Architecture Health [S1045].

### 12. Interaction-contract invariants IC-001..010 (S1048 — 3 rows)
Interaction-contract invariant IC-001, illustrated by the Nexus adapter translating a Nexus API response into NexusObservation and ultimately EvidenceRegistered — the external API contract terminates at the adapter [S1048]. Interaction-contract invariant IC-002 [S1048]. Defines eight further interaction invariants IC-003..IC-010 consolidating the step's principles [S1048].

### 13. API-level architecture invariants API-001..008 (S1049 — 1 row)
Defines eight API-level architecture invariants API-001..008: no direct external mutation of domain state; verification verdicts produced by assurance not supplied by caller; material actions cannot bypass authorization; every material operation has trace/correlation identity; external system models do not cross the port/adapter boundary; agent capability does not imply execution authority; authoritative state transitions occur through explicit domain operations; graph/search projections are not authoritative mutation interfaces (preventing clients from treating the graph as master state) [S1049].

### 14. Persistence-architecture invariants DATA-001..006 (S1051 — 1 row)
Defines six persistence-architecture invariants DATA-001..006, with DATA-006 called "one of the most important graph requirements" [S1051].

### 15. Runtime-security invariants RT-001..006 (S1052 — 1 row)
Defines six runtime-security invariants RT-001..006 [S1052].

### 16. Registry invariants REG-001..007 and the Registry v1 Definition of Done (S1055 — 1 row)
Defines seven Registry invariants REG-001..007, and a Registry v1 Definition of Done checklist (confirmed contexts declared, module ownership explicit, dependency rules explicit, ports/adapters explicit, contracts/events identified, persistence ownership declared, blocking rules reference deterministic checkers, registry self-validates, repository scannable against it, at least one intentional violation detected, at least one legitimate exception represented, architecture evidence produced) [S1055].

### 17. Self-Assurance Engine invariants ASSURE-001..007 (S1057 — 1 row)
Defines seven Self-Assurance Engine invariants ASSURE-001..007 [S1057].

## Notes for P3
- Own observation: this label aggregates at least nine PARALLEL numbering series (AFR-nn up to 36, RA-nn, KOS-ARCH-nnn, AF-nnn, IC-nnn, API-nnn, DATA-nnn, RT-nnn, REG-nnn, ASSURE-nnn) written across 15 different source documents/steps. Several series overlap in intent without an explicit stated mapping between them (e.g. AF-001..007 in theme 11 is explicitly noted in its own source as "distinct from the earlier AFR-nn catalogue though overlapping in intent" — S1045). P3 reconciliation should treat this label as a catalogue-of-catalogues, not a single coherent rule numbering, and should prioritize resolving which series (if any) is the canonical/superseding one.
- Own observation: `lifecycle_candidate` is DORMANT with last_seen at the final (S1057) document in a long, clearly-still-active-at-the-time programme (Self-Assurance Engine invariants) — this reads as "the ledger's capture window ended here," not "the fitness-rule programme was abandoned"; P3 should check later corpus material for a continuation past S1057.
- Own observation: several rules across different series restate the same underlying principle in different vocabulary — e.g. AFR-01/AFR-11/AFR-33 (agent memory/local knowledge must not become authoritative) and AFR-05/AFR-24 (recommendations must stay distinguishable from decisions, with recommendation-evidence linkage) are essentially the same non-collapse restated at each new numbering series; a single canonical statement of each underlying principle, with the rule-IDs as pointers, would likely reduce this catalogue considerably.
- Own observation: the AFR/RA/KOS-ARCH/AF/IC/API/DATA/RT/REG/ASSURE numbering pattern itself (Architecture Principle -> Fitness Rule -> Automated Check -> Evidence -> Verdict, named explicitly in theme 2) is the label's most reusable structural contribution — worth treating as a first-class object in its own right (a "fitness rule pipeline" concept) separate from any specific rule catalogue, if P3 does not already have such a label.
