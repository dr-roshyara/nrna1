---
knowledge_id: PORTAL-INDEX
title: Engineering Knowledge Platform — Portal
knowledge_type: portal
bounded_context: global
status: approved
authority: derived
audience: [newcomer, developer, architect, reviewer, ai, operations, security]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [portal, index, ekp, navigation]
related_to: [KNOWLEDGE-CONSTITUTION, META-LIFECYCLE]
---

# Engineering Knowledge Platform (EKP) — Portal

> The single entry point. The EKP turns ~2,500 scattered documents into **retrievable knowledge** — organised by *ownership*, classified by *type*, connected by a *graph*, and governed like code.

## Start here

| You are… | Go to |
|---|---|
| New to the project | [By role → Newcomer](by-role.md#newcomer) |
| A developer building a feature | [By role → Developer](by-role.md#developer) · [Recipes](recipes/) |
| An architect | [By role → Architect](by-role.md#architect) · [ADR index](adr-index.md) |
| An AI assistant | [By role → AI](by-role.md#ai-assistant) · [Packages](packages/) |
| Looking for a topic | [Topic hubs](#topic-hubs) |
| Looking for a kind of doc | [By type](by-type.md) |

## How the EKP is organised

The **primary axis is knowledge ownership**, not document type (type is metadata):

```
docs/knowledge/
  Knowledge-Constitution.md   ← immutable principles (read first)
  schema/                     ← controlled vocabularies the linter enforces
  _meta/                      ← lifecycle, naming, OWNERS, card template
  global/                     ← platform-wide knowledge
  domains/<context>/          ← self-contained per bounded context
  working/  research/  ai/    ← in-flight / exploratory / AI material
  archive/                    ← superseded & historical (never deleted)
  portal/                     ← you are here: indexes, hubs, recipes, packages
```

Foundations: [Knowledge Constitution](../Knowledge-Constitution.md) · [Lifecycle & Governance](../_meta/lifecycle.md) · [Schema](../schema/) · [Naming](../_meta/naming-conventions.md) · [Ownership](../_meta/OWNERS)

## Legacy folders during the transition

We **evolve, not revolutionise** — existing folders keep their meaning and the portal links into them where files still live:

| Folder | Role | Lifecycle |
|---|---|---|
| [`architecture/`](../../../architecture/) | **Think** — exploration, AI work, drafts | draft / discovery |
| [`docs/`](../../) | **Official Truth** — ADRs, discovery, decisions | approved / baseline |
| [`developer_guide/`](../../../developer_guide/) | **Build** — operational guides | authoritative (operational) |

## Topic hubs

A hub gathers *everything* about a topic across all folders + code + tests (the files stay where they are):

- [Election / Voting](hubs/election.md) — showcase hub
- [Adjudication](hubs/adjudication.md) — pilot domain (fully assembled)
- _Membership, Governance, Contestation, Geography … (stubs — added incrementally)_

## Recipes & packages

- [Recipes](recipes/) — step-by-step task guides (e.g. [Implement an Aggregate](recipes/implement-aggregate.md), [Create an ADR](recipes/create-adr.md)).
- [Packages](packages/) — reusable **context bundles** for AI + humans (manifests of the docs to load for a task).

## Tooling (knowledge is linted like code)

- `npm run knowledge-lint` — validates frontmatter, vocabularies, links, traceability, single-authority, orphans, cycles, boundaries.
- `npm run knowledge-graph` — regenerates the [knowledge graph](graph/).

> Status of this initiative: **Iteration 1** — schema, governance, portal, automation, and the Adjudication pilot. The other 9 domains and corpus-wide frontmatter backfill follow incrementally.
