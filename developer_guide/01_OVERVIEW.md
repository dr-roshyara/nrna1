# Demo Election Voting System - Architecture Overview

## Quick Summary

This document describes the **5-step anonymous voting workflow** with persistent step tracking.

## Key Features

- ✅ **Anonymous Voting**: No user_id stored in votes table
- ✅ **5-Step Workflow**: Code verification → Agreement → Vote submission → Verification → Final submission
- ✅ **Persistent Audit Trail**: All steps recorded in voter_slug_steps table with timestamps
- ✅ **Step Progression Control**: Cannot skip steps, middleware enforces order
- ✅ **Election Scoping**: Separate demo_* tables for demo elections
- ✅ **Demo Independence**: Reset demo elections without affecting real elections

## System Flow

```
1. Code Verification        → Step 1 recorded in voter_slug_steps
2. Agreement Acceptance     → Step 2 recorded
3. Vote Selection Submission → Step 3 recorded
4. Vote Verification/Review → Step 4 recorded
5. Final Vote Submission    → Step 5 recorded + Vote saved ANONYMOUSLY
```

## Physical Separation

- **Demo Elections**: demo_candidacies, demo_votes, demo_results (separate tables)
- **Real Elections**: candidacies, votes, results (separate tables)
- **Shared**: voter_slugs, voter_slug_steps, codes, posts, elections

## Core Innovation

**voter_slug_steps Table**: New persistent step tracking system

```sql
CREATE TABLE voter_slug_steps (
    id, voter_slug_id, slug, election_id, 
    step (1-5), step_data (JSON), completed_at
    UNIQUE(voter_slug_id, election_id, step)
);
```

This replaces fragile step calculations with a reliable audit trail.

## Vote Anonymity Enforcement

```php
// Votes table has NO user_id column
$vote = new DemoVote;
$vote->voting_code = hash_bcrypt($code);  // For audit trail, not user ID
$vote->election_id = $election->id;
$vote->save();  // Anonymous vote saved
```

## Key Services

1. **VoterStepTrackingService**: Manages step completion and progression
2. **EnsureVoterStepOrder Middleware**: Validates step order
3. **DemoVotingService**: Handles demo election voting operations

## Files Modified/Created

- `app/Services/VoterStepTrackingService.php` (NEW)
- `app/Models/VoterSlugStep.php` (NEW)
- `database/migrations/*_create_voter_slug_steps_table.php` (NEW)
- `app/Http/Middleware/EnsureVoterStepOrder.php` (UPDATED)
- `app/Http/Controllers/CodeController.php` (UPDATED - added step recording)
- `app/Http/Controllers/VoteController.php` (UPDATED - added step recording)
- `app/Services/DemoVotingService.php` (UPDATED - method visibility)
- `app/Models/VoterSlug.php` (UPDATED - added election_id, steps relationship)

## Testing

```bash
# Verify setup
php artisan tinker
> $election = \App\Models\Election::where('type', 'demo')->first();
> $candidates = \App\Models\DemoCandidate::where('election_id', 1)->count();
> echo "Ready: $candidates candidates";
```

---

**Status**: ✅ Complete and tested
**Last Updated**: 2026-02-04
