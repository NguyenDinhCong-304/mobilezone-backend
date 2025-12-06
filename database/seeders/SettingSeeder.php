<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('setting')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        DB::table('setting')->insert([
            'site_name' => 'My Website',
            'email' => 'info@example.com',
            'phone' => '0123456789',
            'hotline' => '0987654321',
            'address' => '123 Example Street, City',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
