<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_store')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('product_store')->insert([
                'product_id' => rand(1, 10),
                'price_root' => $faker->randomFloat(2, 50, 500),
                'qty' => $faker->numberBetween(1, 100),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
