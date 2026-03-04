<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DemoElectionCreationService
{
    /**
     * Create org-specific demo election with complete demo data
     *
     * Includes:
     * - 2 national posts (President, Vice President)
     * - 2 regional posts for Europe (State Representative, District Representative)
     * - 3 candidates for State Representative (select 2)
     * - 2 candidates for District Representative (select 1)
     * - Demo verification codes for each candidate
     *
     * @param int $organisationId
     * @param Organisation $organisation
     * @return Election
     */
    public function createOrganisationDemoElection(int $organisationId, Organisation $organisation): Election
    {
        $slug = 'demo-election-org-' . $organisationId;

        // 1. Create election with organisation_id
        $election = Election::create([
            'name' => 'Demo Election',
            'slug' => $slug,
            'type' => 'demo',
            'is_active' => true,
            'description' => 'Demo election for ' . $organisation->name . ' - test voting before live elections',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(365)->format('Y-m-d'),
            'organisation_id' => $organisationId,
        ]);

        // 2. Create national posts with candidates
        $this->createNationalPosts($election);

        // 3. Create regional posts with candidates (Europe only for auto-creation)
        $this->createRegionalPosts($election, ['Europe']);

        // 4. Log creation
        Log::channel('voting_audit')->info('Demo election auto-created', [
            'organisation_id' => $organisationId,
            'organisation_name' => $organisation->name,
            'election_id' => $election->id,
        ]);

        return $election;
    }

    /**
     * Create national posts (President, Vice President) with candidates and codes
     */
    private function createNationalPosts(Election $election): void
    {
        $nationalPosts = [
            [
                'post_id_prefix' => 'president',
                'name' => 'President',
                'nepali_name' => 'राष्ट्रपति',
                'position_order' => 1,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Alice Johnson', 'candidacy_name' => 'Alice Johnson - Progressive Platform'],
                    ['name' => 'Bob Smith', 'candidacy_name' => 'Bob Smith - Economic Growth'],
                    ['name' => 'Carol Williams', 'candidacy_name' => 'Carol Williams - Community First'],
                ]
            ],
            [
                'post_id_prefix' => 'vice_president',
                'name' => 'Vice President',
                'nepali_name' => 'उप-राष्ट्रपति',
                'position_order' => 2,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Daniel Miller', 'candidacy_name' => 'Daniel Miller - Innovation Leader'],
                    ['name' => 'Eva Martinez', 'candidacy_name' => 'Eva Martinez - Social Justice'],
                    ['name' => 'Frank Wilson', 'candidacy_name' => 'Frank Wilson - Infrastructure Expert'],
                ]
            ],
        ];

        foreach ($nationalPosts as $postData) {
            $this->createPost($election, $postData, true, null);
        }
    }

    /**
     * Create regional posts with candidates and codes
     * Creates both State Representative and District Representative for each region
     */
    private function createRegionalPosts(Election $election, array $regions): void
    {
        $regionalPostTemplates = [
            [
                'post_id_prefix' => 'state_rep',
                'name' => 'State Representative',
                'nepali_name' => 'प्रदेश सभा सदस्य',
                'position_order' => 3,
                'required_number' => 2,
                'candidates' => [
                    ['name' => 'Hans Mueller', 'candidacy_name' => 'Hans Mueller - Local Development'],
                    ['name' => 'Anna Schmidt', 'candidacy_name' => 'Anna Schmidt - Education Focus'],
                    ['name' => 'Klaus Weber', 'candidacy_name' => 'Klaus Weber - Infrastructure'],
                ]
            ],
            [
                'post_id_prefix' => 'district_rep',
                'name' => 'District Representative',
                'nepali_name' => 'जिल्ला सभा सदस्य',
                'position_order' => 4,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Maria Fischer', 'candidacy_name' => 'Maria Fischer - Health Services'],
                    ['name' => 'Thomas Wagner', 'candidacy_name' => 'Thomas Wagner - Youth Empowerment'],
                ]
            ],
        ];

        foreach ($regions as $region) {
            foreach ($regionalPostTemplates as $postTemplate) {
                $this->createPost($election, $postTemplate, false, $region);
            }
        }
    }

    /**
     * Create a single post with candidates and codes
     */
    private function createPost(Election $election, array $postData, bool $isNational, ?string $region): void
    {
        $candidates = $postData['candidates'];
        unset($postData['candidates']);

        $postId = $postData['post_id_prefix'] . '-' . $election->id . ($region ? '-' . Str::slug($region) : '');

        // Create post
        $post = DemoPost::create([
            'post_id' => $postId,
            'name' => $postData['name'] . ($region ? ' - ' . $region : ''),
            'nepali_name' => $postData['nepali_name'],
            'position_order' => $postData['position_order'],
            'required_number' => $postData['required_number'],
            'is_national_wide' => $isNational ? 1 : 0,
            'state_name' => $region,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id, // CRITICAL: Propagate org context
        ]);

        // Create candidates and codes
        foreach ($candidates as $index => $candidate) {
            // Create candidacy
            DemoCandidacy::create([
                'user_id' => "demo-{$postId}-" . ($index + 1),
                'post_id' => $post->post_id,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id, // CRITICAL: Propagate org context
                'candidacy_id' => "demo-{$postId}-" . ($index + 1),
                'user_name' => $candidate['name'],
                'candidacy_name' => $candidate['candidacy_name'],
                'proposer_name' => $this->getProposerName($index),
                'supporter_name' => $this->getSupporterName($index),
                'position_order' => $index + 1,
                'image_path_1' => $this->getCandidateImagePath($candidate['name'], $postData['name'], $region, $index),
            ]);

            // Create demo code for this candidate
            DemoCode::create([
                'user_id' => null,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id, // CRITICAL: Propagate org context
                'code1' => 'DEMO' . strtoupper(substr(md5($index . 'code1' . $region), 0, 8)),
                'code2' => 'DEMO' . strtoupper(substr(md5($index . 'code2' . $region), 0, 8)),
                'code3' => 'DEMO' . strtoupper(substr(md5($index . 'code3' . $region), 0, 8)),
                'code4' => 'DEMO' . strtoupper(substr(md5($index . 'code4' . $region), 0, 8)),
                'is_code1_usable' => true,
                'is_code2_usable' => true,
                'is_code3_usable' => true,
                'is_code4_usable' => true,
                'can_vote_now' => false,
                'voting_time_in_minutes' => config('voting.time_in_minutes', 30),
                'code1_sent_at' => now(),
            ]);
        }
    }

    /**
     * Get proposer name (rotates through list)
     */
    private function getProposerName(int $index): string
    {
        $proposers = ['John Doe', 'Michael Brown', 'Robert Johnson', 'David Lee', 'James Harris'];
        return $proposers[$index % count($proposers)];
    }

    /**
     * Get supporter name (rotates through list)
     */
    private function getSupporterName(int $index): string
    {
        $supporters = ['Jane Smith', 'Sarah Wilson', 'Emma Davis', 'Nancy Clark', 'Jennifer Martin'];
        return $supporters[$index % count($supporters)];
    }

    /**
     * Generate candidate image path
     */
    private function getCandidateImagePath(string $name, string $postName, ?string $region, int $index): string
    {
        $nameSlug = Str::slug($name);
        $postSlug = Str::slug($postName);
        $regionSlug = $region ? '-' . Str::slug($region) : '';
        return "candidates/{$nameSlug}_{$postSlug}{$regionSlug}_" . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ".png";
    }
}
