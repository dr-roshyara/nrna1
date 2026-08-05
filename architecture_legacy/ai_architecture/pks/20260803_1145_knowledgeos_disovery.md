I would **not** ask Claude to *integrate* this directly.

That wording is subtle, but architecturally important.

One of the strongest principles you've developed is:

> **Strategic Discovery discovers. It does not integrate prematurely.**

If you ask Claude to "integrate this into our strategic discovery," it may unconsciously start **changing the architecture** instead of **testing whether the discovery is valid**.

As a **Senior Knowledge Engineer**, I would instead commission Claude to **evaluate whether the Meta-Model itself has emerged from the evidence**.

That is much more consistent with your Evidence → Discovery → Architecture discipline.

---

# Prompt Instructions for Claude Code

## Strategic Discovery Commission — KnowledgeOS Meta-Model Discovery

---

## Role

You are acting as the:

* Principal Strategic DDD Architect
* Principal Knowledge Engineer
* Enterprise Knowledge Architect

You are continuing the **KnowledgeOS Strategic Discovery Programme**.

This is **NOT** an implementation commission.

This is **NOT** a platform engineering commission.

This is **NOT** an ontology implementation commission.

It is a **Strategic Discovery Commission**.

---

# Context

Our previous Strategic Discovery has produced:

* Strategic Domain Landscape
* Documentation Ontology
* Platform Ontology (candidate)
* Semantic Reconciliation
* Evidence Governance
* Research Validation
* Relationship Validation
* Methodology Boundary Review

A new hypothesis has emerged.

We must determine whether it is supported by evidence.

---

# Discovery Hypothesis

A new hypothesis is emerging:

> **KnowledgeOS may contain multiple categories of architectural elements rather than only engineering domains.**

Examples observed during discovery include:

* Engineering Domains
* Engineering Processes
* Engineering Capabilities
* Knowledge Assets
* Runtime Assets
* Governance Assets

These observations have emerged naturally over many commissions.

They have **NOT** yet been formally evaluated.

Treat them only as candidate observations.

---

# Mission

Determine whether the Strategic Discovery has uncovered a **KnowledgeOS Meta-Model**.

Do NOT invent one.

Determine whether one has already emerged from evidence.

---

# First Principle

Evidence before Architecture.

Do not create categories because they appear useful.

Only recognize categories that have repeatedly emerged during discovery.

---

# Investigation

Review the Strategic Discovery corpus.

Determine whether discovered concepts naturally fall into different architectural categories.

For every significant concept discovered, determine:

* What is it?
* Why does it exist?
* What responsibility does it own?
* What lifecycle does it follow?
* Who owns it?
* What architectural category does it belong to?

---

# Candidate Categories

These are hypotheses only.

Do not assume they exist.

Investigate whether evidence supports categories such as:

### Candidate Category A

Engineering Domains

---

### Candidate Category B

Engineering Processes

---

### Candidate Category C

Engineering Capabilities

---

### Candidate Category D

Knowledge Assets

---

### Candidate Category E

Governance Assets

---

### Candidate Category F

Runtime Assets

---

### Candidate Category G

Product Assets

---

Reject any category that lacks sufficient evidence.

Merge categories if the distinction is artificial.

Introduce **no new categories** unless repeated evidence demands them.

---

# Strategic DDD Questions

Do NOT ask:

> Is this reusable?

Instead ask:

* Is this a Domain?
* Is this a Capability?
* Is this a Process?
* Is this Knowledge?
* Is this Governance?
* Is this Runtime?
* Is this Product-specific?
* Is this merely an implementation artifact?

---

# Architectural Boundary Questions

For every candidate category determine:

* Does it belong to KnowledgeOS?
* Does it belong to PKS?
* Does it belong to Product Engineering?
* Does it belong to Runtime?
* Does it belong outside KnowledgeOS?

---

# Required Deliverables

Produce:

**KnowledgeOS_Meta_Model_Discovery.md**

containing:

## 1. Discovery Summary

State whether a Meta-Model has genuinely emerged.

Supported

Partially Supported

Not Supported

---

## 2. Evidence Matrix

For every proposed category provide:

* supporting evidence
* conflicting evidence
* confidence
* open questions

---

## 3. Candidate Meta-Model

Describe only what evidence supports.

Separate:

Confirmed

Candidate

Hypothesis

Rejected

---

## 4. Relationship Analysis

Determine whether relationships exist between categories.

For example:

Domains own Capabilities

Capabilities use Knowledge Assets

Processes produce Knowledge Assets

Governance constrains Processes

Do not assume these.

Validate them.

---

## 5. Boundary Analysis

Determine whether each category belongs to:

KnowledgeOS

PKS

Product

Runtime

or outside the platform.

---

## 6. Remaining Unknowns

Record everything that still lacks evidence.

Do NOT resolve unknowns through reasoning alone.

---

# Constraints

Do NOT:

* redesign KnowledgeOS
* reorganize folders
* modify ADRs
* create implementations
* invent platform services
* invent bounded contexts
* create new terminology

Remain entirely within Strategic Discovery.

---

# Success Criterion

At the end of this commission we should be able to answer:

> **Has Strategic Discovery revealed not only KnowledgeOS domains, but the fundamental categories of architectural elements that constitute KnowledgeOS?**

If the answer is yes, the Meta-Model becomes a candidate for future architectural work.

If the answer is no, explicitly state why and what additional evidence would be required.

---

# Engineering Rule

Maintain the discipline established throughout the project:

* **Evidence before Architecture**
* **Discovery before Classification**
* **Classification before Governance**
* **Governance before Implementation**

Promote nothing beyond the evidence.

---

## Why I recommend this approach

This prompt keeps the work squarely inside **Strategic Discovery**. It does not assume that a meta-model exists, nor does it ask Claude to design one. Instead, it asks Claude to determine whether the many months of discovery have already revealed recurring categories of architectural elements. If they have, that becomes a well-supported candidate for later architecture. If they have not, the commission should explicitly conclude that more evidence is needed. That preserves the evidence-first discipline that has become one of the defining characteristics of your KnowledgeOS approach.
