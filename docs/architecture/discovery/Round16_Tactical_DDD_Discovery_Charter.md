# Round 16 Tactical DDD Discovery Charter

**Purpose:** Define the scope and mission of Tactical DDD before discovery work begins.

**Status:** Charter Definition

**Date:** 2026-06-06

**Authorized By:** Round 15 ARB Decision (Round15_ARB_Decision_Record.md)

---

## Mission

Round 16 begins Tactical Domain-Driven Design.

The objective is **no longer evaluating strategic concepts**.

The objective is **discovering the tactical shape of the domain**.

Round 16 is expected to provide concrete evidence for:

* Bounded Contexts
* Context Relationships
* Ubiquitous Language
* Aggregate Candidates
* Domain Events
* Commands
* Policies
* Invariants

---

## Active Constraints

While discovering tactical structure, Round 16 must **continue to observe**:

* **Authority Classification** — Open architectural question. May be resolved through tactical evidence.
* **Evidence → Legitimacy Resolution Track** — Active parallel investigation. May affect aggregate invariants.
* **Architectural Debt Items** — Visible but deferred:
  - Permission → Power
  - Power → Acceptance
  - Trust → Consensus
  - Rules → Implementation
* **Revision Triggers** — Active. Tactical discovery may invalidate strategic hypotheses.

---

## Authorized Inputs

Round 16 is informed by but not bound to:

* Round14_Step10_Architecture_Findings_Package.md
* Round15_ARB_Decision_Record.md

**Critical:** Round 14 findings are treated as **working hypotheses**.

They are **not** treated as truth.

Evidence emerging from bounded context discovery may:

* Support Round 14 hypotheses
* Challenge Round 14 hypotheses
* Refine Round 14 hypotheses
* Invalidate Round 14 hypotheses

All outcomes are valid.

---

## Primary Tactical Discovery Questions

### Question 0: Domain Capability Discovery

**What are the actual business capabilities of the election platform?**

Observations sought:

* Major business functions (e.g., Election Creation, Voter Registration, Voting, Vote Counting, Result Publication)
* Who performs each capability (stakeholders, roles)
* What business outcomes each capability produces
* How capabilities relate to each other
* What processes connect capabilities

Example capabilities to investigate:

* Election Creation and Configuration
* Candidate Management
* Voter Registration
* Eligibility Verification
* Ballot Distribution
* Voting Process
* Vote Collection
* Vote Counting
* Result Publication
* Audit & Compliance
* Identity Verification
* Authority Delegation
* Governance Operations

Expected output: **Capability Map** showing major business capabilities, stakeholders, and responsibilities.

**Why first?** The Round 14 strategic concepts (Authority, Governance, Verification, etc.) are vocabulary that *cuts across* capabilities. They are not automatically bounded contexts. We must first understand what the domain actually *does* before organizing those concepts into contexts.

---

### Question 1: Bounded Contexts

**What bounded contexts exist in the domain?**

Observations sought:

* Areas where language changes meaning
* Areas with distinct business rules
* Areas with different consistency models
* Areas with different stakeholders
* Natural boundaries in the domain

Expected output: Candidate list of bounded contexts with observational evidence for each.

---

### Question 2: Ubiquitous Language

**Which terms have different meanings in different contexts?**

Observations sought:

* "Authority" — different meanings? (authorization vs. power vs. expertise vs. legitimacy)
* "Verification" — different meanings? (validation vs. legitimacy vs. audit)
* "Evidence" — different meanings? (audit trail vs. decision material vs. proof)
* "Governance" — different meanings? (rules vs. authority vs. decision process)
* Other terms that shift meaning across contexts

Expected output: Language matrix showing term → context → meaning.

---

### Question 3: Consistency Boundaries

**Where are the consistency boundaries?**

Observations sought:

* What must be consistent within a single transaction?
* What can be eventually consistent?
* What consistency models apply to different aggregates?
* Where do transactions cross context boundaries?
* Where do events bridge contexts?

Expected output: Consistency requirements per context / per aggregate candidate.

---

### Question 4: Aggregate Candidates

**Which concepts appear to be aggregates?**

Observations sought:

* Which concepts have clear identity?
* Which concepts enforce invariants?
* Which concepts control their own state changes?
* Which concepts are root entities?
* Which other entities cluster around roots?

Expected output: Aggregate candidates with root entities, value objects, contained entities, and invariants.

---

### Question 5: Domain Events

**Which events drive domain behavior?**

Observations sought:

* What state changes matter in the domain?
* What happens after a decision is made?
* What happens after verification succeeds/fails?
* What cascades when authority is granted/revoked?
* What happens when rules change?

Expected output: Domain event catalog with events, triggers, and consequences.

---

### Question 6: Invariants

**Which invariants must always hold?**

Observations sought:

* What rules can never be violated?
* What properties must always be true?
* What consistency requirements are non-negotiable?
* What business rules are inviolable?
* What prevents invalid state?

Expected output: Invariant catalog with invariants, their enforcement context, and validation mechanisms.

---

## Explicitly Forbidden Activities

Round 16 must **NOT**:

* Re-open strategic architecture evaluation
* Select candidate structures (Candidates A, B, C, D remain hypotheses)
* Re-run Round 14 analysis
* Create governance documents
* Design microservices
* Design databases
* Design APIs
* Design implementation details
* Perform implementation work

**Round 16 is discovery. Not implementation. Not architecture selection.**

---

## Expected Deliverables

Round 16 discovery work produces seven artifacts:

### 1. Capability Map

Visual and textual representation of major business capabilities.

Structure:
* Capability name
* Business outcome
* Stakeholders involved
* Processes involved
* Relationships to other capabilities
* Strategic concepts that apply (Authority, Governance, Verification, etc.)

---

### 2. Bounded Context Candidate Map

Visual and textual representation of candidate bounded contexts.

Structure:
* Context name
* Primary responsibility
* Key entities / concepts
* Observational evidence for boundaries
* Relationship to other contexts (candidate)
* Which capabilities it supports

---

### 3. Context Relationship Map

How bounded contexts relate to each other.

Structure:
* Source context → Target context
* Relationship type (shared language, dependency, event flow, etc.)
* Strength of evidence
* Open questions about the relationship

---

### 4. Ubiquitous Language Catalog

Terms used in the domain with their context-specific meanings.

Structure:
* Term
* Contexts where it appears
* Meaning in each context
* Observations about meaning shifts
* Open questions about term scope

---

### 5. Aggregate Candidate Workbook

Candidate aggregates with their structure and invariants.

Structure per aggregate:
* Root entity and identity
* Value objects
* Contained entities
* Invariants
* State transitions
* Commands (candidate)
* Events (candidate)
* Observational evidence

---

### 6. Domain Event Catalog

Events that matter in the domain.

Structure per event:
* Event name
* What triggers it
* What context it originates in
* What state changes it represents
* What other events or actions it causes
* Observational evidence

---

### 7. Invariant Catalog

Rules and constraints that must always hold.

Structure per invariant:
* Invariant statement
* Which context it belongs to
* Which aggregate (candidate) enforces it
* Why it matters
* What would violate it
* Observational evidence

---

## Success Criteria

Round 16 is successful when:

✓ **Major business capabilities are identified and mapped** (Capability Map complete)

✓ Context boundaries are visible and documented with evidence

✓ Core ubiquitous language is identified with meaning shifts mapped

✓ Aggregate candidates exist with clear roots, value objects, and invariants

✓ Domain events are documented with triggers and consequences

✓ Invariants are cataloged with their business significance

✓ Strategic hypotheses from Round 14 can be tested against tactical evidence

The goal is **learning what the domain actually looks like tactically**.

Not implementation.

Not architecture selection.

Discovery.

---

## Revision Triggers

If Round 16 discovery reveals:

* **Authority Classification appears to be [Strategic Concept / Capability / Process / Social Property]** → Note for later round-trip to Q3
* **Evidence → Legitimacy mechanism becomes clear** → Note for architectural debt resolution
* **Round 14 candidate structures are clearly supported/challenged** → Document evidence for future evaluation
* **Bounded contexts invalidate candidate structures** → Flag for governance review
* **New concepts emerge that Round 14 missed** → Preserve for possible Round 17 strategic work

All of these are valid outcomes.

---

## Governance Boundary

This charter is a **work definition document**.

It is **not** a governance document.

It is **not** an architecture document.

It defines:

* What we are investigating
* How we will know when investigation is complete
* What boundaries we will respect
* What inputs guide us
* What activities remain forbidden

---

## Transition to Round 16 Work

Once this charter is approved:

Round 16 Bounded Context Discovery begins.

The seven deliverables listed above guide the work.

---

**STATUS: Charter Ready for Use**

**Next Step: Bounded Context Discovery (when charter is approved and Round 16 work begins)**
