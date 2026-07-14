<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtre par recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filtre par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculer les statistiques pour les filtres
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'minimum_stock')->count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Générer une référence automatique
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $nextReference = $lastProduct ? 'REF-' . str_pad($lastProduct->id + 1, 6, '0', STR_PAD_LEFT) : 'REF-000001';

        return view('admin.products.create', compact('nextReference'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Créer le dossier s'il n'existe pas
                $image->storeAs('public/products', $imageName);
                
                $data['image'] = 'products/' . $imageName;
            } else {
                // Image par défaut
                $data['image'] = null;
            }

            $product = Product::create($data);

            return redirect()
                ->route('products.index')
                ->with('success', 'Produit ajouté avec succès.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout du produit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                    Storage::disk('public')->delete($product->image);
                }

                // Upload de la nouvelle image
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                
                $data['image'] = 'products/' . $imageName;
            }

            $product->update($data);

            return redirect()
                ->route('products.index')
                ->with('success', 'Produit modifié avec succès.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification du produit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Supprimer l'image associée
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }

    /**
     * Get products for AJAX requests (pour le POS et les sélecteurs).
     */
    public function getProducts(Request $request)
    {
        $query = Product::active()->where('stock', '>', 0);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->get(['id', 'name', 'reference', 'selling_price', 'stock', 'category', 'image']);

        return response()->json($products);
    }
}