<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Muestra el listado interactivo de tareas (manejado por el componente Livewire).
     */
    public function index(): View
    {
        return view('tasks.index');
    }

    /**
     * Muestra el formulario para crear una nueva tarea, pasando categorías, estados y prioridades desde la BD.
     */
    public function create(): View
    {
        // Se cargan las categorías ordenadas alfabéticamente
        $categories = Category::orderBy('name')->get();
        // Se obtienen los estados válidos (Pendiente, En progreso, Completada)
        $statuses = Task::availableStatuses();
        // Se obtienen los niveles de prioridad dinámicamente desde la base de datos
        $priorities = Priority::orderBy('id')->get();

        return view('tasks.create', [
            'categories' => $categories,
            'statuses' => $statuses,
            'priorities' => $priorities,
        ]);
    }

    /**
     * Almacena una nueva tarea en la base de datos tras validar los datos.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tarea registrada correctamente.');
    }

    /**
     * Muestra el detalle de una tarea específica con su categoría y prioridad asociadas.
     */
    public function show(Task $task): View
    {
        $task->load(['category', 'priority']);

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Muestra el formulario de edición cargando los valores actuales de la tarea y prioridades desde la BD.
     */
    public function edit(Task $task): View
    {
        $categories = Category::orderBy('name')->get();
        $statuses = Task::availableStatuses();
        $priorities = Priority::orderBy('id')->get();

        return view('tasks.edit', [
            'task' => $task,
            'categories' => $categories,
            'statuses' => $statuses,
            'priorities' => $priorities,
        ]);
    }

    /**
     * Actualiza la tarea en la base de datos tras validar los datos enviados.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Elimina la tarea especificada de la base de datos.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tarea eliminada correctamente.');
    }
}
