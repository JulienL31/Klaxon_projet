# Touche pas au klaxon

> Projet d’exercice – Auteur : **Launois Julien**  
> Application intranet pour publier les trajets inter-sites et favoriser le covoiturage.

---

## 1) Objectif (selon l’énoncé)

- Accueil **public** : liste des trajets **à venir**, triés par **date de départ croissante**, **uniquement** ceux avec des **places disponibles**.  
- Après **connexion** :
  - voir les **détails** d’un trajet en **modal** (auteur, téléphone, email, nombre total de places) ;
  - **proposer** un trajet ;
  - **modifier**/**supprimer** ses propres trajets.
- **Administrateur** :
  - tableau de bord ;
  - lister **utilisateurs**, **agences**, **trajets** ;
  - **CRUD Agences** (création, modification, suppression) ;
  - **suppression** d’un trajet.

Techno imposées : PHP (Laravel 9 – MVC), MySQL/MariaDB, **Bootstrap + Sass**, code **commenté**, contrôle qualité (**PHPStan**), **tests unitaires** (écritures DB).

---

## 2) Stack & prérequis

- **PHP** ≥ 8.0.2  
- **Composer**  
- **Node.js** (Vite)  
- **MySQL/MariaDB**

---

## 3) Installation

```bash
git clone <URL_DU_DEPOT>
cd Klaxon_project
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configurer la base dans `.env` (ex.) :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klaxon
DB_USERNAME=root
DB_PASSWORD=
```

### Option A — Fichiers SQL fournis (recommandé)
Importer **dans votre base** :
- `database/schema/mysql-schema.sql` (création des tables)
- `database/seeders/seed_data.sql` (jeu d’essai)

### Option B — Migrations/seeders Laravel
```bash
php artisan migrate --seed
```

---

## 4) Lancement

Dans un terminal :
```bash
npm run dev      # assets (Vite)
```
Dans un autre :
```bash
php artisan serve
# http://localhost:8000
```

### Comptes de test
- **Admin** : `admin@example.com` / `password`  
- **User**  : `user@example.com`  / `password`

---

## 5) Fonctionnalités livrées (vérifiées)

- **Accueil (public)** : trajets futurs, places > 0, triés (date/heure de départ).  
- **Modal “Détails”** (après connexion) : auteur, téléphone, email, total places.  
- **Création / Édition / Suppression** d’un trajet par son **auteur**.  
- **Admin** :
  - **Dashboard**
  - **Users** : liste
  - **Agencies** : **CRUD** complet
  - **Trips** : liste + **suppression**
- **Header/Footer** conformes aux rôles (visiteur / user / admin).

---

## 6) Qualité & tests

### PHPStan
```bash
vendor/bin/phpstan analyse --memory-limit=1G
```

### PHPUnit
```bash
vendor/bin/phpunit
```
Statut actuel : **OK (12 tests, 40 assertions)**.

Tests couvrent a minima les **opérations d’écriture DB** (création, mise à jour, suppression de trajets).

---

## 7) Structure (résumé)

- `app/Models` : `User`, `Agency`, `Trip` (casts & relations)  
- `app/Http/Controllers` : `HomeController`, `AuthController`, `TripController`, `Admin/*`  
- `resources/views` : `home`, `trips` (create/edit), `admin/*`, `partials/*`  
- `public/` : assets buildés  
- `database/migrations` & `database/seeders`  
- `database/schema/mysql-schema.sql` & `database/seeders/seed_data.sql`

---

## 8) Notes

- **Sass/Bootstrap** : palette via variables Bootstrap (fichier SCSS), identité graphique minimale respectée.  
- **Sécurité** : seules les **auteurs** (ou **admin**) peuvent modifier/supprimer un trajet.  
- **Téléphone** : champ **`users.phone`** ajouté, normalisé côté modèle et affiché (modal & panneau infos).

---

## 9) Licence

Projet d’exercice étudiant.
