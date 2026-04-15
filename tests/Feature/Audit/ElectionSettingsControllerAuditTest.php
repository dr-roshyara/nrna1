<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionSettingsControllerAuditTest extends TestCase
{
    use RefreshDatabase;

    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditBasePath = storage_path('logs/audit');

        // Clean up audit directory before each test
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
    }

    /**
     * Test: ElectionSettingsController::update() logs 'settings_changed' event
     * Category: committee
     * Metadata: changes array (what settings changed)
     */
    public function test_update_logs_settings_changed_event(): void
    {
        // Setup
        $organisation = Organisation::factory()->create();

        $election = Election::factory()->create([
            'organisation_id' => $organisation->id,
            'type' => 'real',
            'status' => 'active',
            'ip_restriction_enabled' => true,  // Start with true
            'ip_restriction_max_per_ip' => 1,  // Start with 1
            'no_vote_option_enabled' => false,
            'selection_constraint_type' => 'any',
            'selection_constraint_min' => null,
            'selection_constraint_max' => null,
        ]);

        // Create admin user
        $admin = User::factory()->create(['organisation_id' => $organisation->id]);
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $admin->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Act: Update election settings to different values
        $response = $this->actingAs($admin)
            ->patch(route('elections.settings.update', [
                'election' => $election->slug,
            ]), [
                'settings_version' => $election->settings_version,
                'ip_restriction_enabled' => false,  // Change from true to false
                'ip_restriction_max_per_ip' => 5,    // Change from 1 to 5
                'no_vote_option_enabled' => true,    // Change from false to true
                'selection_constraint_type' => 'exact',  // Change from 'any' to 'exact'
                'selection_constraint_min' => 2,
                'selection_constraint_max' => 2,
            ]);

        // Assert: Successful redirect
        $response->assertRedirect();

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'committee.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('settings_changed', $entry['event']);
        $this->assertEquals($admin->id, $entry['user_id']);
        $this->assertEquals('committee', $entry['category']);

        // Assert: Metadata contains changes
        $this->assertIsArray($entry['metadata']['changes'] ?? null, "changes metadata not present or not an array");

        // Should contain the settings that changed
        $changes = $entry['metadata']['changes'];
        $this->assertNotEmpty($changes, "No changes recorded in metadata");

        // Verify specific changes are tracked
        $changedFields = array_keys($changes);
        $this->assertTrue(
            in_array('ip_restriction_enabled', $changedFields) ||
            in_array('ip_restriction_max_per_ip', $changedFields) ||
            in_array('description', $changedFields),
            "Changed fields not properly tracked in audit log"
        );
    }

    /**
     * Helper: Get the audit log file path for an election
     */
    private function getLogFilePath(Election $election, string $filename): string
    {
        $startDate = $election->start_date ?? now();
        $folderName = sprintf(
            '%s_%s_%s',
            $election->slug,
            $startDate->format('Ymd'),
            $startDate->format('Hi')
        );

        return $this->auditBasePath . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $filename;
    }
}
