<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\StockAdjustment;

class StockAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockAdjustment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $productID = Product::query()->inRandomOrder()->value('id');
        $quanity_adjusted = $this->faker->numberBetween(-50, 50);

        return [
            'product_id' => $productID,
            'quantity_adjusted' => $quanity_adjusted,
            'reason' => $this->faker->sentence(),
        ];
    }

    public function configure(): StockAdjustmentFactory
    {
        return $this->afterCreating(
            function (StockAdjustment $stockAdjustment) {
                $product = $stockAdjustment->product;
                $product->stock_quantity += $stockAdjustment->quantity_adjusted;
                $product->save();
            }
        );
    }
}
