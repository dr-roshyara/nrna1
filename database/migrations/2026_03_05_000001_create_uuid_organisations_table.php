<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['platform', 'tenant'])->default('tenant');
            $table->boolean('is_default')->default(false);
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('representative')->nullable();
            $table->json('settings')->nullable();
            $table->json('languages')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
        });

        // Insert the default platform organisation (Public Digit)
        // This serves as the default for all users and demo elections
        $this->insertDefaultOrganisation();
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }

    /**
     * Insert the default Public Digit platform organisation
     */
    private function insertDefaultOrganisation(): void
    {
        $exists = DB::table('organisations')
            ->where('slug', 'publicdigit')
            ->exists();

        if (!$exists) {
            DB::table('organisations')->insert([
                'id' => Str::uuid(),
                'name' => 'Public Digit',
                'slug' => 'publicdigit',
                'type' => 'platform',
                'is_default' => true,
                'email' => null,
                'address' => null,
                'representative' => null,
                'settings' => json_encode(['is_default' => true]),
                'languages' => json_encode(['en', 'de', 'np']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
