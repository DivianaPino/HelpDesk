<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=> "Admin",
            'email'=> "admin@gmail.com",
            'password'=>bcrypt('12345678'),
        ])->assignRole('Administrador');

        User::create([
            'name'=> "Diviana Pino",
            'email'=> "divianap96@gmail.com",
            'telegram_id'=> "875806110",
            'password'=>bcrypt('12345678'),
        ])->assignRole('Técnico de soporte');

        User::create([
            'name'=> "Maria Lopez",
            'email'=> "dividesing@gmail.com",
            'password'=>bcrypt('12345678'),
        ])->assignRole('Usuario estándar');
        
    }
}
