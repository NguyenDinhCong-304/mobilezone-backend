<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create(); // <-- khởi tạo $faker
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('product')->insert([
                'category_id' => rand(1, 10),
                'name' => 'Product ' . $i,
                'slug' => 'product-' . $i,
                'thumbnail' => 'product' . $i . '.jpg',
                'content' => $faker->paragraph(3),
                'description' => $faker->sentence(10),
                'price_buy' => $faker->randomFloat(2, 100, 1000),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
