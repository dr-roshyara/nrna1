# Demo Election — Troubleshooting

---

## Public Demo: "Demo election is not currently available" (503)

**Symptom:** Visiting `/public-demo/start` returns a 503 error.

**Cause:** No demo election exists and auto-creation failed because no default platform organisation is set.

**Fix:**
```php
// In tinker
$org = Organisation::where('type', 'platform')->first();
$org->update(['is_default' => true]);

// Or create one
Organisation::create([
    'name' => 'PublicDigit Platform',
    'type' => 'platform',
    'is_default' => true,
    'slug' => 'publicdigit',
]);
```

Then visit `/public-demo/start` again — the demo election will be auto-created.

---

## Auth Demo: Voter Slug Not Found / `InvalidVoterSlugException`

**Symptom:** Error thrown by `VerifyVoterSlug` middleware on `/v/{slug}/*` routes.

**Cause:** The `DemoVoterSlug` record was deleted or expired between page loads.

**Fix (user-facing):** Have the user restart the demo via `/election/demo/start`.

**Fix (developer — testing):** Ensure `DemoVoterSlug` is active in your test:
```php
$slug = DemoVoterSlug::factory()->create([
    'user_id' => $this->user->id,
    'election_id' => $this->election->id,
    'is_active' => true,
    'status' => 'active',
    'expires_at' => now()->addMinutes(30),
]);
```

---

## Auth Demo: `SlugOwnershipException` — "This voting session does not belong to you"

**Symptom:** 403 error when accessing a slug URL.

**Cause:** The slug's `user_id` does not match `auth()->id()`. This can happen if a user shares their voting URL with another account.

**This is expected security behaviour.** Each slug is tied to one user.

**Fix (user-facing):** Have the user restart the demo at `/election/demo/start` to get a fresh slug.

---

## Public Demo: Step 1 Code Rejected Even With Correct Code

**Symptom:** Entering the displayed code returns a validation error.

**Cause 1:** The `PublicDemoSession` record was deleted between page load and form submit (e.g. 404 route error).

**Cause 2:** Case sensitivity — the `display_code` is stored uppercase (`ABCD-1234`) but the user entered lowercase.

**Fix in controller:** The `codeVerify()` method compares raw strings. If case is an issue, normalise on comparison:
```php
if (strtoupper(trim($request->code)) !== $publicDemoSession->display_code) {
```

---

## Auth Demo: Candidates Array Is Empty on Step 3

**Symptom:** The vote page renders but no candidates appear.

**Cause:** `DemoCandidacy` records have `organisation_id` filtered out by the `BelongsToTenant` global scope. The `current_organisation_id` in session doesn't match the election's `organisation_id`.

**Fix in tests:**
```php
session(['current_organisation_id' => $this->organisation->id]);
```

**Fix in controller:** Always use `withoutGlobalScopes()` when loading candidates for demo:
```php
DemoCandidacy::withoutGlobalScopes()->where('post_id', $post->id)->get();
```

---

## `SQLSTATE: table demo_codes has no column named has_code1_sent`

**Symptom:** Many tests fail with this error.

**Cause:** Some test files reference `has_code1_sent` but the migration that added this column was not applied to the test database.

**Fix:**
```bash
php artisan migrate --env=testing
```

This is a pre-existing issue in the test suite, not caused by the public demo feature.

---

## Public Demo: Same Visitor Gets New Session on Each Visit

**Symptom:** `/public-demo/start` creates a new `PublicDemoSession` every visit.

**Cause:** The Laravel session driver is set to `array` (in-memory) in tests. In production, ensure the session driver persists (e.g. `database` or `file`).

**Verify:**
```bash
# .env
SESSION_DRIVER=database
```

```bash
php artisan session:table
php artisan migrate
```

---

## Public Demo: `Route [public-demo.start] not defined` in Tests

**Cause:** Route cache is stale.

**Fix:**
```bash
php artisan route:clear
php artisan cache:clear
```

---

## Demo Election Auto-Created With Wrong Organisation

**Symptom:** `DemoElectionResolver::getPublicDemoElection()` creates a demo election for the wrong organisation.

**Cause:** `Organisation::getDefaultPlatform()` returns the wrong org (multiple orgs with `is_default = true`).

**Fix:** Ensure only one platform organisation has `is_default = true`:
```sql
UPDATE organisations SET is_default = false WHERE type = 'platform';
UPDATE organisations SET is_default = true WHERE slug = 'publicdigit' AND type = 'platform';
```

---

## Logs for Debugging

Key log messages to search in `storage/logs/laravel.log`:

| Log message | What it means |
|-------------|--------------|
| `🎯 [DemoElectionResolver] Finding demo election` | Election resolution started |
| `🔨 Auto-creating org-specific demo election` | Creating demo election for first time |
| `✅ Using org-specific demo election` | Resolver found the right election |
| `🎬 Demo election start requested` | User clicked "Demo Versuchen" |
| `✅ Voter slug created` | Slug generation succeeded |
| `SECURITY: Slug user mismatch detected` | Someone tried to use another user's slug |
| `🔥 VERIFY VOTER SLUG - START` | Middleware running |
