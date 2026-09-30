# assertion-responsibility-candidate

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Assertion(p) -> Responsibility(p) · **Aliases:** Williamson's knowledge account of assertion
**Candidate group membership (NOT an identity claim):**
- **G1807** [`assertion-responsibility-candidate` · `epistemic-iteration-non-closure`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1808** [`assertion-responsibility-candidate` · `epistemic-non-transparency-principle`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1809** [`assertion-responsibility-candidate` · `margin-for-error-candidate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0061`, scope `OBJECT`: Candidate rule that asserting p creates a candidate responsibility for p's truth, mapped onto the existing Claim->Evidence->Determination->Decision chain and the claimant/evidence/justification/authority/standing separation; explicitly not adopted as a KnowledgeOS primitive, and used to argue LLM-confidence != Knowledge and GeneratedAssertion != Determination (anti-reasoner/deterministic-kernel boundary).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2528] §"asserting something creates responsibility for the truth of its content. ... Assertion \Rightarrow Responsibility Candidate ... An LLM can generate Claim(p) without Knowledge(p) ... LLM confidence \neq Knowledge ... GeneratedAssertion \neq Determination"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2528] §"EPISTEMIC LIMITS, ACCESSIBILITY AND KNOWLEDGE STANDING ... EL.1 Factivity [DECIDED/REINFORCED] ... EL.7 Structural Unknowability [PROP — strong research candidate] ... EL.8 Assertion Responsibility [PROP]"

## Lifecycle
last_seen: S2530. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used (S2530), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2528, S2530 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2528 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2528 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2528] (EXTENSION/ARGUMENT) Maps Williamson's knowledge-account-of-assertion onto governance: Assertion(p) -> Responsibility-Candidate, reinforcing separation of claimant/evidence/justification/authority/standing (Status: [REINFORCED]); direct application to AI-generated claims: an LLM can produce Claim(p) or Confident(p) without Knowledge(p) or True(p), giving LLM-confidence != Knowledge and GeneratedAssertion != Determination, compatible with the anti-reasoner/deterministic-kernel-boundary stance.
- [S2530] (ARGUMENT/EXTENSION) States the Knowledge Rule of assertion (assert p only if you know p) and its explanatory power (lottery-assertion inappropriateness, Moore's paradox, the 'how do you know?' challenge), translated as: KnowledgeOS's Assert operation should be governed by a knowledge rule rather than a mere-truth or mere-belief rule -- a stronger, unhedged version of the later assertion-responsibility candidate in S2528.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2528] types=[EXTENSION, ARGUMENT] scope=CROSS-OBJECT — "Maps Williamson's knowledge-account-of-assertion onto governance: Assertion(p) -> Responsibility-Candidate, reinforcing separation of claimant/evidence/justification/authority/standing (Status: [REINFORCED]); direct application to AI-generated claims: an LLM can produce Claim(p) or Confident(p) without Knowledge(p) or True(p), giving LLM-confidence != Knowledge and GeneratedAssertion != Determination, compatible with the anti-reasoner/deterministic-kernel-boundary stance." (anchor: "asserting something creates responsibility for the truth of its content. ... Assertion \Rightarrow Responsibility Candidate ... An LLM can generate Claim(p) without Knowledge(p) ... LLM confidence \neq Knowledge ... GeneratedAssertion \neq Determination")
- [S2528] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL — "Proposes an 8-section theory addition 'EPISTEMIC LIMITS, ACCESSIBILITY AND KNOWLEDGE STANDING': EL.1 Factivity [DECIDED/REINFORCED], EL.2 Epistemic Non-Transparency [DERIVED], EL.3 Knowledge Iteration [DERIVED], EL.4 Evidence Relativity [PROP], EL.5 Margin of Error [PROP], EL.6 Accessibility (distinguishing Truth/Knowability/Accessibility/ActualKnowledge) [DERIVED], EL.7 Structural Unknowability [PROP-strong], EL.8 Assertion Responsibility [PROP], consolidating this file's individual candidates into one proposed section." (anchor: "EPISTEMIC LIMITS, ACCESSIBILITY AND KNOWLEDGE STANDING ... EL.1 Factivity [DECIDED/REINFORCED] ... EL.7 Structural Unknowability [PROP — strong research candidate] ... EL.8 Assertion Responsibility [PROP]")
- [S2530] types=[ARGUMENT, EXTENSION] scope=OBJECT — "States the Knowledge Rule of assertion (assert p only if you know p) and its explanatory power (lottery-assertion inappropriateness, Moore's paradox, the 'how do you know?' challenge), translated as: KnowledgeOS's Assert operation should be governed by a knowledge rule rather than a mere-truth or mere-belief rule -- a stronger, unhedged version of the later assertion-responsibility candidate in S2528." (anchor: "The fundamental rule of assertion is that one should assert p only if one knows p. ... explains why lottery assertions are inappropriate ... explains Moore's paradox ... explains the challenge "How do you know?" ... Assert in KnowledgeOS should be governed by a knowledge rule.")
- [S2530] types=[GOVERNANCE] scope=THEORY-LEVEL — "Final recommendation table adopts all seven listed concepts (Knowledge First, Evidence=Knowledge, Factive Mental States, Non-Luminosity, Margin for Error, Anti-KK, Knowledge Rule for Assertion) unconditionally (all checked, no [PROP]/[OPEN] hedging) and recommends integrating them directly into KnowledgeOS Theory v1.2 -- a materially stronger, less-hedged recommendation than the same-day review (S2528), which instead treats most of these as PROP-status research candidates and explicitly places 'Evidence=Knowledge' on its own rejection list." (anchor: "What to Adopt: Knowledge First ✅ ... Evidence = Knowledge ✅ ... Factive Mental States ✅ ... Non-Luminosity ✅ ... Margin for Error ✅ ... Anti-KK ✅ ... Knowledge Rule for Assertion ✅ ... What to Reject: Knowledge = Justified True Belief ❌ ... Phenomenal Evidence ❌ ... Transparency of Rationality ❌ ... Luminosity of Mental States ❌ ... Recommendation: Integrate ... into KnowledgeOS Theory v1.2")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
