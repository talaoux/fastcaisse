@extends('layouts.cashier')

@section('title', 'Nouvelle vente')

@section('subtitle', 'Espace Caissier')

@section('content')
@php
    $productsForJs = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'reference' => $product->reference,
            'category' => $product->category,
            'stock' => $product->stock,
            'price' => (float) $product->selling_price,
            'image' => $product->image_url,
        ];
    });
@endphp
<script>
    function cashierData() {
        return {
            catalog: @json($productsForJs, JSON_UNESCAPED_SLASHES),

            cart: [],
            selectedCategory: 'all',
            searchQuery: '',
            discount: 0,
            paymentMethod: 'especes',
            amountReceived: 0,
            showCheckoutSuccess: false,

            init() {
                // Initialize with especes payment method
                this.paymentMethod = 'especes';
            },
            
            get filteredProducts() {
                return this.catalog.filter(item => {
                    const categoryMatch = this.selectedCategory === 'all' || item.category === this.selectedCategory;
                    const searchMatch = this.searchQuery === '' || 
                        item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                        item.reference.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return categoryMatch && searchMatch;
                });
            },
            
            addToCart(item) {
                if (item.stock <= 0) {
                    alert('Ce produit est en rupture de stock !');
                    return;
                }
                let existing = this.cart.find(i => i.id === item.id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.cart.push({
                        id: item.id,
                        name: item.name,
                        price: item.price,
                        qty: 1,
                        image: item.image,
                        reference: item.reference
                    });
                }
                // Auto-fill amount received for cash payments
                if (this.paymentMethod === 'especes') {
                    this.amountReceived = this.total;
                }
            },
            
            removeFromCart(id) {
                this.cart = this.cart.filter(i => i.id !== id);
                if (this.paymentMethod === 'especes') {
                    this.amountReceived = this.total;
                }
            },

            decreaseQty(item) {
                if (item.qty > 1) {
                    item.qty--;
                } else {
                    this.removeFromCart(item.id);
                }
                if (this.paymentMethod === 'especes') {
                    this.amountReceived = this.total;
                }
            },

            increaseQty(item) {
                item.qty++;
                if (this.paymentMethod === 'especes') {
                    this.amountReceived = this.total;
                }
            },
            
            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },
            
            get tva() {
                return 0;
            },
            
            get total() {
                return Math.max(0, this.subtotal - this.discount);
            },
            
            get change() {
                return Math.max(0, this.amountReceived - this.total);
            },

            // Watch payment method to auto-fill amount for cash
            updatePaymentMethod() {
                if (this.paymentMethod === 'especes') {
                    this.amountReceived = this.total;
                }
            },
            
            clearCart() {
                this.cart = [];
                this.discount = 0;
                this.amountReceived = 0;
            },
            
            async checkout() {
                if (this.cart.length === 0) {
                    alert('Panier vide !');
                    return;
                }

                // Auto-fill amount for cash payments if not set
                if (this.paymentMethod === 'especes' && this.amountReceived < this.total) {
                    this.amountReceived = this.total;
                }

                if (this.amountReceived >= this.total) {
                    try {
                        // Prepare sale data
                        const saleData = {
                            items: this.cart.map(item => ({
                                product_id: item.id,
                                quantity: item.qty
                            })),
                            subtotal: this.subtotal,
                            discount: this.discount,
                            total: this.total,
                            payment_method: this.paymentMethod,
                            amount_paid: this.amountReceived
                        };

                        console.log('Sending sale data:', saleData);

                        // Send to server
                        const response = await fetch('/cashier/sales', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(saleData)
                        });

                        console.log('Response status:', response.status);

                        const result = await response.json();

                        console.log('Response result:', result);

                        if (result.success) {
                            this.showCheckoutSuccess = true;
                        } else {
                            alert('Erreur: ' + (result.message || 'Erreur lors de l\'enregistrement de la vente'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Erreur: ' + error.message);
                    }
                } else {
                    alert('Montant insuffisant !');
                }
            },
            
            closeCheckout() {
                this.showCheckoutSuccess = false;
                this.clearCart();
            },
            
            suspendSale() {
                if (this.cart.length > 0) {
                    alert('Vente suspendue avec succès !');
                    this.clearCart();
                }
            },
            
            printReceipt() {
                alert('Impression du ticket en cours...');
            },
            
            openCashDrawer() {
                alert('Tiroir-caisse ouvert !');
            }
        };
    }
</script>
<div x-data="cashierData()" class="space-y-4">

    <!-- ============================================ -->
    <!-- MAIN LAYOUT: TWO COLUMNS                     -->
    <!-- ============================================ -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        
        <!-- LEFT COLUMN: Products (70%) -->
        <div class="xl:col-span-2 space-y-4">
            
            <!-- Search Bar -->
            <div class="bg-white rounded-[18px] p-4 border border-slate-200 shadow-sm">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Rechercher un produit (nom, référence)..."
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all">
                    <i data-lucide="search" class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500"></i>
                </div>
                <!-- Results count -->
                <div class="mt-2 text-[10px] text-slate-500 font-medium">
                    <span x-text="filteredProducts.length + ' produit(s) trouvé(s)'"></span>
                </div>
            </div>
            
            <!-- Category Filters -->
            <div class="bg-white rounded-[18px] p-4 border border-slate-200 shadow-sm">
                <div class="flex flex-wrap gap-2">
                    <button @click="selectedCategory = 'all'" 
                            :class="selectedCategory === 'all' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'" 
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Tous
                    </button>
                    <button @click="selectedCategory = 'alimentation'" 
                            :class="selectedCategory === 'alimentation' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'" 
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Alimentation
                    </button>
                    <button @click="selectedCategory = 'boissons'" 
                            :class="selectedCategory === 'boissons' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'" 
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Boissons
                    </button>
                    <button @click="selectedCategory = 'hygiene'" 
                            :class="selectedCategory === 'hygiene' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'" 
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Hygiène
                    </button>
                    <button @click="selectedCategory = 'divers'" 
                            :class="selectedCategory === 'divers' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'" 
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Divers
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <template x-for="item in filteredProducts" :key="item.id">
                    <div @click="addToCart(item)"
                         :class="item.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:scale-[1.03] hover:shadow-md'" 
                         class="bg-white rounded-[18px] p-3 border border-slate-200 shadow-sm transition-all duration-300 flex flex-col justify-between relative group overflow-hidden">
                        
                        <!-- Category Badge -->
                        <span class="absolute top-2.5 right-2.5 px-2 py-1 bg-green-100 text-green-600 text-[9px] font-bold uppercase tracking-wider rounded-lg z-10" x-text="item.category">
                        </span>

                        <!-- Stock Status -->
                        <span x-show="item.stock <= 0" class="absolute top-2.5 left-2.5 px-2 py-1 bg-red-100 text-red-600 text-[9px] font-bold uppercase tracking-wider rounded-lg z-10">
                            Rupture
                        </span>

                        <!-- Product Image -->
                        <div class="relative h-28 w-full rounded-xl overflow-hidden mb-2.5 bg-slate-50 flex items-center justify-center border border-slate-100">
                            <img class="h-full w-full object-cover group-hover:scale-110 transition-all duration-300" :src="item.image" :alt="item.name">
                        </div>
                        
                        <!-- Product Info -->
                        <div class="space-y-1.5">
                            <div>
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider" x-text="item.reference"></span>
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-tight" x-text="item.name"></h4>
                            </div>
                            <div class="flex items-center justify-between pt-1.5 border-t border-slate-100">
                                <span class="text-sm font-extrabold text-green-600" x-text="new Intl.NumberFormat().format(item.price) + ' Ar'"></span>
                                <button class="p-1.5 bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white rounded-lg transition-all">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- Empty State -->
                <template x-if="filteredProducts.length === 0">
                    <div class="col-span-2 sm:col-span-3 lg:col-span-4">
                        <div class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
                            <i data-lucide="package" class="w-12 h-12 text-slate-300 mb-2"></i>
                            <span class="text-xs font-semibold text-slate-900">Aucun produit trouvé</span>
                            <span class="text-[10px] mt-1">Vérifiez vos filtres ou votre recherche</span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Quick Shortcuts -->
            <div class="bg-white rounded-[18px] p-4 border border-slate-200 shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                    <button @click="alert('Derniers produits')" 
                            class="flex flex-col items-center justify-center space-y-2 p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all duration-200">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                        <span class="text-[10px] font-bold">Derniers produits</span>
                    </button>
                    <button @click="alert('Produits favoris')" 
                            class="flex flex-col items-center justify-center space-y-2 p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all duration-200">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                        <span class="text-[10px] font-bold">Favoris</span>
                    </button>
                    <button @click="alert('Promotion du jour')" 
                            class="flex flex-col items-center justify-center space-y-2 p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all duration-200">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                        <span class="text-[10px] font-bold">Promo du jour</span>
                    </button>
                    <button @click="alert('Recherche avancée')" 
                            class="flex flex-col items-center justify-center space-y-2 p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all duration-200">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span class="text-[10px] font-bold">Recherche avancée</span>
                    </button>
                    <button @click="alert('Historique rapide')" 
                            class="flex flex-col items-center justify-center space-y-2 p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all duration-200">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                        <span class="text-[10px] font-bold">Historique rapide</span>
                    </button>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="bg-white rounded-[18px] p-4 border border-slate-200 shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                    <button @click="suspendSale()" 
                            class="py-3 bg-amber-100 hover:bg-amber-200 text-amber-600 border border-amber-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                        <i data-lucide="pause" class="w-4 h-4"></i>
                        <span>Suspendre vente</span>
                    </button>
                    <button @click="alert('Mettre en attente')" 
                            class="py-3 bg-blue-100 hover:bg-blue-200 text-blue-600 border border-blue-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>Mettre en attente</span>
                    </button>
                    <button @click="clearCart()" 
                            class="py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-sm">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Nouvelle vente</span>
                    </button>
                    <button @click="openCashDrawer()" 
                            class="py-3 bg-green-100 hover:bg-green-200 text-green-600 border border-green-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                        <i data-lucide="lock-open" class="w-4 h-4"></i>
                        <span>Ouvrir tiroir</span>
                    </button>
                    <button @click="printReceipt()" 
                            class="py-3 bg-slate-100 hover:bg-slate-200 text-slate-900 border border-slate-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>Imprimer ticket</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Cart Panel (30%) -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-[18px] border border-slate-200 shadow-sm p-5 flex flex-col sticky top-24" style="max-height: calc(100vh - 120px);">
                
                <!-- Cart Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-3">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>
                        <h3 class="text-sm font-bold text-slate-900">Panier actuel</h3>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-bold rounded-full" x-text="cart.length + ' art.'"></span>
                    </div>
                    <button @click="clearCart()" 
                            x-show="cart.length > 0"
                            class="text-xs font-bold text-red-600 hover:underline">
                        Vider
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto space-y-2 mb-3 pr-1" style="max-height: 300px;">
                    <template x-if="cart.length === 0">
                        <div class="flex flex-col items-center justify-center py-8 text-center text-slate-500">
                            <i data-lucide="shopping-cart" class="w-12 h-12 stroke-[1.5] text-slate-300 mb-2"></i>
                            <span class="text-xs font-semibold text-slate-900">Panier vide</span>
                            <span class="text-[10px] mt-1">Ajoutez des articles pour commencer</span>
                        </div>
                    </template>
                    
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-start gap-2.5 p-2.5 bg-slate-50 rounded-xl border border-slate-100 hover:border-blue-600/20 transition-all">
                            <img class="w-10 h-10 rounded-lg object-cover bg-white border border-slate-200 flex-shrink-0" :src="item.image" :alt="item.name">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-[11px] font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                <span class="text-[10px] text-slate-500 font-semibold" x-text="new Intl.NumberFormat().format(item.price) + ' Ar'"></span>
                                
                                <div class="flex items-center justify-between mt-1.5">
                                    <div class="flex items-center gap-1">
                                        <button @click="decreaseQty(item)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white border border-slate-200 rounded hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-all">
                                            <i data-lucide="minus" class="w-3 h-3"></i>
                                        </button>
                                        <span class="text-xs font-bold text-slate-900 w-5 text-center" x-text="item.qty"></span>
                                        <button @click="increaseQty(item)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white border border-slate-200 rounded hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-all">
                                            <i data-lucide="plus" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900" x-text="new Intl.NumberFormat().format(item.price * item.qty) + ' Ar'"></span>
                                </div>
                            </div>
                            <button @click="removeFromCart(item.id)" 
                                    class="p-1 text-slate-500 hover:text-red-600 rounded hover:bg-red-50 transition-all flex-shrink-0">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Financial Summary -->
                <div x-show="cart.length > 0" class="border-t border-slate-200 pt-3 space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-500">
                        <span>Sous-total</span>
                        <span class="text-slate-900" x-text="new Intl.NumberFormat().format(subtotal) + ' Ar'"></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                        <span>Remise</span>
                        <div class="flex items-center gap-1.5">
                            <input type="number" 
                                   x-model="discount" 
                                   min="0" 
                                   class="w-16 text-right px-2 py-1 border border-slate-200 bg-slate-50 rounded text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span class="text-[10px] font-bold">Ar</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs font-semibold text-slate-500">
                        <span>TVA</span>
                        <span class="text-slate-900" x-text="new Intl.NumberFormat().format(tva) + ' Ar'"></span>
                    </div>

                    <div class="border-t border-dashed border-slate-200 my-2"></div>
                    
                    <div class="flex justify-between items-center bg-green-50 p-2.5 rounded-xl">
                        <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Total</span>
                        <span class="text-xl font-extrabold text-green-600 tracking-tight" x-text="new Intl.NumberFormat().format(total) + ' Ar'"></span>
                    </div>

                    <!-- Amount Received & Change -->
                    <div class="space-y-2 pt-2">
                        <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                            <span>Montant reçu</span>
                            <input type="number" 
                                   x-model="amountReceived" 
                                   min="0"
                                   class="w-24 text-right px-2 py-1 border border-slate-200 bg-slate-50 rounded text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-blue-600">
                        </div>
                        <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                            <span>Monnaie à rendre</span>
                            <span class="text-green-600 font-bold" x-text="new Intl.NumberFormat().format(change) + ' Ar'"></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div x-show="cart.length > 0" class="border-t border-slate-200 pt-3 mt-3 space-y-2.5">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mode de paiement</span>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" @change="updatePaymentMethod()" value="especes" class="hidden peer">
                            <div class="p-2.5 border-2 border-slate-200 rounded-xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-center">
                                <i data-lucide="banknote" class="w-4 h-4 mx-auto mb-1 text-slate-500 peer-checked:text-blue-600"></i>
                                <span class="text-[10px] font-bold text-slate-900 block">Espèces</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" @change="updatePaymentMethod()" value="mobile" class="hidden peer">
                            <div class="p-2.5 border-2 border-slate-200 rounded-xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-center">
                                <i data-lucide="smartphone" class="w-4 h-4 mx-auto mb-1 text-slate-500 peer-checked:text-blue-600"></i>
                                <span class="text-[10px] font-bold text-slate-900 block">Mobile Money</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" @change="updatePaymentMethod()" value="carte" class="hidden peer">
                            <div class="p-2.5 border-2 border-slate-200 rounded-xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-center">
                                <i data-lucide="credit-card" class="w-4 h-4 mx-auto mb-1 text-slate-500 peer-checked:text-blue-600"></i>
                                <span class="text-[10px] font-bold text-slate-900 block">Carte bancaire</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" @change="updatePaymentMethod()" value="bon" class="hidden peer">
                            <div class="p-2.5 border-2 border-slate-200 rounded-xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-center">
                                <i data-lucide="ticket" class="w-4 h-4 mx-auto mb-1 text-slate-500 peer-checked:text-blue-600"></i>
                                <span class="text-[10px] font-bold text-slate-900 block">Bon d'achat</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div x-show="cart.length > 0" class="border-t border-slate-200 pt-3 mt-3 space-y-2">
                    <button @click="checkout()" 
                            class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm shadow-green-600/20 hover:scale-[1.01] active:scale-95 transition-all">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Paiement</span>
                    </button>
                    
                    <button @click="clearCart()" 
                            class="w-full py-3 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl font-bold text-sm flex items-center justify-center gap-2 transition-all">
                        <i data-lucide="x-circle" class="w-5 h-5"></i>
                        <span>Annuler</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CHECKOUT SUCCESS MODAL                       -->
    <!-- ============================================ -->
    <div x-show="showCheckoutSuccess" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80" 
         style="display: none;">
        <div class="bg-white rounded-[18px] max-w-sm w-full p-6 text-center space-y-5 shadow-2xl">
            
            <!-- Success Icon -->
            <div class="mx-auto p-4 bg-green-100 text-green-600 rounded-full w-20 h-20 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-12 h-12 stroke-[2.5]"></i>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-900">Encaissement validé !</h3>
                <p class="text-xs text-slate-500">Le ticket de caisse a été imprimé avec succès.</p>
            </div>
            
            <!-- Receipt Summary -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-left space-y-2">
                <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                    <span>Ticket #TX-<span x-text="Math.floor(Math.random() * 9000) + 1000"></span></span>
                    <span x-text="new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})"></span>
                </div>
                
                <div class="pt-2 space-y-1.5 max-h-32 overflow-y-auto">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-slate-500" x-text="item.qty + 'x ' + item.name"></span>
                            <span class="text-slate-900 font-bold" x-text="new Intl.NumberFormat().format(item.price * item.qty) + ' Ar'"></span>
                        </div>
                    </template>
                </div>
                
                <div class="border-t border-slate-200 pt-2 mt-2 space-y-1">
                    <div class="flex justify-between text-[10px] font-semibold text-slate-500">
                        <span>Sous-total</span>
                        <span x-text="new Intl.NumberFormat().format(subtotal) + ' Ar'"></span>
                    </div>
                    <div x-show="discount > 0" class="flex justify-between text-[10px] font-semibold text-red-600">
                        <span>Remise</span>
                        <span x-text="'-' + new Intl.NumberFormat().format(discount) + ' Ar'"></span>
                    </div>
                    <div class="flex justify-between text-sm font-extrabold text-slate-900 pt-1">
                        <span>TOTAL</span>
                        <span class="text-green-600" x-text="new Intl.NumberFormat().format(total) + ' Ar'"></span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-2">
                <button @click="closeCheckout()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all">
                    Nouvelle vente
                </button>
                <button @click="printReceipt(); closeCheckout()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-900 rounded-xl font-bold text-xs transition-all">
                    Imprimer un duplicata
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Initialize Lucide icons once after page load
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    // Re-initialize icons only when Alpine.js finishes updating
    document.addEventListener('alpine:initialized', function() {
        lucide.createIcons();
    });
</script>
@endpush