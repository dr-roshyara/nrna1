Yes. Based on the architecture we have established, I would make this a **developer guide for agent onboarding into an existing KnowledgeOS**, not a guide for building another AI framework.

The central rule should be:

> **Configure the AI agent to navigate and consume KnowledgeOS; do not copy KnowledgeOS into the agent harness.**

# Developer Guide: Setting Up AI Agents with KnowledgeOS

## 1. Purpose

This guide explains how to integrate an AI coding agent into a project that uses **KnowledgeOS as its authoritative engineering knowledge system**.

The guide applies to multiple AI agents, including:

* Claude Code CLI
* Codex CLI
* future AI engineering agents

The objective is to establish a common architecture:

```text
                    KNOWLEDGEOS
                         │
                         ▼
              PROJECT ENGINEERING TRUTH
                         │
       ┌─────────────────┼─────────────────┐
       │                 │                 │
   Governance        Architecture        DDD
       │                 │                 │
       └─────────────────┼─────────────────┘
                         │
                  Shared Scripts
                         │
             ┌───────────┴───────────┐
             │                       │
          Claude                   Codex
        .claude/              AGENTS.md + .codex/
             │                       │
        Claude CLI               Codex CLI
             │                       │
             └───────────┬───────────┘
                         │
                    SAME CODEBASE
```

The fundamental architectural principle is:

> **One project engineering truth. Multiple AI execution harnesses.**

---

# 2. Core Ownership Principle

An AI agent has two fundamentally different responsibilities:

### Agent execution

The agent needs to know:

* how to operate
* what process to follow
* what it may modify
* when it must stop
* how to invoke verification
* where to find project knowledge

This belongs to the **agent harness**.

### Engineering knowledge

The project needs authoritative information about:

* architecture
* DDD
* governance
* ADRs
* engineering standards
* domain knowledge
* project decisions
* implementation methodology

This belongs to **KnowledgeOS / the project engineering system**.

Therefore:

```text
Agent harness
    │
    │ navigation + operating rules
    ▼
KnowledgeOS
    │
    │ authoritative knowledge
    ▼
Project Engineering Truth
```

Never reverse this relationship.

---

# 3. The Golden Rule: Linked, Not Copied

The agent harness may **reference** KnowledgeOS.

It must not reproduce KnowledgeOS.

### Correct

```text
AGENTS.md

KnowledgeOS is authoritative.

Start here:
docs/knowledge/portal/INDEX.md

Before substantive engineering work:
consult the relevant KnowledgeOS package,
governance, architecture, DDD principles and ADRs.
```

### Incorrect

```text
AGENTS.md

# Architecture

[500 lines copied from architecture documentation]

# DDD

[800 lines copied from DDD principles]

# Governance

[1000 lines copied from KnowledgeOS]
```

The second approach creates a competing source of truth.

The KnowledgeOS principle is:

> **Linked, not copied.**

---

# 4. Step 1 — Identify the KnowledgeOS Entry Point

Before configuring an AI agent, locate the canonical KnowledgeOS entry point.

In this project:

```text
docs/knowledge/portal/INDEX.md
```

The agent should begin KnowledgeOS discovery there.

Do not guess which documentation is authoritative.

The portal should guide the agent toward:

```text
KnowledgeOS
   │
   ├── by role
   ├── by domain
   ├── knowledge packages
   ├── governance
   ├── methodology
   ├── architecture
   └── ADRs
```

The agent harness should contain a **pointer to the portal**, not a copy of its contents.

---

# 5. Step 2 — Identify Engineering Authorities

The agent must know where different types of authority live.

For this project, the relevant navigation includes:

```text
KnowledgeOS
    │
    ├── Governance
    │
    ├── Architecture
    │
    ├── DDD
    │
    ├── ADRs
    │
    ├── Implementation methodology
    │
    └── Project state
```

For example:

```text
engineering/governance/STANDARDS_INDEX.md

engineering/architecture/reference/Engineering_Decision_Model.md

engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md

docs/architecture/governance/DDD_PRINCIPLES.md

docs/knowledge/portal/adr-index.md
```

These locations should be **referenced by the agent operating contract**.

They should not be copied into it.

---

# 6. Step 3 — Establish the Agent Operating Contract

Every AI agent needs an agent-specific operating contract.

The mechanism differs by agent.

### Claude

The Claude project harness lives under:

```text
.claude/
```

with its Claude-specific instruction/configuration mechanisms.

### Codex

The project currently uses:

```text
AGENTS.md
.codex/
```

The exact runtime configuration is Codex-specific.

The architectural rule is the same:

```text
Claude operating contract
        │
        └── points to KnowledgeOS

Codex operating contract
        │
        └── points to KnowledgeOS
```

The two contracts may use different syntax and runtime mechanisms.

They must reference the **same engineering truth**.

---

# 7. Step 4 — Put the Right Things in the Agent Contract

The agent contract should contain five categories.

## A. Identity

Example:

```text
You are an AI engineering agent operating within this repository.
```

## B. Authority

Example:

```text
KnowledgeOS, governance, architecture, DDD principles,
approved ADRs and approved designs establish engineering authority.
```

## C. Navigation

Example:

```text
Start KnowledgeOS discovery at:

docs/knowledge/portal/INDEX.md
```

## D. Operating behavior

Example:

```text
Before substantive engineering work:

1. Understand the task.
2. Consult relevant KnowledgeOS.
3. Check governance.
4. Check architecture.
5. Apply DDD reasoning.
6. Check relevant ADRs.
7. Inspect implementation and tests.
8. Define the authorized change boundary.
9. Implement.
10. Verify.
```

## E. Safety

Example:

```text
If architecture, authority, domain ownership or authorization
is unclear, stop and ask for clarification.
```

---

# 8. Step 5 — Establish the DDD Mindset

The agent contract should establish **how the agent thinks about domain changes**.

It should not copy the entire DDD methodology.

The operating mindset should be:

```text
Problem
  ↓
Domain meaning
  ↓
Ubiquitous language
  ↓
Bounded context
  ↓
Responsibility / ownership
  ↓
Invariants
  ↓
Architecture
  ↓
ADR / approved design
  ↓
Implementation
  ↓
Verification
```

The key instruction is:

> **Start with the responsibility and boundary being changed—not with the class to edit.**

This prevents an AI agent from allowing the existing code structure to dictate the domain model.

---

# 9. DDD Ownership Questions

Before modifying domain behavior, the agent should ask:

### What business/domain responsibility is changing?

Not:

```text
Which class should I modify?
```

Instead:

```text
What domain responsibility is changing?
```

### Who owns that responsibility?

Determine the appropriate:

* bounded context
* aggregate
* entity
* value object
* domain service
* application service
* policy
* domain event

Only where the existing domain model supports those concepts.

### What invariant must remain true?

Identify the behavior that must not be violated.

### What boundary owns the invariant?

This determines where the behavior belongs.

### What architecture already exists?

Consult KnowledgeOS and architecture documentation before introducing new structure.

---

# 10. Step 6 — Establish the Authority Hierarchy

The agent should operate under an explicit hierarchy:

```text
KnowledgeOS / Governance
          ↓
Architecture
          ↓
DDD principles
          ↓
Approved ADRs
          ↓
Approved Design
          ↓
Implementation
          ↓
Verification
```

This prevents an agent from making an implementation decision and then treating that implementation as architectural authority.

For example:

```text
Bad:

AI decides:
"Let's create a new bounded context."

        ↓

AI implements it.

        ↓

AI claims:
"Architecture now has a new bounded context."
```

Instead:

```text
Engineering problem
        ↓
Architecture / DDD analysis
        ↓
Governance / architectural decision
        ↓
Approved design
        ↓
Implementation
```

---

# 11. Step 7 — Integrate Shared Scripts

Engineering scripts should remain **agent-neutral**.

For example:

```text
scripts/
├── check-domain-purity.sh
├── structure-check.sh
└── observations/
    ├── observe.php
    └── doctor.php
```

Both Claude and Codex should invoke the same scripts.

```text
                    Shared Script
                         │
                  ┌──────┴──────┐
                  │             │
               Claude         Codex
```

Do **not** create:

```text
.claude/scripts/check-domain-purity.sh
.codex/scripts/check-domain-purity.sh
```

when the project already has:

```text
scripts/check-domain-purity.sh
```

That would create multiple implementations of the same engineering rule.

---

# 12. Step 8 — Separate Agent-Specific Machinery

Agent-specific mechanisms belong to the respective harness.

For example:

### Claude

```text
.claude/
├── settings
├── hooks
├── Claude-specific skills
├── sessions
└── Claude runtime mechanisms
```

### Codex

```text
AGENTS.md
.codex/
```

The exact structure depends on the agent.

The important architectural property is:

```text
Agent-specific mechanism
        ↓
Agent harness

Engineering knowledge
        ↓
KnowledgeOS
```

---

# 13. Step 9 — Protect Existing Agent Harnesses

When installing a second AI agent, **do not modify the first agent's harness merely to make the second agent work**.

For example:

> Codex must not modify `.claude/**` simply because it is studying Claude's configuration.

Likewise, future Claude changes should not automatically modify `.codex/**`.

Both are peer execution adapters.

```text
             Project Engineering Truth
                       │
              ┌────────┴────────┐
              │                 │
           Claude             Codex
         .claude/          AGENTS.md/.codex/
```

---

# 14. Step 10 — Protect KnowledgeOS

AI agents must not casually modify KnowledgeOS while performing implementation work.

There is an important distinction:

```text
Using KnowledgeOS
        ≠
Changing KnowledgeOS
```

An agent can:

* search KnowledgeOS
* read KnowledgeOS
* reason from KnowledgeOS
* cite KnowledgeOS
* identify missing knowledge

without automatically changing it.

Knowledge changes should be governed separately.

---

# 15. Step 11 — Establish Stop Conditions

Every agent should have explicit stop conditions.

Stop when:

* architectural authority conflicts
* governance authority is unclear
* domain ownership is unclear
* bounded-context ownership is unclear
* implementation requires architectural redesign
* authorization is unclear
* required evidence is missing
* a verification gate produces an unexplained failure
* the requested change exceeds its scope

The correct response is:

```text
Identify conflict
      ↓
Cite evidence
      ↓
Explain impact
      ↓
STOP
      ↓
Ask for resolution
```

Not:

```text
Conflict
   ↓
AI guesses
   ↓
Implementation
```

---

# 16. Step 12 — Distinguish Authorization States

The agent must distinguish:

```text
Authorized
Implemented
Verified
Completed
```

These are separate states.

For example:

```text
Architecture proposal exists
        ≠
Architecture approved

Code exists
        ≠
Implementation authorized

Tests pass
        ≠
Work completed
```

This is particularly important in a governed engineering environment.

---

# 17. Step 13 — Project State

Project state requires special treatment.

Do not automatically assume that an agent-specific memory file is the canonical project state.

For any state artifact, determine:

```text
What does it represent?
Who owns it?
Who reads it?
Who may write it?
Is it authoritative or derived?
Can multiple agents safely modify it?
```

This is especially important for artifacts such as:

```text
.claude/CONTEXT.md
.claude/MEMORY.md
.claude/memory/
```

Do not migrate or share these simply because another AI agent needs project context.

Ownership must first be established.

---

# 18. Step 14 — Verify the Installation

After installing an AI agent, perform a **read-only cold-boot qualification**.

The agent should demonstrate that it can identify:

```text
KnowledgeOS entry point
Governance authority
Architecture authority
DDD authority
ADR navigation
Project state
Verification mechanisms
```

It should also demonstrate:

```text
Claude/Codex boundary
Shared script usage
DDD reasoning
Authorization model
Stop conditions
```

The qualification should make **no changes**.

---

# 19. Step 15 — Independent Architecture Verification

Do not allow the agent that created its harness to be the only reviewer.

Use another agent or human architect to verify:

```text
One engineering truth
        │
        ├── Claude harness
        │
        └── Codex harness
```

Verify:

* no duplicated KnowledgeOS
* no duplicated DDD rules
* no duplicated architecture
* no duplicated governance
* no duplicated scripts
* no unauthorized cross-harness modifications
* no global configuration changes
* correct ownership boundaries

This is especially valuable when introducing multiple AI agents.

---

# 20. Recommended Repository Pattern

The desired project structure is conceptually:

```text
project/
│
├── AGENTS.md
│
├── .codex/
│   └── README.md
│
├── .claude/
│   ├── Claude runtime machinery
│   ├── settings
│   ├── hooks
│   └── Claude-specific mechanisms
│
├── docs/
│   └── knowledge/
│       └── portal/
│           └── INDEX.md
│
├── engineering/
│   ├── governance/
│   ├── architecture/
│   └── knowledge/
│
├── scripts/
│   ├── architecture checks
│   ├── DDD checks
│   └── verification
│
├── docs/
│   ├── architecture/
│   ├── implementation/
│   └── ...
│
├── tests/
│
└── application/
```

The important relationship is:

```text
AGENTS.md ───────────────┐
                         │
.claude/ ────────────────┤
                         ▼
                    KnowledgeOS
                         │
                         ▼
             Project Engineering Truth
                         │
                         ▼
                 Shared Scripts
                         │
                         ▼
                    Codebase
```

---

# 21. What NOT to Do

### ❌ Do not copy KnowledgeOS into `.codex/`

### ❌ Do not copy KnowledgeOS into `.claude/`

### ❌ Do not create separate DDD rulebooks

### ❌ Do not create separate architecture rulebooks

### ❌ Do not duplicate ADRs

### ❌ Do not copy shared scripts into agent directories

### ❌ Do not create Codex versions of Claude hooks unnecessarily

### ❌ Do not make Claude the owner of project architecture

### ❌ Do not make Codex the owner of project architecture

### ❌ Do not automatically move Claude memory into KnowledgeOS

### ❌ Do not treat agent memory as authoritative engineering truth

### ❌ Do not let an agent silently resolve governance conflicts

---

# 22. Definition of Done

An AI agent integration is complete when:

* [ ] project-local agent harness exists
* [ ] agent operating contract exists
* [ ] KnowledgeOS entry point is referenced
* [ ] governance authority is referenced
* [ ] architecture authority is referenced
* [ ] DDD authority is referenced
* [ ] ADR navigation is referenced
* [ ] shared scripts are discoverable
* [ ] agent-specific runtime mechanisms remain agent-specific
* [ ] KnowledgeOS has not been duplicated
* [ ] architecture has not been duplicated
* [ ] DDD rules have not been duplicated
* [ ] governance has not been duplicated
* [ ] shared scripts have not been duplicated
* [ ] global configuration has not been modified
* [ ] existing agent harnesses remain protected
* [ ] stop conditions are defined
* [ ] authorization states are distinguished
* [ ] cold-boot qualification passes
* [ ] independent architecture verification passes

---

# 23. Final Mental Model

The simplest way for a developer to remember the entire architecture is:

```text
┌───────────────────────────────────────────┐
│          PROJECT ENGINEERING TRUTH        │
│                                           │
│ KnowledgeOS                               │
│ Governance                                │
│ Architecture                              │
│ DDD                                       │
│ ADRs                                      │
│ Shared Scripts                            │
└────────────────────┬──────────────────────┘
                     │
          ┌──────────┴──────────┐
          │                     │
       Claude                  Codex
       .claude/            AGENTS.md/.codex/
          │                     │
      Claude CLI             Codex CLI
          │                     │
          └──────────┬──────────┘
                     │
                SAME CODEBASE
```

### The one sentence to remember

> **Configure AI agents to consume KnowledgeOS; never configure KnowledgeOS to belong to an AI agent.**

That is the foundation for a **multi-agent, DDD-oriented engineering platform** in which Claude, Codex, and future agents can evolve independently without creating competing sources of engineering truth.
