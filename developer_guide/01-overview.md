# Architecture Overview: Verifiable Anonymity Voting System

## What is Verifiable Anonymity?

**Verifiable Anonymity** is a voting system design that solves the fundamental tension in democratic elections:

> How can elections be both **completely anonymous** AND **individually verifiable**?

The answer: **Cryptographic proof without voter identification.**

### The Problem We Solve

Traditional voting systems face a dilemma:

| Approach | Privacy | Verifiability | Winner |
|----------|---------|---------------|--------|
| Blind Voting | ✅ High | ❌ No verification | Privacy over accountability |
| Traditional Ballots | ⚠️ Medium | ✅ Can recount | Medium security |
| Digital with User ID | ❌ None | ✅ Perfect audit | Massive privacy breach |

**Our Solution: Cryptographic Proof**
- Voters verify participation WITHOUT exposing choices
- Results remain absolutely anonymous
- Tamper-proof through SHA256 hashing
- Complete audit trail for disputes

---

## System Architecture

### 1. Core Components

```
┌─────────────────────────────────────────────────────────────┐
│                    VOTING SYSTEM LAYERS                     │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │            VOTER INTERFACE (Vue/Angular)             │  │
│  │  • Enter voting code                                 │  │
│  │  • Accept terms                                      │  │
│  │  • Select candidates                                 │  │
│  │  • Verify vote submitted                             │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓↑                                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │        APPLICATION LAYER (Laravel Controllers)       │  │
│  │  • Authenticate via voting code                      │  │
│  │  • Validate candidate selections                     │  │
│  │  • Generate vote_hash                                │  │
│  │  • Persist vote                                      │  │
│  │  • Mark code as used                                 │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓↑                                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              DOMAIN LAYER (Models)                   │  │
│  │  • BaseVote (Verification logic)                     │  │
│  │  • BaseResult (Aggregation logic)                    │  │
│  │  • Code (Voter tracking)                             │  │
│  │  • Election (Election context)                       │  │
│  │  • Candidacy (Candidate info)                        │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓↑                                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │     PERSISTENCE LAYER (MySQL/PostgreSQL)            │  │
│  │  • Codes: user_id + election tracking               │  │
│  │  • Votes: vote_hash + selections (NO user_id)       │  │
│  │  • Results: aggregated data                          │  │
│  │  • Organisations: Multi-tenant isolation             │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### 2. Data Relationships

```
┌─────────────────────────────────────────────────────────────┐
│                    DATA FLOW & ISOLATION                    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────┐                                          │
│  │   USERS      │                                          │
│  │   (identity) │                                          │
│  └──────────────┘                                          │
│         │                                                   │
│         │ identifies                                        │
│         ▼                                                   │
│  ┌──────────────────────────────────────────────────┐     │
│  │   CODES TABLE (User Tracking)                    │     │
│  │   • code_id                                      │     │
│  │   • user_id ← Links user to participation       │     │
│  │   • election_id                                  │     │
│  │   • code1 ← Used in hash generation              │     │
│  │   • has_voted ← Boolean flag                     │     │
│  └──────────────────────────────────────────────────┘     │
│         │                                                   │
│         │ verification only (via hash)                     │
│         │ NO direct link to votes                          │
│         ▼                                                   │
│  ┌──────────────────────────────────────────────────┐     │
│  │   VOTES TABLE (Actual Voting Data)               │     │
│  │   • vote_id                                      │     │
│  │   • election_id                                  │     │
│  │   • organisation_id                              │     │
│  │   • vote_hash ← Cryptographic proof              │     │
│  │   • candidate_01..60 ← Vote selections           │     │
│  │   • no_vote_posts ← Abstentions                  │     │
│  │   • cast_at ← Timestamp                          │     │
│  │   • ❌ NO user_id ← CRITICAL!                    │     │
│  └──────────────────────────────────────────────────┘     │
│         │                                                   │
│         │ aggregation                                       │
│         ▼                                                   │
│  ┌──────────────────────────────────────────────────┐     │
│  │   RESULTS TABLE (Public Outcomes)                │     │
│  │   • result_id                                    │     │
│  │   • vote_id ← For verification                   │     │
│  │   • candidate_id ← Aggregated                    │     │
│  │   • vote_count ← Number of votes                 │     │
│  │   • ❌ NO user_id ← CRITICAL!                    │     │
│  └──────────────────────────────────────────────────┘     │
│                                                              │
│  KEY PRINCIPLE:                                             │
│  ┌──────────────────────────────────────────────────┐     │
│  │ Codes contain identity (who voted)               │     │
│  │ Votes contain choices (how they voted)           │     │
│  │ These tables are INTENTIONALLY NOT JOINED        │     │
│  │ This enforces anonymity at the database level    │     │
│  └──────────────────────────────────────────────────┘     │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## Key Design Principles

### 1. **Anonymity by Default**
- No user_id column in votes or results tables
- User identity isolated in codes table
- Database structure enforces anonymity

### 2. **Verifiability Through Cryptography**
- vote_hash = SHA256(user_id + election_id + code + timestamp)
- Uniquely identifies a voter's participation
- Voter can prove they voted WITHOUT revealing choices
- Tamper-proof (hash changes if vote is modified)

### 3. **Multi-Tenant Isolation**
- organisation_id scopes all queries
- Separate tables for real vs demo elections
- No cross-organisation data leakage possible

### 4. **Audit Trail Capability**
- vote_hash allows verification after the fact
- Timestamps enable dispute resolution
- Cryptographic proof prevents false claims

### 5. **Election Integrity**
- Votes require valid election_id
- Results linked to votes for verification
- No vote duplication possible (unique hash)

---

## The Voting Workflow (5 Steps)

```
STEP 1: Code Entry
├─ Voter enters voting code
├─ System looks up code in CODES table
└─ Verifies user identity & eligibility

STEP 2: Terms Acceptance
├─ Voter reviews election information
├─ Confirms understanding of voting process
└─ Locks in election context

STEP 3: Candidate Selection
├─ Voter selects candidates
├─ System validates selections against posting rules
└─ Voter can review/change selections

STEP 4: Vote Submission
├─ System generates vote_hash
├─ Creates VOTES record (NO user_id!)
├─ Creates RESULTS records (one per selection)
└─ Updates CODE as "has_voted"

STEP 5: Verification
├─ Voter receives vote confirmation
├─ vote_hash_prefix displayed for audit
├─ Voter can later verify using code & hash
└─ No information about choices revealed
```

---

## Vote Hash Generation Algorithm

The heart of Verifiable Anonymity:

```php
// Step 1: Gather components
$user_id = $code->user_id;           // User identity
$election_id = $code->election_id;   // Which election
$code_value = $code->code1;          // Voter's unique code
$timestamp = now()->timestamp;       // Vote submission time

// Step 2: Create input string
$input = $user_id . $election_id . $code_value . $timestamp;

// Step 3: Generate SHA256 hash
$vote_hash = hash('sha256', $input);

// Step 4: Store hash (identity components are NOT stored)
$vote->vote_hash = $vote_hash;
$vote->cast_at = $timestamp;
// NO user_id stored here!
```

### Security Properties

| Property | How It Works |
|----------|-------------|
| **Uniqueness** | Same user + election + code + time = same hash. Different = different hash. |
| **Irreversibility** | Cannot reverse SHA256 to get user_id, even knowing vote_hash |
| **Tamper Detection** | Changing any vote field changes hash. Verification fails. |
| **Auditability** | Voter can regenerate hash independently to verify |

---

## Multi-Tenancy: Organisation Isolation

```
┌─────────────────────────────────────────────────┐
│          LANDLORD DB (Platform)                 │
│  Contains: Organisations metadata                │
└─────────────────────────────────────────────────┘
            ↓
    ┌───────────────────────────────────┐
    │  Organisation A ID: 1             │
    │  Organisation B ID: 2             │
    │  Demo (NULL)                      │
    └───────────────────────────────────┘
            ↓↓↓
┌───────────────────────────────────────────────┐
│         APPLICATION DB (Votes & Results)      │
│                                               │
│  Codes where organisation_id = 1              │
│  Votes where organisation_id = 1              │
│  Results where organisation_id = 1            │
│                                               │
│  Codes where organisation_id = 2              │
│  Votes where organisation_id = 2              │
│  Results where organisation_id = 2            │
│                                               │
│  Codes where organisation_id IS NULL          │
│  Votes where organisation_id IS NULL (demo)   │
│  Results where organisation_id IS NULL        │
└───────────────────────────────────────────────┘
```

**Isolation Rules:**
- Every query includes `WHERE organisation_id = ?`
- Demo mode uses `organisation_id = NULL`
- No cross-organisation queries possible
- Global scopes enforce tenant boundaries

---

## Database Table Responsibilities

### Codes Table
**Purpose:** Track voter participation and eligibility
- Links user_id to election (identity)
- Tracks if voter has already voted
- Provides code1 for hash generation
- Marked as "voted" when vote submitted

### Votes Table
**Purpose:** Record actual voting data (anonymously)
- NO user_id (enforces anonymity)
- vote_hash for verification
- candidate_01 through candidate_60 (vote selections)
- no_vote_posts for abstentions
- cast_at timestamp
- organisation_id for multi-tenancy

### Results Table
**Purpose:** Aggregated election outcomes
- NO user_id (prevents voter identification)
- vote_id (reference to source vote)
- candidate_id (which candidate received vote)
- vote_count (for aggregation)
- One record per vote per candidate selected

---

## Key Files & Their Responsibilities

| File | Purpose |
|------|---------|
| `BaseVote.php` | Abstract model with verification logic |
| `BaseResult.php` | Abstract model with aggregation logic |
| `Vote.php` | Real elections votes table |
| `DemoVote.php` | Demo elections votes table |
| `Result.php` | Real elections results table |
| `DemoResult.php` | Demo elections results table |
| `Code.php` | Voter codes and participation tracking |
| `Election.php` | Election context and metadata |
| `VoteController.php` | Real vote submission handling |
| `DemoVoteController.php` | Demo vote submission handling |

---

## Why This Architecture?

### Traditional Approach (WRONG ❌)
```
Votes Table: [user_id, candidate_id]
Problem: Anyone with DB access knows exactly how each person voted
Risk: Coercion, voter intimidation, privacy violation
```

### Verifiable Anonymity Approach (RIGHT ✅)
```
Codes Table: [user_id, code1]
Votes Table: [vote_hash, candidate_id]  ← No user_id!

Result:
- Voter can prove they voted (via code + hash regeneration)
- NO ONE can determine how any voter voted (anonymity)
- Votes are tamper-proof (hash validation)
```

---

## Next Steps

- **Understand the concept?** → Read [02-verifiable-anonymity.md](./02-verifiable-anonymity.md)
- **Need schema details?** → Read [03-schema-changes.md](./03-schema-changes.md)
- **Ready to implement?** → Read [04-implementation-guide.md](./04-implementation-guide.md)
- **Want API details?** → Read [05-api-reference.md](./05-api-reference.md)

---

**Summary:** This architecture achieves the perfect balance between voter privacy (through database anonymity) and election integrity (through cryptographic verification). The vote_hash enables voters to verify their vote was recorded correctly without ever exposing how they voted.
