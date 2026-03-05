<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    /**
     * Create a new organisation
     *
     * User becomes OWNER, and switches to this new org
     * User retains membership of platform org for support access
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $org = DB::transaction(function () use ($request, $user) {
            // Create new tenant organisation
            $org = Organisation::create([
                'name' => $request->name,
                'type' => 'tenant',
                'is_default' => false,
            ]);

            // User becomes OWNER of new org
            UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $org->id,
                'role' => 'owner',
            ]);

            // Switch user to new org (they still belong to platform too)
            $user->update(['organisation_id' => $org->id]);

            return $org;
        });

        return redirect("/organisations/{$org->id}/dashboard");
    }
}
