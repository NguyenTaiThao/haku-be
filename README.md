### Tech stack:

-   Laravel PHP Framework v8.x ([doc](https://laravel.com/docs/8.x))
-   Mysql v5.7
-   Dependencies:
    -   [others](composer.json)
#### Docker (recommended):

-   Install docker environment
    -   [MacOS](https://docs.docker.com/docker-for-mac/install/)
    -   [Ubuntu - Linux](https://docs.docker.com/engine/install/ubuntu/)
    -   [Windows](https://docs.docker.com/docker-for-windows/install/)
-   Install docker-compose tools ([link](https://docs.docker.com/compose/install/))
-   Config env:
    -   Make a copy of .env.example, name `.env`
-   Run containers:
    `docker-compose up -d`
-   Install dependencies:
    `docker-compose exec php-fpm composer install`
-   Generate app key:
    `docker-compose exec php-fpm php artisan key:generate`
-   Migrate database schema: 
    `docker-compose exec php-fpm php artisan migrate --seed`
-   To create the symbolic link: 
    `docker-compose exec php-fpm php artisan storage:link`
-   Cache config:
    `docker-compose exec php-fpm php artisan config:cache`
-   Done
# haku-be
