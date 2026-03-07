# 🚀 Organisation Isolation - Quick Reference

## Basic Rules

### Rule 1: Always set session before querying
```php
session(['current_organisation_id' => $organisation->id]);
```

### Rule 2: All queries are automatically scoped
```php
// This ONLY returns current org's records:
$elections = Election::all();
```

### Rule 3: Never manually add WHERE organisation_id
```php
// ❌ DON'T do this:
Election::where('organisation_id', session('current_organisation_id'))->get();

// ✅ DO this instead:
Election::all();
```

### Rule 4: Use withoutGlobalScopes() only when needed
```php
// ✅ For relationships:
$organisation = $election->organisation()->withoutGlobalScopes();

// ✅ For admin operations only:
$allOrgs = Election::withoutGlobalScopes()->get();
```

---

## Common Commands

### Create Record (Auto-fills org)
```php
$election = Election::create(['name' => 'Test']);
// organisation_id automatically set to current org
```

### Find Record (Returns null if wrong org)
```php
$election = Election::find($id);
// Returns null if election belongs to different org
```

### List All Records (Scoped)
```php
$elections = Election::all();
// Only current org's elections
```

### Filter with Conditions (Still scoped)
```php
$active = Election::where('status', 'active')->get();
// Only current org's active elections
```

### Count (Scoped)
```php
$count = Election::count();
// Count of current org's records only
```

### Bypass Scope (Rare)
```php
$all = Election::withoutGlobalScopes()->get();
// ALL elections, no filtering
```

---

## Testing Patterns

### Setup Test Organisation
```php
use RefreshDatabase;

protected function setUp(): void
{
    parent::setUp();
    $this->org = Organisation::factory()->create();
    session(['current_organisation_id' => $this->org->id]);
}
```

### Test Isolation
```php
public function test_only_current_org_records()
{
    $other_org = Organisation::factory()->create();

    $my_election = Election::factory()->create(['organisation_id' => $this->org->id]);
    $other_election = Election::factory()->create(['organisation_id' => $other_org->id]);

    $elections = Election::all();

    $this->assertCount(1, $elections);
    $this->assertEquals($my_election->id, $elections->first()->id);
}
```

### Test Isolation with Find
```php
public function test_find_returns_null_for_other_org()
{
    $other_org = Organisation::factory()->create();
    $other_election = Election::factory()->create(['organisation_id' => $other_org->id]);

    $found = Election::find($other_election->id);

    $this->assertNull($found);
}
```

---

## Middleware Flow

```
Request arrives
    ↓
EnsureOrganisationMember middleware
    ↓
Extract org from URL (slug/UUID)
    ↓
Verify user is member
    ↓
Set session('current_organisation_id' => org->id)
    ↓
Request forwarded to Controller
    ↓
Model queries automatically scoped
    ↓
Only current org's data returned
```

---

## Model Definition Checklist

```php
class MyModel extends Model
{
    // ✅ Add trait
    use BelongsToTenant;

    // ✅ Add to fillable
    protected $fillable = [
        'organisation_id',
        'name',
    ];

    // ✅ Define relationship without double-filtering
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }
}
```

---

## Error Messages

| Error | Cause | Fix |
|-------|-------|-----|
| "No organisation context set" | Session not set | Run `session(['current_organisation_id' => ...])` |
| Model from other org showing | Trait not added | Add `use BelongsToTenant;` |
| Find returns null | Record from other org | Verify `organisation_id` matches session |
| N+1 query warning | Too many queries | Use static cache (built-in) |

---

## Architecture Layers

```
LAYER 1: Middleware
  - Extracts org from URL
  - Validates membership
  - Sets session context

LAYER 2: Model Trait
  - Adds global scope
  - Auto-fills on create
  - Caches platform org

LAYER 3: Eloquent
  - WHERE clause applied
  - Automatic filtering
  - Developer forgetting prevented

LAYER 4: Database
  - Foreign key constraints
  - Composite indexes
  - Last resort protection
```

---

## File Locations

- **Trait:** `app/Traits/BelongsToTenant.php`
- **Middleware:** `app/Http/Middleware/EnsureOrganisationMember.php`
- **Service:** `app/Services/TenantContext.php`
- **Tests:** `tests/Feature/OrganisationIsolationTest.php`
- **Guide:** `developer_guide/model/isolation/README.md`
