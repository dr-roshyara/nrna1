<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * NOTE: organisation_id is REQUIRED (NOT NULL foreign key constraint)
     * You must either:
     * 1. Use ->forOrganisation($org) method
     * 2. Manually pass organisation_id => $org->id to create()
     * 3. Wrap in User::factory()->forOrganisation(Organisation::factory()->create())->create()
     *
     * @return array
     */
    public function definition()
    {
        // Get or create platform organisation for default
        $platform = Organisation::firstOrCreate(
            ['type' => 'platform', 'is_default' => true],
            [
                'name' => 'PublicDigit',
                'slug' => 'publicdigit',
            ]
        );

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'region' => $this->faker->state(), // Required for multi-tenancy
            'organisation_id' => $platform->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }

    /**
     * Indicate that the user is a voter.
     *
     * @return $this
     */
    public function voter()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_voter' => true,
                'can_vote' => true,
                'has_voted' => false,
            ];
        });
    }

    /**
     * Create a user for a specific organisation
     */
    public function forOrganisation(Organisation $organisation)
    {
        return $this->state([
            'organisation_id' => $organisation->id,
        ]);
    }
}
