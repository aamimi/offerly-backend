# Offerly Api

## Requirements

- PHP 8.4
- Composer
- Node.js 22.x
- NPM
- Docker

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/your-username/your-repository.git
    cd your-repository
    ```

2. Install PHP dependencies:
    ```sh
    composer install
    ```

3. Install Node.js dependencies:
    ```sh
    npm install
    ```

4. Copy the example environment file and generate an application key:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

5. Create the SQLite database file:
    ```sh
    touch database/database.sqlite
    ```

6. Run database migrations:
    ```sh
    php artisan migrate
    ```
