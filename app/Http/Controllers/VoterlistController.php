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
                      ->orWhere('nrna_id', 'LIKE', "%{$value}%")
                      ->orWhere('voting_ip', 'LIKE', "%{$value}%");
            });
        });

        // Build query for voters only
        $query = User::where('is_voter', 1);

        $users = QueryBuilder::for($query)
            ->defaultSort('name')
            ->allowedSorts(['name', 'user_id', 'nrna_id', 'voting_ip', 'approvedBy'])
            ->allowedFilters(['name', 'user_id', 'nrna_id', 'voting_ip', 'approvedBy', $globalSearch])
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
                'nrna_id' => $user->nrna_id ?? null,
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
                'nrna_id'           => 'NRNA ID',
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
    public function approveVoter($id)
    {
        try {
            // Check if current user is committee member
            if (!auth()->user()->is_committee_member) {
                return back()->withErrors(['error' => 'Unauthorized. Only committee members can approve voters.']);
            }

            // Find the user
            $user = User::findOrFail($id);

            // Check if user is a voter
            if (!$user->is_voter) {
                return back()->withErrors(['error' => 'User is not registered as a voter.']);
            }

            // Update can_vote to 1, set approver, and capture voting_ip from user_ip, and clear suspension info
            $user->update([
                'can_vote' => 1,
                'voting_ip'=>$user->user_ip,  // Save user's current IP as voting IP
                'approvedBy' => auth()->user()->name,
                'suspendedBy' => null,      // Clear suspension info when approved
                'suspended_at' => null      // Clear suspension timestamp
            ]);

            return back()->with('success', $user->name . ' has been approved to vote by ' . auth()->user()->name);

        } catch (\Exception $e) {
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
            // Check if current user is committee member
            if (!auth()->user()->is_committee_member) {
                return back()->withErrors(['error' => 'Unauthorized. Only committee members can suspend voters.']);
            }

            // Find the user
            $user = User::findOrFail($id);

            // Update can_vote to 0 and track suspension (KEEP approvedBy info)
            $user->update([
                'can_vote' => 0,
                'suspendedBy' => auth()->user()->name,  // Track who suspended
                'suspended_at' => now()                 // Track when suspended
                // approvedBy stays unchanged - keeps original approver info
            ]);

            return back()->with('success', $user->name . ' voting access has been suspended by ' . auth()->user()->name);

        } catch (\Exception $e) {
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