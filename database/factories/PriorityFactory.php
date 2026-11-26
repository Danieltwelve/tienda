<?php

namespace Database\Factories;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Priority>
 */
class PriorityFactory extends Factory
{
    protected $model = Priority::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }

    public function baja(): static
    {
        return $this->state(fn (): array => ['name' => 'Baja']);
    }

    public function media(): static
    {
        return $this->state(fn (): array => ['name' => 'Media']);
    }

    public function alta(): static
    {
        return $this->state(fn (): array => ['name' => 'Alta']);
    }
}
