<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => \App\OrderStatus::class,
        'payment_method' => \App\PaymentMethod::class,
    ];

    public static function booted(): void
    {
        static::creating(function (self $order) {
            $order->user_id = auth()->id();
            $order->total = 0;
        });

        static::saving(function (self $order) {
            if ($order->isDirty('total')) {
                $order->loadMissing('orderDetails.product');

                $profitCalculation = $order->orderDetails->reduce(function ($carry, $detail) {
                    $productProfit = ($detail->price - $detail->product->cost_price) * $detail->quantity;
                    return $carry + $productProfit;
                }, 0);

                $order->attributes['profit'] = $profitCalculation;
            }
        });
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
