# step171-accountability-authority-chain-and-transition-contract

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Actor_technical vs AccountableEntity, Delegation != AccountabilityTransfer, Identity!=Capability!=Responsibility!=Authority!=Accountability, T=<SourceState,TargetState,Preconditions,Producer,Verifier,Authority,Evidence,Time,Accountability>, Valid(T) = Preconditions ∧ EvidenceSufficient ∧ AuthorityValid ∧ TemporalConstraints ∧ VerificationSatisfied · **Aliases:** five orthogonal governance dimensions, transition contract formalism
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0033 · scope THEORY-LEVEL: Step 171 chains together five distinct predicates for an actor a: Identity(a), Capability(a,x), Authority(a,x), Responsibility(a,x), Accountability(a,x), explicitly warning against collapsing them into a single role=admin field; adds Identity!=Authority, Auth(A,x) does not imply Accountable(A,x), and Accountable(A,x) does not imply Correct(x) (accountability concerns responsibility, not epistemic truth). Requires AI accountability to be modeled by separating a TechnicalActor (e.g. AI) from a distinct AccountableHuman/Organization -- 'AI = accountable' merely because AI performed the action is explicitly disallowed. States Delegation != AccountabilityTransfer (a system may delegate execution while retaining accountability). Introduces a general transition contract T=<SourceState,TargetState,Preconditions,Producer,Verifier,Authority,Evidence,Time,Accountability> with Valid(T) = Preconditions AND EvidenceSufficient AND AuthorityValid AND TemporalConstraints AND VerificationSatisfied, framed as the beginning of a constitutional model expressed as invariants (I1-I5, e.g. Evidence must have provenance; consequential decisions must have identifiable authority) rather than technology mandates.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1364 §"we should never model: AI = accountable merely because AI performed the action. Instead: AI → TechnicalActor while: AccountableHuman/Organization is modeled separately where required. ... Delegation ≠ AccountabilityTransfer. ... Identity(a) Capability(a,x) Authority(a,x) Responsibility(a,x) Accountability(a,x) are distinct predicates. ... Auth(A,x) ⇏ Accountable(A,x). ... Accountable(A,x) ⇏ Correct(x)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1364 §"T = <SourceState, TargetState, Preconditions, Producer, Verifier, Authority, Evidence, Time, Accountability>. A transition is valid only when its applicable contract is satisfied. Formally: Valid(T) = Preconditions ∧ EvidenceSufficient ∧ AuthorityValid ∧ TemporalConstraints ∧ VerificationSatisfied. ... I1: Evidence must have provenance. I2: Consequential decisions must have identifiable authority. I3: Execution must respect applicable authorization. I4: Verification scope must be explicit. I5: Historical decision justification must be reconstructible where required."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1364 §"T = <SourceState, TargetState, Preconditions, Producer, Verifier, Authority, Evidence, Time, Accountability>. A transition is valid only when its applicable contract is satisfied. Formally: Valid(T) = Preconditions ∧ EvidenceSufficient ∧ AuthorityValid ∧ TemporalConstraints ∧ VerificationSatisfied. ... I1: Evidence must have provenance. I2: Consequential decisions must have identifiable authority. I3: Execution must respect applicable authorization. I4: Verification scope must be explicit. I5: Historical decision justification must be reconstructible where required."]

## Lifecycle
last_seen: S1364. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1364 |
| type_signature | PRESENT | S1364 |
| invariants | PRESENT | S1364 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1364 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1364] types=[PRINCIPLE, INVARIANT] scope=THEORY-LEVEL — "Prohibits modeling 'AI = accountable' merely because AI performed the action -- AI is a TechnicalActor, with AccountableHuman/Organization modeled separately where required. States Delegation != AccountabilityTransfer (delegated execution need not transfer accountability). Chains five distinct predicates for an actor -- Identity, Capability, Authority, Responsibility, Accountability -- warning against collapsing them into a single role=admin field; adds Auth(A,x) does not imply Accountable(A,x), and Accountable(A,x) does not imply Correct(x) (accountability is about responsibility, not epistemic truth)." (anchor: "we should never model: AI = accountable merely because AI performed the action. Instead: AI → TechnicalActor while: AccountableHuman/Organization is modeled separately where required. ... Delegation ≠ AccountabilityTransfer. ... Identity(a) Capability(a,x) Authority(a,x) Responsibility(a,x) Accountability(a,x) are distinct predicates. ... Auth(A,x) ⇏ Accountable(A,x). ... Accountable(A,x) ⇏ Correct(x).")
- [S1364] types=[FORMALIZATION, GOVERNANCE] scope=THEORY-LEVEL — "Defines a general transition contract T=<SourceState,TargetState,Preconditions,Producer,Verifier,Authority,Evidence,Time,Accountability> with validity Valid(T) = Preconditions AND EvidenceSufficient AND AuthorityValid AND TemporalConstraints AND VerificationSatisfied (exact conjunction varies by domain). Frames this as the beginning of a constitutional model expressed via invariants rather than technology mandates, listing five example laws: I1 Evidence must have provenance; I2 consequential decisions must have identifiable authority; I3 execution must respect applicable authorization; I4 verification scope must be explicit; I5 historical decision justification must be reconstructible where required." (anchor: "T = <SourceState, TargetState, Preconditions, Producer, Verifier, Authority, Evidence, Time, Accountability>. A transition is valid only when its applicable contract is satisfied. Formally: Valid(T) = Preconditions ∧ EvidenceSufficient ∧ AuthorityValid ∧ TemporalConstraints ∧ VerificationSatisfied. ... I1: Evidence must have provenance. I2: Consequential decisions must have identifiable authority. I3: Execution must respect applicable authorization. I4: Verification scope must be explicit. I5: Historical decision justification must be reconstructible where required.")

## Notes for P3
(none beyond what is captured above)
