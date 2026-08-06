I would make the commission even narrower.

Right now the risk is that Claude starts redesigning the organisation model. That is **not** what you want.

You want to answer one engineering question:

> **"Is the current redirection mechanism correct, understandable, deterministic and robust?"**

The prompt below keeps Claude in reviewer mode and prevents it from inventing a new architecture.

---

# PBDIGIT-31 — Redirection Mechanism Review (Discovery Only)

## Mission

You are acting as a **Senior Software Architect, DDD Practitioner, Laravel Expert and Product Reviewer**.

This is **NOT** an implementation commission.

This is **NOT** a redesign commission.

This is a **discovery and evaluation commission**.

The objective is to understand the existing redirection mechanism completely, evaluate its quality, and recommend improvements **only if evidence shows they are necessary**.

No code shall be modified.

No architecture shall be proposed before the discovery is complete.

---

# Business Context

PublicDigit is an **organisation-centric platform**.

Every authenticated user belongs to at least one organisation.

The default **PublicDigit** organisation is a bootstrap/platform organisation.

It is **not** the organisation in which the user performs daily work.

The business expectation is:

* if the user has one real organisation → automatically enter it
* if the user has multiple real organisations → ask the user which organisation they want to work in
* the user should always know which organisation they are currently working in

This business expectation is **input only**.

Do not evaluate whether it is correct.

Evaluate whether the current implementation supports it.

---

# Scope

Review only the **organisation redirection mechanism**.

This includes:

* login redirection
* dashboard redirection
* organisation switching
* organisation creation
* logout/login behaviour
* session restoration
* middleware
* routing
* controller decisions
* cached decisions
* organisation context resolution

Do **NOT** redesign elections, membership or multi-tenancy.

---

# Review Process

## Phase 1 — Customer Journey

Document the complete customer journey.

Example:

```text
Register

↓

Login

↓

Determine destination

↓

Organisation selected

↓

Dashboard

↓

Create organisation

↓

Redirect

↓

Logout

↓

Login again
```

Do not use code.

Use business language.

---

## Phase 2 — Business Rules

Identify every business rule currently implemented.

Example:

* redirect after login
* redirect after organisation creation
* redirect after organisation switch
* redirect after invitation acceptance

For every rule state:

* evidence
* where implemented
* verified or inferred

---

## Phase 3 — Redirection Lifecycle

Draw the complete lifecycle.

Example

```text
Authentication

↓

Destination determined

↓

Organisation resolved

↓

Redirect executed

↓

Working session begins
```

---

## Phase 4 — Decision Points

Identify every place where a redirection decision is made.

For each decision answer:

* who makes it?
* controller?
* middleware?
* service?
* helper?
* policy?
* event?
* route?
* cache?
* session?

Produce one table.

---

## Phase 5 — Route Analysis

Review every route involved.

Determine

* where the user enters
* where the user leaves
* redirects
* loops
* duplicated routes
* unreachable routes

Produce one route map.

---

## Phase 6 — Implementation Review

For every redirection mechanism determine

* responsibility
* readability
* cohesion
* duplication
* hidden assumptions
* robustness

Do not suggest improvements yet.

---

## Phase 7 — Runtime Evaluation

Determine possible runtime failures.

Examples

* stale session
* deleted organisation
* revoked membership
* multiple organisations
* no organisation
* cache inconsistency
* infinite redirect
* invalid slug
* bookmarked URL

State

* expected behaviour
* current behaviour
* evidence

---

## Phase 8 — Improvement Opportunities

Only after discovery is complete.

For every improvement provide

| Improvement | Evidence | Business Impact | Risk | Priority |

Recommendations must improve the existing mechanism.

Do **NOT** redesign the platform.

Do **NOT** introduce new architectural concepts unless the evidence proves the current design cannot satisfy the business rules.

---

## Required Deliverables

Produce

1. Customer Journey
2. Business Rule Matrix
3. Redirection Lifecycle Diagram
4. Decision Point Matrix
5. Route Map
6. Runtime Scenario Matrix
7. Findings
8. Improvement Opportunities
9. Overall Evaluation

---

## Evaluation Criteria

Evaluate the current mechanism using:

* Correctness
* Determinism
* Simplicity
* Single Responsibility
* DDD consistency
* User Experience
* Maintainability
* Robustness

Give each criterion a rating from **1–5** with evidence.

---

## Constraints

* Discovery only.
* No code changes.
* No implementation.
* No refactoring.
* No redesign.
* No new DDD model.
* Separate **facts**, **interpretation**, and **recommendations**.
* Every finding must be backed by repository evidence.
* If evidence is missing, write **"Evidence not found."**

---

I like this commission because it stays focused on **improving an existing capability**, not inventing a new one. It also fits the review methodology you've been developing: start with the customer journey, trace the business rules into the implementation, evaluate robustness, and only then discuss improvements.
