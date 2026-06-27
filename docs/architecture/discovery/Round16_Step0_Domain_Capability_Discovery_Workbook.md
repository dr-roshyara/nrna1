# Round 16 Step 0 — Domain Capability Discovery Workbook

**Purpose:** Discover the actual business capabilities of the election platform.

**Status:** Discovery in Progress

**Date:** 2026-06-06

---

## Mission

Answer: **What does the election platform actually do?**

Do NOT identify bounded contexts yet.

Do NOT identify aggregates yet.

Do NOT design architecture.

Do NOT select strategic structures.

Focus only on: **What business capabilities exist?**

---

## Inputs

* Domain knowledge from Rounds 8–15
* Election platform project documentation
* Existing business workflows and user journeys
* Observed domain behavior
* Stakeholder responsibilities

---

## Investigation Questions

### Question 1: What business outcomes does the platform provide?

**Observations:**

The platform enables organizations to conduct secure, anonymous, verifiable elections entirely online.

Primary outcomes:

* **Verified election results** — Results that can be audited and verified
* **Voter anonymity** — No linkage between voters and votes
* **Election integrity** — Ability to detect and prevent fraud
* **Stakeholder confidence** — Organizations can trust the election process
* **Audit compliance** — Complete audit trails for disputes
* **Accessibility** — Voters can vote from anywhere

---

### Question 2: Who are the actors?

**Identified Actors:**

| Actor | Role | Responsibilities |
|-------|------|-----------------|
| Organization Administrator | Governs elections | Create elections, configure rules, manage authority, certify results |
| Election Officer | Manages election operations | Register voters, configure ballots, monitor voting, publish results |
| Voter | Participates in election | Register eligibility, receive voting code, cast vote, verify vote |
| Auditor | Ensures compliance | Review audit logs, verify election integrity, challenge decisions |
| System Administrator | Manages platform | Manage multi-tenancy, configure system, audit security |
| Candidate Manager | Manages candidacies | Create candidates, manage candidacies, associate with positions |
| Governance Authority | Sets policy | Define governance rules, approve election formats, oversee process |

---

### Question 3: What responsibilities exist?

**Discovered Responsibilities:**

| Responsibility | Owner (Actor) | Scope |
|----------------|--------------|-------|
| Election Authorization | Organization Administrator | Decide whether elections happen |
| Election Configuration | Election Officer | Define posts, positions, regions, voter eligibility |
| Governance Rule Definition | Governance Authority | Define how elections operate (SELECT_ALL_REQUIRED, voting windows, etc.) |
| Voter Registration | Election Officer | Register eligible voters in election |
| Voter Eligibility Verification | Election Officer | Confirm voter meets election requirements |
| Ballot Preparation | Election Officer | Create ballot with posts, positions, candidates |
| Ballot Distribution | Election Officer | Issue voting codes to eligible voters |
| Voting Process Management | System | Manage 5-step voting workflow |
| Vote Casting | Voter | Select candidates and submit selections |
| Vote Collection | System | Store votes while maintaining anonymity |
| Vote Counting | System | Tally votes by position |
| Result Publication | Election Officer | Publish election results |
| Result Certification | Organization Administrator | Declare results official |
| Audit Trail Maintenance | System | Record all voter actions with timestamps and IP |
| Audit Review | Auditor | Examine logs for irregularities |
| Eligibility Challenge | Auditor | Question voter eligibility decisions |
| Result Challenge | Auditor | Question result accuracy |
| Authority Delegation | Organization Administrator | Grant decision-making authority to officers |
| Multi-Tenancy Management | System Administrator | Isolate organization data completely |

---

### Question 4: What capabilities are required to fulfill those responsibilities?

**Discovered Capabilities:**

#### Governance and Setup

**Capability: Election Authorization**
- Organization leadership decides elections will occur
- Establishes election scope and rules
- Allocates resources and authority
- Sets governance framework

**Capability: Governance Rule Definition**
- Define selection rules (SELECT_ALL_REQUIRED vs. flexible)
- Define voting time windows
- Define verification requirements
- Define eligibility rules

**Capability: Authority Delegation**
- Organization assigns officers to manage elections
- Defines scope of delegated authority
- Tracks who has what permissions
- Can revoke authority

---

#### Election Setup

**Capability: Election Configuration**
- Create new election
- Define time window (start/end dates)
- Configure election type (national, regional, mixed)
- Set up governance rules for this specific election

**Capability: Position and Post Definition**
- Create posts (President, Vice President, Secretary, etc.)
- Mark posts as national-wide or regional
- Define which regions see which posts
- Set number of candidates to select per post (required_number)

**Capability: Candidate Registration**
- Register candidates for positions
- Assign candidates to posts
- Track candidate information
- Handle candidate updates or withdrawals

**Capability: Ballot Preparation**
- Compile posts and candidates into ballot structure
- Determine candidate ordering
- Prepare ballot for this specific election
- Validate ballot completeness

---

#### Voter Management

**Capability: Voter Registration**
- Register individuals as eligible voters for this election
- Capture voter information (name, region, organization role)
- Assign region affiliation (for regional post filtering)
- Verify voter identity

**Capability: Voter Eligibility Verification**
- Check voter meets election participation criteria
- Verify voter region matches post regions
- Confirm voter not already voted
- Handle eligibility disputes

**Capability: Voting Code Distribution**
- Generate unique voting codes (one or two per voter)
- Record which codes belong to which voting session
- Deliver codes to voters
- Track code issuance

---

#### Voting Process

**Capability: Voting Workflow Management**
- Manage 5-step voting process (Code Entry → Agreement → Voting → Verification → Completion)
- Track voter progress through steps
- Record timestamps and IP addresses
- Allow stepping back within same session
- Enforce voting time window
- Handle voting session timeouts

**Capability: Ballot Rendering**
- Display correct ballot for voter
- Show national posts to all voters
- Show regional posts only to voters in that region
- Present candidates in correct order
- Allow candidate selection
- Enforce selection rules (exact match or up-to)

**Capability: Vote Casting**
- Accept voter's candidate selections
- Store selections without linking to voter identity
- Enforce invariants (no duplicate selections, required count matches, etc.)
- Handle "no vote" choices
- Prevent vote tampering or modification after submission

---

#### Vote Processing and Results

**Capability: Vote Collection**
- Store submitted votes anonymously
- Maintain vote integrity
- Ensure votes cannot be reversed to voters
- Keep votes separate from voting codes

**Capability: Vote Counting**
- Tally all votes by position
- Count candidate selections
- Count abstentions and no-votes
- Produce vote totals per candidate per position

**Capability: Result Publication**
- Display election results
- Show vote counts by candidate by position
- Present results clearly to stakeholders
- Archive results for audit

**Capability: Result Certification**
- Organization leadership declares results official
- Timestamp certification
- Provide signature or approval marker
- Create immutable record

---

#### Verification and Audit

**Capability: Audit Trail Maintenance**
- Log every voter action (step completion, timestamps, IP address, selections)
- Separate logs per voter per election
- Maintain audit trail integrity
- Preserve logs for dispute resolution

**Capability: Audit Review**
- Search and examine audit logs
- Reconstruct voter journey
- Verify completeness of process
- Identify irregularities

**Capability: Vote Verification**
- Voter verifies their vote was recorded correctly
- Display selections without revealing voter identity
- Allow voter to confirm accuracy
- Provide confirmation receipt

**Capability: Eligibility Challenge**
- Auditor or administrator can question voter eligibility decisions
- Review eligibility verification logs
- Potentially reverse or correct eligibility
- Document challenge resolution

**Capability: Result Challenge**
- Auditor or administrator can question result accuracy
- Recount votes
- Review vote casting logs
- Produce audit report

---

#### System Operations

**Capability: Multi-Tenancy Isolation**
- Completely separate organization data
- Ensure no data leakage between organizations
- Enforce organization_id scoping
- Audit isolation enforcement

**Capability: Demo Mode Management**
- Create test elections with organization_id = NULL
- Allow unlimited voting for testing
- Reset demo election completely
- Separate demo data from production

**Capability: Session Management**
- Create voting sessions
- Track voter progress across steps
- Manage session timeouts
- Preserve session state

**Capability: Permission Enforcement**
- Enforce role-based access control (admin, officer, auditor)
- Prevent unauthorized access
- Log permission violations
- Enforce principle of least privilege

---

## Capability Map

```
GOVERNANCE & AUTHORIZATION
├── Election Authorization
├── Governance Rule Definition
└── Authority Delegation

ELECTION SETUP
├── Election Configuration
├── Position and Post Definition
├── Candidate Registration
└── Ballot Preparation

VOTER MANAGEMENT
├── Voter Registration
├── Voter Eligibility Verification
└── Voting Code Distribution

VOTING PROCESS
├── Voting Workflow Management
├── Ballot Rendering
└── Vote Casting

VOTE PROCESSING
├── Vote Collection
├── Vote Counting
├── Result Publication
└── Result Certification

VERIFICATION & AUDIT
├── Audit Trail Maintenance
├── Audit Review
├── Vote Verification
├── Eligibility Challenge
└── Result Challenge

SYSTEM OPERATIONS
├── Multi-Tenancy Isolation
├── Demo Mode Management
├── Session Management
└── Permission Enforcement
```

---

## Capability Dependencies

### Direct Dependencies

**Election Authorization** → Election Configuration
- Elections must be authorized before configured

**Governance Rule Definition** → Ballot Rendering, Vote Casting
- Rules defined at governance level affect voting behavior

**Election Configuration** → Position and Post Definition → Ballot Preparation
- Posts must be created before ballot can be prepared

**Candidate Registration** → Ballot Preparation
- Candidates must exist before ballot preparation

**Voter Registration** → Voter Eligibility Verification → Voting Code Distribution
- Voters registered → eligibility verified → codes distributed

**Voting Code Distribution** → Voting Workflow Management
- Codes must be issued before voting can begin

**Voting Workflow Management** → Ballot Rendering → Vote Casting
- Workflow orchestrates the steps

**Vote Casting** → Vote Collection → Vote Counting
- Votes submitted → collected → counted

**Vote Counting** → Result Publication → Result Certification
- Counts → published → certified

**Result Certification** → Audit Review
- Once certified, results can be audited

**Authority Delegation** → all other capabilities
- Authority is prerequisite for all operations

---

## Capability Characteristics

### Governance and Setup Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Election Authorization | Rare (per election) | Organization Admin | Low | Low |
| Governance Rule Definition | Rare (per election) | Governance Authority | Low | Medium |
| Authority Delegation | Rare | Organization Admin | Low | Low |

### Election Setup Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Election Configuration | Rare (per election) | Election Officer | Low | Medium |
| Position and Post Definition | Rare | Election Officer | Low | Low |
| Candidate Registration | Occasional | Candidate Manager | Medium | Low |
| Ballot Preparation | Rare (per election) | Election Officer | Medium | Low |

### Voter Management Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Voter Registration | Occasional (pre-election) | Election Officer | High (many voters) | Low |
| Voter Eligibility Verification | Occasional | Election Officer | High | Medium |
| Voting Code Distribution | Occasional | System | High | Low |

### Voting Process Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Voting Workflow Management | Very frequent (per voter) | System | High | High |
| Ballot Rendering | Very frequent (per voter) | System | Medium | Medium |
| Vote Casting | Very frequent (per voter) | Voter | Medium | Medium |

### Vote Processing Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Vote Collection | Very frequent | System | High | Low |
| Vote Counting | Rare (post-voting) | System | High | Medium |
| Result Publication | Rare (post-election) | Election Officer | Medium | Low |
| Result Certification | Rare (post-election) | Organization Admin | Low | Low |

### Verification and Audit Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Audit Trail Maintenance | Very frequent | System | Very high | Low |
| Audit Review | Occasional | Auditor | Very high | High |
| Vote Verification | Frequent (per voter) | Voter | Medium | Low |
| Eligibility Challenge | Rare | Auditor | Low | Medium |
| Result Challenge | Rare | Auditor | High | High |

### System Operations Capabilities

| Capability | Frequency | Actor | Data Volume | Complexity |
|------------|-----------|-------|-------------|-----------|
| Multi-Tenancy Isolation | Continuous | System | Very high | High |
| Demo Mode Management | Occasional | Administrator | High | Low |
| Session Management | Very frequent | System | High | Medium |
| Permission Enforcement | Very frequent | System | Low | High |

---

## Observed Patterns

### Temporal Sequencing

Capabilities form a clear timeline:

**Pre-Election Phase:**
- Governance setup (rules, authority)
- Election configuration (posts, candidates)
- Voter registration and eligibility
- Voting code distribution

**Election Phase:**
- Voting workflow management
- Ballot rendering and vote casting
- Vote collection

**Post-Election Phase:**
- Vote counting
- Result publication and certification
- Audit review and challenges

### Isolation Boundaries

Some capabilities appear highly isolated:

- **Voting Process** — Intense focus on this phase; everything else supports it
- **Vote Anonymity** — Vote Casting and Vote Collection isolate voter from vote
- **Audit Trails** — Separate from voting; runs in parallel with all other capabilities

### Cross-Cutting Concerns

Some capabilities cut across others:

- **Authority Delegation** — Required before any other capability operates
- **Governance Rule Definition** — Affects Ballot Rendering, Vote Casting, Eligibility Verification
- **Multi-Tenancy Isolation** — Required for all capabilities
- **Permission Enforcement** — Required for all capabilities
- **Session Management** — Required for Voting Workflow Management

---

## Explicitly Not Discussed

This workbook does NOT address:

* Bounded contexts
* Aggregates
* Event models
* Microservice boundaries
* Database schemas
* API endpoints
* Implementation technologies

These emerge only after capabilities are mapped.

---

## Success Criteria

✓ Complete capability landscape discovered

✓ Business can be described in terms of capabilities

✓ Dependencies between capabilities are visible

✓ Actor responsibilities are mapped to capabilities

✓ Frequency and complexity characteristics documented

✓ Temporal sequencing patterns identified

✓ Isolation boundaries observed

---

## Next Step

Only after this workbook is approved may:

**Round16_Step1_Bounded_Context_Discovery.md** begin

---

**STATUS: Capability Discovery Complete**

**Ready for Review**
