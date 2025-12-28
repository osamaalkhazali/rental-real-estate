# MyRentals

Rental property management app for tracking apartments, leases, utilities (water and electric), expenses, payments, and images.

## Tech Stack

- Laravel 11 (PHP 8.2+)
- MySQL or MariaDB
- Vite + Tailwind CSS
- Pest for tests

## Quick Start (local)

1) Install PHP, Composer, Node.js, and a MySQL/MariaDB instance.
2) Clone the repo and install dependencies:
   ```bash
   composer install
   npm install
   ```
3) Copy the environment template and set your values (DB, mail, etc.):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4) Run migrations and seed demo data (creates admin/user seeders if configured):
   ```bash
   php artisan migrate --seed
   ```
5) Link storage for public uploads:
   ```bash
   php artisan storage:link
   ```
6) Build assets (or run the dev server):
   ```bash
   npm run build    # or npm run dev
   ```
7) Start the app:
   ```bash
   php artisan serve
   ```

Visit http://localhost:8000 and log in with the seeded credentials (see database/seeders if provided).

## Environment Notes

- Database defaults are in .env.example; update for your local DB name, user, and password.
- Queues, mail, and file storage can all run in sync mode for local development; switch drivers in .env when needed.

## Testing

- Run the suite with Pest:
  ```bash
  php artisan test
  ```

## Common Issues

- If assets do not load, rerun npm run dev (or npm run build) and ensure php artisan storage:link has been executed.
- If migrations fail, confirm DB credentials and that the database exists before running php artisan migrate --seed.
