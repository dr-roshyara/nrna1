# authority-identity-role-permission-program

**Scope(s):** THEORY-LEVEL · **Row count:** 8 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority != Identity != Role != Permission != Responsibility` · **Aliases:** `Step 196`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 195's closing research program, taken up as Step 196: separating authority from identity, role, permission, and responsibility, and testing whether the architecture prevents technical access from silently becoming epistemic authority.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1412 §"Authority\neqIdentity\neqRole\neqPermission\neqResponsibility ... whether our architecture can prevent an actor from acquiring epistemic authority merely because it has technical access."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1414. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1414 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1414 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1414, S1414, S1414, S1414, S1414 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1412 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1412] types=['FUTURE-RESEARCH', 'OPEN-QUESTION'] scope=THEORY-LEVEL — "Opens Step 196: having covered Truth/Evidence/Knowledge/Causality/Identity/Time/Decision, the next boundary is authority -- who is allowed to assert, determine, change, or invalidate a state (epistemic and domain authority, not mere authentication); proposes testing Authority!=Identity!=Role!=Permission!=Responsibility and whether the architecture can prevent an actor from acquiring epistemic authority merely from technical access, connecting to DDD policies, governance, deterministic assurance, and AI agents." (anchor: "Authority\neqIdentity\neqRole\neqPermission\neqResponsibility ... whether our architecture can prevent an actor from acquiring epistemic authority merely because it has technical access.")
- [S1414] types=['DEFINITION', 'DISTINCTION'] scope=THEORY-LEVEL — "Separately defines five concepts: Identity (who/what is acting), Role (what function the actor performs, contextual, possibly plural), Permission (what operation the actor can technically perform, an access-control concept), Responsibility (what the actor is accountable for), and Authority (whose determination is recognized as binding for a particular domain decision) -- explicitly not interchangeable." (anchor: "Identity ... Role ... Permission ... Responsibility ... Authority. They are related, but they are not interchangeable.")
- [S1414] types=['INVARIANT'] scope=THEORY-LEVEL — "An AI agent's write-permission (e.g. Permission(AI,writeProposal)=true) must never automatically confer Authority(approveArchitecture); AI execution capability must not imply governance authority." (anchor: "AI execution capability must not imply governance authority.")
- [S1414] types=['DISTINCTION'] scope=OBJECT — "An AI agent's outputs (Observation, Classification, Hypothesis, Recommendation, CandidateDecision) are all categorically distinct from AuthorizedDetermination; an AI-inferred proposition (P_candidate) must go through Review->Verification->Determination before becoming AuthoritativeKnowledge, never jumping directly there." (anchor: "Observation, Classification, Hypothesis, Recommendation, CandidateDecision. These are different from: AuthorizedDetermination.")
- [S1414] types=['DISTINCTION'] scope=OBJECT — "An expert can have strong technical knowledge without organizational decision authority, and vice versa; Expertise feeds Evidence/Assessment while Authority governs Determination -- these are separate roles in the pipeline." (anchor: "Expertise\neq Authority. ... Expertise \rightarrow Evidence/Assessment while: Authority \rightarrow Determination.")
- [S1414] types=['CONSTRAINT'] scope=OBJECT — "Authority does not flow directly to Action; an explicit decision or policy transition must intervene (Authority->Decision->Action), applying the Chapter-3 action lens." (anchor: "Authority \not\rightarrow Action directly. There should normally be an explicit decision or policy transition.")
- [S1414] types=['PRINCIPLE'] scope=THEORY-LEVEL — "Engineering principle: AI may expand the epistemic search space (hypotheses, candidate mappings, summaries, anomaly detections, statistical assessments, recommendations), but the authoritative transition itself must remain governed." (anchor: "AI may expand the epistemic search space; governance determines the authoritative state.")
- [S1414] types=['RESTATEMENT', 'VALIDATION'] scope=THEORY-LEVEL — "Step 196 verdict: restates the full five-way separation (Identity/Role/Permission/Responsibility/Authority) plus Authority!=Truth, Authority!=Correctness, and Technical-access!=Governance-legitimacy as established results, with the no-semantic-elevation principle as the most important outcome." (anchor: "Identity\neq Role\neq Permission\neq Responsibility\neq Authority. And: Authority\neq Truth. And: Authority\neq Correctness. And: Technical access\neq Governance legitimacy.")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
