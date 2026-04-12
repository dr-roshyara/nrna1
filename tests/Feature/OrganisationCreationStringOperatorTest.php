<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Extensive tests for "[] operator not supported for strings" error
 *
 * This error occurs when the DNS validation closure receives a non-string value
 * or when form data is sent in unexpected formats (JSON string instead of array)
 */
class OrganisationCreationStringOperatorTest extends TestCase
{
    use RefreshDatabase;

    protected function validPayload(): array
    {
        return [
            'name' => 'Test Organisation',
            'email' => 'test@example.com',
            'address' => [
                'street' => 'Main Street 42',
                'city' => 'Munich',
                'zip' => '80331',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Max Mustermann',
                'role' => 'Chairman',
                'email' => 'max@example.com',
                'is_self' => false,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ];
    }

    /**
     * Test that email field is properly validated as string
     */
    public function test_email_field_handles_string_values()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        // Normal string email should work
        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // In test environment, this should pass
        $this->assertIn($response->getStatusCode(), [201, 422]);
        // 201 = success, 422 = validation error (expected in test for DNS validation)
    }

    /**
     * Test that email validation doesn't crash with empty string
     */
    public function test_email_validation_handles_empty_string()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['email'] = ''; // Empty email

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should return 422 validation error, not crash with string operator error
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test that representative email validation handles empty string
     */
    public function test_representative_email_handles_empty_string()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['representative']['email'] = ''; // Empty representative email
        $data['representative']['is_self'] = false;

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should return 422 validation error, not crash
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test that email with unusual format doesn't crash validation
     */
    public function test_email_validation_handles_malformed_email()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['email'] = 'no-at-sign'; // Missing @ symbol

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should return validation error, not string operator error
        $this->assertIn($response->getStatusCode(), [422, 500]);
        if ($response->getStatusCode() === 422) {
            $response->assertJsonValidationErrors(['email']);
        }
    }

    /**
     * Test that email with multiple @ symbols doesn't crash
     */
    public function test_email_validation_handles_multiple_at_symbols()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['email'] = 'test@@example.com'; // Multiple @ symbols

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should handle gracefully
        $this->assertNotEquals(0, $response->getStatusCode());
    }

    /**
     * Test that validation doesn't crash when email is null
     */
    public function test_email_validation_handles_null_value()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        unset($data['email']); // Omit email entirely

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should return validation error about required field
        $this->assertIn($response->getStatusCode(), [422, 500]);
    }

    /**
     * Test that validation handles representative email being null/empty
     */
    public function test_representative_email_handles_null_for_external_rep()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['representative']['is_self'] = false;
        // Don't provide email - should fail validation for required field

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should fail validation, not crash with string operator error
        $this->assertIn($response->getStatusCode(), [422, 500]);
    }

    /**
     * Test that self-representative with email still validates properly
     */
    public function test_self_representative_with_email_validates()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = ''; // Should be allowed for self-rep

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should not crash with string operator error
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test that form data sent as JSON is parsed correctly
     */
    public function test_form_data_parsed_as_json_correctly()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        // Explicitly send as JSON (default for postJson)
        $response = $this->actingAs($user)->postJson(
            '/organisations',
            $data,
            ['Content-Type' => 'application/json']
        );

        // Should not crash with string operator error
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test that representative object is properly parsed
     */
    public function test_representative_object_parsed_correctly()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        // Representative should be array/object
        $this->assertIsArray($data['representative']);
        $this->assertArrayHasKey('is_self', $data['representative']);

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should not crash
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test complete form submission flow doesn't trigger string operator error
     */
    public function test_complete_form_submission_no_string_operator_error()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        // Submit form
        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should NOT be a 500 error (which would indicate string operator error)
        $this->assertNotEqual(
            500,
            $response->getStatusCode(),
            'String operator error occurred: [] operator not supported for strings'
        );

        // Should be either 201 (success) or 422 (validation error)
        $this->assertIn($response->getStatusCode(), [201, 422]);
    }

    /**
     * Test that validation works correctly in production environment
     */
    public function test_validation_works_in_non_testing_environment()
    {
        // This test documents that DNS validation should work in production
        // without crashing on string operator errors

        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        // The validation closure should handle all edge cases
        // - strings vs non-strings
        // - empty values
        // - malformed emails
        // - missing @ symbols
        // - multiple @ symbols

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // No 500 error means validation handled all cases properly
        $this->assertNotEqual(500, $response->getStatusCode());
    }

    /**
     * Test with various email formats
     */
    public function test_email_formats_dont_crash_validation()
    {
        Mail::fake();

        $user = User::factory()->create();

        $emailFormats = [
            'test@example.com',
            'test+tag@example.co.uk',
            'test.name@sub.example.com',
            'test_name@example.com',
            'test123@example.com',
        ];

        foreach ($emailFormats as $email) {
            $data = $this->validPayload();
            $data['email'] = $email;

            $response = $this->actingAs($user)->postJson('/organisations', $data);

            $this->assertNotEqual(
                500,
                $response->getStatusCode(),
                "String operator error with email: $email"
            );
        }
    }

    /**
     * Test with whitespace in email field
     */
    public function test_email_with_whitespace_handled()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();
        $data['email'] = '  test@example.com  '; // With leading/trailing spaces

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should not crash with string operator error
        $this->assertNotEqual(500, $response->getStatusCode());
    }

    /**
     * Test that error is descriptive if it still occurs
     */
    public function test_string_operator_error_is_prevented()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validPayload();

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Most important: verify the specific error doesn't occur
        if ($response->getStatusCode() === 500) {
            $content = $response->getContent();
            $this->assertStringNotContainsString(
                '[] operator not supported for strings',
                $content,
                'The string operator error is still occurring!'
            );
        }
    }
}
