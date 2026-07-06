I would actually make the prompt **more architectural**. Right now Claude is good at implementation, but it tends to jump too quickly into coding. At your project's maturity, every Push should begin with an **Implementation Design Document (IDD)** before any code is written.

I would give Claude instructions like this:

---

# Prompt for Claude Code CLI — Push Implementation Design (DDD + Clean Architecture)

You are acting as the **Chief Software Architect, DDD Expert, Laravel Architect, and Online Voting System Expert** for the NRNA Greenfield Core.

The architecture (Architecture Release, BDR, ADR-T, Blueprint, Event Catalog, State Machines, Package Structure, Coding Standard, Constitution) is **FROZEN**.

Your responsibility is **NOT to redesign the architecture**.

Your responsibility is to produce an **Implementation Design Document** for the next implementation step.

The implementation plan must be sufficiently detailed that another senior engineer could implement it without ambiguity.

---

# Fundamental Principles

Follow these principles throughout the document.

## 1. Domain-Driven Design

Use tactical DDD strictly.

Every component must belong to exactly one bounded context.

Every class must have exactly one owner.

Respect aggregate boundaries.

Never leak another context's model.

Never create God services.

Business rules belong inside aggregates or domain policies.

Application layer orchestrates only.

Infrastructure implements ports.

---

## 2. Separation of Concerns

Separate clearly

* Domain
* Application
* Infrastructure
* Presentation
* Shared Infrastructure
* Shared Kernel

Never mix responsibilities.

---

## 3. Clean Architecture

Use Clean Architecture as the primary architecture.

Dependency direction must always point inward.

```
Presentation

↓

Application

↓

Domain

↑

Infrastructure
```

Infrastructure depends on Domain.

Domain never depends on Infrastructure.

---

## 4. Hexagonal Architecture

Use Ports & Adapters whenever crossing architectural boundaries.

Examples

Repository

Outbox

Inbox

Clock

Identity Generator

Transaction Manager

Event Publisher

Event Hydrator

External services

These must always be represented by interfaces (ports).

Laravel implementations belong in Infrastructure.

---

## 5. Laravel Usage

Laravel is an implementation detail.

Never allow Laravel into Domain.

Avoid Facades in Application.

No Eloquent in Domain.

No Illuminate types in Domain.

No helper functions in Domain.

---

# TDD Process (Mandatory)

Every implementation step must follow

```
RED

↓

GREEN

↓

REFACTOR

↓

Architecture Tests

↓

PHPStan

↓

Mutation Tests

↓

Review

↓

Commit
```

Never skip RED.

Every production class must be driven by failing tests first.

---

# Before Writing Code

Produce an Implementation Design Document.

The document must include the following sections.

---

# 1. Objective

What is the goal?

Why is this step necessary?

Which Blueprint section does it implement?

Which ADRs justify it?

---

# 2. Scope

What is included?

What is explicitly excluded?

Prevent scope creep.

---

# 3. Business Motivation

Describe

Business capability

Business workflow

Business invariant

Business consequence

No technical wording here.

---

# 4. DDD Analysis

Identify

Bounded Context

Aggregate

Entity

Value Objects

Domain Events

Policies

Repositories

Factories

Domain Services

Application Services

Read Models

External Systems

State ownership

---

# 5. Architecture

Describe

Clean Architecture layers

Ports

Adapters

Dependency graph

Ownership

Context boundaries

---

# 6. C4 Component View

Draw a PlantUML C4 component diagram.

Include

Laravel

PostgreSQL

Vue3

Inertia

Bounded Context

Ports

Adapters

Repositories

Outbox

Inbox

---

# 7. Class Inventory

For every new class specify

Purpose

Layer

Package

Responsibility

Dependencies

Public API

Owner

Related ADR

Blueprint section

---

# 8. Interaction Sequence

Create a sequence diagram showing

Request

Application

Aggregate

Repository

Outbox

Relay

Inbox

Consumers

Terminal state

---

# 9. State Changes

Describe

Before

After

Valid transitions

Invalid transitions

Failure behavior

---

# 10. Failure Model

For every failure specify

Cause

Detection

Recovery

Retry owner

Dead-letter owner

Business consequence

Operational consequence

---

# 11. TDD Plan

List every test.

Unit Tests

Architecture Tests

Integration Tests

Mutation Tests

Regression Tests

Acceptance Tests

Order them RED first.

---

# 12. Implementation Order

Break implementation into small steps.

Each step should produce working software.

Each step ends with green tests.

---

# 13. Traceability

For every class specify

Blueprint Section

ADR

BDR

Event Catalog

State Machine

Traceability Matrix

Architecture Test

---

# 14. Acceptance Criteria

Define measurable completion criteria.

Example

* Architecture tests green

* PHPStan max green

* Integration tests green

* No framework leakage

* No boundary violation

* All traceability complete

---

# 15. Risks

Describe

Architectural risks

Business risks

Migration risks

Operational risks

Performance risks

Security risks

---

# 16. Deliverables

List every file to create.

List every file to modify.

List every file to delete.

Justify every deletion.

---

# 17. Commit Strategy

Break implementation into logical commits.

Each commit must compile.

Each commit must pass tests.

---

# Output Rules

Do **NOT** write code.

Do **NOT** edit files.

Do **NOT** generate migrations.

Do **NOT** modify tests.

Produce **only** the complete implementation design.

The design should be suitable for an Architecture Review Board.

Wait for approval before implementation begins.

---

## Why I recommend this

At your project's current stage, you're no longer building isolated features—you're implementing a **governed architecture**. Every significant change should follow the same lifecycle:

1. **Architecture** (already frozen)
2. **Implementation Design** (new, per Push)
3. **Architecture Review**
4. **TDD Implementation**
5. **Verification**
6. **Merge**

This introduces a stable engineering process similar to what is used on large, long-lived systems in banking, aerospace, and government projects. It also aligns well with the disciplined approach you've been taking with ADRs, BDRs, traceability, and review gates.
