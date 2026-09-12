# B0004 extraction summary

**Files processed:** 40/40 (S0122-S0161), including 1 image (S0147, PNG diagram, read directly). All read in full per STEP 1-13.

## Status counts
- CONTENT: 40 · FIREWALL-LIMITED: 0

## Provenance counts
- PRIMARY: 33 · SECONDARY-SYNTHESIS: 7 (S0125, S0129, S0130, S0132, S0139, S0140, S0147) · PROVENANCE-UNRESOLVED: 0

## Contribution counts (155 total, spanning 39 of 40 files — S0151 is a byte-identical duplicate with no new content)
- By type (approx.): PRINCIPLE ~30 · WARNING ~28 · ANALYSIS/ARGUMENT ~25 · FORMALIZATION ~18 · CORRECTION ~14 · DISTINCTION ~14 · GOVERNANCE ~12 · CONSTRAINT ~10 · DEFINITION ~10 · LIMITATION ~6 · EXPERIMENT/EXPERIMENTAL-RESULT 5 · VALIDATION 5 · EXTENSION 4 · RESTATEMENT 4 · ALTERNATIVE 2 · COUNTEREXAMPLE 2 · OPEN-QUESTION 2 · HYPOTHESIS 1
- By scope: OBJECT ~65 · THEORY-LEVEL ~45 · METHODOLOGICAL ~35 · CROSS-OBJECT ~10

## Proposed labels (16, in index-proposals.jsonl)
c10-e2-receipt-completeness-model · c10-d5-establishment-criteria · gov-state-durability-adr · b-prime-relocation-decision · r-conflict-invariant · migration-plan-amendment-chain · deterministic-assurance-track2 · cost-optimization-governance-assurance-proposal · knowledgeos-ddd-architecture-v3 · kos-ip-protection-strategy · kos-design-pattern-catalogue · poa-ddd-decision-hierarchy · event-driven-domain-loop · kos-execution-evidence-governance-boundary · knowledge-product-operating-system-v2 · election-only-mode-readiness

Two labels flagged `relation_to_existing: POSSIBLY:capability-c10-knowledge-distribution` (E2 receipt model, D5 establishment criteria) as tightly-coupled sub-artifacts of the registered C-10 capability discovery.

## UNKNOWN-OBJECT-CANDIDATE rows (5)
- S0147 (diagram.png): candidate_of six-role-model / knowledgeos-c4-architecture-diagrams.
- S0154 (lcom4-multi-language-binding.md): 4 contributions candidate_of kos-contract-neutrality-001-fact-model (near-identical L3/L4/L5 "L0-L5 pipeline" vocabulary to the already-registered object).

## Files with review_flag
No files.jsonl row flagged. One contribution (S0141, RD-3-a) carries review_flag=TYPE-QUESTION: two of the plan's own acceptance criteria are jointly unsatisfiable as written.

## Source-claimed lineage
DERIVATION (S0122), EXTENSION (S0134, S0158), REFINEMENT (S0128, S0145, S0149), REPLACEMENT (S0154), CONTRADICTION (S0123), IDENTITY (S0160). No SEPARATION or RETRACTION found in this batch.

## Duplicates / overlap
S0151 is a byte-identical duplicate of S0149 (its own frontmatter says so); zero contributions recorded for it. S0158/S0159 substantially overlap in theme (same external article, same week) but each adds distinct material, so both were extracted; S0159 flagged EXTENDS. S0149/S0151/S0160 appear to be brainstorming precursors to the later formal S0128 ADR — recorded only as an observation, not asserted (their mtime_block is BULK-02, i.e. unordered).

## Off-topic content
S0157 (PublicDigit Election-Only mode readiness) is self-classified `type: noise` in its own frontmatter; recorded per protocol, no KnowledgeOS theory extracted.

## Largest findings for orchestrator attention
- DV-1 (S0142): the one UNSAFE-direction finding across the entire AMD3-AMD6 migration-plan chain.
- 23/30 concurrent-append data loss (S0136): reproducible experiment falsifying a load-bearing plan assumption.
- Phase-0 back-test + mutation test pair (S0143/S0144): falsification methodology for the assurance checker itself, incl. 5 real live defects (V-2) and a defining-vs-quoting false-positive class (V-3).
- 29%->46%->58% mechanical-finding-share trend (S0145): empirical basis for the assurance-automation proposal.

## Notes for later phases
1. Two POSSIBLY-overlap proposals and two UNKNOWN-OBJECT-CANDIDATE clusters need cross-batch resolution.
2. The S0149/S0151/S0160 -> S0128 precursor relationship needs verification against full commit history in a later phase.
3. `migration-plan-amendment-chain` is an extremely dense object (11 files) carrying ~60+ named sub-findings (CL/RC/RD/DI/DV/C series); this ledger samples representative findings per category and the overall verdicts rather than itemizing every one — a targeted re-pass may be warranted if later phases need full enumeration.
