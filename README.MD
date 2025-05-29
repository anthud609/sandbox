# Omni Core Framework - Technical Documentation

## Table of Contents
1. [Overview](#overview)
2. [System Requirements](#system-requirements)
3. [Installation](#installation)
4. [Project Structure](#project-structure)
5. [Core Components](#core-components)
6. [Authentication Module](#authentication-module)
7. [Testing Framework](#testing-framework)
8. [Configuration](#configuration)
9. [Development Workflow](#development-workflow)
10. [Troubleshooting](#troubleshooting)
11. [API Reference](#api-reference)

## Overview

The Omni Core Framework is a lightweight, modular PHP framework built with a focus on authentication and testing. It follows PSR-4 autoloading standards and implements a clean MVC architecture with comprehensive testing capabilities using PHPUnit and Behat.

### Key Features
- **Modular Architecture**: Clean separation of concerns with module-based organization
- **Custom Router**: Simple yet powerful routing system
- **Authentication System**: Complete user authentication with session management
- **Comprehensive Testing**: Both unit tests (PHPUnit) and feature tests (Behat)
- **PSR-4 Compliance**: Modern PHP autoloading standards
- **Session Management**: Secure session handling with test mode support

## System Requirements

- **PHP**: ^8.1 or higher
- **Composer**: Latest version recommended
- **Web Server**: Apache with mod_rewrite enabled
- **Extensions**: Standard PHP extensions (session, filter, etc.)

## Installation

### Step 1: Clone or Download
```bash
git clone <repository-url> anthud609-sandbox
cd anthud609-sandbox
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Critical i18n.php Setup
⚠️ **IMPORTANT**: Ensure that `i18n.php` exists in `/vendor/` NOT `/vendor/behat/gherkin/` or a missing i18n.php file error will trigger when running behat tests.

### Step 4: Web Server Configuration
Configure your web server to point to the `public/` directory as the document root.

#### Apache Virtual Host Example
```apache
<VirtualHost *:80>
    ServerName sandbox.dev.local
    DocumentRoot /path/to/anthud609-sandbox/public
    
    <Directory /path/to/anthud609-sandbox/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Step 5: Update Hosts File (Optional)
```
127.0.0.1    sandbox.dev.local
```

## Project Structure

```
anthud609-sandbox/
├── README.MD                           # Installation and setup instructions
├── behat.yml                          # Behat configuration for feature tests
├── composer.json                      # Dependencies and scripts configuration
├── debug.feature                      # Debug feature file for testing
├── app/                               # Application source code
│   ├── Core/                          # Core framework components
│   │   └── Router/
│   │       └── Router.php             # Custom routing system
│   └── Modules/                       # Application modules
│       └── Auth/                      # Authentication module
│           ├── Controllers/           # Controller layer
│           │   └── AuthController.php # Authentication logic
│           ├── Models/                # Data layer
│           │   └── User.php           # User model with hardcoded data
│           ├── Tests/                 # Module-specific tests
│           │   ├── Feature/           # Behat feature tests
│           │   │   ├── login.feature  # Login/logout scenarios
│           │   │   └── Bootstrap/
│           │   │       └── FeatureContext.php # Behat context
│           │   └── Unit/              # PHPUnit unit tests
│           │       ├── AuthControllerTest.php # Controller tests
│           │       └── UserModelTest.php      # Model tests
│           └── Views/                 # Presentation layer
│               └── LoginView.php      # Login form template
└── public/                           # Web-accessible directory
    ├── index.php                     # Application entry point
    └── .htaccess                     # Apache rewrite rules
```

## Core Components

### Router (`app/Core/Router/Router.php`)

The custom router handles HTTP requests and maps them to appropriate handlers.

#### Key Features
- Supports GET and POST methods
- Callable function handlers
- Array-based controller/method handlers
- Automatic 404 handling
- URL path parsing

#### Usage Examples
```php
$router = new Router();

// Function handler
$router->get('/', function() {
    echo "Homepage";
});

// Controller handler
$router->post('/login', [AuthController::class, 'processLogin']);

// Handle the request
$router->handle();
```

#### Methods
- `get($path, $handler)`: Register GET route
- `post($path, $handler)`: Register POST route  
- `handle()`: Process current request

### Entry Point (`public/index.php`)

The main application bootstrap file that:
- Loads Composer autoloader
- Starts PHP session
- Defines application routes
- Handles requests through the router

#### Route Definitions
- `GET /`: Homepage with login/logout links
- `GET /login`: Display login form
- `POST /login`: Process login credentials
- `GET /logout`: Logout and redirect

## Authentication Module

### AuthController (`app/Modules/Auth/Controllers/AuthController.php`)

Handles all authentication-related operations with comprehensive session management.

#### Key Features
- **Test Mode Support**: Prevents actual redirects during testing
- **Session Management**: Secure login/logout handling
- **Error Handling**: User-friendly error messages
- **Redirect Logic**: Smart redirection based on authentication state

#### Methods

##### `setTestMode($testMode = true)`
Enables/disables test mode to prevent redirects during unit testing.

##### `showLogin()`
Displays the login form with error handling:
- Redirects authenticated users to homepage
- Shows login errors from session
- Includes login form template

##### `processLogin()`
Processes login form submission:
- Validates email and password presence
- Authenticates credentials via User model
- Sets session variables on success
- Redirects with error messages on failure

##### `logout()`
Handles user logout:
- Destroys session (normal mode)
- Clears session variables (test mode)
- Redirects to homepage

#### Session Variables Used
- `$_SESSION['user_id']`: Authenticated user ID
- `$_SESSION['username']`: Authenticated username
- `$_SESSION['login_error']`: Login error messages
- `$_SESSION['_test_redirect']`: Test mode redirect tracking

### User Model (`app/Modules/Auth/Models/User.php`)

Simple user data management with hardcoded user database.

#### Test Users
```php
// User 1
ID: 1
Username: testuser
Email: test@example.com
Password: password123

// User 2  
ID: 2
Username: admin
Email: admin@example.com
Password: admin123
```

#### Methods

##### `authenticate($email, $password)`
Validates user credentials and returns user data or false.

##### `findById($id)`
Retrieves user by ID, returns user array or false.

##### `findByEmail($email)`
Retrieves user by email address, returns user array or false.

### Login View (`app/Modules/Auth/Views/LoginView.php`)

Complete HTML template for the login form with:
- Responsive CSS styling
- Error message display
- Form validation attributes
- Test credentials display
- Navigation links

#### Form Fields
- `email`: Email input (required, type="email")
- `password`: Password input (required, type="password")
- `submit`: Submit button with value "Login"

## Testing Framework

### Testing Architecture
The framework implements comprehensive testing with both unit tests (PHPUnit) and behavioral tests (Behat).

### PHPUnit Configuration
- **Version**: ^11.5
- **Test Suites**: Unit tests organized by module
- **Test Mode**: Controllers support test mode to prevent redirects

### Unit Tests

#### AuthControllerTest (`app/Modules/Auth/Tests/Unit/AuthControllerTest.php`)

Comprehensive controller testing with session management.

##### Test Methods
- `testProcessLoginWithValidCredentials()`: Successful login flow
- `testProcessLoginWithInvalidCredentials()`: Failed authentication
- `testProcessLoginWithMissingEmail()`: Email validation
- `testProcessLoginWithMissingPassword()`: Password validation
- `testLogout()`: Logout functionality
- `testShowLoginWhenNotLoggedIn()`: Login form display
- `testShowLoginWhenLoggedIn()`: Authenticated user redirect
- `testShowLoginWithError()`: Error message handling

##### Setup/Teardown
- Starts PHP session for testing
- Enables test mode on AuthController
- Clears session and POST data after each test

#### UserModelTest (`app/Modules/Auth/Tests/Unit/UserModelTest.php`)

Model testing for user data operations.

##### Test Methods
- `testAuthenticateWithValidCredentials()`: Successful authentication
- `testAuthenticateWithInvalidCredentials()`: Failed authentication
- `testAuthenticateWithValidEmailButWrongPassword()`: Partial validation
- `testAuthenticateWithEmptyCredentials()`: Empty input handling
- `testFindById()`: User retrieval by ID
- `testFindByIdNotFound()`: Non-existent user handling
- `testFindByIdWithZero()`: Edge case testing
- `testFindByEmail()`: User retrieval by email
- `testFindByEmailNotFound()`: Non-existent email handling
- `testFindByEmailWithEmptyString()`: Empty email handling
- `testFindAdminUser()`: Admin user specific testing

### Behat Configuration (`behat.yml`)

Feature testing configuration with Mink extension for web testing.

#### Configuration Details
- **Base URL**: `http://sandbox.dev.local`
- **Default Session**: Goutte (headless browser)
- **Timeout**: 60 seconds
- **SSL Verification**: Disabled for development
- **Context Class**: `Omni\Core\Modules\Auth\Tests\Feature\Bootstrap\FeatureContext`

### Feature Tests

#### login.feature (`app/Modules/Auth/Tests/Feature/login.feature`)

Behavioral tests for authentication workflow.

##### Scenarios
1. **User can access login page**: Navigation and form presence
2. **User can login with valid credentials**: Successful authentication flow
3. **User cannot login with invalid credentials**: Failed authentication handling
4. **User cannot login with missing email**: Form validation
5. **User can logout**: Logout functionality

##### Background
Each scenario starts with user on homepage.

#### FeatureContext (`app/Modules/Auth/Tests/Feature/Bootstrap/FeatureContext.php`)

Behat context class extending MinkContext for web interactions.

##### Custom Step Definitions
- `@Given I am on the home page`: Navigate to homepage
- `@Given I am logged in as :email with password :password`: Login automation
- `@When I click :link`: Link clicking
- `@Then I should see the page content`: Debug page content display

## Configuration

### Composer Configuration (`composer.json`)

#### Package Information
- **Name**: omni/core
- **Type**: library
- **Description**: Omni Core Framework with modular authentication

#### Autoloading
- **PSR-4**: `Omni\\Core\\` namespace maps to `app/` directory
- **Development**: Same namespace for test files

#### Dependencies
- **PHP**: ^8.1 minimum version
- **Behat**: ^3.11 for feature testing
- **Mink**: ^1.10 for web testing
- **PHPUnit**: ^11.5 for unit testing

#### Scripts
Available composer commands:

##### `composer test`
Runs PHPUnit with colored output and test documentation format.

##### `composer test-unit`  
Runs only unit tests with detailed output.

##### `composer test-feature`
Runs only Behat feature tests with colors.

##### `composer test-all`
Comprehensive testing sequence:
1. Unit tests with progress indicators
2. Feature tests with progress indicators
3. Completion confirmation

##### `composer test-verbose`
Detailed testing with warnings and notices displayed.

##### `composer test-quick`
Fast testing without detailed output formatting.

### Apache Configuration (`public/.htaccess`)

Rewrite rules for clean URLs:
- Enables mod_rewrite
- Skips existing files and directories
- Routes all requests to index.php
- Preserves query strings
- Last rule priority

## Development Workflow

### Setting Up Development Environment

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Configure Web Server**
   Point document root to `public/` directory

3. **Verify Installation**
   ```bash
   composer test-quick
   ```

### Development Commands

#### Running Tests
```bash
# All tests with progress
composer test-all

# Unit tests only
composer test-unit

# Feature tests only  
composer test-feature

# Quick test run
composer test-quick

# Verbose output with warnings
composer test-verbose
```

#### Adding New Routes
```php
// In public/index.php
$router->get('/new-route', function() {
    // Handler logic
});

$router->post('/api/endpoint', [Controller::class, 'method']);
```

#### Creating New Modules
1. Create module directory in `app/Modules/`
2. Follow Auth module structure:
   - Controllers/
   - Models/
   - Views/
   - Tests/Feature/
   - Tests/Unit/

### Code Standards
- Follow PSR-4 autoloading
- Use proper namespacing
- Implement test coverage for new features
- Include both unit and feature tests
- Use test mode in controllers for testing

## Troubleshooting

### Common Issues

#### i18n.php Missing Error
**Error**: Missing i18n.php file when running Behat tests
**Solution**: Ensure i18n.php exists in `/vendor/` directory, not in `/vendor/behat/gherkin/`

#### 404 Errors on Routes
**Cause**: Apache mod_rewrite not enabled or .htaccess not processed
**Solution**: 
- Enable mod_rewrite: `a2enmod rewrite`
- Check AllowOverride All in virtual host
- Verify .htaccess file permissions

#### Session Issues in Tests
**Cause**: Session already started or not properly cleared
**Solution**: 
- Use `session_status()` check before `session_start()`
- Clear `$_SESSION` array in test teardown
- Enable test mode in controllers

#### Autoloader Issues
**Cause**: Composer autoloader not updated after namespace changes
**Solution**: 
```bash
composer dump-autoload
```

#### Behat Base URL Issues
**Cause**: Base URL in behat.yml doesn't match development environment
**Solution**: Update `base_url` in behat.yml to match your local setup

### Debug Mode

Use the debug.feature file to troubleshoot page content:
```bash
vendor/bin/behat debug.feature
```

This will output the actual page content for debugging purposes.

## API Reference

### Router Class

#### Constructor
```php
public function __construct()
```
Initializes empty routes array.

#### Methods

##### get()
```php
public function get(string $path, callable|array $handler): void
```
Registers a GET route.

**Parameters:**
- `$path`: URL path pattern
- `$handler`: Callable function or [ControllerClass::class, 'method'] array

##### post()
```php
public function post(string $path, callable|array $handler): void
```
Registers a POST route.

##### handle()
```php
public function handle(): void
```
Processes the current HTTP request and executes matching handler.

### AuthController Class

#### Constructor
```php
public function __construct()
```
Initializes User model and sets test mode to false.

#### Methods

##### setTestMode()
```php
public function setTestMode(bool $testMode = true): void
```
Controls redirect behavior for testing.

##### showLogin()
```php
public function showLogin(): void
```
Displays login form or redirects authenticated users.

##### processLogin()
```php
public function processLogin(): void
```
Processes login form submission and handles authentication.

##### logout()
```php
public function logout(): void
```
Logs out user and redirects to homepage.

### User Model Class

#### Methods

##### authenticate()
```php
public function authenticate(string $email, string $password): array|false
```
Validates credentials and returns user data or false.

##### findById()
```php
public function findById(int $id): array|false
```
Retrieves user by ID.

##### findByEmail()
```php
public function findByEmail(string $email): array|false
```
Retrieves user by email address.

### Session Variables

#### Authentication State
- `$_SESSION['user_id']`: Integer user ID when logged in
- `$_SESSION['username']`: String username when logged in

#### Error Handling
- `$_SESSION['login_error']`: String error message for login failures

#### Testing Support
- `$_SESSION['_test_redirect']`: String redirect URL in test mode

### Test Credentials

For development and testing:

**Regular User:**
- Email: test@example.com
- Password: password123
- Username: testuser
- ID: 1

**Admin User:**
- Email: admin@example.com  
- Password: admin123
- Username: admin
- ID: 2

---

This documentation covers the complete Omni Core Framework codebase. For additional support or questions, refer to the individual source files or extend this documentation as the framework evolves.