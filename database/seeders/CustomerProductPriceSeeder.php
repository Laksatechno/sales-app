<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        // Mengambil ID pelanggan yang sudah ada di database
        $customers = DB::table('customers')->pluck('id');
        $products = DB::table('products')->pluck('id');

        foreach ($customers as $customer_id) {
            foreach ($products as $product_id) {
                DB::table('customer_product_prices')->insert([
                    'customer_id' => $customer_id, // Menambahkan ID pelanggan yang valid
                    'product_id' => $product_id,   // Menambahkan ID produk yang valid
                    'price' => $faker->numberBetween(10000, 1000000), // Harga acak untuk produk pelanggan
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
