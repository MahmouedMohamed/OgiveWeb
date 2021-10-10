<?php

namespace Database\Factories;

use App\Models\User;

use App\Models\Needy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class NeedyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Needy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = ['إيجاد مسكن مناسب', 'تحسين مستوي المعيشة', 'تجهيز لفرحة', 'سداد الديون', 'إيجاد علاج'];

        return [
            //
            'name' => $this->faker->name(),
            "type" => Arr::random($type),
            'age' => $this->faker->numberBetween(1, 10),
            'severity' => $this->faker->numberBetween(1, 10),
            'details' => $this->faker->text(500),
            'collected' => $this->faker->numberBetween(0, 1),
            'address' => $this->faker->text(20),
            'satisfied' => $this->faker->numberBetween(0, 1),
            'approved' => $this->faker->numberBetween(0, 1),
            "createdBy" => User::inRandomOrder()->first(),
            'created_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-4 week', 'now'),
        ];
    }
}
