# Fix: Create Election Page — Constitutional Alignment + Date UX
**Type:** TDD Feature Fix
**Scope:** Controller validation, test repair, Vue date UX
**Date:** 2026-05-21

---

## Context

The create election form at `/organisations/{org}/elections/create` has three categories of problems:

1. **Test/controller mismatch:** `ElectionCreationTest` sends `start_date`/`end_date` as plain date strings and asserts validation errors. The controller **ignores** these fields and derives them from voting phase timestamps. Four validation tests silently fail.

2. **`expected_voter_count` never saved:** The Vue form collects it, the user sees the ">40 requires approval" warning, but the controller never validates or stores it. This breaks the approval workflow: the constitution uses this value to auto-approve (≤40) or route to manual review (>40).

3. **Date UX not constitutional:**
   - Voting dates default to **today** (which fails past-date guards)
   - The form blocks submission if any dates are out of chronological order — but dates are **not required at creation** per the constitution; they're only required before `open_voting`
   - The form structure implies all phases must be planned at create time

**Constitutional reality:** `state = 'draft'` at creation. The only preconditions enforced at later transitions:
- `submit_for_approval` → requires `timezone_set`
- `open_voting` → requires `voting_window_defined` + `timezone_set`
- All date fields are nullable at creation

---

## What Changes

### 1. `ElectionManagementController::store()` (app/Http/Controllers/Election/ElectionManagementController.php ~line 99)

**Add to validation rules:**
```php
'expected_voter_count' => ['required', 'integer', 'min:1', 'max:10000'],
'timezone'             => ['nullable', 'string', 'timezone:all'],
```

**Add to Election::create([...]):**
```php
'expected_voter_count' => $validated['expected_voter_count'],
'timezone'             => $validated['timezone'] ?? null,
```

**Remove `validateTimelineForEdit()` call on creation** — timeline validation is a setup-time concern, not creation. An election in `draft` state has no required dates.

**Keep** `start_date`/`end_date` derivation from voting dates — they are still used in active WHERE queries (list endpoint lines 529-550). They are NOT authoritative (derived from `voting_starts_at`/`voting_ends_at`) but must stay in sync. Null at creation is fine.

---

### 2. `ElectionCreationTest.php` (tests/Feature/Election/ElectionCreationTest.php)

**Remove 4 broken tests** (they assert validation errors on fields the controller doesn't validate):
- `test_election_requires_start_date`
- `test_election_requires_end_date`
- `test_start_date_must_be_before_end_date`
- `test_start_date_cannot_be_in_past`

**Fix `validPayload()`** — update to use constitutional fields:
```php
private function validPayload(): array
{
    return [
        'name'                 => 'General Election 2026',
        'description'          => 'Election for organisation leadership',
        'expected_voter_count' => 20,
    ];
}
```

**Fix status assertion** — constitutional state is `draft`, not `planned`:
```php
// test_organisation_owner_can_create_election
$this->assertDatabaseHas('elections', [
    'organisation_id' => $this->org->id,
    'name'            => 'General Election 2026',
    'type'            => 'real',
    'state'           => 'draft',   // was 'status' => 'planned'
]);

// Rename test_election_defaults_to_planned_status → test_election_defaults_to_draft_state
$this->assertEquals('draft', $election->state);
```

**Add new constitutional tests (7 total):**

*expected_voter_count tests:*
```php
public function test_election_requires_expected_voter_count(): void
public function test_expected_voter_count_must_be_at_least_1(): void
public function test_expected_voter_count_is_saved_on_creation(): void
```

*Approval-routing tests (constitutional consequences):*
```php
// ≤40 voters → engine can auto-approve (expected_voter_count persisted correctly)
public function test_small_election_saves_expected_voter_count_for_auto_approval(): void
// >40 voters → expected_voter_count stored, triggers manual approval path
public function test_large_election_saves_expected_voter_count_for_manual_review(): void
```

*Lifecycle capability tests (draft invariants):*
```php
public function test_draft_election_created_with_null_dates(): void
public function test_draft_election_state_is_draft(): void
```

---

### 3. `Create.vue` (resources/js/Pages/Organisations/Elections/Create.vue)

**Fix date defaults (suggestions, not persisted pre-fills):**
- Voting start/end: leave empty, show placeholder text (e.g., "e.g. 2026-06-30T09:00")
- Helps user understand format without silently pre-filling operational schedules
- If user explicitly types a date, validate it (must be future, end after start)

**Relax `phasesDatesError` validation:**
- Current: blocks submit if ANY date is filled but not all chronologically valid
- New: only validate each phase if BOTH start AND end for that phase are provided; only validate cross-phase if adjacent phases are both filled

**Add timezone dropdown (visible, constitutional):**
```vue
<select v-model="form.timezone">
  <option value="">Select timezone...</option>
  <option value="UTC">UTC</option>
  <option value="Europe/Berlin">Europe/Berlin (CET/CEST)</option>
  <option value="Europe/London">Europe/London (GMT/BST)</option>
  <option value="Asia/Kathmandu">Asia/Kathmandu (NPT)</option>
</select>
```

**Update submit payload** to include `timezone`.

---

## TDD Execution

### Phase 1 — RED (Existing Failures)
```bash
php artisan test tests/Feature/Election/ElectionCreationTest.php --no-coverage
```
Expected failures: 4 date tests + status assertions.

### Phase 2 — RED (New Tests)
Write 5 new constitutional tests. All should fail RED:
- `test_election_requires_expected_voter_count` — fails, field not validated yet
- `test_expected_voter_count_is_saved_on_creation` — fails, not stored

### Phase 3 — GREEN (Controller)
Apply 3 controller changes (validation + storage + remove validateTimelineForEdit).
Run tests → new tests GREEN.

### Phase 4 — GREEN (Test cleanup)
Remove 4 broken tests, fix status→state, fix validPayload().

### Phase 5 — GREEN (Vue)
Fix date defaults, relax validation, add timezone.

### Phase 6 — VERIFY
```bash
php artisan test tests/Feature/Election/ElectionCreationTest.php --no-coverage
php artisan test tests/Unit/Application/Election/ --no-coverage
```

---

## Critical Files

| File | Change |
|------|--------|
| `app/Http/Controllers/Election/ElectionManagementController.php` | Add expected_voter_count + timezone; remove validateTimelineForEdit() call |
| `tests/Feature/Election/ElectionCreationTest.php` | Remove 4 broken tests; fix state; add 5 new tests; fix validPayload() |
| `resources/js/Pages/Organisations/Elections/Create.vue` | Fix date defaults; relax validation; add timezone field |
| `resources/js/locales/pages/Organisations/Elections/Create/en.json` | Add timezone field label/help |

---

## Definition of Done

- [ ] `expected_voter_count` saved to DB on creation (drives approval workflow)
- [ ] `timezone` optional on creation but accepted and stored
- [ ] No `validateTimelineForEdit()` call on creation
- [ ] All dates nullable at creation
- [ ] Voting date defaults are +30/+37 days from today
- [ ] Phase dates never block submission if empty
- [ ] All 5 new constitutional tests GREEN
- [ ] 4 old broken date tests removed
- [ ] `state = 'draft'` asserted (not `status = 'planned'`)
- [ ] No regressions in existing green tests
