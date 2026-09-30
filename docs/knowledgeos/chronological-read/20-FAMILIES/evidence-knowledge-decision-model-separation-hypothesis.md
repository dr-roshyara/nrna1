# evidence-knowledge-decision-model-separation-hypothesis

**Scope(s):** THEORY-LEVEL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Evidence / Knowledge / Decision as different models · **Aliases:** Wisdom is not Knowledge + more data
**Candidate group membership (NOT an identity claim):** G0081: shares an explicit agent-stated uncertainty with `wisdom-multi-objective-formalization` (batch B0012) — that later material formalizes Wisdom as a function of Knowledge, Uncertainty, Values, Consequences, Responsibility and time-Horizon (not reducible to expected-utility optimization), with multi-objective utility vectors, a reversibility metric Rev(a) constraining Risk(a)*(1-Rev(a))<threshold, multi-horizon utility, and Governance as a hard constraint set defining the feasible decision set within which Wisdom selects — noted as possibly related to this label — relationship not yet decided (P3).

A `single_candidate_flags` entry also exists (not a group, but a recorded uncertainty): S2479 (batch B0060) — the "strongest statement must never exceed available evidence" maxim recurs verbatim throughout the later corpus as a named governing principle, and it is unclear whether it already has a dedicated index entry distinct from this label or is still uncaptured. This is a flagged uncertainty in the ledger itself, not evidence resolved in this label's own rows.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope THEORY-LEVEL: "Hypothesis (explicitly framed as a DDD discovery hypothesis, not yet a redesign) that Evidence (must be traceable to source), Knowledge (must have justified inference), Decision (must have legitimate authority and rationale) and a Wisdom Assessment (must account for uncertainty, consequences, applicability, constraints) are different models with different invariants sharing vocabulary, proposed as a candidate bounded-context investigation; introduces a second uncertainty type (decision uncertainty, distinct from epistemic uncertainty) and a normative/values layer that causal analysis cannot itself resolve."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0450 §"wisdom requires consequence space, not only outcome estimation. ... ConsequenceAssessment: benefit, cost, risk, externality, reversibility, time horizon, affected parties."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0450 §"A wisdom-oriented system should potentially produce: DO NOT ACT YET ... Required next evidence: ... That is epistemic restraint."]
- CANDIDATE-FORMAL-BIRTH: [S0450 §"wisdom requires consequence space, not only outcome estimation. ... ConsequenceAssessment: benefit, cost, risk, externality, reversibility, time horizon, affected parties."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0453. Candidate lifecycle: DORMANT. Evidence: retracted_by empty, superseded_by empty, contested_by_own_contradiction_type false — nothing in this label's own rows claims retraction, supersession, or internal contradiction. DORMANT here is a heuristic based on recency of source_id (S0453, batch B0012) relative to the corpus's later range, not a confirmed close-out; the `single_candidate_flags` note above (S2479, batch B0060) shows the corpus may still be actively referencing an associated maxim much later, which is a signal against reading DORMANT as "abandoned."

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0450, S0451 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0450 (x3), S0451 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0450, S0451, S0453 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0450 (x4), S0453 |
| examples | PRESENT | S0450 (x3), S0453 |
| warnings | PRESENT | S0450 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0450 |

## Rationale
S0450 synthesizes seven lenses (Zero/Statistical/Causal/DDD/Wisdom/Governance/Learning), each mapped to a fundamental question and a KnowledgeOS responsibility, producing an Evidence->Inference->Knowledge->Wisdom->Decision->Action->Outcome->Evidence cycle; it explicitly declines to redesign KnowledgeOS around this immediately, instead proposing the separation as a domain-discovery hypothesis requiring a dedicated DDD/Wisdom discovery round, suggested to possibly expose KnowledgeOS's actual core domain [S0450]. The stated gap this closes: causal inference alone ("mandatory reviews -> fewer defects") cannot tell a system whether to act, because effect, cost, risk, applicability, reversibility, and values jointly determine the decision, and conflating "X causes Y" with "we should do X" was identified as the error to avoid [S0450]. S0451 gives the rationale a concrete unifying shape: it combines the four books/lenses (Evidence, Causal Inference, Statistics, DDD, Wisdom) into one epistemic operating loop (Decision -> Evidence Need -> ... -> Wisdom -> Decision), describing it as "starting to look like the actual KnowledgeOS epistemic operating loop" — i.e. the hypothesis's purpose is architectural: to keep Evidence, Knowledge, and Decision as separately-invariant models rather than one undifferentiated pipeline [S0451]. rationale_truncated_count is 0 — no further rationale rows exist beyond what's shown.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0450] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Wisdom Lens #1 Consequence: a causal estimate generates a wider consequence space, formalized as a ConsequenceAssessment object (benefit/cost/risk/externality/reversibility/time_horizon/affected_parties)." (anchor: "wisdom requires consequence space, not only outcome estimation. ... ConsequenceAssessment: benefit, cost, risk, externality, reversibility, time horizon, affected parties.")
- [S0450] types=[PRINCIPLE, WARNING] scope=OBJECT, label_confidence=UNCERTAIN — "Wisdom Lens #2 Context: causal effects are population/intervention-specific, so CausalKnowledge{population, intervention, conditions, assumptions} must pass an Applicability check before generalizing, preventing 'evidence from context A -> universal rule.'" (anchor: "Even if an intervention is causally effective in population A, should we apply it to population B? ... prevents Evidence from context A -> Universal rule.")
- [S0450] types=[PRINCIPLE] scope=THEORY-LEVEL, label_confidence=UNCERTAIN — "Wisdom Lens #3 Epistemic humility: confidence/uncertainty/limitations/unknowns/assumption-dependence/sensitivity as wisdom-layer fields; proposes UNKNOWN or NOT JUSTIFIED as possibly the most important resulting state." (anchor: "observational causal inference depends on assumptions external to the observed data. ... Evidence + Assumptions = Causal conclusion, not Evidence = Truth. ... UNKNOWN or NOT JUSTIFIED")
- [S0450] types=[EXAMPLE, CONCEPT] scope=OBJECT, label_confidence=UNCERTAIN — "Wisdom Lens #4 Knowing when not to act: given moderate evidence and high intervention cost, a wisdom-oriented system should output 'DO NOT ACT YET' with reasons and required next evidence — termed 'epistemic restraint.'" (anchor: "A wisdom-oriented system should potentially produce: DO NOT ACT YET ... Required next evidence: ... That is epistemic restraint.")
- [S0450] types=[FORMALIZATION, EXAMPLE] scope=OBJECT, label_confidence=UNCERTAIN — "Wisdom Lens #5 Reversibility: decisions should carry reversibility/rollback_cost/irreversible_consequences/option_value fields, a decision-theoretic layer above causal inference." (anchor: "A causal result does not tell you whether an intervention is safe to try. ... reversibility, rollback cost, irreversible consequences, option value ... Low confidence + irreversible organizational change -> do not proceed.")
- [S0450] types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Proposes a four-aggregate chain: CausalInquiry -> CausalAssessment -> DecisionAssessment -> Decision, contrasted with a naive 'AI Agent -> LLM -> Recommendation' architecture." (anchor: "I would not put Wisdom inside the causal inference aggregate. ... The causal model optimizes for valid inference. The decision/wisdom model optimizes for responsible action. Those are different invariants.")
- [S0450] types=[DISTINCTION] scope=THEORY-LEVEL — "Introduces a second uncertainty type: epistemic uncertainty (whether X causes Y) versus decision uncertainty (whether acting on X is worthwhile) — causal certainty of 90% is compatible with decision suitability of only 40%." (anchor: "Epistemic uncertainty: We don't know whether X causes Y. Decision uncertainty: Even if X probably causes Y, we don't know whether acting on it is worthwhile. ... Causal certainty: 90% / Decision suitability: 40% is completely possible.")
- [S0450] types=[ANALYSIS, FUTURE-RESEARCH] scope=THEORY-LEVEL — "Synthesizes seven lenses (Zero/Statistical/Causal/DDD/Wisdom/Governance/Learning) into an Evidence->Inference->Knowledge->Wisdom->Decision->Action->Outcome->Evidence cycle; proposes a dedicated DDD/Wisdom discovery round rather than redesigning immediately." (anchor: "Lens | Fundamental question | KnowledgeOS responsibility ... Zero/Statistical/Causal/DDD/Wisdom/Governance/Learning ... a dedicated DDD/Wisdom discovery round.")
- [S0451] types=[ANALYSIS, FORMALIZATION] scope=THEORY-LEVEL (also labeled evidence-acquisition-domain, causal-reasoning-assurance-layer) — "Combines all four books+lenses into one epistemic operating loop from Decision through Evidence Need, Acquisition, Assessment, Statistical/Causal Analysis, Knowledge, Wisdom, back to Decision; described as 'starting to look like the actual KnowledgeOS epistemic operating loop.'" (anchor: "Book of Evidence tells us HOW observations become evidence ... Causal Inference tells us HOW evidence supports causal claims ... Statistical methods tell us HOW evidence is quantified ... DDD tells us HOW to model the domain ... Wisdom tells us HOW to determine what evidence is needed.")
- [S0453] types=[DISTINCTION, EXAMPLE] scope=THEORY-LEVEL — "Confirms the Wisdom/Decision separation via Bayesian decision theory: the same 10% failure probability yields 'accept risk' at low cost of failure but 'mitigate' at high cost — belief and decision must never be collapsed." Carries a lineage_claims entry (SOURCE-CLAIMED-IDENTITY) targeting "the Wisdom Lens epistemic-vs-decision-uncertainty distinction (S0450)," quote "This perfectly confirms our previous Wisdom extraction." (anchor: "Bayesian epistemology tells us: How should belief change? Decision theory asks: What should we do? ... probabilities alone do not determine action; utility is also required. ... this perfectly confirms our previous Wisdom extraction.")

## Notes for P3
- Own observation: this label is unusually internally coherent — all ten rows read as one continuous argument (S0450's five "Wisdom Lenses" -> S0451's unifying loop -> S0453's independent Bayesian confirmation) with no correction/retraction rows, which is a stronger-than-usual evidentiary base for a THEORY-LEVEL hypothesis this early in the corpus (B0012).
- Own observation: the `single_candidate_flags` entry (S2479, batch B0060) flags that a specific maxim ("the strongest statement must never exceed available evidence") recurs much later in the corpus as a named governing principle, and its relationship to this label is explicitly unresolved in the ledger — P3 may want to check whether that maxim descends from this hypothesis's epistemic-restraint material (S0450) or is an independently-born governance principle.
- Own observation: S0451 also carries two other labels (`evidence-acquisition-domain`, `causal-reasoning-assurance-layer`) in the same row — this is a multi-label row, not a merge signal; flagging per R5/R12 discipline.
