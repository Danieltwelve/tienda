@extends('layouts.app')

@section('title', 'Registrar Nueva Tarea')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle text-primary me-2"></i> Registrar Nueva Tarea
                </h3>
                <p class="text-muted small mb-0">Completa el formulario para clasificar y agregar una nueva tarea.</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <!-- Título -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold text-secondary">
                            Título de la Tarea <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Ej: Preparar informe mensual de finanzas"
                            required
                            maxlength="255"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold text-secondary">
                            Descripción (opcional)
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Detalla los puntos clave, instrucciones o notas relevantes de la tarea..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Categoría (Menú desplegable dinámico desde la BD) -->
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-semibold text-secondary">
                                Categoría <span class="text-danger">*</span>
                            </label>
                            <select
                                name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >
                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Seleccionar categoría --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prioridad (Menú desplegable dinámico desde la BD) -->
                        <div class="col-md-4">
                            <label for="priority_id" class="form-label fw-semibold text-secondary">
                                Prioridad <span class="text-danger">*</span>
                            </label>
                            <select
                                name="priority_id"
                                id="priority_id"
                                class="form-select @error('priority_id') is-invalid @enderror"
                                required
                            >
                                <option value="" disabled {{ old('priority_id') ? '' : 'selected' }}>-- Seleccionar prioridad --</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                                        {{ $priority->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold text-secondary">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select
                                name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', \App\Models\Task::STATUS_PENDIENTE) === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('tasks.index') }}" class="btn btn-light border">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <i class="bi bi-save me-2"></i> Guardar Tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
