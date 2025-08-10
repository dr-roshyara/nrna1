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
        
         // dd(DB::table('users')->first());
         $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('id', 'LIKE', "%{$value}%");
                // $query->where('itemId', "{$value}");

                   
                // ->orWhere('variationId', 'LIKE', "%{$value}%");
                
            });
        });
        /***
         * 
         * allow sorting 
         * 
         */
    
        $query = User::where('is_voter', 1);
        $users = QueryBuilder::for($query)
        ->defaultSort('name')
        ->allowedSorts(['name','nrna_id', 'voting_ip', 'approvedBy'])
        ->allowedFilters(['name','nrna_id', 'voting_ip', 'approvedBy', $globalSearch])
        ->paginate(2000) 
        ->withQueryString();
        // chain on any of Laravel's query builder methods
        
        // dd($users);
         /***
          * 
          *return 
          */
        return Inertia::render('Voter/IndexVoter', [
            'voters'=>$users,
            'isCommitteeMember' => auth()->user()->is_committee_member ?? false,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([                
                'name'              => 'Name',
                'user_id'           => 'User ID',
                'voting_ip'         => 'Voting IP',
                'approvedBy'        => 'Approved By',
                // 'item.keywords'          => 'Keywords',
                // 'variationId'            => 'Variation Id',
                // 'itemId'   =>  'Item Id',
            ])->addFilter('mainWarehouseId', 'Warehouse', [
                '25' => 'Keramag',
                 
                // 'manufacturer.name' =>'Manufacturer',
                // 'nl' => 'Nederlands',
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
        
        
        
        
        request()->validate([
            'direction'=> ['in:asc,desc'],
            'field' => ['in:id,name,last_name,nrna_id,state,telephone,created_at']
        ]);
        // $query =User::query();
         $query = DB::table('users')->where('is_voter', 1);
        // if(request('direction')){
        //     $query->orderBy('id',request('direction'));

        // }else{
        //     $query->orderBy('id','desc');

        // }
        
        if(request('search')){
            $query->where('last_name', 'LIKE', '%'.request('search').'%');
        } 
        if(request('name')){
            $query->where('name', 'LIKE', '%'.request('name').'%');
        } 
        //
         if(request('nrna_id')){
            $query->where('nrna_id', 'LIKE', '%'.request('nrna_id').'%');
        } 
        //
        if(request()->has(['field', 'direction'])){
            $query->orderBy(request('field'), request('direction')); 
        }else{
            $query->orderBy('id','desc'); 
        }
        //the following lines are for the first type of search 

        // $voters =Message::when( $request->term, 
        //     function($query, $term){
        //     $query->where('to', 'LIKE', '%'.$term.'%' );
        // })->paginate(20); 
        $btemp      =auth()->user()->hasAnyPermission('send code');
        // dd($btemp);
         $voters     =$query->paginate(50);
        // $voters =$voters->sortBy('created_at')->reverse();
        return Inertia::render('Voter/IndexVoter', [
          'voters' => $voters,
          'can_send_code'=>$btemp, 
          'isCommitteeMember' => auth()->user()->is_committee_member ?? false,
          'filters' =>request()->all(['name','nrna_id','field','direction'])  
 
        ]);
    

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