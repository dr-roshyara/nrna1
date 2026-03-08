# Voter Slug System - Developer Guide

## Overview

The Voter Slug System manages secure, expiring voting sessions for the Public Digit election platform. A "voter slug" is a unique, time-limited token that authorizes a voter to participate in an election.

**Key characteristics:**
- Time-limited: Slugs expire after 30 minutes (configurable)
- Single-use path: Each slug tracks voter progression through 5 voting steps
- Anonymous: Vote hash uses code ID, not user ID (preserves anonymity)
- Multi-layer validated: Model -> Service -> Middleware -> Background cleanup
- Multi-tenancy aware: Completely scoped to organisation and election

## Quick Start

### Creating a Voter Slug

\`\`\`php
use App\Services\VoterSlugService;
use App\Models\User;
use App\Models\Election;

\$service = app(VoterSlugService::class);
\$user = User::find(\$userId);
\$election = Election::find(\$electionId);

// Get or create slug
\$slug = \$service->getOrCreateSlug(\$user, \$election, \$forceNew = false);
echo \$slug->slug;
\`\`\`

### Validating a Slug

\`\`\`php
\$slugString = \$request->route('vslug');
\$slug = \$service->getValidatedSlug(\$slugString, \$user, \$election);

if (\$slug) {
    // Slug is valid, unexpired, and belongs to this user/election
    \$request->attributes->set('voter_slug', \$slug);
}
\`\`\`

### Checking Slug Status

\`\`\`php
\$slug->is_active;        // true if not expired
\$slug->expires_at;       // Carbon instance
\$slug->current_step;     // 1-5
\$slug->status;           // 'active', 'voted', 'expired', 'abstained'

\$slug->isExpired();      // true if expires_at is past
\$slug->isValid();        // is_active && !isExpired()
\`\`\`

## Architecture

### 4-Layer Validation Strategy

Layer 1: MIDDLEWARE (EnsureVoterSlugWindow)
  - Block expired/inactive slugs
  - Validate slug ownership
  - Set request attributes

Layer 2: SERVICE (VoterSlugService)
  - Smart slug reuse vs creation
  - Ownership validation
  - Hard-delete old slugs

Layer 3: MODEL BOOT (VoterSlug::booted)
  - Auto-mark expired slugs
  - Auto-set defaults
  - Prevent stale sessions

Layer 4: BACKGROUND (voting:clean-expired-slugs)
  - Remove old soft-deleted slugs
  - Remove old demo slugs
  - Run daily

## Key Concepts

### VoterSlug vs DemoVoterSlug

| Aspect | VoterSlug | DemoVoterSlug |
|--------|-----------|---------------|
| Table | voter_slugs | demo_voter_slugs |
| Soft Delete | Yes | No |
| Election Type | Real | Demo |
| Slug Reuse | Yes (if active) | No (always fresh) |

### Unique Constraint

The UNIQUE(election_id, user_id) constraint prevents multiple voting sessions per voter per election.

**Critical:** When creating a new slug, hard-delete old soft-deleted slugs:

\`\`\`php
// Correct approach
VoterSlug::onlyTrashed()
    ->where('election_id', \$election->id)
    ->where('user_id', \$user->id)
    ->forceDelete();  // Hard delete!
\`\`\`

## Common Usage Patterns

### Pattern 1: Starting a Voting Session

\`\`\`php
public function startVoting(Request \$request)
{
    \$user = auth()->user();
    \$election = \$request->attributes->get('election');
    \$service = app(VoterSlugService::class);

    \$slug = \$service->getOrCreateSlug(\$user, \$election);
    return redirect()->route('voting.select-candidates', ['vslug' => \$slug->slug]);
}
\`\`\`

### Pattern 2: Handling Expired Sessions

\`\`\`php
if (\$slug && \$slug->isExpired()) {
    abort(403, 'Voting link has expired. Please request a new voting link.');
}
\`\`\`

### Pattern 3: Demo Elections Reset

\`\`\`bash
php artisan db:seed --class=DemoVoterSlugSeeder
# or
php artisan tinker
> DemoVoterSlug::truncate()
\`\`\`

## Security Guarantees

1. Vote Anonymity: Vote hash uses code ID (not user ID)
2. Cross-Election Prevention: Slug ownership validation
3. Cross-User Prevention: Middleware validates slug owner
4. Expiration Protection: Multi-layer defense against stale sessions

## File Structure

developer_guide/election_engine/voter_slug/
  - README.md (this file)
  - ARCHITECTURE.md (detailed system design)
  - API.md (VoterSlugService API reference)
  - SECURITY.md (security guarantees)
  - TESTING.md (test patterns)
  - TROUBLESHOOTING.md (common issues)
  - SCHEDULING.md (background job setup)

## Next Steps

See the other documentation files for detailed information:
- ARCHITECTURE.md - System design and database schema
- API.md - Complete method reference
- SECURITY.md - Validation rules and guarantees
- TESTING.md - Test patterns and examples
- TROUBLESHOOTING.md - Common issues and solutions
- SCHEDULING.md - Background job configuration
● Perfect! I've successfully created the comprehensive developer guide for the Voter Slug System. Here's what was generated:

  📚 Developer Guide Created

  All documentation files are now in place at:
  C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\developer_guide\election_engine\voter_slug\

  Files Created:

  1. README.md (4.5 KB)
    - Overview of voter slug system
    - Quick start examples (creating, validating, checking status)
    - 4-layer validation strategy architecture diagram
    - Key concepts and slug lifecycle
    - File structure and next steps
  2. ARCHITECTURE.md (6.2 KB)
    - Complete database schema for voter_slugs and demo_voter_slugs
    - Composite unique constraint explanation
    - Why soft delete vs no soft delete
    - VoterSlugService architecture and methods
    - Model boot hooks (retrieved, creating)
    - Middleware request flow diagram
    - Background job design
    - Tenant isolation implementation
    - Transaction safety and vote anonymity design
  3. API.md (5.2 KB)
    - Complete API reference for VoterSlugService
    - getOrCreateSlug() - Get or create slug
    - getValidatedSlug() - Retrieve and validate
    - validateSlugOwnership() - Strict validation
    - createNewSlug() - Create with cleanup
    - cleanupExpiredSlugs() - Helper method
    - Related model methods
    - Usage patterns (voting session, middleware, service)
    - Error handling examples
    - Database queries and configuration
  4. SECURITY.md (5.0 KB)
    - 4 critical security guarantees
    - Vote anonymity (code ID vs user ID)
    - Cross-election prevention
    - Cross-user prevention
    - Expiration protection (4-layer defense)
    - Unique constraint security
    - Tenant isolation enforcement
    - Attack vectors and prevention
    - Logging and monitoring
    - Security checklist
  5. TESTING.md (4.6 KB)
    - VoterSlugExpirationTest (10 tests)
    - EnsureVoterSlugWindowTest (6 tests)
    - Detailed test descriptions
    - Test setup pattern with tenant context
    - Running tests commands
    - Test coverage details
    - Common test issues and solutions
  6. TROUBLESHOOTING.md (3.6 KB)
    - Common issues and solutions
    - 403 Invalid voting link causes and fixes
    - Unique constraint violation handling
    - Slug status NULL issue
    - Test slug not found problem
    - Demo vs real election behavior
    - Performance optimization
    - Debug logging configuration
    - Escalation procedure
  7. SCHEDULING.md (6.6 KB)
    - Background job: voting:clean-expired-slugs
    - Command signature and parameters
    - Usage examples
    - Laravel scheduler configuration
    - What gets cleaned (real vs demo)
    - Monitoring cleanup jobs
    - Troubleshooting scheduler
    - Performance considerations
    - Production deployment steps
    - Configuration options
    - Monitoring dashboard metrics

  ---
  📋 Documentation Highlights

  Quick Start: New developers can start with README.md and understand the system in minutes.

  Architecture Deep Dive: ARCHITECTURE.md provides complete system design including database schema, service methods, and validation layers.

  API Reference: API.md documents every public method with parameters, returns, examples, and error handling.

  Security Model: SECURITY.md explains the 4 critical guarantees and how each attack vector is prevented.

  Testing: TESTING.md covers all 16 test cases with clear descriptions of what each tests.

  Troubleshooting: TROUBLESHOOTING.md has solutions for common problems developers will encounter.

  Operations: SCHEDULING.md explains how to deploy and monitor the cleanup job in production.

  ---
  The documentation is now ready for developers to onboard onto the voter slug system! 🎉
