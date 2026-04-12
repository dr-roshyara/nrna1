<?php

namespace Tests\Feature\Membership;

use App\Models\MembershipApplication;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApplicationSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['slug' => 'test-org']);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name'       => 'Jane',
            'last_name'        => 'Smith',
            'email'            => 'jane@example.com',
            'telephone_number' => '+49 123 456789',
            'education_level'  => "Bachelor's Degree",
            'city'             => 'Berlin',
            'country'          => 'Germany',
            'profession'       => 'Engineer',
            'message'          => 'I would like to join.',
            'website'          => '',
        ], $overrides);
    }

    public function test_public_join_page_renders_without_auth(): void
    {
        $response = $this->get("/organisations/{$this->org->slug}/join");
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Organisations/Membership/PublicApply'));
    }

    public function test_valid_submission_creates_membership_application(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join", $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('membership_applications', [
            'organisation_id' => $this->org->id,
            'applicant_email' => 'jane@example.com',
            'source'          => 'public',
            'status'          => 'submitted',
        ]);

        $this->assertNull(
            MembershipApplication::withoutGlobalScopes()->where('applicant_email', 'jane@example.com')->first()->user_id,
            'user_id must be null for public applications'
        );
    }

    public function test_application_data_stores_all_fields(): void
    {
        $this->post("/organisations/{$this->org->slug}/join", $this->validPayload());

        $app = MembershipApplication::withoutGlobalScopes()->where('applicant_email', 'jane@example.com')->first();

        $this->assertEquals('Jane',            $app->application_data['first_name']);
        $this->assertEquals('Smith',           $app->application_data['last_name']);
        $this->assertEquals('Engineer',        $app->application_data['profession']);
        $this->assertEquals("Bachelor's Degree", $app->application_data['education_level']);
        $this->assertEquals('Berlin',          $app->application_data['city']);
        $this->assertEquals('Germany',         $app->application_data['country']);
        $this->assertEquals('I would like to join.', $app->application_data['message']);
    }

    public function test_first_name_is_required(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join",
            $this->validPayload(['first_name' => '']));
        $response->assertSessionHasErrors('first_name');
    }

    public function test_last_name_is_required(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join",
            $this->validPayload(['last_name' => '']));
        $response->assertSessionHasErrors('last_name');
    }

    public function test_email_is_required(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join",
            $this->validPayload(['email' => '']));
        $response->assertSessionHasErrors('email');
    }

    public function test_email_must_be_valid_format(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join",
            $this->validPayload(['email' => 'not-an-email']));
        $response->assertSessionHasErrors('email');
    }

    public function test_optional_fields_can_be_omitted(): void
    {
        $response = $this->post("/organisations/{$this->org->slug}/join", [
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'email'      => 'jane@example.com',
            'website'    => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('membership_applications', [
            'applicant_email' => 'jane@example.com',
        ]);
    }

    public function test_invalid_organisation_slug_returns_404(): void
    {
        $response = $this->get('/organisations/nonexistent-org/join');
        $response->assertStatus(404);
    }
}
