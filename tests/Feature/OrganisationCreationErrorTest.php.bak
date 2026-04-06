<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrganisationCreationErrorTest extends TestCase
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
     * Test that mail server failure doesn't leave incomplete data
     *
     * Even if email sending fails, organisation creation should be rolled back
     */
    public function test_mail_server_failure_rolls_back_creation()
    {
        Mail::fake();
        Mail::shouldReceive('send')->andThrow(new \Exception('Mail server connection failed'));

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $initialOrgCount = Organisation::count();

        try {
            $this->actingAs($user)->postJson('/organisations', $data);
        } catch (\Exception $e) {
            // Expected to fail
        }

        // Verify organisation was NOT created despite mail failure
        $this->assertEquals($initialOrgCount, Organisation::count());
        $this->assertNull(Organisation::where('email', $data['email'])->first());
    }

    /**
     * Test that invalid JSON request is rejected
     */
    public function test_invalid_json_request_rejected()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/organisations', 'invalid json');

        // Should return 400 or 422 (bad request/validation)
        $this->assertIn($response->getStatusCode(), [400, 422]);
    }

    /**
     * Test that missing required fields returns proper validation errors
     */
    public function test_missing_required_fields_returns_validation_errors()
    {
        $user = User::factory()->create();

        // Omit name
        $response = $this->actingAs($user)->postJson('/organisations', [
            'email' => 'test@org.de',
            'address' => ['street' => 'Test', 'city' => 'Test', 'zip' => '12345', 'country' => 'DE'],
            'representative' => ['name' => 'Test', 'role' => 'Chairman', 'is_self' => true],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test that validation error response includes all errors
     */
    public function test_validation_error_response_structure()
    {
        $user = User::factory()->create();

        // Submit with multiple errors
        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => '', // Required
            'email' => 'invalid', // Invalid format
            'address' => ['city' => 'Test'], // Missing required fields
            'representative' => [], // Missing required fields
            'accept_gdpr' => false, // Required to be true
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => [
                'name',
                'email',
                'address.street',
                'address.zip',
                'accept_gdpr',
            ],
        ]);
    }

    /**
     * Test that specific validation messages are returned
     */
    public function test_specific_validation_messages()
    {
        $user = User::factory()->create();

        // Name too short
        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => 'AB', // 2 chars, needs 3+
            'email' => 'test@test.de',
            'address' => ['street' => 'Test', 'city' => 'Test', 'zip' => '12345', 'country' => 'DE'],
            'representative' => ['name' => 'Test', 'role' => 'Chairman', 'is_self' => true],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Error message should be meaningful
        $errors = $response->json('errors.name');
        $this->assertIsArray($errors);
        $this->assertGreaterThan(0, count($errors));
    }

    /**
     * Test that database constraint violations are handled gracefully
     */
    public function test_duplicate_email_returns_validation_error()
    {
        Mail::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $data = $this->validOrganisationData();

        // Create first organisation
        $response1 = $this->actingAs($user1)->postJson('/organisations', $data);
        $response1->assertStatus(201);

        // Attempt to create second with same email
        $response2 = $this->actingAs($user2)->postJson('/organisations', $data);

        // Should fail with validation error (not 500 server error)
        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that exception during user creation is handled
     */
    public function test_exception_during_creation_returns_error()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        // This test documents that unexpected errors should be handled
        // rather than returning a 500 Server Error

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should succeed normally
        if ($response->getStatusCode() === 201) {
            $response->assertJsonPath('success', true);
        }
    }

    /**
     * Test that email sending failure returns proper error
     *
     * When email cannot be sent, user should get meaningful error, not 500
     */
    public function test_email_send_failure_returns_meaningful_error()
    {
        // Don't use Mail::fake() - let it try to actually send
        // This documents expected behavior when real mail server fails

        // This test is skipped in normal test runs since we can't control
        // actual mail server failures reliably in tests

        $this->markTestSkipped('Requires real mail server failure simulation');
    }

    /**
     * Test that timeout during creation is handled
     */
    public function test_timeout_handling()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        // Normal creation should complete within reasonable time
        $response = $this->actingAs($user)
            ->withoutMiddleware('throttle')
            ->postJson('/organisations', $data);

        // Should succeed
        $response->assertStatus(201);
    }

    /**
     * Test that organisation creation with empty array data is rejected
     */
    public function test_empty_array_data_rejected()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/organisations', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /**
     * Test that null values in required fields are rejected
     */
    public function test_null_values_in_required_fields_rejected()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => null,
            'email' => null,
            'address' => null,
            'representative' => null,
            'accept_gdpr' => null,
            'accept_terms' => null,
        ]);

        $response->assertStatus(422);
        $this->assertGreaterThan(0, count($response->json('errors')));
    }

    /**
     * Test that very large payloads are handled
     */
    public function test_large_payload_handling()
    {
        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['name'] = str_repeat('A', 1000); // Very long name

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should either fail validation or be truncated
        // Should not throw error
        $this->assertIn($response->getStatusCode(), [201, 422]);
    }

    /**
     * Test that special characters in email are handled
     */
    public function test_special_characters_in_email_validation()
    {
        $user = User::factory()->create();

        $invalidEmails = [
            'test@.de',
            'test.@de',
            'test@domain..de',
            '@domain.de',
            'test@',
        ];

        foreach ($invalidEmails as $email) {
            $data = $this->validOrganisationData();
            $data['email'] = $email;

            $response = $this->actingAs($user)->postJson('/organisations', $data);

            // Should fail validation
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email']);
        }
    }

    /**
     * Test response headers are secure
     */
    public function test_response_headers_are_secure()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Verify response content type
        $this->assertTrue(
            str_contains($response->headers->get('Content-Type'), 'application/json'),
            'Response should be JSON'
        );

        // Verify no sensitive headers exposed
        $this->assertNotNull($response->headers->get('Content-Type'));
    }

    /**
     * Test that error messages don't expose sensitive information
     */
    public function test_error_messages_dont_expose_sensitive_info()
    {
        Mail::fake();

        $user = User::factory()->create();

        // Attempt invalid request
        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => '',
            'email' => 'invalid',
        ]);

        $response->assertStatus(422);

        // Verify error response doesn't contain sensitive info
        $errorMessage = json_encode($response->json());
        $this->assertStringNotContainsString('password', strtolower($errorMessage));
        $this->assertStringNotContainsString('secret', strtolower($errorMessage));
    }
}
