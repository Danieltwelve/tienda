<?php

namespace App\Models;

use Database\Factories\PriorityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Priority extends Model
{
    /** @use HasFactory<PriorityFactory> */
    use HasFactory;

    /**
     * Obtiene todas las tareas asociadas a este nivel de prioridad.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
