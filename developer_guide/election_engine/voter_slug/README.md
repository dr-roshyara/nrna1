# Voter Slug System — Developer Guide

## Overview

The Voter Slug System manages secure, time-limited voting sessions for both real elections (with organisation_id) and demo elections (organisation_id = NULL) using a dual-model architecture.

## Critical Architecture Decision

The system uses two separate Eloquent models:

| Component | Real Elections | Demo Elections |
|-----------|---|---|
| Model | VoterSlug | DemoVoterSlug |
| Table | voter_slugs | demo_voter_slugs |
| Step Model | VoterSlugStep | DemoVoterSlugStep |
| Key Column | voter_slug_id | voter_slug_id |

## Root Cause of Demo Elections Issue

Four cascading compatibility issues:

### 1. Route Model Binding (routes/web.php)

Problem: Route binding only searched voter_slugs table.

Solution: Search both VoterSlug and DemoVoterSlug:

```php
Route::bind('vslug', function (string $value) {
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->with('user')
        ->where('slug', $value)
        ->first();

    if (!$voterSlug) {
        $voterSlug = DemoVoterSlug::withoutGlobalScopes()
            ->with('user')
            ->where('slug', $value)
            ->first();
    }

    if (!$voterSlug) abort(404, 'Voting link not found.');
    return $voterSlug;
});
```

Key Principle: Check VoterSlug first (more common), then DemoVoterSlug.

### 2. Middleware Type Checking (EnsureVoterStepOrder.php)

Problem: Middleware only accepted VoterSlug instances.

Solution:
```php
if (!$vslug instanceof VoterSlug && !$vslug instanceof DemoVoterSlug) {
    abort(403, 'Invalid voting link.');
}
```

Key Principle: Use compound instanceof checks for both models.

### 3. Service Type Hints (VoterStepTrackingService.php)

Problem: Type hints rejected DemoVoterSlug at call time.

Solution: Remove type hints and detect at runtime:
```php
public function completeStep($voterSlug, Election $election, int $step, array $stepData = [])
{
    $isDemo = $voterSlug instanceof DemoVoterSlug;
    $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

    $result = $StepModel::where('voter_slug_id', $voterSlug->id)->first();
}
```

Key Principle: Use runtime detection instead of type hints.

### 4. Model Column Names (DemoVoterSlugStep.php)

Problem: Model referenced demo_voter_slug_id but table had voter_slug_id.

Solution: Use same column name voter_slug_id in both tables:
```php
protected $fillable = [
    'organisation_id',
    'voter_slug_id',  // Same for both!
    'election_id',
    'step',
    'step_data',
    'completed_at',
];
```

Key Principle: Column names must be identical across both step tables.

## Critical Care Areas

### 1. Route Model Binding (routes/web.php)

- Search both VoterSlug and DemoVoterSlug
- Check VoterSlug first (more common)
- Use withoutGlobalScopes() to bypass tenant filtering

### 2. Middleware Type Acceptance

- Accept both VoterSlug and DemoVoterSlug
- Use compound instanceof checks
- Never reject either model type

### 3. Service Polymorphism

- Never use type hints for VoterSlug or DemoVoterSlug
- Always detect type at runtime with instanceof
- Select correct step model based on slug type

### 4. Database Schema Consistency

- Both step tables MUST have identical columns
- Foreign key MUST be named voter_slug_id
- Never create table-specific column names

## Debugging Checklist

### 404 Not Found on Slug Routes

Check:
1. Does Route::bind() search both models?
2. Is withoutGlobalScopes() used?
3. Does slug exist: SELECT * FROM voter_slugs/demo_voter_slugs WHERE slug='abc';

### 403 Forbidden on Voter Slug Routes

Check:
1. Which middleware returns 403?
2. Does middleware accept both model types?
3. Is DemoVoterSlug imported?

### Type Error Passing DemoVoterSlug

Check:
1. Service methods have type hints? (Remove them)
2. Service detecting instanceof DemoVoterSlug?
3. Service selecting correct step model?

### SQL Error — Unknown Column

Check:
1. Model references wrong column name?
2. Schema mismatch between step tables?
3. Migration created incorrect columns?

## Schema Requirements

Both voter_slug_steps and demo_voter_slug_steps MUST be identical:

```sql
CREATE TABLE voter_slug_steps (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id BIGINT UNSIGNED,
    voter_slug_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    step TINYINT UNSIGNED NOT NULL,
    step_data JSON,
    completed_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (voter_slug_id) REFERENCES voter_slugs(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX (voter_slug_id, election_id)
);

CREATE TABLE demo_voter_slug_steps (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id BIGINT UNSIGNED,
    voter_slug_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    step TINYINT UNSIGNED NOT NULL,
    step_data JSON,
    completed_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (voter_slug_id) REFERENCES demo_voter_slugs(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX (voter_slug_id, election_id)
);
```

## Common Mistakes

| Wrong | Right |
|-------|-------|
| protected $fillable = ['demo_voter_slug_id'] | protected $fillable = ['voter_slug_id'] |
| public function process(VoterSlug $slug) | public function process($slug) |
| if (!$vslug instanceof VoterSlug) | if (!$vslug instanceof VoterSlug && !$vslug instanceof DemoVoterSlug) |
| Route binding searches one table | Route binding searches both tables |

## Summary

| Issue | File | Fix |
|-------|------|-----|
| Route binding only checks VoterSlug | routes/web.php | Add fallback search for DemoVoterSlug |
| Middleware rejects DemoVoterSlug | EnsureVoterStepOrder.php | Accept both in instanceof check |
| Service type hints reject DemoVoterSlug | VoterStepTrackingService.php | Remove type hints, detect at runtime |
| Model column name mismatch | DemoVoterSlugStep.php | Use voter_slug_id in both tables |

Key Lesson: Demo and real elections are parallel implementations. Every layer must handle both symmetrically.
