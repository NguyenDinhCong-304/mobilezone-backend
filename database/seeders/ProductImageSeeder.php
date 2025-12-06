<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_image')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create(); // <-- khởi tạo $faker
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('product_image')->insert([
                'product_id' => rand(1, 10),
                'image' => 'product' . $i . '_image.jpg',
                'alt' => $faker->word,
                'title' => $faker->sentence(3),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
