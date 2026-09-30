# step008-six-outcome-acceptance-function-and-omega-A

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `NotAccepted != Rejected; Unresolved != Rejected`, `Omega_A=(SupportStatus,AcceptanceStatus,CommitmentStatus,ContestStatus)`, `alpha_rho: EA x P x C -> {Candidate,Supported,Accepted,Rejected,Contested,Unresolved}` · **Aliases:** `Step 008's branched six-outcome acceptance function`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): The source (Step 008) formal object underlying PF-6/the status-ladder-committed-boundary residue: a six-outcome acceptance function alpha_rho: EA x P x C -> {Candidate,Supported,Accepted,Rejected,Contested,Unresolved} explicitly warned to be non-linear (Supported+Contested is a legitimate joint combination), culminating in a source recommendation of a multidimensional status tuple Omega_A=(SupportStatus,AcceptanceStatus,CommitmentStatus,ContestStatus) with a worked instance (Support:Strong, Acceptance:Accepted, Commitment:Committed, Contest:Active) illustrating institutional acceptance coexisting with live epistemic dispute -- 'exactly why epistemic status and governance status should not be collapsed.' Boxes two inequalities the book states it will reuse constantly: NotAccepted != Rejected and Unresolved != Rejected (insufficiency is not refutation).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1385] §"these are not simply levels of 'confidence'; they represent different domain states. Its formal acceptance function has SIX outcomes — α_ρ : EA × P × C → {Candidate, Supported, Accepted, Rejected, Contested, Unresolved} ... Ω_A = ( SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus ) ... NotAccepted ≠ Rejected and Unresolved ≠ Rejected — insufficiency is not refutation."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1385] §"these are not simply levels of 'confidence'; they represent different domain states. Its formal acceptance function has SIX outcomes — α_ρ : EA × P × C → {Candidate, Supported, Accepted, Rejected, Contested, Unresolved} ... Ω_A = ( SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus ) ... NotAccepted ≠ Rejected and Unresolved ≠ Rejected — insufficiency is not refutation."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1493. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1385, S1486 |
| type_signature | PRESENT | S1385, S1486 |
| invariants | PRESENT | S1385, S1486 |
| dependencies | PRESENT | S1385, S1486, S1493 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1385 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1385] types=[RESTATEMENT, FORMALIZATION] scope=OBJECT — "Restates the source (Step 008) six-outcome acceptance function alpha_rho:EAxPxC->{Candidate,Supported,Accepted,Rejected,Contested,Unresolved}, explicitly non-linear (Supported+Contested is a legitimate joint state), and the source's own recommended multidimensional status Omega_A=(SupportStatus,AcceptanceStatus,CommitmentStatus,ContestStatus) with a worked institutionally-accepted-yet-epistemically-contested instance, plus the two boxed inequalities NotAccepted!=Rejected and Unresolved!=Rejected." (anchor: "these are not simply levels of 'confidence'; they represent different domain states. Its formal acceptance function has SIX outcomes — α_ρ : EA × P × C → {Candidate, Supported, Accepted, Rejected, Contested, Unresolved} ... Ω_A = ( SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus ) ... NotAccepted ≠ Rejected and Unresolved ≠ Rejected — insufficiency is not refutation.")
- [S1486] types=[FORMALIZATION, INVARIANT] scope=THEORY-LEVEL — "Step-008 (file 151920) states the opening law Supported != Accepted != Committed != True and a BRANCHED status chain (main chain Candidate->Supported->Accepted->Committed, with additional branches Supported->Contested, Supported->Rejected, Candidate->Unresolved), explicitly framed as different domain states rather than mere confidence levels. Defines the six-outcome acceptance function alpha_rho: EA x P x C -> {Candidate,Supported,Accepted,Rejected,Contested,Unresolved} with the caveat that outputs are not a single linear state (an assertion can be Supported-and-Contested simultaneously), leading to the multidimensional Omega_A=(SupportStatus,AcceptanceStatus,CommitmentStatus,ContestStatus), illustrated with a worked case of institutional acceptance coexisting with active epistemic contest -- 'epistemic status and governance status should not be collapsed.' States an AcceptancePolicy rho_A with six example condition types explicitly framed as policy examples, not universal laws; the pipeline Assessment->AcceptancePolicy->Acceptance->Governance->Commitment; the assertion decomposition A=(P,Sigma,Omega,Pi,tau,Ctx) called the single most reused structure in the batch; an acceptance-lattice refusal (the four statuses are not universally ordered, so AcceptanceStatus should be modeled as a state space, not a scalar hierarchy); the product space O=O_S x O_A x O_C x O_X with a PARTIAL transition function omega:Omega_A x Event x Policy -> Omega_A (mirroring step-007's partial delta); and ten invariants A1-A10 (Supported does not imply Accepted; Accepted does not imply Committed; Accepted does not imply Truth; Unresolved does not imply Rejected; NotAccepted does not imply False; Authority determines commitment not evidential truth; acceptance is policy-governed; commitment is purpose/context-governed; historical acceptance must remain auditable; acceptance does not imply IdealState satisfaction)." (anchor: "C8. step-008 (151920) -- Epistemic Acceptance and Commitment ... section2 the BRANCHED status chain ... section9 alpha_rho ... section10 Omega_A ... section27 the product space and omega ... section28 invariants A1-A10")
- [S1493] types=[VALIDATION] scope=OBJECT — "Verifies that step-008 contains a genuine, valid in-file supersession: the six-outcome acceptance function's codomain S_A is explicitly replaced by the multidimensional product state Omega_A within the same document, satisfying the programme's own four-condition resolution test (same question, new result, actually answers, not later reopened in-file). Confirms that projecting Omega_A down to any single linear status remains one of eight pending governance decisions (numbered #8 in the governance register) and that TV-F-019's Determination-versus-Accepted bridge question depends directly on this file's vocabulary." (anchor: "Step 008 ... Two acceptance codomains in one file with an explicit in-file supersession (S_A -> Omega_A) -- valid supersession under the section5 test ... Omega_A projection to any linear status is one of the 8 pending governance decisions (#8)")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
