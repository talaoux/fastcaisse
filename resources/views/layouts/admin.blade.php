<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fastcaisse - Dashboard Administrateur</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN v3 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#22C55E', // Green
                            hover: '#16A34A',
                        },
                        success: {
                            DEFAULT: '#22C55E', // Green
                            hover: '#16A34A',
                        },
                        warning: {
                            DEFAULT: '#F59E0B', // Orange/Amber
                            hover: '#D97706',
                        },
                        danger: {
                            DEFAULT: '#EF4444', // Red
                            hover: '#DC2626',
                        },
                        bgPrincipal: '#F8FAFC',
                        textPrincipal: '#0F172A',
                        textSecondaire: '#64748B',
                        borderCouleur: '#E2E8F0',
                    },
                    borderRadius: {
                        'custom': '18px',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 12px -1px rgba(0, 0, 0, 0.04), 0 4px 30px -4px rgba(0, 0, 0, 0.02)',
                        'soft-hover': '0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 16px -6px rgba(0, 0, 0, 0.03)',
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Alpine.js CDN (Deferred) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom base styles to ensure premium feel -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Custom scrollbar for premium look */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full overflow-x-hidden" x-data="{ activeTab: '{{ request()->routeIs('admin.dashboard') ? 'dashboard' : (request()->routeIs('products.*') ? 'products' : (request()->routeIs('admin.sales') || request()->routeIs('cashier.dashboard') ? 'sales' : (request()->routeIs('admin.stock') ? 'stock' : (request()->routeIs('admin.customers') ? 'customers' : 'dashboard')))) }}', mobileSidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" 
         @click="mobileSidebarOpen = false"
         style="display: none;"></div>

    <!-- Mobile Sidebar Drawer -->
    <div x-show="mobileSidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white px-6 py-6 flex flex-col justify-between border-r border-slate-200 lg:hidden"
         style="display: none;">
        
        <div>
            <!-- Close button -->
            <div class="flex items-center justify-between mb-8">
                <!-- Logo -->
                <div class="flex items-center space-x-2.5">
                    <div class="p-2 bg-green-100 rounded-xl text-green-600 flex items-center justify-center shadow-sm shadow-green-500/5">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Speed lines -->
                            <path d="M2.5 7.5H5.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            <path d="M1.5 11.5H4.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            <path d="M3 15.5H5.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            <!-- Shopping cart -->
                            <path d="M8 5.5H9.5L12 13.5H19L21.5 7.5H10.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Wheels -->
                            <circle cx="13" cy="18.5" r="1.5" fill="currentColor"/>
                            <circle cx="18" cy="18.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-xl tracking-tighter text-slate-900 font-extrabold select-none">Fast<span class="text-green-600 font-semibold">caisse</span></span>
                </div>
                <button @click="mobileSidebarOpen = false" class="p-2 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" @click="activeTab = 'dashboard'; mobileSidebarOpen = false" :class="activeTab === 'dashboard' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('products.index') }}" @click="activeTab = 'products'; mobileSidebarOpen = false" :class="activeTab === 'products' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Produits</span>
                </a>
                <a href="{{ route('admin.sales') }}" @click="activeTab = 'sales'; mobileSidebarOpen = false" :class="activeTab === 'sales' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Ventes</span>
                </a>
                <a href="{{ route('admin.stock') }}" @click="activeTab = 'stock'; mobileSidebarOpen = false" :class="activeTab === 'stock' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left">
                    <i data-lucide="archive" class="w-5 h-5"></i>
                    <span>Stock</span>
                </a>
            </nav>
        </div>

        <!-- Mobile Bottom Section -->
        <div class="mt-auto space-y-4">
            <!-- Caisse ouverte Card -->
            <div class="p-4 bg-green-50 rounded-[18px] border border-green-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-green-600 uppercase tracking-wider block">Caisse ouverte</span>
                    <span class="text-sm font-bold text-slate-900">Caisse 01</span>
                    <span class="text-xs text-slate-500 block">Ouverte à 08:00</span>
                </div>
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </div>
            </div>

            <!-- Profile Widget -->
            <div class="flex items-center justify-between p-2 bg-slate-50 rounded-[18px] border border-slate-200">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <img class="w-10 h-10 rounded-full object-cover border-2 border-white ring-2 ring-slate-100" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Aissata K.">
                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Aissata K.</h4>
                        <span class="text-[10px] text-slate-500">Administrateur</span>
                    </div>
                </div>
                <button class="p-1 text-slate-500 hover:text-slate-900 rounded-lg">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Fixed Sidebar -->
    <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0 z-30 bg-white border-r border-slate-200 px-6 py-8 justify-between">
        <div class="flex flex-col space-y-8 overflow-y-auto max-h-[calc(100vh-180px)] pr-1">
            <!-- Logo Fastcaisse -->
            <div class="flex items-center space-x-3 px-2">
                <div class="p-2.5 bg-green-100 rounded-xl text-green-600 flex items-center justify-center shadow-sm shadow-green-500/10 hover:scale-105 transition-all duration-200">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Speed lines -->
                        <path d="M2.5 7.5H5.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M1.5 11.5H4.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M3 15.5H5.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <!-- Shopping cart -->
                        <path d="M8 5.5H9.5L12 13.5H19L21.5 7.5H10.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- Wheels -->
                        <circle cx="13" cy="18.5" r="1.5" fill="currentColor"/>
                        <circle cx="18" cy="18.5" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <span class="text-2xl tracking-tighter text-slate-900 font-extrabold select-none">Fast<span class="text-green-600 font-semibold">caisse</span></span>
            </div>

            <!-- Menu Navigation Links -->
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('products.index') }}" @click="activeTab = 'products'" :class="activeTab === 'products' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Produits</span>
                </a>
                <a href="{{ route('admin.sales') }}" @click="activeTab = 'sales'" :class="activeTab === 'sales' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Ventes</span>
                </a>
                <a href="{{ route('admin.stock') }}" @click="activeTab = 'stock'" :class="activeTab === 'stock' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="archive" class="w-5 h-5"></i>
                    <span>Stock</span>
                </a>
                <a href="{{ route('admin.customers') }}" @click="activeTab = 'customers'" :class="activeTab === 'customers' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Clients</span>
                </a>
                <a href="#" @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/20 hover:bg-primary/95' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-[12px] font-medium transition-all duration-200 text-left hover:translate-x-1">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Paramètres</span>
                </a>
            </nav>
        </div>

        <!-- Desktop Sidebar Bottom Footer / Profile -->
        <div class="flex flex-col space-y-4 pt-4 border-t border-slate-200 mt-4">
            <!-- Caisse ouverte Card -->
            <div class="p-4 bg-green-50 rounded-[18px] border border-green-100 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider block">Caisse ouverte</span>
                    <span class="text-sm font-bold text-slate-900">Caisse 01</span>
                    <span class="text-xs text-slate-500 block">Ouverte à 08:00</span>
                </div>
                <div class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="relative" x-data="{ openProfile: false }" @click.away="openProfile = false">
                <div @click="openProfile = !openProfile" 
                     class="flex items-center justify-between p-2.5 hover:bg-slate-50 rounded-[18px] border border-slate-200 cursor-pointer transition-all duration-200 select-none">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img class="w-10 h-10 rounded-full object-cover border-2 border-white ring-2 ring-slate-100" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Aissata K.">
                            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Aissata K.</h4>
                            <span class="text-[10px] text-slate-500">Administrateur</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="openProfile ? 'rotate-180' : ''"></i>
                </div>

                <!-- Dropdown Menu -->
                <div x-show="openProfile"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white border border-slate-200 rounded-[18px] shadow-lg py-2 z-50 overflow-hidden"
                     style="display: none;">
                    <a href="#" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-900 hover:bg-slate-50">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                        <span>Mon Profil</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-900 hover:bg-slate-50">
                        <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                        <span>Paramètres</span>
                    </a>
                    <div class="border-t border-slate-200 my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 text-left">
                            <i data-lucide="log-out" class="w-4 h-4 text-red-600"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- Topbar -->
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/80 backdrop-blur-md px-6 lg:px-8 shadow-sm">
            
            <!-- Welcome Info / Hamburger Menu -->
            <div class="flex items-center space-x-4">
                <!-- Hamburger Button (Mobile Only) -->
                <button @click="mobileSidebarOpen = true" class="p-2.5 text-slate-500 hover:text-slate-900 rounded-[12px] border border-slate-200 lg:hidden hover:bg-slate-100 transition-all duration-200">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Titles -->
                <div class="hidden sm:block">
                    <h1 class="text-xl font-bold tracking-tight text-slate-900">@yield('title', 'Tableau de bord')</h1>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">@yield('subtitle', 'Bienvenue Admin, voici un aperçu de votre activité.')</p>
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center space-x-4">
                
                <!-- Period Selector Dropdown -->
                <div class="relative" x-data="{ openPeriod: false, selectedPeriod: 'Aujourd\'hui' }" @click.away="openPeriod = false">
                    <button @click="openPeriod = !openPeriod" 
                            class="flex items-center space-x-2.5 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-900 rounded-[12px] shadow-sm transition-all duration-200 select-none">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                        <span x-text="selectedPeriod">Aujourd'hui</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="openPeriod ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Dropdown Options -->
                    <div x-show="openPeriod"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-[12px] shadow-lg py-1.5 z-50 overflow-hidden"
                         style="display: none;">
                        <button @click="selectedPeriod = 'Aujourd\'hui'; openPeriod = false" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-900 hover:bg-slate-50 transition-all duration-150">Aujourd'hui</button>
                        <button @click="selectedPeriod = 'Hier'; openPeriod = false" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-900 hover:bg-slate-50 transition-all duration-150">Hier</button>
                        <button @click="selectedPeriod = '7 derniers jours'; openPeriod = false" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-900 hover:bg-slate-50 transition-all duration-150">7 derniers jours</button>
                        <button @click="selectedPeriod = 'Ce mois-ci'; openPeriod = false" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-900 hover:bg-slate-50 transition-all duration-150">Ce mois-ci</button>
                        <button @click="selectedPeriod = 'Année 2026'; openPeriod = false" class="w-full text-left px-4 py-2 text-xs font-medium text-slate-900 hover:bg-slate-50 transition-all duration-150">Année 2026</button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="relative" x-data="{ openNotifications: false }" @click.away="openNotifications = false">
                    <button @click="openNotifications = !openNotifications" 
                            class="relative p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-[12px] border border-slate-200 shadow-sm transition-all duration-200">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white ring-2 ring-white">3</span>
                    </button>

                    <!-- Notifications Dropdown Panel -->
                    <div x-show="openNotifications"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-[18px] shadow-lg py-2 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">Notifications</span>
                            <button class="text-[10px] font-semibold text-green-600 hover:underline">Marquer tout comme lu</button>
                        </div>
                        <div class="divide-y divide-slate-200 max-h-72 overflow-y-auto">
                            <a href="#" class="flex px-4 py-3 hover:bg-slate-50 transition-all duration-150">
                                <div class="p-2 bg-red-100 rounded-full text-red-600 mr-3 h-8 w-8 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">Alerte stock critique !</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Le produit "Lait entier 1L" est passé sous le seuil d'alerte (2 restants).</p>
                                    <span class="text-[9px] text-slate-500 font-medium mt-1 block">Il y a 10 min</span>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 hover:bg-slate-50 transition-all duration-150">
                                <div class="p-2 bg-green-100 rounded-full text-green-600 mr-3 h-8 w-8 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">Fermeture de caisse validée</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">La caisse de l'employée Ravaka a été clôturée avec succès, aucun écart.</p>
                                    <span class="text-[9px] text-slate-500 font-medium mt-1 block">Il y a 1 heure</span>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 hover:bg-slate-50 transition-all duration-150">
                                <div class="p-2 bg-amber-100 rounded-full text-amber-600 mr-3 h-8 w-8 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">Objectif quotidien atteint</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Le chiffre d'affaires du jour a dépassé l'objectif fixé à 2 000 000 Ar.</p>
                                    <span class="text-[9px] text-slate-500 font-medium mt-1 block">Il y a 3 heures</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Admin Profile Bubble -->
                <div class="flex items-center space-x-3 pl-2 border-l border-slate-200">
                    <img class="w-9 h-9 rounded-full object-cover border-2 border-slate-200" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Aissata K.">
                    <div class="hidden md:block">
                        <span class="text-xs font-bold text-slate-900 block leading-none">Aissata K.</span>
                        <span class="text-[9px] text-slate-500 block mt-0.5 font-medium">Administrateur</span>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Workspace Area -->
        <main class="flex-1 p-6 lg:p-8 space-y-8">
            <!-- Alert message or subheader on Mobile -->
            <div class="sm:hidden mb-2">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">@yield('title', 'Tableau de bord')</h1>
                <p class="text-xs font-medium text-slate-500 mt-1">@yield('subtitle', 'Bienvenue Admin, voici un aperçu de votre activité.')</p>
            </div>

            <!-- Page content loaded dynamically -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-6 px-6 lg:px-8 border-t border-slate-200 bg-white text-center flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs font-medium text-slate-500">© 2026 Fastcaisse - Tous droits réservés.</p>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-xs text-slate-500 hover:text-slate-900 transition-all duration-200 font-medium">Documentation</a>
                <a href="#" class="text-xs text-slate-500 hover:text-slate-900 transition-all duration-200 font-medium">Support</a>
                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-bold">v1.2.0</span>
            </div>
        </footer>
    </div>

    <!-- Initialize Lucide icons and yield page scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>