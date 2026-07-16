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
                            DEFAULT: '#22C55E', // Green (changed from blue)
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
<body class="h-full overflow-x-hidden">

    <!-- Main Content Layout -->
    <div class="flex flex-col min-h-screen">
        
        <!-- Topbar -->
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/80 backdrop-blur-md px-6 lg:px-8 shadow-sm">
            
            <!-- Logo & Title -->
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-green-100 rounded-xl text-green-600 flex items-center justify-center shadow-sm shadow-green-500/10">
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

                <!-- Titles -->
                <div class="hidden sm:block pl-4 border-l border-slate-200">
                    <h1 class="text-xl font-bold tracking-tight text-slate-900">@yield('title', 'Nouvelle Vente')</h1>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">@yield('subtitle', 'Espace Caissier')</p>
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center space-x-4">
                
                <!-- Caisse ouverte -->
                @if(Auth::user() && Auth::user()->role === 'cashier')
                <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
                    <div class="p-2 bg-green-100 rounded-xl text-green-600">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </div>
                    <div class="text-left">
                        <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider block">Caisse ouverte</span>
                        <span class="text-sm font-bold text-slate-900">Caisse 01</span>
                        <span class="text-xs text-slate-500 block">Ouverte à <span id="cashierOpenTime">{{ session('cashier_login_time') ? session('cashier_login_time')->format('H:i') : '--:--' }}</span></span>
                    </div>
                </div>
                @endif

                <!-- Time & Date -->
                <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
                    <i data-lucide="clock" class="w-4 h-4 text-slate-500"></i>
                    <div class="text-right">
                        <div class="text-xs font-bold text-slate-900" id="currentTime">14:32</div>
                        <div class="text-[10px] text-slate-500" id="currentDate">08/07/2026</div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="relative" x-data="{ openProfile: false }" @click.away="openProfile = false">
                    <button @click="openProfile = !openProfile" class="flex items-center space-x-2 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                <div class="relative">
                    @if(Auth::user() && Auth::user()->avatar)
                        <img class="w-9 h-9 rounded-full object-cover border-2 border-white ring-2 ring-slate-100" src="{{ asset('storage/' . Auth::user()->avatar . '?t=' . Auth::user()->updated_at->timestamp) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img class="w-9 h-9 rounded-full object-cover border-2 border-white ring-2 ring-slate-100" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=256&auto=format&fit=crop" alt="{{ Auth::user()->name ?? 'Utilisateur' }}">
                    @endif
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                </div>
                <div class="hidden lg:block text-left">
                    <h4 class="text-xs font-bold text-slate-900">{{ Auth::user()->name ?? 'Utilisateur' }}</h4>
                    <span class="text-[10px] text-slate-500">{{ Auth::user()->role === 'admin' ? 'Administrateur' : 'Caissier' }}</span>
                </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200 hidden lg:block" :class="openProfile ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openProfile"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-[18px] shadow-lg py-2 z-50"
                         style="display: none;">
                        <button @click="document.getElementById('avatarInputCashier').click()" class="w-full flex items-center space-x-2 px-4 py-2 text-xs text-slate-900 hover:bg-slate-50 text-left">
                            <i data-lucide="camera" class="w-4 h-4 text-slate-500"></i>
                            <span>Changer la photo</span>
                        </button>
                        <form id="avatarFormCashier" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            <input type="file" name="avatar" accept="image/png, image/jpeg, image/webp" class="hidden" id="avatarInputCashier" onchange="uploadAvatarCashier(this)">
                        </form>
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
        </header>

        <!-- Main Workspace Area -->
        <main class="flex-1 p-6 lg:p-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 mb-6">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2 mb-6">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

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
            
            // Update time
            function updateTime() {
                const now = new Date();
                const timeOptions = { hour: '2-digit', minute: '2-digit' };
                const dateOptions = { day: '2-digit', month: '2-digit', year: 'numeric' };
                
                const timeElement = document.getElementById('currentTime');
                const dateElement = document.getElementById('currentDate');
                const cashierOpenTimeElement = document.getElementById('cashierOpenTime');
                
                if (timeElement) {
                    timeElement.textContent = now.toLocaleTimeString('fr-FR', timeOptions);
                }
                if (dateElement) {
                    dateElement.textContent = now.toLocaleDateString('fr-FR', dateOptions);
                }
                if (cashierOpenTimeElement) {
                    cashierOpenTimeElement.textContent = now.toLocaleTimeString('fr-FR', timeOptions);
                }
            }
            
            updateTime();
            setInterval(updateTime, 60000);
        });
        
        // Re-initialize icons when Alpine.js is ready
        document.addEventListener('alpine:initialized', function() {
            lucide.createIcons();
        });
        
        // Avatar upload handler with AJAX for cashier
        function uploadAvatarCashier(input) {
            if (input.files && input.files[0]) {
                const form = document.getElementById('avatarFormCashier');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Reload page to show new avatar
                        setTimeout(() => location.reload(), 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'upload de l\'image');
                });
            }
        }
    </script>
    @stack('scripts')
</body>
</html>