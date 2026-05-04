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

        $members = User::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->values();

        return response()->json($members);
    }
}
