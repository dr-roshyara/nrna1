# shieber-basing-relation-object

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `BasingRelation`, `InputRule/TransitionRule`
**Aliases:** `Input Rules + Transition Rules`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0012, scope OBJECT: Shieber's justification model: INPUT RULES (what may enter reasoning) + TRANSITION RULES (what transformations may be performed) -> justified belief, contrasting foundationalism/coherentism/externalism; promoted into the Kernel as EpistemicProcess{InputPolicy, TransitionPolicy}. Paired with the basing relation: evidence existing is not the same as a claim being based on that evidence (Claim --is-based-on--> Evidence, not merely --mentions-->), with invariant EVIDENCE_PRESENT != EVIDENCE_USED_AS_BASIS and CLAIM_SUPPORT MUST BE TRACEABLE.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0463 §"Shieber presents justification as a process governed by two classes of rules: INPUT RULES ... TRANSITION RULES ... EpistemicProcess: InputPolicy, TransitionPolicy. ... INPUT admissibility + TRANSFORMATION admissibility = EPISTEMIC VALIDITY."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0463 §"Shieber presents justification as a process governed by two classes of rules: INPUT RULES ... TRANSITION RULES ... EpistemicProcess: InputPolicy, TransitionPolicy. ... INPUT admissibility + TRANSFORMATION admissibility = EPISTEMIC VALIDITY."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0463. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S0463), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0463 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0463 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0463 |
| dependencies | PRESENT | S0463 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0463 |
| examples | PRESENT | S0463 |
| warnings | PRESENT | S0463 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0463] (ARGUMENT, DISTINCTION): Externalism (nonmental inputs, environmental states, unrecognized-by-the-knower reliable processes) validates a KnowledgeOS that spans repositories/APIs/files/build-systems/tests/databases/CI/agents/observations/external sources/testimony far better than a purely internalist architecture; but only the principle ('knowledge may depend on externally grounded reliability conditions') belongs in the Kernel -- specific reliability algorithms/sensor validation/LLM evaluation/source scoring stay outside.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0463] types=['FORMALIZATION', 'DEFINITION'] scope=OBJECT — "Single most important architectural contribution (per the file): Input Rules + Transition Rules model of justification, contrasting foundationalism/coherentism/externalism, promoted directly into the Kernel as EpistemicProcess{InputPolicy, TransitionPolicy}, giving a formal place to ask 'was this input allowed to participate?' and 'was this transformation allowed to produce the resulting claim?'" (anchor: "Shieber presents justification as a process governed by two classes of rules: INPUT RULES ... TRANSITION RULES ... EpistemicProcess: InputPolicy, TransitionPolicy. ... INPUT admissibility + TRANSFORMATION admissibility = EPISTEMIC VALIDITY.")
- [S0463] types=['DISTINCTION', 'INVARIANT'] scope=OBJECT — "The basing relation (second-strongest Kernel finding): a Claim must be explicitly is-based-on Evidence, not merely --mentions--> it; Claim carries Evidence/BasingRelation/ReasoningProcess/Source/Assessment." (anchor: "having good evidence is not enough; the belief must actually be based on that evidence. ... Evidence exists ≠ Claim is supported by that evidence. ... EVIDENCE_PRESENT != EVIDENCE_USED_AS_BASIS. And: CLAIM_SUPPORT MUST BE TRACEABLE.")
- [S0463] types=['ARGUMENT', 'DISTINCTION'] scope=THEORY-LEVEL — "Externalism (nonmental inputs, environmental states, unrecognized-by-the-knower reliable processes) validates a KnowledgeOS that spans repositories/APIs/files/build-systems/tests/databases/CI/agents/observations/external sources/testimony far better than a purely internalist architecture; but only the principle ('knowledge may depend on externally grounded reliability conditions') belongs in the Kernel -- specific reliability algorithms/sensor validation/LLM evaluation/source scoring stay outside." (anchor: "Externalism permits nonmental inputs; bodily states; environmental states; processes whose reliability the knower does not understand; empirical investigation of whether processes are actually reliable. ... do NOT make externalism itself a Kernel implementation.")
- [S0463] types=['CONSTRAINT', 'EXAMPLE'] scope=CROSS-OBJECT — "Attacks the transparency/infallibility assumptions to establish that an agent cannot be its own unquestionable authority: an agent's claim to have verified something is not itself verification evidence -- separate AgentClaim and VerificationEvidence fields required." (anchor: "transparency ... infallibility ... An AI agent saying 'I verified this.' cannot itself constitute verification evidence. ... AgentClaim: 'I verified X' / VerificationEvidence: actual test / observation / artifact.")
- [S0463] types=['FORMALIZATION', 'DISTINCTION'] scope=OBJECT — "Rejects both presumptivism ('someone said it -> believe it') and inferentialism ('prove source reliability first') for testimony; a testimonial claim needs a rich TestimonialSource object rather than Claim.source='Alice'." (anchor: "Pure presumptivism ... Pure inferentialism ... The externalist approach says testimony can provide knowledge when it is actually reliably accurate, without requiring the recipient to consciously perform the reliability argument. ... TestimonialSource: SourceIdentity, Context, Method, ReliabilityEvidence, HistoricalAccuracy, TransmissionPath.")
- [S0463] types=['WARNING', 'EXAMPLE'] scope=CROSS-OBJECT — "Reframes AI hallucination through the basing relation: not merely a wrong answer but a basing failure/process-reliability failure/provenance failure -- illustrated by an agent claiming a repository observation that never occurred; new invariant CLAIM MUST NOT CLAIM A SOURCE THAT DID NOT ACTUALLY PRODUCE ITS BASIS, and DECLARED_REASONING != ACTUAL_EPISTEMIC_PROVENANCE, proposed as an AI-platform assurance rule." (anchor: "Agent says: 'The repository contains X.' Actual process: model generated X from prior pattern memory. No repository observation occurred. ... CLAIM BASIS != CLAIM CONTENT. ... CLAIM MUST NOT CLAIM A SOURCE THAT DID NOT ACTUALLY PRODUCE ITS BASIS.")

## Notes for P3
(none beyond what is noted above)
