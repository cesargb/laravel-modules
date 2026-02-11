# Laravel Modules App

A local module management system for Laravel using Composer. Organize your application into independent modules that are installed and uninstalled as Composer packages using `path` type repositories.

## Installation

```bash
composer require --dev cesargb/laravel-modules
```

## Initial Configuration

### 1. Configure the modules directory

Add the repository configuration to your `composer.json`:

```bash
php artisan modules:config
```

## Creating a New Module

### Using the create command

The easiest way to create a new module is using the `modules:create` command:

```bash
php artisan modules:create my-vendor/my-module
```

This command will automatically generate:

- The module directory structure (`modules/my-module/`)
- A `composer.json` file with proper configuration
- A `src/` directory for your module classes
- A Service Provider for the module

#### Command options

```bash
# Create a module with a specific vendor/namespace
php artisan modules:create my-module --vendor=MyCompany --namespace=MyApp\\MyModule

# Create a module with a specific version
php artisan modules:create my-module --version=2.0.0
```

### Manual Module Structure

If you prefer to create the module manually, each module must have its own `composer.json` and follow this structure:

```text
modules/
└── my-module/
    ├── composer.json
    └── src/
        ├── MyModuleServiceProvider.php
        └── ... (module classes)
```

## Module Management Commands

### List available modules

Shows all modules found in the modules directory with their installation status:

```bash
php artisan modules:list
```

### Install modules

Install one or more modules using Composer:

```bash
php artisan modules:install my-module
php artisan modules:install module1 module2 module3
```

### Uninstall modules

Uninstall one or more modules:

```bash
php artisan modules:uninstall my-module
php artisan modules:uninstall module1 module2
```

## Make Commands

All `module:make:*` commands follow the pattern: `php artisan module:make:{type} {module-name} {Name}`

Files are generated inside the module directory at `modules/{module-name}/src/`

### Available commands

#### Basic Commands

```bash
# Create a console command
php artisan module:make:command my-module SendEmailCommand

# Create a generic class
php artisan module:make:class my-module Services/PaymentService

# Create a configuration
php artisan module:make:config my-module payment

# Create a trait
php artisan module:make:trait my-module Concerns/HasUuid

# Create an interface
php artisan module:make:interface my-module Contracts/PaymentGateway
```

#### Models and Database

```bash
# Create a model
php artisan module:make:model my-module User

# Create an observer
php artisan module:make:observer my-module UserObserver

# Create a scope
php artisan module:make:scope my-module ActiveScope

# Create a cast
php artisan module:make:cast my-module Json
```

#### HTTP and API

```bash
# Create a request (Form Request)
php artisan module:make:request my-module StoreUserRequest

# Create a resource
php artisan module:make:resource my-module UserResource

# Create a middleware
php artisan module:make:middleware my-module CheckRole
```

#### Events and Jobs

```bash
# Create an event
php artisan module:make:event my-module UserRegistered

# Create a listener
php artisan module:make:listener my-module SendWelcomeEmail

# Create a job
php artisan module:make:job my-module ProcessPayment

# Create a job middleware
php artisan module:make:job-middleware my-module RateLimited
```

#### Notifications and Emails

```bash
# Create a notification
php artisan module:make:notification my-module InvoicePaid

# Create a mailable
php artisan module:make:mail my-module WelcomeEmail

# Create a notification channel
php artisan module:make:channel my-module SmsChannel
```

#### Views and Components

```bash
# Create a view
php artisan module:make:view my-module dashboard

# Create a component
php artisan module:make:component my-module Alert
```

#### Others

```bash
# Create an exception
php artisan module:make:exception my-module PaymentFailedException

# Create an enum
php artisan module:make:enum my-module UserStatus

# Create a validation rule
php artisan module:make:rule my-module Uppercase

# Create a policy
php artisan module:make:policy my-module PostPolicy

# Create a provider
php artisan module:make:provider my-module PaymentServiceProvider

# Create a test
php artisan module:make:test my-module UserTest
```

## Programmatic Usage

You can also interact with modules programmatically:

```php
use Cesargb\Modules\Modules;

// Get all modules
$modules = Modules::all();

// Get only installed modules
$installed = Modules::installed();

// Get only uninstalled modules
$uninstalled = Modules::uninstalled();

// Get a specific module
$module = Modules::get('my-module');

// Check if a module exists
if (Modules::exists('my-module')) {
    // ...
}

// Check if a module is installed
if (Modules::isInstalled('my-module')) {
    // ...
}

// Install a module
Modules::install('my-module');

// Uninstall a module
Modules::uninstall('my-module');
```

### Working with Module instances

```php
$module = Modules::get('my-module');

echo $module->name;         // my-module
echo $module->packageName;  // my-company/my-module
echo $module->version;      // ^1.0.0
echo $module->namespace;    // MyCompany\MyModule\
var_dump($module->installed); // true/false

// Install/uninstall
$module->install();
$module->uninstall();
```

## Complete Example

### 1. Create the module structure

```bash
mkdir -p modules/blog/src
```

### 2. Create the module's composer.json

```json
{
    "name": "my-app/blog",
    "version": "1.0.0",
    "type": "library",
    "autoload": {
        "psr-4": {
            "MyApp\\Blog\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "MyApp\\Blog\\BlogServiceProvider"
            ]
        }
    }
}
```

### 3. Install the module

```bash
php artisan modules:install blog
```

### 4. Create module components

```bash
php artisan module:make:model blog Post
php artisan module:make:controller blog PostController
php artisan module:make:request blog StorePostRequest
php artisan module:make:resource blog PostResource
```

## Testing

This package includes a test suite built with PHPUnit and Orchestra Testbench.

### Running Tests

```bash
# Install dependencies
composer install

# Run all tests
composer test

# Or use PHPUnit directly
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Feature
```

### Test Structure

- `tests/Unit/` - Unit tests for individual classes
- `tests/Feature/` - Feature tests for commands and integration
- `tests/TestCase.php` - Base test case with Orchestra Testbench configuration

## Advantages

- ✅ **Modularity**: Organize your application into independent and reusable modules
- ✅ **Local development**: Modules are developed locally without needing to publish them
- ✅ **Native Composer**: Uses Composer's package system without additional tools
- ✅ **Automatic autoloading**: Modules are autoloaded automatically when installed
- ✅ **Service Providers**: Each module can have its own Service Provider
- ✅ **Integrated commands**: Generate code inside modules using Artisan commands

## TODO

- [ ] Automatically add repository.path in modules
