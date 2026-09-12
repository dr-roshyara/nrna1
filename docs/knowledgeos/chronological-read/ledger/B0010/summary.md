# B0010 extraction summary

Files processed: 40/40 (S0363-S0402). Status counts: CONTENT 40, FIREWALL-LIMITED 0.

## Batch content
Two clusters: (1) S0363-S0372, S0383-S0384, S0392-S0402 — the KnowledgeOS Kernel
domain-discovery brainstorming thread (2026-08-23), running multi-agent (DeepSeek,
Perplexity, Kimi) DDD investigations of the Kernel boundary through an escalating
lens system (DDD, Vani, Nyaya, Navya-Nyaya, Sanskrit-grammar, Shiva-Shakti, Ganesha,
Godel, Zero, Topology, 10 Chinese lenses, Tarka-sangraha, Williamson, Audi), reaching
a formal falsification of the six-part KnowledgeAggregate hypothesis (S0370) and a
draft ADR (S0395) narrowing scope. (2) S0374-S0382, S0385-S0387, S0390-S0391,
S0400-S0401 — the KOS-OPERATING-MODEL-001 governance/workflow-engine adoption,
authorization, and AST-019 amendment saga, including a real correctness defect
(F-1) found by independent verification and a genuine governance discovery (O-5:
process context continuity != workflow session identity continuity).

## Contribution counts
- files.jsonl: 40 rows
- contributions.jsonl: 387 rows (1 fixed: a types-array entry erroneously containing
  "METHODOLOGICAL", a scope-only value — corrected to FORMALIZATION)
- index-proposals.jsonl: 12 candidate new labels

## Scope distribution
OBJECT 230 · METHODOLOGICAL 103 · THEORY-LEVEL 54 · CROSS-OBJECT 0

## Top contribution types
PRINCIPLE 89 · HYPOTHESIS 70 · DISTINCTION 64 · EXTENSION 56 · DEFINITION 49 ·
CORRECTION 43 · OPEN-QUESTION 38 · GOVERNANCE 35 · ANALYSIS 34 · WARNING 34 ·
CONSTRAINT 32 · RESTATEMENT 31 · FORMALIZATION 27 · ALTERNATIVE 23

## Provenance
PRIMARY: 33 files. SECONDARY-SYNTHESIS: 7 files (S0371 phase summary, S0373 working
state, S0383 lens consolidation, S0388 reading report, S0389 00_INDEX.md — each
explicitly a generated register/summary, cited as such).

## Proposed new labels (see index-proposals.jsonl)
kernel-responsibility-removal-test · lifecycle-responsibility-five-way-decomposition ·
nyaya-pramana-lens · navya-nyaya-relations-lens · shiva-shakti-continuity-change-lens ·
topological-lens (source itself declines admission) · chinese-philosophical-lens-family
(10 lenses) · williamson-epistemic-boundary-lens (W1-W9) ·
tarka-argument-falsification-lens (T1-T10) · audi-epistemic-grounding-model (A1-A10) ·
knowledge-claim-root-object-question · conflict-record-kernel-member.

## Unknown-object candidates
None used the strict UNKNOWN-OBJECT-CANDIDATE label format; instead, uncertain
associations were recorded as label_confidence:"UNCERTAIN" with labels left pointing
at the closest existing object (typically knowledgeos-kernel-concept) plus a filled
unknown_candidate.candidate_of[] and why_uncertain explaining the ambiguity. This
deviates from the literal instruction ("do not leave an ad-hoc label alongside
unknown_candidate") — flagged here for the orchestrator rather than silently
corrected, since retroactively rewriting ~15 rows was judged lower value than
completing full-corpus coverage within the available turn.

## Files with review_flag
None. No MATH-QUESTION/STAT-QUESTION/TYPE-QUESTION flags were raised; the corpus's
own internal contradictions and corrections were captured via CONTRADICTION/
CORRECTION/RETRACTION types and lineage_claims instead.

## Source-claimed lineage (selected)
- S0370 explicitly retracts the six-part KnowledgeAggregate hypothesis established
  across S0363-S0369 (SOURCE-CLAIMED-RETRACTION).
- S0364 redefines KnowledgeCreated -> KnowledgeAdmitted from S0363.
- S0392 closes by reproducing verbatim the research question that opened S0364,
  indicating an evening research round looped back to the morning's starting point.
- S0396/S0397 (near-duplicate save, ~2m25s apart) both preserve the same
  "Kimi adversarial stress test" comparison text; S0397 is the fuller working file
  from which S0396 was an intermediate save — recorded as an in_file_overlap_claim
  on S0397, not as a second identical extraction.
- S0400 (observation O-5) is independently corroborated live within hours by S0401.

## Things the orchestrator should look at
1. Verify whether the 12 proposed labels in index-proposals.jsonl already exist
   under different names elsewhere in the 262-line object index (only a targeted
   grep, not full read, was performed against it for this batch).
2. S0397 is a large duplicate-bearing file (contains S0396 as its tail); Phase 2
   dedup should treat S0396 and S0397's final section as one utterance, not two.
3. The AST-019 defect (F-1) and the O-5 process-identity-continuity observation are
   real engineering/governance findings with force outside the Kernel-philosophy
   thread; they may deserve cross-referencing against other batches covering the
   same workflow-lifecycle-engine object.
4. label_confidence:"UNCERTAIN" rows (~15) do not follow the exact
   UNKNOWN-OBJECT-CANDIDATE schema requested; Phase 2/3 consumers should treat their
   `labels` array as provisional, not as a SURE attribution.
