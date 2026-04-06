<?php

namespace Tests\Feature\Membership;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class ParticipantImportErrorMessagesTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);
    }

    private function makeCsv(string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'participant_import_') . '.csv';
        file_put_contents($path, $content);
        return new UploadedFile($path, 'participants.csv', 'text/csv', null, true);
    }

    // ── Error message tests ───────────────────────────────────────────────────

    public function test_error_message_for_nonexistent_user_includes_invite_instruction(): void
    {
        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "nonexistent@example.com,staff,coordinator,,\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $error = $response->json('preview.0.errors.0');

        $this->assertStringContainsStringIgnoringCase('does not exist', $error);
        $this->assertStringContainsStringIgnoringCase('invite', $error);
    }

    public function test_error_message_for_invalid_participant_type_shows_valid_options(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "test@example.com,invalid_role,coordinator,,\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $error = $response->json('preview.0.errors.0');

        $this->assertStringContainsStringIgnoringCase('staff', $error);
        $this->assertStringContainsStringIgnoringCase('guest', $error);
        $this->assertStringContainsStringIgnoringCase('election_committee', $error);
    }

    public function test_error_message_for_invalid_date_format_shows_expected_format(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "test@example.com,guest,observer,invalid-date,\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $error = $response->json('preview.0.errors.0');

        $this->assertStringContainsStringIgnoringCase('YYYY-MM-DD', $error);
    }

    public function test_error_message_for_past_expiry_date_includes_todays_date(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "test@example.com,guest,observer,2020-01-01,\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $error = $response->json('preview.0.errors.0');

        $this->assertStringContainsStringIgnoringCase('future', $error);
        $this->assertStringContainsString(date('Y-m-d'), $error);
    }

    public function test_error_message_for_invalid_json_shows_example(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "test@example.com,staff,coordinator,,{invalid json}\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $error = $response->json('preview.0.errors.0');

        $this->assertStringContainsString('{"key":"value"}', $error);
    }

    public function test_multiple_errors_on_same_row_are_all_returned(): void
    {
        // User doesn't exist AND invalid type — both errors should appear
        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "nonexistent@example.com,invalid_type,coordinator,,\n";

        $response = $this->actingAs($this->admin)
            ->postJson(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk();
        $errors = $response->json('preview.0.errors');

        $this->assertIsArray($errors);
        $this->assertGreaterThanOrEqual(2, count($errors));
    }

    public function test_success_message_includes_created_and_participant_wording(): void
    {
        User::factory()->create(['email' => 'valid@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "valid@example.com,staff,coordinator,,\n";

        $response = $this->actingAs($this->admin)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                [
                    'file'      => $this->makeCsv($csv),
                    'confirmed' => '1',
                ]
            );

        $response->assertRedirect();
        $message = $response->getSession()->get('success');

        $this->assertNotNull($message);
        $this->assertStringContainsStringIgnoringCase('created', $message);
        $this->assertStringContainsStringIgnoringCase('participant', $message);
    }
}
