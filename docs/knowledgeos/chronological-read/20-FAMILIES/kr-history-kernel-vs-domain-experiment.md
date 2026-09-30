# kr-history-kernel-vs-domain-experiment

**Scope(s):** METHODOLOGICAL · **Row count:** 9 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** KR-HISTORY-2026-09-02 · **Aliases:** Closure Event: Kernel vs Domain History
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0059, scope METHODOLOGICAL: Follow-up experiment to KR-CLOSURE-2026-09-02 testing whether ClosureEvent irreversibility belongs to Kernel, pure Audit, or Domain History, via a decisive test comparing two histories reaching identical K_t,K_{t+1}.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2441 §"KR-HISTORY-2026-09-02 -- Closure Event: Kernel vs Domain History. Test only: 1. Can every epistemically relevant current-state behavior be reproduced without ClosureEvent? ... The decisive test is: K_t,K_{t+1} identical ∧ History_1≠History_2. If the two histories produce identical epistemic behavior for every kernel operation, then ClosureEvent is not kernel capability."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2441 §"KR-HISTORY-2026-09-02 -- Closure Event: Kernel vs Domain History. Test only: 1. Can every epistemically relevant current-state behavior be reproduced without ClosureEvent? ... The decisive test is: K_t,K_{t+1} identical ∧ History_1≠History_2. If the two histories produce identical epistemic behavior for every kernel operation, then ClosureEvent is not kernel capability."]
- CANDIDATE-GOVERNANCE-BIRTH: [S2443 §"The smallest useful next step is a decision, not an experiment: does KnowledgeOS want an operation that consumes closure history? ... Do not resolve it by adding the operation and observing that it works -- that would be the 'repair by implementation' failure mode this programme has already recorded three times."]

## Lifecycle
last_seen: S2463. Candidate lifecycle: ACTIVE.
Evidence: no retraction/supersession/contradiction evidence recorded — the ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2443 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2443 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2443 |
| experiments | PRESENT | S2441, S2443 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2443] (ANALYSIS/LIMITATION) Hypotheses A (pure audit) and B (domain history) are architecturally indistinguishable until a consumer of closure history exists; S2441's own proposed consumer example ('never closed' vs 'closed and reopened') does not currently exist in the theory or corpus -- adding it would make B true, but that would be a design decision, not a discovery.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S2441] types=['EXPERIMENT'] scope=METHODOLOGICAL — "Specifies the follow-up experiment KR-HISTORY-2026-09-02 (Closure Event: Kernel vs Domain History) with six test questions and a decisive test: construct two worlds with identical K_t and K_{t+1} but differing histories (History_1 != History_2); if every kernel operation behaves identically across the two, ClosureEvent is not kernel capability; if a legitimate domain operation distinguishes them, it may be domain history, but that still does not establish kernel membership." (anchor: "KR-HISTORY-2026-09-02 -- Closure Event: Kernel vs Domain History. Test only: 1. Can every epistemically relevant current-state behavior be reproduced without ClosureEvent? ... The decisive test is: K_t,K_{t+1} identical ∧ History_1≠History_2. If the two histories produce identical epistemic behavior for every kernel operation, then ClosureEvent is not kernel capability.")
- [S2443] types=['HYPOTHESIS'] scope=OBJECT — "States the three unassumed hypotheses under test: A (ClosureEvent is pure Audit/History), B (ClosureEvent is DomainHistory that later domain behavior depends on), C (ClosureEvent is kernel structure)." (anchor: "A ClosureEvent ∈ Audit/History the kernel need not know closure happened. B ClosureEvent ∈ DomainHistory later DOMAIN behaviour depends on it. C ClosureEvent ∈ 𝒦 the kernel itself needs event semantics.")
- [S2443] types=['EXPERIMENT'] scope=OBJECT — "Constructs the decisive test configuration: two epistemic states H_closed (history Determine->ClosureEvent->Revise(superseded)->Determine, 4 entries) and H_never (history Acquire->Determine, 2 entries) with identical current content (determinations, evidence, hypotheses, rejections, observations, interpretations, assessments) but different histories -- exactly the K_t,K_{t+1} identical / History_1!=History_2 configuration S2441 commissioned." (anchor: "H_closed: Determine → ClosureEvent → Revise(superseded) → Determine (4 entries, contains ClosureEvent: yes). H_never: Acquire → Determine (2 entries, contains ClosureEvent: no). ... determinations, evidence, hypotheses, rejections, observations, interpretations, assessments identical. K_t, K_{t+1} identical ∧ History_1 ≠ History_2 -- exactly the configuration commissioned.")
- [S2443] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "All six commissioned tests (T1-T6) resolved: T1 yes (behavior reproducible without ClosureEvent), T2 no (history not reconstructable from state alone), T3 no (zero operations read closure), T4 neither the event nor historical state is currently required -- only the record's survival (preservation is not consumption), T5 yes (an external History context can supply it without enlarging the kernel), T6 only observability/auditability changes if ClosureEvent is removed, not semantics." (anchor: "T1: Can every epistemically relevant current-state behaviour be reproduced without ClosureEvent? Yes ... T2: Can historical closure be reconstructed from state alone? No ... T3: Does any valid KnowledgeOS operation require knowing closure happened? No -- zero reads, statically confirmed. T4: ... Neither, currently. AX-5, I9 and §17 require the record to survive. Preservation is not consumption. T5: ... Yes -- the preservation duty is write-side ... T6: ... Only observability/auditability -- semantics are unchanged.")
- [S2443] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "A corpus search over reopen/re-open/previously closed/was closed/closed before/re-closure finds every hit is either research-programme closure (a different sense of 'closed') or the KR-CLOSURE protocol's own scenario text -- no operation, rule, or policy in the corpus actually reads whether a proposition was previously closed, establishing [EXP] the theory's history obligation is write-only: an axiom-level preservation duty (AX-5 provenance preservation, I9 provenance survival) with no consumer." (anchor: "The distinction turns on whether any operation, rule or policy consumes closure history. ... No operation, rule or policy in the corpus reads whether a proposition was previously closed. ... [EXP] The theory's history obligation is WRITE-ONLY: an axiom-level preservation duty with no consumer.")
- [S2443] types=['ANALYSIS', 'LIMITATION'] scope=OBJECT — "Hypotheses A (pure audit) and B (domain history) are architecturally indistinguishable until a consumer of closure history exists; S2441's own proposed consumer example ('never closed' vs 'closed and reopened') does not currently exist in the theory or corpus -- adding it would make B true, but that would be a design decision, not a discovery." (anchor: "A and B are architecturally indistinguishable until a consumer exists. Your own example -- distinguishing 'never closed' from 'closed and subsequently reopened' -- is precisely such a consumer. It does not exist. Adding it would make B true; that is a design decision, not a discovery.")
- [S2443] types=['EXPERIMENTAL-RESULT', 'PRINCIPLE'] scope=THEORY-LEVEL — "Confirms and sharpens S2441's proposed principle Current State != Historical Transition (K_t != History_{<=t}, confirmed by T2) with a stronger, statically-testable second clause [PROP]: the kernel WRITES history and never READS it -- establishing a standing invariant that any future operation reading history is a proposal to move the kernel/history boundary and must be explicitly adjudicated, not silently absorbed." (anchor: "Current State ≠ Historical Transition and therefore K_t ≠ History_{≤t}. Confirmed by T2 -- no function of K_t recovers the history. The experiment adds a second clause: [PROP] the kernel WRITES history and never READS it -- ... any future operation that reads history is a proposal to move the boundary, and must be adjudicated as such rather than absorbed.")
- [S2443] types=['GOVERNANCE', 'WARNING'] scope=METHODOLOGICAL — "Frames the next step as a governance decision, not an experiment: whether KnowledgeOS wants an operation consuming closure history (if no, A is settled and the write-only boundary becomes a standing invariant; if yes, the first such operation must be named and given a home, likely History as a bounded context with behavior) -- explicitly warning against resolving it by 'repair by implementation' (adding the operation and observing it works), a failure mode already recorded three times in this programme." (anchor: "The smallest useful next step is a decision, not an experiment: does KnowledgeOS want an operation that consumes closure history? ... Do not resolve it by adding the operation and observing that it works -- that would be the 'repair by implementation' failure mode this programme has already recorded three times.")
- [S2463] types=['CORRECTION', 'LIMITATION'] scope=OBJECT — "Cautions against over-generalizing S2443's empirical finding (ReadsHistory(Kernel)=0 in the current implementation) into a theoretical law: retrospective epistemic revision may legitimately require historical information, so history access should remain an explicit architectural capability decision rather than a constitutionalized 'kernel never reads history' rule." (anchor: "'The kernel writes history and never reads it.' ... does not yet establish a theoretical law. ... retrospective epistemic revision may legitimately require historical information. So I would preserve: History access by the kernel is an explicit architectural capability rather than constitutionalizing: Kernel never reads History.")

## Notes for P3
None beyond what is recorded above.
