# Architecture Decisions & Rationale

Documentation of key architectural decisions made in Phase A and their rationale.

---

## Decision 1: UUID Primary Keys Over Auto-Incrementing Integers

### What We Chose
All models use `char(36)` UUID string primary keys via Laravel's `HasUuids` trait.

### Why
1. **Multi-Tenancy:** UUIDs make it safe to merge datasets from different instances
2. **Security:** Auto-increment IDs expose system size and can be guessed
3. **Client-Side Generation:** Can generate IDs before database insert (useful for APIs)
4. **Distributed Systems:** Ready for horizontal scaling across services

### Implementation
```php
// In every model
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Model extends Model {
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
}
```

### Trade-offs
- **Downside:** Takes 36 bytes vs 4-8 for integer
  - Acceptable for benefits gained
  - Still performant with proper indexing
- **Learning Curve:** Developers unfamiliar with UUIDs need education
  - Covered in onboarding documentation

---

## Decision 2: Explicit Pivot Model (UserOrganisationRole)

### What We Chose
Instead of Laravel's implicit pivot table, we created an explicit `UserOrganisationRole` model.

### Why
1. **Richer Data:** Can store additional attributes (`role`, `permissions`)
2. **Querying:** Can query pivot without joining parent models
3. **Relationships:** Can define belongsTo relationships on pivot
4. **Explicit:** Makes relationships clearer to new developers

### Implementation
```php
// Explicit pivot model
class UserOrganisationRole extends Model {
    protected $fillable = ['user_id', 'organisation_id', 'role'];
    public function user() { return $this->belongsTo(User::class); }
    public function organisation() { return $this->belongsTo(Organisation::class); }
}

// In Organisation
public function users() {
    return $this->belongsToMany(User::class, 'user_organisation_roles')
                ->withPivot('role');
}
```

### Trade-offs
- **Downside:** More code to maintain
  - Offset by clearer intent and better testability
- **Upside:** Can add new attributes without schema migrations
  - Just update fillable array

---

## Decision 3: HasManyThrough for Election→Candidacies

### What We Chose
Elections access Candidacies through Posts using `HasManyThrough` relationship.

### Why
1. **Database Normalization:** No direct foreign key from candidacies to elections
   - Candidacies only know their post: `post_id`
   - Elections only know their posts: `id` in posts
   - This is correct normalization
2. **Data Integrity:** Can't accidentally orphan candidacies by changing election
3. **Scalability:** Natural decomposition for large datasets

### Implementation
```php
// In Election model
public function candidacies(): HasManyThrough
{
    return $this->hasManyThrough(
        Candidacy::class,    // Final model
        Post::class,         // Intermediate
        'election_id',       // FK on posts
        'post_id',           // FK on candidacies
        'id',                // Local key on elections
        'id'                 // Local key on posts
    )->withoutGlobalScopes();
}
```

### Diagram
```
Elections
   ↓ (has_many)
Posts
   ↓ (has_many)
Candidacies

Election.id → Post.election_id → Post.id → Candidacy.post_id
```

### Trade-offs
- **Downside:** Slightly slower queries (two joins instead of one)
  - Acceptable - queries are rare and not performance-critical
- **Upside:** Forces correct data model design
  - Prevents future refactoring headaches

---

## Decision 4: Vote Anonymity (NO User→Vote Relationships)

### What We Chose
Votes table has **NO user_id column**. Users cannot be linked to their votes.

```sql
-- CORRECT schema
CREATE TABLE votes (
    id UUID,
    election_id UUID,
    voting_code_hash VARCHAR,
    -- NO user_id column!
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- WRONG schema (violates anonymity)
CREATE TABLE votes (
    id UUID,
    election_id UUID,
    user_id UUID,  -- ❌ BROKEN!
    voting_code_hash VARCHAR,
    created_at TIMESTAMP
);
```

### Why
1. **Legal Requirement:** Vote secrecy is fundamental to democratic elections
2. **Coercion Prevention:** Without user→vote link, voters can't prove who they voted for
3. **Audit Trail:** Anonymous voting codes create audit trail without revealing votes
4. **Trust:** Voters must trust system cannot expose their votes

### Implementation
```php
// Allowed (one-way)
class VoterSlug extends Model {
    public function votes() {
        return $this->hasMany(Vote::class);  // ✅ One-way only
    }
}

// FORBIDDEN (would break anonymity)
class User extends Model {
    // public function votes() { ... }  // ❌ NO!
}

class Vote extends Model {
    // public function user() { ... }   // ❌ NO!
}
```

### Trade-offs
- **Downside:** Cannot directly query "how did user X vote"
  - This is intentional and correct
  - Can only see they voted, not their selections
- **Upside:** Legal compliance, voter privacy, election integrity

### Enforcement Mechanisms
1. **Database:** No foreign key exists
2. **Model:** Relationship never created
3. **Tests:** Explicit test verifies no direct relationship
4. **Review:** Code reviewers check for violations

---

## Decision 5: Session-Based Tenant Context with withoutGlobalScopes()

### What We Chose
- Tenant context via `session('current_organisation_id')`
- All tenant-scoped queries use `BelongsToTenant` global scope
- Tests and special queries use `->withoutGlobalScopes()`

### Why
1. **Simplicity:** Single source of truth for current tenant
2. **Safety:** Automatic filtering prevents accidental cross-tenant access
3. **Testability:** Explicit `withoutGlobalScopes()` makes scope behavior obvious
4. **Convention:** Common Laravel pattern

### Implementation
```php
// Set context
session(['current_organisation_id' => $org->id]);

// Auto-filtered query
$posts = Post::all();  // Only current org's posts

// Explicit bypass (tests)
$posts = Post::withoutGlobalScopes()
    ->where('organisation_id', $org->id)
    ->get();

// Via relationship (implicit bypass)
$posts = $org->posts;  // Relationship handles scope
```

### Trade-offs
- **Downside:** Global state can be confusing
  - Mitigated by explicit naming and documentation
- **Upside:** Simple, conventional, easy to debug

### Future Consideration
Could evolve to explicit parameter passing if requirements change.

---

## Decision 6: Relationship Methods with withoutGlobalScopes()

### What We Chose
Every relationship method includes `->withoutGlobalScopes()` explicitly.

```php
// Required pattern
public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();
}
```

### Why
1. **Clarity:** Makes scope behavior explicit to reader
2. **Correctness:** Prevents relationship queries from returning empty results
3. **Testing:** Relationship queries work identically in production and tests
4. **Maintainability:** Future developers understand why withoutGlobalScopes is needed

### Code Pattern
```php
// Check: Does related model use BelongsToTenant trait?
class Post extends Model {
    use BelongsToTenant;  // ← Yes!
}

// Then: Add withoutGlobalScopes() to relationship
public function posts()
{
    return $this->hasMany(Post::class)
                ->withoutGlobalScopes();  // ← Required!
}
```

### Trade-offs
- **Downside:** Verbose code, seems redundant
  - Reality: Necessity forced by trait design
- **Upside:** Prevents silent bugs, makes intent clear

---

## Decision 7: Separate Relationships for Filtered Results

### What We Chose
Instead of one relationship with optional filters, create separate methods for common filters.

```php
// Three methods instead of one
class Post extends Model {
    // Base relationship
    public function candidacies() { ... }

    // Approved only
    public function approvedCandidacies() { ... }

    // Can also do: ->with('user'), ->orderBy()
}

// Usage is clear
$all = $post->candidacies;           // All statuses
$approved = $post->approvedCandidacies;  // Approved only
```

### Why
1. **Clarity:** Intent is clear from method name
2. **Reusability:** Easy to use in multiple places without repeating filter
3. **Discoverability:** IDE autocomplete shows available filtered views
4. **Performance:** Can be optimized individually

### Trade-offs
- **Downside:** More methods to maintain
  - Offset by clarity gained
- **Upside:** Developers don't need to remember filter syntax

---

## Decision 8: Organisation Type as Enum (platform|tenant)

### What We Chose
Organisations have a `type` enum with two values: `platform` or `tenant`.

```php
Schema::create('organisations', function (Blueprint $table) {
    $table->enum('type', ['platform', 'tenant']);
});
```

### Why
1. **Multi-Tenancy Mode:** Platform org manages system configuration
2. **Tenant Mode:** Individual tenant organisations run elections
3. **Type Safety:** Enum prevents invalid values
4. **Querying:** Easy to filter: `where('type', 'tenant')`

### Usage
```php
// Get platform organisation
$platform = Organisation::getDefaultPlatform();

// Filter tenants only
$tenants = Organisation::where('type', 'tenant')->get();

// Check type
if ($org->isPlatform()) { ... }
if ($org->isTenant()) { ... }
```

### Trade-offs
- **Downside:** Requires careful migration if type semantics change
  - Unlikely given election use case
- **Upside:** Clear distinction between administrative and operational orgs

---

## Decision 9: Soft Deletes Everywhere

### What We Chose
All models implement soft deletes via `SoftDeletes` trait.

```php
class Model extends Model {
    use SoftDeletes;
}
```

### Why
1. **Audit Trail:** Deleted records preserved for legal/audit reasons
2. **Recovery:** Accidentally deleted data can be restored
3. **Reporting:** Can query historical state
4. **Elections:** Never actually delete election data

### Implementation
```php
// Soft-deleted records hidden by default
$elections = Election::all();

// Include soft-deleted
$all = Election::withTrashed()->get();

// Only soft-deleted
$deleted = Election::onlyTrashed()->get();

// Permanently delete
$election->forceDelete();
```

### Trade-offs
- **Downside:** Requires filtering in all queries (automatic via trait)
- **Upside:** Data safety, audit trail, recovery capability

---

## Decision 10: Separate Demo Tables vs Type Column

### What We Chose
Different table structure for demo elections:
- Real elections: `votes`, `results`, `candidacies`
- Demo elections: `demo_votes`, `demo_results`, `demo_candidacies`

### Why
1. **Isolation:** Demo data completely separate from real elections
2. **Reset:** Can truncate demo tables without touching real data
3. **Schema:** Can have different columns if needed
4. **Clarity:** Intent obvious from table name

### Usage
```php
// Different table access
if ($election->isDemo()) {
    $votes = DemoVote::where('election_id', $id)->get();
} else {
    $votes = Vote::where('election_id', $id)->get();
}

// Relationships handle this
$votes = $election->votes();  // Returns correct table automatically
```

### Trade-offs
- **Downside:** Code duplication for demo vs real logic
  - Acceptable for election data safety
- **Upside:** Complete isolation, simpler reset logic

---

## Decision 11: Nullable Foreign Keys for Optional Relationships

### What We Chose
Some relationships are optional (`user_id` on `Candidacy` is nullable).

```php
Schema::create('candidacies', function (Blueprint $table) {
    $table->uuid('user_id')->nullable();  // Optional
});
```

### Why
1. **Flexibility:** Can create candidacy without assigned user
2. **Deletion Safety:** Can delete user without deleting candidacy
3. **Inheritance:** Some candidacies may not have associated users
4. **Decoupling:** Models less tightly bound

### Usage
```php
$candidacy = Candidacy::find($id);

if ($candidacy->user) {
    echo $candidacy->user->name;
} else {
    echo 'No user assigned';
}
```

### Trade-offs
- **Downside:** Code must check for null relationships
- **Upside:** Greater flexibility for edge cases

---

## Decision 12: Explicit Scope Methods Over Query Builder

### What We Chose
Define named scopes for common filters rather than inline query building.

```php
// Preferred
$posts = Post::forOrganisation($org_id)->get();

// Not preferred
$posts = Post::withoutGlobalScopes()
    ->where('organisation_id', $org_id)
    ->get();
```

### Why
1. **Reusability:** Use scope in multiple places without repetition
2. **Clarity:** Intent clear from method name
3. **Maintenance:** Change filter in one place
4. **Discoverability:** IDE shows available scopes

### Implementation
```php
class Post extends Model {
    public function scopeForOrganisation($query, $org_id)
    {
        return $query->withoutGlobalScopes()
                     ->where('organisation_id', $org_id);
    }
}
```

### Trade-offs
- **Downside:** More methods to define
- **Upside:** More maintainable and reusable code

---

## Summary Table

| Decision | Choice | Key Reason |
|----------|--------|-----------|
| 1. Primary Keys | UUID strings | Multi-tenancy safety, security |
| 2. Pivot Model | Explicit model | Richer data, better queries |
| 3. Election→Candidacy | HasManyThrough | Database normalization |
| 4. Vote Anonymity | NO User→Vote | Legal requirement, voter privacy |
| 5. Tenant Context | Session + Global scope | Simplicity, safety |
| 6. Relationships | withoutGlobalScopes() | Clarity, correctness |
| 7. Filtered Methods | Separate methods | Clarity, reusability |
| 8. Org Type | Enum (platform\|tenant) | Type safety, querying |
| 9. Deletion | Soft deletes | Audit trail, recovery |
| 10. Demo Tables | Separate tables | Isolation, reset capability |
| 11. Optional FKs | Nullable relationships | Flexibility, decoupling |
| 12. Scopes | Named scopes | Reusability, clarity |

---

## Future Considerations

### If Requirements Change

**Scaling Horizontally**
- UUIDs support database sharding
- Session context could become explicit parameter passing
- Separate databases per tenant could be added

**Regulatory Requirements**
- Vote anonymity must NEVER be compromised
- Could add cryptographic proof of vote encryption
- Audit logging can be enhanced

**Performance Optimization**
- Cache frequently accessed relationships
- Add database indices on common filters
- Consider materialized views for complex queries

**New Relationship Types**
- Follow same patterns established in Phase A
- Always include organization_id
- Always use withoutGlobalScopes() for tenant-scoped models
- Test thoroughly with multiple organisations

---

**Last Updated:** 2026-03-06
**Phase:** A (Architecture Decisions)
