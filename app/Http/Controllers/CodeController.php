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
    public $times_ip_used; 
    public $clientIP;
    public $voting_time_in_minutes;
    
    public function __construct()
    {
        $this->clientIP = \Request::getClientIp(true);
        $this->max_use_clientIP = config('app.max_use_clientIP', 7); // Default to 7 if not set
        $this->voting_time_in_minutes = 20; // Set consistent 20-minute limit
        
        // Check IP rate limiting (but don't block in constructor)
        $this->checkIPRateLimit();
    }


     public function index()
    {
        //
    }

    /***
    * Enhanced IP rate limiting check
    * Logs but doesn't block in constructor
    */
    private function checkIPRateLimit()
    {
        $votes_from_ip = Code::where('client_ip', $this->clientIP)
            ->where('has_voted', 1)
            ->count();
        $this->times_ip_used =$votes_from_ip;

        if ($votes_from_ip >= $this->max_use_clientIP) {
            \Log::warning('IP rate limit reached', [
                'client_ip' => $this->clientIP,
                'votes_from_ip' => $votes_from_ip,
                'limit' => $this->max_use_clientIP,
            ]);
            
            // Don't block here - let individual methods handle it
            // This prevents issues with constructor blocking
        }
    }

    
/**
 * STEP 1: Initial ballot access request
 * Generate and send code1 via email (ONLY ONCE)
 * Professional implementation following exact specifications
 *
 * @return \Illuminate\Http\Response
 */
public function create()
{
    $auth_user = auth()->user();
    
    // Log method start for debugging
    \Log::info('Create method started', [
        'user_id' => $auth_user->id,
        'nrna_id' => $auth_user->nrna_id ?? null,
        'client_ip' => $this->clientIP,
        'timestamp' => now(),
    ]);

    // ==========================================
    // #1: CHECK IF USER IS ALLOWED TO VOTE
    // ==========================================
    
    // First check: Basic eligibility (is_voter, can_vote, election.is_active)
    if (!$auth_user->canAccessBallot()) {
        $accessStatus = $auth_user->getBallotAccessStatus();
        
        \Log::warning('Basic ballot access denied', [
            'user_id' => $auth_user->id,
            'denial_reason' => $accessStatus['error_type'],
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/BallotAccessDenied', [
            'error_type' => $accessStatus['error_type'],
            'error_title' => $accessStatus['error_title'],
            'error_message_nepali' => $accessStatus['error_message_nepali'],
            'error_message_english' => $accessStatus['error_message_english'],
            'user_name' => $auth_user->name,
        ]);
    }
    
    // Second check: Additional validations (IP limits, account status, etc.)
    $voterValidation = $this->isVoterValidated($auth_user);
    if (!$voterValidation['allowed']) {
        $errorData = $voterValidation['error_data'];
        
        \Log::warning('Additional voter validation failed', [
            'user_id' => $auth_user->id,
            'error_type' => $errorData['error_type'],
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/VoteDenied', array_merge($errorData, [
            'user_name' => $auth_user->name,
        ]));
    }
    
    \Log::info('✅ User allowed to vote - all validations passed');

    // ==========================================
    // #2: CHECK IF CODE FOR THIS VOTER EXISTS
    // ==========================================
    
    $code = Code::where('user_id', $auth_user->id)->first();
    
    \Log::info('Code existence check', [
        'code_exists' => $code ? 'yes' : 'no',
        'user_id' => $auth_user->id,
    ]);
   
    if ($code) {
        // Code already exists - validate it
        \Log::info('Code exists - validating', [
            'has_code1_sent' => $code->has_code1_sent ?? 'null',
            'is_code1_usable' => $code->is_code1_usable ?? 'null',
            'is_codemodel_valid' => $code->is_codemodel_valid ?? 'null',
            'has_voted' => $code->has_voted ?? 'null',
            'has_code2_sent' => $code->has_code2_sent ?? 'null',
            'code1_sent_at' => $code->code1_sent_at ?? 'null',
        ]);
        
        // Check if code is valid according to specifications
        $codeValidationResult = $this->validateExistingCodeModel($code, $auth_user);
        
        if ($codeValidationResult !== true) {
            return $codeValidationResult; // Return error response
        }
        
    } else {
        // Code doesn't exist - create new record
        \Log::info('Code does not exist - creating new record');
        $code = $this->createNewCodeRecord($auth_user);
    }

    // ==========================================
    // #3: IP ADDRESS VALIDATION
    // ==========================================
    // Check if IP address matches saved IP in User model
    if (isset($auth_user->voting_ip) && !empty($auth_user->voting_ip)) {
        if ($auth_user->voting_ip !== "$this->clientIP") {
            \Log::warning('IP mismatch with User model during create', [
                'user_id' => $auth_user->id,
                'user_stored_ip' => $auth_user->client_ip,
                'current_ip' => $this->clientIP,
            ]);
            
            return Inertia::render('Vote/VoteDenied', [
                'denial_type' => 'IP Address Mismatch',
                'error_title' => 'Vote Denied - IP Address Changed',
                'title_english' => 'Vote Denied - IP Address Changed',
                'title_nepali' => 'मतदान अस्वीकृत - IP एड्रेस फेरिएको',
                'error_message_english' => 'Your IP address does not match the IP address saved in your user profile. For security reasons, voting must be done from the registered IP address.',
                'error_message_nepali' => 'तपाईंको IP एड्रेस तपाईंको प्रयोगकर्ता प्रोफाइलमा सुरक्षित IP एड्रेससँग मेल खाँदैन। सुरक्षा कारणले, दर्ता गरिएको IP एड्रेसबाट मतदान गर्नुपर्छ।',
                'solution_english' => 'Please return to your registered network connection and try again, or contact the election committee.',
                'solution_nepali' => 'कृपया आफ्नो दर्ता गरिएको नेटवर्क जडानमा फर्कनुहोस् र फेरि प्रयास गर्नुहोस्, वा निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
                'user_name' => $auth_user->name,
                'current_ip' => $this->clientIP,
                'registered_ip' => $auth_user->client_ip,
            ]);
        }
    }

    // ==========================================
    // #4: CODE GENERATION AND SENDING
    // ==========================================
    
    // shouldSendCode is true only if has_code1_sent is false (send only once)
    $shouldSendCode = ($code->has_code1_sent == 0);
    
    \Log::info('Code sending decision', [
        'should_send_code' => $shouldSendCode,
        'has_code1_sent' => $code->has_code1_sent,
        'reason' => $shouldSendCode ? 'Code never sent before' : 'Code already sent once',
    ]);
    
    if ($shouldSendCode) {
        // Generate and send new code
        $sendResult = $this->generateAndSendCode($code, $auth_user);
        
        if (!$sendResult['success']) {
            \Log::error('Failed to generate and send code', [
                'user_id' => $auth_user->id,
                'error' => $sendResult['error'],
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'Failed to send verification code. Please try again or contact the election committee.');
        }
        
        \Log::info('✅ New code generated and sent successfully');
        
    } else {
        \Log::info('Code already sent previously, skipping generation');
        
        // Check if existing code is still valid or expired
        if ($code->code1_sent_at) {
            $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
            $minutesSinceSent = now()->diffInMinutes($sentAt);
            
            // If code expired and user didn't receive it, forward to denial page
            if ($minutesSinceSent > $this->voting_time_in_minutes) {
                \Log::warning('Code expired - user should contact administrator', [
                    'user_id' => $auth_user->id,
                    'minutes_since_sent' => $minutesSinceSent,
                    'voting_time_limit' => $this->voting_time_in_minutes,
                    'code1_sent_at' => $code->code1_sent_at,
                ]);
                
                return Inertia::render('Vote/VoteDenied', [
                    'denial_type' => 'Code Expired',
                    'title_english' => 'Verification Code Expired',
                    'title_nepali' => 'प्रमाणीकरण कोड समाप्त भयो',
                    'error_message_english' => "Your verification code was sent more than {$this->voting_time_in_minutes} minutes ago and has expired. If you did not receive the code in your email, please contact the election administrator.",
                    'error_message_nepali' => "तपाईंको प्रमाणीकरण कोड {$this->voting_time_in_minutes} मिनेट भन्दा बढी समय अघि पठाइएको थियो र समाप्त भएको छ। यदि तपाईंले आफ्नो इमेलमा कोड प्राप्त गर्नुभएन भने, कृपया निर्वाचन प्रशासकलाई सम्पर्क गर्नुहोस्।",
                    'solution_english' => 'Please contact the election administrator for assistance with a new verification code.',
                    'solution_nepali' => 'कृपया नयाँ प्रमाणीकरण कोडको साथ सहायताको लागि निर्वाचन प्रशासकलाई सम्पर्क गर्नुहोस्।',
                    'user_name' => $auth_user->name,
                    'expired_minutes' => $minutesSinceSent,
                    'time_limit' => $this->voting_time_in_minutes,
                    'code_sent_at' => $code->code1_sent_at,
                ]);
            }
        }
    }

    // ==========================================
    // #5: PREPARE RESPONSE DATA
    // ==========================================
    
    // Calculate time data for display
    $timeData = $this->calculateTimeData($code);
    
    \Log::info('Final state before rendering', [
        'total_duration' => $timeData['total_duration'],
        'remaining_time' => $timeData['remaining_time'],
        'code_already_sent' => $code->has_code1_sent,
        'is_codemodel_valid' => $code->is_codemodel_valid ?? 'null',
    ]);
    // Show code entry page
    return Inertia::render('Vote/CreateCode', [
        'user_name' => $auth_user->name,
        'nrna_id' => $auth_user->nrna_id ?? 'N/A',
        'state' => $auth_user->state ?? 'N/A',
        'code_duration' => $timeData['total_duration'],
        'code_expires_in' => $this->voting_time_in_minutes,
        'remaining_time' => $timeData['remaining_time'],
        'code_already_sent' => $code->has_code1_sent,
        'is_codemodel_valid' => $code->is_codemodel_valid ?? false,
        'instructions_nepali' => 'तपाईंको इमेलमा एक प्रमाणीकरण कोड पठाइएको छ। कृपया त्यो कोड यहाँ प्रविष्ट गर्नुहोस्।',
        'instructions_english' => 'A verification code has been sent to your email. Please enter the code here to continue voting.',
        'client_ip' => $this->clientIP, // For debugging
    ]);


}



 


/**
 * STEP 2: Verify code1 and enable voting session
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
/**
 * STEP 2: Verify code1 and enable voting session
 * Complete validation with all security checks
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    // ==========================================
    // PHASE 1: BASIC VALIDATIONS
    // ==========================================
    
    $auth_user = auth()->user();
    
    if (!$auth_user) {
        \Log::error('Store method called without authenticated user');
        return redirect()->route('login')
            ->with('error', 'Authentication required. Please log in first.');
    }

    // Validate request input format
    $request->validate([
        'voting_code' => 'required|string|min:6|max:6'
    ], [
        'voting_code.required' => 'Please enter the verification code.',
        'voting_code.min' => 'Code must be exactly 6 characters.',
        'voting_code.max' => 'Code must be exactly 6 characters.',
    ]);

    // Get and clean submitted code
    $submitted_code = trim(strtoupper($request->input('voting_code')));
    $user_id = $auth_user->id;

    // Check if Code record exists for user
    $code = Code::where('user_id', $user_id)->first();
    if (!$code) {
        \Log::warning('Code verification attempted without code record', [
            'user_id' => $user_id,
            'client_ip' => $this->clientIP,
        ]);
        
        return redirect()->route('code.create')
            ->with('error', 'No verification code found. Please start the voting process from the beginning.');
    }

    // ==========================================
    // PHASE 2: USER ELIGIBILITY VALIDATIONS
    // ==========================================

    // Check 1: User must have is_voter = 1
    if (!isset($auth_user->is_voter) || $auth_user->is_voter != 1) {
        \Log::warning('Voting attempted by non-voter user', [
            'user_id' => $user_id,
            'nrna_id' => $auth_user->nrna_id,
            'is_voter' => $auth_user->is_voter ?? 'null',
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/BallotAccessDenied', [
            'error_type' => 'not_eligible_voter',
            'error_title' => 'Not Eligible to Vote',
            'error_message_english' => 'You are not registered as an eligible voter for this election.',
            'error_message_nepali' => 'तपाईं यस चुनावको लागि योग्य मतदाताको रूपमा दर्ता हुनुहुन्न।',
            'user_name' => $auth_user->name,
        ]);
    }

    // Check 2: User must have can_vote = 1
    if (!isset($auth_user->can_vote) || $auth_user->can_vote != 1) {
        \Log::warning('Voting attempted by user without can_vote permission', [
            'user_id' => $user_id,
            'nrna_id' => $auth_user->nrna_id,
            'can_vote' => $auth_user->can_vote ?? 'null',
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/BallotAccessDenied', [
            'error_type' => 'cannot_vote',
            'error_title' => 'Voting Not Permitted',
            'error_message_english' => 'Your account does not have voting permissions for this election.',
            'error_message_nepali' => 'तपाईंको खातामा यस चुनावको लागि मतदान अनुमति छैन।',
            'user_name' => $auth_user->name,
        ]);
    }

    // Check 3: Comprehensive ballot access validation
    if (!$auth_user->canAccessBallot()) {
        $accessStatus = $auth_user->getBallotAccessStatus();
        
        \Log::warning('Ballot access denied during code verification', [
            'user_id' => $user_id,
            'nrna_id' => $auth_user->nrna_id,
            'denial_reason' => $accessStatus['error_type'],
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/BallotAccessDenied', [
            'error_type' => $accessStatus['error_type'],
            'error_title' => $accessStatus['error_title'],
            'error_message_nepali' => $accessStatus['error_message_nepali'],
            'error_message_english' => $accessStatus['error_message_english'],
            'user_name' => $auth_user->name,
        ]);
    }

    // ==========================================
    // PHASE 3: VOTING STATUS VALIDATIONS
    // ==========================================

    // Check if user has already voted
    if ($code->has_voted == 1 || $code->vote_submitted == 1) {
        \Log::info('Code verification attempted after voting completed', [
            'user_id' => $user_id,
            'has_voted' => $code->has_voted,
            'vote_submitted' => $code->vote_submitted,
            'client_ip' => $this->clientIP,
        ]);
        
        return Inertia::render('Vote/AlreadyVoted', [
            'user_name' => $auth_user->name,
            'message_nepali' => 'तपाईंले पहिले नै मतदान गरिसक्नुभएको छ।',
            'message_english' => 'You have already voted and cannot vote again.',
            'voted_at' => $code->vote_submitted_at ?? $code->updated_at,
        ]);
    }

    // Check if user already has active voting session
    if ($code->can_vote_now == 1) {
        \Log::info('Code verification with existing active session', [
            'user_id' => $user_id,
            'can_vote_now' => $code->can_vote_now,
            'client_ip' => $this->clientIP,
        ]);
        
        return redirect()->route('code.agreement')
            ->with('info', 'You already have an active voting session. Please continue with the voting process.');
    }

    // ==========================================
    // PHASE 4: CODE STATE VALIDATIONS
    // ==========================================

    // Check if code1 exists and was sent
    if (empty($code->code1) || !$code->has_code1_sent) {
        \Log::warning('Code verification attempted without valid code1', [
            'user_id' => $user_id,
            'code1_exists' => !empty($code->code1),
            'has_code1_sent' => $code->has_code1_sent,
            'client_ip' => $this->clientIP,
        ]);
        
        return redirect()->route('code.create')
            ->with('error', 'No valid verification code found. Please request a new code.');
    }

    // Check if code1 is still usable
    if (!$code->is_code1_usable) {
        \Log::warning('Code verification attempted with unusable code', [
            'user_id' => $user_id,
            'is_code1_usable' => $code->is_code1_usable,
            'client_ip' => $this->clientIP,
        ]);
        
        return redirect()->route('code.create')
            ->with('error', 'This verification code has already been used or is no longer valid. Please request a new code.');
    }

    // Check code1 expiration (20 minutes from sending)
    $minutesSinceSent = 0;
    if ($code->code1_sent_at) {
        $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
        $minutesSinceSent = now()->diffInMinutes($sentAt);
        
        if ($minutesSinceSent > 20) {
            \Log::warning('Code1 verification attempted after expiration', [
                'user_id' => $user_id,
                'nrna_id' => $auth_user->nrna_id,
                'code_sent_at' => $code->code1_sent_at,
                'minutes_since_sent' => $minutesSinceSent,
                'expiry_limit' => 20,
                'client_ip' => $this->clientIP,
            ]);

            // Reset expired code
            $code->update([
                'is_code1_usable' => 0,
                'can_vote_now' => 0,
            ]);

            return back()->withErrors([
                'voting_code' => 'This verification code has expired after 20 minutes. Please request a new code to continue.'
            ])->withInput();
        }
    }


 // ==========================================
// PHASE 5: IP SECURITY VALIDATIONS - FIXED
// ==========================================

// Check if current IP matches stored IP in Code model (primary check)
if ($code->client_ip && $code->client_ip !== $this->clientIP) {
    \Log::warning('Current IP does not match Code model IP', [
        'user_id' => $user_id,
        'current_ip' => $this->clientIP,
        'code_stored_ip' => $code->client_ip,
    ]);
    
    return Inertia::render('Vote/VoteDenied', [
        'denial_type' => 'IP Address Changed',
        'title_english' => 'Vote Denied - IP Address Mismatch',
        'title_nepali' => 'मतदान अस्वीकृत - IP एड्रेस मिलेन',
        'message_english' => 'Your current IP address does not match the IP address used when you started the voting process.',
        'message_nepali' => 'तपाईंको हालको IP एड्रेस मतदान प्रक्रिया सुरु गर्दाको IP एड्रेससँग मिलेन।',
        'solution_english' => 'Please return to your original network connection and try again.',
        'solution_nepali' => 'कृपया आफ्नो मूल नेटवर्क जडानमा फर्कनुहोस् र फेरि प्रयास गर्नुहोस्।',
        'user_name' => $auth_user->name,
        'current_ip' => $this->clientIP,
        'original_ip' => $code->client_ip,
    ]);
}

// Optional: Check User model IP if it exists (secondary check)
if (isset($auth_user->client_ip) && !empty($auth_user->client_ip)) {
    if ($auth_user->client_ip !== $this->clientIP) {
        \Log::warning('Current IP does not match User model IP', [
            'user_id' => $user_id,
            'current_ip' => $this->clientIP,
            'user_stored_ip' => $auth_user->client_ip,
        ]);
        
        return Inertia::render('Vote/VoteDenied', [
            'denial_type' => 'User IP Mismatch',
            'title_english' => 'Vote Denied - User IP Mismatch',
            'title_nepali' => 'मतदान अस्वीकृत - प्रयोगकर्ता IP मेल खाँदैन',
            'message_english' => 'Your IP address does not match your registered IP address.',
            'message_nepali' => 'तपाईंको IP एड्रेस तपाईंको दर्ता गरिएको IP एड्रेससँग मेल खाँदैन।',
            'user_name' => $auth_user->name,
        ]);
    }
} else {
    // Log that User model doesn't have client_ip but continue (not a blocking error)
    \Log::info('User model client_ip not set, using Code model IP for validation', [
        'user_id' => $user_id,
        'code_ip' => $code->client_ip,
        'current_ip' => $this->clientIP,
    ]);
}
    // ==========================================
    // PHASE 6: CODE VERIFICATION
    // ==========================================

    // Log verification attempt for audit trail
    \Log::info('Code1 verification attempt initiated', [
        'user_id' => $user_id,
        'nrna_id' => $auth_user->nrna_id,
        'minutes_since_sent' => $minutesSinceSent,
        'submitted_code_length' => strlen($submitted_code),
        'client_ip' => $this->clientIP,
        'attempted_at' => now(),
    ]);

    // ✅ CRITICAL: Verify submitted code matches stored hash
    if (!Hash::check($submitted_code, $code->code1)) {
        \Log::warning('❌ FAILED code1 verification - Hash mismatch', [
            'user_id' => $user_id,
            'nrna_id' => $auth_user->nrna_id,
            'submitted_code_length' => strlen($submitted_code),
            'submitted_first_char' => substr($submitted_code, 0, 1),
            'submitted_last_char' => substr($submitted_code, -1),
            'hash_length' => strlen($code->code1),
            'client_ip' => $this->clientIP,
            'attempted_at' => now(),
        ]);

        // ⚠️ STOP EXECUTION HERE - Code verification failed
        return back()->withErrors([
            'voting_code' => 'Invalid verification code. Please check your email and enter the exact code sent to you. / गलत प्रमाणीकरण कोड। कृपया आफ्नो इमेल जाँच गर्नुहोस् र तपाईंलाई पठाइएको सही कोड प्रविष्ट गर्नुहोस्।'
        ])->withInput();
    }
    
    // ==========================================
    // PHASE 7: SUCCESS HANDLING
    // ==========================================

    // ✅ CODE VERIFICATION SUCCESSFUL
    \Log::info('✅ Code1 verified successfully - All validations passed', [
        'user_id' => $user_id,
        'nrna_id' => $auth_user->nrna_id,
        'verification_duration_seconds' => microtime(true) - LARAVEL_START,
        'client_ip' => $this->clientIP,
        'verified_at' => now(),
    ]);


    try {
        // Update Code model - Enable voting session

        $updateResult = $code->update([
            'can_vote_now' => 1,                                        // ✅ Enable voting session
            'is_code1_usable' => 0,                                     // ✅ Mark code1 as used
            'code1_used_at' => now(),                                   // ✅ Record usage time
            'voting_time_in_minutes' =>$this->voting_time_in_minutes,     // ✅ Set 20-minute session
            'client_ip' => $this->clientIP,                              // ✅ Update current IP
            'updated_at' => now()                                        // ✅ Update timestamp
        ]);
           
        if (!$updateResult) {
            throw new \Exception('Failed to update code record after verification');
        }
  
        // Log successful database update
        \Log::info('Code record updated successfully after verification', [
            'user_id' => $user_id,
            'can_vote_now' => 1,
            'voting_time_minutes' => $this->voting_time_minutes,
            'updated_at' => now(),
        ]);
        // ✅ SUCCESS: Redirect to agreement page
        return redirect()->route('code.agreement')
            ->with('success', '✅ Code verified successfully! Please read and accept the voting agreement to continue. / कोड सफलतापूर्वक प्रमाणित! कृपया जारी राख्नको लागि मतदान सम्झौता पढ्नुहोस् र स्वीकार गर्नुहोस्।');

    } catch (\Exception $e) {
        // Handle database update failure
        \Log::error('❌ Failed to update code after successful verification', [
            'user_id' => $user_id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
        ]);

        return back()->withErrors([
            'voting_code' => 'System error occurred after code verification. Please try again or contact support.'
        ])->withInput();
    }
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
            ->with('error', 'Invalid voting session. Please start the voting process again.');
    }

    // Check if agreement already accepted (allow revisiting)
    if ($code->has_agreed_to_vote && $code->voting_started_at) {
        // User has already agreed, redirect to voting
        return redirect()->route('vote.create')
            ->with('info', 'You have already accepted the agreement. Continue voting.');
    }

    return Inertia::render('Code/Agreement', [
        'user_name' => $auth_user->name,
        'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
        'agreement_text_nepali' => 'म यो अनलाइन मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु र मेरो मत गोप्य राखिनेछ भन्ने कुरामा सहमत छु। म समय सीमा भित्र मतदान पूरा गर्नेछु र मतदान कोडहरू कसैसँग साझा गर्दिन।',
        'agreement_text_english' => 'I voluntarily participate in this online voting system and agree that my vote will remain secret and secure. I will complete voting within the time limit and will not share my voting codes with anyone.'
    ]);
}



/**
 * STEP 4: Process agreement submission with IP validation
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
            ->with('error', 'Invalid voting session. Please start the voting process again.');
    }

    // **CRITICAL IP VALIDATION CHECKS**
    $ipValidationResult = $this->validateClientIP($code, $auth_user);
    if ($ipValidationResult !== true) {
        return $ipValidationResult; // Returns redirect to denial page
    }

    // Validate agreement acceptance
    $request->validate([
        'agreement' => 'required|accepted'
    ], [
        'agreement.required' => 'You must accept the terms and conditions to proceed.',
        'agreement.accepted' => 'You must accept the terms and conditions to proceed.',
    ]);

    try {
        // **AGREEMENT ACCEPTED** - Update Code model and start voting session
        $code->has_agreed_to_vote = 1;
        $code->has_agreed_to_vote_at = now();        
        $code->voting_started_at = now();
        $code->updated_at = now();
        $saveResult = $code->save();

        if ($saveResult) {
            // Log agreement acceptance for audit trail
            \Log::info('Voting agreement accepted', [
                'user_id' => $auth_user->id,
                'nrna_id' => $auth_user->nrna_id,
                'client_ip' => $this->clientIP,
                'agreed_at' => now(),
                'voting_time_limit' => $code->voting_time_in_minutes,
            ]);

            // Redirect to actual voting page (VoteController)
            return redirect()->route('vote.create')
                ->with('success', 'Agreement accepted. You may now cast your vote.');
        } else {
            throw new \Exception('Failed to save agreement acceptance');
        }

    } catch (\Exception $e) {
        \Log::error('Failed to process agreement', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->withErrors([
            'agreement' => 'Failed to process agreement. Please try again.'
        ])->withInput();
    }
}
/**
 * Comprehensive IP validation for voting security
 *
 * @param  Code  $code
 * @param  User  $user
 * @return bool|Redirect
 */
private function validateClientIP($code, $user)
{
    // Check 1: IP must match the original IP from code generation
    if (!$this->validateIPMatch($code, $user)) {
        return $this->showVoteDeniedPage('ip_mismatch', $user);
    }

    // Check 2: IP rate limiting (max 7 votes per IP)
    if (!$this->validateIPRateLimit($user)) {
        return $this->showVoteDeniedPage('ip_rate_limit', $user);
    }

    return true; // All validations passed
}

/**
**
 * Check if current IP matches the stored IP in code record
 *
 * @param  Code  $code
 * @param  User  $user
 * @return bool
 */
private function validateIPMatch($code, $user)
{
    if ($code->client_ip && $code->client_ip !== $this->clientIP) {
        // Log IP mismatch for security monitoring
        \Log::warning('IP mismatch during agreement submission', [
            'user_id' => $user->id,
            'nrna_id' => $user->nrna_id,
            'original_ip' => $code->client_ip,
            'current_ip' => $this->clientIP,
            'code_sent_at' => $code->code1_sent_at,
            'attempted_at' => now(),
        ]);
        
        return false;
    }
    
    return true;
}

/**
 * Check IP rate limiting (max 7 votes per IP address)
 *
 * @param  User  $user
 * @return bool
 */
private function validateIPRateLimit($user)
{
    $votesFromIP = Code::where('client_ip', $this->clientIP)
        ->where('has_voted', 1)
        ->count();

    if ($votesFromIP >= 7) {
        // Log rate limit violation
        \Log::warning('IP rate limit exceeded during agreement submission', [
            'user_id' => $user->id,
            'nrna_id' => $user->nrna_id,
            'client_ip' => $this->clientIP,
            'votes_from_ip' => $votesFromIP,
            'attempted_at' => now(),
        ]);
        
        return false;
    }
    
    return true;
}


/**
 * Show vote denied page with specific reason
 *
 * @param  string  $reason
 * @param  User    $user
 * @return \Illuminate\Http\Response
 */
private function showVoteDeniedPage($reason, $user)
{
    $denialData = $this->getDenialData($reason, $user);
    
    return Inertia::render('Vote/VoteDenied', $denialData);
}


/**
 * Get denial page data based on reason
 *
 * @param  string  $reason
 * @param  User    $user
 * @return array
 */
private function getDenialData($reason, $user)
{
    $baseData = [
        'user_name' => $user->name,
        'nrna_id' => $user->nrna_id,
        'client_ip' => $this->clientIP,
        'denial_reason' => $reason,
    ];

    switch ($reason) {
        case 'ip_mismatch':
            return array_merge($baseData, [
                'denial_type' => 'IP Mismatch',
                'title_english' => 'Vote Denied - IP Address Mismatch',
                'title_nepali' => 'मतदान अस्वीकृत - आईपी एड्रेस मिलेन',
                'message_english' => 'Your current IP address does not match the IP address used when you started the voting process. For security reasons, voting must be completed from the same network connection.',
                'message_nepali' => 'तपाईंको हालको आईपी एड्रेस मतदान प्रक्रिया सुरु गर्दाको आईपी एड्रेससँग मिलेन। सुरक्षा कारणले, मतदान उही नेटवर्क जडानबाट पूरा गर्नुपर्छ।',
                'solution_english' => 'Please return to your original network connection and try again, or contact the election committee for assistance.',
                'solution_nepali' => 'कृपया आफ्नो मूल नेटवर्क जडानमा फर्कनुहोस् र फेरि प्रयास गर्नुहोस्, वा सहायताको लागि निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
            ]);

        case 'ip_rate_limit':
            $votesFromIP = Code::where('client_ip', $this->clientIP)
                ->where('has_voted', 1)
                ->count();
                
            return array_merge($baseData, [
                'denial_type' => 'IP Rate Limit Exceeded',
                'title_english' => 'Vote Denied - Too Many Votes from This IP',
                'title_nepali' => 'मतदान अस्वीकृत - यो आईपीबाट धेरै मतहरू',
                'message_english' => "This IP address ({$this->clientIP}) has already been used for {$votesFromIP} votes. Our security policy allows a maximum of 7 votes per IP address.",
                'message_nepali' => "यो आईपी एड्रेस ({$this->clientIP}) पहिले नै {$votesFromIP} मतहरूको लागि प्रयोग भएको छ। हाम्रो सुरक्षा नीतिले प्रति आईपी एड्रेस अधिकतम ७ मतहरूलाई अनुमति दिन्छ।",
                'solution_english' => 'Please vote from a different network connection, or contact the election committee if you believe this is an error.',
                'solution_nepali' => 'कृपया फरक नेटवर्क जडानबाट मतदान गर्नुहोस्, वा यदि तपाईंलाई लाग्छ कि यो त्रुटि हो भने निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
                'votes_from_ip' => $votesFromIP,
                'max_votes_allowed' => 7,
            ]);

        default:
            return array_merge($baseData, [
                'denial_type' => 'Access Denied',
                'title_english' => 'Vote Denied - Access Restricted',
                'title_nepali' => 'मतदान अस्वीकृत - पहुँच प्रतिबन्धित',
                'message_english' => 'Your voting access has been restricted due to security policies.',
                'message_nepali' => 'सुरक्षा नीतिहरूको कारणले तपाईंको मतदान पहुँच प्रतिबन्धित गरिएको छ।',
                'solution_english' => 'Please contact the election committee for assistance.',
                'solution_nepali' => 'कृपया सहायताको लागि निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
            ]);
    }
}

// Note: Update your existing checkIPRateLimit() method in constructor to use 7 instead of $this->max_use_clientIP
// and optionally redirect to denial page instead of aborting

/**
 * Get comprehensive IP statistics for debugging
 *
 * @return array
 */
public function getIPStatistics()
{
    $stats = [
        'current_ip' => $this->clientIP,
        'votes_from_ip' => Code::where('client_ip', $this->clientIP)->where('has_voted', 1)->count(),
        'total_codes_from_ip' => Code::where('client_ip', $this->clientIP)->count(),
        'users_from_ip' => Code::where('client_ip', $this->clientIP)->distinct('user_id')->count(),
        'max_allowed' => 7,
    ];
    
    return $stats;
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

     /**
 * Calculate time data for code expiration display
 * 
 * @param Code $code
 * @return array
 */
private function calculateTimeData($code)
{
    $totalDuration = 0;
    $remainingTime = $this->voting_time_in_minutes;
    
    if ($code->has_code1_sent && $code->code1_sent_at) {
        $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
        $totalDuration = now()->diffInMinutes($sentAt);
        $remainingTime = max(0, $this->voting_time_in_minutes - $totalDuration);
    }
    
    return [
        'total_duration' => $totalDuration,
        'remaining_time' => $remainingTime
    ];
}

/**
 * Generate random string for verification codes
 * 
 * @param int $length
 * @return string
 */
private function generateRandomString($length = 6)
{
    $characters = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}


/**
 * Additional voter validation beyond canAccessBallot()
 * Checks IP limits, voting session states, and other restrictions
 * 
 * @param User $user
 * @return array ['allowed' => bool, 'error_data' => array|null]
 */
public function isVoterValidated($user)
{
    // ==========================================
    // CHECK 1: IP RATE LIMITING  
    // ==========================================
    
    $votes_from_ip = Code::where('client_ip', $this->clientIP)
        ->where('has_voted', 1)
        ->count();

    if ($votes_from_ip >= $this->max_use_clientIP) {
        \Log::warning('IP rate limit exceeded during voter validation', [
            'user_id' => $user->id,
            'client_ip' => $this->clientIP,
            'votes_from_ip' => $votes_from_ip,
            'limit' => $this->max_use_clientIP,
        ]);
        
        return [
            'allowed' => false,
            'error_data' => [
                'error_type' => 'ip_rate_limit',
                'error_title' => 'IP Voting Limit Reached',
                'error_message_english' => "This IP address ({$this->clientIP}) has already been used for {$votes_from_ip} votes. Our security policy allows a maximum of {$this->max_use_clientIP} votes per IP address.",
                'error_message_nepali' => "यो IP एड्रेस ({$this->clientIP}) पहिले नै {$votes_from_ip} मतहरूको लागि प्रयोग भएको छ। हाम्रो सुरक्षा नीतिले प्रति IP एड्रेस अधिकतम {$this->max_use_clientIP} मतहरूलाई अनुमति दिन्छ।",
                'solution_english' => 'Please vote from a different network connection, or contact the election committee if you believe this is an error.',
                'solution_nepali' => 'कृपया फरक नेटवर्क जडानबाट मतदान गर्नुहोस्, वा यदि तपाईंलाई लाग्छ कि यो त्रुटि हो भने निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
                'votes_from_ip' => $votes_from_ip,
                'max_votes_allowed' => $this->max_use_clientIP,
                'current_ip' => $this->clientIP,
            ]
        ];
    }

    // ==========================================
    // CHECK 2: USER IP CONSISTENCY
    // ==========================================
    
    // Check if User model has stored IP and if it matches current IP
    if (isset($user->client_ip) && !empty($user->client_ip)) {
        if ($user->client_ip !== $this->clientIP) {
            \Log::warning('User IP mismatch during voter validation', [
                'user_id' => $user->id,
                'stored_ip' => $user->client_ip,
                'current_ip' => $this->clientIP,
            ]);
            
            return [
                'allowed' => false,
                'error_data' => [
                    'error_type' => 'user_ip_mismatch',
                    'error_title' => 'IP Address Changed',
                    'error_message_english' => 'Your current IP address does not match your registered IP address. For security reasons, you must vote from the same network connection.',
                    'error_message_nepali' => 'तपाईंको हालको IP एड्रेस तपाईंको दर्ता गरिएको IP एड्रेससँग मेल खाँदैन। सुरक्षा कारणले, तपाईंले उही नेटवर्क जडानबाट मतदान गर्नुपर्छ।',
                    'solution_english' => 'Please return to your original network connection and try again.',
                    'solution_nepali' => 'कृपया आफ्नो मूल नेटवर्क जडानमा फर्कनुहोस् र फेरि प्रयास गर्नुहोस्।',
                    'current_ip' => $this->clientIP,
                    'registered_ip' => $user->client_ip,
                ]
            ];
        }
    }

    // ==========================================
    // CHECK 3: ACCOUNT STATUS VALIDATIONS
    // ==========================================
    
    // Check if user account is suspended
    if (isset($user->is_suspended) && $user->is_suspended == 1) {
        \Log::warning('Suspended user attempted voting', [
            'user_id' => $user->id,
            'nrna_id' => $user->nrna_id ?? null,
        ]);
        
        return [
            'allowed' => false,
            'error_data' => [
                'error_type' => 'account_suspended',
                'error_title' => 'Account Suspended',
                'error_message_english' => 'Your account has been suspended and you cannot vote at this time.',
                'error_message_nepali' => 'तपाईंको खाता निलम्बित गरिएको छ र तपाईं यस समयमा मतदान गर्न सक्नुहुन्न।',
                'solution_english' => 'Please contact the election committee for assistance.',
                'solution_nepali' => 'कृपया सहायताको लागि निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
            ]
        ];
    }

    // Check if user is banned
    if (isset($user->is_banned) && $user->is_banned == 1) {
        \Log::warning('Banned user attempted voting', [
            'user_id' => $user->id,
            'nrna_id' => $user->nrna_id ?? null,
        ]);
        
        return [
            'allowed' => false,
            'error_data' => [
                'error_type' => 'account_banned',
                'error_title' => 'Account Banned',
                'error_message_english' => 'Your account has been banned from participating in this election.',
                'error_message_nepali' => 'तपाईंको खातालाई यस चुनावमा भाग लिनबाट प्रतिबन्धित गरिएको छ।',
                'solution_english' => 'Please contact the election committee if you believe this is an error.',
                'solution_nepali' => 'यदि तपाईंलाई लाग्छ कि यो त्रुटि हो भने कृपया निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
            ]
        ];
    }

    // ==========================================
    // CHECK 4: VOTING SESSION CONFLICTS
    // ==========================================
    
    // Check for any active voting sessions from different IPs for this user
    $active_sessions = Code::where('user_id', $user->id)
        ->where('can_vote_now', 1)
        ->where('client_ip', '!=', $this->clientIP)
        ->count();

    if ($active_sessions > 0) {
        \Log::warning('Multiple active voting sessions detected', [
            'user_id' => $user->id,
            'current_ip' => $this->clientIP,
            'active_sessions' => $active_sessions,
        ]);
        
        return [
            'allowed' => false,
            'error_data' => [
                'error_type' => 'multiple_sessions',
                'error_title' => 'Multiple Voting Sessions',
                'error_message_english' => 'You have an active voting session from another location. Only one voting session is allowed at a time.',
                'error_message_nepali' => 'तपाईंको अर्को स्थानबाट सक्रिय मतदान सत्र छ। एक पटकमा केवल एक मतदान सत्रलाई अनुमति दिइन्छ।',
                'solution_english' => 'Please complete your voting from your original location, or contact the election committee.',
                'solution_nepali' => 'कृपया आफ्नो मूल स्थानबाट मतदान पूरा गर्नुहोस्, वा निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।',
                'current_ip' => $this->clientIP,
            ]
        ];
    }

    // ==========================================
    // CHECK 5: TIME-BASED RESTRICTIONS
    // ==========================================
    
    // Check if it's within voting hours (if configured)
    $voting_start = config('election.voting_start_time');
    $voting_end = config('election.voting_end_time');
    
    if ($voting_start && $voting_end) {
        $current_time = now();
        $start_time = \Carbon\Carbon::parse($voting_start);
        $end_time = \Carbon\Carbon::parse($voting_end);
        
        if (!$current_time->between($start_time, $end_time)) {
            \Log::warning('Voting attempted outside allowed hours', [
                'user_id' => $user->id,
                'current_time' => $current_time,
                'voting_start' => $start_time,
                'voting_end' => $end_time,
            ]);
            
            return [
                'allowed' => false,
                'error_data' => [
                    'error_type' => 'outside_voting_hours',
                    'error_title' => 'Outside Voting Hours',
                    'error_message_english' => "Voting is only allowed between {$start_time->format('H:i')} and {$end_time->format('H:i')}.",
                    'error_message_nepali' => "मतदान केवल {$start_time->format('H:i')} र {$end_time->format('H:i')} बीच मात्र अनुमति छ।",
                    'solution_english' => 'Please return during the allowed voting hours.',
                    'solution_nepali' => 'कृपया अनुमतित मतदान समयमा फर्कनुहोस्।',
                    'voting_start' => $start_time->format('H:i'),
                    'voting_end' => $end_time->format('H:i'),
                ]
            ];
        }
    }

    // ==========================================
    // ALL VALIDATIONS PASSED
    // ==========================================
    
    \Log::info('Voter validation passed', [
        'user_id' => $user->id,
        'client_ip' => $this->clientIP,
        'votes_from_ip' => $votes_from_ip,
        'validated_at' => now(),
    ]);

    return [
        'allowed' => true,
        'error_data' => null
    ];
}
/**
 * Validate existing code model according to specifications
 * When is code_model valid: is_codemodel_valid = true
 * 
 * @param Code $code
 * @param User $auth_user
 * @return bool|Response
 */
private function validateExistingCodeModel($code, $auth_user)
{
    // a) Check if user has already voted
    if ($code->has_voted == 1) {
        \Log::info('User has already voted', [
            'user_id' => $auth_user->id,
            'has_voted' => $code->has_voted,
            'vote_submitted_at' => $code->vote_submitted_at ?? 'null',
        ]);
        
        return Inertia::render('Vote/AlreadyVoted', [
            'user_name' => $auth_user->name,
            'message_nepali' => 'तपाईंले पहिले नै मतदान गरिसक्नुभएको छ। तपाईं फेरि मतदान गर्न सक्नुहुन्न।',
            'message_english' => 'You have already voted and cannot vote again.',
            'voted_at' => $code->vote_submitted_at ?? $code->updated_at,
        ]);
    }

    // b) Check if code2 is already set (advanced stage)
    if ($code->has_code2_sent == 1) {
        \Log::warning('User in code2 stage trying to restart', [
            'user_id' => $auth_user->id,
            'has_code2_sent' => $code->has_code2_sent,
            'code2_sent_at' => $code->code2_sent_at ?? 'null',
        ]);
        
        return redirect()->route('vote.finalize')
            ->with('info', 'You are in the final voting stage. Please complete your vote submission with the code sent to your email.');
    }

    // c) Check time from code1_sent_at till now
    if ($code->has_code1_sent == 1 && $code->code1_sent_at) {
        $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
        $minutesSinceSent = now()->diffInMinutes($sentAt);
        
        \Log::info('Code time validation', [
            'minutes_since_sent' => $minutesSinceSent,
            'time_limit' => $this->voting_time_in_minutes,
            'code1_sent_at' => $code->code1_sent_at,
            'is_expired' => $minutesSinceSent > $this->voting_time_in_minutes,
        ]);
        
        if ($minutesSinceSent > $this->voting_time_in_minutes) {
            // Code expired - contact administrator
            return Inertia::render('Vote/VoteDenied', [
                'denial_type' => 'Code Expired',
                'title_english' => 'Verification Code Expired',
                'title_nepali' => 'प्रमाणीकरण कोड समाप्त भयो',
                'error_message_english' => "Your verification code has expired. It was valid for {$this->voting_time_in_minutes} minutes only.",
                'error_message_nepali' => "तपाईंको प्रमाणीकरण कोड समाप्त भएको छ। यो केवल {$this->voting_time_in_minutes} मिनेटको लागि मात्र वैध थियो।",
                'solution_english' => 'Please contact the election administrator for assistance with a new verification code.',
                'solution_nepali' => 'कृपया नयाँ प्रमाणीकरण कोडको साथ सहायताको लागि निर्वाचन प्रशासकलाई सम्पर्क गर्नुहोस्।',
                'user_name' => $auth_user->name,
                'expired_minutes' => $minutesSinceSent,
                'time_limit' => $this->voting_time_in_minutes,
                'code_sent_at' => $code->code1_sent_at,
            ]);
        }
    }

    // d) Check if is_code1_usable = true
    if ($code->has_code1_sent == 1 && $code->is_code1_usable != 1) {
        \Log::warning('Code1 not usable', [
            'user_id' => $auth_user->id,
            'is_code1_usable' => $code->is_code1_usable,
            'has_code1_sent' => $code->has_code1_sent,
        ]);
        
        return redirect()->route('code.agreement')
            ->with('info', 'Your verification code has already been used. Please continue with the voting process.');
    }

    // Set code model as valid if all checks pass
    if ($code->is_codemodel_valid != true) {
        $code->update(['is_codemodel_valid' => true]);
        
        \Log::info('Code model marked as valid', [
            'user_id' => $auth_user->id,
            'code_id' => $code->id,
        ]);
    }

    return true; // Code is valid
}

/**
 * Create new code record with proper initialization
 * 
 * @param User $auth_user
 * @return Code
 */
private function createNewCodeRecord($auth_user)
{
    $code = Code::create([
        'user_id' => $auth_user->id,
        'client_ip' => $this->clientIP,
        'voting_time_in_minutes' => $this->voting_time_in_minutes,
        
        // Code states
        'is_code1_usable' => 0,
        'is_code2_usable' => 0,
        'is_code3_usable' => 0,
        'is_code4_usable' => 0,
        
        // Sending states  
        'has_code1_sent' => 0,
        'has_code2_sent' => 0,
        
        // Voting states
        'can_vote_now' => 0,
        'has_voted' => 0,
        'vote_submitted' => 0,
        
        // Validation state
        'is_codemodel_valid' => false,
        
        // Timestamps
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    \Log::info('New code record created with proper initialization', [
        'code_id' => $code->id,
        'user_id' => $auth_user->id,
        'client_ip' => $this->clientIP,
    ]);
    
    return $code;
}

/**
 * Generate and send verification code (ONLY ONCE)
 * Set required fields when sending email
 * 
 * @param Code $code
 * @param User $auth_user
 * @return array
 */
private function generateAndSendCode($code, $auth_user)
{
    try {
        DB::beginTransaction();
        
        // Generate random 6-character code
        $form_opening_code = $this->generateRandomString(6);
        
        \Log::info('Generated new verification code', [
            'code_length' => strlen($form_opening_code),
            'user_id' => $auth_user->id,
            'generated_at' => now(),
        ]);
        
        // When sending email, set the following (as per specifications):
        $updateData = [
            // 1) save code1 as hashed
            'code1' => Hash::make($form_opening_code),
            
            // 2) set is_code1_usable: 1 
            'is_code1_usable' => 1,
            
            // 3) has_code1_sent: 1
            'has_code1_sent' => 1,
            
            // 4) save client_ip
            'client_ip' => $this->clientIP,
            
            // 5) code1_sent_at = now()
            'code1_sent_at' => now(),
            
            // 6) set voting_time_in_minutes
            'voting_time_in_minutes' => $this->voting_time_in_minutes,
            
            // 7) set is_codemodel_valid = true (as per specifications)
            'is_codemodel_valid' => true,
            
            // Update timestamp
            'updated_at' => now(),
        ];
        
        $updateResult = $code->update($updateData);
        
        if (!$updateResult) {
            throw new \Exception('Failed to update code record');
        }
        
        // Send notification
        $auth_user->notify(new SendFirstVerificationCode($auth_user, $form_opening_code));
        
        DB::commit();
        
        // Log successful operation
        \Log::info('✅ Code1 generated and sent successfully', [
            'user_id' => $auth_user->id,
            'nrna_id' => $auth_user->nrna_id ?? null,
            'client_ip' => $this->clientIP,
            'sent_at' => now(),
            'voting_time_minutes' => $this->voting_time_in_minutes,
            'is_codemodel_valid' => true,
        ]);
        
        return ['success' => true];
        
    } catch (\Exception $e) {
        DB::rollback();
        
        \Log::error('❌ Failed to generate and send code', [
            'user_id' => $auth_user->id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
        ]);
        
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}


}
