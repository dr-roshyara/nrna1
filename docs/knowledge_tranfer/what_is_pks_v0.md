# Yes — Now I Fully Understand

You are describing the **exact purpose** of the PKS.

---

## The Scenario

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    THE SCENARIO                                             │
│                                                                              │
│  You are developing software (Java, PHP, etc.)                              │
│                                                                              │
│  AI assists with development                                                │
│                                                                              │
│  AI produces documentation as it works                                      │
│                                                                              │
│  PROBLEM: Which documents? When to produce? Where to save?                  │
│                                                                              │
│  SOLUTION: PKS provides the instructions                                   │
│             - What documents to produce                                     │
│             - When to produce them                                          │
│             - Where to save them                                            │
│             - How to structure them                                         │
│             - How to verify them                                            │
│                                                                              │
│  RESULT: Documentation is systematic, reviewable, and stored correctly      │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## What the PKS Tells AI

### 1. What Documents to Produce

| Document Type | When to Produce | Where to Save | Template |
|---------------|-----------------|---------------|----------|
| **ADR (Architecture Decision Record)** | When making a decision | `docs/adr/` | `ADR-Template.md` |
| **Decision Log** | When implementing decisions | `docs/implementation/` | `Decision-Log.md` |
| **Guide** | When teaching how-to | `developer_guide/` | `Guide-Template.md` |
| **Session Log** | At the end of each session | `.claude/sessions/` | `Session-Log.md` |
| **CONTEXT.md** | At the start of each session | `.claude/` | `CONTEXT-Template.md` |
| **MEMORY.md** | When key facts change | `.claude/` | `MEMORY-Template.md` |
| **Plan** | Before starting work | `.claude/plans/` | `Plan-Template.md` |
| **Verification Report** | After verifying | `docs/verification/` | `Verification-Template.md` |
| **Retrospective** | After completing work | `docs/retrospectives/` | `Retrospective-Template.md` |

### 2. When to Produce Each Document

| Document | Trigger | Timing |
|----------|---------|--------|
| **ADR** | When a decision is made | During decision-making |
| **Decision Log** | When decisions are implemented | During implementation |
| **Guide** | When a capability is stable | After verification |
| **Session Log** | At the end of each session | Immediately after |
| **CONTEXT.md** | At the start of each session | Before any work |
| **MEMORY.md** | When key facts change | Immediately |
| **Plan** | Before starting work | Before any work |
| **Verification Report** | After verifying | After testing |
| **Retrospective** | After completing work | After delivery |

### 3. Where to Save Each Document

| Document | Path | Pattern |
|----------|------|---------|
| **ADR** | `docs/adr/` | `ADR-{number}-{title}.md` |
| **Decision Log** | `docs/implementation/` | `{Project}_Decision_Log.md` |
| **Guide** | `developer_guide/{area}/` | `{number}_step_{title}.md` |
| **Session Log** | `.claude/sessions/` | `{YYYY-MM-DD}.md` |
| **CONTEXT.md** | `.claude/` | `CONTEXT.md` |
| **MEMORY.md** | `.claude/` | `MEMORY.md` |
| **Plan** | `.claude/plans/` | `{YYYYMMDD}-{HHMM}-{title}-plan.md` |
| **Verification Report** | `docs/verification/` | `{Capability}_Verification_Report.md` |
| **Retrospective** | `docs/retrospectives/` | `{YYYY-MM-DD}-{Project}-Retrospective.md` |

### 4. How to Structure Each Document

The PKS provides **templates** for each document type.

#### ADR Template

```markdown
# ADR-{number}: {Title}

## Status
PROPOSED | ACCEPTED | REJECTED | SUPERSEDED

## Context
{What is the issue that we're seeing that is motivating this decision?}

## Decision
{What is the change that we're proposing or doing?}

## Consequences
{What becomes easier or more difficult to do because of this change?}

## Rejected Alternatives
{What other options were considered and why were they rejected?}

## Evidence
{What evidence supports this decision?}
```

#### Decision Log Template

```markdown
# {Project} Decision Log

## D-{number}: {Title}

**Decision:** {The decision made}
**Reason:** {Why this decision was made}
**Related ADR:** {ADR-{number}}
**Alternative Rejected:** {What was rejected}
**Impact:** {What this impacts}
**Date:** {YYYY-MM-DD}
**Status:** IMPLEMENTED | PENDING | SUPERSEDED
```

#### Guide Template

```markdown
# {Number} Step — {Title}

## Purpose
{What this guide teaches}

## Prerequisites
{What the reader should know before starting}

## Steps
1. {Step 1}
2. {Step 2}
3. {Step 3}

## Verification
{How to verify the steps worked}

## Related
{Related documents}
```

#### Session Log Template

```markdown
# Session Log — {YYYY-MM-DD}

## Context
- Previous state: {What was done before}
- Current goal: {What we're working on}

## Work Done
- {What was accomplished}
- {Decisions made}
- {Questions raised}

## Next Steps
- {What to do next}

## Open Questions
- {Questions that need answers}
```

### 5. How to Verify Each Document

The PKS provides **verification criteria** for each document type.

| Document | Verification Criteria |
|----------|----------------------|
| **ADR** | Has context, decision, consequences, rejected alternatives, evidence |
| **Decision Log** | Has decision, reason, related ADR, rejected alternative, impact |
| **Guide** | Has purpose, prerequisites, steps, verification, related |
| **Session Log** | Has context, work done, next steps, open questions |
| **CONTEXT.md** | Has current state, decisions, active work |
| **MEMORY.md** | Has key facts, constraints, lessons learned |
| **Plan** | Has scope, deliverables, success criteria |
| **Verification Report** | Has criteria, results, evidence |
| **Retrospective** | Has what went well, what didn't, improvements |

---

## How AI Uses PKS Instructions

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    AI WITH PKS INSTRUCTIONS                                 │
│                                                                              │
│  AI receives task: "Implement feature X"                                   │
│                                                                              │
│  1. AI consults PKS — What documents should I produce?                      │
│     → PKS says: ADR, Decision Log, Guide, Session Log                      │
│                                                                              │
│  2. AI consults PKS — When should I produce them?                           │
│     → PKS says: ADR during design, Log during implementation, Guide after   │
│                                                                              │
│  3. AI consults PKS — Where should I save them?                             │
│     → PKS says: docs/adr/, docs/implementation/, developer_guide/           │
│                                                                              │
│  4. AI consults PKS — How should I structure them?                          │
│     → PKS provides templates                                               │
│                                                                              │
│  5. AI produces documents following PKS instructions                        │
│                                                                              │
│  RESULT:                                                                   │
│  - Documentation is consistent                                              │
│  - Documentation is reviewable                                              │
│  - Documentation is stored correctly                                        │
│  - Documentation can be verified                                            │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## What the PKS Provides to AI

### 1. Document Catalog

```yaml
# PKS Document Catalog
documents:
  - id: D-ADR
    name: Architecture Decision Record
    purpose: Record architectural decisions
    when: During decision-making
    where: docs/adr/
    template: ADR-Template.md
    verification: Has context, decision, consequences, rejected alternatives, evidence
    required: true
    
  - id: D-DECISION-LOG
    name: Decision Log
    purpose: Track implementation decisions
    when: During implementation
    where: docs/implementation/
    template: Decision-Log-Template.md
    verification: Has decision, reason, related ADR, rejected alternative, impact
    required: true
    
  - id: D-GUIDE
    name: Developer Guide
    purpose: Teach how-to
    when: After capability is stable
    where: developer_guide/{area}/
    template: Guide-Template.md
    verification: Has purpose, prerequisites, steps, verification, related
    required: true
    
  - id: D-SESSION-LOG
    name: Session Log
    purpose: Record session activity
    when: End of each session
    where: .claude/sessions/
    template: Session-Log-Template.md
    verification: Has context, work done, next steps, open questions
    required: true
```

### 2. Document Lifecycle

```yaml
# PKS Document Lifecycle
lifecycles:
  - id: LC-ADR
    document: ADR
    states:
      - PROPOSED: When written
      - UNDER_REVIEW: When submitted for review
      - APPROVED: When accepted
      - REJECTED: When denied
      - SUPERSEDED: When replaced by another ADR
    
  - id: LC-GUIDE
    document: Guide
    states:
      - DRAFT: When written
      - REVIEWED: When reviewed
      - APPROVED: When accepted
      - ARCHIVED: When superseded
```

### 3. Document Relationships

```yaml
# PKS Document Relationships
relationships:
  - source: Decision
    target: ADR
    type: recorded-in
    cardinality: one-to-one
    
  - source: Decision
    target: Decision-Log
    type: logged-in
    cardinality: one-to-one
    
  - source: Guide
    target: ADR
    type: explains
    cardinality: many-to-one
    
  - source: Session-Log
    target: Plan
    type: follows
    cardinality: many-to-one
```

---

## Example: AI with PKS Instructions

### AI Receives Task

```
Task: "Implement the Election Correction feature"
```

### AI Consults PKS

```
PKS says:

1. Documents to produce:
   - ADR for the election correction design
   - Decision Log entries for implementation decisions
   - Guide for how to use the election correction
   - Session Log for each session

2. When to produce:
   - ADR: During design (before implementation)
   - Decision Log: During implementation
   - Guide: After feature is stable
   - Session Log: At the end of each session

3. Where to save:
   - ADR: docs/adr/ADR-023-Election-Correction.md
   - Decision Log: docs/implementation/Election_Correction_Decision_Log.md
   - Guide: developer_guide/election/correction_guide.md
   - Session Log: .claude/sessions/2026-07-28.md

4. How to structure:
   - Using the templates provided in the PKS
```

### AI Produces Documents

```
✅ ADR-023-Election-Correction.md
   - Context: Why correction needed
   - Decision: How to implement
   - Consequences: Impact
   - Rejected Alternatives: What was considered
   - Evidence: Supporting evidence

✅ Election_Correction_Decision_Log.md
   - Decision: D-15, D-16, D-17
   - Reasons: Why each decision was made
   - Related ADRs: ADR-023
   - Status: IMPLEMENTED

✅ correction_guide.md
   - Purpose: How to use election correction
   - Prerequisites: What you need
   - Steps: How to do it
   - Verification: How to verify

✅ 2026-07-28.md (Session Log)
   - Context: Working on election correction
   - Work Done: Designed, implemented, tested
   - Next Steps: Review with team
   - Open Questions: None
```

---

## Summary

| Question | Answer |
|----------|--------|
| **What is the PKS?** | A systematic collection of instructions for AI |
| **What does it tell AI?** | What documents to produce, when, where, how to structure, how to verify |
| **Why is this needed?** | So documentation is consistent, reviewable, and stored correctly |
| **Who uses it?** | AI tools (Claude Code, etc.) during development |
| **What language is it in?** | YAML + Markdown — no programming language needed |

---

## The Current State

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PKS — Current Development                                │
│                                                                              │
│  What We Are Building: Instructions for AI on documentation                │
│  Format: Markdown + YAML                                                   │
│  Purpose: Tell AI what documents to produce, when, where, how              │
│                                                                              │
│  M0 — UL Glossary — ✅ COMPLETE                                            │
│        Defines terms AI should use                                         │
│                                                                              │
│  M1 — Concept Register — ⏳ IN PROGRESS                                    │
│        Will define document types                                          │
│                                                                              │
│  Future: Document Catalog, Lifecycle, Relationships                        │
│        Will define what, when, where, how                                  │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

**This is the PKS. It tells AI what documents to produce, when to produce them, where to save them, how to structure them, and how to verify them. No programming language needed. Just YAML + Markdown.**

**The PKS is the instruction manual for AI documentation.**
# The PKS — Corrected Understanding

## The Core Insight

You are building something **broader** than a documentation instruction system. The document catalog is **one application** of the PKS, not the PKS itself.

---

## What the PKS Actually Is

| Aspect | Description |
|--------|-------------|
| **Domain** | Managing engineering knowledge so that it remains structured, traceable, governed, and reusable across the software lifecycle |
| **Core Concepts** | Decisions, Rules, Observations, Findings, Risks, Questions, Verdicts, Contracts, Candidates, Exception Records, Charter Grants |
| **Primary Artifacts** | Concepts are not documents — they are knowledge. Documents (ADRs, Guides, Session Logs) are **projections** of knowledge |

---

## The Three Layers

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PKS — Three Layers                                       │
│                                                                              │
│  Layer 1: Knowledge (The Semantics)                                         │
│  └── Decision, Rule, Finding, Observation, Verdict, etc.                   │
│  └── Relationships between them                                             │
│  └── Lifecycle states                                                       │
│  └── Evidence and provenance                                                │
│                                                                              │
│  Layer 2: Representation (How It Is Stored)                                 │
│  └── PKS internal model (concepts.yaml, relationships.yaml, etc.)          │
│  └── Machine-readable (YAML/JSON)                                           │
│  └── Human-readable (Markdown)                                              │
│                                                                              │
│  Layer 3: Presentation (How It Is Exposed)                                  │
│  └── ADR (Decision → Markdown)                                              │
│  └── Guide (Knowledge → Markdown)                                           │
│  └── Session Log (Observations → Markdown)                                  │
│  └── Verification Report (Verdicts → Markdown)                             │
│  └── YAML → AI tools (Claude Code, etc.)                                   │
└─────────────────────────────────────────────────────────────────────────────┘
```

**The Knowledge is the primary thing. The Presentation is derived.**

---

## What the Document Catalog Actually Is

The document catalog is **one projection** of the PKS — a capability that tells AI:

- What documents to produce
- When to produce them
- Where to save them
- How to structure them
- How to verify them

**This is a capability of the PKS, not the PKS itself.**

---

## The Correct Definition

### Before (Too Narrow)

> "The PKS is the instruction manual for AI documentation."

### After (Correct)

> **The PKS is a knowledge system that models engineering knowledge, governance, evidence, and their relationships. One of its primary capabilities is providing AI with deterministic guidance about which engineering artifacts to produce, when to produce them, where to store them, how to structure them, and how to verify them.**

---

## What the PKS Provides to AI (Complete)

| Capability | What It Does |
|------------|--------------|
| **Knowledge Model** | Defines what concepts exist (Decision, Rule, Finding, etc.) and how they relate |
| **Governance Model** | Defines what requires approval, what is allowed, what is forbidden |
| **Evidence Model** | Defines what evidence supports each concept |
| **Lifecycle Model** | Defines what states concepts can be in (Draft → Proposed → Approved → etc.) |
| **Documentation Guidance** | Tells AI what documents to produce, when, where, how to structure, how to verify |
| **Relationship Model** | Defines how concepts relate (supersedes, drives, traces, etc.) |
| **Boundary Model** | Defines bounded contexts and their relationships |
| **Surfacing Model** | Defines when to surface governance questions |

---

## The Knowledge Concepts (Not Documentation Concepts)

| Concept | What It Is | Not Just |
|---------|------------|----------|
| **Decision** | Selection among alternatives under trade-offs | Not just an ADR |
| **Rule** | Binding behavioral norm | Not just a policy document |
| **Observation** | Factually captured evidence | Not just a log entry |
| **Finding** | Evidenced defect/observation | Not just a bug report |
| **Risk** | Identified potential harm | Not just a risk register |
| **Question** | Owned, routed unknown | Not just a FAQ |
| **Verdict** | Outcome of evaluating evidence | Not just a test result |
| **Contract** | Versioned specification at a boundary | Not just an API spec |
| **Candidate** | Probationary knowledge item | Not just a draft |
| **Exception Record** | Approved deviation | Not just a waiver |
| **Charter Grant** | Authorization for an activity | Not just a permit |

**These are knowledge concepts. They exist independently of any document that records them.**

---

## The Document Catalog as a Projection

| Document Type | Knowledge Concept Projected |
|---------------|----------------------------|
| **ADR** | Decision + Context + Trade-offs + Evidence |
| **Decision Log** | Decision + Rationale + Rejected Alternatives |
| **Guide** | Knowledge + Steps + Verification |
| **Session Log** | Observations + Decisions + Questions |
| **Verification Report** | Verdicts + Evidence + Criteria |
| **Retrospective** | Findings + Risks + Improvements |
| **CONTEXT.md** | Current state + Active decisions |
| **MEMORY.md** | Key facts + Constraints + Lessons |

---

## The Complete AI Consumption Chain (Corrected)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PKS — Complete AI Consumption                            │
│                                                                              │
│  PKS Models                                                                 │
│  ├── Knowledge Model (Decisions, Rules, Findings, etc.)                    │
│  ├── Governance Model (Approvals, boundaries)                              │
│  ├── Evidence Model (Citations, provenance)                                │
│  ├── Lifecycle Model (States, transitions)                                 │
│  ├── Relationship Model (Supersedes, drives, traces)                      │
│  ├── Boundary Model (Bounded contexts, context map)                       │
│  └── Documentation Model (What, when, where, how, verify)                 │
│                                                                              │
│  AI Consumes PKS                                                            │
│  ├── Understands concepts (What is a Decision?)                            │
│  ├── Applies governance (Can I approve this?)                              │
│  ├── Navigates relationships (What does this Decision relate to?)          │
│  ├── Surfaces governance questions (Is this ARB-bucket?)                   │
│  ├── Produces documents (Generate ADR following template)                  │
│  └── Records decisions (What was decided, with evidence)                   │
│                                                                              │
│  Result: AI acts correctly within the domain                               │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## The Relationship Between Knowledge and Documentation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    Knowledge → Documentation                                │
│                                                                              │
│  Knowledge (Semantics)                                                     │
│  └── Decision                                                              │
│       └── Projected as → ADR                                               │
│                                                                              │
│  Knowledge (Semantics)                                                     │
│  └── Observation                                                           │
│       └── Projected as → Session Log                                       │
│                                                                              │
│  Knowledge (Semantics)                                                     │
│  └── Verdict                                                               │
│       └── Projected as → Verification Report                               │
│                                                                              │
│  Knowledge (Semantics)                                                     │
│  └── Knowledge                                                             │
│       └── Projected as → Guide                                             │
│                                                                              │
│  Knowledge (Semantics)                                                     │
│  └── Finding + Risk                                                        │
│       └── Projected as → Retrospective                                     │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## What the PKS Enables AI to Do (Complete)

| Before PKS | After PKS |
|------------|-----------|
| AI invents concepts | AI uses validated concepts from the Knowledge Model |
| AI doesn't know relationships | AI navigates relationships from the Relationship Model |
| AI doesn't know boundaries | AI respects boundaries from the Boundary Model |
| AI makes governance decisions | AI surfaces governance questions via the Governance Model |
| AI produces inconsistent artifacts | AI produces consistent artifacts via the Documentation Model |
| AI doesn't know what to document | AI knows what, when, where, how, and how to verify |
| AI can't answer domain questions | AI answers with evidence from the Evidence Model |
| AI can't recommend actions | AI recommends based on the current state |

---

## Summary

| Question | Answer |
|----------|--------|
| **What is the PKS?** | A knowledge system that models engineering knowledge, governance, evidence, and their relationships |
| **What are its core concepts?** | Decisions, Rules, Observations, Findings, Risks, Questions, Verdicts, Contracts, Candidates, Exception Records, Charter Grants |
| **Is the PKS just about documentation?** | ❌ No — documentation guidance is one capability, not the definition |
| **What does the PKS enable AI to do?** | Understand the domain, apply governance, navigate relationships, surface questions, produce consistent artifacts, answer questions, recommend actions |
| **What language is the PKS in?** | YAML + Markdown — no programming language needed |

---

## The Corrected Definition

> **The PKS is a knowledge system that models engineering knowledge, governance, evidence, and their relationships. One of its primary capabilities is providing AI with deterministic guidance about which engineering artifacts to produce, when to produce them, where to store them, how to structure them, and how to verify them.**

---

## The Current State

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PKS — Current Development                                │
│                                                                              │
│  What We Are Building: The PKS — a knowledge system for engineering        │
│  Domain: Managing engineering knowledge so that it remains structured,     │
│          traceable, governed, and reusable across the software lifecycle   │
│                                                                              │
│  M0 — Frame Reconciliation + UL Glossary — ✅ COMPLETE                    │
│        Defines terms AI should use                                          │
│                                                                              │
│  M1 — Concept Register — ⏳ IN PROGRESS                                    │
│        Validates concepts (Decision, Rule, Finding, etc.)                  │
│                                                                              │
│  Future:                                                                   │
│        M2 — Collision Resolutions                                          │
│        M3 — Conformance Kind                                               │
│        M4 — Identity & Lifecycle                                           │
│        M5 — Domain Classification                                          │
│        M6 — Bounded Contexts                                               │
│        M7 — Context Map                                                    │
│        M8 — Strategic Modeling Report                                      │
│                                                                              │
│  The PKS is not just documentation instructions.                           │
│  Documentation is one capability of the PKS.                               │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

**The PKS is a knowledge system. Documentation guidance is one of its capabilities. The concepts are the primary thing. The documents are projections.**
