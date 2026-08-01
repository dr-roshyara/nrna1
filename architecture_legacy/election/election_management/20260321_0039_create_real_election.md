## 🧠 **Brainstorming: Real Election Creation Flow**

Let's map out the complete process for creating and managing a real election in your system.

---

## 📋 **Current State Analysis**

### **What We Have**
| Component | Status |
|-----------|--------|
| Election Model | ✅ Exists with `type` field (`real`/`demo`) |
| Election Officer System | ✅ Complete (appointment, invitations, acceptance) |
| Election Management Dashboard | ✅ Complete (publish results, voting control) |
| Voter Management | ✅ Complete (assign voters per election, approve/suspend) |
| Election Membership | ✅ Complete (tracks voters per election with status) |

### **What's Missing**
| Feature | Status |
|---------|--------|
| Create Election Form | ❌ Not yet built |
| Election Settings (dates, posts, candidates) | ❌ Not yet built |
| Election Status Workflow | ❌ Draft → Active → Completed → Archived |
| Election Results Calculation | ❌ Not yet built |
| Voter Access Control by Election Dates | ❌ Not yet built |

---

## 🎯 **Real Election Creation Flow**

### **Phase 1: Election Setup (Chief/Deputy)**

```
1. Navigate to Organisation Dashboard
   ↓
2. Click "Create New Election" → /organisations/{slug}/elections/create
   ↓
3. Fill Election Details:
   - Name (required)
   - Description (optional)
   - Type: "Real Election" (locked, not demo)
   - Start Date (when voting begins)
   - End Date (when voting ends)
   - Positions/Posts to be elected
   ↓
4. Create Posts (positions to be elected):
   - Post Title (e.g., "President")
   - Description
   - Max candidates per voter (e.g., 1 for single-seat)
   - Add multiple posts
   ↓
5. Add Candidates to Posts:
   - Select Post
   - Add candidate name, bio, photo
   - Can be done after election creation
   ↓
6. Review & Create
   ↓
7. Election Status: "draft"
```

---

### **Phase 2: Voter Assignment**

```
1. Chief/Deputy goes to Election Management
   ↓
2. Click "Manage Voters"
   ↓
3. Bulk assign voters from organisation members:
   - Search members
   - Select multiple
   - Assign to election
   ↓
4. Voters receive invitation email (if not already in system)
   ↓
5. Election Membership status: "invited"
```

---

### **Phase 3: Voter Approval Process**

```
1. Chief/Deputy reviews voters in Election Voter List
   ↓
2. Approve voters who are eligible
   ↓
3. Election Membership status: "active"
   ↓
4. Optional: Suspend voters who violate rules
   ↓
5. Election Membership status: "inactive"
```

---

### **Phase 4: Election Activation**

```
1. Chief confirms all settings are ready
   ↓
2. Clicks "Start Election" → Status changes from "draft" to "active"
   ↓
3. Voting period begins (respects start_date)
   ↓
4. Voters can now access voting page
   ↓
5. Election Management Dashboard shows real-time stats
```

---

### **Phase 5: Voting Period**

```
1. Voters log in → see active elections they're eligible for
   ↓
2. Navigate to election → view candidates by post
   ↓
3. Cast votes for each post
   ↓
4. Votes stored in votes table with election_id
   ↓
5. Election Membership tracks has_voted = true
```

---

### **Phase 6: Election Closure & Results**

```
1. Chief clicks "End Election" (or automatic at end_date)
   ↓
2. Election status: "completed"
   ↓
3. No more votes accepted
   ↓
4. Chief calculates results:
   - Count votes per post
   - Determine winners
   ↓
5. Chief clicks "Publish Results"
   ↓
6. Results visible to all organisation members
```

---

## 🏗️ **Database Schema Considerations**

### **Election Table (already exists)**
```php
Schema::create('elections', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('type')->default('real'); // 'real' or 'demo'
    $table->string('status')->default('draft'); // draft, active, completed, archived
    $table->timestamp('start_date')->nullable();
    $table->timestamp('end_date')->nullable();
    $table->boolean('results_published')->default(false);
    $table->json('settings')->nullable(); // voting rules, anonymous mode, etc.
    $table->foreignUuid('organisation_id')->constrained();
    $table->timestamps();
    $table->softDeletes();
});
```

### **Posts Table (positions to be elected)**
```php
Schema::create('posts', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('election_id')->constrained()->cascadeOnDelete();
    $table->string('title'); // e.g., "President"
    $table->text('description')->nullable();
    $table->integer('max_votes')->default(1); // how many candidates voter can select
    $table->integer('order')->default(0); // display order
    $table->timestamps();
});
```

### **Candidates Table**
```php
Schema::create('candidates', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('user_id')->nullable()->constrained(); // if candidate is existing user
    $table->string('name');
    $table->text('bio')->nullable();
    $table->string('photo_url')->nullable();
    $table->string('party')->nullable();
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

### **Votes Table (already exists? Check)**
```php
Schema::create('votes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('election_id')->constrained();
    $table->foreignUuid('user_id')->constrained();
    $table->foreignUuid('post_id')->constrained();
    $table->foreignUuid('candidate_id')->constrained();
    $table->timestamps();
});
```

---

## 🎨 **UI Flow - Election Creation**

### **Step 1: Create Election Form**
```
┌─────────────────────────────────────────────────┐
│  Create New Election                            │
├─────────────────────────────────────────────────┤
│  Election Name *                                 │
│  ┌─────────────────────────────────────────┐    │
│  │ General Election 2026                   │    │
│  └─────────────────────────────────────────┘    │
│                                                  │
│  Description                                     │
│  ┌─────────────────────────────────────────┐    │
│  │ Election for organisation leadership... │    │
│  └─────────────────────────────────────────┘    │
│                                                  │
│  Start Date *           End Date *               │
│  ┌──────────┐          ┌──────────┐             │
│  │ 2026-03-21│          │ 2026-03-28│             │
│  └──────────┘          └──────────┘             │
│                                                  │
│  ┌─────────────────────────────────────────┐    │
│  │ ☑ Enable anonymous voting                │    │
│  │ ☐ Allow write-in candidates              │    │
│  └─────────────────────────────────────────┘    │
│                                                  │
│  [ Cancel ]                    [ Next: Add Posts ] │
└─────────────────────────────────────────────────┘
```

### **Step 2: Add Posts**
```
┌─────────────────────────────────────────────────┐
│  Election: General Election 2026                │
│  Add Positions to be Elected                    │
├─────────────────────────────────────────────────┤
│                                                  │
│  Position 1                                      │
│  ┌─────────────────────────────────────────┐    │
│  │ President                               │    │
│  └─────────────────────────────────────────┘    │
│  Description                                    │
│  ┌─────────────────────────────────────────┐    │
│  │ Leading the organisation...             │    │
│  └─────────────────────────────────────────┘    │
│  Max votes per voter: [ 1 ]                    │
│                                                  │
│  [+ Add Position]                               │
│                                                  │
│  Position 2                                      │
│  ┌─────────────────────────────────────────┐    │
│  │ Vice President                          │    │
│  └─────────────────────────────────────────┘    │
│                                                  │
│  [ Back ]                    [ Next: Add Candidates ] │
└─────────────────────────────────────────────────┘
```

### **Step 3: Add Candidates**
```
┌─────────────────────────────────────────────────┐
│  Add Candidates                                 │
├─────────────────────────────────────────────────┤
│  Position: President                            │
│                                                  │
│  Candidate 1                                     │
│  ┌─────────────────────────────────────────┐    │
│  │ John Doe (Select from members or type)  │    │
│  └─────────────────────────────────────────┘    │
│  Bio: ┌─────────────────────────────────────┐   │
│  │ Experienced leader...                   │   │
│  └─────────────────────────────────────────┘   │
│                                                  │
│  [+ Add Candidate]                              │
│                                                  │
│  Position: Vice President                       │
│  Candidate 1                                     │
│  ┌─────────────────────────────────────────┐    │
│  │ Jane Smith                              │    │
│  └─────────────────────────────────────────┘    │
│                                                  │
│  [ Back ]                    [ Create Election ] │
└─────────────────────────────────────────────────┘
```

---

## 🔄 **Election Status Workflow**

```
┌─────────────────────────────────────────────────────────────┐
│                    Election Lifecycle                       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────┐     ┌─────────┐     ┌───────────┐            │
│  │  DRAFT  │ ──→ │ ACTIVE  │ ──→ │ COMPLETED │            │
│  └─────────┘     └─────────┘     └───────────┘            │
│       │              │                  │                   │
│       │              │                  ↓                   │
│       │              │            ┌───────────┐            │
│       │              │            │ ARCHIVED  │            │
│       │              │            └───────────┘            │
│       │              │                                      │
│       ↓              ↓                                      │
│  Can edit      Can't edit           Results can be         │
│  Can add      Voting active         published              │
│  posts/candidates                                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🧪 **TDD Approach for Real Election Creation**

### **Test Suite: `tests/Feature/Election/ElectionCreationTest.php`**

```php
// Test cases to write first:

test_chief_can_create_election()
test_deputy_can_create_election()
test_commissioner_cannot_create_election()
test_election_requires_name()
test_election_requires_start_date()
test_election_requires_end_date()
test_start_date_must_be_before_end_date()
test_election_defaults_to_draft_status()
test_can_add_posts_to_election()
test_can_add_candidates_to_posts()
test_election_cannot_be_activated_without_voters()
test_chief_can_activate_election()
test_activated_election_cannot_be_edited()
```

---

## 🚀 **Implementation Priority**

| Priority | Feature | Time Estimate |
|----------|---------|---------------|
| **1** | Election Creation Form (basic) | 3-4 hours |
| **2** | Posts & Candidates Management | 4-5 hours |
| **3** | Election Status Workflow (activate/close) | 2-3 hours |
| **4** | Voter Assignment UI (link existing VoterList) | 1-2 hours |
| **5** | Results Calculation & Publishing | 3-4 hours |

---

## ❓ **Questions to Consider**

1. **Should candidates be existing users or can be added as text?**
   - Option A: Only existing organisation members
   - Option B: Free text entry (for write-ins)

2. **How to handle ties?**
   - Manual override by chief?
   - Tie-breaking rules?

3. **Should voting be anonymous?**
   - Current votes table has `user_id` (not anonymous)
   - Could add `anonymous` flag to election

4. **What about multiple rounds?**
   - Simple: single round, most votes wins
   - Complex: runoff if no majority

5. **Should voters see real-time results during voting?**
   - Option A: Hidden until voting ends
   - Option B: Visible live

---

**What aspect of real election creation would you like to build first?** 🚀
## ✅ **Great Decisions! Let's Document the Requirements**

Based on your choices:

| Decision | Implementation |
|----------|----------------|
| **Candidates** | Only existing organisation members |
| **Anonymity** | No `user_id` in votes table - completely anonymous |
| **Voting System** | Single round, most votes wins |
| **Results Visibility** | Hidden until chief publishes results |

---

## 🏗️ **Revised Database Schema**

### **Votes Table (Anonymous)**
```php
Schema::create('votes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('election_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('candidate_id')->constrained()->cascadeOnDelete();
    $table->string('voter_session_id')->nullable(); // For preventing double voting
    $table->timestamps();
    
    // Unique constraint: one vote per candidate per voter session
    $table->unique(['election_id', 'post_id', 'voter_session_id'], 'unique_vote_per_voter');
});
```

### **Voter Session Tracking (For Anonymous Voting)**
```php
Schema::create('voter_sessions', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('election_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('voter_id')->constrained('users')->cascadeOnDelete(); // Link to user for eligibility
    $table->string('session_token')->unique(); // Random token for voting
    $table->boolean('has_voted')->default(false);
    $table->timestamp('voted_at')->nullable();
    $table->timestamps();
});
```

---

## 📋 **Voting Flow with Anonymity**

```
1. Voter logs in (authenticated)
   ↓
2. Checks eligibility via ElectionMembership (status = 'active')
   ↓
3. If eligible, generate unique session token
   ↓
4. Store token in voter_sessions table (links voter to election)
   ↓
5. Voter navigates to voting page
   ↓
6. For each post, selects candidate
   ↓
7. On submit, store votes using session_token (NOT user_id)
   ↓
8. Mark voter_sessions.has_voted = true
   ↓
9. No link between vote and user identity in votes table
```

---

## 🎯 **TDD-First Approach for Real Election Creation**

Shall I write the tests for the **Election Creation** feature first, following the same TDD pattern we've used successfully? This would include:

1. **ElectionCreationTest.php** - 10-12 tests for creating elections
2. **ElectionPostsTest.php** - Tests for adding posts to elections
3. **ElectionCandidatesTest.php** - Tests for adding candidates
4. **ElectionActivationTest.php** - Tests for status workflow
5. **AnonymousVotingTest.php** - Tests for vote storage without user_id

**Which part would you like to start with?** 🚀 
## 🚀 **Part 1: Election Creation - TDD First**

Let's start with the **Election Creation** feature. We'll follow the same TDD pattern that worked so well for the other features.

---

## 📝 **Step 1: Write Tests First**

### **Test File: `tests/Feature/Election/ElectionCreationTest.php`**

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionCreationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $chief;
    private User $deputy;
    private User $commissioner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->chief = $this->makeOfficer('chief', 'active');
        $this->deputy = $this->makeOfficer('deputy', 'active');
        $this->commissioner = $this->makeOfficer('commissioner', 'active');
    }

    // =========================================================================
    // Permission Tests
    // =========================================================================

    /** @test */
    public function chief_can_create_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'description' => 'Election for organisation leadership',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
            'type' => 'real',
            'status' => 'draft',
        ]);
    }

    /** @test */
    public function deputy_can_create_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function commissioner_cannot_create_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    // =========================================================================
    // Validation Tests
    // =========================================================================

    /** @test */
    public function election_requires_name(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function election_requires_start_date(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertSessionHasErrors('start_date');
    }

    /** @test */
    public function election_requires_end_date(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertSessionHasErrors('end_date');
    }

    /** @test */
    public function start_date_must_be_before_end_date(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(14)->toDateString(),
                'end_date' => now()->addDays(7)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertSessionHasErrors('end_date');
    }

    /** @test */
    public function election_defaults_to_draft_status(): void
    {
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $election = Election::where('name', 'General Election 2026')->first();
        $this->assertEquals('draft', $election->status);
    }

    /** @test */
    public function election_type_defaults_to_real_if_not_provided(): void
    {
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
            ]);

        $election = Election::where('name', 'General Election 2026')->first();
        $this->assertEquals('real', $election->type);
    }

    /** @test */
    public function cannot_create_demo_election_as_real(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Demo Election',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'demo',
            ]);

        $response->assertForbidden(); // Only demo elections via demo controller
    }

    /** @test */
    public function election_name_must_be_unique_within_organisation(): void
    {
        // Create first election
        Election::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
            'type' => 'real',
            'status' => 'draft',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
        ]);

        // Try to create duplicate
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $response->assertSessionHasErrors('name');
    }

    // =========================================================================
    // Cross-Organisation Tests
    // =========================================================================

    /** @test */
    public function officer_from_different_org_cannot_create_election(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $outsiderChief = $this->makeOfficerInOrg('chief', 'active', $otherOrg);

        $response = $this->actingAs($outsiderChief)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'General Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'type' => 'real',
            ]);

        $this->assertContains($response->status(), [302, 403, 404]);
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function makeOfficer(string $role, string $status): User
    {
        return $this->makeOfficerInOrg($role, $status, $this->org);
    }

    private function makeOfficerInOrg(string $role, string $status, Organisation $org): User
    {
        $user = User::factory()->create([
            'organisation_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $org->id,
            'user_id' => $user->id,
            'role' => $role,
            'status' => $status,
            'appointed_by' => $user->id,
            'appointed_at' => now(),
            'accepted_at' => $status === 'active' ? now() : null,
        ]);
        return $user;
    }
}
```

---

## 🚀 **Step 2: Run Tests (They Will FAIL)**

```bash
php artisan test --filter=ElectionCreationTest
```

Expected: **0/12 passing** (routes and controller don't exist yet)

---

## 📋 **What's Next?**

After confirming the tests fail, we'll implement:

1. **Route** for election creation
2. **Controller** with `store()` method
3. **Policy** updates for `create` permission
4. **Validation** rules

**Ready to proceed?** 🚀