# Grace Periods Implementation Guide

## Overview

Grace periods enable **automatic phase transitions** in elections. After a phase completes, a configurable grace period elapses before the next phase begins automatically.

---

## How Grace Periods Work

### Timeline Example

```
ADMINISTRATION PHASE COMPLETES
↓
✓ administration_completed_at = 2026-04-15 10:00
✓ allow_auto_transition = true
✓ auto_transition_grace_days = 7
↓
┌─────────────────────────┐
│ GRACE PERIOD: 7 DAYS    │
│ 2026-04-15 to 2026-04-22│
└─────────────────────────┘
↓
2026-04-22 10:00 (Grace period elapsed)
↓
NOMINATION PHASE STARTS AUTOMATICALLY
nomination_suggested_start auto-set
```

---

## Configuration

### UI Control Points

**Path:** `/elections/{slug}/timeline`

**Vue Component:** `resources/js/Pages/Election/Partials/ElectionTimelineSettings.vue`

Controls:
- **Allow Auto-Transition** (checkbox) → Enable/disable auto-transitions
- **Grace Period (Days)** (number input) → 0-30 days

### Model Configuration

```php
// app/Models/Election.php
protected $fillable = [
    'allow_auto_transition',
    'auto_transition_grace_days',
];

protected $casts = [
    'allow_auto_transition' => 'boolean',
    'auto_transition_grace_days' => 'integer',
];
```

### Validation Rules

```php
// app/Http/Controllers/Election/ElectionManagementController.php
$request->validate([
    'allow_auto_transition' => 'sometimes|boolean',
    'auto_transition_grace_days' => 'sometimes|integer|between:0,30',
]);
```

---

## Database Schema

### Elections Table Columns

```sql
allow_auto_transition BOOLEAN DEFAULT FALSE
auto_transition_grace_days INTEGER DEFAULT 7
administration_completed_at TIMESTAMP NULL
nomination_completed_at TIMESTAMP NULL
```

---

## Processing Grace Periods

### Console Command

```bash
php artisan elections:process-auto-transitions
```

**Location:** `app/Console/Commands/ProcessElectionAutoTransitions.php`

**Schedule:** Hourly (in `routes/console.php`)

### Command Logic

**Scenario 1: Admin → Nomination**
```php
$deadline = $election->administration_completed_at
    ->copy()
    ->addDays($election->auto_transition_grace_days);

if (now()->isAfter($deadline)) {
    if ($election->posts()->count() > 0 && 
        $election->memberships()->count() > 0) {
        $election->completeNomination('Grace period auto-transition', null);
    }
}
```

**Scenario 2: Nom → Voting**
```php
$deadline = $election->nomination_completed_at
    ->copy()
    ->addDays($election->auto_transition_grace_days);

if (now()->isAfter($deadline)) {
    if (!$election->candidacies()->where('status', 'pending')->exists()) {
        $election->lockVoting(null);
    }
}
```

---

## Grace Period Calculation

### Formula

```
Grace Deadline = Phase Completion Time + Grace Period Days

Example:
Phase completed: 2026-04-15 14:30:00
Grace days:      7
Grace deadline:  2026-04-22 14:30:00

If now() >= deadline → Transition
```

### Carbon Date Handling

```php
$completedAt = $election->administration_completed_at;
$graceDays = $election->auto_transition_grace_days;

// Calculate deadline
$graceDeadline = $completedAt->copy()->addDays($graceDays);

// Check if elapsed
if (now()->isAfter($graceDeadline)) {
    // Safe to transition
}
```

---

## Error Handling

### Prerequisites Not Met

```php
// If grace period elapses but prerequisites aren't met:
if ($election->posts()->count() === 0) {
    Log::warning("Auto-transition skipped: No posts in {$election->id}");
    return;
}
```

---

## Audit Trail

Every grace period transition is recorded:

```php
$election->logStateChange([
    'action' => 'auto_transition',
    'trigger' => 'grace_period',
    'reason' => 'Grace period of 7 days elapsed',
    'metadata' => [
        'grace_deadline' => $graceDeadline,
        'auto_transition_grace_days' => 7,
    ]
]);
```

---

## Testing Grace Periods

### Test Cases

```php
// tests/Feature/Console/ProcessElectionAutoTransitionsTest.php

// Grace period elapsed → transition occurs
public function test_grace_period_elapsed_transitions()
{
    $election = Election::factory()->create([
        'nomination_completed_at' => now()->subDays(8),
        'auto_transition_grace_days' => 7,
        'allow_auto_transition' => true,
    ]);
    
    $this->artisan('elections:process-auto-transitions')
        ->assertSuccessful();
    
    $election->refresh();
    $this->assertTrue($election->voting_locked);
}

// Grace period not elapsed → skip transition
public function test_grace_period_not_elapsed_skips_transition()
{
    $election = Election::factory()->create([
        'nomination_completed_at' => now()->subDays(3),
        'auto_transition_grace_days' => 7,
        'allow_auto_transition' => true,
    ]);
    
    $this->artisan('elections:process-auto-transitions')
        ->assertSuccessful();
    
    $election->refresh();
    $this->assertFalse($election->voting_locked);
}

// Auto-transition disabled → skip transition
public function test_grace_period_disabled_skips_transition()
{
    $election = Election::factory()->create([
        'nomination_completed_at' => now()->subDays(8),
        'auto_transition_grace_days' => 7,
        'allow_auto_transition' => false,
    ]);
    
    $this->artisan('elections:process-auto-transitions')
        ->assertSuccessful();
    
    $election->refresh();
    $this->assertFalse($election->voting_locked);
}
```

---

## Best Practices

✅ **Do**
- Set reasonable grace periods (3-14 days typical)
- Monitor command execution via logs
- Test before enabling on live elections
- Audit all auto-transitions
- Document settings for officers

❌ **Don't**
- Set grace period to 0 without manual transition plan
- Enable auto-transition without reviewing prerequisites
- Forget to schedule the console command
- Mix auto and manual transitions simultaneously
- Ignore audit logs after transitions

---

**Document Version:** 1.0  
**Implementation Status:** Complete  
**Test Coverage:** 13 tests
