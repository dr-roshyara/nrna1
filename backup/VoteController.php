<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Candidacy;
use App\Models\Post;
use App\Models\Result;
use App\Models\Code;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use App\Notifications\SecondVerificationCode;
use App\Notifications\SendVoteSavingCode;
//controllers 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class VoteController extends Controller 
{
    public $vote ; 
    public $has_voted;
    public $in_code ; 
    public $out_code;
    public $user_id;
    public $verify_final_vote;
    public $vote_id_for_voter;

    /***
     * 
     * construct 
     * Voting processs 
     * 1. Vote.Create 
     * 2. get post request to first_submission 
     * 3. vote.verify 
     * 4.
     * 
     */
    public function __construct(){
         $this->in_code  ='';
         $this->verify_final_vote=false;
        //  $this->user_id = auth()->user()->id;

     }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
         
        
        //  here ends 
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
        $auth_user      =auth()->user();
        $code           =$auth_user->code;
        // dd($code->is_code1_usable); 

        //$tfValue= is_url_only_after_first('code/create','/vote/create');
        // if(!$tfValue){
        //     /****
        //      * 
        //      * Go to dash board again 
        //      * 
        //      * 
        //      **/ 
        //     return redirect()->route('code/create');
        // }
   
        
         $can_vote_now   =$auth_user->can_vote_now;
         $code           =$auth_user->code;
         if($code){
            $has_voted      = $code->has_voted;
            //  dd($code->is_code1_usable); 
            //  $this->vote_pre_check($code);
             $return_to =$this->vote_pre_check($code);
           
             // dd($return_to);
            if($return_to!=""){
                if($return_to=='404'){
                    abort(404);
                } 
                return redirect()->route($return_to);
             }
             
         }else{
             return redirect()->route('dashboard');
         }
       
       
        // dd($code->is_code1_usable); 
        /***
         * Now check if the code 1 is usable or not 
         * 
         */
        
          
        
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('candidacy_id', 'LIKE', "%{$value}%");
                // $query->where('itemId', "{$value}");
                // ->orWhere('warehouseId', 'LIKE', "%{$value}%");
            });
        });
        
        // $candidacies = QueryBuilder::for(Candidacy::class)
        $national_posts = QueryBuilder::for(Post::with('candidates.user'))
        ->defaultSort('post_id')
        // ->allowedSorts(['name', 'is_national_wide', 'state_name', 'required_number'])
        ->where ('is_national_wide',1)
        ->paginate(250) 
        ->withQueryString();
        //regional posts
        $regional_posts = QueryBuilder::for(Post::with('candidates.user'))
                        ->defaultSort('post_id')
                        ->where ('is_national_wide',0)
                        ->where('state_name', trim(auth()->user()->region))
                        ->paginate(250) 
                        ->withQueryString();
          
        
        // $candidacies =Candidacy::where('post_id', "2021_36")->first();
        // $candidacies =Candidacy::all()->get(['post_id','candidacy_id','image_path_1']);
        $candidacies = QueryBuilder::for(Candidacy::Class)
        ->defaultSort('post_id')
        ->allowedSorts(['name', 'is_national_wide', 'state_name', 'required_number'])
        ->paginate(150) 
        ->withQueryString();
        
       
      
              
        $btemp          = $can_vote_now && !$has_voted;
        // $btemp          =$btemp & ($totalDuration<$voting_time);
       
        // dd($btemp);
         if(!$can_vote_now){
            echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                    You are not elegible to vote . Please first ask the administrators to keep you in the voter lists!
                    तपाइकाे नाम मतदाता  नामावलीमा समावेस गरिएको छैन। 
                    </div>';
             
            return (404);
        }
         if($has_voted){
                 echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                You have already voted! Please check your Vote </div>';
                abort(404);
               return (404);
             } 
            //  dd($candidacies);
     if($btemp){   
        return Inertia::render('Vote/CreateNew', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents, 
                "national_posts" =>$national_posts,
                "regional_posts" =>$regional_posts,                                
                'user_name'=>$auth_user->name,
                'user_id'=>$auth_user->id,
                'user_region'=>$auth_user->region,
                // 'user_lcc'=>$lcc 
                
            ]); 
        }else{
            return redirect()->route('vote.show');
        } 
        //    {name: "Hari Bahadur", photo: "test1.png",  post: ["President", "अद्यक्ष"], id:"hari", checked: false, disabled: false },
  
    }     

/**
 * Render the grouped voting form after all eligibility and access checks have passed.
 * The voting form is only accessible via redirect from verify_first_submission.
 * Direct URL access and multiple refreshes are blocked using a session flag.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
 */
public function cast_vote(Request $request)
{
    $auth_user = $request->user();
    $code      = $auth_user->code;

    // Session flag set by verify_first_submission to block direct access
    if (!session('vote_access_granted')) {
        return redirect()->route('dashboard')
            ->withErrors(['vote' => 'Direct access to the voting form is not allowed.']);
    }
    // Remove flag so user can't refresh and resubmit
    //session()->forget('vote_access_granted');

    // Extra eligibility checks (defense in depth)
    if (
        !$auth_user ||
        $auth_user->is_voter != 1 ||
        $auth_user->can_vote_now != 1 ||
        $auth_user->can_vote != 1 ||
        $auth_user->has_used_code1 != 1 ||
        $auth_user->has_used_code2 != 0 ||
        $auth_user->has_voted == 1
    ) {
        return redirect()->route('vote.show')
            ->withErrors(['vote' => 'You are not permitted to access the voting form.']);
    }

    // Fetch all posts with candidates and candidate user info
    $posts = \App\Models\Post::with(['candidacies.user'])
        ->orderBy('id')
        ->get();

    // Transform for frontend: one group per post/category
    $postsWithCandidates = $posts->map(function ($post) {
        return [
            'post_id'        => $post->post_id,
            'name'           => $post->name,
            'nepali_name'    => $post->nepali_name,
            'required_number'=> $post->required_number,
            'candidates'     => $post->candidacies->map(function ($c) {
                return [
                    'candidacy_id' => $c->candidacy_id,
                    'user'         => [
                        'id'   => $c->user->id,
                        'user_id'=> $c->user->user_id,                        
                        'name' => $c->user->name,
                        // ...other user fields if needed
                    ],
                    'post_id'      => $c->post_id,
                    'image_path_1' => $c->image_path_1,
                    // ...other candidacy fields if needed
                ];
            })->values(),
        ];
    });

    // Render the Inertia voting page
    return Inertia::render('Vote/CreateVotingPage', [
        'user' => [
            'id'   => $auth_user->id,
            'name' => $auth_user->name,
            // ... add more user fields as needed
        ],
        'code' => $code ? [
            'id'             => $code->id,
            'can_vote_now'   => $code->can_vote_now,
            'has_voted'      => $code->has_voted,
            'has_used_code1' => $code->has_used_code1,
            // ... add more as needed
        ] : null,
        'posts' => $postsWithCandidates, // Grouped posts with candidates for Vue
    ]);
}

/**
 * Handles the very first submission of the vote (after Code-1 check).
 */
public function first_submission(Request $request)
{
    $auth_user = auth()->user();

    // Get the code model and set as submitted
    $code = $auth_user->code;
    $code->vote_submitted    = 1;
    $code->vote_submitted_at = \Carbon\Carbon::now();
    $code->save(); // Save the state!

    // Pre-checks (time, code usability, etc.)
    $pre_check_route = $this->vote_pre_check($code);
    if ($pre_check_route && $pre_check_route != "") {
        if ($pre_check_route == '404') {
            abort(404);
        }
        return redirect()->route($pre_check_route);
    }

    // Run verify_first_submission; this can return a route name or a RedirectResponse
    $verify_result = $this->verify_first_submission($request, $code, $auth_user);

    if ($verify_result instanceof \Illuminate\Http\RedirectResponse) {
        return $verify_result; // Redirect back with errors
    }

    // At this point, $verify_result is a route string: 'vote.cast' or 'vote.show'
    return redirect()->route($verify_result);
}



    /***
     * 
     * This is the first submisson of vote 
     * 
     */
    Public function  first_submission_later(Request $request){
       
        //dd(request()->all());
     
        $auth_user          =auth()->user();
        $return_to           ='';
        
      
        /***
         * 
         * Get the voting Code here  and set as submitted
         * 
         * 
         */
        $code                       =$auth_user->code;
        $code->vote_submitted       =1;
        $code->vote_submitted_at     = Carbon::now();
    
        /****
         * 
         *Check the code  
         */
         $return_to =$this->vote_pre_check($code);
         
         /***
          * 
          *Check the Submit 
          */
         $this->verify_first_submission($request, $code , $auth_user) ;

         //dd($return_to);
        if($return_to!=""){
            if($return_to=='404'){
                abort(404);
            } 
            return redirect()->route($return_to);
        }

         
      
         /**
          *Here you come only if  the user votes for the first time 
          * 
           */

           $vote = request()->all();

           //check the validity of vote 

           //send the second verification code 
           $totalDuration =$this->send_second_voting_code($code, $auth_user);        

          
          //save the vote in Session
          $code_expires_in          =$code->voting_time_in_minutes;
          $vote["totalDuration"]    =$totalDuration;
          $vote["code_expires_in"]  =$code_expires_in;
          $request->session()->put('vote', $vote);

        //redirect to the verification 
        return redirect()->route('vote.verifiy')
                ->with([
                    'totalDuration'=>$totalDuration, 
                    'code_expires_in'=> $code_expires_in
                ]); 
        
     }

    ////////////////////////////////////////////////// 
    
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->in_code    ="4321";
       
        $this->out_code    = trim($request['voting_code']);
        //get vote from session 
        $input_data = $request->session()->get('vote');
             
        $auth_user         = auth()->user();
        $this->user_id     = $auth_user->id;
        $code              =$auth_user->code;
        // dd($code);
        $this->has_voted   =$code->has_voted;
        // $vote              =$auth_user->vote;
        $_error   =$this->vote_post_check($auth_user, $code,$input_data);
        // dd("test");
        if($_error["error_message"]!=""){
            echo $_error["error_message"];
            abort(404);
        }
        if($_error["return_to"]!=""){
           
            
           return redirect()->route($_error["return_to"]);
        
        }
        
        // dd("test");
        /***
         * if there is no code then return to dashboard 
         * 
         */
       

       
        if($code->is_code2_usable){
            $this->in_code  =$code->code2;
            
        }else{
            /*** 
             * 
             * if the code is not usable you can not proceed further
             * you should redirect the form in dashboard
             * 
             */
            echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
            Your code has Problem .Please Send the screenshot to administrator!             </div>';
            abort ('404');
            return redirect()->route('dashboard');
        }

        /**
        * 
        *Validate the voting code
        *
        */
        $validator        =$this->verify_vote_submit();
        $validator->validate($request);
     
        if($this->has_voted){
            // $auth_user->save();
            $request->session()->forget('vote');
            return redirect()->route('vote.verify_to_show'); 

        }
        if(Hash::check($this->out_code,$this->in_code) & !$this->has_voted)
        {
            /**
             *Here Everything is checked . you save the vote. 
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true 
             * He has not voted before 
             */
         
            //Step1 : save the vote and get the voting id
            $this->save_vote($input_data);            
            // $vote_id =$vote->id; 
            //step3 : Get a unkown random string and make private Key
              $random_key =get_random_string(6); 
              $private_key =$random_key."_".$this->vote_id_for_voter;
             /*******
              * Step4: Hash the voted id very securely
              ** After hashing, nobody can detect what is inside.
              **One can only compare this hashed key with the orginal private key.
              **The original private will not be saved in database and given directly
              **to the voter. If the voter loose his private key, nobody can detect 
              **the voter associated with his/her vote 
              **and find if the given text is correct or not.
             **/
              $hashed_voteId =  Hash::make( $private_key);
             //step4: Save the hashed key in database  
              $code->code_for_vote =$hashed_voteId;
             // step 5: Inform the voter the private in human readable format 
            //  After sending the key to voter, one can never know the hashed 
            // key without this value about his private key 
             $auth_user->notify(new SendVoteSavingCode($private_key));          
            //step 6: Save that the voter has voted and he/she cannot vote again.
            //Also save the date and time of voting .             
            $code->has_voted       =1;
            $code->can_vote_now    =0;
            $code->is_code2_usable =0;
            $code->code2_used_at   =Carbon::now();            
            $code->save();
            //Stp 7: Forward the voter to show the vote. Vote can be 
            //shown only if the voter give the correct private key.             
            return redirect()->route('vote.show'); 

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
           
       
        /***
         * 
         * Finally forget the vote session and redirect to show the vote 
         * 
         */
       
        // $auth_user->save();
        $request->session()->forget('vote');
         return redirect()->route('vote.show'); 

     
    }

     public function verify_to_show(){
        //
        //   $vote =$auth_user->vote();
        $auth_user            = auth()->user();
        $code                 = $auth_user->code;        
        $this->user_id        =$auth_user->id;
        $has_voted            =false;
        if($code!=null) {
            $has_voted            = $code->has_voted;
        }       
        
      //   dd($selected_candidates);
        
      return Inertia::render('Vote/VoteShowVerify', [
         
              'user_name'=>$auth_user->name,
              'has_voted'=>$has_voted,                             
       ]);    

     }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        $vote               =[];
        $has_voted          =false;
        $name               ='';
        $verify_final_vote  =false;
        $code               =auth()->user()->code;    
      
        // echo $code->has_voted ;
        // dd($code);
        if($code!=null){
                
            
            $final_vote = request()->session()->get('final_vote');
            // dd($final_vote);
            // dd(gettype($final_vote));
            if($final_vote==null){
                return redirect()->route('vote.verify_to_show');
            }
            if($final_vote){
                if(array_key_exists('name', $final_vote)){
                    $name = $final_vote["name"]; 
                }

                if(array_key_exists('selected_candidates', $final_vote)){
                    $vote = $final_vote["selected_candidates"]; 
                }

                if(array_key_exists('has_voted', $final_vote)){
                    $has_voted = $final_vote["has_voted"]; 
                }
                if(array_key_exists('verify_final_vote', $final_vote)){
                    $verify_final_vote = $final_vote["verify_final_vote"]; 
                }
            }
            request()->session()->forget('final_vote');
         }
        return Inertia::render('Vote/VoteShow',[
            'vote'=>  $vote, 
            'name'=> $name,
            'has_voted' => $has_voted,
            'verify_final_vote'=>$verify_final_vote
               
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

       $vote        = request()->session()->get('vote');
       $auth_user   =auth()->user();
       $code        =$auth_user->code;
        //    dd("test");
       $_error   =$this->vote_post_check($auth_user, $code,$vote);
        // dd("test");
        // dd($_error);
        if($_error["error_message"]!=""){
            echo $_error["error_message"];
            abort(404);
        }
        if($_error["return_to"]!=""){
           
            
           return redirect()->route($_error["return_to"]);
        
        }
        // dd("test");
        //check coding  
        $_message           =$this->second_code_check($code);       
        $code_expires_in    = $code->voting_time_in_minutes;
      
        if($_message["error_message"]!=""){
                echo $_message["error_message"];
                abort(404);
        }

        if( $_message["return_to"]!=""){
          
            return redirect()->route($_message["return_to"]);
        }
        // dd("testsa");
       return Inertia::render('Vote/Verify', [
                'vote'              =>$vote,
                 'name'             =>$auth_user->name,
                 'nrna_id'          =>$auth_user->nrna_id,
                 'state'            =>$auth_user->state,
                 'totalDuration'    =>$_message["totalDuration"],
                 'code_expires_in'  =>$code_expires_in,             
        ]);
                   
     
    } //end of verify


    public function verify_vote_submit()
    {
        $validator =  Validator::make(request()->all(), [
                    'voting_code' =>['required'],                    
                ]);
        //        
        //  $thvoting_code   =request('voting_code');   
        //  $code1         =$auth_user->code1;
        //  $has_voted      =$auth_user->has_voted ;
        //    //$has_voted      =false;
         // $auth_user->has_voted ;
         
        // $code1          ="1234";         
        // //hook to add additional rules by calling the ->after method
         $validator->after(function ($validator) {
              
                  $voting_code =request('voting_code');
                if (!Hash::check($this->out_code,$this->in_code )) {
                    //add custom error to the Validator
                    $validator->errors()->add('voting_code',
                    "You have submitted wrong Voting Code! <br> 
                    यहाँले दिनु भएको कोड सही प्रमाणित हुन सकेन। यसैले आफ्नो इमेल चेक गरेर  सहि कोड हालेर फेरी वटन थिच्नुहोस। ");
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
        // dd($input_data); 
        $no_vote_option     = $input_data["no_vote_option"];
        $vote               =new Vote;
        // $vote->user_id      = Hash::make($this->user_id); 
        $vote->voting_code  =$this->out_code;       
        $vote->save();   //save the vote first
        //save the $this->vote_id_for_voter  it to voter ;
        $this->vote_id_for_voter =$vote->id; 
        if($no_vote_option) { 
            //check if voter has given no_vote  option 
            // Go for no vote option 
            $vote->no_vote_option   =1;
      }else{
         /**
          * Here you save the all candidates finally :
          * 
          */ 
            $all_candidates =$input_data["natioanal_selected_candidates"];
            $all_candidates =array_merge($all_candidates, 
                            $input_data["regional_selected_candidates"]);
           
            for ($i=0; $i<sizeof($all_candidates); $i++)
            {
                $col_name = "candidate"; 
                $_vote_json=[];
                if($i<9){ 
                    $col_name .="_0".strval($i+1);
                }else{
                    $col_name .="_".strval($i+1);
                }


                if($all_candidates[$i] ==null){
                    $_vote_json["candidates"] = null; 
                    $_vote_json["no_vote"]  =true ;
                    
                }else{
                    $_vote_json             = $all_candidates[$i] ;
                    $_vote_json["no_vote"]  =false;
                    //Here save the vote result one by one in Result 
                    $post_id                = $_vote_json['post_id'];
                    $candidates             =$_vote_json["candidates"];
                    // dd($candidates);
                    for($j=0;$j<sizeof($candidates);$j++){
                      //save each selected candidates in the result
                      $result                = new Result; 
                      $result->vote_id       =$vote->id;
                      $result->post_id       =$post_id;
                      $result->candidacy_id  =$candidates[$j]['candidacy_id'];
                      $result->save();

                    }
                    
                 
                
                }
                //save the vote again  
              
                $vote->$col_name = json_encode($_vote_json); 
            
            }       
      
            // dd($input_data);
        } //end of else 
        
        $vote->save();
        
        //   dd($input_data);

            
    }
    //vote thanks 
    public function thankyou(){
           return Inertia::render('Thankyou/Thankyou', [
                 'vote' =>$vote,
                //  'name'=>auth()->user()->name,
                //  'nrna_id'=>auth()->user()->nrna_id,
                //  'state' =>auth()->user()->state              
        ]);
                   
    }

    // 
    public function verify_final_vote(Request $request){

        $this->out_code         = trim($request['voting_code']);
        // dd($this->out_code);
        $auth_user              = auth()->user();
        $this->user_id          = $auth_user->id;      
        $code                   =$auth_user->code;
        $selected_candidates    =[];
        $verify_final_vote  =false;
   
             
        $validator          =$this->verify_code_to_check_vote($code);
        $validator->validate($request);
     
             if (Hash::check($this->out_code, $this->in_code)) {
                $this->verify_final_vote =true;
                $str_pos =strpos($this->out_code,"_")+1;
                $voting_id =(int)substr($this->out_code, $str_pos);
                // dd($voting_id);
                $vote  =Vote::find($voting_id);
                $vote     =(array) json_decode($vote);
                $arr_keys =array_keys($vote);
                $key_string ="candidate";
                foreach($arr_keys as $kstring){
                    // echo $kstring .", ";
                    // echo stristr($kstring, $key_string). "\n <br>";
                    if(stristr($kstring, $key_string)!=false ){
                        if($vote[$kstring]){
                            array_push($selected_candidates, $vote[$kstring]);
                    
                        }
                    } 
                    }
                // $vote  =DB::table('votes')
                // -> where('id','=', $voting_id)
                // ->get();
                // // dd($vote);
           
                // $vote   =$vote;
            
            }
            $final_vote['selected_candidates']  =$selected_candidates;
            $final_vote['name']                  =$auth_user->name;
            $final_vote['has_voted']             =$this->has_voted;
            $final_vote['verify_final_vote'] =$this->verify_final_vote;
            // dd( $final_vote);
            $request->session()->put('final_vote', $final_vote);
            return redirect()->route('vote.show');
                // ->with([
                //     'vote'=>$selected_candidates, 
                //     'name'=>$auth_user->name,
                //     'has_voted' =>$this->has_voted,
                //     'verify_final_vote'=> $verify_final_vote
                // ]); 
       
     
                
                
        

    } //end of function : final_vote_verify
    
 
    //
    public function verify_code_to_check_vote($code){

        $validator =  Validator::make(request()->all(), [
            'voting_code' =>['required'],                    
        ]);
        if($code==null  ){
             
            $validator->errors()->add('code_to_check_vote',
            "Sorry, you have submitted wrong Voting Code! 
            यहाँले दिनु भएको कोड सही प्रमाणित हुन सकेन। यसैले आफ्नो इमेल चेक गरेर  सहि कोड हालेर फेरी वटन थिच्नुहोस। ");
             
        }
        if($code!=null){
            $this->has_voted   =$code->has_voted;
           $this->in_code      =$code->code_for_vote;
            
        }
        if(!$this->has_voted){
            $validator->errors()->add('code_to_check_vote',
            "Sorry, It seems like that you have not voted yet. Please do vote first. Then only you can see and check your vote. <br>
             यहाँले मतदानमा भागनै नलिएको जस्ताे देखियो। कृपया पहिले मतदान गर्नुहोस। ");
             
        }
      
         $validator->after(function ($validator) {
      
            $voting_code =request('voting_code');
            if (!Hash::check($this->out_code, $this->in_code)) {
                // The passwords match...
                //add custom error to the Validator
                $validator->errors()->add('code_to_check_vote',"Sorry, you have submitted wrong Voting Code! 
                यहाँले दिनु भएको कोड सही प्रमाणित हुन सकेन। यसैले आफ्नो इमेल चेक गरेर  सहि कोड हालेर फेरी वटन थिच्नुहोस। ");
            }
        });

       
        return $validator;
    }

    /****
     **
    * Code pre Checking 
    */    
    public function vote_pre_check(&$code){
                    
        $return_to       ="";
        $current         = Carbon::now();
        $code1_used_at   =$code->code1_used_at;
        $voting_time     =$code->voting_time_in_minutes;
        $totalDuration   = $current->diffInMinutes($code1_used_at );
       //dd($code);

       /***
        * if there is no code then return to dashboard 
        * 
       */
       if($code==null){
            /*** 
            * 
            * if the code is not usable you can not proceed further
             * you should redirect the form in dashboard
            * 
            */
          return   $return_to ="code.create";
            
       }
        //    dd($code->can_vote_now);  
       ($code->can_vote_now);
        if(!$code->can_vote_now){
            return     $return_to ="dashboard";
        }
        // dd("test1"); 
        if($code->has_voted){

            return     $return_to ="dashboard";
        }      
        //if code1 is not sent then return to code create
        if(!$code->has_code1_sent ){

            return   $return_to ="code.create";
        }
        //if code 1 is still usable and you havent used it, then use it first.
        //dd($code);
        if($code->is_code1_usable ){

            return   $return_to ="code.create";
        }
            /***
             * 
             * check when the first code was verified last time . 
             * If the time after first verification is longer thean the 
             * voting period then, we should return to code and send a new code 
             * s
             */
       
        if($totalDuration>$voting_time)
         {
            $code->can_vote_now     =0;
            $code->is_code1_usable  =0;
            $code->is_code2_usable  =0;
            $code->has_code1_sent   =0;
            $code->has_code2_sent   =0;
            $code->save();
            $return_to = "code.create";     
        }
        return  $return_to;    
    } //end of vote_pre_check
   
    
    /***
     * 
     * post check
     * Check after submitting the code 
     *  
     */
    public function vote_post_check($auth_user, &$code, $vote){
        // dd($code);
        $_error                  =[];
        $_error["return_to"]     ="";
        $_error["error_message"]  ="";
       
        //  dd($code);

        if($code==null){
            /*** 
             * 
            * if the code is not usable you can not proceed further
            * you should redirect the form in dashboard
            * 
            */
            $error_message = '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
            Either Your code is wrong or you have not voted properly. Send the screenshot to administrator!          
                <p> <a href="';
                $error_message .= route('dashboard') .'"> Click here to go to the main Dashboard </a></p>
            </div>';
            $_error['error_message']   =$error_message;
            //  dd($_error);
           return  $_error;

        }
        // dd($code);
        // check the ip address 
        $clientIP             =\Request::getClientIp(true);
        $max_use_clientIP     =config('app.max_use_clientIP');
        $_message= check_ip_address($clientIP,$max_use_clientIP);
        if($_message['error_message']!=""){
            echo $_message['error_message'];
            abort(404);
        }
        
        if(!$code->is_code2_usable){
            $code->is_code1_usable      =0;
            $code->has_code1_sent       =0;                
            $_error['return_to']       ='vote.create';
           //  dd($_error);
          return  $_error;
        }
        /***
         *
         * Check 2: if the user has already voted then
         *  then return back to the vote 
         * 
         *  */
        $_error_already_voted = '<div style="margin:auto; color:red; padding:20px; 
                        font-weight:bold; text-align:center;"> 
                    <p>You have already voted and your vote is already saved! See below</p>';
        $_error_already_voted .='<p style="text-align: center margin: 2px 0px 2px 0px; font-weight:bold;">';
        $_error_already_voted .= '<a href="'. route('vote.verify_to_show').'" > Click here to see your vote</a>';
        $_error_already_voted .= '</p></div>'; 
        // dd($code->has_voted);
        if($code->has_voted){
            $_error['error_message']   =$_error_already_voted;
             
            return  $_error;
           
            }
        /***
         * 
         * Check if the voter has already voted or in any case vote is already saved 
         *  
         */
        
        /***
         * 
         * check if the vote is there or not 
         * 
         */
        $_error_no_voted = '<div style="margin:auto; color:red; padding:20px; 
        font-weight:bold; text-align:center;"> 
        <p>We could not find your vote. Please contact the administrator. You can start also to vote again </p>';
        $_error_no_voted  .='<p style="text-align: center margin: 2px 0px 2px 0px; font-weight:bold;">';
        $_error_no_voted  .= '<a href="'. route('code.create').'" > Click here to vote</a>';
        $_error_no_voted  .= '</p></div>'; 
        if($vote ==null)
        {
            $_error['error_message'] =$_error_no_voted;
           //  dd($_error);
          return  $_error;
        } 
        return $_error ;
    } //end of vote_post_check
    
    public function second_code_check(&$code){
        $_message                  = [];
        $_message['error_message'] = "";
        $_message['return_to']     ="";
        $_message['totalDuration'] =0;
      
        $code_expires_in        = $code->voting_time_in_minutes;
        $current                = Carbon::now();
        $code1_used_at          = $code->code1_used_at;       
        $totalDuration          = $current->diffInMinutes($code1_used_at );
        $_message['totalDuration']=$totalDuration;
        if($totalDuration> $code_expires_in| $code->is_code1_usable){
            $code->is_code1_usable      =0;
            $code-> has_code2_sent      =0;
            $code->vote_submitted       =0;
            $code->save();
            $return_to                  = 'code.create';
            $_message["return_to"]      = $return_to ;
            $_message["totalDuration"]  = $totalDuration ;
            return $_message;
        }
        if(!$code->vote_submitted){
            $code->is_code1_usable      =0;
            $code->is_code2_usable      =0;
            $code-> has_code2_sent      =0;
            $code->save();
            $_message["return_to"]      ='vote.create';
            $_message["totalDuration"]  = $totalDuration ;
            return $_message;
        }
      return $_message;
    }

    /**
     * Validate the user's eligibility and status before allowing vote casting.
     * If any condition fails, redirects back with detailed errors.
     * If already voted, returns the route to the vote summary page.
     * If all checks pass, sets a session flag to grant one-time access to the voting form and returns the voting route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $code
     * @param  \App\Models\User  $auth_user
     * @return string|\Illuminate\Http\RedirectResponse
     */
    public function verify_first_submission(Request $request, &$code, $auth_user)
    {
        // Abort if not authenticated (defensive check)
        if (!$auth_user) {
            abort(403, 'Not authenticated.');
        }

        // Gather inputs from request
        $user_id      = $request->input('user_id');
        $agree_button = $request->input('agree_button');
        $errors       = [];

        // 1. User must be a registered voter
        if ($auth_user->is_voter != 1) {
            $errors['is_voter'] = 'You are not registered as a voter.';
        }

        // 2. Voting window must be open for this user
        if ($auth_user->can_vote_now != 1) {
            $errors['can_vote_now'] = 'Voting is not open for you at this time.';
        }

        // 3. User must be eligible to vote
        if ($auth_user->can_vote != 1) {
            $errors['can_vote'] = 'You are not eligible to vote.';
        }

        // 4. User must have used Code-1 to reach this step
        if ($auth_user->has_used_code1 != 1) {
            $errors['has_used_code1'] = 'You have not used your first voting code yet.';
        }

        // 5. User must NOT have used Code-2 (should be 0)
        if ($auth_user->has_used_code2 != 0) {
            $errors['has_used_code2'] = 'You have already confirmed your vote with Code-2.';
        }

        // 6. User must NOT have already voted
        if ($auth_user->has_voted == 1) {
            // Instead of redirecting back, return the 'vote.show' route for already-voted users
            return 'vote.show';
        }

        // 7. Ensure the submitted user ID matches the authenticated user
        if ((int)$user_id !== (int)$auth_user->id) {
            $errors['user_id'] = 'Login user does not match form user.';
        }

        // 8. User must agree before proceeding (checkbox)
        if (!$agree_button) {
            $errors['agree_button'] = 'You must agree before proceeding.';
        }

        // If there are any errors, redirect back to the form with all error messages and old input
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Grant one-time session access for the voting form
        session(['vote_access_granted' => true]);

        // Return the route name to render the voting form
        return 'vote.cast';
    }
    
    /***
      * 
      * @params1: Code Object 
    *   @returns: time in minutes Integer
      */
     public function send_second_voting_code(&$code, $auth_user){
        $code1_used_at  = Carbon::parse($code->code1_used_at);
        $current        = Carbon::now();
        $totalDuration  = $current->diffInMinutes($code1_used_at);
        if(!$code->is_code2_usable){
            $code->has_code2_sent=0;
        }else{
     

            if($totalDuration<$code->voting_time_in_minutes ){
                $code->has_code2_sent   =0;
                $code->is_code2_usable  =0;

            }

        }
       
       
        if(!$code->has_code2_sent)
        {
            $voting_code            = get_random_string (6);
            $code->code2            = Hash::make($voting_code);
            $totalDuration          =0;
            $auth_user->notify(new SecondVerificationCode($auth_user, $voting_code));
            
            $code->has_code2_sent   =1;
            $code->is_code1_usable  =0; 
            $code->is_code2_usable   =1;  
            $code->save();
        }

       
        return $totalDuration;
        
     }

}//end of the controller 
