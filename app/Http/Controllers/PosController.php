<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    // 1. Show the Cash Register Screen
    public function index()
    {
        // Fetch products that actually have stock to show on the buttons
        $products = Product::where('stock_quantity', '>', 0)->get();

        return view('pos.index', compact('products'));
    }

    // 2. Process the Sale from the Frontend JavaScript
    public function checkout(Request $request)
    {
        // 1. Validate that we actually received a cart and payment method
        $request->validate([
            'cart' => ['required', 'array'],
            'cart.*.product_id' => ['required', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string']
        ]);

        // 2. Start a Database Transaction (Safety Net)
        DB::beginTransaction();

        try {
            $grandTotal = 0;
            $saleItemsData = [];

            // 3. Loop through everything the cashier clicked
            foreach ($request->cart as $item) {
                // Find the product in the database securely
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                // Make sure we have enough stock!
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}. Only {$product->stock_quantity} left.");
                }

                // SECURE MATH: Calculate the subtotal using the database price, NOT the frontend
                $subtotal = $product->selling_price * $item['quantity'];
                $grandTotal += $subtotal;

                // Prepare the line item for the receipt
                $saleItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ];

                // Deduct the stock
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            // 4. Create the main Sale (The Top of the Receipt)
            $sale = Sale::create([
                'user_id' => Auth::id(), // Records which cashier made the sale
                'total_amount' => $grandTotal,
                'payment_method' => $request->payment_method,
            ]);

            // 5. Attach all the items to this sale (The Middle of the Receipt)
            $sale->items()->createMany($saleItemsData);

            // 6. Everything is perfect. Save to database permanently.
            DB::commit();

            // Send the cashier back to a fresh register screen with a success message
            return redirect()->route('pos.receipt', $sale->id);

        } catch (\Exception $e) {
            // If anything goes wrong (like low stock), undo all database changes and show the error
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function receipt($id){
        $sale = Sale::with(['items.product', 'user'])->findorFail($id);
        return view('pos.receipt', compact('sale'));
    }
}
