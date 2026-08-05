---
knowledge_id: GUIDE-EKP
title: Engineering Knowledge Platform — Developer Guide
knowledge_type: guide
bounded_context: global
status: approved
authority: authoritative
audience: [developer, architect, ai, newcomer]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [ekp, guide, onboarding, governance, knowledge-lint]
related_to: [PORTAL-INDEX, KNOWLEDGE-CONSTITUTION, META-LIFECYCLE, META-NAMING]
requires: [META-NAMING]
code_refs:
  - scripts/knowledge-lint.php
  - scripts/knowledge-graph.php
---

# Engineering Knowledge Platform — Developer Guide

> What the EKP is, how it is built, and how you (human or AI) work with it day-to-day. This is the practical companion to the [Knowledge Constitution](../Knowledge-Constitution.md) (the *why*) and the [Lifecycle](../_meta/lifecycle.md) (the *governance*).

## 1. What the EKP is (and why)

The project had ~2,500 markdown files scattered across the repo root, two parallel `architecture/` trees, ~82 status reports, duplicate ADRs, and orphan files — with **no metadata**, so nothing was reliably retrievable. The real problem was never *storage*; it was **retrieval**: "six months from now, where is everything about the Election aggregate?"

The Engineering Knowledge Platform (EKP) treats knowledge as a **governed engineering asset**, with the same discipline applied to code (PHPStan, Deptrac, tests). It lives under `docs/knowledge/`.

## 2. The layered model

Everything derives from this hierarchy (mirrors the software architecture):

```
1. Constitution   docs/knowledge/Knowledge-Constitution.md   immutable principles
2. Schema         docs/knowledge/schema/*.yaml                controlled vocabularies + metadata
3. Lifecycle      docs/knowledge/_meta/lifecycle.md           state machine + roles + quality gates
4. Tooling        scripts/knowledge-lint.php, knowledge-graph.php
5. Portal         docs/knowledge/portal/                      navigation, hubs, recipes, packages
```

## 3. Layout (organised by ownership, not file type)

```
docs/knowledge/
  Knowledge-Constitution.md   frozen principles
  schema/                     7 vocabulary files the linter enforces
  _meta/                      lifecycle, naming, OWNERS, knowledge-card template
  global/                     platform-wide knowledge   <- THIS guide lives here
  domains/<context>/          self-contained per bounded context
  portal/                     INDEX, by-role, by-type, adr-index, hubs/, recipes/, packages/, graph/
  ai/                         prompts, AI outputs, context (segregated until reviewed)
  working/  research/         drafts / exploration
  archive/                    superseded, obsolete, orphan (git mv, never deleted)
```

> **Type is metadata, never a folder.** A guide, ADR, or prompt is placed by *ownership* (global vs which domain) and classified by `knowledge_type`.

## 4. The knowledge card (every governed doc starts with one)

Copy [`_meta/knowledge-card.template.md`](../_meta/knowledge-card.template.md). Required fields:

```yaml
knowledge_id:     # stable & unique, PREFIX-ID (e.g. ADJ-MODEL-001). Permanent — links use this, not paths
title:
knowledge_type:   # one of schema/knowledge-types.yaml
bounded_context:  # one of schema/bounded-contexts.yaml, or `global`
status:           # lifecycle position (schema/statuses.yaml)
authority:        # trust/source (schema/authorities.yaml) — INDEPENDENT of status
owner:
```

Two dimensions are deliberately independent: `status` (where it is in its life) vs `authority` (how far to trust it). An AI doc can be `status: approved, authority: generated`.

Typed relationships become edges in the knowledge graph: `implements`, `requires`, `depends_on`, `derived_from`, `supersedes`/`superseded_by`, `related_to`, `documents`, `verified_by`, `tested_by`, `reviewed_by`, plus `code_refs`/`test_refs` linking knowledge to code.

## 5. Lifecycle & quality gates

```
draft → discovery → reviewed → approved → baseline → frozen
                                   └→ superseded → archived
```

"Approved" is **objective** — all [quality gates](../_meta/lifecycle.md#4-knowledge-quality-gates-definition-of-approved) must pass: metadata complete, traceability complete, links resolve, reviewed, single-source-of-truth respected, `knowledge-lint` clean. Roles and transition guards are in the [lifecycle doc](../_meta/lifecycle.md). **AI output never becomes authoritative without human review.**

## 6. Tooling — knowledge is linted like code

```bash
npm run knowledge-lint            # report (warn-only)
npm run knowledge-lint:strict     # exit 1 on any error (CI / pre-merge)
npm run knowledge-graph           # regenerate docs/knowledge/portal/graph/
```

`knowledge-lint` ("PHPStan for knowledge") validates frontmatter, controlled vocabularies, link resolution, relationship-target existence, **single authoritative per topic**, **traceability completeness**, **orphan documents**, **circular dependencies**, and **boundary consistency** (no cross-context links unless typed cross-context — Deptrac-for-docs). CI runs warn-only (`.github/workflows/knowledge-lint.yml`); promote to blocking once the full corpus is clean.

## 7. How to add knowledge

**A new document:** copy the knowledge card → set required fields → write the body → add typed relationships → `npm run knowledge-lint` → move `status` up the lifecycle as it is reviewed.

**A new ADR:** follow [Recipe: Create an ADR](../portal/recipes/create-adr.md).

**A new aggregate / domain:** follow [Recipe: Implement an Aggregate](../portal/recipes/implement-aggregate.md) and use the **Adjudication pilot** ([`domains/adjudication/`](../domains/adjudication/README.md)) as the copy-paste template — it shows the full chain: discovery → aggregate → state machine → wiring → tests → roadmap, each with a knowledge card.

**Reusable context for a task:** load a [Knowledge Package](../portal/packages/README.md) — a manifest of the exact docs to pull together (great for AI).

## 8. What Iteration 1 delivered

- Data-driven `schema/` (types, statuses, authorities, bounded-contexts, relationships, audiences + master schema).
- Constitution + `_meta/` governance (lifecycle, naming, OWNERS, card template).
- Portal: INDEX, role/type indexes, canonical ADR index, Election + Adjudication hubs, recipes, packages, generated graph.
- **Adjudication** pilot assembled end-to-end; stub READMEs for the other 10 contexts.
- `knowledge-lint` + `knowledge-graph` + CI + npm scripts.
- Non-destructive hygiene: ADR triplicate consolidated; **22 orphans + 81 status reports + 2 superseded ADRs archived via `git mv`** (history preserved); repo-root `.md/.txt` cut from **147 → 67**.

## 9. What is deferred (next iterations)

- Migrate the other 10 domains and the ~1,500 nested feature docs into `domains/` / `global/`.
- Backfill knowledge cards across the full corpus (incl. governing ADRs as knowledge docs, which re-enables `implementation → adr` traceability).
- Promote `knowledge-lint` CI to blocking; add a pre-commit hook.
- Migrate `developer_guide/`, `tenancy/`, and `claude/` content into `global/` (the "consolidate under docs/" end-state).

## 10. Legacy folders during the transition

| Folder | Role | Lifecycle |
|---|---|---|
| [`architecture/`](../../../architecture_legacy/README.md) | **Think** — exploration, AI work, drafts | draft / discovery |
| [`docs/`](../../) | **Official Truth** — ADRs, decisions, baselines | approved / baseline |
| [`developer_guide/`](../../../developer_guide/) | **Build** — operational app guides | authoritative (operational) |

The portal links into all three; content migrates into `docs/knowledge/` gradually. **Start at the [Portal](../portal/INDEX.md).**
