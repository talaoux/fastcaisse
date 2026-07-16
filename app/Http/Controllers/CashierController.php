<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    /**
     * Display the cashier dashboard.
     */
    public function index()
    {
        $products = Product::active()->orderBy('name')->get();
        
        // Préparer les produits pour le JavaScript sans utiliser les accesseurs
        $productsForJs = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'reference' => $product->reference,
                'category' => $product->category,
                'stock' => $product->stock,
                'price' => (float) $product->selling_price,
                'image' => $product->image ? asset('storage/' . $product->image) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#f1f5f9"/><rect x="50" y="60" width="100" height="80" rx="8" fill="#cbd5e1"/><path d="M70 100 L90 120 L130 80" stroke="#94a3b8" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="100" cy="100" r="60" fill="none" stroke="#e2e8f0" stroke-width="2"/></svg>'),
            ];
        });
        
        return view('cashier.dashboard', compact('products', 'productsForJs'));
    }

    /**
     * Store a new sale from cashier.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Sale request received', ['data' => $request->all()]);

            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'payment_method' => 'required|in:especes,mobile,carte,bon',
                'amount_paid' => 'required|numeric|min:0',
            ]);

            \Log::info('Validation passed');

            $sale = Sale::create([
                'sale_number' => 'SALE-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'customer_id' => null,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change' => $request->amount_paid - $request->total,
                'status' => 'completed',
                'sale_date' => now(),
            ]);

            \Log::info('Sale created', ['sale_id' => $sale->id]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_reference' => $product->reference,
                    'unit_price' => $product->selling_price,
                    'purchase_price' => $product->purchase_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->selling_price * $item['quantity'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            \Log::info('Sale items created');

            return response()->json([
                'success' => true,
                'sale' => $sale,
                'message' => 'Vente enregistrée avec succès!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Sale error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}