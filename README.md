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



## Архитектура

Nginx — единая точка входа. Все запросы идут через него.
Контейнеры объединены внутренней Docker-сетью `app-net`.

```
Браузер
  → Nginx
      ├── /api/*          → Symfony (REST API)
      ├── /admin/*        → Symfony (EasyAdmin)
      ├── /login, /logout → Symfony (авторизация)
      ├── /uploads/*      → статика с диска
      ├── /media/cache/*  → LiipImagine (ресайз картинок)
      └── /*              → React (dist/ в prod, Vite в dev)
```

### Контейнеры

| Контейнер        | Сервис             | Сеть      | Порты              |
|------------------|--------------------|-----------|--------------------|
| `nginx_web`      | Nginx              | app-net   | 80 (prod), 3000 (dev) |
| `php_container`  | PHP-FPM + Symfony  | app-net   | —                  |
| `postgres`       | PostgreSQL         | app-net   | 5432               |
| `react_app`      | Vite dev server    | app-net   | — (только в dev)   |

### Порты

| Порт   | Режим | Что обслуживает                          |
|--------|-------|------------------------------------------|
| `:80`  | Prod  | Статика из `dist/` + API + админка       |
| `:3000`| Dev   | Vite с HMR + API + админка               |

## Dev-режим

**URL: `http://localhost:3000`**

```bash
# 1. Поднять бэкенд (создаёт сеть app-net)
cd symfony_flowers_shop
docker compose up -d

# 2. Поднять фронт (подключается к app-net)
cd react_flowers_shop
docker compose up -d
```

- Vite dev server работает с горячей перезагрузкой (HMR)
- Изменения в коде сразу отображаются в браузере
- API и админка доступны на том же порту `:3000`

## Prod-режим

**URL: `http://localhost`** (порт 80)

```bash
# 1. Поднять бэкенд
cd symfony_flowers_shop
docker compose up -d

# 2. Поднять фронт, собрать билд, потушить
cd react_flowers_shop
docker compose up -d
docker exec react_app npm run build
docker compose down
```

- Nginx отдаёт статику из `dist/`
- React контейнер в проде не нужен
- Пересборка: `docker exec react_app npm run build` (без удаления `dist/`)

## Nginx — роутинг запросов

Конфиг: `symfony_flowers_shop/docker_env/nginx/config/default.conf`

Два server-блока (prod и dev) с одинаковой структурой:

```
1. Symfony     → /api, /admin, /login, /logout  → PHP-FPM
2. Статика     → /uploads (файлы), /media/cache (ресайзы)
3. PHP-FPM     → обработка *.php
4. Vite        → HMR, исходники (только dev)
5. Фронтенд   → dist/ (prod) или Vite proxy (dev)
```

Добавить новый Symfony роут — добавить префикс в regex:

```nginx
location ~ ^/(api|admin|login|logout|новый_префикс)(/|$) {
```

## Ресайз картинок (LiipImagine)

Конфиг: `symfony_flowers_shop/config/packages/liip_imagine.yaml`

| Фильтр   | Размер    | Где используется        |
|----------|-----------|-------------------------|
| `mini`   | 150×150   | Миниатюры               |
| `thumb`  | 400×534   | Карточки товаров         |
| `detail` | 800×1067  | Детальная страница, hero |

API возвращает URL вида `/media/cache/thumb/uploads/products/image.jpg`.
При первом запросе Nginx передаёт в LiipImagine → генерация → кэш.
При повторном — Nginx отдаёт файл напрямую без PHP.

### Прогрев кэша

При первом запуске или после очистки кэша:

```bash
docker exec php_container php -d memory_limit=2G \
  bin/console liip:imagine:cache:resolve \
  $(find public/uploads/products -type f | sed 's|public/||') \
  --filter=thumb --filter=mini --filter=detail
```

## Docker-сеть

Бэкенд создаёт сеть `app-net`. Фронт и dbgate подключаются как external:

```yaml
# react_flowers_shop/docker-compose.yml
networks:
  app-net:
    external: true
    name: app-net
```

Порядок запуска: **сначала бэкенд** (создаёт сеть), **потом фронт** (подключается).
