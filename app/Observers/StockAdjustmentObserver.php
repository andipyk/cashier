<?php

namespace App\Observers;

use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;

class StockAdjustmentObserver
{
    /**
     * Handle the StockAdjustment "created" event.
     */
    public function created(StockAdjustment $stockAdjustment): void
    {
        DB::transaction(function () use ($stockAdjustment) {
            $product = $stockAdjustment->product;
            $product->stock_quantity += $stockAdjustment->quantity_adjusted;
            $product->save();
        });
    }

    /**
     * Handle the StockAdjustment "deleted" event.
     */
    public function deleted(StockAdjustment $stockAdjustment): void
    {
        DB::transaction(function () use ($stockAdjustment) {
            $product = $stockAdjustment->product;
            $product->stock_quantity -= $stockAdjustment->quantity_adjusted;
            $product->save(); 
         });
    }

}
