<?php

namespace Database\Factories;

use App\Models\VoterSlug;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoterSlugFactory extends Factory
{
    protected $model = VoterSlug::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'slug' => Str::random(40),
            'is_active' => true,
            'expires_at' => now()->addHours(2),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the slug has expired
     */
    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => now()->subHour(),
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the slug is inactive
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the slug is active and valid
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
                'expires_at' => now()->addHours(2),
            ];
        });
    }
}
