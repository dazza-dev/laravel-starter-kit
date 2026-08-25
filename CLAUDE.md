# Laravel Starter Kit — Development Rules

## Stack

- Laravel 13, PHP 8.3
- Session-based cookie auth (`auth:web`), `StartSession` loaded on API routes
- `database/` holds the whole schema: migrations, seeders and the JSON seed data

---

## Comments

- **Language:** every comment is written in **English**, same as the code. Names of variables, functions, classes, methods, fields, DB columns, types, and comments all stay in **English**.
- **Function / method / class docblocks:** always use the **multi-line block** form spanning **at least 3 lines**, even when the text is a single line. Never collapse a docblock to one line (`/** text */`):

  ```php
  /**
   * Resolves a list of UUIDs to the given model's integer primary keys.
   */
  private function idsFromUuids(string $model, array $uuids): array { ... }
  ```

- **Inline comments inside a function body:** a single-line `//` comment is fine — do NOT expand these to blocks.

---

## Module Structure

Every feature lives in `app/Modules/{Domain}/{Feature}/` (or `app/Modules/{Domain}/` for top-level domains):

```
app/Modules/Configs/Groups/
├── Controllers/GroupController.php
├── Models/Group.php
├── Requests/GroupRequest.php
├── Requests/GroupFilterRequest.php
├── Resources/GroupResource.php
├── Routes/api.php
├── Services/GroupService.php
├── Services/GroupDataTableService.php
├── Lang/en/{messages,validation}.php
├── Lang/es/{messages,validation}.php
├── Lang/pt/{messages,validation}.php
└── GroupsServiceProvider.php
```

Modules that ship with the starter:

| Domain    | Contents                                                            |
| --------- | ------------------------------------------------------------------- |
| `Core`    | Traits, helpers, middleware (locale)                                |
| `Auth`    | Login, password recovery, logout, profile                           |
| `Users`   | **Reference CRUD** — application users (`User` model, table `users`) |
| `Configs` | `Roles`, `Permissions`, `Settings`, `Groups`                        |
| `Files`   | Serves the private disk through an endpoint instead of a real path  |

> `Configs/Groups` is the **smallest complete example** of the module pattern (single field, full CRUD + soft deletes + restore). Copy it when creating a new module. `Users` is the fuller example: relations, filters, sorting, extra validation.

Register every new module in `bootstrap/providers.php`. `CoreServiceProvider` must appear first (registers the `module_path()` helper).

---

## ServiceProvider

Each module registers its own translations and routes:

```php
public function boot(): void
{
    $this->loadTranslationsFrom(module_path('Configs/Groups', 'Lang'), 'groups');
    $this->mapApiRoutes();
}

protected function mapApiRoutes(): void
{
    Route::prefix('api/v1')
        ->middleware('api')
        ->group(module_path('Configs/Groups', 'Routes/api.php'));
}
```

- Every module registers under `api/v1`, `Auth` included
- Middleware stack: `'api'` on the prefix, then `auth:web` inside the route group

---

## Routes

```php
// Routes/api.php
Route::middleware('auth:web')->group(function () {
    Route::post('groups/{uuid}/restore', [GroupController::class, 'restore'])->middleware('permission:update-groups');

    Route::get('groups', [GroupController::class, 'index'])->middleware('permission:read-groups');
    Route::post('groups', [GroupController::class, 'store'])->middleware('permission:create-groups');
    // ...
});
```

**Never use implicit route model binding.** Always use `string $uuid` parameters and resolve manually via the service, so the internal ids never reach the client.

Every route declares its permission with the `permission:` middleware. Permission names follow `{verb}-{resource}` (`read-users`, `create-groups`) and must exist in `database/data/Permissions.json`.

---

## Models

```php
declare(strict_types=1);

#[Fillable(['name'])]
class Group extends Model
{
    use HasUuid, SoftDeletes;
}
```

Rules:

- `declare(strict_types=1)` on every file
- Use PHP 8 attribute `#[Fillable([...])]` — never `protected $fillable`
- Use `HasUuid` trait **only if** the table has a `uuid` column
- Use `SoftDeletes` **only if** the table has a `deleted_at` column
- No class-level PHPDoc blocks on models

### HasUuid trait

Located at `app/Modules/Core/Traits/HasUuid.php`. Auto-generates a UUID on model creation. Apply to every model whose records are exposed to the frontend.

---

## Services

Controllers never access models directly — all DB logic goes in the service.

Rules:

- Every public method has a single-line PHPDoc comment explaining what it does. The constructor is
  exempt: a docblock that only repeats the class name is noise
- UUID-based lookup: `findByUuid` (nullable) + `findByUuidOrFail` (aborts 404)
- `findByUuidOrFail` uses `abort(404, __('module::messages.not_found'))` — never throws an exception manually
- For restore operations, add `findTrashedByUuidOrFail` using `onlyTrashed()`. `restore()` receives a model, not a UUID — the controller resolves it first
- The frontend only ever sends UUIDs. Translating them into foreign keys is the **service's** job (see `UserService::roleIdsFromUuids`), never the controller's

### Auth in services

Services must **never** call `Auth::id()`, `Auth::guard(...)`, `auth()->user()`, or `request()->user()`. The controller extracts the authenticated user and passes it (or its ID) as a method parameter. Using `Auth::` in a **controller** is allowed — controllers are the auth boundary.

---

## Controllers

Rules:

- Inject services via constructor property promotion
- Route parameters are always `string $uuid` (never model injection)
- Every public method has a single-line PHPDoc comment, except the constructor
- Responses always wrap in `['data' => ..., 'message' => ...]`; store returns HTTP 201
- **Always extract `findByUuidOrFail` / `findTrashedByUuidOrFail` to a local variable** before passing to the service — never nest the call inline

---

## JSON Resources

Resources never expose `id` — only `uuid` and business fields.

- `index` returns `Resource::collection($paginator)` — Laravel wraps it in `{ data: [], meta: { ... } }` automatically
- `show` / `store` / `update` return `['data' => Resource::make($model)]`
- Every controller action that returns Eloquent model data **must** use a Resource — never return `$model->toArray()` or inline arrays built from model fields
- Responses from raw DB queries or computed aggregates (stats, charts) do not require Resources
- **All response array keys must be `snake_case`** — the frontend's `axios-case-converter` middleware converts `snake_case` → `camelCase` on every response, so TypeScript types stay camelCase while the wire format is snake_case

---

## Form Requests

Every module has **two** Request classes:

- `XxxRequest` — create/update validation
- `XxxFilterRequest` — index/datatable validation (search, pagination, sort, filters)

`XxxFilterRequest` uses three global helpers from `app/Modules/Core/Helpers/datatable.php`, which merge base pagination/search/sort rules with any module-specific additions:

```php
public function rules(): array
{
    return dataTableFilterRules([
        'only_trashed' => ['nullable', 'boolean'],
    ]);
}

public function attributes(): array { return dataTableFilterAttributes([]); }
public function messages(): array { return dataTableFilterMessages([]); }
```

Base translations live in `app/Modules/Core/Lang/{en,es,pt}/validation.php` under the `core` namespace.

---

## DataTable Service

Every module has a dedicated `XxxDataTableService` separate from `XxxService`. The controller injects **both** and uses the DataTableService for `index()`.

### Sorting

The SPA sends the sort as `sort_by[0][key]` + `sort_by[0][order]`.

`key` is the **field name as the API returns it** (camelCase), never the DB column: the wire format
is snake_case but `axios-case-converter` only converts *keys*, and this one travels as a *value*.
Each `SORTABLE` map translates it to the real column:

```php
private const SORTABLE = [
    'firstName' => 'first_name',
    'fullName' => 'first_name',
    'email' => 'email',
];
```

Resolve it with the `dataTableSort()` helper, never pass the raw key to `orderBy` — that is an SQL
injection hole. An unknown key falls back to the resource's default order instead of erroring:

```php
$sort = dataTableSort($sortBy, self::SORTABLE, 'firstName');
// ->orderBy($sort['column'], $sort['order'])
```

Only the first criterion is used.

---

## Permissions

- The catalog is a **fixed list** (`modules` + `permissions`): adding a permission means editing `database/data/Permissions.json` and re-seeding
- The matrix has two levels: `permissions.module_id` (nullable, the tab) and `permissions.group` (the row). A permission with no module falls into the `general` tab
- The pivots (`permission_role`, `permission_user`) carry real foreign keys with `cascadeOnDelete`, so deleting a permission or a user clears its assignments — no cleanup job needed
- `PermissionsServiceProvider::registerGate()` resolves every ability as a permission, so `$user->can('read-roles')`, the `permission:` middleware and policies share one source of truth
- Users hold roles through the `role_user` pivot and can have several; their permissions add up
- The `admin` role has a full bypass via `Gate::before`. It is not a role with every checkbox ticked — un-ticking one could otherwise lock everyone out of the permissions screen

---

## Queues and scheduled tasks

Queues run on Redis through Horizon, configured in `config/horizon.php`. Horizon's own docs cover
supervisors and batches — what matters here:

- The Horizon panel is guarded by the `horizon.auth` middleware (basic auth, `HORIZON_BASIC_AUTH_*`), not by the app session. It closes when either credential is empty, and it must only be served over HTTPS
- `job_batches` and `failed_jobs` point at an explicit connection, not `default`

`app:example` is the template command — it queues a job that only writes to the log. Schedule new
ones in `routes/console.php`, which ships with **no active task**: the starter kit must not run
anything on its own.

---

## Files

Two disks, split by who may read the file:

| Disk     | Written to            | Served by                            | For                    |
| -------- | --------------------- | ------------------------------------ | ---------------------- |
| `public` | `storage/app/public`  | static under `/storage/...`          | Logos, branding assets |
| `local`  | `storage/app/private` | `GET api/v1/files/...`, with session | User uploads           |

- The public disk needs `php artisan storage:link`; the private one must never be symlinked
- The private disk sets `url` to `FILES_BASE_URL`, so `Storage::url()` already returns the masked URL.
  Always name the disk when storing (`$file->store('docs', 'local')`) — picking it is the only
  decision the calling code makes
- `FileController` (`GET api/v1/files/{folder}/{filename}`) streams files off the **private** disk, so
  the URL says nothing about where the file lives and switching driver does not invalidate stored URLs
- The route sits inside an `auth:web` group. Per-file authorization — who owns it, who may read it —
  is the app's job. Never serve the public disk through it, and never put user uploads on the public one
- Both path segments are checked against `^[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*$` before touching the
  disk. A segment starting with a dot or equal to `..` would escape the storage root

## Password recovery

- `POST auth/forgot-password` and `POST auth/reset-password` are public; the emailed token is the credential
- `User::sendPasswordResetNotification()` is overridden so the link points at the SPA (`{FRONTEND_URL}/auth/reset-password`) instead of a server-rendered route
- `forgotPassword` answers the same whether or not the email exists — the endpoint must not reveal which accounts are registered
- The notification is **not** queued on purpose: regaining access should not depend on a worker being alive

---

## Translations

- **Three languages are mandatory: `en`, `es`, `pt`.** Every translation file must exist in all three locales with the **exact same keys** — never add a key to one locale without adding it to the other two. The frontend sends the user's language via the `Accept-Language` header, which `SetLocale` middleware reads to set the app locale; a missing key silently falls back to `en`
- `Lang/{en,es,pt}/messages.php` — CRUD success/error messages used in controllers
- `Lang/{en,es,pt}/validation.php` — validation error messages used in FormRequests
- Namespace registered in ServiceProvider = feature name only (e.g. `'groups'`, `'users'`)
- `pt` is Brazilian Portuguese (e.g. role → "função", delete → "excluído"). Keep placeholders (`:max`, `:min`, `:attribute`) and array keys identical across locales — translate only the string values

---

## Settings — Select-Input Endpoints

`SettingsController` exposes dedicated endpoints for populating select/autocomplete inputs. These return **unpaginated** arrays and are separate from the CRUD datatable endpoints. Never use datatable endpoints (`v1/users`, `v1/groups`) with a large `per_page` to fill selects.

| Endpoint               | Response                          | Purpose                            |
| ---------------------- | --------------------------------- | ---------------------------------- |
| `GET settings/roles`   | `{ data: [{uuid,name,slug}] }`    | All roles (`name` = display_name)  |
| `GET settings/groups`  | `{ data: [{uuid,name}] }`         | All groups                         |

The frontends consume them from a single shared hook, so a select never hits a datatable endpoint.

---

## Related projects

- `vue-starter-kit` — the Vue 3 SPA that consumes this API
- `react-starter-kit` — the React 19 SPA that consumes this API
