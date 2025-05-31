
# 🌍 Translation Management API – Laravel 12

A high-performance, scalable, and secure API built with Laravel 12 to manage multilingual translations. Designed for real-world use cases like dynamic locale support, frontend exports, and tag-based filtering. 

---

## 🚀 Features

- ✅ RESTful API with PSR-12 and SOLID design
- ✅ CRUD for translations with locale and tag support
- ✅ Search endpoint with flexible filters
- ✅ JSON export endpoint for frontend (Vue, React, etc.)
- ✅ Token-based auth via Laravel Sanctum
- ✅ Performance-focused (export < 500ms)
- ✅ Seeder for 100,000+ test records
- ✅ Dockerized setup for easy local dev
- ✅ Swagger/OpenAPI docs included
- ✅ Test coverage using PHPUnit

---

## 📁 Project Structure

- `/app` – Core app logic (controllers, models)
- `/routes/api.php` – API route definitions
- `/tests/Feature` – Feature tests including performance
- `/database/factories` – Factory to generate test records
- `/swagger/swagger.json` – API documentation schema

---

## 🛠️ Setup Instructions

### 1. Clone and Install

```bash
git clone <repo_url>
cd translation-api
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
```

### 2. Configure `.env`

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=translation-db
DB_USERNAME=SecretUser
DB_PASSWORD=Secret#123
```

---

## 🐳 Docker Setup (Recommended)

Make sure Docker is installed, then run:

```bash
docker-compose up -d
```

App: http://localhost:8000  
MySQL: port 3306  
Credentials: `user / secret`

---

## 🔐 Authentication (Sanctum)

- **Register/Login via:**
  - `POST /api/register`
  - `POST /api/login`
- **Use token in header:**
  ```
  Authorization: Bearer <token>
  ```

- **Logout:**
  - `POST /api/logout`

---

## 🔤 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | /api/translations | List translations |
| POST   | /api/translations | Create new translation |
| PUT    | /api/translations/{id} | Update translation |
| DELETE | /api/translations/{id} | Delete translation |
| GET    | /api/translations/search | Filter by key, tag, content |
| GET    | /api/translations/export/{locale} | JSON export by locale |

---

## 🧠 Caching Strategy

- `export/{locale}` is cached for 5 minutes for fast frontend load
- `search` is optionally cached with dynamic query keys
- Cache invalidates on `store`, `update`, `destroy`

---

## 🧪 Run Tests

```bash
php artisan test
```

Includes performance test:
- Validates export completes in < 500ms
- Uses `Sanctum::actingAs()` for protected routes

---

## 🧬 Seeder for 100k+ Records

```bash
php artisan db:seed --class=TranslationSeeder
```

---

## 📘 Swagger API Documentation

1. Swagger schema at `/swagger/swagger.json`
2. Use [Swagger UI](https://editor.swagger.io/) and import file
3. Or host with Laravel `l5-swagger` for `/api/documentation`

---

## 📦 Deployment Notes

- Make sure to configure cache driver (e.g. Redis or Memcached)
- Use `php artisan config:cache` before deployment
- CDN support enabled via `Cache-Control` headers on export

---

## 🧾 License

This project is open-sourced and available for technical assessment or enhancement.

---

## 🙋 Need Help?

Reach out with questions or contributions — we welcome PRs!


---

## 🐳 Docker Details

### Dockerfile (Laravel PHP App)
```Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y     zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev     && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

WORKDIR /var/www/html

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000
```

### docker-compose.yml
```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:8
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel
      MYSQL_USER: user
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

---

## 🧪 Test Case Examples

### ✅ Export Performance Test
```php
public function test_export_performance()
{
    Sanctum::actingAs(User::factory()->create());

    $start = microtime(true);
    $response = $this->getJson('/api/translations/export/en');
    $end = microtime(true);

    $response->assertStatus(200);
    $this->assertLessThan(0.5, $end - $start, "Export endpoint took too long");
}
```

### ✅ Search Feature Test
```php
public function test_search_by_locale_and_tag()
{
    Sanctum::actingAs(User::factory()->create());

    Translation::factory()->create([
        'locale' => 'en',
        'key' => 'welcome',
        'content' => 'Welcome',
        'tag' => 'web',
    ]);

    $response = $this->getJson('/api/translations/search?locale=en&tag=web');
    $response->assertStatus(200)
             ->assertJsonFragment(['key' => 'welcome']);
}
```

---

## ✅ You're All Set!

Now you're ready to build, test, and scale this Laravel-based translation API — powered by Sanctum, Docker, Swagger, and high performance principles.
