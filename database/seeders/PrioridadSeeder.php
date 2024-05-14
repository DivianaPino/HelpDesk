<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prioridad;

class PrioridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prioridad::create([
            'nombre'=> "Urgente",
            'tiempo_resolucion' => 2,
        ]);

        Prioridad::create([
            'nombre'=> "Alta",
            'tiempo_resolucion' => 3,
        ]);

        Prioridad::create([
            'nombre'=> "Media",
            'tiempo_resolucion' => 4,
        ]);

        Prioridad::create([
            'nombre'=> "Baja",
            'tiempo_resolucion' => 5,
        ]);

    }
}
