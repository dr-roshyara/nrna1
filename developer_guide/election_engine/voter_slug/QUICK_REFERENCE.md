# Voter Slug System - Quick Reference

## Essential Commands

### Generate Voter Slug

```php
use App\Services\VoterSlugService;

// For demo elections (auto-selects correct election)
$slug = app(VoterSlugService::class)->generateSlugForUser($user);

// For real elections (explicit election_id)
$slug = app(VoterSlugService::class)->generateSlugForUser($user, electionId: 42);
```

### Get or Create Slug

```php
// Returns existing slug if valid, creates new if expired
$slug = app(VoterSlugService::class)->getOrCreateActiveSlug($user);

// Use in controller: automatically gets existing or creates new
$slug = $this->voterSlugService->getOrCreateActiveSlug($user);
```

### Validate Slug

```php
// Validates slug belongs to user and is still active
$valid = app(VoterSlugService::class)->validateSlugForUser($slug->slug, $user);

if ($valid) {
    // Proceed with voting
} else {
    abort(403, 'Voting session expired');
}
```

### Build Voting URL

```php
$link = $this->voterSlugService->buildVotingLink($slug);
// Returns: /v/{slug}/slug.code.create

// Custom route name
$link = $this->voterSlugService->buildVotingLink($slug, 'vote.start');
```

---

## Schema Quick Reference

### voter_slugs Table

| Column | Type | Notes |
|--------|------|-------|
| `id` | INT | Primary Key |
| `user_id` | INT | FK → users.id |
| `slug` | VARCHAR(255) | Unique token |
| `election_id` | INT | FK → elections.id |
| `organisation_id` | INT | FK → organisations.id (CRITICAL) |
| `expires_at` | TIMESTAMP | When slug becomes invalid |
| `is_active` | BOOLEAN | Soft delete (false = revoked) |
| `current_step` | INT | Voting progress (1-N) |
| `step_meta` | JSON | Step-specific metadata |
| `created_at` | TIMESTAMP | Creation time |
| `updated_at` | TIMESTAMP | Last update |

---

## Critical MUST-HAVEs

### 1. Save organisation_id with slug

```php
// ✅ CORRECT
$slug = VoterSlug::create([
    'election_id' => $electionId,
    'organisation_id' => $election->organisation_id,  // MUST HAVE
]);

// ❌ WRONG
$slug = VoterSlug::create([
    'election_id' => $electionId,
    // Missing organisation_id → context lost!
]);
```

### 2. Use DemoElectionResolver for demo elections

```php
// ✅ CORRECT - Auto-selects org-specific or platform demo
$election = app(DemoElectionResolver::class)->getDemoElectionForUser($user);

// ❌ WRONG - Grabs first demo regardless of organisation
$election = Election::where('type', 'demo')->first();
```

### 3. Handle BelongsToTenant scope in tests

```php
// ✅ In tests, use withoutGlobalScopes() when needed
$slug = VoterSlug::withoutGlobalScopes()->where('id', $slugId)->first();

// ❌ Regular queries auto-filtered by organisation
$slug = VoterSlug::where('id', $slugId)->first();  // Affected by global scope
```

---

## Common Patterns

### Controller: Demo Voting Entry Point

```php
public function create(string $vslug, VoterSlugService $service)
{
    $user = auth()->user();

    // Get or create slug (extends validity if exists)
    $slug = $service->getOrCreateActiveSlug($user);

    // Validate it matches request
    if ($slug->slug !== $vslug) {
        abort(403);
    }

    // Update step
    $slug->update([
        'current_step' => 2,
        'step_meta' => ['started_at' => now()]
    ]);

    // Show voting form
    return inertia('Vote/VoteShowVerify', [
        'slug' => $slug->slug,
        'election_id' => $slug->election_id,
    ]);
}
```

### Controller: Vote Submission

```php
public function store(Request $request, VoterSlugService $service)
{
    $user = auth()->user();
    $vslug = $request->route('vslug');

    // Validate slug
    $slug = $service->validateSlugForUser($vslug, $user);
    if (!$slug) {
        abort(403, 'Voting session expired');
    }

    // Record vote
    $vote = DemoVote::create([
        'election_id' => $slug->election_id,
        'voter_slug_id' => $slug->id,
        'post_id' => $request->post_id,
        'candidate_id' => $request->candidate_id,
    ]);

    // Update step
    $slug->update([
        'current_step' => 3,
        'step_meta' => array_merge($slug->step_meta, [
            'votes_submitted' => ($slug->step_meta['votes_submitted'] ?? 0) + 1
        ])
    ]);

    return response()->json(['success' => true]);
}
```

---

## Election Selection Logic

### Demo Elections Priority

```
User org_id = 5

Option A: Demo with org_id=5
Option B: Demo with org_id=NULL (platform)
Option C: No demo exists

Priority:
1st → Option A (org-specific) ✅ SELECTED
2nd → Option B (platform fallback)
3rd → Exception thrown
```

---

## Debugging Commands

### Find User's Active Slug

```bash
# In Tinker
php artisan tinker

$user = App\Models\User::find(123);
$slug = App\Models\VoterSlug::where('user_id', $user->id)->valid()->first();
dd($slug);
```

### Check Slug Validity

```php
$slug = VoterSlug::find($slugId);

// Is it expired?
$slug->isExpired();  // bool

// Is it valid (active + not expired)?
$slug->isValid();    // bool

// When does it expire?
$slug->expires_at->diffInMinutes(now());  // minutes remaining
```

### Create Demo Election

```bash
php artisan demo:setup --org=5
```

### Extend Slug Manually

```php
$slug = VoterSlug::find($slugId);
$slug->update(['expires_at' => now()->addMinutes(30)]);
```

---

## Error Responses

### "Voting session expired"
- Slug doesn't exist
- Slug is inactive (revoked)
- Slug expired (> 30 minutes old)
- **Fix**: Generate new slug

### "No demo election available"
- User's organisation has no demo election
- Platform has no demo election
- **Fix**: Run `php artisan demo:setup --org={id}`

### "Slug mismatch"
- Route slug doesn't match user's active slug
- User accessing wrong slug
- **Fix**: Regenerate slug with correct context

### "Cross-organisation access"
- User from org A accessing org B's slug
- BelongsToTenant scope blocking query
- **Fix**: Ensure correct organisation context

---

## Performance Notes

- Slug generation: ~5ms (includes DB uniqueness check)
- Slug lookup: ~2ms (indexed by slug column)
- Election resolution: ~3ms (cached during request)
- Global scope filtering: ~0.5ms (indexed by organisation_id)

### Optimization Tips

1. **Cache election list** for DemoElectionResolver
2. **Index** `user_id` + `is_active` for faster queries
3. **Cleanup expired slugs** with scheduled job:
   ```bash
   # app/Console/Kernel.php
   $schedule->call(function () {
       app(VoterSlugService::class)->cleanupExpiredSlugs();
   })->everyMinute();
   ```

---

## Testing Checklist

Before deploying changes to voter slug system:

- [ ] All 29 integration tests passing
- [ ] All 14 demo election resolver tests passing
- [ ] Org-specific demos prioritized correctly
- [ ] Platform demo used as fallback
- [ ] Cross-organisation access prevented
- [ ] Slugs expire after 30 minutes
- [ ] Slug uniqueness maintained
- [ ] Election context preserved (organisation_id saved)

**Run Tests**:
```bash
php artisan test tests/Feature/Services/VoterSlugServiceTest.php
php artisan test tests/Unit/Services/DemoElectionResolverTest.php
```

---

## Key Files

| File | Purpose |
|------|---------|
| `app/Services/VoterSlugService.php` | Main service |
| `app/Services/DemoElectionResolver.php` | Election selection |
| `app/Models/VoterSlug.php` | Database model |
| `tests/Feature/Services/VoterSlugServiceTest.php` | Integration tests (29) |
| `tests/Unit/Services/DemoElectionResolverTest.php` | Unit tests (14) |
| `app/Http/Controllers/Demo/DemoCodeController.php` | Demo voting entry |
| `app/Http/Controllers/Demo/DemoVoteController.php` | Demo vote submission |

---

**Quick Start**: Generate slug → Send user link → Validate → Record vote → Extend slug
