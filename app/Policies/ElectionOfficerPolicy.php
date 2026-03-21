<?php

namespace App\Policies;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ElectionOfficerPolicy
{
    /**
     * Admin/owner/commission can appoint and remove officers.
     */
    public function manage(User $user, Organisation $organisation): bool
    {
        return DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['admin', 'owner', 'commission'])
            ->exists();
    }

    /**
     * Only the appointed user themselves may accept their own appointment.
     */
    public function accept(User $user, ElectionOfficer $officer): bool
    {
        return $user->id === $officer->user_id;
    }
}
