# Round 16 — Reassessment Framework

**Purpose:** Define the methodology that a future reassessment phase must follow. This document does NOT perform reassessment—it establishes how reassessment will be conducted when authorized.

**Date:** 2026-06-06

**Status:** Framework Definition Complete

**Critical Constraint:** This document defines process only. It makes no candidate evaluations, rankings, mergers, eliminations, or architectural recommendations.

---

## Context

Round 16 has collected:
- **Evidence Assessment** — Inventory of available sources
- **Governance Evidence Extraction** — Constitutional domain evidence
- **Election Domain Evidence Assessment** — Operational domain evidence
- **Knowledge System Investigation** — Analysis of how understanding is organized
- **Candidate Signal Traceability Review** — Evidence linked back to Step 1A candidates

The next phase (Step 1C) is candidate reassessment. Before reassessment proceeds, the methodology must be established.

This framework defines:
- What evidence types are acceptable
- How conflicting evidence is handled
- What constitutes supporting vs contradictory vs missing evidence
- How knowledge system boundaries inform reassessment
- What decisions reassessment may make
- What decisions are explicitly forbidden

---

## Question 1 — What Evidence Types Are Allowed?

Reassessment may consider the following evidence types:

### Type A: Problem-Space Evidence
**Definition:** Evidence originating from business needs, organizational procedures, or domain requirements.

**Examples:**
- User guides written for election officers
- Business case documents referencing organizational bylaws
- Operational procedures describing workflow
- Stakeholder interviews or requirements
- Regulatory documents

**Acceptance Criteria:**
- Source is written from organizational perspective (not engineering)
- Evidence describes how business actors understand the problem
- Evidence is authoritative for domain understanding

**Acceptable in Reassessment:** ✅ Yes

---

### Type B: Solution-Space Evidence
**Definition:** Evidence originating from system design and implementation.

**Examples:**
- Architecture documents and ADRs
- Database schema migrations
- Implementation guides
- API route definitions
- Type definitions and code structure
- Engineering design patterns

**Acceptance Criteria:**
- Source is written from engineering perspective
- Evidence describes how system architects understand the solution
- Evidence may reflect implementation choices, not business requirements

**Acceptable in Reassessment:** ✅ Yes

**Caveat:** Solution-space evidence must be mapped to problem-space to determine which domain concern it addresses. A database schema alone does not prove a bounded context exists.

---

### Type C: Knowledge-System Evidence
**Definition:** Evidence about how understanding is organized, transmitted, and owned.

**Examples:**
- Vocabulary clustering (who uses which terms, in which contexts)
- Document authorship and audience (who writes for whom)
- Change drivers and update authority (what changes the rules, who decides)
- Decision ownership patterns (who makes decisions, who verifies them)
- Translation boundaries (where one knowledge system translates to another)

**Acceptance Criteria:**
- Evidence documents social/linguistic facts about understanding
- Evidence identifies distinct communities of knowledge
- Evidence is discoverable from document analysis

**Acceptable in Reassessment:** ✅ Yes

**Important:** Knowledge system boundaries are observations about how knowledge is organized, not architecture decisions. Their presence does not automatically create bounded contexts.

---

### Type D: Operational Evidence (Discovered During Investigation)
**Definition:** Evidence about actual operational workflows and constraints.

**Examples:**
- Officer role descriptions and permissions
- Voter approval/suspension workflows
- Result publication procedures
- Candidacy application pipelines
- Time constraints and dependencies

**Acceptance Criteria:**
- Evidence describes real operational procedures
- Evidence is documented in guides or procedures
- Evidence is specific to this domain

**Acceptable in Reassessment:** ✅ Yes

---

### Type E: Architectural Evidence (Existing Decisions)
**Definition:** Evidence from prior architectural decisions, ADRs, or design rationale.

**Examples:**
- ADR-001: Constitutional Capability Sovereignty
- ADR-003: Governance-Driven Revocation
- Election Policy design rationale
- GEO-3.5 Voting Context Architecture
- Explicitly stated architectural principles

**Acceptance Criteria:**
- Evidence is documented in architectural sources
- Evidence reflects deliberate design decisions
- Evidence has explicit rationale

**Acceptable in Reassessment:** ✅ Yes

---

## Question 2 — How Are Conflicting Evidence Types Handled?

When evidence types conflict, the following priority order applies:

```
Problem-space evidence > Knowledge-system evidence > Solution-space evidence
```

**Rationale:**
- Problem-space evidence describes the actual business concern
- Knowledge-system evidence describes how people organize understanding
- Solution-space evidence describes implementation choices

### Conflict Resolution Examples

#### Example 1: Problem-space vs Solution-space conflict

**Problem-space says:** "Voter approval and voting are distinct operational concerns"
**Solution-space says:** "Voting and voter approval are in the same database aggregate"

**Resolution:** Problem-space takes priority. The implementation choice (same aggregate) does not override the operational distinction. Reassessment should investigate why they are co-located if they are operationally distinct.

#### Example 2: Knowledge-system vs Solution-space conflict

**Knowledge-system says:** "Governance and election operations use different vocabularies and belong to different communities"
**Solution-space says:** "Governance computations are embedded in election operations code"

**Resolution:** Knowledge-system observation is valid. Implementation coupling does not invalidate knowledge system distinction. Reassessment should note the coupling while preserving the distinction.

#### Example 3: Problem-space vs Knowledge-system conflict

**Problem-space says:** "Tallying and publication are different operational tasks"
**Knowledge-system says:** "Sources conflate tallying and publication terminology"

**Resolution:** Both are valid. Problem-space describes what should be separate; knowledge-system describes how stakeholders currently understand them. Reassessment should note the terminology conflation as a gap or confusion point.

---

## Question 3 — What Constitutes Supporting vs Contradictory vs Missing Evidence?

### Supporting Evidence

**Definition:** Evidence that indicates a candidate signal is accurate or informative.

**Characteristics:**
- Evidence aligns with the original signal from Step 1A
- Evidence is explicit and unambiguous in primary sources
- Evidence appears consistently across multiple independent sources
- Evidence comes from appropriate knowledge holders (problem-space for problem concerns, governance sources for governance concerns)

**Examples:**
- Multiple operational guides document voter approval workflow (supports Voter Registration candidate)
- Governance documents explicitly define constitutional decision patterns (supports Governance & Authority candidate)
- Chief/Deputy/Commissioner terminology appears consistently in operational sources (supports Election Administration candidate)

**Treatment in Reassessment:** Supporting evidence strengthens the basis for preserving a candidate.

---

### Contradictory Evidence

**Definition:** Evidence that indicates a candidate signal is inaccurate, incomplete, or misleading.

**Characteristics:**
- Evidence contradicts the original signal from Step 1A
- Contradiction comes from authoritative sources
- Contradiction is explicit and cannot be reconciled with supporting evidence
- Contradiction suggests the candidate should be reconsidered

**Examples:**
- If operational guides showed voter approval and voting are always combined and cannot be separated (contradicts Voter Registration as separate candidate)
- If governance sources showed authority is always delegated by election officers, never by constitutional kernel (contradicts Governance & Authority candidate)

**Treatment in Reassessment:** Contradictory evidence requires either evidence reinterpretation or candidate revision.

---

### Missing Evidence

**Definition:** Evidence expected to exist based on the candidate signal but not located during investigation.

**Characteristics:**
- Candidate signal implies a business concern should exist
- Investigation did not locate supporting evidence
- Absence is scoped to this investigation (not claimed as global absence)
- Absence may indicate: gap in repository, gap in organizational documentation, or incorrect candidate signal

**Examples:**
- Vote Tallying candidate implies operational counting procedures, but no such procedures were documented in repository (missing evidence)
- Audit candidate implies operational audit procedures, but none were found (missing evidence)

**Treatment in Reassessment:** Missing evidence requires investigation: Is the candidate signal incorrect, or is the evidence just not in this repository? This distinction matters.

---

## Question 4 — How Should Knowledge-System Boundaries Be Considered?

Knowledge-system boundaries are observations about how understanding is organized. They are NOT automatically architecture decisions.

### Valid Uses of Knowledge-System Evidence

**Use Case 1: Informing Candidate Clarity**

A knowledge system boundary may clarify whether two candidates are truly distinct:

```
Observation:
Voting and Voter Registration appear in separate 
operational guides and are discussed by different 
communities of practice.

Valid Conclusion:
These represent distinct concerns in the organizational 
understanding.

Invalid Conclusion:
Therefore they must be separate bounded contexts.
```

**Use Case 2: Identifying Missing Signals**

A knowledge system boundary may reveal a candidate signal that was missed:

```
Observation:
Governance sources form a complete knowledge system
distinct from election operations.

Valid Conclusion:
The distinction between Governance and Election Operations
may be more significant than the original signals suggested.

Invalid Conclusion:
Governance and Election Operations are automatically separate
bounded contexts.
```

**Use Case 3: Noting Implementation Gaps**

A knowledge system boundary may indicate where implementation does not match understanding:

```
Observation:
Governance and election operations use different vocabulary
and belong to different knowledge systems.

Valid Conclusion:
If governance and election operations are coupled in code,
this may indicate hidden dependencies or unclear responsibility.

Invalid Conclusion:
The coupling proves the knowledge system boundary is wrong.
```

---

### Forbidden Uses of Knowledge-System Evidence

**Forbidden 1: Converting observation to architecture**

```
NOT ALLOWED:
"Knowledge systems are distinct, therefore create separate contexts."

ALLOWED:
"Knowledge systems are distinct. This observation supports investigating
whether the concerns should be separate."
```

**Forbidden 2: Using knowledge systems to rank candidates**

```
NOT ALLOWED:
"Governance knowledge system is more complete, therefore 
it is a stronger candidate."

ALLOWED:
"Governance knowledge system evidence is extensive (solution-space).
Election operations knowledge system evidence is extensive (problem-space).
These are different evidence types and require different treatment."
```

**Forbidden 3: Creating new candidates from knowledge systems**

```
NOT ALLOWED:
"Knowledge systems suggest a new candidate: 
'Knowledge System Translation Layer'"

ALLOWED:
"Knowledge system boundaries show translation points in 
ADR-003, ElectionPolicy, and Business Case. These translation
points are observations about the existing domain."
```

---

## Question 5 — What Decisions May Reassessment Make?

Reassessment may produce the following outcomes for each candidate:

### Decision Type A: Preserve Candidate

**Definition:** Candidate signal remains valid. Evidence supports continuing to track this candidate as a potential bounded context signal.

**Requirements:**
- Supporting evidence outweighs contradictory evidence
- Original signal is confirmed or clarified by new evidence
- Candidate remains distinct from other candidates

**Output:** Candidate preserved for future bounded context discovery.

---

### Decision Type B: Clarify Candidate

**Definition:** Candidate signal is valid but requires clarification or refinement based on new evidence.

**Requirements:**
- Evidence reveals important nuances not visible in Step 1A
- Clarification does not eliminate or merge the candidate
- Clarification improves understanding of what the candidate represents

**Output:** Candidate preserved with explicit clarifications noted for future discovery.

**Examples:**
- "Tallying should be understood as 'Result Publication and Counting' not just counting"
- "Audit should be understood as conflating operational logging and governance archaeology"
- "Governance & Authority should be understood as solution-space evidence for constitutional concerns"

---

### Decision Type C: Identify Ambiguity

**Definition:** Evidence reveals that a candidate signal is ambiguous or conflates multiple concerns.

**Requirements:**
- Evidence shows the candidate spans multiple distinct concerns
- Concerns cannot be resolved during reassessment (requires future discovery)
- Ambiguity should be explicitly noted for future work

**Output:** Candidate marked with ambiguity flag; deferred for future clarification.

**Examples:**
- "Audit conflates operational logging and governance archaeology—distinction requires further investigation"
- "Tallying conflates result counting and result publication—requires clarification before further work"

---

### Decision Type D: Defer Decision

**Definition:** Insufficient evidence exists to make any of the above decisions. Candidate decision is deferred pending additional investigation.

**Requirements:**
- Evidence is genuinely insufficient (not just "more could always be found")
- Future work is clearly identified that would resolve the deferral
- Deferral is temporary and specific

**Output:** Candidate marked deferred with specific future investigation required.

**Examples:**
- "Vote Tallying has missing evidence. Deferral: requires investigation of whether counting procedures exist outside this repository."
- "Eligibility verification has missing evidence. Deferral: requires NRNA organizational bylaws which are referenced but not available."

---

## Question 6 — What Decisions Are Explicitly Forbidden?

Reassessment explicitly may NOT:

### Forbidden Decision 1: Rank Candidates

```
NOT ALLOWED:
"Governance & Authority is a stronger candidate than Voting"

Reassessment may only:
- Preserve candidates
- Clarify candidates
- Mark ambiguities
- Defer decisions

Ranking implies a winnowing process that is not authorized.
```

---

### Forbidden Decision 2: Merge Candidates

```
NOT ALLOWED:
"Voting and Voter Registration should merge because they are in the
same knowledge system"

Reassessment may only:
- Note that candidates appear in the same knowledge system
- Identify dependencies between candidates
- Recommend future investigation of relationships

Merging requires bounded context design, not reassessment.
```

---

### Forbidden Decision 3: Eliminate Candidates

```
NOT ALLOWED:
"Audit candidate should be eliminated because audit is not
documented in operational guides"

Reassessment may only:
- Note missing evidence
- Mark deferral pending further investigation
- Identify gaps in understanding

Elimination requires confirmation that the concern is truly absent,
not just undocumented.
```

---

### Forbidden Decision 4: Create New Candidates

```
NOT ALLOWED:
"Knowledge system evidence reveals a new candidate:
'Governance Translation Layer'"

Reassessment works within the six candidates from Step 1A.
New signal discovery requires a separate discovery process, not
reassessment of existing candidates.
```

---

### Forbidden Decision 5: Propose Bounded Contexts

```
NOT ALLOWED:
"This evidence indicates a Voting Context should be created"

Reassessment produces candidate decisions only.
Bounded context proposal requires bounded context discovery,
which is a separate phase not authorized for reassessment.
```

---

### Forbidden Decision 6: Make Architectural Recommendations

```
NOT ALLOWED:
"Evidence suggests Governance should use a Policy Layer pattern"

Reassessment is about candidate signals, not architecture.
Architectural design belongs in bounded context discovery,
not in candidate reassessment.
```

---

## Reassessment Output Specification

When Step 1C Candidate Reassessment is performed, it must produce a document with this structure:

```
For each of the six candidates:

Candidate Name
├── Original Step 1A Signal
├── Evidence Summary
│   ├── Supporting Evidence Found
│   ├── Contradictory Evidence Found
│   └── Missing Evidence Identified
├── Knowledge System Notes
│   └── How evidence relates to discovered knowledge systems
├── Reassessment Decision
│   ├── Type (Preserve / Clarify / Identify Ambiguity / Defer)
│   └── Reasoning
└── Status for Future Work
```

No ranking. No merging. No elimination. No new candidates. No architecture proposals.

---

## Closing Statement

```
Framework Established

Reassessment methodology defined.
Evidence types specified.
Conflict resolution protocol defined.
Allowed decisions listed.
Forbidden decisions marked.

Ready for Step 1C authorization.
```

This framework establishes the discipline for candidate reassessment. It prevents reassessment from drifting into:
- Architectural decision-making
- Candidate ranking and winnowing
- Context creation
- New signal discovery

Reassessment stays focused on one question:

```
Given the evidence gathered in Round 16 investigation,
what is the current status of each original candidate signal?
```

The framework preserves this focus while providing clear methodology for assessment and decision recording.

When Step 1C Candidate Reassessment is authorized, this framework will govern its execution.

**Status: Framework Complete**

**Ready for:** Future authorization of Step 1C Candidate Reassessment
