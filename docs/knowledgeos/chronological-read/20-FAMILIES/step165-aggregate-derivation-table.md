# step165-aggregate-derivation-table

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Aggregate = State + Invariants + Transactional Consistency, Evidence/Knowledge/Decision/Authorization: Strong; Determination/Inquiry: Medium; Action/Execution/Observation: Weak, c --I_i--> S_i' --> Event_i · **Aliases:** aggregate derivation table (step 165), five-question aggregate root test
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 165's formal aggregate test (Aggregate = State + Invariants + Transactional Consistency; central question 'what must change together?') applied via a five-question aggregate-root test (Identity/Lifecycle/Invariants/Ownership/Consistency) to each Step-162 BC candidate, producing a confidence table: Evidence, Knowledge, Decision, Authorization = Strong aggregate candidates; Determination, Inquiry = Medium; Action, Execution, Observation = Weak/provisional -- explicitly framed as hypotheses, not architecture decisions. Formalizes the state-transition/event mechanism c --I_i--> S_i' --> Event_i (a command c is only accepted if the resulting state S_i' satisfies the aggregate's invariant set I_i, and only then may Event_i be emitted). States the central lesson: 'a domain concept earns an aggregate boundary by possessing invariants that require local consistency -- not simply because it is an important noun' and 'a bounded context earns its boundary because its model, language, ownership and invariants differ -- not because we want another service.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1358 §"The aggregate is not: a convenient collection of related objects. It is: a consistency boundary that protects invariants. So our fundamental test is: Aggregate = State + Invariants + Transactional Consistency. The key question is: What must change together?"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1358 §"Test 1 — Identity ... Test 2 — Lifecycle ... Test 3 — Invariants ... Test 4 — Ownership ... Test 5 — Consistency. If most answers are 'yes': AggregateCandidate = Strong. ... Strong aggregate candidates: Evidence, Knowledge, Decision, Authorization. Medium candidates: Determination, Inquiry. Weak/provisional candidates: Action, Execution, Observation. This is not final."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1358. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1358 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1358 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1358 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1358 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1358] (FORMALIZATION/ANALYSIS) Applies a five-question aggregate-root test (Identity/Lifecycle/Invariants/Ownership/Consistency) per-concept via scoring tables, producing: Evidence, Knowledge, Decision, Authorization = Strong; Determination, Inquiry = Medium; Action, Execution, Observation = Weak/provisional -- explicitly hypotheses, not final architecture decisions. Notes Determination is uncertain between two models: (A) a first-class domain object with its own lifecycle, or (B) a governed projection of reasoning D=f(K,E,C,M) not requiring an independent aggregate; the 'mathematician's test' (can two valid Determinations D1=f(K,E,C1), D2=f(K,E,C2) coexist over the same Knowledge under different contexts? yes) strengthens the case for treating Determination as a distinct 'contextual conclusion' model rather than 'the truth derived from Knowledge'.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1358] types=['DEFINITION', 'PRINCIPLE'] scope=METHODOLOGICAL — "Redefines aggregate strictly as a consistency boundary protecting invariants (Aggregate = State + Invariants + Transactional Consistency), never a convenient collection of related objects; the derivation question is 'what must change together?'" (anchor: "The aggregate is not: a convenient collection of related objects. It is: a consistency boundary that protects invariants. So our fundamental test is: Aggregate = State + Invariants + Transactional Consistency. The key question is: What must change together?")
- [S1358] types=['FORMALIZATION', 'ANALYSIS'] scope=THEORY-LEVEL — "Applies a five-question aggregate-root test (Identity/Lifecycle/Invariants/Ownership/Consistency) per-concept via scoring tables, producing: Evidence, Knowledge, Decision, Authorization = Strong; Determination, Inquiry = Medium; Action, Execution, Observation = Weak/provisional -- explicitly hypotheses, not final architecture decisions. Notes Determination is uncertain between two models: (A) a first-class domain object with its own lifecycle, or (B) a governed projection of reasoning D=f(K,E,C,M) not requiring an independent aggregate; the 'mathematician's test' (can two valid Determinations D1=f(K,E,C1), D2=f(K,E,C2) coexist over the same Knowledge under different contexts? yes) strengthens the case for treating Determination as a distinct 'contextual conclusion' model rather than 'the truth derived from Knowledge'." (anchor: "Test 1 — Identity ... Test 2 — Lifecycle ... Test 3 — Invariants ... Test 4 — Ownership ... Test 5 — Consistency. If most answers are 'yes': AggregateCandidate = Strong. ... Strong aggregate candidates: Evidence, Knowledge, Decision, Authorization. Medium candidates: Determination, Inquiry. Weak/provisional candidates: Action, Execution, Observation. This is not final.")
- [S1358] types=['DISTINCTION'] scope=THEORY-LEVEL — "Aggregate != BoundedContext: a bounded context (e.g. Governance) can contain several aggregates (Policy, Decision, Authority, Delegation), so classifying Governance as a BC does not mean Governance itself is one aggregate." (anchor: "Aggregate ≠ BoundedContext. A bounded context can contain several aggregates. ... Governance BC ├── Policy ├── Decision ├── Authority └── Delegation. So our earlier candidate: Governance = BC does not imply: Governance = Aggregate.")

## Notes for P3
None beyond what is recorded above.
