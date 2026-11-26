@extends('layouts.app')

@section('title', 'Detalles de la Tarea: ' . $task->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-info-circle text-primary me-2"></i> Detalle de la Tarea #{{ $task->id }}
                </h3>
                <p class="text-muted small mb-0">Información detallada del registro.</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis border px-2 py-1">
                        <i class="bi bi-folder2 me-1"></i> Categoría: {{ $task->category->name }}
                    </span>
                    @php
                        $priorityName = $task->priority?->name ?? 'Sin prioridad';
                    @endphp
                    @if (strcasecmp($priorityName, 'Alta') === 0)
                        <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-2 py-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Prioridad: Alta
                        </span>
                    @elseif (strcasecmp($priorityName, 'Media') === 0)
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                            <i class="bi bi-dash-circle me-1"></i> Prioridad: Media
                        </span>
                    @elseif (strcasecmp($priorityName, 'Baja') === 0)
                        <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle px-2 py-1">
                            <i class="bi bi-arrow-down-circle me-1"></i> Prioridad: Baja
                        </span>
                    @else
                        <span class="badge bg-light text-dark border px-2 py-1">Prioridad: {{ $priorityName }}</span>
                    @endif
                </div>
                <div>
                    @if ($task->status === \App\Models\Task::STATUS_PENDIENTE)
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1">
                            <i class="bi bi-clock-history me-1"></i> Pendiente
                        </span>
                    @elseif ($task->status === \App\Models\Task::STATUS_EN_PROGRESO)
                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle px-3 py-1">
                            <i class="bi bi-arrow-repeat me-1"></i> En progreso
                        </span>
                    @elseif ($task->status === \App\Models\Task::STATUS_COMPLETADA)
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1">
                            <i class="bi bi-check2-circle me-1"></i> Completada
                        </span>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">
                <h4 class="card-title fw-bold text-dark mb-3">{{ $task->title }}</h4>

                <div class="mb-4">
                    <label class="fw-semibold text-secondary small d-block mb-1">Descripción de la Tarea:</label>
                    <div class="p-3 bg-light rounded border text-secondary" style="min-height: 80px;">
                        {{ $task->description ?: 'Sin descripción detallada.' }}
                    </div>
                </div>

                <div class="row g-3 text-secondary small border-top pt-3">
                    <div class="col-sm-6">
                        <i class="bi bi-calendar-plus me-1 text-primary"></i>
                        <strong>Fecha de creación:</strong> {{ $task->created_at->format('d/m/Y H:i:s') }}
                    </div>
                    <div class="col-sm-6">
                        <i class="bi bi-calendar-check me-1 text-primary"></i>
                        <strong>Última actualización:</strong> {{ $task->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash3 me-1"></i> Eliminar Tarea
                    </button>
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                        <i class="bi bi-pencil-square me-1"></i> Editar Tarea
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
