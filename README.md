# symfony_flowers_shop

## Install project in docker

1. Copy .env and put credentials inside
```bash
$ cp .env.example .env
```

2. Build docker
```bash
$ docker-compose build
```
3. Run docker
```bash
$ docker-compose up
```

4. Go to docker container and install dependencies and run migrations
```bash
$ docker exec -it php_container bash
```

5. Inside docker container
```bash
$ composer install
$ php bin/console doctrine:migrations:migrate
```

6. Optionally load fixtures
```bash
$ php bin/console doctrine:fixtures:load
```

7. Create admin (change login and password)
```bash
$  php bin/console app:create-admin admin_login admin_password
```
8. Give permissions to folders
```bash
$ chown -R www-data:www-data public/uploads public/media
$ chmod -R 775 public/uploads
$ chmod -R 775 public/media
```

9. Admin panel available localhost/admin
10. 
10. If need run dbgate
```bash
$ docker compose -f docker-compose.dbgate.yml up -d
$ docker compose -f docker-compose.dbgate.yml down -v
```

## Xdebug

1. Добавить конфигурацию для xdebug -> php web page
2. Там же добавить сервер - don_stroy (в docker-compose.yml PHP_IDE_CONFIG)
3. Прописать хост - 0.0.0.0 и путь на сервере к проекту - /var/www/html
