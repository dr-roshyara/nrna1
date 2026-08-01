# Claude CLI Prompt: Complete Remaining Tasks (TDD First Approach)

## 📋 Complete Prompt to Copy into Claude CLI

```markdown
## Context
I have completed 5 major phases of the membership system:
- Phase 1-4: Member approval, fee recalculation, participant UI, expiry automation
- Phase 5: Participant bulk import
- Phase 6: Voter eligibility enforcement (only active formal members can vote)

All 139 tests pass. However, there are **remaining gaps** identified in the analysis.

## Remaining Tasks (Prioritized)

### Task 1: Fix Voter Dropdown UI (P2 - Low Risk)
**Current Problem:** `ElectionVoterController::index()` queries `user_organisation_roles` for the dropdown, showing ineligible users (staff, guests, expired members). They are blocked on submit, but the UI is misleading.

**Expected Behavior:** Dropdown should only show **active formal members with full voting rights** (same as `isEligibleVoter()`).

**Files to Change:**
- `app/Http/Controllers/ElectionVoterController.php` (index method, lines 56-62)
- Update the query to use `members` table instead of `user_organisation_roles`

### Task 2: Add Election-Scoped Check to Legacy Vote Route (P1 - Medium Risk)
**Current Problem:** Legacy `vote.store` route (line 148 in `routes/electionRoutes.php`) uses `vote.eligibility` middleware which checks `is_voter`/`can_vote` flags - NOT election-scoped.

**Expected Behavior:** Legacy route should also verify the user is registered for THIS specific election.

**Files to Change:**
- `app/Http/Middleware/VoteEligibility.php` (add election-scoped check)
- Or update the route to use `ensure.election.voter` middleware

### Task 3: Add `isVoterInElection()` Cached Method (P3 - Optimization)
**Current Problem:** The method exists but cache TTL may be too long (5 min). Consider making configurable.

**Files to Change:**
- `app/Models/User.php` (line ~309)
- Add configurable cache TTL

### Task 4: Add Policy for Voter Management (P3 - Nice to Have)
**Current Problem:** Authorization is done manually with `authorize('manageVoters', $election)` but policy may not be fully implemented.

**Files to Change:**
- `app/Policies/ElectionPolicy.php` (ensure `manageVoters` method exists and is correct)

## TDD Implementation Required

### Phase A: Fix Voter Dropdown UI (TDD)

#### Step A1: Write Tests First (Red)
Create or update `tests/Feature/Election/VoterDropdownTest.php`:

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterDropdownTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);
        
        $this->officer = $this->createOfficer();
    }

    private function createOfficer(): User
    {
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => 'owner',
        ]);
        return $user;
    }

    private function createActiveMember(User $user, string $feesStatus = 'paid', ?string $expiresAt = null): Member
    {
        $type = MembershipType::factory()->fullMember()->create([
            'organisation_id' => $this->org->id,
        ]);
        
        $orgUser = \App\Models\OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => 'member',
        ]);
        
        return Member::factory()->create([
            'organisation_id' => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id' => $type->id,
            'status' => 'active',
            'fees_status' => $feesStatus,
            'membership_expires_at' => $expiresAt,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Tests
    // ══════════════════════════════════════════════════════════════════════════

    public function test_dropdown_shows_only_eligible_voters(): void
    {
        $eligible = User::factory()->create(['name' => 'Eligible User']);
        $this->createActiveMember($eligible, 'paid', now()->addYear());
        
        $ineligibleStaff = User::factory()->create(['name' => 'Staff User']);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $ineligibleStaff->id,
            'organisation_id' => $this->org->id,
            'role' => 'staff',
        ]);
        
        $ineligibleExpired = User::factory()->create(['name' => 'Expired Member']);
        $this->createActiveMember($ineligibleExpired, 'paid', now()->subDay());
        
        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]));
        
        $response->assertOk();
        $response->assertInertia(fn($page) => 
            $page->has('unassignedMembers')
                 ->where('unassignedMembers', fn($members) => 
                     count($members) === 1 && 
                     $members[0]['name'] === 'Eligible User'
                 )
        );
    }

    public function test_dropdown_excludes_members_with_unpaid_fees(): void
    {
        $unpaidUser = User::factory()->create(['name' => 'Unpaid Member']);
        $this->createActiveMember($unpaidUser, 'unpaid', now()->addYear());
        
        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]));
        
        $response->assertOk();
        $response->assertInertia(fn($page) => 
            $page->where('unassignedMembers', fn($members) => 
                collect($members)->every(fn($m) => $m['name'] !== 'Unpaid Member')
            )
        );
    }

    public function test_dropdown_excludes_associate_members(): void
    {
        $associateType = MembershipType::factory()->associateMember()->create([
            'organisation_id' => $this->org->id,
        ]);
        
        $associateUser = User::factory()->create(['name' => 'Associate Member']);
        $orgUser = \App\Models\OrganisationUser::factory()->create([
            'user_id' => $associateUser->id,
            'organisation_id' => $this->org->id,
        ]);
        
        Member::factory()->create([
            'organisation_id' => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id' => $associateType->id,
            'status' => 'active',
            'fees_status' => 'paid',
        ]);
        
        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]));
        
        $response->assertOk();
        $response->assertInertia(fn($page) => 
            $page->where('unassignedMembers', fn($members) => 
                collect($members)->every(fn($m) => $m['name'] !== 'Associate Member')
            )
        );
    }

    public function test_dropdown_excludes_already_assigned_voters(): void
    {
        $assignedUser = User::factory()->create(['name' => 'Already Assigned']);
        $this->createActiveMember($assignedUser, 'paid', now()->addYear());
        
        // Assign as voter
        \App\Models\ElectionMembership::create([
            'user_id' => $assignedUser->id,
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'role' => 'voter',
            'status' => 'active',
            'assigned_by' => $this->officer->id,
            'assigned_at' => now(),
        ]);
        
        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]));
        
        $response->assertOk();
        $response->assertInertia(fn($page) => 
            $page->where('unassignedMembers', fn($members) => 
                collect($members)->every(fn($m) => $m['name'] !== 'Already Assigned')
            )
        );
    }
}
```

#### Step A2: Implement Fix
Update `ElectionVoterController@index`:

```php
// Replace the unassignedMembers query with:
$unassignedMembers = User::whereHas('member', function ($q) use ($organisation, $election) {
    $q->where('organisation_id', $organisation->id)
      ->where('status', 'active')
      ->whereIn('fees_status', ['paid', 'exempt'])
      ->whereHas('membershipType', fn($t) => $t->where('grants_voting_rights', true))
      ->where(fn($q) => $q->whereNull('membership_expires_at')->orWhere('membership_expires_at', '>', now()))
      ->whereDoesntHave('electionMemberships', fn($em) => $em->where('election_id', $election->id));
})->get(['id', 'name', 'email']);
```

### Phase B: Add Election-Scoped Check to Legacy Vote Route (TDD)

#### Step B1: Write Tests First (Red)
Create `tests/Feature/Election/LegacyVoteRouteTest.php`:

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyVoteRouteTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create();
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create(['status' => 'active']);
        
        $this->voter = User::factory()->create();
    }

    private function registerVoterForElection(User $user, Election $election): void
    {
        ElectionMembership::create([
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);
    }

    public function test_legacy_route_rejects_user_not_registered_for_election(): void
    {
        // User is NOT registered for this election
        $response = $this->actingAs($this->voter)
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
                'candidate_id' => 'some-uuid',
            ]);
        
        $response->assertForbidden()
            ->assertSee('not eligible');
    }

    public function test_legacy_route_accepts_user_registered_for_election(): void
    {
        $this->registerVoterForElection($this->voter, $this->election);
        
        $response = $this->actingAs($this->voter)
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
                'candidate_id' => 'some-uuid',
            ]);
        
        $this->assertNotEquals(403, $response->status());
    }

    public function test_legacy_route_rejects_user_registered_for_different_election(): void
    {
        $otherElection = Election::factory()
            ->forOrganisation($this->org)
            ->create(['status' => 'active']);
        
        $this->registerVoterForElection($this->voter, $otherElection);
        
        $response = $this->actingAs($this->voter)
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
                'candidate_id' => 'some-uuid',
            ]);
        
        $response->assertForbidden();
    }
}
```

#### Step B2: Implement Fix
Update `app/Http/Middleware/VoteEligibility.php`:

```php
public function handle(Request $request, Closure $next)
{
    $user = $request->user();
    $electionId = $request->input('election_id') ?? $request->route('election');
    
    // Check election-scoped voter registration FIRST
    if ($electionId && !$user->isVoterInElection($electionId)) {
        abort(403, 'You are not registered as a voter for this election.');
    }
    
    // Legacy check (keep for backward compatibility)
    if (!$user->is_voter || !$user->can_vote) {
        abort(403, 'You are not eligible to vote.');
    }
    
    return $next($request);
}
```

### Phase C: Configurable Cache TTL (TDD)

#### Step C1: Add Configuration
Add to `config/elections.php`:

```php
return [
    'voter_cache_ttl' => env('VOTER_CACHE_TTL', 300), // 5 minutes default
];
```

#### Step C2: Update User.php
```php
public function isVoterInElection($electionId): bool
{
    $ttl = config('elections.voter_cache_ttl', 300);
    
    return Cache::remember("user_{$this->id}_voter_in_{$electionId}", $ttl, function () use ($electionId) {
        return $this->electionMemberships()
            ->where('election_id', $electionId)
            ->where('role', 'voter')
            ->where('status', 'active')
            ->exists();
    });
}

// Add cache invalidation method
public function invalidateVoterCache($electionId = null): void
{
    if ($electionId) {
        Cache::forget("user_{$this->id}_voter_in_{$electionId}");
    } else {
        // Clear all election caches for this user
        $prefix = "user_{$this->id}_voter_in_";
        Cache::deleteByPrefix($prefix);
    }
}
```

### Phase D: Ensure Policy Exists (TDD)

#### Step D1: Write Test
Add to `tests/Feature/Election/ElectionPolicyTest.php`:

```php
public function test_manageVoters_policy_allows_owner_and_admin(): void
{
    $owner = $this->createUserWithRole('owner');
    $admin = $this->createUserWithRole('admin');
    $commission = $this->createUserWithRole('election_commission');
    
    $this->assertTrue($owner->can('manageVoters', $this->election));
    $this->assertTrue($admin->can('manageVoters', $this->election));
    $this->assertFalse($commission->can('manageVoters', $this->election));
}
```

#### Step D2: Ensure Policy Method Exists
In `app/Policies/ElectionPolicy.php`:

```php
public function manageVoters(User $user, Election $election): bool
{
    $role = UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $election->organisation_id)
        ->value('role');
    
    return in_array($role, ['owner', 'admin'], true);
}
```

## Execution Order

```bash
# 1. Run existing tests to confirm baseline
php artisan test --no-coverage

# 2. Write Phase A tests → Confirm Red
php artisan test tests/Feature/Election/VoterDropdownTest.php --no-coverage

# 3. Implement Phase A fix → Confirm Green

# 4. Write Phase B tests → Confirm Red
php artisan test tests/Feature/Election/LegacyVoteRouteTest.php --no-coverage

# 5. Implement Phase B fix → Confirm Green

# 6. Run full test suite
php artisan test --no-coverage

# 7. Verify all 139+ tests pass
```

## Success Criteria

- [ ] Voter dropdown shows ONLY eligible members
- [ ] Legacy vote route checks election-scoped registration
- [ ] Cache TTL is configurable
- [ ] `manageVoters` policy is fully implemented
- [ ] All existing tests still pass
- [ ] No regressions in voter eligibility enforcement

## Files to Modify

| File | Changes |
|------|---------|
| `app/Http/Controllers/ElectionVoterController.php` | Fix index() query |
| `app/Http/Middleware/VoteEligibility.php` | Add election-scoped check |
| `app/Models/User.php` | Add cache invalidation |
| `config/elections.php` | Add voter_cache_ttl |
| `app/Policies/ElectionPolicy.php` | Ensure manageVoters exists |
| `routes/electionRoutes.php` | Optional: update middleware order |

Proceed with TDD. Write tests first, confirm Red, implement, confirm Green.
```

---

## Quick Summary for You

This prompt will make Claude:

1. **Phase A (P2)** - Fix voter dropdown UI (4 tests)
2. **Phase B (P1)** - Add election-scoped check to legacy route (3 tests)
3. **Phase C (P3)** - Make cache TTL configurable
4. **Phase D (P3)** - Ensure policy exists (1 test)

**Total:** ~8-10 new tests, all passing after implementation.

Copy the entire prompt above into Claude CLI to complete the remaining tasks professionally with TDD! 🚀