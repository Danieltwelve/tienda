<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    /**
     * Tema de diseño utilizado por el paginador (Bootstrap 5).
     */
    protected string $paginationTheme = 'bootstrap';

    /**
     * Término de búsqueda por título.
     */
    public string $search = '';

    /**
     * ID de la categoría seleccionada para filtrar.
     */
    public string $selectedCategory = '';

    /**
     * Estado seleccionado para filtrar.
     */
    public string $selectedStatus = '';

    /**
     * ID de la prioridad seleccionada para filtrar (obtenida desde la BD).
     */
    public string $selectedPriority = '';

    /**
     * Reinicia la paginación a la página 1 cuando cambia el texto de búsqueda.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reinicia la paginación a la página 1 cuando cambia el filtro de categoría.
     */
    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    /**
     * Reinicia la paginación a la página 1 cuando cambia el filtro de estado.
     */
    public function updatingSelectedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Reinicia la paginación a la página 1 cuando cambia el filtro de prioridad.
     */
    public function updatingSelectedPriority(): void
    {
        $this->resetPage();
    }

    /**
     * Restablece todos los filtros aplicados a sus valores vacíos por defecto y reinicia la paginación.
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'selectedCategory', 'selectedStatus', 'selectedPriority']);
        $this->resetPage();
    }

    /**
     * Elimina una tarea directamente desde la tabla de Livewire y emite mensaje flash.
     */
    public function deleteTask(int $taskId): void
    {
        $task = Task::find($taskId);

        if ($task) {
            $task->delete();
            session()->flash('success', 'Tarea eliminada correctamente.');
        }
    }

    /**
     * Construye la consulta combinando todos los filtros activos y renderiza la vista.
     */
    public function render(): View
    {
        // Consulta base cargando las relaciones en eager loading
        $query = Task::with(['category', 'priority'])->latest();

        // 1. Filtro condicional por título
        if (trim($this->search) !== '') {
            $query->where('title', 'like', '%'.trim($this->search).'%');
        }

        // 2. Filtro condicional por categoría
        if ($this->selectedCategory !== '') {
            $query->where('category_id', $this->selectedCategory);
        }

        // 3. Filtro condicional por estado
        if ($this->selectedStatus !== '') {
            $query->where('status', $this->selectedStatus);
        }

        // 4. Filtro condicional por prioridad (desde la BD)
        if ($this->selectedPriority !== '') {
            $query->where('priority_id', $this->selectedPriority);
        }

        // Paginación de 5 tareas por página
        $tasks = $query->paginate(5);
        $categories = Category::orderBy('name')->get();
        $statuses = Task::availableStatuses();
        $priorities = Priority::orderBy('id')->get();

        return view('livewire.task-list', [
            'tasks' => $tasks,
            'categories' => $categories,
            'statuses' => $statuses,
            'priorities' => $priorities,
        ]);
    }
}
