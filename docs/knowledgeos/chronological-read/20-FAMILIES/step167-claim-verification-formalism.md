# step167-claim-verification-formalism

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** A(x)=<C,P,E,V,R>, Claim→Predicate→Evidence→Verification→Verdict, Declared!=Implemented!=Observed, V_i=(P_i,E_i,M_i,R_i) · **Aliases:** architecture-as-claims formalism, three epistemic levels (Declared/Implemented/Observed)
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 167's core formalism treating the architecture as a set of claims C={C1..Cn} (e.g. 'Evidence has identifiable provenance', 'Decisions require appropriate authority'), each requiring a verification obligation V_i=(Predicate,Evidence,Method,Result) via the chain Claim->Predicate->Evidence->Verification->Verdict. Introduces three epistemic levels -- Declared (D, what someone says should be true), Implemented (I, what the system/config/code actually enforces), Observed (O, what happened in reality) -- with DocumentedRule != ImplementedRule != ObservedCompliance, transforming architecture from a descriptive discipline into an assurance discipline (D->I->O). Concludes: 'an architectural claim without an explicit verification path is an assertion, not an assurance.' Final assurance tuple A(x) = <Claim,Predicate,Evidence,Verification,Result>.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1360 §"Claim → Predicate → Evidence → Verification → Verdict. This is the formal skeleton of our assurance model. ... DocumentedRule ≠ ImplementedRule ≠ ObservedCompliance. ... Declared ≠ Implemented ≠ Observed although ideally: D ≈ I ≈ O within the relevant scope. ... An architectural claim without an explicit verification path is an assertion, not an assurance."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1360 §"L_i = (Claim_i, Predicate_i, Evidence_i, Method_i, Verdict_i, Timestamp_i). ... Ledger = {L1,...,Ln}. The ledger is not merely a checklist. It is a record of assurance claims and their evidential status. ... UNASSESSED SUPPORTED VERIFIED FAILED INCONCLUSIVE NOT_VERIFIABLE. This is preferable to: done = true because 'done' has no epistemic meaning."]
- CANDIDATE-FORMAL-BIRTH: [S1360 §"Claim → Predicate → Evidence → Verification → Verdict. This is the formal skeleton of our assurance model. ... DocumentedRule ≠ ImplementedRule ≠ ObservedCompliance. ... Declared ≠ Implemented ≠ Observed although ideally: D ≈ I ≈ O within the relevant scope. ... An architectural claim without an explicit verification path is an assertion, not an assurance."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1360. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1360 |
| type_signature | PRESENT | S1360 |
| invariants | PRESENT | S1360 |
| dependencies | PRESENT | S1360 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1360 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1360 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1360] types=['FORMALIZATION', 'PRINCIPLE'] scope=THEORY-LEVEL — "Formalizes the architecture as a set of claims C={C1..Cn}, each requiring a verification obligation V_i=(Predicate_i,Evidence_i,Method_i,Result_i) via Claim->Predicate->Evidence->Verification->Verdict; distinguishes three epistemic levels Declared (D)/Implemented (I)/Observed (O), with DocumentedRule!=ImplementedRule!=ObservedCompliance, ideally D~=I~=O within scope; states the concluding principle that an architectural claim lacking an explicit verification path is merely an assertion, not an assurance." (anchor: "Claim → Predicate → Evidence → Verification → Verdict. This is the formal skeleton of our assurance model. ... DocumentedRule ≠ ImplementedRule ≠ ObservedCompliance. ... Declared ≠ Implemented ≠ Observed although ideally: D ≈ I ≈ O within the relevant scope. ... An architectural claim without an explicit verification path is an assertion, not an assurance.")
- [S1360] types=['WARNING', 'FORMALIZATION'] scope=THEORY-LEVEL — "Distinguishes AI Confidence (an internal scoring value) from EvidenceStrength/Truth (AIConfidence != EvidenceStrength; a reported Confidence=0.97 does not mean Truth=0.97). Proposes decomposing evidence quality as Q(E)=f(provenance,independence,recency,integrity,method) rather than one unexplained score. Gives a key statistical warning for AI-assisted assurance: if multiple AI outputs A1,A2,A3 all trace to the same underlying source E0, they are not independent evidence (N_observations=3 does not imply N_independent_evidences=3), and if Corr(E_i,E_j)~=1 treating them as independent substantially overstates evidential strength -- motivating an evidence graph G_E=(V,E) with relationship types derived-from/duplicates/corroborates/contradicts/supersedes so evidential reasoning becomes graph-aware." (anchor: "AIConfidence ≠ EvidenceStrength. ... Q(E) = f(provenance, independence, recency, integrity, method). ... If they all rely on the same source: E0, then they are not three independent pieces of evidence. ... N_observations = 3 does not imply: N_independent_evidences = 3. ... If Corr(E_i,E_j) ≈ 1, then treating them as independent substantially overstates evidential strength.")
- [S1360] types=['PRINCIPLE', 'EXTENSION'] scope=THEORY-LEVEL — "When E1 implies K and E2 implies not-K, the architecture must not silently pick one side but must represent Conflict(K) as an explicit domain state, treated as a first-class epistemic condition (Conflict != Error -- conflicting evidence can be exactly what an investigation is meant to surface), routed through ConflictDetected -> Investigate -> Evaluate -> Resolve/PreserveUncertainty toward K=Established, K=Rejected, or crucially K=Unresolved. Restates Not-proven != disproven (P=false means evidence supports negation; P=unknown means insufficient information exists) as a classic logical distinction the architecture must resist collapsing (Unknown must not be pushed to False or True)." (anchor: "Conflict(K) becomes an explicit domain state. ... We should treat: Conflict as a first-class epistemic condition. Not: Conflict = Error. Sometimes conflicting evidence is exactly what an investigation is supposed to discover. ... ConflictDetected → Investigate → Evaluate → Resolve/PreserveUncertainty. ... K = Unresolved. That third state is crucial. ... Not proven ≠ disproven.")
- [S1360] types=['FORMALIZATION', 'CONCEPT'] scope=THEORY-LEVEL — "Justifies a verification ledger as a structural consequence of the claim/verification formalism: each entry L_i=(Claim,Predicate,Evidence,Method,Verdict,Timestamp), Ledger={L_1..L_n}, is 'a record of assurance claims and their evidential status', not merely a checklist. Proposes a claim-status vocabulary UNASSESSED/SUPPORTED/VERIFIED/FAILED/INCONCLUSIVE/NOT_VERIFIABLE, explicitly preferable to a boolean 'done=true' because 'done' carries no epistemic meaning. Closes with the candidate formalism Assurance(x) = <Claim(x),Predicate(x),Evidence(x),Verification(x),Verdict(x)> as a structured tuple, not literal arithmetic addition." (anchor: "L_i = (Claim_i, Predicate_i, Evidence_i, Method_i, Verdict_i, Timestamp_i). ... Ledger = {L1,...,Ln}. The ledger is not merely a checklist. It is a record of assurance claims and their evidential status. ... UNASSESSED SUPPORTED VERIFIED FAILED INCONCLUSIVE NOT_VERIFIABLE. This is preferable to: done = true because 'done' has no epistemic meaning.")

## Notes for P3
None beyond what is recorded above.
