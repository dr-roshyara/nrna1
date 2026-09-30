# step166-process-vs-aggregate-vs-case

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Aggregate=domain state; ProcessManager=coordination state`; `Case != ProcessManager`; `PM: Event* -> NextCommand`; `Process != Aggregate`; `ProcessManager proposes; Aggregate decides legality`
**Aliases:** "Process Manager, Saga, Case distinctions (step 166)"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 166's coordination-layer taxonomy: Process != Aggregate (a process such as Evidence->Knowledge->Determination->Decision->Authorization->Action coordinates causally related but independently-owned state transitions across bounded contexts, none of which is one aggregate); a Process Manager maintains long-running coordination state PM: Event* -> NextCommand without owning any domain invariant (ProcessManager != DomainAuthority; formally ProcessManager proposes commands, the target Aggregate independently evaluates Invariant(S,c) and decides legality -- 'defense in depth' even against a wrongly-coordinating process); a Case represents a business/investigative subject (e.g. 'assess whether Nexus migration may proceed') bundling references to Inquiry+Evidence+Determination+Decision without owning them, distinct from a Process Manager (Case != ProcessManager) and possibly closer to the actual KnowledgeOS domain than a generic Saga -- 'a navigational boundary across knowledge and governance', answering 'what are we trying to resolve?' rather than 'what is true?'. Introduces four distinct identifier types answering different questions: EntityID (which domain object), ProcessID (which coordination process), CaseID (which business/inquiry subject), CorrelationID (which related interaction/event group)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1359 §"This is a process. But none of these objects necessarily belongs to one aggregate. Therefore: Process ≠ Aggregate. A process coordinates. An aggregate protects invariants."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1359 §"PM: Event* → NextCommand. ... The Process Manager may know: 'After X, ask Y.' But it should not decide: 'Knowledge is valid.' That belongs to the Knowledge aggregate. Therefore: ProcessManager ≠ DomainAuthority. ... ProcessManager proposes; Aggregate decides legality. ... Policy → Process → Command → AggregateInvariant. Even if process coordination is wrong, the aggregate protects itself."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1359. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1359), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1359 (×2) |
| type_signature | PRESENT | S1359 |
| invariants | PRESENT | S1359 (×3) |
| dependencies | PRESENT | S1359 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1359 (×5) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1359] types=[DISTINCTION] scope=THEORY-LEVEL — "Distinguishes Process (coordinates causally related but independently owned state transitions across contexts, e.g. Evidence->Knowledge->Determination->Decision->Authorization->Action) from Aggregate (protects invariants) -- Process != Aggregate." (anchor: "This is a process. But none of these objects necessarily belongs to one aggregate. Therefore: Process ≠ Aggregate. A process coordinates. An aggregate protects invariants.")
- [S1359] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Formalizes a Process Manager as a function from event history to the next command (PM: Event* -> NextCommand) that coordinates without holding domain authority (ProcessManager != DomainAuthority); the strong design rule 'ProcessManager proposes; Aggregate decides legality' means even a miscoordinating process cannot bypass an aggregate's invariant check (Policy -> Process -> Command -> AggregateInvariant), giving defense in depth, particularly valuable for AI-assisted systems: 'AI participates in the process; AI does not own the process' (an AI recommendation becomes a CandidateCommand subject to Governance/InvariantEvaluation, never executed directly)." (anchor: "PM: Event* → NextCommand. ... The Process Manager may know: 'After X, ask Y.' But it should not decide: 'Knowledge is valid.' That belongs to the Knowledge aggregate. Therefore: ProcessManager ≠ DomainAuthority. ... ProcessManager proposes; Aggregate decides legality. ... Policy → Process → Command → AggregateInvariant. Even if process coordination is wrong, the aggregate protects itself.")
- [S1359] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Introduces Case as a candidate concept representing a business/investigative subject, bundling references (not ownership) to Inquiry+Evidence+Determination+Decision, distinct from Process Manager (Case != ProcessManager; a Case may be long-lived and span many processes); described as possibly closer to the actual KnowledgeOS domain than a generic Saga, functioning as 'a navigational boundary across knowledge and governance' answering 'what are we trying to resolve?' rather than 'what is true?' -- explicitly to be derived from the domain, not assumed. Also introduces a four-identifier taxonomy: EntityID (which domain object), ProcessID (which coordination process), CaseID (which business/inquiry subject), CorrelationID (which related interaction/event group)." (anchor: "A Case represents a unit of investigation/work. ... The Case could contain: Inquiry + EvidenceReferences + DeterminationReferences + DecisionReference. ... Case ≠ ProcessManager. A Case may be long-lived and contain many processes. ... A navigational boundary across knowledge and governance. It answers: What are we trying to resolve? rather than: What is true?")
- [S1359] types=[DISTINCTION, PRINCIPLE] scope=THEORY-LEVEL — "Distinguishes AgentSession (a computational interaction) from BusinessProcess (a domain coordination state) -- a process may survive many sessions (e.g. investigation session, verification session, governance-review session, execution session), so process state must be durable and resumable from authoritative state after a session/agent disappears -- stated as a major requirement for a reliable AI engineering platform." (anchor: "AgentSession ≠ BusinessProcess. A session is a computational interaction. A process is a domain coordination state. A process may survive many sessions. ... A new agent/session can resume it from authoritative state. This is a major requirement for a reliable AI engineering platform.")
- [S1359] types=[RESTATEMENT, PRINCIPLE] scope=THEORY-LEVEL — "Consolidates the step's answer to 'how can a system act without forgetting why it acted?' via a State+Process+Lineage triad, each answering a distinct question (State: what is true now; Process: what are we doing/waiting for; Lineage: how did we get here), alongside Evidence/Knowledge/Determination (why do we believe what we believe), Governance (who had authority to decide), and Operations (what actually happened). Also draws (with explicit caution against overclaiming) a control-theoretic analogy Observe->Interpret->Decide->Act->Observe as a useful analytical lens without literally treating KnowledgeOS as a control-theory system, DDD remaining the primary model; closes with nine architectural aphorisms: Aggregates protect, Events record, Commands request, Processes coordinate, Governance authorizes, Evidence supports, Knowledge represents governed claims, Agents contribute, Observations reconnect execution to knowledge." (anchor: "State + Process + Lineage. State tells us: What is true now? Process tells us: What are we doing / waiting for? Lineage tells us: How did we get here? And Evidence/Knowledge/Determination tell us: Why do we believe what we believe? Governance tells us: Who had the authority to decide? Operations tells us: What actually happened? ... How can a system act without forgetting why it acted?") — lineage claim: SOURCE-CLAIMED-CONTINUATION of Step 167

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
