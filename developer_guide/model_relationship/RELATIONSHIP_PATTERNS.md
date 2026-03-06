# Relationship Patterns Reference

Quick reference for common relationship patterns used in Phase A.

---

## 1. BelongsTo Pattern

**Use when:** Model belongs to a parent model.

```php
// In Child Model
class Post extends Model {
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }

    public function election()
    {
        return $this->belongsTo(Election::class)
                    ->withoutGlobalScopes();
    }
}

// Usage
$post->organisation;  // Get parent organisation
$post->election;      // Get parent election
```

**Key Points:**
- Parent model name determines default foreign key (`organisation_id`)
- Explicitly include `->withoutGlobalScopes()` for tenant-scoped models
- Optional: Use second parameter if FK name differs: `belongsTo(User::class, 'user_id', 'id')`

---

## 2. HasMany Pattern

**Use when:** Parent model owns multiple child records.

```php
// In Parent Model
class Election extends Model {
    public function posts()
    {
        return $this->hasMany(Post::class)
                    ->withoutGlobalScopes();
    }

    public function candidacies()
    {
        return $this->hasManyThrough(
            Candidacy::class,
            Post::class,
            'election_id',
            'post_id',
            'id',
            'id'
        )->withoutGlobalScopes();
    }
}

// Usage
$posts = $election->posts;           // Get all posts in election
$candidates = $election->candidacies; // Get all candidates
```

**Key Points:**
- Foreign key on child table (`election_id` on posts)
- Always add `->withoutGlobalScopes()` if child is tenant-scoped
- Can chain additional constraints: `$election->posts()->where('is_national_wide', true)->get()`

---

## 3. BelongsToMany Pattern (Pivot)

**Use when:** Two models have many-to-many relationship through pivot table.

```php
// In Organisation Model
class Organisation extends Model {
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}

// In User Model
class User extends Model {
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}

// Usage
$org->users;                           // Get all users in org
$org->users()->wherePivot('role', 'admin')->get();  // Filter by role
$org->admins()->get();                 // Helper method

$user->organisations;                  // Get all orgs user is member of
```

**Key Points:**
- Pivot table name: `user_organisation_roles` (explicit, not auto-generated)
- Include `->withPivot('role')` to access pivot attributes
- Use `->withTimestamps()` if pivot has created_at/updated_at
- Can chain `wherePivot()` for filtering: `->wherePivot('role', 'admin')`

---

## 4. HasManyThrough Pattern

**Use when:** Access related records through an intermediate model.

```php
// Structure: Elections → Posts → Candidacies
// Goal: Access all candidates in an election

class Election extends Model {
    public function candidacies(): HasManyThrough
    {
        return $this->hasManyThrough(
            Candidacy::class,      // Final model to access
            Post::class,           // Intermediate model
            'election_id',         // Foreign key on posts table
            'post_id',             // Foreign key on candidacies table
            'id',                  // Local key on elections
            'id'                   // Local key on posts
        )->withoutGlobalScopes();
    }
}

// Usage
$all_candidates = $election->candidacies;
$approved = $election->candidacies()->where('status', 'approved')->get();
```

**Key Points:**
- Order of parameters: Final → Intermediate → FK(Intermediate) → FK(Final) → Local(Start) → Local(Intermediate)
- Bypass global scopes on both intermediate and final models
- No direct foreign key on final model to starting model

---

## 5. Query Scope Pattern

**Use when:** Need to create reusable query filters.

```php
// In Model
class Post extends Model {
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()
                     ->where('organisation_id', $organisationId);
    }

    public function scopeForElection($query, string $electionId)
    {
        return $query->withoutGlobalScopes()
                     ->where('election_id', $electionId);
    }

    public function scopeNational($query)
    {
        return $query->where('is_national_wide', true);
    }

    public function scopeRegional($query)
    {
        return $query->where('is_national_wide', false);
    }
}

// Usage (single scope)
$org_posts = Post::forOrganisation($org_id)->get();

// Usage (chained scopes)
$national_posts = Post::forOrganisation($org_id)
    ->national()
    ->get();

$regional_posts = Post::forOrganisation($org_id)
    ->regional()
    ->where('state_name', 'Bavaria')
    ->get();
```

**Key Points:**
- Scope method name convention: `scope` + CamelCase method name
- Always include `->withoutGlobalScopes()` if filtering tenant-scoped models
- Scopes are chainable and work with other constraints
- Used in tests to avoid repetitive `withoutGlobalScopes()` calls

---

## 6. Relationships with Additional Constraints

**Use when:** Need to filter relationships beyond the basic FK.

```php
class Post extends Model {
    // Get only approved candidates
    public function approvedCandidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->where('status', 'approved')
                    ->orderBy('position_order')
                    ->withoutGlobalScopes();
    }

    // Get candidates ordered by position
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->orderBy('position_order')
                    ->withoutGlobalScopes();
    }
}

class Election extends Model {
    // Get pending voters
    public function pendingVoters()
    {
        return $this->voterRegistrations()
                    ->where('status', 'pending')
                    ->with('user');
    }

    // Get approved voters
    public function approvedVoters()
    {
        return $this->voterRegistrations()
                    ->where('status', 'approved')
                    ->with('user');
    }
}

// Usage
$approved_candidates = $post->approvedCandidacies;
$pending = $election->pendingVoters;
```

**Key Points:**
- Define separate methods for different filters
- Include constraints in the relationship definition
- Can include eager loading: `->with('user')`
- Can include ordering: `->orderBy('position_order')`

---

## 7. Eager Loading Pattern

**Use when:** Prevent N+1 query problems.

```php
// ❌ BAD - N+1 problem
foreach ($elections as $election) {
    echo $election->organisation->name;  // Query per iteration!
}

// ✅ GOOD - Eager loading
$elections = Election::with('organisation')->get();
foreach ($elections as $election) {
    echo $election->organisation->name;  // No additional queries
}

// ✅ GOOD - Nested eager loading
$elections = Election::with(['posts' => function($q) {
    $q->with('candidacies');
}])->get();

// Access nested relationships
foreach ($elections as $election) {
    foreach ($election->posts as $post) {
        foreach ($post->candidacies as $candidacy) {
            // All loaded in memory
        }
    }
}
```

**Key Points:**
- Use `->with()` to eager load relationships
- Nest with closures to eager load related relationships
- Dramatically reduces database queries
- Essential for performance with large datasets

---

## 8. One-Way Relationships (Non-Inverse)

**Use when:** Relationship only exists in one direction.

```php
// Vote → VoterSlug is allowed
class VoterSlug extends Model {
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}

// Vote → User is FORBIDDEN (breaks anonymity)
class Vote extends Model {
    // This relationship MUST NOT exist!
    // public function user() { ... }  // NO!
}

// Usage
$slug->votes;  // Get votes for this voter slug ✅
// $vote->user;  // Cannot do this (no relationship exists) ✅
```

**Key Points:**
- Some relationships are intentionally one-way for security
- Vote anonymity requires Vote has NO reference back to User
- Document why one-way relationships exist

---

## 9. Nullable Relationships

**Use when:** A relationship is optional.

```php
class Candidacy extends Model {
    // user_id is nullable in database
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
                    ->withoutGlobalScopes();
    }
}

// Usage
$candidacy = Candidacy::find($id);

if ($candidacy->user) {
    // User exists
    echo $candidacy->user->name;
} else {
    // No user associated (deleted or never assigned)
    echo 'No user assigned';
}
```

**Key Points:**
- Always check with `if ($model->relationship)` before accessing
- Foreign key must be `nullable()` in migration
- Helpful for records that may have optional relationships

---

## 10. Relationship Type Decision Tree

```
Is A owned by B?
├─ YES (one B per A)
│   └─ Use BelongsTo
│       (A.organisation_id → B.id)
│
├─ NO
│   Does B own many A's?
│   ├─ YES (direct FK on A)
│   │   └─ Use HasMany
│   │       (B.id ← A.election_id)
│   │
│   └─ NO
│       Does A access data through intermediate C?
│       ├─ YES
│       │   └─ Use HasManyThrough
│       │       (A.id → C.fk1 → D.fk2)
│       │
│       └─ NO
│           Are A and B connected via pivot table?
│           ├─ YES
│           │   └─ Use BelongsToMany
│           │       (via pivot table)
│           │
│           └─ NO (different domains, no relationship)
```

---

## Quick Reference Table

| Pattern | Use Case | FK Location | Example |
|---------|----------|------------|---------|
| **BelongsTo** | Child to Parent (many-to-one) | On child | Post → Organisation |
| **HasMany** | Parent to Children (one-to-many) | On child | Election → Posts |
| **BelongsToMany** | Many-to-Many | Pivot table | Organisation ↔ Users |
| **HasManyThrough** | Access through intermediate | On both intermediates | Election → Posts → Candidacies |
| **HasOne** | Parent to single child | On child | (not used in Phase A) |
| **Query Scope** | Reusable filter | N/A | Post::forOrganisation() |

---

## Common Mistakes

| ❌ Wrong | ✅ Correct | Why |
|---------|-----------|-----|
| `$post->hasMany()` | `$post->hasMany()->withoutGlobalScopes()` | Global scope blocks queries |
| `$candidacy->election` | `$candidacy->post->election` | No direct FK on candidacies |
| `$vote->user()` | Cannot exist | Breaks vote anonymity |
| `$org->users()` | `$org->users()->wherePivot('role', 'admin')` | Use wherePivot for pivot filtering |
| `Post::all()` without session | `Post::forOrganisation($org_id)->get()` | BelongsToTenant filters out |

---

**Last Updated:** 2026-03-06
