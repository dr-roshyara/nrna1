# Election Engine Multi-Tenancy Analysis

**Document**: ELECTION_ENGINE_ORGANISATION_ID_ANALYSIS
**Generated**: 2026-02-19 at 14:30:00 UTC
**Version**: 1.0
**Status**: DRAFT - PENDING USER VERIFICATION

---

## Executive Summary

The **election voting engine** is a sophisticated 5-step anonymous voting system with complete vote anonymity protection. To implement multi-tenancy with organisation_id while preserving vote anonymity and data isolation, we need to:

1. Add `organisation_id` to **8 core tables**
2. Add `BelongsToTenant` trait to **8 models**
3. Create **8 migrations**
4. Update **4 controllers** to ensure proper tenant context
5. Maintain **vote anonymity** (no user_id links)

---

## System Architecture Overview

### Current 5-Step Voting Workflow

```
Step 1: Code Verification (CodeController::store)
   ↓ voter_slug_steps.step = 1 recorded
   ↓
Step 2: Agreement Acceptance (CodeController::submitAgreement)
   ↓ voter_slug_steps.step = 2 recorded
   ↓
Step 3: Vote Selection (VoteController::first_submission)
   ↓ voter_slug_steps.step = 3 recorded
   ↓
Step 4: Vote Verification (VoteController::verify)
   ↓ voter_slug_steps.step = 4 recorded
   ↓
Step 5: Final Submission (VoteController::store)
   ↓ voter_slug_steps.step = 5 recorded
   ↓ Vote saved ANONYMOUSLY (NO user_id)
   ↓ Results recorded (NO user_id)
```

### Critical Principle: Vote Anonymity

**NEVER compromise this principle:**

```
Database Chain:
✓ users table        → has user_id
✓ codes table        → has user_id (links users to voting)
✗ votes table        → NO user_id (preserves anonymity)
✗ results table      → NO user_id (preserves anonymity)
✓ voter_slugs table  → has user_id (voter access token)

This INTENTIONAL separation ensures:
- Election officials cannot link votes to users
- No vote coercion is possible
- Anonymity is data-level, not just logical
```

---

## Physical Data Separation (CRITICAL)

### Election Type Separation

```
REAL ELECTIONS:
├── elections table (type='real')
├── votes table (NO user_id, has voting_code hash)
├── results table (NO user_id)
├── candidacies table (real candidates)
├── codes table (real voting codes)
└── posts table (real positions)

DEMO ELECTIONS:
├── elections table (type='demo')
├── demo_votes table (NO user_id, has voting_code hash)
├── demo_results table (NO user_id)
├── demo_candidacies table (demo candidates)
├── codes table (SHARED - same table, different election_id)
└── posts table (SHARED via election_id)

SHARED ACROSS BOTH:
├── voter_slugs table (user voting tokens)
├── voter_slug_steps table (step tracking)
├── codes table (all voting codes)
└── elections table (both real and demo)
```

---

## Tables Requiring organisation_id

### 1. Elections Table
**Current**: No organisation_id
**Required**: YES - identifies which org owns the election
**Reason**: Org A's elections must be separate from Org B's elections
**Impact**: HIGH - all voting logic flows through elections

```php
// Before
$election = Election::find($id);

// After
$election = Election::find($id);  // Automatically filtered by organisation_id
```

### 2. Codes Table
**Current**: No organisation_id
**Required**: YES - links user voting codes to specific org's elections
**Reason**: Org A users cannot use codes for Org B's elections
**Impact**: HIGH - first security layer

```php
// Before
$code = Code::where('code1', $input)->first();

// After
$code = Code::where('code1', $input)->first();  // Filtered by organisation_id
```

### 3. Votes Table (Real Elections)
**Current**: No organisation_id
**Required**: YES - for election scoping, NOT user identification
**Reason**: Real election votes must be org-scoped
**NOTE**: Still has NO user_id (anonymity preserved)
**Impact**: HIGH - data isolation

```sql
-- OLD: votes table
| id | voting_code | election_id | created_at |
| 1  | hash123     | 1          | 2026-02-19 |

-- NEW: votes table
| id | voting_code | election_id | organisation_id | created_at |
| 1  | hash123     | 1          | 1              | 2026-02-19 |

-- CRITICAL: Still NO user_id column (anonymity preserved)
```

### 4. DemoVotes Table
**Current**: No organisation_id
**Required**: YES - for election scoping
**Reason**: Demo election votes must be org-scoped
**NOTE**: Still has NO user_id (anonymity preserved)
**Impact**: MEDIUM - test data isolation

### 5. Results Table (Real Elections)
**Current**: No organisation_id
**Required**: YES - for election scoping
**Reason**: Real election results (candidate selections) must be org-scoped
**NOTE**: Still has NO user_id (anonymity preserved)
**Impact**: HIGH - data isolation

### 6. DemoResults Table
**Current**: No organisation_id
**Required**: YES - for election scoping
**Reason**: Demo results must be org-scoped
**NOTE**: Still has NO user_id (anonymity preserved)
**Impact**: MEDIUM - test data isolation

### 7. VoterSlugs Table
**Current**: Has election_id, NO organisation_id
**Required**: YES - voter tokens must be org-scoped
**Reason**: Org A users cannot vote using Org B's voter_slugs
**Impact**: MEDIUM - access token isolation

```php
// Current
$voterSlug = VoterSlug::where('slug', $slug)->first();

// After multi-tenancy
$voterSlug = VoterSlug::where('slug', $slug)->first();
// Filtered by session('current_organisation_id')
```

### 8. VoterSlugSteps Table
**Current**: Has election_id, NO organisation_id
**Required**: YES - step tracking must be org-scoped
**Reason**: Step progression must be per-org
**Impact**: LOW-MEDIUM - audit trail isolation

---

## Models Requiring BelongsToTenant Trait

### Summary Table

| Model | Table | Current organisation_id | Status |
|-------|-------|------------------------|--------|
| Election | elections | ❌ No | **REQUIRED** |
| Code | codes | ❌ No | **REQUIRED** |
| Vote | votes | ❌ No | **REQUIRED** |
| DemoVote | demo_votes | ❌ No | **REQUIRED** |
| Result | results | ❌ No | **REQUIRED** |
| DemoResult | demo_results | ❌ No | **REQUIRED** |
| VoterSlug | voter_slugs | ❌ No (has election_id) | **REQUIRED** |
| VoterSlugStep | voter_slug_steps | ❌ No (has election_id) | **REQUIRED** |

### Model Files to Update

```
1. app/Models/Election.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable

2. app/Models/Code.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable

3. app/Models/Vote.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - NOTE: Still has NO user_id

4. app/Models/DemoVote.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - NOTE: Still has NO user_id

5. app/Models/Result.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - NOTE: Still has NO user_id

6. app/Models/DemoResult.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - NOTE: Still has NO user_id

7. app/Models/VoterSlug.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - Already has election_id

8. app/Models/VoterSlugStep.php
   - Add: use BelongsToTenant;
   - Add: 'organisation_id' to $fillable
   - Already has election_id
```

---

## Migrations Required (8 Total)

### Migration 1: Elections Table
```php
Schema::table('elections', function (Blueprint $table) {
    if (!Schema::hasColumn('elections', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();
    }
});
```

### Migration 2: Codes Table
```php
Schema::table('codes', function (Blueprint $table) {
    if (!Schema::hasColumn('codes', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();
    }
});
```

### Migration 3: Votes Table
```php
Schema::table('votes', function (Blueprint $table) {
    if (!Schema::hasColumn('votes', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // NOTE: Deliberately no user_id to preserve anonymity
    }
});
```

### Migration 4: DemoVotes Table
```php
Schema::table('demo_votes', function (Blueprint $table) {
    if (!Schema::hasColumn('demo_votes', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // NOTE: Deliberately no user_id to preserve anonymity
    }
});
```

### Migration 5: Results Table
```php
Schema::table('results', function (Blueprint $table) {
    if (!Schema::hasColumn('results', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // NOTE: Deliberately no user_id to preserve anonymity
    }
});
```

### Migration 6: DemoResults Table
```php
Schema::table('demo_results', function (Blueprint $table) {
    if (!Schema::hasColumn('demo_results', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // NOTE: Deliberately no user_id to preserve anonymity
    }
});
```

### Migration 7: VoterSlugs Table
```php
Schema::table('voter_slugs', function (Blueprint $table) {
    if (!Schema::hasColumn('voter_slugs', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // Note: Already has election_id
    }
});
```

### Migration 8: VoterSlugSteps Table
```php
Schema::table('voter_slug_steps', function (Blueprint $table) {
    if (!Schema::hasColumn('voter_slug_steps', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();

        // Note: Already has election_id
    }
});
```

---

## Controllers Requiring Updates

### 1. CodeController (4 locations)
**File**: `app/Http/Controllers/CodeController.php`

```php
// Locations that need verification/updates:
1. Line ~50: create() method
   - Ensure voter_slug has organisation_id set

2. Line ~100: store() method
   - Verify codes are scoped by organisation_id
   - Verify election belongs to current organisation

3. Line ~200: submitAgreement() method
   - Verify election context is correct

4. Line ~500: getOrCreateCode() method
   - Ensure code is organisation_scoped
   - Prevent cross-org code generation
```

### 2. VoteController (5 locations)
**File**: `app/Http/Controllers/VoteController.php`

```php
// Locations that need verification/updates:
1. Line ~100: create() method
   - Ensure voter_slug has correct organisation_id

2. Line ~300: first_submission() method
   - Verify election and voter_slug match organisation

3. Line ~500: verify() method
   - Validate tenant context

4. Line ~1000: store() method (main vote submission)
   - CRITICAL: Ensure vote is saved with correct organisation_id
   - Ensure results are saved with correct organisation_id

5. Line ~1300: Show thankyou page
   - Validate organisation context
```

### 3. ElectionController
**File**: `app/Http/Controllers/ElectionController.php`

```php
// Ensure election selection respects organisation_id
- Only allow users to select elections from their organisation
- Verify session('current_organisation_id') matches election.organisation_id
```

### 4. VoterSlugService (if exists)
**File**: `app/Services/VoterSlugService.php`

```php
// Ensure voter slugs are created with correct organisation_id
- generateSlugForUser() must set organisation_id from session
- Must match user's organisation
```

---

## Data Flow with Multi-Tenancy

### Scenario: Org A User Voting in Org A Election

```
1. User Authenticates
   ├── auth()->user()->organisation_id = 1
   └── TenantContext middleware: session('current_organisation_id') = 1

2. User Gets Voter Slug
   ├── VoterSlug::create(['organisation_id' => 1, ...])
   └── Slug is scoped to Org A

3. Step 1: Code Verification
   ├── Code::where('code1', input)->first()
   │  └── Filtered by organisation_id = 1
   ├── Election must be Org A's election
   └── Step recorded with organisation_id = 1

4. Step 2-4: Agreement & Vote Selection
   ├── All queries filtered by organisation_id = 1
   └── Voter cannot access Org B's election candidates

5. Step 5: Vote Saved (ANONYMOUSLY)
   ├── Vote created with:
   │  ├── organisation_id = 1 ✓ (for data isolation)
   │  ├── election_id = 1 ✓ (which election)
   │  ├── voting_code hash ✓ (audit trail)
   │  └── NO user_id ✓ (anonymity preserved)
   ├── Results created with:
   │  ├── organisation_id = 1 ✓ (for data isolation)
   │  ├── election_id = 1 ✓
   │  ├── voting_code hash ✓
   │  └── NO user_id ✓
   └── Step 5 recorded

6. Results Aggregation
   ├── Election::find($id)->results
   │  └── Filtered by organisation_id = 1
   └── Only Org A's votes counted
```

### Security Guarantee

```
Attack Scenario: Org A User Tries to Vote in Org B Election

1. User Gets Org A Voter Slug (organisation_id = 1)
2. Tries to access Org B's election (organisation_id = 2)

Blocked at EVERY layer:
├── Layer 1 (Middleware): VoterSlug.organisation_id ≠ current session
├── Layer 2 (Controller): Election.organisation_id ≠ current session
├── Layer 3 (Query): Code scoped by organisation_id
├── Layer 4 (Model): BelongsToTenant filters all queries
└── Result: 403 Forbidden OR 404 Not Found
```

---

## Why This Works for Vote Anonymity

### Key Insight

```
organisation_id is for DATA ISOLATION, not USER IDENTIFICATION

Votes table structure:
| id | voting_code | election_id | organisation_id | created_at |
|    | (hash)      | (election)  | (org)           |            |

Security properties:
✓ No user_id → Anonymity preserved
✓ organisation_id → Data isolation enforced
✓ voting_code hash → Audit trail available
✓ election_id → Results aggregation possible

Result:
- Election officials see: "1000 votes cast, 40% chose Candidate A"
- Election officials cannot see: "Which user chose Candidate A"
- Multi-tenancy enforced: "Org A cannot see Org B's votes"
```

---

## Testing Strategy

### Test 1: Tenant Isolation
```php
public function test_votes_isolated_by_organisation()
{
    // Org 1: Create election and vote
    session(['current_organisation_id' => 1]);
    $org1Election = Election::create(['name' => 'Org1 Election']);
    $org1Vote = Vote::create(['election_id' => $org1Election->id, ...]);

    // Org 2: Try to access
    session(['current_organisation_id' => 2]);
    $votes = Vote::all();

    // Assert: Org 2 sees 0 votes
    $this->assertCount(0, $votes);
}
```

### Test 2: Voting Process Respects Tenancy
```php
public function test_5_step_voting_process_respects_organisation()
{
    // Set Org 1 context
    $this->actAsUser($org1User);  // Sets organisation_id in session

    // Generate voter slug (should have organisation_id = 1)
    $voterSlug = VoterSlug::create([...]);
    $this->assertEquals(1, $voterSlug->organisation_id);

    // Go through 5 steps
    // 1. CodeController::store -> Step 1 recorded
    // 2. CodeController::submitAgreement -> Step 2 recorded
    // 3. VoteController::first_submission -> Step 3 recorded
    // 4. VoteController::verify -> Step 4 recorded
    // 5. VoteController::store -> Vote saved with organisation_id = 1

    // Verify vote has correct organisation_id
    $vote = Vote::first();
    $this->assertEquals(1, $vote->organisation_id);
}
```

### Test 3: Demo Elections Also Org-Scoped
```php
public function test_demo_elections_are_organisation_scoped()
{
    // Demo election should also respect organisation_id
    session(['current_organisation_id' => 1]);
    $demoVote = DemoVote::create([...]);
    $this->assertEquals(1, $demoVote->organisation_id);
}
```

---

## Implementation Roadmap

### Phase 1: Preparation (Review & Planning)
- [ ] User verifies this analysis
- [ ] User approves migration approach
- [ ] User confirms table list is complete

### Phase 2: Migrations (Database Changes)
- [ ] Create 8 migration files
- [ ] Run migrations
- [ ] Verify columns added with indexes

### Phase 3: Models (ORM Updates)
- [ ] Add BelongsToTenant trait to 8 models
- [ ] Add organisation_id to $fillable arrays
- [ ] Test auto-fill with tinker

### Phase 4: Controllers (Application Logic)
- [ ] Review CodeController for organisation context
- [ ] Review VoteController for organisation context
- [ ] Update VoterSlugService if needed
- [ ] Verify ElectionController respects org scoping

### Phase 5: Testing (Verification)
- [ ] Create tenant isolation tests
- [ ] Test 5-step voting process with multi-tenancy
- [ ] Test demo elections with multi-tenancy
- [ ] Test vote anonymity is preserved
- [ ] Run all existing tests to ensure no regressions

### Phase 6: Documentation
- [ ] Create ELECTION_ENGINE_TENANCY.md guide
- [ ] Update ADDING_TENANCY.md with voting system notes
- [ ] Document organisation_id requirements for votes/results

---

## Critical Notes

### ⚠️ PRESERVE VOTE ANONYMITY AT ALL COSTS

```
DO NOT add user_id to votes or results tables
DO NOT create any user_id ↔ vote linkage
DO NOT bypass the voting_code hash system

This is not just a technical requirement, it's a LEGAL requirement.
Vote anonymity must be maintained for election integrity.
```

### ⚠️ MAINTAIN ELECTION TYPE SEPARATION

```
Real elections and demo elections must:
✓ Use separate vote tables (votes vs demo_votes)
✓ Be separately resettable
✓ Have independent result tracking
✓ Allow demo re-voting while blocking real re-voting
```

### ⚠️ VERIFY TENANT CONTEXT IN CONTROLLERS

```
Every step of the 5-step process must verify:
1. Voter belongs to current organisation
2. Election belongs to current organisation
3. Codes match current organisation
4. Results are saved with correct organisation_id
```

---

## Questions for User Verification

1. **Table List**: Are these 8 tables complete?
   - Elections
   - Codes
   - Votes
   - DemoVotes
   - Results
   - DemoResults
   - VoterSlugs
   - VoterSlugSteps

2. **Model List**: Are these 8 models correct?
   - Election
   - Code
   - Vote
   - DemoVote
   - Result
   - DemoResult
   - VoterSlug
   - VoterSlugStep

3. **Controller Updates**: Should we update:
   - CodeController (4 methods)
   - VoteController (5 methods)
   - ElectionController (1 method)
   - VoterSlugService (1 method)

4. **Vote Anonymity**: Confirmed we should NOT add user_id to votes/results tables?

5. **Tenant Filtering**: Should all queries automatically filter by organisation_id via BelongsToTenant trait?

---

## Summary

**Status**: ✅ Analysis Complete, Awaiting Verification

**Total Changes**:
- 8 Tables: Add organisation_id column + index
- 8 Models: Add BelongsToTenant trait
- 8 Migrations: Create migration files
- 4 Controllers: Review & verify organisation context
- Vote Anonymity: Preserved (NO user_id added)
- Security: Tenant isolation enforced at all layers

**Next Step**: User reviews and verifies this analysis, then we proceed with implementation.

---

**Document Generated**: 2026-02-19 at 14:30:00 UTC
**Document Status**: DRAFT - Awaiting User Verification
**Next Action**: Please review and confirm or suggest modifications
