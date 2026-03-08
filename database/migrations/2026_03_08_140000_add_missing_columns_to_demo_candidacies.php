<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds missing columns to demo_candidacies table that DemoElectionCreationService
     * expects to store when auto-creating demo elections. Without these columns,
     * the service data is silently dropped.
     */
    public function up(): void
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Add missing columns required by DemoElectionCreationService
            if (!Schema::hasColumn('demo_candidacies', 'election_id')) {
                $table->string('election_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('demo_candidacies', 'candidacy_id')) {
                $table->string('candidacy_id')->nullable()->after('election_id');
            }

            if (!Schema::hasColumn('demo_candidacies', 'user_name')) {
                $table->string('user_name')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('demo_candidacies', 'candidacy_name')) {
                $table->string('candidacy_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('demo_candidacies', 'proposer_name')) {
                $table->string('proposer_name')->nullable()->after('candidacy_name');
            }

            if (!Schema::hasColumn('demo_candidacies', 'supporter_name')) {
                $table->string('supporter_name')->nullable()->after('proposer_name');
            }

            if (!Schema::hasColumn('demo_candidacies', 'image_path_1')) {
                $table->string('image_path_1')->nullable()->after('supporter_name');
            }
        });

        // Fix user_id: drop FK constraint since service uses fake strings like "demo-XXXXX-1"
        // Cannot directly change column type, so drop FK, then modify in a separate operation
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Drop the foreign key if it exists
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist, continue
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Drop all added columns
            if (Schema::hasColumn('demo_candidacies', 'election_id')) {
                $table->dropColumn('election_id');
            }

            if (Schema::hasColumn('demo_candidacies', 'candidacy_id')) {
                $table->dropColumn('candidacy_id');
            }

            if (Schema::hasColumn('demo_candidacies', 'user_name')) {
                $table->dropColumn('user_name');
            }

            if (Schema::hasColumn('demo_candidacies', 'candidacy_name')) {
                $table->dropColumn('candidacy_name');
            }

            if (Schema::hasColumn('demo_candidacies', 'proposer_name')) {
                $table->dropColumn('proposer_name');
            }

            if (Schema::hasColumn('demo_candidacies', 'supporter_name')) {
                $table->dropColumn('supporter_name');
            }

            if (Schema::hasColumn('demo_candidacies', 'image_path_1')) {
                $table->dropColumn('image_path_1');
            }
        });

        // Restore the foreign key
        Schema::table('demo_candidacies', function (Blueprint $table) {
            try {
                $table->foreign('user_id')->references('id')->on('users');
            } catch (\Exception $e) {
                // Foreign key may not be needed, continue
            }
        });
    }
};
