# Portfolio API (Laravel + JWT Auth)

This repository contains the backend API for a personal portfolio
website.\
It is built with Laravel, uses JWT authentication for a single admin
user, and exposes public endpoints for projects, contact messages, and
profile data.

## Features

### Public Endpoints

-   View projects\
-   View profile details\
-   Submit a contact form

### Admin Endpoints (JWT Protected)

-   Login with email/password\
-   Manage projects (CRUD)\
-   Manage profile info\
-   View contact messages\
-   Logout (JWT invalidate)

### Tech Stack

-   Laravel 10+
-   PHP 8.1+
-   tymon/jwt-auth
-   MySQL

## Installation & Setup

1.  Clone repository\
2.  Install dependencies\
3.  Configure `.env`\
4.  Run `php artisan jwt:secret`\
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

5.  Run migrations\
6.  Create admin user\
7.  Start server

## API Endpoints

### Public

GET /api/projects\
GET /api/projects/{id}\
GET /api/profile\
POST /api/contact

### Admin (JWT Protected)

POST /api/admin/login\
POST /api/admin/logout\
POST /api/admin/projects\
PUT /api/admin/projects/{id}\
DELETE /api/admin/projects/{id}\
POST /api/admin/profile\
GET /api/admin/contact
