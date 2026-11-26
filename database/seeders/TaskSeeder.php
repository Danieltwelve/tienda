<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $priorities = Priority::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        if ($priorities->isEmpty()) {
            $this->call(PrioritySeeder::class);
            $priorities = Priority::all();
        }

        foreach ($categories as $category) {
            foreach ($priorities as $priority) {
                Task::factory()->create([
                    'category_id' => $category->id,
                    'priority_id' => $priority->id,
                ]);
            }
        }
    }
}
