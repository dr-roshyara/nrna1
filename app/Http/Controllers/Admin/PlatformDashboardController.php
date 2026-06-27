<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class PlatformDashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'pending_elections' => Election::withoutGlobalScopes()
                ->where('state', 'pending_approval')
                ->count(),
            'platform_admins' => User::where('is_super_admin', true)
                ->orWhere('platform_role', 'platform_admin')
                ->count(),
            'organisations' => Organisation::count(),
            'total_elections' => Election::withoutGlobalScopes()->count(),
        ];

        return Inertia::render('Admin/Dashboard', ['stats' => $stats]);
    }
}
