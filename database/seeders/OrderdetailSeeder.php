<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrderdetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('orderdetail')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $faker = Faker::create();
        $now = Carbon::now();

        for ($i = 1; $i <= 30; $i++) {
            $qty = rand(1,5);
            $price = rand(50, 500);
            DB::table('orderdetail')->insert([
                'order_id' => rand(1,10),
                'product_id' => rand(1,10),
                'price' => $price,
                'qty' => $qty,
                'amount' => $price * $qty,
                'discount' => rand(0,20),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
