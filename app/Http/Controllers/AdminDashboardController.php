<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentRole = $request->attributes->get('current_role', 'admin');

        // Get organisations where user is admin
        $organisations = $user->organisationRoles()
            ->wherePivot('role', 'admin')
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'slug' => $org->slug,
                    'type' => $org->type,
                    'created_at' => $org->created_at->format('Y-m-d'),
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'currentRole' => $currentRole,
            'organisations' => $organisations,
            'quickStats' => [
                'totalElections' => 0,
                'activeElections' => 0,
                'totalVoters' => 0,
                'participationRate' => 0,
            ],
        ]);
    }
}
