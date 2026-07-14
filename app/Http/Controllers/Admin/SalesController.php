<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    /**
     * Display the sales/POS page.
     */
    public function index()
    {
        $products = Product::active()->orderBy('name')->get();
        $customers = Customer::active()->get();
        
        return view('admin.sales', compact('products', 'customers'));
    }

    /**
     * Store a new sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile,other',
            'amount_paid' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $sale = Sale::create([
            'sale_number' => 'SALE-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'customer_id' => $request->customer_id,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'change' => $request->amount_paid - $request->total,
            'status' => 'completed',
            'sale_date' => now(),
        ]);

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

            // Create stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'sale',
                'quantity' => $item['quantity'],
                'stock_before' => $product->stock + $item['quantity'],
                'stock_after' => $product->stock,
                'reference' => $sale->sale_number,
                'notes' => 'Vente ' . $sale->sale_number,
                'movement_date' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'sale' => $sale,
            'message' => 'Vente enregistrée avec succès!'
        ]);
    }

    /**
     * Display sales history.
     */
    public function history()
    {
        $sales = Sale::with(['user', 'customer', 'items'])
            ->orderBy('sale_date', 'desc')
            ->paginate(20);
        
        return view('admin.sales.history', compact('sales'));
    }
}