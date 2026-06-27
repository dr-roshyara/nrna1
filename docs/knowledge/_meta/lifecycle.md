---
knowledge_id: META-LIFECYCLE
title: Knowledge Lifecycle, Governance & Authority Model
knowledge_type: reference
bounded_context: global
status: baseline
authority: authoritative
audience: [architect, developer, reviewer, ai]
owner: nab.raj.sharma
version: 1.1
schema_version: 1
tags: [governance, lifecycle, ekp]
related_to: [KNOWLEDGE-CONSTITUTION]
derived_from: [KNOWLEDGE-CONSTITUTION]
---

# Knowledge Lifecycle, Governance & Authority Model

> How a document moves from a rough idea to an authoritative baseline, **who** may move it, what **guards** each move, and how trust is tracked separately from lifecycle.

This document operationalises the principles in the [Knowledge Constitution](../Knowledge-Constitution.md). The Knowledge lifecycle **supports** the software lifecycle; it is not the same thing (see §5).

## 1. Two independent dimensions

Every document carries **both** at all times. Most projects wrongly conflate them.

### `status` — lifecycle position (`schema/statuses.yaml`)

```
idea → research → draft → discovery → reviewed → approved → baseline → frozen
                                                       │
                                                       └→ superseded → archived
```

### `authority` — trust & source (`schema/authorities.yaml`), **independent of status**

`authoritative` · `derived` · `generated` · `historical` · `provisional`

Real combinations: `approved` + `generated` (an approved AI doc); `baseline` + `derived` (a generated index built from baselines); `frozen` + `authoritative` (canonical, change-controlled truth).

## 2. Governance roles — *who* moves a document

| Transition | Performed by |
|---|---|
| draft → discovery | Author |
| discovery → reviewed | Peer reviewer |
| reviewed → approved | Architecture Review Board / Chief Architect |
| approved → baseline | Knowledge / Release Manager |
| baseline → frozen | Governance Board |
| frozen → superseded | Only via an approved **ADR** |
| any → archived | Knowledge Manager (with `superseded_by` set, if replaced) |

Roles map to people in [`OWNERS`](OWNERS).

## 3. Transition guards — *what must be true* to move

The lifecycle is a state machine; each transition has guards `knowledge-lint` can check.

| Target state | Guards (must hold) |
|---|---|
| `draft` | `owner`, `knowledge_type`, `bounded_context` present |
| `discovery` | + at least one relationship or `code_refs` (it must connect to something) |
| `reviewed` | + traceability complete for its type, metadata complete, no broken links |
| `approved` | + **all Knowledge Quality Gates pass** (§4), reviewer recorded in `reviewed_by` |
| `baseline` | + `authority: authoritative` or `derived`, version set |
| `frozen` | + is a baseline; subsequent edits require an ADR (`frozen_changed_without_adr`) |
| `superseded` | + `superseded_by` points to an existing doc |

## 4. Knowledge Quality Gates — *definition of "Approved"*

"Approved" is **objective**, not a feeling. A document is Approvable only when **all** pass (these are the `knowledge-lint` rules):

- [ ] Metadata complete (all required frontmatter fields)
- [ ] `knowledge_type`, `bounded_context`, `status`, `authority` assigned & valid
- [ ] Traceability complete for its type (`knowledge-types.yaml: traceability_requires`)
- [ ] No broken links; all relationship targets exist
- [ ] Reviewed (a `reviewed_by` entry)
- [ ] Single-source-of-truth respected (no duplicate `authoritative` for the topic)
- [ ] Knowledge graph valid (no dangling edges)
- [ ] `knowledge-lint` passes with no errors

## 5. Knowledge lifecycle ≠ Software lifecycle

They are parallel and connected, not identical:

```
KNOWLEDGE     idea → research → discovery → review → approved → baseline → frozen → superseded → archived
SOFTWARE      requirement → design → implementation → testing → release
                                  ▲            ▲
              knowledge (ADRs, models, discovery) FEEDS design & implementation;
              code/tests link back via code_refs / test_refs.
```

Knowledge **supports** software. An ADR being `baseline` does not mean the code is released, and vice-versa.

## 6. The single-source-of-truth rule

> **There shall be exactly one `authority: authoritative` document for each governed knowledge topic within a bounded context.**

Everything else on that topic is `derived`, `generated`, `historical`, or `provisional`. Violations are a lint **error** (`single_authoritative`).

## 7. AI governance

AI is a first-class participant, but:

```
AI-generated knowledge → review → approval → baseline → authoritative
```

> **AI output is never `authority: authoritative` until a human review moves it there.** Freshly generated AI docs are `authority: generated` (often `status: draft`). See the Constitution's AI principles and `docs/knowledge/ai/`.

## 8. Mapping to the legacy folders (transition period)

```
architecture/   (Think)  → drafts & discovery   → status: draft|discovery
docs/           (Truth)  → approved/baseline     → status: approved|baseline|frozen
developer-guide/(Build)  → operational guides    → authority: authoritative (operational)
```

The EKP under `docs/knowledge/` is where these converge with metadata + traceability. Entry point: [`../portal/INDEX.md`](../portal/INDEX.md).
