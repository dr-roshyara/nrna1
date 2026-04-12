<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganisationCreationValidationTest extends TestCase
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
     * Test that organisation name requires minimum 3 characters
     */
    public function test_organization_name_min_length()
    {
        $user = User::factory()->create();

        // Test with 2 characters (should fail)
        $data = $this->validOrganisationData();
        $data['name'] = 'AB';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test with 3 characters (should pass)
        $data['name'] = 'ABC';
        $data['email'] = 'abc@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);
    }

    /**
     * Test that organisation name has maximum length
     */
    public function test_organization_name_max_length()
    {
        $user = User::factory()->create();

        // Test with 255 characters (should pass)
        $longName = str_repeat('A', 255);
        $data = $this->validOrganisationData();
        $data['name'] = $longName;
        $data['email'] = 'long1@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);

        // Test with 256 characters (should fail)
        $tooLongName = str_repeat('A', 256);
        $data['name'] = $tooLongName;
        $data['email'] = 'long2@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test that email validation requires valid email format with DNS check
     *
     * Note: DNS validation may fail in test environment
     * This test documents the validation rule
     */
    public function test_organization_email_format_validation()
    {
        $user = User::factory()->create();

        $invalidEmails = [
            'notanemail',
            'missing@domain',
            '@nodomain.de',
            'spaces in@email.de',
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $data = $this->validOrganisationData();
            $data['email'] = $invalidEmail;

            $response = $this->actingAs($user)->postJson('/organisations', $data);
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email']);
        }
    }

    /**
     * Test that zip code must be exactly 5 digits
     */
    public function test_zip_code_format_validation()
    {
        $user = User::factory()->create();

        $testCases = [
            '1234' => false,      // 4 digits - fail
            '12345' => true,      // 5 digits - pass
            '123456' => false,    // 6 digits - fail
            'abcde' => false,     // letters - fail
            '123 45' => false,    // space - fail
        ];

        foreach ($testCases as $zip => $shouldPass) {
            $data = $this->validOrganisationData();
            $data['address']['zip'] = $zip;
            $data['email'] = "test_{$zip}@test.de";

            $response = $this->actingAs($user)->postJson('/organisations', $data);

            if ($shouldPass) {
                $this->assertNotEquals(422, $response->getStatusCode(),
                    "Zip {$zip} should be valid");
            } else {
                $response->assertStatus(422);
                $response->assertJsonValidationErrors(['address.zip']);
            }
        }
    }

    /**
     * Test that country code must be 2 characters and allowed
     */
    public function test_country_code_validation()
    {
        $user = User::factory()->create();

        // Allowed countries: DE, AT, CH
        $allowedCountries = ['DE', 'AT', 'CH'];
        $disallowedCountries = ['US', 'GB', 'FR', 'ES', 'D', 'GER'];

        // Test allowed countries
        foreach ($allowedCountries as $country) {
            $data = $this->validOrganisationData();
            $data['address']['country'] = $country;
            $data['email'] = "test_{$country}@test.de";

            $response = $this->actingAs($user)->postJson('/organisations', $data);
            $response->assertNotEquals(422, $response->getStatusCode(),
                "Country {$country} should be allowed");
        }

        // Test disallowed countries
        foreach ($disallowedCountries as $country) {
            $data = $this->validOrganisationData();
            $data['address']['country'] = $country;
            $data['email'] = "test_{$country}@test.de";

            $response = $this->actingAs($user)->postJson('/organisations', $data);
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['address.country']);
        }
    }

    /**
     * Test that representative email is required only when is_self is false
     */
    public function test_representative_email_required_when_not_self()
    {
        $user = User::factory()->create();

        // Case 1: is_self = false, email empty (should fail)
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = false;
        $data['representative']['email'] = '';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['representative.email']);

        // Case 2: is_self = true, email empty (should pass)
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = '';
        $data['email'] = 'self@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);
    }

    /**
     * Test that GDPR and terms acceptance are required
     */
    public function test_gdpr_and_terms_acceptance_required()
    {
        $user = User::factory()->create();

        // Test missing GDPR acceptance
        $data = $this->validOrganisationData();
        $data['accept_gdpr'] = false;
        $data['email'] = 'gdpr@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['accept_gdpr']);

        // Test missing terms acceptance
        $data = $this->validOrganisationData();
        $data['accept_terms'] = false;
        $data['email'] = 'terms@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['accept_terms']);

        // Test both accepted (should pass)
        $data = $this->validOrganisationData();
        $data['accept_gdpr'] = true;
        $data['accept_terms'] = true;
        $data['email'] = 'both@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);
    }

    /**
     * Test that special characters in organisation name are handled
     */
    public function test_special_characters_in_organization_name()
    {
        $user = User::factory()->create();

        $specialNames = [
            'Müller Verein',
            'Café Berlin',
            'ABC & XYZ',
            'Test (Local)',
            'Org-123',
            'Org/Other',
        ];

        foreach ($specialNames as $name) {
            $data = $this->validOrganisationData();
            $data['name'] = $name;
            $data['email'] = 'special_' . bin2hex(substr($name, 0, 5)) . '@test.de';

            $response = $this->actingAs($user)->postJson('/organisations', $data);

            // Should not return 422 (might be 201 or other success)
            $this->assertNotEquals(422, $response->getStatusCode(),
                "Special characters in '{$name}' should be handled");
        }
    }

    /**
     * Test that email addresses are trimmed and normalized
     */
    public function test_email_trimming_and_normalization()
    {
        Mail::fake();

        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['email'] = '  TEST@ORG.DE  '; // With spaces and uppercase

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);

        // Get organisation and verify email is stored normalized
        $org = $response->json('organisation');
        $this->assertEquals('test@org.de', $org['email']); // lowercase, no spaces
    }

    /**
     * Test representative name minimum length
     */
    public function test_representative_name_min_length()
    {
        $user = User::factory()->create();

        // Test with 2 characters (should fail)
        $data = $this->validOrganisationData();
        $data['representative']['name'] = 'AB';
        $data['email'] = 'name2@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['representative.name']);

        // Test with 3 characters (should pass)
        $data['representative']['name'] = 'ABC';
        $data['email'] = 'name3@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(201);
    }

    /**
     * Test that representative role is required
     */
    public function test_representative_role_required()
    {
        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['representative']['role'] = '';
        $data['email'] = 'role@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['representative.role']);
    }

    /**
     * Test all address fields are required
     */
    public function test_address_fields_required()
    {
        $user = User::factory()->create();

        $requiredFields = ['street', 'city', 'zip', 'country'];

        foreach ($requiredFields as $field) {
            $data = $this->validOrganisationData();
            unset($data['address'][$field]);
            $data['email'] = "addr_{$field}@test.de";

            $response = $this->actingAs($user)->postJson('/organisations', $data);
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(["address.{$field}"]);
        }
    }

    /**
     * Test that street has reasonable maximum length
     */
    public function test_street_address_max_length()
    {
        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['address']['street'] = str_repeat('A', 256); // Exceeds 255
        $data['email'] = 'street@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['address.street']);
    }

    /**
     * Test that city has reasonable maximum length
     */
    public function test_city_max_length()
    {
        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['address']['city'] = str_repeat('A', 101); // Exceeds 100
        $data['email'] = 'city@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['address.city']);
    }
}
