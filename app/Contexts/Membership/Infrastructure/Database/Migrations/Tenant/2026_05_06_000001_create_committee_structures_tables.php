<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('committee_structures')) {
            return;
        }

        Schema::create('committee_structures', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->uuid('organisation_id');
            $table->string('name', 255);
            $table->string('status', 20)->default('draft'); // draft | active | deprecated
            $table->integer('version')->default(1);
            $table->timestamps();

            // Query optimization (business rule "only ONE ACTIVE per tenant" enforced at application layer)
            $table->index(['organisation_id', 'status'], 'idx_structures_org_status');
            $table->index(['organisation_id', 'created_at'], 'idx_structures_org_created');
        });

        Schema::create('committee_structure_levels', function (Blueprint $table) {
            $table->id();
            $table->char('committee_structure_id', 26);
            $table->smallInteger('level_index');
            $table->string('name', 255);
            $table->string('geo_policy', 20); // none | required | optional
            $table->string('geo_scope', 50)->nullable();
            $table->json('role_limits')->nullable();
            $table->smallInteger('min_membership_years')->default(0);
            $table->smallInteger('age_range_min')->nullable();
            $table->smallInteger('age_range_max')->nullable();
            $table->string('gender_requirement', 20)->nullable();
            $table->timestamps();

            // Domain invariant: one index per structure
            $table->unique(
                ['committee_structure_id', 'level_index'],
                'uniq_level_per_structure'
            );

            // Foreign key
            $table->foreign('committee_structure_id', 'fk_levels_structure')
                ->references('id')
                ->on('committee_structures')
                ->onDelete('cascade');

            // Query optimization
            $table->index(['committee_structure_id'], 'idx_levels_structure');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_structure_levels');
        Schema::dropIfExists('committee_structures');
    }
};
