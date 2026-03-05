<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create the default platform organisation (ID: 1)
     * This organisation serves as the default for all users and demo elections
     */
    public function up(): void
    {
        // Check if the platform organisation already exists
        $exists = DB::table('organisations')->where('id', 1)->exists();

        if (!$exists) {
            DB::table('organisations')->insert([
                'id' => 1,
                'name' => 'Public Digit',
                'slug' => 'publicdigit',
                'type' => 'platform',
                'email' => null,
                'address' => null,
                'representative' => null,
                'settings' => json_encode(['is_default' => true]),
                'languages' => json_encode(['en', 'de']),
                'is_platform' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Don't delete the platform organisation on rollback
        // It's a core system requirement
    }
};
