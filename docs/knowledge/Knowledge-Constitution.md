---
knowledge_id: KNOWLEDGE-CONSTITUTION
title: Engineering Knowledge Platform — Constitution
knowledge_type: constitution
bounded_context: global
status: frozen
authority: authoritative
audience: [architect, developer, reviewer, product-owner, ai, newcomer, operations, security]
owner: nab.raj.sharma
reviewers: []
version: 1.0
schema_version: 1
tags: [governance, constitution, ekp, principles]
related_to: [META-LIFECYCLE]
---

# Engineering Knowledge Platform (EKP) — Constitution

> The immutable principles from which the schema, lifecycle, tooling, and portal all derive.
> This document is `status: frozen`: it may only change via an approved ADR.

## Purpose

The Engineering Knowledge Platform treats the project's accumulated knowledge — DDD discovery, architecture decisions, research, AI collaboration, implementation guidance, and operations — as a **governed engineering asset**, with the same discipline applied to code (PHPStan, Deptrac, tests). Its goal is not to *store* documents but to **retrieve the right knowledge at the moment it is needed**, by humans and AI alike, over a 10+ year horizon.

## The layered model

Everything in the EKP derives from this hierarchy — the same layered discipline used for the software architecture:

```
1. Knowledge Constitution   ← immutable principles            (this document)
2. Knowledge Schema         ← controlled vocabularies + metadata   (schema/*.yaml)
3. Knowledge Lifecycle      ← state machine + governance + gates   (_meta/lifecycle.md)
4. Knowledge Tooling        ← lint, graph generation, validation   (scripts/knowledge-lint.sh, knowledge-graph.sh)
5. Knowledge Portal         ← navigation, hubs, recipes, packages   (portal/)
```

## Knowledge Principles

1. **Knowledge is versioned.** Every governed doc has a version and lives in git.
2. **Knowledge is traceable.** Every doc links to what it implements, depends on, supersedes, documents, and is tested by.
3. **Knowledge has ownership.** Every doc has an owner (see `_meta/OWNERS`).
4. **Knowledge has a lifecycle.** Every doc has a `status` and moves through a governed state machine.
5. **Knowledge is searchable.** Every doc declares `knowledge_type`, `bounded_context`, `tags`, and `audience`.
6. **Knowledge is governed.** Transitions have roles and guards; quality gates define "approved".
7. **Knowledge is reviewed.** Nothing becomes authoritative without review.
8. **Knowledge is never duplicated.** Exactly one authoritative document per topic per context.
9. **Knowledge is linked, not copied.** The portal connects existing files; we evolve, not duplicate.
10. **Knowledge evolves through ADRs.** Frozen knowledge changes only via an approved ADR.

## Single source of truth

> There shall be exactly **one** `authority: authoritative` document for each governed knowledge topic within a bounded context. All other documents on that topic are `derived`, `generated`, `historical`, or `provisional`.

## Ownership model

Ownership mirrors the existing repository `OWNERS` convention: a named owner per domain/area, change-type approval gates, and time-bound exceptions reviewed quarterly. Knowledge ownership is by **domain expertise**, not by person-wide decree. See [`_meta/OWNERS`](_meta/OWNERS).

## Governance model

Two independent dimensions govern every document:

- **`status`** — where it is in its life (idea → … → baseline → frozen → archived).
- **`authority`** — how far to trust it and where it came from (authoritative / derived / generated / historical / provisional).

Roles, transition guards, and quality gates are defined in [`_meta/lifecycle.md`](_meta/lifecycle.md).

## Traceability philosophy

A document in isolation is nearly worthless; its value is in its connections. The metadata captures **typed, semantic relationships** (`schema/knowledge-relationships.yaml`) so that the knowledge graph can answer *why* two artefacts relate — and so that knowledge connects all the way down to **code and tests** (`code_refs`, `test_refs`). The target chain:

```
Business goal → Discovery → ADR → Domain model / Aggregate → State machine → API → Implementation → Tests → UI
```

## Quality philosophy

Documentation is a first-class engineering artefact and is held to the same bar as code. `knowledge-lint` is to knowledge what PHPStan/Deptrac are to code: schema validation, traceability, orphan detection, duplicate-authority detection, circular-reference detection, and boundary consistency. CI runs it on every change.

## AI collaboration principles

AI (Claude / ChatGPT / DeepSeek) is a first-class participant, governed explicitly:

- AI-generated knowledge enters as `authority: generated` (usually `status: draft`).
- **AI output never becomes `authoritative` without human review.**
- AI working material (prompts, raw outputs, context bundles) is segregated under `docs/knowledge/ai/` and only migrates into authoritative knowledge after review.
- Reusable AI context is packaged as **Knowledge Packages** (`portal/packages/`) so the right bundle can be loaded for a task.

## Relationship to software architecture

The knowledge architecture is **isomorphic** to the software architecture without duplicating its folders: bounded contexts in `app/Contexts/` map to `domains/<context>/`; cross-cutting concerns map to `global/`; the same Deptrac-style boundary discipline that prevents cross-context coupling in code is mirrored by the `boundary_consistency` lint rule for knowledge.

---

*Amendment process:* changes to this constitution require an ADR (`knowledge_type: adr`) that `supersedes` the relevant section, reviewed by the Architecture Review Board.
