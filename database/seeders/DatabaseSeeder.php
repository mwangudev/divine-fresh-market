<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Staff Accounts
        User::updateOrCreate(
            ['email' => 'admin@divine.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@divine.com'],
            [
                'name' => 'John Cashier',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
            ]
        );

        // 2. Define Realistic Store Data
        $storeData = [
            'Fresh Vegetables' => [
                ['name' => 'Red Tomatoes', 'buy' => 1500, 'sell' => 2000, 'qty' => 50, 'unit' => 'kg'],
                ['name' => 'White Onions', 'buy' => 1000, 'sell' => 1500, 'qty' => 30, 'unit' => 'kg'],
                ['name' => 'Fresh Spinach', 'buy' => 500, 'sell' => 1000, 'qty' => 20, 'unit' => 'bunches'],
            ],
            'Fresh Fruits' => [
                ['name' => 'Sweet Bananas', 'buy' => 2000, 'sell' => 3000, 'qty' => 15, 'unit' => 'bunches'],
                ['name' => 'Mangoes', 'buy' => 800, 'sell' => 1200, 'qty' => 40, 'unit' => 'pcs'],
                ['name' => 'Local Oranges', 'buy' => 300, 'sell' => 500, 'qty' => 100, 'unit' => 'pcs'],
            ],
            'Meat & Seafood' => [
                ['name' => 'Fresh Tilapia Fish', 'buy' => 10000, 'sell' => 15000, 'qty' => 10, 'unit' => 'pcs'],
                ['name' => 'Beef Steak', 'buy' => 8000, 'sell' => 12000, 'qty' => 25, 'unit' => 'kg'],
                ['name' => 'Whole Chicken', 'buy' => 7000, 'sell' => 10000, 'qty' => 12, 'unit' => 'pcs'],
            ],
            'Pantry Essentials' => [
                ['name' => 'Sunflower Cooking Oil', 'buy' => 6500, 'sell' => 8000, 'qty' => 40, 'unit' => 'liters'],
                ['name' => 'Premium Rice', 'buy' => 2000, 'sell' => 2800, 'qty' => 100, 'unit' => 'kg'],
                ['name' => 'Wheat Flour', 'buy' => 1800, 'sell' => 2200, 'qty' => 60, 'unit' => 'kg'],
            ],
            'Dairy Products' => [
                ['name' => 'Fresh Cow Milk', 'buy' => 1500, 'sell' => 2500, 'qty' => 30, 'unit' => 'liters'],
                ['name' => 'Cheddar Cheese', 'buy' => 12000, 'sell' => 18000, 'qty' => 5, 'unit' => 'pcs'],
                ['name' => 'Real Butter', 'buy' => 6000, 'sell' => 8500, 'qty' => 15, 'unit' => 'pcs'],
            ],
        ];

        // 3. Loop through the data and save it to the database
        foreach ($storeData as $categoryName => $products) {
            // Create the Category
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['description' => 'All items related to ' . $categoryName]
            );

            // Create the Products for this Category
            foreach ($products as $prod) {
                Product::firstOrCreate(
                    ['name' => $prod['name']], // Prevent duplicates if run twice
                    [
                        'category_id' => $category->id,
                        'sku' => strtoupper(substr($categoryName, 0, 3)) . '-' . rand(1000, 9999),
                        'buying_price' => $prod['buy'],
                        'selling_price' => $prod['sell'],
                        'stock_quantity' => $prod['qty'],
                        'unit_of_measure' => $prod['unit'],
                    ]
                );
            }
        }

        $this->command->info('Divine Fresh Market has been fully stocked!');
    }
}
