<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            // create a few demo movements per product
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $type = (rand(0, 1) === 1) ? 'purchase' : 'sale';
                $qty = rand(1, max(1, (int)floor(max(1, $product->stock ?: 10) / 2)));

                $stockBefore = $product->stock;
                $stockAfter = $stockBefore + ($type === 'purchase' ? $qty : -$qty);
                if ($stockAfter < 0) {
                    $stockAfter = 0;
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => 1,
                    'type' => $type,
                    'quantity' => $qty,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference' => null,
                    'notes' => 'Seeded demo movement',
                    'movement_date' => now()->subDays(rand(0, 10)),
                ]);

                // update product stock for next iteration
                $product->stock = $stockAfter;
                $product->save();
            }
        }
    }
}
