<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Admin;
use App\Models\Distributor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'keona',
            'email' => 'keona@gmail.com',
            'password' => bcrypt('12345678'),
            'point' => 10000, 
        ]);

        Admin::create([
            'name' => 'admin', 
            'username' => 'Admin', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

        Distributor::create([
            'nama_distributor' => 'jeremia', 
            'lokasi' => 'Berastagi', 
            'kontak' => '08227024480',
            'email' => 'jere05@gmail.com',
        ]);
    }
}