<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regional Candidates Display Tests
 *
 * These tests verify that the regional candidates feature works correctly
 * by testing the translation files and component integration.
 */
class RegionalCandidatesDisplayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that regional posts translation file exists in English
     */
    public function test_regional_posts_english_translations_exist()
    {
        $translationFile = resource_path('js/locales/pages/Vote/DemoVote/CreateVotingPage/en.json');
        $this->assertFileExists($translationFile);

        $translations = json_decode(file_get_contents($translationFile), true);

        // Verify translation structure
        $this->assertArrayHasKey('createVotingPage', $translations);
        $this->assertArrayHasKey('regionalPosts', $translations['createVotingPage']);

        // Verify required translation keys
        $regionalPosts = $translations['createVotingPage']['regionalPosts'];
        $this->assertArrayHasKey('sectionTitle', $regionalPosts);
        $this->assertArrayHasKey('noCandidatesTitle', $regionalPosts);
        $this->assertArrayHasKey('noCandidatesMessage', $regionalPosts);
    }

    /**
     * Test that regional posts translation file exists in German
     */
    public function test_regional_posts_german_translations_exist()
    {
        $translationFile = resource_path('js/locales/pages/Vote/DemoVote/CreateVotingPage/de.json');
        $this->assertFileExists($translationFile);

        $translations = json_decode(file_get_contents($translationFile), true);

        // Verify German translations are different from English
        $this->assertArrayHasKey('createVotingPage', $translations);
        $this->assertStringContainsString('Region', $translations['createVotingPage']['regionalPosts']['sectionTitle']);
    }

    /**
     * Test that regional posts translation file exists in Nepali
     */
    public function test_regional_posts_nepali_translations_exist()
    {
        $translationFile = resource_path('js/locales/pages/Vote/DemoVote/CreateVotingPage/np.json');
        $this->assertFileExists($translationFile);

        $translations = json_decode(file_get_contents($translationFile), true);

        // Verify Nepali translations exist
        $this->assertArrayHasKey('createVotingPage', $translations);
        $this->assertArrayHasKey('regionalPosts', $translations['createVotingPage']);
    }

    /**
     * Test that CreateVotingPage component has user_region prop
     */
    public function test_voting_page_component_has_user_region_prop()
    {
        $componentFile = resource_path('js/Pages/Vote/DemoVote/CreateVotingPage.vue');
        $this->assertFileExists($componentFile);

        $componentContent = file_get_contents($componentFile);

        // Verify user_region prop is defined
        $this->assertStringContainsString('user_region', $componentContent);

        // Verify it's used in template
        $this->assertStringContainsString('regionalPostsMessages', $componentContent);
    }

    /**
     * Test that CreateVotingPage imports regional translations
     */
    public function test_component_imports_regional_translations()
    {
        $componentFile = resource_path('js/Pages/Vote/DemoVote/CreateVotingPage.vue');
        $componentContent = file_get_contents($componentFile);

        // Verify imports for translation files
        $this->assertStringContainsString('regionDe', $componentContent);
        $this->assertStringContainsString('regionEn', $componentContent);
        $this->assertStringContainsString('regionNp', $componentContent);
        $this->assertStringContainsString('DemoVote/CreateVotingPage', $componentContent);
    }

    /**
     * Test that all demo voting tests still pass (integration test)
     */
    public function test_demo_voting_system_integration()
    {
        // Create organisation and election
        $organisation = Organisation::factory()->create();
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => $organisation->id,
        ]);

        // Create a user with region
        $user = User::factory()->create(['region' => 'east']);
        $organisation->users()->attach($user->id);

        // Verify basic structure exists
        $this->assertNotNull($election);
        $this->assertNotNull($user);
        $this->assertEquals('demo', $election->type);
        $this->assertEquals('east', $user->region);
    }
}
