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
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
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
     * Ensure organisation exists when created.
     */
    public function create($attributes = [], ?\Illuminate\Database\Eloquent\Model $parent = null)
    {
        // If organisation_id is provided, ensure it exists
        if (isset($attributes['organisation_id']) && $attributes['organisation_id']) {
            $org_id = $attributes['organisation_id'];
            if (!Organisation::find($org_id)) {
                Organisation::create([
                    'id' => $org_id,
                    'name' => 'Test Organisation',
                    'slug' => 'test-org-' . $org_id,
                    'type' => 'other', // Must use valid enum value
                ]);
            }
        }

        return parent::create($attributes, $parent);
    }
}
