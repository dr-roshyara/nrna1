# Voter Slug Troubleshooting Guide

## Common Issues

### Voter gets 403 Invalid voting link

Possible causes:
1. Slug has expired (30-minute voting window closed)
2. Slug is inactive (marked as voted)
3. Cross-user access (different user using slug)
4. Cross-election access (slug from wrong election)

Solutions:
- Request new voting link to get fresh slug
- Check voter_slugs table for status and is_active fields
- Ensure each voter gets unique slug
- Verify election context matches slug

### Unique Constraint Violation on Slug Creation

Cause: Old soft-deleted slugs still count toward UNIQUE(election_id, user_id)

Solution: MUST use forceDelete() not delete()

Correct:
VoterSlug::onlyTrashed()
  ->where('election_id', $id)
  ->where('user_id', $uid)
  ->forceDelete();

Wrong (does not work):
VoterSlug::where('election_id', $id)
  ->where('user_id', $uid)
  ->delete();

### Slug Status is NULL

Cause: Boot hook not setting default status

Solution: Verify VoterSlug::booted() includes:
if (!$slug->status) {
    $slug->status = 'active';
}

### Test Slug Not Found

Cause: BelongsToTenant global scope filtering by organisation_id

Solutions:
1. Set session context in test setUp():
   session(['current_organisation_id' => $organisation->id]);
2. Use withoutGlobalScopes() in test queries:
   VoterSlug::withoutGlobalScopes()->find($id);

### Demo Election Always Creating New Slug

This is expected! Demo elections intentionally always create fresh slugs for unlimited testing.

### Real Election Always Creating New Slug

Cause: Previous slug expired or was marked voted

Solutions:
1. Increase VOTING_SLUG_EXPIRATION_MINUTES if window too short
2. Check if previous slug status='voted' (already voted once)

### Performance Issues

Ensure database has indexes:
- UNIQUE(election_id, user_id)
- INDEX on expires_at (for cleanup)
- INDEX on is_active (for status checks)

Run migrations to create indexes:
php artisan migrate

### Enable Debug Logging

In .env:
LOG_LEVEL=debug

Watch logs for security warnings:
- Cross-user slug access attempted
- Slug ownership validation failed
- Voting link has expired
- Voting link is not active

### Test Failures

If tests fail:

1. Run migrations:
   php artisan migrate --env=testing

2. Set organisation context in setUp():
   $this->organisation = Organisation::factory()->create();
   session(['current_organisation_id' => $this->organisation->id]);

3. Run tests individually:
   php artisan test tests/Unit/Models/VoterSlugExpirationTest.php

### Middleware Not Blocking Invalid Slug

Check:
1. Election is set in request->attributes->get('election')
2. User is authenticated (auth()->user() not null)
3. Middleware runs before route handler
4. BelongsToTenant not filtering slug

Debug by adding:
Log::info('Slug check', [
    'is_active' => $slug->is_active,
    'expired' => $slug->expires_at->isPast(),
    'user_match' => $slug->user_id === auth()->id(),
]);

### Hard Delete Fails with Foreign Key

Cause: Vote records reference the slug

Solution: Delete votes first or use ON DELETE CASCADE

php artisan tinker
Vote::where('voter_slug_id', $slug->id)->delete();
$slug->forceDelete();

### Escalation Procedure

1. Check database state:
   SELECT * FROM voter_slugs WHERE id='slug-uuid';

2. Review logs:
   tail -f storage/logs/laravel.log

3. Run test suite:
   php artisan test tests/Unit/

4. Check migrations:
   php artisan migrate:status

See related documentation:
- README.md: Quick start and overview
- ARCHITECTURE.md: System design details
- SECURITY.md: Validation rules
- TESTING.md: Test patterns
- API.md: Method reference

