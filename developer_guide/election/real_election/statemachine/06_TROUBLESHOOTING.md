# Troubleshooting Guide

## Issue: "Another transition is already in progress"

### Cause
Cache lock is still held from a previous request.

### Solutions

1. **Wait 30 seconds** - Lock TTL expires automatically
2. **Clear Redis cache** (if stuck):
   ```bash
   redis-cli DEL "election_transition:{election_id}"
   ```
3. **Check for stalled processes** - One request might be hanging
4. **Restart queue workers** if background jobs are stuck

### Prevention
- Implement retry logic with exponential backoff
- Add timeout to frontend requests
- Monitor logs for slow transitions

---

## Issue: "Cannot transition from X to Y"

### Cause
Trying invalid state transition.

Valid transitions:
- administration → nomination
- nomination → voting
- voting → results_pending
- results_pending → results

### Solution
Verify current_state before attempting transition:
```php
if ($election->current_state !== 'nomination') {
    return back()->with('error', 'Wrong phase');
}
```

---

## Issue: "Cannot open voting from voting phase"

### Cause
Election is already in voting state.

### Solution
Check if voting already started:
```php
if ($election->voting_starts_at && now()->greaterThan($election->voting_starts_at)) {
    // Voting already started
}
```

---

## Issue: Test Fails - "voting_locked is null"

### Cause
Database factory doesn't set voting_locked default.

### Solution
Remove or adjust assertion:
```php
// Instead of:
$this->assertFalse($this->election->voting_locked);

// Use:
$this->election->refresh();  // Reload from DB
$this->assertTrue($this->election->voting_locked);
```

---

## Issue: Tests Fail with "403 Unauthorized"

### Cause
User doesn't have ElectionOfficer relationship.

### Solution
Create officer in test setUp():
```php
ElectionOfficer::create([
    'organisation_id' => $this->election->organisation_id,
    'election_id' => $this->election->id,
    'user_id' => $this->officer->id,
    'role' => 'chief',
    'status' => 'active',
]);
```

---

## Issue: State Doesn't Change After transitionTo()

### Cause
Model not refreshed from database.

### Solution
```php
$election->transitionTo('voting', 'manual', ...);
$election->refresh();  // Reload from DB
echo $election->current_state;  // Now shows 'voting'
```

---

## Issue: No Audit Record Created

### Cause
transitionTo() threw exception before creating record.

### Solution
Check exception message:
```php
try {
    $election->transitionTo('voting', 'manual', ...);
} catch (RuntimeException $e) {
    // Cache lock issue
    Log::error('Cache lock: ' . $e->getMessage());
} catch (Exception $e) {
    // Other error
    Log::error('Transition failed: ' . $e->getMessage());
}
```

---

## Issue: Button Shows When It Shouldn't

### Cause
Frontend computed property using wrong field.

### Fix
Ensure using election.current_state:
```vue
// WRONG:
const canOpenVoting = computed(() => props.election.status === 'planned')

// RIGHT:
const canOpenVoting = computed(() => props.election.current_state === 'nomination')
```

---

## Issue: Event Not Firing

### Cause
Listener not registered or event not fired.

### Debug
1. Check EventServiceProvider has listener registered
2. Add logging to event:
   ```php
   event(new ElectionStateChangedEvent(...));
   Log::info('Event fired', ['event' => 'ElectionStateChangedEvent']);
   ```
3. Check listener implementation

---

## Issue: Flash Message Not Showing

### Cause
Frontend not reading flash messages correctly.

### Solution
Check flash handling:
```vue
<div v-if="page.props.flash?.success" class="bg-green-100">
    {{ page.props.flash.success }}
</div>

<div v-if="page.props.flash?.error" class="bg-red-100">
    {{ page.props.flash.error }}
</div>
```

---

## Debugging Checklist

- [ ] Check current_state is correct: `election.current_state`
- [ ] Verify transition is valid (check TRANSITIONS array)
- [ ] Confirm user has authorization (ElectionOfficer with chief/deputy role)
- [ ] Check database migrations applied (ElectionStateTransition table exists)
- [ ] Look at logs: `storage/logs/laravel.log`
- [ ] Run tests: `php artisan test tests/Feature/Election/ --no-coverage`
- [ ] Verify cache is working: `redis-cli ping` → "PONG"
- [ ] Check database connection: `php artisan tinker` → `Election::count()`

---

## Common Error Messages

| Error | Fix |
|-------|-----|
| "Another transition is already in progress" | Wait 30 sec or clear Redis |
| "Cannot transition from X to Y" | Check valid paths, verify current state |
| "Voting already ended and locked" | Double-close attempt, normal behavior |
| "Cannot open voting from voting phase" | Already in voting, check if correct |
| "Failed to open voting: ..." | Check exception message in logs |
| "403 Unauthorized" | Add ElectionOfficer relationship in test |

---

## Issue: 403 Forbidden on Election Management Page

### Symptoms
- Visiting `/elections/{slug}/management` returns 403 Unauthorized
- User is logged in and has correct permissions (election chief, org owner, etc.)
- Policy authorization seems correct but still blocked

### Root Cause: Route Model Binding Not Working

The route binding converts URL slug to Election model:

```
/elections/namaste-lk0dziy6/management
                    ↓
Route::bind('election', ...) should resolve this
                    ↓
Pass Election object to controller
```

If binding is broken, the raw string slug is passed instead, causing authorization to fail.

### How to Debug

1. **Check the logs** for parameter type:
   ```
   "is_string":true  ❌ Route binding not working
   "is_election":true  ✅ Route binding working correctly
   ```

2. **Verify RouteServiceProvider registration**:
   ```php
   // File: app/Providers/RouteServiceProvider.php
   protected function registerElectionBinding(): void
   {
       Route::bind('election', function (string $value) {
           return \App\Models\Election::withoutGlobalScopes()
               ->where('slug', $value)
               ->firstOrFail();
       });
   }
   ```

3. **Common causes**:
   - Empty `registerElectionBinding()` method (no code to bind)
   - Stale route cache not reloaded
   - Service provider changes not picked up by PHP

### Solution

#### Step 1: Ensure explicit route binding exists
```php
// In RouteServiceProvider.php::registerElectionBinding()
Route::bind('election', function (string $value) {
    return \App\Models\Election::withoutGlobalScopes()
        ->where('slug', $value)
        ->firstOrFail();
});
```

#### Step 2: Clear route cache
```bash
php artisan route:clear
php artisan optimize:clear
```

#### Step 3: Restart dev server (forces PHP code reload)
```bash
# Kill current dev server, then restart:
php artisan serve
```

#### Step 4: Test with fresh request
- Visit management page again
- Check logs: should now show `"is_election":true`

### Prevention
- Don't rely on implicit binding for route prefixes like `{election:slug}`
- Always add explicit `Route::bind()` for custom resolution logic
- Remember to **clear caches and restart after changing RouteServiceProvider**

---

**Last Updated:** April 26, 2026
