# Voter Slug Architecture

## System Design

### Database Schema

#### voter_slugs table (Real Elections)

Column Name | Type | Constraints | Purpose
---|---|---|---
id | UUID | PRIMARY KEY | Unique identifier
election_id | UUID | FOREIGN KEY | Links to election
user_id | UUID | FOREIGN KEY | Links to voter
organisation_id | UUID | FOREIGN KEY | Tenant isolation
slug | VARCHAR(64) | UNIQUE | Public voting link
expires_at | TIMESTAMP | NOT NULL | When voting session expires
is_active | BOOLEAN | DEFAULT true | Active status
current_step | INT | DEFAULT 1 | Voting step (1-5)
step_meta | JSON | NULLABLE | Metadata per step
status | ENUM | DEFAULT active | Status enum
created_at | TIMESTAMP | DEFAULT NOW() | Creation timestamp
updated_at | TIMESTAMP | DEFAULT NOW() | Update timestamp
deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp

#### demo_voter_slugs table (Demo Elections)

Same schema as voter_slugs EXCEPT:
- deleted_at column does NOT exist (no soft delete)
- Used only for demo elections

### Composite Unique Constraint

Both tables have: UNIQUE(election_id, user_id)

This ensures one active voting session per voter per election.

### Why Soft Delete on voter_slugs?

Soft deletes preserve audit trails for real elections. When a voter completes voting:
1. Slug is marked deleted_at = NOW()
2. Slug remains in database for audit
3. Old soft-deleted slugs are removed via background job

### Why No Soft Delete on demo_voter_slugs?

Demo slugs are test data. Truncating or deleting demo slugs is safe.

---

## VoterSlugService Architecture

### Core Responsibility

The VoterSlugService orchestrates the entire voter slug lifecycle:
1. Creating new slugs
2. Reusing active non-expired slugs
3. Validating slug ownership
4. Handling expiration

### Key Methods

#### getOrCreateSlug(user, election, forceNew = false)

Purpose: Get existing valid slug or create new one

Logic:
- If forceNew = true: create new slug (demo elections)
- If forceNew = false: reuse active non-expired slug (real elections)

Returns: VoterSlug or DemoVoterSlug instance

#### getValidatedSlug(slugString, user, election)

Purpose: Retrieve and validate slug in one operation

Validation checks:
1. Slug exists in database
2. Slug belongs to this user
3. Slug belongs to this election
4. Slug is not expired
5. Slug is active

Returns: VoterSlug if valid, null if invalid

#### validateSlugOwnership(slug, user, election)

Purpose: Strict ownership validation

Throws AccessDeniedHttpException if:
- Slug user_id != user.id
- Slug election_id != election.id

Used by: EnsureVoterSlugWindow middleware

#### createNewSlug(user, election, model)

Purpose: Create new slug after hard-deleting old ones

Steps:
1. Find all soft-deleted slugs for this user/election
2. Hard-delete them (critical!)
3. Create new slug with auto-set defaults
4. Return new slug

Why hard-delete? Soft-deleted records still count toward UNIQUE constraint.

---

## Model Boot Hooks

### VoterSlug::booted() and DemoVoterSlug::booted()

Two critical hooks prevent stale slugs from blocking new votes:

#### Hook 1: retrieved() - Auto-mark expired slugs

Triggered: When model is retrieved from database

Logic:
- If expires_at < now AND is_active = true:
  - UPDATE database: is_active = false, status = expired
  - UPDATE in-memory instance
- Log the auto-marking

Why: Ensures expired slugs are marked inactive on retrieval.

#### Hook 2: creating() - Auto-set defaults

Triggered: Before slug is saved to database

Logic:
- If expires_at not set: expires_at = now + 30 minutes
- If status not set: status = active
- If is_active not set: is_active = true
- If can_vote_now not set: can_vote_now = true

Why: Ensures slugs have sensible defaults.

---

## Middleware: EnsureVoterSlugWindow

### Request Flow

1. HTTP Request arrives with vslug parameter
2. Middleware extracts vslug from route parameter
3. Query database to get VoterSlug object
4. Boot hook retrieved() fires: auto-mark if expired
5. Validate is VoterSlug instance (not string)
6. Validate slug is active
7. Validate slug is not expired
8. Ownership validation via VoterSlugService
9. Set request attributes (voter, voter_slug)
10. Touch last_accessed timestamp
11. Proceed to controller

### Security Checks

1. Instance validation: Is it really a VoterSlug object?
2. Status validation: Is slug marked active?
3. Expiration validation: Has it expired?
4. Ownership validation: Does it belong to authenticated user?
5. Election validation: Does it belong to requested election?

---

## Background Job: voting:clean-expired-slugs

### Purpose

Remove old slug records to maintain database hygiene.

### Signature

php artisan voting:clean-expired-slugs {--hours=24} {--detailed}

### Parameters

- --hours=24 (default): Slugs older than N hours are removed
- --detailed (optional): Show detailed removal information

### Logic

For real elections (voter_slugs):
1. Find all soft-deleted slugs where expires_at < (now - 24 hours)
2. Permanently delete them (forceDelete)
3. Log number deleted

For demo elections (demo_voter_slugs):
1. Find all slugs where expires_at < (now - 24 hours)
2. Delete them
3. Log number deleted

---

## Tenant Isolation

All slugs are scoped to organisation via organisation_id column.

BelongsToTenant trait:
1. Automatically adds WHERE organisation_id = ? to all queries
2. Prevents cross-tenant queries
3. Requires withoutGlobalScopes() for schema operations

Test setup must set organisation context:

protected function setUp(): void
{
    \$this->organisation = Organisation::factory()->create([type => tenant]);
    session([current_organisation_id => \$this->organisation->id]);
}

---

## Vote Anonymity Design

### Critical: Code ID, Not User ID

Vote hash uses code->id:

CORRECT:
\$hash = hash(sha256,
    \$code->id .               // Code UUID (no voter linkage!)
    \$election->id .
    \$code->code_to_open_voting_form .
    \$vote->cast_at->timestamp
);

WRONG:
\$hash = hash(sha256,
    \$code->user_id .          // Links voter to vote!
    ...
);

### Why This Matters

Voter slugs link voter to voting session (needed for access control).
Votes must NOT link voter to selections (needed for anonymity).

By using code->id instead of user->id, we preserve voter anonymity.


