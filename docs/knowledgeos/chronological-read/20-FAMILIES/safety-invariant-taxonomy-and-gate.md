# safety-invariant-taxonomy-and-gate

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Gate(d,K,t)=InvariantSatisfied(d,K,t)`, `HardInvariant/PolicyConstraint/AdvisoryCondition` · **Aliases:** `Invariant taxonomy`, `Safety gate`
**Candidate group membership (NOT an identity claim):**
- **G0193**: [`invariant-catalog` · `safety-invariant-taxonomy-and-gate`] — explicit agent-stated uncertainty: 'safety-invariant-taxonomy-and-gate' POSSIBLY relates to 'invariant-catalog' (batch B0023). Note: Step 42's general invariant taxonomy (structural/temporal/numerical/referential/governance/security), the Hard/Soft/Advisory-corrected-to-Hard/PolicyConstraint/Advisory classification, invariant ownership by bounded context, inductive invariant proof pattern, and the safety-gate unknown-invariant-blocks default; distinct from invariant-catalog (BC-7's specific I-1..I-10/T-1..T-4 catalog).
- **G1451**: [`assurance-composition-tree` · `safety-invariant-taxonomy-and-gate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1452**: [`decision-contract-admissibility-model` · `safety-invariant-taxonomy-and-gate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0023, scope OBJECT: "Step 42's general invariant taxonomy (structural/temporal/numerical/referential/governance/security), the Hard/Soft/Advisory-corrected-to-Hard/PolicyConstraint/Advisory classification, invariant ownership by bounded context, inductive invariant proof pattern, and the safety-gate unknown-invariant-blocks default; distinct from invariant-catalog (BC-7's specific I-1..I-10/T-1..T-4 catalog)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0937 §"Gate(d,K,t)=InvariantSatisfied(d,K,t); Unknown safety condition != Safe"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0937 §"Invariant taxonomy: structural, temporal, numerical, referential, governance, security"]
- CANDIDATE-FORMAL-BIRTH: [S0937 §"Formal invariant I(s)=True over reachable states; inductive invariant reasoning"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0937. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0937 |
| type_signature | PRESENT | S0937 |
| invariants | PRESENT | S0937 |
| dependencies | PRESENT | S0937 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0937 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0937 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0937] types=[DEFINITION, PRINCIPLE] scope=OBJECT — "Defines a safety gate as Gate(d,K,t)=InvariantSatisfied(d,K,t), with the rule that for critical invariants False => Block. For the case Invariant=Unknown, argues the default should generally be Block for safety-critical decisions -- 'a very strong architectural default' framed as a policy decision, formalized as Unknown safety condition != Safe." (anchor: "Gate(d,K,t)=InvariantSatisfied(d,K,t); Unknown safety condition != Safe")
- [S0937] types=[DISTINCTION, PRINCIPLE] scope=THEORY-LEVEL — "Distinguishes open-world semantics (absence of evidence for A does not imply not-A) from closed-world semantics (system may treat unknown A as False). Argues KnowledgeOS should not adopt one universal assumption; the applicable domain policy must specify which semantics apply. For a safety gate, Unknown->Block is usually appropriate; for exploratory analytics, Unknown->ContinueWithWarning may be appropriate; Policy_decision determines the treatment of unknowns." (anchor: "Open-world versus closed-world interpretation")
- [S0937] types=[CONCEPT, DEFINITION] scope=OBJECT — "Distinguishes six invariant kinds with examples: Structural (Entity must have exactly one owner), Temporal (Approval must precede deployment), Numerical (Amount>=0), Referential (EveryClaim -> ValidEvidence), Governance (ProductionChange -> RequiredApproval), Security (Credential must not be exposed)." (anchor: "Invariant taxonomy: structural, temporal, numerical, referential, governance, security")
- [S0937] types=[PRINCIPLE, DISTINCTION] scope=CROSS-OBJECT — "In DDD, invariants often belong to an aggregate boundary (Aggregate must guarantee Invariant(AggregateState)); KnowledgeOS should respect that ownership and not arbitrarily redefine an aggregate's invariants. Every invariant should have an OwnerContext (e.g. Invariant_1 -> BC_Voting, Invariant_2 -> BC_Governance). This prevents KnowledgeOS from becoming the owner of every business invariant: instead KnowledgeOS represents, evaluates and traces governed invariants while the domain remains authoritative." (anchor: "Invariant ownership and DDD aggregate boundaries")
- [S0937] types=[DISTINCTION, CORRECTION] scope=OBJECT — "First proposes InvariantType in {Hard, Soft, Advisory} (Hard: violation blocks action; Soft: violation requires explicit justification; Advisory: violation produces warning), then self-corrects: strictly speaking a property that CAN be violated is not an invariant in the mathematical sense, so the vocabulary should instead be HardInvariant versus PolicyConstraint versus AdvisoryCondition -- flagged explicitly as 'a useful correction in our formal vocabulary'." (anchor: "HardInvariant versus PolicyConstraint versus AdvisoryCondition")
- [S0937] types=[FORMALIZATION] scope=OBJECT — "Defines a true invariant as I(s)=True for every reachable valid state s, established inductively: base case I(s_0)=True, inductive step I(s) and ValidTransition(s,s') => I(s'), concluding I holds for all reachable states -- noted as powerful for software verification. Applies it to a governed workflow Requested->Reviewed->Approved->Provisioned with the invariant Provisioned=>Approved, requiring it be impossible for a valid transition to produce Provisioned & not-Approved." (anchor: "Formal invariant I(s)=True over reachable states; inductive invariant reasoning")
- [S0937] types=[FORMALIZATION] scope=OBJECT — "Defines S as the set of system states, T subset of SxS as valid transitions, and an invariant I:S->{True,False}; the safety requirement is (s,s') in T and I(s) => I(s'). Connects this directly to event-driven architecture: an event such as ApprovalGranted must follow the domain's event semantics, and KnowledgeOS can reconstruct State_t from Events_<=t -- but an event itself may be invalid, so EventValid(e,K,t) must be checked (an invalid event must not silently establish a valid state). Each state transition should ideally be traceable end to end: Event -> Actor -> Evidence -> Authorization, giving complete decision lineage." (anchor: "State transition system S, T subset SxS, safety requirement")
- [S0937] types=[FORMALIZATION] scope=OBJECT — "Formalizes ordering invariants as t_approval < t_deployment (violated if t_deployment <= t_approval), and separately temporal validity: a fact can be true but stale -- e.g. SecurityApproved(t_1) is used at decision time t_2, requiring t_2 in ValidityWindow(SecurityApproval). Composes multiple temporal conditions into TemporalSufficiency(d,t) = ApprovalValid(t) and SecurityValid(t) and TestValid(t)." (anchor: "Temporal invariant and temporal validity window")
- [S0937] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Runs twelve falsification tests against the Step 42 model, all recorded PASS: (1) epistemic requirements pass but authorization fails => Admissible=False; (2) authorization passes but a hard invariant fails => Block; (3) all conditions known except a safety-critical invariant => Block/Unknown per policy, never automatic authorization; (4) a valid transition from a valid state preserves the invariant; (5) a transition that would violate a domain invariant is rejected; (6) a required approval expires before execution => decision becomes inadmissible; (7) evidence used by a decision is revoked => dependency closure identifies affected assurance and decisions; (8) an AI proposes an action violating a governance rule => proposal may be recorded but execution is blocked; (9) new evidence disproves an earlier assumption => existing assurance can be downgraded/revoked; (10) a decision is evaluated without execution => dry-run result has no side effect; (11) an invariant belongs to bounded context BC_A => KnowledgeOS does not silently redefine it from BC_B; (12) a decision passes technical assurance but lacks governance authorization => TechnicallySupported=True, Authorized=False, therefore Execute=False." (anchor: "Twelve falsification experiments for Step 42 (all PASS)")
- [S0937] types=[RESTATEMENT, PRINCIPLE] scope=THEORY-LEVEL — "Declares STEP 42 -- PASS and restates seven principles as the step's headline results: (1) Sufficient Knowledge != Valid Decision; (2) Valid Decision != Authorized Action; (3) Unknown safety state must not silently become safe; (4) Hard invariants are non-negotiable; (5) AI proposal != authorized execution; (6) Every consequential decision should have an explicit contract; (7) Evidence invalidation must propagate through decision dependencies." (anchor: "Step 42 verdict and seven closing principles")

## Notes for P3
- family.files_touching lists source_id(s) ['S0943'] that do not appear among this label's own family.rows — a data-completeness oddity for P3 to check against 03-CONTRIBUTIONS.jsonl.
