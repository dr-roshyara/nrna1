# Phase 6: Quick API Reference & Troubleshooting

---

## User Model API

### Membership Checks

```php
$user->belongsToOrganisation($orgId)           // → bool
$user->hasTenantOrganisation()                 // → bool
$user->getRoleInOrganisation($orgId)           // → string|null
```

### Get Organisations

```php
$user->organisations()                         // → Collection[Org]
$user->currentOrganisation()                   // → Org (by organisation_id)
$user->getOwnedOrganisation()                  // → Org|null (where role='owner')
```

### Actions

```php
$user->switchToOrganisation($org)              // → void (updates organisation_id)
$user->organisations()->attach($orgId, ['role' => 'member'])  // Add to org
$user->organisations()->detach($orgId)         // Remove from org
```

---

## Organisation Model API

### Check Type

```php
$org->isPlatform()                             // → bool
$org->isTenant()                               // → bool
$org->type                                     // → 'platform' | 'tenant'
$org->is_default                               // → bool (true only for platform)
```

### Get Users

```php
$org->users()                                  // → Collection[User]
$org->admins()                                 // → Collection[User] (role='admin')
$org->commissionMembers()                      // → Collection[User] (role='commission')
$org->voters()                                 // → Collection[User] (role='voter')
```

### Static Methods

```php
Organisation::getDefaultPlatform()             // → Org|null
```

---

## UserOrganisationRole (Pivot)

### Create/Update

```php
UserOrganisationRole::create([
    'user_id' => $userId,
    'organisation_id' => $orgId,
    'role' => 'member',              // or 'admin', 'owner'
    'permissions' => null,           // JSON
]);
```

### Roles

```
'member'    - Default role, limited permissions
'admin'     - Can manage elections and users
'owner'     - Full control, can add staff
```

---

## HTTP Routes

```
POST   /register                         # Create user
       Params: firstName, lastName, email, region, password, terms

POST   /organisations                    # Create organisation
       Auth required
       Params: name

GET    /                                 # Dashboard
       Route: ElectionController@dashboard
       Logic: Shows demo vs tenant dashboard

POST   /organisations/{id}/switch        # Switch context
       Auth required
       Updates: user.organisation_id
```

---

## Common Code Patterns

### Register a User (Test)

```php
$this->post('/register', [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john@example.com',
    'region' => 'Bayern',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'terms' => true,
]);

$user = User::where('email', 'john@example.com')->first();
assert($user->hasTenantOrganisation() === false);
```

### Create Organisation

```php
$this->actingAs($user)->post('/organisations', [
    'name' => "Alice's Company",
]);

$org = Organisation::where('name', "Alice's Company")->first();
assert($user->fresh()->getOwnedOrganisation()->id === $org->id);
assert($user->fresh()->organisation_id === $org->id);
```

### Switch Context

```php
$user->switchToOrganisation($newOrg);
assert($user->fresh()->organisation_id === $newOrg->id);
```

### Check Permissions

```php
if ($user->getRoleInOrganisation($org->id) === 'owner') {
    // Show admin panel
}
```

### List All Organisations

```php
$organisations = $user->organisations
    ->map(fn($org) => [
        'name' => $org->name,
        'role' => $user->getRoleInOrganisation($org->id),
        'type' => $org->type,
    ]);
```

---

## Troubleshooting

### Error: "organisation_id doesn't have a default value"

```
Location: When creating a user

Root Cause: User created without organisation_id

Fix:
  In RegisterController::store()
  $user->update(['organisation_id' => $platformOrg->id]);

Test:
  php artisan test tests/Feature/Auth/RegistrationMultiTenancyTest.php
```

### Error: "Cannot switch to organisation you don't belong to"

```
Location: When switching organisations

Root Cause: No pivot record exists for user + org

Fix:
  $user->organisations()->attach($orgId, ['role' => 'member']);
  $user->switchToOrganisation($org);

Test:
  php artisan test --filter=user_can_switch_organisations
```

### Error: "Duplicate entry 'publicdigit' for key 'organisations_slug_unique'"

```
Location: During tests or mass creation

Root Cause: Multiple processes creating platform org

Fix:
  Use firstOrCreate in factory:
  Organisation::firstOrCreate(
      ['type' => 'platform', 'is_default' => true],
      ['name' => 'PublicDigit', 'slug' => 'publicdigit']
  );

File: database/factories/UserFactory.php
```

### Error: "Tests Fail with RefreshDatabase"

```
Location: Running phpunit

Root Cause: RefreshDatabase transaction isolation

Fix:
  Use RefreshDatabase trait in test:
  use Illuminate\Foundation\Testing\RefreshDatabase;

  Use factories properly:
  Organisation::factory()->tenant()->create()  // Auto-generates slug
```

### Issue: Dashboard Shows Wrong Elections

```
Location: GET / route

Root Cause: hasTenantOrganisation() check failing

Debug:
  $user = Auth::user()->fresh();
  dd($user->organisations);
  dd($user->organisation_id);
  dd($user->hasTenantOrganisation());

Fix:
  Check UserFactory provides organisation_id
  Check RegisterController sets organisation_id
```

---

## Useful Debugging Commands

```php
// In tinker or test

// Check user state
$user = User::find($userId);
$user->organisations;              // All orgs
$user->organisation_id;            // Current org
$user->hasTenantOrganisation();    // Has tenant?
$user->getOwnedOrganisation();     // Owns which?

// Check organisation
$org = Organisation::find($orgId);
$org->type;                        // 'platform' or 'tenant'
$org->is_default;                  // Is platform?
$org->users;                       // All members

// Check pivot
UserOrganisationRole::where('user_id', $userId)
    ->where('organisation_id', $orgId)
    ->first();                     // Get pivot record

// List all
Organisation::all();               // All orgs
User::all();                       // All users
UserOrganisationRole::all();       // All pivots
```

---

## File Locations

```
Models:
  app/Models/User.php (helpers: lines 1113-1141)
  app/Models/Organisation.php (helpers: lines 78-99)
  app/Models/UserOrganisationRole.php

Controllers:
  app/Http/Controllers/Auth/RegisterController.php
  app/Http/Controllers/OrganisationController.php
  app/Http/Controllers/Election/ElectionController.php

Factories:
  database/factories/UserFactory.php
  database/factories/OrganisationFactory.php

Tests:
  tests/Feature/Auth/RegistrationMultiTenancyTest.php
  tests/Feature/OrganisationCreationIntegrationTest.php
  tests/Feature/UuidMultiTenancyTest.php
  tests/Unit/Models/UserTest.php
```

---

## Key Concepts

| Term | Means | Example |
|------|-------|---------|
| **Platform Org** | Demo/sandbox environment | PublicDigit (type='platform') |
| **Tenant Org** | Production environment | Acme Inc (type='tenant') |
| **Pivot Record** | User membership in org | user_organisation_roles row |
| **Current Org** | User's active context | user.organisation_id |
| **Member** | Basic user role | Can view/vote |
| **Owner** | Creator of org | Full permissions |
| **Admin** | Staff member | Can manage content |

---

## Performance Tips

```php
// Good: Eager load
User::with('organisations', 'currentOrganisation')->get();

// Bad: N+1 queries
User::all()->each(fn($u) => $u->organisations);

// Good: Cache platform (rarely changes)
Cache::rememberForever('org:platform:default',
    fn() => Organisation::getDefaultPlatform()
);

// Good: Use indices
// Already present on:
//   organisations(slug) UNIQUE
//   user_organisation_roles(user_id, organisation_id) UNIQUE
```

---

## Testing Template

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourMultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_do_something()
    {
        // Setup
        $user = User::factory()->create();
        $org = Organisation::factory()->tenant()->create();

        // Action
        $user->switchToOrganisation($org);

        // Assert
        $this->assertEquals($org->id, $user->fresh()->organisation_id);
    }
}
```

---

**For more details, see PHASE_6_COMPLETE.md or check test files.**
