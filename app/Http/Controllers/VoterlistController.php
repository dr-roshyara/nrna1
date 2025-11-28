<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class VoterlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Global search filter that searches across multiple fields including name
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")
                      ->orWhere('user_id', 'LIKE', "%{$value}%")
                      ->orWhere('voting_ip', 'LIKE', "%{$value}%");
            });
        });

        // Build query for voters only
        $query = User::where('is_voter', 1);

        $users = QueryBuilder::for($query)
            ->defaultSort('user_id')
            ->allowedSorts(['name', 'user_id', 'voting_ip', 'approvedBy'])
            ->allowedFilters(['name', 'user_id', 'voting_ip', 'approvedBy', $globalSearch])
            ->paginate(2000)
            ->withQueryString();

        // Transform data to ensure all required fields have default values
        $users->getCollection()->transform(function ($user) {
            // Ensure critical fields have default values if null
            // Use setAttribute to safely set values and avoid accessor issues
            return (object) [
                'id' => $user->id ?? null,
                'name' => $user->name ?? 'Unknown',
                'user_id' => $user->user_id ?? 'N/A',
                'can_vote' => $user->can_vote ?? 0,
                'approvedBy' => $user->approvedBy ?? null,
                'suspendedBy' => $user->suspendedBy ?? null,
                'voting_ip' => $user->voting_ip ?? null,
                'user_ip' => $user->user_ip ?? null,
                'is_voter' => $user->is_voter ?? 0,
            ];
        });

        // Check permissions
        $btemp = auth()->user()->hasAnyPermission('send code');

        return Inertia::render('Voter/IndexVoter', [
            'voters' => $users,
            'can_send_code' => $btemp,
            'isCommitteeMember' => auth()->user()->is_committee_member ?? false,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'name'              => 'Name',
                'user_id'           => 'User ID',
                'voting_ip'         => 'Voting IP',
                'approvedBy'        => 'Approved By',
            ])->addColumns([
                'sn'                  => 'S.N.',
                'user_id'             => 'User ID',
                'name'                => 'Name',
                'status'              => 'Voting Status',
                'approved_by'         => 'Status Details',
                'voting_ip'           => 'Voting IP',
                'actions'             => 'Actions'
            ]);
        });
    }

    /**
     * Approve voter - Set can_vote = 1 and store approver name
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * ⚠️ SECURITY FIX: Approve voter using secure setter method
     * Authorization check is now enforced at multiple levels
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveVoter($id)
    {
        try {
            // AUTHORIZATION CHECK #1: Verify current user is committee member
            if (!auth()->user()->is_committee_member) {
                \Log::warning('Unauthorized voter approval attempt', [
                    'attempted_by' => auth()->id(),
                    'target_voter' => $id,
                    'ip' => request()->ip(),
                ]);
                return back()->withErrors(['error' => 'Unauthorized. Only committee members can approve voters.']);
            }

            // Find the voter
            $voter = User::findOrFail($id);

            // AUTHORIZATION CHECK #2: Use secure setter method which validates internally
            $voter->approveForVoting(auth()->user());

            \Log::info('Voter approved successfully', [
                'voter_id' => $voter->id,
                'approved_by' => auth()->user()->name,
                'committee_user_id' => auth()->id(),
            ]);

            return back()->with('success', $voter->name . ' has been approved to vote by ' . auth()->user()->name);

        } catch (\Exception $e) {
            \Log::error('Error approving voter', [
                'voter_id' => $id,
                'error' => $e->getMessage(),
                'attempted_by' => auth()->id(),
            ]);
            return back()->withErrors(['error' => 'Error approving voter: ' . $e->getMessage()]);
        }
    }

    /**
     * Suspend voter - Set can_vote = 0 and store who suspended (keep approver info)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectVoter($id)
    {
        try {
            // AUTHORIZATION CHECK #1: Verify current user is committee member
            if (!auth()->user()->is_committee_member) {
                \Log::warning('Unauthorized voter suspension attempt', [
                    'attempted_by' => auth()->id(),
                    'target_voter' => $id,
                    'ip' => request()->ip(),
                ]);
                return back()->withErrors(['error' => 'Unauthorized. Only committee members can suspend voters.']);
            }

            // Find the voter
            $voter = User::findOrFail($id);

            // AUTHORIZATION CHECK #2: Use secure setter method which validates internally
            $voter->suspendVoting(auth()->user());

            \Log::info('Voter suspended successfully', [
                'voter_id' => $voter->id,
                'suspended_by' => auth()->user()->name,
                'committee_user_id' => auth()->id(),
            ]);

            return back()->with('success', $voter->name . ' voting access has been suspended by ' . auth()->user()->name);

        } catch (\Exception $e) {
            \Log::error('Error suspending voter', [
                'voter_id' => $id,
                'error' => $e->getMessage(),
                'attempted_by' => auth()->id(),
            ]);
            return back()->withErrors(['error' => 'Error suspending voter: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = DB::table('users')->where('id', $id)->first();
        return Inertia::render('User/Profile', [
          'user' => $user,

        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}