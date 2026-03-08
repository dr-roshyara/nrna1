# Voter Slug System Documentation Index

Complete guide to the voter slug system that manages voting sessions for real and demo elections.

## Quick Links

### For First-Time Readers
Start with [README.md](./README.md) - Overview of the system and the four critical fixes.

### For Architecture Understanding
Read [ARCHITECTURE.md](./ARCHITECTURE.md) - System layers, data flow, and design patterns.

### For Implementation Details
- [MODEL_BINDING.md](./MODEL_BINDING.md) - How URL slugs are resolved to models
- [SCHEMA_CONSISTENCY.md](./SCHEMA_CONSISTENCY.md) - Database schema requirements
- [DEBUGGING.md](./DEBUGGING.md) - Troubleshooting checklist

---

## The Problem We Solved

Demo elections (with organisation_id = NULL) were completely inaccessible due to four issues:

1. **Route binding** only checked VoterSlug table, not DemoVoterSlug
2. **Middleware** only accepted VoterSlug type, rejected DemoVoterSlug
3. **Service** had type hints that rejected DemoVoterSlug at call time
4. **Model** referenced wrong column name (demo_voter_slug_id vs voter_slug_id)

Each issue blocked the next layer. Fixing all four restored demo elections.

---

## Core Concepts

### Two Models, Same Purpose

Real Elections:
- VoterSlug model
- voter_slugs table
- VoterSlugStep model
- voter_slug_steps table

Demo Elections:
- DemoVoterSlug model
- demo_voter_slugs table
- DemoVoterSlugStep model
- demo_voter_slug_steps table

Demo elections are fully isolated from real elections for testing.

### The Five-Step Voting Workflow

All voters progress through:
1. Code Entry - Enter voting code
2. Agreement - Accept terms
3. Vote Selection - Choose candidates
4. Vote Verification - Review selections
5. Vote Completion - Submit and finish

Both VoterSlugStep and DemoVoterSlugStep track progress identically.

### Polymorphic Design

The same code handles both model types through runtime detection:

```php
$isDemo = $voterSlug instanceof DemoVoterSlug;
$StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;
```

No branching in queries - same logic works for both.

---

## Critical Areas

### 1. Route Model Binding (routes/web.php)

Entry point for all slug-based routes.

Must search both tables:
```php
Route::bind('vslug', function ($value) {
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->where('slug', $value)->first();
    
    if (!$voterSlug) {
        $voterSlug = DemoVoterSlug::withoutGlobalScopes()
            ->where('slug', $value)->first();
    }
    
    if (!$voterSlug) abort(404);
    return $voterSlug;
});
```

**Why both?** Demo elections use same URL parameter as real elections.
**Why withoutGlobalScopes()?** Demo slugs have organisation_id=NULL.

### 2. Middleware Type Checking

EnsureVoterStepOrder and VerifyVoterSlug validate voter slugs.

Must accept both models:
```php
if (!$vslug instanceof VoterSlug && !$vslug instanceof DemoVoterSlug) {
    abort(403, 'Invalid voting link.');
}
```

**Why compound check?** Each model type is valid for different use cases.

### 3. Service Polymorphism

VoterStepTrackingService handles both slug types.

Remove type hints, detect at runtime:
```php
public function completeStep($voterSlug, Election $election, int $step, array $stepData = [])
{
    $isDemo = $voterSlug instanceof DemoVoterSlug;
    $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;
    
    // Same code for both models
    $result = $StepModel::where('voter_slug_id', $voterSlug->id)->first();
}
```

**Why no type hints?** Type hints reject DemoVoterSlug at call time.

### 4. Database Schema Consistency

Both step tables must be identical columns (except FK reference).

voter_slug_steps and demo_voter_slug_steps must have:
- Same column names (voter_slug_id, not demo_voter_slug_id)
- Same data types
- Same indexes
- Same constraints

**Why identical?** Enables model-agnostic queries.

---

## Common Mistakes

| Wrong | Right |
|-------|-------|
| Type hints on slug params | Remove all type hints for slug |
| Column: demo_voter_slug_id | Column: voter_slug_id (same for both) |
| if (!$slug instanceof VoterSlug) | if (!$slug instanceof VoterSlug && !$slug instanceof DemoVoterSlug) |
| Route binding searches one table | Route binding searches both tables |
| Service code branches by model type | Service detects type, selects model, same code for both |

---

## Debugging Workflow

1. **404 Not Found?** → Check Route::bind() searches both tables
2. **403 Forbidden?** → Check middleware instanceof accepts both models
3. **Type Error?** → Check service has no type hints
4. **Unknown Column?** → Check model uses voter_slug_id

See [DEBUGGING.md](./DEBUGGING.md) for detailed checklist.

---

## File Structure

```
developer_guide/
└── election_engine/
    └── voter_slug/
        ├── INDEX.md                 <- You are here
        ├── README.md                <- System overview + four fixes
        ├── ARCHITECTURE.md          <- System design and layers
        ├── MODEL_BINDING.md         <- Route binding details
        ├── SCHEMA_CONSISTENCY.md    <- Database requirements
        └── DEBUGGING.md             <- Troubleshooting guide
```

---

## Key Files to Modify

When working with voter slug system:

| File | Purpose | Key Change |
|------|---------|-----------|
| routes/web.php | Route model binding | Search both VoterSlug and DemoVoterSlug |
| EnsureVoterStepOrder.php | Middleware validation | Accept both model types |
| VerifyVoterSlug.php | Middleware validation | Accept both model types |
| VoterStepTrackingService.php | Step tracking | Remove type hints, detect at runtime |
| VoterSlugStep.php | Model definition | Ensure fillable array matches DemoVoterSlugStep |
| DemoVoterSlugStep.php | Model definition | Use voter_slug_id column name |
| Database migrations | Schema | Keep both step tables identical |

---

## Testing the System

Verify both paths work:

```bash
# Real election flow
1. Create election with organisation_id
2. Create voter slug
3. Visit /v/{slug}/demo-code/create
4. Verify VoterSlug is used

# Demo election flow
1. Create election with organisation_id = NULL
2. Create demo voter slug
3. Visit /v/{slug}/demo-code/create
4. Verify DemoVoterSlug is used
```

---

## Future Improvements

1. Extract base SlugModel class (DRY principle)
2. Add repository pattern for slug queries
3. Event-driven step completion
4. Audit logging for all slug operations
5. Performance metrics collection
6. Mobile app support

---

## Questions?

Refer to specific guides:
- System architecture? → ARCHITECTURE.md
- How URL slug resolves? → MODEL_BINDING.md
- Database structure? → SCHEMA_CONSISTENCY.md
- Something broken? → DEBUGGING.md
- Overall overview? → README.md

---

## Key Takeaway

**Demo and real elections are parallel implementations that must be handled symmetrically at every layer.**

Any new feature for voter slugs must work for both VoterSlug and DemoVoterSlug.

Consistency across both models is non-negotiable.

