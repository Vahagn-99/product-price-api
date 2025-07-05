# 💰 Product Price API

REST API приложение для получения списка товаров с ценами и конвертацией валют.  
Проект реализован на Laravel 12, завернут в Docker и документирован через Swagger/OpenAPI.

## 🚀 Быстрый старт

### 1. Клонирование репозитория

```bash
git clone https://github.com/Vahagn-99/product-price-api.git product-price-api
cd product-price-api
```

### 2. Настройка переменных окружения

Скопируйте `.env.example` в `.env`:

```bash
cp .env.example .env
```

### 3. Запуск контейнеров

```bash
docker compose up -d --build
```

### 4. Установка зависимостей, миграции и сидеры

```bash
docker compose exec app bash

composer install
php artisan migrate --seed
```

---

## 🔐 Авторизация

Для данного тестового задания авторизация не требуется.

---

## 📘 Использование

Получить список товаров с ценами:

```
GET /api/v1/prices
```

Параметры:
- `currency` — Валюта отображения цен (`rub`, `usd`, `eur`)
- `page` — Номер страницы
- `per_page` — Количество элементов на странице

---

## 🧪 Swagger UI

Документация API доступна по адресу:

```
http://localhost/api/documentation
```

---


## 📚 Основной функционал

- Получение списка товаров с ценами
- Конвертация в RUB, USD, EUR с форматированием
- Пагинация
- Ограничение по частоте запросов (rate limiting)

---

## 📝 Тестирование

```bash
docker compose exec app php artisan test
```