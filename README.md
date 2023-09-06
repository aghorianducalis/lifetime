# Lifetime Management System

## Overview

The Lifetime Management System is a web application built with Laravel that allows users to manage their Lifetime. The goal of this project is to create a user-friendly interface REST API for creating, editing, deleting, and viewing events. Users can create, edit, delete, and view events. Each event includes a title, description.

## Features

- User Registration and Authentication
- Create, Edit, Delete, and View Events
- Validation for Task Creation and Updates
- Authorization: Users can only manage their events
- API Controller (Temporarily using Blade Templates)

## Installation and Setup

1. Requirements
   <br><br>
   Before starting work on the project, ensure that you have the following components installed:
   - PHP
   - Composer
   - Node.js and npm
   - MySQL
   - Laravel

   Make sure the following components are installed on your system:

   - Docker: [Docker installation instructions](https://docs.docker.com/get-docker/)
   - Docker Compose: [Docker Compose installation instructions](https://docs.docker.com/compose/install/)

2. Clone the repository to your local machine:
   ```shell
   git clone https://github.com/aghorianducalis/lifetime.git
   cd lifetime
   ```
3. Set up environment:
   <br><br>
   Copy the `.env.example` to `.env` and set the environment values.

   Configure database access and other necessary parameters. Set up your database in the `.env` file:
   ```shell
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```
   
4. Build and run the project:

   Use Docker Compose to build and start your project:
   ```
   docker-compose up -d
   ```
   This command will start the containers in detached mode. <br><br>
5. Access your project:

   Once the containers are up and running, you can access your project at http://localhost or another specified address.
   Now you can get into the app container:
   ```
   docker exec -it app sh
   ```
   Laravel artisan commands are available inside the container. I.e. generate an application key:
   ```
   php artisan key:generate
   ```
   
6. TODO update this section 
7. Install composer dependencies:
```shell
composer install
```
Run database migrations:
```shell
php artisan migrate
```
Install JavaScript dependencies:
```shell
npm install
```
In another terminal, run Vite for frontend development:
```shell
npm run dev
```
Set up Laravel authentication:
```shell
php artisan make:auth
```
Start the development server:
```shell
php artisan serve
```

Now, you can open a web browser and navigate to http://localhost:8000 to view your project.


## Usage

Once you have started the Artisan development server, your application will be accessible in your web browser at http://localhost:8000.

After installing and configuring the project, you can:

Register, log in, and log out.

## Authentication

The Lifetime Management System includes user registration and authentication. Users can sign up, log in, and log out. Only authenticated users can create, edit, delete, and view their events.

## Testing

The application includes PHPUnit tests to ensure functionality and authorization are working as expected. Run the tests with the following command:

## Security Vulnerabilities

If you discover a security vulnerability within application, please send an e-mail to developer via [aghorianducalis@gmail.com](mailto:aghorianducalis@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

###########################################################################

## Local Setup

Install [Docker](https://www.docker.com/products/docker-desktop)

Install [Git](https://git-scm.com/downloads)

Clone this repository

```
  git clone git@github.com:aghorianducalis/lifetime.git
  cd lifetime
```

Copy environment template file
```
  cp -n .env.example .env
```

Setup docker containers(and keep it works in background)
```
  docker-compose up -d
```

Copy DB dump file **dump.sql** to **/docker/mysql/data**

Enter into **db** container
```
  docker-compose exec db bash 
```

Then, there:

```
  mysql -uroot -proot lifetime_db < /var/lib/mysql/dump.sql
```

Enter into **app** container (Main container with php application)
```
  docker-compose exec app bash
```

To rebuild docker containers use command:
```
  docker-compose up --build -d
```
To see logs:
```
  docker-compose logs
```

#### All next operations should be executed inside container:

```
  composer install
```

Execute laravel preparations commands:
```
  php artisan key:generate

  php artisan storage:link
```

Setup file access rules at storage folder(cache, logs and user files)
```
  chmod -R o+w storage
```

## Containers and services

### Php-application

There you can find codebase and PHP-fpm

Outside accessible via:

```
  docker-compose exec app bash
```

### Nginx

https://localhost:8897 - for local development (not used due to problems)

http://localhost:8896 - version without ssl

### MySQL

Accessible from outside via port 33061.

Connecting to mysql from host system:

```
  docker-compose exec db mysql -uroot -proot lifetime_db
```
