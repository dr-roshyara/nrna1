# VoterSlugService API Reference

## Class: App\Services\VoterSlugService

Complete API reference for the VoterSlugService class.

---

## Public Methods

### getOrCreateSlug(User $user, Election $election, bool $forceNew = false): VoterSlug|DemoVoterSlug

Get existing valid slug or create new one.

**Parameters:**
- `$user` (User): The voter
- `$election` (Election): The election they are voting in
- `$forceNew` (bool): Force creation of new slug (demo elections)

**Returns:** VoterSlug or DemoVoterSlug instance

**Behavior:**
- If `$forceNew = true`: Always create new slug
- If `$forceNew = false`: Reuse active non-expired slug if exists
- Demo elections: Always forceNew
- Real elections: Reuse when possible

**Example:**
```php
$service = app(VoterSlugService::class);
$slug = $service->getOrCreateSlug($user, $election);
// Returns existing slug OR creates new one
```

---

### getValidatedSlug(string $slugString, User $user, Election $election): ?VoterSlug

Retrieve slug and validate ownership in one operation.

**Parameters:**
- `$slugString` (string): The slug value
- `$user` (User): The authenticated voter
- `$election` (Election): The current election context

**Returns:** VoterSlug if valid, null if invalid/expired/wrong owner

**Validation Checks:**
1. Slug exists in database
2. Slug belongs to this user (user_id match)
3. Slug belongs to this election (election_id match)
4. Slug is not expired (expires_at > now)
5. Slug is active (is_active = true)

**Usage:**
```php
$slug = $service->getValidatedSlug($slugString, $user, $election);
if ($slug) {
    $request->attributes->set('voter_slug', $slug);
} else {
    abort(403, 'Invalid voting link');
}
```

---

### validateSlugOwnership(VoterSlug $slug, User $user, Election $election): void

Strict ownership validation (throws if invalid).

**Throws:** AccessDeniedHttpException if validation fails

**Validation:**
- Throws if `$slug->user_id !== $user->id`
- Throws if `$slug->election_id !== $election->id`

---

### createNewSlug(User $user, Election $election, Model $model): VoterSlug

Create new slug after hard-deleting old ones.

**Returns:** Newly created slug instance

**Process:**
1. Hard-delete all soft-deleted slugs for this user/election
2. Auto-set defaults via model boot hook
3. Return new slug instance

**Critical:** Uses hard delete (forceDelete) not soft delete!

---

### cleanupExpiredSlugs(User $user, Election $election, Model $model): int

Hard-delete expired slug records.

**Returns:** int - Number of deleted records

---

## Related Model Methods

### VoterSlug Instance Methods

**Properties:**
- `$slug->id` (UUID)
- `$slug->slug` (string) - Public voting link
- `$slug->user_id` (UUID)
- `$slug->election_id` (UUID)
- `$slug->expires_at` (Carbon)
- `$slug->is_active` (boolean)
- `$slug->current_step` (int) - 1-5
- `$slug->status` (enum) - active, voted, expired, abstained

**Methods:**
- `isExpired(): bool` - Check if past expiration
- `isValid(): bool` - Check if active AND not expired

---

## Usage Patterns

### Pattern 1: Starting Voting Session

```php
public function startVoting(Request $request)
{
    $user = auth()->user();
    $election = $request->attributes->get('election');
    $service = app(VoterSlugService::class);

    $slug = $service->getOrCreateSlug($user, $election);
    
    return redirect()->route('voting.step-1', ['vslug' => $slug->slug]);
}
```

### Pattern 2: Validating In Middleware

```php
public function handle(Request $request, Closure $next)
{
    $slugString = $request->route('vslug');
    $user = auth()->user();
    $election = $request->attributes->get('election');
    $service = app(VoterSlugService::class);

    $slug = $service->getValidatedSlug($slugString, $user, $election);
    
    if (!$slug) {
        abort(403, 'Invalid voting link');
    }

    $request->attributes->set('voter_slug', $slug);
    return $next($request);
}
```

### Pattern 3: Strict Validation

```php
public function submitVote(VoterSlug $slug, User $user, Election $election, array $selections)
{
    // Strict validation before processing
    $this->slugService->validateSlugOwnership($slug, $user, $election);
    
    // Safe to proceed
    $vote = Vote::create([...]);
    return $vote;
}
```

---

## Error Handling

### When getOrCreateSlug() Fails

```php
try {
    $slug = $service->getOrCreateSlug($user, $election);
} catch (Exception $e) {
    Log::error('Slug creation failed', [
        'user_id' => $user->id,
        'election_id' => $election->id,
        'error' => $e->getMessage(),
    ]);
    return back()->with('error', 'Unable to start voting session');
}
```

### When validateSlugOwnership() Fails

```php
try {
    $service->validateSlugOwnership($slug, $user, $election);
} catch (AccessDeniedHttpException $e) {
    Log::warning('Slug ownership validation failed');
    abort(403, 'Unauthorized');
}
```

---

## Configuration

```php
config('voting.slug_expiration_minutes', 30)  // Default: 30 minutes
```

---

## Performance

### Slug Reuse

- Demo elections: Always create new slugs
- Real elections: Reuse active non-expired slugs to reduce writes

### Database Indexes

Create indexes on:
- `UNIQUE(election_id, user_id)`
- `expires_at` (for cleanup)
- `is_active` (for status checks)

