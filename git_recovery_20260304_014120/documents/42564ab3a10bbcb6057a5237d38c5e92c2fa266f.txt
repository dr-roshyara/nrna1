# Voter Slug Steps: Implementation Guide

**Date:** March 2, 2026
**Version:** 1.0
**Audience:** Backend Developers

---

## Quick Reference

### The Model

```php
// app/Models/VoterSlugStep.php
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // Multi-tenant isolation

    protected $fillable = [
        'voter_slug_id',
        'election_id',
        'organisation_id',  // CRITICAL - always set!
        'step',
        'ip_address',
        'started_at',
        'completed_at',
        'metadata',
    ];
}
```

### The Database Schema

```sql
CREATE TABLE voter_slug_steps (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    voter_slug_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED,  -- Added: Tenant isolation
    step SMALLINT UNSIGNED NOT NULL,
    ip_address VARCHAR(255),
    started_at TIMESTAMP,
    completed_at TIMESTAMP,
    metadata JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- Foreign keys
    FOREIGN KEY (voter_slug_id) REFERENCES voter_slugs(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,

    -- Indexes
    UNIQUE (voter_slug_id, election_id, step),
    INDEX (voter_slug_id, step),
    INDEX (organisation_id),  -- NEW: For tenant filtering
);
```

---

## Common Operations

### 1. Record a Step (When Voter Completes an Action)

```php
// Service: app/Services/VoterStepService.php

class VoterStepService
{
    /**
     * Record that a voter completed a step
     */
    public function recordStepCompletion(
        VoterSlug $slug,
        int $stepNumber,
        string $ipAddress,
        ?array $metadata = null
    ): VoterSlugStep {
        // Always verify the slug is in the correct organisation
        $this->validateTenantAccess($slug);

        // Check if step already exists for this slug
        $existingStep = VoterSlugStep::where('voter_slug_id', $slug->id)
            ->where('step', $stepNumber)
            ->first();

        if ($existingStep) {
            // Update completed_at if not already done
            if (!$existingStep->completed_at) {
                $existingStep->update([
                    'completed_at' => now(),
                    'metadata' => $metadata ?? $existingStep->metadata,
                ]);
            }
            return $existingStep;
        }

        // Create new step record
        // NOTE: organisation_id is automatically set from session or can be explicit
        $step = VoterSlugStep::create([
            'voter_slug_id' => $slug->id,
            'election_id' => $slug->election_id,
            'organisation_id' => $slug->organisation_id,  // Explicit assignment
            'step' => $stepNumber,
            'ip_address' => $ipAddress,
            'started_at' => now(),
            'completed_at' => now(),
            'metadata' => $metadata ?? [],
        ]);

        Log::info('Step recorded', [
            'voter_slug_id' => $slug->id,
            'step' => $stepNumber,
            'organisation_id' => $slug->organisation_id,
        ]);

        return $step;
    }

    private function validateTenantAccess(VoterSlug $slug): void
    {
        $currentOrgId = session('current_organisation_id');
        if ($slug->organisation_id !== $currentOrgId) {
            throw new TenantIsolationException(
                "Voter slug belongs to organisation {$slug->organisation_id}, "
                . "but request is from organisation {$currentOrgId}"
            );
        }
    }
}
```

**Usage:**

```php
// In controller
$slug = VoterSlug::findOrFail($slugId);

$this->stepService->recordStepCompletion(
    slug: $slug,
    stepNumber: 1,  // Code entry step
    ipAddress: $request->ip(),
    metadata: ['code_entered' => true]
);
```

### 2. Get Voter's Progress (All Steps for a Voter)

```php
// Service: app/Services/VoterProgressService.php

class VoterProgressService
{
    /**
     * Get all steps completed by a voter in an election
     */
    public function getVoterProgress(VoterSlug $slug): array
    {
        // Query automatically filtered by organisation_id via global scope
        $steps = VoterSlugStep::where('voter_slug_id', $slug->id)
            ->orderBy('step')
            ->get();

        return [
            'voter_slug_id' => $slug->id,
            'election_id' => $slug->election_id,
            'total_steps' => 5,
            'completed_steps' => $steps->count(),
            'current_step' => $steps->max('step') ?? 0,
            'progress' => [
                1 => $this->formatStep($steps->firstWhere('step', 1)),
                2 => $this->formatStep($steps->firstWhere('step', 2)),
                3 => $this->formatStep($steps->firstWhere('step', 3)),
                4 => $this->formatStep($steps->firstWhere('step', 4)),
                5 => $this->formatStep($steps->firstWhere('step', 5)),
            ],
        ];
    }

    private function formatStep(?VoterSlugStep $step): array
    {
        if (!$step) {
            return ['completed' => false];
        }

        return [
            'completed' => true,
            'started_at' => $step->started_at,
            'completed_at' => $step->completed_at,
            'duration_seconds' => $step->completed_at
                ? $step->completed_at->diffInSeconds($step->started_at)
                : null,
            'ip_address' => $step->ip_address,
        ];
    }
}
```

**Usage:**

```php
$slug = VoterSlug::findOrFail($slugId);
$progress = $this->progressService->getVoterProgress($slug);

// Result:
[
    'voter_slug_id' => 123,
    'election_id' => 456,
    'total_steps' => 5,
    'completed_steps' => 3,
    'current_step' => 3,
    'progress' => [
        1 => ['completed' => true, 'started_at' => ..., ...],
        2 => ['completed' => true, ...],
        3 => ['completed' => true, ...],
        4 => ['completed' => false],
        5 => ['completed' => false],
    ]
]
```

### 3. Check If Voter Completed a Specific Step

```php
// In middleware or controller
public function hasCompletedStep(VoterSlug $slug, int $step): bool
{
    return VoterSlugStep::where('voter_slug_id', $slug->id)
        ->where('step', $step)
        ->where('completed_at', '!=', null)
        ->exists();
}

// Usage:
if (!$this->hasCompletedStep($slug, 2)) {
    return response()->json(['error' => 'Please accept agreement first'], 403);
}
```

### 4. Get Audit Trail for a Voter

```php
// For compliance and fraud detection
public function getAuditTrail(VoterSlug $slug): array
{
    $steps = VoterSlugStep::where('voter_slug_id', $slug->id)
        ->orderBy('created_at')
        ->get();

    return $steps->map(fn($step) => [
        'timestamp' => $step->created_at,
        'step' => $step->step,
        'ip_address' => $step->ip_address,
        'started_at' => $step->started_at,
        'completed_at' => $step->completed_at,
        'duration' => $step->completed_at
            ? $step->completed_at->diff($step->started_at)
            : null,
        'metadata' => $step->metadata,
    ])->toArray();
}

// Result:
[
    [
        'timestamp' => '2026-03-02 10:15:30',
        'step' => 1,
        'ip_address' => '192.168.1.100',
        'started_at' => '2026-03-02 10:15:30',
        'completed_at' => '2026-03-02 10:15:45',
        'duration' => '15 seconds',
    ],
    [
        'timestamp' => '2026-03-02 10:15:46',
        'step' => 2,
        'ip_address' => '192.168.1.100',
        'started_at' => '2026-03-02 10:15:46',
        'completed_at' => '2026-03-02 10:16:20',
        'duration' => '34 seconds',
    ],
]
```

### 5. Get All Steps for an Election (Admin Only)

```php
// For reporting - note the explicit withoutGlobalScopes()
public function getElectionStatistics(Election $election): array
{
    // IMPORTANT: withoutGlobalScopes() bypasses tenant filtering
    // This is for admin reporting only!
    $steps = VoterSlugStep::withoutGlobalScopes()
        ->where('election_id', $election->id)
        ->where('organisation_id', $election->organisation_id)  // Re-apply scoping!
        ->get();

    $stepStats = [];
    for ($step = 1; $step <= 5; $step++) {
        $stepsForThisStep = $steps->where('step', $step);
        $stepStats[$step] = [
            'total' => $stepsForThisStep->count(),
            'completed' => $stepsForThisStep->whereNotNull('completed_at')->count(),
            'avg_duration' => $stepsForThisStep
                ->filter(fn($s) => $s->completed_at)
                ->avg(fn($s) => $s->completed_at->diffInSeconds($s->started_at)),
            'completion_rate' => $stepsForThisStep->count() > 0
                ? ($stepsForThisStep->whereNotNull('completed_at')->count() / $stepsForThisStep->count()) * 100
                : 0,
        ];
    }

    return [
        'election_id' => $election->id,
        'total_voters' => $steps->pluck('voter_slug_id')->unique()->count(),
        'step_statistics' => $stepStats,
    ];
}
```

---

## Middleware Integration

### Accessing Steps in Middleware

```php
// app/Http/Middleware/VerifyVoterSlugWindow.php

class VerifyVoterSlugWindow
{
    public function handle($request, Closure $next)
    {
        $voterSlug = $request->attributes->get('voter_slug');

        if (!$voterSlug) {
            throw new InvalidVoterSlugException('No voter slug found');
        }

        // Get current step progress
        // This is automatically scoped to organisation via BelongsToTenant!
        $lastStep = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->orderByDesc('step')
            ->first();

        // Store for later use in controller
        $request->attributes->set('current_step', $lastStep?->step ?? 0);
        $request->attributes->set('step_details', $lastStep);

        return $next($request);
    }
}
```

### Using Step Data in Controllers

```php
// app/Http/Controllers/VoterController.php

class VoterController extends Controller
{
    public function showVoteSelection($slug)
    {
        $voterSlug = $this->getVerifiedSlug($slug);
        $currentStep = request()->attributes->get('current_step');

        // Require completion of step 2 (agreement)
        if ($currentStep < 2) {
            return redirect()->route('step.agreement')
                ->with('error', 'Please accept the agreement first');
        }

        // Get step 3 details if exists
        $step3 = request()->attributes->get('step_details');

        return view('voting.select', [
            'slug' => $voterSlug,
            'current_step' => $currentStep,
            'step_details' => $step3,
        ]);
    }
}
```

---

## Data Integrity Patterns

### Ensure One Step Per Voter Per Step Number

```php
// Using unique constraint
VoterSlugStep::updateOrCreate(
    [
        'voter_slug_id' => $slug->id,
        'election_id' => $slug->election_id,
        'step' => 1,
    ],
    [
        'organisation_id' => $slug->organisation_id,
        'ip_address' => $request->ip(),
        'started_at' => now(),
        'completed_at' => now(),
    ]
);

// Database constraint ensures:
// UNIQUE (voter_slug_id, election_id, step)
// So only one row can exist per voter per step
```

### Prevent Step Skipping

```php
public function validateStepSequence(VoterSlug $slug, int $requestedStep): bool
{
    $lastCompletedStep = VoterSlugStep::where('voter_slug_id', $slug->id)
        ->where('completed_at', '!=', null)
        ->orderByDesc('step')
        ->value('step') ?? 0;

    // Can only proceed to next step or repeat current step
    return $requestedStep <= $lastCompletedStep + 1;
}

// Usage in controller:
if (!$this->validateStepSequence($slug, $requestedStep)) {
    return response()->json(
        ['error' => 'You must complete previous steps first'],
        403
    );
}
```

---

## Common Gotchas

### ❌ Gotcha 1: Forgetting organisation_id When Creating

```php
// WRONG - Will fail when organisation_id is NOT NULL
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'election_id' => $slug->election_id,
    'step' => 1,
    // Missing organisation_id!
]);

// RIGHT - Always include it
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'election_id' => $slug->election_id,
    'organisation_id' => $slug->organisation_id,  // ✅
    'step' => 1,
]);
```

### ❌ Gotcha 2: Querying Without Considering Global Scope

```php
// DANGER - If BelongsToTenant has a bug or session is NULL,
// this could return ALL steps, not just current organisation's
$allSteps = VoterSlugStep::all();

// SAFER - Explicitly check and filter
$organisationId = session('current_organisation_id');
if (!$organisationId) {
    throw new Exception('No organisation context');
}

$steps = VoterSlugStep::where('organisation_id', $organisationId)->get();
```

### ❌ Gotcha 3: Using withoutGlobalScopes Without Re-filtering

```php
// DANGER - This returns steps from ALL organisations!
$steps = VoterSlugStep::withoutGlobalScopes()->get();

// SAFE - Explicitly filter after bypassing scope
$steps = VoterSlugStep::withoutGlobalScopes()
    ->where('organisation_id', session('current_organisation_id'))
    ->get();
```

### ❌ Gotcha 4: Not Handling Orphaned Records

```php
// When an election is deleted, what happens to its steps?
// Thanks to foreign key CASCADE DELETE, they're automatically removed!

// Verify this works:
$election = Election::find($id);
$election->delete();

// All voter_slug_steps for this election are deleted automatically
$this->assertEquals(0, VoterSlugStep::where('election_id', $id)->count());
```

---

## Performance Considerations

### Index Usage

```php
// These queries will use the index efficiently:

// Fast: Uses index on (voter_slug_id, step)
VoterSlugStep::where('voter_slug_id', 123)
    ->where('step', 1)
    ->first();

// Fast: Uses index on organisation_id
VoterSlugStep::where('organisation_id', 2)->count();

// Slower: Full table scan (no single index matches)
VoterSlugStep::where('ip_address', '192.168.1.1')->get();
```

### Eager Loading

```php
// Bad: N+1 query problem
$steps = VoterSlugStep::where('election_id', 123)->get();
foreach ($steps as $step) {
    echo $step->voterSlug->user->name;  // Extra query per step!
}

// Good: Load relationships upfront
$steps = VoterSlugStep::with(['voterSlug.user', 'election'])
    ->where('election_id', 123)
    ->get();

foreach ($steps as $step) {
    echo $step->voterSlug->user->name;  // Already loaded!
}
```

### Aggregation with Tenant Filtering

```php
// The global scope is automatically applied to aggregates
$countByStep = VoterSlugStep::where('election_id', 123)
    ->groupBy('step')
    ->selectRaw('step, COUNT(*) as count')
    ->get();
// WHERE organisation_id = ? is automatically added!

$avgDuration = VoterSlugStep::where('election_id', 123)
    ->whereNotNull('completed_at')
    ->avg(DB::raw('TIMESTAMPDIFF(SECOND, started_at, completed_at)'));
// WHERE organisation_id = ? is automatically added!
```

---

## Testing Patterns

### Unit Test: Create and Retrieve

```php
public function test_can_create_voter_step_with_organisation()
{
    $slug = VoterSlug::factory()->create();

    $step = VoterSlugStep::create([
        'voter_slug_id' => $slug->id,
        'election_id' => $slug->election_id,
        'organisation_id' => $slug->organisation_id,
        'step' => 1,
        'ip_address' => '192.168.1.1',
    ]);

    $this->assertDatabaseHas('voter_slug_steps', [
        'voter_slug_id' => $slug->id,
        'organisation_id' => $slug->organisation_id,
        'step' => 1,
    ]);
}
```

### Feature Test: Cross-Tenant Isolation

```php
public function test_voter_slug_steps_are_isolated_by_organisation()
{
    $org1 = Organisation::factory()->create();
    $org2 = Organisation::factory()->create();

    // Create steps in each organisation
    $slug1 = VoterSlug::factory(['organisation_id' => $org1->id])->create();
    $slug2 = VoterSlug::factory(['organisation_id' => $org2->id])->create();

    $step1 = VoterSlugStep::create([
        'voter_slug_id' => $slug1->id,
        'election_id' => $slug1->election_id,
        'organisation_id' => $org1->id,
        'step' => 1,
    ]);

    $step2 = VoterSlugStep::create([
        'voter_slug_id' => $slug2->id,
        'election_id' => $slug2->election_id,
        'organisation_id' => $org2->id,
        'step' => 1,
    ]);

    // When viewing as Org 1, can only see Org 1's steps
    session(['current_organisation_id' => $org1->id]);
    $visibleSteps = VoterSlugStep::all();
    $this->assertCount(1, $visibleSteps);
    $this->assertEquals($step1->id, $visibleSteps[0]->id);

    // Org 1 cannot access Org 2's step
    $this->assertNull(VoterSlugStep::find($step2->id));
}
```

---

## Migration Notes

### Applied: March 2, 2026

```bash
# To apply this migration:
php artisan migrate

# To verify:
php artisan migrate:status | grep "add_organisation_id_to_voter_slug_steps"

# Expected output:
# 2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table ......... 2026_03_02_10:14 Batch 1
```

### Rollback (If Needed)

```bash
# To rollback this migration:
php artisan migrate:rollback --step=1

# Verify rollback:
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> false
```

---

## Related Links

- [Multi-Tenancy Isolation Guide](./01-multi-tenancy-isolation.md)
- [Testing Guide](../../developer_guide/06-testing-guide.md)
- [API Reference](../../developer_guide/05-api-reference.md)
- [Troubleshooting](../../developer_guide/07-troubleshooting.md)

---

**Last Updated:** March 2, 2026
**Maintained By:** Development Team
**Status:** ✅ Production Ready
