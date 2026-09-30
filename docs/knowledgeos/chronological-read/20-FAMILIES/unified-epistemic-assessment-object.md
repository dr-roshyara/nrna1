# unified-epistemic-assessment-object

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EpistemicAssessment=(Proposition,Status,Evidence,Model,Probability,Uncertainty,Assumptions,Scope,Time,Author,Authority,Lineage)`, `I_68` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 199's consolidated twelve-field epistemic-assessment schema and its capstone invariant I_68 (no silent uncertain->certain conversion), plus the formal epistemic-layer definition E=(H,E,M,S,U,A) and governance function G:ExAuthority->Decision.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1417] §"EpistemicAssessment = (Proposition,Status,Evidence,Model,Probability,Uncertainty,Assumptions,Scope,Time,Author,Authority,Lineage) Not every field is mandatory in every context."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1417] §"EpistemicAssessment = (Proposition,Status,Evidence,Model,Probability,Uncertainty,Assumptions,Scope,Time,Author,Authority,Lineage) Not every field is mandatory in every context."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1429. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1417 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1417 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1417 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1417, S1429 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Step 199 verdict: restates seven distinctions established in this step (False!=Unknown, Unknown!=Ambiguous, Ambiguous!=Conflicted, Probability!=Truth, Confidence!=Probability, Decision-certainty!=Epistemic-certainty, Evidence-quantity!=Evidence-independence) and states the deeper result that KnowledgeOS must represent uncertainty rather than hide it, culminating in Knowing!=Doing!=BeingAuthorized!=BeingCertain as a central architectural principle, mapped onto the recurring four-Gita-chapter lens (meaning/identity, state/continuity, action/consequence, transmission/history/wisdom) [S1417].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1417] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Proposes a unified twelve-field EpistemicAssessment object (Proposition, Status, Evidence, Model, Probability, Uncertainty, Assumptions, Scope, Time, Author, Authority, Lineage), explicitly noting not every field is mandatory in every context -- e.g. a deterministic rule (Age>=18) may have Satisfied=True with no meaningful probability." (anchor: "EpistemicAssessment = (Proposition,Status,Evidence,Model,Probability,Uncertainty,Assumptions,Scope,Time,Author,Authority,Lineage) Not every field is mandatory in every context.")
- [S1417] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines the Step 199 epistemic layer formally as E=(Hypotheses, Evidence, Models, Status, Uncertainty, Assumptions), with the governance layer as a function G: E x Authority -> Decision." (anchor: "\mathcal{E} = (H,E,M,S,U,A) ... G: \mathcal{E}\times Authority \rightarrow Decision.")
- [S1417] types=[INVARIANT] scope=THEORY-LEVEL — "Final and capstone invariant I_68 of Step 199: no epistemic uncertainty may be silently converted into certainty; any such transition must be explicit, rule-governed, and traceable -- summarized as the single most important boundary of the step." (anchor: "I_{68}: No epistemic uncertainty may be silently converted into certainty; any such transition must be explicit, rule-governed, and traceable.")
- [S1417] types=[RESTATEMENT, ARGUMENT] scope=THEORY-LEVEL — "Step 199 verdict: restates seven distinctions established in this step (False!=Unknown, Unknown!=Ambiguous, Ambiguous!=Conflicted, Probability!=Truth, Confidence!=Probability, Decision-certainty!=Epistemic-certainty, Evidence-quantity!=Evidence-independence) and states the deeper result that KnowledgeOS must represent uncertainty rather than hide it, culminating in Knowing!=Doing!=BeingAuthorized!=BeingCertain as a central architectural principle, mapped onto the recurring four-Gita-chapter lens (meaning/identity, state/continuity, action/consequence, transmission/history/wisdom)." (anchor: "KnowledgeOS must represent uncertainty, not hide it. ... Knowing \neq Doing \neq Being authorized \neq Being certain.")
- [S1429] types=[RESTATEMENT, LIMITATION] scope=OBJECT — "Confirms Step 199's own framing that elevating a probability to accepted truth is a policy act, not a mathematical one, but adds the verifier finding that no actual mapping is ever given anywhere between the five-valued epistemic status set and any probability value -- the two coexist as independent, unconnected tuple slots." (anchor: "Elevating probability to truth is a policy act (P>=0.95 -> CandidateAcceptance is DomainPolicy, not mathematics, §199.33–35). ... No mapping between E5 and any P is given anywhere; the two sit as independent tuple slots.")
- [S1429] types=[LIMITATION] scope=OBJECT — "Notes that Step 199's own five-valued epistemic status set demands precise semantics for each value by its own stated standard, yet precise semantics are actually supplied for only two of the five values." (anchor: "Status ladder E3 -> E4 -> E5 = {Supported, Refuted, Unknown, Ambiguous, Conflicted}; precise semantics required by the file itself but supplied for only 2 of 5.")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
