# Voter Import State Gate Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Gate voter import (page, preview, template download, and actual import) so it is only accessible when the election is in `setup_administration` state — any other state returns 403.

**Architecture:** Add `abort_if($election->state !== 'setup_administration', 403, ...)` to all four action methods in `VoterImportController`. Write the failing test first, then implement the gate, then update two existing test files whose setup creates elections without state.

**Tech Stack:** Laravel 11, PHPUnit, `--env=testing` (PostgreSQL `nrna_test` DB), RefreshDatabase via `tests/TestCase.php`.

---

## Context

The `/organisations/{org}/elections/{election}/voters/import` route is currently reachable regardless of election lifecycle state. An election in `draft`, `submitted_for_approval`, `voting_active`, etc. can still receive voter imports. Business rule: voter import must only be possible when the election is in the `setup_administration` phase (the period where governance infrastructure — posts, voters, committee — is built).

---

## Files

| Action | File | Purpose |
|---|---|---|
| **Create** | `tests/Feature/Election/VoterImportStateGateTest.php` | New test class — all state-gate assertions |
| **Modify** | `app/Http/Controllers/Election/VoterImportController.php` | Add `abort_if` state check to `create()`, `template()`, `preview()`, `import()` |
| **Modify** | `tests/Feature/Election/CsvVoterImportTest.php` | Set `state` to `setup_administration` in `setUp()` — currently omitted, will break once gate is added |
| **Modify** | `tests/Feature/Voter/VoterImportElectionOnlyTest.php` | Same fix — set `state` to `setup_administration` |

---

## Task 1: Write the failing state-gate tests

**Files:**
- Create: `tests/Feature/Election/VoterImportStateGateTest.php`

### Step 1a: Write the test class

Create `tests/Feature/Election/VoterImportStateGateTest.php` with this content:

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterImportStateGateTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        \App\Services\TenantContext::set($this->org->id);

        $this->officer = User::factory()->create(['email_verified_at' => now()]);

        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->officer->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        session(['current_organisation_id' => $this->org->id]);
    }

    // ── helper ────────────────────────────────────────────────────────────────

    private function makeElectionInState(string $state): Election
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['state' => $state, 'voter_source_strategy' => 'election_only']);

        ElectionOfficer::create([
            'id'              => (string) Str::uuid(),
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $this->officer->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        return $election;
    }

    // ── create (page) ────────────────────────────────────────────────────────

    /** @test */
    public function import_page_is_accessible_in_setup_administration_state(): void
    {
        $election = $this->makeElectionInState('setup_administration');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertOk();
    }

    /** @test */
    public function import_page_is_forbidden_in_draft_state(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    /** @test */
    public function import_page_is_forbidden_in_voting_active_state(): void
    {
        $election = $this->makeElectionInState('voting_active');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    /** @test */
    public function import_page_is_forbidden_in_approved_state(): void
    {
        $election = $this->makeElectionInState('approved');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    // ── template download ─────────────────────────────────────────────────────

    /** @test */
    public function template_download_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.template', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    // ── preview ───────────────────────────────────────────────────────────────

    /** @test */
    public function preview_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]), [
                'file' => UploadedFile::fake()->create('voters.csv', 1, 'text/csv'),
            ]);

        $response->assertForbidden();
    }

    // ── import ────────────────────────────────────────────────────────────────

    /** @test */
    public function import_action_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('elections.voters.import', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]), [
                'file'      => UploadedFile::fake()->create('voters.csv', 1, 'text/csv'),
                'confirmed' => '1',
            ]);

        $response->assertForbidden();
    }

    // ── tutorial (always accessible) ─────────────────────────────────────────

    /** @test */
    public function tutorial_is_accessible_regardless_of_state(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.tutorial', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertOk();
    }

    // ── edge cases ────────────────────────────────────────────────────────────

    /** @test */
    public function import_page_is_forbidden_when_state_is_null(): void
    {
        $election = $this->makeElectionInState('draft');
        $election->update(['state' => null]);

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    /** @test */
    public function unauthorized_users_cannot_access_import_even_in_correct_state(): void
    {
        $election = $this->makeElectionInState('setup_administration');
        $stranger = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($stranger)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }
}
```

- [ ] **Step 1b: Verify the route names exist**

```bash
php artisan route:list --name=elections.voters.import --env=testing 2>&1 | head -20
```

Expected: lines listing `elections.voters.import.create`, `elections.voters.import.template`, `elections.voters.import.preview`, `elections.voters.import` (POST).

> **If route names differ**, adjust the `route(...)` calls in the test class to match actual names from `php artisan route:list`.

- [ ] **Step 1c: Run the new tests to confirm they FAIL**

```bash
php artisan test tests/Feature/Election/VoterImportStateGateTest.php --env=testing
```

Expected: multiple failures — currently no state gate exists so the 403-expected tests will get 200 or redirects, and the 200-expected test may also fail if state `setup_administration` has no special setup needed.

---

## Task 2: Add state gate to VoterImportController

**Files:**
- Modify: `app/Http/Controllers/Election/VoterImportController.php`

Add the gate immediately after `$this->authorize('manageVoters', $election)` in each of the four methods. The gate is identical in all four — extract to a private helper to keep it DRY.

- [ ] **Step 2a: Add private helper + gates to all four methods**

In `app/Http/Controllers/Election/VoterImportController.php`, add this private method at the bottom of the class (before the closing `}`):

```php
private function assertAdministrationSetupState(Election $election): void
{
    if ($election->state !== 'setup_administration') {
        \Log::warning('Voter import blocked — wrong election state', [
            'election_id'    => $election->id,
            'current_state'  => $election->state,
            'required_state' => 'setup_administration',
            'user_id'        => auth()->id(),
            'ip'             => request()->ip(),
        ]);

        abort(403, 'Voter import is only available during the administration setup phase.');
    }
}
```

Then update each of the four public methods to call it right after `$this->authorize(...)`:

**`create()` — after line `$this->authorize('manageVoters', $election);`:**
```php
$this->authorize('manageVoters', $election);
$this->assertAdministrationSetupState($election);
```

**`tutorial()` — same position:**
```php
$this->authorize('manageVoters', $election);
$this->assertAdministrationSetupState($election);
```

**`template()` — same position:**
```php
$this->authorize('manageVoters', $election);
$this->assertAdministrationSetupState($election);
```

**`preview()` — same position:**
```php
$this->authorize('manageVoters', $election);
$this->assertAdministrationSetupState($election);
```

**`import()` — same position:**
```php
$this->authorize('manageVoters', $election);
$this->assertAdministrationSetupState($election);
```

> `publicTutorial()` does NOT get the gate — it has no election context and is informational only.

- [ ] **Step 2b: Run the new state-gate tests — expect them to PASS now**

```bash
php artisan test tests/Feature/Election/VoterImportStateGateTest.php --env=testing
```

Expected: all tests green.

- [ ] **Step 2c: Commit**

```bash
git add app/Http/Controllers/Election/VoterImportController.php \
        tests/Feature/Election/VoterImportStateGateTest.php
git commit -m "feat: gate voter import to setup_administration state only

Import page, preview, template download, and import action all return 403
when the election is not in setup_administration state.

Tests: VoterImportStateGateTest (7 assertions)"
```

---

## Task 3: Fix broken existing tests

Adding the gate will cause two existing test files to fail because their setUp() creates elections without setting `state` to `setup_administration`. Fix them.

**Files:**
- Modify: `tests/Feature/Election/CsvVoterImportTest.php`
- Modify: `tests/Feature/Voter/VoterImportElectionOnlyTest.php`

- [ ] **Step 3a: Run existing import tests to confirm they now fail**

```bash
php artisan test tests/Feature/Election/CsvVoterImportTest.php \
                 tests/Feature/Voter/VoterImportElectionOnlyTest.php \
                 --env=testing
```

Expected: failures — the elections are in default `draft` state; gate now blocks them.

- [ ] **Step 3b: Fix CsvVoterImportTest setUp**

In `tests/Feature/Election/CsvVoterImportTest.php`, find the election factory call in `setUp()`:

```php
$this->election = Election::factory()
    ->forOrganisation($this->org)
    ->real()
    ->create(['status' => 'active']);
```

Change to:

```php
$this->election = Election::factory()
    ->forOrganisation($this->org)
    ->real()
    ->create(['status' => 'active', 'state' => 'setup_administration']);
```

> Note: `status` is a different column from `state`. Keep `status => 'active'`; add `state => 'setup_administration'`.

- [ ] **Step 3c: Fix VoterImportElectionOnlyTest setUp**

In `tests/Feature/Voter/VoterImportElectionOnlyTest.php`, find the election factory call. Look for `Election::factory()` in `setUp()` and add `'state' => 'setup_administration'` to the `create([...])` call.

Exact change will depend on what fields are already set — add `'state' => 'setup_administration'` to the existing `create([...])` array.

- [ ] **Step 3d: Run all three test files together**

```bash
php artisan test tests/Feature/Election/CsvVoterImportTest.php \
                 tests/Feature/Voter/VoterImportElectionOnlyTest.php \
                 tests/Feature/Election/VoterImportStateGateTest.php \
                 --env=testing
```

Expected: all green.

- [ ] **Step 3e: Commit**

```bash
git add tests/Feature/Election/CsvVoterImportTest.php \
        tests/Feature/Voter/VoterImportElectionOnlyTest.php
git commit -m "test: update voter import tests to use setup_administration state

Existing tests used default draft state; state gate now requires
setup_administration — update setUp() factories to match."
```

---

## Verification

After all tasks are complete, run the full voter import test suite:

```bash
php artisan test tests/Feature/Election/VoterImportStateGateTest.php \
                 tests/Feature/Election/CsvVoterImportTest.php \
                 tests/Feature/Voter/VoterImportElectionOnlyTest.php \
                 --env=testing
```

All tests should pass. Then do a smoke test in the browser:

1. Open an election in `draft` state → navigate to its voter import URL → expect 403
2. Transition the election to `setup_administration` → navigate to voter import URL → expect page to load
