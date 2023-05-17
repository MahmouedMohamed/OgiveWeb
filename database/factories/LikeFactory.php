<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\MemoryWall\Memory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $memory = Memory::inRandomOrder()->first();

        return [
            'userId' => $user,
            'memoryId' => $memory,
            'created_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
        ];
    }
}
