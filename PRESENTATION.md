# FastCaisse - Documentation Technique

**Version:** 1.0  
**Date:** Juillet 2025  
**Auteur:** Équipe FastCaisse  
**Statut:** Production

---

## Table des matières

1. [Présentation générale](#1-présentation-générale)
2. [Technologies utilisées](#2-technologies-utilisées)
3. [Architecture Laravel](#3-architecture-laravel)
4. [Structure du projet](#4-structure-du-projet)
5. [Base de données](#5-base-de-données)
6. [Authentification](#6-authentification)
7. [Sécurité](#7-sécurité)
8. [Dashboard Administrateur](#8-dashboard-administrateur)
9. [Dashboard Caissier](#9-dashboard-caissier)
10. [Logique complète d'une vente](#10-logique-complète-dune-vente)
11. [Gestion des stocks](#11-gestion-des-stocks)
12. [Fonctionnement des statistiques](#12-fonctionnement-des-statistiques)
13. [Fonctionnement des courbes](#13-fonctionnement-des-courbes)
14. [Fonctionnement de la caisse](#14-fonctionnement-de-la-caisse)
15. [Workflow général](#15-workflow-général)
16. [Évolutions futures](#16-évolutions-futures)
17. [Conclusion](#17-conclusion)

---

## 1. Présentation générale

### 1.1 Qu'est-ce que FastCaisse ?

FastCaisse est une application web de gestion de caisse et de point de vente (POS) moderne, développée avec le framework Laravel. Elle permet aux commerces de toute taille de gérer efficacement leurs ventes, leurs stocks, leurs clients et leurs employés à travers une interface intuitive et professionnelle.

### 1.2 Public cible

FastCaisse est destiné à :

- **Petits commerces** : Épiceries, boutiques, pharmacies
- **Moyennes entreprises** : Magasins multi-employés
- **Administrateurs** : Gestion complète du système
- **Caissiers** : Interface simplifiée pour les ventes

### 1.3 Objectifs du projet

- **Moderniser** la gestion de caisse traditionnelle
- **Automatiser** les processus de vente et de stock
- **Centraliser** toutes les données commerciales
- **Sécuriser** les transactions et les accès
- **Analyser** les performances commerciales en temps réel
- **Simplifier** le travail quotidien des caissiers

### 1.4 Avantages

| Avantage | Description |
|----------|-------------|
| **Rapidité** | Interface optimisée pour des transactions rapides |
| **Sécurité** | Gestion des rôles et permissions avancée |
| **Traçabilité** | Historique complet de toutes les opérations |
| **Analytique** | Statistiques et rapports détaillés |
| **Multi-utilisateurs** | Support pour admin et caissiers |
| **Responsive** | Fonctionne sur desktop, tablette et mobile |
| **Évolutif** | Architecture modulaire et extensible |

### 1.5 Fonctionnalités principales

#### Gestion des ventes
- Création de ventes rapides
- Scan de code-barres
- Gestion du panier
- Calcul automatique des totaux
- Gestion des remises
- Impression de tickets

#### Gestion des stocks
- Suivi en temps réel
- Alertes de stock minimum
- Historique des mouvements
- Inventaire
- Gestion des entrées/sorties

#### Gestion des clients
- Base de données clients
- Historique des achats
- Informations de contact

#### Gestion des utilisateurs
- Rôles (Admin, Caissier)
- Permissions granulaires
- Authentification sécurisée

#### Statistiques et rapports
- Chiffre d'affaires
- Produits les plus vendus
- Évolution des ventes
- Rapports personnalisés

#### Gestion de la caisse
- Ouverture/fermeture de caisse
- Calcul automatique des différences
- Historique des sessions

---

## 2. Technologies utilisées

### 2.1 Laravel (v9.x)

**Rôle :** Framework PHP principal  
**Description :** Laravel fournit l'architecture MVC (Modèle-Vue-Contrôleur), le système de routage, l'authentification, la validation, l'ORM Eloquent pour la base de données, et de nombreux outils de développement.

**Utilisation dans FastCaisse :**
- Gestion des routes et des contrôleurs
- ORM Eloquent pour les modèles
- Système d'authentification
- Validation des formulaires
- Migrations de base de données
- Système de templates Blade

### 2.2 PHP (v8.0+)

**Rôle :** Langage de programmation principal  
**Description :** PHP est le langage serveur utilisé pour toute la logique métier, le traitement des données et la génération des pages web.

**Utilisation dans FastCaisse :**
- Logique métier dans les contrôleurs
- Traitement des formulaires
- Calculs statistiques
- Gestion des sessions

### 2.3 Blade

**Rôle :** Moteur de templates Laravel  
**Description :** Blade est le moteur de templating de Laravel qui permet de créer des vues dynamiques avec une syntaxe simple et élégante.

**Utilisation dans FastCaisse :**
- Templates des pages (login, register, dashboards)
- Layouts réutilisables
- Inclusion de composants
- Affichage des données dynamiques

### 2.4 Tailwind CSS

**Rôle :** Framework CSS utilitaire  
**Description :** Tailwind CSS est un framework CSS qui fournit des classes utilitaires pour construire rapidement des interfaces modernes et responsive.

**Utilisation dans FastCaisse :**
- Design system complet
- Layout responsive
- Composants UI modernes
- Animations et transitions

### 2.5 JavaScript

**Rôle :** Langage de programmation côté client  
**Description :** JavaScript ajoute de l'interactivité et des fonctionnalités dynamiques côté navigateur.

**Utilisation dans FastCaisse :**
- Toggle de visibilité des mots de passe
- Aperçu d'images (avatars)
- Interactions dynamiques
- Appels AJAX (si nécessaire)

### 2.6 Alpine.js

**Rôle :** Framework JavaScript réactif léger  
**Description :** Alpine.js permet d'ajouter de l'interactivité directement dans le HTML sans écrire de JavaScript complexe.

**Utilisation dans FastCaisse :**
- Composants interactifs légers
- Gestion d'états simples
- Animations CSS

### 2.7 MySQL

**Rôle :** Système de gestion de base de données  
**Description :** MySQL stocke toutes les données de l'application : utilisateurs, produits, ventes, clients, etc.

**Utilisation dans FastCaisse :**
- Stockage des utilisateurs
- Catalogue produits
- Historique des ventes
- Données clients
- Sessions de caisse

### 2.8 Composer

**Rôle :** Gestionnaire de dépendances PHP  
**Description :** Composer gère l'installation et la mise à jour des bibliothèques PHP.

**Utilisation dans FastCaisse :**
- Installation de Laravel
- Gestion des packages
- Autoloading des classes

### 2.9 Vite

**Rôle :** Outil de build moderne  
**Description :** Vite est un outil de build qui compile et optimise les assets (CSS, JS) pour la production.

**Utilisation dans FastCaisse :**
- Compilation des assets CSS/JS
- Hot reloading en développement
- Optimisation pour la production

### 2.10 Git & GitHub

**Rôle :** Gestion de version et collaboration  
**Description :** Git permet le suivi des modifications du code, GitHub héberge le dépôt distant.

**Utilisation dans FastCaisse :**
- Versioning du code
- Collaboration entre développeurs
- Backup du projet
- Gestion des branches

---

## 3. Architecture Laravel

### 3.1 Principe MVC

FastCaisse suit l'architecture **Modèle-Vue-Contrôleur (MVC)** :

```
┌─────────────────────────────────────────────────────────┐
│                    NAVIGATEUR (Client)                   │
└───────────────────────┬─────────────────────────────────┘
                        │ HTTP Request
                        ↓
┌─────────────────────────────────────────────────────────┐
│  ROUTES (routes/web.php)                                 │
│  - Définit les URLs                                       │
│  - Associe les URLs aux contrôleurs                       │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  MIDDLEWARE                                                │
│  - Authentification (auth)                                 │
│  - Vérification des rôles (role:admin, role:cashier)       │
│  - Protection CSRF                                         │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  CONTRÔLEUR (Controller)                                   │
│  - Reçoit la requête                                       │
│  - Valide les données                                      │
│  - Appelle les modèles                                     │
│  - Retourne une vue                                        │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  MODÈLE (Model) - Eloquent ORM                            │
│  - Représente les données                                  │
│  - Gère les relations                                      │
│  - Effectue les opérations CRUD                            │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  BASE DE DONNÉES (MySQL)                                   │
│  - Stocke toutes les données                               │
│  - Exécute les requêtes SQL                                │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  CONTRÔLEUR (Controller)                                   │
│  - Reçoit les données du modèle                            │
│  - Traite et formate les données                           │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│  VUE BLADE (View)                                         │
│  - Affiche les données                                     │
│  - Génère le HTML                                          │
│  - Applique le design (CSS)                                │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────┐
│                    NAVIGATEUR (Client)                   │
│  - Affiche la page web                                     │
│  - Exécute le JavaScript                                   │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Composants Laravel

#### Routes (`routes/web.php`)

**Rôle :** Définissent les URLs de l'application et les associent aux contrôleurs.

**Types de routes dans FastCaisse :**
- **Routes publiques** : Page d'accueil, login, register
- **Routes protégées** : Dashboards, gestion des produits
- **Routes avec middleware de rôle** : Admin uniquement, Caissier uniquement

**Exemple :**
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
```

#### Contrôleurs (`app/Http/Controllers/`)

**Rôle :** Gèrent la logique métier, reçoivent les requêtes, interagissent avec les modèles, retournent les vues.

**Contrôleurs principaux :**
- `AuthController` : Gestion de l'authentification
- `RegisteredUserController` : Inscription des utilisateurs
- `DashboardController` : Dashboard administrateur
- `CashierController` : Dashboard caissier
- `ProductController` : Gestion des produits
- `SalesController` : Gestion des ventes
- `StockController` : Gestion des stocks
- `CustomersController` : Gestion des clients

#### Modèles (`app/Models/`)

**Rôle :** Représentent les tables de la base de données, gèrent les relations et les opérations CRUD.

**Modèles principaux :**
- `User` : Utilisateurs (admin, caissier)
- `Product` : Produits
- `Category` : Catégories de produits
- `Customer` : Clients
- `Sale` : Ventes
- `SaleItem` : Lignes de vente
- `StockMovement` : Mouvements de stock
- `CashSession` : Sessions de caisse

#### Vues Blade (`resources/views/`)

**Rôle :** Templates HTML qui affichent les données à l'utilisateur.

**Structure :**
- `layouts/` : Layouts principaux (admin, cashier)
- `auth/` : Pages d'authentification (login, register)
- `admin/` : Vues du dashboard admin
- `cashier/` : Vues du dashboard caissier
- `components/` : Composants réutilisables

#### Middleware

**Rôle :** Filtrent les requêtes HTTP avant qu'elles n'atteignent les contrôleurs.

**Middleware utilisés :**
- `auth` : Vérifie si l'utilisateur est connecté
- `role:admin` : Vérifie si l'utilisateur est admin
- `role:cashier` : Vérifie si l'utilisateur est caissier
- `guest` : Vérifie si l'utilisateur n'est pas connecté

#### Validation

**Rôle :** Valide les données entrées par les utilisateurs.

**Exemple :**
```php
$validated = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],
]);
```

#### Storage

**Rôle :** Gère le stockage des fichiers (images, documents).

**Utilisation :**
- Avatars des utilisateurs
- Images de produits
- Tickets de vente (PDF)

#### Assets

**Rôle :** Fichiers statiques (CSS, JS, images).

**Structure :**
- `public/css/` : Fichiers CSS
- `public/js/` : Fichiers JavaScript
- `public/images/` : Images
- `resources/css/` : Sources CSS (compilées par Vite)
- `resources/js/` : Sources JavaScript

#### Configuration (`config/`)

**Rôle :** Fichiers de configuration de l'application.

**Fichiers principaux :**
- `app.php` : Configuration générale
- `database.php` : Configuration de la base de données
- `auth.php` : Configuration de l'authentification
- `mail.php` : Configuration des emails

#### Migrations (`database/migrations/`)

**Rôle :** Gèrent la structure de la base de données de manière versionnée.

**Exemple :**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});
```

#### Seeders (`database/seeders/`)

**Rôle :** Peuplent la base de données avec des données de test.

**Utilisation :**
- Données de démonstration
- Comptes de test

#### Factories (`database/factories/`)

**Rôle :** Génèrent des données factices pour les tests.

**Utilisation :**
- Tests automatisés
- Développement avec données réalistes

---

## 4. Structure du projet

```
fastCaisse/
├── app/                          # Code source de l'application
│   ├── Console/                  # Commandes Artisan
│   ├── Exceptions/               # Gestion des exceptions
│   ├── Http/                     # Couche HTTP
│   │   ├── Controllers/          # Contrôleurs
│   │   │   ├── Admin/            # Contrôleurs admin
│   │   │   ├── Auth/             # Contrôleurs d'authentification
│   │   │   ├── AuthController.php
│   │   │   ├── CashierController.php
│   │   │   ├── ContactController.php
│   │   │   ├── ProductController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/            # Middleware personnalisés
│   │   └── Requests/             # Form Requests (validation)
│   ├── Models/                   # Modèles Eloquent
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Customer.php
│   │   ├── Sale.php
│   │   ├── SaleItem.php
│   │   ├── StockMovement.php
│   │   └── CashSession.php
│   └── Providers/                # Service Providers
│
├── bootstrap/                    # Fichiers de démarrage
│   ├── app.php                   # Configuration de l'application
│   └── cache/                    # Cache des configurations
│
├── config/                       # Fichiers de configuration
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mail.php
│   └── ...
│
├── database/                     # Gestion de la base de données
│   ├── migrations/               # Migrations
│   ├── seeders/                  # Seeders
│   └── factories/                # Factories
│
├── public/                       # Dossier public (accessible via le web)
│   ├── index.php                 # Point d'entrée
│   ├── css/                      # Fichiers CSS compilés
│   ├── js/                       # Fichiers JavaScript compilés
│   ├── images/                   # Images
│   └── uploads/                  # Fichiers uploadés
│
├── resources/                    # Ressources de l'application
│   ├── views/                    # Vues Blade
│   │   ├── layouts/              # Layouts
│   │   ├── auth/                 # Authentification
│   │   ├── admin/                # Vues admin
│   │   ├── cashier/              # Vues caissier
│   │   └── components/           # Composants
│   ├── css/                      # Sources CSS
│   ├── js/                       # Sources JavaScript
│   └── lang/                     # Fichiers de traduction
│
├── routes/                       # Routes de l'application
│   ├── web.php                   # Routes web
│   ├── api.php                   # Routes API
│   └── console.php               # Commandes console
│
├── storage/                      # Stockage de l'application
│   ├── app/                      # Fichiers de l'application
│   ├── framework/                # Cache, sessions, vues
│   └── logs/                     # Fichiers de log
│
├── tests/                        # Tests automatisés
│
├── vendor/                       # Dépendances Composer
│
├── composer.json                 # Dépendances PHP
├── package.json                  # Dépendances JavaScript
├── vite.config.js                # Configuration Vite
├── .env.example                  # Exemple de configuration
├── artisan                       # Interface en ligne de commande
└── README.md                     # Documentation du projet
```

### 4.1 Rôle de chaque dossier

#### `app/`
Cœur de l'application. Contient toute la logique métier :
- **Console/** : Commandes Artisan personnalisées
- **Exceptions/** : Gestion des erreurs
- **Http/** : Couche de présentation (contrôleurs, middleware, requêtes)
- **Models/** : Modèles de données (Eloquent)
- **Providers/** : Services qui configurent l'application

#### `bootstrap/`
Fichiers de démarrage de l'application. Charge l'autoloader et configure l'application.

#### `config/`
Toutes les configurations de l'application (base de données, authentification, mail, etc.).

#### `database/`
Gestion de la base de données :
- **migrations/** : Historique des modifications de la BDD
- **seeders/** : Données de test
- **factories/** : Générateurs de données factices

#### `public/`
Point d'entrée web. Tous les fichiers accessibles publiquement (CSS, JS, images, uploads).

#### `resources/`
Ressources non compilées :
- **views/** : Templates Blade
- **css/** : Sources CSS (Sass/PostCSS)
- **js/** : Sources JavaScript

#### `routes/`
Définition des routes de l'application :
- **web.php** : Routes web (avec sessions, CSRF)
- **api.php** : Routes API (sans sessions)
- **console.php** : Commandes console

#### `storage/`
Stockage de l'application :
- **app/** : Fichiers générés par l'application
- **framework/** : Cache, sessions, vues compilées
- **logs/** : Fichiers de log

#### `tests/`
Tests automatisés (unitaires, fonctionnels).

#### `vendor/`
Dépendances PHP installées par Composer.

---

## 5. Base de données

### 5.1 Schéma des relations

```
┌─────────────┐       ┌──────────────┐       ┌─────────────┐
│    users    │       │  categories  │       │  suppliers  │
├─────────────┤       ├──────────────┤       ├─────────────┤
│ id          │       │ id           │       │ id          │
│ name        │       │ name         │       │ name        │
│ username    │       │ description  │       │ email       │
│ email       │       │ created_at   │       │ phone       │
│ password    │       │ updated_at   │       │ address     │
│ role        │       └──────┬───────┘       └──────┬──────┘
│ phone       │              │                      │
│ avatar      │              │                      │
│ created_at  │              │                      │
│ updated_at  │       ┌──────┴───────┐              │
└──────┬──────┘       │   products   │◄─────────────┘
       │               ├──────────────┤
       │               │ id           │
       │               │ name         │
       │               │ sku          │
       │               │ barcode      │
       │               │ category_id  │
       │               │ supplier_id  │
       │               │ purchase_price│
       │               │ selling_price│
       │               │ stock_quantity│
       │               │ min_stock    │
       │               │ image        │
       │               │ created_at   │
       │               │ updated_at   │
       │               └──────┬───────┘
       │                      │
       │                      │ 1
       │                      │
       │               ┌──────┴──────────┐
       │               │  sale_items     │
       │               ├─────────────────┤
       │               │ id              │
       │               │ sale_id         │
       │               │ product_id      │
       │               │ quantity        │
       │               │ unit_price      │
       │               │ discount        │
       │               │ subtotal        │
       │               │ created_at      │
       │               │ updated_at      │
       │               └────────┬────────┘
       │                        │
       │                        │ *
       │               ┌────────┴────────┐
       │               │     sales       │
       │               ├─────────────────┤
       │               │ id              │
       │               │ user_id         │
       │               │ customer_id     │
       │               │ total           │
       │               │ subtotal        │
       │               │ discount        │
       │               │ tax             │
       │               │ payment_method  │
       │               │ amount_paid     │
       │               │ change          │
       │               │ status          │
       │               │ created_at      │
       │               │ updated_at      │
       │               └────────┬────────┘
       │                        │
       │                        │ 1
       │               ┌────────┴────────┐
       │               │   payments      │
       │               ├─────────────────┤
       │               │ id              │
       │               │ sale_id         │
       │               │ amount          │
       │               │ method          │
       │               │ reference       │
       │               │ created_at      │
       │               │ updated_at      │
       │               └─────────────────┘
       │
       │               ┌─────────────────┐
       │               │ stock_movements │
       │               ├─────────────────┤
       │               │ id              │
       │               │ product_id      │
       │               │ user_id         │
       │               │ type            │
       │               │ quantity        │
       │               │ reason          │
       │               │ created_at      │
       │               │ updated_at      │
       │               └─────────────────┘
       │
       │               ┌─────────────────┐
       │               │ cash_sessions   │
       │               ├─────────────────┤
       │               │ id              │
       │               │ user_id         │
       │               │ opening_amount  │
       │               │ closing_amount  │
       │               │ expected_amount │
       │               │ difference      │
       │               │ opened_at       │
       │               │ closed_at       │
       │               │ created_at      │
       │               │ updated_at      │
       │               └─────────────────┘
       │
       │               ┌─────────────────┐
       │               │   customers     │
       │               ├─────────────────┤
       │               │ id              │
       │               │ name            │
       │               │ email           │
       │               │ phone           │
       │               │ address         │
       │               │ created_at      │
       │               │ updated_at      │
       │               └─────────────────┘
```

### 5.2 Table users

**Objectif :** Stocker les informations des utilisateurs (admins et caissiers)

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| name | VARCHAR(255) | Nom complet |
| username | VARCHAR(255) | Nom d'utilisateur (unique) |
| email | VARCHAR(255) | Email (unique) |
| password | VARCHAR(255) | Mot de passe hashé |
| role | ENUM | Rôle (admin, cashier) |
| phone | VARCHAR(20) | Numéro de téléphone (optionnel) |
| avatar | VARCHAR(255) | Chemin de l'avatar (optionnel) |
| email_verified_at | TIMESTAMP | Date de vérification email |
| remember_token | VARCHAR(100) | Token "se souvenir de moi" |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `hasMany(Sale)` : Un utilisateur peut créer plusieurs ventes
- `hasMany(StockMovement)` : Un utilisateur peut effectuer plusieurs mouvements de stock
- `hasMany(CashSession)` : Un utilisateur peut ouvrir plusieurs sessions de caisse

### 5.3 Table products

**Objectif :** Stocker le catalogue des produits

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| name | VARCHAR(255) | Nom du produit |
| sku | VARCHAR(255) | Référence interne (unique) |
| barcode | VARCHAR(255) | Code-barres (unique) |
| category_id | BIGINT | ID de la catégorie |
| supplier_id | BIGINT | ID du fournisseur |
| purchase_price | DECIMAL | Prix d'achat |
| selling_price | DECIMAL | Prix de vente |
| stock_quantity | INT | Quantité en stock |
| min_stock | INT | Stock minimum d'alerte |
| image | VARCHAR(255) | Image du produit |
| description | TEXT | Description |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(Category)` : Un produit appartient à une catégorie
- `belongsTo(Supplier)` : Un produit a un fournisseur
- `hasMany(SaleItem)` : Un produit peut être dans plusieurs lignes de vente
- `hasMany(StockMovement)` : Un produit peut avoir plusieurs mouvements de stock

### 5.4 Table categories

**Objectif :** Classer les produits par catégorie

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| name | VARCHAR(255) | Nom de la catégorie |
| description | TEXT | Description |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `hasMany(Product)` : Une catégorie peut avoir plusieurs produits

### 5.5 Table suppliers

**Objectif :** Gérer les fournisseurs

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| name | VARCHAR(255) | Nom du fournisseur |
| email | VARCHAR(255) | Email |
| phone | VARCHAR(20) | Téléphone |
| address | TEXT | Adresse |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `hasMany(Product)` : Un fournisseur peut fournir plusieurs produits

### 5.6 Table customers

**Objectif :** Gérer les clients

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| name | VARCHAR(255) | Nom du client |
| email | VARCHAR(255) | Email (unique) |
| phone | VARCHAR(20) | Téléphone |
| address | TEXT | Adresse |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `hasMany(Sale)` : Un client peut avoir plusieurs ventes

### 5.7 Table sales

**Objectif :** Enregistrer chaque vente effectuée

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| user_id | BIGINT | ID du caissier/admin |
| customer_id | BIGINT | ID du client (nullable) |
| total | DECIMAL | Montant total |
| subtotal | DECIMAL | Sous-total |
| discount | DECIMAL | Remise |
| tax | DECIMAL | Taxe |
| payment_method | VARCHAR(50) | Méthode de paiement |
| amount_paid | DECIMAL | Montant payé |
| change | DECIMAL | Monnaie rendue |
| status | ENUM | Statut (completed, pending, cancelled) |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(User)` : Une vente est effectuée par un utilisateur
- `belongsTo(Customer)` : Une vente concerne un client
- `hasMany(SaleItem)` : Une vente contient plusieurs lignes
- `hasMany(Payment)` : Une vente peut avoir plusieurs paiements

### 5.8 Table sale_items

**Objectif :** Stocker chaque produit d'une vente

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| sale_id | BIGINT | ID de la vente |
| product_id | BIGINT | ID du produit |
| quantity | INT | Quantité |
| unit_price | DECIMAL | Prix unitaire |
| discount | DECIMAL | Remise sur la ligne |
| subtotal | DECIMAL | Sous-total de la ligne |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(Sale)` : Une ligne appartient à une vente
- `belongsTo(Product)` : Une ligne concerne un produit

### 5.9 Table payments

**Objectif :** Enregistrer les paiements d'une vente

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| sale_id | BIGINT | ID de la vente |
| amount | DECIMAL | Montant payé |
| method | VARCHAR(50) | Méthode (cash, card, mobile) |
| reference | VARCHAR(255) | Référence (pour carte/mobile) |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(Sale)` : Un paiement appartient à une vente

### 5.10 Table stock_movements

**Objectif :** Tracker tous les mouvements de stock

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| product_id | BIGINT | ID du produit |
| user_id | BIGINT | ID de l'utilisateur |
| type | ENUM | Type (in, out, adjustment) |
| quantity | INT | Quantité |
| reason | VARCHAR(255) | Raison du mouvement |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(Product)` : Un mouvement concerne un produit
- `belongsTo(User)` : Un mouvement est effectué par un utilisateur

### 5.11 Table cash_sessions

**Objectif :** Gérer les sessions de caisse

**Colonnes principales :**
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Identifiant unique |
| user_id | BIGINT | ID de l'utilisateur |
| opening_amount | DECIMAL | Montant d'ouverture |
| closing_amount | DECIMAL | Montant de clôture |
| expected_amount | DECIMAL | Montant attendu |
| difference | DECIMAL | Différence |
| opened_at | TIMESTAMP | Date d'ouverture |
| closed_at | TIMESTAMP | Date de fermeture |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Relations :**
- `belongsTo(User)` : Une session appartient à un utilisateur

---

## 6. Authentification

### 6.1 Connexion

**Processus :**
1. L'utilisateur accède à `/login`
2. Il saisit son email et mot de passe
3. Le système valide les identifiants
4. Si valide : création de la session et redirection vers le dashboard
5. Si invalide : message d'erreur

**Sécurité :**
- Hash des mots de passe avec bcrypt
- Protection CSRF sur le formulaire
- Limitation des tentatives de connexion
- Sessions sécurisées

### 6.2 Inscription

**Processus :**
1. L'utilisateur accède à `/register`
2. Il remplit le formulaire avec :
   - Nom complet
   - Email
   - Nom d'utilisateur
   - Mot de passe
   - Confirmation du mot de passe
   - Téléphone (optionnel)
3. Validation des données
4. Création du compte avec rôle 'admin'
5. Connexion automatique
6. Redirection vers `/admin/dashboard`

**Validation :**
- Email unique
- Username unique
- Mot de passe confirmé
- Format email valide

### 6.3 Déconnexion

**Processus :**
1. L'utilisateur clique sur "Déconnexion"
2. La session est détruite
3. Redirection vers la page de login

### 6.4 Hash des mots de passe

**Méthode :** Bcrypt (via Laravel)

**Processus :**
```php
// Automatique grâce au cast dans le modèle User
protected function casts(): array
{
    return [
        'password' => 'hashed',
    ];
}
```

### 6.5 Gestion des rôles

**Rôles disponibles :**
- **admin** : Accès complet à toutes les fonctionnalités
- **cashier** : Accès limité aux ventes et aux produits

**Implémentation :**
- Colonne `role` dans la table `users`
- Middleware `role:admin` et `role:cashier`
- Méthodes `isAdmin()` et `isCashier()` dans le modèle User

### 6.6 Redirections

**Logique :**
- Après login : Redirection vers le dashboard approprié selon le rôle
- Admin → `/admin/dashboard`
- Caissier → `/cashier/dashboard`

### 6.7 Protection des routes

**Middleware utilisés :**
- `auth` : Protège les routes nécessitant une authentification
- `role:admin` : Protège les routes admin uniquement
- `role:cashier` : Protège les routes caissier uniquement
- `guest` : Protège les routes pour utilisateurs non connectés

**Exemple :**
```php
Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    });
});
```

---

## 7. Sécurité

### 7.1 Validation des formulaires

**Principe :** Toutes les données entrées par les utilisateurs sont validées côté serveur.

**Exemple :**
```php
$validated = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],
]);
```

**Règles de validation utilisées :**
- `required` : Champ obligatoire
- `string` : Doit être une chaîne de caractères
- `email` : Doit être un email valide
- `unique:table,column` : Doit être unique dans la base de données
- `max:255` : Longueur maximale
- `confirmed` : Doit avoir un champ de confirmation

### 7.2 CSRF (Cross-Site Request Forgery)

**Protection :** Laravel inclut automatiquement un token CSRF dans tous les formulaires.

**Utilisation :**
```html
<form method="POST" action="/login">
    @csrf
    <!-- champs du formulaire -->
</form>
```

### 7.3 Hashage des mots de passe

**Méthode :** Bcrypt

**Avantages :**
- Irréversible
- Salt automatique
- Résistant aux attaques par force brute

### 7.4 Protection XSS (Cross-Site Scripting)

**Protection :** Blade échappe automatiquement toutes les variables affichées.

**Exemple :**
```blade
{{ $user->name }}  <!-- Échappé automatiquement -->
{!! $user->name !!} <!-- Non échappé (à éviter) -->
```

### 7.5 Protection SQL Injection

**Protection :** Eloquent ORM utilise des requêtes préparées.

**Exemple sécurisé :**
```php
User::where('email', $email)->first();
```

### 7.6 Mass Assignment

**Protection :** Seuls les champs définis dans `$fillable` peuvent être assignés en masse.

**Exemple :**
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

### 7.7 Middleware

**Rôle :** Filtrer les requêtes HTTP avant qu'elles n'atteignent les contrôleurs.

**Middleware de sécurité :**
- `auth` : Vérifie l'authentification
- `role:admin` : Vérifie le rôle
- `throttle` : Limite les requêtes (protection DDoS)

### 7.8 Gestion des permissions

**Système :** Basé sur les rôles (RBAC - Role-Based Access Control)

**Implémentation :**
- Rôles stockés dans la table `users`
- Middleware de vérification de rôle
- Redirection selon le rôle

### 7.9 Gestion des sessions

**Configuration :**
- Driver : file (par défaut) ou database
- Lifetime : 120 minutes (configurable)
- Secure : HTTPS en production
- HttpOnly : Cookies accessibles uniquement via HTTP

### 7.10 Protection des uploads

**Mesures :**
- Validation du type de fichier (image uniquement)
- Validation de la taille (max 2MB)
- Stockage hors du dossier public
- Génération de noms de fichiers uniques

### 7.11 Validation des images

**Règles :**
```php
'avatar' => ['nullable', 'image', 'max:2048']
```

**Vérifications :**
- Type MIME (image/jpeg, image/png, etc.)
- Dimensions (si nécessaire)
- Taille maximale

### 7.12 Gestion des erreurs

**En production :**
- Affichage d'une page d'erreur générique
- Logging des erreurs détaillées
- Pas d'affichage des stack traces

**En développement :**
- Affichage détaillé des erreurs
- Stack traces complètes
- Page d'erreur Laravel

### 7.13 Bonnes pratiques

✅ **Respectées dans FastCaisse :**
- Validation systématique des entrées
- Hash des mots de passe
- Protection CSRF
- Échappement automatique (Blade)
- Requêtes préparées (Eloquent)
- Mass assignment protection
- Middleware de protection
- Gestion des erreurs
- Logging des actions sensibles

---

## 8. Dashboard Administrateur

### 8.1 Rôle

Le Dashboard Administrateur est l'interface de gestion complète de FastCaisse. Il donne un accès total à toutes les fonctionnalités du système.

### 8.2 Accès

**URL :** `/admin/dashboard`  
**Rôle requis :** admin  
**Middleware :** `auth` + `role:admin`

### 8.3 Sections principales

#### 8.3.1 Vue d'ensemble

**Objectif :** Afficher les statistiques clés en temps réel

**Indicateurs :**
- Chiffre d'affaires du jour
- Nombre de ventes du jour
- Nombre de produits en stock
- Alertes de stock bas
- Nombre de clients

#### 8.3.2 Gestion des produits

**Fonctionnalités :**
- **Liste des produits** : Affichage de tous les produits avec pagination
- **Ajout de produit** : Formulaire de création
- **Modification** : Édition des informations
- **Suppression** : Suppression avec confirmation
- **Recherche** : Recherche par nom, SKU, code-barres
- **Filtres** : Par catégorie, fournisseur, stock

**Champs d'un produit :**
- Nom
- SKU (référence interne)
- Code-barres
- Catégorie
- Fournisseur
- Prix d'achat
- Prix de vente
- Quantité en stock
- Stock minimum
- Image
- Description

#### 8.3.3 Gestion des catégories

**Fonctionnalités :**
- Créer, modifier, supprimer des catégories
- Associer des produits à des catégories
- Vue d'ensemble des catégories

#### 8.3.4 Gestion des fournisseurs

**Fonctionnalités :**
- Ajouter des fournisseurs
- Modifier les informations
- Associer des produits à des fournisseurs
- Historique des commandes

**Informations :**
- Nom
- Email
- Téléphone
- Adresse

#### 8.3.5 Gestion des stocks

**Fonctionnalités :**
- **Vue d'ensemble** : État global des stocks
- **Entrée de stock** : Ajout de quantités
- **Sortie de stock** : Retrait de quantités
- **Ajustement** : Correction manuelle
- **Alertes** : Produits en dessous du stock minimum
- **Historique** : Tous les mouvements de stock

**Types de mouvements :**
- `in` : Entrée (achat, retour)
- `out` : Sortie (vente, perte)
- `adjustment` : Ajustement manuel

#### 8.3.6 Gestion des clients

**Fonctionnalités :**
- Liste des clients
- Ajout de clients
- Modification des informations
- Historique des achats par client
- Recherche et filtres

**Informations :**
- Nom
- Email
- Téléphone
- Adresse

#### 8.3.7 Gestion des ventes

**Fonctionnalités :**
- **Liste des ventes** : Historique complet
- **Détails d'une vente** : Vue détaillée avec lignes de vente
- **Filtres** : Par date, client, utilisateur, montant
- **Recherche** : Par référence, nom client
- **Annulation** : Possibilité d'annuler une vente

**Informations affichées :**
- Date et heure
- Caissier/Admin
- Client
- Produits vendus
- Montants (sous-total, remise, total)
- Mode de paiement
- Statut

#### 8.3.8 Gestion des employés

**Fonctionnalités :**
- Liste des utilisateurs
- Ajout d'employés (caissiers)
- Modification des rôles
- Activation/Désactivation de comptes
- Historique des connexions

**Rôles disponibles :**
- Admin : Accès complet
- Caissier : Accès limité aux ventes

#### 8.3.9 Rapports et statistiques

**Types de rapports :**
- **Ventes** : Par période, par produit, par client
- **Stocks** : État des stocks, mouvements
- **Clients** : Meilleurs clients, nouveaux clients
- **Employés** : Performance des caissiers
- **Financiers** : Chiffre d'affaires, bénéfices, marges

**Périodes :**
- Aujourd'hui
- Cette semaine
- Ce mois
- Cette année
- Période personnalisée

**Formats :**
- Graphiques (Chart.js)
- Tableaux exportables
- PDF (si implémenté)

#### 8.3.10 Gestion de la caisse

**Fonctionnalités :**
- **Ouverture de caisse** : Définir le montant initial
- **Fermeture de caisse** : Calculer le montant final
- **Différence de caisse** : Comparaison montant attendu vs réel
- **Historique** : Toutes les sessions de caisse
- **Rapport de clôture** : Détail de la session

#### 8.3.11 Paramètres

**Configuration :**
- Informations du commerce
- Paramètres fiscaux (taxes)
- Paramètres d'impression
- Préférences utilisateur
- Notifications

#### 8.3.12 Dépenses

**Fonctionnalités :**
- Enregistrement des dépenses
- Catégorisation
- Historique
- Rapports de dépenses

---

## 9. Dashboard Caissier

### 9.1 Rôle

Le Dashboard Caissier est une interface simplifiée et optimisée pour la saisie rapide des ventes.

### 9.2 Accès

**URL :** `/cashier/dashboard`  
**Rôle requis :** cashier  
**Middleware :** `auth` + `role:cashier`

### 9.3 Fonctionnalités principales

#### 9.3.1 Création d'une vente

**Processus simplifié :**

1. **Recherche de produit**
   - Par nom
   - Par code-barres (scan)
   - Par référence (SKU)

2. **Ajout au panier**
   - Sélection de la quantité
   - Ajout automatique au panier

3. **Gestion du panier**
   - Modification des quantités
   - Suppression d'articles
   - Application de remises

4. **Calcul automatique**
   - Sous-total
   - Remises
   - Total

5. **Paiement**
   - Sélection du mode de paiement
   - Saisie du montant payé
   - Calcul de la monnaie à rendre

6. **Validation**
   - Création de la vente
   - Déduction automatique du stock
   - Impression du ticket

#### 9.3.2 Recherche produit

**Méthodes de recherche :**
- **Saisie manuelle** : Par nom ou référence
- **Scan code-barres** : Utilisation d'un lecteur de code-barres
- **Liste des produits** : Affichage de tous les produits disponibles

**Affichage :**
- Nom du produit
- Prix de vente
- Stock disponible
- Image (si disponible)

#### 9.3.3 Scan code-barres

**Fonctionnement :**
- Le lecteur de code-barres simule une saisie clavier
- Le champ de recherche détecte le code-barres
- Recherche automatique du produit
- Ajout au panier si trouvé

**Avantages :**
- Rapidité de saisie
- Réduction des erreurs
- Optimisation du temps de traitement

#### 9.3.4 Ajout au panier

**Processus :**
1. Recherche du produit
2. Sélection de la quantité
3. Ajout automatique au panier
4. Mise à jour du total

**Affichage du panier :**
- Liste des articles
- Quantité par article
- Prix unitaire
- Sous-total par article
- Total général

#### 9.3.5 Calcul automatique

**Calculs effectués :**
- Sous-total = Σ (quantité × prix unitaire)
- Remise = Montant ou pourcentage
- Total = Sous-total - Remise
- Monnaie = Montant payé - Total

**Mise à jour en temps réel :**
- Dès qu'un article est ajouté/modifié
- Dès qu'une remise est appliquée

#### 9.3.6 Paiement

**Modes de paiement :**
- **Espèces (cash)** : Calcul de la monnaie
- **Carte bancaire** : Référence de transaction
- **Mobile Money** : Référence de transaction
- **Mixte** : Plusieurs modes de paiement

**Informations enregistrées :**
- Montant total
- Montant payé
- Monnaie rendue
- Mode de paiement
- Référence (si applicable)

#### 9.3.7 Impression ticket

**Contenu du ticket :**
- Nom du commerce
- Date et heure
- Numéro de vente
- Liste des articles (quantité, prix, sous-total)
- Sous-total
- Remise
- Total
- Mode de paiement
- Montant payé
- Monnaie rendue
- Message de remerciement

**Format :** Thermal printer (80mm)

#### 9.3.8 Historique

**Affichage :**
- Ventes du jour
- Ventes de la semaine
- Recherche par date
- Détail de chaque vente

**Informations :**
- Date et heure
- Numéro de vente
- Montant total
- Mode de paiement

#### 9.3.9 Retour produit

**Processus :**
1. Recherche de la vente originale
2. Sélection des articles à retourner
3. Validation du retour
4. Création d'un mouvement de stock (entrée)
5. Remboursement (si applicable)

**Conditions :**
- Vente du jour ou période configurable
- Produit retournable
- Validation par un admin (si nécessaire)

### 9.4 Limitations des permissions

**Le caissier ne peut pas :**
- Accéder aux paramètres
- Gérer les produits (créer, modifier, supprimer)
- Gérer les stocks (hors ventes)
- Accéder aux rapports complets
- Gérer les employés
- Accéder aux fournisseurs
- Gérer les clients (selon configuration)

**Le caissier peut :**
- Créer des ventes
- Consulter l'historique de ses ventes
- Rechercher des produits
- Scanner des code-barres
- Imprimer des tickets

---

## 10. Logique complète d'une vente

### 10.1 Schéma du processus

```
┌─────────────────────────────────────────────────────────────┐
│ 1. ARRIVÉE DU CLIENT                                         │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. OUVERTURE DE SESSION DE CAISSE                            │
│    - Vérification session ouverte                             │
│    - Si non : ouverture avec montant initial                  │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. RECHERCHE DE PRODUIT                                      │
│    - Saisie nom / scan code-barres / SKU                     │
│    - Affichage des résultats                                  │
│    - Sélection du produit                                     │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. AJOUT AU PANIER                                           │
│    - Saisie de la quantité                                    │
│    - Vérification du stock disponible                         │
│    - Ajout à la liste des articles                            │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. CALCUL AUTOMATIQUE                                        │
│    - Sous-total = Σ (quantité × prix unitaire)               │
│    - Remise (si applicable)                                   │
│    - Total = Sous-total - Remise                              │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. GESTION DU PANIER                                         │
│    - Modification des quantités                               │
│    - Suppression d'articles                                   │
│    - Application de remises globales                          │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. PAIEMENT                                                  │
│    - Sélection du mode de paiement                            │
│    - Saisie du montant payé                                   │
│    - Calcul de la monnaie à rendre                            │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 8. VALIDATION DE LA VENTE                                    │
│    - Vérification du stock                                    │
│    - Création de l'enregistrement sale                        │
│    - Création des lignes de vente (sale_items)                │
│    - Déduction automatique du stock                            │
│    - Création des mouvements de stock                          │
│    - Création du paiement                                     │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 9. ENREGISTREMENT EN BASE DE DONNÉES                        │
│    - INSERT INTO sales                                       │
│    - INSERT INTO sale_items (pour chaque produit)            │
│    - UPDATE products SET stock_quantity = stock - quantité   │
│    - INSERT INTO stock_movements                             │
│    - INSERT INTO payments                                    │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 10. IMPRESSION DU TICKET                                     │
│     - Génération du contenu                                   │
│     - Envoi à l'imprimante thermique                          │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│ 11. FIN                                                      │
│     - Réinitialisation du panier                              │
│     - Prêt pour la prochaine vente                            │
└─────────────────────────────────────────────────────────────┘
```

### 10.2 Étapes détaillées

#### Étape 1 : Arrivée du client

Le client se présente à la caisse avec ses articles.

#### Étape 2 : Ouverture de session de caisse

**Vérifications :**
- Le caissier est-il connecté ?
- Une session de caisse est-elle ouverte ?

**Si non :**
- Saisie du montant initial (fonds de caisse)
- Création de la session dans `cash_sessions`
- Enregistrement de la date/heure d'ouverture

#### Étape 3 : Recherche de produit

**Méthodes :**
1. **Saisie manuelle** : Le caissier tape le nom du produit
2. **Scan code-barres** : Le lecteur scanne le code-barres
3. **SKU** : Recherche par référence interne

**Requête SQL :**
```sql
SELECT * FROM products 
WHERE name LIKE '%search%' 
   OR barcode = 'search' 
   OR sku = 'search'
   AND stock_quantity > 0
```

**Affichage :**
- Nom du produit
- Prix de vente
- Stock disponible
- Image (si disponible)

#### Étape 4 : Ajout au panier

**Actions :**
1. Le caissier sélectionne le produit
2. Saisie de la quantité
3. Vérification du stock disponible
4. Ajout au panier

**Validation :**
```php
if ($quantity > $product->stock_quantity) {
    return "Stock insuffisant";
}
```

#### Étape 5 : Calcul automatique

**Formules :**
```
Sous-total = Σ (quantité × prix_unitaire)
Remise = montant_remise ou (sous-total × pourcentage_remise / 100)
Total = Sous-total - Remise
```

**Mise à jour en temps réel :**
- À chaque ajout/modification/suppression d'article
- À chaque modification de remise

#### Étape 6 : Gestion du panier

**Actions possibles :**
- Modifier la quantité d'un article
- Supprimer un article
- Appliquer une remise globale
- Vider le panier

**Recalcul automatique :**
- Après chaque modification

#### Étape 7 : Paiement

**Modes de paiement :**
- **Espèces** : Saisie du montant payé, calcul de la monnaie
- **Carte** : Saisie de la référence de transaction
- **Mobile Money** : Saisie de la référence
- **Mixte** : Plusieurs modes de paiement

**Calculs :**
```
Monnaie = Montant payé - Total
```

#### Étape 8 : Validation de la vente

**Vérifications :**
- Stock toujours disponible (verrouillage)
- Montants corrects
- Informations complètes

**Création des enregistrements :**

1. **Sale (vente)**
```php
Sale::create([
    'user_id' => auth()->id(),
    'customer_id' => $customerId,
    'total' => $total,
    'subtotal' => $subtotal,
    'discount' => $discount,
    'payment_method' => $paymentMethod,
    'amount_paid' => $amountPaid,
    'change' => $change,
    'status' => 'completed'
]);
```

2. **SaleItems (lignes de vente)**
```php
foreach ($cart as $item) {
    SaleItem::create([
        'sale_id' => $sale->id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'unit_price' => $item['price'],
        'subtotal' => $item['quantity'] * $item['price']
    ]);
}
```

3. **Mise à jour du stock**
```php
$product->decrement('stock_quantity', $quantity);
```

4. **StockMovement (mouvement de stock)**
```php
StockMovement::create([
    'product_id' => $product->id,
    'user_id' => auth()->id(),
    'type' => 'out',
    'quantity' => $quantity,
    'reason' => "Vente #{$sale->id}"
]);
```

5. **Payment (paiement)**
```php
Payment::create([
    'sale_id' => $sale->id,
    'amount' => $amountPaid,
    'method' => $paymentMethod,
    'reference' => $reference
]);
```

#### Étape 9 : Enregistrement en base de données

**Transactions :**
Toutes les opérations sont effectuées dans une transaction pour garantir l'intégrité des données.

```php
DB::transaction(function () use ($data) {
    // Création de la vente
    // Création des lignes de vente
    // Mise à jour des stocks
    // Création des mouvements de stock
    // Création des paiements
});
```

#### Étape 10 : Impression du ticket

**Génération du ticket :**
- Récupération des données de la vente
- Formatage pour l'imprimante thermique (80mm)
- Envoi à l'imprimante

**Contenu :**
```
╔══════════════════════════════════════╗
║     FastCaisse                      ║
║     123 Rue du Commerce             ║
║     Tél: 01 23 45 67 89             ║
╠══════════════════════════════════════╣
║ Date: 15/01/2025 14:30              ║
║ Vente #: 1234                       ║
╠══════════════════════════════════════╣
║ Produit               Qté  Prix     ║
║──────────────────────────────────────║
║ Produit 1              2   10.00     ║
║ Produit 2              1   15.00     ║
╠══════════════════════════════════════╣
║ Sous-total:              25.00       ║
║ Remise:                   5.00       ║
║ TOTAL:                    20.00      ║
╠══════════════════════════════════════╣
║ Paiement: Espèces        25.00       ║
║ Monnaie:                  5.00       ║
╠══════════════════════════════════════╣
║ Merci de votre visite !             ║
╚══════════════════════════════════════╝
```

#### Étape 11 : Fin

**Actions :**
- Réinitialisation du panier
- Affichage du message de succès
- Prêt pour la prochaine vente

---

## 11. Gestion des stocks

### 11.1 Entrée de stock

**Définition :** Augmentation de la quantité d'un produit en stock.

**Causes :**
- Réception de marchandise (achat fournisseur)
- Retour client
- Correction d'inventaire

**Processus :**
1. Sélection du produit
2. Saisie de la quantité ajoutée
3. Saisie de la raison
4. Mise à jour du stock
5. Création d'un mouvement de stock de type `in`

**Exemple SQL :**
```sql
UPDATE products SET stock_quantity = stock_quantity + 100 WHERE id = 1;
INSERT INTO stock_movements (product_id, user_id, type, quantity, reason) 
VALUES (1, 5, 'in', 100, 'Réception fournisseur');
```

### 11.2 Sortie de stock

**Définition :** Diminution de la quantité d'un produit en stock.

**Causes :**
- Vente
- Perte/Casse
- Vol
- Don

**Processus :**
1. Sélection du produit
2. Saisie de la quantité retirée
3. Saisie de la raison
4. Mise à jour du stock
5. Création d'un mouvement de stock de type `out`

**Exemple SQL :**
```sql
UPDATE products SET stock_quantity = stock_quantity - 5 WHERE id = 1;
INSERT INTO stock_movements (product_id, user_id, type, quantity, reason) 
VALUES (1, 5, 'out', 5, 'Vente #1234');
```

### 11.3 Ajustement

**Définition :** Correction manuelle du stock.

**Causes :**
- Inventaire
- Correction d'erreur
- Constat de différence

**Processus :**
1. Sélection du produit
2. Saisie de la nouvelle quantité
3. Saisie de la raison
4. Mise à jour du stock
5. Création d'un mouvement de stock de type `adjustment`

**Exemple SQL :**
```sql
UPDATE products SET stock_quantity = 50 WHERE id = 1;
INSERT INTO stock_movements (product_id, user_id, type, quantity, reason) 
VALUES (1, 5, 'adjustment', 50, 'Inventaire mensuel');
```

### 11.4 Inventaire

**Définition :** Comptage physique de tous les produits en stock.

**Processus :**
1. Génération de la liste des produits
2. Comptage physique de chaque produit
3. Saisie des quantités comptées
4. Comparaison avec les quantités système
5. Création d'ajustements pour les différences
6. Génération du rapport d'inventaire

**Rapport d'inventaire :**
- Produits conformes
- Produits en surstock
- Produits en sous-stock
- Écart total (valeur)

### 11.5 Alertes

**Définition :** Notifications automatiques quand un produit atteint le stock minimum.

**Configuration :**
- Chaque produit a un `min_stock` (stock minimum)
- Alerte quand `stock_quantity <= min_stock`

**Affichage :**
- Badge d'alerte dans la liste des produits
- Section dédiée aux alertes
- Notification dans le dashboard

**Exemple :**
```php
$lowStockProducts = Product::where('stock_quantity', '<=', 'min_stock')->get();
```

### 11.6 Stock minimum

**Définition :** Seuil en dessous duquel un produit doit être réapprovisionné.

**Configuration :**
- Défini par produit
- Modifiable par l'admin
- Utilisé pour les alertes

**Exemple :**
```php
$product->min_stock = 10;
$product->stock_quantity = 5;
// Alerte : stock bas !
```

### 11.7 Historique

**Affichage :**
- Tous les mouvements de stock
- Filtrés par produit, date, type, utilisateur
- Tri par date décroissante

**Informations :**
- Date et heure
- Produit
- Type (in, out, adjustment)
- Quantité
- Utilisateur
- Raison

**Utilité :**
- Traçabilité complète
- Détection d'anomalies
- Analyse des tendances

---

## 12. Fonctionnement des statistiques

### 12.1 Objectif

Fournir des indicateurs clés de performance (KPI) pour aider à la prise de décision.

### 12.2 Indicateurs calculés

#### 12.2.1 Chiffre d'affaires

**Définition :** Somme totale des ventes sur une période.

**Calcul :**
```sql
SELECT SUM(total) FROM sales 
WHERE status = 'completed' 
  AND created_at BETWEEN '2025-01-01' AND '2025-01-31'
```

**Affichage :**
- Par jour
- Par semaine
- Par mois
- Par année
- Par période personnalisée

#### 12.2.2 Nombre de ventes

**Définition :** Nombre total de transactions sur une période.

**Calcul :**
```sql
SELECT COUNT(*) FROM sales 
WHERE status = 'completed' 
  AND created_at BETWEEN '2025-01-01' AND '2025-01-31'
```

#### 12.2.3 Produits les plus vendus

**Définition :** Classement des produits par quantité vendue.

**Calcul :**
```sql
SELECT p.name, SUM(si.quantity) as total_qty
FROM sale_items si
JOIN products p ON si.product_id = p.id
JOIN sales s ON si.sale_id = s.id
WHERE s.status = 'completed'
  AND s.created_at BETWEEN '2025-01-01' AND '2025-01-31'
GROUP BY p.id, p.name
ORDER BY total_qty DESC
LIMIT 10
```

**Affichage :**
- Top 10 des produits
- Graphique à barres
- Pourcentage du total

#### 12.2.4 Clients actifs

**Définition :** Nombre de clients ayant effectué au moins un achat.

**Calcul :**
```sql
SELECT COUNT(DISTINCT customer_id) FROM sales 
WHERE status = 'completed' 
  AND created_at BETWEEN '2025-01-01' AND '2025-01-31'
```

#### 12.2.5 Marge bénéficiaire

**Définition :** Différence entre le prix de vente et le prix d'achat.

**Calcul :**
```
Marge = Prix de vente - Prix d'achat
Marge (%) = (Marge / Prix d'achat) × 100
```

**Requête :**
```sql
SELECT 
    p.name,
    SUM(si.quantity) as qty_sold,
    SUM(si.quantity * (si.unit_price - p.purchase_price)) as profit
FROM sale_items si
JOIN products p ON si.product_id = p.id
JOIN sales s ON si.sale_id = s.id
WHERE s.status = 'completed'
GROUP BY p.id, p.name
```

#### 12.2.6 Évolution mensuelle

**Définition :** Évolution du chiffre d'affaires mois par mois.

**Calcul :**
```sql
SELECT 
    MONTH(created_at) as month,
    SUM(total) as revenue
FROM sales
WHERE status = 'completed'
  AND YEAR(created_at) = 2025
GROUP BY MONTH(created_at)
ORDER BY month
```

**Affichage :**
- Graphique linéaire
- Comparaison avec période précédente
- Pourcentage de croissance/décroissance

### 12.3 Outils utilisés

#### Chart.js

**Rôle :** Bibliothèque JavaScript pour les graphiques.

**Types de graphiques :**
- **Ligne** : Évolution dans le temps
- **Barre** : Comparaisons
- **Camembert** : Répartitions
- **Doughnut** : Parts de marché

**Exemple d'utilisation :**
```javascript
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Chiffre d\'affaires',
            data: [12000, 15000, 14000, 18000, 20000, 22000],
            borderColor: '#16a34a',
            tension: 0.4
        }]
    }
});
```

#### Eloquent ORM

**Rôle :** Requêtes sur la base de données.

**Exemples :**
```php
// Ventes du jour
$todaySales = Sale::whereDate('created_at', today())->get();

// Produits les plus vendus
$topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total'))
    ->with('product')
    ->groupBy('product_id')
    ->orderByDesc('total')
    ->take(10)
    ->get();
```

#### Collections Laravel

**Rôle :** Manipulation des données en PHP.

**Exemples :**
```php
// Calcul du chiffre d'affaires
$revenue = $sales->sum('total');

// Produit le plus vendu
$topProduct = $saleItems->groupBy('product_id')
    ->map(function ($items) {
        return $items->sum('quantity');
    })
    ->sortDesc()
    ->first();
```

#### Agrégations SQL

**Fonctions utilisées :**
- `SUM()` : Somme
- `COUNT()` : Comptage
- `AVG()` : Moyenne
- `MAX()` : Maximum
- `MIN()` : Minimum
- `GROUP BY` : Regroupement

**Exemple :**
```sql
SELECT 
    DATE(created_at) as date,
    COUNT(*) as sales_count,
    SUM(total) as revenue,
    AVG(total) as average_sale
FROM sales
WHERE created_at >= '2025-01-01'
GROUP BY DATE(created_at)
ORDER BY date
```

### 12.4 Logique des requêtes

#### Chiffre d'affaires par période

**Périodes disponibles :**
- Aujourd'hui
- Cette semaine
- Ce mois
- Cette année
- Période personnalisée

**Logique :**
```php
// Aujourd'hui
$startDate = today();
$endDate = today();

// Cette semaine
$startDate = now()->startOfWeek();
$endDate = now()->endOfWeek();

// Ce mois
$startDate = now()->startOfMonth();
$endDate = now()->endOfMonth();

// Requête
$revenue = Sale::whereBetween('created_at', [$startDate, $endDate])
    ->where('status', 'completed')
    ->sum('total');
```

#### Nombre de ventes par heure

**Utilité :** Identifier les heures de pointe.

**Logique :**
```php
$salesByHour = Sale::select(
        DB::raw('HOUR(created_at) as hour'),
        DB::raw('COUNT(*) as count')
    )
    ->whereDate('created_at', today())
    ->groupBy('hour')
    ->orderBy('hour')
    ->get();
```

#### Taux de conversion

**Définition :** Pourcentage de visiteurs qui effectuent un achat.

**Calcul :**
```
Taux de conversion = (Nombre de ventes / Nombre de visiteurs) × 100
```

---

## 13. Fonctionnement des courbes

### 13.1 Principe

Les courbes affichent l'évolution des données commerciales dans le temps.

### 13.2 Collecte des données

**Sources :**
- Table `sales` : Ventes
- Table `sale_items` : Détails des ventes
- Table `products` : Catalogue
- Table `stock_movements` : Mouvements de stock

**Requêtes Eloquent :**
```php
// Ventes par jour pour le mois en cours
$salesByDay = Sale::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total) as revenue'),
        DB::raw('COUNT(*) as count')
    )
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->where('status', 'completed')
    ->groupBy('date')
    ->orderBy('date')
    ->get();
```

### 13.3 Traitement

**Étapes :**
1. Récupération des données brutes
2. Formatage pour Chart.js
3. Calcul des agrégations
4. Gestion des valeurs manquantes (0 si pas de vente)

**Exemple :**
```php
// Formater pour Chart.js
$labels = $salesByDay->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'));
$data = $salesByDay->pluck('revenue');
```

### 13.4 Envoi au frontend

**Méthode :**
- Passage de données via la vue Blade
- Utilisation de `@json()` pour encoder en JSON

**Exemple :**
```blade
<script>
    const salesData = @json($salesByDay);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.date),
            datasets: [{
                label: 'Chiffre d\'affaires',
                data: salesData.map(item => item.revenue)
            }]
        }
    });
</script>
```

### 13.5 Affichage avec Chart.js

**Configuration :**
```javascript
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Chiffre d\'affaires (€)',
            data: data,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22, 163, 74, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
```

### 13.6 Actualisation

**Méthodes :**
- **Manuelle** : Bouton "Actualiser"
- **Automatique** : Actualisation toutes les X secondes/minutes
- **AJAX** : Requête asynchrone sans rechargement de page

**Exemple AJAX :**
```javascript
setInterval(() => {
    fetch('/api/stats/sales')
        .then(response => response.json())
        .then(data => {
            chart.data.datasets[0].data = data.revenue;
            chart.update();
        });
}, 60000); // Toutes les 60 secondes
```

### 13.7 Filtres

**Filtres disponibles :**
- Période (aujourd'hui, semaine, mois, année, personnalisé)
- Par produit
- Par catégorie
- Par client
- Par utilisateur (caissier)

**Implémentation :**
```php
$query = Sale::query();

if ($request->has('period')) {
    $query->whereBetween('created_at', $period);
}

if ($request->has('product_id')) {
    $query->whereHas('items', function ($q) use ($request) {
        $q->where('product_id', $request->product_id);
    });
}

$sales = $query->get();
```

### 13.8 Périodes

#### Aujourd'hui

**Logique :**
```php
$startDate = today()->startOfDay();
$endDate = today()->endOfDay();
```

#### Cette semaine

**Logique :**
```php
$startDate = now()->startOfWeek();
$endDate = now()->endOfWeek();
```

#### Ce mois

**Logique :**
```php
$startDate = now()->startOfMonth();
$endDate = now()->endOfMonth();
```

#### Cette année

**Logique :**
```php
$startDate = now()->startOfYear();
$endDate = now()->endOfYear();
```

#### Période personnalisée

**Logique :**
```php
$startDate = Carbon::parse($request->start_date)->startOfDay();
$endDate = Carbon::parse($request->end_date)->endOfDay();
```

---

## 14. Fonctionnement de la caisse

### 14.1 Ouverture de caisse

**Objectif :** Initialiser une session de caisse avec un montant de départ.

**Processus :**
1. Le caissier se connecte
2. Vérification : une session est-elle déjà ouverte ?
3. Si non :
   - Saisie du montant initial (fonds de caisse)
   - Création de la session
   - Enregistrement de la date/heure d'ouverture
   - Enregistrement de l'utilisateur

**Données enregistrées :**
```php
CashSession::create([
    'user_id' => auth()->id(),
    'opening_amount' => $openingAmount,
    'opened_at' => now()
]);
```

### 14.2 Fermeture de caisse

**Objectif :** Clôturer une session de caisse et vérifier les comptes.

**Processus :**
1. Le caissier demande la fermeture
2. Saisie du montant réel en caisse
3. Calcul automatique :
   - Montant attendu = Montant initial + Ventes en espèces
   - Différence = Montant réel - Montant attendu
4. Enregistrement de la clôture
5. Génération du rapport de clôture

**Données enregistrées :**
```php
$session->update([
    'closing_amount' => $closingAmount,
    'expected_amount' => $expectedAmount,
    'difference' => $difference,
    'closed_at' => now()
]);
```

### 14.3 Montant initial

**Définition :** Somme d'argent présente dans la caisse au début de la session.

**Usage :**
- Donner la monnaie aux clients
- Fonds de roulement de la caisse

**Exemple :**
- Montant initial : 100€
- Ventes en espèces : 500€
- Montant attendu : 600€

### 14.4 Calcul automatique

**Formules :**
```
Montant attendu = Montant initial + Σ(Ventes en espèces) - Σ(Monnaie rendue)
Différence = Montant réel - Montant attendu
```

**Exemple :**
```
Montant initial : 100€
Ventes en espèces : 500€
Monnaie rendue : 20€
Montant attendu : 100 + 500 - 20 = 580€

Montant réel compté : 590€
Différence : 590 - 580 = +10€ (excédent)
```

### 14.5 Différence de caisse

**Définition :** Écart entre le montant attendu et le montant réel.

**Types :**
- **Positive (+) :** Excédent (plus d'argent que prévu)
- **Négative (-) :** Déficit (moins d'argent que prévu)
- **Nulle (0) :** Caisse conforme

**Causes possibles :**
- Erreur de caisse
- Vol
- Oubli de déclarer une vente
- Erreur de calcul de la monnaie

### 14.6 Validation

**Processus :**
1. Le caissier saisit le montant réel
2. Le système calcule la différence
3. Si différence ≠ 0 :
   - Demande de confirmation
   - Saisie d'une explication (optionnel)
   - Validation par un admin (si nécessaire)
4. Enregistrement de la clôture

### 14.7 Historique

**Affichage :**
- Toutes les sessions de caisse
- Filtrées par utilisateur, date
- Tri par date décroissante

**Informations :**
- Date d'ouverture
- Date de fermeture
- Utilisateur
- Montant initial
- Montant final
- Montant attendu
- Différence
- Durée de la session

### 14.8 Rapport de clôture

**Contenu :**
- Informations de la session
- Montant initial
- Total des ventes en espèces
- Total des ventes par mode de paiement
- Montant attendu
- Montant réel
- Différence
- Liste des ventes de la session
- Signature du caissier

**Format :**
- PDF (si implémenté)
- Impression thermique

### 14.9 Permissions

**Règles :**
- Un seul utilisateur par session de caisse
- Seul l'utilisateur ayant ouvert la session peut la fermer
- Un admin peut forcer la fermeture
- Historique accessible aux admins uniquement

---

## 15. Workflow général

### 15.1 Workflow Administrateur

```
┌─────────────────────────────────────────────────────────────┐
│                    VISITEUR                                  │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  ACCÈS À L'APPLICATION                                      │
│  http://fastcaisse.test                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  PAGE D'ACCUEIL                                             │
│  - Présentation de FastCaisse                                │
│  - Bouton "Se connecter"                                     │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  PAGE DE CONNEXION                                           │
│  - Email                                                      │
│  - Mot de passe                                               │
│  - Bouton "Se connecter"                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  AUTHENTIFICATION                                            │
│  - Vérification des identifiants                              │
│  - Création de la session                                     │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  VÉRIFICATION DU RÔLE                                        │
│  - Si admin → Dashboard Admin                                │
│  - Si cashier → Dashboard Caissier                           │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  DASHBOARD ADMINISTRATEUR                                    │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  SIDEBAR                                             │  │
│  │  - Tableau de bord                                   │  │
│  │  - Produits                                          │  │
│  │  - Catégories                                        │  │
│  │  - Stocks                                            │  │
│  │  - Clients                                           │  │
│  │  - Fournisseurs                                      │  │
│  │  - Employés                                          │  │
│  │  - Ventes                                            │  │
│  │  - Dépenses                                          │  │
│  │  - Rapports                                          │  │
│  │  - Paramètres                                        │  │
│  │  - Déconnexion                                       │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  CONTENU PRINCIPAL                                   │  │
│  │  - Statistiques en temps réel                         │  │
│  │  - Graphiques                                        │  │
│  │  - Alertes                                           │  │
│  │  - Activité récente                                   │  │
│  └──────────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  GESTION DES PRODUITS                                        │
│  - Ajout / Modification / Suppression                        │
│  - Gestion des images                                        │
│  - Attribution catégories/fournisseurs                       │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  GESTION DES STOCKS                                          │
│  - Entrées / Sorties / Ajustements                           │
│  - Alertes de stock minimum                                  │
│  - Historique des mouvements                                 │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  GESTION DES VENTES                                          │
│  - Consultation de l'historique                              │
│  - Détails des ventes                                        │
│  - Annulations (si nécessaire)                               │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  RAPPORTS ET STATISTIQUES                                    │
│  - Ventes par période                                        │
│  - Produits les plus vendus                                  │
│  - Performance des caissiers                                  │
│  - Évolution du chiffre d'affaires                           │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  GESTION DES EMPLOYÉS                                        │
│  - Ajout de caissiers                                        │
│  - Modification des rôles                                    │
│  - Activation/Désactivation                                   │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  DÉCONNEXION                                                 │
│  - Destruction de la session                                  │
│  - Redirection vers la page de login                          │
└─────────────────────────────────────────────────────────────┘
```

### 15.2 Workflow Caissier

```
┌─────────────────────────────────────────────────────────────┐
│                    VISITEUR                                  │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  PAGE DE CONNEXION                                           │
│  - Email                                                      │
│  - Mot de passe                                               │
│  - Bouton "Se connecter"                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  AUTHENTIFICATION                                            │
│  - Vérification des identifiants                              │
│  - Création de la session                                     │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  DASHBOARD CAISSIER                                          │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  INTERFACE DE VENTE                                   │  │
│  │  ┌────────────────┐  ┌──────────────────────────┐   │  │
│  │  │ Recherche      │  │ Panier                    │   │  │
│  │  │ produit        │  │ - Article 1   2x 10€     │   │  │
│  │  │ [___________]  │  │ - Article 2   1x 15€     │   │  │
│  │  │                │  │                           │   │  │
│  │  │ Résultats:     │  │ Sous-total: 35€          │   │  │
│  │  │ - Produit 1    │  │ Remise: 5€               │   │  │
│  │  │ - Produit 2    │  │ TOTAL: 30€               │   │  │
│  │  │ - Produit 3    │  │                           │   │  │
│  │  └────────────────┘  └──────────────────────────┘   │  │
│  │                                                      │  │
│  │  [Scanner] [Valider] [Annuler]                       │  │
│  └──────────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  RECHERCHE DE PRODUIT                                        │
│  - Saisie du nom / scan code-barres                          │
│  - Affichage des résultats                                   │
│  - Sélection du produit                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  AJOUT AU PANIER                                             │
│  - Saisie de la quantité                                     │
│  - Vérification du stock                                     │
│  - Ajout à la liste                                          │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  CALCUL AUTOMATIQUE                                          │
│  - Mise à jour du sous-total                                 │
│  - Application des remises                                   │
│  - Calcul du total                                           │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  PAIEMENT                                                    │
│  - Sélection du mode de paiement                              │
│  - Saisie du montant payé                                    │
│  - Calcul de la monnaie                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  VALIDATION                                                  │
│  - Création de la vente                                      │
│  - Déduction du stock                                        │
│  - Impression du ticket                                      │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  HISTORIQUE DES VENTES                                       │
│  - Consultation des ventes du jour                            │
│  - Recherche par date                                        │
│  - Détail d'une vente                                        │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ↓
┌─────────────────────────────────────────────────────────────┐
│  DÉCONNEXION                                                 │
│  - Destruction de la session                                  │
│  - Redirection vers la page de login                          │
└─────────────────────────────────────────────────────────────┘
```

---

## 16. Évolutions futures

### 16.1 Paiement Mobile Money

**Description :** Intégration des paiements Mobile Money (Orange Money, Wave, etc.)

**Bénéfices :**
- Plus de flexibilité pour les clients
- Paiements sans espèces
- Traçabilité des transactions

**Implémentation :**
- Intégration API Mobile Money
- Génération de QR codes
- Confirmation automatique des paiements

### 16.2 Multi-boutiques

**Description :** Gestion de plusieurs boutiques depuis une seule interface.

**Fonctionnalités :**
- Création de multiples boutiques
- Gestion des stocks par boutique
- Transferts entre boutiques
- Statistiques par boutique
- Utilisateurs par boutique

**Modifications nécessaires :**
- Ajout table `shops`
- Ajout colonne `shop_id` dans les tables concernées
- Middleware de filtrage par boutique

### 16.3 API REST

**Description :** Exposition d'une API REST pour intégrations externes.

**Bénéfices :**
- Intégration avec des applications tierces
- Développement d'applications mobiles
- Automatisation des processus

**Technologies :**
- Laravel Sanctum (authentification API)
- Resources API (transformation des données)
- Documentation avec Swagger/OpenAPI

### 16.4 Application mobile Flutter

**Description :** Application mobile native pour caissiers et admins.

**Fonctionnalités :**
- Ventes hors ligne
- Synchronisation automatique
- Notifications push
- Scan de code-barres via caméra

**Technologies :**
- Flutter (Dart)
- API REST Laravel
- SQLite local
- Synchronisation différée

### 16.5 Mode hors ligne

**Description :** Fonctionnement sans connexion internet.

**Fonctionnalités :**
- Ventes en mode hors ligne
- Stockage local des données
- Synchronisation automatique quand connexion rétablie
- Gestion des conflits

**Technologies :**
- Service Workers
- IndexedDB
- Background Sync API

### 16.6 Synchronisation

**Description :** Synchronisation des données entre plusieurs appareils.

**Cas d'usage :**
- Multi-boutiques
- Application mobile + web
- Plusieurs caissiers simultanés

**Stratégies :**
- Synchronisation temps réel (WebSockets)
- Synchronisation différée (CRON)
- Résolution de conflits

### 16.7 Notifications

**Description :** Système de notifications en temps réel.

**Types :**
- Alertes de stock bas
- Nouvelles ventes
- Alertes de caisse
- Messages système

**Canaux :**
- Interface web
- Email
- SMS (si nécessaire)
- Push notifications (mobile)

**Technologies :**
- Laravel Notifications
- Pusher (WebSockets)
- Firebase (push notifications)

### 16.8 Sauvegardes automatiques

**Description :** Système de sauvegarde automatique de la base de données.

**Fonctionnalités :**
- Sauvegardes quotidiennes
- Sauvegardes hebdomadaires
- Rétention configurable
- Téléchargement des sauvegardes
- Restauration en un clic

**Technologies :**
- Laravel Backup (spatie/laravel-backup)
- Stockage cloud (S3, Google Drive)
- Compression et chiffrement

### 16.9 Multi-langues

**Description :** Support de plusieurs langues.

**Langues :**
- Français (par défaut)
- Anglais
- Autres langues africaines

**Implémentation :**
- Laravel Localization
- Fichiers de traduction
- Sélecteur de langue
- Détection automatique

### 16.10 Codes QR

**Description :** Génération et lecture de codes QR.

**Utilisations :**
- Codes-barres 2D pour les produits
- Paiement par QR code
- Partage d'informations

**Technologies :**
- Bibliothèque de génération QR
- Scan via caméra (mobile)
- Intégration avec paiements Mobile Money

---

## 17. Conclusion

### 17.1 Résumé des objectifs

FastCaisse est une application web moderne de gestion de caisse qui répond aux besoins des commerces de toute taille. Elle offre :

- ✅ **Interface intuitive** : Facile à utiliser pour les caissiers
- ✅ **Gestion complète** : Produits, stocks, ventes, clients
- ✅ **Sécurité renforcée** : Authentification, rôles, permissions
- ✅ **Statistiques avancées** : Aide à la décision
- ✅ **Architecture évolutive** : Facile à maintenir et étendre

### 17.2 Architecture évolutive

**Points forts :**
- **Modulaire** : Chaque fonctionnalité est indépendante
- **Maintenable** : Code propre et bien organisé
- **Extensible** : Facile d'ajouter de nouvelles fonctionnalités
- **Testable** : Architecture permettant les tests automatisés
- **Documentée** : Code commenté et documentation complète

**Technologies modernes :**
- Laravel 9.x (framework robuste)
- Blade (templating élégant)
- Tailwind CSS (design moderne)
- MySQL (base de données fiable)
- Chart.js (visualisations interactives)

### 17.3 Bonnes pratiques Laravel utilisées

✅ **Architecture MVC** : Séparation des responsabilités  
✅ **Eloquent ORM** : Requêtes élégantes et sécurisées  
✅ **Validation** : Protection contre les données invalides  
✅ **Middleware** : Filtrage des requêtes  
✅ **Migration** : Gestion versionnée de la BDD  
✅ **Seeders** : Données de test  
✅ **Factories** : Génération de données factices  
✅ **Services** : Logique métier réutilisable  
✅ **Form Requests** : Validation externalisée  
✅ **Resources API** : Transformation des données  
✅ **Events & Listeners** : Architecture événementielle  
✅ **Notifications** : Communication avec les utilisateurs  
✅ **Queues** : Traitement asynchrone (si implémenté)  
✅ **Tests** : Tests unitaires et fonctionnels  
✅ **Documentation** : Code commenté et README  

### 17.4 Perspectives

FastCaisse est conçu pour évoluer avec les besoins des utilisateurs. Les fonctionnalités futures prévues (Mobile Money, multi-boutiques, API REST, application mobile) permettront de transformer FastCaisse en une plateforme complète de gestion commerciale.

**Vision à long terme :**
- Devenir le leader des solutions de caisse en Afrique
- Offrir un écosystème complet (POS, gestion, comptabilité, e-commerce)
- Support multi-devices (web, mobile, tablette)
- Intelligence artificielle pour les recommandations
- Analyse prédictive des ventes

---

## Annexes

### A. Glossaire

**Termes techniques :**

- **POS (Point of Sale)** : Système de caisse enregistreuse
- **MVC** : Modèle-Vue-Contrôleur (architecture)
- **ORM** : Mapping Objet-Relationnel (Eloquent)
- **CSRF** : Protection contre les attaques Cross-Site Request Forgery
- **XSS** : Cross-Site Scripting (injection de scripts)
- **SQL Injection** : Injection de code SQL malveillant
- **API** : Interface de Programmation d'Application
- **CRUD** : Create, Read, Update, Delete (opérations de base)
- **RBAC** : Contrôle d'Accès Basé sur les Rôles
- **KPI** : Indicateur Clé de Performance
- **SKU** : Stock Keeping Unit (référence produit)
- **Barcode** : Code-barres

### B. Ressources

**Documentation officielle :**
- [Laravel](https://laravel.com/docs)
- [MySQL](https://dev.mysql.com/doc/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Chart.js](https://www.chartjs.org/docs/)
- [Font Awesome](https://fontawesome.com/docs)

**Outils de développement :**
- [Laravel Pint](https://laravel.com/docs/pint) - Formatage de code
- [Laravel Tinker](https://laravel.com/docs/tinker) - REPL interactif
- [PHPUnit](https://phpunit.de/) - Tests unitaires

### C. Contact

**Équipe FastCaisse**
- Email : contact@fastcaisse.com
- GitHub : https://github.com/talaoux/fastcaisse
- Documentation : https://docs.fastcaisse.com

---

**Fin de la documentation**

*Document généré le 15 juillet 2025*
*Version 1.0*
