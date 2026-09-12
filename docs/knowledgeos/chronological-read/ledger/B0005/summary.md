# B0005 — Extraction Summary

(Persisted by the orchestrator from the agent's returned final message — its own
`Write` call for this file was blocked by the harness with "Subagents should return
findings as text, not write report files"; content is unmodified from what it returned.)

## Files processed
40 / 40 (S0162–S0201). All CONTENT — no FIREWALL-LIMITED files. One path discrepancy: S0183's batch-listed path `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-SUMMARY.md` does not exist; the file was located and read at `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-SUMMARY.md` instead (noted in files.jsonl and read from there).

## Status counts
CONTENT: 40 · FIREWALL-LIMITED: 0

## Contribution counts
193 contribution records total. Per-file range: 2 (S0169, S0170, S0180, S0181, S0182, S0193, S0194, S0195) to 22 (S0164, the 3329-line composite governance-cost/parsing/six-role/durability transcript).

## Scope counts (approximate, self-tallied)
OBJECT ~101 · THEORY-LEVEL ~40 · CROSS-OBJECT ~30 · METHODOLOGICAL ~22.

## Proposed labels (26 total, in index-proposals.jsonl)
knowledgeos-bounded-context-map-v1 · evidence-record-reference-bundle-triad · knowledgeos-kernel-concept · digitalization-robot-concept · business-translator-capability · composition-root-di-principle · kos-aip-gov-state-durability-program · deterministic-assurance-track2-program · six-role-cost-optimization-proposal · knowledgeos-commercial-ip-strategy · four-session-role-model · track2-eks-rule-authority-scope-semantics · knowledgeos-architecture-review-set · eks-current-architecture-baseline · pks-current-architecture-baseline-stage2 · aip-current-architecture-reconstruction-stage3 · ep01-eks-pks-aip-landscape-study · hpa-review-gate-protocol · knowledgeos-epistemic-architecture-investigation · complex-number-knowledge-state-model · observation-runtime-changeset-pipeline · recommendation-decision-outcome-assessment-loop · deferred-architecture-register-mechanism · eks-kernel-extraction-mapping · voting-election-outcome-lifecycle-model · election-operating-core-delegation-map.

Seven carry `relation_to_existing: POSSIBLY:<label>` (routing to unresolved-candidates rather than auto-registering): `knowledgeos-bounded-context-map-v1`→POSSIBLY:bounded-context-map (name collision, almost certainly a different system — KnowledgeOS-product's own 7 contexts vs. the AI-Engineering-Platform estate's BC-1..7 map); `evidence-record-reference-bundle-triad`→POSSIBLY:assurance-model; `kos-aip-gov-state-durability-program`→POSSIBLY:workflow-lifecycle-engine; `six-role-cost-optimization-proposal` and `four-session-role-model`→both POSSIBLY:six-role-model; `knowledgeos-architecture-review-set`→POSSIBLY:knowledgeos-platform; `eks-current-architecture-baseline`→POSSIBLY:architecture-baseline-001; `hpa-review-gate-protocol`→POSSIBLY:producer-verifier-separation; `recommendation-decision-outcome-assessment-loop`→POSSIBLY:recommendation-lifecycle.

## Unknown-object candidates
None. Every contribution was assignable to an existing or clearly-new label. (One draft line briefly set `unknown_candidate` while keeping an ad-hoc label alongside it — the exact mistake a sibling batch made — caught by self-check and corrected to a plain label with `unknown_candidate: null`.)

## Files with a review_flag
S0164 carries two `TYPE-QUESTION` flags (the NEW-5 qualifier-kind divergence; the intra_class_calls/own_class_name_resolution contract collision) — both the source's own unresolved semantic questions reserved to PO/ARB. S0200 carries one `MATH-QUESTION` flag on the ΔK = ΔS + iΔA difference-operator claim.

## Source-claimed lineage (replacement / retraction / contradiction / correction)
- **S0164** (internal): falsifies its own earlier sub-claims mid-transcript (six-role "appears nowhere" contradicted by an untracked C4 diagram; C-14 "absent" contradicted by a live grant; AMD6's quarantine-MOVE justification corrected as "inverted").
- **S0183**: corrects AMD5's inverted quarantine-MOVE reasoning and an acceptance-criterion phrase.
- **S0188 → S0189**: a second EKS-baseline attempt explicitly recommends preferring an earlier (2026-08-01) draft over itself, with two named downgrade corrections.
- **S0191 → S0187**: revises its own prior EKS-kernel-extraction assessment and retracts a "start EP-01 now" recommendation.
- **S0195/S0196**: HPA review corrects a PKS-baseline mis-measurement, withdrawing a claimed contradiction.
- **S0197**: records a v1 launch-prompt framing REJECTED by the Human Principal Architect, retained as labelled history, not deleted.
- **S0199 → S0198**: independent verification upgrades a finding to CONFIRMED-BLOCKING, contradicting the AIP baseline's own completeness and single-writer claims.
- **S0193 → S0192 → S0184**: three successive reviewer-gate refusals, each extending the prior diagnosis, converging on "the missing artefact is one recorded START, not another reviewer."

## Anything the orchestrator should look at
1. S0183's path mismatch (manifest says `reviews/`, actual file is under `architecture/`) — check whether other batches have the same drift for this filename.
2. Three-to-four independent EKS "Current Architecture Baseline" documents exist in this batch alone, with explicit cross-comparisons and downgrades between them (S0188↔S0189) — Phase 3 will need all of them side by side.
3. The DV-correction independence saga (S0184, S0185, S0190, S0192, S0193) is a five-document refusal chain where no review is ever actually performed — worth flagging for operational/governance-friction tracking.
4. S0164 is extremely dense (≥4 distinct governed work items interleaved); 22 contributions were extracted but the AMD3–AMD5 review cycle (as opposed to AMD6 and the DV-correction aftermath) is covered only at the files.jsonl summary level, not contribution granularity — a candidate for deeper mining later.
5. Off-topic PublicDigit content (S0170, S0174) was recorded per corpus policy but contributes nothing to the KnowledgeOS theory — confirm this matches sibling batches' disposition.
