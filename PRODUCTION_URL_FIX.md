# Production URL Fix - Removing /public from URLs

## Problem
On live environment, URLs are showing `/public` in them (e.g., `https://domain.com/public/register`).

## Solution Options

### Option 1: Point Web Server Document Root to `public` Directory (RECOMMENDED)

This is the **best practice** for Laravel applications:

1. **For Apache:**
   - Update your VirtualHost configuration to point `DocumentRoot` to the `public` directory:
   ```apache
   <VirtualHost *:80>
       ServerName yourdomain.com
       DocumentRoot /path/to/your/project/public
       
       <Directory /path/to/your/project/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **For Nginx:**
   - Update your server block to point `root` to the `public` directory:
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /path/to/your/project/public;
       
       index index.php;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

3. **For cPanel:**
   - In cPanel File Manager, navigate to your domain's public_html
   - Move all files from `public` folder to `public_html`
   - Or create a symlink: `ln -s /path/to/project/public /path/to/public_html`

### Option 2: Keep Document Root at Project Root (Current Setup)

If you cannot change the document root, ensure:

1. **Update `.env` file:**
   ```env
   APP_URL=https://yourdomain.com
   # DO NOT include /public in APP_URL
   ```

2. **The root `.htaccess` is already configured** to route to `public/index.php`

3. **Verify asset URLs:**
   - Laravel's `asset()` helper should generate URLs without `/public`
   - If you see `/public` in URLs, clear config cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Verification Steps

1. **Check APP_URL:**
   ```bash
   php artisan tinker
   >>> config('app.url')
   ```
   Should return: `https://yourdomain.com` (NOT `https://yourdomain.com/public`)

2. **Test asset URLs:**
   - Visit any page and check the browser's Network tab
   - CSS/JS files should load from `/build/assets/...` not `/public/build/assets/...`

3. **Test routes:**
   - Visit `https://yourdomain.com/register`
   - Should work without `/public` in the URL

## Additional Configuration

### If using subdirectory (e.g., `/event` or `/mycard`):

Update `.env`:
```env
APP_URL=https://yourdomain.com/event
```

The `.htaccess` is already configured to handle subdirectory setups.

## Troubleshooting

### URLs still show /public:

1. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Check .htaccess is working:**
   - Create a test file: `public/test.txt`
   - Access: `https://yourdomain.com/test.txt`
   - Should work without `/public` in URL

3. **Check web server configuration:**
   - Ensure mod_rewrite is enabled
   - Ensure `.htaccess` files are allowed (AllowOverride All)

4. **Check APP_URL in .env:**
   - Must NOT include `/public`
   - Should be: `APP_URL=https://yourdomain.com`
   - NOT: `APP_URL=https://yourdomain.com/public`

## Files Modified

- `.htaccess` (root) - Updated to route to `public/index.php`
