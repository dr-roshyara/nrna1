# Brief, part P2: Ch. 2 and Ch. 3 (whole-book review S0100)

*2026-10-19. Rulebook v1.3 §12c. Read `../REVIEWER-NOTE.md` first; it binds you. Paths below are relative to the lane root `docs/knowledgeos/knowlegeos_manual_theory_20261018/` unless they start with `docs/`, `developer_guide/` or another repository folder.*

## What you review

- **Your part:** Ch. 2 and Ch. 3 of the frozen copy `06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW/`. Its files:
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.00-index.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.00a-what-counts-as-a-rule.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.01-why-rules-need-structure.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.02-the-rule-as-a-formal.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.03-what-a-conflict-is.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.04-most-apparent-contradictions-are-not.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.05-why-priority-numbers-are-rejected.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.06-scale-partitioning-must-be-sound.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.07-where-ai-fits.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.08-scope-silence-and-three-valued-applicability.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.09-how-strongly-a-rule-binds.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.10-the-rules-with-meaning-and.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.11-exceptions-and-the-rule-lifecycle.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/02.99-change-log.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_02/TODO.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.00-index.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.01-dimensions.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.02-derived-attributes.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.03-kinds.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.04-knowledge-and-artifact.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.05-ontology-layers.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.06-relationships.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.07-cross-product-projection.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.08-four-orders.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.09-what-executes.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/03.10-spaces-nested-or-adjacent.md`
  - `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_03/TODO.md`
- **Also in the copy, for context:** `06-theory/BOOK-OUTLINE.md`, `06-theory/CHAPTER-PLAN.md`, `06-theory/THEORY-MAP.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/PRINCIPAL-TESTIMONY.md` (all under `BOOK-UNDER-REVIEW/`), and any other chapter of the copy where your part cites it.
- **Chapter 7** ("The KnowledgeOS loop and its evidence") is listed in `CHAPTER-PLAN.md` as planned. The copy has no `chapter_07/`.
- **Book search:** `python3 -I 06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW/tmp/tools/book_search.py …` only.

## Step 1: the blind sample (fixed before anyone read, seed 20261019)

Read these four sources **before** opening your part, and write the inventory first. Then choose **one further source** from your part's allowed references below, skim it, and name it and your reason in the inventory.

| Source | Why sampled | Path |
|---|---|---|
| S0016 | most cited in this part (31 anchors) | `docs/knowledgeos/KnowledgeOS_Meta_Model.md` |
| S0020 | most cited in this part (23 anchors) | `docs/knowledgeos/KnowledgeOS_Ontology_Architecture_Classification.md` |
| S0030 | drawn at random from the part's other allowed references | `docs/knowledgeos/AI_Workflow_Observation_Log.md` |
| S0013 | drawn at random from the part's other allowed references | `docs/knowledgeos/KnowledgeOS_Architecture_Synthesis_Matrix.md` |

## Your part's allowed references (for quote checks)

You may open the **whole** allowed source behind any anchor your part cites. Nothing outside the allowed list and the grant below.

| Source | Path |
|---|---|
| S0001 | `docs/knowledgeos/KnowledgeOS_Viewpoint_Ownership_Discovery.md` |
| S0003 | `docs/knowledgeos/KnowledgeOS_Governed_Retrieval_Positioning_Validation.md` |
| S0005 | `docs/knowledgeos/KnowledgeOS_Relationship_Validation_Matrix.md` |
| S0007 | `docs/knowledgeos/KnowledgeOS_Architecture_Validation_Matrix.md` |
| S0008 | `docs/knowledgeos/KnowledgeOS_Semantic_Architecture_Reconciliation.md` |
| S0009 | `docs/knowledgeos/KnowledgeOS_Meta_Model_Discovery.md` |
| S0012 | `docs/knowledgeos/KnowledgeOS_Lifecycle_Gap_Analysis.md` |
| S0014 | `docs/knowledgeos/KnowledgeOS_Phase_B_Evidence_Reconciliation.md` |
| S0015 | `docs/knowledgeos/KnowledgeOS_Independent_Review_Phase_B.md` |
| S0017 | `docs/knowledgeos/KnowledgeOS_Engineering_Decision_Model_Discovery.md` |
| S0018 | `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` |
| S0021 | `docs/knowledgeos/KnowledgeOS_Relationship_Ontology.md` |
| S0022 | `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md` |
| S0023 | `docs/knowledgeos/KnowledgeOS_ARB_Decision_Docket.md` |
| S0024 | `docs/knowledgeos/KnowledgeOS_Architecture_Fitness_Assessment.md` |
| S0025 | `docs/knowledgeos/2026-08-02-mvk-bootstrap-validation-report.md` |
| S0026 | `docs/knowledgeos/KnowledgeOS_Ontology_Discovery.md` |
| S0028 | `docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md` |
| S0029 | `docs/knowledgeos/KnowledgeOS_Deferred_Architecture_Register.md` |
| S0031 | `docs/knowledgeos/2026-08-02-knowledgeos-architecture-consolidation.md` |
| S0033 | `docs/knowledgeos/2026-08-02-knowledgeos-strategic-boundary-consolidation.md` |
| S0034 | `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md` |
| S0035 | `docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md` |
| S0043 | `docs/knowledgeos/KnowledgeOS_Operational_Knowledge_Principles.md` |
| S0166 | `docs/knowledgeos/brainstorming/20260819-220924-spring-di-principle-for-kos.md` |
| S0357 | `docs/knowledgeos/brainstorming/20260823-103606-kernel-eight-capacities-k1-k8-and-minimum-kernel.md` |
| S0577 | `docs/knowledgeos/brainstorming/synthesis/KNOWLEDGE-CONCEPT-EXTRACTION-001.md` |
| S0651 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260825-224434_how-can-an-acquired-knowledge-state-be-represented-mathematically.md` |
| S0721 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260826-112128_zero-lens-as-foundational-epistemic-and-architectural-lens.md` |
| S0889 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260827-181825_step-025a-4-executable-knowledge-state-model.md` |
| S1052 | `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-131303_step-150-knowledgeos-runtime-architecture.md` |
| S1524 | `docs/knowledgeos/brainstorming/verification/spec/STEP-VERIFY-141-158.md` |
| S2221 | `docs/knowledgeos/brainstorming/phase_measure_theory/knowledgeos_kernel/prompts/20260901-005729_step_286_hpa-gita-chapter-11-hypotheses-for-knowledgeos-and-especially-the-kernel.md` |
| S2838 | `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-132227_observation-as-entry-point-into-an-unbounded-dimensional-space.md` |

## Granted files (P1 and P2 only)

The Principal grants you read-only access to **exactly** these twelve files, **solely to check quoted material and its meaning** (Ch. 0's short names in brackets where the book uses one). No other file outside the allowed list is covered.

| File | Short name |
|---|---|
| `docs/implementation/KnowledgeOS_Product_Discovery_Charter.md` | Charter |
| `docs/implementation/KnowledgeOS_Development_Constitution.md` | DevConst |
| `docs/implementation/KnowledgeOS_Governance_Execution_Plan.md` | — |
| `docs/implementation/Project_Knowledge_Architecture_Charter.md` | — |
| `docs/implementation/Project_Knowledge_Strategic_Model.md` | PKSM |
| `docs/implementation/RQ-002_Architectural_Synthesis.md` | Synth |
| `docs/implementation/RQ-002_General_Knowledge_Architecture_Constitutional_Model.md` | GKC |
| `docs/implementation/RQ-002_Knowledge_Meta_Model.md` | Meta-Model |
| `docs/implementation/RQ-002_Knowledge_Taxonomy.md` | — |
| `docs/implementation/RQ-002_Project_Knowledge_Domain.md` | — |
| `docs/implementation/RQ-002_Research_Findings_Raw.md` | — |
| `docs/implementation/RQ-002_Strategic_Discovery_Report.md` | — |

**Not covered, and not to be opened:** the `docs/knowledge_tranfer/` files the chapters also cite (V3/v3, EP01, GA, TR, DR-AB, AMS, CF, AB, IM, IA, PC, DR, DE, AG, SI, RS, SC, SD, EV, RM), `developer_guide/` (DG01), the background notes (B-05, B-11, B-12, KT-8) and code files (`*.php`, `*.sh`). A claim resting only on them can be checked for **internal validity only**; its attribution is unverifiable.

## Verifiability of this part (anchor counts per section, from the copy)

Classes: **V** every anchor is to an allowed source or a granted file · **U** anchors only to files that are neither · **M** mixed · **—** no line anchors. Counted by pattern (`KEY Lnn`, `S nnnn Lnn`); an anchor written another way is not counted. **Mark each finding V, U or M by what it rests on**, and say in your report which conclusions are source-verified and which are checked only for internal consistency.

| Section | Allowed S-anchors | Granted-file anchors | Non-granted anchors (keys) | Code anchors | Class |
|---|---|---|---|---|---|
| 02.00-index | 0 | 0 | 1 (RS) | 0 | U |
| 02.00a-what-counts-as-a-rule | 0 | 1 | 11 (RS, SD) | 0 | M |
| 02.01-why-rules-need-structure | 0 | 0 | 5 (CF, GA, RM, V3) | 0 | U |
| 02.02-the-rule-as-a-formal | 0 | 0 | 5 (CF, RM, RS) | 0 | U |
| 02.03-what-a-conflict-is | 0 | 0 | 8 (RM) | 0 | U |
| 02.04-most-apparent-contradictions-are-not | 1 | 0 | 11 (AB, CF, RM, V3) | 0 | M |
| 02.05-why-priority-numbers-are-rejected | 3 | 0 | 2 (RM) | 0 | M |
| 02.06-scale-partitioning-must-be-sound | 0 | 0 | 2 (RM) | 0 | U |
| 02.07-where-ai-fits | 0 | 0 | 4 (AB, IM, RM) | 0 | U |
| 02.08-scope-silence-and-three-valued-applicability | 3 | 1 | 66 (AMS, DE, EV, IA, IM, PC, RM, SC, SD, SI, V3) | 4 | M |
| 02.09-how-strongly-a-rule-binds | 15 | 0 | 18 (DE, IM, PC, RS) | 0 | M |
| 02.10-the-rules-with-meaning-and | 4 | 0 | 5 (RS, SD) | 0 | M |
| 02.11-exceptions-and-the-rule-lifecycle | 1 | 0 | 6 (RS) | 0 | M |
| 03.00-index | 0 | 0 | 0 | 0 | — |
| 03.01-dimensions | 21 | 1 | 0 | 0 | V |
| 03.02-derived-attributes | 9 | 0 | 0 | 0 | V |
| 03.03-kinds | 14 | 0 | 0 | 0 | V |
| 03.04-knowledge-and-artifact | 9 | 0 | 0 | 0 | V |
| 03.05-ontology-layers | 19 | 0 | 0 | 0 | V |
| 03.06-relationships | 18 | 0 | 0 | 0 | V |
| 03.07-cross-product-projection | 3 | 0 | 0 | 0 | V |
| 03.08-four-orders | 6 | 0 | 0 | 0 | V |
| 03.09-what-executes | 47 | 0 | 0 | 0 | V |
| 03.10-spaces-nested-or-adjacent | 38 | 0 | 0 | 0 | V |
| **total** | 211 | 3 | 144 | 4 | |

## Changed theory (Rulebook §2 step 9)

Text changed at the two previous settlements (theory turn 4 and window C18), as it stands in the copy. Review it like the rest of the part.

- None: no text of Ch. 2 or Ch. 3 was changed at the last two settlements.

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
| §2.1 | §0.1 | — | §0.1 |
| §2.2 | §0.2, §1.14, §1.2, §2.3, §2.5, §2.8 | — | §0.2, §1.14, §1.2 |
| §2.3 | §2.11 | — | — |
| §2.4 | §1.10, §1.13, §1.14, §1.3, §1.6, §1.9, §2.2, §2.3, §2.5, CON-0062 [INVALID] | CON-0062 | §1.10, §1.13, §1.14, §1.3, §1.6, §1.9, CON-0062 |
| §2.5 | §1.10, §1.11, §1.6, §2.10, §2.3, §2.8, CON-0040 [INVALID] | CON-0040 | §1.10, §1.11, §1.6, CON-0040 |
| §2.6 | — | — | — |
| §2.7 | §1.16, §1.6, §2.8 | — | §1.16, §1.6 |
| §2.8 | §0.7, §1.11, §1.12, §1.13, §1.17, §1.2, §1.5, §1.6, §1.8, §2.11, §2.3, §2.5, §2.9, §5.4, CON-0063 [VALIDATED], CON-0082 [PROVISIONAL], CON-0083 [PROVISIONAL] | CON-0082, CON-0083 | §0.7, §1.11, §1.12, §1.13, §1.17, §1.2, §1.5, §1.6, §1.8, §5.4, CON-0063, CON-0082, CON-0083 |
| §2.9 | §1.12, §1.14, §1.9, §2.10, §2.11, §2.3, §2.8, §3.6, CON-0034 [INVALID] | CON-0034 | §1.12, §1.14, §1.9 |
| §2.10 | §0.1, §1.12, §1.9, §2.5, §2.8, §2.9, CON-0055 [INVALID], CON-0056 [VALIDATED] | CON-0055 | §0.1, §1.12, §1.9, CON-0055, CON-0056 |
| §2.11 | §1.12, §1.2, §1.6, §1.9, §2.2, §2.8, CON-0061 [INVALID] | CON-0061 | §1.12, §1.2, §1.6, §1.9, CON-0061 |
| §3.1 | §0.3, §1.2, §1.8, §2.9, §3.2, §3.3, CON-0001 [VALIDATED], CON-0002 [VALIDATED], CON-0003 [INVALID], CON-0004 [PROVISIONAL], CON-0005 [VALIDATED], CON-0006 [INVALID] | CON-0003, CON-0004, CON-0006 | §0.3, §1.2, §1.8 |
| §3.2 | §1.2, §3.1, §3.3, CON-0007 [INVALID], CON-0009 [INVALID] | CON-0007, CON-0009 | §1.2 |
| §3.3 | §3.1, §3.4, CON-0008 [INVALID], CON-0011 [INVALID] | CON-0008, CON-0011 | — |
| §3.4 | §1.17, §1.5, §2.8, §3.5, CON-0010 [INVALID] | CON-0010 | §1.17, §1.5 |
| §3.5 | §1.10, §1.14, §1.6, §2.8, §3.10, §5.1, CON-0029 [INVALID], CON-0030 [INVALID], CON-0031 [INVALID], CON-0032 [INVALID], CON-0033 [INVALID] | CON-0029, CON-0030, CON-0031, CON-0032, CON-0033 | §1.10, §1.14, §1.6, §5.1 |
| §3.6 | §0.9, §1.1, §1.14, §2.5, §2.9, §3.1, §3.2, §3.3, §3.4, §3.5, §3.7, §6.1, CON-0011 [INVALID], CON-0034 [INVALID], CON-0035 [INVALID], CON-0036 [INVALID], CON-0037 [INVALID], CON-0038 [VALIDATED], CON-0039 [INVALID], CON-0041 [INVALID], CON-0042 [INVALID], CON-0057 [INVALID] | CON-0011, CON-0034, CON-0035, CON-0036, CON-0037, CON-0039, CON-0041, CON-0042, CON-0057 | §0.9, §1.1, §1.14, §6.1 |
| §3.7 | §1.11, §1.14, §1.6, §3.5, §3.6, CON-0034 [INVALID], CON-0039 [INVALID], CON-0041 [INVALID], CON-0043 [INVALID], CON-0044 [INVALID], CON-0045 [INVALID], CON-0046 [INVALID], CON-0047 [VALIDATED] | CON-0034, CON-0039, CON-0041, CON-0043, CON-0044, CON-0045, CON-0046 | §1.11, §1.14, §1.6 |
| §3.8 | §1.17, §1.5, §3.3, §3.4, §3.9, §5.3, CON-0039 [INVALID], CON-0064 [VALIDATED], CON-0065 [INVALID], CON-0066 [INVALID], CON-0067 [INVALID] | CON-0039, CON-0065, CON-0066, CON-0067 | §1.17, §1.5, §5.3 |
| §3.9 | §1.10, §1.5, §1.9, §3.4, §3.8, §6.1, CON-0039 [INVALID], CON-0066 [INVALID], CON-0090 [PROVISIONAL], CON-0091 [INVALID], CON-0092 [VALIDATED], CON-0093 [VALIDATED], CON-0094 [PROVISIONAL], CON-0095 [PROVISIONAL] | CON-0039, CON-0066, CON-0090, CON-0091, CON-0094, CON-0095 | §1.10, §1.5, §1.9, §6.1 |
| §3.10 | §1.11, §3.3, §3.4, §3.6, §3.8, §6.1, §6.3, CON-0011 [INVALID], CON-0096 [INVALID], CON-0097 [PROVISIONAL], CON-0098 [PROVISIONAL] | CON-0011, CON-0096, CON-0097, CON-0098 | §1.11, §6.1, §6.3 |

| Concept hosted in this part | Status | Rests on (register fields) | Of which unverified |
|---|---|---|---|
| CON-0001 | VALIDATED | §0.3, §3.1 | — |
| CON-0002 | VALIDATED | §0.3, §3.1 | — |
| CON-0003 | INVALID | §3.1 | — |
| CON-0004 | PROVISIONAL | §0.3, §2.8, §3.1 | — |
| CON-0005 | VALIDATED | §3.1 | — |
| CON-0006 | INVALID | §3.1 | — |
| CON-0007 | INVALID | §3.2 | — |
| CON-0008 | INVALID | §3.3 | — |
| CON-0009 | INVALID | §3.2 | — |
| CON-0010 | INVALID | §3.4, §3.5 | — |
| CON-0011 | INVALID | §3.3 | — |
| CON-0029 | INVALID | CON-0030 | CON-0030 |
| CON-0030 | INVALID | CON-0029 | CON-0029 |
| CON-0031 | INVALID | — | — |
| CON-0032 | INVALID | — | — |
| CON-0033 | INVALID | — | — |
| CON-0034 | INVALID | — | — |
| CON-0035 | INVALID | — | — |
| CON-0036 | INVALID | — | — |
| CON-0037 | INVALID | — | — |
| CON-0038 | VALIDATED | — | — |
| CON-0039 | INVALID | §1.1 | — |
| CON-0041 | INVALID | — | — |
| CON-0042 | INVALID | CON-0011 | CON-0011 |
| CON-0043 | INVALID | CON-0042 | CON-0042 |
| CON-0044 | INVALID | CON-0026 | CON-0026 |
| CON-0045 | INVALID | CON-0033 | CON-0033 |
| CON-0046 | INVALID | CON-0037 | CON-0037 |
| CON-0047 | VALIDATED | — | — |
| CON-0057 | INVALID | — | — |
| CON-0064 | VALIDATED | — | — |
| CON-0065 | INVALID | CON-0034 | CON-0034 |
| CON-0066 | INVALID | CON-0002 | — |
| CON-0067 | INVALID | CON-0012 | CON-0012 |
| CON-0090 | PROVISIONAL | CON-0066 | CON-0066 |
| CON-0091 | INVALID | CON-0039, CON-0095, §3.9 | CON-0039, CON-0095 |
| CON-0092 | VALIDATED | — | — |
| CON-0093 | VALIDATED | — | — |
| CON-0094 | PROVISIONAL | CON-0093 | — |
| CON-0095 | PROVISIONAL | CON-0091 | CON-0091 |
| CON-0096 | INVALID | CON-0011, CON-0098, §6.3 | CON-0011, CON-0098 |
| CON-0097 | PROVISIONAL | CON-0011 | CON-0011 |
| CON-0098 | PROVISIONAL | CON-0096, §6.3 | CON-0096 |

## What you hand back
Step 1: the inventory, then wait for the step-2 message. Each report ends with the list of every file you opened and every search you ran (REVIEWER-NOTE item 5).
