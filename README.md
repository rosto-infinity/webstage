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
