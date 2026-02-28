# Three-Role System Documentation

## Overview

The platform supports three distinct user roles with specific capabilities and content:

1. **Admin** - organisation and election management
2. **Commission** - Election oversight and monitoring
3. **Voter** - Participation in elections

## Role Detection

### RoleDetectionService

**File:** `app/Services/Dashboard/RoleDetectionService.php`

### Detection Logic

#### Admin Role
```php
// Check: User has organizationRoles
if ($user->organizationRoles()->count() > 0) {
    $roles->push('admin');
}
```

#### Commission Role
```php
// Check: User has is_committee_member flag
if ((bool) $user->getAttribute('is_committee_member')) {
    $roles->push('commission');
}
```

#### Voter Role
```php
// Check: User has voterRegistrations
if ($user->voterRegistrations()->count() > 0) {
    $roles->push('voter');
}
```

### Primary Role Priority

When user has multiple roles, priority is:
```
admin > commission > voter
```

## Role-Specific Features

### Admin Role

#### Capabilities
- Create organizations
- Add/manage members
- Create elections
- Configure election settings
- View election results
- Manage roles and permissions

#### Onboarding Steps
1. New user (0%)
2. organisation created (25%)
3. Members added (50%)
4. Election created (75%)
5. Setup complete (100%)

#### Content Blocks
- RoleBasedActionBlock - Org creation/member management
- OrganizationStatusBlock - Setup progress
- PendingActionsBlock - If setup incomplete

#### Available Actions
```
create_organization (primary)
add_members
create_election
view_organization
manage_election
```

#### Confidence Scoring
- Base: 40 points
- +15 for account age (established)
- +10 for completed actions
- +15 for multiple roles
- +20 for multiple organizations

#### UI Mode
- Simplified (new admins)
- Standard (experienced)
- Advanced (power users)

---

### Commission Role

#### Capabilities
- Monitor elections
- View voting progress
- Access results
- Generate reports

#### Content Blocks
- RoleBasedActionBlock - Election management
- PendingActionsBlock - If has actions

#### Available Actions
```
manage_election (primary)
view_results
download_report
```

#### Confidence Scoring
- Base: 50 points (established users)
- +10 for election participation
- +10 for multiple roles

---

### Voter Role

#### Capabilities
- Participate in elections
- View election information
- Cast votes
- Verify vote (if enabled)

#### Content Blocks
- RoleBasedActionBlock - Voting cards
- PendingActionsBlock - If has pending votes

#### Available Actions
```
cast_vote (primary - if pending)
view_votes
view_election_info
```

#### Confidence Scoring
- Base: 35 points (typical member)
- +5 for prior votes
- +10 for organisation membership

---

## Composite States

User state combines roles and context:

### New Users
```
new_user_no_roles
- No organisation
- No roles
- Shows: Create Org, Join Org, Request Help
```

### Admins

#### Setup Phase
```
admin_no_org
- Admin but no organisation
- Shows: Create organisation (primary)

admin_setup_started
- organisation exists, needs members
- Shows: Add Members (primary)

admin_setup_in_progress
- Members added, needs election
- Shows: Create Election (primary)
```

#### Active Phase
```
admin_with_elections
- Setup complete, has elections
- Shows: organisation management options
```

### Commission
```
commission_no_election
- No active election to manage

commission_election_active
- Has active election to oversee
- Shows: Manage Election (primary)
```

### Voters
```
voter_with_pending_votes
- Has pending elections to vote in
- Shows: Cast Vote (primary)

voter_no_pending_votes
- No active elections
- Shows: View past votes
```

## Multi-Role Users

Users can have multiple roles simultaneously.

### Example Scenario
User is both **Admin** and **Voter**:

```
Primary Role: admin (highest priority)
Roles Array: ['admin', 'voter']
Composite State: admin_with_elections
Available Actions: Merge of both role actions
Content Blocks: Admin-specific blocks
UI Mode: Advanced (multiple roles = experienced)
```

### Action Card Logic

```php
// Admin cards shown first (priority)
if (in_array('admin', $userState->roles)) {
    $cards = [...admin cards...];
}

// Commission cards added
if (in_array('commission', $userState->roles)) {
    $cards = array_merge($cards, [...commission cards...]);
}

// Voter cards added last
if (in_array('voter', $userState->roles)) {
    $cards = array_merge($cards, [...voter cards...]);
}
```

## Testing Roles

### Find Users by Role

```bash
# Find admin
php artisan tinker
> User::whereHas('organizationRoles')->first()

# Find commission
> User::where('is_committee_member', true)->first()

# Find voter
> User::whereHas('voterRegistrations')->first()
```

### Test Role Detection

```bash
php artisan tinker
> $user = User::first()
> app(\App\Services\Dashboard\RoleDetectionService::class)->getDashboardRoles($user)
> app(\App\Services\Dashboard\RoleDetectionService::class)->getPrimaryRole($user)
> app(\App\Services\Dashboard\RoleDetectionService::class)->detectCompositeState($user)
```

### Simulate Different Users

```php
// Create test users with different roles
$adminUser = User::factory()->create();
$adminUser->organizationRoles()->create(['role' => 'admin']);

$commissionUser = User::factory()->create();
$commissionUser->update(['is_committee_member' => true]);

$voterUser = User::factory()->create();
$voterUser->voterRegistrations()->create([]);
```

## Role-Specific Welcome Content

### Admin Welcome
- organisation setup prompt
- Member management guide
- Election creation wizard
- Trust signals about compliance

### Commission Welcome
- Election monitoring dashboard
- Results viewing instructions
- Report generation option
- Security/audit information

### Voter Welcome
- Active elections list
- Voting instructions
- Verification information
- Past voting history

## Adding New Roles

To add a new role:

1. **Update RoleDetectionService**
```php
// In getDashboardRoles()
if ($user->hasNewRoleIndicator()) {
    $roles->push('new_role');
}
```

2. **Update ActionService**
```php
// Add actions for new role
if (in_array('new_role', $userState->roles)) {
    $cards = array_merge($cards, [
        ['id' => 'action_1', 'title' => '...'],
        // ...
    ]);
}
```

3. **Update TrustSignalService**
```php
// Add role-specific signals
if (in_array('new_role', $userState->roles)) {
    $signals[] = [
        'type' => 'new_role_specific',
        'message' => '...'
    ];
}
```

4. **Add Vue Component Content**
- New card designs for role
- Role-specific help text
- Role-specific warnings

5. **Add Translations**
- German: `de.json`
- English: `en.json`
- Nepali: `np.json`

## Permissions vs Roles

**Roles** (used in Welcome Page):
- admin, commission, voter

**Permissions** (used elsewhere):
- create_election, delete_member, etc.

Welcome page uses **roles only** for content display.

## GDPR Considerations

Each role may have different:
- Data display requirements
- Consent prerequisites
- Information access levels
- Privacy settings

See [05-GDPR-COMPLIANCE.md](./05-GDPR-COMPLIANCE.md) for details.

## Performance

Role detection is optimized with safe eager loading:

**Query Efficiency:**
- Uses `relationLoaded()` checks to avoid re-querying
- Never calls `.count()`, `.first()`, `.exists()` on unloaded relationships
- Single eager load of all relationships in UserStateBuilder
- No N+1 queries (exactly 6 total queries for dashboard welcome)

**Important Safe Pattern:**
```php
// ✅ CORRECT: Check if already loaded
$organizationRoles = $user->relationLoaded('organizationRoles')
    ? $user->organizationRoles
    : $user->organizationRoles()->get();

// ❌ WRONG: Always queries (causes N+1)
$organizationRoles = $user->organizationRoles()->get();
```

**Performance Metrics (Dashboard Welcome):**
- Total queries: 6 (not 50+)
- Response time: ~180ms (not 30+ seconds)
- Cached via UserStateBuilder
- No performance degradation with multiple roles

## Security

- Role detection validates actual data (not session-based)
- Frontend shows UI only for detected roles
- Backend enforces permissions (separate system)
- No privilege escalation possible from UI alone
