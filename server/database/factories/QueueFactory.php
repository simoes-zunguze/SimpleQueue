<?php

namespace Database\Factories;

use App\Models\Queue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QueueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Queue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->country,
            'description' => Str::random(10),
        ];
    }
}
