## 🔍 Excellent Analysis! You've Identified a Critical Gap

Your analysis is **100% correct**. The current voter import **does NOT enforce formal membership** - it only checks if the user has ANY role in the organisation (via `user_organisation_roles`).

### Current State vs. Required State

| Check | Current | Required |
|-------|---------|----------|
| User exists in platform | ✅ Yes | ✅ Yes |
| User belongs to organisation | ✅ Yes (any role) | ✅ Yes |
| **User is active formal member** | ❌ **NO** | ✅ **YES** |
| User has voting rights | ❌ Not checked | ✅ Should be 'full' |

### The Problem

Currently, a **staff member** or **guest** (who never paid fees) can be imported as a voter. This violates the rule that **only active members can vote**.

### The Fix: Add Formal Membership Check

Here's the updated validation for `VoterImportService.php`:

```php
// Replace lines 130-164 in VoterImportService.php

private function validateRow(array $row): array
{
    $errors = [];
    $email = trim($row['email'] ?? '');

    if ($email === '') {
        $errors[] = 'Email is required.';
        return $errors;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "'{$email}' is not a valid email address.";
        return $errors;
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        $errors[] = "User '{$email}' does not exist in the platform.";
        return $errors;
    }

    // Check 1: User must belong to this organisation (any role)
    $hasOrgRole = DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->where('organisation_id', $this->election->organisation_id)
        ->exists();

    if (!$hasOrgRole) {
        $errors[] = "'{$email}' is not a member of this organisation.";
        return $errors;
    }

    // Check 2: User must be an ACTIVE FORMAL MEMBER (paid, not expired)
    $isActiveMember = DB::table('members')
        ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
        ->where('organisation_users.user_id', $user->id)
        ->where('members.organisation_id', $this->election->organisation_id)
        ->where('members.status', 'active')
        ->where(function($q) {
            $q->whereNull('members.membership_expires_at')
              ->orWhere('members.membership_expires_at', '>', now());
        })
        ->exists();

    if (!$isActiveMember) {
        $errors[] = "'{$email}' is not an active formal member of this organisation. Only active members can vote.";
        return $errors;
    }

    // Check 3: Member must have FULL voting rights (paid fees)
    $member = Member::whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
        ->where('organisation_id', $this->election->organisation_id)
        ->where('status', 'active')
        ->first();

    if ($member && $member->voting_rights !== 'full') {
        $errors[] = "'{$email}' does not have full voting rights (fees status: {$member->fees_status}).";
    }

    return $errors;
}
```

### Also Need to Fix Other Voter Assignment Paths

To be consistent, update these files as well:

**1. `app/Http/Controllers/ElectionVoterController.php` - `store()` method (line ~91)**

```php
// Add membership check before assigning voter
$isActiveMember = Member::whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
    ->where('organisation_id', $organisation->id)
    ->where('status', 'active')
    ->where(fn($q) => $q->whereNull('membership_expires_at')->orWhere('membership_expires_at', '>', now()))
    ->exists();

if (!$isActiveMember) {
    return back()->with('error', 'User is not an active formal member and cannot vote.');
}
```

**2. `app/Models/ElectionMembership.php` - `bulkAssignVoters()` method (line ~229)**

```php
// Replace the user_organisation_roles query with members query
$validUserIds = DB::table('members')
    ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
    ->whereIn('organisation_users.user_id', $userIds)
    ->where('members.organisation_id', $election->organisation_id)
    ->where('members.status', 'active')
    ->where(function($q) {
        $q->whereNull('members.membership_expires_at')
          ->orWhere('members.membership_expires_at', '>', now());
    })
    ->pluck('organisation_users.user_id');
```

### Summary of Required Changes

| File | Method | Change |
|------|--------|--------|
| `VoterImportService.php` | `validateRow()` | Add formal member check |
| `ElectionVoterController.php` | `store()` | Add formal member check |
| `ElectionVoterController.php` | `bulkStore()` | Add formal member check |
| `ElectionMembership.php` | `bulkAssignVoters()` | Replace role check with member check |


# Claude CLI Prompt for Voter Eligibility TDD Implementation

## 📋 Complete Prompt to Copy into Claude CLI

```markdown
## Context
I need to enforce that ONLY active formal members can become voters in elections. Currently, the system allows ANY user with a role in the organisation (staff, guest, etc.) to be assigned as a voter.

## Current Gap Analysis
- `VoterImportService::validateRow()` only checks `user_organisation_roles` (any role)
- `ElectionVoterController::store()` only checks org membership
- `ElectionVoterController::bulkStore()` only checks org membership  
- `ElectionMembership::bulkAssignVoters()` only checks `user_organisation_roles`

## The Rule
**A user can be a voter ONLY IF:**
1. User exists in platform ✅ (already checked)
2. User belongs to the organisation ✅ (already checked)
3. User has an ACTIVE formal membership record in `members` table
4. User's membership is NOT expired (`membership_expires_at` > now() OR null)
5. User has FULL voting rights (`member.voting_rights === 'full'`)

## TDD Implementation Required

### Phase 1: Write Tests First (Red)
Create `tests/Feature/Election/VoterEligibilityTest.php` with:

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
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VoterEligibilityTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $owner;
    private MembershipType $fullMemberType;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create();
        $this->owner = $this->createUserWithRole('owner');
        $this->fullMemberType = MembershipType::factory()->fullMember()->create([
            'organisation_id' => $this->org->id,
        ]);
        
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'status' => 'active',
        ]);
    }

    private function createUserWithRole(string $role, ?Organisation $org = null): User
    {
        $org = $org ?? $this->org;
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => $role,
        ]);
        return $user;
    }

    private function createFormalMember(User $user, string $status = 'active', ?string $expiresAt = null, string $feesStatus = 'paid'): Member
    {
        $orgUser = OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        
        return Member::factory()->create([
            'organisation_id' => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id' => $this->fullMemberType->id,
            'status' => $status,
            'fees_status' => $feesStatus,
            'membership_expires_at' => $expiresAt,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Group 1: Single Voter Assignment (ElectionVoterController@store)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_active_member_with_full_voting_rights_can_be_assigned_as_voter(): void
    {
        $user = $this->createUserWithRole('member');
        $this->createFormalMember($user, 'active', now()->addYear(), 'paid');
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.store', $this->election), [
                'user_id' => $user->id,
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('election_voters', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_active_member_with_expired_membership_cannot_be_assigned_as_voter(): void
    {
        $user = $this->createUserWithRole('member');
        $this->createFormalMember($user, 'active', now()->subDay(), 'paid');
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.store', $this->election), [
                'user_id' => $user->id,
            ]);
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('election_voters', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_active_member_with_unpaid_fees_cannot_be_assigned_as_voter(): void
    {
        $user = $this->createUserWithRole('member');
        $this->createFormalMember($user, 'active', now()->addYear(), 'unpaid');
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.store', $this->election), [
                'user_id' => $user->id,
            ]);
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('election_voters', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_staff_member_without_formal_membership_cannot_be_assigned_as_voter(): void
    {
        $user = $this->createUserWithRole('staff');
        // No formal member record created
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.store', $this->election), [
                'user_id' => $user->id,
            ]);
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('election_voters', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_without_formal_membership_cannot_be_assigned_as_voter(): void
    {
        $user = $this->createUserWithRole('guest');
        // No formal member record created
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.store', $this->election), [
                'user_id' => $user->id,
            ]);
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('election_voters', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Group 2: Bulk Voter Import (VoterImportService)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_bulk_import_accepts_only_active_members_with_full_voting_rights(): void
    {
        $validUser = $this->createUserWithRole('member');
        $this->createFormalMember($validUser, 'active', now()->addYear(), 'paid');
        
        $invalidUser = $this->createUserWithRole('staff');
        // No formal membership
        
        $csv = "email\n";
        $csv .= $validUser->email . "\n";
        $csv .= $invalidUser->email . "\n";
        
        $file = $this->createCsvFile($csv);
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.import.preview', $this->election), [
                'file' => $file,
            ], ['Accept' => 'application/json']);
        
        $response->assertOk();
        $response->assertJsonPath('stats.valid', 1);
        $response->assertJsonPath('stats.invalid', 1);
    }

    public function test_bulk_import_rejects_member_with_expired_membership(): void
    {
        $expiredUser = $this->createUserWithRole('member');
        $this->createFormalMember($expiredUser, 'active', now()->subDay(), 'paid');
        
        $csv = "email\n";
        $csv .= $expiredUser->email . "\n";
        
        $file = $this->createCsvFile($csv);
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.import.preview', $this->election), [
                'file' => $file,
            ], ['Accept' => 'application/json']);
        
        $response->assertOk();
        $response->assertJsonPath('stats.invalid', 1);
        $response->assertJsonPath('preview.0.errors.0', function ($error) {
            return str_contains($error, 'active formal member');
        });
    }

    public function test_bulk_import_rejects_member_with_unpaid_fees(): void
    {
        $unpaidUser = $this->createUserWithRole('member');
        $this->createFormalMember($unpaidUser, 'active', now()->addYear(), 'unpaid');
        
        $csv = "email\n";
        $csv .= $unpaidUser->email . "\n";
        
        $file = $this->createCsvFile($csv);
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.import.preview', $this->election), [
                'file' => $file,
            ], ['Accept' => 'application/json']);
        
        $response->assertOk();
        $response->assertJsonPath('stats.invalid', 1);
    }

    public function test_bulk_import_rejects_associate_member(): void
    {
        $associateType = MembershipType::factory()->associateMember()->create([
            'organisation_id' => $this->org->id,
        ]);
        
        $user = $this->createUserWithRole('member');
        $orgUser = OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        
        Member::factory()->create([
            'organisation_id' => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id' => $associateType->id,
            'fees_status' => 'paid',
            'status' => 'active',
        ]);
        
        $csv = "email\n";
        $csv .= $user->email . "\n";
        
        $file = $this->createCsvFile($csv);
        
        $response = $this->actingAs($this->owner)
            ->post(route('elections.voters.import.preview', $this->election), [
                'file' => $file,
            ], ['Accept' => 'application/json']);
        
        $response->assertOk();
        $response->assertJsonPath('stats.invalid', 1);
        $response->assertJsonPath('preview.0.errors.0', function ($error) {
            return str_contains($error, 'full voting rights');
        });
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Group 3: ElectionMembership::bulkAssignVoters
    // ══════════════════════════════════════════════════════════════════════════

    public function test_bulk_assign_voters_only_accepts_active_members(): void
    {
        $validUser = $this->createUserWithRole('member');
        $this->createFormalMember($validUser, 'active', now()->addYear(), 'paid');
        
        $invalidUser = $this->createUserWithRole('staff');
        
        $result = ElectionMembership::bulkAssignVoters(
            [$validUser->id, $invalidUser->id],
            $this->election->id,
            $this->owner->id
        );
        
        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['invalid']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Helper method
    // ══════════════════════════════════════════════════════════════════════════

    private function createCsvFile(string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'voter_test_') . '.csv';
        file_put_contents($path, $content);
        return new UploadedFile($path, 'voters.csv', 'text/csv', null, true);
    }
}
```

### Phase 2: Implement to Make Tests Green

Update the following files:

1. **`app/Services/VoterImportService.php`** - Add formal member check in `validateRow()`
2. **`app/Http/Controllers/ElectionVoterController.php`** - Add member check in `store()` and `bulkStore()`
3. **`app/Models/ElectionMembership.php`** - Replace `user_organisation_roles` check with `members` table check in `bulkAssignVoters()`

### Phase 3: Helper Method (Optional but Recommended)

Add to `app/Models/User.php`:

```php
public function isActiveFormalMember(Organisation $organisation): bool
{
    return Member::whereHas('organisationUser', fn($q) => $q->where('user_id', $this->id))
        ->where('organisation_id', $organisation->id)
        ->where('status', 'active')
        ->where(fn($q) => $q->whereNull('membership_expires_at')->orWhere('membership_expires_at', '>', now()))
        ->exists();
}

public function hasFullVotingRights(Organisation $organisation): bool
{
    $member = Member::whereHas('organisationUser', fn($q) => $q->where('user_id', $this->id))
        ->where('organisation_id', $organisation->id)
        ->first();
    
    return $member && $member->voting_rights === 'full';
}
```

## Execution Order (TDD)

```bash
# 1. Create the test file
# 2. Run tests - ALL SHOULD FAIL (Red)
php artisan test tests/Feature/Election/VoterEligibilityTest.php --no-coverage

# 3. Implement fixes in the 3 files
# 4. Run tests again - ALL SHOULD PASS (Green)
php artisan test tests/Feature/Election/VoterEligibilityTest.php --no-coverage

# 5. Run full test suite to ensure no regressions
php artisan test --no-coverage
```

## Expected Outcome

- 10+ new tests, all passing
- Only active formal members with full voting rights can be voters
- Staff, guests, expired members, associate members cannot be voters
- All existing voter assignment methods (single, bulk, import) enforce the same rule

Proceed with TDD implementation. Write tests first, confirm they fail, then implement the fixes.
```

---

## Quick Summary for You

This prompt will make Claude:

1. **Write comprehensive tests** (10+ scenarios) covering all voter assignment paths
2. **Run tests to confirm Red** (all fail initially)
3. **Implement fixes** in 3 files to enforce formal membership requirement
4. **Run tests to confirm Green** (all pass)
5. **Verify no regressions** with full test suite

Copy the entire prompt above into Claude CLI and it will execute the complete TDD cycle! 🚀
