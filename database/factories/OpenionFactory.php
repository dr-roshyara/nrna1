<?php

namespace Database\Factories;

use App\Models\Openion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
class OpenionFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Openion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
           'user_id' => 1,
            'title' =>$this->faker->sentence(),
            'body'=>$this->faker->paragraph(),
        ];
    }
}
