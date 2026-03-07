<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from' => $this->faker->email(),
            'to' => $this->faker->email(),
            'message' => $this->faker->sentence(),
            'code' => $this->faker->unique()->bothify('????-####'),
            'message_receiver_id' => null,
            'message_receiver_name' => $this->faker->name(),
            'message_sender_id' => null,
            'message_sender_name' => $this->faker->name(),
            'organisation_id' => Organisation::factory(),
        ];
    }
}
