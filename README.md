# Short URL App

Сервис сокращения ссылок на Laravel.

Позволяет пользователям регистрироваться, создавать короткие ссылки, отслеживать переходы и управлять своими URL.

## Возможности

- Регистрация и авторизация пользователей
- Создание коротких ссылок
- Редирект с короткой ссылки на оригинальный URL
- Базовая статистика переходов
- Защита маршрутов через middleware
- Работа с базой данных через Eloquent

## Стек

- PHP 8.2+
- Laravel
- MySQL / PostgreSQL
- Docker + Docker Compose
- Git

## Установка и запуск

### Требования
- Docker
- Docker Compose

### Быстрый старт

```bash
git clone https://github.com/Rifat172/short-url-app.git
cd short-url-app

cp .env.example .env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
