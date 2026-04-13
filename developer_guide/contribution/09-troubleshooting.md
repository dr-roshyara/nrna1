# 09 — Troubleshooting

## Common Issues

### 1. "Table 'contributions' doesn't exist"

**Cause:** Migration hasn't been run.

**Fix:**
```bash
php artisan migrate
```

If the migration was already run but rolled back:
```bash
php artisan migrate:status
# Look for 2026_04_11_000001_create_contributions_tables
```

---

### 2. Points preview doesn't match awarded points

**Cause:** The frontend formula and backend formula are out of sync.

**How to verify:**
1. Check `TRACK_CONFIG` in `Create.vue` matches `TRACK_CONFIG` in `GaneshStandardFormula.php`
2. Check the order of operations — the backend uses `floor()`, not `round()`
3. Check that the frontend applies weekly cap only for micro track

**The backend is always authoritative.** The frontend preview is an estimate.

---

### 3. Weekly cap not enforcing correctly

**Cause:** The `getWeeklyPoints()` method uses ISO week boundaries (`startOfWeek()` / `endOfWeek()`).

**Debug:**
```php
// Check what the service thinks the weekly total is
$service = app(ContributionPointsService::class);
$weekly = $service->getWeeklyPoints($userId, $orgId);
dd($weekly, now()->startOfWeek(), now()->endOfWeek());
```

**Common pitfall:** Tests running near midnight on Sunday may see unexpected week boundaries.

---

### 4. Leaderboard shows no entries

**Possible causes:**
1. No `points_ledger` entries with `action = 'earned'` exist
2. All users have `leaderboard_visibility = 'private'`
3. The `organisation_id` doesn't match

**Debug:**
```php
// Check raw ledger data
DB::table('points_ledger')
    ->where('organisation_id', $orgId)
    ->where('action', 'earned')
    ->get();

// Check user visibility
User::whereIn('id', $userIds)
    ->pluck('leaderboard_visibility', 'id');
```

---

### 5. "Contributor #N" numbering seems wrong

**This is by design.** The anonymous counter is sequential based on the order anonymous users appear in the result set, not based on their rank. If rank 1 is public, rank 2 is anonymous, and rank 3 is anonymous, they appear as:

| Rank | Visibility | Display |
|------|-----------|---------|
| 1 | public | "John Smith" |
| 2 | anonymous | "Contributor #1" |
| 3 | anonymous | "Contributor #2" |

---

### 6. BelongsToTenant scope interfering with queries

**Symptom:** Queries return empty results even though data exists in the database.

**Cause:** The `BelongsToTenant` global scope filters by the session's current organisation. Service-layer queries that don't rely on session state need `withoutGlobalScopes()`.

**Already handled:** `ContributionPointsService::getWeeklyPoints()` uses `withoutGlobalScopes()` and filters by `organisation_id` explicitly.

**If you add new queries:** Always use `withoutGlobalScopes()` in service-layer code and add explicit `where('organisation_id', $orgId)`.

---

### 7. Contribution form validation errors not showing

**Check:**
1. The Vue component maps `onError` to `errors.value`
2. Error display uses `v-if="errors.title"` (not `v-if="errors.value.title"`)
3. The `errors` ref is cleared on each submit (`errors.value = {}`)

---

### 8. Ledger entry created with 0 points

**This is correct behavior.** The system intentionally writes ledger entries even for zero-point results. This provides:
- Audit trail showing the contribution was processed
- Evidence that the weekly cap was exhausted at the time
- No "missing" entries in the ledger timeline

---

### 9. Tests failing with "relation organisations does not exist"

**Cause:** Test database hasn't been migrated.

**Fix:**
```bash
php artisan migrate --env=testing
```

Or ensure `.env.testing` has `DB_DATABASE` pointing to the test database.

---

### 10. Vue page shows blank white screen

**Check:**
1. The Inertia render call matches the file path: `Inertia::render('Contributions/Create')` → `resources/js/Pages/Contributions/Create.vue`
2. Frontend has been built: `npm run build` (or `npm run dev` for development)
3. No JavaScript console errors — check browser dev tools
4. The `PublicDigitLayout` component is importable from `@/Layouts/PublicDigitLayout.vue`
