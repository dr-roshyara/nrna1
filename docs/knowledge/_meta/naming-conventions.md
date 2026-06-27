---
knowledge_id: META-NAMING
title: EKP Naming & File Conventions
knowledge_type: reference
bounded_context: global
status: approved
authority: authoritative
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [governance, naming, ekp]
related_to: [KNOWLEDGE-CONSTITUTION]
---

# EKP Naming & File Conventions

> Stable, predictable names so links survive and the graph stays valid.

## Knowledge IDs (the stable handle)

- Format: `<PREFIX>-<ID>` where `PREFIX` comes from `schema/knowledge-types.yaml` `id_prefix`.
- `ID` is zero-padded number (`ADR-0001`) or a kebab discriminator (`ADJ-MODEL-determination`).
- **The `knowledge_id` is permanent.** Filenames may change; the id must not. Links/relationships use ids, not paths.
- Domain-scoped ids may add a context token: `ADJ-` (Adjudication), `MEM-` (Membership), `GOV-` (Governance), etc.

## Filenames

- Lowercase kebab-case: `determination-state-machine.md`.
- **ADRs:** `ADR-NNNN-kebab-title.md` (4-digit, zero-padded) in `docs/adr/`. One canonical file per decision.
- **No timestamp prefixes in authoritative areas.** Reserve the `YYYYMMDD_HHMM_` prefix for `working/` and `research/` only, where chronology matters.
- One concept per file. Avoid `FINAL_`, `_v2`, `_COMPLETE`, `_FIX` suffixes — lifecycle is tracked in `status`, not the filename.

## Folders (ownership axis, not type)

- `global/` — platform-wide knowledge.
- `domains/<context>/` — one per bounded context (`schema/bounded-contexts.yaml`), self-contained.
- `working/`, `research/`, `ai/`, `archive/`, `portal/`, `schema/`, `_meta/` — as defined in the Constitution.
- **Type is metadata, never a folder.** A guide, ADR, or prompt is placed by *ownership* (global vs which domain), and classified by `knowledge_type`.

## Frontmatter

Every governed doc starts with the [knowledge card](knowledge-card.template.md). Required: `knowledge_id, title, knowledge_type, bounded_context, status, authority, owner`.

## Archive

Archived files keep their original name and gain `status: archived`, `authority: historical`. They move under `archive/<category>/` via `git mv` (history preserved) — never deleted.
