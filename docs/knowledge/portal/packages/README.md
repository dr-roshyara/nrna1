---
knowledge_id: PKG-README
title: Knowledge Packages — reusable context bundles
knowledge_type: reference
bounded_context: global
status: approved
authority: authoritative
audience: [ai, developer, architect]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [packages, ai, context, bundle]
related_to: [PORTAL-INDEX]
---

# Knowledge Packages

> A **package** is a reusable *context bundle*: a manifest listing the exact set of knowledge documents (by `knowledge_id`) to load together for a given task. It exists so a human — or an AI assistant — can pull the right context in one step instead of hunting across folders.

## Why

When you sit down to "implement an aggregate" you don't think *"I need a prompt"* — you think about the task. A package assembles everything that task needs: principles + guide + template + prompt + example + checklist + tests.

## Format

Each package is a small YAML manifest:

```yaml
package: <kebab-name>
knowledge_id: PKG-<NAME>
purpose: <one line>
audience: [ai, developer]
includes:           # ordered list of knowledge_ids to load
  - <KNOWLEDGE-ID>
  - ...
code_refs: []       # optional reference code to read
```

`knowledge-lint` checks that every `includes` id resolves to a real document.

## Available packages

- [`implement-aggregate.yaml`](implement-aggregate.yaml) — everything to add a DDD aggregate.
- [`architecture-review.yaml`](architecture-review.yaml) — everything to run an architecture review.
