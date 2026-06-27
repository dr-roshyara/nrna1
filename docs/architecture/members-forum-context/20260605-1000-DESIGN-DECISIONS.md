# Design Decisions Log

**Date:** 2026-06-05 10:00 UTC  
**Status:** Decisions Documented & Justified  
**Revision:** 1.0

---

## Decision 1: Problem as First-Class Aggregate

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05  
**Proposed By:** Senior DDD Architect

### The Choice
Problem is a standalone Aggregate Root, not nested inside Idea or Proposal.

### Why We Considered Alternatives

**Alternative A: Problem nested inside Idea**
```
Idea
├── ProblemStatement
├── Title
└── Details
```
❌ Rejected because:
- Multiple ideas solve same problem
- Same problem generates many ideas
- Can't aggregate "all solutions to Problem X" without duplication
- Violates DDD principle of bounded aggregates

**Alternative B: Problems generated automatically from discussions**
```
Discussion
  ↓ (if enough engagement)
  ↓ (system creates)
Problem
  ↓
Ideas
```
❌ Rejected because:
- Problems need intentional creation, not accidental
- Understanding requirement comes first
- Members need explicit "problem definition" step

### Our Decision

**Problem is its own Aggregate Root**

```
Problem (Aggregate Root)
├── ProblemId
├── Title, Description
├── Status: IDENTIFIED → DISCUSSED → UNDERSTOOD → ARCHIVED
└── ...

Idea (Aggregate Root)
├── IdeaId
├── ProblemId (reference, not containment)
└── ...
```

### Why This Is Correct

1. **Organizations exist to solve problems**, not to discuss generically
2. **Problem understanding precedes solution generation** — you can't properly evaluate solutions without understanding the problem
3. **Multiple solutions address one problem** — need to see them together
4. **Organizational learning requires problem traceability** — "Did we address rural healthcare?" requires all-solutions-for-that-problem query
5. **DDD principle: Each aggregate has one reason to change** — Problem changes when status/scope changes; Ideas change when refined; these are different reasons

### Evidence This Is Right

- Real organizations work this way: "Here's the problem, let's brainstorm solutions"
- Political parties, NGOs, cooperatives all use this model
- Archives show "Problem X" with all attempts at solutions
- Search "rural education" finds both problems and solutions

### Lifecycle Enforced

```
Problem created (IDENTIFIED)
    ↓ (members discuss)
Problem UNDERSTOOD (threshold reached)
    ↓ (only NOW can ideas be proposed)
Ideas proposed (addressing this problem)
    ↓
Alternatives created, compared, chosen
```

Cannot skip: Ideas can only be created if Problem is UNDERSTOOD.

---

## Decision 2: Idea vs AlternativeProposal as Separate Aggregates

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05

### The Choice
Idea (draft proposal) and AlternativeProposal (mature, formal) are separate aggregates, not states of same aggregate.

### Why Separation Matters

**Without separation (single Idea aggregate):**
```
Idea {
  Status: DRAFT | DISCUSSION | REFINEMENT | PROPOSAL | VOTED | ACCEPTED
  ...
}
```

**Problems:**
- Large aggregate gets bloated with all states
- DRAFT ideas with 0 comments look same as MATURE ideas with 1000 comments
- Can't easily compare alternatives (stored as separate ideas, not as proposals)
- Archive unclear: is this idea or proposal?

**With separation:**
```
Idea (lightweight)
├── Early-stage, being refined
└── Converts to → AlternativeProposal

AlternativeProposal (heavyweight)
├── Mature, formal, comparable
├── Can be compared side-by-side
└── Can become Initiative
```

### Our Decision

**Two aggregates, clear lifecycle:**

```
Idea Status:     DRAFT → DISCUSSION → REFINEMENT → MATURE
                                                        ↓
                                          AlternativeProposal
                                          Status: CANDIDATE → SELECTED → IMPLEMENTATION → COMPLETED
```

### Why This Is Correct

1. **Different invariants:** Idea's invariant is "can be refined". Proposal's invariant is "can be decided on"
2. **Different lifecycles:** Idea lifecycle is refinement; Proposal lifecycle is decision
3. **Better bounded contexts:** Refinement context owns Ideas; Decision context owns Proposals
4. **Clearer comparison:** Alternatives shown side-by-side, all in CANDIDATE status
5. **Archive clarity:** Archived proposals show which were chosen, which weren't

### Real Example

```
Problem: Improve rural education

Idea 1: "Use digital learning" (status: MATURE)
Idea 2: "Train more teachers" (status: REFINEMENT)
Idea 3: "Online curricula" (status: DISCUSSION)
        ↓
        (Idea 1 & 2 become ready)
        ↓
AlternativeProposal A: "Digital Learning Program" (status: CANDIDATE)
AlternativeProposal B: "Teacher Training Initiative" (status: CANDIDATE)
        ↓
        (Leadership compares, chooses A)
        ↓
AlternativeProposal A: (status: SELECTED)
AlternativeProposal B: (status: CANDIDATE - preserved for history)
        ↓
Initiative: "Digital Learning Program" (status: IN_PROGRESS)
```

---

## Decision 3: Sentiment ≠ Evidence

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05  
**Proposed By:** Senior DDD Architect (after risk assessment)

### The Choice
Members vote on "what they think" (Sentiment) separately from "what's proven" (Evidence). These are not the same thing.

### Why This Matters

**Without separation (traditional voting):**
```
Proposal: Digital Voting

Upvotes: 3,500
Downvotes: 500

Result: "Members want digital voting" ✓
```

**Problems:**
- High votes don't mean good evidence
- Popular ideas might be wrong
- Mob rule possible
- Expert knowledge ignored

**With separation:**
```
Proposal: Digital Voting

Sentiment: {Support: 3,500, Concerns: 500}
Evidence: {Research: 5 papers, Expert: 2, Case: 3, Data: 4}
Evidence Quality: STRONG

Interpretation:
"Members want this, AND research supports it"
vs
"Members want this, BUT research contradicts it"
```

### Our Decision

**Two separate signals:**

```
Sentiment (members.VOICE)
├── Support (members like this)
├── Concerns (members worry about this)
├── Neutral (no strong opinion)
└── HighPriority (members think urgent)

Evidence (external.FACTS)
├── ResearchReport (academic study)
├── ExpertOpinion (domain expert)
├── FinancialAnalysis (cost/benefit)
├── CaseStudy (similar implementation)
└── Data (real-world numbers)
```

### Why This Is Correct

1. **Democracy vs Truth:** Members should have equal voice, but evidence quality varies
2. **Prevents tyranny of majority:** Good ideas can have minority support; bad ideas can have majority
3. **Real-world decisions:** "Members want it, but budget doesn't allow" → transparent reason
4. **Evidence quality matters:** Research by Nobel laureate > social media claims
5. **No weighting by authority:** Evidence evaluated on quality, not who submitted it

### Real Example from Nepal/India Politics

```
Proposal: Privatize public education

Sentiment: Support: 40%, Concerns: 55%, Neutral: 5%
Evidence: Concerns: 50 research papers about failures, 20 expert opinions against
          Support: 3 ideology papers, 0 successful case studies

Decision: Despite 40% support, rejected due to research evidence

Result:
"Members divided, but research clearly shows risks"
(More legitimate than "50% + 1 won")
```

### Why NOT Simple Voting

- **Simple democracy (1 person = 1 vote) fails** when:
  - Majority is uninformed
  - Minority has better evidence
  - Manipulation is possible

- **Expert override fails** when:
  - Experts have special interests
  - Members are excluded from voice
  - Trust erodes

- **Our way (sentiment + evidence)** succeeds because:
  - Members heard AND considered
  - Evidence evaluated objectively
  - Decisions are explainable
  - No mob rule, no expert autocracy

---

## Decision 4: Reconsideration, NOT Appeal

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05

### The Choice
Members can request **Reconsideration** (new evidence → review) but not **Appeal** (override decision).

### The Difference

**Appeal (NOT used here)**
```
Decision made: REJECTED
Member files appeal: "You're wrong"
Result: Decision overridden by higher authority

Problem:
- Creates endless appeal loops
- "Higher authority" becomes de facto decision maker
- Original authority loses power
```

**Reconsideration (USED here)**
```
Decision made: REJECTED
Member files reconsideration: "Here's NEW EVIDENCE"
Leadership reviews: Evidence meaningful?
Result: Either decision stands OR new decision made

Benefit:
- Only new facts trigger review, not disagreement
- Keeps original decision maker in authority
- Encourages evidence gathering
- Clear end condition
```

### Our Decision

**ReconsiderationRequest aggregate:**
- Members can submit: "Decision was wrong because [NEW EVIDENCE]"
- Leadership reviews: Is evidence actually new? Does it change things?
- Result: Decision stands OR reversed (not appealed to higher authority)

### Why This Is Correct

1. **Respects leadership authority** without giving them absolute power
2. **Encourages evidence gathering** instead of "I disagree"
3. **No appeal hierarchy** (eliminates endless escalation)
4. **Clear boundary:** New evidence required (not just disagreement)
5. **Fair to underdog ideas:** If new research emerges, can reconsider

### Real Example

```
2026: "Rural digital voting rejected due to budget constraints"

2027: New evidence emerges
├── International donor offers funding
├── Technology cost dropped 50%
└── New research shows benefits

Reconsideration request: Filed with 3 new evidence items

Leadership reviews: "New circumstances change our decision"

Result: Decision RECONSIDERED and REVERSED

vs Appeal:

2026: Decision made
2027: "That was wrong, appeal to Central Committee"
Central Committee: "Original decision stands"
2028: "Appeal that decision"
...endless loop...
```

---

## Decision 5: Multiple Alternatives Preserved Forever

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05

### The Choice
All Alternative Proposals (chosen AND rejected) are preserved permanently in archive. No "delete losing option" behavior.

### Why This Matters

**Without preservation (traditional):**
```
Proposal A: Chosen
Proposal B: Rejected (deleted from history)

5 years later: "Why did we choose A?"
Answer: Lost in time
```

**With preservation:**
```
Proposal A: Chosen (status: SELECTED → IMPLEMENTED → COMPLETED)
Proposal B: Rejected (status: CANDIDATE → ARCHIVED)

5 years later: "Why did we choose A?"
Answer: "See Proposal B — we considered it but evidence favored A"
```

### Our Decision

**Archive all proposals, mark status, preserve comparison:**

```
AlternativeProposal {
  proposalId: A,
  status: IMPLEMENTED (chosen)
  ...
}

AlternativeProposal {
  proposalId: B,
  status: ARCHIVED (not chosen)
  reasonNotChosen: "Research showed implementation cost 3x estimate"
  ...
}
```

### Why This Is Correct

1. **Organizational learning:** "What did we try? What didn't work?"
2. **New context:** New members see alternatives tried before
3. **Prevents rediscovery:** "Let's try that idea" → "We already tried it, here's why it failed"
4. **Legitimacy:** Rejected members see all options were considered
5. **Accountability:** Archive shows which proposals were seriously considered
6. **Future opportunity:** If circumstances change, old proposal can be reconsidered

### Real Example: Sri Lanka Civil War Prevention

```
1980s: Multiple proposals existed
├── Proposal A: Gradual devolution (chosen)
├── Proposal B: Immediate federalism (not chosen)
└── Proposal C: Mixed model (not chosen)

1990s: Conflict escalated

2000s: "If only we'd done B or C"

But if all were archived with reasons:
"B was rejected because it was too fast, C because it lacked economic framework"

New evidence in 2020s can trigger reconsideration with fresh analysis
```

---

## Decision 6: Scope is Configurable, Not Hardcoded

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05

### The Choice
Scope levels (Ward, District, State, National) are configurable per organization, not hardcoded in system.

### Why This Matters

**Without configurability:**
```
System has:
├── WARD
├── DISTRICT
├── STATE
├── NATIONAL

Political Party in Nepal: "We also have committees and caucuses"
NGO in Kenya: "We have chapters and regional offices"
Cooperative: "We have producer groups and zone"
→ System doesn't fit
```

**With configurability:**
```
OrganizationPolicy {
  scopeLevels: ["WARD", "MUNICIPALITY", "DISTRICT", "PROVINCIAL", "NATIONAL"]
}

vs

OrganizationPolicy {
  scopeLevels: ["CHAPTER", "REGION", "NATIONAL"]
}

vs

OrganizationPolicy {
  scopeLevels: ["PRODUCER_GROUP", "ZONE", "NATIONAL"]
}
```

### Our Decision

**Scope as configurable value object:**

```
Scope = {
  level: ScopeLevel (configurable enum per org),
  organizationUnitId: UUID (reference to actual unit),
  name: String (e.g., "Ward 17, Kathmandu")
}

OrganizationPolicy = {
  scopeLevels: [ScopeLevel] (what levels exist in this org?),
  escalationRules: [Rule] (how to move between levels?)
}
```

### Why This Is Correct

1. **Reusability:** Platform works for political parties, NGOs, cooperatives, professional associations
2. **No hardcoding:** Each organization defines own structure
3. **Alignment with Organization Context:** Uses existing org structure, doesn't impose new one
4. **Scalability:** Works for flat organizations AND deeply hierarchical ones
5. **Future flexibility:** Organization can add new scope levels without code change

---

## Decision 7: Decision Transparency is Non-Negotiable

**Status:** ✅ APPROVED  
**Date Decided:** 2026-06-05

### The Choice
ALL decisions must be:
- Publicly visible
- Explained with mandatory reason
- Permanent (archived, not deleted)
- Permanent (cannot be hidden later)

### Why This Matters

**Without transparency:**
```
Leadership: Makes decision
Members: Never learn reason
Organization: Trust erodes
```

**With transparency:**
```
Decision: REJECTED

Reason: "Budget constraints prevent 2026 implementation"

Reviewer: Central Committee

Date: 2026-01-15

Visibility: PUBLIC (cannot be changed)
```

### Our Decision

**Decision aggregate enforces transparency:**

```
Decision {
  status: ACCEPTED | REJECTED,
  reason: MANDATORY (cannot be empty),
  reasonType: ENUM (budget, duplicate, legal, etc.),
  reasonDetails: STRING (full explanation),
  reviewer: UUID,
  reviewDate: DateTime,
  visibility: PUBLIC (always),
  archived: IMMUTABLE
}

Key: Once created, Decision cannot be deleted, modified, or hidden.
```

### Why This Is Correct

1. **Prevents autocracy:** Hidden decisions breed suspicion
2. **Encourages quality decisions:** Leaders must justify publicly
3. **Organizational memory:** "Why did we reject this?" has an answer
4. **Fairness:** Rejected members see decision was thoughtful, not arbitrary
5. **Accountability:** Hidden decisions impossible
6. **Trust building:** Even wrong decisions are better than unexplained ones

### Real Example

```
Without transparency:
2026: Proposal rejected (members never know why)
2030: Same proposal submitted again
"Didn't we reject this?"
"I don't remember..."
→ Wasteful rework

With transparency:
2026: Proposal rejected (reason: "Technology not mature yet")
2030: New proposal with mature technology
"Yes, we rejected v1 in 2026 because immature. This v2 is better."
→ Builds on past knowledge
```

---

## Summary Table

| Decision | Choice | Why | Risk Mitigation |
|----------|--------|-----|-----------------|
| **Problem Aggregate** | Separate root | Problems precede solutions | Enforced lifecycle |
| **Idea vs Proposal** | Two aggregates | Different invariants | Clear boundaries |
| **Sentiment ≠ Evidence** | Separated | Democracy vs truth | No weighting by rank |
| **Reconsideration** | Not appeal | New evidence only | Clear end condition |
| **Archive forever** | All proposals | Organizational learning | Status field shows result |
| **Scope configurable** | Per organization | Multi-org reusability | Uses existing org structure |
| **Transparency** | Mandatory | Prevents autocracy | Immutable, public |

---

**Version:** 1.0  
**Status:** Ready for Bounded Context Review  
**Next:** Continue reviewing or modify decisions?
