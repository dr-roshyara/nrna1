# Core Domain Model: Aggregates & Value Objects

**Date:** 2026-06-05 09:20 UTC  
**Status:** Validated by Domain Discovery  
**Revision:** 1.0

---

## Overview

The core domain consists of two primary aggregates:

1. **Problem** — What the organization needs to solve
2. **Solution** — How the organization will solve it

These are separate because:
- Problems can exist without solutions
- Multiple solutions can address one problem
- Understanding problem comes before choosing solution
- Organization learns from solutions chosen AND not chosen

---

## Aggregate 1: Problem

### Root: Problem Aggregate

```
Problem (Aggregate Root)
├── ProblemId (Unique identifier)
├── Title (e.g., "Low rural healthcare access")
├── Description (full context and background)
├── AuthorId (member who identified problem)
├── OriginScope (where problem identified: Ward, District, etc.)
├── Scope (current scope, may escalate)
├── Category (configurable: Education, Health, Infrastructure, etc.)
├── Tags (searchable keywords)
├── Status (Enum: see below)
│   ├── IDENTIFIED (newly created)
│   ├── DISCUSSED (members understanding the problem)
│   ├── UNDERSTOOD (sufficient discussion, ready for solutions)
│   └── ARCHIVED (resolved or no longer relevant)
├── CreatedAt (timestamp)
├── LastActivity (timestamp of last comment/signal)
├── DiscussionCount (how many members discussed)
├── EscalationPath (if promoted to higher scope)
│   ├── FromScope: Ward
│   ├── ToScope: District
│   └── EscalationDate: DateTime
└── RelatedProblems (links to similar problems)

ValueObject: ProblemStatus
  ├── Status: IDENTIFIED | DISCUSSED | UNDERSTOOD | ARCHIVED
  ├── ChangedAt: DateTime
  └── ChangedBy: MemberId

ValueObject: ProblemScope
  ├── Level: ScopeLevel (WARD, MUNICIPALITY, DISTRICT, STATE, NATIONAL)
  ├── OrganizationUnitId: UUID
  └── EligibleParticipants: [MemberId] (derived from Organization Context)
```

### Child Entities

```
ProblemDiscussion (Entity, not Aggregate Root)
├── DiscussionId
├── ProblemId (parent)
├── Comments: [Comment]
├── Signals: [Signal]
├── ParticipantCount
├── CreatedAt
└── LastActivity

Comment (Entity)
├── CommentId
├── AuthorId
├── Content (text of comment)
├── Attachments: [AttachmentReference]
├── CreatedAt
├── Replies: [Comment] (threaded)
└── Signals: [Signal] (members can signal on specific comment)

Signal (Value Object)
├── SignalType: IMPORTANT | DUPLICATE_PROBLEM | ROOT_CAUSE | NEEDS_CLARIFICATION
├── AuthorId
├── Content (optional context)
├── CreatedAt
└── Evidence (optional attachment)
```

### Problem Invariants

**Must always be true:**
1. Problem must have author
2. Problem must have scope
3. Problem cannot have same title + scope + category twice (duplicate check)
4. Status can only transition: IDENTIFIED → DISCUSSED → UNDERSTOOD (or → ARCHIVED)
5. Cannot delete problem (only archive)

### Problem Lifecycle

```
IDENTIFIED
    ├─ Members discuss causes and context
    ├─ Comments, evidence, clarifications added
    ↓
DISCUSSED
    ├─ Sufficient discussion occurred (threshold-based or manual)
    ├─ Problem is well-understood by community
    ↓
UNDERSTOOD
    ├─ Now solutions can be proposed
    ├─ Ideas can be linked to this problem
    ↓
ARCHIVED (if resolved or obsolete)
    └─ Hidden from active list, preserved in archive
```

---

## Aggregate 2: Solution

### Root 1: Idea Aggregate

**Purpose:** First-stage proposal, not yet mature

```
Idea (Aggregate Root)
├── IdeaId (unique)
├── ProblemId (which problem does it address?)
├── Title (e.g., "Implement telemedicine in rural areas")
├── Description (detailed proposal)
├── AuthorId (member who proposed)
├── OriginScope (where idea originated)
├── Scope (current scope)
├── Status (Enum)
│   ├── DRAFT (author editing, not public)
│   ├── DISCUSSION (public, members commenting)
│   ├── REFINEMENT (evidence gathering, amendments)
│   └── MATURE (ready to become formal proposal)
├── CreatedAt
├── LastActivity
├── AmendmentHistory
│   ├── Amendment1: "Changed scope to District from Ward"
│   ├── Amendment2: "Added evidence from WHO report"
│   └── Amendment3: "Clarified internet requirement"
├── EscalationHistory (if promoted to higher scope)
└── ConvertedToProposalDate (if promoted to Proposal)

ValueObject: IdeaRefinement
  ├── Stage: DRAFT | DISCUSSION | REFINEMENT | MATURE
  ├── Evidence: [AttachmentReference]
  ├── SentimentSummary: {Support: 850, Concerns: 120, Neutral: 200}
  └── LastSentimentUpdate: DateTime
```

### Root 2: AlternativeProposal Aggregate

**Purpose:** Mature idea competing with other solutions for same problem

```
AlternativeProposal (Aggregate Root)
├── ProposalId (unique)
├── ProblemId (which problem do alternatives solve together?)
├── IdeaId (evolved from which idea?)
├── Title (e.g., "Digital Telemedicine System")
├── Description (detailed, comprehensive)
├── AuthorId (original author)
├── OriginIdeaPath (how many ideas merged into this?)
│   ├── Idea #1: "Implement telemedicine"
│   ├── Idea #2: "Add AI diagnostics"
│   └── Idea #3: "Train rural nurses"
├── Status (Enum)
│   ├── CANDIDATE (one of several alternatives)
│   ├── SELECTED (leadership chose this one)
│   ├── IMPLEMENTATION (executing the decision)
│   ├── COMPLETED (finished)
│   └── ARCHIVED (alternative rejected, preserved for history)
├── CreatedAt
├── SentimentSummary
│   ├── Support: 1200
│   ├── Concerns: 450
│   ├── Neutral: 300
│   └── HighPriority: 150
├── Evidence
│   ├── ResearchReports: [AttachmentReference]
│   ├── ExpertOpinions: [AttachmentReference]
│   ├── FinancialAnalysis: [AttachmentReference]
│   ├── CaseStudies: [AttachmentReference]
│   └── LastEvidenceAdded: DateTime
├── Comparisons
│   ├── AlternativeA: Strengths & Weaknesses vs this
│   ├── AlternativeB: Strengths & Weaknesses vs this
│   └── AlternativeC: Strengths & Weaknesses vs this
├── RelatedProposals (other alternatives for same problem)
└── DecisionId (if accepted, link to Decision)

ValueObject: ProposalScope
  ├── OriginScope: {Level: WARD, UnitId: UUID}
  ├── CurrentScope: {Level: DISTRICT, UnitId: UUID}
  └── EscalationPath: [Scope] (WARD → MUNICIPALITY → DISTRICT)
```

### Root 3: Decision Aggregate

**Purpose:** The choice made by leadership, with full transparency

```
Decision (Aggregate Root)
├── DecisionId (unique)
├── ProposalId (which alternative was chosen?)
├── ProblemId (what problem is solved?)
├── Status (Enum)
│   ├── ACCEPTED (proposal was chosen)
│   ├── REJECTED (proposal not chosen)
│   └── RECONSIDERED (new evidence, reviewing again)
├── DecisionMaker (person or committee)
├── ReviewDate (when decided)
├── Reason (mandatory explanation)
│   ├── RejectionType: BUDGET_LIMITATION | DUPLICATE_EFFORT | LEGAL_ISSUE | CONSTITUTION_CONFLICT | ALTERNATIVE_CHOSEN | TIMING | OTHER
│   ├── Details: "Budget constraints in 2026-2027 fiscal year. Will reconsider in 2028."
│   ├── AlternativeChosen: ProposalId (if rejected because Alternative B chosen)
│   └── Reference: "See Proposal #456 for alternative selected"
├── Evidence (supporting the decision)
│   ├── FinancialAnalysis: [Attachment]
│   ├── LegalReview: [Attachment]
│   └── RiskAssessment: [Attachment]
├── AlternativesConsidered: [ProposalId]
├── MembersInformed: Boolean (decision published?)
├── InformedDate: DateTime
├── Visibility: PUBLIC (cannot be hidden)
└── CanBeReconsideredAfter: DateTime (earliest reconsideration date)

ValueObject: DecisionReason
  ├── Type: Enum (rejection type)
  ├── Summary: String (one-line reason)
  ├── Details: String (full explanation)
  └── Reference: Optional[ProposalId] (if related to other decision)

ValueObject: DecisionTransparency
  ├── PubliclyVisible: true (always)
  ├── ExplanationRequired: true (always)
  ├── AppealAvailable: false
  ├── ReconsiderationAvailable: true (with new evidence)
  └── ArchivePolicy: PERMANENT (never deleted)
```

### Root 4: Initiative Aggregate

**Purpose:** Accepted proposal being executed, progress tracked

```
Initiative (Aggregate Root)
├── InitiativeId (unique)
├── DecisionId (linked to which decision)
├── ProposalId (from which proposal)
├── ProblemId (solving which problem)
├── Title (e.g., "Rural Digital Health Initiative")
├── Description (scope of execution)
├── Status (Enum)
│   ├── NOT_STARTED (accepted, waiting to start)
│   ├── IN_PROGRESS (actively being executed)
│   ├── PAUSED (temporarily stopped)
│   ├── COMPLETED (finished)
│   └── ARCHIVED (replaced or abandoned)
├── ExecutionDetails
│   ├── StartDate: DateTime
│   ├── PlannedEndDate: DateTime
│   ├── ActualEndDate: DateTime
│   ├── ResponsibleTeam: [MemberId]
│   └── BudgetAllocated: Decimal
├── ProgressTracking
│   ├── PercentComplete: 0-100
│   ├── LastUpdateDate: DateTime
│   ├── UpdateSource: EXTERNAL_SYSTEM | MANUAL_REPORT
│   ├── Milestones: [Milestone]
│   │   ├── Milestone1: "Hire telemedicine coordinator" (Status: DONE)
│   │   ├── Milestone2: "Setup equipment" (Status: IN_PROGRESS)
│   │   └── Milestone3: "Train rural nurses" (Status: PENDING)
│   └── Blockers: [String] (what's preventing progress?)
├── OutcomeTracking
│   ├── MetricsDefinition: [Metric]
│   │   ├── Metric1: "Patient access in rural areas"
│   │   ├── Metric2: "Healthcare cost reduction"
│   │   └── Metric3: "Community satisfaction"
│   ├── CurrentMetrics: [MetricValue]
│   └── FinalOutcome: (populated at completion)
├── HandoffToExternalSystem (if applicable)
│   ├── SystemName: "Project Management System"
│   ├── ExternalId: "PM-12345"
│   ├── SyncActive: Boolean
│   └── LastSync: DateTime
├── MembersCanTrackProgress: true
└── OutcomeTransparency
    ├── PubliclyVisible: true
    ├── UpdateFrequency: WEEKLY | MONTHLY
    └── FinalResultsPublished: Boolean

ValueObject: Milestone
  ├── Title: String
  ├── PlannedDate: DateTime
  ├── ActualDate: DateTime
  ├── Status: PENDING | IN_PROGRESS | COMPLETED | BLOCKED
  └── BlockerReason: Optional[String]

ValueObject: Metric
  ├── Title: String
  ├── Definition: String
  ├── TargetValue: Decimal
  ├── CurrentValue: Decimal
  ├── Unit: String
  └── MeasurementDate: DateTime
```

---

## Related Aggregate: ReconsiderationRequest

```
ReconsiderationRequest (Aggregate Root)
├── ReconsiderationId (unique)
├── DecisionId (which decision to reconsider?)
├── RequestorId (who requested reconsideration?)
├── ProposalId (which proposal?)
├── RequestDate: DateTime
├── Status (Enum)
│   ├── SUBMITTED (newly requested)
│   ├── UNDER_REVIEW (decision-maker reviewing)
│   ├── GRANTED (new evidence accepted, decision reversed)
│   └── DENIED (no new evidence, decision stands)
├── NewEvidence
│   ├── Evidence: [AttachmentReference]
│   ├── ExplanationOfChange: String
│   └── WhyThisChangesDecision: String
├── ReviewedBy (decision-maker who reviewed)
├── ReviewDate: DateTime
├── IfGranted
│   ├── NewDecision: ACCEPTED | DEFERRED | RECONSIDERED_AGAIN
│   ├── Reason: String
│   └── NewInitiativeStarted: Boolean
└── Visibility: PUBLIC (reconsideration visible to organization)

**Invariants:**
1. Can only reconsider REJECTED decisions (initially)
2. Requires NEW EVIDENCE (not just disagreement)
3. Organization can set: maximum reconsiderations per decision
4. Minimum time must pass before reconsideration allowed
5. Reconsideration history is permanent (accountability)
```

---

## Cross-Aggregate Value Objects

These are shared across multiple aggregates:

```
ValueObject: Scope
  ├── Level: ScopeLevel (WARD | MUNICIPALITY | DISTRICT | STATE | NATIONAL)
  ├── OrganizationUnitId: UUID
  ├── Name: String (e.g., "Ward 17, Kathmandu")
  └── EligibleMembersCount: Int (derived from Organization)

ValueObject: Sentiment
  ├── Support: Count
  ├── Concerns: Count
  ├── Neutral: Count
  ├── HighPriority: Count
  ├── AlternativeProposals: Count
  └── LastUpdated: DateTime

ValueObject: Evidence
  ├── Type: EvidenceType (RESEARCH | EXPERT_OPINION | CASE_STUDY | DATA | LEGAL | FINANCIAL)
  ├── Attachment: AttachmentReference
  ├── SubmittedBy: MemberId
  ├── SubmittedAt: DateTime
  ├── Quality: STRONG | MODERATE | WEAK (evaluated by community, not author)
  └── RelevantTo: [ProposalId] (which proposals use this evidence?)

ValueObject: AttachmentReference
  ├── AttachmentId: UUID
  ├── FileName: String
  ├── FileType: String (pdf, docx, jpg, etc.)
  ├── FileSize: Int
  ├── StorageLocation: String (external storage URI)
  ├── UploadedBy: MemberId
  ├── UploadedAt: DateTime
  └── VirusScanned: Boolean

ValueObject: Category
  ├── CategoryId: UUID
  ├── Name: String (Education, Health, Infrastructure, etc.)
  ├── Description: String
  ├── CreatedBy: OrganizationAdminId
  ├── OrganizationId: UUID (each org configures own)
  └── ProposalCount: Int (derived)

ValueObject: Tag
  ├── TagId: UUID
  ├── Name: String (searchable keyword)
  ├── UsageCount: Int
  └── OrganizationId: UUID
```

---

## Aggregate Relationships

```
Problem
  1──N Idea (ideas address this problem)
  1──N AlternativeProposal (alternatives solve this problem)
  1──N Decision (decisions made about this problem)
  1──N Initiative (initiatives solving this problem)

Idea
  N──1 Problem (addresses which problem?)
  └─→ evolves into AlternativeProposal

AlternativeProposal
  N──1 Problem (solves which problem?)
  1──N Decision (can have decision made)
  1──1 Initiative (if accepted, becomes initiative)
  N──N AlternativeProposal (related alternatives for comparison)

Decision
  N──1 AlternativeProposal (decision about which proposal?)
  N──1 Problem (solves which problem?)
  1──1 Initiative (if accepted, linked to initiative)
  N──N ReconsiderationRequest (can be reconsidered)

Initiative
  N──1 Decision (from which decision?)
  N──1 AlternativeProposal
  N──1 Problem

ReconsiderationRequest
  N──1 Decision (reconsidering which decision?)
  N──1 AlternativeProposal
```

---

## Aggregate Design Rationale

### Why Problem is a Separate Aggregate

- Problems can exist without solutions
- Multiple solutions can address one problem
- Understanding problem is prerequisite for good solutions
- Organization learns from problems solved and unsolved

### Why Idea and AlternativeProposal are Separate

- Ideas are draft proposals, not final
- Mature ideas become formal alternatives
- Multiple ideas merge into one proposal
- Alternatives need to be compared side-by-side

### Why Decision is its Own Aggregate

- Decisions must be immutable (accountability)
- Decisions must be traceable (who, why, when)
- Decisions link to initiatives (execution)
- Decisions can be reconsidered (with new evidence)

### Why Initiative is its Own Aggregate

- Execution happens outside this context (external system)
- Initiative tracks progress for member visibility
- Initiative has its own lifecycle (start, in-progress, completed)
- Initiative metrics separate from proposal metrics

---

## Key Design Decisions

| Decision | Why |
|----------|-----|
| **Problem first-class** | Problems exist before solutions |
| **Idea vs Proposal separate** | Ideas are draft, proposals are formal |
| **Multiple alternatives preserved** | Organization learns from paths not taken |
| **Decision immutable** | Accountability requires permanent record |
| **Evidence separate from Sentiment** | Members vote with hearts, logic is separate |
| **Reconsideration, not Appeal** | New evidence can trigger review, but not override |
| **Initiative tracking** | Members need to see ideas become action |
| **Scope as Value Object** | Different organizations have different hierarchies |

---

**Version:** 1.0  
**Status:** Ready for Event Modeling  
**Next:** 20260605-0940-DOMAIN-EVENTS.md
