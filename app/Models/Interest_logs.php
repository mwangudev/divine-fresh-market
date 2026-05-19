<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest_logs extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'message_text',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
