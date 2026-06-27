---
knowledge_id: RCP-CREATE-ADR
title: Recipe — Create an Architecture Decision Record
knowledge_type: recipe
bounded_context: global
status: approved
authority: authoritative
audience: [architect, developer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adr, decision, recipe, how-to]
related_to: [PORTAL-ADR-INDEX, RCP-IMPLEMENT-AGGREGATE]
requires: [META-NAMING]
---

# Recipe — Create an Architecture Decision Record

## When to use

A decision with lasting architectural consequence (a boundary, a pattern, a trade-off). Lighter calls can use `knowledge_type: decision` instead.

## Steps

1. **Allocate the ID** — next `ADR-NNNN` (4-digit). Check the [ADR index](../adr-index.md) for the last number.
2. **Create the file** — `docs/adr/ADR-NNNN-kebab-title.md`.
3. **Add the knowledge card** — copy [the template](../../_meta/knowledge-card.template.md); set `knowledge_type: adr`, `bounded_context`, `status: draft`, `authority: provisional`.
4. **Write the body** — the project's standard sections:
   ```
   ## Status        (proposed | accepted | superseded)
   ## Context       (the problem / forces)
   ## Decision      (what we decided)
   ## Rationale     (why; alternatives rejected)
   ## Implications   (what changes as a result)
   ```
5. **Link it** — set `implements`/`related_to` on affected docs; add a row to the [ADR index](../adr-index.md).
6. **Review** — move `status: reviewed → approved` per the [lifecycle gates](../../_meta/lifecycle.md#4-knowledge-quality-gates-definition-of-approved); record reviewer in `reviewed_by`.
7. **Supersession** — to replace an old ADR, set the new one's `supersedes` and the old one's `status: superseded` + `superseded_by`.

## Checklist

- [ ] ID is unique and sequential
- [ ] Knowledge card present and valid (`knowledge-lint` passes)
- [ ] All five body sections present
- [ ] Added to the ADR index
- [ ] Affected docs link back via `implements`
