<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class DeligateCodeController extends Controller
{
    //
    public function create()
    {
        //
          return Inertia::render('DeligateVote/CreateDeligateCode', [
        //    "presidents" => $presidents,
        //    "vicepresidents" => $vicepresidents,
             'name'=>auth()->user()->name,
             'nrna_id'=>auth()->user()->nrna_id,
             'state' =>auth()->user()->state              
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
        //
        $this->has_voted =auth()->user()->has_voted;
        $this->in_code   =auth()->user()->code1;
        $this->in_code    ="1234";
        $this->out_code   = $request['voting_code'];
        $validator  =$this->verify_vote_submit();
        $validator->validate($request);
        
        // if($code1 != $voting_code){
        //     $validator->errors()->add('voting_code', 'Your code is wrong');
        //  }
           
        //  dd($request->all());
            // var_dump($code1);    
        // dd(request()->all());
        if($this->in_code==$this->out_code & !$this->has_voted)
        {
            /**
             * Here you go to voting form. 
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true 
             * He has not voted before 
             */
            auth()->user()->can_vote_now =1;
            auth()->user()->save();
            return redirect()->route('deligatevote.create');

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
            
    }
    //helper function 
    public function verify_vote_submit()
    {
        $validator =  Validator::make(request()->all(), [
                    'voting_code' =>['required'],                    
                ]);
        
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


}//end of controller  
