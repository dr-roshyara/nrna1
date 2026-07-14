# Knowledge Transfer (KT) Document

## PublicDigit / NRNA Constitutional Governance Platform

### Principal Architect Handover

**Status:** Architecture Baseline v1.0 Established → Execution Phase

---

# 1. Project Overview & Current Status

## Vision

The project is **not merely an online voting system**.

It is a **Constitutional Governance Platform** whose first product capability is trustworthy elections.

The architecture is designed so that future constitutional capabilities (membership, nominations, delegations, adjudication, governance, etc.) can evolve independently while preserving constitutional correctness.

The long-term goal is to create a **provider-independent AI-assisted engineering process** capable of producing high-quality software using Domain-Driven Design, Test-Driven Development, Architecture Governance, and Evidence-Based Decision Making.

---

## Current Phase

The project has completed two parallel workstreams.

### Workstream 1 — Product

Building the PublicDigit platform.

Current feature:

```
PB-004
Election Reaction
```

---

### Workstream 2 — AI Engineering Platform

A provider-independent engineering methodology that governs software development.

Current state:

```
Baseline v1.0 Established

↓

Execution Mode

↓

Operational Qualification (C3 pending)
```

The platform is now considered architecturally stable.

No further platform evolution is allowed unless implementation evidence demonstrates the need.

---

# Current Product Progress

Completed

* Messaging Platform architecture
* Inbox/Outbox architecture
* Contestation context
* Adjudication context
* Event Catalog
* Policy Catalog
* Messaging contracts
* Event Storming
* Strategic DDD model
* PB-003
* PB-004 Step 4A.2 IDD

Current work

```
PB-004

Step 4A.3

Infrastructure implementation
```

---

# Current Status

Architecture

```
Stable
```

DDD

```
Stable
```

Governance

```
Stable
```

Execution

```
Active
```

Platform

```
Frozen (Baseline v1.0)
```

---

# 2. Technology Stack

## Backend

Laravel 12

PHP 8.4

MySQL

Eloquent (Infrastructure only)

PHPUnit

PHPStan

Architecture Tests

---

## Frontend

Vue 3

TypeScript

Inertia.js

Pinia

Vite

Composition API

---

## Architecture

Hexagonal Architecture

Domain Driven Design

Clean Architecture

Event Driven Architecture

CQRS (where beneficial)

Strategic DDD

Architecture Fitness Tests

---

## Development

Git

GitHub

ADR based governance

Developer Guides

Architecture Decision Records

Architecture Knowledge Base

Implementation Process

---

# 3. Architecture & Technical Patterns

---

# Strategic DDD

The most important realization during the project:

> **DDD is not primarily about separation.**
>
> **DDD is about ownership.**

Everything else follows.

Hierarchy:

```
Business Reality

↓

Ownership

↓

Language

↓

Responsibility

↓

Autonomy

↓

Consistency

↓

Architecture

↓

Technology

↓

Code
```

---

# Core Principle

Every architectural discussion begins with

```
Who owns this concept?
```

not

```
Where should this class go?
```

This single question now governs the project.

---

# Bounded Contexts

Current bounded contexts include:

Membership

Election

Contestation

Adjudication

Governance

Shared

Messaging

Identity (planned)

Authorization (planned)

Audit (planned)

---

# Hexagonal Architecture

Domain

↓

Application

↓

Infrastructure

↓

Framework

Never the reverse.

Infrastructure never leaks into Domain.

---

# Tactical DDD

Use:

Entities

Value Objects

Aggregates

Repositories (Domain Ports)

Factories

Specifications

Policies

Domain Events

Application Services

Infrastructure Adapters

---

# CQRS

Used where command/read separation provides value.

Not applied dogmatically.

---

# Messaging

Current platform includes:

Inbox

Outbox

Idempotency

Event versioning

Consumer contexts

Exactly-once semantics where required

---

# Test Driven Development

Development workflow:

```
Business Question

↓

DDD Ownership

↓

Strategic Decision

↓

Implementation Design

↓

RED

↓

GREEN

↓

Regression

↓

Completion Review

↓

STOP
```

---

# Architectural Reviews

Every implementation slice is reviewed before coding.

Review order:

```
Business

↓

Ownership

↓

Architecture

↓

Implementation

↓

Testing
```

---

# 4. Core Business Rules

---

# Election

Election owns:

Election lifecycle

Election identity

Election correction

Election publication

Election policy

---

# Contestation

Owns:

Challenge admission

Challenge lifecycle

Challenge language

---

# Adjudication

Owns:

Legal determination

Binding constitutional decisions

---

# Messaging

DeterminationIssued

↓

Election reacts

↓

ElectionCorrectionApplied

Different domain facts.

Different timestamps.

Never collapsed.

---

# Timestamp Rule

```
DeterminationIssued.occurredAt

≠

ElectionCorrectionApplied.appliedAt
```

These represent different business events.

---

# Ownership Rule

The project consistently distinguishes

Business ownership

from

Operational source of truth.

Example:

Business owner

```
Election BC
```

Current operational source

```
Legacy elections table
```

These are not the same.

---

# ACL Rule

Legacy systems are hidden behind an

Anti-Corruption Layer.

Current design:

```
Election

↓

ElectionExistencePort

↓

LegacyElectionExistenceAdapter

↓

Legacy Database
```

The Domain never knows where data originated.

---

# Architectural Invariants

Current invariants include:

* Domain never imports legacy code.
* ACL is read-only.
* ACL translates.
* Tenant isolation preserved.
* No vote/result data crosses the ACL.
* No distributed transaction.
* Domain never knows implementation source.
* ACL removable without Domain changes.

---

# Business Meaning First

Interfaces express

business meaning

not

technical implementation.

Example:

```
ElectionExistencePort

exists()

```

instead of

```
ElectionDirectory

knows()
```

---

# 5. Engineering Methodology

---

# Plan First

Every feature begins with a plan.

No implementation before approval.

---

# ARB Review

Claude proposes.

Architect reviews.

Only then implementation starts.

---

# TDD

Always

RED

↓

GREEN

↓

Regression

Never code first.

---

# DDD Ownership Review

Every slice begins with

```
Who owns

this concept

this invariant

this timestamp

this event

this persistence

this translation
```

If ownership is unclear

stop.

---

# Completion Review

Each slice ends with

## Product Evidence

DDD correctness

Business behavior

Architecture

Tests

---

## Platform Evidence

Automation

Workflow

Process

Evidence only

No self-verdicts

---

## Learning

Observations

Patterns

Candidate improvements

or

"No learning."

---

# AI Engineering Platform

Current status:

Baseline v1.0

Frozen.

No architectural changes until retrospective.

Platform improvements require implementation evidence.

---

# 6. Current Product State

PB-003

Completed

---

PB-004

Completed

* Strategic design
* Event Storming
* IDD
* Step 4A.1
* Step 4A.2

Current

```
Step 4A.3

Infrastructure
```

---

Step 4A.3 scope

Implement:

ElectionExistencePort

LegacyElectionExistenceAdapter

Composite ElectionRepository

Correction persistence

Dependency bindings

Tests

No lifecycle implementation.

No Election Policy.

No ADR.

No governance work.

---

# 7. Immediate Next Steps

The next authorized work is:

## PB-004 Step 4A.3

Workflow:

1.

Verify DDD ownership

(no redesign)

↓

2.

Write RED tests

↓

3.

Implement GREEN

↓

4.

Regression

↓

5.

Completion Review

↓

6.

STOP

Do not continue to Step 4B.

---

# 8. Architectural Philosophy

The biggest architectural lesson from this project is:

> **DDD is an architecture of autonomous business models.**

Separation is the mechanism.

Ownership is the principle.

Autonomy is the objective.

Technology is the implementation.

---

The project consistently asks:

```
Who owns this concept?
```

instead of

```
Where should this class live?
```

That single shift has produced most of the architectural improvements over the past months.

---

# 9. AI Engineering Platform Status

The AI Engineering Platform is now treated as a product in its own right, but it has entered **Baseline Execution Mode v1.0**.

Current policy:

* No changes to `.claude` architecture.
* No new hooks.
* No new governance artifacts.
* No new automation.
* Gather implementation evidence.
* Revisit improvements only during the scheduled retrospective.

The platform now exists to **support product delivery**, not compete with it.

---

# Bootstrap Prompt for the Next Chat

> I am continuing development of the **PublicDigit Constitutional Governance Platform**. Act as a **Principal Software Architect, Senior DDD Architect, TDD mentor, and Architecture Review Board (ARB) member**. We follow **Business → Ownership → Architecture → Code**. Every implementation begins with **DDD Ownership Analysis**, then an approved **Implementation Design**, then **RED → GREEN → Regression → Completion Review → STOP**. Never skip planning or redesign previously approved architecture unless implementation evidence demonstrates a real deficiency. Treat the AI Engineering Platform as **Baseline v1.0 (frozen)**—do not evolve `.claude` or governance artifacts unless operational evidence justifies it. We are currently implementing **PB-004 Step 4A.3**, which realizes the already-approved IDD for the Election Existence Anti-Corruption Layer. Your role is to review Claude's plans, strengthen Strategic and Tactical DDD, enforce Hexagonal Architecture, ensure business ownership is explicit, and verify both product quality and engineering discipline before any implementation proceeds.
