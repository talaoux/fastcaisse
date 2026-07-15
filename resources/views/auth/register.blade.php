<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row bg-gray-50">

        {{-- Colonne gauche : présentation --}}
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center px-16 py-12 relative overflow-hidden">

            {{-- Formes décoratives en fond --}}
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-green-100/60 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 w-[28rem] h-[28rem] bg-green-50 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-12">
                    <div class="bg-green-600 p-2 rounded-lg">
                        {{-- Icône panier --}}
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Fast<span class="text-green-600">caisse</span></span>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-4">
                    Créez votre compte<br>
                    et commencez à gérer<br>
                    votre <span class="text-green-600">activité</span>
                </h1>

                <p class="text-gray-500 text-lg mb-12 max-w-md">
                    Rejoignez FastCaisse et simplifiez la gestion de vos ventes, produits et encaissements.
                </p>

                <div class="grid grid-cols-3 gap-6 max-w-md mb-16">
                    <div>
                        <div class="bg-green-100 w-10 h-10 flex items-center justify-center rounded-lg mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">Sécurisé</p>
                        <p class="text-gray-500 text-xs">Vos données sont sécurisées</p>
                    </div>
                    <div>
                        <div class="bg-green-100 w-10 h-10 flex items-center justify-center rounded-lg mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">Rapide</p>
                        <p class="text-gray-500 text-xs">Encaissez en un clin d'œil</p>
                    </div>
                    <div>
                        <div class="bg-green-100 w-10 h-10 flex items-center justify-center rounded-lg mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">Efficace</p>
                        <p class="text-gray-500 text-xs">Suivez vos performances</p>
                    </div>
                </div>

                {{-- Illustration : carte dashboard + sac --}}
                <div class="relative w-80 h-56">
                    <div class="absolute top-0 left-10 w-56 h-40 bg-white/70 border border-green-100 rounded-xl shadow-sm p-4">
                        <div class="flex items-end gap-2 h-16 mb-3">
                            <div class="w-4 bg-green-200 rounded h-6"></div>
                            <div class="w-4 bg-green-300 rounded h-10"></div>
                            <div class="w-4 bg-green-400 rounded h-16"></div>
                            <div class="w-4 bg-green-300 rounded h-8"></div>
                        </div>
                        <div class="h-2 bg-green-100 rounded w-3/4 mb-1.5"></div>
                        <div class="h-2 bg-green-100 rounded w-1/2"></div>
                    </div>

                    <div class="absolute top-16 left-40 w-40 h-36 bg-white border border-green-100 rounded-xl shadow-md p-4">
                        <div class="w-14 h-14 rounded-full mb-3"
                             style="background: conic-gradient(#16a34a 0deg 220deg, #dcfce7 220deg 360deg);"></div>
                        <div class="h-2 bg-gray-100 rounded w-full mb-1.5"></div>
                        <div class="h-2 bg-gray-100 rounded w-2/3"></div>
                    </div>

                    <div class="absolute bottom-0 left-0 bg-green-600 rounded-2xl p-4 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne droite : formulaire --}}
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-sm p-8 lg:p-10">

                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Créer un compte</h2>
                        <p class="text-sm text-gray-500">Remplissez les informations ci-dessous pour vous inscrire</p>
                    </div>
                </div>

                {{-- Erreurs de validation --}}
                <x-input-error :messages="$errors->get('role')" class="mb-3" />

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Photo de profil --}}
                    <div class="mb-6 flex flex-col items-center">
                        <label for="avatar" class="relative cursor-pointer group">
                            <div id="avatar-preview"
                                 class="w-24 h-24 rounded-full bg-green-50 border-2 border-dashed border-green-300 flex items-center justify-center overflow-hidden group-hover:border-green-500 transition-colors">
                                <svg class="w-9 h-9 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="absolute bottom-0 right-0 bg-green-600 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 17a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" />
                                </svg>
                            </span>
                            <input id="avatar" type="file" name="avatar" accept="image/png, image/jpeg, image/webp"
                                   class="hidden">
                        </label>
                        <p class="text-xs text-gray-500 mt-2">Photo de profil (optionnel)</p>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                    </div>

                    {{-- Sélection du rôle --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">Je m'inscris en tant que :</label>
                        <div class="grid grid-cols-2 gap-4">

                            {{-- Administrateur --}}
                            <label class="role-card relative cursor-pointer block">
                                <input type="radio" name="role" value="admin" class="peer sr-only"
                                    {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}>
                                <span class="check-badge hidden absolute -top-2 -right-2 w-6 h-6 rounded-full bg-green-600 items-center justify-center z-10 shadow">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div class="border-2 border-gray-200 rounded-xl p-4 peer-checked:border-green-600 peer-checked:bg-green-50 transition-colors h-full">
                                    <div class="bg-green-100 w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-gray-900 text-sm">Administrateur</p>
                                    <p class="text-gray-500 text-xs mt-1">Accès complet à toutes les fonctionnalités</p>
                                </div>
                            </label>

                            {{-- Caissier --}}
                            <label class="role-card relative cursor-pointer block">
                                <input type="radio" name="role" value="cashier" class="peer sr-only"
                                    {{ old('role') === 'cashier' ? 'checked' : '' }}>
                                <span class="check-badge hidden absolute -top-2 -right-2 w-6 h-6 rounded-full bg-green-600 items-center justify-center z-10 shadow">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <div class="border-2 border-gray-200 rounded-xl p-4 peer-checked:border-green-600 peer-checked:bg-green-50 transition-colors h-full">
                                    <div class="bg-green-100 w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-gray-900 text-sm">Caissier</p>
                                    <p class="text-gray-500 text-xs mt-1">Accès limité à la caisse et aux ventes</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Nom complet / Email --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="name" value="Nom complet" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <x-text-input id="name" class="pl-10 block w-full" type="text" name="name"
                                    :value="old('name')" required autofocus autocomplete="name"
                                    placeholder="Entrez votre nom complet" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <x-text-input id="email" class="pl-10 block w-full" type="email" name="email"
                                    :value="old('email')" required autocomplete="username"
                                    placeholder="Entrez votre email" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Nom d'utilisateur / Mot de passe --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="username" value="Nom d'utilisateur" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <x-text-input id="username" class="pl-10 block w-full" type="text" name="username"
                                    :value="old('username')" required
                                    placeholder="Choisissez un nom d'utilisateur" />
                            </div>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Mot de passe" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <x-text-input id="password" class="pl-10 pr-10 block w-full toggle-password-input"
                                    type="password" name="password" required autocomplete="new-password"
                                    placeholder="Créez un mot de passe" />
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Confirmation mot de passe --}}
                    <div class="mb-4">
                        <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <x-text-input id="password_confirmation" class="pl-10 pr-10 block w-full toggle-password-input"
                                type="password" name="password_confirmation" required autocomplete="new-password"
                                placeholder="Confirmez votre mot de passe" />
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Conditions d'utilisation --}}
                    <div class="mb-6">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}
                                class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-600">
                                J'accepte les
                                <a href="#" class="text-green-600 font-medium hover:underline">Conditions d'utilisation</a>
                                et la
                                <a href="#" class="text-green-600 font-medium hover:underline">Politique de confidentialité</a>
                            </span>
                        </label>
                        <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Créer mon compte
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-4">
                        Vous avez déjà un compte ?
                        <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Se connecter</a>
                    </p>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                &copy; {{ date('Y') }} Fastcaisse - Tous droits réservés.
            </p>
        </div>
    </div>

    {{-- JS : badge de sélection rôle, aperçu photo, toggle password --}}
    <script>
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            const updateChecks = () => {
                document.querySelectorAll('.role-card .check-badge').forEach(el => {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                });
                document.querySelectorAll('input[name="role"]:checked').forEach(checked => {
                    const badge = checked.closest('.role-card').querySelector('.check-badge');
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                });
            };
            radio.addEventListener('change', updateChecks);
            updateChecks();
        });

        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.previousElementSibling;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });

        // Aperçu de la photo de profil
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        if (avatarInput) {
            avatarInput.addEventListener('change', () => {
                const file = avatarInput.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    avatarPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Aperçu">`;
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</x-guest-layout>