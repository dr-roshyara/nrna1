<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

final class MemberSearchController extends Controller
{
    public function index(Organisation $organisation): JsonResponse
    {
        $this->authorize('manageCommittee', $organisation);

        $query = request()->input('q', '');
        $limit = (int) request()->input('limit', 10);

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search in imported members first (those with personal_info JSON)
        $importedMembers = \App\Models\Member::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->whereRaw("(personal_info::jsonb->>'fullName') ILIKE ?", ["%{$query}%"])
            ->orWhereRaw("(personal_info::jsonb->>'email') ILIKE ?", ["%{$query}%"])
            ->get()
            ->map(function ($member) {
                $personalInfo = json_decode($member->personal_info, true) ?? [];
                return [
                    'id' => $member->id,
                    'name' => $personalInfo['fullName'] ?? 'Unknown',
                    'email' => $personalInfo['email'] ?? 'Unknown',
                ];
            });

        // Search in organisation users
        $organisationUsers = User::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);

        // Combine and deduplicate by ID
        $allMembers = collect()
            ->merge($importedMembers)
            ->merge($organisationUsers)
            ->unique('id')
            ->take($limit)
            ->values();

        return response()->json($allMembers);
    }
}
