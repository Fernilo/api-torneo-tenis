<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement([1,2]);
        $name = ($gender == 1)? $this->faker->name('male') : $this->faker->name('female');
        return [
            'name' => $name,
            'skill' => $this->faker->numberBetween(0,100),
            'good_look' => $this->faker->numberBetween(0,100),
            'travel_speed' => $this->faker->numberBetween(0,100),
            'reaction_time' => $this->faker->numberBetween(0,100),
            'strengh' => $this->faker->numberBetween(0,100),
            'type' => $gender
        ];
    }
}
