# AtelierCouture

Plateforme de gestion de commandes pour atelier de couture artisanal. MVP complet avec catalogue, commandes, mesures, portfolio, et suivi client.

## Stack technique

- **Backend** : PHP 8.3 + Laravel 12
- **Base de donnees** : MySQL 8
- **Frontend** : Blade + TailwindCSS 3.4
- **Interactivite** : Alpine.js 3
- **Build** : Vite 6
- **Stockage images** : Cloudflare R2 (compatible S3)

## Fonctionnalites

1. **Catalogue de modeles** - Gestion des modeles de vetements avec photos, prix de base et description
2. **Categories de modeles** - Organisation hierarchique du catalogue par categories
3. **Gestion des clients** - Fiches clients avec coordonnees et consentement RGPD
4. **Commandes** - Creation et suivi de commandes (standard, retouche, sur-mesure)
5. **Mesures clients** - Saisie et historique des mensurations par client
6. **Accessoires** - Gestion des accessoires ajoutables aux commandes (boutons, fermetures, tissus)
7. **Portfolio** - Galerie publique des realisations avec photos avant/apres
8. **Suivi client** - Espace client avec suivi de l'avancement des commandes
9. **Dashboard admin** - Tableau de bord avec statistiques, commandes recentes, rappels
10. **Systeme de rappels** - Rappels automatiques et manuels (mesures, essayage, livraison)
11. **Prix dynamique** - Calcul automatique du prix selon modele + accessoires selectionnes
12. **Prix final manuel** - Possibilite pour le couturier de fixer un prix final different du calcul automatique

## Architecture

Le projet suit le pattern **Controller -> Service -> Repository -> Model** :

- **Controllers** : Recoivent les requetes HTTP, delegent la logique aux services
- **Services** : Contiennent la logique metier (CommandeService, TarificationService, etc.)
- **Repositories** : Abstraient l'acces aux donnees via des interfaces (Contracts) et implementations Eloquent
- **Models** : Representent les entites de la base de donnees avec relations Eloquent

Composants additionnels :
- **Enums** : Enumeration PHP pour les statuts et types (OrderStatus, OrderType, ReminderType, UserRole)
- **Events/Listeners** : Evenements metier (creation commande, changement de statut) avec listeners pour les effets secondaires (rappels, logs)
- **Form Requests** : Validation des donnees entrantes dans des classes dediees
- **Policies** : Autorisation fine par ressource (ClientPolicy, OrderPolicy, MeasurementPolicy)

## Prerequisites

- PHP 8.3+ avec extensions : mbstring, xml, curl, mysql (pdo_mysql), json, openssl, tokenizer, bcmath
- Composer 2.x
- Node.js 20+ et npm
- MySQL 8.0+

## Installation

1. **Cloner le depot**
```bash
git clone <url> atelier-couture
cd atelier-couture
```

2. **Installer les dependances PHP**
```bash
composer install
```

3. **Installer les dependances JavaScript**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Creer la base de donnees MySQL**
```sql
CREATE DATABASE ateliercouture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Ou en ligne de commande :
```bash
mysql -u root -p2025 -e "CREATE DATABASE ateliercouture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

6. **Configurer le fichier .env**

Les valeurs par defaut dans `.env.example` sont pre-configurees pour le developpement local (utilisateur `root`, mot de passe `2025`). Verifiez simplement qu'elles correspondent a votre configuration MySQL locale.

7. **Executer les migrations et seeders**
```bash
php artisan migrate --seed
```

8. **Creer le lien symbolique de stockage**
```bash
php artisan storage:link
```

9. **Compiler les assets frontend**
```bash
npm run build
```

10. **Lancer le serveur de developpement**
```bash
php artisan serve
```

L'application est accessible sur [http://localhost:8000](http://localhost:8000)

## Compte de demonstration

| Role | Email | Mot de passe |
|------|-------|--------------|
| Admin (couturier) | admin@ateliercouture.test | password |

Apres connexion, l'administrateur est redirige vers `/admin` (dashboard).

## Stockage Cloudflare R2

Les images (modeles, portfolio) sont stockees sur Cloudflare R2. Le fichier `.env.example` contient les identifiants R2 pre-configures. Le disque `r2` est configure dans `config/filesystems.php` en utilisant le driver S3 compatible.

Variables d'environnement requises :
```
CLOUDFLARE_R2_ACCESS_KEY_ID=
CLOUDFLARE_R2_SECRET_ACCESS_KEY=
CLOUDFLARE_R2_BUCKET=
CLOUDFLARE_R2_URL=
CLOUDFLARE_R2_ENDPOINT=
```

## Commandes Artisan utiles

```bash
# Lancer en mode developpement (avec hot reload)
npm run dev

# Rafraichir la base de donnees
php artisan migrate:fresh --seed

# Vider les caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear
```

## Structure du projet

```
app/
├── Enums/          - Enums PHP (OrderStatus, OrderType, ReminderType, UserRole)
├── Events/         - Evenements metier (OrderCreated, OrderStatusChanged, etc.)
├── Exceptions/     - Exceptions metier personnalisees
├── Http/
│   ├── Controllers/
│   │   ├── Admin/   - Controllers administration (Dashboard, Catalogue, Commandes, etc.)
│   │   ├── Auth/    - Authentification (Login, Register, Logout)
│   │   ├── Client/  - Espace client (Commandes, Mesures, Profil)
│   │   └── Public/  - Pages publiques (Accueil, Catalogue, Portfolio)
│   ├── Middleware/  - Middlewares personnalises (Admin, Client, Consentement)
│   └── Requests/   - Form Requests de validation
├── Listeners/      - Listeners d'evenements (rappels, logs)
├── Models/         - 12 modeles Eloquent
├── Policies/       - Politiques d'autorisation
├── Providers/      - Service providers (App, Event, Repository)
├── Repositories/
│   ├── Contracts/  - Interfaces des repositories
│   └── Eloquent/   - Implementations Eloquent
└── Services/       - Services metier (Commande, Tarification, etc.)

database/
├── factories/      - Factories pour les tests et seeders
├── migrations/     - 13 migrations
└── seeders/        - Seeders de donnees de demonstration

resources/views/    - Vues Blade avec composants reutilisables
routes/             - Definition des routes web
config/             - Configuration Laravel + filesystems (R2)
```

## Licence

MIT
