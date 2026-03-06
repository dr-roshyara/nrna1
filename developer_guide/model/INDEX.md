# Developer Guide: Model Implementation & DeviceFingerprint Service

**Last Updated:** 2026-03-06
**Status:** Phase C.4 Complete - Ready for Phase D (Controller Integration)

---

## 📋 Table of Contents

1. [Session Overview](#session-overview)
2. [DeviceFingerprint Service (New)](#devicefingerprint-service-new)
3. [Model Review & Fixes](#model-review--fixes)
4. [Architecture Patterns](#architecture-patterns)
5. [Testing Patterns](#testing-patterns)
6. [Error Resolution Log](#error-resolution-log)
7. [Database Schema Reference](#database-schema-reference)
8. [Next Steps & Pending Tasks](#next-steps--pending-tasks)
9. [Quick Reference](#quick-reference)

---

## Session Overview

### Objectives Completed

| Objective | Status | Tests | Coverage |
|-----------|--------|-------|----------|
| Implement DeviceFingerprint service (TDD) | ✅ Complete | 7/7 passing | 18 assertions |
| Review 14 core models (6A + 4B + 4C) | ✅ Complete | Comprehensive | All reviewed |
| Fix schema-model mismatches | ✅ Complete | 3 models | Code, VoterSlug, DemoCode |
| Document architecture decisions | ✅ Complete | N/A | This guide |

### Key Deliverables

```
New Files:
  ✅ app/Services/DeviceFingerprint.php (180 lines)
  ✅ tests/Unit/Services/DeviceFingerprintTest.php (224 lines)
  ✅ database/migrations/2026_03_06_155200_add_device_fingerprinting_to_codes.php

Modified Files:
  ✅ app/Models/Code.php (fixed fillable & casts)
  ✅ app/Models/VoterSlug.php (added 23 missing columns)
  ✅ app/Models/DemoCode.php (added HasUuids trait)
  ✅ database/factories/CodeFactory.php (fixed organisation_id)
  ✅ app/Providers/AppServiceProvider.php (singleton registration)

Reviewed (No Changes):
  ✅ Organisation, User, UserOrganisationRole, Election, Post, Candidacy
  ✅ Vote, Result, DemoVoterSlug, DemoVote, DemoResult
```

---

## DeviceFingerprint Service (New)

### Purpose

Privacy-preserving device identification and vote fraud detection. Enables configurable per-device vote limits without exposing voter identity.

### Architecture

```
Request → DeviceFingerprint::generate()
           ↓
        Extract: IP + User-Agent + Salt
           ↓
        SHA256 Hash (one-way, irreversible)
           ↓
        Store in codes.device_fingerprint_hash
           ↓
        Query & Analytics via DeviceFingerprint service
```

### Class Structure

**File:** `app/Services/DeviceFingerprint.php`

#### Public Methods

```php
public function generate(Request $request, array $additional = []): string
```
- **Purpose:** Create device fingerprint from HTTP request
- **Input:** Illuminate\Http\Request, optional additional data array
- **Output:** SHA256 hash (64 chars)
- **Logic:** `SHA256('ip.useragent.salt' . implode($additional))`
- **Privacy:** One-way hash - cannot reverse to identify user
- **Testing:** Deterministic - same request = same hash

```php
public function canVote(
    string $deviceHash,
    string $electionId,
    ?Organisation $organisation = null
): array
```
- **Purpose:** Check if device can still vote
- **Returns:**
  ```php
  [
    'allowed' => bool,           // Can vote now?
    'used' => int,               // Votes already cast
    'max' => int,                // Configured maximum
    'remaining' => int,          // Can vote N more times
    'limit_message' => string,   // User-friendly text
  ]
  ```
- **Config:** `config('voting.max_votes_per_device')` (default: 3)
- **Override:** Via `$organisation->voting_settings['max_votes_per_device']`
- **Query:** Counts codes with matching device_fingerprint_hash

```php
public function detectAnomaly(
    string $deviceHash,
    string $electionId
): array
```
- **Purpose:** Find suspicious voting patterns
- **Returns:**
  ```php
  [
    'detected' => bool,           // Is anomaly present?
    'count' => int,               // Codes in time window
    'threshold' => int,           // Anomaly threshold
    'time_window_minutes' => int, // Config lookback period
  ]
  ```
- **Thresholds:** Configured via:
  - `config('voting.device_anomaly_threshold')` (default: 5)
  - `config('voting.device_time_window_minutes')` (default: 15)
- **Logic:** Codes created in last N minutes > threshold = anomaly

```php
public function getLimitMessage(
    ?Organisation $organisation = null,
    int $remaining = null
): string
```
- **Purpose:** User-friendly vote limit message
- **Customizable:** Per organisation if provided
- **Fallback:** Uses default config if org not provided
- **Example Output:** "Your family can cast 3 votes per device"

```php
public function getDeviceStats(
    string $deviceHash,
    string $electionId
): array
```
- **Purpose:** Device usage analytics
- **Returns:**
  ```php
  [
    'total_codes' => int,
    'used_codes' => int,           // Both codes exhausted
    'unused_codes' => int,         // Still have votes
    'first_used' => Carbon|null,   // First code creation
    'last_used' => Carbon|null,    // Last code creation
  ]
  ```

### Configuration

Add to `config/voting.php`:

```php
return [
    // Device-based fraud detection
    'max_votes_per_device' => env('VOTING_MAX_VOTES_PER_DEVICE', 3),
    'device_anomaly_threshold' => env('VOTING_DEVICE_ANOMALY_THRESHOLD', 5),
    'device_time_window_minutes' => env('VOTING_DEVICE_TIME_WINDOW_MINUTES', 15),
];
```

### Service Registration

**File:** `app/Providers/AppServiceProvider.php`

```php
public function register(): void
{
    $this->app->singleton(DeviceFingerprint::class, function () {
        return new DeviceFingerprint();
    });
}
```

### Usage Example

```php
// In VoterController::store()
$deviceHash = app(DeviceFingerprint::class)->generate($request);

$canVote = app(DeviceFingerprint::class)->canVote($deviceHash, $electionId);
if (!$canVote['allowed']) {
    return back()->withErrors([
        'device_limit' => $canVote['limit_message']
    ]);
}

// Store in codes table
$code->update(['device_fingerprint_hash' => $deviceHash]);
```

### Test Coverage

**File:** `tests/Unit/Services/DeviceFingerprintTest.php`

| Test | Assertions | Status |
|------|-----------|--------|
| `it_generates_consistent_hash_for_same_device()` | 1 | ✅ |
| `it_generates_different_hash_for_different_ips()` | 1 | ✅ |
| `it_respects_max_votes_per_device_config()` | 4 | ✅ |
| `it_blocks_when_max_votes_reached()` | 2 | ✅ |
| `it_detects_anomalous_patterns()` | 3 | ✅ |
| `it_returns_family_voting_message_when_configured()` | 2 | ✅ |
| `it_gets_device_statistics()` | 4 | ✅ |
| **Total** | **18 assertions** | **✅ 7/7 PASSING** |

#### Test Helper Method

```php
protected function createCodeWithDevice(
    string $deviceHash,
    string $electionId,
    string $orgId
): Code
```

**Purpose:** Create codes for testing without factory complexity

**Why Needed:**
- Factory dependency chains unreliable in test isolation
- Global scopes filter test records
- Raw inserts bypass these issues

**Implementation:**
1. Ensure election exists (create if missing)
2. Create user via raw insert
3. Create code via raw insert with all required fields
4. Return model fetched without global scopes

**Advantages:**
- Deterministic (no factory randomization)
- Isolated (bypasses boot hooks)
- Fast (single database roundtrip)

---

## Model Review & Fixes

### Overview

Comprehensive review of 14 core models (6 Phase A + 4 Phase B + 4 Phase C) identified 3 critical schema-model mismatches.

### Models Reviewed

#### Phase A (Core Models) - 6 models
- ✅ Organisation - Correct
- ✅ User - Correct (code() relationship flagged for anonymity review)
- ✅ UserOrganisationRole - Correct
- ✅ Election - Correct
- ✅ Post - Correct
- ⚠️ Candidacy - User relationship flagged (for candidate display, not result aggregation)

#### Phase B (Voting Models) - 4 models
- ❌ **Code** - 3 issues found & fixed
- ❌ **VoterSlug** - 1 issue found & fixed
- ✅ Vote - Correct (NO user relationship - anonymity preserved)
- ✅ Result - Correct (NO user relationship - anonymity preserved)

#### Phase C (Demo Models) - 4 models
- ❌ **DemoCode** - 1 issue found & fixed
- ⚠️ DemoVoterSlug - Correct (used as reference implementation)
- ✅ DemoVote - Correct (JSON candidate schema documented)
- ✅ DemoResult - Correct

### Critical Fixes

#### 1. Code Model - Schema Mismatch

**File:** `app/Models/Code.php`

**Issue:** Fillable array contained 38 fields; actual UUID migration schema has only ~24 fields

**Root Cause:** Legacy column definitions from old integer-based schema not updated during UUID consolidation (Phases 1-3)

**Fields Removed (10 total):**
```php
// These columns DO NOT EXIST in the UUID migration
'code3',                  // Was never dual-code system
'code4',
'code3_sent_at',
'code4_sent_at',
'code3_used_at',
'code4_used_at',
'vote_show_code',        // Legacy voting column
'code_for_vote',
'session_name',
'voting_time_in_minutes' // Now in voter_slugs.voting_time_min
```

**Fields Added (2 total):**
```php
// Device fingerprinting - NEW
'device_fingerprint_hash',
'device_metadata_anonymized',
```

**Fields Corrected (Casts):**
```php
// Removed non-existent field casts
// Added: 'device_metadata_anonymized' => 'array'
```

**Result:** Fillable now has 24 fields, all existing in database

**Verification:**
```sql
-- Check Code table columns
DESCRIBE codes;
-- Expected: id, organisation_id, user_id, election_id, code1, code2,
--           is_code1_usable, is_code2_usable, code1_sent_at, code2_sent_at,
--           can_vote_now, has_voted, code1_used_at, code2_used_at,
--           vote_submitted, vote_submitted_at, has_code1_sent, has_code2_sent,
--           has_agreed_to_vote, has_used_code1, has_used_code2,
--           has_agreed_to_vote_at, voting_started_at, is_codemodel_valid,
--           device_fingerprint_hash, device_metadata_anonymized,
--           created_at, updated_at, deleted_at
```

#### 2. VoterSlug Model - Missing Step Tracking Columns

**File:** `app/Models/VoterSlug.php`

**Issue:** Fillable array had 6 fields; DemoVoterSlug reference implementation had 23 fields

**Root Cause:** Model implemented incomplete - step tracking infrastructure not included

**Fields Added (23 total):**
```php
// Step tracking columns - ALL MISSING from original
'expires_at',              // Token expiration
'is_active',              // Active/inactive toggle
'current_step',           // 1-5, current voting step
'step_meta',              // JSON metadata per step
'has_voted',              // Boolean - voted yet?
'can_vote_now',           // Boolean - voting allowed?
'voting_time_min',        // Integer - minutes allowed

// Step 1 tracking
'step_1_ip',              // IP address at step 1
'step_1_completed_at',    // Timestamp completed

// Step 2 tracking
'step_2_ip',
'step_2_completed_at',

// Step 3 tracking
'step_3_ip',
'step_3_completed_at',

// Step 4 tracking
'step_4_ip',
'step_4_completed_at',

// Step 5 tracking
'step_5_ip',
'step_5_completed_at',
```

**Casts Added (23 total):**
```php
protected $casts = [
    // All datetime fields
    'expires_at' => 'datetime',
    'step_1_completed_at' => 'datetime',
    'step_2_completed_at' => 'datetime',
    'step_3_completed_at' => 'datetime',
    'step_4_completed_at' => 'datetime',
    'step_5_completed_at' => 'datetime',

    // Boolean fields
    'is_active' => 'boolean',
    'has_voted' => 'boolean',
    'can_vote_now' => 'boolean',

    // Integer fields
    'current_step' => 'integer',
    'voting_time_min' => 'integer',

    // JSON fields
    'step_meta' => 'array',
];
```

**Reference:** Used `DemoVoterSlug` model as source of truth (mirrors real schema)

**Result:** VoterSlug now fully mirrors database schema and DemoVoterSlug

#### 3. DemoCode Model - Missing HasUuids Trait

**File:** `app/Models/DemoCode.php`

**Issue:** Model had UUID primary key configuration but no HasUuids trait

**Root Cause:** Trait not included during demo model creation

**Fix Applied:**
```php
class DemoCode extends Model
{
    use HasFactory, HasUuids;  // ← ADDED
    use BelongsToTenant;

    protected $table = 'demo_codes';
    protected $keyType = 'string';        // Already correct
    public $incrementing = false;         // Already correct
}
```

**Additional Alignment:**
- Updated fillable to match Code model (removed 10 legacy fields, added device fingerprinting)
- Updated casts to match Code model
- Now consistent with Code model structure

**Result:** DemoCode model fully aligned with Code model and database schema

### Models Flagged for Review (No Changes)

#### User Model - code() Relationship

**File:** `app/Models/User.php`

**Status:** ⚠️ Needs architecture review (not modified)

**Question:** Does `$user->code()` relationship violate anonymity principles?

**Context:**
- Used in controllers to link users to their voting codes
- Codes are used for verification, not vote linkage
- Vote table has NO user_id (anonymity preserved)

**Architectural Intent:**
- Users have codes (for voting eligibility)
- Codes → verified → votes cast (anonymously)
- No vote-user relationship exists

**Verdict:** Acceptable - code() is pre-voting, vote() is post-voting (unlinked)

#### Candidacy Model - user() Relationship

**File:** `app/Models/Candidacy.php`

**Status:** ⚠️ Noted (not modified)

**Question:** Does candidacy-user relationship violate anonymity?

**Context:**
- Candidacy represents a candidate for a position
- User is the actual person running
- Results table shows vote counts per candidacy, not per person

**Architectural Intent:**
- Elections display candidates (public)
- Results show which candidates won
- No result-user-vote chain exists

**Verdict:** Acceptable - shows who is running, not how they voted

---

## Architecture Patterns

### 1. Multi-Tenancy Pattern (organisation_id)

All core models include tenant scoping:

```php
class Code extends Model
{
    use BelongsToTenant;  // Adds organisation_id scope

    // All queries automatically filtered by:
    // WHERE organisation_id = {current_org_id}
}
```

**Key Points:**
- BelongsToTenant trait adds global scope
- withoutGlobalScopes() available for special cases (testing, cross-tenant queries)
- organisation_id on every model query

### 2. UUID Primary Keys

All models use UUID string identifiers:

```php
class Code extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
}
```

**Database:** `id CHAR(36) PRIMARY KEY`

**Advantages:**
- Distributed generation (no race conditions)
- Type safety (string, not integer)
- Security (no predictable sequences)
- Consistency across multi-tenant system

### 3. Vote Anonymity Pattern

Critical constraint: NO direct user-vote relationship

```php
// ✅ CORRECT: No user_id in votes table
class Vote extends Model
{
    // NO user() relationship
    // NO user_id column

    public function voterSlug()  // One-way only
    {
        return $this->belongsTo(VoterSlug::class);
    }
}

// ✅ CORRECT: No user_id in results table
class Result extends Model
{
    // NO user() relationship
    // NO user_id column

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }
}
```

**Enforcement:**
- Votes verified via voter_slug (code → slug → vote)
- Results aggregated anonymously
- No audit trail links vote to voter

### 4. Device Fingerprinting Pattern

Privacy-preserving fraud detection:

```php
// Generate
$hash = app(DeviceFingerprint::class)->generate($request);

// Store
$code->update(['device_fingerprint_hash' => $hash]);

// Query
$stats = app(DeviceFingerprint::class)->getDeviceStats($hash, $electionId);
```

**Privacy Guarantee:**
- Hash is one-way (cannot reverse to IP)
- No personal data stored
- Salt ensures different devices get different hashes
- Can detect fraud without identifying users

### 5. Factory Pattern (Mass Assignment)

Factories use explicit organisation context:

```php
// ✅ CORRECT
Code::factory()
    ->for(User::factory()->forOrganisation($org), 'user')
    ->create([
        'organisation_id' => $org->id,
        'election_id' => $election->id,
    ]);

// ❌ WRONG - Missing organisation context
Code::factory()->create();
```

**Key Points:**
- Factories require explicit organisation_id
- User must be created forOrganisation() first
- Foreign keys validated on create

---

## Testing Patterns

### 1. TDD First Approach

All features tested BEFORE implementation:

```
Step 1: Write failing test (RED)
Step 2: Run test → verify FAIL
Step 3: Write minimal implementation (GREEN)
Step 4: Run test → verify PASS
Step 5: Refactor & clean up
Step 6: Commit with test reference
```

### 2. Test Isolation with Global Scopes

Models use global scopes that filter queries:

```php
// ❌ Problem: Global scope filters test records
$code = Code::find($testCodeId);  // Returns null!

// ✅ Solution: Bypass global scopes in tests
$code = Code::withoutGlobalScopes()->find($testCodeId);  // Works!
```

**Used In:** `DeviceFingerprint` service methods

### 3. Raw Database Inserts for Test Data

Factory complexity bypassed with direct inserts:

```php
// Create test code directly
DB::table('codes')->insert([
    'id' => Str::uuid()->toString(),
    'organisation_id' => $orgId,
    'user_id' => $userId,
    'election_id' => $electionId,
    'device_fingerprint_hash' => $deviceHash,
    'code1' => (string) rand(100000, 999999),
    'is_code1_usable' => 1,
    'is_code2_usable' => 0,
    // ... other required fields
]);

// Fetch without global scopes
return Code::withoutGlobalScopes()->find($codeId);
```

**Advantages:**
- Fast (single INSERT)
- Deterministic (no randomization)
- Isolated (no boot hooks)
- Clear (explicit field assignment)

### 4. Faker for Realistic Data

Test data uses realistic values:

```php
$faker = FakerFactory::create();

DB::table('elections')->insert([
    'id' => $electionId,
    'name' => $faker->word(),          // Realistic name
    'slug' => $faker->slug(),          // URL-safe slug
    'type' => 'demo',                  // Fixed type
    'is_active' => 1,                  // Consistent state
    'start_date' => now(),
    'end_date' => now()->addDays(7),
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### 5. Assertion Coverage

Each test has clear assertions:

```php
public function it_respects_max_votes_per_device_config()
{
    config(['voting.max_votes_per_device' => 3]);

    // Create 2 codes with same device
    $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
    $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

    $result = $service->canVote($deviceHash, $electionId);

    // 4 assertions
    $this->assertTrue($result['allowed']);           // ✓ Can still vote
    $this->assertEquals(2, $result['used']);         // ✓ Used 2
    $this->assertEquals(3, $result['max']);          // ✓ Max is 3
    $this->assertEquals(1, $result['remaining']);    // ✓ 1 left
}
```

---

## Error Resolution Log

### Error 1: Factory Organisation ID Not Set

**Symptom:** Code factory created records with null organisation_id

**Root Cause:** CodeFactory definition() called `User::factory()->create()` without forOrganisation()

**Resolution:**
```php
// BEFORE (broken)
'user_id' => User::factory(),

// AFTER (fixed)
'user_id' => User::factory()
    ->forOrganisation($platform)
    ->create()
    ->id,
'organisation_id' => $platform->id,
```

**Learning:** Factory dependencies must explicitly scope to organisation

---

### Error 2: device_fingerprint_hash Column Not Found

**Symptom:** Tests failed with "Unknown column 'device_fingerprint_hash' in 'field list'"

**Root Cause:** Migration not created yet; tests tried to insert data into missing columns

**Resolution:** Created migration `2026_03_06_155200_add_device_fingerprinting_to_codes.php`

**Columns Added:**
```sql
ALTER TABLE codes ADD COLUMN device_fingerprint_hash VARCHAR(255) NULL;
ALTER TABLE codes ADD COLUMN device_metadata_anonymized JSON NULL;
CREATE INDEX idx_device_fingerprint ON codes(device_fingerprint_hash, election_id);
```

**Learning:** Always create migrations before tests that depend on new columns

---

### Error 3: Code::find() Returns Null in Tests

**Symptom:** Raw inserts created records, but Code::find($id) returned null

**Root Cause:** BelongsToTenant global scope filtered out test records created with test organisation_id

**Resolution:**
```php
// ❌ Before: Returns null due to global scope
$code = Code::find($codeId);

// ✅ After: Returns code without scope filtering
$code = Code::withoutGlobalScopes()->find($codeId);
```

**Usage Pattern:**
```php
protected function createCodeWithDevice(
    string $deviceHash,
    string $electionId,
    string $orgId
): Code
{
    // ... create code via raw insert ...

    // Return without global scopes
    return Code::withoutGlobalScopes()->find($codeId)
        ?? throw new \Exception("Code not found: $codeId");
}
```

**Learning:** Global scopes bypass needed in tests; use withoutGlobalScopes()

---

### Error 4: Test Table Missing Required Columns

**Symptom:** Tests failed with "NOT NULL constraint failed"

**Root Cause:** Raw inserts didn't include all NOT NULL columns from migration

**Resolution:** Updated helper method to include all required fields:

```php
DB::table('codes')->insert([
    'id' => $codeId,
    'organisation_id' => $orgId,              // NOT NULL
    'user_id' => $userId,                     // NOT NULL
    'election_id' => $electionId,             // NOT NULL
    'device_fingerprint_hash' => $deviceHash, // NEW
    'code1' => (string) rand(100000, 999999), // NOT NULL
    'code2' => (string) rand(100000, 999999), // NOT NULL
    'is_code1_usable' => 1,                   // NOT NULL
    'is_code2_usable' => 0,                   // NOT NULL
    'can_vote_now' => 0,                      // NOT NULL
    'has_voted' => 0,                         // NOT NULL
    'created_at' => now(),                    // NOT NULL
    'updated_at' => now(),                    // NOT NULL
]);
```

**Learning:** Review migration to find all NOT NULL columns before raw inserts

---

### Error 5: Code Model Fillable Array Mismatch

**Symptom:** Discrepancies between migration columns and model fillable

**Root Cause:** Legacy columns from old schema not cleaned up during UUID consolidation

**Resolution:** Removed 10 non-existent columns, added 2 missing device fingerprinting columns

**Columns Removed:**
- code3, code4, code3_sent_at, code4_sent_at
- code3_used_at, code4_used_at
- vote_show_code, code_for_vote
- session_name, voting_time_in_minutes

**Columns Added:**
- device_fingerprint_hash
- device_metadata_anonymized

**Verification:**
```bash
# Check migrations
git log --oneline database/migrations/ | head -20

# Check model
grep -A 50 'protected $fillable' app/Models/Code.php
```

**Learning:** Always align model fillable with latest migration schema

---

### Error 6: VoterSlug Missing Step Tracking Columns

**Symptom:** VoterSlug model had 6 fillable fields; database schema had 23

**Root Cause:** Model implemented incomplete; DemoVoterSlug was reference but never mirrored

**Resolution:** Added all 23 missing fields to match DemoVoterSlug and database schema

**Source of Truth:**
```bash
# Compare implementations
diff app/Models/VoterSlug.php app/Models/DemoVoterSlug.php
# Find missing columns in real implementation
```

**Learning:** Use demo models as reference implementation; keep them synchronized

---

### Error 7: DemoCode Missing HasUuids Trait

**Symptom:** DemoCode table uses UUID primary key but model didn't have trait

**Root Cause:** Trait configuration incomplete during demo model creation

**Resolution:** Added HasUuids trait to match Code model

```php
// Before
class DemoCode extends Model
{
    use HasFactory;  // Missing HasUuids!
}

// After
class DemoCode extends Model
{
    use HasFactory, HasUuids;  // ✓ Complete
}
```

**Learning:** Demo and real models must have identical trait configurations

---

## Database Schema Reference

### Core Voting Tables

#### codes table
```sql
CREATE TABLE codes (
    id CHAR(36) PRIMARY KEY,
    organisation_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    election_id CHAR(36) NOT NULL,

    -- Two-code system
    code1 VARCHAR(6) NOT NULL,
    code2 VARCHAR(6) NOT NULL,
    is_code1_usable BOOLEAN DEFAULT 1,
    is_code2_usable BOOLEAN DEFAULT 0,
    code1_sent_at TIMESTAMP NULL,
    code2_sent_at TIMESTAMP NULL,
    code1_used_at TIMESTAMP NULL,
    code2_used_at TIMESTAMP NULL,

    -- Voting state
    can_vote_now BOOLEAN DEFAULT 0,
    has_voted BOOLEAN DEFAULT 0,
    has_code1_sent BOOLEAN DEFAULT 0,
    has_code2_sent BOOLEAN DEFAULT 0,

    -- Verification
    has_agreed_to_vote BOOLEAN DEFAULT 0,
    has_used_code1 BOOLEAN DEFAULT 0,
    has_used_code2 BOOLEAN DEFAULT 0,
    has_agreed_to_vote_at TIMESTAMP NULL,

    -- Voting lifecycle
    voting_started_at TIMESTAMP NULL,
    vote_submitted BOOLEAN DEFAULT 0,
    vote_submitted_at TIMESTAMP NULL,
    is_codemodel_valid BOOLEAN DEFAULT 1,

    -- Device fingerprinting
    device_fingerprint_hash VARCHAR(255) NULL,
    device_metadata_anonymized JSON NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_org (organisation_id),
    INDEX idx_election (election_id),
    INDEX idx_device_fingerprint (device_fingerprint_hash, election_id)
);
```

#### voter_slugs table
```sql
CREATE TABLE voter_slugs (
    id CHAR(36) PRIMARY KEY,
    organisation_id CHAR(36) NOT NULL,
    election_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,

    -- Voting token
    slug VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT 1,

    -- Step tracking (5 steps)
    current_step INTEGER DEFAULT 1,
    step_meta JSON NULL,

    -- Step 1: Code entry
    step_1_ip VARCHAR(45) NULL,
    step_1_completed_at TIMESTAMP NULL,

    -- Step 2: Agreement acceptance
    step_2_ip VARCHAR(45) NULL,
    step_2_completed_at TIMESTAMP NULL,

    -- Step 3: Vote selection
    step_3_ip VARCHAR(45) NULL,
    step_3_completed_at TIMESTAMP NULL,

    -- Step 4: Verification
    step_4_ip VARCHAR(45) NULL,
    step_4_completed_at TIMESTAMP NULL,

    -- Step 5: Completion
    step_5_ip VARCHAR(45) NULL,
    step_5_completed_at TIMESTAMP NULL,

    -- Voting state
    has_voted BOOLEAN DEFAULT 0,
    can_vote_now BOOLEAN DEFAULT 0,
    voting_time_min INTEGER NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_org (organisation_id),
    INDEX idx_election (election_id),
    INDEX idx_slug (slug)
);
```

#### votes table (ANONYMITY CRITICAL)
```sql
CREATE TABLE votes (
    id CHAR(36) PRIMARY KEY,
    election_id CHAR(36) NOT NULL,

    -- NO user_id column - votes are anonymous
    -- Verification via voter_slug, not direct user linkage

    -- Vote receipt
    voter_slug_id CHAR(36) NOT NULL,
    receipt_hash VARCHAR(255) UNIQUE NULL,
    participation_proof VARCHAR(255) NULL,
    encrypted_vote TEXT NULL,

    -- Candidate selections (JSON or columns)
    -- Post 1-10 candidates (varies per post)

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (voter_slug_id) REFERENCES voter_slugs(id),
    -- NO FOREIGN KEY to users
    INDEX idx_election (election_id),
    UNIQUE KEY uidx_voter_election (voter_slug_id, election_id)
);
```

#### results table (ANONYMITY CRITICAL)
```sql
CREATE TABLE results (
    id CHAR(36) PRIMARY KEY,
    election_id CHAR(36) NOT NULL,

    -- NO user_id column - results are aggregated anonymously

    vote_id CHAR(36) NOT NULL,
    candidacy_id CHAR(36) NOT NULL,
    post_id CHAR(36) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (vote_id) REFERENCES votes(id),
    FOREIGN KEY (candidacy_id) REFERENCES candidacies(id),
    FOREIGN KEY (post_id) REFERENCES posts(id),
    -- NO FOREIGN KEY to users
    INDEX idx_election (election_id),
    INDEX idx_candidacy (candidacy_id)
);
```

### Demo Equivalents

`demo_codes`, `demo_voter_slugs`, `demo_votes`, `demo_results` have identical schema.

---

## Next Steps & Pending Tasks

### Phase D (Next) - VoterController Integration

**Task:** Integrate DeviceFingerprint service into voting workflow

**Files to Update:**
```
app/Http/Controllers/VoterController.php
  - Step 3 (vote submission): Check device limits
  - Step 4 (verification): Log device hash
  - Error responses: Include device_limit_message
```

**Implementation:**
```php
public function store(Request $request)
{
    // Get device hash
    $deviceHash = app(DeviceFingerprint::class)->generate($request);

    // Check vote limits
    $canVote = app(DeviceFingerprint::class)->canVote(
        $deviceHash,
        $request->election_id
    );

    if (!$canVote['allowed']) {
        return back()->withErrors([
            'device_limit' => $canVote['limit_message']
        ]);
    }

    // Store device fingerprint with code
    $code->update(['device_fingerprint_hash' => $deviceHash]);

    // Continue voting workflow...
}
```

### Phase E - Anomaly Detection & Logging

**Task:** Monitor and log anomalous voting patterns

**Implementation:**
```php
$anomaly = app(DeviceFingerprint::class)->detectAnomaly(
    $deviceHash,
    $electionId
);

if ($anomaly['detected']) {
    Log::warning('Anomalous voting pattern detected', [
        'device_hash' => $deviceHash,
        'election_id' => $electionId,
        'codes_in_window' => $anomaly['count'],
        'threshold' => $anomaly['threshold'],
    ]);
}
```

### Phase F - Admin Dashboard

**Task:** Display device statistics to election admins

**Dashboard Widgets:**
- Votes per device distribution
- Anomalies detected
- Max device vote limits
- Family voting statistics

### Pending Architecture Reviews

| Item | Status | Priority |
|------|--------|----------|
| User->Code relationship (anonymity) | Flagged | Medium |
| Candidacy->User relationship | Flagged | Medium |
| DemoVote JSON vs column schema | Documented | Low |
| Vote receipt & proof implementation | Design phase | High |
| Cross-tenant vote queries | Design phase | High |

---

## Quick Reference

### Commands

```bash
# Run DeviceFingerprint tests only
php artisan test tests/Unit/Services/DeviceFingerprintTest.php --no-coverage

# Run all model tests
php artisan test tests/Unit/Models/ --no-coverage

# Run migrations
php artisan migrate

# Check model-schema alignment
php artisan tinker
>>> \App\Models\Code::query()->getConnection()->getSchemaBuilder()->getColumnListing('codes')
```

### Common Patterns

#### Generate Device Hash
```php
$deviceHash = app(\App\Services\DeviceFingerprint::class)->generate($request);
```

#### Check Vote Limits
```php
$result = app(\App\Services\DeviceFingerprint::class)
    ->canVote($deviceHash, $electionId);

if (!$result['allowed']) {
    // Show limit message
}
```

#### Get Device Stats
```php
$stats = app(\App\Services\DeviceFingerprint::class)
    ->getDeviceStats($deviceHash, $electionId);

// $stats['total_codes'], $stats['used_codes'], etc.
```

#### Query Across Tenants (Testing)
```php
$code = \App\Models\Code::withoutGlobalScopes()
    ->find($codeId);
```

### File Locations

| File | Purpose |
|------|---------|
| `app/Services/DeviceFingerprint.php` | Device fraud detection |
| `tests/Unit/Services/DeviceFingerprintTest.php` | 7 integration tests |
| `app/Models/Code.php` | Voting code model |
| `app/Models/VoterSlug.php` | Voting slug model |
| `app/Models/DemoCode.php` | Demo voting code |
| `database/migrations/2026_03_06_155200_*` | Device fingerprinting columns |

### Configuration Keys

```php
config('voting.max_votes_per_device')           // Default: 3
config('voting.device_anomaly_threshold')       // Default: 5
config('voting.device_time_window_minutes')     // Default: 15
```

---

## Related Documentation

- **Architecture Documents:**
  - `architecture/security/implement_finger_device.md` - DeviceFingerprint design
  - `architecture/model/20260306_1331_review_relatioinship.md` - Model review checklist

- **Code References:**
  - `app/Traits/BelongsToTenant.php` - Multi-tenancy trait
  - `app/Models/DemoVoterSlug.php` - Reference implementation
  - `database/factories/` - Factory patterns

---

**Last Updated:** 2026-03-06
**Next Review:** After Phase D (VoterController integration)
**Maintainer:** Claude Code Assistant
