<div align="center">

# ✂️ Clippy

**A clean, self-hosted URL shortener with a REST API**

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

[Features](#features) · [Screenshots](#screenshots) · [Quick Start](#quick-start) · [API Reference](#api-reference) · [Configuration](#configuration)

</div>

---

## Overview

Clippy is a self-hosted URL shortener built with Laravel. Shorten long URLs into clean slugs, protect them with passwords, set expiry dates, and manage everything through a web dashboard or REST API.

---

## Features

- **Shorten URLs** — generate short slugs automatically or specify your own
- **Password protection** — require a password before redirecting
- **Expiry dates** — clips automatically expire after a set date
- **Dashboard** — manage all your clips with live stats (total, active, protected, expired)
- **REST API** — full API with Sanctum token authentication
- **Self-hosted** — your data, your server

---

## Screenshots

> _Replace placeholders with real screenshots_

### Dashboard

<img width="1812" height="998" alt="dashboard" src="https://github.com/user-attachments/assets/fb06f112-c296-41aa-9908-39d68769b7b1" />

_The main dashboard showing all clips with stats overview — total, active, password-protected, and expired counts._

### Create a Clip

<img width="1822" height="875" alt="create" src="https://github.com/user-attachments/assets/5b50bf63-2799-41bc-9194-46fdfb5e1a6f" />

_Create a new shortened URL with optional custom slug, password protection, and expiry date._

### Clip Detail

<img width="1812" height="999" alt="CleanShot 2026-06-14 at 15 24 27" src="https://github.com/user-attachments/assets/4fdd19d3-3df5-4a2f-b150-7260efc873e6" />

_View and manage an individual clip — copy the short URL, edit settings, or delete._

### Login

<img width="1809" height="998" alt="login" src="https://github.com/user-attachments/assets/a27a838f-51f0-4019-8213-e16cd140cb8a" />

_Clean login screen. Registration can be enabled/disabled via config._

---

## Quick Start

### Prerequisites

- PHP 8.1+
- Composer
- MySQL / SQLite
- Node.js (for assets)

### Installation

```bash
# 1. Clone the repo
git clone https://github.com/your-username/clippy.git
cd clippy

# 2. Install PHP dependencies
composer install

# 3. Copy and configure environment
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env, then migrate
php artisan migrate

# 5. Build frontend assets
npm install && npm run build

# 6. Start the server
php artisan serve
```

Open `http://localhost:8000` and log in.

### Docker

```bash
docker-compose up -d
```

---

## Configuration

Key `.env` values:

| Variable | Default | Description |
|---|---|---|
| `APP_URL` | `http://localhost` | Base URL used when generating short links |
| `APP_REGISTRATION_ENABLED` | `false` | Allow new user registrations |
| `DB_CONNECTION` | `mysql` | Database driver (`mysql`, `sqlite`, `pgsql`) |

---

## API Reference

All API routes are prefixed with `/api`. Protected routes require a Bearer token (obtained via `/api/login`).

### Authentication

<details>
<summary><code>POST /api/login</code> — Get an access token</summary>

**Request**
```json
{
  "email": "user@example.com",
  "password": "secret"
}
```

**Response `200`**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "1|abc123..."
  }
}
```
</details>

---

### Clips

<details>
<summary><code>GET /api/clips</code> — List all clips</summary>

**Headers:** `Authorization: Bearer {token}`

**Response `200`**
```json
{
  "success": true,
  "message": "Successfully Retrieved Clips",
  "data": [
    {
      "id": 1,
      "url": "https://example.com/very-long-url",
      "slug": "abc123",
      "password": null,
      "expires_at": null,
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```
</details>

<details>
<summary><code>POST /api/clip</code> — Create a clip</summary>

**Headers:** `Authorization: Bearer {token}`

**Request**
```json
{
  "url": "https://example.com/very-long-url",
  "slug": "my-link",        // optional — auto-generated if omitted
  "password": "secret123",  // optional
  "expires_at": "2025-12-31 23:59:59"  // optional
}
```

**Response `201`**
```json
{
  "success": true,
  "message": "Clip created successfully",
  "data": {
    "id": 1,
    "slug": "my-link",
    "short_url": "https://yourapp.com/my-link"
  }
}
```
</details>

<details>
<summary><code>GET /api/clip/{slug}</code> — Get a clip by slug</summary>

**Headers:** `Authorization: Bearer {token}`

**Response `200`**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "url": "https://example.com/very-long-url",
    "slug": "my-link",
    "expires_at": null
  }
}
```
</details>

<details>
<summary><code>PUT /api/clip/{id}</code> — Update a clip</summary>

**Headers:** `Authorization: Bearer {token}`

**Request** — send only the fields you want to change:
```json
{
  "url": "https://new-destination.com",
  "password": null,
  "expires_at": "2026-06-01 00:00:00"
}
```
</details>

<details>
<summary><code>DELETE /api/clip/{id}</code> — Delete a clip</summary>

**Headers:** `Authorization: Bearer {token}`

**Response `200`**
```json
{
  "success": true,
  "message": "Clip deleted successfully"
}
```
</details>

---

## Visiting a Short Link

`GET /{slug}` — Redirects to the original URL. If the clip is password-protected, the visitor is shown a password prompt first. If expired, a `410 Gone` page is shown.

---

## Development

```bash
# Run tests
php artisan test

# Lint with Pint
./vendor/bin/pint

# Regenerate API docs (Scribe)
php artisan scribe:generate
```

API docs are served at `/docs` after running `scribe:generate`.

---

## License

MIT
