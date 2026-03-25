# Symfony API Project

This project is a REST API built with Symfony 8.5.  
It provides endpoints for managing users and phone numbers.

## Features

- Symfony 8
- REST API
- MongoDB (Doctrine ODM)
- Docker support
- PHPUnit tests (>90% coverage)
- Symfony Messenger (queue)
- Validation
- JSON responses
- Aggregation queries
- Clean architecture
- External API integration (IpLocate)

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

MongoDB:
```bash
mongodb://root:root@localhost:27017
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

## Count users

```
GET /users/count
```

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

## OpenAPI documentation

API documentation is generated automatically using OpenAPI.
Available at:

```
/doc
```

Example:
```
[http://localhost:8081/users?limit=10&cursor=0&sort=asc](http://localhost:8081/doc)
```

Features:
	•	Auto generated schema
	•	DTO support
	•	Request / Response description
	•	Swagger UI


## 📨 Queue

Project uses Symfony Messenger.

Message → Handler → Service → Repository

Run consumer:

```bash
php bin/console messenger:consume async
```

## 🧪 Tests

Run tests:

```bash
php bin/phpunit --coverage-text
```

Includes:
	•	DTO tests
	•	Service tests
	•	Handler tests
	•	Resolver tests
	•	Repository tests (with mocks)
	•	IpLocate tests
Rules:
	•	Unit tests only
	•	No DB connection
	•	No external services
	•	Using mocks
	•	Coverage > 90%


## Author

Nikita Burmak
