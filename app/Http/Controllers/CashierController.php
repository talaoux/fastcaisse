<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
}