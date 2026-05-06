<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'category_id', 'images', 'price', 'stock', 'created_by', 'status'];

    protected $casts = [
        'images' => 'array',
    ];

    protected static function booted()
    {
        static::updated(function ($product) {
            // Jika stok berkurang hingga <= 5 dan sebelumnya > 5
            if ($product->wasChanged('stock') && $product->stock <= 5 && $product->getOriginal('stock') > 5) {
                $admin = \App\Models\User::where('role', 'penjual')->first();
                if ($admin) {
                    try {
                        $admin->notify(new \App\Notifications\LowStockAlert($product->name, $product->stock));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send low stock notification: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function getDisplayPriceAttribute()
    {
        if ($this->sizes->count() > 0) {
            $minPrice = $this->sizes->whereNotNull('price')->min('price');
            return $minPrice ?: $this->price;
        }
        return $this->price;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
