<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TaskList;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_list_component_renders(): void
    {
        $priority = Priority::factory()->create(['name' => 'Alta']);
        $task = Task::factory()->create([
            'title' => 'Tarea Inicial de Prueba',
            'priority_id' => $priority->id,
        ]);

        Livewire::test(TaskList::class)
            ->assertStatus(200)
            ->assertSee('Tarea Inicial de Prueba')
            ->assertSee($task->category->name)
            ->assertSee('Alta');
    }

    public function test_can_search_tasks_by_title(): void
    {
        $task1 = Task::factory()->create(['title' => 'Aprender Livewire 3']);
        $task2 = Task::factory()->create(['title' => 'Configurar Docker en local']);

        Livewire::test(TaskList::class)
            ->set('search', 'Livewire')
            ->assertSee($task1->title)
            ->assertDontSee($task2->title);
    }

    public function test_can_filter_tasks_by_category(): void
    {
        $catWork = Category::factory()->create(['name' => 'Trabajo']);
        $catHome = Category::factory()->create(['name' => 'Hogar']);

        $taskWork = Task::factory()->create(['title' => 'Entregar informe', 'category_id' => $catWork->id]);
        $taskHome = Task::factory()->create(['title' => 'Comprar viveres', 'category_id' => $catHome->id]);

        Livewire::test(TaskList::class)
            ->set('selectedCategory', (string) $catWork->id)
            ->assertSee($taskWork->title)
            ->assertDontSee($taskHome->title);
    }

    public function test_can_filter_tasks_by_status(): void
    {
        $taskPending = Task::factory()->create(['title' => 'Tarea no empezada', 'status' => Task::STATUS_PENDIENTE]);
        $taskCompleted = Task::factory()->create(['title' => 'Tarea ya finalizada', 'status' => Task::STATUS_COMPLETADA]);

        Livewire::test(TaskList::class)
            ->set('selectedStatus', Task::STATUS_PENDIENTE)
            ->assertSee($taskPending->title)
            ->assertDontSee($taskCompleted->title);
    }

    public function test_can_filter_tasks_by_priority(): void
    {
        $priorityHigh = Priority::factory()->create(['name' => 'Alta']);
        $priorityLow = Priority::factory()->create(['name' => 'Baja']);

        $taskHigh = Task::factory()->create([
            'title' => 'Urgente arreglar bug en pago',
            'priority_id' => $priorityHigh->id,
        ]);
        $taskLow = Task::factory()->create([
            'title' => 'Actualizar copyright del footer',
            'priority_id' => $priorityLow->id,
        ]);

        Livewire::test(TaskList::class)
            ->set('selectedPriority', (string) $priorityHigh->id)
            ->assertSee($taskHigh->title)
            ->assertDontSee($taskLow->title);
    }

    public function test_can_combine_filters(): void
    {
        $catTech = Category::factory()->create(['name' => 'Tecnologia']);
        $catDesign = Category::factory()->create(['name' => 'Diseno']);
        $priorityHigh = Priority::factory()->create(['name' => 'Alta']);
        $priorityLow = Priority::factory()->create(['name' => 'Baja']);

        $target = Task::factory()->create([
            'title' => 'Revisar PR de API',
            'category_id' => $catTech->id,
            'priority_id' => $priorityHigh->id,
            'status' => Task::STATUS_EN_PROGRESO,
        ]);

        $other1 = Task::factory()->create([
            'title' => 'Revisar PR de Frontend',
            'category_id' => $catDesign->id,
            'priority_id' => $priorityHigh->id,
            'status' => Task::STATUS_EN_PROGRESO,
        ]);

        $other2 = Task::factory()->create([
            'title' => 'Revisar PR de Base de Datos',
            'category_id' => $catTech->id,
            'priority_id' => $priorityHigh->id,
            'status' => Task::STATUS_COMPLETADA,
        ]);

        $other3 = Task::factory()->create([
            'title' => 'Revisar PR de Documentacion API',
            'category_id' => $catTech->id,
            'priority_id' => $priorityLow->id,
            'status' => Task::STATUS_EN_PROGRESO,
        ]);

        Livewire::test(TaskList::class)
            ->set('search', 'API')
            ->set('selectedCategory', (string) $catTech->id)
            ->set('selectedStatus', Task::STATUS_EN_PROGRESO)
            ->set('selectedPriority', (string) $priorityHigh->id)
            ->assertSee($target->title)
            ->assertDontSee($other1->title)
            ->assertDontSee($other2->title)
            ->assertDontSee($other3->title);
    }

    public function test_priority_filter_resets_pagination(): void
    {
        $priority = Priority::factory()->create(['name' => 'Alta']);
        Task::factory()->count(15)->create();

        Livewire::test(TaskList::class)
            ->call('setPage', 2)
            ->assertSet('paginators.page', 2)
            ->set('selectedPriority', (string) $priority->id)
            ->assertSet('paginators.page', 1);
    }

    public function test_can_delete_task_via_livewire(): void
    {
        $task = Task::factory()->create(['title' => 'Tarea para ser borrada']);

        Livewire::test(TaskList::class)
            ->call('deleteTask', $task->id)
            ->assertDontSee('Tarea para ser borrada');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_reset_filters(): void
    {
        $category = Category::factory()->create();
        $priority = Priority::factory()->create();

        Livewire::test(TaskList::class)
            ->set('search', 'Test')
            ->set('selectedCategory', (string) $category->id)
            ->set('selectedStatus', Task::STATUS_COMPLETADA)
            ->set('selectedPriority', (string) $priority->id)
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('selectedCategory', '')
            ->assertSet('selectedStatus', '')
            ->assertSet('selectedPriority', '');
    }
}
