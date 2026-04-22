# Voting Locks Implementation Guide

## Overview

Voting locks **prevent late vote submissions** by freezing vote acceptance when voting should have ended.

---

## What Are Voting Locks?

A voting lock is a **database flag** that prevents new votes from being submitted:

```
voting_locked = false  → Members CAN vote
voting_locked = true   → Members CANNOT vote
```

### Why Needed?

Without voting locks, members could submit votes after:
- Voting period officially ends
- Results are being tabulated
- Results are published

Voting locks enforce hard cutoffs and prevent invalid votes.

---

## Lock Activation Timing

Voting is locked at **TWO critical moments**:

### 1. When Nomination Completes

```php
$election->completeNomination($reason, $userId);
    ↓
    Calls: $election->lockVoting($userId)
    ↓
Sets:
  - voting_locked = true
  - voting_locked_at = now()
  - voting_locked_by = $userId (actor who triggered)
```

**Why here?** Once nomination ends, voting window begins. No retroactive votes allowed.

### 2. When Voting Window Closes (Automatic)

```php
// ProcessElectionAutoTransitions command (runs hourly)
if ($election->voting_ends_at < now() && !$election->voting_locked) {
    $election->lockVoting(null);  // System lock, no actor
}
```

**Why here?** Guarantees voting is locked even if nomination was manual.

---

## Database Schema

### Elections Table Columns

```sql
voting_locked BOOLEAN DEFAULT FALSE          -- Lock status flag
voting_locked_at TIMESTAMP NULL              -- When locked
voting_locked_by UUID NULL                   -- Who locked it (user_id)

-- Foreign key constraint:
FOREIGN KEY (voting_locked_by) REFERENCES users(id)
```

### Migration

```php
// database/migrations/2026_04_22_000004_add_locking_columns_to_elections_table.php

Schema::table('elections', function (Blueprint $table) {
    $table->boolean('voting_locked')->default(false)->after('voting_ends_at');
    $table->timestamp('voting_locked_at')->nullable()->after('voting_locked');
    $table->uuid('voting_locked_by')->nullable()->after('voting_locked_at');
    
    $table->foreign('voting_locked_by')
        ->references('id')
        ->on('users')
        ->nullOnDelete();
});
```

---

## Lock Enforcement

### Vote Submission Controller

```php
// app/Http/Controllers/VoteController.php

public function store(Request $request, Election $election)
{
    // Check if voting is locked
    if ($election->voting_locked) {
        return back()->withErrors([
            'error' => 'Voting has ended. No further votes can be submitted.'
        ]);
    }
    
    // Check if voting window is open
    if (!$election->allowsAction('cast_vote')) {
        return back()->withErrors([
            'error' => 'Voting is not currently open for this election.'
        ]);
    }
    
    // Safe to proceed with vote submission
    // ...
}
```

### Policy Authorization

```php
// app/Policies/ElectionPolicy.php

public function castVote(User $user, Election $election): bool
{
    // Check state allows voting
    if (!$election->allowsAction('cast_vote')) {
        return false;
    }
    
    // Check voting not locked
    if ($election->voting_locked) {
        return false;
    }
    
    // Check user is active member
    return $election->memberships()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->exists();
}
```

---

## Model Implementation

### Lock Voting Method

```php
// app/Models/Election.php

public function lockVoting(?string $actorId = null): void
{
    if ($this->voting_locked) {
        return;  // Already locked
    }
    
    $this->update([
        'voting_locked' => true,
        'voting_locked_at' => now(),
        'voting_locked_by' => $actorId,  // null = system lock
    ]);
    
    // Log the lock event
    $this->logStateChange([
        'action' => 'voting_locked',
        'actor_id' => $actorId,
        'reason' => $actorId 
            ? 'Voting locked by nomination completion'
            : 'Voting locked by system (voting_ends_at reached)',
        'metadata' => [
            'voting_locked_at' => now(),
            'locked_by_system' => $actorId === null,
        ]
    ]);
}
```

### Boot Hook Integration

```php
// In Election model booted() method

protected static function booted()
{
    static::creating(function ($election) {
        // Auto-initialize voting_locked
        $election->voting_locked = false;
    });
    
    static::updated(function ($election) {
        // When nomination_completed_at changes
        if ($election->isDirty('nomination_completed_at')) {
            $election->lockVoting($election->updated_by ?? null);
        }
    });
}
```

---

## Audit Trail

### Logging Lock Events

Every lock is recorded with dual-write pattern:

```php
// JSON append to elections.state_transitions_log
ElectionAuditLog::create([
    'election_id' => $election->id,
    'action' => 'voting_locked',
    'old_values' => [
        'voting_locked' => false,
        'voting_locked_at' => null,
        'voting_locked_by' => null,
    ],
    'new_values' => [
        'voting_locked' => true,
        'voting_locked_at' => now(),
        'voting_locked_by' => $actorId,
    ],
    'user_id' => $actorId,
    'ip_address' => request()->ip(),
    'session_id' => session()->getId(),
]);
```

### Query Audit Trail

```php
// Find when voting was locked
$lockEvent = ElectionAuditLog::where('election_id', $election->id)
    ->where('action', 'voting_locked')
    ->first();

// Extract details
$lockedAt = $lockEvent->new_values['voting_locked_at'];
$lockedBy = $lockEvent->user_id;  // null = system
```

---

## Unlock Scenarios

### Manual Override (Admin Only)

```php
// Emergency unlock if needed
$election->update([
    'voting_locked' => false,
    'voting_locked_at' => null,
]);

// Log the override
$election->logStateChange([
    'action' => 'voting_unlocked',
    'reason' => 'Emergency override by system administrator',
    'actor_id' => auth()->id(),
]);
```

### Scenario: Extended Voting Period

```php
// If voting_ends_at is extended, check lock status:
$election->update([
    'voting_ends_at' => now()->addDays(7),  // Extend voting
    'voting_locked' => false,               // Unlock for extended period
]);

// ProcessElectionAutoTransitions will re-lock when new deadline passes
```

---

## Testing Voting Locks

### Unit Tests

```php
// tests/Feature/Election/ElectionStateMachineTest.php

public function test_complete_nomination_locks_voting()
{
    $election = Election::factory()->create([
        'voting_locked' => false,
    ]);
    
    // Create approved candidates (no pending)
    DemoCandidacy::factory()->create([
        'election_id' => $election->id,
        'status' => 'approved',
    ]);
    
    // Complete nomination
    $election->completeNomination('Ready for voting', $this->userId);
    
    // Verify voting locked
    $election->refresh();
    $this->assertTrue($election->voting_locked);
    $this->assertNotNull($election->voting_locked_at);
    $this->assertEquals($this->userId, $election->voting_locked_by);
}

public function test_voting_locked_prevents_vote_submission()
{
    $election = Election::factory()->create([
        'voting_locked' => true,
        'voting_locked_at' => now(),
    ]);
    
    $response = $this->actingAs($this->voter)
        ->post(route('votes.store', $election), [
            'candidates' => [1, 2, 3],
        ]);
    
    // Vote rejected
    $response->assertRedirect();
    $response->assertSessionHasErrors(['error' => 'Voting has ended']);
    
    // No vote recorded
    $this->assertFalse($election->votes()->exists());
}

public function test_voting_locked_by_system_when_voting_ends()
{
    $election = Election::factory()->create([
        'voting_ends_at' => now()->subHours(1),  // Ended 1 hour ago
        'voting_locked' => false,
    ]);
    
    // Run background job
    $this->artisan('elections:process-auto-transitions')
        ->assertSuccessful();
    
    // Verify locked
    $election->refresh();
    $this->assertTrue($election->voting_locked);
    $this->assertNull($election->voting_locked_by);  // System lock
}
```

### Integration Tests

```php
public function test_voting_locked_after_nomination_in_ui()
{
    $election = Election::factory()->create();
    
    // Create candidacy and approve it
    $candidacy = DemoCandidacy::factory()->create([
        'election_id' => $election->id,
        'status' => 'pending',
    ]);
    
    // Officer completes nomination
    $response = $this->actingAs($this->officer)
        ->post(route('elections.complete-nomination', $election), [
            'reason' => 'All candidates approved',
        ]);
    
    $response->assertRedirect();
    
    // Verify voting is locked
    $election->refresh();
    $this->assertTrue($election->voting_locked);
}
```

---

## Best Practices

✅ **Do**
- Always lock voting when nomination completes
- Monitor voting_locked status in reports
- Document lock times in audit trail
- Test lock enforcement in voting controller
- Alert officers when voting is locked

❌ **Don't**
- Manually unlock voting without audit trail
- Ignore voting_locked flag in vote controller
- Allow votes after voting_ends_at without explicit unlock
- Mix manual and automatic locking in same election
- Forget to set voting_locked_by for manual locks

---

## Troubleshooting

### Voting Locked But Shouldn't Be

```php
// Check when it was locked
$election->voting_locked_at   // Timestamp
$election->voting_locked_by   // User ID or null

// If locked by system, check if voting_ends_at was reached
$election->voting_ends_at > now()  // Should be false if system-locked

// Manual unlock (with audit)
$election->update(['voting_locked' => false]);
$election->logStateChange(['action' => 'voting_unlocked', ...]);
```

### Voting Should Be Locked But Isn't

```php
// Check if nomination completed
if ($election->nomination_completed_at === null) {
    // Nomination not completed yet - lock manually
    $election->lockVoting($userId);
}

// Check if command is scheduled
// In routes/console.php:
Schedule::command('elections:process-auto-transitions')->hourly();
```

---

**Document Version:** 1.0  
**Implementation Status:** Complete  
**Test Coverage:** 5 tests for voting locks
