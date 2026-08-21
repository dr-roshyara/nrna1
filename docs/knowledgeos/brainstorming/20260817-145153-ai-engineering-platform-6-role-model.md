---
source:
  original_name: "ai-engineering-platform-6-role-model.md"
  original_path: "docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md"
  detected_timestamp: "2026-08-17 14:51:53"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "06-operating-model-product"
  type: architecture-proposal
status:
  authoritative: false
  proposed: false
---

This is a very good evolution of the AI Engineering Platform model. What you discovered is that the original **4 roles** were actually the **production lifecycle roles**, but the system also needs **cross-cutting platform roles**.

The important architectural insight:

> Governance is not only a gatekeeper. Governance also creates the operating environment in which the other roles can work safely.

Therefore, Communication Engineer and Knowledge Engineer should not be treated as "extra helpers". They are **platform capabilities**.

I would extend the model like this.

---

# Current model (4 core execution roles)

You started with:

```
1. Governance
2. Architecture
3. Implementation
4. Independent Verification
```

These correspond to the classic engineering lifecycle:

```
Decide → Design → Build → Assure
```

This is correct.

But your KnowledgeOS experience showed missing capabilities.

---

# Extended AI Engineering Operating Model (6 roles)

I would define:

```
                    Human / ARB / Product Authority
                              |
                              |
                    AI Engineering Platform
                              |
        ------------------------------------------------
        |              |              |                |
        v              v              v                v

   Governance     Architecture   Implementation   Verification


        |
        |
        +------------------------------+
        |                              |
        v                              v

Communication Engineer          Knowledge Engineer
```

---

# Role 1 — Governance Engineer

## Mission

Protect authority, lifecycle, and decision integrity.

Question:

> "Who is allowed to decide what?"

Responsibilities:

* session registration
* grants
* handoffs
* START/COMPLETE lifecycle
* acceptance records
* decision records
* policy enforcement

Allowed:

✅ create workflow transitions
✅ enforce gates
✅ record human decisions

Forbidden:

❌ design architecture
❌ write implementation
❌ verify own work

---

# Role 2 — Communication Engineer

This is a very important addition.

Most AI systems fail because information transfer fails.

The Communication Engineer owns:

## Human ↔ AI communication quality

Question:

> "Does everybody understand the current state?"

Responsibilities:

* explain current status
* create actor instructions
* translate technical findings
* prepare handover prompts
* summarize decisions
* detect ambiguity

Example from your project:

You repeatedly asked:

> "Who should act now?"

This was not architecture.
This was not governance.

This was Communication Engineering.

---

Artifacts:

```
NEXT-ACTION.md

ACTOR-HANDOVER.md

SESSION-BRIEF.md

DECISION-SUMMARY.md
```

---

# Role 3 — Knowledge Engineer

This is the second missing role.

Question:

> "Can we trust the knowledge accumulated over time?"

Responsibilities:

* knowledge lifecycle
* evidence linking
* document structure
* supersession
* ADR indexing
* architectural memory

Example:

The rule:

> "Never rewrite a verification report because reality changed."

This belongs here.

Because it protects knowledge integrity.

---

Artifacts:

```
Knowledge Graph

Evidence Registry

ADR Index

Architecture Baseline Registry

Decision History
```

---

# Role 4 — Architecture Engineer

Mission:

Design the system structure.

Question:

> "What should exist?"

Responsibilities:

Strategic:

* bounded contexts
* domain landscape
* context maps
* capability models

Tactical:

* aggregates
* entities
* value objects
* domain services

Artifacts:

```
Architecture Baseline

Context Map

ADR

Domain Model
```

---

# Role 5 — Implementation Engineer

Mission:

Turn approved design into working software.

Question:

> "How do we build it?"

Responsibilities:

* coding
* testing
* refactoring
* deployment
* automation

Artifacts:

```
Source Code

Tests

CI/CD

Infrastructure
```

---

# Role 6 — Independent Verification Engineer

Mission:

Challenge truth.

Question:

> "Can we trust this?"

Responsibilities:

* architecture verification
* implementation verification
* evidence checking
* adversarial review

Artifacts:

```
Verification Report

Findings

Risk Assessment
```

---

# Now the interesting part: relationship with DDD

Your AI Engineering Platform is actually forming its own bounded contexts.

Something like:

```
AI Engineering Platform

├── Governance Context
│
├── Communication Context
│
├── Knowledge Context
│
├── Architecture Context
│
├── Implementation Context
│
└── Verification Context
```

This is very DDD-like.

Each context has:

* responsibility
* vocabulary
* rules
* boundaries
* inputs/outputs

---

# How to implement this in your existing .claude platform

You already have:

```
.claude/platform/

composition_root
session_manager
knowledge_manager
workflow_engine
verification_engine
review_engine
drafting_studio
platform_registry
```

I would extend the registry:

Current:

```yaml
platform_components:
  governance:
  architecture:
  implementation:
  verification:
```

Extend:

```yaml
platform_roles:

  governance_engineer:
    purpose: lifecycle authority
    owns:
      - transitions
      - grants
      - acceptance

  communication_engineer:
    purpose: human-ai coordination
    owns:
      - handovers
      - summaries
      - actor prompts

  knowledge_engineer:
    purpose: knowledge integrity
    owns:
      - evidence
      - documents
      - traceability

  architecture_engineer:
    purpose: system design
    owns:
      - models
      - boundaries
      - ADRs

  implementation_engineer:
    purpose: delivery
    owns:
      - code
      - tests

  verification_engineer:
    purpose: independent assurance
    owns:
      - reviews
      - findings
```

---

# But do not create 6 independent agents immediately

Important.

Your platform should evolve gradually.

I would implement in phases.

## Phase 1 (already mostly exists)

```
Governance
Architecture
Implementation
Verification
```

---

## Phase 2 (next)

Add:

```
Knowledge Engineer
Communication Engineer
```

because these two solve the problems you already experienced:

* duplicate records
* unclear next actor
* misplaced documents
* context loss between sessions
* unclear ownership

---

## Phase 3

Create specialized agents:

```
Architecture Agent

Knowledge Agent

Verification Agent

Implementation Agent

Communication Agent
```

with Governance as orchestrator.

---

My recommendation:

Do not think of Communication Engineer and Knowledge Engineer as "additional workers".

They are the **nervous system and memory of the AI Engineering Platform**.

The four engineering roles are the **hands and brain**.

The two new roles are:

* Communication Engineer → nervous system
* Knowledge Engineer → memory system

Without them, the AI platform can work, but it cannot scale safely. Your recent KnowledgeOS governance journey is actually the evidence that these two roles are needed.
#
Yes, this idea **can and should be integrated**, but **not immediately into the running Architecture work**. From a Senior Principal Architect / DDD perspective, the timing is critical.

The short answer:

> **Do not add Communication Engineer and Knowledge Engineer into the active KOS-ATTR-ARCH-001 Stage 2 decision flow.**
> First finish the current bounded-context decision. Then integrate them into the AI Engineering Platform as a platform evolution (ADR-AIP-04 or similar).

The reason is the same discipline you applied with BC-7:

**Do not redesign the platform while using the platform to discover the platform.**

---

# 1. Where are we now?

The current architecture journey has reached this state:

```
KOS-ARCH-BASELINE
        |
        v
Current Architecture accepted
        |
        v
KOS-ATTR-ARCH-001 Stage 2
        |
        v
ADR-AIP-03
(Governed Session Orchestration BC-7)
        |
        v
PO/ARB decision pending
```

The active architectural question is:

> "What bounded contexts exist in KnowledgeOS?"

Not:

> "How should the AI Engineering Platform itself evolve?"

Those are different decisions.

---

# 2. Where do Communication Engineer and Knowledge Engineer belong?

They do **not belong initially as new bounded contexts inside KnowledgeOS**.

They belong first as:

```
AI Engineering Platform capabilities
```

Think of the distinction:

## KnowledgeOS domain

The product/system being architected.

Example:

```
BC-1 Evidence
BC-2 Voting
BC-3 Appointment
BC-4 Contestation
...
BC-7 Governance Session Orchestration
```

---

## AI Engineering Platform

The machinery helping humans and AI build KnowledgeOS.

Example:

```
Governance capability
Architecture capability
Implementation capability
Verification capability
Communication capability
Knowledge capability
```

Different layer.

---

# 3. The correct integration point

I would introduce it after ADR-AIP-03 decision.

The sequence:

```
CURRENT

Stage 2
 |
 |
 v
ADR-AIP-03
Governed Session Orchestration
 |
 |
 v
PO/ARB decision
 |
 |
 v
Architecture Baseline update
 |
 |
 v
AI Engineering Platform evolution
 |
 |
 v
ADR-AIP-04
Communication & Knowledge Engineering Capabilities
```

---

# 4. Why not now?

Because right now the system is proving something important:

Can AI Engineering Platform govern itself?

The BC-7 discovery already revealed:

* workflow exists
* authority exists
* sessions exist
* handoffs exist
* evidence exists

Now you discovered:

* communication failures
* knowledge placement failures
* context loss
* duplicate acts
* missing records

These are **observations**.

They are not yet architecture decisions.

DDD rule:

> Observations become capabilities only after repeated evidence and bounded responsibility.

You now have enough evidence to propose them, but not to interrupt current decisions.

---

# 5. Proposed future ADR structure

After ADR-AIP-03, create:

## ADR-AIP-04 — AI Engineering Platform Capability Model

Question:

> Should Communication Engineering and Knowledge Engineering become first-class platform capabilities?

Options:

---

## Option A — Accept six-role model

```
AI Engineering Platform

Governance
Architecture
Implementation
Verification
Communication
Knowledge
```

Recommended.

---

## Option B — Keep them as Governance sub-capabilities

Example:

```
Governance
 |
 +-- Communication
 |
 +-- Knowledge Management
```

Possible initially.

---

## Option C — Defer

Until more evidence.

---

My recommendation:

Eventually Option A.

---

# 6. DDD interpretation

Actually your discovery is very DDD-like.

You found two missing capabilities.

## Communication Engineer

Possible bounded capability:

Ubiquitous language:

```
handover
brief
instruction
context transfer
actor readiness
next action
```

Owns:

* prompts
* session briefs
* transition explanations
* actor instructions

---

## Knowledge Engineer

Possible bounded capability:

Ubiquitous language:

```
evidence
traceability
supersession
baseline
decision history
artifact lineage
```

Owns:

* ADR index
* evidence registry
* document lifecycle
* knowledge graph

---

# 7. How this maps to your existing `.claude/platform`

You already have the foundation:

```
.claude/platform/

composition_root
session_manager
knowledge_manager
workflow_engine
verification_engine
review_engine
drafting_studio
platform_registry
```

Notice something important:

You already have:

```
knowledge_manager
```

This means the Knowledge Engineer concept already exists technically.

It is just not yet a formal role.

Same for communication:

You have:

```
session_manager
drafting_studio
```

but no explicit ownership.

---

# 8. The evolution path I recommend

## Phase 0 (now)

Finish:

```
KOS-ATTR-ARCH-001 Stage 2
ADR-AIP-03
```

No platform redesign.

---

## Phase 1

Create:

```
ADR-AIP-04
AI Engineering Platform Role Model
```

Define:

```
6 roles
```

---

## Phase 2

Extend:

```
.claude/platform/registry.yaml
```

Example:

```yaml
roles:

 governance_engineer:
   owns:
    - lifecycle
    - authority

 architecture_engineer:
   owns:
    - ADR
    - models

 implementation_engineer:
   owns:
    - code

 verification_engineer:
   owns:
    - assurance

 communication_engineer:
   owns:
    - handover
    - summaries
    - actor instructions

 knowledge_engineer:
   owns:
    - evidence
    - traceability
    - knowledge lifecycle
```

---

## Phase 3

Introduce specialized agents.

Only then:

```
Governance Agent
Architecture Agent
Implementation Agent
Verification Agent
Communication Agent
Knowledge Agent
```

---

# 9. My architectural recommendation

Your current order should be:

```
1. Finish ADR-AIP-03
       |
       v
2. Close KOS-ATTR-ARCH-001 Stage 2
       |
       v
3. Freeze current architecture decisions
       |
       v
4. Create ADR-AIP-04
       |
       v
5. Introduce 6-role AI Engineering Platform model
```

Do **not** mix ADR-AIP-04 with BC-7.

They are connected, but different bounded contexts.

---

The important insight:

Your discovery of Communication Engineer and Knowledge Engineer is actually evidence that the AI Engineering Platform itself has matured from a "tool collection" into a **socio-technical operating system**.

But mature architecture means knowing **when not to change**.

Right now: finish BC-7 decision first.
After that: formalize the 6-role AI Engineering Platform.
