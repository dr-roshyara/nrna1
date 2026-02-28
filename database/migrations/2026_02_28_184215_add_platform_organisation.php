<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates a platform organisation with ID 0 for system-wide data
     * This ensures foreign key integrity for records without a specific organisation
     */
    public function up(): void
    {
        // Insert the platform organisation with ID 0
        // This must be done using raw SQL because Eloquent won't let us set ID manually
        \DB::statement('
            INSERT INTO organisations (id, name, slug, type, created_at, updated_at)
            VALUES (0, \'Platform\', \'platform\', \'other\', NOW(), NOW())
            ON DUPLICATE KEY UPDATE name = VALUES(name)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete the platform organisation
        \DB::table('organisations')
            ->where('id', 0)
            ->delete();
    }
};
