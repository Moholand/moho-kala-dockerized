# Moho Kala

A dockerized Laravel 9 e-commerce application.

Stack: PHP 8.1 (FPM) · Nginx · MySQL 8 · Elasticsearch 8.4 · Kibana · phpMyAdmin · Node 18 / Vite · Vue 3.

## Requirements

Only **Docker** and **Docker Compose** are needed on your machine. PHP, Composer and
Node are all provided by the containers (Composer is installed inside the image in the
`Dockerfile`), so nothing has to be installed on the host.

> Linux only: Elasticsearch needs an increased virtual memory limit. Run once
> `sudo sysctl -w vm.max_map_count=262144`.

## Services

| Service      | Container | Host URL                | Notes                         |
| ------------ | --------- | ----------------------- | ----------------------------- |
| web          | nginx     | http://localhost:8000   | Laravel app                   |
| phpmyadmin   | phpmyadmin| http://localhost:8080   | login `root` / `root`         |
| database     | mysql 8   | localhost:3306          | db `laravel_moho_kala`        |
| elasticsearch| es 8.4    | http://localhost:9200   | security disabled             |
| kibana       | kibana    | http://localhost:5602   |                               |
| js (vite)    | node 18   | http://localhost:5173   | dev asset server / HMR        |
| app          | php-fpm   | —                       | runs PHP, used via `exec`     |

## First-time setup

### 1. Create the environment file

```bash
cp .env.example .env
```

Then edit `.env` and set at least:

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=laravel_moho_kala
DB_USERNAME=root
DB_PASSWORD=root

ELASTIC_HOST=http://elasticsearch
ELASTIC_PORT=9200
```

`ELASTIC_HOST` / `ELASTIC_PORT` are **required** — they are missing from
`.env.example`. Without them the app builds an invalid Elasticsearch URI
(`http://:`) and `composer install` / `artisan package:discover` fails during the
build. `DB_HOST` and `ELASTIC_HOST` use the compose service names because the
containers talk to each other over the Docker network.

### 2. Build and start the containers

```bash
docker compose build
docker compose up -d app web database phpmyadmin elasticsearch kibana
```

The `js` service is started separately further down (it needs `node_modules` first).

### 3. Install PHP dependencies

```bash
docker compose exec app composer install
```

The image already runs `composer install` at build time, but the
`.:/var/www` bind mount masks the image's `vendor/` directory, so it must be
installed once on the mounted project.

### 4. Generate the app key and migrate the database

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

### 5. Fix storage permissions

The bind mount also masks the `chown` performed in the `Dockerfile`, so PHP-FPM
(which runs as `www-data`) cannot write logs/cache:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app rm -f storage/logs/laravel.log
```

### 6. Install front-end dependencies and run Vite

```bash
docker compose run --rm --no-deps js npm install
docker compose up -d js
```

This starts the Vite dev server and creates `public/hot`. Without it Laravel
fails with `Vite manifest not found at .../public/build/manifest.json`.

If you prefer pre-built assets instead of a running dev server:

```bash
docker compose run --rm --no-deps js npm run build
```

### 7. Open the app

- App: http://localhost:8000
- phpMyAdmin: http://localhost:8080 (`root` / `root`)
- Kibana: http://localhost:5602

## Everyday commands

```bash
docker compose up -d                      # start everything
docker compose down                       # stop containers
docker compose exec app php artisan ...   # run any artisan command
docker compose exec app composer ...      # run composer
docker compose exec app php artisan test  # run the test suite
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
