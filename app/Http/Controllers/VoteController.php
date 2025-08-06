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
    public $session_name;

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
    public function create1()
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
   
        
         $can_vote_now   =$code->can_vote_now;
         $code           =$auth_user->code;
         if($code){
            $has_voted      = $code->has_voted;
            //  dd($code->is_code1_usable); 
            //  $this->vote_pre_check($code);
             $return_to =$this->vote_pre_check($code);
           
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
        ->allowedSorts(['is_national_wide', 'state_name', 'required_number'])
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




public function create(Request $request)
{
    $auth_user = $request->user();
    $code = $auth_user->code;

    // --- Fetch National Posts and Candidates ---
    $national_posts = QueryBuilder::for(Post::with(['candidates' => function($query) {
        $query->join('users', 'users.user_id', '=', 'candidacies.user_id')
              ->select('candidacies.*', 'users.name as user_name');
    }]))
    ->where('is_national_wide', 1)
    ->orderBy('post_id')
    ->get()
    ->map(function ($post) {
        return [
            'post_id' => $post->post_id,
            'name' => $post->name,
            'nepali_name' => $post->nepali_name,
            'required_number' => $post->required_number,
            'candidates' => $post->candidates->map(function ($c) {
                return [
                    'candidacy_id' => $c->candidacy_id,
                    'user' => [
                        'id' => $c->user_id,
                        'name' => $c->user_name, // Now using the joined user name
                    ],
                    'post_id' => $c->post_id,
                    'image_path_1' => $c->image_path_1,
                    'candidacy_name' => $c->candidacy_name,
                    'proposer_name' => $c->proposer_name,
                    'supporter_name' => $c->supporter_name,
                ];
            })->values(),
        ];
    })->values();

    // Similarly update the regional posts query
    $regional_posts = collect();
    if (!empty($auth_user->region)) {
        $regional_posts = QueryBuilder::for(Post::with(['candidates' => function($query) {
            $query->join('users', 'users.id', '=', 'candidacies.user_id')
                  ->select('candidacies.*', 'users.name as user_name');
        }]))
        ->where('is_national_wide', 0)
        ->where('state_name', trim($auth_user->region))
        ->orderBy('post_id')
        ->get()
        ->map(function ($post) {
            return [
                'post_id' => $post->post_id,
                'name' => $post->name,
                'nepali_name' => $post->nepali_name,
                'required_number' => $post->required_number,
                'candidates' => $post->candidates->map(function ($c) {
                    return [
                        'candidacy_id' => $c->candidacy_id,
                        'user' => [
                            'id' => $c->user_id,
                            'name' => $c->user_name,
                        ],
                        'post_id' => $c->post_id,
                        'image_path_1' => $c->image_path_1,
                        'candidacy_name' => $c->candidacy_name,
                        'proposer_name' => $c->proposer_name,
                        'supporter_name' => $c->supporter_name,
                    ];
                })->values(),
            ];
        })->values();
    }

    return Inertia::render('Vote/CreateVotingPage', [
        'national_posts' => $national_posts,
        'regional_posts' => $regional_posts,
        'user_name' => $auth_user->name,
        'user_id' => $auth_user->id,
        'user_region' => $auth_user->region,
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



/**
 * STEP 6: Handle the submission of candidate selections
 * Process vote data, validate selections, send second verification code,
 * and redirect to verification page
 */
public function second_submission(Request $request)
{
    DB::beginTransaction();
    
    try {
        $auth_user = auth()->user();
        
        // Basic authentication check
        if (!$auth_user) {
            Log::error('Second submission attempted without authentication');
            return redirect()->route('dashboard')
                ->withErrors(['auth' => 'Authentication required. Please log in again.']);
        }

        $code = $auth_user->code;
        
        // Check if user has a code record
        if (!$code) {
            Log::error('Second submission attempted without code record', ['user_id' => $auth_user->id]);
            return redirect()->route('code.create')
                ->withErrors(['code' => 'Voting code not found. Please start the voting process again.']);
        }

        // Pre-submission timing and eligibility checks
        $pre_check_result = $this->vote_pre_check($code);
        if ($pre_check_result && $pre_check_result !== "") {
            Log::warning('Pre-check failed during second submission', [
                'user_id' => $auth_user->id,
                'redirect_to' => $pre_check_result
            ]);
            
            if ($pre_check_result === '404') {
                abort(404, 'Voting session expired or invalid');
            }
            return redirect()->route($pre_check_result);
        }

        // Validate request structure
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'national_selected_candidates' => 'nullable|array',
            'regional_selected_candidates' => 'nullable|array', 
            'agree_button' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            Log::warning('Second submission validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify user ID matches authenticated user
        $submitted_user_id = $request->input('user_id');
        if ((int)$submitted_user_id !== (int)$auth_user->id) {
            Log::error('User ID mismatch in second submission', [
                'auth_user_id' => $auth_user->id,
                'submitted_user_id' => $submitted_user_id
            ]);
            
            return redirect()->back()
                ->withErrors(['user_id' => 'User verification failed. Please try again.'])
                ->withInput();
        }

        // Enhanced eligibility verification
        $eligibility_errors = $this->validateVotingEligibility($auth_user, $code);
        if (!empty($eligibility_errors)) {
            return redirect()->route('dashboard')
                ->withErrors($eligibility_errors);
        }

        // Get and validate vote data
        $vote_data = $request->all();
        $validation_errors = $this->validate_candidate_selections($vote_data);
        
        if (!empty($validation_errors)) {
            Log::warning('Vote selection validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $validation_errors
            ]);
            
            return redirect()->back()
                ->withErrors($validation_errors)
                ->withInput();
        }

        // Additional vote integrity checks
        $integrity_errors = $this->validateVoteIntegrity($vote_data, $auth_user);
        if (!empty($integrity_errors)) {
            Log::error('Vote integrity validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $integrity_errors
            ]);
            
            return redirect()->back()
                ->withErrors($integrity_errors)
                ->withInput();
        }

        // Update submission status
        $code->vote_submitted = 1;
        $code->vote_submitted_at = Carbon::now();
         $code->session_name = 'vote_' . $code->id."_". auth()->id();

        $code->save();

        // Send second verification code with error handling
        $code_result = $this->send_second_voting_code($code, $auth_user);
        
        if (isset($code_result['error'])) {
            Log::error('Failed to send second verification code', [
                'user_id' => $auth_user->id,
                'error' => $code_result['error']
            ]);
            
            return redirect()->back()
                ->withErrors(['code' => 'Failed to send verification code. Please try again.'])
                ->withInput();
        }

        $totalDuration = $code_result['duration'] ?? 0;

        // Prepare comprehensive session data
        $session_data = $this->prepareSessionData($vote_data, $auth_user, $totalDuration, $code);
        
        // Store in session with error handling
        try {
              
            $session_name =$code->session_name;

            $request->session()->put($session_name, $session_data);
            
            
            // Verify session storage
            $stored_data = $request->session()->get($session_name);
            if (!$stored_data) {
                throw new \Exception('Session storage verification failed');
            }
            $code->senssion_name=$session_name;
            
        } catch (\Exception $e) {
            Log::error('Session storage failed during vote submission', [
                'user_id' => $auth_user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withErrors(['session' => 'Failed to store vote data. Please try again.'])
                ->withInput();
        }

        // Log successful submission with detailed info
        Log::info('Vote second submission completed successfully', [
            'user_id' => $auth_user->id,
            'user_name' => $auth_user->name,
            'total_duration' => $totalDuration,
            'national_posts_count' => count($vote_data['national_selected_candidates'] ?? []),
            'regional_posts_count' => count($vote_data['regional_selected_candidates'] ?? []),
            'session_id' => $request->session()->getId(),
            'timestamp' => Carbon::now()->toISOString()
        ]);

        DB::commit();

        // Redirect to verification with success message
        return redirect()->route('vote.verify')
            ->with([
                'totalDuration' => $totalDuration,
                'code_expires_in' => $code->voting_time_in_minutes ?? 15,
                'success' => 'Vote submitted successfully. Please check your email for verification code.'
            ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        
        // Comprehensive error logging
        Log::error('Second submission failed with exception', [
            'user_id' => auth()->id(),
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'stack_trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['_token'])
        ]);

        return redirect()->back()
            ->withErrors(['submission' => 'An error occurred while processing your vote. Please try again.'])
            ->withInput();
    }
}

/**
 * Enhanced voting eligibility validation
 */
private function validateVotingEligibility($auth_user, $code)
{
    $errors = [];
    
    if (!$code->can_vote_now) {
        $errors['eligibility'] = 'Voting is not currently available for you.';
    }
    
    if ($code->has_voted) {
        $errors['already_voted'] = 'You have already completed your vote.';
    }
    
    if (!$code->is_code1_usable && $code->vote_submitted) {
        // Check if we're in the valid submission window
        $submission_window = $code->voting_time_in_minutes ?? 15;
        $elapsed = Carbon::now()->diffInMinutes($code->code1_used_at);
        
        if ($elapsed > $submission_window) {
            $errors['expired'] = 'Your voting session has expired. Please start again.';
        }
    }
    
    return $errors;
}

/**
 * Validate vote data integrity against available posts and candidates
 */
private function validateVoteIntegrity($vote_data, $auth_user)
{
    $errors = [];
    
    try {
        // Get available posts for verification
        $available_national_posts = Post::where('is_national_wide', 1)->pluck('post_id')->toArray();
        $available_regional_posts = Post::where('is_national_wide', 0)
            ->where('state_name', trim($auth_user->region))
            ->pluck('post_id')->toArray();
        
        // Validate national selections
        foreach ($vote_data['national_selected_candidates'] ?? [] as $index => $selection) {
            if ($selection && !$selection['no_vote']) {
                $post_id = $selection['post_id'] ?? null;
                
                if (!in_array($post_id, $available_national_posts)) {
                    $errors["national_integrity_{$index}"] = "Invalid national post selection detected.";
                    continue;
                }
                
                // Validate candidates exist for this post
                foreach ($selection['candidates'] ?? [] as $candidate) {
                    $candidacy_exists = Candidacy::where('candidacy_id', $candidate['candidacy_id'])
                        ->where('post_id', $post_id)
                        ->exists();
                    
                    if (!$candidacy_exists) {
                        $errors["national_candidate_{$index}"] = "Invalid candidate selection detected.";
                    }
                }
            }
        }
        
        // Validate regional selections
        foreach ($vote_data['regional_selected_candidates'] ?? [] as $index => $selection) {
            if ($selection && !$selection['no_vote']) {
                $post_id = $selection['post_id'] ?? null;
                
                if (!in_array($post_id, $available_regional_posts)) {
                    $errors["regional_integrity_{$index}"] = "Invalid regional post selection detected.";
                    continue;
                }
                
                // Validate candidates exist for this post
                foreach ($selection['candidates'] ?? [] as $candidate) {
                    $candidacy_exists = Candidacy::where('candidacy_id', $candidate['candidacy_id'])
                        ->where('post_id', $post_id)
                        ->exists();
                    
                    if (!$candidacy_exists) {
                        $errors["regional_candidate_{$index}"] = "Invalid candidate selection detected.";
                    }
                }
            }
        }
        
    } catch (\Exception $e) {
        Log::error('Vote integrity validation error', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage()
        ]);
        $errors['integrity'] = 'Unable to validate vote integrity. Please try again.';
    }
    
    return $errors;
}

/**
 * Prepare comprehensive session data
 */
private function prepareSessionData($vote_data, $auth_user, $totalDuration, $code)
{
    $code_expires_in = $code->voting_time_in_minutes ?? 15;
    
    return [
        'user_id' => $auth_user->id,
        'user_name' => $auth_user->name,
        'user_region' => $auth_user->region,
        'national_selected_candidates' => $vote_data['national_selected_candidates'] ?? [],
        'regional_selected_candidates' => $vote_data['regional_selected_candidates'] ?? [],
        'agree_button' => $vote_data['agree_button'] ?? false,
        'totalDuration' => $totalDuration,
        'code_expires_in' => $code_expires_in,
        'submission_timestamp' => Carbon::now()->toISOString(),
        'session_id' => session()->getId(),
        'vote_integrity_hash' => $this->generateVoteHash($vote_data),
    ];
}

/**
 * Generate hash for vote integrity verification
 */
private function generateVoteHash($vote_data)
{
    $hash_data = [
        'national' => $vote_data['national_selected_candidates'] ?? [],
        'regional' => $vote_data['regional_selected_candidates'] ?? [],
        'timestamp' => Carbon::now()->timestamp,
    ];
    
    return hash('sha256', serialize($hash_data));
}

/**
 * Enhanced second code sending with better error handling
 */
public function send_second_voting_code(&$code, $auth_user)
{
    try {
        $code1_used_at = Carbon::parse($code->code1_used_at);
        $current = Carbon::now();
        $totalDuration = $current->diffInMinutes($code1_used_at);
        
        // Check if we're within the valid voting window
        $voting_window = $code->voting_time_in_minutes ?? 15;
        if ($totalDuration >= $voting_window) {
            return [
                'error' => 'Voting window expired',
                'duration' => $totalDuration
            ];
        }
        
        // Check if we need to send a new code
        if (!$code->has_code2_sent || !$code->is_code2_usable) {
            $voting_code = get_random_string(6);
            $code->code2 = Hash::make($voting_code);
            $code->has_code2_sent = 1;
            $code->is_code1_usable = 0; 
            $code->is_code2_usable = 1;
            $code->code2_sent_at = Carbon::now();
            $code->save();
            
            // Send notification with error handling
            try {
                $auth_user->notify(new SecondVerificationCode($auth_user, $voting_code));
                
                Log::info('Second verification code sent successfully', [
                    'user_id' => $auth_user->id,
                    'duration' => $totalDuration
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to send second verification code notification', [
                    'user_id' => $auth_user->id,
                    'error' => $e->getMessage()
                ]);
                
                return [
                    'error' => 'Failed to send verification code email',
                    'duration' => $totalDuration
                ];
            }
        }
        
        return [
            'success' => true,
            'duration' => $totalDuration
        ];
        
    } catch (\Exception $e) {
        Log::error('Exception in send_second_voting_code', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'error' => 'Code generation failed',
            'duration' => 0
        ];
    }
}


/**
 * Simplified validation for candidate selections
 * Focuses on actual data structure rather than complex flow checks
 *
 * @param array $vote_data
 * @return array
 */
private function validate_candidate_selections($vote_data)
{
    $errors = [];

    // Get selections
    $national_selections = $vote_data['national_selected_candidates'] ?? [];
    $regional_selections = $vote_data['regional_selected_candidates'] ?? [];

    // Check if user made any selections at all
    $has_any_selection = false;

    // Check national selections
    foreach ($national_selections as $index => $selection) {
        if ($selection) {
            if (isset($selection['no_vote']) && $selection['no_vote']) {
                $has_any_selection = true;
            } elseif (isset($selection['candidates']) && is_array($selection['candidates']) && count($selection['candidates']) > 0) {
                $has_any_selection = true;
                
                // Validate candidate count doesn't exceed required number
                $required_count = $selection['required_number'] ?? 1;
                $candidate_count = count($selection['candidates']);
                
                if ($candidate_count > $required_count) {
                    $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);
                    $errors["national_post_{$index}"] = "Too many candidates selected for {$post_name}. Maximum: {$required_count}";
                }
            }
        }
    }

    // Check regional selections
    foreach ($regional_selections as $index => $selection) {
        if ($selection) {
            if (isset($selection['no_vote']) && $selection['no_vote']) {
                $has_any_selection = true;
            } elseif (isset($selection['candidates']) && is_array($selection['candidates']) && count($selection['candidates']) > 0) {
                $has_any_selection = true;
                
                // Validate candidate count doesn't exceed required number
                $required_count = $selection['required_number'] ?? 1;
                $candidate_count = count($selection['candidates']);
                
                if ($candidate_count > $required_count) {
                    $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);
                    $errors["regional_post_{$index}"] = "Too many candidates selected for {$post_name}. Maximum: {$required_count}";
                }
            }
        }
    }

    // Ensure user made at least one selection or no-vote choice
    if (!$has_any_selection) {
        $errors['no_selections'] = 'Please make at least one selection or choose "Skip" for the positions you wish to abstain from.';
    }

    return $errors;
}
/**
 * Validate vote selections to ensure proper choices are made
 *
 * @param array $vote_data
 * @return array
 */
private function validate_vote_selections($vote_data)
{
    $errors = [];

    // Get selections
    $national_selections = $vote_data['national_selected_candidates'] ?? [];
    $regional_selections = $vote_data['regional_selected_candidates'] ?? [];

    // Validate that user has made at least some choices
    $has_national_choices = $this->has_valid_selections($national_selections);
    $has_regional_choices = $this->has_valid_selections($regional_selections);

    if (!$has_national_choices && !$has_regional_choices) {
        $errors['no_selections'] = 'Please make at least one selection or choose "Skip" for the positions you wish to abstain from.';
    }

    // Validate individual selections
    foreach ($national_selections as $index => $selection) {
        if ($selection && !$selection['no_vote']) {
            $candidate_count = count($selection['candidates'] ?? []);
            $required_count = $selection['required_number'] ?? 1;
            
            if ($candidate_count > $required_count) {
                $errors["national_post_{$index}"] = "Too many candidates selected for {$selection['post_name']}. Maximum allowed: {$required_count}";
            }
        }
    }

    foreach ($regional_selections as $index => $selection) {
        if ($selection && !$selection['no_vote']) {
            $candidate_count = count($selection['candidates'] ?? []);
            $required_count = $selection['required_number'] ?? 1;
            
            if ($candidate_count > $required_count) {
                $errors["regional_post_{$index}"] = "Too many candidates selected for {$selection['post_name']}. Maximum allowed: {$required_count}";
            }
        }
    }

    return $errors;
}

/**
 * Check if selections array has any valid choices (either candidates or no_vote)
 *
 * @param array $selections
 * @return bool
 */
private function has_valid_selections($selections)
{
    foreach ($selections as $selection) {
        if ($selection) {
            // Valid if either no_vote is true OR candidates array has items
            if ($selection['no_vote'] || (!empty($selection['candidates']) && count($selection['candidates']) > 0)) {
                return true;
            }
        }
    }
    return false;
} 
 
    ////////////////////////////////////////////////// 
    
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    DB::beginTransaction();
    
    try {
   
        $auth_user          = auth()->user();
        $code               = $auth_user->code;
        $voting_session_name =Hash::make($code->code2);

        //everything take from Code Model
        $this->has_voted    =$code->has_voted;
        $this->in_code      =$code->code2;
        $this->out_code     = $request['voting_code'];
        $voting_code        = trim($request->input('voting_code'));
       
        $this->user_id      =$code->user_id;
        $code->session_name = 'vote_' . $code->id."_". auth()->id();
        $code->save();
        $session_name       =$code->session_name;
        //get deligatevote from session
        $vote_data = $request->session()->get($session_name);

        // dd($vote_data["national_selected_candidates"]);
        // 1. Validate pre-conditions
        $pre_check = $this->vote_post_check($auth_user, $code, $vote_data);
        
            

        /**
             *Here Everything is checked . you save the deligatevote.
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true
             * He has not voted before
             */
            //get deligatevote from session
            // 6. Generate and store verification key

            $this->save_vote($vote_data);


 
             // 6. Save the vote WITHOUT user information
            // $vote = $this->saveAnonymizedVote($prviate_key, $vote_data);
            // 7. Save the vote and related data
            // $vote = $this->saveVoteTransactionally($auth_user, $private_key, $vote_data);


            $private_key = $this->generateAndStoreVerificationKey($code);
             $hashed_key = Hash::make($private_key);

        
            // 8. Mark user as voted and update code status
                $this->markUserAsVoted($code, $hashed_key); 

                // 9. Send verification notification
                $auth_user->notify(new SendVoteSavingCode($private_key));

                DB::commit();
        
        // $request->session()->forget('vote');     
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
 * Mark user as having voted and update code status
 * 
 * @param Code $code
 */
 function markUserAsVoted(Code $code, string $primary_key )
{
    $code->update([
        'has_voted' => true,
        'vote_for_code'=>$primary_key,
        'vote_show_code'=>$primary_key,
        'can_vote_now' => false,
        'is_code2_usable' => false,
        'code2_used_at' => now()
    ]);
}

/**
 * Prepare vote data for storage
 * 
 * @param array|null $selection
 * @return array|null
 */
protected function prepareVoteData(?array $selection): ?array
{
    if ($selection === null) {
        return ['candidates' => null, 'no_vote' => true];
    }

    return [
        'candidates' => $selection['candidates'] ?? [],
        'no_vote' => $selection['no_vote'] ?? false,
        'post_id' => $selection['post_id'] ?? null,
        'post_name' => $selection['post_name'] ?? null
    ];
}
/**
 * Save individual candidate results
 * 
 * @param int $vote_id
 * @param array $selection
 */
protected function saveCandidateResults(int $vote_id, array $selection)
{
    foreach ($selection['candidates'] as $candidate) {
        Result::create([
            'vote_id' => $vote_id,
            'post_id' => $selection['post_id'],
            'candidacy_id' => $candidate['candidacy_id']
        ]);
    }
}

protected function handleVoteError(string $message)
{
    return redirect()->back()
        ->withErrors(['vote_error' => $message])
        ->withInput();
}

/**
 * Save the vote and related candidate selections in a transaction
 * 
 * @param User $user
 * @param string $voting_code
 * @param array $vote_data
 * @return Vote
 */
protected function saveVoteTransactionally(User $user, string $voting_code, array $vote_data): Vote
{
    $vote = new Vote();
    $vote->user_id = $user->id; // Store actual user ID
    $vote->voting_code = $voting_code;
    $vote->save();

    if (!empty($vote_data['national_selected_candidates']) || !empty($vote_data['regional_selected_candidates'])) {
        $this->saveCandidateSelections($vote, $vote_data);
    }

    return $vote;
}

/**
 * Save all candidate selections for the vote
 * 
 * @param Vote $vote
 * @param array $vote_data
 */
protected function saveCandidateSelections(Vote $vote, array $vote_data)
{
    $all_candidates = array_merge(
        $vote_data['national_selected_candidates'] ?? [],
        $vote_data['regional_selected_candidates'] ?? []
    );

    foreach ($all_candidates as $index => $selection) {
        $column_name = 'candidate_' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        $vote_data = $this->prepareVoteData($selection);
        
        if ($vote_data) {
            $vote->$column_name = json_encode($vote_data);
            
            // Save individual candidate results if selection exists
            if (!empty($selection['candidates'])) {
                $this->saveCandidateResults($vote->id, $selection);
            }
        }
    }

    $vote->save();
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
 * Generate and store the vote verification key with proper hashing
 * 
 * @param Code $code
 * @param int $vote_id
 * @return string Returns the unhashed private key for one-time notification
 */
 function generateAndStoreVerificationKey(Code $code): string
{

    // Generate a secure random key component
    $random_key = bin2hex(random_bytes(16)); // 32-character random string
    
    // Create the composite private key
    $private_key = $random_key . '_' . $code->id;
    $this->out_code=$private_key;
    // Hash the private key using Laravel's secure Hash facade
    $hashed_key = Hash::make($private_key);
    
    // Store only the hashed version in the database
    $code->code_for_vote = $hashed_key;
    $code->save();
    
    // Return the unhashed version only for the one-time notification
    return $private_key;
}

/**
 * Verify the submitted voting code against the hashed version
 * 
 * @param string $submitted_code
 * @param Code $code
 * @return bool
 */
public function verifyVotingCode(string $submitted_code, Code $code): bool
{
    // Security checks before verification
    if (empty($submitted_code) || empty($code->code_for_vote)) {
        return false;
    }
    
    // Timing attack resistant comparison
    return Hash::check($submitted_code, $code->code_for_vote);
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


/**
 * STEP 7: Display the vote verification page where users confirm their selections
 * and enter the second verification code to finalize their vote
 * 
 * This function handles the verification step after candidate selections have been submitted
 *
 * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
 */
public function verify()
{
    try {
        $auth_user = auth()->user();
        $code = $auth_user->code;
        
        // Get vote data from session (stored by second_submission)
        $vote_data = request()->session()->get($code->session_name);
        
        // Critical check: Ensure we have vote data in session
        if (!$vote_data) {
            Log::warning('Vote verification attempted without session data', [
                'user_id' => $auth_user->id,
                'session_id' => request()->session()->getId()
            ]);
            
            return redirect()->route('vote.create')
                ->withErrors(['session' => 'Vote session expired. Please start the voting process again.']);
        }

        // Perform comprehensive post-submission checks
        $_error = $this->vote_post_check($auth_user, $code, $vote_data);
        
        if ($_error["error_message"] != "") {
            Log::error('Vote verification post-check failed', [
                'user_id' => $auth_user->id,
                'error' => $_error["error_message"]
            ]);
            
            return redirect()->route('dashboard')
                ->withErrors(['verification' => 'Vote verification failed. Please contact support if this persists.']);
        }
        
        if ($_error["return_to"] != "") {
            Log::info('Vote verification redirecting user', [
                'user_id' => $auth_user->id,
                'redirect_to' => $_error["return_to"]
            ]);
            return redirect()->route($_error["return_to"]);
        }

        // Check second code timing and validity
        $_message = $this->second_code_check($code);
        $code_expires_in = $code->voting_time_in_minutes;
        
        if ($_message["error_message"] != "") {
            Log::error('Second code check failed during verification', [
                'user_id' => $auth_user->id,
                'error' => $_message["error_message"],
                'total_duration' => $_message["totalDuration"] ?? 'unknown'
            ]);
            
            return redirect()->route('code.create')
                ->withErrors(['code' => 'Verification code expired. Please restart the voting process.']);
        }

        if ($_message["return_to"] != "") {
            return redirect()->route($_message["return_to"]);
        }

        // Process and structure vote data for clean display
        $processed_vote_data = $this->process_vote_data_for_verification($vote_data);

        // Calculate remaining time for user awareness
        $remaining_time = max(0, $code_expires_in - $_message["totalDuration"]);
        
        // Prepare comprehensive user information
        $user_info = [
            'name' => $auth_user->name,
            'nrna_id' => $auth_user->nrna_id ?? 'N/A',
            'state' => $auth_user->state ?? 'N/A',
            'region' => $auth_user->region ?? 'N/A',
        ];

        // Generate voting summary for quick overview
        $voting_summary = $this->generate_verification_summary($processed_vote_data);

        // Log successful verification page load
        Log::info('Vote verification page loaded successfully', [
            'user_id' => $auth_user->id,
            'remaining_time' => $remaining_time,
            'total_posts' => $voting_summary['total_posts'],
            'voted_posts' => $voting_summary['voted_posts']
        ]);

        return Inertia::render('Vote/Verify', [
            'vote_data' => $processed_vote_data,
            'user_info' => $user_info,
            'timing_info' => [
                'total_duration' => $_message["totalDuration"],
                'code_expires_in' => $code_expires_in,
                'remaining_time' => $remaining_time,
                'submission_time' => $vote_data['submission_timestamp'] ?? Carbon::now()->toISOString(),
                'code_sent_at' => Carbon::now()->subMinutes($_message["totalDuration"])->format('H:i:s')
            ],
            'voting_summary' => $voting_summary,
        ]);

    } catch (\Exception $e) {
        Log::error('Verify function encountered unexpected error', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('dashboard')
            ->withErrors(['system' => 'System error during verification. Please try again or contact support.']);
    }
}

/**
 * Process vote data specifically for verification display
 * Handles both the new data structure and ensures backward compatibility
 *
 * @param array $vote_data
 * @return array
 */
private function process_vote_data_for_verification($vote_data)
{
    $processed = [
        'national_posts' => [],
        'regional_posts' => [],
        'has_national_votes' => false,
        'has_regional_votes' => false,
        'submission_metadata' => [
            'user_id' => $vote_data['user_id'] ?? null,
            'user_name' => $vote_data['user_name'] ?? 'Unknown',
            'submission_timestamp' => $vote_data['submission_timestamp'] ?? null,
            'agree_button' => $vote_data['agree_button'] ?? false
        ]
    ];

    // Process national posts selections
    if (isset($vote_data['national_selected_candidates']) && is_array($vote_data['national_selected_candidates'])) {
        foreach ($vote_data['national_selected_candidates'] as $index => $selection) {
            if ($selection && is_array($selection)) {
                $post_data = [
                    'post_id' => $selection['post_id'] ?? "unknown_national_{$index}",
                    'post_name' => $selection['post_name'] ?? 'Unknown National Post',
                    'required_number' => $selection['required_number'] ?? 1,
                    'no_vote' => $selection['no_vote'] ?? false,
                    'candidates' => $selection['candidates'] ?? [],
                    'selection_type' => 'national'
                ];
                
                $processed['national_posts'][] = $post_data;
                
                // Check if this post has actual candidate votes
                if (!$post_data['no_vote'] && !empty($post_data['candidates'])) {
                    $processed['has_national_votes'] = true;
                }
            }
        }
    }

    // Process regional posts selections
    if (isset($vote_data['regional_selected_candidates']) && is_array($vote_data['regional_selected_candidates'])) {
        foreach ($vote_data['regional_selected_candidates'] as $index => $selection) {
            if ($selection && is_array($selection)) {
                $post_data = [
                    'post_id' => $selection['post_id'] ?? "unknown_regional_{$index}",
                    'post_name' => $selection['post_name'] ?? 'Unknown Regional Post',
                    'required_number' => $selection['required_number'] ?? 1,
                    'no_vote' => $selection['no_vote'] ?? false,
                    'candidates' => $selection['candidates'] ?? [],
                    'selection_type' => 'regional'
                ];
                
                $processed['regional_posts'][] = $post_data;
                
                // Check if this post has actual candidate votes
                if (!$post_data['no_vote'] && !empty($post_data['candidates'])) {
                    $processed['has_regional_votes'] = true;
                }
            }
        }
    }

    return $processed;
}

/**
 * Generate comprehensive voting summary for verification page
 *
 * @param array $processed_vote_data
 * @return array
 */
private function generate_verification_summary($processed_vote_data)
{
    $summary = [
        'total_posts' => 0,
        'voted_posts' => 0,
        'no_vote_posts' => 0,
        'candidate_selections' => 0,
        'completion_percentage' => 0,
        'national_summary' => [
            'total' => 0, 
            'voted' => 0, 
            'no_vote' => 0,
            'candidates' => 0
        ],
        'regional_summary' => [
            'total' => 0, 
            'voted' => 0, 
            'no_vote' => 0,
            'candidates' => 0
        ],
    ];

    // Count national posts
    foreach ($processed_vote_data['national_posts'] as $post) {
        $summary['total_posts']++;
        $summary['national_summary']['total']++;
        
        if ($post['no_vote']) {
            $summary['no_vote_posts']++;
            $summary['national_summary']['no_vote']++;
        } else {
            $candidate_count = count($post['candidates']);
            if ($candidate_count > 0) {
                $summary['voted_posts']++;
                $summary['national_summary']['voted']++;
                $summary['candidate_selections'] += $candidate_count;
                $summary['national_summary']['candidates'] += $candidate_count;
            }
        }
    }

    // Count regional posts
    foreach ($processed_vote_data['regional_posts'] as $post) {
        $summary['total_posts']++;
        $summary['regional_summary']['total']++;
        
        if ($post['no_vote']) {
            $summary['no_vote_posts']++;
            $summary['regional_summary']['no_vote']++;
        } else {
            $candidate_count = count($post['candidates']);
            if ($candidate_count > 0) {
                $summary['voted_posts']++;
                $summary['regional_summary']['voted']++;
                $summary['candidate_selections'] += $candidate_count;
                $summary['regional_summary']['candidates'] += $candidate_count;
            }
        }
    }

    // Calculate completion percentage
    if ($summary['total_posts'] > 0) {
        $completed_posts = $summary['voted_posts'] + $summary['no_vote_posts'];
        $summary['completion_percentage'] = round(($completed_posts / $summary['total_posts']) * 100, 1);
    }

    return $summary;
}

/**
 * Validate the voting code submission with proper error handling
 * 
 * @return \Illuminate\Validation\Validator
 */
/**
 * Validate the voting code submission
 * 
 * @return \Illuminate\Validation\Validator
 */
public function verifyVoteSubmit(): array
{

    $request = request();

    $auth_user = auth()->user();
    $code      =$auth_user->code;
    $in_code  = $code->code2;
  
    $submittedCode = trim($request->input('voting_code'));

    $isCodeValid = false;

    $validator = Validator::make($request->all(), [
        'voting_code' => 'required|string|size:6'
    ]);
 
    $validator->after(function ($validator) use ($code, $submittedCode, &$isCodeValid) {
        if (!$code) {
            $validator->errors()->add('voting_code', 'Verification record missing.');
            return;
        }

        if ($code->has_voted) {
            $validator->errors()->add('voting_code', 'You have already voted.');
            return;
        }

        if (!$code->is_code2_usable) {
            $validator->errors()->add('voting_code', 'This code is no longer valid.');
            return;
        }
        
        if (!Hash::check($submittedCode, $in_code)) {
            $validator->errors()->add('voting_code', 'Incorrect code. Please try again.');
            return;
        }

        $isCodeValid = true; // ✅ Set your flag
    });
    // Run validation logic
    $validator->validate(); // Triggers after callbacks
      return [
        'validator' => $validator,
        'is_code_valid' => $isCodeValid
    ];
}


//save all candidates 
    public function save_vote($input_data){
        $no_vote_option     = 0; 
        $vote               =new Vote;
        // $vote->user_id      = $this->user_id;
         // $vote->user_id      = Hash::make($this->user_id);
        // $vote->user_id      = Hash::make($vote->user_id);        
        $vote->no_vote_option=0; 
        $vote->voting_code  =$this->out_code;       
        $vote->save();   //save the vote first
        
        //save the $this->vote_id_for_voter  it to voter ;
        {
         /**
          * Here you save the all candidates finally :
          * 
          */ 
        //  dd($input_data["candidacies"]);

            // dd($input_data);

            $all_candidates =$input_data["national_selected_candidates"];
                    
 
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
      
        } //end of else 
        
        $vote->save();
      
    
            
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

public function verify_final_vote(Request $request)
{
    $this->out_code = trim($request['voting_code']);
    $auth_user = auth()->user();
    $code = $auth_user->code;

    $validator = $this->verify_code_to_check_vote($code);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    }

    $final_vote = [
        'selected_candidates' => [],
        'name' => $auth_user->name,
        'has_voted' => $code->has_voted,
        'verify_final_vote' => Hash::check($this->out_code, $code->code_for_vote)
    ];

    if ($final_vote['verify_final_vote']) {
        $str_pos = strpos($this->out_code, "_") + 1;
        $voting_id = (int)substr($this->out_code, $str_pos);
        $vote = Vote::find($voting_id);
        
        if ($vote) {
            $vote_data = $vote->toArray();
            $final_vote['selected_candidates'] = array_filter($vote_data, function($key) {
                return strpos($key, 'candidate') === 0;
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    // Store in multiple ways for redundancy
    $request->session()->put('final_vote', $final_vote);
    session(['final_vote' => $final_vote]); // Alternative method
    $request->session()->save(); // Force immediate save 

    // Also store in database as backup
    //$code->vote_show_data = json_encode($final_vote);
    $code->save();

    \Log::debug('Session ID during save:', ['id' => session()->getId()]);
    \Log::debug('Final vote data saved:', $final_vote);

    return redirect()->route('vote.show')->with([
        '_final_vote' => $final_vote // Flash data as additional backup
    ]);
}
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
 public function vote_post_check($auth_user, &$code, $vote)
{
    $_error = [
        "return_to" => "",
        "error_message" => "",
    ];

    // 1. Code is missing or invalid
    if ($code === null) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'Either your code is wrong or you have not voted properly. Send the screenshot to administrator!',
            'link' => route('dashboard'),
            'link_text' => 'Click here to go to the main Dashboard',
        ])->render();
        return $_error;
    }

    // 2. IP address check
    $clientIP = \Request::getClientIp(true);
    $max_use_clientIP = config('app.max_use_clientIP');
    $_message = check_ip_address($clientIP, $max_use_clientIP);

    if (!empty($_message['error_message'])) {
        // Just return the error, let the controller handle the redirect/flash
        $_error['error_message'] = $_message['error_message'];
        return $_error;
    }

    // 3. Code is not usable
    if (!$code->is_code2_usable) {
        $code->is_code1_usable = 0;
        $code->has_code1_sent = 0;
        $_error['return_to'] = 'vote.create';
        return $_error;
    }

    // 4. User already voted
    if ($code->has_voted) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'You have already voted and your vote is already saved! See below',
            'link' => route('vote.verify_to_show'),
            'link_text' => 'Click here to see your vote',
        ])->render();
        return $_error;
    }

    // 5. Vote is missing (should never happen if code is valid and hasn't voted)
    if ($vote === null) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'We could not find your vote. Please contact the administrator. You can also start to vote again.',
            'link' => route('code.create'),
            'link_text' => 'Click here to vote',
        ])->render();
        return $_error;
    }
     
        // No error
    return $_error;
}

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
        if ($code->can_vote_now != 1) {
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
 
// Add these methods to your VoteController class

/**
 * Process vote verification code and display the associated vote
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function submit_code_to_view_vote(Request $request)
{
    try {
        // Validate request input
        $request->validate([
            'voting_code' => 'required|string|min:3|max:50'
        ], [
            'voting_code.required' => 'Verification code is required.',
            'voting_code.min' => 'Verification code is too short.',
            'voting_code.max' => 'Verification code is too long.'
        ]);

        $auth_user = auth()->user();
        $submitted_code = trim($request->input('voting_code'));
        
        // Get user's code record
        $code = $auth_user->code;
        
        if (!$code) {
            return redirect()->back()
                ->withErrors(['voting_code' => 'No voting record found. Please contact administrator.'])
                ->withInput();
        }

        // Validate the verification code
        $validation_result = $this->validate_vote_verification_code($submitted_code, $code, $auth_user);
        
        if (!$validation_result['success']) {
            return redirect()->back()
                ->withErrors(['voting_code' => $validation_result['message']])
                ->withInput();
        }

        // Extract vote ID from the verification code
        $vote_data = $this->extract_vote_data_from_code($submitted_code);
        
        if (!$vote_data['success']) {
            return redirect()->back()
                ->withErrors(['voting_code' => 'Invalid verification code format.'])
                ->withInput();
        }

        // Retrieve the actual vote record
        $vote_record = $this->retrieve_vote_record($vote_data['vote_id']);
        
        if (!$vote_record['success']) {
            return redirect()->back()
                ->withErrors(['voting_code' => 'Vote record not found.'])
                ->withInput();
        }

        // Prepare vote display data
        $display_data = $this->prepare_vote_display_data($vote_record['vote'], $auth_user, $submitted_code);
        
        // Store in session for display
        $request->session()->put('vote_display_data', $display_data);
        
        // Log successful verification
        Log::info('Vote verification successful', [
            'user_id' => $auth_user->id,
            'vote_id' => $vote_data['vote_id'],
            'verification_timestamp' => now()->toISOString()
        ]);

        return redirect()->route('vote.show')
            ->with('success', 'Vote verification successful.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('Vote verification failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withErrors(['voting_code' => 'Verification failed. Please try again or contact support.'])
            ->withInput();
    }
}

/**
 * Validate the submitted verification code against stored hash
 * 
 * @param string $submitted_code
 * @param object $code
 * @param object $auth_user
 * @return array
 */
private function validate_vote_verification_code($submitted_code, $code, $auth_user)
{
    // Check if user has voted
    if (!$code->has_voted) {
        return [
            'success' => false,
            'message' => 'You have not voted yet. Please vote first before verifying.'
        ];
    }

    // Check if verification code exists
    if (!$code->code_for_vote) {
        return [
            'success' => false,
            'message' => 'No verification code found. Please contact administrator.'
        ];
    }

    // Verify the code against stored hash
    if (!Hash::check($submitted_code, $code->code_for_vote)) {
        return [
            'success' => false,
            'message' => 'Invalid verification code. Please check your email and try again.'
        ];
    }

    return [
        'success' => true,
        'message' => 'Code verified successfully.'
    ];
}

/**
 * Extract vote ID from verification code format (e.g., "ABC123_456")
 * 
 * @param string $verification_code
 * @return array
 */
private function extract_vote_data_from_code($verification_code)
{
    // Check if code contains underscore separator
    if (!str_contains($verification_code, '_')) {
        return [
            'success' => false,
            'message' => 'Invalid code format.'
        ];
    }

    // Extract vote ID from after the underscore
    $parts = explode('_', $verification_code);
    
    if (count($parts) < 2) {
        return [
            'success' => false,
            'message' => 'Invalid code format.'
        ];
    }

    $vote_id = (int) end($parts);
    
    if ($vote_id <= 0) {
        return [
            'success' => false,
            'message' => 'Invalid vote ID in code.'
        ];
    }

    return [
        'success' => true,
        'vote_id' => $vote_id,
        'random_part' => $parts[0]
    ];
}

/**
 * Retrieve vote record from database
 * 
 * @param int $vote_id
 * @return array
 */
private function retrieve_vote_record($vote_id)
{
    try {
        $vote = Vote::with('user')->find($vote_id);
        
        if (!$vote) {
            return [
                'success' => false,
                'message' => 'Vote record not found in database.'
            ];
        }

        return [
            'success' => true,
            'vote' => $vote
        ];
        
    } catch (\Exception $e) {
        Log::error('Failed to retrieve vote record', [
            'vote_id' => $vote_id,
            'error' => $e->getMessage()
        ]);

        return [
            'success' => false,
            'message' => 'Database error while retrieving vote.'
        ];
    }
}

/**
 * Prepare comprehensive vote data for display
 * 
 * @param object $vote
 * @param object $auth_user
 * @param string $verification_code
 * @return array
 */
private function prepare_vote_display_data($vote, $auth_user, $verification_code)
{
    // Get voter information (might be different from auth user if they're viewing someone else's vote)
    $voter_user = $vote->user;
    
    // Process vote candidates from JSON columns
    $vote_selections = $this->process_vote_selections($vote);
    
    // Determine if this is the current user's own vote
    $is_own_vote = $voter_user && $voter_user->id === $auth_user->id;
    
    return [
        'vote_id' => $vote->id,
        'verification_code' => $verification_code,
        'verification_timestamp' => now()->toISOString(),
        'verification_successful' => true,
        'is_own_vote' => $is_own_vote,
        'voter_info' => [
            'name' => $voter_user->name ?? 'Unknown Voter',
            'user_id' => $voter_user->user_id ?? 'N/A',
            'region' => $voter_user->region ?? 'N/A',
        ],
        'vote_info' => [
            'voted_at' => $vote->created_at ? $vote->created_at->format('M j, Y \a\t g:i A') : 'Unknown',
            'no_vote_option' => $vote->no_vote_option ?? false,
            'voting_code_used' => $vote->voting_code ?? 'N/A',
        ],
        'vote_selections' => $vote_selections,
        'summary' => [
            'total_positions' => count($vote_selections),
            'positions_voted' => count(array_filter($vote_selections, function($selection) {
                return !empty($selection['candidates']) || $selection['no_vote'];
            })),
            'candidates_selected' => array_sum(array_map(function($selection) {
                return count($selection['candidates'] ?? []);
            }, $vote_selections))
        ]
    ];
}

/**
 * Process vote selections from database JSON columns
 * 
 * @param object $vote
 * @return array
 */
private function process_vote_selections($vote)
{
    $selections = [];
    
    // Process all candidate columns (candidate_01, candidate_02, etc.)
    for ($i = 1; $i <= 30; $i++) {
        $column_name = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
        
        if (isset($vote->$column_name) && !empty($vote->$column_name)) {
            $selection_data = json_decode($vote->$column_name, true);
            
            if ($selection_data && is_array($selection_data)) {
                // Enrich candidate data with additional information
                $enriched_selection = $this->enrich_selection_data($selection_data);
                
                if ($enriched_selection) {
                    $selections[] = $enriched_selection;
                }
            }
        }
    }
    
    return $selections;
}

/**
 * Enrich selection data with candidate and post information
 * Get candidate names from User table through relationship
 * 
 * @param array $selection_data
 * @return array|null
 */
private function enrich_selection_data($selection_data)
{
    try {
        $enriched = [
            'post_id' => $selection_data['post_id'] ?? 'Unknown',
            'post_name' => $selection_data['post_name'] ?? 'Unknown Position',
            'post_nepali_name' => $selection_data['post_nepali_name'] ?? '',
            'no_vote' => $selection_data['no_vote'] ?? false,
            'candidates' => []
        ];

        // If no vote was selected, return early
        if ($enriched['no_vote'] || empty($selection_data['candidates'])) {
            return $enriched;
        }

        // Enrich candidate information
        foreach ($selection_data['candidates'] as $candidate_data) {
            $candidacy_id = $candidate_data['candidacy_id'] ?? null;
            
            if ($candidacy_id) {
                // Load candidacy WITH user relationship
                $candidacy = Candidacy::with('user')
                    ->where('candidacy_id', $candidacy_id)
                    ->first();
                
                if ($candidacy) {
                    // Get candidate name from User table through relationship
                    $candidate_name = $this->getCandidateNameFromCandidacy($candidacy);
                    
                    $enriched['candidates'][] = [
                        'candidacy_id' => $candidacy->candidacy_id,
                        'candidacy_name' => $candidate_name,  // ✅ FROM USER TABLE
                        'proposer_name' => $candidacy->proposer_name,
                        'supporter_name' => $candidacy->supporter_name,
                        'image_path_1' => $candidacy->image_path_1,
                        'user_info' => [
                            'id' => $candidacy->user->id ?? null,
                            'name' => $candidacy->user->name ?? 'Unknown',
                            'user_id' => $candidacy->user->user_id ?? 'N/A',
                            'region' => $candidacy->user->region ?? 'N/A',
                        ]
                    ];
                } else {
                    // Fallback if candidacy not found in database
                    $enriched['candidates'][] = [
                        'candidacy_id' => $candidacy_id,
                        'candidacy_name' => 'Candidate ' . str_replace(['_', '-'], ' ', $candidacy_id),
                        'proposer_name' => 'Unknown',
                        'supporter_name' => 'Unknown',
                        'image_path_1' => '',
                        'user_info' => [
                            'id' => null,
                            'name' => 'Unknown',
                            'user_id' => 'N/A',
                            'region' => 'N/A',
                        ]
                    ];
                }
            }
        }

        return $enriched;
        
    } catch (\Exception $e) {
        Log::warning('Failed to enrich selection data', [
            'selection_data' => $selection_data,
            'error' => $e->getMessage()
        ]);
        
        // Return basic structure with available data
        return [
            'post_id' => $selection_data['post_id'] ?? 'Unknown',
            'post_name' => $selection_data['post_name'] ?? 'Unknown Position',
            'post_nepali_name' => $selection_data['post_nepali_name'] ?? '',
            'no_vote' => $selection_data['no_vote'] ?? false,
            'candidates' => []
        ];
    }
}

/**
 * Get candidate name from Candidacy model using User relationship
 * 
 * @param \App\Models\Candidacy $candidacy
 * @return string
 */
private function getCandidateNameFromCandidacy($candidacy)
{
    // Priority 1: Get name from related User
    if ($candidacy->user && !empty($candidacy->user->name)) {
        return $candidacy->user->name;
    }
    
    // Priority 2: Construct from first_name + last_name if available
    if ($candidacy->user && (!empty($candidacy->user->first_name) || !empty($candidacy->user->last_name))) {
        $fullName = trim(($candidacy->user->first_name ?? '') . ' ' . ($candidacy->user->last_name ?? ''));
        if (!empty($fullName)) {
            return $fullName;
        }
    }
    
    // Priority 3: Use user_name field from candidacy table (backup)
    if (!empty($candidacy->user_name)) {
        return $candidacy->user_name;
    }
    
  
    
    // Priority 5: Generate from candidacy_id
    if (!empty($candidacy->candidacy_id)) {
        return 'Candidate ' . str_replace(['_', '-'], ' ', $candidacy->candidacy_id);
    }
    
    return 'Unknown Candidate';
}


/**
 * Display the verified vote record
 * 
 * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
 */
public function show()
{
    try {
        $auth_user = auth()->user();
        
        // Get vote display data from session
        $vote_display_data = request()->session()->get('vote_display_data');
        
        // Check if we have vote data to display
        if (!$vote_display_data) {
            return redirect()->route('vote.verify_to_show')
                ->withErrors(['session' => 'No vote data found. Please verify your code again.']);
        }

        // Validate vote display data structure
        if (!$this->is_valid_vote_display_data($vote_display_data)) {
            request()->session()->forget('vote_display_data');
            return redirect()->route('vote.verify_to_show')
                ->withErrors(['data' => 'Invalid vote data. Please verify your code again.']);
        }

        // Log vote display access
        Log::info('Vote display accessed', [
            'viewing_user_id' => $auth_user->id,
            'vote_id' => $vote_display_data['vote_id'] ?? 'unknown',
            'is_own_vote' => $vote_display_data['is_own_vote'] ?? false,
            'access_timestamp' => now()->toISOString()
        ]);

        // Clear session data after successful retrieval (optional security measure)
        // request()->session()->forget('vote_display_data');

        return Inertia::render('Vote/VoteShow', [
            'vote_data' => $vote_display_data,
            'viewing_user' => [
                'id' => $auth_user->id,
                'name' => $auth_user->name,
                'user_id' => $auth_user->user_id ?? 'N/A'
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Vote show page error', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('vote.verify_to_show')
            ->withErrors(['system' => 'An error occurred while displaying the vote. Please try again.']);
    }
}

/**
 * Validate vote display data structure
 * 
 * @param mixed $data
 * @return bool
 */
private function is_valid_vote_display_data($data)
{
    if (!is_array($data)) {
        return false;
    }

    $required_keys = [
        'vote_id',
        'verification_successful',
        'voter_info',
        'vote_info',
        'vote_selections'
    ];

    foreach ($required_keys as $key) {
        if (!array_key_exists($key, $data)) {
            return false;
        }
    }

    return true;
}


}//end of the controller 
