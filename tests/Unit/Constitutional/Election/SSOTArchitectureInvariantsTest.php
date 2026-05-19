<?php

namespace Tests\Unit\Constitutional\Election;

use Tests\TestCase;
use Illuminate\Support\Facades\File;

class SSOTArchitectureInvariantsTest extends TestCase
{
    private array $migratedControllers = [
        'app/Http/Controllers/ElectionController.php',
        'app/Http/Controllers/VoteController.php',
        'app/Http/Controllers/ElectionVotingController.php',
        'app/Http/Controllers/VoterSlugController.php',
    ];

    /**
     * @test
     * STREAM 5: RED — No migrated controller queries election with is_active column
     */
    public function no_migrated_controller_queries_election_with_is_active_column()
    {
        $basePath = base_path();

        foreach ($this->migratedControllers as $file) {
            $content = file_get_contents("{$basePath}/{$file}");

            // Check for Election model queries using is_active (but allow VoterSlug.is_active)
            // Pattern: where('is_active' on Election model - look for absence of VoterSlug context
            if (strpos($content, "Election::") !== false || strpos($content, '$election->') !== false) {
                $this->assertStringNotContainsString(
                    "Election::where('is_active'",
                    $content,
                    "File {$file} queries Election with is_active - must use ElectionLifecycle facade"
                );
            }
        }
    }

    /**
     * @test
     * STREAM 5: RED — No migrated controller reads election status directly
     */
    public function no_migrated_controller_reads_election_status_directly()
    {
        $basePath = base_path();

        foreach ($this->migratedControllers as $file) {
            $content = file_get_contents("{$basePath}/{$file}");

            // Check for direct status access (but not membership->status which is intentional)
            $this->assertStringNotContainsString(
                '$election->status',
                $content,
                "File {$file} reads election->status directly - must use ElectionLifecycle facade"
            );
        }
    }

    /**
     * @test
     * STREAM 5: RED — All migrated controllers import ElectionLifecycle facade
     */
    public function all_migrated_controllers_import_lifecycle_facade()
    {
        $basePath = base_path();
        $expectedImport = 'use App\Application\Election\Facades\ElectionLifecycle';

        foreach ($this->migratedControllers as $file) {
            $content = file_get_contents("{$basePath}/{$file}");

            $this->assertStringContainsString(
                $expectedImport,
                $content,
                "File {$file} does not import ElectionLifecycle facade"
            );
        }
    }

    /**
     * @test
     * STREAM 5: RED — No controller injects ElectionLifecycleEngine directly
     */
    public function no_controller_injects_lifecycle_engine_directly()
    {
        $basePath = base_path('app/Http/Controllers');
        $files = File::allFiles($basePath);

        foreach ($files as $file) {
            $content = file_get_contents($file->getPathname());

            $this->assertStringNotContainsString(
                'ElectionLifecycleEngine',
                $content,
                "File {$file->getPathname()} injects ElectionLifecycleEngine directly - must use ElectionLifecycle facade"
            );
        }
    }

    /**
     * @test
     * STREAM 5: RED — DeprecationPolicy guards only known deprecated fields
     */
    public function deprecation_policy_guards_only_known_deprecated_fields()
    {
        $policyFile = base_path('app/Application/Election/Deprecation/DeprecationPolicy.php');
        $content = file_get_contents($policyFile);

        // Verify both required deprecated fields are defined
        $this->assertStringContainsString(
            "'status'",
            $content,
            "DeprecationPolicy must define 'status' field"
        );

        $this->assertStringContainsString(
            "'is_active'",
            $content,
            "DeprecationPolicy must define 'is_active' field"
        );

        // Regression guard: prevent silent expansion of deprecated fields
        $this->assertStringNotContainsString(
            "'legacy_state'",
            $content,
            "DeprecationPolicy should not add unexpected deprecated fields"
        );
    }
}
