Yes. I would make one important change compared to a typical prompt.

Most people ask Claude:

> "Draw C4 diagrams."

That usually produces **generic** diagrams that don't match the actual architecture.

Instead, Claude should **derive the C4 diagrams from your frozen architecture** (AKB, BDR, ADRs, Blueprint, Traceability Matrix, Package Structure). The diagrams should be treated as **architecture views**, not new design artifacts.

Below is the prompt I would use.

---

# Prompt for Claude Code

```text
You are acting as a Principal Software Architect, DDD expert, Enterprise Architect, and C4 Model expert.

## Goal

Create a complete C4 Architecture Model (PlantUML) for this project.

This is NOT an architecture discovery exercise.

The architecture has already been completed and approved.

The C4 diagrams must faithfully represent the approved architecture.

Never invent new architecture.

Never simplify the architecture.

Never redesign the architecture.

The diagrams are documentation only.

---------------------------------------------------------
ARCHITECTURAL AUTHORITY
---------------------------------------------------------

The following documents are authoritative, in priority order.

1. Architecture Constitution
2. Strategic Architecture Landscape v1.0
3. Implementation Landscape v1.0
4. Push B Architecture Blueprint v1.0
5. Boundary Decision Register (BDR v1.1)
6. ADR-T log
7. Canonical Event Catalog
8. Round 50 documents
9. Package Structure & Naming
10. Implementation Coding Standard
11. Implementation Traceability Matrix

If two documents disagree:

STOP.

Report the inconsistency.

Do not invent architecture.

---------------------------------------------------------
TECHNOLOGY
---------------------------------------------------------

Backend

Laravel
PHP 8.x

Frontend

Vue 3
Inertia.js

Database

PostgreSQL

Architecture

Modular Monolith

DDD

Hexagonal Architecture

CQRS-light

Transactional Outbox

Inbox

Event-driven

Repository Pattern

Application Services

Value Objects

Aggregates

Domain Events

---------------------------------------------------------
IMPORTANT
---------------------------------------------------------

DO NOT model this as microservices.

This is a modular monolith.

Bounded Contexts are NOT deployment units.

Containers are deployment/runtime containers, not DDD contexts.

---------------------------------------------------------
OUTPUT
---------------------------------------------------------

Create the following directory.

docs/architecture/c4/

---------------------------------------------------------
DOCUMENTS
---------------------------------------------------------

01_System_Context.md

02_Container.md

03_Component.md

04_Code.md

05_Runtime_Event_Flow.md

06_Deployment.md

README.md

---------------------------------------------------------
PLANTUML
---------------------------------------------------------

Generate PlantUML using the official C4-PlantUML library.

Each markdown document contains

• explanation

• assumptions

• PlantUML

• architectural rationale

Store every PlantUML diagram separately under

docs/architecture/c4/plantuml/

Example

SystemContext.puml

Container.puml

Component_Contestation.puml

Component_Adjudication.puml

Component_Election.puml

Code_Challenge.puml

Code_Determination.puml

Runtime_CorrectionLoop.puml

Deployment.puml

---------------------------------------------------------
LEVEL 1
---------------------------------------------------------

System Context

Show

External actors

Diaspora Member

Candidate

Election Officer

Committee

Auditor

Administrator

System

NRNA Governance Platform

External systems

Email

Identity Provider (if approved)

External Trust Anchor (if documented)

Show only approved relationships.

---------------------------------------------------------
LEVEL 2
---------------------------------------------------------

Container Diagram

Remember

This is NOT microservices.

Containers should include

Browser

Vue 3 + Inertia SPA

Laravel Application

Queue Workers

Scheduler

PostgreSQL

Redis (if used)

Outbox

Object Storage (if documented)

Explain why bounded contexts are inside Laravel.

---------------------------------------------------------
LEVEL 3
---------------------------------------------------------

Create one component diagram for EACH confirmed bounded context.

At minimum

Contestation

Adjudication

Voting

Evidence

Appointment

If implementation already exists, show concrete components.

Show

Application Services

Aggregates

Repositories

Policies

Domain Services

Read Models

Ports

Adapters

Outbox

Inbox

DO NOT expose Eloquent inside Domain.

Show dependency direction.

---------------------------------------------------------
LEVEL 4
---------------------------------------------------------

Generate code-level diagrams for every implemented aggregate.

Challenge

Determination

Show

Aggregate

Value Objects

Domain Events

Repository Interface

Policies

Exceptions

Factories

Application Service

Infrastructure Repository

Mapper

Do NOT include framework classes.

---------------------------------------------------------
RUNTIME VIEW
---------------------------------------------------------

This is NOT standard C4.

Document it separately.

Generate sequence diagrams for

Challenge Raised

↓

Challenge Routed

↓

Determination Issued

↓

Election Correction Applied

↓

Challenge Resolved

Show

Outbox

Relay

Inbox

Transaction boundaries

CorrelationId

CausationId

EventId

---------------------------------------------------------
DEPLOYMENT VIEW
---------------------------------------------------------

Deployment diagram

Browser

↓

Nginx

↓

Laravel

↓

Queue Worker

↓

Scheduler

↓

PostgreSQL

↓

Redis

Include

Transactional Outbox

Explain deployment assumptions.

---------------------------------------------------------
DDD RULES
---------------------------------------------------------

Every Component diagram must identify

Bounded Context

Aggregate

Application Layer

Domain Layer

Infrastructure Layer

Repository

Events

Policies

Read Models

Ports

Adapters

---------------------------------------------------------
QUALITY RULES
---------------------------------------------------------

Every diagram must

match Blueprint

match ADRs

match Traceability Matrix

match Package Structure

No invented events.

No invented repositories.

No invented aggregates.

No invented services.

---------------------------------------------------------
DELIVERABLES
---------------------------------------------------------

At completion provide

1.

Generated files

2.

Architecture assumptions

3.

Any inconsistencies found

4.

Recommendations for missing diagrams

Do not continue beyond documentation.

Do not modify architecture.

Produce documentation only.
```

---

## One additional recommendation

Since your project is a **DDD Modular Monolith**, I would **not stop at standard C4**. Ask Claude to generate additional PlantUML diagrams that complement the C4 views:

* **Context Map** (showing relationships between bounded contexts using DDD patterns such as Partnership, Customer–Supplier, Conformist, ACL, Open Host Service, etc., where applicable).
* **Aggregate Relationship Diagram** (aggregates and their references, respecting aggregate boundaries).
* **Event Storming / Event Flow Diagram** (domain events flowing between contexts).
* **Hexagonal Architecture Diagram** (ports, adapters, application layer, domain layer, infrastructure layer for a representative context).
* **Package Structure Diagram** (mirroring your frozen package conventions).
* **Sequence Diagrams** for the most important workflows (for example, the full correction loop in Push B).

These diagrams are not part of the official C4 model, but together with the C4 diagrams they will give future architects a much more complete understanding of both the software structure and the DDD design.
