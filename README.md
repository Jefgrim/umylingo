IT26 University of Mindanao Project

# UMYLINGO — Language Learning Web Application

Built with **Laravel 11 · Livewire 3 · Tailwind CSS · MySQL**

---

## Local Development

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

---

## Deployment (Railway — Free Tier)

### 1. Create a Railway project
- Go to [railway.app](https://railway.app) → **New Project → Deploy from GitHub repo → Jefgrim/umylingo**
- Railway auto-detects `nixpacks.toml`

### 2. Add MySQL
- In your project → **+ New → Database → MySQL**

### 3. Set Environment Variables

In your **app service → Variables tab → Raw Editor**, add the following:

APP_NAME=umylingo
APP_ENV=production
APP_KEY=base64:Xrh5EKXiL5TZ/ROQbWQlgAuCuvf3Ox+UIFpAC2h4RJI=
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=sync
CACHE_STORE=database
FILESYSTEM_DISK=local
LOG_CHANNEL=stderr
LOG_LEVEL=error
SEEDER_USER_PASSWORD=123456123456
SEEDER_ADMIN1_FIRSTNAME=Glenn
SEEDER_ADMIN1_LASTNAME=Oliva
SEEDER_ADMIN1_USERNAME=admin
SEEDER_ADMIN1_EMAIL=admin@umylingo
SEEDER_ADMIN1_PASSWORD=123456123456
SEEDER_ADMIN2_FIRSTNAME=Cyril
SEEDER_ADMIN2_LASTNAME=Tomas
SEEDER_ADMIN2_USERNAME=admin2
SEEDER_ADMIN2_EMAIL=admin2@umylingo
SEEDER_ADMIN2_PASSWORD=123456123456


> **Note:** `${{MySQL.MYSQLHOST}}` is Railway's reference variable syntax — it pulls the value automatically from your MySQL service.

### 4. Deploy & Seed

Railway auto-deploys on every push. Migrations run automatically on start.

After the **first** successful deploy, seed the database **once**:

```bash
# Via Railway Shell tab (in the dashboard), or Railway CLI:
php artisan db:seed --force
```

### 5. Get your live URL

Railway → your app service → **Settings → Domains** → copy the `.railway.app` URL, then update `APP_URL`.

---

## Auto-Deploy

Every push to `main` triggers a new Railway deployment:
```
git push origin main  →  Railway builds  →  migrations run  →  app live
```
