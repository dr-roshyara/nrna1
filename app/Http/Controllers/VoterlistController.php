<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB; 

class VoterlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   //starts here 
   public function index(Request $request)
   {
            request()->validate([
                'direction'=> ['in:asc,desc'],
                'field' => ['in:id,first_name,last_name,nrna_id,state,telephone,created_at']
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
            if(request('first_name')){
                $query->where('first_name', 'LIKE', '%'.request('first_name').'%');
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

    // $users =Message::when( $request->term, 
    //     function($query, $term){
    //     $query->where('to', 'LIKE', '%'.$term.'%' );
    // })->paginate(20); 
    
       //the following lines are for the first type of search 

       // $users =Message::when( $request->term, 
       //     function($query, $term){
       //     $query->where('to', 'LIKE', '%'.$term.'%' );
       // })->paginate(20); 
        /**
         * Select only those users which are eligible for voting purpose 
         */
        //  $users =$query->paginate(50);
        // $users = DB::table('users')->where('is_voter', 1);
        $users =$query;
         $users =$users->paginate(50);

        // $users =$query->where('is_voter', 1);
        // dd($users);

       // $users =$users->sortBy('created_at')->reverse();
       return Inertia::render('Voter/IndexVoter', [
         'users' => $users,
         'filters' =>request()->all(['search', 'first_name','nrna_id','field','direction'])  

       ]);
   

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
