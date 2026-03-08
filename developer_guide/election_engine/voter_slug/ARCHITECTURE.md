# Voter Slug System Architecture

## System Overview

The Voter Slug System manages secure, anonymous voting sessions for both real and demo elections.

## Two-Model Architecture

Real Elections:
- VoterSlug (model) -> voter_slugs (table)
- VoterSlugStep (model) -> voter_slug_steps (table)

Demo Elections:
- DemoVoterSlug (model) -> demo_voter_slugs (table)
- DemoVoterSlugStep (model) -> demo_voter_slug_steps (table)

Why? Demo elections need:
- Different lifecycle (always fresh)
- Isolation from real elections
- Same UI/UX as real elections
- Testable without affecting production

## Request Flow

1. User enters code in /election/demo/start
   -> VoterSlugService::getOrCreateSlug()
   -> Creates DemoVoterSlug with fresh slug
   -> Redirects to /v/{slug}/demo-code/create

2. Browser requests /v/{slug}/demo-code/create
   -> Route::bind('vslug') searches both tables
   -> Returns DemoVoterSlug model instance

3. Middleware chain processes request
   -> VerifyVoterSlug (owns slug? is active?)
   -> EnsureVoterStepOrder (can proceed to this step?)

4. Controller receives model instance
   -> Calls VoterStepTrackingService->completeStep()
   -> Service detects DemoVoterSlug
   -> Creates DemoVoterSlugStep record

5. Response shows next form step
   -> User continues through 5-step workflow

## Layer Responsibilities

### Layer 1: Routing (routes/web.php)

Job: Translate URL slug to Eloquent model

Key Pattern:
- Route::bind('vslug') searches VoterSlug first
- Falls back to DemoVoterSlug
- Returns 404 if neither found
- No type hints on route parameters

### Layer 2: Middleware

Job: Validate voter and authorize action

VerifyVoterSlug checks:
- Slug ownership (user_id matches auth user)
- Slug activation (is_active = 1)

EnsureVoterStepOrder checks:
- Step progression (can't skip ahead)
- Slug expiration
- Accepts both VoterSlug and DemoVoterSlug

### Layer 3: Controllers

Job: Handle HTTP requests

Accept both model types:
```php
public function create(Request $request, VoterSlug|DemoVoterSlug $vslug)
{
    // $vslug already bound and validated
}
```

### Layer 4: Services

Job: Business logic (model-agnostic)

VoterStepTrackingService:
- completeStep($voterSlug, $election, $step)
- getHighestCompletedStep($voterSlug, $election)
- getNextStep($voterSlug, $election)
- getCompletedSteps($voterSlug, $election)

Key: Detect model type, select correct step model

VoterSlugService:
- getOrCreateSlug($election, $forceNew)
- Creates VoterSlug or DemoVoterSlug
- Handles expiration logic

### Layer 5: Models

VoterSlug / DemoVoterSlug:
- Relationships (user, steps, election)
- Scopes (by election, by user)
- Boot hooks (auto-set defaults)

VoterSlugStep / DemoVoterSlugStep:
- Identical columns (same fillable array)
- Identical relationships
- Identical scopes
- Same foreign key name (voter_slug_id)

### Layer 6: Database

Tables:
- voter_slugs / demo_voter_slugs
- voter_slug_steps / demo_voter_slug_steps
- elections
- users

Constraint: Step tables IDENTICAL except FK reference

## Design Patterns

### Pattern 1: Runtime Type Detection

Instead of type hints (compile-time):
```php
public function process($voterSlug)  // No type hint!
{
    $isDemo = $voterSlug instanceof DemoVoterSlug;
    $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;
}
```

### Pattern 2: Model Selection

Select once, use everywhere:
```php
$StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

// Use in all queries
$StepModel::where('voter_slug_id', $id)->first();
$StepModel::create($data);
$StepModel::find($id)->update($data);
```

### Pattern 3: Schema Consistency

Identical columns = identical code:
```php
// Works for both step models
$StepModel::where('voter_slug_id', $slug->id)
    ->where('election_id', $election->id)
    ->where('step', $step)
    ->first();
```

### Pattern 4: Middleware Chain

Validate at each layer:
1. Route binding - Get model or 404
2. VerifyVoterSlug - Check ownership
3. EnsureVoterStepOrder - Check progression
4. Controller - Use validated model

## Key Guarantees

1. Type Acceptance
   - Route: Search both, return one or 404
   - Middleware: Accept both or reject
   - Service: Work with both
   - Controllers: Type hint both

2. Column Naming
   - Both step tables: voter_slug_id
   - Models: Same fillable array
   - Queries: All use voter_slug_id

3. Business Logic
   - Same 5-step workflow
   - Same validation rules
   - Same expiration handling
   - Same progression logic

Only difference: Lifecycle
   - Real: Reuse non-expired
   - Demo: Always fresh

## Testing Requirements

Test both models:
- Real election flow (VoterSlug)
- Demo election flow (DemoVoterSlug)
- Cross-model cases
- Expiration handling
- Step progression
- Ownership validation

Test both branches of instanceof:
- if ($slug instanceof VoterSlug) path
- if ($slug instanceof DemoVoterSlug) path

## Performance Notes

Route binding queries both tables:
- First: voter_slugs (usually succeeds)
- Fallback: demo_voter_slugs (rarely needed)

Optimization: Index on (slug, is_active)

Caching: Could cache results but careful with test data

