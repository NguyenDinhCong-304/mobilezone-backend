<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_attribute')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $now = Carbon::now();

        for ($i = 1; $i <= 20; $i++) {
            DB::table('product_attribute')->insert([
                'product_id' => rand(1, 10),
                'attribute_id' => rand(1, 10),
                'value' => $faker->word(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
