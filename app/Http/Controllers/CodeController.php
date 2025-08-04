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
    public $voting_time_in_minutes =20;
    
     public function __construct()
    {
        $this->clientIP = \Request::getClientIp(true);
        $this->max_use_clientIP = config('app.max_use_clientIP', 3);
        
        // Check IP rate limiting
        $this->checkIPRateLimit();
    }



     public function index()
    {
        //
    }

    
    /**
     * STEP 1: Initial ballot access request
     * Generate and send code1 via email
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth_user = auth()->user();
        $voting_time_in_minutes =$this->voting_time_in_minutes  ;
        
        // **ELIGIBILITY CHECK**: is_voter = true AND can_vote = true
        if (!$auth_user->canAccessBallot()) {
            $accessStatus = $auth_user->getBallotAccessStatus();
            
            return Inertia::render('Vote/BallotAccessDenied', [
                'error_type' => $accessStatus['error_type'],
                'error_title' => $accessStatus['error_title'],
                'error_message_nepali' => $accessStatus['error_message_nepali'],
                'error_message_english' => $accessStatus['error_message_english'],
                'user_name' => $auth_user->name
            ]);
        }

        // Get or create Code record (anonymization layer)
        $code = Code::firstOrCreate(
            ['user_id' => $auth_user->id],
            [
                'client_ip' => $this->clientIP,
                'voting_time_in_minutes' => $this->voting_time_in_minutes, // 20 minutes as per architecture
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Check if already completed voting
        if ($code->has_voted) {
            return Inertia::render('Vote/AlreadyVoted', [
                'user_name' => $auth_user->name,
                'message_nepali' => 'तपाईंले पहिले नै मतदान गरिसक्नुभएको छ!',
                'message_english' => 'You have already voted!',
                'vote_show_code' => $code->vote_show_code
            ]);
        }

        // Handle code1 generation and sending
        $totalDuration = 0;
        $code_expires_in = $this->voting_time_in_minutes;

        if ($code->has_code1_sent && $code->is_code1_usable) {
            // Check if existing code1 is still valid
            $updated_at = Carbon::parse($code->updated_at);
            $current = Carbon::now();
            $totalDuration = $current->diffInMinutes($updated_at);
            
            if ($totalDuration > $code_expires_in) {
                // Code expired, reset
                $this->resetCode1($code);
                $totalDuration = 0;
            }
        }

        // Generate new code1 if not sent or expired
        if (!$code->has_code1_sent || !$code->is_code1_usable) {
            $form_opening_code = $this->generateRandomString(6);
            
            // Save code1 (hashed for security)
            $code->update([
                'code1' => Hash::make($form_opening_code),
                'is_code1_usable' => true,
                'has_code1_sent' => true,
                'can_vote_now' => false, // Not yet - needs verification first
                'client_ip' => $this->clientIP,
                'updated_at' => now()
            ]);

            // Send email with code1
            $auth_user->notify(new SendFirstVerificationCode($auth_user, $form_opening_code));
        }

        return Inertia::render('Vote/CreateCode', [
            'user_name' => $auth_user->name,
            'nrna_id' => $auth_user->nrna_id,
            'state' => $auth_user->state,
            'code_duration' => $totalDuration,
            'code_expires_in' => $code_expires_in,
            'instructions_nepali' => 'तपाईंको इमेलमा भर्खै एउटा कोड पठाइएको छ। कृपया त्यो कोड यहाँ प्रविष्ट गर्नुहोस्।',
            'instructions_english' => 'A verification code has been sent to your email. Please enter the code here.'
        ]);
    }

    

    /**
     * STEP 2: Verify code1 and enable voting session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_user = auth()->user();
        
        // Re-check eligibility
        if (!$auth_user->canAccessBallot()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not eligible to access the ballot.');
        }

        $code = Code::where('user_id', $auth_user->id)->first();
        
        if (!$code) {
            return redirect()->route('code.create')
                ->with('error', 'Please start the voting process from the beginning.');
        }

        // Check if already voted
        if ($code->has_voted) {
            return redirect()->route('vote.show')
                ->with('info', 'You have already completed voting.');
        }

        // Validate the submitted code
        $request->validate([
            'voting_code' => 'required|string|min:6|max:6'
        ]);

        $submitted_code = $request->input('voting_code');

        // Verify code1
        if (!Hash::check($submitted_code, $code->code1) || !$code->is_code1_usable) {
            return back()->withErrors([
                'voting_code' => 'Invalid or expired code. Please check your email or request a new code.'
            ])->withInput();
        }

        // **CODE1 VERIFIED** - Update Code model as per architecture
        $code->update([
            'can_vote_now' => true,           // Enable voting session
            'is_code1_usable' => false,       // Code1 used, no longer valid
            'code1_used_at' => now(),         // Track when code1 was used
            'voting_time_in_minutes' => 20,   // Set 20-minute voting session
            'client_ip' => $this->clientIP,   // Update IP for audit
            'updated_at' => now()
        ]);

        // Redirect to agreement page
        return redirect()->route('vote.agreement');
    }
    
    /**
     * STEP 3: Show agreement page
     *
     * @return \Illuminate\Http\Response
     */
    public function showAgreement()
    {
        $auth_user = auth()->user();
        $code = Code::where('user_id', $auth_user->id)->first();

        // Security checks
        if (!$code || !$code->can_vote_now || $code->has_voted) {
            return redirect()->route('dashboard')
                ->with('error', 'Invalid voting session.');
        }

        return Inertia::render('Vote/Agreement', [
            'user_name' => $auth_user->name,
            'voting_time_minutes' => $code->voting_time_in_minutes,
            'agreement_text_nepali' => 'म यो अनलाइन मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु र मेरो मत गोप्य राखिनेछ भन्ने कुरामा सहमत छु।',
            'agreement_text_english' => 'I voluntarily participate in this online voting system and agree that my vote will remain secret and secure.'
        ]);
    }

    /**
     * STEP 4: Process agreement submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitAgreement(Request $request)
    {
        $auth_user = auth()->user();
        $code = Code::where('user_id', $auth_user->id)->first();

        // Security checks
        if (!$code || !$code->can_vote_now || $code->has_voted) {
            return redirect()->route('dashboard')
                ->with('error', 'Invalid voting session.');
        }

        $request->validate([
            'agreement' => 'required|accepted'
        ]);

        // **AGREEMENT ACCEPTED** - Update Code model
        $code->update([
            'has_agreed_to_start_vote' => true,
            'voting_started_at' => now(),
            'updated_at' => now()
        ]);

        // Redirect to actual voting page (VoteController)
        return redirect()->route('vote.create');
    }

     /**
     * STEP 5: Send code2 after first vote submission
     * Called by VoteController after temporary vote storage
     *
     * @param  Code  $code
     * @return bool
     */
    public function sendSecondCode(Code $code)
    {
        $user = User::find($code->user_id);
        $second_code = $this->generateRandomString(6);

        // Update Code model
        $code->update([
            'code2' => Hash::make($second_code),
            'is_code2_usable' => true,
            'has_code2_sent' => true,
            'code2_sent_at' => now(),
            'updated_at' => now()
        ]);

        // Send email with code2
        $user->notify(new SendSecondVerificationCode($user, $second_code));

        return true;
    }
    /**
     * STEP 6: Verify code2 for final submission
     * Called by VoteController
     *
     * @param  string  $submitted_code2
     * @param  Code    $code
     * @return bool
     */
    public function verifySecondCode($submitted_code2, Code $code)
    {
        if (!Hash::check($submitted_code2, $code->code2) || !$code->is_code2_usable) {
            return false;
        }

        // **CODE2 VERIFIED** - Mark as used
        $code->update([
            'code2_used_at' => now(),
            'is_code2_usable' => false,
            'updated_at' => now()
        ]);

        return true;
    }
    /**
     * STEP 7: Finalize voting process
     * Called by VoteController after vote is saved
     *
     * @param  Code  $code
     * @return string vote_show_code
     */
    public function finalizeVoting(Code $code)
    {
        // Generate vote show code for receipt
        $vote_show_code = $this->generateRandomString(8);

        // **VOTING COMPLETED** - Final update
        $code->update([
            'has_voted' => true,
            'vote_submitted' => true,
            'vote_submitted_at' => now(),
            'vote_show_code' => $vote_show_code,
            'updated_at' => now()
        ]);

        // Send receipt email
        $user = User::find($code->user_id);
        // $user->notify(new SendVoteReceipt($user, $vote_show_code));

        return $vote_show_code;
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

        /**
         * Helper: Check IP rate limiting
         */
        private function checkIPRateLimit()
        {
            $votes_from_ip = Code::where('client_ip', $this->clientIP)
                ->where('has_voted', true)
                ->count();

            if ($votes_from_ip >= $this->max_use_clientIP) {
                $error_html = $this->generateIPLimitError();
                echo $error_html;
                abort(403);
            }
        }

       /**
     * Helper: Generate random string for codes
     *
     * @param  int  $length
     * @return string
     */
    private function generateRandomString($length = 6)
    {
        return strtoupper(Str::random($length));
    }

    /**
     * Helper: Reset expired code1
     *
     * @param  Code  $code
     */
    private function resetCode1(Code $code)
    {
        $code->update([
            'code1' => null,
            'is_code1_usable' => false,
            'has_code1_sent' => false,
            'can_vote_now' => false
        ]);
    }
    /**
     * Helper: Generate IP limit error HTML
     *
     * @return string
     */
    private function generateIPLimitError()
    {
        return '
        <div style="max-width: 600px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; font-family: Arial, sans-serif;">
            <h2 style="color: #e53e3e; margin-bottom: 1rem;">मतदान सीमा पूरा | Voting Limit Reached</h2>
            <p style="color: #4a5568; margin-bottom: 1rem; line-height: 1.6;">
                तपाइको आइपी एड्रेस <strong>' . $this->clientIP . '</strong> बाट पहिले नै ' . $this->max_use_clientIP . ' पटक मतदान भएको छ।
            </p>
            <p style="color: #4a5568; margin-bottom: 1.5rem; line-height: 1.6;">
                Your IP address <strong>' . $this->clientIP . '</strong> has already been used for ' . $this->max_use_clientIP . ' votes.
            </p>
            <a href="' . route('dashboard') . '" style="display: inline-block; background: #3182ce; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 4px; font-weight: 600;">
                Dashboard मा जानुहोस् | Go to Dashboard
            </a>
        </div>';
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
           
           if ($this->times_use_clientIP >= $this->max_use_clientIP) {
    $css = '
        <style>
            .vote-error-container {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                background-color: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                text-align: center;
                font-family: Arial, sans-serif;
            }
            .vote-error-message {
                color: #e53e3e;
                font-weight: 600;
                margin-bottom: 1.5rem;
                line-height: 1.6;
            }
            .vote-error-message-nepali {
                color: #4a5568;
                margin-bottom: 1.5rem;
                line-height: 1.6;
                font-size: 0.95rem;
            }
            .vote-error-ip {
                color: #2d3748;
                font-weight: 700;
                display: inline-block;
                margin: 0.5rem 0;
            }
            .vote-error-link {
                margin-top: 1rem;
            }
            .vote-error-link a {
                color: #3182ce;
                font-weight: 600;
                text-decoration: none;
                transition: color 0.2s;
            }
            .vote-error-link a:hover {
                color: #2c5282;
                text-decoration: underline;
            }
        </style>
    ';

    $html = '
        <div class="vote-error-container">
            <p class="vote-error-message">
                There are already more than ' . $this->max_use_clientIP . ' votes cast from your IP Address:
                <br>
                <span class="vote-error-ip">' . $this->clientIP . '</span>
                <br>
                We are sorry to say that you cannot vote any more using this IP address.
            </p>
            
            <p class="vote-error-message-nepali">
               aas ---तपाइको आइपी एड्रेस वाट पहिले नै ' . $this->max_use_clientIP . ' पटक भोट हाली सकिएको छ। 
                माफ गर्नु होला, हाम्राे नियम अनुसार एउटा आइपि एड्रेस वाट त्यस भन्दा वढी भोट हाल्न मिल्दैन।
            </p>
            
            <p class="vote-error-link">
                <a href="' . route('dashboard') . '">Go to the Dashboard</a>
            </p>
        </div>
    ';

    $_message['error_message'] = $css . $html;
    $_message['return_to'] = '404';
    return $_message;
}    $_message['return_to'] ='404';
            // dd($_message);

          }
        return $_message;
    }
}
