<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Add slug column for name-based URL routing
     * Slug is tenant-scoped (unique per organisation)
     */
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('name');
        });

        // Generate slugs from existing committee names
        $this->generateSlugs();

        // Make slug not nullable after populating
        Schema::table('committees', function (Blueprint $table) {
            $table->string('slug', 255)->nullable(false)->change();

            // Unique constraint: organisation + slug (tenant-scoped)
            $table->unique(['organisation_id', 'slug'], 'unique_committee_slug_per_tenant');
        });
    }

    /**
     * Generate slugs from committee names
     */
    private function generateSlugs(): void
    {
        $committees = \DB::table('committees')->get();

        foreach ($committees as $committee) {
            $slug = Str::slug($committee->name);

            // Ensure uniqueness within organisation
            $counter = 1;
            $originalSlug = $slug;

            while (\DB::table('committees')
                ->where('organisation_id', $committee->organisation_id)
                ->where('slug', $slug)
                ->where('id', '!=', $committee->id)
                ->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }

            \DB::table('committees')
                ->where('id', $committee->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropUnique('unique_committee_slug_per_tenant');
            $table->dropColumn('slug');
        });
    }
};
