<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WelcomeDashboardController extends Controller
{
    /**
     * Show the welcome/onboarding dashboard for first-time users
     *
     * Displays welcome dashboard with:
     * - Quick start options (create organization, join organization)
     * - Election templates (NRNA Chapter, Student Association, Community Assembly)
     * - Guided setup wizard
     * - Help & Resources
     */
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Welcome/Dashboard', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'userCreatedAt' => $user->created_at,
        ]);
    }
}
