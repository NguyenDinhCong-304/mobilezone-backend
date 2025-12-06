<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('category')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create(); // <-- khởi tạo $faker
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('category')->insert([
                'name' => 'Category ' . $i,
                'slug' => 'category-' . $i,
                'image' => 'category' . $i . '.jpg',
                'parent_id' => null,
                'sort_order' => $i,
                'description' => $faker->sentence(10),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
