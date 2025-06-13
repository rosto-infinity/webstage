# webStage

**webStage** est une application de suivi de présence développée avec Laravel 12, Inertia.js, Vue 3, Tailwind CSS v4, et Chart.js.

---

## 📦 Technologies

- **Back‑end** :
  - PHP ≥ 8.2
  - Laravel 12
  - Inertia.js via `inertiajs/inertia-laravel`
  - Ziggy (routage côté client)
- **Front‑end** :
  - Vue.js 3
  - @inertiajs/vue3
  - Tailwind CSS v4
  - Chart.js (^4.4.9) via `chart.js`
  - lucide-vue-next (icônes)
- **Outils & dev** :
  - Vite, Laravel Vite Plugin
  - TypeScript ^5.2.2
  - tailwind‑merge, cla$$x
  - fakerphp/faker, pestphp/pest, mockery/mockery, laravel/pint…
  - sail, collision, …etc.
  - **Environnemnt & dev** :
    - Linux mint
    - Sever Nginx
    - Docker (optionnel)
    - Composer
    - Node.js (npm)
    - MySQL (ou MariaDB)
    - SQLite (optionnel pour dev)
    - MySQL 8.0


---

## 🛠️ Migration `presences`

La table `presences` est définie comme suit :

```php
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->date('date');
$table->time('arrival_time')->nullable();
$table->time('departure_time')->nullable();
$table->integer('late_minutes')->default(0);
$table->boolean('absent')->default(false);
$table->boolean('late')->default(false);
$table->timestamps();
## 🚀 Installation

Clone le dépôt :

```bash
git clone <repo-url> webStage
cd webStage
```

Installe les dépendances composer & npm :

```bash
composer install
npm install
```

Configure l’environnement :

```bash
cp .env.example .env
php artisan key:generate
# renseigne tes identifiants DB…
```

Crée la base et lance la migration :

```bash
php artisan migrate
```

(Optionnel) Remplis avec des données de test via factory + seeder :

```bash
php artisan db:seed
```

Compile les assets et démarre le serveur :

```bash
npm run dev       # ou npm run prod pour production
php artisan serve
```

## 🧩 Fonctionnalités

- Authentification Laravel native
- CRUD des présences (utilisateurs, status, dates, heures, retard…)
- Dashboard avec statistiques graphiques :
  - Camembert des présences du jour
  - Bar chart hebdomadaire
  - Line chart mensuel
  - Motifs d’absence (mois en cours)
- Interface responsive grâce à Tailwind v4
- Navigation fluide avec Inertia.js + Ziggy

## 💡 Utilisation

- **Présences** : accès via `/presences`
- **Ajout** : lien "Ajouter présence", form avec sélection d’utilisateur
- **Dashboard** : accessible via `/dashboard`, présente les graphiques

## 🧪 Tests & qualité

- Tests unitaires et features avec Pest via `php artisan test`
- Formatting par Laravel Pint
- Qualité du code contrôlée par Composer et `npm run lint`

## 📄 Licence

MIT License - voir le fichier LICENSE.

## 🧭 À venir

- Export Excel / PDF des présences
- Authentification avancée (rôles, permissions)
- Notification email automatique
- Filtrage avancé, recherche dynamique, pagination infinie

## 📞 Contact
  - **Waffo lele rostand**
  - **+2376 915 848 19**
