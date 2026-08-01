# Claude CLI Prompt: Per-Voter Audit Trail — Plan Then TDD Implementation

---

## Role: Senior Developer — Election Platform Audit System

You are implementing a **per-voter audit trail** that records every action a voter takes during the voting process. Each voter gets a separate JSONL file inside an election-specific folder. This builds on the existing `ElectionAuditService` which already writes `voters.jsonl`, `election.jsonl`, and `committee.jsonl` — but doesn't yet create per-voter files.

---

## Phase 1: Plan Mode — Understand What Exists

### Step 1.1: Read the Existing Audit Infrastructure

Read these files to understand the current system:

| File | What to Learn |
|------|---------------|
| `app/Services/ElectionAuditService.php` | How folders are created, how JSONL files are written, the `log()` method signature |
| `app/Http/Controllers/VoteController.php` | Where `ElectionAuditService::log()` is already called (`first_submission`, `store`) |
| `app/Http/Controllers/CodeController.php` | Where code creation/verification happens — needs audit calls added |
| `app/Http/Middleware/VoterSlugStep.php` | The `auditTrail()` method for reading audit data |
| `app/Models/ElectionAuditLog.php` | Database audit model — may or may not be used |

### Step 1.2: Identify All Audit Points

Map every voter action that should be recorded:

| Step | Action | Controller | Method | Currently Logged? |
|:---:|--------|-----------|--------|:---:|
| 1 | Code created/verified | `CodeController` | `store()` | ❌ |
| 2 | Agreement accepted | `CodeController` | `submitAgreement()` | ❌ |
| 3 | Vote submitted (first) | `VoteController` | `first_submission()` | ✅ `vote_submitted` to voters.jsonl |
| 4 | Vote verified | `VoteController` | `verify()` | ❌ |
| 5 | Vote confirmed (final) | `VoteController` | `store()` | ✅ `vote_confirmed` to voters.jsonl |
| — | Vote receipt viewed | `VoteController` | `show()` | ❌ |

### Step 1.3: Design the Per-Voter File Structure

```
storage/logs/audit/{election_slug}_{YYYYMMDD}_{HHmm}/
├── election.jsonl          ← ALL events (already exists)
├── voters.jsonl            ← ALL voter events (already exists)
├── committee.jsonl         ← committee actions (already exists)
└── voters/                 ← NEW: per-voter files
    ├── John_Doe.jsonl      ← All of John's actions
    ├── Jane_Smith.jsonl    ← All of Jane's actions
    └── ...
```

### Step 1.4: Plan the Implementation

| Phase | What | Where |
|:---:|------|-------|
| A | Add `logVoterAction()` method to `ElectionAuditService` | `app/Services/ElectionAuditService.php` |
| B | Call `logVoterAction()` from CodeController steps | `app/Http/Controllers/CodeController.php` |
| C | Call `logVoterAction()` from VoteController steps | `app/Http/Controllers/VoteController.php` |
| D | Add admin endpoint to download/view audit files | `app/Http/Controllers/Admin/AdminElectionController.php` |
| E | Write TDD tests | `tests/Feature/Voting/VoterAuditTrailTest.php` |

---

## Phase 2: TDD Implementation

### Step 2.1: Write Failing Tests (RED)

File: `tests/Feature/Voting/VoterAuditTrailTest.php`

```php
<?php

namespace Tests\Feature\Voting;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class VoterAuditTrailTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $voter;
    private ElectionAuditService $auditService;
    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create();
        $this->voter = User::factory()->create(['name' => 'John Doe']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'slug' => 'test-election-2026',
            'start_date' => now(),
        ]);

        $this->auditService = app(ElectionAuditService::class);
        $this->auditBasePath = storage_path('logs/audit');
    }

    protected function tearDown(): void
    {
        // Clean up test audit files
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
        parent::tearDown();
    }

    /** @test */
    public function per_voter_audit_file_is_created(): void
    {
        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified',
            metadata: ['code_id' => 'test-code-uuid']
        );

        // Find the audit folder
        $folders = File::directories($this->auditBasePath);
        $this->assertNotEmpty($folders, 'Audit folder should be created');

        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $this->assertFileExists($voterFile, 'Per-voter file should exist');

        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));
        $this->assertCount(1, $lines, 'Should have one log entry');

        $entry = json_decode($lines[0], true);
        $this->assertEquals(1, $entry['step']);
        $this->assertEquals('code_verified', $entry['action']);
        $this->assertEquals($this->voter->name, $entry['voter_name']);
    }

    /** @test */
    public function per_voter_file_records_multiple_steps(): void
    {
        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 2,
            action: 'agreement_accepted'
        );

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 3,
            action: 'vote_submitted',
            metadata: ['post_count' => 3]
        );

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';

        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));
        $this->assertCount(3, $lines, 'Should have three log entries');

        $entries = array_map(fn ($l) => json_decode($l, true), $lines);
        $this->assertEquals([1, 2, 3], array_column($entries, 'step'));
        $this->assertEquals(
            ['code_verified', 'agreement_accepted', 'vote_submitted'],
            array_column($entries, 'action')
        );
    }

    /** @test */
    public function voter_email_is_masked_in_audit(): void
    {
        $this->voter->update(['email' => 'john.doe@example.com']);

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $content = File::get($voterFile);
        $entry = json_decode(explode("\n", $content)[0], true);

        $this->assertStringContainsString('***@', $entry['voter_email']);
        $this->assertStringNotContainsString('john.doe', $entry['voter_email']);
    }

    /** @test */
    public function voter_name_with_special_chars_creates_safe_filename(): void
    {
        $this->voter->update(['name' => 'Jöhn Döe / Müller']);

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $folders = File::directories($this->auditBasePath);
        $votersDir = $folders[0] . '/voters';
        $files = File::files($votersDir);

        $this->assertCount(1, $files);
        $filename = $files[0]->getFilename();
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_\-]+\.jsonl$/', $filename, 'Filename should be sanitized');
    }

    /** @test */
    public function audit_trail_spans_entire_voting_flow(): void
    {
        // Simulate full voting flow
        $steps = [
            ['step' => 1, 'action' => 'code_created', 'metadata' => []],
            ['step' => 1, 'action' => 'code_verified', 'metadata' => ['code_id' => 'abc']],
            ['step' => 2, 'action' => 'agreement_accepted', 'metadata' => []],
            ['step' => 3, 'action' => 'vote_submitted', 'metadata' => ['post_count' => 2]],
            ['step' => 4, 'action' => 'vote_verified', 'metadata' => []],
            ['step' => 5, 'action' => 'vote_confirmed', 'metadata' => ['receipt_hash' => 'abc123']],
        ];

        foreach ($steps as $s) {
            $this->auditService->logVoterAction(
                election: $this->election,
                voter: $this->voter,
                step: $s['step'],
                action: $s['action'],
                metadata: $s['metadata']
            );
        }

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));

        $this->assertCount(6, $lines, 'Should record all 6 voting steps');
    }

    /** @test */
    public function admin_can_download_voter_audit_file(): void
    {
        // Write some audit data first
        $this->auditService->logVoterAction(
            election: $this->election, voter: $this->voter,
            step: 1, action: 'code_verified'
        );

        // Create admin user
        $admin = User::factory()->create(['is_super_admin' => true]);

        $folders = File::directories($this->auditBasePath);
        $folderName = basename($folders[0]);

        $response = $this->actingAs($admin)
            ->get("/platform/elections/{$this->election->id}/audit/{$folderName}/voters/John_Doe.jsonl");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/octet-stream');
    }
}
```

Run: `php artisan test tests/Feature/Voting/VoterAuditTrailTest.php`

Expected: **All 6 tests FAIL (RED)** — `logVoterAction()` method doesn't exist yet.

---

### Step 2.2: Implement `logVoterAction()` (GREEN)

File: `app/Services/ElectionAuditService.php`

Add after the existing `log()` method:

```php
/**
 * Log a per-voter action to the voter's individual audit file.
 * Creates election folder and voters/ subfolder automatically.
 *
 * @param Election $election The election context
 * @param User $voter The voter performing the action
 * @param int $step Voting step number (1-5)
 * @param string $action Action description (e.g., 'code_verified', 'vote_submitted')
 * @param array $metadata Additional event data
 */
public function logVoterAction(
    Election $election,
    User $voter,
    int $step,
    string $action,
    array $metadata = []
): void {
    // Get or create the election audit folder
    $folderPath = $this->getOrCreateAuditFolder($election);

    // Create voters/ subfolder
    $votersFolder = $folderPath . DIRECTORY_SEPARATOR . 'voters';
    if (!is_dir($votersFolder)) {
        File::makeDirectory($votersFolder, 0755, true, true);
    }

    // Build safe filename from voter name
    $safeName = $this->sanitizeFileName($voter->name);
    $filename = $safeName . '.jsonl';

    // Build log entry
    $entry = [
        'step' => $step,
        'action' => $action,
        'election_id' => $election->id,
        'election_slug' => $election->slug,
        'timestamp' => now()->toIso8601String(),
        'voter_id' => $voter->id,
        'voter_name' => $voter->name,
        'voter_email' => $this->maskEmail($voter->email),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'metadata' => $metadata,
    ];

    // Append to voter's file
    $this->appendToJsonlFile($votersFolder, $filename, $entry);
}

/**
 * Sanitize a voter name into a safe filename.
 * Removes special characters, spaces become underscores.
 *
 * @param string $name Raw voter name
 * @return string Safe filename (without extension)
 */
private function sanitizeFileName(string $name): string
{
    // Transliterate special characters (ü → u, é → e)
    $safe = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
    
    // Replace non-alphanumeric characters with underscores
    $safe = preg_replace('/[^a-zA-Z0-9]/', '_', $safe);
    
    // Collapse multiple underscores
    $safe = preg_replace('/_+/', '_', $safe);
    
    // Trim underscores from ends
    $safe = trim($safe, '_');
    
    // Fallback for completely non-ASCII names
    if (empty($safe)) {
        $safe = 'voter_' . substr(hash('sha256', $name), 0, 8);
    }
    
    return $safe;
}
```

---

### Step 2.3: Add Audit Calls to CodeController

File: `app/Http/Controllers/CodeController.php`

In the `store()` method (after successful code verification, ~line 371):

```php
// Log per-voter audit: code verified
app(\App\Services\ElectionAuditService::class)->logVoterAction(
    election: $election,
    voter: $auth_user,
    step: 1,
    action: 'code_verified',
    metadata: ['code_id' => $code->id]
);
```

In the `submitAgreement()` method (after successful agreement, ~line 610):

```php
// Log per-voter audit: agreement accepted
app(\App\Services\ElectionAuditService::class)->logVoterAction(
    election: $election,
    voter: $auth_user,
    step: 2,
    action: 'agreement_accepted'
);
```

---

### Step 2.4: Add Audit Calls to VoteController

File: `app/Http/Controllers/VoteController.php`

In `first_submission()` (after vote submitted, around the existing `ElectionAuditService::log()` call):

```php
// Add per-voter audit alongside existing log
app(\App\Services\ElectionAuditService::class)->logVoterAction(
    election: $election,
    voter: $auth_user,
    step: 3,
    action: 'vote_submitted',
    metadata: ['post_count' => $postCount]
);
```

In `verify()` (after successful verification display):

```php
// Log per-voter audit: vote verified
app(\App\Services\ElectionAuditService::class)->logVoterAction(
    election: $election,
    voter: $auth_user,
    step: 4,
    action: 'vote_verified'
);
```

In `store()` (after successful vote confirmation, around the existing log call):

```php
// Add per-voter audit alongside existing log
app(\App\Services\ElectionAuditService::class)->logVoterAction(
    election: $election,
    voter: $auth_user,
    step: 5,
    action: 'vote_confirmed',
    metadata: ['receipt_hash' => $receiptHash]
);
```

---

### Step 2.5: Add Admin Download Endpoint

File: `app/Http/Controllers/Admin/AdminElectionController.php`

```php
/**
 * Download a voter audit file.
 * GET /platform/elections/{election}/audit/{folder}/{file}
 */
public function downloadAuditFile(Election $election, string $folder, string $file): \Symfony\Component\HttpFoundation\BinaryFileResponse
{
    $election = Election::withoutGlobalScopes()->findOrFail($election->id);
    
    $filePath = storage_path("logs/audit/{$folder}/{$file}");
    
    if (!File::exists($filePath)) {
        abort(404, 'Audit file not found.');
    }
    
    // Validate the file is within the audit directory (prevent path traversal)
    $realPath = realpath($filePath);
    $auditBase = realpath(storage_path('logs/audit'));
    if (!str_starts_with($realPath, $auditBase)) {
        abort(403, 'Access denied.');
    }
    
    return response()->download($filePath, $file, [
        'Content-Type' => 'application/octet-stream',
    ]);
}
```

Add route in `routes/platform.php`:

```php
Route::get('/elections/{election}/audit/{folder}/{file}', [AdminElectionController::class, 'downloadAuditFile'])
    ->name('platform.elections.audit.download')
    ->where('file', '.*'); // Allow dots in filename
```

Add import in the controller:
```php
use Illuminate\Support\Facades\File;
```

---

### Step 2.6: Run Tests (GREEN)

```bash
php artisan test tests/Feature/Voting/VoterAuditTrailTest.php
```

Expected: **All 6 tests PASS.**

---

### Step 2.7: Full Regression Check

```bash
php artisan test tests/Feature/Voting/
```

Expected: All previous tests still pass. New total: **32 passed, 19 skipped, 0 failed.**

---

## Verification Checklist

1. Start a voting session → complete all 5 steps
2. Check `storage/logs/audit/` → find election folder
3. Open `voters/{Voter_Name}.jsonl` → verify all 5 steps recorded
4. Each entry has: `step`, `action`, `timestamp`, `voter_name`, `masked_email`, `ip`
5. Admin can download audit file from platform dashboard
6. All tests pass

---

## What NOT to Do

- Do NOT modify the existing `log()` method — it still writes to `voters.jsonl` and `election.jsonl`
- Do NOT change the `ElectionAuditLog` database model
- Do NOT add user identification to the `votes` table — audit files are separate from anonymous votes
- Do NOT expose audit files publicly — admin-only endpoint

---

**Total: 1 new method + 1 helper in service, 5 audit calls across 2 controllers, 1 admin endpoint, 6 tests.**