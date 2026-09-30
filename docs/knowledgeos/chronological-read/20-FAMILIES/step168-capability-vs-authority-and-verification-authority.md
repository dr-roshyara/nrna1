# step168-capability-vs-authority-and-verification-authority

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Capability(a,x) does not imply Authority(a,x), DeterministicVerifier != AI_Assessment, VerificationAuthority != DecisionAuthority · **Aliases:** capability vs authority invariant, verification does not confer decision authority
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0033 · scope THEORY-LEVEL: Step 168 introduces the security/governance invariant Capability(a,x) does not imply Authority(a,x) (actor a being technically able to perform x does not mean a is legitimately permitted to), worked with Claude: capability to modify architecture documentation does not imply authorization to approve architecture (Capability_AI != GovernanceAuthority_AI). Separately, VerificationAuthority != DecisionAuthority: a verifier establishing P=true (e.g. 'migration technically satisfies required checks') does not itself grant governance approval authority ('migration is approved'). Distinguishes DeterministicVerifier from AI_Assessment: a binary AI output does not imply a deterministic verification process, since the underlying process may not be reproducible; an AI assessment can discover/propose/prioritize/explain/generate test cases but cannot substitute for deterministic verification V_d for a claim requiring it (assurance composition rule).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1361 §"Capability(a,x) ⇏ Authority(a,x). This is a very important security/governance invariant. ... Claude may have the capability to: modify architecture documentation. That does not imply: Claude is authorized to approve the architecture. ... VerificationAuthority ≠ DecisionAuthority."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1361 §"L1: AIOutput ⇏ KnowledgeEstablished L2: Capability ⇏ Authority L3: Execution ⇏ Verification L4: Unknown ≠ False L5: CurrentState ≠ CompleteHistory L6: Aggregate ≠ Process L7: Evidence ≠ Claim L8: Verification ≠ GovernanceDecision ... Step 169 should therefore be a falsification exercise, not another confirmation exercise. We should actively try to break the architecture. ... do not ask only whether our model explains the evidence—ask what evidence would prove the model wrong."]

## Lifecycle
last_seen: S1361. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1361 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1361 |
| examples | PRESENT | S1361 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1361 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1361] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "Introduces the invariant Capability(a,x) does not imply Authority(a,x): an actor being technically able to perform x does not mean it is legitimately permitted to. Worked AI example: Claude's capability to modify architecture documentation does not imply authorization to approve architecture (Capability_AI != GovernanceAuthority_AI). Separately, a verifier establishing a claim true does not itself grant governance decision authority (VerificationAuthority != DecisionAuthority)." (anchor: "Capability(a,x) ⇏ Authority(a,x). This is a very important security/governance invariant. ... Claude may have the capability to: modify architecture documentation. That does not imply: Claude is authorized to approve the architecture. ... VerificationAuthority ≠ DecisionAuthority.")
- [S1361] types=[DISTINCTION, PRINCIPLE] scope=THEORY-LEVEL — "Distinguishes DeterministicVerifier from AI_Assessment (a binary AI output does not imply a deterministic/reproducible verification process); sharpens the earlier 'AI as participant' principle into: 'AI may generate epistemic candidates; the architecture determines which mechanisms can promote them to authoritative state' -- described as much stronger than merely 'AI should be supervised.'" (anchor: "DeterministicVerifier from: AI_Assessment. An AI assessment may support a verification process, but should not be conflated with deterministic verification. ... AI may generate epistemic candidates; the architecture determines which mechanisms can promote them to authoritative state.")
- [S1361] types=[GOVERNANCE, FUTURE-RESEARCH] scope=THEORY-LEVEL — "Consolidates the step series so far into eight candidate 'KnowledgeOS Architectural Laws': L1 AIOutput does not imply KnowledgeEstablished; L2 Capability does not imply Authority; L3 Execution does not imply Verification; L4 Unknown!=False; L5 CurrentState!=CompleteHistory; L6 Aggregate!=Process; L7 Evidence!=Claim; L8 Verification!=GovernanceDecision. Proposes Step 169 explicitly as a falsification exercise (not confirmation) testing each law against DDD, mathematical consistency, statistical reasoning, Chapters 1-4 insights, existing KnowledgeOS architecture, implementation evidence, and counterexamples -- 'do not ask only whether our model explains the evidence -- ask what evidence would prove the model wrong.'" (anchor: "L1: AIOutput ⇏ KnowledgeEstablished L2: Capability ⇏ Authority L3: Execution ⇏ Verification L4: Unknown ≠ False L5: CurrentState ≠ CompleteHistory L6: Aggregate ≠ Process L7: Evidence ≠ Claim L8: Verification ≠ GovernanceDecision ... Step 169 should therefore be a falsification exercise, not another confirmation exercise. We should actively try to break the architecture. ... do not ask only whether our model explains the evidence—ask what evidence would prove the model wrong.")

## Notes for P3
(none beyond what is captured above)
