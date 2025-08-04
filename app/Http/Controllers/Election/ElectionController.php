<?php
namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

class ElectionController extends Controller
{
    public function dashboard()
    {
        $authUser = Auth::user();

        // Authenticated user: Show Election Dashboard
        if ($authUser) {
            $authUser->makeVisible(['is_voter', 'can_vote']);
            $ballotAccess = [
                'can_access'=> $authUser->canAccessBallot(),
                error_title=> '',
                error_message_nepali=> '',
                error_message_english=> ''
            
            ];
            $electionStatus = [
                'is_active' => false,
                'results_published' => false
            ];
            return Inertia::render('Dashboard/ElectionDashboard', [
                'authUser' => $authUser,
                'electionStatus' => $electionStatus
            ]);
        }

        // Guest: Show Welcome Page
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
