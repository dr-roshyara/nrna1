<?php

use App\Models\ElectionOfficer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $commissions = DB::table('user_organisation_roles')
            ->where('role', 'commission')
            ->get();

        foreach ($commissions as $row) {
            ElectionOfficer::firstOrCreate(
                [
                    'organisation_id' => $row->organisation_id,
                    'user_id'         => $row->user_id,
                ],
                [
                    'role'         => 'commissioner',
                    'status'       => 'active',
                    'appointed_by' => $row->user_id,
                    'appointed_at' => now(),
                    'accepted_at'  => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        // Best-effort: soft-delete officers that were created from legacy commission roles
        // where no other appointment source exists (do nothing — data is safe to keep)
    }
};
