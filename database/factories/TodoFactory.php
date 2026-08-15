<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Todo>
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->optional()->sentence(3),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'due_date' => fake()->boolean(70)
                ? fake()->dateTimeBetween('+1 day', '+1 year')->format('Y-m-d')
                : null,
            'complete' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the todo is complete.
     */
    public function complete(): static
    {
        return $this->state(fn (array $attributes) => [
            'complete' => true,
        ]);
    }

    /**
     * Indicate that the todo is incomplete.
     */
    public function incomplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'complete' => false,
        ]);
    }
}
