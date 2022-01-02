<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
Use App\Models\User;
use App\Models\Code;
use Illuminate\Support\Str;
use App\Notifications\SendFirstVerificationCode;

// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validator;
class CodeController extends Controller
{
    public $has_voted;
    public $in_code ; 
    public $out_code;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct(){
        //  $this->has_voted =auth()->user()->has_voted;
         
         //  $this->in_code   =auth()->user()->code1;
     }
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
          
        $auth_user = auth()->user();
        // dd($user);
        $user_id    = $auth_user->id ;
        // $user_email =$auth_user->email;
        /***
         * 
         * If the user has already voted then it should show error.
         * 
         */
       
        // dd($user);
        $code =Code::where('user_id','=',$user_id)->first();
        // dd($code->is_code1_usable); 
        

        if($code==null){
            $code = new Code;
            // dd($code);
        }
        
        /***
         * 
         * If already voted then show error.
         * 
         */
       
        if($code->has_voted){
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
                You have already voted! Please check your Vote </div>';
                abort(404);
    
        }
        
        /***
         * 
         * Assign the new  code value only if it has not been used 
         */
         if(!$code->is_code1_usable & !$code->has_voted){
          
            $form_opening_code = get_random_string (6);       
            // echo($form_opening_code);
            $code->user_id          =$user_id ;
            $code->code1            =$form_opening_code ;
            $code->is_code1_usable   =1; 
            
               
             /***
              * 
              *Finally: save the vote form opening code 
              *
              */
                $code->save();
            /****
             * 
             * send the vote opening code via email here 
             *  
             */            

            $auth_user->notify(new SendFirstVerificationCode($auth_user));

         }
        
        //
          return Inertia::render('Vote/CreateCode', [
        //    "presidents" => $presidents,
        //    "vicepresidents" => $vicepresidents,
             'name'=>auth()->user()->name,
             'nrna_id'=>auth()->user()->nrna_id,
             'state' =>auth()->user()->state,
            //  'form_opening_code' =>$form_opening_code              
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
       
      
        /***
         * 
         * Firstly, get the code for vote opening form    
         */
        $user_id = auth()->user()->id ;
        // dd($user);
        $code =Code::where('user_id','=',$user_id)->first();
        // dd($code);
        if($code==null){
           /**
            * Normally this condidtion never comes but just in case 
            * if it comes then Redirect back to the creating voting code 
            *
            */
            return redirect()->route('code.create');
        }
        
        /***
         * 
         * Secondly check if this user has already voted . 
         * 
         */
         /***
         * 
         * If the user has already voted then it should show error.
         * 
         */
         $this->has_voted =$code->has_voted;
        if($this->has_voted){
            echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;"> 
            You have already voted! Please check your Vote </div>';
            abort(404);

        }


        $this->in_code    = $code->code1;
        
        $this->out_code   = $request['voting_code'];
        $validator          =$this->verify_vote_submit($code->code1);
        if($this->in_code!=$this->out_code){
            $validator->errors()->add('voting_code', 
            'Your code is wrong. Please check your email. If you do not have any code, then go to Dashboard and start form the begning.');
         }
    
        $validator->validate($request);
        // dd($validator);
        
      
           
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
            // auth()->user()->can_vote_now =1;
            $code->can_vote_now =1;
            $code->save();
            return redirect()->route('vote.create');

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
    public function verify_vote_submit($inner_code)
    {
        $validator =  Validator::make(request()->all(), [
                    'voting_code' =>['required'],                    
                ]);
        
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
                if ($this->in_code!= $voting_code) {
                    //add custom error to the Validator
                    $validator->errors()->add('voting_code',
                    "You have submitted  wrong voting code .  
                     Please recheck your email. 
                     If you do not have any code, 
                     then go to Dashboard and start form the begening.\n
                     तपाइले हाल्नु भएको भोटीङ कोड  मिलेन। कृपया आफ्नो भोटीङ कोड फेरी जाँच गर्नुहोस। "
                    );
                }
                if ($this->has_voted ) {
                    //add custom error to the Validator
                    $validator->errors()->add('Your_Vote',
                    "You have already voted! Please check your vote. 
                    तपाइले पहिल्यै भोट हालि सक्नु भयो जस्तो छ। पहिला आफ्नो भोट जाँच गर्नुहोस।  ");
                }
                

            });

        //run validation which will redirect on failure
        $validator->validate(request()->all());
        return $validator;
    }
}
