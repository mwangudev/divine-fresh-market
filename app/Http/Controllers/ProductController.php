<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    //Eager load the category relationship to optimize queries
    public function index(){
        $products = Product::with('category')->latest()->get();

        //Fetch categories for populate the HTML select dropdown
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));

    }

    // 2. save a new product to the database
    public function store(Request $request){

        // strict validation rules for decimals and strings
        $request->validate([
            'category_id'=> ['required', 'exists:categories,id'],
            'name'=> ['required', 'string', 'exists:products,id', 'max:255'],
            'sku'=> ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'buying_price'=> ['required', 'numeric', 'min:0'],
            'selling_price'=> ['required', 'numeric', 'min:0'],
            'stock_quantity'=> ['required', 'integer', 'min:0'],
            'unit_of_measure'=> ['required', 'string', 'max:50'],
            'description'=> ['nullable', 'string'],
        ]);

        // Create the new product
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // 3. Restock or Edit an existing product
    public function update(Request $request, Product $product)
    {
        // Validate the incoming data
        $request->validate([
            'add_stock' => ['nullable', 'numeric', 'min:0'], // How much new stock arrived?
            'buying_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        // Add the new stock to the existing stock (if they typed a number)
        if ($request->filled('add_stock')) {
            $product->stock_quantity += $request->add_stock;
        }

        // Update the prices in case they changed with the new shipment
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;

        $product->save();

        return redirect()->route('products.index')
            ->with('success', $product->name . ' has been updated successfully! New Stock: ' . $product->stock_quantity);
    }
}
