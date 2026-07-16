<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inscription - FastCaisse</title>

<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body>

<div class="register-container">

    <div class="logo">

        <div class="logo-icon">
           <i class="fa-solid fa-cart-shopping"></i>
        </div>

        <span>FastCaisse</span>

    </div>

    <h1>Inscription</h1>

    <p class="subtitle">
        Créez votre compte administrateur
    </p>

    @if ($errors->any())
        <div style="background: #fee; padding: 12px; margin-bottom: 20px; border-radius: 12px; color: #c33; font-size: 14px; border: 1px solid #fcc;">
            @foreach ($errors->all() as $error)
                <p style="margin: 4px 0;"><i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Première ligne : Nom complet et Email --}}
        <div class="form-row">

            <div class="form-group">

                <label>Nom complet</label>

                <div class="input">

                    <i class="fa-regular fa-user icon-left"></i>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Entrez votre nom complet"
                    required>

                </div>
                @error('name')
                    <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror

            </div>

            <div class="form-group">

                <label>Email</label>

                <div class="input">

                    <i class="fa-regular fa-envelope icon-left"></i>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Entrez votre email"
                    required>

                </div>
                @error('email')
                    <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror

            </div>

        </div>

        {{-- Deuxième ligne : Nom d'utilisateur --}}
        <div class="form-group">

            <label>Nom d'utilisateur</label>

            <div class="input">

                <i class="fa-regular fa-user icon-left"></i>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Choisissez un nom d'utilisateur"
                    required>

            </div>
            @error('username')
                <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror

        </div>

        {{-- Troisième ligne : Mot de passe --}}
        <div class="form-group">

            <label>Mot de passe</label>

            <div class="input">

                <i class="fa-solid fa-lock icon-left"></i>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Entrez votre mot de passe"
                    required>

                <i class="fa-regular fa-eye icon-right" id="togglePassword"></i>

            </div>
            @error('password')
                <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror

        </div>

        {{-- Quatrième ligne : Confirmer le mot de passe --}}
        <div class="form-group">

            <label>Confirmer le mot de passe</label>

            <div class="input">

                <i class="fa-solid fa-lock icon-left"></i>

                <input
                    type="password"
                    name="password_confirmation"
                    id="passwordConfirmation"
                    placeholder="Confirmez votre mot de passe"
                    required>

                <i class="fa-regular fa-eye icon-right" id="togglePasswordConfirmation"></i>

            </div>
            @error('password_confirmation')
                <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror

        </div>

        {{-- Cinquième ligne : Téléphone --}}
        <div class="form-group">

            <label>Téléphone <span style="color:#64748b; font-weight:400;">(optionnel)</span></label>

            <div class="input">

                <i class="fa-solid fa-phone icon-left"></i>

                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Entrez votre numéro de téléphone">

            </div>
            @error('phone')
                <span style="color: #c33; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror

        </div>

        {{-- Carte d'information --}}
        <div class="info-card">

            <div class="info-card-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <div class="info-card-content">
                <div class="info-card-title">Compte administrateur</div>
                <div class="info-card-text">Ce compte aura un accès complet au système FastCaisse.</div>
            </div>

        </div>

        {{-- Bouton principal --}}
        <button type="submit">

            <i class="fa-solid fa-lock"></i>

            Créer mon compte

        </button>

    </form>

    {{-- Séparateur --}}
    <div class="separator">

        <span>OU</span>

    </div>

    {{-- Lien connexion --}}
    <p class="register">

        Déjà un compte ?

        <a href="{{ route('login') }}">Se connecter</a>

    </p>

</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordConfirmation');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
