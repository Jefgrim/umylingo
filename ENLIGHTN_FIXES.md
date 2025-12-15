# Enlightn Security & Performance Fixes

## ✅ Fixed Issues

### 1. Environment Function Calls (Performance)
- **Status**: ✅ Fixed
- Moved all `env()` calls from controllers and seeders to config files
- Created `config/ops.php` for MySQL/mysqldump paths
- Created `config/seeder.php` for seeder defaults
- Updated `DatabaseSeeder.php` and `OpsController.php` to use `config()` instead

### 2. Cache Prefix Collision (Reliability)
- **Status**: ✅ Fixed
- Changed cache prefix from generic `laravel_cache_` to unique hash-based prefix
- Uses `uml_<md5_hash>_` format to prevent collisions with other apps

### 3. Unused Global Middleware (Performance)
- **Status**: ✅ Fixed
- Removed unused `trustProxies` call and created proper middleware class
- Consolidated all middleware registration in `bootstrap/app.php`

### 4. Vulnerable Dependencies (Security)
- **Status**: ✅ Fixed
- Updated Laravel Framework: 11.34.2 → 11.47.0
- Updated Symfony HTTP Foundation: 7.1.9 → 7.4.1
- Patched CVE-2025-64500 and Laravel XSS vulnerabilities

### 5. XSS Protection (Security)
- **Status**: ✅ Fixed
- Created `ContentSecurityPolicy` middleware with environment-aware policies
- Development: Allows inline scripts/styles for debugging
- Production: Strict CSP without `unsafe-eval` or `unsafe-inline`

### 6. HSTS Header (Security)
- **Status**: ✅ Fixed (requires production env)
- Created `StrictTransportSecurity` middleware
- Adds HSTS header when `SESSION_SECURE_COOKIE` is enabled
- Configure in production: `SESSION_SECURE_COOKIE=true`

## ⚠️ Production Environment Requirements

The following checks require server/environment configuration and cannot be fixed in code:

### 1. MySQL Unix Socket (Performance)
- **Current**: Using TCP connection (host:port)
- **Production Recommendation**: Use Unix socket for 50% performance gain
- **How to Fix**:
  ```env
  # Find your MySQL socket path (usually /tmp/mysql.sock or /var/run/mysqld/mysqld.sock)
  DB_SOCKET=/tmp/mysql.sock
  # Leave DB_HOST and DB_PORT unset or empty when using socket
  ```

### 2. OPcache Configuration (Performance)
- **Current**: Disabled
- **Production Requirement**: Enable OPcache in php.ini
- **How to Fix** (php.ini or PHP-FPM pool config):
  ```ini
  opcache.enable=1
  opcache.enable_cli=0
  opcache.memory_consumption=256
  opcache.interned_strings_buffer=16
  opcache.max_accelerated_files=10000
  opcache.validate_timestamps=0  ; Disable in production
  opcache.revalidate_freq=0
  opcache.save_comments=1
  ```

### 3. PHP Security Configuration (Security)
- **Current**: Default PHP settings
- **Production Requirement**: Harden php.ini
- **How to Fix** (php.ini):
  ```ini
  allow_url_fopen=0
  expose_php=0
  display_startup_errors=0
  display_errors=0
  log_errors=1
  ```

### 4. File Permissions (Security)
- **Current**: Windows development (777/666)
- **Production Requirement**: Restrict permissions on Linux/Unix
- **How to Fix** (deployment script):
  ```bash
  # Set directory permissions to 775
  find /path/to/project -type d -exec chmod 775 {} \;
  
  # Set file permissions to 664
  find /path/to/project -type f -exec chmod 664 {} \;
  
  # Set executable permissions for artisan and deployment scripts
  chmod 775 /path/to/project/artisan
  
  # Storage and cache need write access
  chmod -R 775 storage bootstrap/cache
  ```

### 5. HTTPS Configuration (Security)
- **Production Checklist**:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://umylingo.online
  SESSION_SECURE_COOKIE=true
  ```
- Configure web server (nginx/Apache) to force HTTPS
- HSTS middleware will automatically add security headers

### 6. Unstable Dependencies (Security)
- **Current**: Root package on `dev-main`
- **How to Fix**: This is expected for development
- For production deployment, use tagged releases or ensure composer.lock is committed

## 🚀 Deployment Checklist

Before deploying to production:

```bash
# 1. Update environment
cp .env.example .env.production
# Edit .env.production with production values

# 2. Install dependencies (production only)
composer install --no-dev --optimize-autoloader

# 3. Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Set permissions
chmod -R 775 storage bootstrap/cache
find . -type d -exec chmod 775 {} \;
find . -type f -exec chmod 664 {} \;
chmod 775 artisan

# 5. Verify Enlightn passes all applicable checks
php artisan enlightn
```

## 📝 Configuration Files Modified

- `app/Http/Controllers/Admin/OpsController.php` - Removed env() calls
- `app/Http/Middleware/ContentSecurityPolicy.php` - New CSP middleware
- `app/Http/Middleware/StrictTransportSecurity.php` - New HSTS middleware
- `app/Http/Middleware/TrustProxies.php` - New proxy middleware
- `bootstrap/app.php` - Consolidated middleware registration
- `config/cache.php` - Unique cache prefix
- `config/database.php` - Socket preference for MySQL
- `config/ops.php` - New ops tooling configuration
- `config/seeder.php` - New seeder configuration
- `config/trustedproxy.php` - New proxy configuration
- `database/seeders/DatabaseSeeder.php` - Use config() instead of env()

## 🔍 Testing

Run Enlightn scan:
```bash
php artisan enlightn
```

Expected results:
- ✅ All Performance checks pass (except OPcache on dev)
- ✅ All Reliability checks pass
- ✅ All Security checks pass (except php.ini and file permissions on dev)
