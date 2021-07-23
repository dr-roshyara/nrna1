<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Candidacy;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
//controllers 

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
        //  dd($candidacies);
         $query =Candidacy::query();
        $candidacies =$query->paginate(120);
         
            
        //  Inertia::render("Vote/IndexVote", [
        //     "candidacies" => $candidacies 
        // ]); 
        // dd(auth()->user());
        $btemp =auth()->user()->can_vote_now;
        $btemp =true;
     if($btemp){   
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
        if(auth()->user()->has_voted){
        
        // say error 
        /**
         * If user has already voted, then he can not vote agian so check
         */
         
            //$voting_code =$request['voting_code'];   
            //$code1 =auth()->user()->code1;        
        //hook to add additional rules by calling the ->after method
            $validator =  Validator::make(request()->all());
            $validator->after(function ($validator) {
                  $validator->errors()->add('Vote: ',"You have already Voted. Thank you !");              
            });
            //run validation which will redirect on failure
            $validator->validate($request);  
        }else{
         /**
          *Here you come only if  the user votes for the first time 
          * 
           */

           $input_data = $request->all();

          if($input_data['no_vote']){ //check if voter has given no_vote  option 
            // Go for no vote option 
            $vote =new Vote; 
            $vote->no_vote_option =1;
            $vote->user_id =auth()->user()->id;           

          }else{
            //here goes the saving of vote 
           //$candidacies =DB::table('candidacies')->get();
           //    $candi_names = $candidacies->pluck('post_name');
           //   dd($candi_names);          
           //save all  votes 
           //save icc candidates 
           $vote[' icc_member1_id']  =$input_data['icc_member1_id'];
           $vote['icc_member']       = $input_data['icc_member'];
           $vote['icc_member']        = $input_data['icc_member'];
           

        }
        //dd($request->all());
        //save all votes 
        //check if vote already exists for this user         
        $vote->save();
        $user = Auth::user();
        $user->has_voted=1;
        $user->save();

        // auth()->user()->save();
        
        return Inertia::render('Vote/VoteShow', [
                 'vote' =>$vote,
                 'name'=>auth()->user()->name,
                 'nrna_id'=>auth()->user()->nrna_id,
                 'state' =>auth()->user()->state              
             ]);
                   

        } //end of if user votes for the first time  
        


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
