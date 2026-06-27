<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugCandidacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_403(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $member = User::factory()->create();
        $election = Election::factory()->create([
            'organisation_id'         => $org->id,
            'type'                    => 'real',
            'administration_completed' => true,
            'nomination_completed'    => false,
            'slug'                    => 'test-election-for-debug',
        ]);
        $post = Post::factory()->forElection($election)->create();
        UserOrganisationRole::create([
            'user_id'         => $member->id,
            'organisation_id' => $org->id,
            'role'            => 'voter',
        ]);

        $response = $this->actingAs($member)
             ->get('/organisations/' . $org->slug . '/elections/' . $election->slug . '/candidacy/apply');

        $content = $response->getContent();

        // Extract just the text content to find the error message
        $text = strip_tags($content);
        file_put_contents('C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\storage\logs\debug_response.txt', $text);

        dump('Status: ' . $response->getStatusCode());
        dump('Response body text saved to storage/logs/debug_response.txt');
        echo "\n=== RESPONSE BODY (first 2000 chars) ===\n";
        echo substr($text, 0, 2000);
        echo "\n=== END ===\n";
    }
}
