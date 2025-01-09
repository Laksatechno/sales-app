<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('products')->insert([
                'name' => $faker->word,
                // 'price' => $faker->numberBetween(1000, 2000000), // Harga antara 2 - 1000
                'stock' => $faker->numberBetween(1, 100), // Stok antara 1 - 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
