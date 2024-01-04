# cowiki

<img src="header.jpg"/>

## Introduction

This repository is the main project for cowiki, developed by the Verbund-Offener-Werkstaetten. We welcome and invite everyone to contribute to this open-source project by helping with coding and reporting issues.

The tech stack for vow-cowiki is based on PHP Laravel for the backend and Vue3 for the frontend.

## Set-Up

To get started with contributing to vow-cowiki, follow these steps:

1. Make sure you have Visual Studio Code installed, as it is the recommended code editor for this project.
2. The development environment is based on Docker. Clone the repository using the following command:

```
git clone git@github.com:Verbund-Offener-Werkstaetten/vow-cowiki.git
```

3. Create a useful .env file for the Laravel project by running:

```
cp laravel/.env.example laravel/.env
```

4. Build the development containers using Docker Compose:

```
docker-compose build
```

5. Once the build is complete, start the project with:

```
docker-compose up -d
```

6. For convenience, we provide a Visual Studio Code Workspace file at `.vscode/vow-cowiki.code-workspace`.

### Seeding Initial Data

To seed the initial data into the database, follow these steps:

1. Log onto the Laravel Docker container:

```
docker exec -it cowiki.laravel /bin/bash
```

2. Run all migrations:

```
art migrate
```

3. Seed fake data into the database:

```
art db:seed
```

## Code-Style

To maintain a consistent code style across the project, we use the following tools and extensions:

### PHP-CS-Fixer

To install PHP-CS-Fixer, run the following command within `laravel/tools/php-cs-fixer`:

```
composer install
```

### Prettier

For JavaScript and Vue files, we rely on "Prettier". Install the VS Code Extension `esbenp.prettier-vscode` to ensure proper code formatting.

### EditorConfig

We also use an `.editorconfig` file to handle code style settings for other file types. Make sure to install the corresponding EditorConfig extension for your code editor.

## Xdebug for PHP Debugging

To enable XDebug for PHP debugging, follow these steps:

1. Set `XDEBUG=true` in the `laravel/.env` file.
2. Restart the Docker containers to apply the changes.

You can now use the `vow-cowiki Xdebug` debug-preset inside Visual Studio Code to start debugging.

## Testing

All test files are located in `laravel/tests` and can be run using the following command: 

```
art test
```
