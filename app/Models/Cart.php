<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'qty', 'size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute()
    {
        if ($this->size) {
            $productSize = ProductSize::query()
                ->where('product_id', '=', $this->product_id, 'and')
                ->where('size', '=', $this->size, 'and')
                ->first();
            
            if ($productSize && $productSize->price) {
                return $productSize->price;
            }
        }
        return $this->product->price;
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->qty;
    }


}
