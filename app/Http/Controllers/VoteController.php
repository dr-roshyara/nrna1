<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Candidacy;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
class VoteController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
         return Inertia::render('Vote/VoteIndex', [
            //    "presidents" => $presidents,
            //   "vicepresidents" => $vicepresidents,
                // 'name'=>auth()->user()->name 
              
         ]);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
         $candidacies = DB::table('candidacies')->get();

            //  $candidacies = Candidacy::query();
                // $presidents=DB::table('candidacies')->pluck('post_name');
                // ->pluck('title');
            // $presidents =$candidacies->where('post_name',"president");
        //    for ($i=0; $i<sizeof($presidents); $i++){
        //         dd($presidents[$i]);
        //         dd(gettype($candidacies[$i]));   
        //         //   $candidacies[$i]["checbox"] =>"dispabled";
        //    } 
         dd($candidacies);
         $query =Candidacy::query();
        $candidacies =$query->paginate(120);
         
            
        //  Inertia::render("Vote/IndexVote", [
        //     "candidacies" => $candidacies 
        // ]); 
     if(auth()->user()->can_vote_now){   
        return Inertia::render('Vote/CreateVote', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents,
                "candidacies" =>$candidacies,
                'name'=>auth()->user()->name 
                
            ]);
        }else{
            abort(404);
        } 
        //    {name: "Hari Bahadur", photo: "test1.png",  post: ["President", "अद्यक्ष"], id:"hari", checked: false, disabled: false },
  
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
          
         $request->validate([
            'icc_member' =>['required'],
             'president' =>['required']
            
         ]);
         dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
        return Inertia::render('Vote/VoteShow', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents,
            
                'name'=>auth()->user()->name 
              
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
