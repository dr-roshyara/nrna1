# Testing Guide

## Test Suites

| Suite | File | Tests | What It Covers |
|-------|------|-------|----------------|
| Officer Management | `tests/Feature/Election/ElectionOfficerManagementTest.php` | 11 | Appoint, remove, reappoint, duplicate prevention |
| Officer Invitation | `tests/Feature/ElectionOfficerInvitationTest.php` | 7 | Email sent, signature, guest flow, wrong user, expiry |
| Dashboard Access | `tests/Feature/Election/ElectionDashboardAccessTest.php` | 12 | Role-based access to management/viewboard/actions |
| Election Creation | `tests/Feature/Election/ElectionCreationTest.php` | 21 | Owner/admin can create; officers/members cannot; validation; defaults |
| Election Activation | `tests/Feature/Election/ElectionActivationTest.php` | 9 | Chief/deputy activate; commissioner blocked; status checks; email notifications |

```bash
# Run all 52
php artisan test tests/Feature/Election/

# Expected:
# Tests: 52 passed (123 assertions)

# Run specific suites
php artisan test tests/Feature/Election/ElectionCreationTest.php
php artisan test tests/Feature/Election/ElectionActivationTest.php
```

---

## ElectionDashboardAccessTest Setup

The test setUp creates a realistic org + election + officer matrix:

```php
protected function setUp(): void
{
    parent::setUp();

    $this->org = Organisation::factory()->create(['type' => 'tenant']);
    session(['current_organisation_id' => $this->org->id]);

    $this->election = Election::factory()->forOrganisation($this->org)->real()->create([
        'status'            => 'active',
        'results_published' => false,
    ]);

    $this->chief       = $this->makeOfficer('chief', 'active');
    $this->deputy      = $this->makeOfficer('deputy', 'active');
    $this->commissioner = $this->makeOfficer('commissioner', 'active');
    $this->pendingChief = $this->makeOfficer('chief', 'pending');
    $this->nonOfficer  = User::factory()->create(['organisation_id' => $this->org->id]);
}
```

### Key helper: `orgSession()`

```php
private function orgSession(): array
{
    return ['current_organisation_id' => $this->org->id];
}
```

> ⚠️ **Do not name this helper `session()`** — it conflicts with Laravel TestCase's public `session()` method. Use `orgSession()` or similar.

### Key helper: `makeOfficer()`

```php
private function makeOfficer(string $role, string $status): User
{
    $user = User::factory()->create(['organisation_id' => $this->org->id, ...]);
    UserOrganisationRole::create([...]); // needed for org membership
    ElectionOfficer::create([
        'organisation_id' => $this->org->id,
        'user_id'         => $user->id,
        'role'            => $role,
        'status'          => $status,
        'appointed_by'    => $user->id,
        'appointed_at'    => now(),
        'accepted_at'     => $status === 'active' ? now() : null,
    ]);
    return $user;
}
```

---

## Test Cases Explained

### Management Dashboard

```php
// Chief and deputy get 200 + Inertia Election/Management component
public function test_chief_can_access_management_dashboard(): void
{
    $this->actingAs($this->chief)
        ->withSession($this->orgSession())
        ->get(route('elections.management', $this->election))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Election/Management'));
}

// Commissioner gets 403
public function test_commissioner_cannot_access_management_dashboard(): void
{
    $this->actingAs($this->commissioner)
        ->withSession($this->orgSession())
        ->get(route('elections.management', $this->election))
        ->assertForbidden();
}

// Pending officer gets 403 — status=pending is not active
public function test_pending_officer_cannot_access_management_dashboard(): void
{
    $this->actingAs($this->pendingChief)
        ->withSession($this->orgSession())
        ->get(route('elections.management', $this->election))
        ->assertForbidden();
}
```

### Publish Results

```php
// Chief can publish — results_published becomes true
public function test_chief_can_publish_results(): void
{
    $this->actingAs($this->chief)
        ->withSession($this->orgSession())
        ->post(route('elections.publish', $this->election))
        ->assertRedirect();

    $this->assertTrue($this->election->fresh()->results_published);
}

// Deputy cannot publish — 403
public function test_deputy_cannot_publish_results(): void
{
    $this->actingAs($this->deputy)
        ->withSession($this->orgSession())
        ->post(route('elections.publish', $this->election))
        ->assertForbidden();
}
```

### Voting Period Control

```php
public function test_chief_can_open_and_close_voting(): void
{
    // Close first
    $this->actingAs($this->chief)
        ->withSession($this->orgSession())
        ->post(route('elections.close-voting', $this->election))
        ->assertRedirect();

    $this->assertEquals('completed', $this->election->fresh()->status);

    // Re-open
    $this->actingAs($this->chief)
        ->withSession($this->orgSession())
        ->post(route('elections.open-voting', $this->election))
        ->assertRedirect();

    $this->assertEquals('active', $this->election->fresh()->status);
}
```

### Cross-Org Isolation

```php
public function test_officer_from_different_org_cannot_access_election(): void
{
    $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
    $outsiderChief = User::factory()->create(['organisation_id' => $otherOrg->id]);
    ElectionOfficer::create([
        'organisation_id' => $otherOrg->id,
        'user_id'         => $outsiderChief->id,
        'role'            => 'chief',
        'status'          => 'active',
        ...
    ]);

    $response = $this->actingAs($outsiderChief)
        ->withSession(['current_organisation_id' => $otherOrg->id])
        ->get(route('elections.management', $this->election));

    // 403 (policy) or 404 (BelongsToTenant scope) — both are correct
    $this->assertContains($response->status(), [403, 404],
        'Cross-org access must be blocked (403 or 404)');
}
```

> **Why 403 or 404?** `BelongsToTenant` global scope on `Election` filters by `session('current_organisation_id')`. If the outsider's session org doesn't match the election's org, route model binding fails → 404 before the policy even runs. Either outcome correctly blocks access.

---

## ElectionOfficerInvitationTest Cases

```
1. test_email_is_sent_when_officer_is_appointed
2. test_invitation_link_requires_valid_signature
3. test_authenticated_officer_can_accept_invitation
4. test_guest_sees_login_page_with_prefilled_email
5. test_wrong_user_gets_403
6. test_expired_link_returns_403
7. test_already_accepted_shows_error_page
```

Key pattern for testing signed URLs:

```php
// Generate a valid signed URL
$url = URL::temporarySignedRoute(
    'organisations.election-officers.invitation.accept',
    now()->addDays(7),
    ['organisation' => $organisation->slug, 'officer' => $officer->id]
);

$this->actingAs($officer->user)
    ->get($url)
    ->assertOk()
    ->assertInertia(fn ($page) => $page->component('Organisations/ElectionOfficers/Accepted'));

// Officer is now active
$this->assertEquals('active', $officer->fresh()->status);
```

---

---

## ElectionCreationTest Cases

The creation test uses owner/admin as the permitted actors. Officers (chief/deputy/commissioner) are created but expected to receive 403.

```
test_organisation_owner_can_create_election        → 302, DB has election status='planned', type='real'
test_organisation_admin_can_create_election        → 302, DB has election
test_election_chief_cannot_create_election         → 403
test_election_deputy_cannot_create_election        → 403
test_election_commissioner_cannot_create_election  → 403
test_regular_member_cannot_create_election         → 403
test_policy_only_allows_owner_and_admin_to_create  → assertTrue/assertFalse via $user->can()
test_election_requires_name                        → assertSessionHasErrors('name')
test_election_requires_start_date                  → assertSessionHasErrors('start_date')
test_election_requires_end_date                    → assertSessionHasErrors('end_date')
test_start_date_must_be_before_end_date            → assertSessionHasErrors('end_date')
test_start_date_cannot_be_in_past                  → assertSessionHasErrors('start_date')
test_cannot_submit_demo_type                       → assertSessionHasErrors('type')
test_election_name_must_be_unique_within_org       → assertSessionHasErrors('name')
test_same_election_name_allowed_in_different_org   → 302, both orgs have their own election
test_election_defaults_to_planned_status           → assertEquals('planned', ...)
test_election_type_is_always_real                  → assertEquals('real', ...)
test_description_is_optional                       → 302, description=null in DB
test_slug_is_generated_on_creation                 → assertNotNull, contains slugified name
test_success_flash_message_on_creation             → assertSessionHas('success')
test_officer_from_different_org_cannot_create      → assertContains([302,403,404])
```

---

## ElectionActivationTest Cases

```
test_chief_can_activate_planned_election           → 302, status='active', success flash
test_deputy_can_activate_planned_election          → 302, status='active'
test_commissioner_cannot_activate_election         → 403, status unchanged
test_owner_cannot_activate_election                → 403, status unchanged
test_cannot_activate_already_active_election       → error flash, status stays 'active'
test_cannot_activate_completed_election            → error flash, status stays 'completed'
test_email_notification_sent_to_chief_when_created → Notification::assertSentTo chief
test_email_notification_sent_to_all_active_chiefs  → both chiefs receive notification
test_email_notification_not_sent_to_inactive_chiefs → inactive chief excluded
```

Key pattern for notification tests:
```php
Notification::fake();

$this->actingAs($this->owner)
    ->withSession($this->orgSession())
    ->post(route('organisations.elections.store', $this->org->slug), [...]);

Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
Notification::assertNotSentTo($inactiveChief, ElectionReadyForActivation::class);
```

---

## Adding New Tests

When adding tests for election management actions, always:

1. Use `makeOfficer()` / `createOfficer()` to create properly structured officers (with both `UserOrganisationRole` and `ElectionOfficer` records)
2. Pass `withSession($this->orgSession())` on every request
3. Use `$this->election->fresh()` to reload from DB after POST assertions
4. Test all three roles (chief, deputy, commissioner) for each permission boundary
5. For creation tests, use `createUserWithRole('owner')` — not an officer helper
