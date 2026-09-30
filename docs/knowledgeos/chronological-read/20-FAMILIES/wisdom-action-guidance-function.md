# wisdom-action-guidance-function

**Scope(s):** THEORY-LEVEL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Decision -> {Act,Refrain,Defer,Escalate,Investigate}`, `Principle IV-A`, `Required/Permitted/Forbidden/Unknown`, `Wisdom: (Knowledge,Context,Evidence,Rules,Authority) -> ActionGuidance` · **Aliases:** `Wisdom of action and restraint`, `deontic action model`
**Candidate group membership (NOT an identity claim):**
- **G0303** [`epistemic-vs-practical-decision-separation` · `wisdom-action-guidance-function`] — explicit agent-stated uncertainty: 'wisdom-action-guidance-function' POSSIBLY relates to 'epistemic-vs-practical-decision-separation' (batch B0026). Note: Gita-Chapter-4-derived proposal that Wisdom is a cross-cutting domain function mapping Knowledge/Context/Evidence/Rules/Authority to an ActionGuidance value drawn from a deontic vocabulary (Required/Permitted/Forbidden/Unknown, Do/Refrain/Defer/Escalate/Investigate), explicitly not to be reified as a literal Wisdom class; parallels but is not identical to the prior epistemic-vs-practical-decision-separation object.
- **G1486** [`historical-continuity-vs-accessible-memory` · `wisdom-action-guidance-function`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0026, scope THEORY-LEVEL): Gita-Chapter-4-derived proposal that Wisdom is a cross-cutting domain function mapping Knowledge/Context/Evidence/Rules/Authority to an ActionGuidance value drawn from a deontic vocabulary (Required/Permitted/Forbidden/Unknown, Do/Refrain/Defer/Escalate/Investigate), explicitly not to be reified as a literal Wisdom class; parallels but is not identical to the prior epistemic-vs-practical-decision-separation object. _(relation_to_existing: POSSIBLY:epistemic-vs-practical-decision-separation)_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1067 §"We should therefore never model governance simply as: allowed = true/false. We need at least the semantic distinction: Required, Permitted, Forbidden, Unknown and potentially: Recommended, Discouraged."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1067 §"Wisdom is not merely storing knowledge. It is continuously asking: What should I do? but equally: What should I refrain from doing? This gives us a second dimension to KnowledgeOS."]
- CANDIDATE-FORMAL-BIRTH: [S1067 §"We should therefore never model governance simply as: allowed = true/false. We need at least the semantic distinction: Required, Permitted, Forbidden, Unknown and potentially: Recommended, Discouraged."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1067 §"I would actually say Chapter 4 strengthens our architecture rather than forcing us to redesign it. Especially these existing decisions: knowledge separated from agents; evidence separated from claims; determination separated from decision; decision separated from execution; provenance preserved; context snapshots; immutable historical determinations; supersession rather than overwriting; AI agents treated as actors; governance as a separate concern."]

## Lifecycle
last_seen: S1069. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1069) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1067 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1067, S1069 |
| type_signature | PRESENT | S1067 |
| invariants | PRESENT | S1067 |
| dependencies | PRESENT | S1067 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1067 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1067 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1067 |

## Rationale
By analogy to the already-established 'Evidence does not imply Truth' principle, Chapter 4 is read as supplying a parallel principle 'Knowledge does not imply Action', requiring an intermediate Judgment stage: Knowledge -> Judgment -> Action/Restraint. [S1067]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1067]` types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Proposes a deontic-modal governance vocabulary (Required, Permitted, Forbidden, Unknown, and potentially Recommended/Discouraged) as necessary to replace a naive boolean allowed=true/false governance model, since 'positive permission' Permitted(a), 'obligation' Required(a), and 'prohibition' Forbidden(a) are argued to be distinct semantic categories, closer to a policy/dharma model than an IF-condition-THEN-execute command system." (anchor: "We should therefore never model governance simply as: allowed = true/false. We need at least the semantic distinction: Required, Permitted, Forbidden, Unknown and potentially: Recommended, Discouraged.")
- `[S1067]` types=[CONCEPT, EXTENSION] scope=THEORY-LEVEL — "Argues Wisdom is a second architectural dimension distinct from Knowledge->Determination->Decision->Action: Chapter 4 suggests Knowledge->Wisdom->Action Guidance, where Wisdom evaluates both action and non-action (DO/ACT vs DO NOT ACT) before the Decision/Action stage." (anchor: "Wisdom is not merely storing knowledge. It is continuously asking: What should I do? but equally: What should I refrain from doing? This gives us a second dimension to KnowledgeOS.")
- `[S1067]` types=[PRINCIPLE, EXTENSION] scope=THEORY-LEVEL — "'Do nothing' (and deferral/escalation/seeking more evidence) must be first-class Decision outcomes rather than an implicit fallback: Decision -> {ACT, REFRAIN, DEFER, ESCALATE, SEEK MORE KNOWLEDGE}; when evidence is insufficient a KnowledgeOS-governed agent should conclude 'Do Not Determine' / 'SeekMoreEvidence' rather than force an answer -- termed 'epistemic restraint'." (anchor: "A workflow should not assume: Decision => Action. Instead: Decision -> {ACT, REFRAIN, DEFER, ESCALATE, SEEK MORE KNOWLEDGE}. ... Insufficient Evidence => Do Not Determine and perhaps => SeekMoreEvidence. That is epistemic restraint.")
- `[S1067]` types=[PRINCIPLE, ARGUMENT] scope=CROSS-OBJECT — "By analogy to the already-established 'Evidence does not imply Truth' principle, Chapter 4 is read as supplying a parallel principle 'Knowledge does not imply Action', requiring an intermediate Judgment stage: Knowledge -> Judgment -> Action/Restraint." (anchor: "We have already established that: Evidence not=> Truth. Now Chapter 4 gives us another principle: Knowledge not=> Action. There must be an intermediate judgment: Knowledge -> Judgment -> Action/Restraint.")
- `[S1067]` types=[PRINCIPLE] scope=THEORY-LEVEL — "Names and records 'Principle IV-A -- Wisdom of action and restraint': a governed system must be able to determine both what should be done and what should not be done, formalized as Decision -> {Act, Refrain, Defer, Escalate, Investigate}." (anchor: "Principle IV-A -- Wisdom of action and restraint: A governed system must be capable of determining both what should be done and what should not be done. Therefore: Decision -> {Act, Refrain, Defer, Escalate, Investigate}.")
- `[S1067]` types=[PRINCIPLE] scope=THEORY-LEVEL — "States a third principle, considered possibly the most important for AI: 'the actor must know the limits of its own knowledge', illustrated by a required repertoire of self-report statements (I know this / I do not know this / I previously knew this but cannot currently reconstruct it / the system has historical evidence for this / the evidence is insufficient for action / the action is prohibited / the action requires authority), termed 'epistemically mature architecture'." (anchor: "The actor must know the limits of its own knowledge. This is perhaps the most important principle for AI. An agent should be able to say: I know this. I do not know this. I previously knew this but cannot currently reconstruct it. ... That is epistemically mature architecture.")
- `[S1067]` types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Revises the KnowledgeOS pipeline diagram (previously Knowledge->Inquiry->Evidence->Determination->Decision->Action) into a richer chain: HISTORICAL LINEAGE -> KNOWLEDGE -> INQUIRY -> EVIDENCE -> DETERMINATION -> WISDOM -> {SHOULD DO, SHOULD NOT DO} -> DECISION -> {ACT, DEFER, REFRAIN} -> EXECUTION -> OBSERVATION -> NEW KNOWLEDGE/UPDATE, with PROVENANCE + LINEAGE positioned as an overarching background layer." (anchor: "HISTORICAL LINEAGE -> KNOWLEDGE -> INQUIRY -> EVIDENCE -> DETERMINATION -> WISDOM -> (SHOULD DO / SHOULD NOT DO) -> DECISION -> (ACT/DEFER/REFRAIN) -> EXECUTION -> OBSERVATION -> NEW KNOWLEDGE/UPDATE, and above all of it: PROVENANCE + LINEAGE")
- `[S1067]` types=[VALIDATION, GOVERNANCE] scope=CROSS-OBJECT — "Explicitly claims Chapter 4's material strengthens/validates (rather than forces redesign of) ten prior architecture decisions: knowledge separated from agents; evidence separated from claims; determination separated from decision; decision separated from execution; provenance preserved; context snapshots; immutable historical determinations; supersession rather than overwriting; AI agents treated as actors; governance as a separate concern." (anchor: "I would actually say Chapter 4 strengthens our architecture rather than forcing us to redesign it. Especially these existing decisions: knowledge separated from agents; evidence separated from claims; determination separated from decision; decision separated from execution; provenance preserved; context snapshots; immutable historical determinations; supersession rather than overwriting; AI agents treated as actors; governance as a separate concern.")
- `[S1067]` types=[FORMALIZATION, OPEN-QUESTION, WARNING] scope=THEORY-LEVEL — "Poses the open question of whether Wisdom should be modeled as a cross-cutting domain function Wisdom: (Knowledge, Context, Evidence, Rules, Authority) -> ActionGuidance with ActionGuidance in {DO, DON'T, WAIT, ASK, ESCALATE, INVESTIGATE}, while explicitly warning against prematurely reifying it as a literal `Wisdom` class; frames this as the key question to examine before 'Step 158', concluding Chapter 4 may reveal a missing architectural layer: a governed mechanism converting knowledge into action or restraint, not another knowledge repository." (anchor: "we should investigate whether Wisdom is a cross-cutting domain function: Wisdom: (Knowledge, Context, Evidence, Rules, Authority) -> (ActionGuidance) where ActionGuidance in {DO, DON'T, WAIT, ASK, ESCALATE, INVESTIGATE}. I don't necessarily mean creating a class called Wisdom. That would be premature.")
- `[S1069]` types=[FORMALIZATION, EXTENSION] scope=METHODOLOGICAL — "Explicitly names wisdom, action guidance, historical continuity, and epistemic restraint as candidate 'missing concepts' example categories to evaluate for Phase 3C -- directly referencing (and thereby corroborating the significance of) the Chapter-4-derived wisdom-action-guidance-function and historical-continuity-vs-accessible-memory material found elsewhere in this same batch (S1067), while cautioning these candidates must not be automatically accepted." (anchor: "3C. Missing concepts ... Look for concepts that the reasoning requires but which were never explicitly modeled. Examples discovered during later reasoning may include: wisdom, action guidance, historical continuity, epistemic restraint. Do not automatically accept these. Evaluate them against the complete corpus.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
