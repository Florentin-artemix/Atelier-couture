# Guide de déploiement — AtelierCouture

Ce document explique **en profondeur** comment héberger et mettre en production l'application **AtelierCouture** (Laravel 12 / PHP 8.3+ / MySQL 8 / Cloudflare R2).

> Lis ce guide du début à la fin avant de commencer. Chaque section est numérotée et peut servir de checklist.

---

## Sommaire

1. Vue d'ensemble & pré-requis
2. Choisir son hébergement
3. Préparer le serveur (VPS Ubuntu)
4. Récupérer le code
5. Configurer l'environnement (.env)
6. Base de données MySQL
7. Stockage des images (Cloudflare R2)
8. Compiler les assets (build)
9. Configurer le serveur web (Nginx)
10. HTTPS / certificat SSL
11. Optimisations de production
12. Tâches planifiées & file d'attente
13. Déploiement simplifié (Laravel Forge / Ploi)
14. Hébergement mutualisé (cPanel)
15. Mises à jour & rollback
16. Sauvegardes
17. Sécurité
18. Dépannage (erreurs fréquentes)

---

## 1. Vue d'ensemble & pré-requis

**Stack technique :**

| Composant | Version | Rôle |
|-----------|---------|------|
| PHP | 8.3 ou 8.4 | Langage |
| Laravel | 12 | Framework |
| MySQL | 8.0+ | Base de données |
| Node.js | 20+ | Build des assets (Vite/Tailwind) |
| Composer | 2.x | Dépendances PHP |
| Nginx ou Apache | récent | Serveur web |
| Cloudflare R2 | — | Stockage images (compatible S3) |

**Extensions PHP requises :** `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `bcmath`, `gd`, `fileinfo`, `openssl`, `tokenizer`, `ctype`, `json`.

**Ressources minimales recommandées :** 1 vCPU, 2 Go de RAM, 20 Go de disque. 2 vCPU / 4 Go pour plus de confort.


---

## 2. Choisir son hébergement

| Option | Pour qui ? | Avantages | Inconvénients |
|--------|-----------|-----------|---------------|
| **VPS** (DigitalOcean, Hetzner, OVH, Contabo...) | Recommandé | Contrôle total, performant, pas cher (~5 €/mois) | Configuration manuelle |
| **Laravel Forge / Ploi** (+ VPS) | Débutant qui veut du clé-en-main | Déploiement auto, SSL auto, simple | ~12 $/mois en plus du VPS |
| **Hébergement mutualisé** (cPanel/Hostinger) | Très petit budget | Pas cher, simple | Limité (pas de SSH parfois, versions PHP figées) |
| **Plateformes PaaS** (Render, Railway, Fly.io) | Sans gestion serveur | Scalable, Git push = deploy | Base MySQL externe à prévoir |

> Pour ce projet, le meilleur rapport simplicité/contrôle est un **VPS Ubuntu 22.04/24.04**. Le reste du guide se base dessus, puis détaille les alternatives.

**Tu auras aussi besoin de :**
- Un **nom de domaine** (ex: `ateliercouture.com`) — chez OVH, Namecheap, Cloudflare Registrar...
- Un **bucket Cloudflare R2** avec accès public (déjà configuré dans ce projet).

---

## 3. Préparer le serveur (VPS Ubuntu)

Connecte-toi en SSH : `ssh root@IP_DU_SERVEUR`

### 3.1 Mise à jour du système
```bash
sudo apt update && sudo apt upgrade -y
```

### 3.2 Installer PHP 8.3 + extensions
```bash
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl
```

### 3.3 Installer Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3.4 Installer Node.js 20 + npm
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 3.5 Installer MySQL 8
```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

### 3.6 Installer Nginx + Git
```bash
sudo apt install -y nginx git
```


---

## 4. Récupérer le code

On place l'application dans `/var/www/atelier-couture`.

```bash
cd /var/www
sudo git clone https://github.com/Florentin-artemix/Atelier-couture.git atelier-couture
cd atelier-couture
```

Installer les dépendances **en mode production** :
```bash
composer install --optimize-autoloader --no-dev
npm install
```

> `--no-dev` n'installe pas les paquets de développement (plus léger, plus sûr en prod).

---

## 5. Configurer l'environnement (.env)

```bash
cp .env.example .env
php artisan key:generate
```

Édite `.env` (`nano .env`) avec les valeurs de **production** :

```env
APP_NAME=AtelierCouture
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ateliercouture.com
APP_LOCALE=fr

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ateliercouture
DB_USERNAME=atelier_user
DB_PASSWORD=UN_MOT_DE_PASSE_SOLIDE

# Sessions / cache (file ou database)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Stockage images : Cloudflare R2
IMAGES_DISK=r2
R2_ACCESS_KEY_ID=ta_cle
R2_SECRET_ACCESS_KEY=ta_cle_secrete
R2_ENDPOINT=https://xxxxx.r2.cloudflarestorage.com
R2_BUCKET=atelier-couture
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

> **TRÈS IMPORTANT en production :**
> - `APP_ENV=production`
> - `APP_DEBUG=false` (sinon les erreurs exposent des infos sensibles)
> - Un `APP_KEY` généré (fait par `key:generate`)
> - Un mot de passe MySQL solide

---

## 6. Base de données MySQL

### 6.1 Créer la base et l'utilisateur
```bash
sudo mysql
```
```sql
CREATE DATABASE ateliercouture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'atelier_user'@'localhost' IDENTIFIED BY 'UN_MOT_DE_PASSE_SOLIDE';
GRANT ALL PRIVILEGES ON ateliercouture.* TO 'atelier_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6.2 Lancer les migrations + données de démo
```bash
php artisan migrate --force --seed
```

> `--force` est obligatoire en production (sinon Laravel demande une confirmation interactive).
> Si tu ne veux PAS les données de démo, fais juste `php artisan migrate --force`.

### 6.3 Compte administrateur
Le seeder crée :
- **Admin** : `admin@ateliercouture.test` / `password`
- **Client démo** : `client@ateliercouture.test` / `password`

> **Change ces mots de passe immédiatement** après le premier déploiement (via la base ou un futur écran de profil).


---

## 7. Stockage des images (Cloudflare R2)

Les images des modèles et du portfolio sont stockées sur **Cloudflare R2** (compatible S3). Le projet utilise le disque `r2` défini dans `config/filesystems.php`.

### 7.1 Créer le bucket (si pas déjà fait)
1. Dashboard Cloudflare → **R2** → **Create bucket** → nom : `atelier-couture`
2. Onglet **Settings** du bucket → **Public Development URL** → **Enable** → tu obtiens une URL `https://pub-xxxxx.r2.dev`
3. **R2 → Manage API Tokens → Create API Token** (permission *Object Read & Write*) → récupère `Access Key ID` et `Secret Access Key`

### 7.2 Renseigner dans `.env`
```env
IMAGES_DISK=r2
R2_ACCESS_KEY_ID=...
R2_SECRET_ACCESS_KEY=...
R2_ENDPOINT=https://<account_id>.r2.cloudflarestorage.com
R2_BUCKET=atelier-couture
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

### 7.3 Alternative : stockage local
Si tu ne veux pas R2, mets `IMAGES_DISK=public` puis crée le lien symbolique :
```bash
php artisan storage:link
```
Les images iront dans `storage/app/public` servies via `/storage`.

---

## 8. Compiler les assets (build)

Tailwind + JS sont compilés par Vite. **À faire sur le serveur** (ou en local puis transférer `public/build`) :
```bash
npm run build
```
Cela génère `public/build/`. En production on ne lance **jamais** `npm run dev`.

---

## 9. Configurer le serveur web (Nginx)

Crée le fichier `/etc/nginx/sites-available/ateliercouture` :

```nginx
server {
    listen 80;
    server_name ateliercouture.com www.ateliercouture.com;
    root /var/www/atelier-couture/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activer le site + permissions :
```bash
sudo ln -s /etc/nginx/sites-available/ateliercouture /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo chown -R www-data:www-data /var/www/atelier-couture/storage /var/www/atelier-couture/bootstrap/cache
sudo chmod -R 775 /var/www/atelier-couture/storage /var/www/atelier-couture/bootstrap/cache
sudo nginx -t && sudo systemctl reload nginx
```

> **Le `root` pointe vers `/public`**, jamais vers la racine du projet (sécurité essentielle).

### Pointer le domaine
Chez ton registrar / Cloudflare DNS : crée un enregistrement **A** `ateliercouture.com` → IP du VPS (et `www` en CNAME ou A).


---

## 10. HTTPS / certificat SSL

Avec **Let's Encrypt** (gratuit) via Certbot :
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d ateliercouture.com -d www.ateliercouture.com
```
Certbot configure Nginx en HTTPS et gère le renouvellement automatique. Pense à mettre `APP_URL=https://...` dans `.env`.

---

## 11. Optimisations de production

À lancer **après chaque déploiement** pour mettre en cache config/routes/vues :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Si tu modifies `.env` ensuite, relance `php artisan config:cache` (sinon les changements ne sont pas pris en compte car la config est mise en cache).

Pour **annuler** ces caches (utile en debug) :
```bash
php artisan optimize:clear
```

---

## 12. Tâches planifiées & file d'attente

### 12.1 Scheduler (rappels automatiques : retards, précommandes en attente)
Le projet définit des tâches planifiées dans `routes/console.php`. Ajoute **une seule** ligne cron :
```bash
sudo crontab -e
```
```
* * * * * cd /var/www/atelier-couture && php artisan schedule:run >> /dev/null 2>&1
```

### 12.2 File d'attente (si `QUEUE_CONNECTION=database`)
Crée la table puis un worker supervisé :
```bash
php artisan queue:table
php artisan migrate --force
```
Service systemd `/etc/systemd/system/atelier-worker.service` :
```ini
[Unit]
Description=AtelierCouture Queue Worker
After=network.target

[Service]
User=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/atelier-couture/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```
```bash
sudo systemctl enable --now atelier-worker
```

> Pour ce MVP, `QUEUE_CONNECTION=sync` (par défaut) fonctionne aussi : tout s'exécute immédiatement, pas besoin de worker. Le worker n'est utile que si tu passes en `database`/`redis`.

---

## 13. Déploiement simplifié (Laravel Forge / Ploi)

Si tu veux éviter la configuration manuelle :
1. Crée un compte **Laravel Forge** (forge.laravel.com) ou **Ploi** (ploi.io)
2. Connecte ton fournisseur de VPS (DigitalOcean, Hetzner...) → Forge provisionne le serveur (PHP, Nginx, MySQL, certificat) automatiquement
3. **New Site** → domaine → connecte le dépôt GitHub `Florentin-artemix/Atelier-couture`, branche `main`
4. Renseigne le `.env` depuis l'interface
5. Script de déploiement (déjà pré-rempli, à compléter) :
```bash
cd $FORGE_SITE_PATH
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
6. Active **Quick Deploy** → chaque `git push` sur `main` redéploie automatiquement.
7. Active le **Scheduler** et un **Queue Worker** en un clic depuis l'interface.

---

## 14. Hébergement mutualisé (cPanel)

Possible mais plus contraignant (pas toujours de SSH, versions PHP figées).

1. Vérifie que l'hébergeur propose **PHP 8.3+** et **MySQL 8** (sinon, change d'hébergeur).
2. En local : `composer install --no-dev` puis `npm run build`.
3. Compresse le projet (en incluant `vendor/` et `public/build/`) et envoie-le via le **Gestionnaire de fichiers** ou FTP.
4. Place le contenu **hors** de `public_html`, et fais pointer le `Document Root` vers le dossier `public/` du projet (ou copie le contenu de `public/` dans `public_html` en ajustant les chemins dans `index.php`).
5. Crée la base MySQL via l'assistant cPanel, renseigne `.env`.
6. Lance les migrations via **Terminal cPanel** (`php artisan migrate --force`) si dispo, sinon importe un dump SQL.
7. Configure une tâche **Cron** : `php /home/USER/atelier/artisan schedule:run` chaque minute.

> Le mutualisé convient pour un trafic faible. Pour la fiabilité, un VPS reste préférable.


---

## 15. Mises à jour & rollback

### 15.1 Déployer une nouvelle version
```bash
cd /var/www/atelier-couture
php artisan down            # mode maintenance (optionnel)
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan up              # sortie du mode maintenance
```

### 15.2 Script de déploiement réutilisable
Crée `deploy.sh` à la racine :
```bash
#!/usr/bin/env bash
set -e
cd /var/www/atelier-couture
php artisan down || true
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
echo "Déploiement terminé."
```
```bash
chmod +x deploy.sh
./deploy.sh
```

### 15.3 Rollback
- **Code** : `git log --oneline` pour trouver le commit stable, puis `git checkout <hash>` (ou `git revert`).
- **Base** : restaure une sauvegarde SQL (voir section 16). Attention : `migrate:rollback` ne restaure pas les données, seulement le schéma.

---

## 16. Sauvegardes

### 16.1 Base de données (cron quotidien)
```bash
sudo crontab -e
```
```
0 2 * * * mysqldump -u atelier_user -p'MDP' ateliercouture | gzip > /var/backups/atelier_$(date +\%F).sql.gz
```

### 16.2 Fichiers
- Les **images** sont déjà sur Cloudflare R2 (redondant et durable).
- Sauvegarde aussi le fichier `.env` (contient les clés) dans un endroit sûr et chiffré.

### 16.3 Restauration
```bash
gunzip < /var/backups/atelier_2026-05-30.sql.gz | mysql -u atelier_user -p ateliercouture
```

---

## 17. Sécurité

- ✅ `APP_DEBUG=false` et `APP_ENV=production`.
- ✅ `root` Nginx pointe sur `/public` uniquement.
- ✅ Permissions : seuls `storage/` et `bootstrap/cache/` sont inscriptibles par `www-data`.
- ✅ HTTPS actif (Certbot) + redirection HTTP→HTTPS.
- ✅ Mots de passe DB/admin solides ; change les comptes de démo.
- ✅ Pare-feu : n'ouvre que 22 (SSH), 80, 443.
  ```bash
  sudo ufw allow OpenSSH && sudo ufw allow 'Nginx Full' && sudo ufw enable
  ```
- ✅ `.env` n'est **jamais** commité (présent dans `.gitignore`).
- ✅ Garde le système à jour : `sudo apt update && sudo apt upgrade`.
- 🔒 Désactive la connexion SSH par mot de passe (clé SSH uniquement) si possible.

---

## 18. Dépannage (erreurs fréquentes)

| Erreur | Cause probable | Solution |
|--------|----------------|----------|
| **500 page blanche** | `APP_KEY` manquant ou cache de config obsolète | `php artisan key:generate` puis `php artisan config:clear` |
| **419 Page Expired** | Sessions/CSRF, `APP_URL` incorrect | Vérifie `APP_URL`, `SESSION_DRIVER`, cookies HTTPS |
| **Permission denied (logs/cache)** | Droits `storage/` | `chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 ...` |
| **Could not find driver** | Extension `pdo_mysql` absente | `sudo apt install php8.3-mysql && sudo systemctl restart php8.3-fpm` |
| **Images ne s'affichent pas** | `R2_PUBLIC_URL` manquant ou bucket non public | Active l'URL publique R2, mets `R2_PUBLIC_URL`, `php artisan config:cache` |
| **CSS absent / page non stylée** | `npm run build` non lancé | `npm run build` puis vider le cache navigateur |
| **Les changements de .env ne s'appliquent pas** | Config en cache | `php artisan config:cache` (ou `config:clear`) |
| **Erreur de migration "table exists"** | Base déjà migrée | Utilise `php artisan migrate --force` (pas `migrate:fresh` en prod, ça efface tout !) |
| **502 Bad Gateway** | PHP-FPM arrêté ou mauvais socket | `sudo systemctl restart php8.3-fpm`, vérifie le chemin du socket dans Nginx |

> ⚠️ **Ne lance JAMAIS `php artisan migrate:fresh` en production** : cette commande **supprime toutes les tables et les données**. Utilise uniquement `php artisan migrate --force`.

---

## Récapitulatif express (checklist VPS)

```bash
# 1. Serveur prêt (PHP 8.3, MySQL, Nginx, Node, Composer)  -> sections 3
# 2. Code
cd /var/www && git clone <repo> atelier-couture && cd atelier-couture
composer install --no-dev --optimize-autoloader && npm install
# 3. Env
cp .env.example .env && php artisan key:generate && nano .env
# 4. Base
php artisan migrate --force --seed
# 5. Assets
npm run build
# 6. Cache prod
php artisan config:cache && php artisan route:cache && php artisan view:cache
# 7. Permissions
sudo chown -R www-data:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache
# 8. Nginx + SSL  -> sections 9 & 10
# 9. Cron scheduler -> section 12
```

Bon déploiement ! 🚀
