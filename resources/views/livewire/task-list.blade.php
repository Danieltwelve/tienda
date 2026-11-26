<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Panel de Filtros y Búsqueda -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3 align-items-end">
                <!-- Búsqueda por título -->
                <div class="col-lg-3 col-md-6">
                    <label for="search" class="form-label small fw-semibold text-secondary">
                        <i class="bi bi-search me-1"></i> Buscar por título
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            class="form-control border-start-0"
                            placeholder="Escribe el título..."
                        >
                    </div>
                </div>

                <!-- Filtro por categoría -->
                <div class="col-lg-3 col-md-6">
                    <label for="filterCategory" class="form-label small fw-semibold text-secondary">
                        <i class="bi bi-tag me-1"></i> Categoría
                    </label>
                    <select
                        id="filterCategory"
                        wire:model.live="selectedCategory"
                        class="form-select"
                    >
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por prioridad (Cargado dinámicamente desde la BD) -->
                <div class="col-lg-2 col-md-4">
                    <label for="filterPriority" class="form-label small fw-semibold text-secondary">
                        <i class="bi bi-speedometer2 me-1"></i> Prioridad
                    </label>
                    <select
                        id="filterPriority"
                        wire:model.live="selectedPriority"
                        class="form-select"
                    >
                        <option value="">Todas las prioridades</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por estado -->
                <div class="col-lg-2 col-md-4">
                    <label for="filterStatus" class="form-label small fw-semibold text-secondary">
                        <i class="bi bi-flag me-1"></i> Estado
                    </label>
                    <select
                        id="filterStatus"
                        wire:model.live="selectedStatus"
                        class="form-select"
                    >
                        <option value="">Todos los estados</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón Limpiar filtros -->
                <div class="col-lg-2 col-md-4 d-flex gap-2">
                    @if ($search !== '' || $selectedCategory !== '' || $selectedStatus !== '' || $selectedPriority !== '')
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="btn btn-outline-secondary w-100"
                            title="Limpiar todos los filtros"
                        >
                            <i class="bi bi-x-circle me-1"></i> Limpiar
                        </button>
                    @endif
                    <div wire:loading class="spinner-border spinner-border-sm text-primary align-self-center" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Tareas -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="bi bi-list-task text-primary me-2"></i> Listado de Tareas
            </h5>
            <span class="badge bg-light text-dark border">
                Total: {{ $tasks->total() }} registros
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 70px;">#ID</th>
                        <th scope="col">Título</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Prioridad</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de Creación</th>
                        <th scope="col" class="text-end" style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        @php
                            $priorityName = $task->priority?->name ?? 'Sin prioridad';
                        @endphp
                        <tr>
                            <td class="text-muted fw-semibold">#{{ $task->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $task->title }}</div>
                                @if ($task->description)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                        {{ $task->description }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle px-2 py-1">
                                    <i class="bi bi-folder2 me-1"></i> {{ $task->category->name }}
                                </span>
                            </td>
                            <td>
                                @if (strcasecmp($priorityName, 'Alta') === 0)
                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-2 py-1">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Alta
                                    </span>
                                @elseif (strcasecmp($priorityName, 'Media') === 0)
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        <i class="bi bi-dash-circle me-1"></i> Media
                                    </span>
                                @elseif (strcasecmp($priorityName, 'Baja') === 0)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle px-2 py-1">
                                        <i class="bi bi-arrow-down-circle me-1"></i> Baja
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $priorityName }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($task->status === \App\Models\Task::STATUS_PENDIENTE)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                        <i class="bi bi-clock-history me-1"></i> Pendiente
                                    </span>
                                @elseif ($task->status === \App\Models\Task::STATUS_EN_PROGRESO)
                                    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-arrow-repeat me-1"></i> En progreso
                                    </span>
                                @elseif ($task->status === \App\Models\Task::STATUS_COMPLETADA)
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check2-circle me-1"></i> Completada
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ $task->status }}</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $task->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary" title="Editar tarea">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button
                                        type="button"
                                        wire:click="deleteTask({{ $task->id }})"
                                        wire:confirm="¿Estás seguro de que deseas eliminar esta tarea?"
                                        class="btn btn-outline-danger"
                                        title="Eliminar tarea"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <p class="mb-1 fw-semibold">No se encontraron tareas con los filtros seleccionados.</p>
                                <small>Intenta cambiar los términos de búsqueda o crear una nueva tarea.</small>
                                <div class="mt-3">
                                    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Registrar Nueva Tarea
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tasks->hasPages())
            <div class="card-footer bg-white py-3 border-0 d-flex justify-content-center">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
