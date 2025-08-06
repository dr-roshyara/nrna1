<?php

namespace App\Http\Controllers;

use App\Models\DeligateVote;
use App\Models\Candidacy;
use App\Models\DeligateCandidacy;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

class DeligateVoteController extends Controller
{

    public $deligatevote ;
    public $has_voted;
    public $in_code ;
    public $out_code;
    public $user_id;
    public $member_keys;

    public function __construct(){
        $this->member_keys =array(
            "member1_id",
            "member2_id",
            "member3_id",
            "member4_id",
            "member5_id",
            "member6_id",
            "member7_id",
            "member8_id",
            "member9_id",
            "member10_id",
            "member11_id",
            "member12_id",
            "member13_id",
            "member14_id",
            "member15_id",
            "member16_id",
            "member17_id",
            "member18_id",
            "member19_id",
            "member21_id",
            "member22_id",
            "member23_id",
            "member24_id",
            "member25_id",
            "member26_id",
            "member27_id",
            "member28_id",
            "member29_id",
            "member30_id",
            "member31_id",
            "member32_id",
            "member33_id",
            "member34_id",
            "member35_id");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidacies = DB::table('deligate_candidacies')->get();
        // $query =DeligateCandidacy::query();
        // $candidacies =$query->paginate(50);
        $can_vote_now   =auth()->user()->can_vote_now;
        $has_voted      = $code->has_voted;
        // $has_voted      =false;
        $btemp          = $can_vote_now && !$has_voted;
        $lcc             =auth()->user()->lcc;
        // $lcc             ="Berlin";
        // dd($btemp);
        // dd($candidacies);
        if(!$can_vote_now){
                echo "Your code can not be verified";
                abort(404);
            return (404);
        }
        if($has_voted){
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;">
                You have already voted! Please check your deligatevote </div>';
                abort(404);
            return (404);
            }

    if($btemp){
        return Inertia::render('DeligateVote/CreateDeligateVote', [
                "candidacies" =>$candidacies,
                'user_name'=>auth()->user()->name,
                'user_id'=>auth()->user()->id,
                'user_lcc'=>$lcc

            ]);
        }else{
            return redirect()->route('deligatevote.show');
        }
        //    {name: "Hari Bahadur", photo: "test1.png",  post: ["President", "अद्यक्ष"], id:"hari", checked: false, disabled: false },

    }

    /**
 * Store a newly created vote resource without any user identification.
 * 
 * This handles the final submission of an anonymized vote after verification.
 * It validates the voting code, saves the vote without user info, generates a private key,
 * and marks the user as having voted.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(Request $request)
{
    DB::beginTransaction();
    
    try {
        $auth_user = auth()->user();
        $code = $auth_user->code;
        $voting_code = trim($request->input('voting_code'));
        $vote_data = $request->session()->get('vote');

        // 1. Validate pre-conditions
        $pre_check = $this->vote_post_check($auth_user, $code, $vote_data);
        
        if (!empty($pre_check['error_message'])) {
            return $this->handleVoteError($pre_check['error_message']);
        }
        
        if (!empty($pre_check['return_to'])) {
            return redirect()->route($pre_check['return_to']);
        }

        // 2. Verify code usability
        if (!$code->is_code2_usable) {
            return $this->handleVoteError('Your code has a problem. Please contact the administrator.');
        }

        // 3. Validate the voting code submission
        $validator = $this->verify_vote_submit();
        $validator->validate();

        // 4. Check if user has already voted (redundant check for safety)
        if ($code->has_voted) {
            $request->session()->forget('vote');
            return redirect()->route('vote.verify_to_show');
        }

        // 5. Verify the code hash
        if (!Hash::check($voting_code, $code->code2)) {
            return $this->handleVoteError($code->has_voted 
                ? 'You have already voted! Please check your vote'
                : 'Your code could not be verified');
        }

        // 6. Save the vote WITHOUT user information
        $vote = $this->saveAnonymizedVote($voting_code, $vote_data);

        // 7. Generate and store verification key
        $private_key = $this->generateAndStoreVerificationKey($code, $vote->id);

        // 8. Mark user as voted and update code status
        $this->markUserAsVoted($code);

        // 9. Send verification notification
        $auth_user->notify(new SendVoteSavingCode($private_key));

        DB::commit();

        // 10. Clean up and redirect
        $request->session()->forget('vote');
        return redirect()->route('vote.show')->with('success', 'Your vote has been successfully submitted.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Vote submission failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return $this->handleVoteError('An error occurred while processing your vote. Please try again.');
    }
}

/**
 * Save an anonymized vote record without any user identification
 * 
 * @param string $voting_code
 * @param array $vote_data
 * @return Vote
 */
protected function saveAnonymizedVote(string $voting_code, array $vote_data): Vote
{
    $vote = new Vote();
    
    // Store only the voting code, no user identification
    $vote->voting_code = $voting_code;
    $vote->save();

    if (!empty($vote_data['national_selected_candidates']) || !empty($vote_data['regional_selected_candidates'])) {
        $this->saveCandidateSelections($vote, $vote_data);
    }

    return $vote;
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeligateVote  $deligateVote
     * @return \Illuminate\Http\Response
     */
    public function show(DeligateVote $deligateVote)
    {
        //
        //   $deligatevote =auth()->user()->deligatevote();
        $this->user_id =auth()->user()->id;
        $deligateVote  =User::find($this->user_id)->deligatevote;
        // $deligatevote  =['no_vote_option'=>1];
        if($deligateVote){


             $deligates =$this->prepare_deligate_vote($deligateVote);


            //  dd($conformation_code);
            //    $vote_id =$deligateVote;

            return Inertia::render('DeligateVote/ShowDeligateVote', [
                //    "presidents" => $presidents,
                //    "vicepresidents" => $vicepresidents,
                    'deligatevote' =>$deligates,
                    'name'=>auth()->user()->name

            ]);

        }else{

            abort(404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeligateVote  $deligateVote
     * @return \Illuminate\Http\Response
     */
    public function edit(DeligateVote $deligateVote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeligateVote  $deligateVote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeligateVote $deligateVote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeligateVote  $deligateVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeligateVote $deligateVote)
    {
        //
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
                 $validator->errors()->add('Nothing_Slected: ',"You must either select at least one deligate or use your right to reject all deligates!");
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

           return redirect()->route('deligatevote.show');
           $validator->after(function ($validator) {
                 $validator->errors()->add('deligatevote: ',
                 'You have already Voted. Thank you! Please check your deligatevote!');
           });
           //run validation which will redirect on failure
       }
       $validator->validate($request);
    //    dd($request);

        /**
         *Here you come only if  the user votes for the first time
         *
          */

          $deligatevote = request()->all();

       //    $request->session()->put('deligatevote', $deligatevote);
          $candi_vec =[];
          array_push($candi_vec, request('no_vote_option'));
          array_push($candi_vec,  $this->get_candidate('member'));
        //    dd($candi_vec);
             $request->session()->put('deligatevote', $candi_vec);
           // session(['deligatevote'=>$candi_vec]);
           //$request->session()->put('key', 'value');
           //session(['key' => 'value']);
           return redirect()->route('deligatevote.verifiy');

       /****
        * Now save the code and show directly
        */
       $input_data = $candi_vec;
       $this->user_id    =auth()->user()->id;
        //    dd($input_data);
           //no_vote option is saved in 19
            $no_vote_option  =$input_data[19];
           if($no_vote_option) { //check if voter has given no_vote  option
               // Go for no deligatevote option
               $deligatevote                   =new deligatevote;
               $deligatevote->no_vote_option   =1;
               $deligatevote->user_id          =$this->user_id;
               $deligatevote->save();

         }else{
            /**
             * Here you save the deligatevote finally :
             * G
             */
               $deligatevote                = new DeligateVote;
               $deligatevote->user_id       = $this->user_id;
               $this->save_vote($input_data);

           }
           //save the deligatevote and save the user has voted
           return redirect()->route('deligatevote.show');
    }

   // helper functions
   public function at_least_one_vote_casted(){

    $btemp = false;
    $btemp =request('no_vote_option');
    $btemp =$btemp | sizeof(request('member'))>0;



    return $btemp;
}
public function get_candidate($key){
    $_candivec =[];
     $submit_vec =request($key);
    if(sizeof($submit_vec)>0)
    {

            // dd($submit_vec);
            for($i=0; $i<sizeof($submit_vec); ++$i){
                //    var_dump($submit_vec[$i] );
                $_candi                      = DB::table('deligate_candidacies')->where([
                ['nrna_id', '=',  $submit_vec[$i] ],
                // ['post_id',        '=',  $_postid]
                ])->get()->first();

                        // dd($_candi);
                   $myvec = array(
                            // 'post_name' =>"Deligate Member",
                            'user_id'     =>$_candi->user_id,
                            'post_id'     =>$_candi->post_id,
                            'nrna_id'     =>$_candi->nrna_id,
                            'name'         => $_candi->name
                    );

                array_push($_candivec,   $myvec);

            }

        }

   return $_candivec;
}
public function verify(){
   $deligatevote = request()->session()->get('deligatevote');
   //$value = $request->session()->get('key');
   // global helper method
    // $deligatevote = session('deligatevote');
    // dd($deligatevote);
    return Inertia::render('DeligateVote/VerifyDeligateVote', [
             'deligatevote' =>$deligatevote,
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

     $validator->after(function ($validator) {
            /**
             * Here we chan change the code condition
             * mention where the code is saved
             * call the code
             * compare the code
             * If code is not equal ,then reject
             *  */

              $voting_code =request('voting_code');
            if ($this->in_code!= $this->out_code ) {
                //add custom error to the Validator
                $validator->errors()->add('voting_code',"You have submitted wrong Voting Code!");
            }
            if ($this->has_voted ) {
                //add custom error to the Validator
                $validator->errors()->add('Your_Vote',"You have already voted! Please check your deligatevote");
            }


        });

    //run validation which will redirect on failure
    // $validator->validate($request);
    return $validator;
}
//save all candidates
public function save_vote($input_data){

            //  dd($input_data);
             $no_vote_option                 =$input_data[0];
             $deligatevote                   =new DeligateVote;
            //this id is not of the person who has been selected but of the person who has voted
             $deligatevote->user_id              =$this->user_id;
             $deligatevote->conformation_code    =$this->in_code;

            if($no_vote_option) { //check if voter has given no_vote  option
                // Go for no deligatevote option
                $deligatevote->no_vote_option   =1;
                $deligatevote->save();

             }else{

             $post                =$input_data[1];
            //save the votes
             for ($i=0; $i<sizeof($this->member_keys); $i++){
                             // save the first vote
                if(sizeof($post)>$i){
                    $_key ="member". ($i+1) ."_id";
                    // dd($_key);
                    $deligatevote[$_key]  =$post[$i]["nrna_id"];
                }


            }



         }
        $deligatevote->save();
        $user =Auth::user();
        $user->has_voted=1;
        $user->save();

}
//deligatevote thanks
public function thankyou(){
       return Inertia::render('Thankyou/Thankyou', [
             'deligatevote' =>$deligatevote,
            //  'name'=>auth()->user()->name,
            //  'nrna_id'=>auth()->user()->nrna_id,
            //  'state' =>auth()->user()->state
    ]);

}
//
/**
 *  Call deligate vote and prepare name, nrna id  etc
 *
 */
    public function prepare_deligate_vote (DeligateVote $deligateVote){
        $deligates =[];
        $deli_vote  =[];
        $no_vote_option =$deligateVote->no_vote_option;
        $conformation_code =$deligateVote->conformation_code;
        $deligates['conformation_code'] =$conformation_code;
        $deligates['no_vote_option'] =$no_vote_option;
        // $deligates['no_vote_option']     =1;
        // $_nsize  = sizeof($deligateVote->getOriginal());
        //deligate 1
        // dd($deligateVote);
           //save the votes
        for ($i=0; $i<sizeof($this->member_keys); $i++){
            $_key ="member". ($i+1) ."_id";

             $deli =$this->find_name_nrnaId($deligateVote->getOriginal($_key));
             if($deli){ array_push($deli_vote, $deli); }
        }

         $deligates['deligatevote'] =$deli_vote;


        return $deligates;

 }
  public function find_name_nrnaId($nrna_id){

    $_user =User::where('nrna_id', $nrna_id)->first();
    if($_user){
        return array(
            'id' =>$_user->id,
            'name'=>$_user->name,
            'nrna_id'=>$_user->nrna_id,
            'lcc'=>$_user->lcc,
            'state'=>$_user->state

        );

    }

}

/**
 * count the result
 */

    public function count(){
        $_deliVote   = DB::table('deligate_votes')->get();

        //  echo gettype($_deliVote[0]);
        //  dd(array_intersect_key((array)$_deliVote[0],(array)$_deliVote[0]));

        // dd($_deliVote);
        $result =[];

        $sumArray = array();
        foreach ($_deliVote  as $k=>$subArray) {
                $_subObject =$subArray;
                // dd($_subObject);
            foreach ($_subObject as $id=>$value) {
                /**
                 * if the key lies in pre_names then sum up
                 */
                    // dd($id);
                if( in_array($id, $this->member_keys) ){
                        // dd($id);
                    $result_key =array_keys($result);
                    if(in_array($value, $result_key)){
                        $result[$value]+=1;
                    }else{
                        if($value!=""){
                                $result[$value]=1;
                        }

                    }


                }

            }

    }
        return($result);

  }

  /**
   *
   * Get the result final way
   */
    public function result (){
        $result             =$this->count();
        arsort( $result);
        // dd($result);
        $deligate_result    =[];
        foreach ($result as $nrna_id=>$voteCount){

            $_deli_info =$this->find_name_nrnaId($nrna_id);
            $_deli_info['vote'] =$voteCount;
            array_push($deligate_result,$_deli_info);
        }




        return Inertia::render('DeligateVote/ResultDeligateVote', [
            'deligate_result' =>$deligate_result,

        ]);

    }

} //end of controller
