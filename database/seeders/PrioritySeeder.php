<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Inserta los niveles de prioridad en la base de datos (Baja, Media, Alta).
     */
    public function run(): void
    {
        $priorities = [
            'Baja',
            'Media',
            'Alta',
        ];

        foreach ($priorities as $name) {
            Priority::firstOrCreate(['name' => $name]);
        }
    }
}
