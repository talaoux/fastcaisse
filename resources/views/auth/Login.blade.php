<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Connexion - FastCaisse</title>

<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body>

<div class="login-container">

    <div class="logo">

        <div class="logo-icon">
           <i class="fa-solid fa-cart-shopping"></i>
        </div>

        <span>Fastcaisse</span>

    </div>

    <h1>Connexion</h1>

    <p class="subtitle">
        Bienvenue ! Connectez-vous à votre compte
    </p>

    @if ($errors->any())
        <div style="background: #fee; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #c33; font-size: 14px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group">

            <label>Email</label>

            <div class="input">

                <i class="fa-regular fa-user"></i>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Entrez votre email"
                    required>

            </div>
            @error('email')
                <span style="color: #c33; font-size: 12px;">{{ $message }}</span>
            @enderror

        </div>

        <div class="form-group">

            <label>Mot de passe</label>

            <div class="input">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    placeholder="Entrez votre mot de passe"
                    required>

                <i class="fa-regular fa-eye"></i>

            </div>
            @error('password')
                <span style="color: #c33; font-size: 12px;">{{ $message }}</span>
            @enderror

        </div>

        <button type="submit">

            <i class="fa-solid fa-lock"></i>

            Se connecter

        </button>

    </form>

    <div class="separator">

        <span>OU</span>

    </div>

    <p class="register">

        Pas encore de compte ?

        <a href="#">S'inscrire</a>

    </p>

</div>

</body>
</html>