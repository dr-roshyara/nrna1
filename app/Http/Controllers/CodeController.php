<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
Use App\Models\User;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SendFirstVerificationCode;

// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validator;
class CodeController extends Controller
{
    public $has_voted;
    public $in_code ;
    public $out_code;
    public $max_use_clientIP;
    public $clientIP;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct(){
        $this->clientIP             =\Request::getClientIp(true);
        $this->max_use_clientIP     =config('app.max_use_clientIP');
        $_message= check_ip_address($this->clientIP,$this->max_use_clientIP);
        if($_message['error_message']!=""){
            echo $_message['error_message'];
            // return abort('404');
        }
        // return abort('404');
        // dd($this->max_use_clientIP);
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

        $auth_user              = auth()->user();
        $form_opening_code      ='';
        $clientIP               = \Request::getClientIp(true);
         // dd($this->max_use_clientIP);

        /**
             * Normally user must be verified and then
             * However , we dont have a verification system now that's why
             * the user can vote directly
             */
         if(!$auth_user->can_vote_now){
            $auth_user->can_vote_now  =1;

            $auth_user->save();

        }

        $totalDuration   = 0;
        $code_expires_in =30;
        // dd($user);
        $user_id        = $auth_user->id ;
        // $user_email =$auth_user->email;
        /***
         *
         * If the user has already voted then it should show error.
         *
         */

        // dd($user);
        $code =Code::where('user_id','=',$user_id)->first();
        // dd($code->is_code1_usable);

        // dd($clientIP);

        if($code==null){
            $code                           = new Code;
            $code->voting_time_in_minutes   = $code_expires_in;
            $totalDuration                  = 0;
            $code->user_id                  =$user_id;
            $code->client_ip                =$clientIP;
            $code->save();
            // dd($code);
        }

        /***
         *
         * If already voted  or if IP address is used more than previously setup times
         * then show error.
         *
         */

        if($code->has_voted){
                echo '<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;">
                    <p> You have already voted! </p>
                     <p > <a href="/vote/show"> Please click here to see your vote.</a> </p>

                </div>';
                abort(404);

        }
        if($code->has_code1_sent){
            /***
             *
             * if the code has already been sent then check its validity.
             *
             */

            if(!$code->is_code1_usable){
                $code->has_code1_sent=0;
            }else{
                $updated_at         = Carbon::parse($code->updated_at);
                $current            = Carbon::now();
                $totalDuration      = $current->diffInMinutes($updated_at);
                     if($totalDuration>$code_expires_in){
                       $code->is_code1_usable =0;
                       $code->is_code2_usable =0;
                       $code->has_code1_sent  =0;
                       $code->can_vote_now    =0;
                       $totalDuration         =0;

                }
            }





        }

        /***
         *
         * Assign the new  code value only if it has not been used
         */
         if(!$code->has_code1_sent){

            $form_opening_code = get_random_string (6);
            // echo($form_opening_code);
            $code->user_id           =$user_id ;
            $code->code1             =Hash::make($form_opening_code) ;

            // $code->code1_used_at     =Carbon::now();


            //  dd($code);
            /****
             *
             * send the vote opening code via email here
             *
             */

            $auth_user->notify(new SendFirstVerificationCode($auth_user, $form_opening_code));

            //save the code info
            $code->is_code1_usable   =1;
            $code->is_code2_usable   =0;
            $code->has_code2_sent    =0;
            $code->has_code1_sent    =1;
            $code->can_vote_now      =0;
            $code->save();
         }
        //  dd($totalDuration);
        //
          return Inertia::render('Vote/CreateCode', [
        //    "presidents" => $presidents,
        //    "vicepresidents" => $vicepresidents,
             'name'             =>$auth_user->name,
             'nrna_id'          =>$auth_user->nrna_id,
             'state'            =>$auth_user->state,
             'code_duration'    =>$totalDuration,
             'code_expires_in'  =>$code_expires_in
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
         $this->pre_check($code);


        $this->in_code    = $code->code1;

        $this->out_code   = $request['voting_code'];
        $validator          =$this->verify_vote_submit($code->code1);
        if(!Hash::check( $this->out_code, $this->in_code)){
            $validator->errors()->add('voting_code',
            'Your code is wrong. Please check your email. If you do not have any code, then go to Dashboard and start form the begning.');
         }

        $validator->validate($request);
        // dd($validator);



        //  dd($request->all());
            // var_dump($code1);
        // dd(request()->all());
        if(Hash::check($this->out_code,$this->in_code) & !$this->has_voted)
        {
            /**
             * Here you go to voting form.
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true
             * He has not voted before
             */
            // auth()->user()->can_vote_now =1;
            $code->can_vote_now             =1;
            $code->code1_used_at            =Carbon::now();
            $code->voting_time_in_minutes   =30;
            $code->is_code1_usable          =0;
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
                if (!Hash::check( $voting_code,$this->in_code)) {
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
    public function  pre_check (& $code){
      $_message                     =[];
      $_message['error_message']    ='';
      $_message['return_to']        ='';
      if($code==null){
          return $_message;
      }

      if($code->has_voted){
        $_message['error_message'] ='<div style="margin:auto; color:red; padding:20px; font-weight:bold; text-align:center;">
        You have already voted! Please check your Vote</div>';
        $_message['return_to'] ='404';
        return $_message;
    }


    return $_message;

    }
    public static function check_ip_address(){
        $_message                   =[];
        $_message['error_message']  ="";
        $_message['return_to']      = "";
        $this->clientIP             =\Request::getClientIp(true);
        $this->max_use_clientIP     =config('app.max_use_clientIP');
        $ip_condition               =  "client_ip ='". $this->clientIP."' ";
        $ip_condition               .=  " AND has_voted";
        // dd($ip_condition);


        $select_statement           = "count(case when ";
        $select_statement           .= $ip_condition." ";
        $select_statement           .= " then 1 end) as ipCount";
        // dd( $select_statement);
        $times_ip_used              = DB::table('codes')
                                    ->selectRaw($select_statement)
                                    ->get();
        // dd($times_ip_used);
        // dd($this->max_use_clientIP);
        $this->times_use_cleintIP= $times_ip_used[0]->ipCount;
        // if($this->times_use_cleintIP>$this->max_use_clientIP){
        if($this->times_use_cleintIP >=$this->max_use_clientIP){
            $_message['error_message'] ='<div style="margin:auto; color:red;
                padding:20px; font-weight:bold; text-align:center;">';
                $_message['error_message'] .="<p> There are alerady more than " ;
                $_message['error_message'] .=$this->max_use_clientIP ;
                $_message['error_message'] .=" Votes casted from your iP Address: ";
                $_message['error_message'] .='<br> <span style="font-weight:bold; color: black;"> '.$this->clientIP."</span><br>";
                $_message['error_message'] .="We are sorry to say that You can not vote
                any more using this ip address.</p>";
                $_message['error_message'] .="
                 <p> तपाइको आइपी एड्रेस वाट पहिले नै ".$this->max_use_clientIP.
                 ' पटक भाेट हाली  सकिएको छ। माफ गर्नु होला,  हाम्राे नियम अनुसार एउटा आइपि एड्रेस वाट त्यस भन्दा वढी भोट हाल्न मिल्दैन।  </p>

                 <p style="margin-top: 4px; color:#1E90FF; font-weight:bold;">
                    <a href="'.route('dashboard'). '"> Go to the Dashboard </a> </p>
                    </div>
                 ';
            $_message['return_to'] ='404';
            // dd($_message);

          }
        return $_message;
    }
}
