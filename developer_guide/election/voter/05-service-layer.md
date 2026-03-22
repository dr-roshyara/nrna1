# 05 — Service Layer

The `ElectionVoterService` handles operations that are too complex or too heavy for a model method: syncing entire organisations, generating reports, and computing statistics with caching.

**File:** `app/Services/ElectionVoterService.php` *(to be created — see implementation below)*

---

## Why a Service Layer?

Model methods like `assignVoter()` handle individual rows. The service layer handles **orchestration**: coordinating multiple models, caching aggregated results, and providing a clean API for controllers without letting business logic leak into HTTP handlers.

---

## `syncAllOrganisationMembers(Election $election): array`

Reads every user from `user_organisation_roles` for the election's organisation and bulk-assigns them all as voters.

```php
$result = $service->syncAllOrganisationMembers($election);
// ['success' => 87, 'already_existing' => 12, 'invalid' => 0]
```

**When to use:** "Everyone in the organisation gets to vote in this election."

**What it does:**
1. Pulls all `user_id` values from `user_organisation_roles` where `organisation_id` matches
2. Calls `ElectionMembership::bulkAssignVoters()` — single transaction, no N+1
3. Invalidates the voter count and stats caches

---

## `getVoterStats(Election $election): array`

Returns a cached breakdown of membership counts. TTL: 5 minutes.

```php
$stats = $service->getVoterStats($election);
```

Returns:

```php
[
    'total_memberships' => 155,
    'total_voters'      => 150,
    'eligible_voters'   => 148,
    'expired_memberships' => 2,
    'by_status' => [
        'active'   => 148,
        'inactive' => 4,
        'invited'  => 1,
        'removed'  => 2,
    ],
    'by_role' => [
        'voter'     => 150,
        'candidate' => 5,
        'observer'  => 0,
        'admin'     => 0,
    ],
]
```

Cache key: `election.{id}.voter_stats` — automatically cleared by `ElectionMembership::booted()` when any membership row changes.

---

## `exportVoterList(Election $election): \Illuminate\Support\Collection`

Returns a flat collection suitable for CSV export or display:

```php
$rows = $service->exportVoterList($election);
// Each row: ['email', 'name', 'voter_id', 'assigned_at', 'expires_at']
```

---

## Implementation

Create `app/Services/ElectionVoterService.php`:

```php
<?php

namespace App\Services;

use App\Models\Election;
use App\Models\ElectionMembership;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ElectionVoterService
{
    /**
     * Assign every current organisation member as a voter in the given election.
     * Skips users who are already assigned. Returns operation summary.
     */
    public function syncAllOrganisationMembers(Election $election): array
    {
        return DB::transaction(function () use ($election) {
            $memberIds = DB::table('user_organisation_roles')
                ->where('organisation_id', $election->organisation_id)
                ->pluck('user_id')
                ->toArray();

            $result = ElectionMembership::bulkAssignVoters(
                $memberIds,
                $election->id,
                auth()->id()
            );

            Log::info('Synced all organisation members to election', [
                'election_id'     => $election->id,
                'organisation_id' => $election->organisation_id,
                'result'          => $result,
            ]);

            return $result;
        });
    }

    /**
     * Get cached voter statistics for an election.
     * Cache key: "election.{id}.voter_stats", TTL: 5 minutes.
     * Automatically invalidated by ElectionMembership::booted() hooks.
     */
    public function getVoterStats(Election $election): array
    {
        return Cache::remember(
            "election.{$election->id}.voter_stats",
            300,
            function () use ($election) {
                $base = ElectionMembership::where('election_id', $election->id);

                return [
                    'total_memberships'   => (clone $base)->count(),
                    'total_voters'        => (clone $base)->where('role', 'voter')->count(),
                    'eligible_voters'     => (clone $base)->eligible()->where('role', 'voter')->count(),
                    'expired_memberships' => (clone $base)->whereNotNull('expires_at')
                                                           ->where('expires_at', '<', now())
                                                           ->count(),
                    'by_status' => [
                        'active'   => (clone $base)->where('status', 'active')->count(),
                        'inactive' => (clone $base)->where('status', 'inactive')->count(),
                        'invited'  => (clone $base)->where('status', 'invited')->count(),
                        'removed'  => (clone $base)->where('status', 'removed')->count(),
                    ],
                    'by_role' => [
                        'voter'     => (clone $base)->where('role', 'voter')->count(),
                        'candidate' => (clone $base)->where('role', 'candidate')->count(),
                        'observer'  => (clone $base)->where('role', 'observer')->count(),
                        'admin'     => (clone $base)->where('role', 'admin')->count(),
                    ],
                ];
            }
        );
    }

    /**
     * Export voter list as a flat collection (use for CSV, display, etc.)
     */
    public function exportVoterList(Election $election): \Illuminate\Support\Collection
    {
        return ElectionMembership::forElection($election->id)
            ->voters()
            ->eligible()
            ->with('user:id,name,email')
            ->get()
            ->map(fn ($m) => [
                'email'       => $m->user?->email ?? '',
                'name'        => $m->user?->name ?? '',
                'voter_id'    => $m->id,
                'assigned_at' => $m->assigned_at?->toDateTimeString(),
                'expires_at'  => $m->expires_at?->toDateTimeString(),
            ]);
    }
}
```

---

## Registering as a Singleton

Add to `app/Providers/AppServiceProvider.php` in the `register()` method:

```php
$this->app->singleton(\App\Services\ElectionVoterService::class);
```

This ensures a single instance is shared across the request lifecycle, which is important for caching — repeated calls to `getVoterStats()` within a single request will hit the singleton's state rather than creating new instances.

---

## Using the Service in Controllers

Inject via the constructor (Laravel resolves it automatically):

```php
use App\Services\ElectionVoterService;

class ElectionDashboardController extends Controller
{
    public function __construct(private ElectionVoterService $voterService) {}

    public function show(Election $election)
    {
        return Inertia::render('Election/Dashboard', [
            'stats' => $this->voterService->getVoterStats($election),
        ]);
    }
}
```

Or resolve from the container when you need it in a command or job:

```php
$service = app(ElectionVoterService::class);
$result = $service->syncAllOrganisationMembers($election);
```

---

## Integrity Monitoring Command

Create `app/Console/Commands/ValidateElectionMemberships.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidateElectionMemberships extends Command
{
    protected $signature   = 'elections:validate-memberships';
    protected $description = 'Audit election_memberships for rows that violate org/election integrity';

    public function handle(): int
    {
        $this->info('Running election membership integrity check...');

        // Find rows where user is no longer in the linked organisation
        $orphaned = DB::table('election_memberships as em')
            ->leftJoin('user_organisation_roles as uor', function ($join) {
                $join->on('em.user_id',         '=', 'uor.user_id')
                     ->on('em.organisation_id', '=', 'uor.organisation_id');
            })
            ->whereNull('uor.user_id')
            ->select('em.id', 'em.user_id', 'em.election_id', 'em.organisation_id')
            ->get();

        if ($orphaned->isEmpty()) {
            $this->info('No integrity violations found.');
            return self::SUCCESS;
        }

        $this->error("Found {$orphaned->count()} orphaned membership(s):");

        $this->table(
            ['Membership ID', 'User ID', 'Election ID', 'Organisation ID'],
            $orphaned->map(fn ($r) => [$r->id, $r->user_id, $r->election_id, $r->organisation_id])
        );

        Log::warning('Orphaned election memberships detected', [
            'count' => $orphaned->count(),
            'ids'   => $orphaned->pluck('id'),
        ]);

        return self::FAILURE;
    }
}
```

Schedule it in `routes/console.php` (Laravel 11 style):

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('elections:validate-memberships')->daily();
```

Run manually:

```bash
php artisan elections:validate-memberships
```
