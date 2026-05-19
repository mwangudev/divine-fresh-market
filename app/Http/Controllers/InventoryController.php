<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Imports\InventoryRestockImport;
use App\Exports\InventoryTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;



class InventoryController extends Controller
{
    // 1. Show the main inventory page
    public function index(Request $request)
    {
        //Urgency Metrics

        $metrics = [
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->where('stock_quantity', '>', 0)->count(),
            'expiring_soon' => Product::whereNotNull('expiry_date')->whereBetween('expiry_date', [now(), now()->addDays(31)])->count(),
            'total_value' => Product::select(DB::raw('SUM(stock_quantity * buying_price) as total_value'))->first()->value ?? 0,
        ];

        // 2. Build the Smart Table Query
        $query = Product::with('category');

        // Apply Filters (Search by Name or Barcode)
        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('barcode', 'LIKE', "%{$request->search}%");
        }

        // Filter by Status (Low Stock, Out of Stock, etc.)
        if ($request->filter == 'low_stock') {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
        }

        $inventory = $query->latest()->paginate(6);
        $categories = Category::all();


        return view('inventory.index', compact('categories', 'inventory', 'metrics'));
    }


    public function adjustStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|numeric|min:0.1',
            'reason' => 'required|string'
        ]);

        if ($request->adjustment_type == 'subtract') {
            $product->decrement('stock_quantity', $request->quantity);
        } else {
            $product->increment('stock_quantity', $request->quantity);
        }

        // Here you would ideally save a log to a StockLog model
        // StockLog::create([...]);

        return back()->with('success', "Inventory for {$product->name} adjusted successfully.");
    }



    // 2. Save a new category to the database
    public function storeCategory(Request $request)
    {
        // Always validate manual inputs for security
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string']
        ]);

        // Create the category using the validated data
        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Redirect back to the inventory page with a success message
        return redirect('/inventory')->with('success', 'New category added successfully!');
    }

    // 3. Show the Excel Import Form
    public function excelRestockForm()
    {
        return view('inventory.excel_import');
    }

    // 4. Process the Excel Import
    public function storeExcelImport(Request $request){
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv']
        ]);

            // 1. Weka hesabu ya session kuwa 0 kabla ya kuanza
            session(['excel_updated_count' => 0]);

            // 2. Fanya import kama kawaida
            Excel::import(new InventoryRestockImport, $request->file('excel_file'));

            // 3. Soma hesabu iliyofanyika kutoka kwenye session
            $count = session('excel_updated_count', 0);

            // 4. Safisha session baada ya kuitumia (Hiari)
            session()->forget('excel_updated_count');
            
        if ($count > 0) {
            return redirect()->route('inventory.index')->with('success', "Inventory updated successfully! {$count} products restocked.");
        } else {
            return redirect()->route('inventory.index')->with('info', "No valid products found in the Excel file. Please check your data and try again.");
        }



    }

    public function downloadExcelTemplate()
{
    return Excel::download(new InventoryTemplateExport, 'divine_inventory_template.xlsx');
}

}
