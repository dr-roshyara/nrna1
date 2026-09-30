# multi-temporal-dimension-model

**Scope(s):** THEORY-LEVEL · **Row count:** 11 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `T_v,T_o,T_k,T_d,T_x` · **Aliases:** `five temporal coordinates`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 193's core result: a proposition's lifecycle involves five distinct temporal coordinates (Valid, Observation, Knowledge/Record, Decision, Execution time) that must not be collapsed into one timestamp; includes the time-indexed status function S_P(t) and the five distinct query types (reality/epistemic/governance/operational/lineage) it motivates.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1396] §"Reality_{t_1}\neq Observed_{t_2}\neq Known_{t_3}\neq Decided_{t_4}\neq Executed_{t_5}"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1396] §"Reality_{t_1}\neq Observed_{t_2}\neq Known_{t_3}\neq Decided_{t_4}\neq Executed_{t_5}"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1396. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1396 |
| informal_meaning | PRESENT | S1396 |
| formal_definition | PRESENT | S1396 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1396 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1396 |
| examples | PRESENT | S1396 |
| warnings | PRESENT | S1396 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
A bare timestamp field is architecturally insufficient because it cannot answer which of five different temporal questions (true/observed/recorded/approved/executed then?) it is answering [S1396]. Step 193 synthesis: connects Reality, Time, Evidence, Knowledge, and Governance, each with its own temporal semantics; the most important distinction is that 'what happened' != 'what was known' != 'what was decided' at a given point in time [S1396]. Step 193 verdict: the temporal test passes but refines the architecture's self-description from 'a graph of knowledge' to 'a temporally versioned epistemic and governance transition system' -- a more precise description, still consistent with the Gita Chapter 1-4 lens (Conflict->Continuity->Action->Transmission/Lineage) used only as a distinction-discovery tool, not a specification source [S1396].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1396] types=[FORMALIZATION, DEFINITION] scope=THEORY-LEVEL — "Core test: five distinct temporal coordinates for one proposition -- Valid time T_v (when true in the domain), Observation time T_o, Knowledge/record time T_k, Decision time T_d, Execution time T_x -- worked through a Nexus-upgrade timeline where t1<t2<t3<t4<t5; declares nothing pathological about these differing, as real engineering systems usually show this pattern." (anchor: "Reality_{t_1}\neq Observed_{t_2}\neq Known_{t_3}\neq Decided_{t_4}\neq Executed_{t_5}")
- [S1396] types=[WARNING, ARGUMENT] scope=THEORY-LEVEL — "A bare timestamp field is architecturally insufficient because it cannot answer which of five different temporal questions (true/observed/recorded/approved/executed then?) it is answering." (anchor: "Timestamp without temporal semantics is insufficient.")
- [S1396] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Walks through valid/observation/knowledge/decision/execution time as separate worked events (Nexus changes at T_v=08:00, observed at T_o=08:45, ingested at T_k=09:00, decided at T_d=10:00, executed at T_x=11:30), each pairwise distinct, with decision explicitly not retroactive knowledge and execution not guaranteed by decision (T_d != T_x, execution can fail)." (anchor: "T_o\neq T_v. ... The organization could not have used this particular knowledge at 08:30 unless it had another source.")
- [S1396] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Extends the proposition representation to carry all five temporal coordinates, but immediately argues this is cleaner represented as separate ObservationEvent(T_o)/KnowledgeEvent(T_k)/DecisionEvent(T_d)/ExecutionEvent(T_x) objects than one object with five timestamp fields -- each event carries its own temporal meaning." (anchor: "P=(statement,context,T_v,T_o,T_k,T_d,T_x). But again, these do not necessarily belong to one object.")
- [S1396] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "An observation's own event timestamp (T_o=10:00) does not establish the causal/domain time it refers to (T_v=08:00); explicit temporal semantics are required, not just a raw timestamp." (anchor: "EventTime\neqDomainTime.")
- [S1396] types=[FORMALIZATION, CORRECTION] scope=OBJECT — "Significant correction: epistemic status is not a scalar Status(P)=S but a time-indexed function Status(P,t)=S, i.e. S_P:T->S (piecewise, e.g. Unknown before t1, Supported in [t1,t2), Refuted after t2); a query for 'current status' and a query for 'status at t1' are both correct simultaneously." (anchor: "Status(P,t)=S. ... Knowledge state is a function: S_P:T\rightarrow\mathcal S.")
- [S1396] types=[VALIDATION, RESTATEMENT] scope=THEORY-LEVEL — "Temporal restatement/validation of the earlier Decision!=Outcome result: Decision(T_d=10:00) does not imply NexusVersion=3.71 at T_d; only execution establishes the operational change." (anchor: "Decision does not imply outcome. We already derived this; the temporal model now makes it mathematically explicit.")
- [S1396] types=[VALIDATION, EXAMPLE] scope=OBJECT — "If execution fails after an approved decision, DecisionStatus=Approved and ExecutionStatus=Failed coexist validly -- the decision remains historically valid even though the outcome diverged from the expected one." (anchor: "DecisionStatus=Approved and: ExecutionStatus=Failed can coexist. This validates our separation of governance and operational state.")
- [S1396] types=[RESTATEMENT, ANALYSIS] scope=THEORY-LEVEL — "Step 193 synthesis: connects Reality, Time, Evidence, Knowledge, and Governance, each with its own temporal semantics; the most important distinction is that 'what happened' != 'what was known' != 'what was decided' at a given point in time." (anchor: "Reality \leftrightarrow Time \leftrightarrow Evidence \leftrightarrow Knowledge \leftrightarrow Governance ... 'What happened?' \neq 'What was known?' \neq 'What was decided?'")
- [S1396] types=[EXTENSION, DEFINITION] scope=THEORY-LEVEL — "Proposes five conceptually distinct query types KnowledgeOS should ultimately support: Reality-oriented, Epistemic, Governance, Operational, and Lineage queries, each answering a fundamentally different question about the same point in time." (anchor: "WhatWasTrueAt(t)? ... WhatWasKnownAt(t)? ... WhatWasDecidedAt(t)? ... WhatWasExecutedAt(t)? ... WhyWasStateReachedAt(t)?")
- [S1396] types=[RESTATEMENT, ARGUMENT] scope=THEORY-LEVEL — "Step 193 verdict: the temporal test passes but refines the architecture's self-description from 'a graph of knowledge' to 'a temporally versioned epistemic and governance transition system' -- a more precise description, still consistent with the Gita Chapter 1-4 lens (Conflict->Continuity->Action->Transmission/Lineage) used only as a distinction-discovery tool, not a specification source." (anchor: "Temporally versioned epistemic and governance transition system.")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
