# step188-four-time-dimensions-and-bitemporal-core

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `BitemporalKnowledge core = (ValidTime,KnowledgeTime); T_observed/T_recorded as provenance metadata, not necessarily core semantics`, `T_e=(T_valid,T_observed,T_known,T_recorded)`, `worked server-compromise example with four distinct dates` · **Aliases:** `four-timestamp refinement of the temporal model`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 188 refines the prior two-time model into four distinct timestamps per evidence item: T_valid (when the proposition was true), T_observed (when observed), T_known (when the organization became aware), T_recorded (when it entered the system) -- worked through a security-compromise example with four different dates (Jan 10/15/17/18), explicitly noting a conventional created_at field loses this distinction. Concludes the semantic core need only be bitemporal (ValidTime, KnowledgeTime), with T_observed/T_recorded potentially treated as provenance metadata rather than part of the domain model's semantic core -- 'we shouldn't put every timestamp into the domain model merely because mathematics allows it.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1388] §"T_e=(T_{valid},T_{observed},T_{known},T_{recorded}). ... compromise occurred: January 10; engineer noticed anomaly: January 15; security team established compromise: January 17; record entered into KnowledgeOS: January 18. ... BitemporalKnowledge with: T=(ValidTime,KnowledgeTime). The additional timestamps may be provenance metadata rather than part of the semantic core."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1388] §"T_e=(T_{valid},T_{observed},T_{known},T_{recorded}). ... compromise occurred: January 10; engineer noticed anomaly: January 15; security team established compromise: January 17; record entered into KnowledgeOS: January 18. ... BitemporalKnowledge with: T=(ValidTime,KnowledgeTime). The additional timestamps may be provenance metadata rather than part of the semantic core."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1388. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1388 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S1388 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1388] types=[FORMALIZATION] scope=THEORY-LEVEL — "Refines the temporal model to four timestamps per evidence item -- T_valid, T_observed, T_known, T_recorded -- worked through a four-date security-compromise example, noting a conventional created_at field loses this distinction. Concludes the semantic core only needs to be bitemporal (ValidTime, KnowledgeTime), with the other two timestamps potentially treated as provenance metadata rather than domain-model core -- 'we shouldn't put every timestamp into the domain model merely because mathematics allows it.'" (anchor: "T_e=(T_{valid},T_{observed},T_{known},T_{recorded}). ... compromise occurred: January 10; engineer noticed anomaly: January 15; security team established compromise: January 17; record entered into KnowledgeOS: January 18. ... BitemporalKnowledge with: T=(ValidTime,KnowledgeTime). The additional timestamps may be provenance metadata rather than part of the semantic core.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
