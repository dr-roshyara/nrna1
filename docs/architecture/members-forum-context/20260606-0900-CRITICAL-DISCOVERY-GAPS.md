# Critical Discovery Gaps — Final Questions Before Tactical Design

**Date:** 2026-06-06 09:00 UTC  
**Status:** Blocking Implementation Planning  
**Revision:** 1.0

---

## Context

The strategic vision is approved (10/10). The platform concept is clear: **Collective Intelligence Platform** that makes member voice visible, traceable, and difficult to ignore.

However, **5 critical domain concepts remain unresolved**. Until these are clarified, we should NOT finalize aggregates or move to implementation.

This document identifies what we're missing and proposes focused discovery.

---

## Gap 1: Collective Voice as First-Class Concept

### The Problem

Our current model shows:
```
Problem → Ideas → Alternatives → Decision → Initiative
```

But we don't explicitly model **what members actually think**.

### What We're Missing

A formal concept for: **Collective Position** or **Collective Voice**

**Example scenario:**
```
Proposal: Rural Digital Healthcare

Leadership Decision: REJECTED (budget constraints)

But:
Members Support: 78% (3,900 of 5,000)
Members Concerns: 20% (1,000 of 5,000)
Members Neutral: 2% (100 of 5,000)

Current Question:
Is this disagreement explicitly preserved? 
Is "78% support, but leadership rejected it" a first-class concept?
Or just a derived metric?
```

### Questions to Resolve

**Q1:** Should Collective Position be an Aggregate Root?

**Option A: Yes, separate aggregate**
```
CollectivePosition (Aggregate Root)
├── ProposalId
├── SentimentSnapshot: {support: 78%, concerns: 20%, neutral: 2%}
├── EvidenceSummary: [Evidence]
├── Timestamp
└── AlignmentWithDecision: ALIGNED | DIVERGENT | CONFLICTED
```

**Why this matters:** Organization explicitly tracks "member opinion diverged from leadership"

**Option B: No, just metrics in Proposal**
```
AlternativeProposal
├── SentimentMetrics: {support: 78%, ...}
└── (derived, not aggregate)
```

**Why this matters:** Simpler model, less overhead

**Option C: New concept - Consensus Model**
```
Consensus (when to use collective voice vs leadership decision)
├── ProposalId
├── RequiredAlignment: "Must be 70% support" | "Leadership decides"
├── ActualAlignment: 78%
└── DecisionProcess: COLLECTIVE | LEADERSHIP | HYBRID
```

**Q2:** How should minority opinions be preserved?

**Current challenge:** If 22% disagree, are they permanently recorded?

**Example:**
```
2026: "Most want rural healthcare"
      (but 20% had valid concerns)

2030: Someone proposes same thing again
      "Did we try this?"
      "Yes, but we forgot why we hesitated"
      (minority concerns lost)
```

Should minority concerns be **queryable**?

```
"Show me all concerns raised about rural healthcare"
"Show me why members hesitated on this proposal"
"What evidence did we miss?"
```

**Q3:** What should happen when leadership diverges from strong member consensus?

**Scenarios:**

Scenario A: 78% support, leadership rejects
```
Platform shows: "Members strongly support, but leadership chose differently"
Question: Is this a transparency win? Or a trust loss?
```

Scenario B: 78% support, leadership accepts
```
Platform shows: "Members support, leadership agrees"
Question: Do we need to show alignment, or is it obvious?
```

Scenario C: 15% support, leadership accepts
```
Platform shows: "Minority supported, leadership overruled majority"
Question: Is this tyranny of leadership? Or visionary decision?
```

---

## Gap 2: Problem vs Opportunity Model

### The Problem

Current model assumes everything starts with a **Problem**.

```
Problem
    ↓
Ideas
```

But organizations also work with **Opportunities**:

```
"Let's create something new"
"Here's an innovation"
"New collaboration possibility"
"Community initiative"
```

Not every initiative solves a *problem*. Sometimes it *creates value*.

### What We're Missing

A more general concept than "Problem".

**Options:**

**Option A: Use "Problem" for everything**
```
Problem: "Opportunity to expand urban outreach"
(strains the language)
```

**Option B: Separate Problem vs Opportunity**
```
Problem: "Rural education access"
Opportunity: "Partnership with NGO X"

Both lead to Solutions
```

**Option C: Abstract concept like "Subject" or "Initiative Seed"**
```
Subject
├── Problem (negative: fix something)
├── Opportunity (positive: create something)
└── Innovation (exploratory: discover something)
```

### Questions to Resolve

**Q1:** Are Problems and Opportunities different aggregates?

**Q2:** Do they have different lifecycles?

**Example:**
```
Problem:
IDENTIFIED → DISCUSSED → UNDERSTOOD → (solutions proposed)

Opportunity:
IDENTIFIED → SCOPED → VALIDATED → (solutions proposed)

Innovation:
IDENTIFIED → EXPLORED → PROTOTYPED → (solutions proposed)
```

**Q3:** Can one Organization track both?

```
Political Party:
├── Problems (policy gaps)
└── Opportunities (new alliances)

NGO:
├── Problems (needs to address)
└── Opportunities (funding sources)

Cooperative:
├── Problems (member challenges)
└── Opportunities (market openings)
```

---

## Gap 3: Ownership of Collective Knowledge

### The Problem

When a proposal evolves:

```
Idea created by: Member A

Refined by: 200 members

Evidence added by: Members B, C, D, E, ...

Final proposal drafted by: Committee

Ownership: ???
```

**Current question:** Who "owns" the proposal?

### What We're Missing

A model for **Collective Authorship** and **Attribution**.

### Questions to Resolve

**Q1:** Should proposals track authorship chain?

```
Proposal {
  originalAuthor: Member A (who started idea)
  contributors: [Member B, C, D, ...]
  finalDrafter: Committee
  ownership: COLLECTIVE
}
```

**Why it matters:** Credit, attribution, accountability, learning

**Q2:** When leadership makes decision, whose name is on it?

```
Decision {
  decidedBy: Leadership Committee
  proposedBy: Collective (many members)
  
  How is credit assigned for success/failure?
}
```

**Q3:** How should organizational learning record "who knew what"?

```
5 years later:
"Where did this idea originate?"
→ Should go back to Member A

"What evidence was in it?"
→ Should show all contributors

"Why didn't we implement?"
→ Should show decision maker + reason
```

---

## Gap 4: Initiative Lifecycle and Execution Transparency

### The Problem

Current model hands off Initiative to external system:

```
Initiative created
    ↓
(Progress tracked externally)
    ↓
Initiative completed
```

But what happens in middle?

**Questions:**

**Q1:** Who can see execution progress?

- All members in scope?
- Committee members only?
- Everyone?

**Q2:** What information is visible?

```
Option A: Minimal
├── Status: IN_PROGRESS | PAUSED | COMPLETED
├── Completion %
└── Last Update

Option B: Detailed
├── Milestones: [completed, in-progress, pending]
├── Budget: [spent, remaining]
├── Team: [who's responsible]
├── Blockers: [what's preventing]
└── Evidence: [results so far]
```

**Q3:** Should members vote on execution decisions?

```
Initiative blocked because: "Budget ran out"

Members can:
- View blocker
- Discuss solutions
- Request reconsideration of funding
```

**Q4:** How is outcome visibility organized?

```
When initiative completes:

Outcome: "We trained 500 rural nurses"
Impact: "Healthcare access increased 40%"
Cost: "5M spent vs 6M budgeted"
Timeline: "3 months early"
Success: YES

Should this be visible to all members?
```

---

## Gap 5: Organizational Learning

### The Problem

We preserve everything, but **organizational learning is not explicit**.

### What We're Missing

A first-class concept for: **How does organization learn from its decisions?**

### Questions to Resolve

**Q1:** What constitutes "organizational learning"?

```
Option A: Archive (everything saved, searchable)
"Here's what happened"

Option B: Lessons Learned (explicit extraction)
├── What did we try?
├── What worked?
├── What didn't work?
├── Why?
└── How apply next time?

Option C: Pattern Recognition (AI-assisted)
"Projects with these characteristics succeeded"
"These constraints predicted failure"

Option D: All of above?
```

**Q2:** How should lessons be made accessible?

```
When proposing new initiative:
"Similar initiative in 2024 had these challenges"
"Evidence suggests this approach works with this constraint"
"Watch out for: budget, timeline, resource constraints"
```

**Q3:** Who decides what counts as "learning"?

```
Committee extracts lessons?
Community votes on lessons?
System auto-generates from archive?
```

**Q4:** How is learning preserved across leadership changes?

```
Leadership A: Learned lessons
Leadership B: New leaders, same organization
Question: Do they inherit learning?
```

---

## The Integration Question

How do these 5 gaps relate?

```
Collective Voice
        ↓ (informs)
Problem / Opportunity
        ↓ (generates)
Solutions
        ↓ (shaped by)
Collective Knowledge Ownership
        ↓ (leads to)
Initiative Execution
        ↓ (creates)
Organizational Learning
        ↓ (informs next)
Collective Voice (cycle)
```

---

## Discovery Session Structure

I propose a **focused session** on these 5 topics:

### Session 1: Collective Voice (Today)
- Is it a first-class aggregate?
- How are minority opinions preserved?
- What happens when leadership diverges from consensus?

### Session 2: Problem vs Opportunity (Tomorrow)
- Different lifecycles?
- How to model both?
- Configurable per organization?

### Session 3: Collective Authorship (Day 3)
- Who owns refined proposals?
- How is credit assigned?
- Attribution chain?

### Session 4: Initiative Transparency (Day 4)
- What's visible during execution?
- Who can see blockers/decisions?
- Outcome feedback loop?

### Session 5: Organizational Learning (Day 5)
- What counts as learning?
- How is it made accessible?
- Across leadership changes?

---

## Why These Matter

Each gap, if unresolved, risks:

1. **Collective Voice:** Platform becomes forum, not intelligence system
2. **Problem/Opportunity:** Can't support NGOs, only political parties
3. **Collective Authorship:** No accountability for knowledge creation
4. **Initiative Transparency:** Members can't see if ideas become action
5. **Organizational Learning:** Same mistakes repeated; institutional memory lost

---

## Recommendation

**Do NOT proceed to:**
- Bounded context design
- Aggregate finalization
- Implementation planning
- Agent dispatching

**Instead:**
- Resolve these 5 gaps
- Create new documents as we discover
- Iterate on core domain model
- THEN move to tactical DDD

---

## Current Status

```
Strategic Vision:             ✅ APPROVED
Domain Discovery:             ⚠️ 90% COMPLETE
Critical Gaps:                🛑 BLOCKING
Tactical Design:              ❌ NOT APPROVED
Implementation:               ❌ DEFERRED
```

---

**Next Action:** Focused discovery session on Gap 1 (Collective Voice)

**Question for user:** Which gap should we address first?
