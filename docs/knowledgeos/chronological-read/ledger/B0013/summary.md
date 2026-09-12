# B0013 Extraction Summary (RETRY)

**Files processed:** 40/40 (S0493-S0530, S0534, S0535). All `status: CONTENT` (no FIREWALL-LIMITED).

**Provenance:** PRIMARY 37, SECONDARY-SYNTHESIS 2 (S0507, S0508 - multi-lens/multi-agent reviews), PROVENANCE-UNRESOLVED 1 (S0497, verbatim pasted external Medium article).

**Contributions:** 632 total. Top types: FORMALIZATION 226, PRINCIPLE 131, DISTINCTION 121, CORRECTION 108, EXAMPLE 86, DEFINITION 82, EXTENSION 70, WARNING 54, RESTATEMENT 50, HYPOTHESIS 47, GOVERNANCE 30, OPEN-QUESTION 27, CONCEPT 15, FUTURE-RESEARCH 14, INVARIANT 14, CONSTRAINT 13, VALIDATION 7, ARGUMENT 7, LIMITATION 6, EXPERIMENT 5, ANALYSIS 4, RETRACTION 3.

**Scope:** THEORY-LEVEL 557, METHODOLOGICAL 54, CROSS-OBJECT 15, OBJECT 6.

**Proposed labels (index-proposals.jsonl, 19 total):** sankhya-consciousness-multi-lens-reference-model, hofstadter-geb-levels-strange-loop-lens, nasadiya-sukta-epistemic-boundary-lens, vedic-time-quantum-analogy-source-article, pramana-six-source-epistemic-acquisition-model, catuskoti-multivalued-epistemic-logic-lens, shannon-weaver-information-theory-lens, knowledgeos-ontology-discovery-multi-agent-review, knowledgeos-kernel-boundary-epistemic-infrastructure-synthesis, merricks-eliminativist-ontology-lens, mcginn-logical-properties-lens, oconnor-persons-causes-agency-lens, information-theory-formal-bounds-lens, knowledge-space-kernel-research-roadmap, floridi-philosophy-of-information-lens, dretske-information-flow-knowledge-lens, fagin-halpern-moses-vardi-epistemic-logic-lens, gardenfors-knowledge-in-flux-lens, searle-social-reality-institutional-facts-lens.

**Unknown-object candidates:** ~76 contributions across S0493-S0505 use `labels:["UNKNOWN-OBJECT-CANDIDATE"]` where no plausible label existed at all (new lenses/objects with `candidate_of` empty or tentative). Separately, ~90+ other contributions across many files use `label_confidence: UNCERTAIN` paired with a real (existing/proposed) label plus a filled `unknown_candidate` (documenting doubt about whether this is truly the same recurring object, e.g. the ~8 unreconciled "Knowledge State" tuples). **Flag for orchestrator:** these two usages of `unknown_candidate` differ (sentinel-only vs. real-label-plus-uncertainty); both are internally consistent and deliberate, but the orchestrator should confirm this dual convention matches the canonical contract intent.

**review_flag:** only S0502 has one (`TYPE-QUESTION`) - an unresolved EKI-08 ID collision: S0501 assigns EKI-08 to "Modal Integrity" and S0502 assigns EKI-08 to a different invariant "Epistemic Type Preservation" on the same day; neither file resolves the other.

**Source-claimed lineage (`lineage_claims`, 85 entries across ~35 files):** dominant kinds are SOURCE-CLAIMED-REFINEMENT, -EXTENSION, -CORRECTION, -IDENTITY, -REPLACEMENT; 3 SOURCE-CLAIMED-RETRACTION (S0513, S0526x2); 3 SOURCE-CLAIMED-CONTRADICTION (S0524 x2, S0525). Notable chain: S0523 (Fagin lens) -> S0524/S0525 (critical review, self-contradicts parts of S0523) -> S0526 (full acceptance/retraction of overclaims) -> S0527 (targeted verification) -> S0528 (Gardenfors) -> S0529/S0530 (projection-model synthesis) -> S0534 (Searle institutional layer) -> S0535 (knowledge-as-capacity synthesis).

**In-file overlap claims:** S0518 COPIES S0516 (byte-identical, only unique first ~700 lines extracted); S0508's "chatgpt" section COPIES its own "perplexity" section (not double-extracted); ~20 other files marked EXTENDS chains to same-day predecessor files (not asserted as birth/replacement, only recorded from source's own framing/references).

**Two schema violations found and fixed during self-check** (leftover from earlier in this same retry session, before this compaction point): 21 contributions had `"METHODOLOGICAL"` incorrectly present in `types` (removed; one entry, S0525 claim-strength convention, was retyped to `DISTINCTION` since it had no other type); 13 contributions had `scope:"GOVERNANCE"` (an invalid scope value; corrected to `METHODOLOGICAL`, since all 13 were research-methodology "decline to freeze / next-steps" statements). Post-fix, `types` and `scope` values are fully within the closed 30-item/4-item enums (verified programmatically).

**Files with notable corruption:** S0524 is a garbled terminal-scrollback capture (mid-word corruption e.g. "Knowledge Spacrated") but still fully readable/extractable; processed as CONTENT, corruption noted in its summary.

**Orchestrator should look at:** (1) the dual unknown_candidate convention noted above; (2) the EKI-08 naming collision (S0501 vs S0502, unresolved by source); (3) the ~8 unreconciled competing "Knowledge State" formal tuples across the batch, never reconciled by the corpus itself; (4) S0497's PROVENANCE-UNRESOLVED classification (pasted external article, not project reasoning).
