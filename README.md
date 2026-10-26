# Simple Task List

A single-page app to create, view, and complete tasks.

**Stack:** Laravel 13 (REST API) · Vue 3 + Vite · MySQL

## Requirements

- PHP 8.3+ with the `pdo_mysql` extension
- Composer 2
- Node.js 20+
- MySQL 8 (developed and tested on MariaDB via XAMPP, which is compatible)

## Setup

### 1. Database

```bash
mysql -u root -e "CREATE DATABASE task_list CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Backend

```bash
cd backend
composer install
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate
```

Update the `DB_*` values in `.env` if your MySQL credentials differ, then create the tables:

```bash
php artisan migrate
php artisan serve           # http://127.0.0.1:8000
```

> Alternatively, `database/schema.sql` creates the `tasks` table without Laravel.
> Use either the migration or the script, not both.

### 3. Frontend

In a second terminal:

```bash
cd frontend
npm install
npm run dev                 # http://localhost:5173
```

Open **http://localhost:5173**. The backend must be running.

## API

| Method | Endpoint      | Description                 | Success | Errors |
|--------|---------------|-----------------------------|---------|--------|
| GET    | `/tasks`      | List all tasks, newest first | `200`   |        |
| POST   | `/tasks`      | Create a task               | `201`   | `422`  |
| PATCH  | `/tasks/{id}` | Mark a task as completed    | `200`   | `404`  |

Example:

```bash
curl -X POST http://127.0.0.1:8000/tasks \
  -H "Content-Type: application/json" \
  -d '{"title": "Buy milk"}'
```

```json
{ "data": { "id": 1, "title": "Buy milk", "completed": false, "created_at": "2026-10-26T11:35:15.000000Z" } }
```

**Validation:** `title` is required, must be a string, and at most 100 characters.
Whitespace-only titles are rejected (Laravel trims input before validating).

Errors always return JSON:

```json
{ "message": "The title field is required.", "errors": { "title": ["The title field is required."] } }
{ "message": "Task not found." }
```

## Tests

```bash
cd backend
php artisan test
```

Feature tests cover task creation, title validation (empty, whitespace, 100 vs 101 characters),
ordering, completion, and the 404 response. They run on an in-memory SQLite database
and do not touch your MySQL data.

## Decisions

- **Routes without the `/api` prefix**, to match the spec exactly (`apiPrefix: ''` in `bootstrap/app.php`).
- **Errors are always JSON** for `/tasks` routes, even without an `Accept` header.
- **`PATCH /tasks/{id}` takes no body.** It only marks a task as completed, as the spec describes, and is idempotent.
- **Vite dev proxy instead of CORS.** The frontend calls `/tasks` on its own origin and Vite forwards it to Laravel.
- **The backend is the single source of truth for validation.** The frontend shows a character counter as a hint, and displays the API's error messages.
- **New tasks are added to the top of the list from the API response**, without refetching the whole list.

## Unfinished / Possible improvements

- Pagination for large task lists, with an index on `created_at`.
- Undo completion, edit and delete (not required by the spec).
- Frontend tests (e.g. Vitest).
- Production build setup: the Vite proxy only works in development, so a deployed frontend would need CORS configured on the API.
- Laravel's default tables (`users`, `cache`, `jobs`, `sessions`) are left in place; they are unused by this app.