# Database Schema

## Migration File

**Location**: `database/migrations/YYYY_MM_DD_HHMMSS_add_election_state_machine_to_elections_table.php`

**Date Created**: 2026-04-21

The migration adds all necessary columns to the `elections` table for state machine functionality.

## New Columns

### Administration Phase Columns

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `administration_suggested_start` | timestamp | Yes | NULL | Admin-set suggested start time |
| `administration_suggested_end` | timestamp | Yes | NULL | Admin-set suggested end time |
| `administration_completed` | boolean | No | false | Flag: phase completed |
| `administration_completed_at` | timestamp | Yes | NULL | When phase was completed |

### Nomination Phase Columns

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `nomination_suggested_start` | timestamp | Yes | NULL | Admin-set suggested start time |
| `nomination_suggested_end` | timestamp | Yes | NULL | Admin-set suggested end time |
| `nomination_completed` | boolean | No | false | Flag: phase completed |
| `nomination_completed_at` | timestamp | Yes | NULL | When phase was completed |

### Voting Phase Columns

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `voting_starts_at` | timestamp | Yes | NULL | Strict voting start time |
| `voting_ends_at` | timestamp | Yes | NULL | Strict voting end time |

### Configuration Columns

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `allow_auto_transition` | boolean | No | true | Enable grace period auto-transitions |
| `auto_transition_grace_days` | integer | No | 7 | Days after suggested end to auto-complete |

### Audit Columns

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `state_audit_log` | json | Yes | NULL | Array of state change records |

## Full Migration Code

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Administration Phase
            $table->timestamp('administration_suggested_start')
                ->nullable()
                ->after('settings_changes');
            $table->timestamp('administration_suggested_end')->nullable();
            $table->boolean('administration_completed')->default(false);
            $table->timestamp('administration_completed_at')->nullable();

            // Nomination Phase
            $table->timestamp('nomination_suggested_start')->nullable();
            $table->timestamp('nomination_suggested_end')->nullable();
            $table->boolean('nomination_completed')->default(false);
            $table->timestamp('nomination_completed_at')->nullable();

            // Voting Phase
            $table->timestamp('voting_starts_at')->nullable();
            $table->timestamp('voting_ends_at')->nullable();

            // Configuration
            $table->boolean('allow_auto_transition')->default(true);
            $table->unsignedInteger('auto_transition_grace_days')->default(7);

            // Audit Trail
            $table->json('state_audit_log')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'administration_suggested_start',
                'administration_suggested_end',
                'administration_completed',
                'administration_completed_at',
                'nomination_suggested_start',
                'nomination_suggested_end',
                'nomination_completed',
                'nomination_completed_at',
                'voting_starts_at',
                'voting_ends_at',
                'allow_auto_transition',
                'auto_transition_grace_days',
                'state_audit_log',
            ]);
        });
    }
};
```

## Existing Related Columns

These columns existed before and are used by the state machine:

| Column | Type | Purpose |
|--------|------|---------|
| `results_published_at` | timestamp | When results were published (final state trigger) |
| `results_published` | boolean | Legacy flag (kept for compatibility) |
| `start_date` | datetime | Legacy election start (not used by state machine) |
| `end_date` | datetime | Legacy election end (not used by state machine) |
| `status` | enum | Legacy status (`planned/active/completed/archived`) |

## State Derivation Logic

The current state is derived from these columns in order:

```php
// Pseudocode
if ($election->results_published_at !== null) {
    return 'results';
}

if ($election->voting_ends_at && now() > $election->voting_ends_at) {
    return 'results_pending';
}

if ($election->voting_starts_at && now()->between($election->voting_starts_at, $election->voting_ends_at)) {
    return 'voting';
}

if ($election->nomination_completed) {
    return 'nomination';
}

if ($election->nomination_suggested_start && now() >= $election->nomination_suggested_start) {
    return 'nomination';
}

if ($election->administration_completed) {
    return 'administration';
}

return 'administration';  // Default
```

## Migration Safeguards

### Backward Compatibility

✅ **All columns are nullable or have defaults**
- Existing elections not affected
- Old elections default to `administration` state
- No data loss

✅ **Existing `status` field preserved**
- Not used by state machine
- Existing code still works
- Can be removed in future major version

### Data Integrity

✅ **Boolean defaults prevent NULL issues**
- `administration_completed` defaults to false
- `nomination_completed` defaults to false
- Prevents ambiguous state

✅ **No unique constraints**
- No blocking of duplicate phase transitions
- Idempotent operations
- Safe for retry logic

## Querying by State

### Single Election

```php
// Get election and check state
$election = Election::find($id);
if ($election->current_state === 'voting') {
    // In voting phase
}
```

### Multiple Elections

```php
// Get all elections in administration phase
$elections = Election::where('administration_completed', false)
    ->where('nomination_suggested_start', null)
    ->get();

// Get all elections in voting phase (complex due to time window)
$now = now();
$elections = Election::where('voting_starts_at', '<=', $now)
    ->where('voting_ends_at', '>=', $now)
    ->get();

// Get all elections waiting for publication
$elections = Election::where('voting_ends_at', '<', now())
    ->whereNull('results_published_at')
    ->get();

// Get all completed elections
$elections = Election::whereNotNull('results_published_at')->get();
```

### Grace Period Queries

```php
// Find elections due for auto-transition (administration)
$gracePeriodDate = now()->subDays(7);  // 7 day grace period
$elections = Election::where('allow_auto_transition', true)
    ->where('administration_completed', false)
    ->where('administration_suggested_end', '<', $gracePeriodDate)
    ->get();

// Find elections due for auto-transition (nomination)
$elections = Election::where('allow_auto_transition', true)
    ->where('nomination_completed', false)
    ->where('nomination_suggested_end', '<', $gracePeriodDate)
    ->get();
```

## Audit Log Structure

The `state_audit_log` JSON column stores an array of state change records:

```json
[
  {
    "timestamp": "2026-04-21T10:30:00Z",
    "actor_id": "a1939e9f-e3da-46aa-b8ac-a278ba67cb6a",
    "actor_email": "admin@example.com",
    "action": "completeAdministration",
    "old_state": "administration",
    "new_state": "nomination",
    "reason": "Manual completion by admin",
    "metadata": {
      "posts_count": 5,
      "voters_count": 150
    }
  },
  {
    "timestamp": "2026-04-21T14:45:00Z",
    "actor_id": "system",
    "action": "auto_transition",
    "old_state": "nomination",
    "new_state": "voting",
    "reason": "Grace period auto-transition",
    "metadata": {}
  }
]
```

### Audit Log Limits

```php
// Keeps last 200 entries
// Oldest entries are removed when limit is exceeded
// Prevents unbounded JSON growth
```

## Performance Considerations

### Indexes

The migration does **not** add indexes. Consider adding if needed:

```php
// Optional: Add indexes for common queries
$table->index('administration_completed');
$table->index('nomination_completed');
$table->index(['voting_starts_at', 'voting_ends_at']);
$table->index('results_published_at');
$table->index('allow_auto_transition');
```

### Query Performance

✅ **State derivation is O(1)**
- Just reads columns, no joins
- No subqueries needed
- Computed attribute, cached in memory

✅ **Grace period queries are indexed**
- If indexes added, use where clauses
- Small result sets expected
- Daily batch operation

### JSON Performance

✅ **Audit log is append-only**
- No updates to JSON array
- PostgreSQL handles efficiently
- Limit of 200 entries prevents bloat

## Rollback Safety

To safely rollback:

```php
// Down migration drops all state machine columns
Schema::table('elections', function (Blueprint $table) {
    $table->dropColumn([
        'administration_suggested_start',
        'administration_suggested_end',
        'administration_completed',
        'administration_completed_at',
        'nomination_suggested_start',
        'nomination_suggested_end',
        'nomination_completed',
        'nomination_completed_at',
        'voting_starts_at',
        'voting_ends_at',
        'allow_auto_transition',
        'auto_transition_grace_days',
        'state_audit_log',
    ]);
});
```

**Important**: 
- Backup database before rollback
- Existing elections will not have state machine functionality
- Code using `$election->current_state` will throw error

## Migrations to Related Systems

If adding to existing election systems:

1. **Run migration**: `php artisan migrate`
2. **Set default values**: All new columns have defaults or are nullable
3. **Existing elections**: Default to `administration` state
4. **Test thoroughly**: Test with sample elections

## Related Tables

The state machine works with these existing tables:

- `elections` - Main election record (modified)
- `posts` - Election positions (read-only for state machine)
- `candidacies` - Candidate applications (read for validation)
- `votes` - Cast votes (read-only)
- `election_officers` - Committee members (read-only)
- `election_memberships` - Voters (read-only for validation)

## References

- Migration file: `database/migrations/YYYY_MM_DD_HHMMSS_add_election_state_machine_to_elections_table.php`
- Model: `app/Models/Election.php` (uses these columns in casts and methods)
- Tests: `tests/Feature/ElectionStateMachineTest.php`

---

**Schema Design Principle**: All columns are additive, nullable/defaulted, and backward-compatible. No breaking changes.
