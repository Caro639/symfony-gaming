# Symfony Gaming Application

Une application Symfony utilisant Webpack Encore pour la gestion des assets frontend.

## Prérequis

-   **PHP** >= 8.2
-   **Composer** (gestionnaire de dépendances PHP)
-   **Node.js** >= 18.x
-   **npm** ou **yarn** (gestionnaire de paquets JavaScript)
-   **Symfony CLI**

## Installation

### 1. Cloner le projet

```bash
git clone <url-du-repository>
cd symfony-gaming
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
# ou
yarn install
```

### 4. Configuration de l'environnement

Créez un fichier `.env.local` à la racine du projet et configurez vos variables d'environnement :

```env
# Configuration de la base de données
DATABASE_URL="mysql://username:password@127.0.0.1:3306/symfony_gaming?serverVersion=8.0.32&charset=utf8mb4"

# Configuration JWT (si nécessaire)
JWT_SECRET_KEY=%kernel.project_dir%/config/JWT/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/JWT/public.pem
JWT_PASSPHRASE=your_passphrase
```

### 5. Base de données

Créez la base de données et exécutez les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Lancement de l'application

### Méthode 1 : Développement avec serveur Symfony

1. **Compiler les assets en mode développement :**

    ```bash
    npm run dev
    # ou pour la compilation en continu
    npm run watch
    ```

2. **Lancer le serveur Symfony :**

    ```bash
    # Avec Symfony CLI
    symfony serve

    # Ou avec PHP built-in server
    php -S localhost:8000 -t public/
    ```

L'application sera accessible à l'adresse : `http://localhost:8000`

### Méthode 2 : Développement avec serveur de développement Webpack

1. **Lancer le serveur de développement Webpack Encore :**

    ```bash
    npm run dev-server
    ```

2. **Dans un autre terminal, lancer le serveur Symfony :**

    ```bash
    symfony serve
    ```

Cette méthode permet le rechargement automatique des assets lors des modifications.

## Scripts disponibles

### Scripts npm/yarn

-   `npm run dev` : Compile les assets en mode développement
-   `npm run watch` : Compile les assets en mode développement avec surveillance des changements
-   `npm run build` : Compile les assets en mode production (optimisés)
-   `npm run dev-server` : Lance le serveur de développement Webpack avec hot reload

### Commandes Symfony utiles

-   `php bin/console cache:clear` : Vider le cache
-   `php bin/console doctrine:schema:update --dump-sql` : Voir les changements SQL à appliquer
-   `php bin/console doctrine:fixtures:load` : Charger les fixtures (si configurées)
-   `php bin/console debug:router` : Afficher toutes les routes

## Structure des assets

Les fichiers sources se trouvent dans le dossier `assets/` :

-   `assets/script/` : Fichiers JavaScript/TypeScript
-   `assets/styles/` : Fichiers SCSS/CSS
-   `assets/controllers/` : Contrôleurs Stimulus

Les assets compilés sont générés dans `public/build/`.

## Technologies utilisées

-   **Backend :** Symfony 7.3, Doctrine ORM, API Platform
-   **Frontend :** Webpack Encore, Bootstrap 5, TypeScript, SCSS
-   **Base de données :** MySQL/MariaDB
-   **Authentification :** JWT (Lexik JWT Authentication Bundle)

## Dépannage

### Problèmes courants

1. **Erreur "Cannot resolve dependency" :**

    ```bash
    rm -rf node_modules package-lock.json
    npm install
    ```

2. **Assets non trouvés :**

    - Vérifiez que `npm run dev` a été exécuté
    - Vérifiez la configuration dans `webpack.config.js`

3. **Erreur de base de données :**
    - Vérifiez la configuration dans `.env.local`
    - Assurez-vous que la base de données existe

### Logs

-   **Logs Symfony :** `var/log/`
-   **Logs Webpack :** Affichés dans le terminal lors de la compilation
