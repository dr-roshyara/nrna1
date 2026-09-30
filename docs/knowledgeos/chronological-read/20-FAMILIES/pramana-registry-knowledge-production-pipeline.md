# pramana-registry-knowledge-production-pipeline

**Scope(s):** OBJECT · **Row count:** 9 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `pramana`, `pramatr`, `prameya`, `pramiti` · **Aliases:** `Epistemic Operating System pipeline`, `Knowledge Source Registry`
**Candidate group membership (NOT an identity claim):**
- **G0047** [`pramana-four-knowledge-sources` · `pramana-registry-knowledge-production-pipeline`] — explicit agent-stated uncertainty: 'pramana-registry-knowledge-production-pipeline' POSSIBLY relates to 'pramana-four-knowledge-sources' (batch B0006). Note: S0224's proposal to architect KnowledgeOS around a registry of knowledge-producing processes (with reliability metadata) rather than knowledge objects; closely related to but more specific/operational than the general pramana-four-knowledge-sources mapping, hence flagged as a possible overlap rather than merged.
- **G0858** [`nyaya-pramana-lens` · `pramana-registry-knowledge-production-pipeline`] — labels share the notation 'pramana'
- **G0859** [`nyaya-pramana-lens` · `pramana-registry-knowledge-production-pipeline`] — labels share the notation 'pramatr'
- **G0860** [`nyaya-pramana-lens` · `pramana-registry-knowledge-production-pipeline`] — labels share the notation 'prameya'
- **G1157** [`pramana-four-knowledge-sources` · `pramana-registry-knowledge-production-pipeline`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1158** [`knowledgeos-character-definition` · `pramana-registry-knowledge-production-pipeline`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0006`, scope `OBJECT`: S0224's proposal to architect KnowledgeOS around a registry of knowledge-producing processes (with reliability metadata) rather than knowledge objects; closely related to but more specific/operational than the general pramana-four-knowledge-sources mapping, hence flagged as a possible overlap rather than merged.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0224 §"KnowledgeOS should be built around Pramana (Knowledge Sources), not Knowledge Objects... Knowledge is not primarily a stored thing. Knowledge is the output of a reliable process."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0224 §"Knowledge Source Registry ... perception: reliability: high_for: [infrastructure_state, runtime_metrics] ... The book identifies four knowledge sources: perception, inference, analogy, testimony"]
- CANDIDATE-FORMAL-BIRTH: [S0224 §"pramana = process/source, prameya = object known, pramatr = knower, pramiti = knowledge/cognition. The four must be correctly related for truth to be grasped."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0224 §"The Nyaya-sutra gives KnowledgeOS something we were missing: A theory of how knowledge enters the system, how it earns trust, how it gets challenged, and how it becomes actionable. This is almost a blueprint for the Epistemic Kernel of KnowledgeOS."]

## Lifecycle
last_seen: S0224. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0224 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0224, S0224, S0224, S0224 |
| type_signature | PRESENT | S0224, S0224 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0224, S0224, S0224 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Draws a distinction: Argues Nyaya is process-reliabilist rather than internalist-justificationist, contrasting an LLM pattern ('Generate answer -> Hope it is correct') with a proposed KnowledgeOS pattern ('Generate claim -> Identify production process -> Check process reliability -> Accept/reject knowledge'). [S0224]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0224] types=['PRINCIPLE', 'DISTINCTION'] scope=THEORY-LEVEL — "Contrasts a modern Data->Information->Knowledge model with a Nyaya-inspired Object->Knowledge Source(Pramana)->Valid Cognition->Successful Action pipeline, arguing this book gives 'the runtime architecture of epistemic operations' as opposed to the prior book's 'logic of justification'." (anchor: "KnowledgeOS should be built around Pramana (Knowledge Sources), not Knowledge Objects... Knowledge is not primarily a stored thing. Knowledge is the output of a reliable process.")
- [S0224] types=['FORMALIZATION'] scope=OBJECT — "Maps the four-part pramana/prameya/pramatr/pramiti relation onto a KnowledgeEvent YAML schema (knower/object/source/cognition/validity)." (anchor: "pramana = process/source, prameya = object known, pramatr = knower, pramiti = knowledge/cognition. The four must be correctly related for truth to be grasped.")
- [S0224] types=['DISTINCTION', 'ARGUMENT'] scope=CROSS-OBJECT — "Argues Nyaya is process-reliabilist rather than internalist-justificationist, contrasting an LLM pattern ('Generate answer -> Hope it is correct') with a proposed KnowledgeOS pattern ('Generate claim -> Identify production process -> Check process reliability -> Accept/reject knowledge')." (anchor: "Nyaya does not ask: 'Does this belief have a justification?' It asks: 'Was this cognition generated by a reliable knowledge-producing process?'... contrasts Nyaya with internalist Western epistemology")
- [S0224] types=['CONCEPT', 'FORMALIZATION'] scope=OBJECT — "Proposes adding a 'Pramana Registry' / Knowledge Source Registry with a YAML schema recording per-source reliability conditions, alongside existing Evidence/Observations/Decisions/ADRs/Governance records." (anchor: "Knowledge Source Registry ... perception: reliability: high_for: [infrastructure_state, runtime_metrics] ... The book identifies four knowledge sources: perception, inference, analogy, testimony")
- [S0224] types=['FORMALIZATION', 'CONCEPT'] scope=OBJECT — "Proposes 'Epistemic Escalation', a knowledge-confidence state machine with six states, explicitly to avoid the cost of reviewing every fact." (anchor: "Knowledge Confidence State Machine: OBSERVED -> INFERRED -> ACCEPTED -> CHALLENGED -> UNDER REVIEW -> VALIDATED / REJECTED. Not every fact needs an expensive review.")
- [S0224] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Presents a combined Nyaya-inspired architecture replacing the 'current' Knowledge/Governance/AI Agents/Verification model with an Epistemic Governance Layer feeding a Knowledge Production Engine (Observation/Inference/Testimony), then a Validation Engine, then Knowledge Objects, then Action/Decision." (anchor: "KnowledgeOS becomes an Epistemic Operating System... Epistemic Governance Layer -> Knowledge Production Engine [Observation, Inference, Testimony] -> Validation Engine -> Knowledge Objects -> Action / Decision")
- [S0224] types=['HYPOTHESIS'] scope=OBJECT — "Drafts ADR-KOS-EPI-001 as a future KnowledgeOS ADR candidate requiring knowledge objects to preserve their production process." (anchor: "ADR-KOS-EPI-001 Knowledge is Process-Generated: Knowledge objects SHALL preserve the knowledge source and production process that generated them.")
- [S0224] types=['HYPOTHESIS'] scope=OBJECT — "Drafts ADR-KOS-EPI-002 requiring every claim to identify its epistemic source category." (anchor: "ADR-KOS-EPI-002 Evidence Source Registry: Every claim SHALL identify its epistemic source category.")
- [S0224] types=['RESTATEMENT', 'GOVERNANCE'] scope=THEORY-LEVEL — "Overall assessment closing the file: the Nyaya-sutra source supplies a theory of knowledge entry/trust/challenge/actionability, described as 'almost a blueprint for the Epistemic Kernel of KnowledgeOS'." (anchor: "The Nyaya-sutra gives KnowledgeOS something we were missing: A theory of how knowledge enters the system, how it earns trust, how it gets challenged, and how it becomes actionable. This is almost a blueprint for the Epistemic Kernel of KnowledgeOS.")

## Notes for P3
- Connected to 6 candidate groups in P2a — worth checking for redundant/overlapping objects in P3.
