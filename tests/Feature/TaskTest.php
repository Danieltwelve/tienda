<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_task_index_page(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee('Gestión de Tareas');
    }

    public function test_can_render_task_create_page(): void
    {
        $category = Category::factory()->create(['name' => 'Proyectos']);
        $priorityBaja = Priority::factory()->create(['name' => 'Baja']);
        $priorityMedia = Priority::factory()->create(['name' => 'Media']);
        $priorityAlta = Priority::factory()->create(['name' => 'Alta']);

        $response = $this->get(route('tasks.create'));

        $response->assertOk();
        $response->assertSee('Registrar Nueva Tarea');
        $response->assertSee($category->name);
        $response->assertSee('Prioridad');
        $response->assertSee($priorityBaja->name);
        $response->assertSee($priorityMedia->name);
        $response->assertSee($priorityAlta->name);
    }

    public function test_can_store_task(): void
    {
        $category = Category::factory()->create();
        $priority = Priority::factory()->create(['name' => 'Alta']);

        $payload = [
            'title' => 'Configurar servidor de producción',
            'description' => 'Instalar Nginx, PHP y MySQL',
            'category_id' => $category->id,
            'priority_id' => $priority->id,
            'status' => Task::STATUS_PENDIENTE,
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Configurar servidor de producción',
            'category_id' => $category->id,
            'priority_id' => $priority->id,
            'status' => Task::STATUS_PENDIENTE,
        ]);
    }

    public function test_store_task_validations(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => '',
            'category_id' => 99999,
            'priority_id' => 99999,
            'status' => 'EstadoInvalido',
        ]);

        $response->assertSessionHasErrors(['title', 'category_id', 'priority_id', 'status']);
    }

    public function test_can_render_task_show_page(): void
    {
        $priority = Priority::factory()->create(['name' => 'Alta']);
        $task = Task::factory()->create([
            'title' => 'Revisión de requerimientos',
            'priority_id' => $priority->id,
        ]);

        $response = $this->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertSee('Revisión de requerimientos');
        $response->assertSee($task->category->name);
        $response->assertSee('Alta');
    }

    public function test_can_render_task_edit_page(): void
    {
        $priority = Priority::factory()->create(['name' => 'Alta']);
        $task = Task::factory()->create(['priority_id' => $priority->id]);

        $response = $this->get(route('tasks.edit', $task));

        $response->assertOk();
        $response->assertSee('Editar Tarea #'.$task->id);
        $response->assertSee($task->title);
        $response->assertSee('Prioridad');
        $response->assertSee('Alta');
    }

    public function test_can_update_task(): void
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $priority1 = Priority::factory()->create(['name' => 'Baja']);
        $priority2 = Priority::factory()->create(['name' => 'Alta']);

        $task = Task::factory()->create([
            'category_id' => $category1->id,
            'priority_id' => $priority1->id,
            'status' => Task::STATUS_PENDIENTE,
        ]);

        $response = $this->put(route('tasks.update', $task), [
            'title' => 'Título Modificado',
            'description' => 'Nueva descripción',
            'category_id' => $category2->id,
            'priority_id' => $priority2->id,
            'status' => Task::STATUS_COMPLETADA,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Título Modificado',
            'category_id' => $category2->id,
            'priority_id' => $priority2->id,
            'status' => Task::STATUS_COMPLETADA,
        ]);
    }

    public function test_can_destroy_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
