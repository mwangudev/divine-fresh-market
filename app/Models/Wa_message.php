<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wa_message extends Model
{
    protected $fillable = [
        'customer_id',
        'direction',
        'message_text',
        'intent',
        'status',
        'received_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


}
