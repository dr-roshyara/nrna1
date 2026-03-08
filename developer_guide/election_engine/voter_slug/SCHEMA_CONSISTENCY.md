# Database Schema Consistency Guide

## Critical Principle

Both voter_slug_steps and demo_voter_slug_steps MUST be identical except for foreign key references.

This allows the same code to work with both models without table-specific logic.

## The Problem We Solved

Before fix: DemoVoterSlugStep referenced 'demo_voter_slug_id'
Reality: Table column was 'voter_slug_id'
Result: SQL error "Unknown column demo_voter_slug_id"

After fix: Both models reference 'voter_slug_id'
Both tables have 'voter_slug_id' column
Code works identically for both

## Schema Comparison

### voter_slug_steps (Real Elections)

Column | Type | Nullable | Notes
------|------|----------|-------
id | BIGINT UNSIGNED | NO | Primary key
organisation_id | BIGINT UNSIGNED | YES | Tenant scoping
voter_slug_id | BIGINT UNSIGNED | NO | FK to slug table
election_id | BIGINT UNSIGNED | NO | FK to elections
step | TINYINT UNSIGNED | NO | 1-5
step_data | JSON | YES | Flexible metadata
completed_at | TIMESTAMP | YES | When step was done
created_at | TIMESTAMP | YES | Laravel auto
updated_at | TIMESTAMP | YES | Laravel auto

### demo_voter_slug_steps (Demo Elections)

Column | Type | Nullable | Notes
------|------|----------|-------
id | BIGINT UNSIGNED | NO | Primary key
organisation_id | BIGINT UNSIGNED | YES | Tenant scoping
voter_slug_id | BIGINT UNSIGNED | NO | FK to slug table (SAME!)
election_id | BIGINT UNSIGNED | NO | FK to elections
step | TINYINT UNSIGNED | NO | 1-5
step_data | JSON | YES | Flexible metadata
completed_at | TIMESTAMP | YES | When step was done
created_at | TIMESTAMP | YES | Laravel auto
updated_at | TIMESTAMP | YES | Laravel auto

## Key Differences

Only the Foreign Key reference differs:

voter_slug_steps: FOREIGN KEY (voter_slug_id) REFERENCES voter_slugs(id)
demo_voter_slug_steps: FOREIGN KEY (voter_slug_id) REFERENCES demo_voter_slugs(id)

Everything else is IDENTICAL.

## Model Fillable Arrays

Both models must have identical fillable arrays:

VoterSlugStep.php:
```php
protected $fillable = [
    'organisation_id',
    'voter_slug_id',
    'election_id',
    'step',
    'step_data',
    'completed_at',
];
```

DemoVoterSlugStep.php:
```php
protected $fillable = [
    'organisation_id',
    'voter_slug_id',  // SAME!
    'election_id',
    'step',
    'step_data',
    'completed_at',
];
```

## Relationship Definitions

Both models follow identical pattern:

VoterSlugStep.php:
```php
public function voterSlug()
{
    return $this->belongsTo(VoterSlug::class, 'voter_slug_id');
}

public function election()
{
    return $this->belongsTo(Election::class, 'election_id');
}

public function scopeForVoterInElection($query, $voterSlugId, $electionId)
{
    return $query->where('voter_slug_id', $voterSlugId)
                 ->where('election_id', $electionId);
}
```

DemoVoterSlugStep.php:
```php
public function demoVoterSlug()
{
    return $this->belongsTo(DemoVoterSlug::class, 'voter_slug_id');  // Same column!
}

public function election()
{
    return $this->belongsTo(Election::class, 'election_id');
}

public function scopeForVoterInElection($query, $voterSlugId, $electionId)
{
    return $query->where('voter_slug_id', $voterSlugId)  // Same column!
                 ->where('election_id', $electionId);
}
```

## Why Column Names Must Match

The service code works identically for both:

```php
public function completeStep($voterSlug, Election $election, int $step, array $stepData = [])
{
    $isDemo = $voterSlug instanceof DemoVoterSlug;
    $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

    // Same query works for both because column name is same!
    $existingStep = $StepModel::where('voter_slug_id', $voterSlug->id)
        ->where('election_id', $election->id)
        ->where('step', $step)
        ->first();

    // Same data structure works for both!
    $data = [
        'voter_slug_id' => $voterSlug->id,
        'election_id' => $election->id,
        'step' => $step,
        'step_data' => $stepData,
        'completed_at' => now(),
    ];

    if ($voterSlug->organisation_id) {
        $data['organisation_id'] = $voterSlug->organisation_id;
    }

    return $StepModel::create($data);  // Works for both!
}
```

If columns differed, you would need branching logic - which defeats the polymorphic design.

## When Adding New Columns

Add to BOTH tables in same migration:

```php
Schema::table('voter_slug_steps', function (Blueprint $table) {
    $table->string('new_field')->nullable();
});

Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
    $table->string('new_field')->nullable();
});
```

Update BOTH models:

VoterSlugStep.php:
```php
protected $fillable = [
    // ... existing ...
    'new_field',
];
```

DemoVoterSlugStep.php:
```php
protected $fillable = [
    // ... existing ...
    'new_field',  // MUST match!
];
```

## Verification

Check tables are identical:

```sql
DESCRIBE voter_slug_steps;
DESCRIBE demo_voter_slug_steps;
```

Both should show same columns, same order, same types.

Only FK reference should differ.

## Summary

- Same column names = polymorphic code works for both
- Same structure = no branching logic needed
- Same migrations pattern = consistency maintained
- Only difference: FK target table

This design enables entire service layer to be model-agnostic.
