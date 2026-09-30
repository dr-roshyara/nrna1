# analytical-lineage-object

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AnalyticalLineage`, `EvidenceEpisode`
**Aliases:** `analysis/selection/publication provenance`
**Candidate group membership (NOT an identity claim):**
- G0076: [`analytical-lineage-object` · `situational-provenance`] — explicit agent-stated uncertainty: 'analytical-lineage-object' POSSIBLY relates to 'situational-provenance' (batch B0012). Note: Extends the source+acquisition provenance model with Selection/Transformation/Analysis/Exclusion/Publication provenance, motivated by the replication-crisis anti-pattern (p-hacking, optional stopping, HARKing, subgroup slicing); proposes AnalyticalLineage (Raw Dataset->Cleaning->Exclusions->Transformation->Subgroup selection->Statistical test->Model->Result->Claim) and EvidenceEpisode (original acquisition/replication/contradiction/extension/failed replication/synthesis) so a claim accumulates a reconstructible evidence history rather than a single overwritten result.
- G0078: [`analytical-lineage-object` · `epistemic-lineage-object`] — explicit agent-stated uncertainty: 'epistemic-lineage-object' POSSIBLY relates to 'analytical-lineage-object' (batch B0012). Note: Extends provenance with search-path/analytical-path lineage (question, hypotheses, search paths, sources, evidence, transformations, analysis, alternatives, selection, conclusion, confidence) so that an AI agent's hidden path-selection process (which queries/documents/interpretations were tried and rejected) is preserved, not just the final answer; paired with InferenceFrame (target, conditioning variables, population, time window, assumptions, perspective, purpose) so a claim under one conditioning frame is not automatically equivalent to the same claim under another -- 'no context-free inference.'
- G0963: [`analytical-lineage-object` · `epistemic-lineage-object`] — working_label token overlap Jaccard=0.50 (shared tokens: ['lineage', 'object'])

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0012, scope OBJECT (relation_to_existing: POSSIBLY:situational-provenance): Extends the source+acquisition provenance model with Selection/Transformation/Analysis/Exclusion/Publication provenance, motivated by the replication-crisis anti-pattern (p-hacking, optional stopping, HARKing, subgroup slicing); proposes AnalyticalLineage (Raw Dataset->Cleaning->Exclusions->Transformation->Subgroup selection->Statistical test->Model->Result->Claim) and EvidenceEpisode (original acquisition/replication/contradiction/extension/failed replication/synthesis) so a claim accumulates a reconstructible evidence history rather than a single overwritten result.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0453 §"p-hacking, optional stopping, publication bias, HARKing, novelty bias, selective reporting, repeated testing, slicing data until something 'significant' appears. ... Evidence acquisition provenance must include the analytical path."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0453 §"A replication should not merely overwrite the original result. It becomes new evidence about the original claim. ... EvidenceEpisode: original acquisition, replication, contradiction, extension, failed replication, synthesis."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0453. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S0453), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0453 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0453 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0453 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0453] types=['WARNING', 'EXTENSION'] scope=OBJECT — "Replication-crisis anti-pattern (Wansink subgroup-slicing example) extends evidence provenance from Source+Acquisition to also include Selection/Transformation/Analysis/Exclusion/Publication provenance, formalized as AnalyticalLineage (Raw Dataset->Cleaning->Exclusions->Transformation->Subgroup selection->Statistical test->Model->Result->Claim) reconstructible by KnowledgeOS, protecting against a result appearing only after twenty undocumented analytical decisions." (anchor: "p-hacking, optional stopping, publication bias, HARKing, novelty bias, selective reporting, repeated testing, slicing data until something 'significant' appears. ... Evidence acquisition provenance must include the analytical path.")
- [S0453] types=['FORMALIZATION'] scope=OBJECT — "EvidenceEpisode object accumulates a claim's evidence history (original acquisition, replication, contradiction, extension, failed replication, synthesis) rather than letting a replication silently overwrite the original result, grounded in the Reproducibility Project's warning against treating an initial significant result as sufficient." (anchor: "A replication should not merely overwrite the original result. It becomes new evidence about the original claim. ... EvidenceEpisode: original acquisition, replication, contradiction, extension, failed replication, synthesis.")

## Notes for P3
(none beyond what is noted above)
