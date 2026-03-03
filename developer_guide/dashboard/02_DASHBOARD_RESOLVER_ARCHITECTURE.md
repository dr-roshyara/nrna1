# DashboardResolver: 6-Priority Routing System

## Overview

`DashboardResolver` is the **core routing engine** that determines which dashboard a user sees. It implements a **6-priority decision tree** that handles all possible user states.

**File:** `app/Services/DashboardResolver.php`

---

## The 6-Priority System

### Priority Order (Highest to Lowest)

```
PRIORITY 1: ACTIVE VOTING SESSION
    Is user in middle of voting?
    └─ YES → Redirect to /vote/{slug}
    └─ NO → Continue
             ↓
PRIORITY 2: ACTIVE ELECTION AVAILABLE
    Is there an active election user can vote in?
    └─ YES → Redirect to /election/dashboard
    └─ NO → Continue
             ↓
PRIORITY 3: NEW USER WELCOME
    Is this a new user who hasn't been onboarded?
    └─ YES → Redirect to /dashboard/welcome
    └─ NO → Continue
             ↓
PRIORITY 4: MULTIPLE ROLES
    Does user have multiple dashboard roles?
    └─ YES → Redirect to /dashboard/roles
    └─ NO → Continue
             ↓
PRIORITY 5: SINGLE ROLE
    Does user have exactly one dashboard role?
    └─ YES → Redirect to role-specific dashboard
    └─ NO → Continue
             ↓
PRIORITY 6: PLATFORM USER FALLBACK
    No roles at all?
    └─ Redirect to /dashboard (generic dashboard)
```

---

## Detailed Priority Explanations

### PRIORITY 1: Active Voting Session

**Question:** Is the user currently in the middle of voting?

**Detection:**
```php
// Check voter_slugs table
$voterSlug = DB::table('voter_slugs')
    ->where('user_id', $user->id)
    ->where('is_active', true)
    ->where('expires_at', '>', now())
    ->where('current_step', '<', 5)  // Not yet completed
    ->first();
```

**Redirect:**
```php
// If found, redirect to voting portal
return redirect()->route('vote.start', ['slug' => $voterSlug->slug]);
```

**Why This Is Highest Priority:**
- User is actively voting RIGHT NOW
- Interrupting is worse than any other issue
- Must redirect immediately to avoid timeout

**Examples:**
- User opened voting, closed browser, came back
- User clicked "continue voting"
- Session expired but voting not complete

---

### PRIORITY 2: Active Election Available

**Question:** Does the user have an active election they can vote in but haven't started?

**Detection:**
```php
// Check elections table
$activeElection = Election::where('organisation_id', $user->organisation_id)
    ->where('status', 'active')
    ->whereBetween('start_date', now())
    ->whereBetween('end_date', now())
    ->whereNotExists(function ($query) {
        $query->table('voter_slugs')
            ->where('user_id', $user->id)
            ->where('is_active', false);  // Already voted
    })
    ->first();
```

**Redirect:**
```php
return redirect()->route('election.dashboard', $activeElection->slug);
```

**Why This Is Priority 2:**
- User should be reminded they can vote
- Time-sensitive (voting window closing)
- Less urgent than "voting in progress" but still urgent

**Examples:**
- New election started for user's organisation
- User hasn't voted yet and voting window is still open
- User finished one election, another is now available

---

### PRIORITY 3: New User Welcome

**Question:** Is this a user who just verified their email but hasn't been onboarded?

**Detection:**
```php
// User is new if:
// 1. Email is verified
// 2. But no organisations assigned
// 3. AND no other roles

$isNew = $user->email_verified_at !== null
    && DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->count() === 0
    && !$user->is_voter
    && !$user->hasRole('admin');
```

**Redirect:**
```php
return redirect()->route('dashboard.welcome');
```

**Why This Is Priority 3:**
- User experience: Guides new users through setup
- Separates onboarding from role assignment
- Prevents confusion with "no roles" fallback

**Examples:**
- User just registered and verified email
- User was registered but never onboarded
- User needs to be assigned to an organisation

---

### PRIORITY 4: Multiple Roles

**Question:** Does the user have more than one dashboard role?

**Detection:**
```php
// Check all role sources
$roles = [];

// Check organisational roles
$roles[] = 'admin' if has_organisation_role;
$roles[] = 'member' if has_member_role;

// Check commission membership
$roles[] = 'commission' if has_election_commission_membership;

// Check legacy voter
$roles[] = 'voter' if user->is_voter;

// More than one?
if (count(array_unique($roles)) > 1) {
    return redirect()->route('role.selection');
}
```

**Redirect:**
```php
return redirect()->route('role.selection');
```

**Why This Is Priority 4:**
- User needs to pick which role to use
- Prevents confusion (admin would see admin dashboard, not voter dashboard)
- Complex scenario, so lower priority than simple ones

**Examples:**
- User is admin in one org AND a voter
- User is admin in two different organisations
- User is commission member AND voter

---

### PRIORITY 5: Single Role

**Question:** Does the user have exactly one dashboard role?

**Detection:**
```php
// If we got here, we know there's exactly 1 role
$role = $dashboardRoles[0];

// Routes by role:
match($role) {
    'admin' => route('organisations.show', $organisation->slug),
    'commission' => route('commission.dashboard'),
    'voter' => route('vote.dashboard'),
}
```

**Redirect:**
```php
return redirect()->route('organisations.show', $organisation->slug);  // Admin
return redirect()->route('commission.dashboard');                      // Commission
return redirect()->route('vote.dashboard');                           // Voter
```

**Why This Is Priority 5:**
- Straightforward case: user has one clear role
- No ambiguity, just go to that dashboard
- Simple routing logic

**Examples:**
- User is only an admin (redirect to org page)
- User is only a commission member (redirect to commission)
- User is only a voter (redirect to voting dashboard)

---

### PRIORITY 6: Platform User Fallback

**Question:** User has no roles at all - what to do?

**Detection:**
```php
// If we reached here with no roles, it's the fallback case
if (empty($dashboardRoles)) {
    return redirect()->route('dashboard');  // Generic dashboard
}
```

**Redirect:**
```php
return redirect()->route('dashboard');
```

**Why This Is Priority 6:**
- Absolute fallback - should rarely happen
- User exists but has no special role
- Generic dashboard shows limited info
- User can request access to organisations

**Examples:**
- User is registered but not assigned to any organisation
- User was removed from all organisations
- Placeholder for platform-wide content

---

## Implementation Pattern

### The resolve() Method

```php
public function resolve(User $user): RedirectResponse
{
    // Log entry
    Log::info('DashboardResolver: Processing user', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

    // PRIORITY 1: Active voting session
    if ($this->hasActiveVotingSession($user)) {
        return $this->redirectToVotingPortal($user);
    }

    // PRIORITY 2: Active election
    $election = $this->getActiveElectionForUser($user);
    if ($election) {
        return redirect()->route('election.dashboard', $election->slug);
    }

    // PRIORITY 3: New user welcome
    if ($this->isNewUserWithoutOrganisation($user)) {
        return redirect()->route('dashboard.welcome');
    }

    // PRIORITY 4: Multiple roles
    $roles = $this->getDashboardRoles($user);
    if (count($roles) > 1) {
        return redirect()->route('role.selection');
    }

    // PRIORITY 5: Single role
    if (count($roles) === 1) {
        return $this->redirectByRole($user, reset($roles));
    }

    // PRIORITY 6: Fallback
    return redirect()->route('dashboard');
}
```

### Helper Methods

Each priority has a dedicated helper method:

```php
protected function hasActiveVotingSession(User $user): bool
protected function getActiveElectionForUser(User $user): ?Election
protected function isNewUserWithoutOrganisation(User $user): bool
protected function getDashboardRoles(User $user): array
protected function redirectByRole(User $user, string $role): RedirectResponse
```

---

## Logging Strategy

Every decision is logged:

```php
Log::info('DashboardResolver: Redirect decision', [
    'user_id' => $user->id,
    'decision' => 'priority_1_active_voting',
    'destination' => 'vote.start',
    'reason' => 'User has active voting session',
]);
```

This allows you to:
- Debug routing issues
- Understand user flow
- Identify misconfigurations
- Monitor system behavior

---

## Testing the 6-Priority System

### Test File
`tests/Feature/Auth/DashboardResolverPriorityTest.php`

### Test Coverage

```
PRIORITY 1 TESTS:
✓ priority_1_active_voting_session_redirects_to_voting_portal()
✓ priority_1_takes_precedence_over_multiple_active_elections()

PRIORITY 2 TESTS:
✓ priority_2_active_election_redirects_to_election_dashboard()
✓ priority_2_skips_elections_where_user_already_voted()
✓ priority_2_ignores_elections_outside_voting_window()

PRIORITY 3 TESTS:
✓ priority_3_new_user_verified_but_no_org_goes_to_welcome()
✓ priority_3_new_user_with_platform_org_only_goes_to_welcome()
✓ priority_3_skips_if_user_already_onboarded()

PRIORITY 4 TESTS:
✓ priority_4_user_with_multiple_roles_goes_to_role_selection()
✓ priority_4_user_with_admin_and_commission_roles_goes_to_role_selection()

PRIORITY 5 TESTS:
✓ priority_5_single_admin_role_redirects_to_organisation_page()
✓ priority_5_single_commission_role_redirects_to_commission_dashboard()

PRIORITY 6 TESTS:
✓ priority_6_user_with_no_roles_goes_to_default_dashboard()

PRECEDENCE TESTS:
✓ active_voting_takes_precedence_over_new_user_welcome()
✓ active_election_takes_precedence_over_new_user_welcome()
✓ roles_take_precedence_over_welcome_when_onboarded()
```

---

## Edge Cases Handled

### Edge Case 1: User Deletes Organisation
**Scenario:** User was admin in organisation, organisation gets deleted

**Handling:** User now has no roles → PRIORITY 6 fallback

### Edge Case 2: Multiple Elections Active
**Scenario:** Two elections are active at same time

**Handling:** Return first available (oldest first by start_date)

### Edge Case 3: User Loses Commission Membership
**Scenario:** User was commission member, gets removed

**Handling:** Loss of commission role → Check remaining roles, re-route

### Edge Case 4: Voting Window Closes
**Scenario:** User starts voting, window closes before they finish

**Handling:** PRIORITY 2 stops showing election → user gets fallback

---

## Performance Considerations

### Caching Strategy

Dashboard roles are cached for **60 seconds**:

```php
$dashboardRoles = Cache::remember(
    "user_{$user->id}_dashboard_roles",
    60,  // 60 seconds
    function() {
        return $this->computeDashboardRoles();
    }
);
```

**Why?**
- Roles don't change often
- User logging in multiple times needs fast routing
- Database queries reduced significantly

**Cache Invalidation:**
- When user is assigned a role → clear cache
- When user is removed from organisation → clear cache

---

## Security Guarantees

✅ **No Cross-Tenant Access** - Organisation checks prevent cross-org routing

✅ **Email Verification Required** - Checked by middleware AND resolve()

✅ **Role Validation** - Middleware re-validates role on target dashboard

✅ **Audit Trail** - Every routing decision is logged

✅ **Timeout Protection** - Voting session timeout prevents abandoned votes

---

## Related Components

- **LoginResponse** - Calls DashboardResolver
- **CheckUserRole Middleware** - Validates role on target dashboard
- **User Model** - getDashboardRoles() method
- **VoterSlug Model** - Tracks voting progress
- **Election Model** - Tracks election status

---

## How to Add a New Priority

If you need to add a new priority (e.g., "check for pending tasks"):

1. **Insert between existing priorities**
2. **Write helper method** - `hasPendingTasks()`
3. **Add to resolve()** - Insert check in correct position
4. **Write tests** - Add test cases to DashboardResolverPriorityTest.php
5. **Document** - Update this file with new priority explanation

---

**Last Updated:** March 4, 2026
