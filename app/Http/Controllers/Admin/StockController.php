<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display the stock management page.
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('movement_date', 'desc')
            ->limit(20)
            ->get();
        
        // Calculate KPIs
        $totalStockValue = Product::sum(\DB::raw('stock * purchase_price'));
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        
        return view('admin.stock', compact('products', 'recentMovements', 'totalStockValue', 'lowStockCount', 'outOfStockCount'));
    }

    /**
     * Store a new stock movement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:purchase,sale,adjustment,return,loss',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);
        $stockBefore = $product->stock;
        $quantity = $request->quantity;

        // Calculate stock after movement
        if (in_array($request->type, ['purchase', 'return', 'adjustment'])) {
            $stockAfter = $stockBefore + $quantity;
        } else {
            // sale, loss
            if ($stockBefore < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant! Stock disponible: ' . $stockBefore
                ], 422);
            }
            $stockAfter = $stockBefore - $quantity;
        }

        // Create stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'movement_date' => now(),
        ]);

        // Update product stock
        $product->update(['stock' => $stockAfter]);

        return response()->json([
            'success' => true,
            'message' => 'Mouvement de stock enregistré avec succès!',
            'stock_after' => $stockAfter
        ]);
    }
}
