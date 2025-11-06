# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CodeIgniter 4 application using SQLite3 as the database. The project includes CodeIgniter Shield for authentication and authorization, with a basic banking/account management system implementation.

## Development Commands

### Starting the Development Server
```bash
php spark serve --host project.lan --port 8080
```

### Database Management
```bash
# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Run seeders
php spark db:seed SeedClients
php spark db:seed SeedAccounts
php spark db:seed SeedTestClients
php spark db:seed SeedTestAccounts

# Check database tables
php spark app:test-db-tables
```

### Testing
```bash
# Run all PHPUnit tests
composer test
# or
vendor/bin/phpunit

# Run a specific test file
vendor/bin/phpunit tests/unit/HealthTest.php

# Run Behat tests (requires configuration)
vendor/bin/behat
```

### Code Quality
```bash
# Run PHP CS Fixer (code style)
vendor/bin/php-cs-fixer fix

# Dry run to see what would be fixed
vendor/bin/php-cs-fixer fix --dry-run
```

### User Management (via Shield)
```bash
# Custom command to manage users
php spark app:manage-users
```

## Architecture

### Database Configuration
- **Driver**: SQLite3
- **Database Path**: `writable/database/store.db`
- **Test Database**: Uses same SQLite file with `test_` prefix for tables
- **Auto-switching**: Tests automatically use the test database group when `ENVIRONMENT === 'testing'`

The database configuration is in `app/Config/Database.php:54-79` with the default group set to SQLite3.

### Authentication with CodeIgniter Shield
This project uses CodeIgniter Shield (v1.1+) for authentication. Shield provides:
- Session-based authentication (default)
- Access token authentication
- JWT authentication
- HMAC SHA256 authentication
- User management and groups
- Auth routes automatically registered via `service('auth')->routes($routes)` in `app/Config/Routes.php:12`

### Data Model Architecture
The application follows a Client-Account banking model:

1. **Shield Users** → Created by CodeIgniter Shield for authentication
2. **Clients** (`clients` table) → Linked to Shield users via `user_id`
3. **Accounts** (`accounts` table) → Linked to Clients via `client_id`

**Important**: The relationship chain is: `User (Shield) → Client → Account(s)`

#### Models
- `ClientModel` (`app/Models/ClientModel.php`) - Manages client data
- `AccountModel` (`app/Models/AccountModel.php`) - Manages accounts with Entity return type
- `TestClientModel` and `TestAccountModel` - Test-specific models for test tables

#### Entities
- `Account` Entity (`app/Entities/Account.php`) - Represents account objects with business logic

### Database Migrations
Migrations are located in `app/Database/Migrations/`:

### Routing Structure
Routes are defined in `app/Config/Routes.php`:
- `/` - Home page (public)
- `/accounts` - View user's accounts (requires authentication)
- `/accounts/transfer` - POST endpoint for account transfers
- Shield auth routes are auto-registered (login, register, logout, etc.)

### Controllers
- `Home` (`app/Controllers/Home.php`) - Landing page
- `Account` (`app/Controllers/Account.php`) - Account management
  - `index()` method retrieves accounts for authenticated user by:
    1. Getting current user via `auth()->user()`
    2. Finding associated client record via `user_id`
    3. Loading all accounts for that client
    4. Passing to `accounts` view

## Directory Structure

```
app/
├── Commands/          - Custom CLI commands
├── Config/            - Configuration files (Routes, Database, Auth, etc.)
├── Controllers/       - HTTP controllers
├── Database/
│   ├── Migrations/   - Database schema migrations
│   └── Seeds/        - Database seeders
├── Entities/         - Entity classes for domain models
├── Models/           - Data models extending CodeIgniter Model
└── Views/            - View templates

features/             - Behat feature files for acceptance testing
tests/
├── unit/            - PHPUnit unit tests
├── database/        - Database-related tests
└── _support/        - Test support files

public/              - Public web root
system/              - CodeIgniter 4 framework (vendor)
writable/
├── database/        - SQLite database file location
└── [logs, cache, etc.]
```

## Important Technical Notes

### PHP Version and Extensions
- **Required PHP**: 8.1+ (current: 8.3)
- **Required Extensions**: `intl`, `mbstring`, `sqlite3`, `curl`
- The `php_sqlite3.dll` must be in the PATH

### Environment Configuration
- Environment variables are in `.env` file (gitignored)
- Use `WRITEPATH` constant for database path - it automatically points to `writable/`
- Test environment automatically uses test database group

### CodeIgniter 4 Patterns
- **Service Pattern**: Custom services defined in `app/Config/Services.php`
- **Model Pattern**: Models use `$returnType` to specify Entity or array returns
- **Validation**: Defined in models via `$validationRules` and `$validationMessages` properties
- **CLI Commands**: Custom commands extend `BaseCommand` and are auto-discovered

### Testing Setup
- PHPUnit configuration in `phpunit.xml.dist`
- Test bootstrap: `system/Test/bootstrap.php`
- Tests use SQLite with `test_` table prefix
- Behat is configured but requires Selenium WebDriver for acceptance tests
- The `features/dawan_selenium.feature` demonstrates Behat usage for web testing

### Seeders and Test Data
- Separate seeders for production (`SeedClients`, `SeedAccounts`) and test data (`SeedTestClients`, `SeedTestAccounts`)
- Test tables use `test_` prefix to isolate test data

### Code Style
- PHP CS Fixer configured with Symfony ruleset
- Risky rules enabled
- Cache file: `var/.php-cs-fixer.cache`

### Git Usage
- IMPORTANT rules: @context/git_rules.md
