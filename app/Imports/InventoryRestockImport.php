<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category; // <--- HAKIKISHA HII IPO JUU KABISA
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Facades\Log;

class InventoryRestockImport implements ToModel, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ",",
            'input_encoding' => 'UTF-8'
        ];
    }

    public function model(array $row)
    {
        // 1. Ruka mstari wa kwanza wa vichwa vya habari
        if ($row[0] === 'search_identifier' || $row[1] === 'product_name') {
            return null;
        }

        if (empty($row[0]) && empty($row[1])) {
            return null;
        }

        $product = null;

        // 2. Tafuta kama bidhaa ipo
        if (!empty($row[0])) {
            $product = Product::where('barcode', $row[0])
                              ->orWhere('sku', $row[0])
                              ->first();
        }

        if (!$product && !empty($row[1])) {
            $product = Product::where('name', 'LIKE', trim($row[1]))->first();
        }

        // 3. KAMA BIDHAA NI MPYA KABISA:
        if (!$product) {
            $product = new Product();
            $product->stock_quantity = 0;
            $product->is_active = true;

            // MTEGO WA CATEGORY: Tafuta category ya dharura, isipopatikana itengeneze papo hapo
            $defaultCategory = Category::firstOrCreate(
                ['name' => 'Uncategorized'], // Inatafuta jina hili
                ['description' => 'Products imported via Excel without a category'] // Kama haipo inaunda
            );

            // Jaza category_id kwenye bidhaa mpya ili MySQL isigomee tena!
            $product->category_id = $defaultCategory->id;
        }

        // 4. Jaza data zingine zote kama kawaida
        if (isset($row[1]) && !empty($row[1]))  $product->name = trim($row[1]);
        if (isset($row[2]))                      $product->sku = trim($row[2]);
        if (isset($row[3]))                      $product->barcode = trim($row[3]);
        if (isset($row[4]))                      $product->brand_name = trim($row[4]);
        if (isset($row[5]))                      $product->description = trim($row[5]);

        if (isset($row[6]) && !empty($row[6]))  $product->unit_of_measure = trim($row[6]);
        if (isset($row[7]))                      $product->base_unit = trim($row[7]);
        if (isset($row[8]))                      $product->base_unit_value = (float)($row[8] ?? 1);

        if (isset($row[9]) && (float)$row[9] > 0) {
            if ($product->exists) {
                // Kama bidhaa ilikuwepo, jumlisha stock ya sasa na ya Excel
                $product->stock_quantity += (float)$row[9];
            } else {
                // Kama ni mpya, weka namba ya Excel moja kwa moja
                $product->stock_quantity = (float)$row[9];
            }
        }

        if (isset($row[10]))                     $product->min_stock_level = (int)($row[10] ?? 5);
        if (isset($row[11]) && (float)$row[11] >= 0) $product->buying_price = (float)$row[11];
        if (isset($row[12]) && (float)$row[12] >= 0) $product->selling_price = (float)$row[12];
        if (isset($row[13]))                     $product->vat_percentage = (float)($row[13] ?? 0);

        if (isset($row[14]) && !empty($row[14])) {
            $product->expiry_date = date('Y-m-d', strtotime($row[14]));
        }

        // 5. Save sasa kwenda kwenye Database
        $product->save();

        $currentCount = session('excel_updated_count', 0);
        session(['excel_updated_count' => $currentCount + 1]);

        return null;
    }
}
