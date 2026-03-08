# Voter Slug Security Model

## Security Guarantees

The voter slug system provides 4 critical security guarantees.

---

## 1. Vote Anonymity

### The Problem
Votes must not be linkable to voters. If you can trace a vote to a person, they can be coerced.

### The Solution
Vote hash uses code ID, not user ID.

CORRECT - Uses code ID (preserves anonymity)
$hash = hash('sha256',
    $code->id .                    // Code UUID
    $election->id .
    $code->code_to_open_voting_form .
    $vote->cast_at->timestamp
);

WRONG - Uses user ID (breaks anonymity)
$hash = hash('sha256',
    $code->user_id .               // Links voter to vote
    ...
);

### Why This Matters

1. Voter Slugs track voter progress (needed for access control)
2. Votes must not track voter (needed for anonymity)

By using code->id instead of user->id:
- Voter anonymity is preserved
- Vote verification still works
- Voter identity is separated from vote content
- Vote coercion becomes impossible

---

## 2. Cross-Election Prevention

### The Problem
A voting code from one election might be used in another election.

### The Solution
Slug ownership validation checks election ID.

Implementation: validateSlugOwnership() checks election_id match

### How It Works

1. Middleware extracts election from route context
2. Service compares slug.election_id with request election
3. If mismatch: throw AccessDeniedHttpException (403)
4. If match: proceed with voting

---

## 3. Cross-User Prevention

### The Problem
User A might try to use User B's voting code.

### The Solution
Middleware validates authenticated user matches slug owner.

Implementation: Check auth()->id() === slug.user_id

### How It Works

1. User authenticates (Laravel Sanctum)
2. Middleware checks auth()->id() === slug.user_id
3. If mismatch: 403 Forbidden
4. If match: proceed to voting

---

## 4. Expiration Protection

### The Problem
Stale voting sessions could be used after expiration.

### The Solution
4-layer expiration defense:

Layer 1: Model Boot - Auto-mark Expired
When slug is loaded from database, if expired: mark is_active=false

Layer 2: Middleware - Block Expired
Middleware checks expiration before routing to voting pages

Layer 3: Service - Fresh Slug on Return
Service automatically creates fresh slug if old one expired

Layer 4: Background Job - Cleanup
Daily job removes expired slug records from database

---

## Unique Constraint Security

### The Design

Both voter_slugs and demo_voter_slugs have:
UNIQUE(election_id, user_id)

This prevents:
- Multiple voting sessions per voter per election
- Vote multiplication attacks
- Concurrent voting in same election

### Hard Delete Requirement

When creating a new slug, old soft-deleted slugs MUST be hard-deleted:

CORRECT:
VoterSlug::onlyTrashed()
    ->where('election_id', $election->id)
    ->where('user_id', $user->id)
    ->forceDelete();  // Hard delete!

WRONG:
VoterSlug::where('election_id', $election->id)
    ->where('user_id', $user->id)
    ->delete();  // Soft delete (fails unique constraint)

Why: Soft-deleted records still count toward UNIQUE constraint.

---

## Tenant Isolation

### The Design

All slugs are scoped to organisation via organisation_id column.

Model uses BelongsToTenant trait which:
1. Auto-adds WHERE organisation_id = ? to all queries
2. Prevents cross-tenant data access
3. Requires withoutGlobalScopes() for schema operations

### Prevents

- Cross-tenant data access
- One organisation reading another's voting data
- Vote leakage across tenants
- Session hijacking across organisations

---

## Validation Flow (Security Pyramid)

Top level: Database Integrity (unique constraints, foreign keys)

Middle levels: Middleware (is_active check) and Model Boot (auto-expire)

Lower levels: Service (ownership) and Background Job (cleanup)

Each layer validates independently.

---

## Common Attack Vectors (Prevented)

### Attack 1: Code Reuse
Attempt: Use same code in multiple elections
Defense: validateSlugOwnership checks election_id
Result: 403 Forbidden

### Attack 2: Vote Multiplication
Attempt: Vote twice with same code
Defense: UNIQUE(election_id, user_id) + status tracking
Result: Second vote rejected

### Attack 3: Vote Coercion
Attempt: Prove user voted for candidate X
Defense: Vote hash uses code->id, not user->id
Result: Cannot prove voter identity for any vote

### Attack 4: Session Hijacking
Attempt: Use another user's code
Defense: validateSlugOwnership checks user_id
Result: 403 Forbidden

### Attack 5: Expired Session Reuse
Attempt: Use expired voting code
Defense: 4-layer expiration defense
Result: 403 Session Expired

---

## Security Checklist

- Vote hash uses code ID, not user ID
- Slug ownership validated (election + user)
- Cross-election voting prevented
- Cross-user voting prevented
- Expired sessions blocked
- Unique constraint prevents vote multiplication
- Soft delete preserves audit trail
- Hard delete before creating new slug
- Tenant isolation enforced
- Security events logged
- Test coverage verifies all validations

