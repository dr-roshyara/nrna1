# Engineering Observations — Developer Guide

Guides for the engineering observation tooling: the static metrics tool and the
observation collectors. These tools OBSERVE engineering artifacts and emit
advisory, verdict-free observations — they never block a build and never judge.

| Step | Guide | Covers |
|---|---|---|
| 01 | [Static Metrics Tool](01_static_metrics_tool.md) | `composer metrics:collect` — pdepend, risk score, stereotype bands, hotspots, trend file |
| 02 | [Test-Presence Collector](02_test_presence_collector.md) | production-changed-without-tests observation over a git range |
| 03 | [LCOM4 Collector & Conformance Suite](03_lcom4_collector_and_conformance.md) | cohesion observation, golden fixtures, the conformance contract |
| 04 | [Evidence Dashboard](04_evidence_dashboard.md) | regenerable projection: stream summaries + outcome questions with honest empty cells |
| 05 | [Recommendation Engine v1](05_recommendation_engine.md) | rules-as-data → advisory recommendations → decision+rationale capture |
| 06 | [Outcome Recording](06_outcome_recording.md) | raw baseline→current→delta records joining recommendation + decision; assessment-free by test |
| 07 | [Assessment Service](07_assessment_service.md) | deterministic interpretation: SUPPORTED / PARTIALLY / NOT / INCONCLUSIVE, closed set, basis included |
| 08 | [Recommendation Inbox](08_recommendation_inbox.md) | the developer's decision entry point: list open recommendations, interactive a/i/d capture |

**The one boundary to understand before touching anything:**
collectors produce observations · KnowledgeOS consumes observations ·
no collector may ever contain policy (WARN/BLOCK belongs to configured
governance, not here — and the tests enforce this).

*Created 2026-08-04 as remediation: these guides were owed per the Developer
Guide Definition of Done and were skipped during implementation (see
OE-KOS-3 in `docs/knowledgeos/KnowledgeOS_Operational_Evidence_Register.md`).*
