<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id', 'order_type', 'buyer', 'created_by', 
        'total_price', 'payment_method', 'shipping_address', 
        'status', 'snap_token', 'redirect_url', 'delivered_at', 'stock_reduced'
    ];

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->wasChanged('status')) {
                $user = $order->user;
                if ($user) {
                    try {
                        $order->load(['items.product', 'user']);
                        $user->notify(new \App\Notifications\OrderStatusUpdated($order));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send order notification: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer');
    }


}
