<?php

namespace Database\Factories;

use App\Models\Candidacy;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidacyFactory extends Factory
{
    protected $model = Candidacy::class;

    public function definition()
    {
        $post = Post::factory()->create();
        $user = User::factory()->forOrganisation($post->organisation)->create();

        return [
            'organisation_id' => $post->organisation_id,
            'post_id' => $post->id,
            'user_id' => $user->id,
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'position_order' => $this->faker->numberBetween(1, 5),
            'status' => 'pending',
        ];
    }
}
