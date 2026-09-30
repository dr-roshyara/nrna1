# hetvabhasa-fallacy-taxonomy

**Scope(s):** OBJECT · **Row count:** 11 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Asiddha, Badhita, Hetvabhasa, Satpratipaksa, Savyabhicara, Viruddha · **Aliases:** Nyaya fallacy taxonomy, reasoning failure modes
**Candidate group membership (NOT an identity claim):**
- **G0960** [`hetvabhasa-fallacy-invariants` · `hetvabhasa-fallacy-taxonomy`] — working_label token overlap Jaccard=0.50 (shared tokens: ['fallacy', 'hetvabhasa'])
- **G1151** [`hetvabhasa-fallacy-taxonomy` · `reasoning-argument-structure-five-member-syllogism`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1152** [`hetvabhasa-fallacy-taxonomy` · `samsaya-structured-doubt-state`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- **G1154** [`hetvabhasa-fallacy-taxonomy` · `vyapti-inference-warranting-relation`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope OBJECT): The five-part Nyaya taxonomy of pseudo-reasoning/fallacious inference, repeatedly proposed as a model for AI reasoning-failure detection that must be preserved rather than discarded; recurs in S0219, S0220, S0221, S0222, S0223.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0219 §"Five Types of Tarka (Fallacious Structures to Avoid): Pramanabadhita, Atmasraya, Anyonyasraya, Cakrakasraya, Anavastha"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0219 §"Five Types of Tarka (Fallacious Structures to Avoid): Pramanabadhita, Atmasraya, Anyonyasraya, Cakrakasraya, Anavastha"]
- CANDIDATE-FORMAL-BIRTH: [S0219 §"Six-Phase Methodology: Samshaya, Pramana, Pancha Avayava, Tarka, Hetvabhasa, Nirnaya"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0223. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0221, S0222 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0219, S0221, S0223 |
| type_signature | PRESENT | S0223 |
| invariants | PRESENT | S0219 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0219 |
| examples | PRESENT | S0220, S0221, S0222, S0223 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- **[EXAMPLE/EXPLANATION]** [S0221]: Full worked examples for all five hetvabhasa fallacies (Savyabhicara, Viruddha, Satpratipaksa, Asiddha, Badhita), each with a concrete illustrative scenario.
- **[ANALYSIS/EXAMPLE]** [S0222]: Maps all five hetvabhasa fallacies onto named AI-reasoning-failure equivalents, framed as 'Nyaya gives us AI failure categories'.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0219]** types=[DISTINCTION, CONCEPT] scope=OBJECT — "Lists tarka's function as reductio ad absurdum with a worked example (self as eternal vs produced, via karmic-inheritance absurdity), and enumerates five fallacious-tarka structures to avoid: contradicted-by-valid-knowledge, self-dependency, mutual dependency, circular dependency, infinite regress." (anchor: "Five Types of Tarka (Fallacious Structures to Avoid): Pramanabadhita, Atmasraya, Anyonyasraya, Cakrakasraya, Anavastha")
- **[S0219]** types=[FORMALIZATION] scope=OBJECT — "Maps the six-phase AI-implementation methodology to KnowledgeOS parallels: doubt analysis -> evidence sourcing -> structured reasoning -> contradiction detection -> fallacy detection -> decision/knowledge state." (anchor: "Six-Phase Methodology: Samshaya, Pramana, Pancha Avayava, Tarka, Hetvabhasa, Nirnaya")
- **[S0219]** types=[CONSTRAINT] scope=THEORY-LEVEL — "Tabulates six forbidden transitions derived from Tarka Shastra, each paired with the Nyaya parallel that forbids it (apramana, hetvabhasa, tarka-is-apramana, unclassified samsaya, dustarka, the five circular-tarka fallacies)." (anchor: "Forbidden Transitions: Knowledge claim without pramana grounding; Inference without vyapti; Tarka treated as independent knowledge source; Unstructured doubt; Reasoning contrary to authority without governed transition; Circular reasoning")
- **[S0220]** types=[HYPOTHESIS, EXAMPLE] scope=OBJECT — "Gives worked circular-reasoning examples and proposes H-KOS-Fallacy-001 requiring KnowledgeOS to detect and preserve (not silently accept) invalid/circular reasoning patterns, framed as 'reasoning loop = epistemic corruption'." (anchor: "Claim A is true because Claim A says so ... A proves B, B proves C, C proves A ... H-KOS-Fallacy-001: KnowledgeOS SHALL detect and preserve invalid reasoning patterns rather than silently accepting conclusions produced by circular or unsupported reasoning.")
- **[S0221]** types=[FORMALIZATION, HYPOTHESIS] scope=OBJECT — "Maps the 16 padarthas onto KnowledgeOS concepts and argues knowledge evolution should be represented as Observation->Doubt->Investigation->Reasoning->Challenge->Determination->Knowledge State rather than a simple Created->Approved->Published pipeline; proposes H-KOS-Lifecycle-Reasoning-001." (anchor: "Pramana->Knowledge source, Prameya->Knowledge object, Samsaya->Unknown/uncertainty, Prayojana->Purpose/intent, Drstanta->Example/reference, Siddhanta->Established knowledge, Avayava->Reasoning structure, Tarka->Validation, Nirnaya->Determination, Vada->Truth-seeking review, Hetvabhasa->Reasoning failure, Nigrahasthana->Invalid argument state ... H-KOS-Lifecycle-Reasoning-001")
- **[S0221]** types=[HYPOTHESIS, CONCEPT] scope=OBJECT — "Restates the five hetvabhasa fallacies mapped to modern equivalents and proposes H-KOS-Failure-001 requiring KnowledgeOS to store not only conclusions but 'why reasoning failed'." (anchor: "Savyabhicara/Viruddha/Satpratipaksa/Asiddha/Badhita ... H-KOS-Failure-001: KnowledgeOS SHALL preserve reasoning failure modes as explicit epistemic states, not discard failed reasoning attempts.")
- **[S0221]** types=[EXAMPLE, EXPLANATION] scope=OBJECT — "Full worked examples for all five hetvabhasa fallacies (Savyabhicara, Viruddha, Satpratipaksa, Asiddha, Badhita), each with a concrete illustrative scenario." (anchor: "Savyabhicara — 'The hill is wet because it rained' — but wetness could also come from a river ... Viruddha — 'The hill is cold because it is on fire' ... Badhita — Claiming fire doesn't exist on a hill where fire is directly perceived")
- **[S0222]** types=[HYPOTHESIS] scope=OBJECT — "Restates the 16-padartha mapping and proposes INV-KOS-ReasoningLifecycle-001 requiring preservation of the complete epistemic path from uncertainty to justified conclusion." (anchor: "KnowledgeOS needs a reasoning lifecycle, not only a knowledge lifecycle... INV-KOS-ReasoningLifecycle-001: KnowledgeOS SHALL preserve the complete epistemic path from uncertainty to justified conclusion, including doubts, evidence, reasoning, alternatives, and final determination.")
- **[S0222]** types=[ANALYSIS, EXAMPLE] scope=OBJECT — "Maps all five hetvabhasa fallacies onto named AI-reasoning-failure equivalents, framed as 'Nyaya gives us AI failure categories'." (anchor: "Hetvabhasa -> AI reasoning failures: Savyabhicara -> Overgeneralization, Viruddha -> Contradictory conclusion, Asiddha -> Unsupported claim, Satpratipaksa -> Ignoring equal evidence, Badhita -> Contradicted by reality")
- **[S0223]** types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Proposes a new kernel capability, a 'Reasoning Verification Engine', with a specified input schema (Claim/Evidence/Inference/Context) and a five-state output classification (VALIDATED/QUESTIONABLE/CONFLICTED/INVALID/UNKNOWN), motivated by the observation that LLMs generate arguments but KnowledgeOS must judge them." (anchor: "sound reasoning vs. sophistical reasoning... Reason Quality Analysis: Valid / Weak / Contradictory / Unsupported ... Reasoning Verification Engine. Input: Claim, Evidence, Inference, Context. Output: VALIDATED, QUESTIONABLE, CONFLICTED, INVALID, UNKNOWN")
- **[S0223]** types=[HYPOTHESIS, EXAMPLE] scope=OBJECT — "Names hetvabhasa (pseudo-evidence) as the classification for AI-hallucinated citations (evidence-looking objects that are NOT valid evidence) and proposes H-KOS-EvidenceAuthenticity-001: KnowledgeOS SHALL distinguish evidence from pseudo-evidence by evaluating whether the evidence possesses the logical force required for the conclusion." (anchor: "pseudo-evidence as something that appears like evidence but lacks the logical force needed to establish the thesis... Claim: Library X supports feature Y. Evidence: Generated citation. Problem: No real source exists ... H-KOS-EvidenceAuthenticity-001")

## Notes for P3
- Own observation: this label carries 4 candidate-group memberships (G0960, G1151, G1152, G1154) — a relatively dense mechanical linkage that may deserve priority attention in P3 reconciliation.
