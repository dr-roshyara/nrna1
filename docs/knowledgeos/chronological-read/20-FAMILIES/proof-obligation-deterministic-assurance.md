# proof-obligation-deterministic-assurance

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Gamma |- tau: S_i -> S_j`
**Aliases:** `ValidTransition as proof obligation`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0034, scope THEORY-LEVEL: Step 197's type-theoretic reframing of transition validity as a proof obligation given an assumption context, making deterministic assurance mathematically explicit (the system proves ValidTransition, not Truth) and separating SemanticInference (AI's role) from DeterministicValidation (software's role).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1415 §"\Gamma\vdash \tau:S_i\rightarrow S_j. ... \Gamma=\{AuthorityValid,EvidenceComplete,RuleSatisfied\}. Then: \Gamma\vdash ApproveArchitecture."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1415 §"\Gamma\vdash \tau:S_i\rightarrow S_j. ... \Gamma=\{AuthorityValid,EvidenceComplete,RuleSatisfied\}. Then: \Gamma\vdash ApproveArchitecture."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1415. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1415), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1415 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1415 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1415 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1415] (DISTINCTION, ARGUMENT): Separates SemanticInference (AI's strength) from DeterministicValidation (software/rules' strength) as two operations with fundamentally different epistemic characteristics, neither making AI 'bad' at its role.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1415] types=['FORMALIZATION', 'EXTENSION'] scope=THEORY-LEVEL — "Interprets an accepted transition as carrying a proof obligation in a type-theoretic style: Gamma |- tau:S_i->S_j, meaning transition tau is valid given assumption context Gamma (e.g. AuthorityValid, EvidenceComplete, RuleSatisfied); the system proves that a governed transition satisfies its declared conditions, not philosophical truth." (anchor: "\Gamma\vdash \tau:S_i\rightarrow S_j. ... \Gamma=\{AuthorityValid,EvidenceComplete,RuleSatisfied\}. Then: \Gamma\vdash ApproveArchitecture.")
- [S1415] types=['DISTINCTION', 'PRINCIPLE'] scope=THEORY-LEVEL — "States deterministic assurance mathematically cleanly: the system need not determine Truth(p)=1, only ValidTransition(tau)=1 -- different questions entirely; an AI-proposed transition tau* is only accepted after the deterministic assurance layer independently evaluates Valid(tau*)." (anchor: "It can determine: ValidTransition(\tau)=1. These are different questions. ... AIProposal \rightarrow Verification \rightarrow GovernedTransition.")
- [S1415] types=['DISTINCTION', 'ARGUMENT'] scope=THEORY-LEVEL — "Separates SemanticInference (AI's strength) from DeterministicValidation (software/rules' strength) as two operations with fundamentally different epistemic characteristics, neither making AI 'bad' at its role." (anchor: "SemanticInference from: DeterministicValidation. AI is particularly useful for the first. Software/rules are particularly strong for the second.")

## Notes for P3
(none beyond what is noted above)
