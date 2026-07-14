@extends('layouts.admin')

@section('title', 'Suivi du Stock')
@section('subtitle', 'Ajustez le stock, surveillez les alertes de rupture et inventaires.')

@section('content')
@php
    $productsForJs = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'reference' => $product->reference,
            'category' => $product->category,
            'stock' => $product->stock,
            'minStock' => $product->minimum_stock,
            'buyPrice' => (float) $product->purchase_price,
            'price' => (float) $product->selling_price,
            'image' => $product->image_url,
            'status' => $product->status,
        ];
    });
@endphp
<script>
    function stockData() {
        return {
            // Product Catalogue Data
    catalog: @json($productsForJs, JSON_UNESCAPED_SLASHES),

    // Stock adjustment form
    selectedProduct: '',
    adjustmentType: 'purchase',
    adjustmentQty: 5,
    isProcessing: false,
    
    // Stock adjustment function
    async adjustStock() {
        if (!this.selectedProduct) {
            alert('Veuillez sélectionner un produit !');
            return;
        }
        
        if (this.isProcessing) return;
        this.isProcessing = true;
        
        try {
            const response = await fetch('{{ route("admin.stock.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: this.selectedProduct,
                    type: this.adjustmentType,
                    quantity: this.adjustmentQty,
                    notes: 'Ajustement manuel'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                this.selectedProduct = '';
                this.adjustmentQty = 5;
                // Reload page to update data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert(data.message || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        } finally {
            this.isProcessing = false;
        }
    }
        };
    }
</script>
<div x-data="stockData()" class="space-y-8">
    
    <!-- Stock KPI Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-green-100 rounded-xl text-green-600">
                <i data-lucide="layers" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Valeur du Stock</span>
                <span class="text-xl font-bold text-slate-900 block mt-0.5">{{ number_format($totalStockValue, 0, ',', ' ') }} Ar</span>
                <span class="text-[9px] text-slate-500">Calculé sur prix d'achat</span>
            </div>
        </div>
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-amber-100 rounded-xl text-amber-600">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Alertes Critiques</span>
                <span class="text-xl font-bold text-amber-600 block mt-0.5">{{ $lowStockCount }} Article{{ $lowStockCount > 1 ? 's' : '' }}</span>
                <span class="text-[9px] text-slate-500">Niveau sous le seuil minimal</span>
            </div>
        </div>
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-red-100 rounded-xl text-red-600">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Ruptures de stock</span>
                <span class="text-xl font-bold text-red-600 block mt-0.5">{{ $outOfStockCount }} Article{{ $outOfStockCount > 1 ? 's' : '' }}</span>
                <span class="text-[9px] text-slate-500">Aucune unité en magasin</span>
            </div>
        </div>
    </div>

    <!-- Inventory Movement History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Stock adjustment form (1/3) -->
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Ajustement du stock</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Saisir les entrées ou corrections manuelles</p>
                </div>
                
                <form @submit.prevent="adjustStock()" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Produit</label>
                        <select x-model="selectedProduct" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                            <option value="">Sélectionner un produit</option>
                            <template x-for="item in catalog" :key="item.id">
                                <option :value="item.id" x-text="item.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Mouvement</label>
                            <select x-model="adjustmentType" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <option value="add">Entrée (+)</option>
                                <option value="remove">Sortie (-)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Quantité</label>
                            <input x-model="adjustmentQty" type="number" required min="1" value="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                        Enregistrer le mouvement
                    </button>
                </form>
            </div>
        </div>

        <!-- low stock list table (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Mouvements de stock récents</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Historique des entrées, ventes et corrections</p>
                </div>
                
                <div class="divide-y divide-slate-200">
                    @forelse($recentMovements as $movement)
                        <div class="flex justify-between items-center py-3 text-xs font-medium">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 {{ in_array($movement->type, ['purchase', 'return', 'adjustment']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-xl flex items-center justify-center">
                                    <i data-lucide="{{ in_array($movement->type, ['purchase', 'return', 'adjustment']) ? 'arrow-up-right' : 'arrow-down-right' }}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-slate-900 font-bold block">{{ ucfirst($movement->type) }} - {{ $movement->product->name }}</span>
                                    <span class="text-slate-500 text-[10px]">{{ $movement->notes ?? 'Aucune note' }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="{{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }} font-bold block">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }} unités
                                </span>
                                <span class="text-slate-500 text-[10px]">{{ $movement->movement_date->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-500">
                            <p class="text-xs">Aucun mouvement de stock enregistré</p>
                        </div>
                    @endforelse
                </div>
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