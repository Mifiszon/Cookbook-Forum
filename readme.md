# Docker Symfony Starter Kit

Starter kit is based on [The perfect kit starter for a Symfony 4 project with Docker and PHP 7.2](https://medium.com/@romaricp/the-perfect-kit-starter-for-a-symfony-4-project-with-docker-and-php-7-2-fda447b6bca1)

# Recipe Forum Project
-
This project is a **recipe forum** built with **PHP Symfony** and **Twig** as the templating engine.** It allows users to add, rate, and manage recipes, including uploading images. The system focuses on **CRUD operations** (Create, Read, Update, Delete) while enforcing access permissions for different user roles. The interface is styled using **Bootstrap**, ensuring a modern and responsive design.

A **recipe search engine** has already been implemented and will be further expanded in future updates.

## Key Features

- **User authentication** with role-based access control
- **Recipe management** (adding, editing, deleting, and rating recipes)
- **Image uploads** for recipes
- **Recipe search engine** (with planned improvements)
- **Responsive UI** using Bootstrap

## Technology Stack

- **Symfony (PHP 8.3 FPM)**
- **Twig (Templating Engine)**
- **MySQL 8.3.1** (Database)
- **Apache 2.4.57 (Debian)**
- **NodeJS LTS (Latest)**
- **Composer (Dependency Management)**
- **Xdebug (Debugging)**
- **Maildev (Email Testing)**

## Installation

1. *(Optional)* Add the following line to your `hosts` file:
   ```bash
   127.0.0.1   symfony.local
   ```
2. Run `build-env.sh` (or `build-env.ps1` on Windows).
3. Enter the PHP container:
   ```bash
   docker-compose exec php bash
   ```
4. Install Symfony inside the container:
   ```bash
   cd app
   rm .gitkeep
   git config --global user.email "you@example.com"
   symfony new ../app --version=lts --webapp
   chown -R dev.dev *
   ```

## URLs and Ports

- **Project URL:**
  ```bash
  http://localhost:8000
  ```
  or
  ```bash
  http://symfony.local:8000
  ```
- **Database (MySQL):**
  - Inside container: `mysql`, port `3306`
  - Outside container: `localhost`, port `3307`
- **Maildev (Email testing):** Available on port `8001`.
- **Xdebug:** Available remotely on port `9000`.

## Database Configuration

Modify the **`.env`** file in Symfony to configure the database:

```yaml
DATABASE_URL=mysql://symfony:symfony@mysql:3306/symfony?serverVersion=5.7
```

## Useful Commands

- `docker-compose up -d` - Start containers
- `docker-compose down` - Stop containers
- `docker-compose exec php bash` - Enter PHP container
- `docker-compose exec mysql bash` - Enter MySQL container
- `docker-compose exec apache bash` - Enter Apache2 container

## Future Development

- Further improvements to the **recipe search engine**.
- Expansion of **user access control** for different roles.
- Additional features for rating and commenting on recipes.

## Screenshots


## Troubleshooting

- **ERROR: for apache 'ContainerConfig'** (2024.05.11)

  If you encounter the error `ERROR: for apache 'ContainerConfig'` after running `docker-compose up -d`, you can resolve it with:
  ```bash
  docker compose up -d --force-recreate
  ```
