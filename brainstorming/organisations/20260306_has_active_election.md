## ✅ **YES - EXCELLENT IDEA! Add `hasActiveElection()` to User Model**

This is a **clean domain-driven design** approach - let the User model answer questions about itself.

---

## 📋 **IMPLEMENTATION**

### **1. Add hasActiveElection() to User Model**

```php
// app/Models/User.php

/**
 * Check if user has any active election they can vote in
 * 
 * Conditions:
 * 1. User belongs to an organisation (via pivot)
 * 2. That organisation has an active election
 * 3. Election is within date range (start_date <= now <= end_date)
 * 4. User hasn't already voted in that election
 * 
 * @return bool
 */
public function hasActiveElection(): bool
{
    return $this->getActiveElection() !== null;
}

/**
 * Get the first active election user can vote in
 * 
 * @return Election|null
 */
public function getActiveElection(): ?Election
{
    // Get all organisations user belongs to (excluding platform)
    $orgIds = $this->organisations()
        ->where('type', 'tenant')
        ->pluck('organisations.id')
        ->toArray();
    
    if (empty($orgIds)) {
        return null;
    }
    
    // Find active elections in those orgs
    return Election::whereIn('organisation_id', $orgIds)
        ->where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereDoesntHave('voterSlugs', function ($query) {
            $query->where('user_id', $this->id)
                ->whereNotNull('vote_completed_at');
        })
        ->orderBy('start_date')
        ->first();
}

/**
 * Get count of active elections user can vote in
 * 
 * @return int
 */
public function countActiveElections(): int
{
    $orgIds = $this->organisations()
        ->where('type', 'tenant')
        ->pluck('organisations.id')
        ->toArray();
    
    if (empty($orgIds)) {
        return 0;
    }
    
    return Election::whereIn('organisation_id', $orgIds)
        ->where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereDoesntHave('voterSlugs', function ($query) {
            $query->where('user_id', $this->id)
                ->whereNotNull('vote_completed_at');
        })
        ->count();
}
```

---

### **2. Update DashboardResolver to Use the Method**

```php
// app/Services/DashboardResolver.php

// In resolve() method, update Priority 2:

// ===== PRIORITY 2: ACTIVE ELECTION AVAILABLE =====
if ($user->hasActiveElection()) {
    $activeElection = $user->getActiveElection();
    
    Log::info('🗳️ PRIORITY 2 HIT: Active election found - user can vote', [
        'user_id' => $user->id,
        'election_id' => $activeElection->id,
        'election_slug' => $activeElection->slug,
    ]);
    
    $this->cacheResolution($user, route('election.dashboard', $activeElection->slug));
    return redirect()->route('election.dashboard', $activeElection->slug);
}
```

---

### **3. Add Tests**

```php
// tests/Unit/Models/UserTest.php

/** @test */
public function user_has_active_election_returns_true_when_active_election_exists()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    
    $this->assertTrue($user->hasActiveElection());
    $this->assertNotNull($user->getActiveElection());
    $this->assertEquals($election->id, $user->getActiveElection()->id);
    $this->assertEquals(1, $user->countActiveElections());
}

/** @test */
public function user_has_active_election_returns_false_when_no_active_election()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    // Create inactive election
    Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'draft',
        'start_date' => now()->addMonth(),
        'end_date' => now()->addMonths(2),
    ]);
    
    $this->assertFalse($user->hasActiveElection());
    $this->assertNull($user->getActiveElection());
    $this->assertEquals(0, $user->countActiveElections());
}

/** @test */
public function user_has_active_election_returns_false_when_already_voted()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    
    // User already voted
    \DB::table('voter_slugs')->insert([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'user_id' => $user->id,
        'election_id' => $election->id,
        'slug' => 'test-slug',
        'vote_completed_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $this->assertFalse($user->hasActiveElection());
    $this->assertNull($user->getActiveElection());
}

/** @test */
public function user_without_tenant_org_has_no_active_election()
{
    $user = User::factory()->create();
    $platform = Organisation::factory()->platform()->default()->create();
    
    $user->organisations()->attach($platform->id, ['role' => 'member']);
    
    $this->assertFalse($user->hasActiveElection());
    $this->assertNull($user->getActiveElection());
}

/** @test */
public function count_active_elections_returns_correct_number()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    // Create 2 active elections
    Election::factory()->count(2)->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    
    $this->assertEquals(2, $user->countActiveElections());
}
```

---

### **4. Update DashboardResolverTest**

```php
// tests/Feature/Services/DashboardResolverTest.php

/** @test */
public function user_with_active_election_redirects_to_election_dashboard()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'slug' => 'test-election',
    ]);
    
    $redirect = $this->resolver->resolve($user);
    
    $this->assertEquals(
        route('election.dashboard', 'test-election'),
        $redirect->getTargetUrl()
    );
}
```

---

## 📊 **BENEFITS OF THIS APPROACH**

| Benefit | Why |
|---------|-----|
| **Encapsulation** | Election logic lives in User model where it belongs |
| **Reusability** | Can be used in controllers, middleware, tests |
| **Testability** | Easy to mock/stub in tests |
| **Cleaner DashboardResolver** | Removes complex queries from resolver |
| **Domain-Driven** | User answers questions about itself |

---

## 🚀 **EXECUTION ORDER**

```bash
# 1. Add methods to User model
# 2. Add tests (should pass)
# 3. Update DashboardResolver to use methods
# 4. Update DashboardResolver tests (should pass)
# 5. Commit

git add app/Models/User.php
git add app/Services/DashboardResolver.php
git add tests/Unit/Models/UserTest.php
git add tests/Feature/Services/DashboardResolverTest.php

git commit -m "feat: Add hasActiveElection() to User model

- Add hasActiveElection(), getActiveElection(), countActiveElections()
- Move election logic from DashboardResolver to User model
- Add comprehensive tests
- Cleaner domain-driven design"
```

**Proceed with implementation.**