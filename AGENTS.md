# Repository Guidelines

## Project Structure & Module Organization

- `app/` contains Laravel application code (HTTP layer, Models, Policies, Services, Filament admin).
- `app/Modules/` hosts self-contained feature modules (see `docs/MODULAR_ARCHITECTURE.md`); scaffold with `php artisan module create Blog`.
- `routes/` defines entrypoints (`web.php`, `api.php`, `socialstream.php`, etc.).
- `resources/` holds Blade views and front-end assets (`resources/css`, `resources/js`); Filament admin theme lives at `resources/css/filament/admin/theme.css`.
- `database/` contains migrations/seeders/factories; local dev commonly uses SQLite at `database/database.sqlite`.
- `tests/` contains PHPUnit tests (`tests/Feature`, `tests/Unit`).
- `.docker/` includes Docker/Octane runtime configuration; CI lives in `.github/workflows/` (and `.circleci/`).

## Build, Test, and Development Commands

- `./setup.sh` installs PHP deps, generates an app key, runs `migrate:fresh` + seed, runs PHPUnit, and clears caches.
- `php artisan serve` runs the local dev server.
- `php artisan octane:start --server=roadrunner` runs Octane (optional; RoadRunner binary/config may be used).
- `npm install` installs front-end dependencies (CI targets Node 20).
- `npm run dev` / `npm run build` runs Vite in dev mode / builds production assets.

## Coding Style & Naming Conventions

- PHP: follow Laravel conventions + PSR-12 (4-space indentation, `StudlyCase` classes, `camelCase` methods).
- Formatting: run `./vendor/bin/pint` before pushing.
- Front-end: keep source edits in `resources/` (don’t hand-edit generated files under `public/build`).

## Testing Guidelines

- Run `./vendor/bin/phpunit` (or `php artisan test`). Tests default to `APP_ENV=testing` and `.env.testing` (SQLite `:memory:`).
- Add/adjust tests with changes: Feature tests for HTTP/Livewire/Filament flows; Unit tests for pure logic.

## Commit & Pull Request Guidelines

- This checkout may not include Git history; use Conventional Commits (`feat:`, `fix:`, `refactor:`, `test:`, `docs:`).
- PRs should include: what/why, how to verify, linked issues, and screenshots for UI/admin changes. If you add env vars, update `.env.example`.

## Security & Configuration Tips

- Never commit secrets: `.env`, `vendor/`, and `node_modules/` are ignored; document new config keys and defaults instead.
- Keep changes small and reviewable; prefer extending via `app/Modules/` when adding large features.

## Agent Notes

- Keep edits scoped, match existing patterns, and run `pint` + `phpunit` before finalizing.
