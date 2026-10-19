# Brief, part P4: Ch. 6 and Ch. 9 (whole-book review S0100)

*2026-10-19. Rulebook v1.3 §12c. Read `../REVIEWER-NOTE.md` first; it binds you. Paths below are relative to the lane root `docs/knowledgeos/knowlegeos_manual_theory_20261018/` unless they start with `docs/`, `developer_guide/` or another repository folder.*

## What you review

- **Your part:** Ch. 6 and Ch. 9 of the frozen copy `06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW/`. Its files:
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.00-index.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.01-method-binding-evidence.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.02-admitting-a-context.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.03-types-and-splits.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.04-ownership-thresholds-breached-invariants.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.05-recording-authority-without-holding-it.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.06-agent-harnesses-linked-not-copied.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/06.07-a-platform-drawn-without-its-kernel.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_06/TODO.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.00-index.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.01-kernel-scaffold.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.02-parts-and-whole.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.03-say-no-and-learn.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.04-invariants-that-cannot-fail.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.05-a-recorded-rule-must-reach-the-actor.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.06-boundary-test-authority-recording.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.07-boundary-test-the-evaluator.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.08-is-history-a-primitive.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/09.09-is-trust-a-primitive.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_09/TODO.md`
- **Also in the copy, for context:** `06-theory/BOOK-OUTLINE.md`, `06-theory/CHAPTER-PLAN.md`, `06-theory/THEORY-MAP.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/PRINCIPAL-TESTIMONY.md` (all under `BOOK-UNDER-REVIEW/`), and any other chapter of the copy where your part cites it.
- **Chapter 7** ("The KnowledgeOS loop and its evidence") is listed in `CHAPTER-PLAN.md` as planned. The copy has no `chapter_07/`.
- **Book search:** `python3 -I 06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW/tmp/tools/book_search.py …` only.

## Step 1: the blind sample (fixed before anyone read, seed 20261019)

Read these four sources **before** opening your part, and write the inventory first. Then choose **one further source** from your part's allowed references below, skim it, and name it and your reason in the inventory.

| Source | Why sampled | Path |
|---|---|---|
| S0071 | most cited in this part (32 anchors) | `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` |
| S0066 | most cited in this part (26 anchors) | `docs/knowledgeos/developer_guide/developer-guide-set-up-ai-agents-with-knowledgeos.md` |
| S2834 | drawn at random from the part's other allowed references | `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-131918_what-knowledgeos-makes-possible-beyond-conventional-ai-rag.md` |
| S0073 | drawn at random from the part's other allowed references | `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-verification-2-report.md` |

## Your part's allowed references (for quote checks)

You may open the **whole** allowed source behind any anchor your part cites. Nothing outside the allowed list.

| Source | Path |
|---|---|
| S0001 | `docs/knowledgeos/KnowledgeOS_Viewpoint_Ownership_Discovery.md` |
| S0002 | `docs/knowledgeos/KnowledgeOS_Platform_Discovery_Coverage_Map.md` |
| S0012 | `docs/knowledgeos/KnowledgeOS_Lifecycle_Gap_Analysis.md` |
| S0019 | `docs/knowledgeos/KnowledgeOS_Mission_Discovery.md` |
| S0022 | `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md` |
| S0024 | `docs/knowledgeos/KnowledgeOS_Architecture_Fitness_Assessment.md` |
| S0025 | `docs/knowledgeos/2026-08-02-mvk-bootstrap-validation-report.md` |
| S0027 | `docs/knowledgeos/KnowledgeOS_Operational_Evidence_Register.md` |
| S0028 | `docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md` |
| S0029 | `docs/knowledgeos/KnowledgeOS_Deferred_Architecture_Register.md` |
| S0030 | `docs/knowledgeos/AI_Workflow_Observation_Log.md` |
| S0031 | `docs/knowledgeos/2026-08-02-knowledgeos-architecture-consolidation.md` |
| S0032 | `docs/knowledgeos/KnowledgeOS_Vision_Mission_Clarification.md` |
| S0033 | `docs/knowledgeos/2026-08-02-knowledgeos-strategic-boundary-consolidation.md` |
| S0034 | `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md` |
| S0035 | `docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md` |
| S0036 | `docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md` |
| S0037 | `docs/knowledgeos/KnowledgeOS_Strategic_Architecture_Discovery.md` |
| S0038 | `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Landscape.md` |
| S0039 | `docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md` |
| S0040 | `docs/knowledgeos/KnowledgeOS_Engineering_Improvement_Cycle.md` |
| S0041 | `docs/knowledgeos/KnowledgeOS_Architecture_Health_Dashboard.md` |
| S0042 | `docs/knowledgeos/README.md` |
| S0045 | `docs/knowledgeos/KnowledgeOS_Operational_Validation_Report.md` |
| S0046 | `docs/knowledgeos/KnowledgeOS_External_Positioning_Comparison_Matrix.md` |
| S0047 | `docs/knowledgeos/reviews/README.md` |
| S0048 | `docs/knowledgeos/reviews/2026-08-16-knowledge-placement-requirement-registration.md` |
| S0049 | `docs/knowledgeos/reviews/2026-08-16-KOS-ATTR-ARCH-001-rev3-independent-architecture-review.md` |
| S0050 | `docs/knowledgeos/reviews/2026-08-16-KOS-ATTR-ARCH-001-f1-f8-repair.md` |
| S0051 | `docs/knowledgeos/reviews/2026-08-16-KOS-ATTR-ARCH-001-f1-f8-repair-independent-verification.md` |
| S0056 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-phase-a-v1.1-correction-verification.md` |
| S0058 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-verification-3-authorization.md` |
| S0062 | `docs/knowledgeos/reviews/2026-08-17-KOS-ATTR-ARCH-001-stage2-bounded-context-confirmation.md` |
| S0063 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-commission.md` |
| S0064 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-principal-review-registration.md` |
| S0065 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-acceptance-registration.md` |
| S0067 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-architecture-landscape-v2.md` |
| S0068 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-capability-map-v2.md` |
| S0069 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-context-map-v2.md` |
| S0070 | `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-003-commission.md` |
| S0072 | `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-verification-report.md` |
| S0078 | `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-domain-model-refinement.md` |
| S0079 | `docs/knowledgeos/reviews/2026-08-18-portfolio-lifecycle-reconciliation.md` |
| S0080 | `docs/knowledgeos/reviews/2026-08-18-arb-sequencing-determination.md` |
| S0081 | `docs/knowledgeos/architecture/README.md` |
| S0083 | `docs/knowledgeos/architecture/01-system-context.puml` |
| S0084 | `docs/knowledgeos/architecture/04-contract-neutrality-fact-model.puml` |
| S0085 | `docs/knowledgeos/architecture/03-component-knowledgeos.puml` |
| S0086 | `docs/knowledgeos/architecture/02-container-architecture.puml` |
| S0089 | `docs/knowledgeos/reviews/2026-08-18-KOS-AIP04-DISCOVERY-001-independent-verification.md` |
| S0090 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-independent-verification-2-amendment1.md` |
| S0091 | `docs/knowledgeos/architecture/2026-08-18-KOS-AIP04-DISCOVERY-001-adr-aip-04-capability-discovery-proposal.md` |
| S0092 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-independent-verification-3-correction2.md` |
| S0095 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-record-integrity-disposition.md` |
| S0096 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-capability-analysis-amendment.md` |
| S0113 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-c10-d4-decision-prep.md` |
| S0128 | `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-ADR.md` |
| S0129 | `docs/knowledgeos/reviews/2026-08-19-KOS-AIP-GOV-STATE-DURABILITY-po-arb-position-registration.md` |
| S0164 | `docs/knowledgeos/brainstorming/20260821-120810-kos-governance-role-cost-optimization.md` |
| S0167 | `docs/knowledgeos/brainstorming/_misc/20260819-224159-linux-analogy-kernel-os-model.md` |
| S0234 | `docs/knowledgeos/architecture/20260821-2259-AIP-Current-Architecture-Reconstruction-Stage-3.md` |
| S0357 | `docs/knowledgeos/brainstorming/20260823-103606-kernel-eight-capacities-k1-k8-and-minimum-kernel.md` |
| S0630 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260825-214833_knowledge-is-probably-not-the-kernel-object.md` |
| S0913 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-100905_step-025u-multi-agent-knowledge-conflict-resolution-consensus-trust-and-distributed-epistemics.md` |
| S0916 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-101108_step-025w-temporal-consistency-bitemporal-knowledge-version-alignment-and-snapshot-semantics.md` |
| S0973 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-120900_step-073-identity-trust-and-cryptographic-provenance.md` |
| S1004 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-123122_step-105-agent-architecture-conformance.md` |
| S1414 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260829-105816_step_196_the-authority-responsibility-model.md` |
| S1491 | `docs/knowledgeos/brainstorming/verification/spec/STEP-TRACE-B5-20260828-early.md` |
| S1500 | `docs/knowledgeos/brainstorming/verification/spec/STEP-VERIFY-025h-025z.md` |
| S1524 | `docs/knowledgeos/brainstorming/verification/spec/STEP-VERIFY-141-158.md` |
| S1609 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-182521_step_247_state-vs-history-sufficiency-test.md` |
| S2775 | `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260906-005257_theory-part-12-time-temporal-semantics-events-intervals-versioning.md` |
| S2782 | `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260906-070417_theory-part-19-persistence-as-semantic-preservation.md` |

## Changed theory (Rulebook §2 step 9)

Text changed at the two previous settlements (theory turn 4 and window C18), as it stands in the copy. Review it like the rest of the part.

- **TT4 settlement:** §9.9 (rewritten at the settlement, including §9.9.11); §9.8 M5 and result 6; register CON-0160, CON-0161; `THEORY-MAP.md` rows F23, F34 and the TT4 block; `BOOK-OUTLINE.md`, the Trust row.

## Dependency structure (generated; no assessments)

From `tmp/tools/dependency_graph.py --root BOOK-UNDER-REVIEW` on the copy. "Rests on" lists the explicit references of a section or concept (CON-IDs, § citations, host sections, register fields). A dependency on a concept without an independent VALIDATED verdict is **unverified** (Rulebook §2 step 8). An INVALID ID can carry a restated form in the same register row; read the row to see which form a citation uses.

```
# dependency_graph.py --root 06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW (run 2026-10-19T19:37:09Z)
nodes: 84 sections, 161 concepts; edges: 972
sections with no explicit link in or out: ['§2.6']
cycles (mutual citation groups, not errors in themselves): 5
    CON-0074 ↔ CON-0075
    CON-0029 ↔ CON-0030
    CON-0027 ↔ CON-0028
    CON-0019 ↔ CON-0020
    CON-0001 ↔ CON-0002 ↔ CON-0003 ↔ CON-0004 ↔ CON-0005 ↔ CON-0006 ↔ CON-0007 ↔ CON-0008 ↔ CON-0009 ↔ CON-0010 ↔ CON-0011 ↔ CON-0013 …

(Limit, Rulebook §12d: this graph sees only explicit references — CON-IDs and § citations in the sections, host sections, and register fields. Implicit dependencies remain the task of step 8 and of the reviewers. Nothing is invalidated automatically.)
```

*Also:* 139 of the graph's nodes (77 sections, 62 concepts) form one group of mutual citation, so downstream lists from the graph cover nearly the whole book and do not separate items. Use the direct "rests on" lists below.

| Section | Rests on (explicit references; concepts with their register status) | Of which unverified (no VALIDATED verdict) | Of which outside this part |
|---|---|---|---|
| §6.1 | §1.14, §1.8, §3.6, §3.8, CON-0041 [INVALID], CON-0057 [INVALID], CON-0072 [VALIDATED], CON-0073 [INVALID], CON-0074 [INVALID], CON-0075 [VALIDATED], CON-0076 [PROVISIONAL] | CON-0041, CON-0057, CON-0073, CON-0074, CON-0076 | §1.14, §1.8, §3.6, §3.8, CON-0041, CON-0057 |
| §6.2 | §0.2, §1.11, §2.8, §3.6, §5.3, §6.1, §6.3, CON-0027 [INVALID], CON-0028 [INVALID], CON-0085 [INVALID] | CON-0027, CON-0028, CON-0085 | §0.2, §1.11, §2.8, §3.6, §5.3, CON-0027, CON-0028 |
| §6.3 | §0.2, §1.11, §3.3, §3.7, §4.1, §6.1, §6.2, §8.1, CON-0042 [INVALID], CON-0043 [INVALID], CON-0086 [INVALID], CON-0087 [VALIDATED], CON-0088 [INVALID], CON-0089 [VALIDATED] | CON-0042, CON-0043, CON-0086, CON-0088 | §0.2, §1.11, §3.3, §3.7, §4.1, §8.1, CON-0042, CON-0043 |
| §6.4 | §0.5, §1.10, §1.5, §1.8, §2.10, §3.8, §3.9, §4.1, §5.5, §6.2, §6.5, §8.1, §8.2, CON-0026 [PROVISIONAL], CON-0056 [VALIDATED], CON-0059 [VALIDATED], CON-0064 [VALIDATED], CON-0070 [VALIDATED], CON-0076 [PROVISIONAL], CON-0092 [VALIDATED], CON-0102 [VALIDATED], CON-0103 [PROVISIONAL], CON-0104 [VALIDATED], CON-0108 [VALIDATED] | CON-0026, CON-0076, CON-0103 | §0.5, §1.10, §1.5, §1.8, §2.10, §3.8, §3.9, §4.1, §5.5, §8.1, §8.2, CON-0026, CON-0056, CON-0059, CON-0064, CON-0070, CON-0092 |
| §6.5 | §0.3, §0.5, §1.10, §1.12, §1.13, §1.14, §1.16, §1.6, §3.2, §4.3, §5.5, §6.2, §6.3, §6.4, §9.3, §9.4, §9.6, CON-0087 [VALIDATED], CON-0102 [VALIDATED], CON-0111 [VALIDATED], CON-0120 [VALIDATED], CON-0132 [PROVISIONAL], CON-0133 [INVALID], CON-0134 [PROVISIONAL] | CON-0132, CON-0133, CON-0134 | §0.3, §0.5, §1.10, §1.12, §1.13, §1.14, §1.16, §1.6, §3.2, §4.3, §5.5, CON-0120 |
| §6.6 | §0.7, §1.10, §1.14, §1.16, §1.6, §1.7, §2.5, §3.8, §3.9, §5.4, §6.4, §6.5, §8.6, §9.1, §9.5, §9.6, CON-0092 [VALIDATED], CON-0093 [VALIDATED], CON-0139 [INVALID], CON-0140 [PROVISIONAL] | CON-0139, CON-0140 | §0.7, §1.10, §1.14, §1.16, §1.6, §1.7, §2.5, §3.8, §3.9, §5.4, §8.6, CON-0092, CON-0093 |
| §6.7 | §0.2, §1.12, §1.13, §1.14, §5.7, §6.5, §6.6, §8.8, §8.9, §9.6, CON-0134 [PROVISIONAL], CON-0146 [INVALID], CON-0147 [PROVISIONAL] | CON-0134, CON-0146, CON-0147 | §0.2, §1.12, §1.13, §1.14, §5.7, §8.8, §8.9 |
| §9.1 | §1.6, §3.9, §5.4, CON-0057 [INVALID], CON-0099 [VALIDATED], CON-0100 [PROVISIONAL] | CON-0057, CON-0100 | §1.6, §3.9, §5.4, CON-0057 |
| §9.2 | §8.1, §9.1, CON-0059 [VALIDATED], CON-0105 [VALIDATED] | — | §8.1, CON-0059 |
| §9.3 | §8.3, §9.1, CON-0100 [PROVISIONAL], CON-0110 [INVALID], CON-0111 [VALIDATED] | CON-0100, CON-0110 | §8.3 |
| §9.4 | §1.14, §1.16, §1.17, §1.6, §5.4, §5.6, §8.6, §9.1, CON-0102 [VALIDATED], CON-0114 [INVALID], CON-0115 [PROVISIONAL], CON-0116 [INVALID] | CON-0114, CON-0115, CON-0116 | §1.14, §1.16, §1.17, §1.6, §5.4, §5.6, §8.6 |
| §9.5 | §0.7, §1.14, §1.16, §1.17, §2.8, §5.5, §6.4, §8.2, §8.4, §9.1, §9.4, CON-0081 [INVALID], CON-0102 [VALIDATED], CON-0108 [VALIDATED], CON-0117 [PROVISIONAL], CON-0118 [INVALID] | CON-0081, CON-0117, CON-0118 | §0.7, §1.14, §1.16, §1.17, §2.8, §5.5, §8.2, §8.4, CON-0081 |
| §9.6 | §0.5, §1.12, §1.13, §1.14, §1.16, §1.6, §6.1, §6.3, §6.5, §8.6, §9.1, §9.3, §9.4, §9.7, CON-0072 [VALIDATED], CON-0087 [VALIDATED], CON-0135 [INVALID], CON-0136 [INVALID], CON-0137 [INVALID], CON-0138 [PROVISIONAL] | CON-0135, CON-0136, CON-0137, CON-0138 | §0.5, §1.12, §1.13, §1.14, §1.16, §1.6, §8.6 |
| §9.7 | §1.10, §1.12, §1.13, §1.14, §1.16, §1.17, §1.6, §5.4, §6.5, §6.6, §8.6, §8.8, §9.6, CON-0134 [PROVISIONAL], CON-0138 [PROVISIONAL], CON-0142 [INVALID], CON-0143 [PROVISIONAL] | CON-0134, CON-0138, CON-0142, CON-0143 | §1.10, §1.12, §1.13, §1.14, §1.16, §1.17, §1.6, §5.4, §8.6, §8.8 |
| §9.8 | §1.12, §1.13, §1.14, §1.16, §1.2, §2.11, §5.7, §6.5, §6.7, §9.7, CON-0134 [PROVISIONAL], CON-0148 [INVALID], CON-0149 [PROVISIONAL] | CON-0134, CON-0148, CON-0149 | §1.12, §1.13, §1.14, §1.16, §1.2, §2.11, §5.7 |
| §9.9 | §1.12, §1.16, §1.3, §1.6, §8.10, §8.11, §8.6, §8.9, §9.7, §9.8, CON-0160 [PROVISIONAL], CON-0161 [INVALID] | CON-0160, CON-0161 | §1.12, §1.16, §1.3, §1.6, §8.10, §8.11, §8.6, §8.9 |

| Concept hosted in this part | Status | Rests on (register fields) | Of which unverified |
|---|---|---|---|
| CON-0072 | VALIDATED | — | — |
| CON-0073 | INVALID | CON-0072, §6.1 | — |
| CON-0074 | INVALID | CON-0075 | — |
| CON-0075 | VALIDATED | CON-0074 | CON-0074 |
| CON-0076 | PROVISIONAL | §5.3, §6.1 | — |
| CON-0085 | INVALID | — | — |
| CON-0086 | INVALID | CON-0042, §6.3 | CON-0042 |
| CON-0087 | VALIDATED | — | — |
| CON-0088 | INVALID | CON-0072 | — |
| CON-0089 | VALIDATED | — | — |
| CON-0099 | VALIDATED | — | — |
| CON-0100 | PROVISIONAL | — | — |
| CON-0102 | VALIDATED | CON-0056 | — |
| CON-0103 | PROVISIONAL | CON-0076 | CON-0076 |
| CON-0104 | VALIDATED | CON-0064 | — |
| CON-0105 | VALIDATED | CON-0100 | CON-0100 |
| CON-0108 | VALIDATED | CON-0092 | — |
| CON-0110 | INVALID | CON-0100, §9.1 | CON-0100 |
| CON-0111 | VALIDATED | CON-0100 | CON-0100 |
| CON-0114 | INVALID | CON-0099, §1.16 | — |
| CON-0115 | PROVISIONAL | — | — |
| CON-0116 | INVALID | §1.17 | — |
| CON-0117 | PROVISIONAL | CON-0102, §0.7, §1.16 | — |
| CON-0118 | INVALID | — | — |
| CON-0132 | PROVISIONAL | CON-0102 | — |
| CON-0133 | INVALID | CON-0087, §1.12 | — |
| CON-0134 | PROVISIONAL | CON-0114 | CON-0114 |
| CON-0135 | INVALID | CON-0133 | CON-0133 |
| CON-0136 | INVALID | CON-0099 | — |
| CON-0137 | INVALID | CON-0132, §1.16 | CON-0132 |
| CON-0138 | PROVISIONAL | CON-0016, §1.14, §1.16, §9.7 | — |
| CON-0139 | INVALID | CON-0117, §1.16, §9.5 | CON-0117 |
| CON-0140 | PROVISIONAL | — | — |
| CON-0142 | INVALID | CON-0138, §1.10, §9.7 | CON-0138 |
| CON-0143 | PROVISIONAL | CON-0136, CON-0138 | CON-0136, CON-0138 |
| CON-0146 | INVALID | — | — |
| CON-0147 | PROVISIONAL | §6.6 | — |
| CON-0148 | INVALID | CON-0134, CON-0138, §1.16, §1.2 | CON-0134, CON-0138 |
| CON-0149 | PROVISIONAL | CON-0148 | CON-0148 |
| CON-0160 | PROVISIONAL | — | — |
| CON-0161 | INVALID | — | — |

## What you hand back
Step 1: the inventory, then wait for the step-2 message. Each report ends with the list of every file you opened and every search you ran (REVIEWER-NOTE item 5).
