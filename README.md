# Symfony API Project

This project is a REST API built with Symfony 8.5.  
It provides endpoints for managing users and phone numbers.

## Features

- Symfony 7.4
- REST API
- Docker support
- PHPUnit tests
- Symfony Messenger (queue)
- DTO validation
- JSON responses
- Clean architecture

## Installation

Clone repository:

```bash
git clone https://github.com/NikitaBurmak/Test.git
cd Test
```

Install dependencies:

```bash
composer install
```

## ▶ Run project

### With Docker

```bash
docker-compose up -d
```

App will be available at:

```
http://localhost:8081
```

### Without Docker

```bash
symfony server:start --port=8081
```

or

```bash
php -S localhost:8081 -t public
```

## 📡 API

### Create user

POST /users

Body:

```json
{
  "firstName": "Mykola",
  "lastName": "Ivanov",
  "phoneNumbers": ["+3367843220"]
}
```

---

### Get all users

```
GET /users
```

Example:

```
http://localhost:8081/users
```

---

### Get users with pagination and sorting

```
GET /users?limit=10&cursor=0&sort=asc
```

Parameters:

- limit — number of users
- cursor — offset
- sort — asc / desc

Example:

```
http://localhost:8081/users?limit=10&cursor=0&sort=asc
```

## 📨 Queue

Project uses Symfony Messenger.

User creation request is sent to queue and processed by handler.

Run consumer:

```bash
php bin/console messenger:consume async
```

## 🧪 Tests

Run tests:

```bash
php bin/phpunit
```

Tests included:

- DTO tests
- Controller tests
- Service tests
- Handler tests

Used:

- KernelTestCase
- WebTestCase

## 📁 Project structure

```
src/
tests/
config/
docker/
public/
composer.json
```

## Author

Nikita Burmak
