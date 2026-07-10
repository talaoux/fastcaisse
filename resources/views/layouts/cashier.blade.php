<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fastcaisse - Espace Caissier</title>

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
                            DEFAULT: '#2563EB', // Blue
                            hover: '#1D4ED8',
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
<body class="h-full overflow-x-hidden" x-data="{ activeTab: 'sales', mobileSidebarOpen: false }">

    <!-- Topbar -->
    <header class="sticky top-0 z-30 flex h-[70px] items-center justify-between border-b border-slate-200 bg-white shadow-sm px-4 lg:px-6">
        
        <!-- Left: Logo & Cash Register Info -->
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <div class="flex items-center space-x-2.5">
                <div class="p-2 bg-primary/10 rounded-xl text-primary flex items-center justify-center">
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
                <div class="hidden sm:block">
                    <span class="text-lg tracking-tighter text-slate-900 font-extrabold">Fast<span class="text-primary font-semibold">caisse</span></span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-900">Caisse 01</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-success mr-1"></span>
                            En ligne
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center: Search Bar -->
        <div class="flex-1 max-w-2xl mx-4 hidden md:block">
            <div class="relative">
                <input type="text" 
                       placeholder="Rechercher un produit, code-barres ou référence..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-slate-500"></i>
            </div>
        </div>

        <!-- Right: Notifications, Profile, Time -->
        <div class="flex items-center space-x-3">
            <!-- Notifications -->
            <button class="relative p-2 text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-all">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-danger text-[9px] font-bold text-white ring-2 ring-white">3</span>
            </button>

            <!-- Profile -->
            <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
                <img class="w-9 h-9 rounded-full object-cover border-2 border-slate-200" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=256&auto=format&fit=crop" alt="Ravaka M.">
                <div class="hidden lg:block">
                    <span class="text-xs font-bold text-slate-900 block leading-none">Ravaka M.</span>
                    <span class="text-[10px] text-slate-500 block">Caissière</span>
                </div>
            </div>

            <!-- Time & Date -->
            <div class="hidden xl:flex items-center space-x-2 pl-3 border-l border-slate-200">
                <i data-lucide="clock" class="w-4 h-4 text-slate-500"></i>
                <div class="text-right">
                    <div class="text-xs font-bold text-slate-900" id="currentTime">14:32</div>
                    <div class="text-[10px] text-slate-500" id="currentDate">08/07/2026</div>
                </div>
            </div>

            <!-- Logout -->
            <button class="p-2 text-slate-500 hover:text-red-600 rounded-xl hover:bg-red-50 transition-all" title="Déconnexion">
                <i data-lucide="log-out" class="w-5 h-5"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 lg:p-6">
        @yield('content')
    </main>

    <!-- Initialize Lucide icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
            
            // Update time
            function updateTime() {
                const now = new Date();
                const timeOptions = { hour: '2-digit', minute: '2-digit' };
                const dateOptions = { day: '2-digit', month: '2-digit', year: 'numeric' };
                
                const timeElement = document.getElementById('currentTime');
                const dateElement = document.getElementById('currentDate');
                
                if (timeElement) {
                    timeElement.textContent = now.toLocaleTimeString('fr-FR', timeOptions);
                }
                if (dateElement) {
                    dateElement.textContent = now.toLocaleDateString('fr-FR', dateOptions);
                }
            }
            
            updateTime();
            setInterval(updateTime, 60000);
        });
        
        // Re-initialize icons when Alpine.js is ready
        document.addEventListener('alpine:initialized', function() {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>