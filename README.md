# IVANTECH — PHP Conversion (Placeholder)

This folder is the starting PHP scaffold for converting the IVANTECH React app into a PHP + MySQL application.

Requirements
- PHP 8.1+ with PDO MySQL
- MySQL / MariaDB
- Apache / Nginx or PHP built-in server

Quick start (XAMPP / Laragon / WAMP)

1. Copy this folder into your web root (e.g. `C:/xampp/htdocs/ivantech`).
2. Create a database and import `database.sql`:

```sql
CREATE DATABASE ivantech;
USE ivantech;
-- import database.sql file
```

3. Configure database connection: copy `.env.example` to `.env` and edit values.
4. Run the seeder to create demo accounts (this will hash passwords):

```bash
php scripts/seed.php
```

5. Start the server and open `http://localhost/ivantech`.

Notes
- Copy the `assets/images` from the original React project into `assets/images/` to preserve branding and product images.
- This scaffold includes the main schema and basic pages. Next steps: implement admin/customer pages, CRUD actions, uploads, CSRF protection, and full UI conversion from the React components.
