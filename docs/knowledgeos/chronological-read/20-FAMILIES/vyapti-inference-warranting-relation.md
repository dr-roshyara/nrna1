# vyapti-inference-warranting-relation

**Scope(s):** OBJECT · **Row count:** 11 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `H-KOS-Vyapti-001`, `INV-KOS-Vyapti-001`, `Vyapti` · **Aliases:** `inference-warranting relation`, `invariable concomitance`
**Candidate group membership (NOT an identity claim):**
- **G0741**: [`h-kos-vyapti-001-inference-warranting-relation` · `vyapti-inference-warranting-relation`] — labels share the notation 'H-KOS-Vyapti-001'
- **G0957**: [`h-kos-vyapti-001-inference-warranting-relation` · `vyapti-inference-warranting-relation`] — working_label token overlap Jaccard=0.80 (shared tokens: ['inference', 'relation', 'vyapti', 'warranting'])
- **G1154**: [`hetvabhasa-fallacy-taxonomy` · `vyapti-inference-warranting-relation`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1156**: [`knowledgeos-character-definition` · `vyapti-inference-warranting-relation`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0006, scope OBJECT: "The proposed missing object between Evidence and Conclusion: the explicit logical relation that warrants an inference; central object of S0223 (title document) and referenced in S0219/S0220/S0222/S0225."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0219 §"1. Pratijna (Proposition) ... 2. Hetu (Reason) ... 3. Udaharana (Example) ... 4. Upanaya (Application) ... 5. Nigamana (Conclusion) ... Pakṣadharmatā ... Vyapti — invariable concomitance"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0223 §"evidence/reason functions as a logical sign (linga) and the inferred entity is the signified (lingin) ... CPU usage 95% -> Role: Possible sign -> Warrant: High CPU sustained > X minutes correlates with degradation -> Inference: Potential performance failure"]
- CANDIDATE-FORMAL-BIRTH: [S0219 §"1. Pratijna (Proposition) ... 2. Hetu (Reason) ... 3. Udaharana (Example) ... 4. Upanaya (Application) ... 5. Nigamana (Conclusion) ... Pakṣadharmatā ... Vyapti — invariable concomitance"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0223 §"Truth is not produced by storing more information. Truth emerges when evidence, reasoning relations, objections, and validation rules are preserved together... This book gives KnowledgeOS the missing 'logic of justification' layer between knowledge storage and truth discovery."]

## Lifecycle
last_seen: S0225. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S0223 |
| formal_definition | PRESENT | S0219, S0223, S0225 |
| type_signature | PRESENT | S0223 |
| invariants | PRESENT | S0219, S0220, S0223 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0223, S0225 |
| examples | PRESENT | S0223 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0219] types=[FORMALIZATION] scope=OBJECT — "Describes the five-membered syllogism (Nyaya-Pararthanumana) and its two validity requirements: pakṣadharmatā (the reason must be present in the subject) and vyapti (invariable concomitance between reason and conclusion, e.g. smoke always accompanied by fire)." (anchor: "1. Pratijna (Proposition) ... 2. Hetu (Reason) ... 3. Udaharana (Example) ... 4. Upanaya (Application) ... 5. Nigamana (Conclusion) ... Pakṣadharmatā ... Vyapti — invariable concomitance")
- [S0219] types=[HYPOTHESIS] scope=THEORY-LEVEL — "Candidate invariant TARKA-002: inferences must establish vyapti between reason and conclusion, confirmed through counterfactual testing; classified 'candidate', pending validation." (anchor: "Candidate TARKA-002: Vyapti Confirmation — Inferences must establish invariable concomitance (vyapti) between reason and conclusion, confirmed through counterfactual testing.")
- [S0219] types=[CONSTRAINT] scope=THEORY-LEVEL — "Tabulates six forbidden transitions derived from Tarka Shastra, each paired with the Nyaya parallel that forbids it (apramana, hetvabhasa, tarka-is-apramana, unclassified samsaya, dustarka, the five circular-tarka fallacies)." (anchor: "Forbidden Transitions: Knowledge claim without pramana grounding; Inference without vyapti; Tarka treated as independent knowledge source; Unstructured doubt; Reasoning contrary to authority without g…")
- [S0220] types=[HYPOTHESIS, INVARIANT] scope=OBJECT — "Proposes H-KOS-Vyapti-001, described as 'perhaps the most architecturally valuable concept': without the explicit rule connecting evidence to conclusion, 'Evidence -> Conclusion' remains opaque." (anchor: "H-KOS-Vyapti-001: KnowledgeOS SHALL preserve the logical relationship that connects evidence to conclusion for every derived knowledge object.")
- [S0223] types=[DEFINITION] scope=OBJECT — "States the reviewed book's central theme (vyapti, the inference-warranting relation) as directly giving KnowledgeOS 'several architectural principles', framing the book as probably one of the strongest philosophical sources for defining KnowledgeOS's reasoning architecture." (anchor: "The book's central theme is the Indian concept of vyapti — the inference-warranting relation between evidence/reason and conclusion... how a relation between a reason and an inferred conclusion become…")
- [S0223] types=[HYPOTHESIS, INVARIANT, EXAMPLE] scope=OBJECT — "Argues the current Evidence->Reasoning->Conclusion model is incomplete and must add an explicit Inference-Warranting Relation (Vyapti) node between Evidence and Conclusion (smoke/fire example), with the added consequence that a conclusion lacking this explicit relation remains only a hypothesis." (anchor: "H-KOS-Vyapti-001: KnowledgeOS SHALL preserve the warranting relation that connects evidence to conclusion. A conclusion without an explicit inference relation SHALL remain a hypothesis.")
- [S0223] types=[CONCEPT, DISTINCTION, EXAMPLE] scope=OBJECT — "Introduces the sign (linga) / signified (lingin) distinction and applies it to an AI-monitoring worked example, arguing the sign-to-inference relationship itself must become a first-class, explicitly warranted object." (anchor: "evidence/reason functions as a logical sign (linga) and the inferred entity is the signified (lingin) ... CPU usage 95% -> Role: Possible sign -> Warrant: High CPU sustained > X minutes correlates wit…")
- [S0223] types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Proposes a new kernel capability, a 'Reasoning Verification Engine', with a specified input schema (Claim/Evidence/Inference/Context) and a five-state output classification (VALIDATED/QUESTIONABLE/CONFLICTED/INVALID/UNKNOWN), motivated by the observation that LLMs generate arguments but KnowledgeOS must judge them." (anchor: "sound reasoning vs. sophistical reasoning... Reason Quality Analysis: Valid / Weak / Contradictory / Unsupported ... Reasoning Verification Engine. Input: Claim, Evidence, Inference, Context. Output: …")
- [S0223] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Applies Dignaga's three-condition test for sign validity (trairupya) to a worked incident-investigation example (memory-leak-caused outage) and proposes an 'Inference Validation Matrix' architecture component scoring similar-vs-different cases." (anchor: "A valid sign must satisfy: 1. Present in the subject under consideration 2. Present in similar cases 3. Absent in dissimilar cases ... Service outage caused by memory leak ... Then confidence increase…")
- [S0223] types=[RESTATEMENT, GOVERNANCE] scope=THEORY-LEVEL — "Closing table maps eight Matilal-derived concepts (Vyapti, Linga/Lingin, Vada, Jalpa/Vitanda, Tarka, Hetvabhasa, triple sign-condition, debate tradition) to eight KnowledgeOS architectural impacts, and states the final insight that this book moves KnowledgeOS from a knowledge graph to an epistemic reasoning system." (anchor: "Truth is not produced by storing more information. Truth emerges when evidence, reasoning relations, objections, and validation rules are preserved together... This book gives KnowledgeOS the missing …")
- [S0225] types=[RESTATEMENT, FORMALIZATION] scope=THEORY-LEVEL — "Combines the two reviewed books (S0223's Nyaya 'Epistemic Kernel'/truth engine and this file's Matilal 'Semantic Kernel'/meaning engine) into a single pipeline from Reality through Semantic Kernel and Epistemic Kernel to Knowledge Object and Decision/Action, framed as a foundation for a true 'Knowledge Operating System'." (anchor: "Reality -> Observation/Language Input -> Semantic Kernel (Matilal, 'What does it mean?') -> Epistemic Kernel (Nyaya, 'Why should we trust it?') -> Knowledge Object -> Decision/Action ... The first boo…")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
