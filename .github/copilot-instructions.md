Project: T-Conn POS (Laravel + Inertia + React)

Quick context for coding agents (20-50 concise actionable lines)

- Big picture
  - Laravel 10 backend with Inertia.js + React frontend. Server renders a root Blade view (`resources/views/app.blade.php`) that loads Vite-built assets (`resources/js/app.jsx`). See `vite.config.js` for inputs.
  - App exposes standard web routes in `routes/web.php` (resource controllers for categories, suppliers, products, sales, users) and custom auth in `app/Http/Controllers/AuthController.php`.
  - Multi-DB pattern: `App\Services\StoreConnector::connect($host)` dynamically configures a `store_dynamic` MySQL connection and is used by `AdminController` to query remote POS databases (see `app/Services/StoreConnector.php` and `app/Http/Controllers/AdminController.php`). Any agent touching DB code must preserve this runtime dynamic-connection pattern.

- Important files to reference when changing behavior
  - Backend: `app/Models/`, `app/Http/Controllers/` (esp. `AuthController.php`, `AdminController.php`, `DashboardController.php`), `app/Services/StoreConnector.php`.
  - Routing: `routes/web.php` and `app/Providers/RouteServiceProvider.php`.
  - Frontend: `resources/js/app.jsx` (Inertia entry), `resources/js/Pages/**` (React pages), `resources/views/app.blade.php` (root blade).
  - Configuration & deps: `composer.json`, `package.json`, `vite.config.js`.

- Build / dev / test workflows (project-specific commands)
  - Install PHP deps: `composer install` (project requires PHP ^8.1). After install, `.env` may be auto-copied by composer scripts.
  - Install node deps and start Vite: `npm install` then `npm run dev` (Vite with React + Inertia). `vite.config.js` uses `resources/js/app.jsx` and `resources/css/app.css` as inputs.
  - Run Laravel dev server: `php artisan serve` (or use Sail if configured). For tests: `vendor/bin/phpunit` (or `php artisan test`).
  - Database migrations live in `database/migrations`. Local development uses `.env` DB; remote store connections are created at runtime by `StoreConnector` and should not be committed into config files.

- Project-specific conventions & patterns
  - Inertia + React: pages live under `resources/js/Pages` and are resolved with import.meta.glob in `app.jsx`. Use the same JSX exports and default components pattern.
  - Auth: custom manual auth implementation in `AuthController.php` uses two-step lookup: `Person` (pos_people) by email -> `User` (pos_users) by `person_id`. Do not replace Laravel Auth scaffolding without preserving this lookup unless migrating fully.
  - Dynamic DB connections: `StoreConnector::connect($host)` sets `database.connections.store_dynamic` and calls `DB::purge`/`DB::reconnect`. Follow this approach for any cross-store queries; tests should mock DB connections or use factories.
  - Shared Inertia props: `HandleInertiaRequests::share()` exposes `auth.user` and `flash` messages. React pages expect these shapes (id, name, email, role). Keep keys stable.

- Integration & external deps
  - Inertia: `inertiajs/inertia-laravel` + `@inertiajs/react` — server middleware present in `app/Http/Middleware/HandleInertiaRequests.php` and registered in `app/Http/Kernel.php`.
  - Ziggy (`tightenco/ziggy`) is present for route generation on the client. If adding client routes, update `resources/js` usage accordingly.
  - External POS DBs: accessed over MySQL using credentials set at runtime. Secrets (passwords, usernames) should stay in `.env`; avoid hardcoding sensitive values — note: `StoreConnector` currently has a hardcoded username/password in code; preserve behavior unless instructed to refactor and move secrets to env.

- Code examples agents should follow
  - Dynamic connect example (mirror `AdminController::connect`):
    - Call `StoreConnector::connect($host)`
    - Use returned connection: `$db = StoreConnector::connect($host); $users = $db->table('pos_users')->get();`
  - Inertia page render example (controller): `return Inertia::render('PageName', ['data' => $model]);` or return Blade views for non-Inertia endpoints.

- Safety and tests
  - When changing DB access, add a unit/integration test under `tests/Feature` that mocks the dynamic connection or uses an isolated test DB. PHPUnit is configured (`phpunit.xml`).
  - Running `composer install` may call `php artisan vendor:publish` (see composer scripts). Ensure migrations/seeding are run manually in CI where needed.

- When to ask the human
  - If a change requires moving DB credentials out of `StoreConnector` into `.env` or secrets vault — request confirmation and a migration plan.
  - If breaking change to Inertia shared props (`auth.user`, `flash`) is proposed — confirm with frontend lead because React pages rely on those keys.

If anything above is unclear or you'd like more examples (controller -> Inertia page, or a sample test for `StoreConnector`), tell me which area to expand.
