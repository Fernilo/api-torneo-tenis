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
        $reactionTime = ($gender == 1)? null : $this->faker->numberBetween(0,100);
        $travelSpeed = $strengh = ($gender == 1)? $this->faker->numberBetween(0,100) : null ;
        
        return [
            'name' => $name,
            'skill' => $this->faker->numberBetween(0,100),
            'good_look' => $this->faker->numberBetween(0,100),
            'travel_speed' => $travelSpeed,
            'reaction_time' => $reactionTime,
            'strengh' => $strengh,
            'type' => $gender
        ];
    }
}
