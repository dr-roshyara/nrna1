# shieber-epistemic-process-lens

**Scope(s):** OBJECT · **Row count:** 28 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** KOS-EPI-01..KOS-EPI-10; basing relation; know-how/knowledge-wh/knowledge-that · **Aliases:** Knowledge is not one thing; Shieber lens
**Candidate group membership (NOT an identity claim):**
- G0956: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, `nyaya-vaisesika-epistemic-process-lens` and `shieber-epistemic-process-lens` share "working_label token overlap Jaccard=0.50 (shared tokens: ['epistemic', 'lens', 'process'])" — a purely mechanical lexical-overlap signal (both labels independently apply an external epistemology lens to the same "epistemic process" theme), not a reviewed or confirmed relationship. No identity is asserted.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0011, scope OBJECT: "S0403's external epistemic lens applying Shieber's epistemology textbook to KnowledgeOS: separates claim from state-of-affairs, evidence from truth, requires a basing relation, distinguishes coherentism/foundationalism/externalism, and proposes a seven-concept kernel model (KnowledgeClaim/Evidence/Grounding/Derivation/Source/Lineage/KnowledgeAssessment) plus ten KOS-EPI invariants; distinct from the generic 'evidence' Operational Evidence Register object and from knowledgeos-kernel-concept (B0005)."

**Single-candidate flag (NOT a confirmed source):** source_id S0430, batch B0011 — why_uncertain: "S0403 (shieber-epistemic-process-lens) also uses a 'KOS-EPI-01..10' numbering convention for its own distinct invariant register; this file's 'KOS-EPISTEMIC-001..012' tags are a different, self-declared register from Chalmers, not shown to be identical to S0403's KOS-EPI series, but the near-identical naming convention (KOS-EPI vs KOS-EPISTEMIC) raises a genuine identity question worth flagging." S0430 does not appear in this label's `family.rows` — it is flagged only as a naming-convention collision, not confirmed evidence for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0403 §"Shieber explicitly separates: know-how; knowledge-wh; knowledge-that"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0403 §same anchor as lexical, above]
- CANDIDATE-FORMAL-BIRTH: [S0403 §"Evidence should have domain identity and provenance."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0403. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (no retracted_by, no superseded_by, not contested). DORMANT is a recency heuristic (batch B0011, relatively early in the corpus, and all 28 rows come from one single-date document) — not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0403 (x4) |
| informal_meaning | PRESENT | S0403 |
| formal_definition | PRESENT | S0403 (x8) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0403 (x11) |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0403 (x13) |
| examples | PRESENT | S0403 (x4) |
| warnings | PRESENT | S0403 (x7) |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Four rows carry classified rationale evidence, all from S0403. The kernel must be graph-shaped, not tree-shaped, supporting both foundationalist grounding chains and coherentist mutual-support webs via a KnowledgeGraph carrying multiple edge types, rather than committing to either philosophical theory. KOS-EPI-09 (Distributed Cognition Requires System-Level Provenance) argues modern knowledge is produced by socially distributed cognitive processes, so KnowledgeOS should model epistemic processes, not merely epistemic documents, with the AI Engineering Platform and KnowledgeOS as distinct but cooperating bounded contexts. A related warning: "local understanding of a process is not equivalent to system-level understanding of the process," supporting Agent Context ≠ Platform Context ≠ Knowledge Context ≠ Governance Context as separate bounded contexts. The deepest DDD conclusion: KnowledgeOS is not fundamentally a repository of knowledge but an epistemic infrastructure for producing, grounding, assessing, preserving, challenging, and governing engineering knowledge — an EPISTEMIC PROCESS pipeline rather than Knowledge+Evidence+Governance+AI. `rationale_truncated_count` is 0, so no further rationale-bearing rows are known to exist beyond this capture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
All 28 rows share `source_id` S0403, path `docs/knowledgeos/brainstorming/kernel/20260824-000400-shieber-external-epistemic-lens-knowledge-is-not-one-thing.md`.

1. `types=[DISTINCTION, CONCEPT]` — Shieber distinguishes know-how, knowledge-wh, knowledge-that; DDD implication: the central object should be called "Knowledge Claim" not "Knowledge."
2. `types=[INVARIANT, DISTINCTION]` — KOS-EPI-01 Claim/Reality Separation: a claim is an assertion about a state of affairs, not the state of affairs itself; kernel pipeline World/System State → Observation → Claim → Assessment → Knowledge Status.
3. `types=[INVARIANT]` — KOS-EPI-02 Evidence/Truth Separation: Evidence ≠ Truth ≠ Claim ≠ Knowledge Status.
4. `types=[DEFINITION, INVARIANT]` — KOS-EPI-03 Basing: possessing good evidence is insufficient; the claim must actually be based on it (the "basing relation"), modeled as a semantic Evidence-BASIS_FOR-Claim relationship.
5. `types=[FORMALIZATION], completeness=PARTIAL` — Proposed Evidence structure (EvidenceId, Source, Observation/Artifact reference, CapturedAt, Context, EvidenceKind) and a Claim.EvidenceBasis relation. Missing: "exact field types".
6. `types=[INVARIANT]` — KOS-EPI-05 Coherence Is Not Truth: a perfectly coherent belief set can still fail to correspond to objective reality.
7. `types=[WARNING, EXAMPLE, DISTINCTION] scope=CROSS-OBJECT` — AI hallucination graph rule: a generated chain A→B→C→D later judged "mutually consistent" is one lineage, not four independent confirmations; the kernel must distinguish Independent Support from Derived Support.
8. `types=[PRINCIPLE]` — Foundationalism motivates "Foundational Evidence"/"Primary Ground": every derivation chain should be traceable to originating grounds, without making KnowledgeOS a foundationalist system.
9. `types=[ARGUMENT, FORMALIZATION]` — see Rationale above (graph-shaped, not tree-shaped kernel).
10. `types=[COUNTEREXAMPLE, INVARIANT]` — KOS-EPI-04 Process Matters: the stopped-clock example shows TRUE+EVIDENCE+BASING is still insufficient without PROCESS RELIABILITY, mapped to the Verification/Assurance Engine.
11. `types=[DISTINCTION, EXAMPLE]` — Distinguishes Epistemic Grounding ("why do we believe this?") from Process Assurance ("why should we trust the process?"), illustrated by a "Build is reproducible" claim example.
12. `types=[CONCEPT, DEFINITION], completeness=PARTIAL` — Proposed KnowledgeAssessment concept evaluating a claim against Evidence/Grounding/Derivation/Source/Process/Context/Contradictions/Temporal validity, producing Claim→Assessment→Epistemic Status. Missing: "formal schema".
13. `types=[INVARIANT, WARNING]` — Persistence of a claim is not proof of persistence of its validity; memory is subject to confabulation over time.
14. `types=[PRINCIPLE, INVARIANT]` — KOS-EPI-07 Historical Evidence Must Survive Forgetting: a claim's historical basis must survive independently of an agent's current memory ("KnowledgeOS externalizes epistemic memory").
15. `types=[INVARIANT, WARNING]` — KOS-EPI-06 Provenance Must Be Externalized: humans are poor at remembering belief sources (source-monitoring failures), so provenance must be systemically captured.
16. `types=[PRINCIPLE, INVARIANT]` — KOS-EPI-08 Source Reliability Is a Process Property: AI agent output should flow Agent→Testimony/Proposal→Evidence→Verification→Assessment→Accepted Knowledge, never directly Agent→Knowledge.
17. `types=[ARGUMENT, INVARIANT] scope=THEORY-LEVEL` — see Rationale above (KOS-EPI-09 distributed cognition).
18. `types=[WARNING, ARGUMENT] scope=CROSS-OBJECT` — see Rationale above (local vs system-level process understanding).
19. `types=[WARNING, INVARIANT, EXAMPLE]` — KOS-EPI-10 Independent Support Requires Lineage Awareness: five agents repeating a claim (A→B→C→D→E) is one lineage, not five independent sources.
20. `types=[DISTINCTION, DEFINITION]` — Source ("where did this originate?") and Lineage ("through which transformations/actors did it reach us?") should be modeled as distinct concepts.
21. `types=[DISTINCTION, EXTENSION], completeness=PARTIAL` — Performative vs acquaintance know-how implies KnowledgeOS should eventually distinguish Declarative Knowledge, Procedural Knowledge, and Operational Know-How. Missing: "formal schema".
22. `types=[RESTATEMENT, PRINCIPLE]` — Perception is not an unmediated copy of reality, reinforcing Observation ≠ Reality: World→Observation mechanism→Observation→Interpretation→Claim.
23. `types=[FORMALIZATION, PRINCIPLE]` — Deterministic assurance should verify the process, not just the answer; an Assurance Record should carry Subject/Method/Inputs/Preconditions/Execution/Result/Environment/Version/Timestamp.
24. `types=[CONCEPT, FORMALIZATION], completeness=PARTIAL` — Proposed seven-concept KnowledgeOS Kernel candidate model: KnowledgeClaim, Evidence, Grounding, Derivation, Source, Lineage, KnowledgeAssessment, under cross-cutting Context/Time/Identity. Missing: "exact field types", "persistence model". Lineage claim: SOURCE-CLAIMED-EXTENSION of "Audi extraction (prior batch)".
25. `types=[WARNING, FORMALIZATION], completeness=PARTIAL` — EpistemicStatus must not be a boolean isKnowledge flag; a candidate enum (Proposed/Supported/Assessed/Accepted/Challenged/Defeated/Superseded/Reinstated) is offered but kept under investigation, not frozen. Missing: "final schema".
26. `types=[CONSTRAINT, WARNING]` — Explicitly excludes from the kernel ConfidenceScore, TruthScore, universal ReliabilityScore, philosophical Foundationalism/Coherentism aggregates, Belief as primary domain object, Mind, Consciousness, SubjectiveExperience, generic Trust, generic AIKnowledge, and a universal KnowledgeGraph aggregate.
27. `types=[ARGUMENT, RESTATEMENT] scope=THEORY-LEVEL` — see Rationale above (epistemic infrastructure, not knowledge repository).
28. `types=[FORMALIZATION, RESTATEMENT] scope=THEORY-LEVEL` — Combined kernel diagram integrating Audi + Williamson + Shieber lenses, placing Observation/Assurance/Governance/Review/AI Engineering/Retrieval/Workflow/Knowledge Delivery outside the kernel as the KnowledgeOS Platform. Lineage claim: SOURCE-CLAIMED-EXTENSION of "Audi and Williamson lenses (prior batch B0010)".

## Notes for P3
- All 28 rows come from a single document (S0403), making this label unusually self-contained and internally coherent — a systematic, single-sitting application of Shieber's epistemology textbook to KnowledgeOS's kernel design, building steadily from distinctions (know-how/knowledge-wh/knowledge-that) through ten numbered KOS-EPI invariants to a seven-concept kernel model and finally a combined Audi+Williamson+Shieber diagram. No internal contradiction observed.
- `files_touching` lists S0404 and S0406 in addition to S0403, but neither appears in any of this label's 28 rows — flagged as a data point for P3, not resolved here.
- The single-candidate flag (S0430) raises a real naming-convention collision: S0430 independently uses "KOS-EPISTEMIC-001..012" (a Chalmers-derived register) as distinct from but suspiciously similar to this label's own "KOS-EPI-01..10" (Shieber-derived) register. P3 should treat this as a priority item to check for accidental register merging risk, even though the note explicitly says the two are "not shown to be identical."
- Three rows (5, 12, 21, 24, 25 — five in total) explicitly mark themselves PARTIAL/missing a formal/final schema — this label collectively represents a well-developed conceptual model that is still, by its own admission, pre-formalization.
