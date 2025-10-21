# my_raw_php_task

1. A minimal raw PHP REST API (v1) with CRUD for `users` for checking.
2. Now working for the office task as per testing me

## Features
- Simple MVC-like organization (not a framework)
- REST API endpoints returning JSON
- PDO (MySQL) for database access
- Instructions for Laragon local setup
- SQL dump included
- Basic Migration system with CMD

## Requirements
- PHP 7.4+ (with PDO MySQL)
- MySQL (Laragon includes it)
- Apache with mod_rewrite (Laragon default)

## Setup (Laragon)
1. Move the `my_raw_php_task` folder into Laragon's `www` directory (e.g. `C:/laragon/www/my_raw_php_task`).
2. Start Laragon (Apache + MySQL).
3. Import the SQL file (`create_db.sql`) using phpMyAdmin or the MySQL CLI.
4. Ensure DB credentials in `app/config/config.php` match your Laragon settings (default is root / empty password).
5. Access the API:
   - List users: `GET http://localhost/my_raw_php_task/public/api/users`
   - Get user: `GET http://localhost/my_raw_php_task/public/api/users/{id}`
   - Create user: `POST http://localhost/my_raw_php_task/public/api/users` (JSON body: {"name":"...","email":"..."})
   - Update user: `PUT http://localhost/my_raw_php_task/public/api/users/{id}` (JSON body)
   - Delete user: `DELETE http://localhost/my_raw_php_task/public/api/users/{id}`

## Notes
- This project intentionally keeps things simple and readable for learning.
- This project is not only for learning purpose, but also a testing project that I understand Raw PHP
- Next steps: add input validation, error handling, and JWT authentication.

