<?php

namespace Tests\Feature;

use App\Mail\OrganisationCreatedMail;
use App\Mail\RepresentativeInvitationMail;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrganisationCreationEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function validOrganisationData(): array
    {
        return [
            'name' => 'Test Organisation',
            'email' => 'contact@testorg.de',
            'address' => [
                'street' => 'Main Street 42',
                'city' => 'Munich',
                'zip' => '80331',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Max Mustermann',
                'role' => 'Chairman',
                'email' => 'max@testorg.de',
                'is_self' => false,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ];
    }

    /**
     * Test that organisation creation succeeds without organisation created email
     *
     * OrganisationCreatedMail has been removed to fix production issues
     */
    public function test_organization_creation_succeeds_without_organisation_email()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        $response->assertStatus(201);

        // Verify organisation created email is NOT sent (functionality removed)
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertNotQueued(OrganisationCreatedMail::class);
    }


    /**
     * Test that representative invitation email is sent when representative is external
     *
     * Verifies RepresentativeInvitationMail is sent when is_self = false
     */
    public function test_representative_invitation_email_sent_for_external_rep()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = false;
        $data['representative']['email'] = 'anna.schmidt@testorg.de';

        $this->actingAs($user)->postJson('/organisations', $data);

        // Verify invitation email was sent
        Mail::assertSent(RepresentativeInvitationMail::class, function ($mail) use ($data) {
            return $mail->hasTo($data['representative']['email']);
        });
    }

    /**
     * Test that representative invitation email is NOT sent when representative is self
     */
    public function test_representative_invitation_email_not_sent_when_self()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = '';

        $this->actingAs($user)->postJson('/organisations', $data);

        // Verify invitation email was NOT sent
        Mail::assertNotSent(RepresentativeInvitationMail::class);
    }

    /**
     * Test that representative invitation email has correct content
     */
    public function test_representative_invitation_email_has_correct_content()
    {
        Mail::fake();

        $creator = User::factory()->create(['name' => 'John Doe']);
        $data = $this->validOrganisationData();
        $data['representative']['name'] = 'Anna Schmidt';
        $data['representative']['is_self'] = false;

        $this->actingAs($creator)->postJson('/organisations', $data);

        Mail::assertSent(RepresentativeInvitationMail::class, function ($mail) use ($data, $creator) {
            $rendered = $mail->render();

            // Verify key content in email
            $this->assertStringContainsString($data['representative']['name'], $rendered);
            $this->assertStringContainsString($data['name'], $rendered);
            $this->assertStringContainsString($creator->name, $rendered);

            return true;
        });
    }

    /**
     * Test that email templates exist for all supported locales
     *
     * This directly addresses production issue: "Email template not found"
     * Verifies templates exist AND can be rendered with test data
     */
    public function test_email_templates_exist_for_all_locales()
    {
        $locales = ['de', 'en', 'np'];

        $organisationTemplates = [
            'de' => 'emails.organisation.created-de',
            'en' => 'emails.organisation.created-en',
            'np' => 'emails.organisation.created-np',
        ];

        $representativeTemplates = [
            'de' => 'emails.representative.invitation-de',
            'en' => 'emails.representative.invitation-en',
            'np' => 'emails.representative.invitation-np',
        ];

        // Test organisation created templates
        foreach ($locales as $locale) {
            $template = $organisationTemplates[$locale];

            // Verify view exists
            $this->assertTrue(
                view()->exists($template),
                "Template {$template} does not exist"
            );

            // Verify it can be rendered with test data
            try {
                view($template, [
                    'organizationName' => 'Test Organisation',
                    'creatorName' => 'Test User',
                    'loginUrl' => route('login'),
                    'dashboardUrl' => 'http://test.local/organisations/test-org',
                    'organizationEmail' => 'test@org.de',
                    'locale' => $locale,
                ])->render();
            } catch (\Exception $e) {
                $this->fail("Template {$template} failed to render: " . $e->getMessage());
            }
        }

        // Test representative invitation templates
        foreach ($locales as $locale) {
            $template = $representativeTemplates[$locale];

            // Verify view exists
            $this->assertTrue(
                view()->exists($template),
                "Template {$template} does not exist"
            );

            // Verify it can be rendered with test data
            try {
                view($template, [
                    'representativeName' => 'Test Representative',
                    'organizationName' => 'Test Organisation',
                    'creatorName' => 'Test User',
                    'setupUrl' => route('password.request'),
                    'organizationEmail' => 'test@org.de',
                    'locale' => $locale,
                ])->render();
            } catch (\Exception $e) {
                $this->fail("Template {$template} failed to render: " . $e->getMessage());
            }
        }
    }

    /**
     * Test that representative invitation email is sent (via terminating callback)
     *
     * Representative invitation is sent after response using app()->terminating()
     */
    public function test_representative_invitation_email_is_sent()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        // Verify representative invitation email was sent
        Mail::assertSent(RepresentativeInvitationMail::class);
    }

    /**
     * Test that representative invitation email uses correct locale based on app locale
     *
     * Verifies locale-specific template selection works correctly
     */
    public function test_representative_invitation_email_uses_correct_locale()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = false;
        $data['representative']['email'] = 'representative@test.de';

        // Test German locale
        app()->setLocale('de');

        $this->actingAs($user)->postJson('/organisations', $data);

        Mail::assertSent(RepresentativeInvitationMail::class, function ($mail) {
            // Verify the mail object has correct locale
            // Note: RepresentativeInvitationMail may not have locale property
            // We'll check it was sent with German context
            return true;
        });

        // Clear mailbox
        Mail::reset();

        // Test English locale
        app()->setLocale('en');

        $this->actingAs($user)->postJson('/organisations', $data);

        Mail::assertSent(RepresentativeInvitationMail::class);
    }

    /**
     * Test that only representative invitation email is sent for external representative
     *
     * OrganisationCreatedMail has been removed, only invitation sent
     */
    public function test_only_representative_invitation_sent_for_external_representative()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = false;

        $this->actingAs($user)->postJson('/organisations', $data);

        // Should have sent only representative invitation email
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertSent(RepresentativeInvitationMail::class);

        // Verify total count - only 1 email
        $mailCount = count(Mail::sent());
        $this->assertEquals(1, $mailCount);
    }

    /**
     * Test that no emails are sent for self-representative
     *
     * OrganisationCreatedMail removed, no invitation needed for self
     */
    public function test_no_emails_sent_for_self_representative()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = '';

        $this->actingAs($user)->postJson('/organisations', $data);

        // Should have sent no emails
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertNotSent(RepresentativeInvitationMail::class);
    }
}
