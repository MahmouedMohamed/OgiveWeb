<?php

namespace Database\Factories;

use App\Models\User;

use App\Models\Memory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Memory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
            'id' => Str::uuid(),
            'personName' => $this->faker->name(),
            "image" => $this->faker->name(),
            'lifeStory' => $this->faker->text(500),
            'birthDate' => Carbon::parse(now())->format('Y-m-d'),
            'deathDate' => Carbon::parse(now())->format('Y-m-d'),
            "createdBy" => $user,
            "nationality" => $user->nationality,
            'created_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
        ];
    }
}
