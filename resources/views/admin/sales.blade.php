@extends('layouts.admin')

@section('title', 'Caisse & Vente POS')
@section('subtitle', 'Saisissez les ventes rapidement, gérez le panier et encaissez.')

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
    
    $customersForJs = $customers->map(function ($customer) {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
        ];
    });
@endphp
<script>
    function salesData() {
        return {
            // Product Catalogue Data
    catalog: @json($productsForJs, JSON_UNESCAPED_SLASHES),
    customers: @json($customersForJs, JSON_UNESCAPED_SLASHES),

    // POS Cart State
    cart: [],
    selectedCustomer: '',

    // Search and Filters
    selectedCategory: 'all',
    searchQuery: '',
    discount: 0,
    showCheckoutSuccess: false,
    isProcessing: false,
    
    // POS Cart Functions
    addToCart(item) {
        if (item.stock <= 0) {
            alert('Ce produit est en rupture de stock !');
            return;
        }
        let existing = this.cart.find(i => i.id === item.id);
        if (existing) {
            if (existing.qty >= item.stock) {
                alert('Stock maximum atteint pour ce produit!');
                return;
            }
            existing.qty++;
        } else {
            this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1, image: item.image, stock: item.stock });
        }
    },
    removeFromCart(id) {
        this.cart = this.cart.filter(i => i.id !== id);
    },
    decreaseQty(item) {
        if (item.qty > 1) {
            item.qty--;
        } else {
            this.removeFromCart(item.id);
        }
    },
    increaseQty(item) {
        item.qty++;
    },
    get subtotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    },
    get total() {
        return Math.max(0, this.subtotal - this.discount);
    },
    clearCart() {
        this.cart = [];
    },
    async checkout() {
        if (this.cart.length === 0 || this.isProcessing) return;
        
        this.isProcessing = true;
        
        try {
            const response = await fetch('{{ route("admin.sales.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: this.cart.map(item => ({
                        product_id: item.id,
                        quantity: item.qty
                    })),
                    subtotal: this.subtotal,
                    discount: this.discount,
                    total: this.total,
                    payment_method: 'cash',
                    amount_paid: this.total,
                    customer_id: this.selectedCustomer || null
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showCheckoutSuccess = true;
                this.clearCart();
                this.selectedCustomer = '';
                this.discount = 0;
                
                // Reload page after 2 seconds to update stock
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                alert(data.message || 'Erreur lors de l\'enregistrement de la vente');
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        } finally {
            this.isProcessing = false;
        }
    },
    closeCheckout() {
        this.showCheckoutSuccess = false;
        this.clearCart();
    }
        };
    }
</script>
<div x-data="salesData()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- POS Left Column: Catalog Selector (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Search & Cat Tabs -->
        <div class="bg-white p-6 rounded-[18px] border border-slate-200 shadow-sm space-y-4">
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Recherche rapide de produits (scanner ou saisir)..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none transition-all">
                <i data-lucide="search" class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500"></i>
            </div>
            
            <!-- Category Tabs selector pills -->
            <div class="flex flex-wrap gap-2 pt-1">
                <button @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition-all uppercase tracking-wider">Tous</button>
                <button @click="selectedCategory = 'alimentation'" :class="selectedCategory === 'alimentation' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition-all uppercase tracking-wider">Alimentation</button>
                <button @click="selectedCategory = 'boissons'" :class="selectedCategory === 'boissons' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition-all uppercase tracking-wider">Boissons</button>
                <button @click="selectedCategory = 'hygiene'" :class="selectedCategory === 'hygiene' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition-all uppercase tracking-wider">Hygiène</button>
            </div>
        </div>

        <!-- Products Catalog Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <template x-for="item in catalog" :key="item.id">
                <div x-show="(selectedCategory === 'all' || item.category === selectedCategory) && (searchQuery === '' || item.name.toLowerCase().includes(searchQuery.toLowerCase()))" 
                     @click="addToCart(item)"
                     :class="item.stock <= 0 ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:scale-[1.02] hover:shadow-md border-slate-200'" 
                     class="bg-white rounded-[18px] p-4 border shadow-sm transition-all duration-200 flex flex-col justify-between relative group overflow-hidden select-none">
                    
                    <!-- Stock Status Label -->
                    <span :class="item.stock <= 0 ? 'bg-red-100 text-red-600' : item.stock <= item.minStock ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600'" class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider z-10" x-text="item.stock <= 0 ? 'Rupture' : item.stock + ' Restants'"></span>

                    <div class="relative h-28 w-full rounded-xl overflow-hidden mb-3 bg-slate-50 flex items-center justify-center border border-slate-100">
                        <img class="h-full w-full object-cover group-hover:scale-105 transition-all duration-300" :src="item.image" :alt="item.name">
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 line-clamp-1" x-text="item.name"></h4>
                        <div class="flex items-center justify-between mt-2 pt-1 border-t border-slate-100">
                            <span class="text-xs font-extrabold text-green-600" x-text="new Intl.NumberFormat().format(item.price) + ' Ar'"></span>
                            <div class="p-1.5 bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white rounded-lg transition-all">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- POS Right Column: Checkout Cart Panel (1/3 width) -->
    <div class="bg-white rounded-[18px] border border-slate-200 shadow-sm p-6 flex flex-col justify-between min-h-[550px]">
        
        <!-- Panel Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
            <div class="flex items-center space-x-2">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-slate-500"></i>
                <h3 class="text-sm font-bold text-slate-900">Panier actuel</h3>
            </div>
            <button @click="clearCart()" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Vider</span>
            </button>
        </div>

        <!-- Cart Items List Scrollable -->
        <div class="flex-1 overflow-y-auto space-y-4 max-h-[300px] pr-1 scrollbar">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
                    <i data-lucide="shopping-cart" class="w-12 h-12 stroke-[1.5] text-slate-300 mb-2"></i>
                    <span class="text-xs font-semibold text-slate-900">Le panier est vide</span>
                    <span class="text-[10px] mt-1">Sélectionnez des articles pour débuter la vente.</span>
                </div>
            </template>
            
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center justify-between py-1 border-b border-slate-100 pb-2">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <img class="w-8 h-8 rounded-lg object-cover bg-slate-50 flex-shrink-0" :src="item.image" :alt="item.name">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900 truncate" x-text="item.name"></h4>
                            <span class="text-[10px] text-slate-500 font-bold" x-text="new Intl.NumberFormat().format(item.price) + ' Ar'"></span>
                        </div>
                    </div>
                    
                    <!-- Qty Selector -->
                    <div class="flex items-center space-x-2 px-2">
                        <button @click="decreaseQty(item)" class="p-1 text-slate-500 hover:text-slate-900 rounded-md hover:bg-slate-100 border border-slate-200">
                            <i data-lucide="minus" class="w-3 h-3"></i>
                        </button>
                        <span class="text-xs font-bold text-slate-900" x-text="item.qty"></span>
                        <button @click="increaseQty(item)" class="p-1 text-slate-500 hover:text-slate-900 rounded-md hover:bg-slate-100 border border-slate-200">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                        </button>
                    </div>
                    
                    <div class="text-right pl-2">
                        <span class="text-xs font-bold text-slate-900 block" x-text="new Intl.NumberFormat().format(item.price * item.qty) + ' Ar'"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Financial Calc Panel -->
        <div class="border-t border-slate-200 pt-4 mt-4 space-y-2.5">
            <div class="flex justify-between text-xs font-semibold text-slate-500">
                <span>Sous-total</span>
                <span class="text-slate-900" x-text="new Intl.NumberFormat().format(subtotal) + ' Ar'"></span>
            </div>
            <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                <span>Remise manuelle</span>
                <div class="flex items-center gap-1.5 w-24">
                    <input type="number" x-model="discount" min="0" class="w-full text-right px-2 py-1 border border-slate-200 bg-slate-50 rounded text-xs font-bold focus:outline-none focus:ring-1 focus:ring-green-600">
                    <span class="text-[10px] font-bold">Ar</span>
                </div>
            </div>
            <div class="border-t border-dashed border-slate-200 my-2"></div>
            <div class="flex justify-between items-center">
                <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Total à payer</span>
                <span class="text-xl font-extrabold text-green-600 tracking-tight" x-text="new Intl.NumberFormat().format(total) + ' Ar'"></span>
            </div>
        </div>

        <!-- Checkout Button -->
        <button @click="checkout()" :disabled="cart.length === 0" :class="cart.length === 0 ? 'bg-slate-100 text-slate-400 cursor-not-allowed shadow-none' : 'bg-green-600 hover:bg-green-700 text-white hover:scale-[1.01] active:scale-95 shadow-sm shadow-green-600/20'" class="w-full mt-6 py-4 rounded-xl text-xs font-bold tracking-wider uppercase transition-all duration-200 flex items-center justify-center gap-2">
            <i data-lucide="credit-card" class="w-4.5 h-4.5"></i>
            <span>Encaisser</span>
        </button>
    </div>

    <!-- POS Checkout Receipt Success Modal -->
    <div x-show="showCheckoutSuccess" class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div class="bg-white rounded-[18px] max-w-sm w-full p-6 text-center space-y-6 shadow-xl border border-slate-200 animate-fade-in" @click.away="closeCheckout()">
            
            <!-- Checked Circle Icon -->
            <div class="mx-auto p-3 bg-green-100 text-green-600 rounded-full w-14 h-14 flex items-center justify-center shadow-inner shadow-green-600/5">
                <i data-lucide="check-circle" class="w-8 h-8 stroke-[2.5]"></i>
            </div>
            
            <div class="space-y-1">
                <h3 class="text-base font-bold text-slate-900">Encaissement validé !</h3>
                <p class="text-[11px] text-slate-500 font-medium">Le ticket de caisse a été validé et imprimé.</p>
            </div>
            
            <!-- Receipt Abstract Details Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-left divide-y divide-slate-100 space-y-3.5 text-xs">
                <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                    <span>Ticket #TX-9483</span>
                    <span x-text="new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})"></span>
                </div>
                
                <!-- Dummy Items list -->
                <div class="pt-3 space-y-2">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between font-medium">
                            <span class="text-slate-500" x-text="item.qty + 'x ' + item.name"></span>
                            <span class="text-slate-900" x-text="new Intl.NumberFormat().format(item.price * item.qty) + ' Ar'"></span>
                        </div>
                    </template>
                </div>
                
                <!-- Totals -->
                <div class="pt-3 space-y-1.5 font-bold">
                    <div class="flex justify-between text-slate-500 text-[10px]">
                        <span>Sous-total</span>
                        <span x-text="new Intl.NumberFormat().format(subtotal) + ' Ar'"></span>
                    </div>
                    <div x-show="discount > 0" class="flex justify-between text-red-600 text-[10px]">
                        <span>Remise</span>
                        <span x-text="'-' + new Intl.NumberFormat().format(discount) + ' Ar'"></span>
                    </div>
                    <div class="flex justify-between text-slate-900 text-sm pt-1">
                        <span>TOTAL ENCAISSÉ</span>
                        <span class="text-green-600" x-text="new Intl.NumberFormat().format(total) + ' Ar'"></span>
                    </div>
                </div>
            </div>
            
            <div class="pt-2 flex flex-col gap-2">
                <button @click="closeCheckout()" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    Terminer et Nouvelle Vente
                </button>
                <button @click="alert('Impression d\'un duplicata...')" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-900 rounded-xl transition-all">
                    Réimprimer le ticket
                </button>
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