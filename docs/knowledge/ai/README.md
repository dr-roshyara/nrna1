---
knowledge_id: AI-README
title: AI Knowledge — Prompts, Outputs & Context
knowledge_type: reference
bounded_context: global
status: approved
authority: authoritative
audience: [ai, developer, architect]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [ai, prompts, governance, ekp]
related_to: [PORTAL-INDEX, KNOWLEDGE-CONSTITUTION]
---

# AI Knowledge

> AI (Claude / ChatGPT / DeepSeek) is a first-class participant — but its output is **segregated here until reviewed**. This enforces the [Constitution's AI principles](../Knowledge-Constitution.md#ai-collaboration-principles).

## The governing rule

```
AI-generated knowledge → review → approval → baseline → authoritative
```

> **AI output is never `authority: authoritative` without a human review.** Freshly generated material is `authority: generated` (usually `status: draft`). Only after review does it migrate into `global/` or a `domains/<ctx>/` folder.

## Structure

| Folder | Holds |
|---|---|
| `prompts/` | Reusable, structured prompts (purpose / when-to-use / expected output / example / version) |
| `reviews/` | AI-produced reviews (architecture, code) awaiting or after human sign-off |
| `generated/` | Raw AI output not yet reviewed |
| `context/` | Context files fed to AI (project facts, conventions) |
| `experiments/` | Throwaway AI explorations |

## Reusable context bundles

For loading the right context in one step, use **Knowledge Packages** — see [`portal/packages/`](../portal/packages/README.md).

## Existing AI footprint to migrate

- `claude/plans/` — dated implementation plans (`YYYYMMDD-HHMM-*.md`).
- `.aider.chat.history.md` — legacy aider session log (archive candidate).
- `.claude/`, `CLAUDE.md`, `UI_GUIDELINES.md` — active AI operating instructions.
