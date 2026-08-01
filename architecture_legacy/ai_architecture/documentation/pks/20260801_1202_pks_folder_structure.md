# PublicDigit Ecosystem — Final Architecture

## Executive Summary

**You have one product ecosystem with three architectural concerns:**

| Concern | What It Is | Location | Audience |
|---------|------------|----------|----------|
| **PublicDigit** | The product — online voting platform for NGOs and political parties | `./app/` | End users |
| **PKS** | Product knowledge — what PublicDigit knows | `./docs/pks/` | Developers, AI tools, architects |
| **KnowledgeOS** | Engineering platform — how to build software | `./engineering/` | Engineering teams, AI tools |

**They are three bounded concerns inside one ecosystem, not three independent projects.**

---

## The Core Architecture

```
                    PublicDigit Ecosystem
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
   PublicDigit             PKS             KnowledgeOS
   (Product)          (Knowledge)       (Engineering)
        │                    │                    │
        └────────────────────┼────────────────────┘
                             │
                             ▼
                    Operational Evidence
                         (Feedback Loop)
```

---

## The Repository Structure

```
PublicDigit/
│
├── app/                              ← PublicDigit (Product)
│   ├── src/
│   │   ├── Domain/
│   │   ├── Application/
│   │   └── Infrastructure/
│   ├── tests/
│   ├── config/
│   └── database/
│
├── docs/
│   │
│   ├── pks/                           ← PKS (Product Knowledge)
│   │   ├── ontology/
│   │   ├── concepts.yaml
│   │   ├── relationships.yaml
│   │   ├── lifecycles.yaml
│   │   ├── bounded_contexts.yaml
│   │   ├── context_map.yaml
│   │   ├── document_catalog.yaml
│   │   └── templates/
│   │
│   ├── evidence/                       ← Operational Evidence
│   │   ├── observations/
│   │   ├── experiments/
│   │   ├── implementation/
│   │   ├── architecture/
│   │   ├── governance/
│   │   └── retrospectives/
│   │
│   ├── product/                        ← Product Documentation
│   │   ├── vision/
│   │   ├── roadmap/
│   │   └── business/
│   │
│   └── architecture/                   ← Product Architecture
│
├── engineering/                        ← KnowledgeOS (Engineering Platform)
│   │
│   ├── governance/                     ← ES-001..ES-006, standards
│   ├── methodology/                    ← SDM v1.2, EOP v1.2
│   ├── knowledge-os/                   ← Platform vision, capabilities
│   ├── architecture/                   ← Engineering architecture
│   ├── ecosystem/                      ← ECOSYSTEM.md, EVOLUTION.md
│   └── standards/                      ← Cross-product standards
│
└── .claude/                            ← Runtime configuration
```

---

## The Three Concerns — Detailed

### 1. PublicDigit (`./app/`) — The Product

| Aspect | Detail |
|--------|--------|
| **What it is** | Online voting platform for NGOs and political parties |
| **Audience** | NGOs, political parties, their members |
| **Core capabilities** | Online voting, constitutional governance, member management, verification, audit |
| **Evolution** | Features, business requirements, user feedback |

### 2. PKS (`./docs/pks/`) — Product Knowledge

| Aspect | Detail |
|--------|--------|
| **What it is** | Knowledge system about PublicDigit |
| **Audience** | Developers, AI tools, architects, governance bodies |
| **Core content** | Concepts, relationships, bounded contexts, context map, document catalog |
| **Evolution** | Operational evidence from PublicDigit development |

**PKS is product-specific and evolves through operational evidence.**

### 3. KnowledgeOS (`./engineering/`) — Engineering Platform

| Aspect | Detail |
|--------|--------|
| **What it is** | Methodology platform for building software |
| **Audience** | Engineering teams, AI tools |
| **Core content** | SDM v1.2, EOP v1.2, governance model, MCR-1..6, ES-001..ES-006 |
| **Evolution** | Slow, through justified extraction of reusable engineering capabilities |

**KnowledgeOS is cross-product and evolves through extraction from product experiences.**

---

## The Flow of Evidence

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FLOW OF EVIDENCE                                         │
│                                                                              │
│  PublicDigit Implementation                                                 │
│         ↓                                                                   │
│  Operational Evidence (./docs/evidence/)                                   │
│         │                                                                   │
│         ├──────────────────────────────────────────────┐                   │
│         │                                              │                   │
│         ▼                                              ▼                   │
│  PKS (./docs/pks/)                              KnowledgeOS (./engineering/)│
│  └── Product knowledge updated                   └── Methodology extracted  │
│      based on evidence                               based on evidence      │
│                                                                              │
│         ↓                                                                   │
│  Feedback to PublicDigit                                                   │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## The Evolution Rule

> **Business Problem → PublicDigit → Operational Evidence → PKS → KnowledgeOS**

No arrow directly from idea to KnowledgeOS. This rule prevents premature abstraction.

| Rule | Why |
|------|-----|
| **Business Problem** → PublicDigit | Product solves real problems |
| PublicDigit → **Operational Evidence** | Evidence from practice |
| Operational Evidence → **PKS** | Knowledge evolves from evidence |
| PKS → **KnowledgeOS** | Reusable engineering capabilities extracted |

---

## What Connects Them

### `./engineering/ecosystem/ECOSYSTEM.md`

This document defines:

| Section | Content |
|---------|---------|
| **Three concerns** | PublicDigit, PKS, KnowledgeOS — their responsibilities |
| **Flow of evidence** | How operational evidence flows between them |
| **Ownership boundaries** | Who owns what |
| **Evolution strategy** | How each evolves |
| **Release cycle** | Independent or coordinated? |

### `./engineering/ecosystem/EVOLUTION.md`

This document defines:

| Section | Content |
|---------|---------|
| **Evolution rule** | Business Problem → PublicDigit → Evidence → PKS → KnowledgeOS |
| **Change control** | How each evolves |
| **Extraction pattern** | When to extract from PKS to KnowledgeOS |
| **Certification** | When methodology changes require MCA/CDR |

---

## What is NOT in This Architecture

| Item | Why Not |
|------|---------|
| Three separate repositories | They evolve together, share governance, share evidence, share releases |
| PKS in `./engineering/` | Violates ES-005.1 — PKS is product-specific |
| KnowledgeOS in `./docs/` | KnowledgeOS is cross-product methodology |
| Operational evidence in governance | Belongs to the product, not the methodology |

---

## Evolution Status

| Concern | Status | Evolves By |
|---------|--------|------------|
| **PublicDigit** | ⏳ To be built | Business requirements, user feedback |
| **PKS** | ✅ Complete (Phase II) | Operational evidence from PublicDigit |
| **KnowledgeOS** | ✅ PROVISIONALLY CERTIFIED | Extraction from PKS, multi-context evidence |

---

## Summary

| Question | Answer |
|----------|--------|
| **How many projects?** | One ecosystem with three concerns |
| **Where is PublicDigit?** | `./app/` |
| **Where is PKS?** | `./docs/pks/` |
| **Where is KnowledgeOS?** | `./engineering/` |
| **Where is Operational Evidence?** | `./docs/evidence/` |
| **What connects them?** | `./engineering/ecosystem/ECOSYSTEM.md` |
| **What is the evolution rule?** | Idea → Product → Evidence → PKS → KnowledgeOS |
| **Is PKS permanently frozen?** | ❌ No — evolves through operational evidence |
| **Is KnowledgeOS cross-product?** | ✅ Yes — reusable methodology |

---

**One ecosystem, three concerns. PublicDigit is the product. PKS is product knowledge. KnowledgeOS is the engineering platform. Operational evidence flows from PublicDigit to PKS to KnowledgeOS. The evolution rule prevents premature abstraction. PKS evolves through operational evidence; KnowledgeOS evolves through extraction.**