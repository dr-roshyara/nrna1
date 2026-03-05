# User Model Relationships Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement the relationship model from architecture/election/user_model/relationship_model.md while maintaining compatibility with existing codebase.

**Architecture:** Adapt the architecture document's relationships to the existing schema without breaking current functionality. Focus on adding missing relationships, fixing incorrect ones, and maintaining backward compatibility.

**Tech Stack:** Laravel 9, Eloquent ORM, PHPUnit

---

## Current State Analysis

The architecture document proposes relationships that assume a different database schema (with a Voter model). Our current system uses:
- `User` → `Code` (1:1, should be 1:many)
- `User` → `VoterRegistration` (1:many, tracks election registration)
- `User` → `Candidacy` (1:many, but with incorrect foreign key)
- No direct `User` → `Vote` relationship (votes are anonymous via `vote_hash`)

We'll implement compatible relationships without schema changes.

---

### Task 1: Analyze and Plan Relationships

**Files:**
- Read: `app/Models/User.php`
- Read: `app/Models/Code.php`
- Read: `app/Models/Candidacy.php`
- Read: `app/Models/Vote.php`
- Read: `app/Models/Result.php`

**Step 1: Document current relationships**

Create a mapping of current vs. desired relationships:

```php
// Current User relationships:
// - organisationRoles() (belongsToMany with pivot)
// - voter() (hasOne with wrong model)
// - candidacies() (hasMany with wrong foreign key)
// - candidacy() (hasOne without where clause)
// - code() (hasOne, should be hasMany)
// - voterSlugs() (hasMany - correct)
// - voterRegistrations() (hasMany - correct)
// - votes() (MISSING)
// - results() (MISSING)

// Architecture document wants:
// - organisations() (alias for organisationRoles)
// - voters() (hasMany - can't implement without Voter model)
// - voter() (hasOne with where clause)
// - candidacies() (hasMany with correct foreign key)
// - candidacy() (hasOne with where clause)
// - codes() (hasMany)
// - voterSlugs() (hasMany)
// - votes() (hasManyThrough Vote via Code)
// - results() (hasManyThrough Result via Vote)
```

**Step 2: Create compatibility plan**

We'll:
1. Add `organisations()` alias for `organisationRoles()`
2. Fix `candidacies()` foreign key
3. Add where clause to `candidacy()`
4. Change `code()` to `codes()` (hasMany) and keep `code()` as helper
5. Implement `votes()` via `voterRegistrations()` and `Code` indirect relationship
6. Implement `results()` via `votes()` relationship

**Step 3: Verify no breaking changes**

Check all usages of current relationships in codebase:
```bash
grep -r "->organisationRoles\|->candidacy\|->code\|->voter" app/ --include="*.php"
```

---

### Task 2: Implement User Model Relationships

**Files:**
- Modify: `app/Models/User.php`

**Step 1: Add organisations() alias**

```php
// In User.php, add after organisationRoles() method:
public function organisations()
{
    return $this->organisationRoles();
}
```

**Step 2: Fix candidacies() relationship**

Current implementation uses wrong foreign key:
```php
// Current (line 214):
return $this->hasMany(\App\Models\Candidacy::class, 'post_id', 'post_id');

// Should be:
return $this->hasMany(Candidacy::class, 'user_id', 'id');
```

**Step 3: Add where clause to candidacy()**

```php
// Current (line 221):
return $this->hasone(candidacy::class);

// Should be:
return $this->hasOne(Candidacy::class, 'user_id', 'id')->where('status', 'approved');
```

**Step 4: Change code() to codes() and add helper**

```php
// Replace current code() method (line 256):
public function codes()
{
    return $this->hasMany(Code::class);
}

// Keep code() as helper method for backward compatibility:
public function code()
{
    return $this->hasOne(Code::class);
}
```

**Step 5: Implement votes() relationship**

Since Vote doesn't have user_id, we need to go through Code:
```php
public function votes()
{
    // Get votes where code's user_id matches this user
    return $this->hasManyThrough(
        Vote::class,
        Code::class,
        'user_id', // Foreign key on Code table
        'id',      // Foreign key on Vote table (vote_hash relationship)
        'id',      // Local key on User table
        'id'       // Local key on Code table
    )->where('codes.user_id', $this->id);
}
```

**Step 6: Implement results() relationship**

```php
public function results()
{
    return $this->hasManyThrough(
        Result::class,
        Vote::class,
        'id',       // Foreign key on Vote table
        'vote_id',  // Foreign key on Result table
        'id',       // Local key on User table
        'id'        // Local key on Vote table
    );
}
```

**Step 7: Update type hints and imports**

Add proper imports at top of User.php:
```php
use App\Models\Candidacy;
use App\Models\Code;
use App\Models\Vote;
use App\Models\Result;
```

**Step 8: Run existing tests**

```bash
php artisan test --filter=UserTest
```

---

### Task 3: Fix Other Model Relationships

**Files:**
- Modify: `app/Models/Candidacy.php`
- Modify: `app/Models/Code.php`
- Modify: `app/Models/Vote.php`

**Step 1: Fix Candidacy user relationship**

Current Candidacy has wrong foreign key:
```php
// Current (line 35):
return $this->belongsTo(User::class, 'user_id', 'user_id')

// Should be:
return $this->belongsTo(User::class, 'user_id', 'id')
```

**Step 2: Add Code -> votes() relationship**

Code model needs votes() relationship:
```php
public function votes()
{
    return $this->hasMany(Vote::class, 'vote_hash', 'code1'); // Adjust based on actual relationship
}
```

**Step 3: Add Vote -> user() relationship via Code**

Vote model needs way to get user through code:
```php
public function user()
{
    return $this->hasOneThrough(
        User::class,
        Code::class,
        'code1',     // Foreign key on Code table (matches vote_hash)
        'id',        // Foreign key on User table
        'vote_hash', // Local key on Vote table
        'user_id'    // Local key on Code table
    );
}
```

**Step 4: Run model tests**

```bash
php artisan test --filter="CandidacyTest|CodeTest|VoteTest"
```

---

### Task 4: Create Relationship Tests

**Files:**
- Create: `tests/Unit/Models/UserRelationshipsTest.php`

**Step 1: Write test for organisations() relationship**

```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_organisations_relationship()
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $user->organisationRoles()->attach($organisation, ['role' => 'member']);

        $this->assertCount(1, $user->organisations);
        $this->assertEquals($organisation->id, $user->organisations->first()->id);
    }
}
```

**Step 2: Write test for candidacies() relationship**

```php
/** @test */
public function user_has_candidacies_relationship()
{
    $user = User::factory()->create();
    $candidacy = \App\Models\Candidacy::factory()->create(['user_id' => $user->id]);

    $this->assertCount(1, $user->candidacies);
    $this->assertEquals($candidacy->id, $user->candidacies->first()->id);
}
```

**Step 3: Write test for codes() relationship**

```php
/** @test */
public function user_has_codes_relationship()
{
    $user = User::factory()->create();
    $code = \App\Models\Code::factory()->create(['user_id' => $user->id]);

    $this->assertCount(1, $user->codes);
    $this->assertEquals($code->id, $user->codes->first()->id);
}
```

**Step 4: Write test for backward compatibility**

```php
/** @test */
public function code_method_maintains_backward_compatibility()
{
    $user = User::factory()->create();
    $code = \App\Models\Code::factory()->create(['user_id' => $user->id]);

    $this->assertNotNull($user->code);
    $this->assertEquals($code->id, $user->code->id);
}
```

**Step 5: Run the new tests**

```bash
php artisan test tests/Unit/Models/UserRelationshipsTest.php
```

---

### Task 5: Update Existing Code Usage

**Files:**
- Search: All files using old relationships
- Modify: Update to new relationships where appropriate

**Step 1: Find all usages of old relationships**

```bash
grep -r "->organisationRoles()" app/ --include="*.php" | head -20
grep -r "->candidacy()" app/ --include="*.php" | head -20
grep -r "->code()" app/ --include="*.php" | head -20
```

**Step 2: Update organisationRoles() to organisations() where appropriate**

For read-only access to organisations, use `organisations()`. For role management, keep `organisationRoles()`.

**Step 3: Verify code() usage works with new implementation**

The `code()` method returns hasOne relationship, so existing code should work.

**Step 4: Run full test suite**

```bash
php artisan test
```

**Step 5: Commit changes**

```bash
git add app/Models/User.php app/Models/Candidacy.php app/Models/Code.php app/Models/Vote.php tests/Unit/Models/UserRelationshipsTest.php
git commit -m "feat: Implement user model relationships from architecture document"
```

---

### Task 6: Documentation and Cleanup

**Files:**
- Create: `docs/relationships/USER_MODEL_RELATIONSHIPS.md`
- Update: `architecture/election/user_model/relationship_model.md` with notes

**Step 1: Document implemented relationships**

Create comprehensive documentation of all User relationships:

```markdown
# User Model Relationships

## Implemented Relationships

### organisations()
Alias for `organisationRoles()`. Returns organisations user belongs to.

### candidacies()
Fixed: `hasMany(Candidacy::class, 'user_id', 'id')`

### candidacy()
Enhanced: `hasOne(Candidacy::class, 'user_id', 'id')->where('status', 'approved')`

### codes()
New: `hasMany(Code::class)` - user can have multiple codes

### code()
Backward compatible: `hasOne(Code::class)` - returns first code

### votes()
Via Code: `hasManyThrough(Vote::class, Code::class, 'user_id', 'id', 'id', 'id')`

### results()
Via Vote: `hasManyThrough(Result::class, Vote::class, 'id', 'vote_id', 'id', 'id')`
```

**Step 2: Add architecture document notes**

Update the original architecture document with implementation notes about schema differences.

**Step 3: Final test run**

```bash
php artisan test --parallel
```

**Step 4: Commit documentation**

```bash
git add docs/relationships/USER_MODEL_RELATIONSHIPS.md architecture/election/user_model/relationship_model.md
git commit -m "docs: Add user relationships documentation"
```

---

## Plan complete and saved to `docs/plans/2026-03-05-user-model-relationships.md`.

**Two execution options:**

1. **Subagent-Driven (this session)** - I dispatch fresh subagent per task, review between tasks, fast iteration

2. **Parallel Session (separate)** - Open new session with executing-plans, batch execution with checkpoints

**Which approach?**