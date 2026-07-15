@extends('layouts.admin')

@section('title')
    <span x-text="activeTab === 'dashboard' ? 'Tableau de bord' : 
                 activeTab === 'products' ? 'Gestion des Produits' : 
                 activeTab === 'sales' ? 'Caisse & Vente POS' : 
                 activeTab === 'stock' ? 'Suivi du Stock' : 'Paramètres Système'">
    </span>
@endsection

@section('subtitle')
    <span x-text="activeTab === 'dashboard' ? 'Bienvenue Admin, voici un aperçu de votre activité aujourd\'hui.' : 
                 activeTab === 'products' ? 'Configurez vos produits, catégories, prix d\'achat et vente.' : 
                 activeTab === 'sales' ? 'Saisissez les ventes rapidement, gérez le panier et encaissez.' : 
                 activeTab === 'stock' ? 'Ajustez le stock, surveillez les alertes de rupture et inventaires.' : 'Ajustez les taux de taxe, l\'imprimante ticket, les devises et caissiers.'">
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
    
    // Format metrics for display
    $revenue = $metrics['revenue'] ?? 0;
    $profit = $metrics['profit'] ?? 0;
    $transactions = $metrics['transactions'] ?? 0;
    $averageCart = $metrics['average_cart'] ?? 0;
    
    // Prepare chart data
    $chartLabels = $salesChartData['labels'] ?? [];
    $chartData = $salesChartData['data'] ?? [];
    
    // Prepare category data
    $categoryData = $categoryBreakdown ?? [];
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
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($revenue, 0, ',', ' ') }} Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> Aujourd'hui
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">Chiffre d'affaires du jour</span>
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
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($profit, 0, ',', ' ') }} Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> Marge nette
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">Bénéfice du jour</span>
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
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $transactions }}</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> Aujourd'hui
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">Transactions du jour</span>
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
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($averageCart, 0, ',', ' ') }} Ar</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="inline-flex items-center text-[11px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> Aujourd'hui
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">Panier moyen du jour</span>
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
                <script>
                    // Sales chart data
                    const salesChartData = {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Chiffre d\'affaires (Ar)',
                            data: @json($chartData),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#16a34a',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6
                        }]
                    };
                    
                    // Initialize chart when DOM is ready
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('salesChart');
                        if (ctx) {
                            new Chart(ctx, {
                                type: 'line',
                                data: salesChartData,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            padding: 12,
                                            cornerRadius: 8,
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
                                                color: 'rgba(148, 163, 184, 0.1)',
                                                drawBorder: false
                                            },
                                            ticks: {
                                                callback: function(value) {
                                                    return new Intl.NumberFormat('fr-FR').format(value) + ' Ar';
                                                },
                                                font: {
                                                    size: 10
                                                },
                                                color: '#64748b'
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
                                                color: '#64748b'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    });
                </script>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="mb-6">
                    <h3 class="text-base font-bold text-slate-900">Top produits</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Les articles les plus vendus de la journée</p>
                </div>
                <div class="space-y-4 flex-1 overflow-y-auto max-h-80 pr-1">
                    @forelse($topProducts as $product)
                    <div class="flex items-center justify-between py-1.5 group">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <img class="w-10 h-10 rounded-xl object-cover bg-slate-100 border border-slate-100 flex-shrink-0 group-hover:scale-105 transition-all duration-200" src="{{ $product->product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $product->product_name }}">
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $product->product_name }}</h4>
                                <span class="text-[10px] text-slate-500 font-semibold">{{ $product->total_quantity }} vendus</span>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                                    <div class="bg-green-600 h-full rounded-full transition-all duration-500" style="width: {{ $product->total_quantity > 0 ? min(100, ($product->total_quantity / ($topProducts->first()->total_quantity ?? 1)) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right pl-3">
                            <span class="text-xs font-bold text-slate-900 block">{{ number_format($product->total_revenue, 0, ',', ' ') }} Ar</span>
                            <span class="text-[9px] text-green-600 font-bold bg-green-50 px-1.5 py-0.5 rounded mt-0.5 inline-block">
                                @if($revenue > 0)
                                    {{ round(($product->total_revenue / $revenue) * 100) }}% du CA
                                @else
                                    0% du CA
                                @endif
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="py-6 text-center text-slate-500">
                        <span class="text-xs font-semibold">Aucune vente aujourd'hui</span>
                    </div>
                    @endforelse
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
                        <span class="text-[10px] font-semibold text-slate-900">Alimentation <strong class="text-slate-500 ml-1">{{ $categoryData['alimentation']['percentage'] ?? 0 }}%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Boissons <strong class="text-slate-500 ml-1">{{ $categoryData['boissons']['percentage'] ?? 0 }}%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Hygiène <strong class="text-slate-500 ml-1">{{ $categoryData['hygiene']['percentage'] ?? 0 }}%</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 block"></span>
                        <span class="text-[10px] font-semibold text-slate-900">Divers <strong class="text-slate-500 ml-1">{{ $categoryData['divers']['percentage'] ?? 0 }}%</strong></span>
                    </div>
                </div>
                <script>
                    // Category chart data
                    const categoryChartData = {
                        labels: ['Alimentation', 'Boissons', 'Hygiène', 'Divers'],
                        datasets: [{
                            data: [
                                {{ $categoryData['alimentation']['percentage'] ?? 0 }},
                                {{ $categoryData['boissons']['percentage'] ?? 0 }},
                                {{ $categoryData['hygiene']['percentage'] ?? 0 }},
                                {{ $categoryData['divers']['percentage'] ?? 0 }}
                            ],
                            backgroundColor: [
                                '#16a34a',
                                '#2563eb',
                                '#f59e0b',
                                '#9333ea'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    };
                    
                    // Initialize chart when DOM is ready
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('categoryChart');
                        if (ctx) {
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: categoryChartData,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '70%',
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            padding: 12,
                                            cornerRadius: 8,
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
                    @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-slate-100 rounded-xl text-slate-700 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="receipt" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Ticket #{{ $transaction->sale_number }}</span>
                                <span class="text-[10px] text-slate-500 font-semibold block">{{ $transaction->sale_date->format('H:i') }} • {{ $transaction->items->count() }} art • {{ $transaction->user->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 block">{{ number_format($transaction->total, 0, ',', ' ') }} Ar</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-600">Payée</span>
                        </div>
                    </div>
                    @empty
                    <div class="py-6 text-center text-slate-500">
                        <span class="text-xs font-semibold">Aucune transaction aujourd'hui</span>
                    </div>
                    @endforelse
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
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/50 transition-all product-row" data-category="{{ $product->category }}" data-name="{{ strtolower($product->name) }}" data-reference="{{ strtolower($product->reference) }}">
                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-100 flex-shrink-0" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">{{ $product->name }}</span>
                                        <span class="text-[9px] font-semibold text-slate-500 block">{{ $product->reference }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $product->category }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-900">{{ number_format($product->purchase_price, 0, ',', ' ') }} Ar</td>
                                <td class="px-6 py-4 text-xs font-bold text-green-600">{{ number_format($product->selling_price, 0, ',', ' ') }} Ar</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-green-600">{{ number_format($product->selling_price - $product->purchase_price, 0, ',', ' ') }} Ar</span>
                                    <span class="text-[9px] text-slate-500 font-medium block">
                                        @if($product->selling_price > 0)
                                            {{ round((($product->selling_price - $product->purchase_price) / $product->selling_price) * 100) }}% marge
                                        @else
                                            0% marge
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @if($product->stock <= 0)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-600">Rupture: <strong>{{ $product->stock }}</strong></span>
                                        @elseif($product->stock <= $product->minimum_stock)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-600">Alerte: <strong>{{ $product->stock }}</strong></span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-600">En Stock: <strong>{{ $product->stock }}</strong></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-slate-500 hover:text-green-600 rounded-lg hover:bg-slate-100 transition-all" title="Modifier">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <form id="deleteForm{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="if(confirm('Voulez-vous vraiment supprimer ce produit ?')) document.getElementById('deleteForm{{ $product->id }}').submit();" class="p-1.5 text-slate-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all" title="Supprimer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="package" class="w-12 h-12 text-slate-300 mb-2"></i>
                                        <span class="text-xs font-semibold text-slate-500">Aucun produit trouvé</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer Pagination Mockup -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500" id="productCount">Affichage de {{ count($products) }} produit(s)</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed">Précédent</button>
                    <button class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-[10px] font-bold">1</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed">Suivant</button>
                </div>
            </div>
        </div>

        <!-- Script pour filtrer et rechercher les produits -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.querySelector('input[placeholder*="Rechercher"]');
                const categorySelect = document.querySelector('select[x-model="selectedCategory"]');
                const productRows = document.querySelectorAll('.product-row');
                const productCount = document.getElementById('productCount');

                function filterProducts() {
                    const searchTerm = (searchInput?.value || '').toLowerCase();
                    const selectedCategory = categorySelect?.value || 'all';
                    let visibleCount = 0;

                    productRows.forEach(row => {
                        const category = row.getAttribute('data-category');
                        const name = row.getAttribute('data-name');
                        const reference = row.getAttribute('data-reference');

                        const categoryMatch = selectedCategory === 'all' || category === selectedCategory;
                        const searchMatch = searchTerm === '' || name.includes(searchTerm) || reference.includes(searchTerm);

                        if (categoryMatch && searchMatch) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    productCount.textContent = visibleCount === 0 
                        ? 'Aucun produit trouvé' 
                        : `Affichage de ${visibleCount} produit(s)`;
                }

                if (searchInput) {
                    searchInput.addEventListener('input', filterProducts);
                }
                if (categorySelect) {
                    categorySelect.addEventListener('change', filterProducts);
                }
            });
        </script>

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
                    
                    <form @submit.prevent="submitStockAdjustment($event)" class="space-y-4">
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
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Notes (optionnel)</label>
                            <input x-ref="adjNotes" type="text" placeholder="Raison ou référence" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                            Enregistrer le mouvement
                        </button>
                    </form>

                    <script>
                        function submitStockAdjustmentHandler(ctx) {
                            return async function (e) {
                                let item = ctx.catalog.find(i => i.id == ctx.$refs.adjProd.value);
                                let qty = parseInt(ctx.$refs.adjQty.value);
                                let type = ctx.$refs.adjType.value === 'add' ? 'adjustment' : 'loss';
                                if (ctx.$refs.adjType.value === 'remove' && item.stock < qty) {
                                    alert('Quantité insuffisante en stock !');
                                    return;
                                }

                                try {
                                    const res = await fetch('{{ route('admin.stock.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            product_id: item.id,
                                            type: type,
                                            quantity: qty,
                                            notes: ctx.$refs.adjNotes ? ctx.$refs.adjNotes.value : null
                                        })
                                    });

                                    const data = await res.json();
                                    if (!res.ok || !data.success) {
                                        alert(data.message || 'Erreur lors de l\'enregistrement du mouvement');
                                        return;
                                    }

                                    // Mettre à jour le stock côté client
                                    item.stock = data.stock_after;
                                    alert(data.message || 'Mouvement de stock enregistré avec succès !');
                                } catch (err) {
                                    console.error(err);
                                    alert('Erreur réseau lors de l\'enregistrement.');
                                }
                            }
                        }
                        // Attach function to Alpine root after initialization
                        document.addEventListener('alpine:initialized', function () {
                            // find Alpine component and bind the method
                            try {
                                let root = document.querySelector('[x-data]');
                                if (root && root.__x) {
                                    let comp = root.__x.$data;
                                    comp.submitStockAdjustment = submitStockAdjustmentHandler(comp);
                                }
                            } catch (e) {
                                // noop
                            }
                        });
                    </script>
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
        // Sales Line Chart with dynamic data
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Chiffre d\'affaires (Ar)',
                        data: @json($chartData),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#16a34a',
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
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            cornerRadius: 8,
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
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value) + ' Ar';
                                },
                                font: {
                                    size: 10
                                },
                                color: '#64748b'
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
                                color: '#64748b'
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

        // Category Doughnut Chart with dynamic data
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Alimentation', 'Boissons', 'Hygiène', 'Divers'],
                    datasets: [{
                        data: [
                            {{ $categoryData['alimentation']['percentage'] ?? 0 }},
                            {{ $categoryData['boissons']['percentage'] ?? 0 }},
                            {{ $categoryData['hygiene']['percentage'] ?? 0 }},
                            {{ $categoryData['divers']['percentage'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#16a34a',
                            '#2563eb',
                            '#f59e0b',
                            '#9333ea'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            cornerRadius: 8,
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
