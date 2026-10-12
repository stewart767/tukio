# 🚨 Tukio360 Production 500 Error - Complete Fix Guide

## 🔍 ROOT CAUSE IDENTIFIED

**Primary Issue:** Database configuration error
- Error: `Database file at path [rental] does not exist`
- The `.env` file has `DB_DATABASE=rental` which is invalid for SQLite
- System is trying to use SQLite but the database path is incorrect

**Secondary Issues:**
- Missing `.htaccess` file in `public/` directory (critical for shared hosting)
- Database connection failures not handled gracefully in SystemSettingsService
- Potential permission issues on shared hosting

---

## ✅ STEP-BY-STEP FIX PROCEDURE

### STEP 1: Access Your Server

1. **SSH into your server** (or use cPanel File Manager)
2. **Navigate to your Laravel root directory** (usually `public_html` or `htdocs`)

---

### STEP 2: Identify the Real Error

#### Option A: Check Laravel Logs
```bash
cd /path/to/your/laravel/project
tail -n 100 storage/logs/laravel.log
```

#### Option B: Temporarily Enable Debug Mode
1. Edit `.env` file:
```bash
APP_DEBUG=true
APP_ENV=local
```
2. Refresh your website - you'll see the exact error
3. **IMPORTANT:** Set back to production after fixing:
```bash
APP_DEBUG=false
APP_ENV=production
```

---

### STEP 3: Fix Database Configuration

#### For MySQL/MariaDB (Recommended for Production):

1. **Create a database** in cPanel:
   - Go to cPanel → MySQL Databases
   - Create a new database (e.g., `tukio360_db`)
   - Create a database user and assign it to the database
   - Note down: database name, username, password

2. **Update `.env` file:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

#### For SQLite (Not Recommended for Production):

If you must use SQLite, ensure the database file exists:
```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
```

Then update `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**⚠️ WARNING:** SQLite is not recommended for production. Use MySQL/MariaDB instead.

---

### STEP 4: Verify `.env` Configuration

Ensure your `.env` file has these critical settings:

```env
APP_NAME="Tukio 360"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://tukio360.co.tz

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Generate APP_KEY if missing:**
```bash
php artisan key:generate
```

---

### STEP 5: Fix File Permissions

Run these commands on your server:

```bash
# Navigate to Laravel root
cd /path/to/your/laravel/project

# Set ownership (adjust user:group for your server)
# Common: www-data:www-data (Ubuntu/Debian) or apache:apache (CentOS)
chown -R www-data:www-data .

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Critical: Storage and cache directories need write permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**For cPanel/Shared Hosting:**
- Use File Manager → Right-click folders → Change Permissions
- Set `storage/` and `bootstrap/cache/` to **775**
- Set all other directories to **755**
- Set all files to **644**

---

### STEP 6: Verify `.htaccess` File

**Ensure `public/.htaccess` exists** with this content:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**If missing, create it:**
```bash
# The file should already exist after deployment, but verify:
ls -la public/.htaccess
```

---

### STEP 7: Verify Document Root

**Critical for Shared Hosting:**

1. **In cPanel:**
   - Go to **Domains** → **Manage** → Select your domain
   - Ensure **Document Root** points to: `/public_html/public` (or `/public_html/your-project/public`)
   - **NOT** `/public_html` (root directory)

2. **Alternative:** If you can't change document root, create a `.htaccess` in root:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### STEP 8: Clear and Rebuild Caches

Run these commands:

```bash
cd /path/to/your/laravel/project

# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild optimized files (only if safe)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**⚠️ Note:** On shared hosting, you might need to run these via SSH or cPanel Terminal.

---

### STEP 9: Verify PHP Environment

**Check PHP Version:**
```bash
php -v
```
**Required:** PHP 8.2 or higher (Laravel 12 requirement)

**Check Required Extensions:**
```bash
php -m | grep -E "pdo|mbstring|openssl|tokenizer|xml|curl|fileinfo|json|bcmath"
```

**Required Extensions:**
- pdo
- pdo_mysql (or pdo_sqlite)
- mbstring
- openssl
- tokenizer
- xml
- curl
- fileinfo
- json
- bcmath

**Increase Memory Limit:**
Edit `php.ini` or add to `.htaccess`:
```apache
php_value memory_limit 256M
```

---

### STEP 10: Database Migration

**Run migrations if needed:**
```bash
php artisan migrate --force
```

**⚠️ WARNING:** Only run if you haven't already migrated. This will create tables.

**Check if tables exist:**
```bash
php artisan tinker
>>> Schema::hasTable('settings')
```

---

### STEP 11: Create Storage Symbolic Link

```bash
php artisan storage:link
```

**Verify the link exists:**
```bash
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

**If symbolic links don't work on shared hosting:**
- Copy files manually or use a script
- Ensure `public/storage` directory exists and is writable

---

### STEP 12: Test Database Connection

**Create a test file** `test-db.php` in `public/`:

```php
<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::connection()->getPdo();
    echo "✅ Database connection successful!";
} catch (\Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
```

**Access:** `https://tukio360.co.tz/test-db.php`
**Delete this file after testing!**

---

### STEP 13: Final Verification

1. **Test Homepage:**
   - Visit: `https://tukio360.co.tz/`
   - Should load without 500 error

2. **Test Admin Login:**
   - Visit: `https://tukio360.co.tz/login`
   - Should load login form

3. **Check Image Display:**
   - Verify uploaded images display correctly
   - Check browser console for 404 errors

4. **Test Navigation:**
   - Click through main navigation links
   - Ensure no 500 errors

---

### STEP 14: Production Safety

**Ensure these are set in `.env`:**
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

**Remove test files:**
```bash
rm public/test-db.php
```

**Monitor logs:**
```bash
tail -f storage/logs/laravel.log
```

---

## 🔧 QUICK FIX CHECKLIST

- [ ] `.env` file has correct database credentials
- [ ] `DB_DATABASE` is NOT set to "rental" (use actual database name)
- [ ] `APP_KEY` is generated and set
- [ ] `APP_DEBUG=false` and `APP_ENV=production`
- [ ] `public/.htaccess` exists and is correct
- [ ] Document root points to `/public` directory
- [ ] `storage/` and `bootstrap/cache/` have 775 permissions
- [ ] `php artisan storage:link` has been run
- [ ] All caches cleared: `php artisan optimize:clear`
- [ ] PHP version is 8.2+ with all required extensions
- [ ] Database connection tested and working
- [ ] Migrations run (if needed)
- [ ] Homepage loads without errors

---

## 🆘 TROUBLESHOOTING

### Still Getting 500 Error?

1. **Check server error logs:**
   - cPanel → Errors → Latest Errors
   - Or: `/home/username/logs/error_log`

2. **Enable detailed error reporting temporarily:**
   ```env
   APP_DEBUG=true
   LOG_LEVEL=debug
   ```

3. **Check PHP error log:**
   ```bash
   tail -f /path/to/php/error_log
   ```

4. **Verify file paths are correct:**
   - All paths in `.env` should be absolute or relative to project root
   - No trailing slashes

### Database Connection Issues?

1. **Test connection manually:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

2. **Check database credentials:**
   - Verify username/password in cPanel MySQL section
   - Ensure database user has proper permissions

3. **Check host:**
   - For cPanel: Usually `127.0.0.1` or `localhost`
   - Some hosts use: `localhost:/tmp/mysql.sock`

### Permission Issues?

1. **Check ownership:**
   ```bash
   ls -la storage/
   ls -la bootstrap/cache/
   ```

2. **Fix ownership:**
   ```bash
   chown -R username:username storage bootstrap/cache
   ```

### Images Not Displaying?

1. **Check storage link:**
   ```bash
   ls -la public/storage
   ```

2. **Recreate link:**
   ```bash
   rm public/storage
   php artisan storage:link
   ```

3. **Check file permissions:**
   ```bash
   chmod -R 755 storage/app/public
   ```

---

## 📞 SUPPORT

If issues persist after following this guide:

1. **Collect error information:**
   - Latest 50 lines of `storage/logs/laravel.log`
   - Server error log entries
   - PHP version: `php -v`
   - Laravel version: `php artisan --version`

2. **Check server requirements:**
   - PHP 8.2+
   - Required extensions installed
   - Sufficient memory (256M+)

---

## ✅ SUCCESS CRITERIA

Your site is fixed when:
- ✅ Homepage loads: `https://tukio360.co.tz/`
- ✅ No 500 errors in browser
- ✅ Admin login page loads
- ✅ Images display correctly
- ✅ Navigation works
- ✅ No errors in `storage/logs/laravel.log`

---

**Last Updated:** 2025-01-27
**Laravel Version:** 12.0
**PHP Requirement:** 8.2+
