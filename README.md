# PHP Code Test

This is a coding test project to demonstrate PHP/Laravel development skills with a focus on code quality, clarity, and maintainability.

## Features

- Authentication with Laravel Sanctum
- Role-based authorization (Administrator, Manager, User)
- User Create, Read operations
- Search and sort users
- Pagination
- Order count per user

## Requirements

- PHP 8.2+
- Composer
- MySQL/PostgreSQL/SQLite

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## API Endpoints

### Authentication

#### Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

### Users

#### List Users (Requires Authentication)

```http
GET /api/users
Authorization: Bearer {token}
```

Query Parameters:

- `search`: Search by name or email
- `sortBy`: Sort by field (name, email, created_at)
- `page`: Page number

Example:

```http
GET /api/users?search=john&sortBy=name&page=1
```

Response:

```json
{
    "page": 1,
    "users": [
        {
            "id": 1,
            "email": "john@example.com",
            "name": "John Doe",
            "role": "user",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "orders_count": 5,
            "can_edit": true
        }
    ]
}
```

#### Create User

```http
POST /api/users
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

## Database Schema

### Users Table

- `id` - Primary key
- `name` - User name
- `email` - Unique email
- `password` - Hashed password
- `role` - User role (administrator/manager/user)
- `active` - Active status
- `email_verified_at` - Verification timestamp
- `created_at` - Creation timestamp

### Orders Table

- `id` - Primary key
- `user_id` - Foreign Key (users.id)
- `created_at` - Creation timestamp

## Testing

```bash
php artisan test
```
