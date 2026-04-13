# Election Settings — Developer Guide

## Overview

Election Settings allow organization admins to configure per-election voting rules and security constraints without modifying code or environment variables. This feature includes IP-based access restrictions, no-vote/abstain options, and flexible candidate selection rules.

**Status:** Phase 1 Complete — Core settings, validation, and IP enforcement implemented
**Test Coverage:** 9/9 tests passing
**Architecture:** TDD-first, multi-tenant, optimistic locking

---

## Architecture

### Domain Model

Election Settings are stored directly on the `elections` table with 12 dedicated columns:

| Column | Type | Purpose |
|--------|------|---------|
| `ip_restriction_enabled` | boolean | Enable per-IP vote limits |
| `ip_restriction_max_per_ip` | integer | Max votes per IP (1–50) |
| `ip_whitelist` | json | CIDR/IP addresses exempt from limits |
| `no_vote_option_enabled` | boolean | Allow abstain/no-vote option |
| `no_vote_option_label` | string(100) | Custom label for abstain option |
| `selection_constraint_type` | enum/string | any\|exact\|range\|minimum\|maximum |
| `selection_constraint_min` | integer | Min candidates to select (if needed) |
| `selection_constraint_max` | integer | Max candidates to select |
| `settings_version` | integer | Optimistic lock token (default: 0) |
| `settings_updated_by` | uuid | FK to users.id (null-safe) |
| `settings_updated_at` | timestamp | Audit trail timestamp |
| `settings_changes` | json | Diff of changes for audit trail |

### Concurrency Control

**Optimistic Locking** prevents lost updates when multiple admins edit simultaneously:

1. Form displays `settings_version` (e.g., `version=5`)
2. Admin submits form with `version=5`
3. Controller compares request version to database version
4. **If mismatch:** Return validation error, request reload
5. **If match:** Increment version on save (5 → 6)

This avoids database locks while ensuring sequential consistency.

### IP Restriction (CIDR Support)

IP restrictions use bitwise operations to support both individual IPs and CIDR ranges:

```php
// Individual IP: 10.0.0.1
isIpWhitelisted('10.0.0.1')  // ✓ matches 10.0.0.1

// CIDR range: 10.0.0.0/8
isIpWhitelisted('10.0.0.1')   // ✓ matches (part of /8)
isIpWhitelisted('10.255.0.1') // ✓ matches (part of /8)
isIpWhitelisted('11.0.0.1')   // ✗ outside /8
```

Implementation uses `ip2long()` and bitwise AND (`&`) to calculate mask coverage.

### Authorization

Settings can be managed by:
- **Organization Owner/Admin** — via `UserOrganisationRole` with role='owner'|'admin'
- **Election Chief/Deputy** — via `ElectionOfficer` with role='chief'|'deputy' and status='active'

Policy check is performed in `ElectionPolicy::manageSettings()`.

---

## Database Schema

### Migration: `2026_04_12_000001_add_election_settings_columns.php`

**Features:**
- ✅ SQLite/MySQL compatibility (enum vs string for selection_constraint_type)
- ✅ Defensive column order (no `after()` constraints)
- ✅ Conditional foreign keys (SQLite doesn't support well)
- ✅ Proper rollback logic

**Down migration:**
- Drops foreign key (if not SQLite)
- Drops all 12 columns atomically

---

## Key Files

### Models

**`app/Models/Election.php`**
- `$fillable`: All 12 settings columns
- `$casts`: Proper type casting (boolean, array, datetime)
- `settingsUpdatedBy()`: BelongsTo relationship
- Helper methods:
  - `isIpRestricted()`: Check if IP limits enabled
  - `isNoVoteEnabled()`: Check if no-vote option enabled
  - `isIpWhitelisted(string $ip)`: CIDR-aware check
  - `validateSelectionCount(int $count)`: Apply constraint logic
  - `getSelectionConstraintType()`: Get constraint type safely

### Controller

**`app/Http/Controllers/Election/ElectionSettingsController.php`**

**`edit(Election $election)` — Display settings page**
- Authorizes `manageSettings` policy
- Loads `settingsUpdatedBy` relationship (eager load)
- Passes `hasVotes` flag for active election guard

**`update(Request $request, Election $election)` — Save settings**
1. Authorize `manageSettings` policy
2. **Optimistic lock check:** Compare request version to database
3. **Active election guard:** If election is active with votes, require `confirmed_active_changes` checkbox
4. **Validation:** 11 rules (boolean, integer ranges, enum, nullable integers)
5. **Build audit diff:** Track before/after values for all changed fields
6. **Save atomically:** Update all 12 fields + metadata (version, user, timestamp, changes)

### Views

**`resources/js/Pages/Elections/Settings/Index.vue`**

**Form State:**
```javascript
const form = ref({
  ip_restriction_enabled: election.ip_restriction_enabled ?? false,
  ip_restriction_max_per_ip: election.ip_restriction_max_per_ip ?? 4,
  ip_whitelist: election.ip_whitelist ?? [],
  no_vote_option_enabled: election.no_vote_option_enabled ?? false,
  no_vote_option_label: election.no_vote_option_label ?? 'No vote / Abstain',
  selection_constraint_type: election.selection_constraint_type ?? 'maximum',
  selection_constraint_min: election.selection_constraint_min ?? null,
  selection_constraint_max: election.selection_constraint_max ?? null,
  settings_version: election.settings_version ?? 0,  // Optimistic lock token
  confirmed_active_changes: false,
})
```

**Key Features:**
- **IP Whitelist Textarea** — Client-side CIDR validation
- **Selection Constraint Radio Group** — Conditional min/max inputs based on type
- **Active Election Warning** — Shows when `is_active && hasVotes`
- **Audit Trail Display** — Read-only section showing version, last updated, updated by
- **Flash Messages** — Success/warning feedback

### Routes

**`routes/election/electionRoutes.php`**

```php
Route::prefix('/elections/{election}')->group(function () {
    Route::get('/settings', [ElectionSettingsController::class, 'edit'])
        ->name('elections.settings.edit')
        ->can('manageSettings', 'election');

    Route::patch('/settings', [ElectionSettingsController::class, 'update'])
        ->name('elections.settings.update')
        ->can('manageSettings', 'election');
});
```

**URLs:**
- `GET  /elections/{slug}/settings` → View/edit form
- `PATCH /elections/{slug}/settings` → Save changes

### Enforcement

**`app/Http/Controllers/ElectionVotingController.php` — `start()` method**

IP restriction enforcement happens **before** voter slug creation:

```php
if ($election->isIpRestricted()) {
    $ip = $request->ip();
    
    // Whitelisted IPs bypass all limits
    if (!$election->isIpWhitelisted($ip)) {
        $votedCount = VoterSlug::where('election_id', $election->id)
            ->where('step_1_ip', $ip)
            ->where('has_voted', true)
            ->count();
        
        if ($votedCount >= $election->ip_restriction_max_per_ip) {
            abort(403, "Maximum votes allowed from your IP");
        }
    }
}
```

**Data Used:**
- `voter_slugs.step_1_ip` — Recorded when user completes Step 1 (code entry)
- `voter_slugs.has_voted = true` — Only counts completed votes
- Election's `ip_whitelist` and `ip_restriction_max_per_ip`

---

## Tests

### Test File: `tests/Feature/Election/ElectionSettingsTest.php`

**9 Tests — All Passing:**

#### Settings CRUD (3 tests)
1. `test_admin_can_view_settings_page` — Admin sees settings form
2. `test_admin_can_update_settings` — Settings persist correctly
3. `test_settings_version_increments_on_each_update` — Version increments from 0→1→2...

#### Access Control (2 tests)
4. `test_non_admin_cannot_update_settings` — Non-admin gets 403
5. `test_optimistic_lock_rejects_stale_version` — Stale version rejected with session errors

#### IP Restriction (2 tests)
6. `test_ip_restriction_blocks_excess_votes_from_same_ip` — Enforces per-IP limit
7. `test_whitelisted_ip_bypasses_restriction` — CIDR whitelist works (10.0.0.0/8 allows 10.0.0.1)

#### Ballot Options (2 tests)
8. `test_no_vote_option_setting_persists_correctly` — No-vote label saved
9. `test_selection_constraint_persists_correctly` — Constraint type & values saved

**Run Tests:**
```bash
php artisan test tests/Feature/Election/ElectionSettingsTest.php --no-coverage
# Output: Tests: 9 passed (34 assertions)
```

---

## Development Workflow

### Adding a New Setting

**Example: Add `voting_time_limit_minutes`**

#### Step 1: Create Migration
```php
// database/migrations/2026_XX_XX_000000_add_voting_time_limit.php
Schema::table('elections', function (Blueprint $table) {
    $table->unsignedInteger('voting_time_limit_minutes')->default(30)->nullable();
});
```

#### Step 2: Update Election Model
```php
// app/Models/Election.php
protected $fillable = [
    // ... existing columns
    'voting_time_limit_minutes',
];

protected $casts = [
    // ... existing casts
    'voting_time_limit_minutes' => 'integer',
];
```

#### Step 3: Add to Controller Validation
```php
// ElectionSettingsController::update()
$validated = $request->validate([
    // ... existing rules
    'voting_time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:480'],
]);
```

#### Step 4: Update Vue Form
```vue
<!-- resources/js/Pages/Elections/Settings/Index.vue -->
<div class="space-y-4">
  <label>Voting Time Limit (minutes)</label>
  <input 
    v-model.number="form.voting_time_limit_minutes"
    type="number" min="1" max="480" 
    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
  />
</div>
```

#### Step 5: Add Test
```php
public function test_voting_time_limit_persists()
{
    $response = $this->actingAs($this->admin)
        ->patch(route('elections.settings.update', $this->election->slug), [
            'voting_time_limit_minutes' => 45,
            'settings_version' => 0,
            // ... other fields
        ]);
    
    $this->election->refresh();
    $this->assertEquals(45, $this->election->voting_time_limit_minutes);
}
```

#### Step 6: Run Tests
```bash
php artisan test tests/Feature/Election/ElectionSettingsTest.php --no-coverage
```

---

## Common Tasks

### Enable IP Restrictions for an Election
```php
$election->update([
    'ip_restriction_enabled' => true,
    'ip_restriction_max_per_ip' => 2,
    'ip_whitelist' => ['192.168.1.0/24', '10.0.0.1'],
    'settings_version' => $election->settings_version + 1,
    'settings_updated_by' => auth()->user()->id,
    'settings_updated_at' => now(),
]);
```

### Validate Selection Count
```php
$isValid = $election->validateSelectionCount(3);
// Respects constraint_type: any, exact, range, minimum, maximum
```

### Check if IP is Whitelisted
```php
if ($election->isIpWhitelisted('10.0.0.50')) {
    // This IP bypasses vote limits
}
```

### Fetch Settings with Audit Info
```php
$election = Election::with('settingsUpdatedBy:id,name')->find($id);
echo $election->settings_updated_by->name;  // "Jane Admin"
echo $election->settings_changes;           // ["field" => ["from" => X, "to" => Y]]
```

---

## Performance Considerations

### Queries
- Settings are read from `elections` table on every vote attempt
- No N+1 problem (settings are denormalized)
- Eager load `settingsUpdatedBy` when displaying settings page

### Caching (Phase 2)
Future optimization: Cache settings with TTL, invalidate on update
```php
// Future pattern
Cache::remember("election-settings-{$id}", 3600, fn() => 
    $election->only(['ip_restriction_enabled', 'ip_whitelist', ...])
);
```

### Database Indexes
Current schema uses primary key + organisation_id scoping.
Consider adding index on `election_id` if settings lookup becomes a bottleneck.

---

## Known Limitations & Future Work

### Phase 1 (Complete)
- ✅ IP restriction with CIDR whitelist
- ✅ No-vote option
- ✅ Selection constraint storage
- ✅ Optimistic locking
- ✅ Active election guard
- ✅ Audit trail

### Phase 2 (Deferred)
- ⬜ Selection constraint **enforcement** in VoteController
- ⬜ Template-per-post required numbers (currently hardcoded)
- ⬜ Caching with invalidation
- ⬜ Bulk election settings import/export
- ⬜ Settings templates/presets

### Phase 3 (Future)
- ⬜ Settings history/rollback
- ⬜ Settings webhooks (notify external systems on change)
- ⬜ Mobile API support for settings queries

---

## Troubleshooting

### Issue: "Settings were modified by another user" Error
**Cause:** Concurrent edits — two admins submitted changes simultaneously
**Fix:** User reloads form to get latest version, then re-edits

### Issue: IP Restriction Not Blocking Votes
**Check:**
1. Is `ip_restriction_enabled = true`?
2. Is `step_1_ip` being recorded in `voter_slugs`?
3. Is `has_voted = true` for previous votes?
4. Does IP fall within whitelist?

### Issue: No-Vote Option Not Appearing on Ballot
**Check:**
1. Is `no_vote_option_enabled = true`?
2. Is Vue form correctly bound to `form.no_vote_option_enabled`?
3. Check browser console for form submission errors

### Issue: SQLite Migration Fails
**Cause:** `MODIFY COLUMN` not supported in SQLite
**Solution:** Migration includes conditional logic — verify `DB::getDriverName() === 'sqlite'`

---

# Phase 2: Voter Verification — Developer Guide

## Overview

**Status:** Foundation Complete — Admin endpoints working, enforcement TBD  
**Test Coverage:** 5/5 admin tests passing, 5 enforcement tests skipped  
**Architecture:** Per-voter, per-election identity verification via video call

Voter Verification allows election admins to verify voter identity (IP address and/or device fingerprint) during a video call, then restrict voting to only verified credentials. This is **per-voter** and **per-election**, separate from the election-level IP restriction count limit.

---

## Architecture

### Domain Model

Voter Verification is stored in a dedicated `voter_verifications` table:

| Column | Type | Purpose |
|--------|------|---------|
| `id` | uuid | Primary key |
| `election_id` | uuid | Foreign key to elections |
| `user_id` | uuid | Foreign key to users (the voter) |
| `organisation_id` | uuid | Foreign key to organisations (tenant isolation) |
| `verified_ip` | string(45) | IPv4/IPv6 address, nullable |
| `verified_device_fingerprint_hash` | string(64) | SHA256 hash of device fingerprint, nullable |
| `verified_device_components` | json | Device metadata (browser, OS, plugins), nullable |
| `verified_by` | uuid | FK to users (admin who verified) |
| `verified_at` | timestamp | When verification occurred |
| `notes` | text | Optional admin notes |
| `status` | string | active\|revoked |
| `revoked_by` | uuid | FK to users (admin who revoked), nullable |
| `revoked_at` | timestamp | When revoked, nullable |
| `created_at`, `updated_at` | timestamp | Audit timestamps |

**Indexes:**
- Primary: `id`
- Unique: `(election_id, user_id)` — One verification per voter per election
- Performance: `(election_id, status)` — Quick lookups during voting

### Verification Modes

The `elections.voter_verification_mode` column (Phase 1) controls enforcement:

```php
// Modes
'none'                // No verification required (default)
'ip_only'             // IP must match verified address
'fingerprint_only'    // Device fingerprint must match
'both'                // Both IP AND fingerprint must match
```

**Precedence Rule:**
```
Verified voter → bypass election IP count limit → check per-voter IP/fingerprint
Unverified voter → run election IP count limit as normal (if enabled)
```

### Authorization

Only users who can "manage settings" can verify voters:
- **Organization Owner/Admin** — via `UserOrganisationRole` with role='owner'|'admin'
- **Election Chief/Deputy** — via `ElectionOfficer` with role='chief'|'deputy'

Policy check: `ElectionPolicy::manageSettings()`

---

## Database Schema

### Migration: `2026_04_12_000003_create_voter_verifications_table.php`

**Features:**
- ✅ SQLite/MySQL compatible (uuid primary key, string status)
- ✅ Composite unique constraint: `(election_id, user_id)`
- ✅ Conditional foreign keys (SQLite compatible)
- ✅ Null-safe references (verified_by, revoked_by optional)
- ✅ Proper rollback logic

```php
// Key columns
$table->uuid('id')->primary();
$table->uuid('election_id');
$table->uuid('user_id');
$table->uuid('organisation_id');
$table->unique(['election_id', 'user_id']);
$table->index(['election_id', 'status']);
```

### Migration: `2026_04_12_000004_add_voter_verification_mode_to_elections.php`

Adds `voter_verification_mode` to elections table:
```php
$table->string('voter_verification_mode')->default('none');
```

---

## Key Files

### Models

**`app/Models/VoterVerification.php`**
- `HasFactory`, `HasUuids` traits
- **Relationships:**
  - `election()` — BelongsTo Election
  - `user()` — BelongsTo User (the voter)
  - `verifiedBy()` — BelongsTo User (admin who verified)
  - `revokedBy()` — BelongsTo User (admin who revoked)
  - `organisation()` — BelongsTo Organisation
- **Scopes:**
  - `active()` — Where status='active'
  - `forElection($electionId)` — Where election_id=$id
  - `forUser($userId)` — Where user_id=$id
- **Casts:**
  - `verified_device_components` — array
  - Timestamps — datetime

**`app/Models/Election.php`** (extended Phase 1)
- Added to `$fillable`: `'voter_verification_mode'`
- **Helper methods:**
  - `requiresVoterVerification()`: bool — Returns true if mode !== 'none'
  - `checksIp()`: bool — Returns true if mode in ['ip_only', 'both']
  - `checksFingerprint()`: bool — Returns true if mode in ['fingerprint_only', 'both']
- **Relationships:**
  - `voterVerifications()` — HasMany VoterVerification

### Controller

**`app/Http/Controllers/Election/VoterVerificationController.php`**

**`store(Request $request, Organisation $organisation, string $election)`** — Save or update verification
1. Resolve election from slug + organisation
2. Authorize `manageSettings` policy
3. Validate:
   - `user_id`: required, uuid, exists:users
   - `verified_ip`: nullable, ipv4
   - `verified_device_fingerprint_hash`: nullable, string, max:64
   - `verified_device_components`: nullable, array
   - `notes`: nullable, string, max:1000
4. Find or create `VoterVerification` record:
   - If exists: Update with new values, reset revoke fields
   - If new: Create with verified_by, verified_at, status='active'
5. Return redirect with success message

**`revoke(Request $request, Organisation $organisation, string $election, VoterVerification $verification)`** — Revoke verification
1. Resolve election from slug + organisation
2. Authorize `manageSettings` policy
3. Verify verification belongs to this election
4. Update: status='revoked', revoked_by, revoked_at
5. Return redirect with success message

### Routes

**`routes/organisations.php`** (nested under election group)
```php
Route::post('/voters/verify', [VoterVerificationController::class, 'store'])
    ->name('elections.voters.verify');

Route::delete('/voters/{verification}/revoke', [VoterVerificationController::class, 'revoke'])
    ->name('elections.voters.verification.revoke');
```

**URLs:**
- `POST /organisations/{slug}/elections/{slug}/voters/verify` — Create/update verification
- `DELETE /organisations/{slug}/elections/{slug}/voters/{id}/revoke` — Revoke verification

### Factories

**`database/factories/VoterVerificationFactory.php`**
- Generates test records with:
  - Random verified_ip: `fake()->ipv4()`
  - Random device fingerprint hash
  - Relationships via `->for()` (election, user, verifiedBy)
- State methods:
  - `revoked()` — Sets status='revoked' with revoked_by, revoked_at

---

## Tests

### Test File: `tests/Feature/Election/VoterVerificationTest.php`

**5 Admin Endpoint Tests — All Passing:**

1. `test_admin_can_save_voter_verification` — POST stores verification
   - Admin verifies voter with IP only
   - Record persists to database
   - Status='active'

2. `test_admin_can_save_verification_with_ip_only` — POST with nullable fingerprint
   - Only `verified_ip` populated
   - Database query confirms record
   - Admin can save partial data

3. `test_admin_can_revoke_voter_verification` — DELETE revokes verification
   - Delete endpoint updates status='revoked'
   - Records revoked_by and revoked_at
   - Original IP/fingerprint preserved

4. `test_re_verifying_updates_existing_record` — POST updates on duplicate
   - First POST creates record with IP X
   - Second POST with IP Y updates (doesn't create duplicate)
   - Unique constraint prevents duplication
   - Only one record exists

5. `test_non_admin_cannot_save_verification` — Authorization check
   - Non-admin voter role gets 403 Forbidden
   - manageSettings policy enforced

**5 Enforcement Tests — Skipped (TBD):**

6. `test_voter_not_verified_is_blocked_when_mode_not_none` — TBD
7. `test_voter_on_wrong_ip_is_blocked` — TBD
8. `test_voter_on_wrong_device_is_blocked` — TBD
9. `test_verification_not_enforced_when_mode_is_none` — TBD
10. `test_verified_voter_bypasses_election_ip_count_limit` — TBD

**Run Tests:**
```bash
php artisan test tests/Feature/Election/VoterVerificationTest.php --env=testing --no-coverage
# Output: Tests: 5 passed, 5 skipped
```

---

## Development Workflow

### Adding a New Verification Mode

**Example: Add `'phone_only'` mode**

#### Step 1: Update Election Model
```php
// app/Models/Election.php
public function checksPhone(): bool
{
    return in_array($this->voter_verification_mode, ['phone_only', 'both']);
}
```

#### Step 2: Update VoterVerification Migration
```php
// New migration: add verified_phone_hash column
$table->string('verified_phone_hash')->nullable();
```

#### Step 3: Update Controller Validation
```php
// VoterVerificationController::store()
$validated = $request->validate([
    // ... existing validations
    'verified_phone_hash' => ['nullable', 'string', 'max:64'],
]);
```

#### Step 4: Update Vue Form
```vue
<!-- Elections/Settings/Index.vue -->
<div v-if="form.voter_verification_mode === 'phone_only'">
  <label>Phone Verification Required</label>
  <input v-model="form.verified_phone_hash" type="text" />
</div>
```

#### Step 5: Implement Enforcement in VoteController
```php
// When enforcement is implemented
if ($election->checksPhone()) {
    $requestPhone = $request->header('X-Verified-Phone');
    if ($requestPhone !== $verification->verified_phone_hash) {
        abort(403, 'Phone mismatch');
    }
}
```

---

## Enforcement Implementation (Phase 2.2 — Future)

When voting enforcement is implemented, add this to `VoteController::create()` or equivalent:

```php
if ($election->requiresVoterVerification()) {
    $verification = VoterVerification::where('election_id', $election->id)
        ->where('user_id', auth()->id())
        ->where('status', 'active')
        ->first();

    // Not verified
    if (!$verification) {
        return Inertia::render('Vote/VoteDenied', [
            'reason' => 'not_verified',
            'message' => 'Your identity has not been verified.',
        ]);
    }

    // IP mismatch
    if ($election->checksIp()) {
        if ($request->ip() !== $verification->verified_ip) {
            return Inertia::render('Vote/VoteDenied', [
                'reason' => 'ip_mismatch',
                'message' => 'Voting from wrong IP address.',
            ]);
        }
    }

    // Fingerprint mismatch
    if ($election->checksFingerprint()) {
        $fp = $request->header('X-Device-Fingerprint');
        if ($fp !== $verification->verified_device_fingerprint_hash) {
            return Inertia::render('Vote/VoteDenied', [
                'reason' => 'device_mismatch',
                'message' => 'Voting from wrong device.',
            ]);
        }
    }

    // Verified voter bypasses election IP count limit
    // (IP count limit is not enforced for verified voters)
}
```

---

## Common Tasks

### Get Verification for a Voter
```php
$verification = VoterVerification::where('election_id', $election->id)
    ->where('user_id', $user->id)
    ->where('status', 'active')
    ->first();

if ($verification) {
    echo $verification->verified_ip;
}
```

### Revoke All Verifications for an Election
```php
VoterVerification::where('election_id', $election->id)
    ->where('status', 'active')
    ->update([
        'status' => 'revoked',
        'revoked_by' => auth()->id(),
        'revoked_at' => now(),
    ]);
```

### Bulk Import Verifications (Future)
```php
// Not yet implemented — would read CSV, validate, bulk create
$rows = [
    ['email' => 'user1@example.com', 'verified_ip' => '10.0.0.1'],
    ['email' => 'user2@example.com', 'verified_ip' => '10.0.0.2'],
];

foreach ($rows as $row) {
    $user = User::where('email', $row['email'])->first();
    if ($user) {
        VoterVerification::updateOrCreate(
            ['election_id' => $election->id, 'user_id' => $user->id],
            ['verified_ip' => $row['verified_ip'], 'status' => 'active']
        );
    }
}
```

---

## Performance Considerations

### Queries
- Verification lookups during voting use unique index `(election_id, user_id)` — O(1)
- No N+1 problem (each voter queried once)
- Eager load relationships when displaying admin lists

### Caching (Future Optimization)
```php
// Cache verified voter status for 5 minutes
$isVerified = Cache::remember(
    "voter-verification-{$election->id}-{$user->id}",
    300,
    fn() => VoterVerification::where('election_id', $election->id)
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->exists()
);
```

---

## Known Limitations & Future Work

### Phase 2.1 (Complete — Current)
- ✅ Admin endpoints (save/revoke)
- ✅ Database schema (voter_verifications)
- ✅ Model relationships
- ✅ Authorization checks
- ✅ Admin tests (5/5 passing)

### Phase 2.2 (Deferred — Next)
- ⬜ Voting enforcement in VoteController
- ⬜ Client-side fingerprint capture (native APIs)
- ⬜ VoterVerificationModal.vue (admin UI)
- ⬜ Voter dashboard (show verified status)
- ⬜ Enforcement tests (5 skipped)

### Phase 2.3 (Future)
- ⬜ Bulk verification import (CSV)
- ⬜ Phone-based verification
- ⬜ Email verification
- ⬜ Multi-factor verification
- ⬜ Verification history/audit report

---

## Troubleshooting

### Issue: "Cannot authorize manageSettings"
**Cause:** User doesn't have admin role in organisation or is not election chief/deputy  
**Fix:** Verify `UserOrganisationRole.role IN ('owner', 'admin')` or `ElectionOfficer.role IN ('chief', 'deputy')`

### Issue: "UNIQUE constraint failed" when verifying
**Cause:** Trying to create second verification for same voter/election  
**Fix:** Use `updateOrCreate()` or check if verification exists first

### Issue: Voter claims they're verified but system says no
**Check:**
1. Is `status='active'`? (not 'revoked')
2. Is `verified_at` not null?
3. Does `election_id` match current election?
4. Does `user_id` match current voter?

---

## References

- **VoterVerification Model:** `app/Models/VoterVerification.php`
- **Controller:** `app/Http/Controllers/Election/VoterVerificationController.php`
- **Tests:** `tests/Feature/Election/VoterVerificationTest.php`
- **Routes:** `routes/organisations.php`
- **Factory:** `database/factories/VoterVerificationFactory.php`
- **Migrations:** `database/migrations/2026_04_12_000003_*.php` and `2026_04_12_000004_*.php`
