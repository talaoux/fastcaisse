<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::first();
        }

        // Get all products
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please create products first.');
            return;
        }

        // Create sales for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);
            
            // Create 1-3 sales per day
            $salesCount = rand(1, 3);
            
            for ($j = 0; $j < $salesCount; $j++) {
                // Create sale
                $sale = Sale::create([
                    'sale_number' => 'TX-' . rand(1000, 9999),
                    'user_id' => $user->id,
                    'customer_id' => null,
                    'subtotal' => 0,
                    'discount' => 0,
                    'total' => 0,
                    'payment_method' => 'cash',
                    'amount_paid' => 0,
                    'change' => 0,
                    'status' => 'completed',
                    'sale_date' => $date->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                ]);

                // Add 1-5 items to each sale
                $itemsCount = rand(1, 5);
                $total = 0;
                $subtotal = 0;

                for ($k = 0; $k < $itemsCount; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 10);
                    $unitPrice = $product->selling_price;
                    $purchasePrice = $product->purchase_price;
                    $itemSubtotal = $unitPrice * $quantity;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_reference' => $product->reference,
                        'unit_price' => $unitPrice,
                        'purchase_price' => $purchasePrice,
                        'quantity' => $quantity,
                        'subtotal' => $itemSubtotal,
                    ]);

                    $subtotal += $itemSubtotal;
                }

                // Update sale totals
                $sale->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'amount_paid' => $subtotal,
                ]);
            }
        }

        $this->command->info('Sales seeded successfully!');
    }
}