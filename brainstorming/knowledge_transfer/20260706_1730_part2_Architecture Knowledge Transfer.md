# Architecture Knowledge Transfer (AKB)

# Part 2 — Technical Architecture, DDD, Implementation Patterns & Development Process

**Document Status:** Knowledge Transfer (KT)

**Architecture Release:** 1.1 (Validated by Implementation)

**Audience:** Future Principal Architect / AI Mentor

---

# 1. Technical Stack

The platform intentionally uses a mature, boring technology stack. Innovation belongs in the **domain**, not in frameworks.

## Backend

* PHP 8.x
* Laravel 12
* PostgreSQL
* Eloquent (Infrastructure only)
* PHPUnit
* PHPStan (maximum level)
* Deptrac
* Infection (Mutation Testing)
* Composer

---

## Frontend

* Vue 3
* Inertia.js
* TypeScript
* Tailwind CSS
* Vite

The frontend is intentionally thin.

Business rules belong to the backend.

---

## Database

Primary database

```
PostgreSQL
```

Design principles

* UUID identifiers
* tenant aware
* event-driven integration
* immutable business history where appropriate

---

## Messaging

Current implementation

```
Transactional Outbox
```

Future

```
Outbox

↓

Relay

↓

Inbox

↓

Domain Handler
```

No message broker currently.

Adding Kafka/RabbitMQ is an architectural decision requiring Blueprint v1.1.

---

# 2. Overall Architecture

The architecture is **DDD-first**.

Not Laravel-first.

Not CRUD-first.

Not database-first.

The architecture looks approximately like

```text
Frontend (Vue3)

↓

Application Layer

↓

Domain Layer

↓

Infrastructure Layer

↓

PostgreSQL
```

---

# 3. Layering

Every bounded context follows the same internal structure.

```text
Context

Domain
    Aggregate
    Value Objects
    Domain Events
    Domain Services
    Repository Interfaces

Application
    Commands
    Services
    Ports

Infrastructure
    Repository
    Mapper
    Service Provider
    Persistence
    Event Adapter
```

---

# 4. Domain Driven Design

DDD is the architectural foundation.

Everything follows DDD terminology.

---

## Strategic DDD

Current confirmed bounded contexts

```
Evidence

Voting

Appointment

Contestation

Adjudication
```

Everything else is

* supporting service
* infrastructure capability
* read model

---

## Tactical DDD

Every bounded context contains

* Aggregate Roots
* Value Objects
* Domain Events
* Domain Services
* Repository Interfaces
* Policies
* State Machines

---

# 5. Aggregate Design

Current aggregates

---

## Challenge

Bounded Context

```
Contestation
```

Responsible for

* challenge lifecycle
* admissibility
* routing
* legal resolution

Owns

```
Challenge Aggregate
```

Current lifecycle

```
Raised

↓

Admitted

↓

Routed

↓

Resolved
```

It never modifies elections.

---

## Determination

Bounded Context

```
Adjudication
```

Responsible for

* legal ruling
* legitimacy
* authority
* jurisdiction

Current lifecycle

```
Draft

↓

Issued

↓

Final
```

Determination is immutable after issuance.

---

## Existing Operational Aggregates

Already exist in legacy system

* Vote
* EvidenceEnvelope
* Mandate
* Election

These will migrate later.

---

# 6. Repository Pattern

Repositories belong to the Domain.

Example

```text
ChallengeRepository

DeterminationRepository
```

Infrastructure implements them.

Example

```text
EloquentChallengeRepository

EloquentDeterminationRepository
```

Never the opposite.

---

# 7. Value Objects

Heavy use of immutable Value Objects.

Examples

```
ChallengeId

DeterminationId

Reason

Jurisdiction

IssuedByAuthority

EvidenceEnvelopeRef

ChallengeRef

TenantId
```

Rules

* immutable
* validated at construction
* equality by value
* no setters

---

# 8. Domain Events

Domain Events are first-class citizens.

Examples

```
ChallengeRaised

ChallengeAdmitted

ChallengeDismissed

ChallengeRouted

ChallengeResolved

DeterminationIssued

ElectionCorrectionApplied
```

Events describe

Something important happened.

Never

Please execute this command.

---

# 9. Event-Driven Architecture

The system deliberately avoids synchronous orchestration.

Instead

```
DeterminationIssued

↓

Election reacts

↓

ElectionCorrectionApplied

↓

Contestation reacts

↓

ChallengeResolved
```

Every context owns its own reaction.

---

# 10. CQRS

The project follows lightweight CQRS.

Write side

```
Aggregates

Repositories

Commands
```

Read side

```
Read Models

Projections
```

Current planned projections

```
Results

Legitimacy
```

---

# 11. Eventual Consistency

Consistency boundaries are bounded context boundaries.

Never perform

```
Challenge

↓

direct database update

↓

Election
```

Instead

```
Challenge

↓

Event

↓

Election
```

---

# 12. Clean Architecture

Infrastructure depends on Domain.

Never the opposite.

Forbidden

```
Domain

↓

Laravel
```

Correct

```
Laravel

↓

Infrastructure

↓

Domain
```

---

# 13. Hexagonal Architecture

Ports

```
Repository

IdentityGenerator

TransactionManager

EventOutbox
```

Adapters

```
EloquentRepository

UuidIdentityGenerator

LaravelTransactionManager

OutboxEventAdapter
```

This separation is now considered stable.

---

# 14. Outbox Pattern

Push A implemented

```
Transactional Outbox
```

Flow

```
Aggregate

↓

Repository

↓

Outbox

↓

Commit

↓

Relay
```

Exactly one event is stored atomically with aggregate persistence.

---

# 15. Inbox Pattern

Currently under implementation.

Purpose

* deduplication
* retries
* replay
* idempotency

Architecture

```
Relay

↓

Inbox

↓

Handler
```

Unique key

```
(event_id,

consumer_context)
```

This was an important architectural decision.

---

# 16. Registry Pattern

To avoid hardcoded switches

Old

```php
match($eventType)
```

New

```
Event Registry

↓

Hydrator

↓

Domain Event
```

Every context owns its hydrators.

Shared Infrastructure knows nothing about concrete events.

---

# 17. Transaction Model

Application Service

↓

Transaction Decorator

↓

Repository

↓

Outbox

↓

Commit

Exactly one transaction.

---

# 18. Identity Generation

UUID generation is abstracted.

Port

```
IdentityGenerator
```

Adapter

```
UuidIdentityGenerator
```

No aggregate generates UUIDs directly.

---

# 19. Mapping

Domain never depends on Eloquent.

Instead

```
Domain

↓

Mapper

↓

Model
```

Example

```
DeterminationMapper
```

---

# 20. State Machines

Aggregates use explicit state machines.

Illegal transitions throw domain exceptions.

No magic status updates.

---

# 21. Test Driven Development

Everything follows strict TDD.

Cycle

```
RED

↓

GREEN

↓

REFACTOR
```

Every Push follows

1 Design

2 Architecture Review

3 RED

4 GREEN

5 Refactor

6 Documentation

7 Verification

---

# 22. Architecture Review Gates

No implementation starts before

* Blueprint
* IDD
* Architecture Review

are approved.

Architecture is frozen before coding.

---

# 23. Quality Gates

Every implementation must pass

## Functional

* PHPUnit

## Static Analysis

* PHPStan

## Architecture

* Architecture Fitness Tests

## Boundaries

* Deptrac

## Mutation

* Infection

Only then

```
Verified
```

---

# 24. Documentation Philosophy

Documentation is treated as code.

Primary documents

```
ADR

BDR

Blueprint

IDD

Decision Log

Traceability Matrix

Backlog

Progress Tracker
```

Everything is versioned.

---

# 25. Docs-as-Jira

Instead of Jira

```
BACKLOG.md
```

contains

```
EPIC

↓

PB Ticket

↓

IDD

↓

Progress

↓

Decision Log
```

Every commit references

```
PB-003
```

etc.

---

# 26. Architecture Knowledge Book (AKB)

The AKB has become the project's memory.

Main categories

```
Architecture

Implementation

Research

Governance

Strategic

ADR

Blueprint

Progress

Debt
```

The AI must trust the AKB more than conversation history.

---

# 27. Engineering Process

Current development process

```
Architecture

↓

Blueprint

↓

IDD

↓

Architecture Review

↓

RED

↓

GREEN

↓

Refactor

↓

Verification

↓

Documentation

↓

Merge
```

Every Push follows this process.

---

# 28. Guiding Principles

The following principles have become part of the project's engineering culture:

* Domain before framework.
* Business before database.
* Architecture before implementation.
* Evidence beats memory.
* No undocumented design drift.
* Every architectural change requires an ADR.
* Every implementation traces back to a Blueprint, ADR, and Backlog item.
* Prefer executable governance (architecture tests) over written guidelines.
* Greenfield code is the reference implementation that future migrations should copy.
* Keep bounded contexts autonomous; communicate through events, not direct dependencies.

---

# 29. Current State of the Technical Architecture

The technical architecture is now considered **stable**.

The following are effectively frozen unless an ADR changes them:

* Strategic DDD boundaries.
* Tactical DDD patterns.
* Hexagonal/Clean Architecture layering.
* Repository pattern.
* Outbox architecture.
* Event Registry pattern.
* Transaction decorator.
* Traceability model.
* Development process.
* Quality gates.

Most remaining work is **implementation**, not architectural discovery.

---

# End of Part 2

In **Part 3**, we will cover the **actual implementation status** in detail:

* Every aggregate implemented
* Every repository
* Every port and adapter
* Push A achievements
* Push B current progress
* Backlog (PB-001 ... PB-007)
* What has been built versus what remains
* Technical debt
* Current maturity (~15% of the whole platform)
* Immediate next implementation steps

This will serve as the operational handover for continuing development.
