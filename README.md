# Layered Todo REST API

A production-ready, versioned Todo REST API built with **Laravel 10**, featuring a strict layered architecture, **JWT authentication**, and **asynchronous Redis queue processing**.

The application is designed to demonstrate clean separation of concerns using the following flow:

**Controller → Service → Repository**

---

## Tech Stack

| Technology | Purpose |
|---|---|
| PHP 8.2 | Application runtime |
| Laravel 10 | REST API framework |
| JWT (`tymon/jwt-auth`) | API authentication |
| MySQL | Relational database |
| Redis | Queue backend and asynchronous processing |
| Docker Sail | Local containerized development environment |

---

## Architecture

The application follows a layered architecture to keep responsibilities clearly separated:

```text
HTTP Request
     │
     ▼
Controller
     │
     ▼
FormRequest
     │
     ▼
Service
     │
     ▼
Repository Interface
     │
     ▼
Repository Implementation
     │
     ▼
Eloquent / Database
```

### Controllers

Controllers are intentionally kept thin. Their responsibilities include:

- Receiving HTTP requests
- Delegating validation to Form Requests
- Calling the appropriate Service
- Returning API responses

Business logic should not be placed directly inside controllers.

### Services

Services contain application and business logic.

They coordinate operations between controllers, repositories, jobs, and other application components without coupling the HTTP layer directly to database implementations.

### Repositories

The Repository Pattern provides an abstraction between the application and Eloquent.

Repository interfaces are bound to their concrete implementations through `AppServiceProvider`, allowing the underlying persistence implementation to be changed without requiring changes to the service layer.

---

## Key Features

### JWT Authentication

The API uses JWT authentication through `tymon/jwt-auth`.

Authenticated requests use the API guard and the authenticated user's ID is used to scope Todo operations.

### User Isolation

Todo records and background operations are strictly scoped to the authenticated user.

```php
auth('api')->id()
```

This ensures that users can only access and manipulate their own Todo records.

### Asynchronous Bulk Processing

Bulk Todo actions are processed asynchronously using Laravel Jobs and Redis.

The flow is:

```text
API Request
    │
    ▼
Dispatch BulkCompleteTodosJob
    │
    ▼
Redis Queue
    │
    ▼
Queue Worker
    │
    ▼
Process Todos
    │
    ▼
Update job_statuses
```

Instead of keeping the HTTP request open while a potentially large operation executes, the API dispatches a `BulkCompleteTodosJob` to Redis and returns a tracking UUID.

The client can then use the tracking UUID to poll the operation status.

---

## Prerequisites

Make sure the following are installed on your development machine:

- Docker
- Docker Compose
- Git

The application runs PHP, MySQL, and Redis through Docker Sail, so PHP and Composer do not need to be installed directly on the host machine.

---

## Local Installation

### 1. Clone the Repository

```bash
git clone git@github.com:schidobvu/hyve-todo-api.git
cd hyve-todo-api
```

### 2. Install PHP Dependencies

If dependencies are not already installed:

```bash
docker run --rm     -u "$(id -u):$(id -g)"     -v "$(pwd):/var/www/html"     -w /var/www/html     laravelsail/php82-composer:latest     composer install
```

Alternatively, if Composer is available locally:

```bash
composer install
```

### 3. Configure the Environment

Create the local environment file:

```bash
cp .env.example .env
```

Make sure the database and Redis configuration matches the Docker Sail services defined by the project.

### 4. Start Docker Containers

Start the application containers:

```bash
./vendor/bin/sail up -d
```

Check the running containers:

```bash
./vendor/bin/sail ps
```

### 5. Generate Application and JWT Keys

Generate the Laravel application key:

```bash
./vendor/bin/sail artisan key:generate
```

Generate the JWT secret:

```bash
./vendor/bin/sail artisan jwt:secret
```

### 6. Run Migrations and Seeders

Create the database schema and seed the database:

```bash
./vendor/bin/sail artisan migrate --seed
```

### 7. Start the Queue Worker

Bulk Todo operations are processed asynchronously through Redis.

Start the queue worker:

```bash
./vendor/bin/sail artisan queue:work
```

Keep the queue worker running while testing asynchronous functionality.

---

## Running the Application

Once Sail is running, the API will be available through the application's configured Sail port.

You can check the configured ports with:

```bash
./vendor/bin/sail ps
```

For the default Laravel Sail setup, the application is typically available at:

```text
http://localhost
```

---

## Running Tests

Run the automated test suite inside the Docker Sail environment:

```bash
./vendor/bin/sail test
```

You can also run a specific test file:

```bash
./vendor/bin/sail artisan test tests/Feature/ExampleTest.php
```

For more detailed output:

```bash
./vendor/bin/sail test --verbose
```

---

## Useful Sail Commands

Start the containers:

```bash
./vendor/bin/sail up -d
```

Stop the containers:

```bash
./vendor/bin/sail down
```

View container status:

```bash
./vendor/bin/sail ps
```

View application logs:

```bash
./vendor/bin/sail logs
```

Open a shell inside the application container:

```bash
./vendor/bin/sail shell
```

Run Artisan commands:

```bash
./vendor/bin/sail artisan <command>
```

Run Composer commands:

```bash
./vendor/bin/sail composer <command>
```

---

## Queue Processing

The application uses Redis as its queue backend.

Start the worker with:

```bash
./vendor/bin/sail artisan queue:work
```

For development, the worker can be left running in a separate terminal.

When a bulk operation is submitted, the application:

1. Creates a job status record.
2. Generates a tracking UUID.
3. Dispatches `BulkCompleteTodosJob` to the Redis queue.
4. Returns the tracking UUID to the client.
5. Processes the operation asynchronously.
6. Updates the corresponding `job_statuses` record.
7. Allows the client to poll the operation status.

This approach prevents long-running bulk operations from unnecessarily blocking API requests.

---

## Configuration

Important environment variables include:

```dotenv
APP_NAME=hyve-todo-api
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080
APP_PORT=8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=hyve-todo
DB_USERNAME=sail
DB_PASSWORD=password

QUEUE_CONNECTION=redis

REDIS_HOST=redis
REDIS_PORT=6379
```

Use the values appropriate for your local Sail configuration.

Do not commit your `.env` file or production secrets to source control.

---

## Project Structure

The application follows Laravel's standard directory structure while separating application responsibilities into layers.

A typical structure is:

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
│
├── Jobs/
│
├── Models/
│
├── Repositories/
│   ├── Contracts/
│   └── Eloquent/
│
├── Services/
│
└── Providers/
    └── AppServiceProvider.php

database/
├── factories/
├── migrations/
└── seeders/

routes/
└── api.php

tests/
├── Feature/
└── Unit/
```

The exact structure may vary depending on the implementation.

---

## Design Principles

### Separation of Concerns

Each layer has a clearly defined responsibility:

- **Controllers** handle HTTP concerns.
- **Form Requests** handle request validation.
- **Services** handle business/application logic.
- **Repositories** handle data access.
- **Jobs** handle asynchronous processing.
- **Models** represent persisted data.

### Dependency Inversion

Services depend on repository contracts rather than concrete Eloquent repository implementations.

Bindings are registered through `AppServiceProvider`.

This keeps business logic independent from the persistence implementation.

### Stateless Authentication

JWT authentication allows the API to authenticate requests without relying on server-side session state.

### Asynchronous Processing

Long-running bulk operations are delegated to Redis-backed queue workers rather than being executed entirely within the HTTP request lifecycle.

---

## Testing Strategy

The project includes automated tests covering the API and application behavior.

Tests should verify areas such as:

- Authentication
- Todo creation
- Todo retrieval
- Todo updates
- Todo deletion
- User isolation
- Validation
- Bulk Todo operations
- Job dispatching
- Job status tracking
- Repository/service behavior

Run the full suite with:

```bash
./vendor/bin/sail test
```

---

## Git Workflow

Before committing changes, run the test suite:

```bash
./vendor/bin/sail test
```

Then review the changes:

```bash
git status
git diff
```

Commit the changes:

```bash
git add .
git commit -m "Your commit message"
```

---

## Troubleshooting

### Sail command not found

Make sure dependencies have been installed:

```bash
composer install
```

Then verify that Sail exists:

```bash
ls vendor/bin/sail
```

### Containers are not running

Check the container status:

```bash
./vendor/bin/sail ps
```

Start the containers:

```bash
./vendor/bin/sail up -d
```

### Queue jobs are not processing

Make sure Redis and the queue worker are running:

```bash
./vendor/bin/sail ps
```

Then start the worker:

```bash
./vendor/bin/sail artisan queue:work
```

### Database connection errors

Make sure the MySQL container is running and that the database settings in `.env` match the Sail configuration.

Then run:

```bash
./vendor/bin/sail artisan migrate
```

---

## License

This project is intended for demonstration and development purposes unless a separate license is provided with the repository.
