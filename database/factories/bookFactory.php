<?php

namespace Database\Factories;

use App\Models\book;
use App\Models\user;

use Illuminate\Database\Eloquent\Factories\Factory;

class bookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'genre' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(), 
            'author' => $this->faker->name(),
        ];
    }
}
