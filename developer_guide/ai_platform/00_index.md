# Developer Guide — AI Engineering Platform

Area covering the PublicDigit AI Engineering Platform (`.claude/platform/` + governed `.claude/` assets). Architecture authority: `engineering/architecture/adr/ADR-AIP-01-AI-Engineering-Platform-Baseline-v1.0.md` and the Baseline v1.0 corpus under `engineering/architecture/proposals/`.

| # | Guide | Covers |
|---|-------|--------|
| 00 | [AI Engineering Architecture](00_ai_engineering_architecture.md) | **The foundational document — why this platform exists.** The inversion (DDD → domain → capabilities → components → assets → provider binding), the twenty principles with pointers to their authoritative homes, reading order for newcomers. Provider-independent. |
| 01 | [The Registry-First Workflow](01_registry_first_workflow.md) | How to add, change, or retire any `.claude` artifact: register → review → implement → verify. The Platform Registry, stable IDs, the five-question rule, validation. |
| 02 | [Working Under the Engineering Process](02_engineering_process_for_developers.md) | EP-01 Planning Stage (plan → explicit approval → implement → re-plan on invalidation), EP-02 Completion Review, the non-trivial decision table, gates, observation classes A–D, the 30-second platform-work question. |
| 03 | *(reserved — written with slice C3: the Verification Engine's first implementation, `run-gates.sh`)* | |

**Reading order for newcomers:** ADR-AIP-01 → `.claude/platform/registry.yaml` (it is small — read it whole) → guide 01 → guide 02.
**Guide obligation:** a numbered step guide here is part of the Definition of Done for every platform implementation step — the same rule as every other `developer_guide/` area.
