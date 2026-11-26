@extends('layouts.app')

@section('title', 'Listado de Tareas')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <div>
        <h2 class="fw-bold mb-1 text-dark">
            <i class="bi bi-check2-circle text-primary me-2"></i> Gestión de Tareas
        </h2>
        <p class="text-muted mb-0 small">
            Administra, clasifica por categoría y filtra tareas en tiempo real con Livewire.
        </p>
    </div>
    <div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary shadow-sm d-inline-flex align-items-center">
            <i class="bi bi-plus-lg me-2"></i> Registrar Nueva Tarea
        </a>
    </div>
</div>

<!-- Componente Livewire 3 reactivo -->
@livewire('task-list')
@endsection
