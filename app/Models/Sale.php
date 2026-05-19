<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getProfitAttribute(){
        // loop through all items in this sale
        return $this->items->sum(function($item) {
            //Revenue (subtotal)
            $revenue = $item->subtotal;

            //Cost
            $cost = $item->quantity * $item->product->buying_price;

            //Profit
            return $revenue - $cost;
        });
    }
}
