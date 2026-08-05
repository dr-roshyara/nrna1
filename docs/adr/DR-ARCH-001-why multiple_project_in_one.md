
# ADR-ARCH-001 — Repository Composition & Architecture Landscape

## Status

**Accepted**

---

## Context

This repository contains several related but distinct initiatives that evolved together during the development of the PublicDIGIT engineering ecosystem.

A reviewer entering the repository for the first time may incorrectly assume that all directories belong to one application.

That assumption is false.

The repository intentionally contains:

* business applications
* engineering platform
* reusable architectural assets
* supporting services

The architectural relationships between these components should be explicit.

---

# Repository Landscape

```
Repository
│
├── PublicDIGIT
│     Primary business application
│
├── KnowledgeOS
│     Engineering platform
│
├── PKS
│     Platform capabilities
│
├── PKS Services
│     Supporting services
│
├── Engineering
│     Architecture
│     Governance
│     Verification
│     Knowledge
│
└── Shared Infrastructure
```

---

# Purpose of each component

## PublicDIGIT

The primary software product.

Contains business capabilities and bounded contexts.

Examples:

* Election
* Governance
* Contestation
* Adjudication

---

## KnowledgeOS

Engineering platform.

Responsible for

* observation
* recommendations
* engineering verification
* developer feedback
* quality measurements

KnowledgeOS improves engineering.

It is not part of the PublicDIGIT business domain.

---

## PKS

Platform capabilities reused by products.

---

## PKS Services

Supporting services used by the ecosystem.

---

## engineering/

Canonical engineering knowledge.

Contains

* architecture
* governance
* verification
* ADRs
* methodology

Not production code.

---

# Architectural Relationship

```
                  Repository

        ┌─────────────────────────┐
        │                         │
        │     engineering/        │
        │  (canonical knowledge)  │
        │                         │
        └────────────┬────────────┘
                     │

     ┌───────────────┼────────────────┐
     │               │                │
     ▼               ▼                ▼

 PublicDIGIT    KnowledgeOS        PKS
     │               │              │
     └───────────────┴──────────────┘
                 Shared ecosystem
```

---

# Why one repository?

This repository is intentionally organized as a **single engineering workspace** because:

* the systems evolve together,
* they share engineering governance,
* they share architectural verification,
* they share engineering knowledge,
* they are versioned together during active development.

This ADR describes the current repository organization.

It does **not** require that future deployments or distributions remain monolithic.

---

# Review Guidance

When reviewing this repository:

**Review PublicDIGIT as the business application.**

**Review KnowledgeOS as the engineering platform.**

**Review PKS and PKS Services as platform capabilities and supporting services.**

Do not assume code in one subsystem belongs to another without following documented dependencies.

---

# Out of Scope

This ADR does **not** define:

* deployment topology
* runtime topology
* microservices
* packaging strategy
* release strategy

Those decisions belong to separate ADRs.

---

# Consequences

Benefits

* reviewers understand repository boundaries
* contributors understand responsibilities
* architecture documents have a common reference
* subsystem ownership is explicit

Trade-offs

* repository is larger than a single-product repository
* reviewers should understand that multiple architectural concerns coexist intentionally

---


# ADR-ARCH-001 — Repository Architecture Landscape and Product Composition

**Status:** Accepted

**Decision Date:** YYYY-MM-DD

**Decision Makers:** Architecture Review Board (ARB)

---

# 1. Context

This repository is **not** a single software application.

It is the engineering workspace for an ecosystem of closely related products, platforms, engineering capabilities, and architectural knowledge that are developed together under a common architectural governance model.

A first-time reviewer naturally assumes:

> "Everything in this repository belongs to one application."

That assumption is incorrect.

The repository intentionally contains multiple architectural concerns that evolve together while remaining logically independent.

The purpose of this ADR is to define:

* what exists,
* why it exists,
* how the components relate,
* how they should be reviewed,
* and which architectural boundaries are intentional.

This ADR is therefore the architectural entry point into the repository.

---

# 2. Architectural Vision

The repository represents an **Engineering Ecosystem**, not a monolithic application.

The ecosystem consists of multiple products that collaborate while remaining independently understandable.

```
                    Engineering Ecosystem

        ┌──────────────────────────────────────────────┐
        │                                              │
        │            Shared Engineering                │
        │ Architecture • Governance • Verification     │
        │ Knowledge • Standards • ADRs                │
        │                                              │
        └──────────────────────────────────────────────┘
                          ▲
                          │
        ┌─────────────────┼────────────────────┐
        │                 │                    │
        ▼                 ▼                    ▼

   PublicDIGIT      KnowledgeOS          PKS Platform

        │                 │                    │
        └─────────────────┴────────────────────┘

               Supporting Platform Services
```

The repository should therefore be viewed as an **engineering landscape** rather than a software application.

---

# 3. Architectural Components

## 3.1 PublicDIGIT

PublicDIGIT is the primary business product.

Its purpose is solving business problems in the public sector.

It contains the business domain model and business bounded contexts.

Typical examples include:

* Election
* Governance
* Contestation
* Adjudication
* Appointment
* Evidence

These bounded contexts represent business capabilities.

PublicDIGIT should always be reviewed through a Domain-Driven Design lens.

---

## 3.2 KnowledgeOS

KnowledgeOS is **not** part of the business domain.

KnowledgeOS is an engineering platform.

Its responsibility is improving how software is engineered.

Typical responsibilities include:

* Engineering Observation
* Metric Collection
* Recommendation Generation
* Developer Feedback
* Engineering Verification
* Operational Qualification
* Evidence Collection
* Engineering Analytics

KnowledgeOS observes engineering.

It never becomes part of the PublicDIGIT business model.

This separation is intentional and protected.

---

## 3.3 PKS

PKS contains reusable platform capabilities.

These capabilities are not specific to PublicDIGIT and are intended to be reusable by multiple products.

Examples include shared platform services, reusable architectural components, or infrastructure capabilities.

---

## 3.4 PKS Services

PKS Services provide operational services supporting the ecosystem.

They are supporting systems rather than business domains.

---

## 3.5 Engineering

The `engineering/` directory is the repository's canonical engineering knowledge base.

It contains:

* Architecture
* Governance
* Verification
* ADRs
* Engineering Standards
* Methodology
* Design Principles
* Knowledge Assets

It is intentionally not production code.

Its role is architectural governance.

---

# 4. Architectural Boundaries

Although developed together, these components have different responsibilities.

```
Business Domain
──────────────────────────────
PublicDIGIT

Engineering Domain
──────────────────────────────
KnowledgeOS

Platform Domain
──────────────────────────────
PKS
PKS Services

Engineering Governance
──────────────────────────────
engineering/
```

These boundaries are intentional.

A capability belongs to one domain only.

---

# 5. Dependency Principles

The repository is intentionally structured to minimize architectural coupling.

The expected dependency direction is:

```
engineering
      ▲

KnowledgeOS

      ▲

PublicDIGIT

      ▲

PKS Services
```

Engineering knowledge governs products.

Products do not govern engineering.

Business domains do not become dependent upon engineering implementation details.

KnowledgeOS may observe PublicDIGIT, but PublicDIGIT should not depend upon KnowledgeOS for business correctness.

This preserves separation of concerns.

---

# 6. Why One Repository?

The decision to maintain a shared repository is intentional.

The primary reasons are:

* common engineering governance
* common architectural standards
* shared verification framework
* shared architectural evolution
* synchronized versioning during active development
* unified engineering review

The repository therefore acts as a **shared engineering workspace**.

This ADR does **not** imply that deployment, packaging, or runtime topology must remain unified.

---

# 7. How Reviewers Should Read This Repository

A reviewer should not approach the repository as a monolithic application.

Instead, reviews should proceed layer by layer.

### Step 1

Review the architectural governance.

```
engineering/
```

Understand:

* architectural principles
* governance
* ADRs
* verification

---

### Step 2

Review PublicDIGIT.

Treat it as a business system.

Review:

* bounded contexts
* aggregates
* domain services
* ubiquitous language

---

### Step 3

Review KnowledgeOS.

Treat it as an engineering platform.

Review:

* observation runtime
* engineering recommendations
* verification
* developer feedback
* engineering lifecycle

Do not review it as business functionality.

---

### Step 4

Review PKS and PKS Services.

Treat them as reusable platform capabilities.

---

# 8. Long-Term Architectural Vision

This repository is intentionally optimized for engineering evolution.

The long-term architectural vision is:

```
Business Products
        ▲

Engineering Platform

        ▲

Engineering Governance
```

Each layer has a different responsibility.

Engineering knowledge evolves independently from business functionality.

Products consume governance but do not redefine it.

---

# 9. Out of Scope

This ADR intentionally does **not** define:

* deployment architecture
* runtime topology
* infrastructure topology
* microservices
* packaging
* release management
* repository split strategy

Those decisions belong to separate ADRs.

---

# 10. Architectural Consequences

## Benefits

* Clear repository navigation for reviewers.
* Explicit separation between business, engineering, and platform concerns.
* Shared engineering governance across products.
* Consistent architectural vocabulary.
* Reduced ambiguity regarding ownership and responsibilities.
* Enables independent evolution of products and engineering capabilities.

## Trade-offs

* The repository is larger than a typical single-product repository.
* Reviewers must distinguish between business functionality and engineering tooling.
* Architectural discipline is required to prevent cross-domain leakage.

---

# 11. Architectural Invariants

The following invariants govern this repository:

1. **PublicDIGIT remains the business domain.**
2. **KnowledgeOS remains an engineering platform.**
3. **Engineering knowledge remains canonical in `engineering/`.**
4. **Platform capabilities remain reusable and independent of business concerns.**
5. **No architectural dependency may violate these responsibility boundaries without an explicit ADR.**

---
