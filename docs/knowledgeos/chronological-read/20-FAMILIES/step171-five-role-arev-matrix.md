# step171-five-role-arev-matrix

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Authority-Responsibility-Evidence matrix, Cap(a,x)!=Auth(a,x)!=Resp(a,x), Initiator/Producer/Verifier/DecisionMaker/Executor/AccountableOwner · **Aliases:** five lifecycle roles, who may initiate/verify/approve/execute/is accountable
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 171's role-separation model for the pipeline: six distinct roles (Initiator, Producer, Verifier, DecisionMaker, Executor, AccountableOwner) that may coincide only by explicit domain decision, never by implementation accident. Extends Capability(a,x)!=>Authority(a,x) with two siblings: Responsibility(a,x)!=>Authority(a,x) and Authority(a,x)!=>Capability(a,x) (worked sysadmin example: Capability(Admin,Deploy)=true, Authority(Admin,Deploy)=false pending approval, Responsibility(Admin,Deploy)=true for operating the mechanism -- Cap!=Auth!=Resp). Produces a generic Authority-Responsibility matrix mapping each pipeline stage (Observation..Outcome) to Producer/Verifier/Decision-authority/Executor/Accountable columns, explicitly generic pending later per-bounded-context instantiation.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1364 §"Initiator Producer Verifier DecisionMaker Executor and, separately: AccountableOwner. These roles may sometimes be held by the same person or system. But that must be an explicit domain decision, not an accidental consequence of implementation. ... Cap(a,x) ... Resp(a,x) ... Auth(a,x) ... Cap ≠ Auth ≠ Resp."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1364. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1364 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1364 |
| dependencies | PRESENT | S1364 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1364 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1364 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1364] types=['DEFINITION', 'INVARIANT'] scope=THEORY-LEVEL — "Introduces six lifecycle roles (Initiator, Producer, Verifier, DecisionMaker, Executor, AccountableOwner) that may only coincide by explicit domain decision, never implementation accident. Extends the Capability!=>Authority invariant with two new predicates: Responsibility(a,x) does not imply Authority(a,x), and Authority(a,x) does not imply Capability(a,x) (worked example: a sysadmin with Capability(Admin,Deploy)=true and Responsibility(Admin,Deploy)=true may still have Authority(Admin,Deploy)=false pending approval) -- Cap!=Auth!=Resp as three distinct relations." (anchor: "Initiator Producer Verifier DecisionMaker Executor and, separately: AccountableOwner. These roles may sometimes be held by the same person or system. But that must be an explicit domain decision, not an accidental consequence of implementation. ... Cap(a,x) ... Resp(a,x) ... Auth(a,x) ... Cap ≠ Auth ≠ Resp.")
- [S1364] types=['RESTATEMENT', 'FUTURE-RESEARCH'] scope=THEORY-LEVEL — "Step 171 verdict: KnowledgeOS is not merely an information flow but 'an information flow constrained by authority'; Governance is not merely a set of approvals but 'the control structure governing epistemic and operational transitions'. Confirms the two structures (epistemic pipeline; governance/role flow) must intersect without collapsing (Identity!=Capability!=Responsibility!=Authority!=Accountability alongside Evidence->Knowledge->Determination->Decision->Authorization->Execution). Proposes Step 172 = Separation-of-Duties Falsification Experiment with six concrete scenarios (A: one human does everything; B: AI assesses, human verifies+approves; C: AI produces+verifies, human authorizes; D: deterministic verifier + governance board decides; E: emergency execution bypasses normal authorization; F: a later observation contradicts the knowledge basis of an earlier decision), testing architectural coherence in each with the rule: a failure means revising the model, not patching the example." (anchor: "KnowledgeOS is not merely an information flow. It is an information flow constrained by authority. And: Governance is not merely a set of approvals. It is the control structure governing epistemic and operational transitions. ... Scenario A ... Scenario F ... Does the architecture remain coherent? If it fails in any scenario, we do not patch the example—we revise the model.")

## Notes for P3
None beyond what is recorded above.
