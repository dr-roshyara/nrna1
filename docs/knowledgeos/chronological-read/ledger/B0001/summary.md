# B0001 — Extraction Summary

**Files processed:** 40/40, all status `CONTENT`. No firewalled paths encountered.
**mtime_block:** BULK-01 for every file (order among them is UNORDERED per the batch spec); every file carries at least one explicit internal date (2026-08-02, 2026-08-03, or 2026-08-04), so `order_evidence` was recorded as `INTERNAL-TIMESTAMP` throughout — no STEP-NUMBER series was consistently present across files, and filename datestamps (where present) always matched the body date.

## Status counts
- CONTENT: 40
- FIREWALL-LIMITED: 0

## Provenance counts
- SECONDARY-SYNTHESIS: 31 (discovery/validation/reconciliation/falsification passes reading and reasoning over other artifacts)
- PRIMARY: 9 (e.g. Research Backlog, the MVK bootstrap report, the Operational Evidence Register, the Deferred Architecture Register, the AI Workflow Observation Log — see `files.jsonl` for the exact per-file call)
- PROVENANCE-UNRESOLVED: 0

## Contribution counts
**Total contributions recorded: 474**

By type (top 10 of the closed vocabulary actually used):
ANALYSIS 101 · CORRECTION 85 · OPEN-QUESTION 77 · VALIDATION 63 · GOVERNANCE 45 · DISTINCTION 36 · WARNING 29 · FORMALIZATION 27 · EXPERIMENTAL-RESULT 25 · RESTATEMENT 24
(also used at lower counts: COUNTEREXAMPLE, CONTRADICTION, HYPOTHESIS, RETRACTION, EXTENSION, DEFINITION, PRINCIPLE, LIMITATION, CONSTRAINT, EXPERIMENT, INVARIANT, ARGUMENT, IMPLEMENTATION). Every `types` array was checked against the closed 30-item list before writing; 0 invalid entries found on self-check.

By scope: THEORY-LEVEL 177 · OBJECT 171 · METHODOLOGICAL 82 · CROSS-OBJECT 44

## Proposed labels
19 new working labels proposed in `index-proposals.jsonl` (object index snapshot was empty, so all are genuinely new): `decision-model`, `knowledge-flow`, `pks`, `capability`, `mission`, `meta-model`, `platform-domain`, `provenance`, `knowledge-space`, `runtime-adapter`, `KnowledgeOS`, `ontology`, `model-amnesia`, `bounded-context`, `evidence`, `execution-asset`, `viewpoint`, `governance-viewpoint-hypothesis`, `decision-authority`. Three pairs are flagged `POSSIBLY` related rather than merged, because the corpus itself treats them as distinct-but-unreconciled: `ontology`<->`meta-model` (D-8 is explicitly the pending reconciliation of that split), `bounded-context`<->`platform-domain` (BC-1..BC-6 vs PD-1..PD-6/D-1..D-7 is an explicitly unreconciled pair per D-3/R-4), `governance-viewpoint-hypothesis`<->`viewpoint` (a proposed fifth viewpoint, framing undecided).

## Unknown-object candidates
6 contributions carry `unknown_candidate` (label uncertain), e.g. an `I-9` reference tagged uncertain against an unindexed invariant registry, and a few single ambiguous mentions under `KnowledgeOS`/`meta-model`/`ontology`/`knowledge-space` boundaries. None were merged into an existing label; none were promoted to a new proposal since each is a single ambiguous mention, not a repeated distinct concept.

## Files with review_flag set
None. This corpus is architectural/DDD/governance reasoning; no formula, statistic, or type claim was judged suspicious enough to flag MATH-QUESTION/STAT-QUESTION/TYPE-QUESTION during the accuracy check. (Several documents *themselves* flag prior mathematical/classification errors -- e.g. "level mixing," "flat vs dimensional" -- but these are captured as CORRECTION/RETRACTION contributions with source-claimed lineage, not as review_flags on my own transcription.)

## Source-claimed lineage (replacement / retraction / contradiction, etc.)
This corpus is unusually self-correcting -- most documents open by revising or withdrawing an earlier document's claim. Lineage-claim kinds captured: SOURCE-CLAIMED-CONTRADICTION 23 · SOURCE-CLAIMED-RETRACTION 16 · SOURCE-CLAIMED-REFINEMENT 13 · SOURCE-CLAIMED-EXTENSION 7 · SOURCE-CLAIMED-REDEFINITION 7 · SOURCE-CLAIMED-REPLACEMENT 3 · SOURCE-CLAIMED-SEPARATION 2. Notable chains (recorded as claims only, never adjudicated here):
- I-4 ("a projection is derived and cited as authority by nothing"): stated -> falsified (Cross-Product Validation) -> the falsification itself withdrawn and reclassified as a lifecycle gap (Lifecycle Gap Analysis, Relationship Ontology, Progression Model) -- three documents disagree in sequence about the same claim.
- "Two missions" (Mission Discovery) -> withdrawn inline in the same document, then again in Vision/Mission Clarification.
- The Platform Capability Model, "P1," and a requested "knowledge taxonomy" (all same-day deliverables) -> each found to duplicate a pre-existing artifact (Platform_Capability_Pattern, Round38C-04, RQ-002) in the Engineering Knowledge Landscape sweep.
- "0 traversals" / "0 of 11 enforcing" -- both stated as absolutes in early documents, each later corrected to a qualified form (n≈3 informal; 41 deny/ask controls enforce) in the Phase B Evidence Reconciliation and Architecture Fitness Assessment.
- The MVK bootstrap FAIL verdict -> immediately reframed (same corpus, multiple documents) as invalidating only the extraction boundary, never the platform or the engineering process itself.

## Anything the orchestrator should look at
- Two distinct tracks appear interleaved under one mtime_block. S0029 (Deferred Architecture Register) and S0030 (AI Workflow Observation Log) are dated 2026-08-04 and describe a materially different, later-stage engineering-tooling effort (recommendation engines, IDE/commit triggers, dev-session tooling) than the 2026-08-02/03 KnowledgeOS strategic-ontology corpus dominating the batch. Processed as ordinary batch members with no special status, but the orchestrator should confirm whether they belong to the same chronological arc or a separate later phase before merging timelines.
- `order_evidence` is INTERNAL-TIMESTAMP only for all 40 files, and several documents claim to supersede others by name (e.g. Mission Discovery -> superseded by Vision/Mission Clarification; MVK Validation Report -> refined by Strategic Boundary Consolidation -> refined again by Platform Capability Model §0). These SOURCE-CLAIMED-REPLACEMENT/REFINEMENT chains may not match ingestion order (source_id) and should be re-derived from the claims themselves in Phase 3, not assumed from S-numbering.
- A recurring named defect, "proposing before searching" / "model amnesia," is counted at escalating occurrence numbers across different documents (n=5, 6, 9, 10, 11+) -- these counts are NOT mutually consistent across documents, which is itself worth flagging as a place where the corpus's own bookkeeping disagrees with itself.
- Several concepts are proposed, then in the same file conceded as pre-existing/duplicative, then partially re-derived with a stated correction (e.g. P1, D-2's grounds, I-11, I-4, CONTAINER). These are captured faithfully as sequential contributions rather than collapsed -- Phase 3 will need all of them to reconstruct each concept's true birth point.
