<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductSaleSeeder::class);
        $this->call(ProductStoreSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(ProductImageSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(TopicSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
