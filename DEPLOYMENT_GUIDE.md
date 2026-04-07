# 🚀 Complete Deployment Guide - Website Desa Warurejo

**Version:** 1.0  
**Last Updated:** 28 November 2025  
**Author:** Development Team

---

## 📋 Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Shared Hosting Deployment (cPanel)](#shared-hosting-deployment-cpanel)
3. [VPS Deployment (Ubuntu/Debian)](#vps-deployment-ubuntu-debian)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [SSL Certificate](#ssl-certificate)
7. [Performance Optimization](#performance-optimization)
8. [Backup & Recovery](#backup--recovery)
9. [Monitoring Setup](#monitoring-setup)
10. [Troubleshooting](#troubleshooting)

---

## 📝 Pre-Deployment Checklist

### **Requirements Check:**

- [x] PHP 8.2 or higher
- [x] MySQL 8.0+ or MariaDB 10.3+
- [x] Composer installed
- [x] Node.js & npm (for asset compilation)
- [x] Git (optional, for version control)
- [x] Domain name configured
- [x] SSL certificate ready

### **Code Preparation:**

```bash
# 1. Run tests
php artisan test

# 2. Build production assets
npm run build

# 3. Clear and optimize
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 4. Install production dependencies only
composer install --no-dev --optimize-autoloader

# 5. Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 6. Optimize images
php artisan images:optimize
```

### **Files to Prepare:**

- `.env.production` (production environment file)
- Database backup (if migrating)
- SSL certificate files
- Deployment scripts

---

## 🖥️ Shared Hosting Deployment (cPanel)

### **Step 1: Upload Files**

#### **Via cPanel File Manager:**

1. Login to cPanel
2. Navigate to **File Manager**
3. Create folder: `public_html/warurejo/` (or custom name)
4. Upload all project files EXCEPT:
   - `.git/`
   - `node_modules/`
   - `.env` (will create new)
   - `tests/`

#### **Via FTP:**

```bash
# Using FileZilla or similar FTP client
Host: ftp.yourdomain.com
Username: your-username
Password: your-password
Port: 21

# Upload to: /public_html/warurejo/
```

### **Step 2: Set Document Root**

In cPanel, navigate to **Domains** > **Domains**:

1. Select your domain
2. Set **Document Root** to: `public_html/warurejo/public`
3. Save changes

**OR** create `.htaccess` in `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ warurejo/public/$1 [L]
</IfModule>
```

### **Step 3: Database Setup**

#### **Create Database:**

1. In cPanel, go to **MySQL® Databases**
2. Create new database: `username_warurejo`
3. Create new user: `username_warurej_user`
4. Set strong password
5. Add user to database with **ALL PRIVILEGES**
6. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost`)

#### **Import Database:**

If migrating from development:

1. Go to **phpMyAdmin**
2. Select your database
3. Click **Import**
4. Upload your SQL dump file
5. Execute

### **Step 4: Environment Configuration**

Create `.env` file in project root:

```bash
# Using cPanel File Manager
1. Navigate to /public_html/warurejo/
2. Create new file: .env
3. Edit and paste configuration below
```

`.env` content:

```env
APP_NAME="Desa Warurejo"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://warurejo.desa.id

# IMPORTANT: Run php artisan key:generate via Terminal
# Or generate manually and paste here

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_warurejo
DB_USERNAME=username_warurej_user
DB_PASSWORD=your_strong_password

BROADCAST_CONNECTION=null
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
CACHE_PREFIX=warurejo

# Cache TTL Settings
CACHE_PROFIL_TTL=86400
CACHE_BERITA_TTL=3600
CACHE_POTENSI_TTL=21600
CACHE_GALERI_TTL=10800
CACHE_SEO_TTL=86400

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@warurejo.desa.id
MAIL_FROM_NAME="${APP_NAME}"
```

### **Step 5: Generate Application Key**

#### **Via cPanel Terminal:**

```bash
cd public_html/warurejo
php artisan key:generate
```

#### **Via SSH:**

```bash
ssh username@yourdomain.com
cd public_html/warurejo
php artisan key:generate
```

#### **Manual (if no terminal access):**

Generate key online: https://www.md5hashgenerator.com/ (use base64 generator)

Or use this format: `base64:RANDOM_32_CHARACTERS_HERE`

### **Step 6: Set Permissions**

```bash
# Via SSH or Terminal
cd public_html/warurejo

chmod -R 755 storage
chmod -R 755 bootstrap/cache

# If using cPanel File Manager:
# Right-click folder > Change Permissions
# storage: 755
# bootstrap/cache: 755
```

### **Step 7: Run Migrations**

```bash
cd public_html/warurejo

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=ProfilDesaSeeder

# Create storage link
php artisan storage:link
```

### **Step 8: Optimize for Production**

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Optimize images
php artisan images:optimize
```

### **Step 9: Setup Cron Job**

In cPanel, go to **Cron Jobs**:

**Add Cron Job:**

```
Minute: *
Hour: *
Day: *
Month: *
Weekday: *

Command: cd /home/username/public_html/warurejo && php artisan schedule:run >> /dev/null 2>&1
```

Or more specific:

```bash
* * * * * php /home/username/public_html/warurejo/artisan schedule:run >> /dev/null 2>&1
```

### **Step 10: Test Website**

1. Visit your domain: `https://yourdomain.com`
2. Test homepage loads
3. Test admin login: `https://yourdomain.com/admin/login`
4. Test all public pages
5. Test admin CRUD operations
6. Check error logs: `storage/logs/laravel.log`

---

## 🖥️ VPS Deployment (Ubuntu/Debian)

### **Step 1: Server Preparation**

#### **Update System:**

```bash
sudo apt update
sudo apt upgrade -y
```

#### **Install Required Software:**

```bash
# PHP 8.2 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl \
    php8.2-bcmath php8.2-dom php8.2-fileinfo

# Nginx
sudo apt install -y nginx

# MySQL
sudo apt install -y mysql-server

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js & npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Git
sudo apt install -y git

# Redis (optional, for caching)
sudo apt install -y redis-server

# Supervisor (for queue workers)
sudo apt install -y supervisor
```

### **Step 2: MySQL Configuration**

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

**In MySQL prompt:**

```sql
CREATE DATABASE warurejo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'warurejo_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON warurejo.* TO 'warurejo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### **Step 3: Deploy Application**

```bash
# Create project directory
sudo mkdir -p /var/www/warurejo
sudo chown -R $USER:$USER /var/www/warurejo

# Clone or upload project
cd /var/www/warurejo
git clone https://github.com/yourusername/web-profil-warurejo.git .

# Or upload via SCP:
# scp -r /path/to/local/project user@server:/var/www/warurejo

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env
# (Edit database credentials and other settings)

# Generate key
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data /var/www/warurejo
sudo chmod -R 755 /var/www/warurejo
sudo chmod -R 775 /var/www/warurejo/storage
sudo chmod -R 775 /var/www/warurejo/bootstrap/cache

# Run migrations
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=ProfilDesaSeeder

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Optimize images
php artisan images:optimize
```

### **Step 4: Nginx Configuration**

Create Nginx config file:

```bash
sudo nano /etc/nginx/sites-available/warurejo
```

**Configuration:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name warurejo.desa.id www.warurejo.desa.id;
    root /var/www/warurejo/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json application/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Enable site:**

```bash
sudo ln -s /etc/nginx/sites-available/warurejo /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### **Step 5: PHP-FPM Optimization**

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

**Recommended settings:**

```ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
max_input_time = 60

# OPcache
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

**Restart PHP-FPM:**

```bash
sudo systemctl restart php8.2-fpm
```

---

## 🔒 SSL Certificate (Let's Encrypt)

### **Install Certbot:**

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### **Obtain Certificate:**

```bash
sudo certbot --nginx -d warurejo.desa.id -d www.warurejo.desa.id
```

Follow prompts:
- Enter email address
- Agree to terms
- Choose redirect HTTP to HTTPS: **Yes**

### **Auto-renewal:**

```bash
# Test renewal
sudo certbot renew --dry-run

# Renewal is automatic via cron
# Check with:
sudo systemctl status certbot.timer
```

### **Manual Certificate Renewal:**

```bash
sudo certbot renew
sudo systemctl reload nginx
```

---

## ⚡ Performance Optimization

### **Redis Cache (Recommended for Production)**

#### **Install Redis:**

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Test
redis-cli ping
# Should return: PONG
```

#### **Install PHP Redis Extension:**

```bash
sudo apt install -y php8.2-redis
sudo systemctl restart php8.2-fpm
```

#### **Update .env:**

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
```

**Restart:**

```bash
php artisan config:cache
sudo systemctl restart php8.2-fpm
```

### **Database Optimization**

```sql
-- Add indexes (if not already)
ALTER TABLE berita ADD INDEX idx_status_published (status, published_at);
ALTER TABLE berita ADD INDEX idx_slug (slug);
ALTER TABLE potensi_desa ADD INDEX idx_active_slug (is_active, slug);
ALTER TABLE galeri ADD INDEX idx_kategori_status (kategori, status);

-- Optimize tables
OPTIMIZE TABLE berita;
OPTIMIZE TABLE potensi_desa;
OPTIMIZE TABLE galeri;
OPTIMIZE TABLE publikasi;
OPTIMIZE TABLE visitors;
```

### **Queue Worker (Optional)**

Create supervisor config:

```bash
sudo nano /etc/supervisor/conf.d/warurejo-worker.conf
```

**Configuration:**

```ini
[program:warurejo-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/warurejo/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/warurejo/storage/logs/worker.log
stopwaitsecs=3600
```

**Start supervisor:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start warurejo-worker:*

# Check status
sudo supervisorctl status
```

---

## 💾 Backup & Recovery

See [BACKUP_SCRIPTS.md](BACKUP_SCRIPTS.md) for complete automated backup solution.

### **Quick Manual Backup:**

#### **Database Backup:**

```bash
# Backup
mysqldump -u warurejo_user -p warurejo > backup-$(date +%Y%m%d-%H%M%S).sql

# Restore
mysql -u warurejo_user -p warurejo < backup-20251128-120000.sql
```

#### **Files Backup:**

```bash
# Backup uploads
tar -czf uploads-backup-$(date +%Y%m%d).tar.gz storage/app/public

# Restore
tar -xzf uploads-backup-20251128.tar.gz -C storage/app/
```

---

## 📊 Monitoring Setup

See [MONITORING_SETUP.md](MONITORING_SETUP.md) for complete monitoring configuration.

### **Quick Health Check Endpoint:**

Add to `routes/web.php`:

```php
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0'),
        'database' => $dbStatus,
        'cache' => Cache::has('profil_desa') ? 'working' : 'empty',
    ]);
});
```

### **Log Rotation:**

```bash
sudo nano /etc/logrotate.d/warurejo
```

**Configuration:**

```
/var/www/warurejo/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        /usr/bin/systemctl reload php8.2-fpm > /dev/null
    endscript
}
```

---

## 🔧 Troubleshooting

### **Common Issues:**

#### **1. Permission Denied Errors:**

```bash
sudo chown -R www-data:www-data /var/www/warurejo
sudo chmod -R 755 /var/www/warurejo
sudo chmod -R 775 /var/www/warurejo/storage
sudo chmod -R 775 /var/www/warurejo/bootstrap/cache
```

#### **2. 500 Internal Server Error:**

```bash
# Check logs
tail -f /var/www/warurejo/storage/logs/laravel.log
tail -f /var/log/nginx/error.log

# Common fixes:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### **3. Images Not Displaying:**

```bash
# Recreate storage link
php artisan storage:link

# Check permissions
ls -la storage/app/public
```

#### **4. Cache Not Working:**

```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear

# If using Redis:
redis-cli FLUSHALL

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### **5. Database Connection Error:**

```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();

# Check credentials in .env
# Check MySQL is running
sudo systemctl status mysql
```

---

## 📞 Support

**Need Help?**
- Review logs: `storage/logs/laravel.log`
- Check documentation: All `*.md` files in project root
- Laravel docs: https://laravel.com/docs

**Emergency Contacts:**
- Technical Lead: [Your email]
- System Admin: [Admin email]

---

## ✅ Post-Deployment Verification

- [ ] Homepage loads correctly
- [ ] Admin login works
- [ ] All CRUD operations functional
- [ ] Images upload and display
- [ ] Search and filters working
- [ ] SSL certificate valid
- [ ] Cron jobs running
- [ ] Backup system configured
- [ ] Monitoring active
- [ ] Performance acceptable (<2s page load)
- [ ] Mobile responsive
- [ ] Error logging working

---

**Deployment Guide Complete! 🎉**

**Last Updated:** 28 November 2025  
**Version:** 1.0  
**Status:** Production Ready
