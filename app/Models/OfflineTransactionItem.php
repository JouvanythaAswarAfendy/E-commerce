<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineTransactionItem extends Model
{
    protected $fillable = ['offline_transaction_id', 'product_id', 'product_name', 'qty', 'price', 'subtotal'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function transaction()
    {
        return $this->belongsTo(OfflineTransaction::class, 'offline_transaction_id');
    }


}
