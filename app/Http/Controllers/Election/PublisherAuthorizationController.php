<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Publisher;
use App\Models\ResultAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Inertia\Inertia;

class PublisherAuthorizationController extends Controller
{
    /**
     * Show authorization interface (renders Authorization.vue)
     */
    public function index(Request $request)
    {
        // Your existing code remains the same
        $publisher = $request->input('publisher');
        $election = $request->input('current_election');
        
        $phase = $election->getCurrentPhase();
        
        $currentAuthorization = $publisher->getCurrentAuthorization(
            $election->id, 
            $election->authorization_session_id
        );

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        $agreedPublishers = ResultAuthorization::getCompletedAuthorizations(
            $election->id,
            $election->authorization_session_id
        );

        $pendingPublishers = ResultAuthorization::getPendingPublishers(
            $election->id,
            $election->authorization_session_id
        );

        return Inertia::render('Publisher/Authorization', [
            'phase' => $phase,
            'publisher' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'title' => $publisher->title,
                'agreed' => $currentAuthorization ? $currentAuthorization->agreed : false,
                'agreed_at' => $currentAuthorization ? $currentAuthorization->agreed_at : null,
            ],
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'phase' => $phase,
                'authorization_deadline' => $election->authorization_deadline,
            ],
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'agreedPublishers' => $agreedPublishers->map(function($auth) {
                return [
                    'name' => $auth->publisher->name,
                    'title' => $auth->publisher->title,
                    'agreed_at' => $auth->agreed_at,
                ];
            }),
            'pendingPublishers' => $pendingPublishers->map(function($pub) {
                return [
                    'name' => $pub->name,
                    'title' => $pub->title,
                ];
            }),
        ]);
    }

    /**
     * ✅ FIXED: Changed method name from 'authorize' to 'submitAuthorization'
     */
  /**
     * ✅ FIXED: Changed method name from 'authorize' to 'submitAuthorization'
     */
    public function submitAuthorization(Request $request)
    {
        $request->validate([
            'authorization_password' => 'required|string',
            'agree' => 'required|accepted',
        ], [
            'authorization_password.required' => 'प्राधिकरण पासवर्ड आवश्यक छ।',
            'agree.accepted' => 'तपाईंले सहमति दिनुपर्छ।',
        ]);

        $publisher = $request->input('publisher');
        $election = $request->input('current_election');

        // Check if already authorized for this election/session
        if ($publisher->agreed) {
            return back()->with('error', 'तपाईंले पहिले नै प्राधिकरण दिनुभएको छ।');
        }

        // Verify authorization password
        if (!Hash::check($request->authorization_password, $publisher->authorization_password)) {
            return back()->with('error', 'गलत प्राधिकरण पासवर्ड।');
        }

        // Update publisher agreement
        $publisher->update([
            'agreed' => true,
            'agreed_at' => now(),
        ]);

        Log::info('Publisher authorization completed', [
            'publisher_id' => $publisher->id,
            'user_id' => Auth::id(),
            'election_id' => $election->id ?? 'unknown',
        ]);

        return back()->with('success', 'प्राधिकरण सफल भयो।');
    }
    /**
     * API: Real-time progress
     */
    public function progress()
    {
        $election = Election::current();
        
        if (!$election || !$election->authorization_session_id) {
            return response()->json(['error' => 'No active authorization session'], 400);
        }

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        return response()->json([
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'phase' => $election->getCurrentPhase(),
            'deadline' => $election->authorization_deadline,
        ]);
    }

    /**
     * Committee: Start sealing process
     */
    public function startSealing(Request $request)
    {
        $election = Election::current();
        if (!$election) {
            return response()->json(['error' => 'No active election'], 400);
        }

        $result = $election->startSealing();

        if ($result) {
            return response()->json([
                'message' => 'Sealing process started successfully',
                'phase' => 'sealed',
            ]);
        }

        return response()->json(['error' => 'Failed to start sealing process'], 500);
    }
}