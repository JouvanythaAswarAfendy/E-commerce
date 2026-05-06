<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'manual_notifications';
    
    protected $fillable = ['user_id', 'title', 'message', 'is_read'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
