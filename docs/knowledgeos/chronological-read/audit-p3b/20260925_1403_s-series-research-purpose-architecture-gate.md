# S-Series Research Purpose & Architecture Gate (analysis only; human decision)

**Scope:** S-Series only. **Production frozen:** OB0004 FAILED; no batch authorized; H-19 SEALED; S5c PROHIBITED. **OB0018 not executed.** No contract, population or methodology changed.

**Labels:** every conclusion is tagged **OBSERVED** (read directly from an artifact or measurement), **INFERRED** (derived from observed facts), **HYPOTHESIS** or **UNRESOLVED**.

**Main sources:**
- frozen protocol v1.7 `prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md`: §1, §1A–1E, §9C.1, §9E, §11.4, §19.4, §23, §26, §31;
- S5 plan v2.3.2;
- `03-CONTRIBUTIONS.jsonl`, `P3B-SAMPLE-PLAN.jsonl`;
- G-LOG-0047…0054;
- the pilot report and the post-pilot brief.

## 1. Current S-Series objective

- **OBSERVED (§1).** P3b's purpose has two halves.
  - (i) To "**reconstruct** what the KnowledgeOS theory actually says and how it develops", **and** (ii) to "discover what it contains that is not yet known": structures, contradictions, gaps, hypotheses.
  - Inside that, P3b "completes Phase 3 of Master Protocol v3.5": the **per-object roll-up for every object label**. "The roll-up is the **controlled floor** of P3b, not its ceiling" (D-27).
- **OBSERVED (§1A, §13, §31).** The research register is "the **primary discovery channel**". v1.3 added cross-object and corpus passes because "theory may emerge only across objects".
- **INFERRED.** The frozen objective is **dual**: a reconciliation floor (reconstruction) plus research on top of it. The human's current statement ("primarily research-oriented") re-weights these; the frozen text does not.

## 2. Historical reconstruction vs research: three requirements

| Requirement | What it asks | Reading depth the frozen text implies | Tag |
|---|---|---|---|
| **A. Discovery** | what might be interesting | candidate generation is **mechanical** from existing layers (§9E step 1): P2a groups, notations, contribution-row type signatures; plus the register | OBSERVED (§9E.1 table) |
| **B. Evidence verification** | what must be inspected before a substantive claim | whole-file reading of the files a claim rests on (§11.2, §11.4: "a FOUND always needs a whole-file reading"); for cross-object findings "whole files are read only to check a specific candidate. **This is not a corpus re-read** (D-04)" | OBSERVED (§9E inputs, §11.4) |
| **C. Historical reconstruction** | what must be read to reconstruct development without hindsight | chronological, per label: whole-file reading of every birth, change or contradiction source (OMQ-14); two-stage absence search over every stage-1 hit file, never thinned (§11.4, §19.4) | OBSERVED (§9.8, §11.4, §19.4) |

- **INFERRED.** The three do **not** require the same depth. **Only C requires exhaustive whole-file reading of every label's evidence.** A is mechanical. B is claim-scoped.

## 3. What S5 currently optimizes

- **OBSERVED (§23).** The terminal predicate requires **every Tier-U and Tier-Z label ACCEPTED** (or BLOCKED), plus the complete S5a and S5b passes.
- **OBSERVED (§9C.1; `P3B-SAMPLE-PLAN.jsonl`).** Deep research (the 23-question checklist) is **already sampled, not exhaustive**: 567 of 1,975 labels, a purposive set plus a stratified random sample, "so the method can still find what we did not expect" (§31).
- **OBSERVED (§9E.1).** S5a generators: G-SHARED-GROUP, G-NOTATION and G-TYPE-SIM use **pre-S5 data** (P2a groups, notations, P1 type signatures). G-DEPENDENCY and G-COCHANGE need **S5 object records**.
- **INFERRED.** S5 optimizes **completion of the exhaustive roll-up floor** (reconstruction C) for all labels. That is a **v3.5 Phase-3 obligation** (§1); v3.5 "is never edited" (§26.4). Research (A and B) is layered on that floor. The per-label whole-file obligation, the never-thinned absence procedure and the all-labels terminal predicate are **inherited from the reconstruction/reconciliation mandate**. Research does not require them as such.

## 4. What OB0004 actually demonstrated

- **OBSERVED (G-LOG-0051; post-pilot brief).**
  - One agent context completed about 1.09 MB of page-proven whole-file reading and could not finish OB0004's 1.91 MB.
  - The largest required **text** file is 241,117 bytes. **No text page exceeds 30 KB.**
  - Only 12 of 2,558 required files are binary.
- **INFERRED.**
  - OB0004 demonstrated a **label/batch-level analytical capacity limit** for exhaustive roll-up reading.
  - It did **not** show that large files cannot be read: file-level capacity is sufficient for all text.
  - It **did** expose a binary-content handling problem (S2276) and the delivery ≠ reading gap.

## 5. What the decomposition pilot actually demonstrated

- **OBSERVED (G-LOG-0053).** Established: mechanical completeness, resumability, provenance and verifier compliance.
- **Not established:** general epistemic equivalence, cross-unit calibration, preservation of cross-unit dependency reasoning, and preservation of change-class reasoning. The four losses stand; they were verified again from stored records in G-LOG-0054.

## 6. Why the four information losses matter

- **INFERRED.** Change classes, calibration, disposition consistency and dependency edges are **exactly the inputs** of reconstruction (timeline, births) and of two S5a generators (G-DEPENDENCY edges, G-COCHANGE change points).
- Under Strategy A they propagate into cross-object research. Under a research-first design they matter wherever a claim depends on cross-file relations, but only for the labels a claim touches.

## 7. Why 82 labels remain a capacity issue

- **OBSERVED.** 82 labels need more than 1.09 MB of text reading (maximum 7.1 MB).
- **INFERRED.** Under exhaustive roll-up (Strategy A) these need decomposition or another capacity mechanism. Under Strategy B they need deep reading only when a research question selects them, and still decomposition if selected.

## 8. Is exhaustive deep label processing required before research discovery can begin?

- **OBSERVED.** Under the **frozen** protocol, the terminal predicate and §9E make S5 object records inputs to part of S5a (DEPENDENCY, COCHANGE) and to all register work.
- **OBSERVED.** Discovery substrate already exists without S5:
  - 27,906 P1 contribution rows over 2,724 source files, with statement, types, invariants, assumptions, dependencies, type_signature and experiment;
  - P2a families (`20-FAMILIES/_derived.json`);
  - 1,792 P3a reconciliation pairs;
  - the stage-1 discovery search.
- **INFERRED.** Exhaustive deep processing is required to **complete the frozen P3b** (v3.5 Phase 3). It is **not logically required for research discovery to begin**: 3 of 5 S5a generators and a broad-extraction substrate operate on pre-S5 layers.
- **UNRESOLVED.** Whether research discovery **without** the reconstructed layer-A records loses anti-hindsight protection. §1B rule 3: "Every register record points to the layer-A records … it rests on"; §14 hindsight controls operate on layer A.

## 9. Research-first alternative (R0–R4), evaluated conceptually

| Property | R0 provenance / R1 broad extraction / R2 targeted deep / R3 verification / R4 formal test | Tag |
|---|---|---|
| **Provenance** | compatible: S-ids, identity manifest, page-hash reading ledger all reusable | INFERRED |
| **Reproducibility** | compatible if R2 triggers are **registered** (question → files chosen by a recorded rule, not by judgment) | INFERRED |
| **Anti-hindsight** | **at risk**: without chronological layer-A reconstruction, a research question formed with hindsight could select and read evidence selectively. Mitigation candidates: R2 must read each selected label's evidence **chronologically and whole** (layer-A records for the touched labels), plus the §13.10 disconfirmation searches | HYPOTHESIS |
| **Auditability** | compatible: READ-COVERAGE and the audit apply per touched label | INFERRED |
| **Negative evidence** | **weaker**: "not found" needs a defined census; R1 extraction is not a census. Absence claims would require an R2-level absence search per claim | INFERRED |
| **Uncertainty** | compatible (layers B/C, epistemic classes) | INFERRED |
| **Research integrity / degrees of freedom** | **at risk**: question-driven selection raises researcher degrees of freedom. The frozen design's matched blind controls and stratified samples (§9C.1, §9E.2) exist precisely against this, and would need to be carried over | HYPOTHESIS |

## 10. Strategy A vs Strategy B (not ranked)

| Dimension | A: exhaustive deep label processing, then research | B: broad discovery, then targeted deep evidence, then research/testing |
|---|---|---|
| **Discovery power** | limited by throughput; cross-object passes start only after roll-up | earlier and wider; limited by R1 extraction quality (P1 rows are AI-produced) |
| **Research recall** | high for structures visible in object records | depends on R1 coverage and triggers; unknown |
| **False negatives** | low within scope (never-thinned absence procedure) | higher for things R1 misses; unknown rate |
| **Evidence strength** | uniform; every label reconciled | claim-scoped; strong where verified, absent elsewhere |
| **Scalability** | capacity-bound (82 labels need decomposition; 396 batches) | scales with the number of questions |
| **Cost** | high and front-loaded (every batch read whole) | lower up front; cost moves to verification |
| **Auditability** | uniform gates | per claim; needs registered triggers |
| **Provenance** | full | full (R0) |
| **Reproducibility** | deterministic batches | depends on trigger registration |
| **Hindsight risk** | low (chronological layer A first) | higher unless R2 reconstructs chronologically |
| **Researcher degrees of freedom** | constrained (fixed population, blind controls) | larger; needs controls carried over |
| **Mathematical research** | structures from every label's formal rows | formal candidates (G-TYPE-SIM, formulas) directly from P1 rows; verification per candidate |
| **Statistical research** | population-level statistics valid (complete census) | population statistics need a sampling frame; claim-level tests fine |
| **DDD / architecture research** | boundary candidates from every label's dependencies | candidates from P2a families, pairs and notations; edges verified per claim |

**Evidence needed to choose (UNRESOLVED):**
- the recall of B relative to A on the same labels;
- the evidence strength of B's verified claims;
- the rate of hindsight and selection problems under B's triggers;
- the cost per verified finding under each.

## 11. Claim-dependent evidence depth (principle validated against the frozen text)

- **OBSERVED.** The frozen protocol **already** scales evidence to claims in several places:
  - §11.2 minimum evidence per status;
  - §11.4 "a FOUND always needs a whole-file reading";
  - §9E whole files read "only to check a specific candidate";
  - §13.10 disconfirmation for serious hypotheses;
  - §13.11 a formal standard for structure candidates;
  - "observation may remain observation" (§13.9).
- **INFERRED.** The principle is consistent with the methodology. What is frozen as **uniform** is only the reconstruction floor for all labels.

| Claim | Depth consistent with the frozen evidence rules | Tag |
|---|---|---|
| a term occurs | R1 row plus provenance (S-id, anchor) | INFERRED |
| a concept is defined in a file | whole-file reading of that file (§11.4 FOUND rule) | OBSERVED rule |
| two concepts are related | whole-file reading of both, comparative (§9E step 2) | OBSERVED rule |
| a mathematical structure exists | the formal standard §13.11 plus source reading of the participants | OBSERVED rule |
| a theory candidate follows | corpus-scale recurrence across independent sets, counter-evidence, predictions (§9E.3) | OBSERVED rule |
| a hypothesis is supported | a registered test (TEST-DEFINED → the test pass) | OBSERVED rule |
| **X is absent / undefined** | a census plus whole reading of every candidate hit (§11.4 two stages) | OBSERVED rule; **the most expensive claim** |

## 12. What OB0018 would actually prove

- **INFERRED.**
  - If decomposition reproduces single-context analysis on OB0018, that supports **architecture 1, 2 or 3 for executing exhaustive roll-up (Strategy A)** on the 82 heavy labels.
  - If it does not, the architecture choice shifts (consistency pass, re-reads or another mechanism) **within Strategy A**.
- **INFERRED.** Either result changes the **engineering method for exhaustive label processing**. Neither changes the research methodology.
  - Its secondary value: decomposition is also needed under Strategy B whenever R2 selects a heavy label. So its fidelity result is **not irrelevant** to B, but it is not central there.

## 13. Should OB0018 run now?

- **INFERRED.** Its value depends on a prior decision: whether the exhaustive roll-up floor (v3.5 Phase 3, frozen terminal predicate) remains the S-Series obligation.
  - **If yes:** OB0018 answers a necessary methodological-engineering question.
  - **If the human re-scopes toward research-first:** OB0018 is premature.
- **OBSERVED.** Re-scoping touches frozen v1.7 (§23, §26) and the v3.5 Phase-3 mandate ("never edited", §26.4). That is a human governance act, not an implementation choice.

## 14. Smallest discriminating experiment (if the human wants evidence before choosing)

- **HYPOTHESIS design; not executed.** A **strategy-comparison probe on labels already analysed**, so no new Strategy-A work is needed:
  - **Labels:** the two pilot labels `knowledgeos-architecture-constitution-v01` and `step-verify-programme`. Their Strategy-A-style research registers exist and are audited: `pilot-s5-decomp/PX0004-S01`, `-S02`.
  - **Strategy B arm:** one agent works from R1 only (the labels' P1 contribution rows, P2a families, pairs and notations; no whole files). It proposes candidate findings and registers R2 triggers. A second agent performs R2/R3 on the triggered files only, with paged whole-file reading.
  - **Comparison (pre-registered):** an independent auditor matches findings between arms and dispositions each as found-by-both / A-only / B-only / B-false.
  - **Metrics:** the recall of B relative to A; B's unique verified findings; B's false-finding rate; bytes read per verified finding; hindsight or selection issues (§13.10, §14 checks).
- **Decision enabled:** evidence on whether broad-first discovery loses material findings that exhaustive processing finds. That is the core question for re-scoping.
- **Cost:** about 2 agents plus 1 auditor; non-production namespace.
- **Limits:**
  - two labels, one of them small;
  - Strategy-A registers from the pilot are one analyst's view;
  - same model family.

## 15. Human decisions required

1. **Objective:** does the S-Series retain the frozen dual objective (exhaustive reconstruction floor **and** research), or re-weight toward research-first? If it re-weights, how is the v3.5 Phase-3 roll-up obligation handled (§26.4)? For example: kept as a separate reconstruction track, deferred, or scoped to the labels research touches.
2. **Gate selection** among:
   - **A:** proceed with OB0018. Supported if the exhaustive floor stays.
   - **B:** redesign around broad discovery plus targeted evidence first. Supported if research-first is adopted; requires a §26 revision and a v3.5-level decision.
   - **C:** run the §14 strategy-comparison probe first. Supported if the human wants recall and evidence data before re-scoping.
   - **D:** start research on the pre-S5 layers now (the three pre-S5 S5a generators, register work from P1 rows) as a **separate non-production track**, while the roll-up question stays open. Supported by §8: the substrate exists. Its hindsight and negative-evidence limits (§9) must be declared.
3. Independent of the gate: the binary policy, byte-bounded paging and NOT-CONSUMED-ESCALATED (post-pilot brief §E–§G).

**State:** analysis only. OB0018 not executed; production frozen; H-19 SEALED; S5c PROHIBITED.
