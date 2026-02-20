<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\DemoCode;
use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * DemoCodeController - Demo Election Code Verification
 *
 * Mirrors CodeController but for demo elections with relaxed rules:
 * - No email notifications needed
 * - Code always displayed (no email fallback needed)
 * - Same verification workflow
 * - Same user experience as real voting
 *
 * Workflow:
 * 1. Show code creation form (display verification code)
 * 2. User enters/verifies the code
 * 3. Code marked as verified
 * 4. Proceed to voting
 */
class DemoCodeController extends Controller
{
    private $votingTimeInMinutes = 30;

    /**
     * STEP 1: Show demo code entry form
     * GET /v/{slug}/demo-code/create
     *
     * Display the verification code for the user to enter
     * (Same workflow as real CodeController but simplified)
     */
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO-CODE] Create page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Get or create DemoCode for this user/election
        $code = DemoCode::firstOrCreate(
            [
                'user_id' => $user->id,
                'election_id' => $election->id,
            ],
            [
                'code1' => strtoupper(Str::random(6)),
                'code2' => strtoupper(Str::random(6)),
                'code1_sent_at' => now(),
                'has_code1_sent' => true,
                'voting_time_in_minutes' => $this->votingTimeInMinutes,
            ]
        );

        // Check if code has expired
        $minutesSinceSent = $code->code1_sent_at ? now()->diffInMinutes($code->code1_sent_at) : 0;

        if ($minutesSinceSent >= $this->votingTimeInMinutes && $code->has_code1_sent) {
            Log::info('🎮 [DEMO-CODE] Code expired - generating new code', [
                'user_id' => $user->id,
                'minutes_since_sent' => $minutesSinceSent,
            ]);

            // Generate new code and reset
            $code->code1 = strtoupper(Str::random(6));
            $code->code1_sent_at = now();
            $code->has_code1_sent = true;
            $code->is_code1_usable = 1;
            $code->save();

            $minutesSinceSent = 0;
        }

        Log::info('🎮 [DEMO-CODE] Showing code entry form', [
            'user_id' => $user->id,
            'code' => $code->code1,
            'minutes_since_sent' => $minutesSinceSent,
        ]);

        return Inertia::render('Code/DemoCode/Create', [
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'election_name' => $election->name,
            'election_type' => 'demo',
            'verification_code' => $code->code1,
            'code_duration' => $minutesSinceSent,
            'code_expires_in' => $this->votingTimeInMinutes,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'state' => 'code_sent',
        ]);
    }

    /**
     * STEP 1 → STEP 2: Verify demo code
     * POST /v/{slug}/demo-code
     *
     * User enters the verification code
     * Same validation as real CodeController
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO-CODE] Verifying code submission', [
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);

        // Validate input
        $request->validate([
            'voting_code' => 'required|string|size:6'
        ], [
            'voting_code.required' => 'Please enter the verification code.',
            'voting_code.size' => 'Code must be exactly 6 characters.',
        ]);

        $submittedCode = strtoupper(trim($request->input('voting_code')));

        // Get DemoCode
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        if (!$code) {
            return back()->withErrors(['voting_code' => 'No verification code found. Please try again.']);
        }

        // Verify the code matches
        if ($submittedCode !== $code->code1) {
            Log::warning('🎮 [DEMO-CODE] Invalid code submitted', [
                'user_id' => $user->id,
                'submitted' => $submittedCode,
                'expected' => $code->code1,
            ]);

            return back()->withErrors(['voting_code' => 'Invalid verification code. Please check and try again.'])->withInput();
        }

        // Code is valid - mark as verified
        $code->update([
            'can_vote_now' => 1,
            'is_code1_usable' => 0,
            'code1_used_at' => now(),
        ]);

        Log::info('🎮 [DEMO-CODE] Code verified successfully', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);

        // Redirect to agreement page
        return redirect()->route(
            $voterSlug ? 'slug.demo-vote.agreement' : 'demo-vote.agreement',
            $voterSlug ? ['vslug' => $voterSlug->slug] : []
        )->with('success', 'Code verified! Please review the voting agreement.');
    }

    /**
     * Helper: Get authenticated user
     */
    private function getUser(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Not authenticated.');
        }

        return $user;
    }

    /**
     * Helper: Get election from request (set by middleware)
     */
    private function getElection(Request $request)
    {
        $election = $request->attributes->get('election');

        if (!$election || $election->type !== 'demo') {
            abort(404, 'Demo election not found.');
        }

        return $election;
    }
}
