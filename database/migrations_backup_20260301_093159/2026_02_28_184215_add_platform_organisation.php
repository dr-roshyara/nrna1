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
        // We need to disable AUTO_INCREMENT temporarily to allow id=0
        try {
            // Get current AUTO_INCREMENT value
            $result = \DB::select('SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "organisations"');
            $currentAutoIncrement = $result[0]->AUTO_INCREMENT ?? 1;

            // Disable AUTO_INCREMENT, insert, and restore it
            \DB::statement('ALTER TABLE organisations AUTO_INCREMENT = 0');
            \DB::insert('
                INSERT INTO organisations (id, name, slug, type, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?)
            ', [0, 'Platform', 'platform', 'other', now(), now()]);
            // Restore AUTO_INCREMENT to max(id) + 1 to prevent conflicts
            \DB::statement('ALTER TABLE organisations AUTO_INCREMENT = ' . max(1, $currentAutoIncrement));
        } catch (\Exception $e) {
            // Log but don't fail - the migration might not be on MySQL or might have restrictions
            \Log::warning('Failed to insert platform organisation: ' . $e->getMessage());
        }
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
