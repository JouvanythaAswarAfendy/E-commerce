<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    protected $fillable = ['transaction_code', 'seller_id', 'total_price', 'status'];
    
    public function items()
    {
        return $this->hasMany(OfflineTransactionItem::class);
    }

    // Relasi ke User (Seller)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


}
