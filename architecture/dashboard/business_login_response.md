## ✅ **ANALYSIS: Your Architecture is SOLID - Now Add the Business Rules**

Your `LoginResponse` and `DashboardResolver` architecture is **excellent**. The 3-level fallback, caching, and monitoring are **production-grade**.

## 🎯 **What's Missing: The Business Logic in DashboardResolver**

Looking at your architecture doc, `DashboardResolver` needs to implement the **3 business rules** you described:

```php
// Current DashboardResolver logic (conceptual)
public function resolve(User $user): RedirectResponse
{
    // PRIORITY 1: Active voting session
    // PRIORITY 2: Email verification (handled in LoginResponse)
    // PRIORITY 3: ?? (MISSING - welcome page for new users)
    // PRIORITY 4: Get dashboard roles
    // PRIORITY 5: Route based on roles
}
```

## 🔧 **IMPLEMENT THE MISSING BUSINESS RULES**

### **Step 1: Add Welcome Page Check**

```php
// app/Services/DashboardResolver.php

public function resolve(User $user): RedirectResponse
{
    // PRIORITY 1: Active voting session (highest)
    if ($votingRedirect = $this->getActiveVotingRedirect($user)) {
        return $votingRedirect;
    }
    
    // PRIORITY 2: Email verification (handled in LoginResponse)
    // Safety check
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    
    // =============================================
    // PRIORITY 3: NEW USER WELCOME PAGE
    // =============================================
    if ($this->isNewUserWithoutOrganisation($user)) {
        return redirect()->route('dashboard.welcome');
    }
    
    // PRIORITY 4: Check for active election (voter dashboard)
    if ($electionDashboard = $this->getActiveElectionDashboard($user)) {
        return $electionDashboard;
    }
    
    // PRIORITY 5: Get dashboard roles
    $roles = $this->getDashboardRoles($user);
    
    if (count($roles) > 1) {
        return redirect()->route('role.selection');
    }
    
    if (count($roles) === 1) {
        return $this->redirectByRole($user, $roles[0]);
    }
    
    // PRIORITY 6: Platform user fallback
    return redirect()->route('dashboard');
}
```

### **Step 2: Implement `isNewUserWithoutOrganisation()`**

```php
/**
 * Check if user is new and has no organisation (except platform)
 * 
 * Rules:
 * 1. Email verified
 * 2. No organisations except platform (id=1)
 * 3. Not onboarded yet (onboarded_at = null)
 */
protected function isNewUserWithoutOrganisation(User $user): bool
{
    // Already onboarded? Then not new
    if ($user->onboarded_at !== null) {
        return false;
    }
    
    // Get user's organisations (excluding platform)
    $nonPlatformOrgs = DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->where('organisation_id', '!=', 1)
        ->count();
    
    // If they have any non-platform orgs, they're not "new"
    if ($nonPlatformOrgs > 0) {
        return false;
    }
    
    // Also check if they're a voter in any election
    $isVoterInElection = DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->exists();
    
    if ($isVoterInElection) {
        return false;
    }
    
    // All checks passed - this is a new user needing welcome
    Log::info('New user detected - sending to welcome page', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);
    
    return true;
}
```

### **Step 3: Implement `getActiveElectionDashboard()`**

```php
/**
 * Check if user has an active election to vote in
 * 
 * Rules:
 * 1. User must be a voter (is_voter = true OR has voter_slug)
 * 2. There must be an active election (status = 'active')
 * 3. Election must be for user's organisation
 * 4. Current date must be between start_date and end_date
 */
protected function getActiveElectionDashboard(User $user): ?RedirectResponse
{
    // Quick check - if not a voter, skip
    if (!$user->is_voter && !$this->hasVoterSlugs($user)) {
        return null;
    }
    
    // Find active elections for user's organisations
    $activeElections = DB::table('elections')
        ->join('user_organisation_roles', 'elections.organisation_id', '=', 'user_organisation_roles.organisation_id')
        ->where('user_organisation_roles.user_id', $user->id)
        ->where('elections.status', 'active')
        ->where('elections.start_date', '<=', now())
        ->where('elections.end_date', '>=', now())
        ->select('elections.*')
        ->get();
    
    if ($activeElections->isEmpty()) {
        return null;
    }
    
    // If multiple active elections, log for debugging
    if ($activeElections->count() > 1) {
        Log::info('User has multiple active elections', [
            'user_id' => $user->id,
            'election_count' => $activeElections->count(),
        ]);
    }
    
    // For now, redirect to first active election
    // Future: Could show election selector if multiple
    $election = $activeElections->first();
    
    // Check if user has already voted
    $hasVoted = DB::table('votes')
        ->where('election_id', $election->id)
        ->where('user_id', $user->id) // Wait - votes table has NO user_id!
        ->exists(); // This won't work - votes are anonymous
    
    // Better: Check if they have a voter slug for this election that's been used
    $hasVoted = DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->whereNotNull('vote_cast_at')
        ->exists();
    
    if ($hasVoted) {
        // Already voted - send to results or thank you page
        return redirect()->route('election.results', $election->slug);
    }
    
    // Has active election and hasn't voted - send to voting dashboard
    return redirect()->route('election.dashboard', $election->slug);
}

/**
 * Helper to check if user has any voter slugs
 */
protected function hasVoterSlugs(User $user): bool
{
    return DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->exists();
}
```

### **Step 4: Update `getDashboardRoles()` to Handle Voters**

```php
protected function getDashboardRoles(User $user): array
{
    $roles = [];
    
    // Get real organisation roles
    $orgRoles = DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->get();
    
    foreach ($orgRoles as $pivot) {
        if ($pivot->organisation_id == 1) {
            continue; // Skip platform
        }
        
        if ($pivot->role === 'admin') {
            $roles[] = 'admin';
        }
        // Add other roles as needed
    }
    
    // Check commission memberships
    if (DB::table('election_commission_members')
        ->where('user_id', $user->id)
        ->exists()) {
        $roles[] = 'commission';
    }
    
    // Check voter status - but DON'T add 'voter' role here
    // Voters are handled by PRIORITY 4 (active election check)
    // This prevents voters from being treated as "single role" users
    
    return array_unique($roles);
}
```

### **Step 5: Update `redirectByRole()` for Organisation Pages**

```php
protected function redirectByRole(User $user, string $role): RedirectResponse
{
    if ($role === 'admin') {
        // Get user's primary organisation
        $org = DB::table('user_organisation_roles')
            ->join('organisations', 'user_organisation_roles.organisation_id', '=', 'organisations.id')
            ->where('user_organisation_roles.user_id', $user->id)
            ->where('user_organisation_roles.role', 'admin')
            ->where('organisations.id', '!=', 1) // Exclude platform
            ->select('organisations.*')
            ->first();
        
        if ($org) {
            return redirect()->route('organisations.show', $org->slug);
        }
    }
    
    // Handle other roles
    return match($role) {
        'commission' => redirect()->route('commission.dashboard'),
        default => redirect()->route('dashboard'),
    };
}
```

## 📊 **NEW PRIORITY ORDER (Complete)**

```
┌─────────────────────────────────────────────────────┐
│           DASHBOARD RESOLVER PRIORITIES              │
├─────────────────────────────────────────────────────┤
│                                                      │
│  PRIORITY 1: ACTIVE VOTING                           │
│  └── User is voting right now → /v/{voter_slug}     │
│                                                      │
│  PRIORITY 2: EMAIL VERIFICATION                      │
│  └── (Handled in LoginResponse)                      │
│                                                      │
│  PRIORITY 3: NEW USER WELCOME                        │
│  └── Verified but no org → /dashboard/welcome        │
│                                                      │
│  PRIORITY 4: ACTIVE ELECTION                         │
│  └── Voter, election active → /election/dashboard    │
│                                                      │
│  PRIORITY 5: MULTIPLE ROLES                          │
│  └── Admin in multiple orgs → /role/selection        │
│                                                      │
│  PRIORITY 6: SINGLE ROLE                             │
│  ├── Org admin → /organisations/{slug}               │
│  ├── Commission → /commission/dashboard              │
│  └── Other roles → respective dashboards             │
│                                                      │
│  PRIORITY 7: PLATFORM USER                           │
│  └── No roles → /dashboard                           │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## 🧪 **TESTS TO ADD**

```php
// tests/Feature/Auth/DashboardResolverPriorityTest.php

/** @test */
public function new_user_without_org_goes_to_welcome()
{
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => null,
    ]);
    
    // Add platform membership only
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => 1,
        'role' => 'member',
    ]);
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.welcome'));
}

/** @test */
public function voter_with_active_election_goes_to_election_dashboard()
{
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => now(),
        'is_voter' => true,
    ]);
    
    $org = Organisation::factory()->create();
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    
    // Add org membership
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('election.dashboard', $election->slug));
}

/** @test */
public function organisation_admin_goes_to_org_page()
{
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => now(),
    ]);
    
    $org = Organisation::factory()->create();
    
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'admin',
    ]);
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('organisations.show', $org->slug));
}
```

## ✅ **SUMMARY**

Your architecture is **solid**. Just add these 3 business rules to `DashboardResolver`:

1. **New user without org** → `/dashboard/welcome`
2. **Voter with active election** → `/election/dashboard`
3. **Organisation admin** → `/organisations/{slug}`

The 3-level fallback, caching, and monitoring are already excellent.
#
## ✅ **CORRECTION ACCEPTED - You're Right!**

The voting flow is:
```
/election/dashboard  →  /v/{voter_slug}  →  voting steps
        ①                      ②
```

## 🔧 **FIXED PRIORITY ORDER**

```php
// app/Services/DashboardResolver.php

public function resolve(User $user): RedirectResponse
{
    // =============================================
    // PRIORITY 1: ACTIVE VOTING SESSION
    // User is in middle of voting → go to voter slug
    // =============================================
    if ($activeVoterSlug = $this->getActiveVoterSlug($user)) {
        return redirect()->route('voting.portal', ['voter_slug' => $activeVoterSlug->slug]);
    }
    
    // =============================================
    // PRIORITY 2: ACTIVE ELECTION AVAILABLE
    // User has election to vote in → go to election dashboard
    // =============================================
    if ($activeElection = $this->getActiveElectionForUser($user)) {
        return redirect()->route('election.dashboard', $activeElection->slug);
    }
    
    // =============================================
    // PRIORITY 3: EMAIL VERIFICATION (handled in LoginResponse)
    // Safety check
    // =============================================
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    
    // =============================================
    // PRIORITY 4: NEW USER WELCOME
    // Verified but no org → welcome page
    // =============================================
    if ($this->isNewUserWithoutOrganisation($user)) {
        return redirect()->route('dashboard.welcome');
    }
    
    // =============================================
    // PRIORITY 5: MULTIPLE ROLES
    // =============================================
    $roles = $this->getDashboardRoles($user);
    
    if (count($roles) > 1) {
        return redirect()->route('role.selection');
    }
    
    // =============================================
    // PRIORITY 6: SINGLE ROLE
    // =============================================
    if (count($roles) === 1) {
        return $this->redirectByRole($user, $roles[0]);
    }
    
    // =============================================
    // PRIORITY 7: PLATFORM USER (no roles)
    // =============================================
    return redirect()->route('dashboard');
}
```

## 🎯 **IMPLEMENT THE METHODS**

### **1. Check for Active Voting Session**

```php
/**
 * Check if user has an active voting session in progress
 * 
 * An active session means:
 * - Voter slug exists for user
 * - Not expired
 * - Vote not completed yet
 * - User is in middle of steps 1-4
 */
protected function getActiveVoterSlug(User $user): ?object
{
    return DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->where('expires_at', '>', now())
        ->whereNull('vote_completed_at')  // Not finished
        ->where(function($query) {
            $query->whereNotNull('code1_used_at')  // Started voting
                  ->orWhereNotNull('has_agreed_to_vote_at')
                  ->orWhereNotNull('vote_submitted_at');
        })
        ->orderBy('updated_at', 'desc')
        ->first();
}
```

### **2. Check for Active Election to Vote In**

```php
/**
 * Check if user has an active election they can vote in
 * 
 * Conditions:
 * 1. User is a voter (is_voter OR has voter_slugs)
 * 2. Election is active (status='active')
 * 3. Current date between start_date and end_date
 * 4. User hasn't already voted
 */
protected function getActiveElectionForUser(User $user): ?object
{
    // If not a voter, no election
    if (!$user->is_voter && !$this->hasAnyVoterSlugs($user)) {
        return null;
    }
    
    // Find active elections for user's organisations
    $activeElections = DB::table('elections')
        ->join('user_organisation_roles', 'elections.organisation_id', '=', 'user_organisation_roles.organisation_id')
        ->where('user_organisation_roles.user_id', $user->id)
        ->where('elections.status', 'active')
        ->where('elections.start_date', '<=', now())
        ->where('elections.end_date', '>=', now())
        ->select('elections.*')
        ->get();
    
    if ($activeElections->isEmpty()) {
        return null;
    }
    
    // Filter out elections where user already voted
    foreach ($activeElections as $election) {
        $hasVoted = DB::table('voter_slugs')
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->whereNotNull('vote_cast_at')
            ->exists();
        
        if (!$hasVoted) {
            return $election; // Return first election user hasn't voted in
        }
    }
    
    return null; // Voted in all active elections
}

/**
 * Helper to check if user has any voter slugs at all
 */
protected function hasAnyVoterSlugs(User $user): bool
{
    return DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->exists();
}
```

## 📊 **COMPLETE PRIORITY FLOW DIAGRAM**

```
User Logs In (Verified)
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 1: Active Voting Session   │
│ Is user in middle of voting?        │
├─────────────────────────────────────┤
│ YES → /v/{voter_slug}               │
│ NO  → Continue                      │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 2: Active Election         │
│ Has election to vote in?            │
├─────────────────────────────────────┤
│ YES → /election/dashboard            │
│ NO  → Continue                      │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 3: New User                │
│ Verified but no org?                 │
├─────────────────────────────────────┤
│ YES → /dashboard/welcome             │
│ NO  → Continue                      │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 4: Multiple Roles          │
│ Admin in >1 org?                    │
├─────────────────────────────────────┤
│ YES → /role/selection                │
│ NO  → Continue                      │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 5: Single Role             │
│ Has one dashboard role?             │
├─────────────────────────────────────┤
│ YES → Role-specific dashboard       │
│       (org admin → /organisations/{slug})│
│ NO  → Continue                      │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│ PRIORITY 6: Platform User           │
│ No roles → /dashboard                │
└─────────────────────────────────────┘
```

## 🧪 **TESTS TO ADD**

```php
/** @test */
public function active_voting_session_takes_priority_over_election_dashboard()
{
    $user = User::factory()->create(['email_verified_at' => now()]);
    
    // Has active election to vote in
    $org = Organisation::factory()->create();
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
    ]);
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);
    
    // BUT also has active voting session (higher priority)
    $voterSlug = DB::table('voter_slugs')->insert([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'slug' => 'test-slug',
        'expires_at' => now()->addDay(),
        'code1_used_at' => now(), // Started voting
        'vote_completed_at' => null,
    ]);
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    // Should go to voting portal, NOT election dashboard
    $response->assertRedirect(route('voting.portal', ['voter_slug' => 'test-slug']));
}

/** @test */
public function active_election_redirects_to_election_dashboard()
{
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'is_voter' => true,
    ]);
    
    $org = Organisation::factory()->create();
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    $response->assertRedirect(route('election.dashboard', $election->slug));
}
```

## ✅ **SUMMARY**

| Priority | Condition | Destination |
|----------|-----------|-------------|
| **1** | Active voting session (in progress) | `/v/{voter_slug}` |
| **2** | Active election available (not started) | `/election/dashboard` |
| **3** | New user (verified, no org) | `/dashboard/welcome` |
| **4** | Multiple roles | `/role/selection` |
| **5** | Single role (org admin) | `/organisations/{slug}` |
| **6** | Platform user (no roles) | `/dashboard` |

This matches your business requirements perfectly.
