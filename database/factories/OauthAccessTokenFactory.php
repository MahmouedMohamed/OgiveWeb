<?php

namespace Database\Factories;

use App\Models\OauthAccessToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OauthAccessTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OauthAccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $appType = ['Ahed', 'Ataa', 'MemoryWall', 'TimeCatcher', 'BreedMe'];
        $accessType = ['Mobile', 'Web'];
        $user = User::inRandomOrder()->first();

        return [
            'id' => Str::uuid(),
            'user_id' => $user,
            'access_token' => $this->faker->unique()->name,
            'appType' => Arr::random($appType),
            'accessType' => Arr::random($accessType),
            'expires_at' => $this->faker->dateTimeBetween('4 week', '8 week'),
            'created_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
        ];
    }
}
