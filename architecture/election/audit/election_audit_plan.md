## Diagnostic Summary

| Finding | Status |
|---------|--------|
| Audit middleware | ❌ Does not exist |
| `app/Helpers/ElectionAudit.php` | ✅ Exists with helper functions |
| Functions being called | ❌ Never called anywhere |
| Current log format | Text format, wrong structure |
| New `storage/logs/audit/` directory | ❌ Does not exist |

## Implementation Plan

### Phase 1: Create ElectionAuditService (TDD)

```
1. Write tests for ElectionAuditService
   - test_creates_election_folder_on_first_log()
   - test_writes_jsonl_entry_with_correct_format()
   - test_masks_email_in_logs()
   - test_handles_concurrent_writes()

2. Implement ElectionAuditService
   - Folder: {slug}_{YYYYMMDD}_{HHmm} (using election start_date)
   - Format: JSONL (one JSON object per line)
   - Files: election.jsonl, voters.jsonl, committee.jsonl

3. Deprecate old ElectionAudit.php helpers
```

### Phase 2: Wire Service to Controllers

| Controller | Method | Event |
|------------|--------|-------|
| `ElectionVotingController` | `start()` | `voting_started` |
| `ElectionVotingController` | `start()` (IP blocked) | `ip_blocked` |
| `VoteController` | `first_submission()` | `vote_submitted` |
| `VoteController` | `store()` | `vote_confirmed` |
| `ElectionSettingsController` | `update()` | `settings_changed` |
| `VoterVerificationController` | `store()` | `voter_verified` |
| `VoterVerificationController` | `revoke()` | `verification_revoked` |

### Phase 3: Cleanup Command

```php
// app/Console/Commands/AuditCleanup.php
// Runs daily, deletes folders older than 30 days
```

## Proceed?

**Om Gam Ganapataye Namah** 🪔🐘

Shall I implement Phase 1 with TDD approach?

## Phase 1: TDD Implementation of ElectionAuditService

### Step 1: Write Failing Tests (RED)

```php
// tests/Feature/Audit/ElectionAuditServiceTest.php
<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ElectionAuditServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private ElectionAuditService $auditService;
    private Election $election;
    private User $user;
    private string $auditBasePath;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->auditService = app(ElectionAuditService::class);
        $this->auditBasePath = storage_path('logs/audit');
        
        // Clean up before tests
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
        
        $this->election = Election::factory()->create([
            'name' => 'Namaste Election',
            'slug' => 'namaste-i6g7ahfc',
            'start_date' => '2026-04-14 21:00:00',
            'type' => 'real',
        ]);
        
        $this->user = User::factory()->create([
            'name' => 'Niraj Adhikari',
            'email' => 'restaurant.namastenepal@gmail.com',
        ]);
    }
    
    protected function tearDown(): void
    {
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
        parent::tearDown();
    }
    
    /** @test */
    public function it_creates_election_folder_on_first_log(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
        );
        
        $expectedFolder = $this->auditBasePath . '/namaste_20260414_2100';
        
        $this->assertTrue(File::exists($expectedFolder));
        $this->assertTrue(File::isDirectory($expectedFolder));
    }
    
    /** @test */
    public function it_writes_jsonl_entry_with_correct_format(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'vote_cast',
            user: $this->user,
            metadata: ['post_count' => 3, 'receipt' => 'abc123'],
        );
        
        $logFile = $this->auditBasePath . '/namaste_20260414_2100/election.jsonl';
        $this->assertTrue(File::exists($logFile));
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertCount(1, $lines);
        
        $entry = json_decode($lines[0], true);
        
        $this->assertEquals('vote_cast', $entry['event']);
        $this->assertEquals($this->election->id, $entry['election_id']);
        $this->assertEquals('namaste-i6g7ahfc', $entry['election_slug']);
        $this->assertEquals($this->user->id, $entry['user_id']);
        $this->assertEquals('Niraj Adhikari', $entry['user_name']);
        $this->assertEquals(3, $entry['metadata']['post_count']);
        $this->assertArrayHasKey('timestamp', $entry);
    }
    
    /** @test */
    public function it_masks_email_in_logs(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
        );
        
        $logFile = $this->auditBasePath . '/namaste_20260414_2100/election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $entry = json_decode($lines[0], true);
        
        $this->assertEquals('r***@gmail.com', $entry['user_email']);
    }
    
    /** @test */
    public function it_writes_to_separate_files_by_category(): void
    {
        // Voter event
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
            category: 'voters',
        );
        
        // Committee event
        $admin = User::factory()->create(['name' => 'Admin User']);
        $this->auditService->log(
            election: $this->election,
            event: 'settings_changed',
            user: $admin,
            category: 'committee',
            metadata: ['changes' => ['ip_restriction_enabled' => ['from' => false, 'to' => true]]],
        );
        
        $basePath = $this->auditBasePath . '/namaste_20260414_2100';
        
        $this->assertTrue(File::exists($basePath . '/voters.jsonl'));
        $this->assertTrue(File::exists($basePath . '/committee.jsonl'));
        $this->assertTrue(File::exists($basePath . '/election.jsonl'));
        
        // Verify voters file has voter event
        $voterLines = file($basePath . '/voters.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(1, $voterLines);
        $voterEntry = json_decode($voterLines[0], true);
        $this->assertEquals('voting_started', $voterEntry['event']);
        
        // Verify committee file has admin event
        $committeeLines = file($basePath . '/committee.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(1, $committeeLines);
        $committeeEntry = json_decode($committeeLines[0], true);
        $this->assertEquals('settings_changed', $committeeEntry['event']);
        
        // Verify election.jsonl has both
        $electionLines = file($basePath . '/election.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(2, $electionLines);
    }
    
    /** @test */
    public function it_captures_ip_address_when_provided(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
            ip: '192.168.1.100',
        );
        
        $logFile = $this->auditBasePath . '/namaste_20260414_2100/election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $entry = json_decode($lines[0], true);
        
        $this->assertEquals('192.168.1.100', $entry['ip']);
    }
    
    /** @test */
    public function it_handles_null_user_gracefully(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'ip_blocked',
            user: null,
            ip: '10.0.0.1',
            metadata: ['reason' => 'limit_exceeded'],
        );
        
        $logFile = $this->auditBasePath . '/namaste_20260414_2100/election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $entry = json_decode($lines[0], true);
        
        $this->assertEquals('ip_blocked', $entry['event']);
        $this->assertNull($entry['user_id']);
        $this->assertEquals('10.0.0.1', $entry['ip']);
        $this->assertEquals('limit_exceeded', $entry['metadata']['reason']);
    }
}
```

### Step 2: Run Tests (Confirm RED)

```bash
php artisan test tests/Feature/Audit/ElectionAuditServiceTest.php
```

Expected: All 6 tests fail.

Proceed with creating the service?