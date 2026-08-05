---
name: ddd_strategic_discovery_complete
description: "Phase DD.3b Strategic DDD Discovery completed 2026-05-29 with P9 approval from Senior Architect. 17 artifacts produced, 4 bounded contexts approved."
metadata: 
  node_type: memory
  type: project
  originSessionId: 9059105f-c80d-49a0-8438-aee9dca2bed8
---

# Strategic DDD Discovery — Phase Complete

**Phase:** DD.3b
**Date approved:** 2026-05-29
**Status:** Complete — P9 gate passed

## Approved Architecture

### Bounded Contexts (4)
- **Observation** — Independent BC. Owns signal creation. Customer/Supplier to Evidence.
- **Evidence** — Independent BC (contains Evaluation as subdomain). Owns preservation, classification, evaluation transport.
- **Legitimacy** — Core Domain. Sole authority for LegitimacyOutcome derivation. Protected by F4/F10.
- **Replay** — Core-Supporting. Open Host Service to all contexts. Owns certification.

### Rejected as BC
- Evaluation → Subdomain of Evidence (failed 5/6 autonomy criteria)
- Governance → Generic Domain (Laravel conventions)
- Certification → Value object within Replay

### Temporary Contexts (with deletion dates)
- Migration → D.5
- Dual Sovereignty → D.2  
- Retirement → D.5

### Dependency Direction
- Evidence → Observation (imports OverlaySignal[])
- Legitimacy → Evidence (imports EvaluationEnvelope)
- Replay → Evidence (reads ConstitutionalEvidenceSnapshot)
- Governance → Legitimacy (imports LegitimacyOutcome)
- Projection → Observation (read-only display)

## Key Decisions
- **Evaluation ⊂ Evidence** — not an independent BC (no unique lifecycle, invariants, language, or decisions)
- **Replay = Core-Supporting** — elevated from Supporting because "decision proving" is as strategic as "decision making"
- **Governance = Generic Domain** — avoid creating a dumping-ground BC
- **3-tier pipeline**: Observation → Evidence → Legitimacy (not 4-tier with Evaluation)

## Next Phase
Aggregate Discovery (P10) — consistency boundaries inside each approved BC, starting with Legitimacy.
