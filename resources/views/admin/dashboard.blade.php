@extends('layouts.admin')

@section('title')
    <span x-text="activeTab === 'dashboard' ? 'Tableau de bord' : 
                 activeTab === 'products' ? 'Gestion des Produits' : 
                 activeTab === 'sales' ? 'Caisse & Vente POS' : 
                 activeTab === 'stock' ? 'Suivi du Stock' : 
                 activeTab === 'customers' ? 'Fichier Clients' : 'Paramètres Système'">
    </span>
@endsection

@section('subtitle')
    <span x-text="activeTab === 'dashboard' ? 'Bienvenue Admin, voici un aperçu de votre activité aujourd\'hui.' : 
                 activeTab === 'products' ? 'Configurez vos produits, catégories, prix d\'achat et vente.' : 
                 activeTab === 'sales' ? 'Saisissez les ventes rapidement, gérez le panier et encaissez.' : 
                 activeTab === 'stock' ? 'Ajustez le stock, surveillez les alertes de rupture et inventaires.' : 
                 activeTab === 'customers' ? 'Consultez l\'historique d\'achats, la fidélité et les crédits.' : 'Ajustez les taux de taxe, l\'imprimante ticket, les devises et caissiers.'">
    </span>
@endsection

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
    function dashboardData() {
        return {
            // Product Catalogue Data
    catalog: [
        { id: 1, name: 'Pain de mie tranché', price: 2000, buyPrice: 1500, category: 'alimentation', stock: 15, minStock: 20, image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=100&auto=format&fit=crop' },
        { id: 2, name: 'Lait entier 1L', price: 1200, buyPrice: 900, category: 'boissons', stock: 120, minStock: 30, image: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?q=80&w=100&auto=format&fit=crop' },
        { id: 3, name: 'Sucre blanc 1kg', price: 850, buyPrice: 650, category: 'alimentation', stock: 0, minStock: 15, image: 'https://images.unsplash.com/photo-1581441363689-1f3c3c414635?q=80&w=100&auto=format&fit=crop' },
        { id: 4, name: 'Eau minérale 1.5L', price: 500, buyPrice: 350, category: 'boissons', stock: 64, minStock: 50, image: 'https://images.unsplash.com/photo-1608885898957-a599fb15e841?q=80&w=100&auto=format&fit=crop' },
        { id: 5, name: 'Savon liquide 500ml', price: 3500, buyPrice: 2800, category: 'hygiene', stock: 45, minStock: 10, image: 'https://images.unsplash.com/photo-1601049676099-e7ed07d825b0?q=80&w=100&auto=format&fit=crop' },
        { id: 6, name: 'Biscuits au chocolat', price: 1500, buyPrice: 1100, category: 'alimentation', stock: 35, minStock: 12, image: 'https://images.unsplash.com/photo-1558961309-dbdf71799f54?q=80&w=100&auto=format&fit=crop' }
    ],
    products: @json($productsForJs, JSON_UNESCAPED_SLASHES),

    // POS Cart State
    cart: [
        { id: 1, name: 'Pain de mie tranché', price: 2000, qty: 2, image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=100&auto=format&fit=crop' },
        { id: 2, name: 'Lait entier 1L', price: 1200, qty: 1, image: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?q=80&w=100&auto=format&fit=crop' },
        { id: 3, name: 'Sucre blanc 1kg', price: 850, qty: 1, image: 'https://images.unsplash.com/photo-1581441363689-1f3c3c414635?q=80&w=100&auto=format&fit=crop' },
        { id: 4, name: 'Eau minérale 1.5L', price: 500, qty: 1, image: 'https://images.unsplash.com/photo-1608885898957-a599fb15e841?q=80&w=100&auto=format&fit=crop' }
    ],

    // Search and Filters
    selectedCategory: 'all',
    searchQuery: '',
    discount: 0,
    showCheckoutSuccess: false,
    showAddProductModal: false,
    
    // POS Cart Functions
    addToCart(item) {
        if (item.stock <= 0) {
            alert('Ce produit est en rupture de stock !');
            return;
        }
        let existing = this.cart.find(i => i.id === item.id);
        if (existing) {
            existing.qty++;
        } else {
            this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1, image: item.image });
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
    checkout() {
        if (this.cart.length > 0) {
            this.showCheckoutSuccess = true;
        }
    },
    closeCheckout() {
        this.showCheckoutSuccess = false;
        this.clearCart();
    }
        };
    }
</script>
<div x-data="dashboardData()" class="space-y-8">

    <!-- ============================================ -->
    <!-- TAB 1: MAIN DASHBOARD                        -->
    <!-- ============================================ -->
    <div x-show="activeTab === 'dashboard'" class="space-y-8" x-transition:enter="transition-all ease-out duration-300">
        
        <!-- Row 1: 4 Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Chiffre d'affaires -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Chiffre d'affaires</span>
                    <div class="p-3 bg-green-100 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">2 450 000 Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> +12%
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">par rapport à hier</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Bénéfice -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bénéfice</span>
                    <div class="p-3 bg-green-100 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">820 000 Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> +8%
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">par rapport à hier</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Transactions -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Transactions</span>
                    <div class="p-3 bg-amber-100 rounded-xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">156</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> +8.3%
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">par rapport à hier</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Panier moyen -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Panier moyen</span>
                    <div class="p-3 bg-purple-100 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <i data-lucide="calculator" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">15 700 Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-down-right" class="w-3.5 h-3.5 mr-0.5"></i> -2.1%
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">par rapport à hier</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Sales Chart & Top Products -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Line Chart Card -->
            <div class="lg:col-span-2 bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Évolution des ventes</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Suivi des encaissements en temps réel</p>
                    </div>
                    <div class="flex bg-slate-100 p-1 rounded-lg text-[10px] font-bold text-slate-500">
                        <button class="px-2.5 py-1.5 bg-white text-slate-900 rounded-md shadow-sm">Aujourd'hui</button>
                        <button class="px-2.5 py-1.5 hover:text-slate-900 transition-all">Hier</button>
                        <button class="px-2.5 py-1.5 hover:text-slate-900 transition-all">Semaine</button>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-6">
                    <h3 class="text-base font-bold text-slate-900">Top produits</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Les articles les plus vendus de la journée</p>
                </div>
                <div class="space-y-4 flex-1 overflow-y-auto max-h-80 pr-1">
                    <template x-for="item in catalog.slice(0, 4)">
                        <div class="flex items-center justify-between py-1.5 group">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <img class="w-10 h-10 rounded-xl object-cover bg-slate-100 border border-slate-100 flex-shrink-0 group-hover:scale-105 transition-all duration-200" :src="item.image" :alt="item.name">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                    <span class="text-[10px] text-slate-500 font-semibold" x-text="(item.id * 15 + 20) + ' vendus'"></span>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                                        <div class="bg-green-600 h-full rounded-full transition-all duration-500" :style="'width: ' + (100 - item.id * 10) + '%'"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pl-3">
                                <span class="text-xs font-bold text-slate-900 block" x-text="new Intl.NumberFormat().format((item.id * 15 + 20) * item.price) + ' Ar'"></span>
                                <span class="text-[9px] text-green-600 font-bold bg-green-50 px-1.5 py-0.5 rounded mt-0.5 inline-block" x-text="(11 - item.id * 2) + '% du CA'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Row 3: Category Breakdown & Cash Register State & Transactions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Breakdown -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Répartition des ventes</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Parts de ventes par catégorie de produits</p>
                </div>
                <div class="relative flex flex-col items-center justify-center py-4">
                    <div class="relative h-44 w-44">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-600 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Alimentation <strong class="text-slate-500 ml-1">45%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Boissons <strong class="text-slate-500 ml-1">25%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Hygiène <strong class="text-slate-500 ml-1">15%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Divers <strong class="text-slate-500 ml-1">15%</strong></span>
                    </div>
                </div>
            </div>

            <!-- Cash register status -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-6 flex items-start justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">État de caisse</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Suivi des flux financiers de la caisse active</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600 uppercase tracking-wider">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Ouverte
                    </span>
                </div>
                <div class="space-y-4 flex-1 flex flex-col justify-center">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="monitor" class="w-4 h-4 text-green-600"></i>
                            <span class="text-xs font-bold text-slate-900">Caisse principale (Caisse 01)</span>
                        </div>
                        <span class="text-[10px] text-slate-500 font-bold">Admin: Aissata K.</span>
                    </div>
                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500">Solde ouverture</span>
                            <span class="text-slate-900 font-semibold">500 000 Ar</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500 flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Entrées (Ventes)
                            </span>
                            <span class="text-green-600 font-bold">+2 150 000 Ar</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500 flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Sorties (Dépenses)
                            </span>
                            <span class="text-red-600 font-bold">-200 000 Ar</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between shadow-sm">
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Solde actuel</span>
                        <span class="text-lg font-extrabold text-slate-900 block tracking-tight mt-0.5">2 450 000 Ar</span>
                    </div>
                    <div class="p-2.5 bg-green-100 rounded-xl text-green-600">
                        <i data-lucide="badge-dollar-sign" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>

            <!-- Recent transactions -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Transactions récentes</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Les derniers tickets de caisse validés</p>
                    </div>
                    <button @click="activeTab = 'sales'" class="text-xs font-semibold text-green-600 hover:underline">Voir tout</button>
                </div>
                <div class="divide-y divide-slate-200 flex-1 overflow-y-auto max-h-72 pr-1">
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-slate-100 rounded-xl text-slate-700 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="receipt" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Ticket #TX-9482</span>
                                <span class="text-[10px] text-slate-500 font-semibold block">14:32 • 4 art • Ravaka M.</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 block">45 500 Ar</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Payée</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-slate-100 rounded-xl text-slate-700 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="receipt" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Ticket #TX-9481</span>
                                <span class="text-[10px] text-slate-500 font-semibold block">14:15 • 1 art • Ravaka M.</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 block">12 000 Ar</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Payée</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-slate-100 rounded-xl text-slate-700 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="receipt" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Ticket #TX-9480</span>
                                <span class="text-[10px] text-slate-500 font-semibold block">13:58 • 2 art • Aissata K.</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 block">8 500 Ar</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Payée</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shortcuts Quick Actions -->
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Raccourcis d'actions rapides</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                <button @click="activeTab = 'sales'" class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2.5 p-4 bg-green-50 border border-green-200 hover:bg-green-600 hover:text-white rounded-xl text-green-600 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-sm group">
                    <i data-lucide="zap" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span class="text-xs font-bold">Vente rapide</span>
                </button>
                <button @click="activeTab = 'products'; showAddProductModal = true" class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2.5 p-4 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-xl text-blue-600 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-sm group">
                    <i data-lucide="plus-circle" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span class="text-xs font-bold">Nouveau produit</span>
                </button>
                <button @click="activeTab = 'sales'" class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2.5 p-4 bg-amber-50 border border-amber-200 hover:bg-amber-600 hover:text-white rounded-xl text-amber-600 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-sm group">
                    <i data-lucide="pause-circle" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span class="text-xs font-bold">Vente en attente</span>
                </button>
                <button @click="activeTab = 'sales'" class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2.5 p-4 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-xl text-blue-600 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-sm group">
                    <i data-lucide="history" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span class="text-xs font-bold">Historique</span>
                </button>
                <button @click="alert('Tiroir caisse ouvert avec succès ! (Impression impulsion ticket)')" class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2.5 p-4 col-span-2 sm:col-span-1 bg-green-50 border border-green-200 hover:bg-green-600 hover:text-white rounded-xl text-green-600 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-sm group">
                    <i data-lucide="unlock" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span class="text-xs font-bold">Ouvrir tiroir</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TAB 2: PRODUCTS GESTION                      -->
    <!-- ============================================ -->
    <div x-show="activeTab === 'products'" class="space-y-6" x-transition:enter="transition-all ease-out duration-300" style="display: none;">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[18px] border border-slate-200 shadow-sm">
            <div class="flex-1 w-full sm:max-w-md relative">
                <input type="text" x-model="searchQuery" placeholder="Rechercher une référence, désignation..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all">
                <i data-lucide="search" class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500"></i>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select x-model="selectedCategory" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-600">
                    <option value="all">Toutes les catégories</option>
                    <option value="alimentation">Alimentation</option>
                    <option value="boissons">Boissons</option>
                    <option value="hygiene">Hygiène</option>
                    <option value="divers">Divers</option>
                </select>
                <button @click="window.location.href='{{ route('products.create') }}'" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-600/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Ajouter un produit</span>
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-[18px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Produit</th>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4">Prix Achat</th>
                            <th class="px-6 py-4">Prix Vente</th>
                            <th class="px-6 py-4">Marge brute</th>
                            <th class="px-6 py-4">Stock Actuel</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="item in products" :key="item.id">
                            <tr x-show="(selectedCategory === 'all' || item.category === selectedCategory) && (searchQuery === '' || item.name.toLowerCase().includes(searchQuery.toLowerCase()) || item.reference.toLowerCase().includes(searchQuery.toLowerCase()))" class="hover:bg-slate-50/50 transition-all">
                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-100 flex-shrink-0" :src="item.image" :alt="item.name">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block" x-text="item.name"></span>
                                        <span class="text-[9px] font-semibold text-slate-500 block" x-text="item.reference"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider" x-text="item.category"></td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-900" x-text="new Intl.NumberFormat().format(item.buyPrice) + ' Ar'"></td>
                                <td class="px-6 py-4 text-xs font-bold text-green-600" x-text="new Intl.NumberFormat().format(item.price) + ' Ar'"></td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-green-600" x-text="new Intl.NumberFormat().format(item.price - item.buyPrice) + ' Ar'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block" x-text="item.price > 0 ? Math.round(((item.price - item.buyPrice) / item.price) * 100) + '% marge' : '0% marge'"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span :class="item.stock <= 0 ? 'bg-red-100 text-red-600' : item.stock <= item.minStock ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600'" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            <span x-text="item.stock <= 0 ? 'Rupture' : item.stock <= item.minStock ? 'Alerte' : 'En Stock'"></span>:
                                            <strong x-text="item.stock"></strong>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button @click="window.location.href = '/products/' + item.id + '/edit'" class="p-1.5 text-slate-500 hover:text-green-600 rounded-lg hover:bg-slate-100 transition-all" title="Modifier">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="if(confirm('Voulez-vous vraiment supprimer ce produit ?')) document.getElementById('deleteForm' + item.id).submit()" class="p-1.5 text-slate-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all" title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                    @foreach($products as $product)
                        <form id="deleteForm{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>
            </div>
            
            <!-- Table Footer Pagination Mockup -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500">Affichage de 1 à 6 sur 6 produits</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed">Précédent</button>
                    <button class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-[10px] font-bold">1</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed">Suivant</button>
                </div>
            </div>
        </div>

        <!-- Add Product Slider / Modal Modal -->
        <div x-show="showAddProductModal" class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
            <div class="bg-white rounded-[18px] max-w-lg w-full p-6 space-y-6 shadow-xl border border-slate-200 animate-fade-in" @click.away="showAddProductModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                    <h3 class="text-base font-bold text-slate-900">Nouveau Produit</h3>
                    <button @click="showAddProductModal = false" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <form @submit.prevent="
                    catalog.push({
                        id: catalog.length + 1,
                        name: $refs.prodName.value,
                        price: parseFloat($refs.prodPrice.value),
                        buyPrice: parseFloat($refs.prodBuy.value),
                        category: $refs.prodCat.value,
                        stock: parseInt($refs.prodStock.value),
                        minStock: 10,
                        image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'
                    });
                    showAddProductModal = false;
                    alert('Produit enregistré avec succès !');
                " class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Désignation du produit</label>
                        <input x-ref="prodName" type="text" required placeholder="Ex: Eau Vive 1.5L, Riz rouge 5kg..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Catégorie</label>
                            <select x-ref="prodCat" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <option value="alimentation">Alimentation</option>
                                <option value="boissons">Boissons</option>
                                <option value="hygiene">Hygiène</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Quantité Initiale</label>
                            <input x-ref="prodStock" type="number" required min="0" value="10" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Prix d'Achat (Ar)</label>
                            <input x-ref="prodBuy" type="number" required placeholder="Ex: 1000" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Prix de Vente (Ar)</label>
                            <input x-ref="prodPrice" type="number" required placeholder="Ex: 1500" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="showAddProductModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-900 rounded-xl">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm">Créer le produit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TAB 3: POS SALES CHECKOUT (DYNAMIC COMPONENT)-->
    <!-- ============================================ -->
    <div x-show="activeTab === 'sales'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-transition:enter="transition-all ease-out duration-300" style="display: none;">
        
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

    <!-- ============================================ -->
    <!-- TAB 4: STOCK / INVENTORY MONITORING          -->
    <!-- ============================================ -->
    <div x-show="activeTab === 'stock'" class="space-y-8" x-transition:enter="transition-all ease-out duration-300" style="display: none;">
        
        <!-- Stock KPI Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-xl text-green-600">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Valeur du Stock</span>
                    <span class="text-xl font-bold text-slate-900 block mt-0.5">380 400 Ar</span>
                    <span class="text-[9px] text-slate-500">Calculé sur prix d'achat</span>
                </div>
            </div>
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-amber-100 rounded-xl text-amber-600">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Alertes Critiques</span>
                    <span class="text-xl font-bold text-amber-600 block mt-0.5">1 Article</span>
                    <span class="text-[9px] text-slate-500">Niveau sous le seuil minimal</span>
                </div>
            </div>
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-red-100 rounded-xl text-red-600">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider block">Ruptures de stock</span>
                    <span class="text-xl font-bold text-red-600 block mt-0.5">1 Article</span>
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
                    
                    <form @submit.prevent="
                        let item = catalog.find(i => i.id == $refs.adjProd.value);
                        let qty = parseInt($refs.adjQty.value);
                        if ($refs.adjType.value === 'remove' && item.stock < qty) {
                            alert('Quantité insuffisante en stock !');
                            return;
                        }
                        if ($refs.adjType.value === 'add') {
                            item.stock += qty;
                        } else {
                            item.stock -= qty;
                        }
                        alert('Mouvement de stock enregistré avec succès !');
                    " class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Produit</label>
                            <select x-ref="adjProd" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <template x-for="item in catalog">
                                    <option :value="item.id" x-text="item.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Mouvement</label>
                                <select x-ref="adjType" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                    <option value="add">Entrée (+)</option>
                                    <option value="remove">Sortie (-)</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Quantité</label>
                                <input x-ref="adjQty" type="number" required min="1" value="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
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
                        <div class="flex justify-between items-center py-3 text-xs font-medium">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 rounded-xl text-green-600 flex items-center justify-center">
                                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-slate-900 font-bold block">Achat Fournisseur "SOCIETE JUMBO"</span>
                                    <span class="text-slate-500 text-[10px]">Lait entier 1L • +50 cartons</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-green-600 font-bold block">+600 unités</span>
                                <span class="text-slate-500 text-[10px]">Hier, 11:32</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-3 text-xs font-medium">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-red-100 rounded-xl text-red-600 flex items-center justify-center">
                                    <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-slate-900 font-bold block">Dépréciation produit endommagé</span>
                                    <span class="text-slate-500 text-[10px]">Pain de mie tranché • Perte humidité</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-red-600 font-bold block">-2 unités</span>
                                <span class="text-slate-500 text-[10px]">05/07/2026</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TAB 5: CUSTOMERS DIRECTORY                   -->
    <!-- ============================================ -->
    <div x-show="activeTab === 'customers'" class="space-y-6" x-transition:enter="transition-all ease-out duration-300" style="display: none;">
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-4">Liste des clients</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Nom Client</th>
                            <th class="px-6 py-4">Téléphone</th>
                            <th class="px-6 py-4">Historique d'Achat</th>
                            <th class="px-6 py-4">Points Fidélité</th>
                            <th class="px-6 py-4">Solde Crédit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium">
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-slate-900 font-bold">Rado Rabe</td>
                            <td class="px-6 py-4 text-slate-500">+261 34 11 222 33</td>
                            <td class="px-6 py-4 text-slate-900">14 Achats (320 000 Ar total)</td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">320 pts</span>
                            </td>
                            <td class="px-6 py-4 text-slate-900">0 Ar</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-slate-900 font-bold">Sitraka Andria</td>
                            <td class="px-6 py-4 text-slate-500">+261 32 44 555 66</td>
                            <td class="px-6 py-4 text-slate-900">5 Achats (85 000 Ar total)</td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">85 pts</span>
                            </td>
                            <td class="px-6 py-4 text-amber-600 font-bold bg-amber-50">15 000 Ar <span class="text-[9px] text-slate-500 font-medium block">Crédit dû</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TAB 6: SETTINGS AND SYSTEM CONFIG            -->
    <!-- ============================================ -->
    <div x-show="activeTab === 'settings'" class="grid grid-cols-1 md:grid-cols-3 gap-8" x-transition:enter="transition-all ease-out duration-300" style="display: none;">
        
        <!-- General Store config form -->
        <div class="md:col-span-2 bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Configuration de l'Établissement</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Paramètres d'affichage, entête et ticket de caisse</p>
            </div>
            
            <form @submit.prevent="alert('Configuration enregistrée avec succès !')" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Nom de la boutique</label>
                        <input type="text" value="Fastcaisse Madagascar" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Devise du système</label>
                        <select class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-600 focus:outline-none">
                            <option value="MGA">Ariary (Ar) - Madagascar</option>
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">Dollars ($)</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Numéro fiscal (NIF/STAT)</label>
                        <input type="text" value="4000123456" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Taux de Taxe TVA (%)</label>
                        <input type="number" value="20" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                </div>
                
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Message de pied de ticket</label>
                    <textarea rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none">Misaotra betsaka tamin'ny tsidika! A bientot.</textarea>
                </div>
                
                <button type="submit" class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                    Enregistrer les paramètres
                </button>
            </form>
        </div>

        <!-- System caissier list card -->
        <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Utilisateurs & Caissiers</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Gérer les comptes d'accès aux caisses</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center space-x-3">
                            <img class="w-8 h-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Aissata">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Aissata K.</span>
                                <span class="text-[9px] text-slate-500 font-bold block uppercase">Administrateur</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Actif</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center space-x-3">
                            <img class="w-8 h-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=256&auto=format&fit=crop" alt="Rina">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Ravaka M.</span>
                                <span class="text-[9px] text-slate-500 font-bold block uppercase">Caissière 01</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Actif</span>
                    </div>
                </div>
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

    // Initialize Charts after DOM and Alpine are ready
    document.addEventListener('alpine:initialized', function() {
        // Sales Line Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
                    datasets: [{
                        label: 'Ventes (Ar)',
                        data: [120000, 190000, 150000, 250000, 220000, 300000, 280000, 350000, 245000],
                        borderColor: '#22C55E',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#22C55E',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: {
                                size: 11,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 10
                            },
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' Ar';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(203, 213, 225, 0.3)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return (value / 1000) + 'k';
                                },
                                font: {
                                    size: 10
                                },
                                color: '#64748B'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                color: '#64748B'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Category Doughnut Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Alimentation', 'Boissons', 'Hygiène', 'Divers'],
                    datasets: [{
                        data: [45, 25, 15, 15],
                        backgroundColor: [
                            '#22C55E',
                            '#2563EB',
                            '#F59E0B',
                            '#9333EA'
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: {
                                size: 11,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 10
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
