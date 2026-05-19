<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::where('is_active', true)->get();
    }

    public function headings(): array
    {
        return [
            'search_identifier', // Barcode/SKU ya sasa (Inatumika kutafuta)
            'product_name',
            'sku',
            'barcode',
            'brand_name',
            'description',
            'unit_of_measure',
            'base_unit',
            'base_unit_value',
            'stock_quantity_to_add', // Default tunaweka 0 ili wajaze mpya tu
            'min_stock_level',
            'buying_price',
            'selling_price',
            'vat_percentage',
            'expiry_date'
        ];
    }

    public function map($product): array
    {
        return [
            $product->barcode ?? $product->sku ?? $product->name, // Identifier
            $product->name,
            $product->sku,
            $product->barcode,
            $product->brand_name,
            $product->description,
            $product->unit_of_measure,
            $product->base_unit ?? 'pcs',
            $product->base_unit_value ?? 1,
            0, // Stock mpya ya kuongeza
            $product->min_stock_level ?? 5,
            $product->buying_price,
            $product->selling_price,
            $product->vat_percentage ?? 0,
            $product->expiry_date
        ];
    }
}
