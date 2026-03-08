# Voter Slug Testing Guide

## Test Files

Two comprehensive test files cover the voter slug system:

1. tests/Unit/Models/VoterSlugExpirationTest.php (10 tests)
2. tests/Unit/EnsureVoterSlugWindowTest.php (6 tests)

Total: 16 tests, all passing.

---

## VoterSlugExpirationTest (10 tests)

Tests the model-level expiration logic and slug lifecycle.

### Test 1: Expired slug marked inactive on retrieval

When slug is in database with past expiration time:
- Boot hook auto-marks is_active = false
- status is set to 'expired'

### Test 2: Active non-expired slug remains active

When slug with future expiration time is retrieved:
- is_active remains true
- No changes made

### Test 3: Demo election slug always new on retrieval

For demo elections:
- Each getOrCreateSlug() call creates new slug
- Previous slug is not reused

### Test 4: Real election reuses active non-expired slug

For real elections:
- Active non-expired slugs are reused
- Only creates new slug if old is expired or missing

### Test 5: Real election creates new slug when existing expired

When existing slug has expired:
- Old slug is hard-deleted
- Fresh slug is created
- New slug has future expiration time

### Test 6: Slug automatically sets expiration on creation

When creating slug without explicit expiration:
- Boot hook sets expires_at = now + 30 minutes
- Configurable via voting.slug_expiration_minutes

### Test 7: Slug must belong to correct election

When validating slug for wrong election:
- getValidatedSlug() returns null
- Slug is rejected

### Test 8: Slug must belong to correct user

When validating slug for wrong user:
- getValidatedSlug() returns null
- Slug is rejected

### Test 9: Service rejects wrong user/election combo

When calling validateSlugOwnership() with wrong user or election:
- Throws AccessDeniedHttpException
- Cannot bypass validation

### Test 10: Service validates slug ownership on retrieval

getValidatedSlug() performs all checks in one call:
- Slug exists
- Slug belongs to user
- Slug belongs to election
- Slug is not expired
- Slug is active

---

## EnsureVoterSlugWindowTest (6 tests)

Tests the middleware-level validation.

### Test 1: Valid slug passes through middleware

With valid slug:
- Request proceeds to controller
- voter_slug attribute is set
- middleware returns OK

### Test 2: Expired slug is blocked

With expired slug:
- Middleware returns 403 Forbidden
- Request does not reach controller

### Test 3: Inactive slug is blocked

With is_active = false:
- Middleware returns 403 Forbidden
- Deactivated slugs cannot access voting

### Test 4: Slug from different user is blocked

When authenticated user does not own slug:
- Throws AccessDeniedHttpException (403)
- Cross-user voting prevented

### Test 5: Slug from different election is blocked

When slug belongs to different election:
- Throws AccessDeniedHttpException (403)
- Cross-election voting prevented

### Test 6: Non-existent slug is blocked

When slug does not exist in database:
- Middleware returns 403 Forbidden
- Invalid slug strings rejected

---

## Test Setup Pattern

All tests use proper tenant context:

```php
protected function setUp(): void
{
    parent::setUp();
    $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
    session(['current_organisation_id' => $this->organisation->id]);
    $this->user = User::factory()->create();
    $this->election = Election::factory()->create([
        'type' => 'real',
        'organisation_id' => $this->organisation->id,
    ]);
}
```

---

## Running Tests

Run all voter slug tests:
```
php artisan test tests/Unit/Models/VoterSlugExpirationTest.php
php artisan test tests/Unit/EnsureVoterSlugWindowTest.php
```

Run with output:
```
php artisan test --verbose
```

Run single test:
```
php artisan test tests/Unit/Models/VoterSlugExpirationTest.php
```

---

## Test Coverage

All critical paths are tested:

- Model boot hooks (expiration, defaults)
- Service creation and reuse logic
- Ownership validation
- Middleware access control
- Database unique constraint
- Tenant isolation
- Soft delete behavior

---

## Common Issues

Issue: BelongsToTenant scope filters out test slugs
Solution: Set session(['current_organisation_id' => id]) in setUp()

Issue: Slug not found when querying
Solution: Use withoutGlobalScopes() or set session context

Issue: Hard delete fails with foreign key
Solution: Delete in order or use transactions
