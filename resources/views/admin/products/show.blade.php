@extends('layouts.admin')

@section('title', 'Détails du Produit')
@section('subtitle', 'Informations complètes du produit')

@section('content')
<div class="space-y-6">
    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" 
           class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-900 rounded-xl transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Retour à la liste</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product) }}" 
               class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="edit-2" class="w-4 h-4"></i>
                <span>Modifier</span>
            </a>
        </div>
    </div>

    <!-- Product Details Card -->
    <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Image -->
            <div class="lg:col-span-1">
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <img src="{{ $product->image_url }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-64 object-cover rounded-lg"
                         onerror="this.src='{{ asset('images/default-product.png') }}'">
                </div>
            </div>

            <!-- Product Information -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $product->name }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Réf: {{ $product->reference }}</p>
                        @if($product->barcode)
                            <p class="text-[10px] text-slate-500 font-medium">Code-barres: {{ $product->barcode }}</p>
                        @endif
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $product->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-600' }}">
                        {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                    </span>
                </div>

                <!-- Category Badge -->
                <div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-600">
                        {{ ucfirst($product->category) }}
                    </span>
                </div>

                <!-- Description -->
                @if($product->description)
                    <div class="border-t border-slate-200 pt-4">
                        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Description</h4>
                        <p class="text-xs text-slate-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <!-- Pricing Information -->
                <div class="border-t border-slate-200 pt-4">
                    <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Informations tarifaires</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <p class="text-[10px] text-slate-500 font-medium mb-1">Prix d'achat</p>
                            <p class="text-sm font-bold text-slate-900">{{ number_format($product->purchase_price, 0, ',', ' ') }} Ar</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-xl border border-green-200">
                            <p class="text-[10px] text-green-600 font-medium mb-1">Prix de vente</p>
                            <p class="text-sm font-bold text-green-600">{{ number_format($product->selling_price, 0, ',', ' ') }} Ar</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-xl border border-purple-200">
                            <p class="text-[10px] text-purple-600 font-medium mb-1">Marge bénéficiaire</p>
                            <p class="text-sm font-bold text-purple-600">{{ $product->profit_margin }}%</p>
                            <p class="text-[10px] text-purple-500 mt-0.5">{{ number_format($product->profit, 0, ',', ' ') }} Ar</p>
                        </div>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="border-t border-slate-200 pt-4">
                    <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Gestion du stock</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <p class="text-[10px] text-slate-500 font-medium mb-1">Stock actuel</p>
                            <p class="text-lg font-bold text-slate-900">{{ $product->stock }}</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-xl border border-amber-200">
                            <p class="text-[10px] text-amber-600 font-medium mb-1">Stock minimum</p>
                            <p class="text-lg font-bold text-amber-600">{{ $product->minimum_stock }}</p>
                        </div>
                        <div class="p-3 rounded-xl border
                            {{ $product->is_out_of_stock ? 'bg-red-50 border-red-200' : 
                               ($product->is_low_stock ? 'bg-amber-50 border-amber-200' : 'bg-green-50 border-green-200') }}">
                            <p class="text-[10px] font-medium mb-1
                                {{ $product->is_out_of_stock ? 'text-red-600' : 
                                   ($product->is_low_stock ? 'text-amber-600' : 'text-green-600') }}">
                                Statut du stock
                            </p>
                            <p class="text-sm font-bold
                                {{ $product->is_out_of_stock ? 'text-red-600' : 
                                   ($product->is_low_stock ? 'text-amber-600' : 'text-green-600') }}">
                                {{ $product->is_out_of_stock ? 'Rupture de stock' : 
                                   ($product->is_low_stock ? 'Stock faible' : 'En stock') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-[18px] p-4 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between text-[10px] text-slate-500">
            <div>
                <span class="font-medium">Créé le: </span>
                {{ $product->created_at->format('d/m/Y à H:i') }}
            </div>
            <div>
                <span class="font-medium">Dernière modification: </span>
                {{ $product->updated_at->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    document.addEventListener('alpine:initialized', function() {
        lucide.createIcons();
    });
</script>
@endpush