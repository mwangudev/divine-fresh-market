<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'buying_price',
        'selling_price',
        'stock_quantity',
        'unit_of_measure',
        'description',
        'brand_name',
        'barcode',
        'vat_percentage',
        'base_unit',
        'base_unit_value',
        'min_stock_level',
        'expiry_date',
        'is_active',
        'image',
        
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
