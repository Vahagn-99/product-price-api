<?php

namespace Database\Seeders;

use App\Models\Product;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() : void
    {
        Product::factory()->count(20)->create();
    }
}
