# Voter Slug System - Developer Guide

**Last Updated:** 2026-02-22
**Status:** Production Ready (Tested)
**Test Coverage:** 29 Integration Tests Passing

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Core Components](#core-components)
4. [Key Concepts](#key-concepts)
5. [Usage Guide](#usage-guide)
6. [Implementation Details](#implementation-details)
7. [Testing Strategy](#testing-strategy)
8. [Common Issues & Solutions](#common-issues--solutions)
9. [Security Considerations](#security-considerations)

---

## Overview

The **Voter Slug System** is a critical component of the Public Digit voting platform that manages temporary access tokens for the voting process. It creates unique, time-limited slugs that allow users to participate in demo or real elections without being permanently logged in.

### Key Features

- ✅ **Time-Limited Access**: Slugs expire after 30 minutes of inactivity
- ✅ **Organisation Scoping**: Slugs are scoped to specific organisations and elections
- ✅ **Single Active Slug Per User**: Prevents multiple concurrent voting sessions
- ✅ **Demo Election Intelligence**: Automatically selects correct demo election (org-specific or platform-wide)
- ✅ **Step Tracking**: Records voting progress through multiple steps
- ✅ **Audit Logging**: All slug operations are logged for compliance

---

## Architecture

### System Overview

```
User Request
    ↓
DemoCodeController / CodeController
    ↓
VoterSlugService
    ↓
DemoElectionResolver (for demo elections only)
    ↓
VoterSlug Model + Database
```

### Data Flow

```
1. User accesses demo or real election
2. Service generates unique slug (30-min validity)
3. Slug stored with:
   - User ID
   - Election ID
   - Organisation ID
   - Current step (1-N)
   - Step metadata
4. User provided slug in URL: /v/{slug}/vote/create
5. Each subsequent action validates and extends slug
6. After 30 minutes of inactivity, slug expires automatically
```

### Components Interaction

```
┌─────────────────────────────────────────────┐
│  VoterSlugService                           │
│  ┌─────────────────────────────────────┐   │
│  │ generateSlugForUser($user)          │   │
│  │ getOrCreateActiveSlug($user)        │   │
│  │ extendSlugExpiry($slug)             │   │
│  │ validateSlugForUser($slug, $user)   │   │
│  └─────────────────────────────────────┘   │
└──────────────────┬──────────────────────────┘
                   │
         ┌─────────┴──────────┐
         │                    │
    ┌────▼──────────┐   ┌────▼──────────────┐
    │ DemoElection  │   │ VoterSlug Model   │
    │ Resolver      │   │ (Database)        │
    │               │   │                   │
    │ - Priority    │   │ - is_active       │
    │   org-demo    │   │ - expires_at      │
    │ - Fallback    │   │ - current_step    │
    │   platform    │   │ - step_meta       │
    └───────────────┘   └───────────────────┘
```

---

## Core Components

### 1. VoterSlugService

**File**: `app/Services/VoterSlugService.php`

Main service responsible for slug lifecycle management.

#### Key Methods

```php
// Generate new 30-minute slug
generateSlugForUser(User $user, ?int $electionId = null): VoterSlug

// Get existing or create new slug (with expiry extension)
getOrCreateActiveSlug(User $user): VoterSlug

// Retrieve active slug for user
getActiveSlugForUser(User $user): ?VoterSlug

// Validate slug belongs to user and is active
validateSlugForUser(string $slug, User $user): ?VoterSlug

// Extend slug validity by 30 minutes (sliding window)
extendSlugExpiry(VoterSlug $slug): bool

// Revoke specific slug
revokeSlug(VoterSlug $slug): bool

// Revoke all active slugs for user
revokeAllSlugsForUser(User $user): int

// Cleanup expired slugs (scheduled job)
cleanupExpiredSlugs(): int

// Build voting link URL
buildVotingLink(VoterSlug $slug, string $routeName = 'slug.code.create'): string
```

### 2. DemoElectionResolver

**File**: `app/Services/DemoElectionResolver.php`

Intelligent election selection service using priority-based logic.

#### Key Methods

```php
// Get correct demo election for user
getDemoElectionForUser(User $user): ?Election
// Priority 1: Organisation-specific demo
// Priority 2: Platform-wide demo (organisation_id = null)

// Validate election appropriateness for user
isElectionValidForUser(User $user, Election $election): bool
```

### 3. VoterSlug Model

**File**: `app/Models/VoterSlug.php`

Eloquent model representing a voter slug in the database.

#### Schema

```
Table: voter_slugs
├── id (PK)
├── user_id (FK) → users
├── slug (UNIQUE, VARCHAR) → URL token
├── election_id (FK) → elections
├── organisation_id (FK) → organisations  // CRITICAL: Scopes slug to org
├── expires_at (TIMESTAMP)
├── is_active (BOOLEAN)
├── current_step (INTEGER, default=1)
├── step_meta (JSON) → step-specific metadata
├── created_at (TIMESTAMP)
├── updated_at (TIMESTAMP)
└── Global Scope: BelongsToTenant (filters by organisation_id)
```

#### Important Scopes

```php
// Scope: Valid active slug (active + not expired)
scopeValid($query)
→ where('is_active', true)->where('expires_at', '>', now())

// Scope: Filter by user
scopeForUser($query, $userId)
→ where('user_id', $userId)

// Methods
isExpired(): bool → checks if expires_at is past
isValid(): bool → checks is_active && !isExpired()
```

---

## Key Concepts

### 1. Slug Generation

A slug is a unique, URL-safe token combining timestamp and random bytes:

```
Format: {base36-timestamp}_{base64-random}
Example: 12abc34_x5y6z7w8...

Generation Process:
1. Timestamp-based prefix ensures natural ordering
2. Random suffix provides cryptographic security
3. Uniqueness checked against database before saving
```

### 2. Election Selection (Demo Only)

When generating slug for demo voting, the system uses priority-based selection:

```
Priority 1 (Org-Specific Demo):
├─ User has organisation_id
└─ Demo election exists with matching organisation_id
   └─ ✅ USE THIS

Priority 2 (Platform Demo):
├─ Demo election exists with organisation_id = NULL
└─ ✅ USE THIS

Priority 3 (No Demo):
└─ ❌ THROW EXCEPTION
```

**Why This Matters**:
- Each organisation can have isolated demo data
- Users from same organisation get consistent demo experience
- Platform-wide demo serves as fallback
- Prevents cross-organisation data leakage

### 3. Validity Window (Sliding)

Slugs have a 30-minute validity window that extends on each action:

```
Initial Creation:
├─ slug created at 12:00 PM
└─ expires_at = 12:30 PM

User navigates to vote form (12:15 PM):
├─ getOrCreateActiveSlug() called
├─ Still active (12:15 < 12:30)
└─ extends expiry → expires_at = 12:45 PM

User submits agreement (12:40 PM):
├─ Still valid (12:40 < 12:45)
└─ Extends again → expires_at = 1:10 PM

User inactive from 12:45-1:15 PM:
├─ 1:15 PM arrives
└─ Slug auto-expires (cleanup job removes at next run)
```

### 4. Organisation Scoping (CRITICAL)

The `BelongsToTenant` trait on `VoterSlug` model adds a global scope that filters all queries by `organisation_id`. This ensures:

- ✅ Users can only access their organisation's slugs
- ✅ Cross-organisation access prevented at query level
- ✅ Multi-tenancy is secure by default

**Important**: When querying in tests or special cases, use `withoutGlobalScopes()`:

```php
// Filtered by current organisation context
VoterSlug::where('user_id', $userId)->first();

// Unrestricted query (use carefully!)
VoterSlug::withoutGlobalScopes()->where('user_id', $userId)->first();
```

---

## Usage Guide

### Basic Flow: Demo Election Voting

```php
// Controller receives request
$user = auth()->user(); // User from organisation_id=5

// Generate slug for voting
$slug = VoterSlugService->generateSlugForUser($user);
// ✅ Automatically selects org-specific demo if exists
// Falls back to platform demo
// Saves: election_id=25, organisation_id=5

// Send user to voting page with slug
redirect(route('slug.vote.create', ['vslug' => $slug->slug]));

// Later: User returns to continue voting
$slug = VoterSlugService->getOrCreateActiveSlug($user);
// ✅ Returns existing slug if still valid
// ✅ Extends expiry by 30 minutes
// Creates new one if expired

// Validate slug before proceeding
$validated = VoterSlugService->validateSlugForUser($slug->slug, $user);
if (!$validated) {
    abort(403, 'Voting session expired');
}
```

### Real Election Voting

Real elections use explicit election IDs provided by the user, not DemoElectionResolver:

```php
// User selected specific election from list
$slug = VoterSlugService->generateSlugForUser($user, electionId: 42);
// ✅ Uses provided election_id directly
// Copies election's organisation_id to slug
```

### Step Tracking

Track voting progress through multiple steps:

```php
// Step 1: User enters slug, sees agreement
$slug->update([
    'current_step' => 2,
    'step_meta' => ['form' => 'agreement']
]);

// Step 2: User reviews candidates
$slug->update([
    'current_step' => 3,
    'step_meta' => ['candidates_viewed' => true]
]);

// Step 3: User casts votes
$slug->update([
    'current_step' => 4,
    'step_meta' => ['votes_recorded' => true, 'vote_count' => 3]
]);

// Step 4: Confirmation
// Reset for next user (or keep for audit)
```

### Extending Validity

Extend slug by 30 minutes before it expires:

```php
// Anywhere in voting flow
$slug = VoterSlugService->getActiveSlugForUser($user);
if ($slug && $slug->expires_at->diffInMinutes(now()) < 5) {
    // Less than 5 minutes remaining
    VoterSlugService->extendSlugExpiry($slug);
}
```

---

## Implementation Details

### Slug Uniqueness Handling

The service ensures no collision by checking database:

```php
do {
    $slug = $this->generateRandomSlug();
} while (VoterSlug::where('slug', $slug)->exists());
// Extremely unlikely collision (256-bit entropy)
```

### Transaction Safety

Critical operations use transactions:

```php
DB::transaction(function () {
    // 1. Revoke previous active slugs
    VoterSlug::where('user_id', $user->id)
        ->where('is_active', true)
        ->update(['is_active' => false]);

    // 2. Generate new slug
    $slug = VoterSlug::create([...]);

    // 3. Return new slug
    return $slug;
});
```

### Election Context Preservation

**CRITICAL BUG FIX**: Voter slug must save `organisation_id` from election:

```php
// ❌ WRONG: election_id=25, organisation_id=NULL
// Later queries fail because context is lost

// ✅ CORRECT: election_id=25, organisation_id=5
// Can retrieve election's organisation later if needed

$election = Election::withoutGlobalScopes()->find($electionId);
$voterSlug = VoterSlug::create([
    'election_id' => $electionId,
    'organisation_id' => $election->organisation_id,  // CRITICAL
]);
```

### Dependency Injection

Service is registered as singleton for consistent instance:

```php
// app/Providers/AppServiceProvider.php
$this->app->singleton(VoterSlugService::class, function () {
    return new VoterSlugService(
        new DemoElectionResolver()
    );
});

// In controllers
public function __construct(VoterSlugService $service) {
    $this->service = $service;
}
```

---

## Testing Strategy

### Test Files

- **`tests/Unit/Services/DemoElectionResolverTest.php`** (14 tests)
  - Tests election selection logic
  - Tests validation rules

- **`tests/Feature/Services/VoterSlugServiceTest.php`** (29 tests)
  - Integration tests with database
  - Tests slug generation, validation, lifecycle

### Running Tests

```bash
# Run all slug-related tests
php artisan test tests/Feature/Services/VoterSlugServiceTest.php

# Run with verbose output
php artisan test tests/Feature/Services/VoterSlugServiceTest.php --verbose

# Run single test
php artisan test tests/Feature/Services/VoterSlugServiceTest.php --filter "generates_slug_with_correct_org_specific_election"
```

### Test Coverage

#### Election Selection Tests (7)
- ✅ Org-specific demo priority
- ✅ Platform demo fallback
- ✅ Null returns
- ✅ Non-demo elections ignored
- ✅ Priority sorting
- ✅ Multi-org isolation

#### Slug Lifecycle Tests (10)
- ✅ Slug generation uniqueness
- ✅ Slug retrieval and validation
- ✅ Slug revocation
- ✅ Slug expiry handling
- ✅ Get-or-create logic

#### Critical Bug Fix Tests (3)
- ✅ **CRITICAL**: Correct election_id is saved
- ✅ **CRITICAL**: organisation_id is saved from election
- ✅ **CRITICAL**: Multi-org isolation

---

## Common Issues & Solutions

### Issue 1: "No demo election available"

**Symptom**: Exception thrown when generating slug for demo voting.

**Root Cause**: No demo election exists for user's organisation or platform.

**Solution**:
```bash
# Create demo election for organisation
php artisan demo:setup --org=5

# Or create platform demo
php artisan demo:setup
```

### Issue 2: Slug Not Found / Expired

**Symptom**: 403 "Voting session expired" on valid slug.

**Root Cause**:
- Slug expired (30 minutes elapsed)
- Global scope filtering by wrong organisation context
- Slug was revoked

**Solution**:
```php
// Check slug validity
if (!$slug->isValid()) {
    // Request new slug
}

// Ensure organisation context matches
dd($slug->organisation_id, auth()->user()->organisation_id);
```

### Issue 3: "Election Not Found"

**Symptom**: Exception in `generateSlugForUser()`.

**Root Cause**: Election ID provided doesn't exist.

**Solution**:
```php
// Verify election exists
$election = Election::withoutGlobalScopes()->find($electionId);
if (!$election) {
    abort(404, 'Election not found');
}
```

### Issue 4: Cross-Organisation Access

**Symptom**: User A can vote on User B's organisation demo.

**Root Cause**: Missing `organisation_id` check or slug not properly scoped.

**Solution**: Ensure all queries use `BelongsToTenant` scope (default).

---

## Security Considerations

### 1. URL Token Security

- Slugs are cryptographically random (256-bit entropy)
- Not predictable/sequential
- Stored in database (not derived on-the-fly)
- Use HTTPS only (prevent MITM attacks)

### 2. Time-Bound Access

- 30-minute validity window (not configurable per user)
- Automatic expiry prevents unauthorized late access
- Sliding window extends based on activity (not absolute time)

### 3. Organisation Isolation

- VoterSlug uses `BelongsToTenant` global scope
- All queries automatically scoped to current organisation
- Cross-organisation queries require explicit `withoutGlobalScopes()`

### 4. Single Active Slug Per User

- Only one active slug per user at a time
- Generating new slug revokes previous one
- Prevents multiple concurrent voting sessions

### 5. Audit Logging

All slug operations are logged:
```php
Log::info('🔑 Creating voter slug', [
    'user_id' => $user->id,
    'election_id' => $electionId,
    'organisation_id' => $election->organisation_id,
]);
```

### 6. Data Isolation

- Election ID stored with slug ensures correct context
- Organisation ID stored for audit and recovery
- Cannot vote on wrong election even with valid slug

---

## Future Enhancements

### Potential Improvements

1. **Configurable Validity Window**
   - Allow per-organisation timeout settings
   - Shorter window for sensitive elections

2. **Rate Limiting**
   - Limit slug generation frequency per user
   - Prevent brute-force slug enumeration

3. **Slug Metadata**
   - Store IP address for fraud detection
   - Track device fingerprint
   - Log browser/mobile distinction

4. **QR Code Support**
   - Generate QR codes for mobile voting
   - Deep-link integration with Android/iOS apps

5. **Slug Analytics**
   - Track slug usage patterns
   - Identify stalled voting sessions
   - Monitor step drop-off rates

---

## References

- **Main Service**: `app/Services/VoterSlugService.php`
- **Election Resolver**: `app/Services/DemoElectionResolver.php`
- **Model**: `app/Models/VoterSlug.php`
- **Tests**: `tests/Feature/Services/VoterSlugServiceTest.php`
- **Tests**: `tests/Unit/Services/DemoElectionResolverTest.php`
- **Related Controllers**:
  - `app/Http/Controllers/Demo/DemoCodeController.php`
  - `app/Http/Controllers/Demo/DemoVoteController.php`

---

## Support

For questions or issues:
1. Check existing tests for usage examples
2. Review logging output (`voting_audit` channel)
3. Consult DemoElectionResolver for election selection logic
4. Check VoterSlug model for database schema

**Last Updated**: 2026-02-22
**Test Status**: 29/29 Passing ✅
