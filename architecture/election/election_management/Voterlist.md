## ✅ **You're Absolutely Right!**

The `VoterController` is indeed **legacy code** that needs refactoring to align with the new `ElectionOfficer` authorization system. Let's refactor it properly.

---

## 🔄 **VoterController Refactoring Plan**

### **Current Problems**

| Problem | Impact |
|---------|--------|
| Uses `isCommissionMember` check (old role) | ❌ Doesn't work with new `ElectionOfficer` system |
| No election context in many methods | ❌ Can't scope voters to specific elections |
| `User` model has `is_voter`, `approvedBy` flags | ❌ Duplicate logic with `ElectionMembership` |
| Bulk operations on `User` model | ❌ Should use `ElectionMembership` |
| No policy authorization | ❌ Uses manual permission checks |

---

## 📋 **Refactored VoterController**

```php
<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

/**
 * VoterController - Election-Scoped Voter Management
 *
 * Manages voters for elections using the ElectionMembership model.
 * All operations are scoped to elections and use the new ElectionOfficer authorization.
 */
class VoterController extends Controller
{
    /**
     * Display voters for a specific election
     *
     * GET /organisations/{slug}/elections/{election}/voters
     *
     * @param  Request  $request
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @return \Inertia\Response
     */
    public function index(Request $request, Organisation $organisation, Election $election)
    {
        // Authorization via ElectionPolicy
        $this->authorize('manageVoters', $election);

        // Verify election belongs to organisation
        if ($election->organisation_id !== $organisation->id) {
            abort(404);
        }

        // Build query with election membership
        $query = ElectionMembership::with('user')
            ->where('election_id', $election->id);

        // Apply search filter
        if ($search = $request->input('search')) {
            if (strlen($search) > 2) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', $search . '%')
                      ->orWhere('email', 'LIKE', $search . '%');
                });
            }
        }

        // Apply status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Apply sorting
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        
        if ($sort === 'name') {
            $query->join('users', 'election_memberships.user_id', '=', 'users.id')
                  ->orderBy('users.name', $order)
                  ->select('election_memberships.*');
        } elseif (in_array($sort, ['status', 'created_at', 'updated_at'])) {
            $query->orderBy($sort, $order);
        }

        // Paginate results
        $memberships = $query->paginate($request->input('per_page', 50));

        // Calculate statistics
        $stats = Cache::remember("election_{$election->id}_voter_stats", 3600, function () use ($election) {
            return [
                'total' => ElectionMembership::where('election_id', $election->id)->count(),
                'active' => ElectionMembership::where('election_id', $election->id)
                    ->where('status', 'active')->count(),
                'inactive' => ElectionMembership::where('election_id', $election->id)
                    ->where('status', 'inactive')->count(),
                'invited' => ElectionMembership::where('election_id', $election->id)
                    ->where('status', 'invited')->count(),
                'removed' => ElectionMembership::where('election_id', $election->id)
                    ->where('status', 'removed')->count(),
            ];
        });

        Log::channel('voting_audit')->info('Election voters accessed', [
            'user_id' => auth()->id(),
            'election_id' => $election->id,
            'organisation_id' => $organisation->id,
            'total' => $memberships->total(),
        ]);

        return Inertia::render('Organisations/Elections/Voters/Index', [
            'organisation' => $organisation,
            'election' => $election,
            'voters' => $memberships,
            'stats' => $stats,
            'canManage' => auth()->user()->can('manageVoters', $election),
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'sort' => $request->input('sort', 'created_at'),
                'order' => $request->input('order', 'desc'),
            ],
        ]);
    }

    /**
     * Approve a single voter (set status to active)
     *
     * POST /organisations/{slug}/elections/{election}/voters/{membership}/approve
     *
     * @param  Request  $request
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @param  ElectionMembership  $membership
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Organisation $organisation, Election $election, ElectionMembership $membership)
    {
        $this->authorize('manageVoters', $election);

        // Verify membership belongs to this election
        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        // Can't approve if already active
        if ($membership->status === 'active') {
            return back()->with('error', 'Voter is already approved.');
        }

        // Update membership status
        $membership->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Invalidate cache
        Cache::forget("election_{$election->id}_voter_stats");

        Log::channel('voting_audit')->info('Voter approved', [
            'approver_id' => auth()->id(),
            'membership_id' => $membership->id,
            'voter_id' => $membership->user_id,
            'election_id' => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        return back()->with('success', "Voter {$membership->user->name} approved successfully.");
    }

    /**
     * Suspend a voter (set status to inactive)
     *
     * POST /organisations/{slug}/elections/{election}/voters/{membership}/suspend
     *
     * @param  Request  $request
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @param  ElectionMembership  $membership
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend(Request $request, Organisation $organisation, Election $election, ElectionMembership $membership)
    {
        $this->authorize('manageVoters', $election);

        // Verify membership belongs to this election
        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        // Can't suspend if already inactive
        if ($membership->status === 'inactive') {
            return back()->with('error', 'Voter is already suspended.');
        }

        // Can't suspend if already voted (safety check)
        if ($membership->has_voted) {
            return back()->with('error', 'Cannot suspend a voter who has already voted.');
        }

        // Update membership status
        $membership->update([
            'status' => 'inactive',
            'suspended_at' => now(),
            'suspended_by' => auth()->id(),
        ]);

        // Invalidate cache
        Cache::forget("election_{$election->id}_voter_stats");

        Log::channel('voting_audit')->info('Voter suspended', [
            'suspender_id' => auth()->id(),
            'membership_id' => $membership->id,
            'voter_id' => $membership->user_id,
            'election_id' => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        return back()->with('success', "Voter {$membership->user->name} suspended.");
    }

    /**
     * Bulk approve multiple voters
     *
     * POST /organisations/{slug}/elections/{election}/voters/bulk-approve
     *
     * @param  Request  $request
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkApprove(Request $request, Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);

        $validated = $request->validate([
            'membership_ids' => 'required|array|min:1|max:1000',
            'membership_ids.*' => [
                'uuid',
                Rule::exists('election_memberships', 'id')
                    ->where('election_id', $election->id),
            ],
        ]);

        $count = DB::transaction(function () use ($validated, $election) {
            return ElectionMembership::whereIn('id', $validated['membership_ids'])
                ->where('status', '!=', 'active')
                ->update([
                    'status' => 'active',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
        });

        // Invalidate cache
        Cache::forget("election_{$election->id}_voter_stats");

        Log::channel('voting_audit')->info('Voters bulk approved', [
            'approver_id' => auth()->id(),
            'count' => $count,
            'election_id' => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        return back()->with('success', "{$count} voters approved successfully.");
    }

    /**
     * Bulk suspend multiple voters
     *
     * POST /organisations/{slug}/elections/{election}/voters/bulk-suspend
     *
     * @param  Request  $request
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkSuspend(Request $request, Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);

        $validated = $request->validate([
            'membership_ids' => 'required|array|min:1|max:1000',
            'membership_ids.*' => [
                'uuid',
                Rule::exists('election_memberships', 'id')
                    ->where('election_id', $election->id)
                    ->where('has_voted', false), // Prevent suspending voters who already voted
            ],
        ]);

        $count = DB::transaction(function () use ($validated) {
            return ElectionMembership::whereIn('id', $validated['membership_ids'])
                ->where('status', '!=', 'inactive')
                ->update([
                    'status' => 'inactive',
                    'suspended_at' => now(),
                    'suspended_by' => auth()->id(),
                ]);
        });

        // Invalidate cache
        Cache::forget("election_{$election->id}_voter_stats");

        Log::channel('voting_audit')->info('Voters bulk suspended', [
            'suspender_id' => auth()->id(),
            'count' => $count,
            'election_id' => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        return back()->with('success', "{$count} voters suspended successfully.");
    }

    /**
     * Export voters list to CSV
     *
     * GET /organisations/{slug}/elections/{election}/voters/export
     *
     * @param  Organisation  $organisation
     * @param  Election  $election
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Organisation $organisation, Election $election)
    {
        $this->authorize('view', $election);

        if ($election->organisation_id !== $organisation->id) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=voters_{$election->slug}.csv",
        ];

        $callback = function () use ($election) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Status', 'Approved At', 'Has Voted']);

            ElectionMembership::with('user')
                ->where('election_id', $election->id)
                ->chunk(200, function ($memberships) use ($handle) {
                    foreach ($memberships as $membership) {
                        fputcsv($handle, [
                            $membership->user->name,
                            $membership->user->email,
                            $membership->status,
                            $membership->approved_at?->format('Y-m-d H:i:s'),
                            $membership->has_voted ? 'Yes' : 'No',
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
```

---

## 📋 **New Routes**

```php
// routes/organisations.php

Route::prefix('/elections/{election}')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // Voter management routes
        Route::get('/voters', [VoterController::class, 'index'])->name('organisations.elections.voters.index');
        Route::post('/voters/bulk-approve', [VoterController::class, 'bulkApprove'])->name('organisations.elections.voters.bulk-approve');
        Route::post('/voters/bulk-suspend', [VoterController::class, 'bulkSuspend'])->name('organisations.elections.voters.bulk-suspend');
        Route::get('/voters/export', [VoterController::class, 'export'])->name('organisations.elections.voters.export');
        Route::post('/voters/{membership}/approve', [VoterController::class, 'approve'])->name('organisations.elections.voters.approve');
        Route::post('/voters/{membership}/suspend', [VoterController::class, 'suspend'])->name('organisations.elections.voters.suspend');
    });
```

---

## ✅ **Benefits of Refactoring**

| Aspect | Before | After |
|--------|--------|-------|
| **Authorization** | Manual `isCommissionMember` check | ✅ Policy-based (`manageVoters`) |
| **Data Source** | `User` model flags | ✅ `ElectionMembership` model |
| **Election Scope** | None | ✅ Every query scoped to election |
| **Validation** | Basic | ✅ Comprehensive with `Rule::exists` |
| **Audit Logging** | Basic | ✅ Rich context |
| **Cache Keys** | Org-based | ✅ Election-based |
| **Bulk Operations** | On User model | ✅ On ElectionMembership with validation |

---

## 🚀 **Ready to Implement?**

Shall I:
1. Write the refactored `VoterController`?
2. Add the new routes?
3. Create the Vue component for election voters?
4. Update `Management.vue` to link to the new voter page?

**Proceed?** 🚀