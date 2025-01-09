<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        
        // Menambahkan 5 pelanggan contoh
        foreach (range(1, 5) as $index) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'email' => $faker->email,
                'tipe_pelanggan' => $faker->randomElement(['Reguler', 'Subdis']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
