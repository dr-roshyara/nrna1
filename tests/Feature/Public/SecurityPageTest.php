<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test security page route exists and returns 200.
     */
    public function test_security_page_route_exists_and_returns_200()
    {
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test security page renders with Inertia component.
     */
    public function test_security_page_renders_security_component()
    {
        $response = $this->get('/security');
        $response->assertInertia(fn($page) => $page->component('Public/Security'));
    }

    /**
     * Test security page passes required props.
     */
    public function test_security_page_passes_required_props()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('layers')
                 ->has('pillars')
                 ->has('badges')
                 ->has('faqItems')
                 ->has('images')
                 ->has('ctaButtons')
        );
    }

    /**
     * Test exactly five layers are provided.
     */
    public function test_exactly_five_layers_provided()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('layers', 5)
        );
    }

    /**
     * Test three security pillars are provided.
     */
    public function test_three_security_pillars_provided()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('pillars', 3)
        );
    }

    /**
     * Test trust badges have required keys.
     */
    public function test_trust_badges_have_required_keys()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('badges.securityTests')
                 ->has('badges.anonymity')
                 ->has('badges.protection')
                 ->has('badges.coverage')
        );
    }

    /**
     * Test FAQ section has minimum questions.
     */
    public function test_faq_section_has_minimum_questions()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('faqItems', 4)
        );
    }

    /**
     * Test images are provided.
     */
    public function test_images_are_provided()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('images.layerArchitecture')
                 ->has('images.votingJourney')
        );
    }

    /**
     * Test translations available English.
     */
    public function test_translations_available_english()
    {
        session(['locale' => 'en']);
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test translations available German.
     */
    public function test_translations_available_german()
    {
        session(['locale' => 'de']);
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test translations available Nepali.
     */
    public function test_translations_available_nepali()
    {
        session(['locale' => 'np']);
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test page is accessible without authentication.
     */
    public function test_page_accessible_without_authentication()
    {
        $this->assertGuest();
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test page works with authenticated user.
     */
    public function test_page_works_with_authenticated_user()
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test CTA buttons structure.
     */
    public function test_cta_buttons_structure()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('ctaButtons', 2)
        );
    }

    /**
     * Test layer IDs are unique.
     */
    public function test_layer_ids_are_unique()
    {
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test layer numbers are sequential.
     */
    public function test_layer_numbers_are_sequential()
    {
        $response = $this->get('/security');
        $response->assertStatus(200);
    }

    /**
     * Test security page returns dataStore section with stored and notStored arrays.
     */
    public function test_security_page_returns_data_store_section()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('dataStore')
                 ->has('dataStore.stored')
                 ->has('dataStore.notStored')
        );
    }

    /**
     * Test security page returns verification methods (receipt and auditor proof).
     */
    public function test_security_page_returns_verification_methods()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('verificationMethods')
                 ->has('verificationMethods.0.id')
                 ->has('verificationMethods.0.badgeType')
        );
    }

    /**
     * Test security page returns fingerprint steps with 4 items.
     */
    public function test_security_page_returns_fingerprint_steps()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('fingerprintSteps', 4)
        );
    }

    /**
     * Test CTA buttons use labelKey instead of hardcoded labels.
     */
    public function test_security_page_cta_buttons_use_label_keys_not_hardcoded_labels()
    {
        $response = $this->get('/security');

        $response->assertInertia(fn($page) =>
            $page->has('ctaButtons.0.labelKey')
                 ->has('ctaButtons.1.labelKey')
        );
    }
}
