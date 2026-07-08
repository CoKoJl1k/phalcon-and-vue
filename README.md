# Subscription Sync Dashboard

Mini-приложение для синхронизации подписок из Stripe-like источника во внутреннюю БД с отображением в интерфейсе.

## Стек

- PHP 8.4 + Phalcon 5
- Vue 3 (Composition API) + Vite
- PostgreSQL 14
- Docker

## Структура

```
backend/
├── public/index.php          # Входная точка
├── app/
│   ├── Config/
│   │   ├── db.php            # Настройки БД
│   │   ├── services.php      # Регистрация сервисов в DI
│   │   └── routes.php        # Маршруты
│   ├── Controllers/
│   │   ├── SubscriptionController.php
│   │   ├── CustomerController.php
│   │   ├── ProductController.php
│   │   └── SyncController.php
│   ├── Models/
│   │   ├── Customer.php
│   │   ├── Product.php
│   │   ├── Subscription.php
│   │   └── SyncLog.php
│   ├── Services/
│   │   ├── SubscriptionService.php
│   │   ├── LogService.php
│   │   └── SyncService.php
│   ├── Stripe/
│   │   ├── StripeClientInterface.php
│   │   └── MockStripeClient.php
│   └── Migrations/init.sql
└── composer.json

frontend/
├── index.html
├── vite.config.js
└── src/
    ├── main.js
    ├── App.vue
    ├── api/index.js
    └── components/
        ├── SubscriptionDashboard.vue
        ├── SubscriptionFilters.vue
        ├── SubscriptionTable.vue
        └── SyncPanel.vue
```

## Запуск

```bash
cp .env.example .env
docker-compose up -d
```

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8080

## API

| Method | Path | Описание |
|--------|------|----------|
| GET | /api/subscriptions | Список подписок |
| GET | /api/customers | Список клиентов |
| GET | /api/products | Список продуктов |
| POST | /api/sync | Запуск синхронизации |
| GET | /api/sync/last | Результат последнего синка |
