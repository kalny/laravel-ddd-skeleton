# Laravel DDD Skeleton

[![Build Status](https://github.com/kalny/laravel-ddd-skeleton/actions/workflows/tests.yml/badge.svg)](https://github.com/kalny/laravel-ddd-skeleton/actions/workflows/tests.yml)
[![codecov](https://codecov.io/github/kalny/laravel-ddd-skeleton/graph/badge.svg?token=AZ8QKOQNHL)](https://codecov.io/github/kalny/laravel-ddd-skeleton)

A starter template for building **Domain-Driven Design (DDD)** applications with Laravel.

This repository demonstrates how to organize a Laravel project using a **clean architecture approach**, separating business logic from framework and infrastructure concerns.

Includes a fully working **User Registration example** that shows how all layers interact: Domain, Application, Infrastructure, API, and tests.

---

# Architecture Overview

```
[HTTP Request] → [Controller] → [UseCase] → [Domain Entities & ValueObjects] → [Infrastructure Repository] → [Database]
```

Folder structure:

```
app
 ├── Shared
 │    ├── Domain
 │    │    ├── Exceptions
 │    │    └── ValueObjects
 │    ├── Application
 │    │    └── Services
 │    └── Infrastructure
 │         └── Services
 └── Identity
      ├── Domain
      │    └── User
      │         ├── Events
      │         ├── Exceptions
      │         └── Repositories
      │
      ├── Application
      │    ├── Services
      │    └── UseCases
      │         ├── ChangeUserPassword
      │         ├── ChangeUserEmail
      │         └── RegisterUser
      │
      └── Infrastructure
           ├── Persistence
           │    └── Eloquent
           │         ├── Models
           │         └── Repositories
           ├── Services
           ├── DomainEventListeners
           ├── IntegrationEvents
           └── IntegrationEventListeners
```

### Layer Responsibilities

**Domain**
- Pure business logic
- Entities, Value Objects, Domain Events
- No framework dependencies

**Application**
- Use Cases and Application Services Interfaces
- Coordinates domain objects
- Independent from Laravel

**Infrastructure**
- Database implementation
- Application Services realization
- External integrations

---

# Features

- Predefined **DDD directory structure**
- Fully working **User Registration flow**
- Clean **Domain Model**
- **Application layer independent from framework**
- Infrastructure layer contains all technical implementations
- **Domain Events** support (example listeners log events)
- **Unit & Feature tests**
- Preconfigured **Docker + docker-compose** for local development

---

# Installation

Clone the repository:

```bash
git clone https://github.com/kalny/laravel-ddd-skeleton.git
cd laravel-ddd-skeleton
```

Set up environment files:

```bash
cp .env.example .env
cp .env.testing.example .env.testing
# edit variables in .env and .env.testing as needed
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
cp docker/.env.example docker/.env
# edit variables in .env as needed
```

Build containers:

```bash
make build
```

Start containers:

```bash
make start
```

---

# Running Tests

Enter the container:

```bash
make shell
```

Run all tests:

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
  "email": "username@gmail.com",
  "password": "password"
}
```

Example response:

```json
{
  "id": "63a5e14f-d26d-48b7-81a7-c568a1c17a75",
  "email": "username@gmail.com"
}
```

---

# Example Use Cases

### Register User (simplified)

```php
if ($this->users->existsByEmail(Email::fromString($command->email))) {
    throw UserAlreadyExistsException::withValue($command->email);
}

$id = $this->idGenerator->generate();

$user = User::register(
    UserId::fromString($id),
    Email::fromString($command->email),
    $this->hasher->hash(PlainPassword::fromString($command->password))
);

$this->users->save($user);
```

### Change User Password (simplified)

```php
$user = $this->users->get($id);

$user->changePassword(
    $this->hasher->hash(PlainPassword::fromString($command->password))
);

$this->users->save($user);
```

### Change User Email (simplified)

```php
$user = $this->users->get($id);

$email = Email::fromString($command->email);

if ($this->users->existsByEmail($email) && !$user->hasEmail($email)) {
    throw EmailAlreadyTakenException::withValue($command->email);
}

$user->changeEmail($email);

$this->users->save($user);
```

---

# Why This Project

This skeleton demonstrates how to build **maintainable Laravel applications using DDD principles**:

- Business logic isolated from framework
- High testability
- Clear separation of responsibilities
- Easy scaling for complex domains

**Use cases**:

- Learning DDD in Laravel
- Starting point for production projects

---

# Author

**Anton Kalnyi**

---

# License

MIT
