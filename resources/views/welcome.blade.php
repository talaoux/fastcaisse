<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastCaisse — Gérez votre caisse en toute simplicité</title>
    <meta name="description" content="FastCaisse est la solution tout-en-un pour gérer vos ventes, stocks, clients et rapports en temps réel.">

    {{-- @vite est DESACTIVE par defaut car il exige d'avoir lance "npm run build"
         (ou "npm run dev") au moins une fois, sinon Laravel renvoie l'erreur
         "Vite manifest not found". Si ton projet a deja Breeze + Tailwind installes :
         1) decommente la ligne @vite ci-dessous
         2) supprime le tag script CDN Tailwind juste apres (celui qui charge cdn.tailwindcss.com)
         3) lance : npm install && npm run build (ou npm run dev en developpement)

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}

    {{-- Tailwind via CDN : fonctionne sans aucune installation npm --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html { scroll-behavior: smooth; }
        [id] { scroll-margin-top: 96px; } /* pour ne pas cacher le titre sous le header sticky */
    </style>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="antialiased text-slate-800">

    {{-- ==================== HEADER / NAV ==================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="#accueil" class="flex items-center gap-2 font-bold text-xl text-slate-900">
                <span class="text-brand-600">
                    <i data-lucide="shopping-cart" class="w-7 h-7"></i>
                </span>
                FastCaisse
            </a>

            {{-- Navigation par ancre : tout reste sur la même page --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
                <a href="#accueil" class="nav-link text-brand-700 border-b-2 border-brand-600 pb-1">Accueil</a>
                <a href="#fonctionnalites" class="nav-link hover:text-brand-700 transition">Fonctionnalités</a>
                <a href="#tarifs" class="nav-link hover:text-brand-700 transition">Tarifs</a>
                <a href="#apropos" class="nav-link hover:text-brand-700 transition">À propos</a>
                <a href="#contact" class="nav-link hover:text-brand-700 transition">Contact</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('register') }}" class="bg-brand-600 hover:bg-brand-700 transition text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                    Inscription Gratuite
                </a>
                <a href="{{ route('login') }}" class="border border-slate-200 hover:border-slate-300 transition text-sm font-semibold px-5 py-2.5 rounded-lg">
                    Se connecter
                </a>
            </div>

            {{-- Bouton menu mobile --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-700">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        {{-- Menu mobile déroulant, toujours des ancres vers les sections --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white px-6 py-4 space-y-4 text-sm font-medium">
            <a href="#accueil" class="block text-brand-700">Accueil</a>
            <a href="#fonctionnalites" class="block text-slate-700">Fonctionnalités</a>
            <a href="#tarifs" class="block text-slate-700">Tarifs</a>
            <a href="#apropos" class="block text-slate-700">À propos</a>
            <a href="#contact" class="block text-slate-700">Contact</a>
            <a href="#contact" class="block bg-brand-600 text-white text-center font-semibold px-5 py-2.5 rounded-lg">Demander une démo</a>
        </div>
    </header>

    <main>
        {{-- ==================== HERO / ACCUEIL ==================== --}}
        <section id="accueil" class="bg-gradient-to-b from-brand-50/60 to-white">
            <div class="max-w-7xl mx-auto px-6 pt-16 pb-20 grid lg:grid-cols-2 gap-12 items-center">

                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-slate-900">
                        Gérez votre caisse<br>
                        en toute <span class="text-brand-600">simplicité</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-600 max-w-xl">
                        FastCaisse est la solution tout-en-un pour gérer vos ventes, stocks,
                        clients et rapports en temps réel. Conçue pour les commerces,
                        supérettes, pharmacies et boutiques.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 transition text-white font-semibold px-6 py-3 rounded-lg">
                            <i data-lucide="play-circle" class="w-5 h-5"></i>
                            Découvrir FastCaisse
                        </a>
                        <button type="button" onclick="document.getElementById('demo-video').classList.remove('hidden')"
                                class="inline-flex items-center gap-2 border border-slate-200 hover:border-slate-300 transition font-semibold px-6 py-3 rounded-lg">
                            <i data-lucide="video" class="w-5 h-5"></i>
                            Voir la vidéo
                        </button>
                    </div>

                    <ul class="mt-8 flex flex-wrap gap-x-8 gap-y-3 text-sm font-medium text-slate-700">
                        <li class="flex items-center gap-2">
                            <span class="text-brand-600">✔</span> Facile à utiliser
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-600">✔</span> Accessible partout
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-600">✔</span> Sécurisé et fiable
                        </li>
                    </ul>
                </div>

                {{-- Photo remplacée par l'image fournie (caisse tactile en situation réelle) --}}
                <div class="rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ asset('images/hero-caisse.png') }}"
                         alt="Client réglant sa commande sur une caisse tactile FastCaisse"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        {{-- ==================== FONCTIONNALITÉS ==================== --}}
        <section id="fonctionnalites" class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid md:grid-cols-4 gap-6">

                <div class="bg-white border border-slate-100 rounded-xl p-6 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center mb-4">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Ventes simplifiées</h3>
                    <p class="text-sm text-slate-600">Enregistrez vos ventes rapidement et efficacement en quelques clics.</p>
                </div>

                <div class="bg-white border border-slate-100 rounded-xl p-6 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center mb-4">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Gestion des stocks</h3>
                    <p class="text-sm text-slate-600">Suivez vos stocks en temps réel et recevez des alertes de réapprovisionnement.</p>
                </div>

                <div class="bg-white border border-slate-100 rounded-xl p-6 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center mb-4">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Rapports détaillés</h3>
                    <p class="text-sm text-slate-600">Analysez vos performances grâce à des rapports clairs et personnalisés.</p>
                </div>

                <div class="bg-white border border-slate-100 rounded-xl p-6 hover:shadow-md transition">
                    <div class="w-11 h-11 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Clients & fidélité</h3>
                    <p class="text-sm text-slate-600">Gérez vos clients et fidélisez-les avec un programme de fidélité intégré.</p>
                </div>
            </div>

            {{-- Bandeau de chiffres clés --}}
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-slate-300 rounded-2xl p-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-900">1 250+</p>
                        <p class="text-sm text-slate-600">Commerces équipés</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-900">5 400+</p>
                        <p class="text-sm text-slate-600">Utilisateurs actifs</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-900">12 800+</p>
                        <p class="text-sm text-slate-600">Transactions par jour</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-900">99,9%</p>
                        <p class="text-sm text-slate-600">Disponibilité du service</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== TARIFS ==================== --}}
        <section id="tarifs" class="bg-slate-50 py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-900">Des tarifs adaptés à votre commerce</h2>
                    <p class="mt-3 text-slate-600">Choisissez la formule qui correspond à la taille de votre activité. Sans engagement.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-slate-100 p-8">
                        <h3 class="font-bold text-slate-900">Boutique</h3>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">29 000 Ar<span class="text-sm font-medium text-slate-500">/mois</span></p>
                        <ul class="mt-6 space-y-3 text-sm text-slate-600">
                            <li>✔ 1 caisse</li>
                            <li>✔ Gestion des stocks</li>
                            <li>✔ Rapports de base</li>
                        </ul>
                        <a href="#contact" class="mt-8 block text-center border border-brand-600 text-brand-700 font-semibold py-2.5 rounded-lg hover:bg-brand-50 transition">Choisir</a>
                    </div>

                    <div class="bg-brand-700 text-white rounded-2xl p-8 shadow-xl scale-105">
                        <p class="text-xs font-semibold tracking-wide uppercase text-brand-100">Le plus populaire</p>
                        <h3 class="mt-1 font-bold">Commerce</h3>
                        <p class="mt-2 text-3xl font-extrabold">69 000 Ar<span class="text-sm font-medium text-brand-100">/mois</span></p>
                        <ul class="mt-6 space-y-3 text-sm text-brand-50">
                            <li>✔ Jusqu'à 5 caisses</li>
                            <li>✔ Fidélité clients</li>
                            <li>✔ Rapports avancés</li>
                        </ul>
                        <a href="#contact" class="mt-8 block text-center bg-white text-brand-700 font-semibold py-2.5 rounded-lg hover:bg-brand-50 transition">Choisir</a>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 p-8">
                        <h3 class="font-bold text-slate-900">Réseau</h3>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">Sur devis</p>
                        <ul class="mt-6 space-y-3 text-sm text-slate-600">
                            <li>✔ Caisses illimitées</li>
                            <li>✔ Multi-magasins</li>
                            <li>✔ Support dédié</li>
                        </ul>
                        <a href="#contact" class="mt-8 block text-center border border-brand-600 text-brand-700 font-semibold py-2.5 rounded-lg hover:bg-brand-50 transition">Nous contacter</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== À PROPOS ==================== --}}
        <section id="apropos" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">À propos de FastCaisse</h2>
                <p class="mt-4 text-slate-600">
                    FastCaisse est née d'un constat simple : les petits commerces manquent d'outils
                    de caisse pensés pour eux. Notre équipe conçoit une application accessible,
                    fiable, et pensée pour fonctionner même dans les contextes les plus exigeants.
                </p>
                <p class="mt-4 text-slate-600">
                    Notre mission : donner à chaque commerçant les mêmes outils que les grandes
                    enseignes, sans la complexité.
                </p>
            </div>
            <div class="bg-brand-50 rounded-2xl p-8">
                <ul class="space-y-4 text-slate-700 text-sm font-medium">
                    <li class="flex gap-3"><span class="text-brand-600">✔</span> Installation rapide, sans matériel complexe</li>
                    <li class="flex gap-3"><span class="text-brand-600">✔</span> Fonctionne même avec une connexion instable</li>
                    <li class="flex gap-3"><span class="text-brand-600">✔</span> Données sauvegardées et sécurisées</li>
                    <li class="flex gap-3"><span class="text-brand-600">✔</span> Support en français, réactif</li>
                </ul>
            </div>
        </section>

        {{-- ==================== BANDEAU CTA ==================== --}}
        <section class="bg-brand-700">
            <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-2xl font-extrabold text-white">Prêt à simplifier la gestion de votre commerce ?</h2>
                    <p class="mt-2 text-brand-100">Rejoignez des milliers de commerçants qui font confiance à FastCaisse.</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="bg-white text-brand-700 font-semibold px-6 py-3 rounded-lg hover:bg-brand-50 transition">Créer un Compte Gratuitement</a>
                        <a href="#contact" class="text-white font-semibold px-2 py-3 hover:underline">Nous contacter →</a>
                    </div>
                </div>
                <div class="bg-white/10 rounded-2xl p-6 flex items-start gap-4">
                    <span class="text-white text-2xl">🛡️</span>
                    <div>
                        <p class="font-bold text-white">Sécurité garantie</p>
                        <p class="text-sm text-brand-100">Vos données sont protégées avec les meilleures normes de sécurité.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== CONTACT ==================== --}}
        <section id="contact" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Contactez-nous</h2>
                <p class="mt-3 text-slate-600">Une question, une démo à planifier ? Notre équipe vous répond rapidement.</p>

                @if (session('success'))
                    <div class="mt-6 rounded-lg border border-brand-100 bg-brand-50 px-4 py-3 text-sm text-brand-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-600">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-600">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-slate-700 mb-1">Message</label>
                        <textarea id="message" name="message" rows="4" required
                                  class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-600">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 transition text-white font-semibold px-6 py-3 rounded-lg">
                        Envoyer le message
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 space-y-4 text-sm text-slate-700 h-fit">
                <p class="flex items-center gap-3"><i data-lucide="map-pin" class="w-5 h-5 text-brand-600"></i> Antananarivo, Madagascar</p>
                <p class="flex items-center gap-3"><i data-lucide="mail" class="w-5 h-5 text-brand-600"></i> contact@fastcaisse.mg</p>
                <p class="flex items-center gap-3"><i data-lucide="phone" class="w-5 h-5 text-brand-600"></i> +261 34 00 000 00</p>
            </div>
        </section>
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="border-t border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <p>© {{ date('Y') }} FastCaisse. Tous droits réservés.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-brand-700">Mentions légales</a>
                <a href="#" class="hover:text-brand-700">Confidentialité</a>
                <a href="#" class="hover:text-brand-700">Conditions d'utilisation</a>
            </div>
        </div>
    </footer>

    {{-- Petite modale vidéo, déclenchée par le bouton "Voir la vidéo" --}}
    <div id="demo-video" class="hidden fixed inset-0 bg-black/70 z-[60] flex items-center justify-center px-6"
         onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-black rounded-xl overflow-hidden max-w-3xl w-full aspect-video">
            <div class="w-full h-full flex items-center justify-center text-white text-sm">
                Emplacement de la vidéo de démonstration
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Ouvre/ferme le menu mobile
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        // Ferme le menu mobile automatiquement après un clic sur un lien d'ancre
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

        // Met en surbrillance le lien de nav correspondant à la section visible
        const sections = ['accueil', 'fonctionnalites', 'tarifs', 'apropos', 'contact'];
        const navLinks = document.querySelectorAll('.nav-link');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => {
                        const isActive = link.getAttribute('href') === `#${entry.target.id}`;
                        link.classList.toggle('text-brand-700', isActive);
                        link.classList.toggle('border-b-2', isActive);
                        link.classList.toggle('border-brand-600', isActive);
                    });
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });

        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });
    </script>
</body>
</html>