<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = ['product_id', 'size', 'price', 'stock'];
    
    protected static function booted()
    {
        static::updated(function ($size) {
            // Jika stok berkurang hingga <= 5 dan sebelumnya > 5
            if ($size->wasChanged('stock') && $size->stock <= 5 && $size->getOriginal('stock') > 5) {
                $admin = \App\Models\User::where('role', 'penjual')->first();
                if ($admin) {
                    try {
                        $admin->notify(new \App\Notifications\LowStockAlert($size->product->name, $size->stock, $size->size));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send low stock notification: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
