# Batch B0008 — extraction summary

**Files processed:** 40/40 (S0283–S0322), all status CONTENT (no firewalled files).
**Commit:** 39fdef05dc027c6264b6c349a26362a59191a35f

## Status counts
- CONTENT: 40 · FIREWALL-LIMITED: 0

## Provenance counts
- PRIMARY: 30
- SECONDARY-SYNTHESIS: 8 (S0287, S0290, S0292, S0293, S0294, S0301 — formal HPA-review/register documents over raw drafts)
- PROVENANCE-UNRESOLVED: 1 (S0285 — external SEP academic-source text)
- (S0294 is also SECONDARY-SYNTHESIS: the master, continuously-appended Character Definition register)

## Contribution counts
- Total contributions: 129 across all 40 files (every file has >=1).
- `types` distribution: PRINCIPLE 19 · FORMALIZATION 15 · ANALYSIS 14 · DISTINCTION 11 · EXTENSION 10 · GOVERNANCE 9 · CONSTRAINT 7 · CORRECTION 6 · DEFINITION 5 · EXPERIMENTAL-RESULT 5 · VALIDATION 4 · WARNING 3 · RESTATEMENT 3 · IMPLEMENTATION 3 · FUTURE-RESEARCH 3 · INVARIANT 2 · ARGUMENT 2 · AXIOM 2 · CONCEPT 1 · ALTERNATIVE 1 · LIMITATION 1 · HYPOTHESIS 1 · COUNTEREXAMPLE 1 · OPEN-QUESTION 1.
- `scope` distribution: OBJECT 72 · THEORY-LEVEL 38 · METHODOLOGICAL 11 · CROSS-OBJECT 8.

## Proposed labels (8, in index-proposals.jsonl)
z-kos-001-neutral-reference · knowledgeos-constitution-v1 · knowledgeos-reference-architecture-v1 · semantic-compiler-kir-architecture · semantic-normal-form-snf · kos-lens-extraction-methodology · kos-session-bootstrap-mechanism · avidya-detection-mechanism.
None of these appeared in the object-index snapshot (~125 rows, through B0007); each has substantial multi-file evidence in this batch (Z-KOS-001: ~10 files; Semantic Compiler/KIR: ~8 files; SNF: ~5 files; session-bootstrap saga: ~10 files).

## Unknown-object candidates
Two rows (both in S0285, the raw SEP-article source text): Dignaga's canonical-syllogism formalization and the hetu-cakra (wheel-of-reasons) matrix — flagged `UNKNOWN-OBJECT-CANDIDATE` with `candidate_of: ["avidya-detection-mechanism"]` because they are the external-source apparatus behind the KnowledgeOS Nyaya extraction, not themselves asserted as KnowledgeOS objects by that source text.

## Files with review_flag
- S0307 (STAT-QUESTION): an unmeasured "5-20x faster / 95% accurate" architectural estimate, explicitly self-retracted by the same author later in the same file.
- S0310 (STAT-QUESTION): an additive accuracy-improvement projection (74.2%+20%+10%+...->~95%) explicitly rejected in-file by a reviewer as an invalid statistical inference (overlapping gains not accounted for).
- S0315 (TYPE-QUESTION): `recorded_human_start_act` inferred as `state != 'CREATED'`, falsified by a lane cancelled directly from CREATED (no START ever recorded) — a boolean field that is provably false in an edge case, though the system still fails safe.

## Source-claimed lineage (replacement/retraction/contradiction)
- S0307: SOURCE-CLAIMED-RETRACTION — the author's own prior "5-20x faster, 95% accurate" estimate, retracted in the same file ("I should not have presented the... figures as if they had been measured").
- S0299: SOURCE-CLAIMED-REDEFINITION — the migration plan's original quarantine-move rationale ("a move would break the enumeration") is withdrawn and replaced with the opposite, correct reasoning ("QUARANTINE LAUNDERING").
- Many other files in this batch contain in-plan "superseded wording, retained as labelled history" per the migration-plan's own §0.4.4 append-only convention (e.g. S0298/S0299/S0300's DV/RV corrections), but these are corrections *within* one governed document's own revision history, not cross-document lineage claims about a KnowledgeOS theory object, so they were recorded via `contribution_assessment`/`statement` prose rather than as separate `lineage_claims` entries.

## Two/three parallel threads in this batch
1. Epistemic-lens research arc (~22 files): Nyaya/Dignaga logic (S0283/S0284/S0285), Escher (S0286/S0287), Sanskrit grammar v1/v2/word-order (S0290/S0292/S0293/S0295/S0303), the master Character-Definition register (S0294, sections 1-54), the 27-file brainstorming-corpus integration assessment (S0301), the DDD-refinement and Semantic-Compiler/KIR architecture-and-benchmark sub-thread (S0306-S0312: proposal -> CPU prototype -> 120-case KOS-SCB v0.1 benchmark (74.2% accuracy, ~123k parses/sec) -> statistical self-correction -> KOS-SCB v0.2 non-collapse refinement), and the Semantic-Normal-Form (SNF) mathematical-measurement sub-thread (S0317, S0321: rigorous metric definitions, structural-vs-semantic-distance distinction, False-Collapse/False-Divergence-Rate safety metrics, a 100-case synthetic collision pilot).
2. Governance/durability-ADR correction thread (~10 files, S0288/S0289/S0291/S0296/S0298/S0299/S0300/S0302/S0304/S0305): the KOS-AIP-GOV-STATE-DURABILITY migration plan (AMD3-AMD6 + DV correction, S0300, 1440 lines) and its DV-1..DV-7/RV-1..RV-7 finding->repair->re-verification->acceptance chain, PublicDigit engineering-governance process, not KnowledgeOS epistemic theory, but rich in generalizable DDD/governance patterns (producer-bar discipline, quarantine-laundering anti-pattern, evidence-vs-assurance distinction).
3. SESSION-BOOTSTRAP-001 governance saga (~8 files, S0313/S0314/S0315/S0316/S0318/S0319/S0320/S0322): a measured, provider-independent multi-agent coordination-legibility defect, its read-only resolver (AST-017), an independent falsification-style verification (RETURN FOR CORRECTION on three findings), and a multi-step PO/ARB correction-commissioning process that explicitly surfaces and resolves a self-referential governance circularity (V-8) rather than deciding it by implementation fiat.

## Anything the orchestrator should look at
- S0294 (`KnowledgeOS_Character_Definition.md`, 1392 lines, sections 1-54) is the master synthesis register for the entire research arc to date. Sections 1-47 span material largely from batches prior to B0008; sections 48-54 correspond to this batch's own Escher/Sanskrit review files plus citations (not full text, and not in this batch) of Gödel/Quranic/Biblical/Moksha review documents (e.g. `20260822-1245-KOS-EP01-Goedel-Reflection-Lens-Extraction.md`, `20260822-1145-KOS-EP01-Quranic-Epistemology-Lens-Extraction.md`). Downstream synthesis passes should treat S0294 as a secondary register, cross-check its section-by-section claims against whichever batch actually contains those cited primary files, and avoid double-counting.
- S0300 (the 1440-line migration plan) and its five satellite DV/RV-correction documents (S0296/S0298/S0299/S0302 + the bounded review S0291) are pure PublicDigit engineering-governance/DDD content, not KnowledgeOS epistemic theory — recorded for completeness per corpus policy (consistent with the two pre-existing off-topic PublicDigit object-index rows from B0004/B0005). A synthesis pass scoped to "KnowledgeOS theory only" can likely filter this whole sub-thread out via the `gov-state-durability-adr` / `migration-plan-amendment-chain` / `r-conflict-invariant` labels.
- The Semantic Compiler / KIR and SNF sub-threads (S0306-S0312, S0317, S0321) contain the batch's most genuinely novel, falsifiable content (real measured benchmarks, explicit statistical self-corrections, formal metric definitions) and are strong candidates for early promotion/cross-referencing in Phase 2-3 synthesis, since they mark the research arc's first shift from philosophical-lens extraction to actual engineering experimentation.
- The kos-session-bootstrap-mechanism saga (S0313-S0322 minus the SNF-thread files S0317/S0321) is a rich, self-contained worked example of governance-circularity resolution and falsification-style verification methodology; it is PublicDigit/AIP-platform content (not KnowledgeOS epistemic theory per se) but was explicitly flagged in S0301 as "outside the candidate register" for exactly this reason — recorded per corpus policy, consistent treatment recommended.
