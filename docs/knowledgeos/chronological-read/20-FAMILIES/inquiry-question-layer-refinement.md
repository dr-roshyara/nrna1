# inquiry-question-layer-refinement

**Scope(s):** OBJECT · **Row count:** 11 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `5W1H`, `OBJECT-LEVEL/META-LEVEL question` · **Aliases:** `Inquiry and Question layer refinement`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0011`, scope `OBJECT`: S0416's refinement proposing an Inquiry/Question layer above Claim/Hypothesis, expanding 5W1H into ten inquiry dimensions, modeling Claim/Hypothesis/Proposal as epistemic-standing roles of a single Assertion, extending the Tractatus lens's sense/truth distinction into a five-way Reference/Question/Claim/Evidence/Epistemic-Status non-collapse family, and proposing an 'Inquiry-Assertion-Evidence-Epistemic-State separation' investigation with four falsification tests.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0416 §"KnowledgeOS cannot be modelled only around "knowledge claims." There must be a layer that captures the inquiry / question structure"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0416 §"KnowledgeOS cannot be modelled only around "knowledge claims." There must be a layer that captures the inquiry / question structure"]
- CANDIDATE-OPERATIONAL-BIRTH: [S0416 §"Inquiry-Assertion-Evidence-Epistemic-State separation"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0416. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0416 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0416, S0416 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0416, S0416 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0416, S0416, S0416, S0416 |
| examples | PRESENT | S0416, S0416 |
| warnings | PRESENT | S0416, S0416 |
| experiments | PRESENT | S0416 |
| open_questions | PRESENT | S0416, S0416 |

## Rationale
The correct DDD question for Kernel-boundary membership is reframed as 'which of these things must change atomically to preserve a KnowledgeOS invariant?' rather than 'which things are important?'; an initial candidate table places Question/Question-type/Semantic-interpretation/Reasoning/Natural-language-expression/Search-retrieval as probably outside the Kernel, and Claim identity/Hypothesis status/Evidence reference/Justification path/Authority reference/Epistemic state as potentially or already inside. [S0416]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0416] types=['EXTENSION', 'FORMALIZATION'] scope=OBJECT — "An Inquiry/Question layer is proposed above Claim/Hypothesis: Inquiry->{Question, Request}, Question decomposed by 5W1H-style sub-facets (WHY/WHAT/WHO/WHEN/WHERE/HOW), feeding Candidate->{Claim, Hypothesis}->Evidence->Assessment->Epistemic State; key insight: 'a question is not a claim, and a claim is not an answer merely because it was produced in response to a question'; the lens discovers what the Kernel must preserve or distinguish, while DDD decides what actually belongs inside the authoritative boundary." (anchor: "KnowledgeOS cannot be modelled only around "knowledge claims." There must be a layer that captures the inquiry / question structure")
- [S0416] types=['DEFINITION', 'EXTENSION'] scope=OBJECT — "A Question is proposed to carry structure (subject, question type, target, context, temporal scope, spatial scope, authority scope, evidence requirements, expected answer form) rather than being a bare string, though exact fields are explicitly deferred as premature implementation; the 5W1H frame is expanded into ten dimensions of inquiry: Identity (who/what/which/whose), Temporal, Spatial, Causal, Procedural, Quantitative, Comparative, Evidential (how do we know/what evidence/what would falsify it), Epistemic (is it known/possible/uncertain/disputed/confidence), and Scope." (anchor: "a question should not be reduced to a string")
- [S0416] types=['PRINCIPLE', 'EXAMPLE'] scope=OBJECT — "Claim/Hypothesis/Proposal are modeled as roles/modalities/epistemic standings of a common ASSERTION concept rather than as separate unrelated aggregates; example: 'the failure was caused by the database' can be the same assertion moving through HYPOTHESIS->SUPPORTED->ESTABLISHED->REJECTED without the assertion's identity changing, consistent with the existing 'identity assigned, never derived' principle." (anchor: "The identity of the assertion does not have to change merely because its epistemic standing changes.")
- [S0416] types=['EXTENSION', 'INVARIANT'] scope=THEORY-LEVEL — "Extending the Tractatus lens's sense!=truth and name/reference!=proposition/assertion distinctions (S0409), a five-way non-collapse family is proposed: Reference != Question != Claim != Evidence != Epistemic Status, described as potentially one of the most important non-collapse families discovered." (anchor: "REFERENCE ≠ QUESTION ≠ CLAIM ≠ EVIDENCE ≠ EPISTEMIC STATUS")
- [S0416] types=['INVARIANT', 'WARNING'] scope=OBJECT — "The Zero lens applied to inquiry: for any answered question (e.g. WHAT happened), many companion questions (WHY, WHO had authority, WHEN was this true, WHERE does it apply, under what conditions, what evidence supports it, what would falsify it, who disputes it, what changed, what remains unknown) may have no answer at all -- 'answer completeness must never be confused with question completeness'; an AI can give a perfectly coherent answer to the wrong question." (anchor: "Answer completeness must never be confused with question completeness.")
- [S0416] types=['WARNING', 'EXAMPLE'] scope=OBJECT — "'No evidence found' for the question 'Who authorized X?' must not become the claim 'Nobody authorized X' -- it must instead register as Question 'Who authorized X?' with Epistemic result UNRESOLVED, exactly the kind of silent inference the Kernel should prevent." (anchor: "unanswered questions are not false claims")
- [S0416] types=['DISTINCTION'] scope=OBJECT — "'What happened?' (an object-level question producing a candidate claim) is distinguished from 'How do we know what happened?' (a meta-level question interrogating epistemic justification), giving Question->{OBJECT-LEVEL->Claim, META-LEVEL->Evidence->Justification}, connecting to prior work on justification paths and sufficiency." (anchor: "OBJECT-LEVEL question ... META-LEVEL question")
- [S0416] types=['PRINCIPLE', 'ANALYSIS'] scope=METHODOLOGICAL — "The correct DDD question for Kernel-boundary membership is reframed as 'which of these things must change atomically to preserve a KnowledgeOS invariant?' rather than 'which things are important?'; an initial candidate table places Question/Question-type/Semantic-interpretation/Reasoning/Natural-language-expression/Search-retrieval as probably outside the Kernel, and Claim identity/Hypothesis status/Evidence reference/Justification path/Authority reference/Epistemic state as potentially or already inside." (anchor: "Which of these things must change atomically to preserve a KnowledgeOS invariant?")
- [S0416] types=['OPEN-QUESTION'] scope=OBJECT — "Recognizes that the 'candidate' the Kernel admits may itself have multiple semantic forms (Question candidate, Claim candidate, Hypothesis candidate, Evidence candidate), and explicitly declines to put all of them into the Kernel automatically -- the open question is 'what is the smallest epistemically authoritative object that the Kernel admits?'" (anchor: "What is the smallest epistemically authoritative object that the Kernel admits?")
- [S0416] types=['FUTURE-RESEARCH', 'EXPERIMENT'] scope=METHODOLOGICAL — "Recommendation not to change the existing Kernel boundary yet but to add a new lens-derived investigation named 'Inquiry-Assertion-Evidence-Epistemic-State separation', attacked with four tests: Test 1 Non-collapse (can Question/Claim/Evidence/Epistemic-State be distinguished without unnecessary aggregates); Test 2 Identity (does Hypothesis->Supported->Established->Superseded preserve the underlying assertion's identity); Test 3 Zero (can 'question exists, answer absent' be represented without manufacturing a negative claim); Test 4 Anti-reasoner (can the Kernel preserve question/candidate-answer/evidence/justification/epistemic-disposition without itself understanding the semantic truth of the answer)." (anchor: "Inquiry-Assertion-Evidence-Epistemic-State separation")
- [S0416] types=['PRINCIPLE', 'RESTATEMENT'] scope=THEORY-LEVEL — "Proposed as a candidate Zero-lens non-collapse finding and whiteboard-level principle: 'A question creates an epistemic demand; a claim proposes an answer; evidence supports or challenges the claim; justification connects the evidence to the claim; epistemic state records the governed standing of the claim. None of these should be silently collapsed into another.' Explicitly does not assert all six belong inside the Kernel -- that is left as the open DDD investigation." (anchor: "A question creates an epistemic demand; a claim proposes an answer; evidence supports or challenges the claim; justification connects the evidence to the claim; epistemic state records the governed standing of the claim.")

## Notes for P3
(none beyond what is noted above)
