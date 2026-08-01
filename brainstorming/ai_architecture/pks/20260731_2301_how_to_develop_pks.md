# PKS & KnowledgeOS — Practical Next Steps (Brainstorming Output)

## Executive Summary

**Phase II governance is closed. Phase III is operational validation.**

The brainstorming above identifies six strategic hypotheses. Below are the **practical, actionable things you can do now** — without opening another governance cycle.

---

## Practical Thing 1 — Publish PKS as a Knowledge Graph

### What This Means

PKS is currently YAML files in `./docs/pks/`. You can make it consumable by multiple tools.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Export PKS YAML as JSON | `pks.json` |
| 2 | Create a simple query API | `GET /pks/concepts`, `GET /pks/relationships` |
| 3 | Build a simple UI dashboard | Visualize concepts, relationships, boundaries |
| 4 | Add a search interface | Search concepts, relationships, documentation |

### Tools to Consider

| Tool | Purpose |
|------|---------|
| **Neo4j** | Graph database for relationships |
| **Elasticsearch** | Search over PKS |
| **Vue.js** | Dashboard UI |
| **Laravel API** | REST endpoints |

### Why This Matters

PKS becomes a **living knowledge graph** — not just files.

---

## Practical Thing 2 — Integrate PKS with Claude Code CLI

### What This Means

Claude Code already loads PKS via `CONTEXT.md`. You can make this integration deeper.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Create a Claude Code tool that queries PKS | `pks search "Decision"` |
| 2 | Add PKS validation in Claude Code | Validate decisions against PKS |
| 3 | Create a Claude Code hook that surfaces governance questions | Auto-surfacing of open questions |
| 4 | Add PKS-based documentation generation | Generate ADRs from PKS decisions |

### Example Claude Code Integration

```yaml
# .claude/tools/pks.yaml
name: pks
description: Query the PKS knowledge graph
tools:
  - name: search_concept
    description: Search for a concept in PKS
    parameters:
      - name: term
        type: string
        required: true
  - name: get_relationships
    description: Get relationships for a concept
    parameters:
      - name: concept
        type: string
        required: true
  - name: validate_decision
    description: Validate a decision against PKS
    parameters:
      - name: decision
        type: object
        required: true
```

### Why This Matters

Claude Code becomes a **PKS-powered assistant** — not just a file reader.

---

## Practical Thing 3 — Build the Document Catalog as a Service

### What This Means

The document catalog tells AI what documents to produce, when, where, how. You can make it executable.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Create a Document Catalog API | `GET /documents` |
| 2 | Add a document generator | Generate documents from templates |
| 3 | Add validation | Validate documents against catalog rules |
| 4 | Add CI integration | Validate documents in CI pipeline |

### Example Document Catalog API

```yaml
# docs/pks/document_catalog.yaml
documents:
  - id: ADR
    name: Architecture Decision Record
    when: During design
    where: docs/adr/
    template: ADR-Template.md
    verification:
      - Has context
      - Has decision
      - Has consequences
      - Has rejected alternatives
      - Has evidence
    required: true
```

### Why This Matters

Documentation becomes **automated and verifiable** — not just guidance.

---

## Practical Thing 4 — Create a PKS Dashboard

### What This Means

A visual interface to explore the PKS knowledge graph.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Build a simple UI with Vue.js | Dashboard |
| 2 | Add concept view | See concept details, relationships |
| 3 | Add relationship view | See how concepts connect |
| 4 | Add boundary view | See bounded contexts |
| 5 | Add search | Search across PKS |
| 6 | Add export | Export PKS as JSON, YAML, Markdown |

### Example Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PKS DASHBOARD                                           │
│                                                                              │
│  Search: [________________] [Search]                                        │
│                                                                              │
│  Concepts:                                                                  │
│  ├── Decision (ADR-T23, D-12, etc.)                                        │
│  ├── Rule (ER-nn, ES-00n)                                                  │
│  ├── Invariant (CI-n, BI-n)                                                │
│  └── ...                                                                    │
│                                                                              │
│  Relationships:                                                             │
│  ├── Decision → supersedes → Decision                                      │
│  ├── Rule → binds → Product                                                │
│  └── ...                                                                    │
│                                                                              │
│  Bounded Contexts:                                                          │
│  ├── CBC-1 Knowledge Assessment (Medium-High)                              │
│  ├── CBC-2 Knowledge Projection (Medium-High)                              │
│  └── ...                                                                    │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Why This Matters

PKS becomes **discoverable and navigable** — not just files.

---

## Practical Thing 5 — Start Collecting Operational Evidence

### What This Means

The feedback loop from PublicDigit implementation back to PKS and KnowledgeOS.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Create an operational evidence register | `evidence.yaml` |
| 2 | Record where PKS helped | Success patterns |
| 3 | Record where PKS caused friction | Improvement candidates |
| 4 | Record where PKS was insufficient | Gaps |
| 5 | Record where PKS was ignored | Deviations |

### Evidence Register Example

```yaml
# docs/evidence/operational_evidence.yaml
evidence:
  - id: E-001
    date: 2026-07-31
    source: PublicDigit Implementation
    type: success
    description: PKS correctly guided the Decision concept
    impact: consistent documentation
    recommendation: None

  - id: E-002
    date: 2026-07-31
    source: PublicDigit Implementation
    type: friction
    description: PKS document catalog unclear on when to produce ADRs
    impact: delayed documentation
    recommendation: Clarify ADR timing in document catalog
```

### Why This Matters

Operational evidence drives **evidence-based evolution** — not speculation.

---

## Practical Thing 6 — Create PKS Integration Guides

### What This Means

Document how to consume PKS — for different audiences and tools.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Write a Developer Guide | How to use PKS |
| 2 | Write an AI Integration Guide | How AI tools consume PKS |
| 3 | Write an API Guide | How to query PKS programmatically |
| 4 | Write a Governance Guide | How PKS fits into governance |

### Example Guide Structure

```markdown
# PKS Integration Guide

## For Developers
- How to read PKS concepts
- How to use PKS for decision-making
- How to contribute to PKS

## For AI Tools
- How to load PKS YAML
- How to query PKS
- How to generate documents from PKS

## For API Consumers
- REST endpoints
- GraphQL queries
- Authentication
```

### Why This Matters

PKS becomes **adoptable** — not just available.

---

## Practical Thing 7 — Prototype AI Agent Integration

### What This Means

Build a prototype AI agent that uses PKS to make decisions.

### What to Do

| Step | Action | Output |
|------|--------|--------|
| 1 | Create a simple agent that uses PKS | PKS-powered assistant |
| 2 | Agent reads concepts from PKS | Understands domain |
| 3 | Agent uses relationships | Navigates domain |
| 4 | Agent applies governance | Knows what requires approval |
| 5 | Agent produces documentation | Follows document catalog |

### Example Agent Flow

```
User: "What is a Decision in PKS?"

Agent:
1. Query PKS for Decision concept
2. Return definition, evidence, relationships
3. Suggest next steps

User: "Create an ADR for a new Decision"

Agent:
1. Load document catalog
2. Apply ADR template
3. Validate against PKS
4. Return structured ADR
```

### Why This Matters

PKS becomes **operational** — not just stored.

---

## Summary of Practical Things

| Priority | Practical Thing | What It Does | How to Start |
|----------|-----------------|--------------|--------------|
| **1** | **Collect Operational Evidence** | Feedback loop | Create `evidence.yaml` |
| **2** | **Publish PKS as Knowledge Graph** | Queryable | Export YAML → JSON, simple API |
| **3** | **Integrate with Claude Code** | AI assistant | Create PKS tools |
| **4** | **Create Document Catalog Service** | Executable guidance | Build simple API |
| **5** | **Build PKS Dashboard** | Visual exploration | UI with Vue.js |
| **6** | **Write Integration Guides** | Adoption | Docs for developers and AI |
| **7** | **Prototype AI Agent** | Operational use | Simple PKS-powered agent |

---

## What to Do Now

| Step | Action |
|------|--------|
| **1** | **Choose 1 practical thing** — Start with the highest value |
| **2** | **Build a prototype** — Don't aim for perfection |
| **3** | **Collect operational evidence** — Record what works, what doesn't |
| **4** | **Feedback to PKS and KnowledgeOS** — Evidence-based evolution |

---

**Phase II is closed. Phase III is operational validation. The practical things above are actionable, low-risk, and evidence-driven. Start with collecting operational evidence, then integrate PKS with tools, then build the dashboard. The evidence will guide the next evolution.**