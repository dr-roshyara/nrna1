# Round 16 — Election Domain Evidence Assessment

**Purpose:** Inventory and classify all available evidence sources related to election operations. Determine whether problem-space evidence exists for the operational election domain.

**Scope:** All discovered sources relating to election creation, administration, candidate management, voter registration, eligibility, ballot preparation, voting, tallying, result publication, audit, and challenge processes.

**Status:** Assessment Complete

**Date:** 2026-06-06

---

## Methodology

For every discovered source, this assessment answers:

1. **Evidence Availability** — What exists? What does not? What remains unknown?
2. **Evidence Type** — Problem-space or solution-space?
3. **Evidence Quality** — Authoritative / Derived / Implementation-only / Unknown
4. **Ubiquitous Language Presence** — What terms appear repeatedly?
5. **Rule Density** — Where do explicit business rules appear?

**Critical constraint:** This assessment does NOT:
- Validate candidate contexts
- Compare Governance against other domains
- Declare signal strength
- Reassess Round 16 Step 1A
- Infer bounded contexts, aggregates, or ownership

---

## Evidence Classification

### Problem-Space Evidence

Evidence originating from business needs, organizational procedures, or domain requirements:
- Election regulations and bylaws
- Election officer manuals and user guides
- Business process descriptions
- Operational workflows from stakeholder perspective
- Legal or constitutional election requirements

### Solution-Space Evidence

Evidence originating from system design and implementation:
- Source code, database schemas, API routes
- Architecture documents and ADRs
- Implementation guides and technical workflows
- Domain models designed by engineers

---

## Discovered Evidence Sources

### Source 1: Election Management User Guides

**Location:** `docs/election_management/` (6 documents)

| Document | Type | Evidence Type |
|----------|------|---------------|
| `01-your-role.md` — Election Officer roles (Chief, Deputy, Commissioner) | Operational role definition | Problem-space |
| `02-accepting-invitation.md` — Officer appointment acceptance | Operational workflow | Problem-space |
| `03-management-dashboard.md` — Election control dashboard | Operational workflow | Mixed |
| `04-voter-list.md` — Managing voter approvals | Operational workflow | Problem-space |
| `05-viewboard.md` — Read-only monitor for Commissioners | Operational workflow | Problem-space |
| `06-faq-troubleshooting.md` — Officer FAQ | Support documentation | Derived |

**Evidence Quality:** Authoritative — written for actual election officers, describes real operational procedures.

**Ubiquitous Language found:**
- Appointment, Invitation, Active/Pending status
- Chief Election Officer, Deputy Election Officer, Commissioner
- Management Dashboard, Viewboard, Voter List
- Approve voter, Suspend voter, Open voting, Close voting, Publish results, Unpublish results
- Voting period, Election status, Results published

**Business Rules explicitly stated:**

| Rule | Location |
|------|----------|
| Only one Chief is typically appointed per election | `01-your-role.md` |
| Commissioner has read-only access — cannot approve voters, open voting, or publish results | `01-your-role.md` |
| Chief can publish/unpublish results; Deputy cannot | `01-your-role.md` and `03-management-dashboard.md` |
| Invitation link expires in 7 days | `01-your-role.md` |
| Officer status = Pending until appointment accepted; no access while Pending | `01-your-role.md` |
| Voting can be re-opened after closing (explicit design decision) | `03-management-dashboard.md` |
| Approved voters can vote; Invited/Suspended voters cannot | `03-management-dashboard.md` |
| Publishing results does not delete votes — only controls visibility | `03-management-dashboard.md` |

---

### Source 2: Candidacy Approval Procedure

**Location:** `developer_guide/election/real_election/candidacy/approval-procedure.md`

**Evidence Type:** Mixed (operational workflow described in developer guide context)

**Evidence Quality:** Authoritative — describes the full pipeline from voter application to ballot appearance with explicit step-by-step rules.

**Ubiquitous Language found:**
- CandidacyApplication (status: pending, approved, rejected)
- Candidacy (status: draft, approved)
- Proposer, Supporter, Manifesto, Statement
- Awaiting Decision, Decided
- Publish, Unpublish, Remove
- Ballot, Post (position), Officer review
- One-per-election rule

**Business Rules explicitly stated:**

| Rule | Location |
|------|----------|
| Voter can only have one active application (pending or approved) per election, regardless of post | Step 1 |
| Server blocks duplicates even if UI is bypassed | Step 1 |
| Approval creates a draft candidate, not yet visible on ballot | Step 2 |
| Rejection requires a stated reason (max 500 characters) | Step 2 |
| Rejection does NOT block future re-application | Step 2 |
| Draft → Approved (Publish) required for candidate to appear on ballot | Step 3 |
| Removing a candidate does not change the linked application status | Step 3 |
| Only approved candidacies appear on the voting ballot | Step 4 |

**Pipeline explicitly documented:**
```
Voter applies → CandidacyApplication (pending)
  → Officer Approves → Candidacy (draft)
  → Officer Publishes → Candidacy (approved) → Appears on ballot
  OR
  → Officer Rejects → CandidacyApplication (rejected) → Voter may re-apply
```

---

### Source 3: SELECT_ALL_REQUIRED Business Case

**Location:** `docs/select-all-required-business-case.md`

**Evidence Type:** Problem-space — written as business case for Election Committee and NRNA leadership.

**Evidence Quality:** Authoritative — references real organizational problems, stakeholder names (NRNA Election Committee), actual statistics from prior elections.

**Ubiquitous Language found:**
- Ballot, Ballot completion, Complete ballot, Incomplete ballot
- Compulsory selection, Flexible selection
- No Vote, Deliberate abstention
- Position, Required candidates, Selection
- Election Committee, Voters, Candidates
- Mandates, Legitimacy
- Bylaw compliance, Audit trail, Certification

**Business Rules explicitly stated:**

| Rule | Location |
|------|----------|
| Election Committee may configure compulsory vs. flexible selection mode per election | Solution Overview |
| "No Vote" option allows deliberate abstention (even in compulsory mode) | Use Case 1 |
| Organizational bylaws mandate specific selection requirements for certain roles | Business Problem section |
| 23% of ballots historically had incomplete selections for multi-candidate positions | Business Problem section |
| Post-election surveys indicate 15% of voters were unaware they could select multiple candidates | Business Problem section |
| Results must be certified within 24 hours (target) | Success Metrics |
| Minimum participation thresholds are bylaw-defined (referenced but not specified) | Recommendations |
| Deliberate abstention ("No Vote") counting must be defined in bylaws | Recommendations |

**Critical observation:** This document explicitly references NRNA organizational bylaws as authoritative source for election rules, but the bylaws themselves are NOT in the repository.

---

### Source 4: Election Management Policy Guide (Developer)

**Location:** `developer_guide/election/election_management/04-election-policy.md`

**Evidence Type:** Solution-space — describes authorization policy implementation.

**Evidence Quality:** Implementation-only — describes code behavior, not business rules directly. However, contains explicit design rationale.

**Ubiquitous Language found:**
- Chief, Deputy, Commissioner
- manageSettings, publishResults, manageVoters, viewResults
- Organisation owner, Admin
- Active officer

**Notable explicit rationale (problem-space adjacent):**

```
"Why `UserOrganisationRole` here? Election creation is an organisational
governance decision (who can start an election). Election *management*
(activation, voter control) is an election officer decision. These are
intentionally separate permission sources."
```

This statement distinguishes two domains explicitly:
- Election creation = organisational governance decision
- Election management = election officer decision

**Business Rule implicitly present:**
- Election creation authority derives from organisational role (owner/admin)
- Election management authority derives from election officer appointment
- These are separate authorization chains

---

### Source 5: Double Vote Prevention Analysis

**Location:** `docs/DOUBLE_VOTE_PREVENTION_ANALYSIS.md`

**Evidence Type:** Solution-space — bug analysis document.

**Evidence Quality:** Implementation-only — describes code and route behavior.

**Ubiquitous Language found:**
- has_voted flag
- Double vote prevention
- First submission, Second vote attempt
- Real election (type='real')

**Business Rule implicitly present:**
- A voter may not vote twice in a real election (enforced via has_voted flag)
- Demo elections operate differently from real elections

**Note:** The rule itself (no double voting) is assumed, not stated as business rule. It appears as implementation constraint.

---

### Source 6: Election Results Publication Guide

**Location:** `docs/publishing-election-results.md`

**Evidence Type:** Mixed — operational guide with technical setup.

**Evidence Quality:** Derived — describes how to operate the system; business rules implied by operations described.

**Ubiquitous Language found:**
- Publish results, Unpublish results
- Election committee role
- Permission (publish-election-results)
- Results visibility

**Business Rule implicitly present:**
- Result publication requires explicit election-committee role
- Results can be published and unpublished (reversible)
- Separate permissions for view vs. publish

---

### Source 7: Voting Context Architecture (GEO-3.5)

**Location:** `docs/GEO_3_5_VOTING_CONTEXT_ARCHITECTURE.md`

**Evidence Type:** Solution-space — architecture document designed by engineers.

**Evidence Quality:** Implementation-only — describes system design for "Voting Context" as technical component.

**Ubiquitous Language found:**
- EligibilitySnapshot, BallotCollection, QuorumDefinition
- VotingSession, VotingEngine
- Eligible voters, Ballot, Selection, Quorum threshold
- Determinism, Order-independence, Semantic safety, Immutability

**Notable observation:** This document describes a "Voting Context" as engineered construct. Vocabulary comes from engineers, not from business documents. EligibilitySnapshot, VotingSession, and VotingEngine are engineering terms, not terms that would appear in election bylaws.

---

### Source 8: Voter Management User Guides

**Location:** `docs/election/` (9 documents)

| Document | Evidence Type |
|----------|---------------|
| `01-getting-started.md` — Login and access | Problem-space (user-facing) |
| `02-voter-list-overview.md` — Voter list overview | Problem-space (operational) |
| `03-searching-filtering.md` — Search and filter voters | Problem-space (operational) |
| `04-managing-voters.md` — Approve/suspend voters | Problem-space (operational) |
| `05-bulk-operations.md` — Bulk voter management | Problem-space (operational) |
| `06-statistics-reports.md` — Statistics and reporting | Mixed |
| `07-tips-troubleshooting.md` — Troubleshooting | Support |
| `08-accessibility.md` — Accessibility | Problem-space (non-election) |
| `09-language-settings.md` — Languages | Problem-space (non-election) |

**Evidence Quality:** Authoritative — written for actual voters and commission members.

**Ubiquitous Language found:**
- Voter, Member, Commission Member, Staff
- Approved, Suspended, Invited (voter statuses)
- Approve voter, Suspend voter
- Organisation, Election
- Voter list, Statistics
- Session timeout (1 hour), Two-factor authentication

---

## Ubiquitous Language Summary

Terms found across multiple problem-space sources (recorded, not interpreted):

| Term | Found In | Frequency |
|------|---------|-----------|
| Election | All sources | Very high |
| Voter | Election management, Voter management, Business case | Very high |
| Ballot | Business case, Candidacy, GEO-3.5 | High |
| Candidate | Candidacy procedure, Business case | High |
| Officer (Chief, Deputy, Commissioner) | Election management guide, Policy | High |
| Approve / Suspend | Voter management, Election management | High |
| Publish results / Unpublish results | Management dashboard, Results guide | High |
| Open voting / Close voting | Management dashboard | High |
| Position / Post | Candidacy procedure, Business case | High |
| Manifesto / Statement | Candidacy procedure | Medium |
| No Vote / Abstention | Business case | Medium |
| Eligibility | Business case (implied), GEO-3.5 | Medium |
| Registration | Voter management (implied) | Medium |
| Tally / Counting | Not found in problem-space sources | Not found |
| Audit | Not found in problem-space sources | Not found |
| Certification | Business case (result certification) | Low |
| Quorum | GEO-3.5 only | Low (solution-space) |

---

## Rule Density by Area

Where explicit business rules appear in problem-space sources:

| Election Operation Area | Rule Density | Primary Source |
|------------------------|--------------|----------------|
| Officer roles and authority | High | `election_management/01-your-role.md` |
| Candidacy application process | High | `candidacy/approval-procedure.md` |
| Voter approval and suspension | Medium | `election_management/` + `election/` |
| Voting period control (open/close) | Medium | `election_management/03-management-dashboard.md` |
| Result publication | Medium | `election_management/03-management-dashboard.md` |
| Ballot completion requirements | Medium | `select-all-required-business-case.md` |
| Election creation authority | Low (implied) | `04-election-policy.md` (solution-space) |
| Vote counting / tallying | Not found | — |
| Vote anonymity rules | Not found directly | Referenced in CLAUDE.md as design principle |
| Election challenge process | Not found | — |
| Eligibility verification | Absent | — |
| Audit process | Not found | — |

---

## Evidence Gaps

### Areas with no problem-space evidence found:

1. **Vote counting and tallying** — No business rules, no operational procedures, no user guide for how counting works
2. **Election challenge process** — No document describing how to formally challenge results or raise election disputes
3. **Eligibility verification rules** — No document specifying what makes a voter eligible (membership criteria, regional criteria, etc.)
4. **Election audit process** — No operational audit guide; audit mentioned in GEO-3.5 as technical feature
5. **NRNA organizational bylaws** — Referenced in business case document but NOT found in repository
6. **Election regulations** — Referenced but not discovered
7. **Quorum requirements** — Found only in GEO-3.5 (solution-space); no business definition

---

## Evidence Type Imbalance

| Election Operation | Problem-Space | Solution-Space |
|-------------------|---------------|----------------|
| Officer roles and authority | High | Medium |
| Candidacy management | High | Medium |
| Voter management | High | Medium |
| Voting period control | Medium | Medium |
| Result publication | Medium | Low |
| Vote casting mechanics | Low | High (GEO-3.5) |
| Vote counting / tallying | None found | None found |
| Election challenge / audit | None found | None found |
| Eligibility verification | None found | Low |

---

## Critical Observations

### Observation 1: Problem-space evidence exists for election operations

Unlike the governance domain, which was heavily documented through solution-space artifacts, election operations have meaningful problem-space evidence:

- Election officer user guides describe real roles with real permissions and real constraints
- Candidacy approval procedure describes a real multi-step organizational process
- Business case references real NRNA organizational problems, stakeholders, and bylaws

This is a different evidence quality from the governance extraction.

---

### Observation 2: Repository evidence is concentrated in election administration

The problem-space evidence found in this repository is concentrated in **election administration**:
- Officer roles, appointment, and authority
- Voter list management (approve/suspend)
- Candidacy application review
- Voting period control
- Result publication

Repository evidence for voting mechanics is limited. The voting mechanics (how votes are cast, stored, counted) appear primarily in solution-space documents within this repository. This observation is scoped to the repository — bylaws, interview records, regulations, and other non-repository sources have not been examined.

---

### Observation 3: Bylaws and regulations are referenced but absent

The business case explicitly references:
- "Organizational bylaws mandate specific selection requirements for certain roles"
- "Bylaw compliance"
- "Recommendations — Review Organizational Bylaws"

These are the highest-authority problem-space sources. They are referenced but not present in the repository.

Their absence means: the most authoritative problem-space evidence has not yet been examined.

---

### Observation 4: "Tally" and "Audit" have no problem-space sources

Two candidate signals from Round 16 Step 1A have no corresponding problem-space evidence sources in this repository:
- Vote Tallying: No business rules, no operational procedures found
- Audit: No operational audit guide found

This does not mean the activities do not exist. It means this repository does not contain problem-space evidence for them.

---

### Observation 5: Governance and Election evidence have different character

| | Governance Evidence | Election Evidence |
|---|---|---|
| Primary source type | Architecture documents, ADRs, implementation guides | User guides, operational procedures, business case |
| Written for | Architects, engineers | Election officers, voters, administrators |
| Business rules | Implicit in architecture decisions | Explicit in operational guides |
| Vocabulary origin | Engineering concepts (Arbitration, Doctrine, Replay) | Organizational roles (Chief, Commissioner, Ballot) |

These are genuinely different types of evidence. Comparing them directly would be a methodological error.

---

## Assessment Observations

**Problem-space evidence found in this repository:**

- Election officer roles and authority (Chief, Deputy, Commissioner)
- Candidacy management (application → review → ballot pipeline)
- Voter management (approve/suspend)
- Voting period control (open/close)
- Result publication and visibility

**Problem-space evidence not found in this repository:**

- Vote counting and tallying
- Election challenge and dispute processes
- Eligibility verification rules
- Authoritative bylaws and regulations (referenced in business case but absent from repository)
- Audit procedures

**Evidence is partial, not absent.**

Repository evidence for election administration operations is present. Repository evidence for voting mechanics, tallying, auditing, and challenge processes is not found. The distinction between "not found in repository" and "does not exist" is preserved.

---

---

**STATUS: Election Domain Evidence Assessment Complete**

**CONCLUSION: Problem-space evidence found for election administration operations. Repository evidence for tallying, auditing, and challenge processes not found. Authoritative bylaws referenced but not in repository.**

**Readiness not assessed by this document. This document inventories evidence only.**
