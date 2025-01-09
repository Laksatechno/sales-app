<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Marketing User',
            'email' => 'marketing@example.com',
            'password' => bcrypt('password'),
            'role' => 'marketing',
        ]);

        User::create([
            'name' => 'Logistik User',
            'email' => 'logistik@example.com',
            'password' => bcrypt('password'),
            'role' => 'logistik',
        ]);
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tipe_pelanggan' => 'reguler',
            'jenis_institusi' => 'pmi',
            'marketing_id' => 1 
        ]);
    }
}
