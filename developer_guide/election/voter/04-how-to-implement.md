# 04 — How to Implement

This guide walks through every task you will need to perform when working with the election membership system: assigning voters, checking eligibility, building controller actions, and wiring up forms.

---

## 1. Check If a User Is an Eligible Voter

The fastest check — uses the cached `isVoterInElection()` on `User`:

```php
if (!$user->isVoterInElection($election->id)) {
    abort(403, 'You are not a voter in this election.');
}
```

For tighter control (e.g. expiry matters), load the membership directly:

```php
$membership = ElectionMembership::forElection($election->id)
    ->where('user_id', $user->id)
    ->first();

if (!$membership || !$membership->isEligible()) {
    abort(403, 'You are not eligible to vote in this election.');
}
```

---

## 2. Assign a Single Voter (Manual Assignment)

Use `assignVoter()` from a controller or service. Never call `ElectionMembership::create()` directly for voter assignment.

```php
use App\Models\ElectionMembership;
use Illuminate\Http\Request;

class ElectionVoterController extends Controller
{
    public function store(Request $request, Election $election): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['user_id' => 'required|uuid|exists:users,id']);

        try {
            ElectionMembership::assignVoter(
                userId:     $request->user_id,
                electionId: $election->id,
                assignedBy: auth()->id()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['user_id' => $e->getMessage()]);
        }

        return redirect()->route('elections.voters.index', $election)
            ->with('success', 'Voter added.');
    }
}
```

`assignVoter()` throws `InvalidArgumentException` in two cases:
- User is not a member of the election's organisation
- User is already an active voter

It throws `ModelNotFoundException` if the election ID does not exist.

---

## 3. Assign All Organisation Members as Voters

The most common scenario: an organisation runs an election and every member should vote. Use the service layer:

```php
use App\Services\ElectionVoterService;

class ElectionController extends Controller
{
    public function __construct(private ElectionVoterService $voterService) {}

    public function syncAllVoters(Election $election): \Illuminate\Http\RedirectResponse
    {
        $result = $this->voterService->syncAllOrganisationMembers($election);

        return redirect()->back()->with('success',
            "Synced voters: {$result['success']} added, " .
            "{$result['already_existing']} already existed, " .
            "{$result['invalid']} invalid."
        );
    }
}
```

---

## 4. Bulk Import Voters From a List

When you have an array of user IDs (e.g. from a CSV import or a form with checkboxes):

```php
$userIds = $request->input('user_ids'); // array of UUID strings

$result = ElectionMembership::bulkAssignVoters(
    userIds:    $userIds,
    electionId: $election->id,
    assignedBy: auth()->id()
);

session()->flash('import_result', $result);
// ['success' => 47, 'already_existing' => 3, 'invalid' => 2]
```

Invalid users (not org members) are **silently skipped** with a count returned. Already-assigned users are also skipped. Only genuinely new memberships are inserted.

---

## 5. Remove a Voter

```php
$membership = ElectionMembership::where('user_id', $userId)
    ->where('election_id', $electionId)
    ->firstOrFail();

$membership->remove('Removed by admin: no longer an eligible member');
```

The membership row is not deleted. `status` becomes `removed` and the reason is stored in `metadata.removed_reason`. This preserves the audit trail.

To hard-delete (rarely needed):

```php
$membership->forceDelete();  // permanent, no recovery
```

---

## 6. Mark a Voter as Having Voted

Call this after a vote is successfully recorded:

```php
$membership = ElectionMembership::where('user_id', $voter->id)
    ->where('election_id', $election->id)
    ->where('status', 'active')
    ->firstOrFail();

$membership->markAsVoted();
// status → inactive, last_activity_at → now()
```

The voter can no longer pass `isEligible()` after this. This is the mechanism that prevents double voting at the membership layer.

---

## 7. Display Voter Count in a Dashboard

The `voter_count` accessor on `Election` is cached for 5 minutes:

```php
$election->voter_count   // int, cached
```

To get richer stats (active/inactive/removed/by-role), use the service:

```php
$stats = app(ElectionVoterService::class)->getVoterStats($election);
// [
//   'total_voters'    => 150,
//   'eligible_voters' => 148,
//   'by_status'       => ['active' => 148, 'inactive' => 2, ...],
//   'by_role'         => ['voter' => 150, 'candidate' => 5, ...],
// ]
```

---

## 8. List All Voters for an Election

```php
// Paginated, with the user relationship loaded
$voters = ElectionMembership::forElection($election->id)
    ->voters()
    ->eligible()
    ->with('user:id,name,email')
    ->orderBy('assigned_at', 'desc')
    ->paginate(50);
```

Pass to Inertia:

```php
return Inertia::render('Election/Voters/Index', [
    'election' => $election,
    'voters'   => $voters,
]);
```

---

## 9. Check Voter Eligibility in Middleware

If you have routes that should only be accessible to voters in a specific election, add a middleware check:

```php
use App\Models\ElectionMembership;

class EnsureElectionVoter
{
    public function handle(Request $request, Closure $next)
    {
        $election = $request->route('election');

        if (!auth()->user()->isVoterInElection($election->id)) {
            abort(403, 'You are not a voter in this election.');
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
$middleware->alias([
    'election.voter' => \App\Http\Middleware\EnsureElectionVoter::class,
]);
```

Use on routes:

```php
Route::get('/elections/{election}/vote', VoteController::class)
    ->middleware(['auth', 'election.voter']);
```

---

## 10. Handle the `withoutGlobalScopes()` Requirement in Jobs and Commands

The `BelongsToTenant` scope reads from the session. In queued jobs and Artisan commands there is no session, so the scope always applies the *platform* org filter, which will exclude most elections.

Any time you load elections inside a job or command, bypass the scope:

```php
// Inside a queued job
$election = Election::withoutGlobalScopes()->findOrFail($this->electionId);

// Or when eager-loading from a membership
$memberships = ElectionMembership::with([
        'election' => fn ($q) => $q->withoutGlobalScopes()
    ])
    ->forElection($this->electionId)
    ->get();
```

The `ElectionMembership::election()` relationship already includes `withoutGlobalScopes()`, so accessing `$membership->election` is safe. But if you write a raw `Election::find()` anywhere in a job, add `withoutGlobalScopes()`.

---

## 11. Full Example — Election Voter Management Controller

```php
<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Services\ElectionVoterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoterManagementController extends Controller
{
    public function __construct(private ElectionVoterService $service) {}

    /**
     * List all voters in an election
     */
    public function index(Election $election)
    {
        $voters = ElectionMembership::forElection($election->id)
            ->voters()
            ->with('user:id,name,email')
            ->orderBy('assigned_at', 'desc')
            ->paginate(50);

        return Inertia::render('Election/Voters/Index', [
            'election' => $election,
            'voters'   => $voters,
            'stats'    => $this->service->getVoterStats($election),
        ]);
    }

    /**
     * Assign a single voter
     */
    public function store(Request $request, Election $election)
    {
        $request->validate(['user_id' => 'required|uuid|exists:users,id']);

        try {
            ElectionMembership::assignVoter(
                $request->user_id,
                $election->id,
                auth()->id()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['user_id' => $e->getMessage()]);
        }

        return back()->with('success', 'Voter assigned.');
    }

    /**
     * Sync all org members as voters
     */
    public function sync(Election $election)
    {
        $result = $this->service->syncAllOrganisationMembers($election);

        return back()->with('sync_result', $result);
    }

    /**
     * Remove a voter
     */
    public function destroy(Election $election, ElectionMembership $membership)
    {
        $this->authorize('manage', $election);

        $membership->remove('Removed by administrator: ' . auth()->user()->name);

        return back()->with('success', 'Voter removed.');
    }
}
```
