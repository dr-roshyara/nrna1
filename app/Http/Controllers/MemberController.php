<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display members of current user's organisation
     *
     * GET /members/index
     */
    public function index(Request $request)
    {
        // Validate query parameters
        $request->validate([
            'direction' => 'in:asc,desc',
            'field' => 'in:id,name,email,role,assigned_at,created_at',
        ]);

        // Get current user
        $user = auth()->user();

        // Determine which organisation to show
        // Priority: session > user's primary organisation
        $organisationId = session('current_organisation_id') ?? $user->organisation_id;

        if (!$organisationId) {
            abort(403, 'No organisation selected. Please select an organisation first.');
        }

        $organisation = Organisation::findOrFail($organisationId);

        // Check if user is member of this organisation
        $isMember = $organisation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isMember) {
            abort(403, 'You do not have access to this organisation.');
        }

        // Build query for organisation members
        $query = $organisation->users()
            ->select('users.id', 'users.name', 'users.email', 'users.state', 'users.created_at')
            ->withPivot(['role', 'permissions', 'assigned_at']);

        // Apply filters
        if ($request->filled('name')) {
            $query->where('users.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('users.email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('role')) {
            $query->wherePivot('role', $request->role);
        }

        // Apply sorting
        $field = $request->input('field', 'assigned_at');
        $direction = $request->input('direction', 'desc');

        if (in_array($field, ['role', 'assigned_at'])) {
            // Pivot column sorting
            $query->orderByPivot($field, $direction);
        } else {
            // User table column sorting
            $query->orderBy('users.' . $field, $direction);
        }

        // Paginate
        $members = $query->paginate(20);

        // Transform members to include pivot data in a clean format
        $members->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'state' => $user->state,
                'created_at' => $user->created_at,
                'role' => $user->pivot->role,
                'assigned_at' => $user->pivot->assigned_at,
            ];
        });

        return Inertia::render('Members/Index', [
            'members' => $members,
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
            ],
            'filters' => $request->only(['name', 'email', 'role', 'field', 'direction']),
            'currentUser' => $user,
            'stats' => [
                'total_members' => $organisation->users()->count(),
                'admins_count' => $organisation->admins()->count(),
                'voters_count' => $organisation->voters()->count(),
            ],
        ]);
    }
}
