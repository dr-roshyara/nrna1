<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Candidacy;
use App\Models\Post;
use App\Models\Code;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use App\Notifications\SecondVerificationCode;

//controllers 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    /***
     * 
     * construct 
     * 
     */
    public function __construct(){
         $this->in_code  ='';
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
         
         $tfValue= is_url_only_after_first('code/create','/vote/create');
        if(!$tfValue){
            /****
             * 
             * Go to dash board again 
             * 
             * 
             **/ 
            return redirect()->route('dashboard');
        }
        //  dd($tfValue);
         $auth_user      =auth()->user();
         $code           =$auth_user->code;
         $can_vote_now   =$auth_user->can_vote_now;
         $code           =$auth_user->code;
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
             return redirect()->route('dashboard');
         }
         
         $has_voted      = $code->has_voted;  
            // dd($code->is_code1_usable); 
         if(!$code->is_code1_usable ){

           return  redirect()->route('code.create'); 
        }
        // dd($code->is_code1_usable); 
        if($code->is_code1_usable){
            $code->is_code1_usable =0;
            $code->save();

         }  
          
        
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
                        // ->where('state_name', auth()->user()->region)
                        ->paginate(250) 
                        ->withQueryString();
         
        
        // $candidacies =Candidacy::where('post_id', "2021_36")->first();
        // $candidacies =Candidacy::all()->get(['post_id','candidacy_id','image_path_1']);
        $candidacies = QueryBuilder::for(Candidacy::Class)
        ->defaultSort('post_id')
        ->allowedSorts(['name', 'is_national_wide', 'state_name', 'required_number'])
        ->paginate(150) 
        ->withQueryString();
        
        // /**
        //  * 
        //  * Load User 
        //  */
        // $candidacies->load(['user' => function ($query) {
        //     $query->select(['id','name', 'region', 'user_id', 'nrna_id']);
        //     // $query->withTraced()->select('name');
        //     //$query->orderBy('published_date', 'asc');
        //     // return($query->get('name'));
        // //  $qs =$query->select('name');
        // //  dd($query);
        // // return $query->pluck('name');
        // // return();
        // }]);
        /**
         * 
         * Load Post 
         */
        // $candidacies->load(['post' => function ($query) {
        //     $query->select(['id','post_id','name','required_number', 'is_national_wide']);
        //     // $query->withTraced()->select('name');
        //     // return  $query->where('is_national_wide', 1);
        //     // return($query->get('name'));
        //     //  $qs =$query->select('name');
        //     //  dd($query);
        //     // return $query->pluck('name');
        //     // return();
        // }]);
        // dd($candidacies);
        //  Inertia::render("Vote/IndexVote", [
        //     "candidacies" => $candidacies 
        // ]); 
        // dd(auth()->user());
      
              
        $btemp          = $can_vote_now && !$has_voted;
        // $lcc             =auth()->user()->lcc;
        // $lcc             ="Berlin";
        // dd($btemp);
         if(!$can_vote_now){
            echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                    You are not elegible to vote . Please first ask the administrators to keep you in the voter lists!
                    तपाइकाे नाम मतदाता  नामावलीमा समावेस गरिएको छैन। 
                    </div>';
                abort(404);
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


    /***
     * 
     * This is the first submisson of vote 
     * 
     */
    Public function  first_submission (Request $request){
         //
        $validator =  Validator::make(request()->all(), [
                    'user_id' =>['required'],                    
                ]);
        // dd(request()->all());
        $user_id            =request('user_id');
        $auth_user          =auth()->user();
        $code_expires_in    =25;
        /***
         * 
         * Get the voting Code here  
         * 
         */
        $code =$auth_user->code;
        if($code==null){
            // if code is not given then redirect to dashboard   
            return redirect()->route('dashboard'); 
        } 
        
        $has_voted          =$code->has_voted;
        $has_voted          =false;
        $nothing_selected   =request('nothing_selected'); 
        $agree_button       =request('agree_button');
        
        // dd($nothing_selected);
        //first check if at least one check box selected 
        // $btemp = $this->at_least_one_vote_casted();
        $btemp=true;

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
                
        if($user_id !=$auth_user->id)
        {
                             
            $validator->after(function ($validator) {
                  $validator->errors()->add('Longin_User: ',"Login usser is different than you!");              
            });
        } 
        //      
        if($has_voted)
        {
        
            return redirect()->route('vote.show');       
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

           $vote = request()->all();
        
     
       
       
        /***
        *
        *Create the second code 
        * 
        */
        /**
         * 
         * 
         */

        $updated_at    = Carbon::parse($code->updated_at);
        $current       = Carbon::now();
        $totalDuration = $current->diffInMinutes($updated_at);
       
        if(!$code->is_code2_usable || ($code->is_code2_usable & $totalDuration>15 ))
        {
            $voting_code            = get_random_string (6);
            $code->code2            =$voting_code;
            $code->is_code2_usable  =1;      
            $totalDuration          =0;
            $code->save();
        }
        
        $vote["totalDuration"]   =$totalDuration;
        $vote["code_expires_in"] =$code_expires_in;
        
        /***
         * 
         * save the vote in  Session 
         *  
         */
         $request->session()->put('vote', $vote);
        
       
            

        /***
         * 
         * send email to the user 
         * 
         */
        //   $auth_user->notify(new Second)   
          $auth_user->notify(new SecondVerificationCode($auth_user));
        /***
         * 
         * redirect the web to vote verify 
         * 
         **/  
        return redirect()->route('vote.verfiy')
                ->with([
                    'totalDuration'=>$totalDuration, 
                    'code_expires_in'=> $code_expires_in
                ]); 
        
     }

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
        $auth_user         = auth()->user();
        $this->user_id     =$auth_user->id;
        $code              =$auth_user->code;
        
        /***
         * 
         * Check if the voter has already voted or in any case vote is already saved 
         *  
         */
        $vote =$auth_user->vote;
        if($vote !=null){
            return redirect()->route('dashboard');
        } 
        
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
            return redirect()->route('dashboard');
        }

        $this->has_voted   =$code->has_voted;
        if($code->is_code2_usable){
            $this->in_code  =$code->code2;
            
        }else{
            /*** 
             * 
             * if the code is not usable you can not proceed further
             * you should redirect the form in dashboard
             * 
             */
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
            return redirect()->route('vote.show'); 

        }
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
            $input_data = $request->session()->get('vote');
            $no_vote_option = $input_data["no_vote_option"];
            // dd($input_data);
            //no_vote option is saved in 19 
            //  $no_vote_option  =$input_data[19];   
            if($no_vote_option) { //check if voter has given no_vote  option 
                // Go for no vote option 
                $vote                   =new Vote; 
                $vote->no_vote_option   =1;
                $vote->user_id          =$this->user_id;        

          }else{
             /**
              * Here you save the vote finally :
              * G
              */ 
                // $vote                = new Vote;
                // $vote->user_id       = $this->user_id;
                $vote                =new Vote;
                $vote->user_id       =$this->user_id;
                $vote->post_id       =$this->user_id;
                $vote->voting_code   =$this->in_code;
                // dd($input_data);
                $all_candidates =$input_data["natioanal_selected_candidates"];
                $all_candidates =array_merge($all_candidates, $input_data["regional_selected_candidates"]);
                
                $this->save_vote( $vote, $all_candidates);
                // dd($input_data);
            }    
            //save the vote and save the user has voted
            $vote->save();
            $code->has_voted       =1;
            $code->is_code2_usable =0;
            $code->save();
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
         
        //
        //   $vote =$auth_user->vote();
          $auth_user            = auth()->user();
          $code                 = $auth_user->code;
          $this->user_id        =$auth_user->id;
          $selected_candidates  =[];
          if($code!=null){       
            //   dd($code->has_voted);
            if($code->has_voted)
            {
                $vote  =$auth_user->vote;
                $vote->voting_code="";
                //   dd(json_decode($vote));  
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
            
            }
        }
        //   dd($selected_candidates);
          
        return Inertia::render('Vote/VoteShow', [
            //    "presidents" => $presidents,
            //    "vicepresidents" => $vicepresidents,
                'vote' => $selected_candidates,
                'name'=>$auth_user->name 
              
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
       $vote = request()->session()->get('vote');
       $auth_user =auth()->user();
       //$value = $request->session()->get('key');
       // global helper method
        // $vote = session('vote');
        // dd($vote);
        // return Inertia::render('Vote/VoteVerify', [
        return Inertia::render('Vote/Verify', [
                'vote' =>$vote,
                 'name'=>$auth_user->name,
                 'nrna_id'=>$auth_user->nrna_id,
                 'state' =>$auth_user->state              
        ]);
                   
     
    }
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
    public function save_vote(&$vote, $input_data){
        // dd($input_data);     
        for ($i=0; $i<sizeof($input_data); $i++){
             $col_name = "candidate"; 
             $json=[];
            if($i<9){ 
                $col_name .="_0".strval($i+1);
            }else{
                $col_name .="_".strval($i+1);
            }


            if($input_data[$i] ===null){
                $json["candidates"] = null; 
                $json["no_vote"]    =true ;
                
            }else{
                $json = $input_data[$i] ;
                $json["no_vote"] =false;
               
            }
            $vote->$col_name = json_encode($json); 
           
        }       
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
          