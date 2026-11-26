<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación aplicadas a la creación de una tarea.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'priority_id' => ['required', 'integer', 'exists:priorities,id'],
            'status' => ['required', 'string', Rule::in(Task::availableStatuses())],
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título de la tarea es obligatorio.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'priority_id.required' => 'Debe seleccionar una prioridad.',
            'priority_id.exists' => 'La prioridad seleccionada no es válida.',
            'status.required' => 'Debe seleccionar un estado.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
