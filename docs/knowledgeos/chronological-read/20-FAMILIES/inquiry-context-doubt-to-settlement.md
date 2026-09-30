# inquiry-context-doubt-to-settlement

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Doubt -> Inquiry -> Settlement of Opinion -> Belief -> Action, Inquiry Context · **Aliases:** Peircean inquiry loop
**Candidate group membership (NOT an identity claim):**
- **G0291** [`inquiry-context-doubt-to-settlement` · `kos-inquiry-concept`] — explicit agent-stated uncertainty: 'kos-inquiry-concept' POSSIBLY relates to 'inquiry-context-doubt-to-settlement' (batch B0025). Note: This batch's formalization of Inquiry as a distinct epistemic-operation kernel object (vs. Question as mere content), reprising and re-deriving a distinction already explored in the B0011/B0012 Inquiry lenses.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Peirce-derived bounded context proposal: Inquiry owns Question/investigation/hypotheses/doubt/evidence requests/acquisition/competing explanations/progress/stopping conditions/unresolved questions/research path/history, but does NOT own Knowledge; its output (InquiryResult) can be Assessment/CandidateClaim/NotProven/InsufficientEvidence/Conflict, with the Knowledge Core deciding what becomes authoritative; belief is argued to be a distinct kind from knowledge (not merely higher-confidence belief), per Prichard.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0462 §"Peirce describes doubt as something that stimulates inquiry, while belief establishes a disposition that guides future action. ... Inquiry is not the same thing as Knowledge."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0462 §"Peirce describes doubt as something that stimulates inquiry, while belief establishes a disposition that guides future action. ... Inquiry is not the same thing as Knowledge."]
- CANDIDATE-FORMAL-BIRTH: [S0462 §"Peirce describes doubt as something that stimulates inquiry, while belief establishes a disposition that guides future action. ... Inquiry is not the same thing as Knowledge."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0462 §"KnowledgeOS must not collapse the epistemic lifecycle into a single 'knowledge' state. It must preserve the distinctions between doubt, inquiry, belief, assessment, knowledge, truth and decision ... I would not yet change the frozen KnowledgeOS Constitution. I would add these as candidate domain distinctions, then test them through the scenario-based DDD falsification round."]

## Lifecycle
last_seen: S0462. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0462 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0462 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0462 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0462 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- **[ANALYSIS]** [S0462]: Principal-Architect verdict identifying four architectural capabilities this book adds beyond the prior synthesis: Inquiry (doubt->inquiry->settlement), Belief (a distinct kind, not low-confidence knowledge), Decision (epistemic standing separate from practical action), and a Truth boundary (preserve truth-related relations without becoming a Truth Machine).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0462]** types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Peirce's doubt->inquiry->settlement-of-opinion->belief->action model motivates a dedicated Inquiry Context that does not own Knowledge, producing an InquiryResult (Assessment/CandidateClaim/NotProven/InsufficientEvidence/Conflict) which the Knowledge Core then judges for authoritative admission; the architecture is revised from Evidence->Reasoning->Knowledge to Question->Doubt->Inquiry->{Evidence,Hypotheses,Research}->Assessment->{Belief,NotProven,Knowledge}->Action." (anchor: "Peirce describes doubt as something that stimulates inquiry, while belief establishes a disposition that guides future action. ... Inquiry is not the same thing as Knowledge.")
- **[S0462]** types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Belief-has-agency-consequences: an EpistemicAssessment (standing + confidence) feeds a separate Belief Policy and Decision Policy en route to Action, keeping epistemology and operational policy cleanly separated; also warns 'KnowledgeOS should not become a decision engine merely because knowledge influences decisions.'" (anchor: "Peirce says belief guides desires and actions. ... belief is not simply another database status. It can have behavioral consequences. ... Knowledge and Belief should not be collapsed merely because both can be represented propositionally.")
- **[S0462]** types=[DISTINCTION, LIMITATION] scope=OBJECT — "Russell's acquaintance-vs-description and Ryle's knowing-how-vs-knowing-that distinctions warn that 'knowledge' cannot automatically reduce to proposition+evidence; recommends KnowledgeKind (PROPOSITIONAL/PROCEDURAL/ACQUAINTANCE/DESCRIPTIVE) as a domain distinction under investigation, not yet a separate aggregate -- 'preserve the distinction first, prove aggregate ownership later.'" (anchor: "Russell's distinction ... knowledge by acquaintance vs knowledge by description ... Ryle's 'knowing how' vs 'knowing that' ... Do not implement all of these as separate aggregates yet. Instead: KnowledgeKind should probably become a domain distinction under investigation.")
- **[S0462]** types=[DEFINITION, CORRECTION] scope=THEORY-LEVEL — "Sharpens the previous KnowledgeOS definition to explicitly include questions/beliefs/inquiry/action and the non-collapse of epistemic status into practical decision or representation." (anchor: "KnowledgeOS preserves the governed relationships among questions, beliefs, claims, evidence, inquiry, assessment, epistemic standing, authority, revision and action without collapsing epistemic status into practical decision or representation.")
- **[S0462]** types=[ANALYSIS] scope=THEORY-LEVEL — "Principal-Architect verdict identifying four architectural capabilities this book adds beyond the prior synthesis: Inquiry (doubt->inquiry->settlement), Belief (a distinct kind, not low-confidence knowledge), Decision (epistemic standing separate from practical action), and a Truth boundary (preserve truth-related relations without becoming a Truth Machine)." (anchor: "This book is not merely another philosophical reference. It adds four architectural capabilities that our previous synthesis did not make explicit enough: Inquiry / Belief / Decision / Truth boundary.")
- **[S0462]** types=[PRINCIPLE, GOVERNANCE] scope=THEORY-LEVEL — "Strengthens the prior architectural principle to require preserving distinctions among doubt/inquiry/belief/assessment/knowledge/truth/decision and their transitions; explicitly defers changing the frozen Constitution, instead queuing these as candidate domain distinctions for the scenario-based DDD falsification round, echoing the anthology's own stated purpose of holding conflicting views together rather than declaring a winner." (anchor: "KnowledgeOS must not collapse the epistemic lifecycle into a single 'knowledge' state. It must preserve the distinctions between doubt, inquiry, belief, assessment, knowledge, truth and decision ... I would not yet change the frozen KnowledgeOS Constitution. I would add these as candidate domain distinctions, then test them through the scenario-based DDD falsification round.")

## Notes for P3
- No unusual tension, evidentiary gap, or priority signal noticed beyond what is already recorded in the sections above.
