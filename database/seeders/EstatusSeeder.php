<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([
            'nombre'=> "Nuevo",
        ]);

        Estado::create([
            'nombre'=> "Abierto",
        ]);

        Estado::create([
            'nombre'=> "En espera",
        ]);

        Estado::create([
            'nombre'=> "En revisión",
        ]);

        Estado::create([
            'nombre'=> "Resuelto"
        ]);

        Estado::create([
            'nombre'=> "Reabierto"
        ]); 

        Estado::create([
            'nombre'=> "Cerrado",
        ]);

    }
}
