<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'description', 'category_id', 'priority_id', 'status'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    public const STATUS_PENDIENTE = 'Pendiente';

    public const STATUS_EN_PROGRESO = 'En progreso';

    public const STATUS_COMPLETADA = 'Completada';

    /**
     * Lista de estados disponibles en el sistema.
     *
     * @return array<int, string>
     */
    public static function availableStatuses(): array
    {
        return [
            self::STATUS_PENDIENTE,
            self::STATUS_EN_PROGRESO,
            self::STATUS_COMPLETADA,
        ];
    }

    /**
     * Obtiene la categoría a la que pertenece la tarea.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene el nivel de prioridad asignado a la tarea desde la base de datos.
     *
     * @return BelongsTo<Priority, $this>
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    /**
     * Obtiene el nombre del nivel de prioridad asociado.
     */
    public function getPriorityNameAttribute(): string
    {
        return $this->priority?->name ?? 'Sin prioridad';
    }
}
