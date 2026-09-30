# evidence-acquisition-domain

**Scope(s):** OBJECT · **Row count:** 19 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Evidence Acquisition Context`; `EvidenceAcquisition` · **Aliases:** evidence collection is itself a domain
**Candidate group membership (NOT an identity claim):**
- G0074: `evidence-acquisition-domain` · `evidence-inquiry-object` — explicit agent-stated uncertainty ("POSSIBLY relates to", batch B0012). Relationship not yet decided (P3); the linked label's note describes a Yin-Yang-lens reframing (SupportingObservations/ContradictingObservations/MissingObservations/AlternativeExplanations/ContextualConditions) expanded into a fuller EvidenceInquiry object — possibly a sibling or successor concept to this label's EvidenceAcquisition, but no identity is asserted.
- G1392: `evidence-acquisition-domain` · `situational-provenance` — co-occur in the same contribution's labels[] 2 separate times across the corpus. Relationship not yet decided (P3) — 3 of this label's 19 rows also carry `situational-provenance`.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Central discovery from Achinstein's 'The Book of Evidence': evidence collection is itself a domain, not mere ingestion; proposes an EvidenceAcquisition aggregate (Question, Hypothesis/TargetClaim, TargetPopulation, SelectionProcedure, ObservationMethod/Protocol, MeasurementMethod/Plan, Conditions, Instrumentation, SamplingRules, Exclusions, Competitors/AlternativeExplanations, ExpectedObservations, Observations, Deviations, AcquisitionAssessment, Result) and a candidate Evidence Acquisition Context bounded context, with the lifecycle EvidenceQuestion -> AcquisitionDesign -> SelectionProcedure -> Observation -> Measurement -> ObservationRecord -> AcquisitionValidation -> CandidateEvidence -> EvidenceAssessment."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0451 §"Hertz and Thomson agreed on the observed result — no electrical deflection was detected — but disagreed about whether that observation constituted evidence for electrical neutrality. Thomson later showed that the experimental setup itself was inadequate ... Observation ≠ Evidence."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0451 §"EvidenceAcquisition: Question, Hypothesis, TargetPopulation, SelectionProcedure, ObservationMethod, MeasurementMethod, Conditions, Instrumentation, SamplingRules, Exclusions, Competitors, ExpectedObservations, Result. The evidence is the result of an acquisition process."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1290. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty. Rows span from an initial theory extraction (S0451/S0452, 2026-08-24) to a later kernel-review correction/dissolution (S1290, batch B0031) — a real chronological arc, not a single burst. DORMANT reflects no further activity after S1290 in captured rows, not a confirmed retirement; the final row (S1290, second entry) treats the concept as a settled "tenth dissolution" resolving an apparent tension, which reads as closure rather than abandonment.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0451 (x2), S1290 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0451 (x5), S0452 (x3) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0451 (x4) |
| dependencies | PRESENT | S0451 (x4), S0452 (x4), S1290 (x2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0451 (x6), S0452, S1290 (x2) |
| examples | PRESENT | S0451 (x4) |
| warnings | PRESENT | S0451 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0451, S0452 |

## Rationale
`rationale_evidence` holds 3 entries (`rationale_truncated_count` 0): (1) S0451 combines Achinstein's Book of Evidence with Causal Inference, Statistical methods, DDD, and Wisdom lenses into one "epistemic operating loop" (Decision -> Evidence Need -> ... -> Wisdom -> Decision), argued to be "starting to look like the actual KnowledgeOS epistemic operating loop" — explaining why evidence acquisition earns its own domain rather than being folded into a generic evidence store; (2) S0451 also argues the architecture should not begin with an "Evidence Repository" but with EVIDENCE NEED, because "evidence claims are themselves empirical" (Thomson's discovery of the Hertz instrumentation flaw is offered as the clearest demonstration); (3) S1290 later dissolves an apparent tension between an earlier finding (S1-F011, excluding evidence acquisition from the Kernel) and this label's own founding claim (S1-F030, acquisition as its own domain) by separating "Kernel extent" from "bounded context" — both can be true of different scope levels. This is explicitly flagged as an HPA-supplied classification instrument applied to existing findings, not new corpus evidence.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

**S0451** (`docs/knowledgeos/brainstorming/kernel/20260824-033419-book-of-evidence-evidence-acquisition-is-its-own-domain.md`) — 12 rows, the founding extraction:
1. types=[EXAMPLE, DISTINCTION] — Central finding: evidence collection is a domain, not mere ingestion; Achinstein's Hertz/Thomson non-deflection example (later traced to inadequate evacuation causing a screening effect) establishes Observation != Evidence, refining the naive collect->store->analyze pipeline into Observation -> Candidate evidence -> Evidence assessment. Invariant: "Observation ≠ Evidence."
2. types=[FORMALIZATION] — Proposes EvidenceAcquisition as a concept distinct from Evidence, with lifecycle Question->Design->Acquire->Validate->Assess selection->Assess flaws->Create candidate evidence; tentatively identifies an Evidence Acquisition Context as a candidate (not certified) bounded context.
3. types=[INVARIANT, WARNING] — Evidence Quality != Quantity: a large observation set can be epistemically weak under biased selection; decomposes Evidence Quality (architectural decomposition, not a literal formula) into observation x selection x measurement quality x context adequacy x explanatory relevance.
4. types=[FORMALIZATION, EXAMPLE] — Measurement itself is an acquisition mechanism: the Hertz/Thomson case shows a hidden instrumentation flaw suppressing an observation; proposes a MeasurementContext object, "an epistemic measurement contract."
5. types=[DISTINCTION, FORMALIZATION] — Three objects that must not collapse: Observation (what happened), Acquisition (how knowledge of it was obtained), Evidence claim (why it supports a hypothesis).
6. types=[DISTINCTION, EXAMPLE] — Wisdom Lens: not every true observation is decision-relevant evidence — Achinstein's "Michael Jordan eats Wheaties" high-probability-without-explanatory-connection example motivates an EvidenceRelevance check.
7. types=[FORMALIZATION, INVARIANT] — Proposed EvidenceAcquisition aggregate (EvidenceQuestion, TargetClaim, TargetPopulation, SelectionProcedure, MeasurementPlan, AcquisitionConditions, AlternativeExplanations, ObservationProtocol, Observations, Deviations, AcquisitionAssessment) with candidate invariant: completion-for-evidence requires sufficiently recorded procedure/conditions/observations/deviations — explicitly flagged as candidate, not for implementation.
8. types=[EXAMPLE, PRINCIPLE] — EvidenceObservation distinct from Observation because an operationally-collected observation may only later become evidence for a new hypothesis; "Evidence is contextualized observation" — grounded in Achinstein's rejection of the old-evidence-can't-count view.
9. types=[ANALYSIS, FORMALIZATION] — Combines Book of Evidence + Causal Inference + Statistics + DDD + Wisdom into one epistemic operating loop (see Rationale). Also labeled `causal-reasoning-assurance-layer`, `evidence-knowledge-decision-model-separation-hypothesis`.
10. types=[PRINCIPLE, CONSTRAINT] — SHALL-level principle: KnowledgeOS SHALL distinguish observation acquisition from evidential interpretation, preserving the acquisition mechanism because evidential status/strength may depend on it — grounded in Achinstein's Hertz, raven, and drug examples.
11. types=[CONSTRAINT, EXTENSION] — Proposes a required AI-agent evidence-collection procedure (clarify hypothesis -> identify evidence need -> identify competing explanations -> define selection criteria -> collect -> record provenance -> audit selection -> assess -> conclusion), replacing naive claim->search->answer behavior; flagged for a future Agent Developer Guide.
12. types=[FUTURE-RESEARCH, OPEN-QUESTION] — Proposes a dedicated discovery round with 14 open questions (Observation vs Evidence, EvidenceNeed, SelectionProcedure, MeasurementContext, selection-bias detection, evidence validation, Wisdom's determination of highest-decision-value acquisition, etc.), explicitly declining to jump to implementation.
13. types=[PRINCIPLE, ARGUMENT] scope=THEORY-LEVEL — Architecture should start from EVIDENCE NEED, not an Evidence Repository (see Rationale). Also labeled `evidence-need-object`.

**S0452** (`docs/knowledgeos/brainstorming/kernel/20260824-033614-zero-and-chinese-lenses-on-minimum-structure-before-evidence.md`) — 4 rows, a same-day refinement:
14. types=[DEFINITION] — Deeper formal definition emphasizing "can subsequently be assessed" vs "is automatically evidence" — an explicit refinement of S0451. `lineage_claims`: SOURCE-CLAIMED-REFINEMENT of S0451's EvidenceAcquisition concept.
15. types=[FORMALIZATION] — Combines the Zero-Lens occurrence-to-action chain with a Chinese relational structure: REALITY -> PHENOMENON -> OBSERVATION -> OBSERVATION RECORD -> CANDIDATE EVIDENCE -> EVIDENCE -> KNOWLEDGE -> WISDOM -> ACTION, plus SITUATION{PERSON/TIME/RELATION} feeding OBSERVATION: "the observation is never epistemically naked." Also labeled `situational-provenance`.
16. types=[PRINCIPLE, DEFINITION] — Two culminating principles: evidence is a STATUS an observation may acquire through a defensible relationship (phenomenon/observation/acquisition procedure/situation/hypothesis/explanation) — stronger than "evidence has provenance"; and an observation cannot be fully understood apart from the situation/relationships/conditions/transformations through which it arose. Also labeled `situational-provenance`.
17. types=[FUTURE-RESEARCH, CORRECTION] — Defers schema design: proposes a discovery round (Zero/Chinese/Achinstein/Causal/Wisdom lenses in sequence) to derive the domain model from first principles, explicitly warning against prematurely inventing entities such as EvidenceAcquisition, EvidenceNeed, or EvidenceTrajectory — i.e., against the very objects this file and its predecessor (S0451) just proposed. Also references `evidence-need-object`, `evidence-trajectory-object`.

**S1290** (`docs/knowledgeos/reviews/kernel/session2/S2-R-F030-review-of-s1-f030-evidence-acquisition.md`) — 2 rows, a later kernel-review correction:
18. types=[CORRECTION, DISTINCTION] — Corrects Session 1's classification of "acquisition procedure determines evidential status" as NOT ADDRESSED: law's EvidenceLinks member already carries acquisition method + reliability conditions ("pseudo-evidence never admitted" gate), so the retention claim is already law; what remains unaddressed is the CONSTITUTIVE claim — whether acquisition method determines (not merely records) evidential standing. `completeness`: PARTIAL; `missing`: "whether acquisition method constitutes (vs merely records) evidential standing."
19. types=[ANALYSIS, DISTINCTION] — Dissolves the apparent tension between S1-F011 (excludes evidence acquisition as a Kernel mechanism) and S1-F030 (this label's founding claim, acquisition as its own domain): via an A/B/C/E extent-vs-surrounding-architecture classification, acquisition can be its own bounded context (C) while outside the Kernel's extent (A) — "the tenth dissolution... found via a distinct mechanism" — explicitly flagged as an HPA-supplied classification instrument, not new corpus evidence.

## Notes for P3
This label shows a genuine three-stage evolution: (1) S0451's founding, expansive proposal (12 rows, same day) that both defines EvidenceAcquisition richly AND explicitly proposes deferring implementation (row 12); (2) S0452's same-day refinement that sharpens the definition (row 14) but then explicitly warns against the very entities S0451 proposed (row 17) — a real, source-acknowledged internal tension, though not flagged by `contested_by_own_contradiction_type`; (3) S1290's later review-stage correction and dissolution, resolving an apparent Kernel-scope conflict via a scope-level distinction (rows 18-19). P3 should treat rows 12 and 17 (both explicitly proposing to defer/redo schema design from first principles) as tempering how settled the earlier formal proposals (rows 2, 7, 15, 16) actually are. Row 18's open constitutive question (does acquisition method determine, not just record, evidential standing?) appears to remain genuinely unresolved within this label's own rows. The G0074 link to `evidence-inquiry-object` (an expanded Yin-Yang-lens reframing) may be a closely related successor/sibling concept worth priority attention in reconciliation.
