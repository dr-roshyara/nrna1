---
knowledge_id: ADJ-ROADMAP
title: Adjudication — Roadmap
knowledge_type: status
bounded_context: adjudication
status: draft
authority: provisional
audience: [architect, developer]
owner: nab.raj.sharma
version: 0.1
schema_version: 1
tags: [adjudication, roadmap]
related_to: [ADJ-README]
---

# Adjudication — Roadmap

> Maturity: **growing** (greenfield, Deptrac-enforced).

## Done

- Determination aggregate (`Draft → Issued → Final`), value objects, `DeterminationIssued` event.
- Ports + adapters; transactional service; tenant migration; unit + integration tests.

## Next

- [ ] Wire `Upheld` outcome to downstream `ElectionCorrectionApplied` (correction execution lives outside this context).
- [ ] Read-side query/projection for issued determinations.
- [ ] HTTP/API surface (currently service-layer only).
- [ ] Expand `deptrac.yaml` assertions as the context grows.

## Open questions

- Remand/partial outcomes were considered and excluded in v1.2 — revisit only via ADR.
