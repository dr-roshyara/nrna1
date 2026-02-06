<?php

namespace Database\Factories;

use App\Models\DemoCandidate;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * DemoCandidateFactory
 *
 * Generates realistic demo candidates for testing election voting workflows.
 * Uses Faker library to generate random candidate data.
 *
 * Usage:
 *   DemoCandidate::factory()->create()                    // Creates 1 candidate
 *   DemoCandidate::factory()->count(10)->create()         // Creates 10 candidates
 *   DemoCandidate::factory()->forPost('president')->count(5)->create()
 */
class DemoCandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DemoCandidate::class;

    /**
     * Post types for candidates
     */
    protected static $posts = [
        'president' => 'President',
        'vice_president' => 'Vice President',
        'secretary' => 'Secretary',
        'treasurer' => 'Treasurer',
        'member_at_large' => 'Member at Large',
    ];

    protected $postId = null;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $fullName = "{$firstName} {$lastName}";
        $postKey = $this->postId ?? array_rand(self::$posts);
        $postName = self::$posts[$postKey];

        return [
            'candidacy_id' => 'DEMO_' . Str::upper(Str::random(3)) . '_' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_id' => 'demo_user_' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_name' => $fullName,
            'candidacy_name' => $fullName,
            'post_id' => $postKey,
            'post_name' => $postName,
            'post_nepali_name' => $this->getNepaliPostName($postKey),
            'proposer_id' => 'demo_prop_' . $this->faker->unique()->numberBetween(1000, 9999),
            'proposer_name' => $this->faker->name(),
            'supporter_id' => 'demo_supp_' . $this->faker->unique()->numberBetween(1000, 9999),
            'supporter_name' => $this->faker->name(),
            'election_id' => Election::where('type', 'demo')->first()?->id ?? 1,
            'image_path_1' => null, // Optional: Can be generated if image seeding is needed
            'image_path_2' => null,
            'image_path_3' => null,
        ];
    }

    /**
     * Set the post for the candidate
     *
     * @param string $postId
     * @return $this
     */
    public function forPost($postId)
    {
        return $this->state(function (array $attributes) use ($postId) {
            $this->postId = $postId;
            return [
                'post_id' => $postId,
                'post_name' => self::$posts[$postId] ?? 'Member at Large',
                'post_nepali_name' => $this->getNepaliPostName($postId),
            ];
        });
    }

    /**
     * Set the election for the candidate
     *
     * @param Election|int $election
     * @return $this
     */
    public function forElection($election)
    {
        $electionId = $election instanceof Election ? $election->id : $election;

        return $this->state(function (array $attributes) use ($electionId) {
            return [
                'election_id' => $electionId,
            ];
        });
    }

    /**
     * Set custom candidate name
     *
     * @param string $name
     * @return $this
     */
    public function withName($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'user_name' => $name,
                'candidacy_name' => $name,
            ];
        });
    }

    /**
     * Set proposer information
     *
     * @param string $proposerName
     * @param string $proposerId (optional)
     * @return $this
     */
    public function withProposer($proposerName, $proposerId = null)
    {
        return $this->state(function (array $attributes) use ($proposerName, $proposerId) {
            return [
                'proposer_name' => $proposerName,
                'proposer_id' => $proposerId ?? 'demo_prop_' . Str::random(8),
            ];
        });
    }

    /**
     * Set supporter information
     *
     * @param string $supporterName
     * @param string $supporterId (optional)
     * @return $this
     */
    public function withSupporter($supporterName, $supporterId = null)
    {
        return $this->state(function (array $attributes) use ($supporterName, $supporterId) {
            return [
                'supporter_name' => $supporterName,
                'supporter_id' => $supporterId ?? 'demo_supp_' . Str::random(8),
            ];
        });
    }

    /**
     * Generate Nepali post names for localization
     *
     * @param string $postId
     * @return string
     */
    private function getNepaliPostName($postId)
    {
        $nepaliNames = [
            'president' => 'अध्यक्ष',
            'vice_president' => 'उपाध्यक्ष',
            'secretary' => 'सचिव',
            'treasurer' => 'कोषाध्यक्ष',
            'member_at_large' => 'सामान्य सदस्य',
        ];

        return $nepaliNames[$postId] ?? 'सदस्य';
    }
}
