---
knowledge_id: PORTAL-BY-TYPE
title: EKP — Navigation by Knowledge Type
knowledge_type: portal
bounded_context: global
status: approved
authority: derived
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [portal, navigation, ontology]
related_to: [PORTAL-INDEX]
---

# Navigation by Knowledge Type

> Every governed document declares a `knowledge_type` from [`schema/knowledge-types.yaml`](../schema/knowledge-types.yaml). This index groups the knowledge base by that ontology. (Once frontmatter is backfilled corpus-wide, `knowledge-graph` regenerates this automatically.)

| Type | What it is | Examples |
|---|---|---|
| `constitution` | Immutable principles | [Knowledge Constitution](../Knowledge-Constitution.md) |
| `adr` | Decisions of record | [ADR index](adr-index.md) |
| `ddd-discovery` | Domain discovery | `architecture/backend/discoveries/` |
| `context-map` | Inter-context relationships | _(to populate)_ |
| `domain-model` / `aggregate` | Models & aggregates | [Determination aggregate](../domains/adjudication/model/determination-aggregate.md) |
| `state-machine` | State designs | [Determination state machine](../domains/adjudication/state-machines/determination-state-machine.md) |
| `policy` / `business-rule` | Rules | _(to populate)_ |
| `api` | API contracts | [`developer_guide/api/`](../../../developer_guide/api/) |
| `implementation` | How it's built | [Adjudication implementation](../domains/adjudication/implementation/wiring.md) |
| `guide` / `tutorial` | How-to | [`developer_guide/`](../../../developer_guide/) |
| `playbook` / `recipe` | "How we do X" | [Recipes](recipes/) |
| `prompt` / `package` | AI assets | [`ai/`](../ai/) · [Packages](packages/) |
| `research` / `idea` | Exploration | [`research/`](../research/) · [`architecture/brain_storming/`](../../../architecture/brain_storming/) |
| `review` | Review records | `docs/architecture/contexts/Round*_ARB_*` |
| `reference` | Stable reference | [Lifecycle](../_meta/lifecycle.md) · [Naming](../_meta/naming-conventions.md) |
| `runbook` | Operations | [`developer_guide/`](../../../developer_guide/) deployment guides |
| `checklist` / `template` | Reusable | [Knowledge card template](../_meta/knowledge-card.template.md) |
| `status` | Point-in-time reports | [`archive/status-reports/`](../archive/) (archived) |
