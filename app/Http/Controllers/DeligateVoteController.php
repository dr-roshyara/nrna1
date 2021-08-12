<?php

namespace App\Http\Controllers;

use App\Models\DeligateVote;
use App\Models\Candidacy;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

class DeligateVoteController extends Controller
{
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
        //$candidacies = DB::table('candidacies')->get();     
        $query =Candidacy::query();
        $candidacies =$query->paginate(120); 
        $can_vote_now   =auth()->user()->can_vote_now;
        $has_voted      = auth()->user()->has_voted;  
        // $has_voted      =false;       
        $btemp          = $can_vote_now && !$has_voted;
        $lcc             =auth()->user()->lcc;
        // $lcc             ="Berlin";
        // dd($btemp);
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
             *Here Everything is checked . you save the deligatevote. 
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true 
             * He has not voted before 
             */
            //get deligatevote from session 
            $input_data = $request->session()->get('deligatevote');
        //    dd($input_data);

            //no_vote option is saved in 19 
            
             $this->save_vote($input_data);   
        
          

        }else{
            if($this->has_voted){
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                You have already voted! Please check your deligatevote </div>';
                abort(404);

             }else{
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                Your code can not be verified </div>';
                abort(404);
             }
        } 
           
       
   
       
        // auth()->user()->save();
        $request->session()->forget('deligatevote');
         return redirect()->route('deligatevote.show'); 

       
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
        $deligatevote  =User::find($this->user_id)->deligatevote;
        // $deligatevote  =['no_vote_option'=>1];
        if($deligatevote){
            return Inertia::render('DeligateVote/ShowDeligateVote', [
                //    "presidents" => $presidents,
                //    "vicepresidents" => $vicepresidents,
                    'deligatevote' =>$deligatevote,
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
       // dd($request);

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
           return redirect()->route('deligatevote.verfiy');

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
            
             dd($input_data);
             $no_vote_option                 =$input_data[19];   
             $deligatevote                   =new DeligateVote; 
             $deligatevote->user_id          =$this->user_id;               
            if($no_vote_option) { //check if voter has given no_vote  option 
                // Go for no deligatevote option 
                $deligatevote->no_vote_option   =1;
                $deligatevote->save();        

             }else{
    
             $post                =$input_data[0];
            if(sizeof($icc_member)>0){
                //   dd($icc_member[0]["candidacy_name"]);
                $deligatevote['icc_member1_name']  =$post[0]["candidacy_name"];
                $deligatevote['icc_member1_id']    =$post[0]["candidacy_id"];

            }         //  
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

} //end of controller
