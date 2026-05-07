<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class InventoryController extends Controller
{
    // 1. Show the main inventory page
    public function index()
    {
        // Fetch all categories from the database
        $categories = Category::all();

        return view('inventory.index', compact('categories'));
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
}
