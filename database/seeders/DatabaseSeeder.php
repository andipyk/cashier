<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Customer as CustomerModel;
use \App\Models\Product as ProductModel;
use \App\Models\StockAdjustment as StockAdjustmentModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);

        CustomerModel::factory(50)->create();
        ProductModel::factory(50)->create();
        StockAdjustmentModel::factory(50)->create();

    }
}
