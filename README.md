# laravel-starter-kit

Laravel API to bootstrap a project without redoing login, roles, permissions and the module
structure.

Laravel 13 · Horizon · MySQL

Consumed by [`vue-starter-kit`](https://github.com/dazza-dev/vue-starter-kit) and
[`react-starter-kit`](https://github.com/dazza-dev/react-starter-kit).

---

## What's included

- Cookie-based session authentication: login, password recovery, logout and profile
- Permission-based authorization with `Gate::before`, `permission:` middleware and `admin` role bypass
- Modular architecture: every feature is a self-contained module with its own routes, services and translations
- Full CRUD for users, roles, permissions, groups and settings
- Redis-backed queues with Horizon, with separate supervisors per job type
- Translations in `en`, `es` and `pt`

## Requirements

- PHP 8.3+ with the `phpredis` extension
- Composer
- MySQL 8+
- Redis

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate

mysql -uroot -p -e "CREATE DATABASE laravel_starter"
php artisan migrate --seed
php artisan storage:link   # publishes the public disk under public/storage

php artisan serve         # http://localhost:8000
php artisan horizon       # processes the queues; panel at /horizon
```

The seeder creates the first user to log in with: `admin@example.test` / `password123`.
The credentials sit at the top of `database/seeders/DatabaseSeeder.php`; change them there before seeding a real environment.

Quick check:

```bash
curl http://localhost:8000/api/v1/settings
```

## Requests

Every module hangs off `api/v1/`, Auth included.

Response keys travel in `snake_case`; the SPA's `axios-case-converter` converts them to `camelCase`
on the way in and back to `snake_case` on the way out.

## Mail

The password reset link points to `{FRONTEND_URL}/auth/reset-password`. Configure `FRONTEND_URL`
and a real `MAIL_MAILER` before deploying.

Locally, `brew install mailpit && brew services start mailpit` spins up an SMTP server on port 1025
and an inbox at `http://localhost:8025`, which is what `.env.example` points to.

## Files

There are two disks, and the difference is who can read what you store.

| Disk     | Written to            | Served by                            | For                    |
| -------- | ---------------------- | ------------------------------------ | ---------------------- |
| `public` | `storage/app/public`   | static under `/storage/...`          | Logos and brand assets |
| `local`  | `storage/app/private`  | `GET api/v1/files/...`, with session | User uploads           |

```php
// Public: the path isn't secret. Needs `php artisan storage:link`.
$path = $file->store('logos', 'public');
Storage::disk('public')->url($path);   // http://localhost:8000/storage/logos/xxx.png

// Private: only reachable through the endpoint, with a session.
$path = $file->store('docs', 'local');
Storage::disk('local')->url($path);    // http://localhost:8000/api/v1/files/docs/xxx.pdf
```

The private disk's `url` is set to the endpoint base (`FILES_BASE_URL`), so `Storage::url()` already
returns the masked URL and the code that stores a file doesn't decide how it's served.

`FileController` reads it off the disk and streams it back, so the URL says nothing about where it
lives or whether the disk is local or a bucket, and switching driver doesn't invalidate stored URLs.
It requires a session; per-file authorization (who owns it, who may view it) is up to each project.

## Queues and scheduled tasks

Queues run on Redis and are managed by [Horizon](https://laravel.com/docs/horizon)
(`php artisan horizon`, panel at `/horizon`).

Configuration lives in `config/horizon.php` and the example job is queued with `app:example`. The
panel is protected with basic authentication via the `horizon.auth` middleware, using the
`HORIZON_BASIC_AUTH_USER` and `HORIZON_BASIC_AUTH_PASSWORD` credentials; it stays closed if either
is empty, and it should only be served over **HTTPS**.

`routes/console.php` ships **with no scheduled task** on purpose; once you add yours, a single cron
entry on the server runs them all:

```
* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1
```

## Structure

```
app/
├── Console/Commands/   Artisan commands
├── Http/Middleware/    Horizon basic auth
├── Jobs/               Queued jobs
└── Modules/
    ├── Core/       Traits, helpers, middleware (locale)
    ├── Auth/       Login, password recovery, profile
    ├── Files/      Serves the private disk through an endpoint
    ├── Users/      User CRUD  ← full example
    └── Configs/
        ├── Groups/      ← the smallest example of the pattern; copy it for new modules
        ├── Roles/
        ├── Permissions/
        └── Settings/

database/
├── migrations/     Full schema
├── seeders/        Seeders
└── data/           Seed data, in JSON
```

Every module is registered in `bootstrap/providers.php`. Development rules, the module pattern and
the conventions live in [`CLAUDE.md`](./CLAUDE.md).

## Adding a module

1. Copy `app/Modules/Configs/Groups/` and rename everything
2. Add its migration in `database/migrations/` and its permissions in `database/data/Permissions.json`
3. Translate the new permissions in `app/Modules/Configs/Permissions/Lang/{en,es,pt}/names.php`
4. Register the service provider in `bootstrap/providers.php`

## Before committing

```bash
./vendor/bin/pint
php artisan test
```
