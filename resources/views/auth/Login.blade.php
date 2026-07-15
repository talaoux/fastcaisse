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

    <form>

        <div class="form-group">

            <label>Email ou numéro de téléphone</label>

            <div class="input">

                <i class="fa-regular fa-user"></i>

                <input
                    type="text"
                    placeholder="Entrez votre email ou téléphone">

            </div>

        </div>

        <div class="form-group">

            <label>Mot de passe</label>

            <div class="input">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    placeholder="Entrez votre mot de passe">

                <i class="fa-regular fa-eye"></i>

            </div>

        </div>

        <div class="forgot">

            <a href="#">Mot de passe oublié ?</a>

        </div>

        <button>

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