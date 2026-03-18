# Laravel DDD Skeleton

[![codecov](https://codecov.io/github/kalny/laravel-ddd-skeleton/graph/badge.svg?token=AZ8QKOQNHL)](https://codecov.io/github/kalny/laravel-ddd-skeleton)

A starter template for building **Domain-Driven Design (DDD)** applications with Laravel.

This repository demonstrates how to organize a Laravel project using a **clean architecture approach**, separating business logic from framework and infrastructure concerns.

The project includes a fully working **user registration example** that shows how all layers interact: Domain, Application, Infrastructure, API, and tests.

---

# Architecture

The project follows a layered DDD architecture:

```
app
 ├── Domain
 │    ├── Common
 │    │    └── Exceptions 
 │    └── User
 │         ├── Events
 │         ├── Exceptions
 │         └── Repository
 │
 ├── Application
 │    ├── Ports
 │    ├── Services
 │    └── UseCase
 │         └── RegisterUser
 │
 └── Infrastructure
      ├── Persistence
      │    └── Eloquent
      │         ├── Models
      │         └── Repositories
      └── Services
```

### Layer responsibilities

**Domain**
- Pure business logic
- Entities, Value Objects, Domain Events
- No framework dependencies

**Application**
- Use cases
- Application services interface
- Coordinates domain objects
- Independent from Laravel

**Infrastructure**
- Database implementation
- Application services realization
- External integrations

---

# Features

- Predefined **DDD directory structure**
- Example **User Registration flow**
- Clean **Domain Model**
- **Application layer independent from the framework**
- Infrastructure layer contains all technical implementations
- **Domain Events** support (example listeners write events to logs)
- **Unit tests and Feature tests**
- Preconfigured **Docker environment**

---

# Installation

Clone the repository:

```bash
git clone https://github.com/kalny/laravel-ddd-skeleton.git
cd laravel-ddd-skeleton
```

Setup environment:

```bash
cp .env.example .env
cp .env.testinf.example .env.testing
# then set up variables in .env and .env.testing files
```

Install dependencies:

```bash
composer install
```

Run migrations:

```bash
php artisan migrate
php artisan migrate --env=testing
```

---

# Running the Project in Docker

Create docker environment:

```bash
cp docker/.env.example .env
# then set up variables in .env file
```

Build containers:

```bash
make build
```

Start the project:

```bash
make start
```

---

# Running Tests

Enter the container:

```bash
make shell
```

Run tests:

```bash
php artisan test
```

---

# Example API

### Register User

```
POST /api/auth/register
```

Request body:

```json
{
  "name": "username",
  "email": "username@gmail.com",
  "password": "password"
}
```

---

# Why This Project

This skeleton demonstrates how to build **maintainable Laravel applications using DDD principles**:

- Business logic isolated from framework
- High testability
- Clear separation of responsibilities
- Easy scaling for complex domains

It can be used as a **starting point for production projects** or as a **learning reference for DDD in Laravel**.

---

# Author

**Anton Kalnyi**

---

# License

MIT
