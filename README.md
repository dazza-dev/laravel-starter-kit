# laravel-starter-kit

API en Laravel para arrancar un proyecto sin volver a montar el login, los roles, los permisos y la
estructura de módulos.

Laravel 13 · Horizon · MySQL

Su pareja en el frontend es [`vue-starter-kit`](https://github.com/dazza-dev/vue-starter-kit), y es
**intercambiable con [`nestjs-starter-kit`](https://github.com/dazza-dev/nestjs-starter-kit)**: mismas
rutas y mismas respuestas, así que la SPA funciona con cualquiera de los dos.

---

## Qué trae

- Autenticación por sesión con cookies: login, recuperación de contraseña, logout y perfil
- Autorización por permisos con `Gate::before`, middleware `permission:` y bypass del rol `admin`
- Arquitectura modular: cada feature es un módulo autocontenido con sus rutas, servicios y traducciones
- CRUD completo de usuarios, roles, permisos, grupos y ajustes
- Colas con Redis y Horizon, con supervisores separados por tipo de trabajo
- Traducciones en `en`, `es` y `pt`

## Requisitos

- PHP 8.3+ con la extensión `phpredis`
- Composer
- MySQL 8+
- Redis

## Puesta en marcha

```bash
composer install
cp .env.example .env
php artisan key:generate

mysql -uroot -p -e "CREATE DATABASE laravel_starter"
php artisan migrate --seed
php artisan storage:link   # publica el disco público en public/storage

php artisan serve         # http://localhost:8000
php artisan horizon       # procesa las colas; panel en /horizon
```

El seeder crea el usuario con el que entras la primera vez: `admin@example.test` / `password123`.
Las credenciales están al principio de `database/seeders/DatabaseSeeder.php`; cámbialas ahí antes de sembrar en un entorno real.

Comprobación rápida:

```bash
curl http://localhost:8000/api/v1/settings
```

## Peticiones

Todos los módulos cuelgan de `api/v1/`, Auth incluido.

Las claves de las respuestas viajan en `snake_case`; el `axios-case-converter` de la SPA las convierte
a `camelCase` al recibirlas y de vuelta a `snake_case` al enviar.

## Correo

El enlace para recuperar la contraseña apunta a `{FRONTEND_URL}/auth/reset-password`. Configura
`FRONTEND_URL` y un `MAIL_MAILER` real antes de desplegar.

En local, `brew install mailpit && brew services start mailpit` levanta un SMTP en el 1025 y una
bandeja en `http://localhost:8025`, que es a donde apunta el `.env.example`.

## Ficheros

Hay dos discos, y la diferencia es quién puede leer lo que guardas.

| Disco    | Dónde escribe        | Cómo se sirve                      | Para qué                     |
| -------- | -------------------- | ---------------------------------- | ---------------------------- |
| `public` | `storage/app/public` | estático en `/storage/...`         | Logos y ficheros de la marca |
| `local`  | `storage/app/private`| `GET api/v1/files/...`, con sesión | Lo que suben los usuarios    |

```php
// Público: la ruta no es secreta. Necesita `php artisan storage:link`.
$path = $file->store('logos', 'public');
Storage::disk('public')->url($path);   // http://localhost:8000/storage/logos/xxx.png

// Privado: solo sale por el endpoint y con sesión.
$path = $file->store('docs', 'local');
Storage::disk('local')->url($path);    // http://localhost:8000/api/v1/files/docs/xxx.pdf
```

El disco privado tiene como `url` la base del endpoint (`FILES_BASE_URL`), así que `Storage::url()`
ya devuelve la URL enmascarada y el código que guarda un fichero no decide cómo se sirve.

`FileController` lo lee por el disco y lo devuelve como stream, así que la URL no dice dónde está ni
si el disco es local o un bucket, y cambiar de driver no invalida las URLs guardadas. Exige sesión;
la autorización por fichero (de quién es, quién puede verlo) es cosa de cada proyecto.

## Colas y tareas programadas

Las colas van por Redis y las gestiona Horizon (`php artisan horizon`, panel en `/horizon`). Dos
supervisores, cada uno con su cola, para que un trabajo largo no retrase a los cortos:

| Supervisor         | Colas                              | Para qué                 |
| ------------------ | ---------------------------------- | ------------------------ |
| `supervisor-fast`  | `default`, `mail`, `notifications` | Correos y notificaciones |
| `supervisor-heavy` | `reports`                          | Importaciones e informes |

El panel se protege con autenticación básica mediante el middleware `horizon.auth`, con las
credenciales de `HORIZON_BASIC_AUTH_USER` y `HORIZON_BASIC_AUTH_PASSWORD`. Si alguna queda vacía el
panel no se abre, así que un despliegue sin configurarlas no lo expone. Publícalo **solo por HTTPS**:
la autenticación básica manda las credenciales en cada petición, y sin TLS viajan legibles.

`app:example` es la plantilla de comando: encola un job que solo escribe en el log.
`routes/console.php` viene **sin ninguna tarea programada** a propósito; cuando añadas la tuya, en el
servidor basta una entrada de cron para todas:

```
* * * * * cd /ruta && php artisan schedule:run >> /dev/null 2>&1
```

## Estructura

```
app/
├── Console/Commands/   Comandos de artisan
├── Http/Middleware/    Basic auth de Horizon
├── Jobs/               Jobs en cola
└── Modules/
    ├── Core/       Traits, helpers, middleware (locale)
    ├── Auth/       Login, recuperación de contraseña, perfil
    ├── Files/      Sirve el disco privado por un endpoint
    ├── Users/      CRUD de usuarios  ← ejemplo completo
    └── Configs/
        ├── Groups/      ← el ejemplo más pequeño del patrón; cópialo para módulos nuevos
        ├── Roles/
        ├── Permissions/
        └── Settings/

database/
├── migrations/     Esquema completo
├── seeders/        Semillas
└── data/           Datos de las semillas, en JSON
```

Cada módulo se registra en `bootstrap/providers.php`. Las reglas de desarrollo, el patrón de módulo
y las convenciones están en [`CLAUDE.md`](./CLAUDE.md).

## Añadir un módulo

1. Copia `app/Modules/Configs/Groups/` y renombra todo
2. Añade su migración en `database/migrations/` y sus permisos en `database/data/Permissions.json`
3. Traduce los permisos nuevos en `app/Modules/Configs/Permissions/Lang/{en,es,pt}/names.php`
4. Registra el service provider en `bootstrap/providers.php`

## Antes de commitear

```bash
./vendor/bin/pint
php artisan test
```
