<?php

namespace Database\Factories;

use App\Models\FoodSharingMarker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodSharingMarkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FoodSharingMarker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'type'=>'Food',
            'description'=>$this->faker->text(5000),
            'quantity'=>$this->faker->numberBetween(1,10),
            'priority'=>$this->faker->numberBetween(1,10),
            'latitude'=>$this->faker->numberBetween(1,90),
            'longitude'=>$this->faker->numberBetween(1,90),
            'existed'=>0,
            'created_at'=>$this->faker->dateTimeBetween('-4 week','now'),
            'updated_at'=>$this->faker->dateTimeBetween('-4 week','now'),
        ];
    }
}
