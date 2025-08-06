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
 * Generate and send code1 via email (ONLY ONCE)
 *
 * @return \Illuminate\Http\Response
 */

/**
 * STEP 1: Initial ballot access request
 * Generate and send code1 via email (ONLY ONCE) - DEBUGGED VERSION
 *
 * @return \Illuminate\Http\Response
 */
public function create()
{
    $auth_user = auth()->user();
    
    // DEBUG: Log the start of the method
    \Log::info('Create method started', [
        'user_id' => $auth_user->id,
        'nrna_id' => $auth_user->nrna_id,
        'client_ip' => $this->clientIP,
    ]);

    // 1. Check if user is allowed to vote
    if (!$auth_user->canAccessBallot()) {
        $accessStatus = $auth_user->getBallotAccessStatus();

        return Inertia::render('Vote/BallotAccessDenied', [
            'error_type' => $accessStatus['error_type'],
            'error_title' => $accessStatus['error_title'],
            'error_message_nepali' => $accessStatus['error_message_nepali'],
            'error_message_english' => $accessStatus['error_message_english'],
            'user_name' => $auth_user->name,
        ]);
    }

    // 2. Look for user's voting record, or create it if not exists
    $code = Code::where('user_id', $auth_user->id)->first();
    
    // DEBUG: Log current code state
    \Log::info('Current code state', [
        'code_exists' => $code ? 'yes' : 'no',
        'has_code1_sent' => $code ? $code->has_code1_sent : 'n/a',
        'code1_sent_at' => $code ? $code->code1_sent_at : 'n/a',
        'is_code1_usable' => $code ? $code->is_code1_usable : 'n/a',
    ]);
    
    if (!$code) {
        // Create new voting record
        $code = Code::create([
            'user_id' => $auth_user->id,
            'client_ip' => $this->clientIP,
            'voting_time_in_minutes' => 20,
            'is_code1_usable' => 0,
            'has_code1_sent' => 0,
            'can_vote_now' => 0,
            'has_voted' => 0,
            'vote_submitted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \Log::info('New code record created', ['code_id' => $code->id]);
    }

    // 3. Check if user has already voted
    if ($code->has_voted == 1 || $code->vote_submitted == 1) {
        return Inertia::render('Vote/AlreadyVoted', [
            'user_name' => $auth_user->name,
            'message_nepali' => 'तपाईंले पहिले नै मतदान गरिसक्नुभएको छ।',
            'message_english' => 'You have already voted and cannot vote again.',
            'voted_at' => $code->vote_submitted_at,
        ]);
    }

    // 4. CRITICAL CHECK: Send code only if has_code1_sent is false (only once)
    if ($code->has_code1_sent == 0) {
        \Log::info('Generating new code1 because has_code1_sent is 0');
        
        $form_opening_code = $this->generateRandomString(6);
        
        // DEBUG: Log the code being generated
        \Log::info('Generated code1', [
            'code_length' => strlen($form_opening_code),
            'code_value' => $form_opening_code, // Remove this in production!
        ]);
        
        // Use DB transaction to ensure atomicity
        try {
            DB::beginTransaction();
            
            // Update code record when sending email (as per requirements)
            $updateResult = $code->update([
                'code1' => Hash::make($form_opening_code),        // 1) save code1 as hashed
                'is_code1_usable' => 1,                          // 2) set is_code1_usable: 1
                'has_code1_sent' => 1,                           // 3) has_code1_sent: 1
                'client_ip' => $this->clientIP,                  // 4) save client_ip
                'code1_sent_at' => now(),                        // 5) code1_sent_at = now()
                'voting_time_in_minutes' => 20,                  // 6) set voting_time_in_minutes: 20
                'updated_at' => now(),
            ]);
            
            // DEBUG: Check if update was successful
            \Log::info('Update result', [
                'update_result' => $updateResult ? 'success' : 'failed',
                'code_id' => $code->id,
            ]);
            
            // Refresh the model to get latest data from database
            $code = $code->fresh();
            
            // DEBUG: Log state after update
            \Log::info('Code state after update', [
                'has_code1_sent' => $code->has_code1_sent,
                'code1_sent_at' => $code->code1_sent_at,
                'is_code1_usable' => $code->is_code1_usable,
                'code1_exists' => !empty($code->code1) ? 'yes' : 'no',
            ]);
            
            // 7) Send notification
            $auth_user->notify(new SendFirstVerificationCode($auth_user, $form_opening_code));
            
            DB::commit();
            
            // Log the successful code generation for audit trail
            \Log::info('Code1 generated and sent successfully', [
                'user_id' => $auth_user->id,
                'nrna_id' => $auth_user->nrna_id,
                'client_ip' => $this->clientIP,
                'sent_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Failed to generate and send code1', [
                'user_id' => $auth_user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'Failed to send verification code. Please try again.');
        }
        
    } else {
        \Log::info('Code1 already sent, skipping generation', [
            'has_code1_sent' => $code->has_code1_sent,
            'code1_sent_at' => $code->code1_sent_at,
        ]);
    }

    // Calculate time since code was sent (if it was sent)
    $totalDuration = 0;
    if ($code->has_code1_sent && $code->code1_sent_at) {
        $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
        $totalDuration = now()->diffInMinutes($sentAt);
    }
    
    // DEBUG: Log final state before rendering
    \Log::info('Final state before rendering', [
        'total_duration' => $totalDuration,
        'remaining_time' => max(0, 20 - $totalDuration),
        'code_already_sent' => $code->has_code1_sent,
    ]);

    // 5. Show page to enter verification code
    return Inertia::render('Vote/CreateCode', [
        'user_name' => $auth_user->name,
        'nrna_id' => $auth_user->nrna_id,
        'state' => $auth_user->state,
        'code_duration' => $totalDuration,
        'code_expires_in' => 20, // Code1 expires in 20 minutes
        'instructions_nepali' => 'तपाईंको इमेलमा भर्खै एउटा कोड पठाइएको छ। कृपया त्यो कोड यहाँ प्रविष्ट गर्नुहोस्।',
        'instructions_english' => 'A verification code has been sent to your email. Please enter the code here.',
        'remaining_time' => max(0, 20 - $totalDuration), // Minutes remaining (20 minutes total)
        'code_already_sent' => $code->has_code1_sent, // Let frontend know if code was already sent
    ]);
}
/**
 * Helper method to generate random string for codes
 * 
 * @param int $length
 * @return string
 */
private function generateRandomString($length = 6)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
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
    if ($code->has_voted == 1 || $code->vote_submitted == 1) {
        return redirect()->route('vote.show')
            ->with('info', 'You have already completed voting.');
    }

    // Check if user already has active voting session
    if ($code->can_vote_now == 1) {
        return redirect()->route('code.agreement')
            ->with('info', 'You already have an active voting session.');
    }
    
    // Validate the submitted code
    $request->validate([
        'voting_code' => 'required|string|min:6|max:6'
    ], [
        'voting_code.required' => 'Please enter the verification code.',
        'voting_code.min' => 'Code must be exactly 6 characters.',
        'voting_code.max' => 'Code must be exactly 6 characters.',
    ]);

    $submitted_code = trim(strtoupper($request->input('voting_code'))); 
    // Check if code1 exists and was sent
    if (empty($code->code1) || !$code->has_code1_sent) {
        return redirect()->route('code.create')
            ->with('error', 'No verification code found. Please request a new code.');
    }

    // Check if code1 is still usable
    if (!$code->is_code1_usable) {
        return redirect()->route('code.create')
            ->with('error', 'This verification code has already been used. Please request a new code.');
    }
    

    // Check if code1 has expired (20 minutes from sending)
    $minutesSinceSent = 0;
    if ($code->code1_sent_at) {
        $sentAt = \Carbon\Carbon::parse($code->code1_sent_at);
        $minutesSinceSent = now()->diffInMinutes($sentAt);
        
        if ($minutesSinceSent > 20) { // Code1 expires after 20 minutes
            // Log expired code attempt for audit trail
            \Log::warning('Code1 verification attempted after expiration', [
                'user_id' => $auth_user->id,
                'nrna_id' => $auth_user->nrna_id,
                'code_sent_at' => $code->code1_sent_at,
                'minutes_since_sent' => $minutesSinceSent,
                'client_ip' => $this->clientIP,
                'attempted_at' => now(),
            ]);

            return back()->withErrors([
                'voting_code' => 'This verification code has expired after 20 minutes. Please contact the election committee for assistance.'
            ])->withInput();
        }
    }

    // Verify the IP address matches (security check)
    if ($code->client_ip && $code->client_ip !== $this->clientIP) {
        \Log::warning('IP mismatch during code verification', [
            'user_id' => $auth_user->id,
            'original_ip' => $code->client_ip,
            'current_ip' => $this->clientIP,
        ]);
        
        return back()->withErrors([
            'voting_code' => 'Security error: IP address mismatch. Please request a new code.'
        ])->withInput();
    }

    // Log the verification attempt for audit trail
    \Log::info('Code1 verification attempt', [
        'user_id' => $auth_user->id,
        'nrna_id' => $auth_user->nrna_id,
        'minutes_since_sent' => $minutesSinceSent,
        'client_ip' => $this->clientIP,
        'attempted_at' => now(),
    ]);

    // REMOVED: dd() statement that was causing issues
    // **VERIFY CODE1**: Check if submitted code matches stored hash
    if (!Hash::check($submitted_code, $code->code1)) {
        // Log failed attempt for security monitoring
        \Log::warning('Failed code1 verification attempt', [
            'user_id' => $auth_user->id,
            'nrna_id' => $auth_user->nrna_id,
            'client_ip' => $this->clientIP,
            'attempted_at' => now(),
        ]);

        return back()->withErrors([
            'voting_code' => 'Invalid verification code. Please check your email and try again.'
        ])->withInput();
    }

    // **CODE1 VERIFIED SUCCESSFULLY** - Update Code model as per architecture
    $code->update([
        'can_vote_now' => 1,                  // Enable voting session (user can now vote)
        'is_code1_usable' => 0,               // Code1 used, no longer valid
        'code1_used_at' => now(),             // Track when code1 was used
        'voting_time_in_minutes' => 20,       // Set 20-minute voting session (corrected from 15)
        'client_ip' => $this->clientIP,       // Update IP for audit
        'updated_at' => now()
    ]);

    // Log successful verification for audit trail
    \Log::info('Code1 verified successfully', [
        'user_id' => $auth_user->id,
        'nrna_id' => $auth_user->nrna_id,
        'client_ip' => $this->clientIP,
        'verified_at' => now(),
    ]);

    // Redirect to agreement page (Step 3)
    return redirect()->route('code.agreement')
        ->with('success', 'Verification successful. Please read and accept the voting agreement.');
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
 * Enhanced IP rate limiting check for constructor
 */
private function checkIPRateLimit()
{
    $votes_from_ip = Code::where('client_ip', $this->clientIP)
        ->where('has_voted', 1)
        ->count();

    if ($votes_from_ip >= 7) {
        // Instead of aborting, redirect to denial page
        $error_data = $this->getDenialData('ip_rate_limit', auth()->user());
        
        return Inertia::render('Vote/VoteDenied', $error_data);
    }
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
