# Offerly Api

This is the backend of the Offerly project, a marketplace application.
It was created to help people share their products or services in a more organized way.

## Requirements

if you use docker, you don't need to install any of the following requirements, but if you want to run the application locally, you need to install the following:
- PHP 8.4
- Composer
- Node.js 22.x
- NPM

## Installation

Offerly backend is a Laravel api application; it's build on top of Laravel 12 and uses a PostgreSQL database. 
If you are familiar with Laravel, you should feel right at home.

1. Clone the repository:
    ```sh
    git clone https://github.com/aamimi/offerly-backend.git
    cd offerly-backend
    ```

2. Run Sail to start the development server:
    ```sh
    ./vendor/bin/sail up -d
    ```

3. Connect to the container:
    ```sh
    ./vendor/bin/sail shell
    ```

4. Install PHP dependencies:
    ```sh
    composer install
    ```

5. Install Node.js dependencies:
    ```sh
    npm install
    ```

6. Copy the example environment file and generate an application key:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

7. Create the PostgreSQL database and run migrations:
    ```sh
    php artisan migrate
    ```

## Packages

### For Prod

#### # [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger)

L5 Swagger - OpenApi or Swagger Specification for Laravel project made easy.

#### # [laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)

This package can associate all sorts of files with Eloquent models. It provides a simple API to work with.

### For dev:

#### # [Sail](https://laravel.com/docs/10.x/sail)

Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development
environment.

#### # [Pest](https://pestphp.com)

Pest is a testing framework with a focus on simplicity,
meticulously designed to bring back the joy of testing in PHP.

#### # [Larastan](https://github.com/nunomaduro/larastan)

Larastan focuses on finding errors in your code. It catches whole classes of bugs even before you write tests for the
code.

#### # [Laravel Pint](https://laravel.com/docs/10.x/pint)

Laravel Pint is an opinionated PHP code style fixer for minimalists.

#### # [Rector](https://github.com/rectorphp/rector)

Rector instantly upgrades and refactors the PHP code of your application.

#### # [Telescope](https://laravel.com/docs/10.x/telescope)

Telescope provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps, and more.

## API Documentation

The API documentation is available at `/api/documentation` route.

## Testing

To run all tests, use the following command:

``` sh
    composer test
```

To run phpstan tests

``` shell
    composer test:types
```

To run Pest tests

``` shell
    composer test:unit
```

To run rector tests

``` shell
    composer test:rector
```

To apply rector changes

``` shell
    composer rector
```

To run lint tests

``` shell
    composer test:lint
```

To fix code style

``` shell
    composer lint
```

To run type coverage tests

``` shell
    composer test:type-coverage
```

## License

This project is licensed under the GNU License. See the `LICENSE` file for details.
