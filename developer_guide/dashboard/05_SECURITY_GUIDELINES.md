# Security Guidelines: Multi-Tenant Isolation

## Core Security Principles

Public Digit implements **defense-in-depth** security with multiple layers protecting tenant data isolation.

---

## Layer 1: Database Design

### Schema Isolation

Every table that holds tenant data includes `organisation_id`:

```sql
-- Users table (landlord - platform admins only)
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    -- No organisation_id here
);

-- Election members (tenant - organisation-specific)
CREATE TABLE elections (
    id BIGINT PRIMARY KEY,
    organisation_id BIGINT NOT NULL,  -- ← Scoping column
    name VARCHAR(255),
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    UNIQUE KEY unique_name_per_org (name, organisation_id)  -- ← Unique PER ORG
);

-- Votes (tenant - organisation-specific)
CREATE TABLE votes (
    id BIGINT PRIMARY KEY,
    election_id BIGINT NOT NULL,      -- ← Foreign key to election
    -- NO user_id (complete anonymity)
    voting_code VARCHAR(255),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);
```

### Foreign Key Constraints

```sql
-- Prevents orphaning data across boundaries
ALTER TABLE elections
    ADD CONSTRAINT elections_org_fk
    FOREIGN KEY (organisation_id)
    REFERENCES organisations(id) ON DELETE CASCADE;
```

**What this does:**
- If organisation deleted, all its elections deleted
- Impossible to create election without organisation
- Impossible to modify organisation_id (referential integrity)

---

## Layer 2: Application Model Scoping

### Global Query Scopes

```php
// app/Models/Election.php
protected static function boot()
{
    parent::boot();

    // Every query automatically includes organisation filter
    static::addGlobalScope('organisation', function (Builder $query) {
        if ($organisation_id = session('current_organisation_id')) {
            $query->where('organisation_id', $organisation_id);
        }
    });
}
```

**Example:**
```php
// This query:
Election::all();

// Automatically becomes:
Election::where('organisation_id', session('current_organisation_id'))->get();

// Without global scope, it would return ALL elections (security bug!)
```

### Explicit Checking

```php
// Always check before accessing
$election = Election::find($id);

if (!$election || $election->organisation_id !== session('current_organisation_id')) {
    abort(403, 'Unauthorized');  // ← MUST check!
}

// Or use explicit method:
$election = Election::forCurrentOrganisation($id);  // Throws 404 if wrong org
```

---

## Layer 3: Middleware

### TenantContext Middleware

```php
// app/Http/Middleware/TenantContext.php
class TenantContext
{
    public function handle($request, $next)
    {
        // Extract tenant from URL
        $slug = $request->route('organisation');

        // Load tenant
        $organisation = Organisation::where('slug', $slug)->first();

        if (!$organisation) {
            abort(404, 'Organisation not found');
        }

        // Set session context
        session(['current_organisation_id' => $organisation->id]);
        session(['current_organisation_slug' => $organisation->slug]);

        // Set database connection (if multi-db)
        if ($organisation->has_separate_database) {
            DB::setPrimary($organisation->db_connection);
        }

        return $next($request);
    }
}
```

**Registered in routes:**
```php
Route::prefix('/{organisation}')
    ->middleware('web', 'auth', 'verified', 'tenant-context')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        // All routes here have tenant context
    });
```

### CheckUserRole Middleware

```php
// app/Http/Middleware/CheckUserRole.php
class CheckUserRole
{
    public function handle($request, $next, ...$roles)
    {
        $user = auth()->user();
        $org_id = session('current_organisation_id');

        // Check user has required role IN THIS ORGANISATION
        $hasRole = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $org_id)
            ->whereIn('role', $roles)
            ->exists();

        if (!$hasRole) {
            abort(403, 'You do not have permission to access this resource');
        }

        return $next($request);
    }
}
```

**Usage:**
```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:admin');  // Must have admin role IN CURRENT ORG

Route::get('/commission', [CommissionController::class, 'index'])
    ->middleware('role:commission');  // Must have commission role IN CURRENT ORG
```

---

## Layer 4: Request Validation

### Manual Validation

```php
public function updateElection(Request $request, Election $election)
{
    // Validate user's org matches election's org
    if ($election->organisation_id !== session('current_organisation_id')) {
        abort(403, 'Cannot modify election from different organisation');
    }

    // ... proceed with update
}
```

### Form Request Validation

```php
// app/Http/Requests/UpdateElectionRequest.php
class UpdateElectionRequest extends FormRequest
{
    public function authorize()
    {
        $election = Election::find($this->route('election'));

        // Must be admin in the election's organisation
        return DB::table('user_organisation_roles')
            ->where('user_id', auth()->id())
            ->where('organisation_id', $election->organisation_id)
            ->where('role', 'admin')
            ->exists();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
        ];
    }
}
```

---

## Layer 5: Vote Anonymity

### Critical Design: No User-Vote Linkage

```sql
-- votes table (INTENTIONALLY NO user_id)
CREATE TABLE votes (
    id BIGINT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    voting_code VARCHAR(255),  -- Hashed, cannot be reversed
    -- NO user_id field
    -- NO identification possible
    FOREIGN KEY (election_id) REFERENCES elections(id)
);
```

### Voting Process

```
1. User starts voting with VOTER SLUG
   voter_slugs: { user_id, election_id, slug, current_step=1 }

2. User submits vote with VOTING CODE
   votes: { voting_code (hashed), election_id }
   ← user_id NOT recorded

3. User verifies their vote with VOTING CODE
   results: Shows selection counts (aggregated, anonymous)
   ← Cannot identify which user voted for what

4. Audit trail (separate)
   voting_audit: { user_id, election_id, timestamp, action }
   ← Records "user X viewed this election"
   ← Does NOT record "user X voted for candidate Y"
```

### Preventing Vote Linkage

```php
// FORBIDDEN: This query returns nothing (no user_id column)
Vote::where('user_id', $user_id)->get();
// Column not found error ✓

// FORBIDDEN: This query returns nothing (no linkage possible)
Vote::where('voting_code', $user->voting_code)->get();
// Column doesn't exist ✓

// ALLOWED: This queries aggregate anonymous results
Result::where('election_id', $election->id)
    ->groupBy('candidate_id')
    ->selectRaw('candidate_id, COUNT(*) as votes')
    ->get();
// Safe - only shows totals ✓
```

---

## Cross-Tenant Access Prevention

### Example: Prevent Cross-Org Access

```php
public function showElection(Election $election)
{
    // BAD: No check (security bug!)
    return view('election.show', ['election' => $election]);

    // GOOD: Explicit check
    if ($election->organisation_id !== session('current_organisation_id')) {
        abort(403, 'Cannot view election from different organisation');
    }
    return view('election.show', ['election' => $election]);

    // BEST: Use global scope (automatic)
    $election = Election::find($id);  // Won't find if wrong org
    if (!$election) {
        abort(404);  // Global scope filtered it out
    }
    return view('election.show', ['election' => $election]);
}
```

---

## Common Security Mistakes

### ❌ MISTAKE 1: Trusting User Input

```php
// WRONG - User can access any organisation
$org_id = $request->input('organisation_id');
$elections = Election::where('organisation_id', $org_id)->get();

// RIGHT - Use session (set by middleware)
$org_id = session('current_organisation_id');
$elections = Election::where('organisation_id', $org_id)->get();
```

### ❌ MISTAKE 2: Forgetting Organisation Check

```php
// WRONG - Trusting model find() alone
public function update(Election $election, Request $request)
{
    $election->update($request->all());  // What if user owns different org?
    return $election;
}

// RIGHT - Verify organisation
public function update(Election $election, Request $request)
{
    if ($election->organisation_id !== session('current_organisation_id')) {
        abort(403);
    }
    $election->update($request->all());
    return $election;
}
```

### ❌ MISTAKE 3: Querying Without Scope

```php
// WRONG - Allows access to any election
$elections = Election::all();

// RIGHT - Filtered by current organisation
$elections = Election::where(
    'organisation_id',
    session('current_organisation_id')
)->get();
```

### ❌ MISTAKE 4: Storing in Wrong Database

```php
// WRONG - All data in same database (no isolation)
DB::connection('mysql')->table('elections')->insert($data);

// RIGHT - Tenant data in tenant database
DB::connection($organisation->db_connection)
    ->table('elections')
    ->insert($data);
```

---

## Testing Security

### Test Cross-Tenant Isolation

```php
/** @test */
public function user_cannot_access_other_organisations_election()
{
    // User belongs to org A
    $userOrgA = User::factory()->create();
    DB::table('user_organisation_roles')->insert([
        'user_id' => $userOrgA->id,
        'organisation_id' => 1,  // Org A
        'role' => 'admin',
    ]);

    // Election in org B
    $election = Election::factory()->create([
        'organisation_id' => 2,  // Org B
    ]);

    // User tries to access org B's election
    $this->actingAs($userOrgA)
        ->session(['current_organisation_id' => 1])  // Set org A
        ->get("/elections/{$election->id}")
        ->assertForbidden();  // ← Should be 403
}
```

### Test Vote Anonymity

```php
/** @test */
public function votes_table_has_no_user_id_column()
{
    $columns = Schema::getColumnListing('votes');

    // Verify user_id column doesn't exist
    $this->assertNotContains('user_id', $columns);

    // Verify other identifying columns don't exist
    $this->assertNotContains('voter_name', $columns);
    $this->assertNotContains('email', $columns);
}
```

---

## GDPR Compliance

### Right to Be Forgotten

When user requests deletion:

```php
public function deleteUser(User $user)
{
    // Delete user record (triggers cascades)
    $user->delete();

    // Cascade deletes via foreign keys:
    // - user_organisation_roles deleted
    // - election_commission_members deleted
    // - voter_slugs deleted (voting history)
    // - voting_codes deleted (audit trail references)

    // IMPORTANT: votes table is NOT deleted!
    // Votes are anonymous, cannot identify user
    // Deleting would break election results
}
```

---

## Security Checklist

- [ ] All tenant data tables have `organisation_id` column
- [ ] Global scopes protect queries automatically
- [ ] TenantContext middleware set in routes
- [ ] CheckUserRole middleware validates role in correct org
- [ ] Manual checks verify organisation ownership before access
- [ ] Votes table has no user_id column
- [ ] CSRF protection enabled on all forms
- [ ] Email verification required for access
- [ ] Session timeout configured appropriately
- [ ] Password hashing using bcrypt/Argon2
- [ ] Rate limiting on login endpoints
- [ ] Audit trail logs all administrative actions
- [ ] Tests verify cross-tenant isolation
- [ ] Tests verify vote anonymity

---

**Last Updated:** March 4, 2026
