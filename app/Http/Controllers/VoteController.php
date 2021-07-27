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
use Illuminate\Routing\Redirector;
//controllers 

class VoteController extends Controller 
{
    public $vote ; 
    public $has_voted;
    public $in_code ; 
    public $out_code;
    public $user_id;
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
        $can_vote_now   =auth()->user()->can_vote_now;
        $has_voted      = auth()->user()->has_voted;  
        $has_voted      =false;      
        $btemp          = $can_vote_now & !$has_voted;
        $lcc             =auth()->user()->lcc;
        $lcc             ="Berlin";
        
     if($btemp){   
        return Inertia::render('Vote/CreateVote', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents,
                "candidacies" =>$candidacies,
                'user_name'=>auth()->user()->name,
                'user_id'=>auth()->user()->id,
                'user_lcc'=>$lcc
                
            ]); 
        }else{
             if(!$can_vote_now){
                echo "Your code can not be verified";
                abort(404);

             }
             if($has_voted){
                 echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                You have already voted! Please check your Vote </div>';
                abort(404);
             }
        } 
        //    {name: "Hari Bahadur", photo: "test1.png",  post: ["President", "अद्यक्ष"], id:"hari", checked: false, disabled: false },
  
    }     
 
    Public function  first_submission (Request $request){
         //
        $validator =  Validator::make(request()->all(), [
                    'user_id' =>['required'],                    
                ]);
        $user_id            =request('user_id');
        $has_voted          =auth()->user()->has_voted;
        $has_voted          =false;
        $nothing_selected   =request('nothing_selected'); 
        $agree_button        =request('agree_button');
        
        // dd($nothing_selected);
        //first check if at least one check box selected 
        $btemp = $this->at_least_one_vote_casted();

        if(!$agree_button){
            $validator->after(function ($validator) {
                  $validator->errors()->add('Without_Agreement: ', " You must agree the voting terms and conditions.!");              
            });
        }    
        if(!$btemp){
            $validator->after(function ($validator) {
                  $validator->errors()->add('Nothing_Slected: ',"You must either vote at least one canidate or use your right to reject all candidates!");              
            });
        }    
                
        if($user_id !=auth()->user()->id)
        {
                             
            $validator->after(function ($validator) {
                  $validator->errors()->add('Longin_User: ',"Login usser is different than you!");              
            });
        } 
        //      
        if($has_voted)
        {
        
                  
            $validator->after(function ($validator) {
                  $validator->errors()->add('Vote: ',
                  'You have already Voted. Thank you! Please check your Vote!');              
            });
            //run validation which will redirect on failure
        }
        $validator->validate($request);  
        // dd($request);

         /**
          *Here you come only if  the user votes for the first time 
          * 
           */

           $vote = $request->all();
        
        //    $request->session()->put('vote', $vote);
           $candi_vec =[];
           array_push($candi_vec,  $this->get_candidate('icc_member'));
           array_push($candi_vec,  $this->get_candidate('president'));
           array_push($candi_vec, $this->get_candidate('vice_president'));
           array_push($candi_vec, $this->get_candidate('wvp'));
           array_push($candi_vec, $this->get_candidate('general_secretary'));
           array_push($candi_vec, $this->get_candidate('secretary'));
           array_push($candi_vec, $this->get_candidate('treasure'));
           array_push($candi_vec, $this->get_candidate('w_coordinator'));
           array_push($candi_vec, $this->get_candidate('y_coordinator'));
           array_push($candi_vec, $this->get_candidate('cult_coordinator'));
           array_push($candi_vec, $this->get_candidate('child_coordinator'));
           array_push($candi_vec, $this->get_candidate('studt_coordinator'));
             array_push($candi_vec, $this->get_candidate('member_berlin'));
           array_push($candi_vec, $this->get_candidate('member_hamburg'));
           array_push($candi_vec, $this->get_candidate('member_nsachsen'));
           array_push($candi_vec, $this->get_candidate('member_nrw'));
           array_push($candi_vec, $this->get_candidate('member_hessen'));
           array_push($candi_vec,  $this->get_candidate('member_rhein_pfalz'));
            array_push($candi_vec,  $this->get_candidate('member_bayern'));
            array_push($candi_vec, request('no_vote_option'));
           $request->session()->put('candi_vec', $candi_vec);
           $this->vote =$candi_vec;
        //    dd($candi_vec);
           return redirect('/vote/verify')->with('vote', $candi_vec);

     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->has_voted =auth()->user()->has_voted;
        $this->in_code   =auth()->user()->code2;
        $this->in_code    ="4321";
        $this->out_code   = $request['voting_code'];
        $this->user_id    =auth()->user()->id;
        $validator        =$this->verify_vote_submit();
        $validator->validate($request);
        //
        if($this->in_code==$this->out_code & !$this->has_voted)
        {
            /**
             *Here Everything is checked . you save the vote. 
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true 
             * He has not voted before 
             */
            //get vote from session 
            $input_data = $request->session()->get('candi_vec');
        //    dd($input_data);
            //no_vote option is saved in 19 
             $no_vote_option  =$input_data[19];   
            if($no_vote_option) { //check if voter has given no_vote  option 
                // Go for no vote option 
                $vote                   =new Vote; 
                $vote->no_vote_option   =1;
                $vote->user_id          =$this->user_id;  
                $vote->save();        

          }else{
             /**
              * Here you save the vote finally :
              * G
              */ 
                $vote                = new Vote;
                $vote->user_id       = $this->user_id;
                $this->save_vote($input_data);
               
            }    
            //save the vote and save the user has voted
          

        }else{
            if($this->has_voted){
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                You have already voted! Please check your Vote </div>';
                abort(404);

             }else{
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                Your code can not be verified </div>';
                abort(404);
             }
        } 
           
       
   
       
        // auth()->user()->save();
        $request->session()->forget('vote');
         return redirect()->route('vote.show'); 

        // //   return redirect('/vote/verify')->with('vote', $candi_vec
        // return Inertia::render('Vote/VoteShow', [
        //          'vote' =>$vote,
        //          'name'=>auth()->user()->name,
        //          'nrna_id'=>auth()->user()->nrna_id,
        //          'state' =>auth()->user()->state              
        //      ]);
                   

       
        
        

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
        //   $vote =auth()->user()->vote();
          $this->user_id =auth()->user()->id;
          $vote  =User::find($this->user_id)->vote;
          
        return Inertia::render('Vote/VoteShow', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents,
                'vote' =>$vote,
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
    public function at_least_one_vote_casted(){
   
        $btemp = false; 
        $btemp =request('no_vote_option');
        $btemp =$btemp | sizeof(request('icc_member'))>0;
        $btemp =$btemp | sizeof(request('president'))>0;
         $btemp =$btemp | sizeof(request('vice_president'))>0;
        $btemp =$btemp | sizeof(request('wvp'))>0;
        $btemp =$btemp | sizeof(request('general_secretary'))>0;
        $btemp =$btemp | sizeof(request('secretary'))>0;
        $btemp =$btemp | sizeof(request('treasure'))>0;
        $btemp =$btemp | sizeof(request('w_coordinator'))>0;
        $btemp =$btemp | sizeof(request('y_coordinator'))>0;
        $btemp =$btemp | sizeof(request('cult_coordinator'))>0;
        $btemp =$btemp | sizeof(request('child_coordinator'))>0;
        $btemp =$btemp | sizeof(request('studt_coordinator'))>0;
        $btemp =$btemp | sizeof(request('member_berlin'))>0;
        $btemp =$btemp | sizeof(request('member_hamburg'))>0;
        $btemp =$btemp | sizeof(request('member_nsachsen'))>0;
        $btemp =$btemp | sizeof(request('member_nrw'))>0;
        $btemp =$btemp | sizeof(request('member_hessen'))>0;
        $btemp =$btemp | sizeof(request('member_rhein_pfalz'))>0;
        $btemp =$btemp | sizeof(request('member_bayern'))>0;


        return $btemp;
    }
    public function get_candidate($key){
        $_candivec =[];
         $submit_vec =request($key);
        if(sizeof($submit_vec)>0)
        {
                
            
                for($i=0; $i<sizeof($submit_vec); ++$i){
                    //    var_dump($submit_vec[$i] );
                    $_candi                      = DB::table('candidacies')->where([
                    ['candidacy_id', '=',  $submit_vec[$i] ], 
                    // ['post_id',        '=',  $_postid]
                    ])->get()->first();

                            // dd($_candi); 
                       $myvec = array(
                                'post_name' =>$_candi->post_name,
                                'candidacy_id'     =>$_candi->user_id,
                                  'candidacy_name'  => $_candi->candidacy_name
                        );
                        
                    array_push($_candivec,   $myvec);         
                    
                }
    
            }
            
       return $_candivec; 
    }
    public function verify(){
       $vote = request()->session()->pull('vote');
        // dd($vote);
        return Inertia::render('Vote/VoteVerify', [
                 'vote' =>$vote,
                 'name'=>auth()->user()->name,
                 'nrna_id'=>auth()->user()->nrna_id,
                 'state' =>auth()->user()->state              
        ]);
                   
     
    }
    public function verify_vote_submit()
    {
        $validator =  Validator::make(request()->all(), [
                    'voting_code' =>['required'],                    
                ]);
        //        
        //  $thvoting_code   =request('voting_code');   
        //  $code1         =auth()->user()->code1;
        //  $has_voted      =auth()->user()->has_voted ;
        //    //$has_voted      =false;
         // auth()->user()->has_voted ;
         
        // $code1          ="1234";         
        // //hook to add additional rules by calling the ->after method
         $validator->after(function ($validator) {
                /**
                 * Here we chan change the code condition 
                 * mention where the code is saved 
                 * call the code 
                 * compare the code 
                 * If code is not equal ,then reject   
                 *  */  
                //   $code1 =auth()->user()->code1; 
                  // just for test 
                 // $code1_1 ="1234"; 
                  //$has_voted= auth()->user()->has_voted ;
                    // $code1_1 =$code1;
                  $voting_code =request('voting_code');
                if ($this->in_code!= $this->out_code ) {
                    //add custom error to the Validator
                    $validator->errors()->add('voting_code',"You have submitted wrong Voting Code!");
                }
                if ($this->has_voted ) {
                    //add custom error to the Validator
                    $validator->errors()->add('Your_Vote',"You have already voted! Please check your vote");
                }
                

            });

        //run validation which will redirect on failure
        // $validator->validate($request);
        return $validator;
    }
    //save all candidates 
    public function save_vote($input_data){
                
                $vote                =new Vote;
                $vote->user_id       =$this->user_id;
                // dd($input_data);
                //icc member 
                $icc_member                =$input_data[0];
                if(sizeof($icc_member)>0){
                    //   dd($icc_member[0]["candidacy_name"]);
                    $vote['icc_member1_name']  =$icc_member[0]["candidacy_name"];
                    $vote['icc_member1_id']    =$icc_member[0]["candidacy_id"];

                }
 
                 // president 
                $president                    =$input_data[1];
                if(sizeof($president)>0){
                    $vote['president_name']  =$president[0]["candidacy_name"];
                    $vote['president_id']    =$president[0]["candidacy_id"];

                }
                
                 //Vice President  
                $post                              =$input_data[2];
                if(sizeof($president)>0){
                    $vote['vice_president1_name']  =$post[0]["candidacy_name"];
                    $vote['vice_president1_id']    =$post[0]["candidacy_id"];

                }
                //Vice President  
                if(sizeof($post)>1){
                    $vote['vice_president2_name']  =$post[1]["candidacy_name"];
                    $vote['vice_president2_id']    =$post[1]["candidacy_id"];

                }
              
                  $post                             =$input_data[3];
                if(sizeof($post)>0){
                    $vote['woman_vice_president_id']  =$post[0]["candidacy_id"];
                    $vote['woman_vice_president_name']    =$post[0]["candidacy_name"];

                }
                //general secretary  4th data 
                $post                             =$input_data[4];
                if(sizeof($post)>0){
                    $vote['general_secretary_id']  =$post[0]["candidacy_id"];
                    $vote['general_secretary_name']    =$post[0]["candidacy_name"];

                }
                //Secretary 
                $post                              =$input_data[5];
                if(sizeof($post)>0){
                    $vote['secretary1_id']          =$post[0]["candidacy_id"];
                    $vote['secretary1_name']        =$post[0]["candidacy_name"];

                }
                   //Secretary 
                   if(sizeof($post)>1){
                    $vote['secretary2_id']   =$post[1]["candidacy_id"];
                    $vote['secretary2_name']   =$post[1]["candidacy_name"];

                }
                //Treasure 
                  $post                              =$input_data[6];
                 if(sizeof($post)>0){
                    $vote['treasure_id']   =$post[0]["candidacy_id"];
                    $vote['treasure_name']   =$post[0]["candidacy_name"];

                }
                //woman_coordinator_id 
                  $post                              =$input_data[7];
                 if(sizeof($post)>0){
                    $vote['woman_coordinator_id']   =$post[0]["candidacy_id"];
                    $vote['woman_coordinator_name']   =$post[0]["candidacy_name"];

                }
             //youth_coordinator_id 
                  $post                              =$input_data[8];
                 if(sizeof($post)>0){
                    $vote['youth_coordinator_id']   =$post[0]["candidacy_id"];
                    $vote['youth_coordinator_name']   =$post[0]["candidacy_name"];

                }
                //culture_coordinator_id 
                  $post                              =$input_data[9];
                 if(sizeof($post)>0){
                    $vote['culture_coordinator_id']   =$post[0]["candidacy_id"];
                    $vote['culture_coordinator_name']   =$post[0]["candidacy_name"];

                }
                 //children_coordinator_id 
                  $post                                 =$input_data[10];
                 if(sizeof($post)>0){
                    $vote['children_coordinator_id']    =$post[0]["candidacy_id"];
                    $vote['children_coordinator_name']   =$post[0]["candidacy_name"];

                }
                 //student_coordinator_id 
                  $post                              =$input_data[11];
                 if(sizeof($post)>0){
                    $vote['student_coordinator_id']   =$post[0]["candidacy_id"];
                    $vote['student_coordinator_name']   =$post[0]["candidacy_name"];

                }
                //member_berlin1_id 
                  $post                              =$input_data[12];
                 if(sizeof($post)>0){
                    $vote['member_berlin1_id']   =$post[0]["candidacy_id"];
                    $vote['member_berlin1_name']   =$post[0]["candidacy_name"];

                }
 
                 if(sizeof($post)>1){
                    $vote['member_berlin2_id']   =$post[1]["candidacy_id"];
                    $vote['member_berlin2_name']   =$post[1]["candidacy_name"];

                }
                //member_hamburg1_id 
                  $post                              =$input_data[13];
                 if(sizeof($post)>0){
                    $vote['member_hamburg1_id']   =$post[0]["candidacy_id"];
                    $vote['member_hamburg1_id']   =$post[0]["candidacy_name"];

                }
 
                  if(sizeof($post)>1){
                    $vote['member_hamburg2_id']   =$post[1]["candidacy_id"];
                    $vote['member_hamburg2_id']   =$post[1]["candidacy_name"];

                } 
                //  'member_niedersachsen1_id
                
                  $post                              =$input_data[14];
                 if(sizeof($post)>0){
                    $vote['member_niedersachsen1_id']   =$post[0]["candidacy_id"];
                    $vote['member_niedersachsen1_name']   =$post[0]["candidacy_name"];

                }
 
                   if(sizeof($post)>1){
                    $vote['member_niedersachsen2_id']   =$post[1]["candidacy_id"];
                    $vote['member_niedersachsen2_name']   =$post[1]["candidacy_name"];

                }
 
                //member_nrw1_id
                  $post                              =$input_data[15];
                 if(sizeof($post)>0){
                    $vote['member_nrw1_id']   =$post[0]["candidacy_id"];
                    $vote['member_nrw1_name']   =$post[0]["candidacy_name"];

                }
 
                  if(sizeof($post)>1){
                    $vote['member_nrw2_id']   =$post[1]["candidacy_id"];
                    $vote['member_nrw2_name']   =$post[1]["candidacy_name"];

                }
                //member_hessen1_id 
                  $post                              =$input_data[16];
                 if(sizeof($post)>0){
                    $vote['member_hessen1_id']   =$post[0]["candidacy_id"];
                    $vote['member_hessen1_name']   =$post[0]["candidacy_name"];

                }
 
                if(sizeof($post)>1){
                    $vote['member_hessen2_id']   =$post[1]["candidacy_id"];
                    $vote['member_hessen2_name']   =$post[1]["candidacy_name"];

                }
                //member_rheinland_pfalz1_id 
                  $post                              =$input_data[17];
                 if(sizeof($post)>0){
                    $vote['member_rheinland_pfalz1_id']   =$post[0]["candidacy_id"];
                    $vote['member_rheinland_pfalz1_name']   =$post[0]["candidacy_name"];

                }
 
                   if(sizeof($post)>1){
                    $vote['member_rheinland_pfalz2_id']   =$post[1]["candidacy_id"];
                    $vote['member_rheinland_pfalz2_name']   =$post[1]["candidacy_name"];

                }
                //member_bayern1_id 
                  $post                              =$input_data[18];
              
                 if(sizeof($post)>0){
                    $vote['member_bayern1_id']   =$post[0]["candidacy_id"];
                    $vote['member_bayern1_name']   =$post[0]["candidacy_name"];

                }
                 if(sizeof($post)>1){
                    $vote['member_bayern2_id']   =$post[1]["candidacy_id"];
                    $vote['member_bayern2_name']   =$post[1]["candidacy_name"];

                }
                

 
              //  
            $vote->save();
            $user =Auth::user();
            $user->has_voted=1;
            $user->save();
    }

}//end of the controller 
/**********************************************************************+************************************ */

 // $icc_member          =  $this->get_candidate('icc_member');
                // $president           =  $this->get_candidate('president');
                // $vice_president      =  $this->get_candidate('vice_president');
                // $w_vice_president    =  $this->get_candidate('wvp');
                // $general_secretary   =  $this->get_candidate('general_secretary');
                // $secretary           =  $this->get_candidate('secretary');
                // $treasure           = $this->get_candidate('treasure');
                // $w_coordinator      = $this->get_candidate('w_coordinator');
                // $y_coordinator      = $this->get_candidate('y_coordinator');
                // $cult_coordinator   = $this->get_candidate('cult_coordinator');
                // $child_coordinator  = $this->get_candidate('child_coordinator');
                // $studt_coordinator  = $this->get_candidate('studt_coordinator');
                // $member_berlin      = $this->get_candidate('member_berlin');
                // $member_hamburg     = $this->get_candidate('member_hamburg');
                // $member_nsachsen    = $this->get_candidate('member_nsachsen');
                // $member_nrw         = $this->get_candidate('member_nrw');
                // $member_hessen      = $this->get_candidate('member_hessen');
                // $member_rhein_pfalz = $this->get_candidate('member_rhein_pfalz');
                // $member_bayern      = $this->get_candidate('member_bayern');
                // //icc member 
                // $vote['icc_member1_name']  =$icc_member->candidacy_name;
                // $vote['icc_member1_id']     =$icc_member->user_id;
 
                // //president 
                //  $vote['president_name']  =$president->candidacy_name;
                //  $vote['president_id']     =$president->user_id;

              //  
          