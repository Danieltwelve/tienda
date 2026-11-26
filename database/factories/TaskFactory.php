<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'category_id' => Category::factory(),
            'priority_id' => Priority::factory(),
            'status' => fake()->randomElement(Task::availableStatuses()),
        ];
    }

    /**
     * Indicate that the task is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Task::STATUS_PENDIENTE,
        ]);
    }

    /**
     * Indicate that the task is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Task::STATUS_EN_PROGRESO,
        ]);
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Task::STATUS_COMPLETADA,
        ]);
    }

    /**
     * Indicate that the task has low priority.
     */
    public function lowPriority(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority_id' => Priority::firstOrCreate(['name' => 'Baja'])->id,
        ]);
    }

    /**
     * Indicate that the task has medium priority.
     */
    public function mediumPriority(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority_id' => Priority::firstOrCreate(['name' => 'Media'])->id,
        ]);
    }

    /**
     * Indicate that the task has high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority_id' => Priority::firstOrCreate(['name' => 'Alta'])->id,
        ]);
    }
}
