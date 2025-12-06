<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('post')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('post')->insert([
                'topic_id' => rand(1, 10),
                'title' => 'Post Title ' . $i,
                'slug' => 'post-title-' . $i,
                'image' => 'post'.$i.'.jpg',
                'content' => $faker->paragraph(5),
                'description' => $faker->sentence(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
