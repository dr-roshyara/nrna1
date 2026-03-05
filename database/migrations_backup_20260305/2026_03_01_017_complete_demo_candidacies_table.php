<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing columns to demo_candidacies table
     *
     * The base migration 2026_03_01_000012 created a simplified demo_candidacies table.
     * This migration adds all the missing columns needed by the demo:setup command.
     */
    public function up(): void
    {
        if (Schema::hasTable('demo_candidacies')) {
            Schema::table('demo_candidacies', function (Blueprint $table) {
                // Make user_id nullable for demo data (not all demo candidates need a user reference)
                if (Schema::hasColumn('demo_candidacies', 'user_id')) {
                    try {
                        $table->unsignedBigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        // If change() fails (some databases don't support it), continue
                    }
                }

                // Add missing columns that demo:setup expects
                if (!Schema::hasColumn('demo_candidacies', 'organisation_id')) {
                    $table->unsignedBigInteger('organisation_id')->nullable();
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
                }

                if (!Schema::hasColumn('demo_candidacies', 'candidacy_id')) {
                    $table->string('candidacy_id')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'user_name')) {
                    $table->string('user_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'candidacy_name')) {
                    $table->string('candidacy_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'proposer_name')) {
                    $table->string('proposer_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'supporter_name')) {
                    $table->string('supporter_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'post_name')) {
                    $table->string('post_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'post_nepali_name')) {
                    $table->string('post_nepali_name')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'proposer_id')) {
                    $table->unsignedBigInteger('proposer_id')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'supporter_id')) {
                    $table->unsignedBigInteger('supporter_id')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'image_path_1')) {
                    $table->string('image_path_1')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'image_path_2')) {
                    $table->string('image_path_2')->nullable();
                }

                if (!Schema::hasColumn('demo_candidacies', 'image_path_3')) {
                    $table->string('image_path_3')->nullable();
                }

                // Add missing index
                try {
                    $table->index(['post_id', 'position_order']);
                } catch (\Exception $e) {
                    // Index might already exist
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('demo_candidacies')) {
            Schema::table('demo_candidacies', function (Blueprint $table) {
                $columns = [
                    'organisation_id', 'candidacy_id', 'user_name', 'candidacy_name',
                    'proposer_name', 'supporter_name', 'post_name', 'post_nepali_name',
                    'proposer_id', 'supporter_id', 'image_path_1', 'image_path_2', 'image_path_3'
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('demo_candidacies', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
