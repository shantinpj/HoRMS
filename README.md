# House Rent Management System

A simplified House Rent Management system built with PHP and SQLite.

## Features
- **Dashboard**: Overview of total houses, active tenants, and rent collected.
- **House Management**: Add, view, and list houses.
- **Tenant Management**: List tenants.
- **Database**: SQLite-powered data storage.
- **UI**: Clean interface using Tailwind CSS.

## How to Run
1. Ensure you have PHP installed.
2. Run the migration script to set up the database:
   ```bash
   php migrate.php
   ```
3. Start the PHP built-in server:
   ```bash
   php -S localhost:8000 -t public
   ```
4. Open your browser and visit `http://localhost:8000`.

## Project Structure
- `app/`: Contains the core logic, controllers, and models.
- `public/`: The web root containing the entry point `index.php`.
- `database/`: Contains the SQLite database file.
- `migrate.php`: Script to initialize database tables.
