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
        $user = User::factory()->create();

        return [
            'election_id' => $post->election_id,
            'post_id' => $post->post_id,
            'candidacy_id' => $this->faker->unique()->word(),
            'user_id' => $user->user_id,
            'proposer_id' => $this->faker->unique()->word(),
            'supporter_id' => $this->faker->unique()->word(),
            'image_path_1' => $this->faker->word() . '.png',
            'image_path_2' => null,
            'image_path_3' => null,
        ];
    }
}
