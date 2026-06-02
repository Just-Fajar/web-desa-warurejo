# 🚀 Deployment Guide - Website Desa Warurejo

**Framework:** Laravel 12 · PHP 8.2+ · MySQL/MariaDB

---

## 📋 Daftar Isi

1. [Persyaratan](#persyaratan)
2. [Shared Hosting (cPanel)](#shared-hosting-cpanel)
3. [VPS (Ubuntu/Debian)](#vps-ubuntudebian)
4. [SSL Certificate](#ssl-certificate)
5. [Troubleshooting](#troubleshooting)
6. [Post-Deployment Checklist](#post-deployment-checklist)

---

## 📝 Persyaratan

- PHP 8.2+
- MySQL 8.0+ atau MariaDB 10.3+
- Composer
- Node.js & npm (untuk build assets)
- Domain + SSL certificate

### Persiapan Sebelum Deploy

```bash
# 1. Build production assets
npm run build

# 2. Install production dependencies
composer install --no-dev --optimize-autoloader

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🖥️ Shared Hosting (cPanel)

### Step 1: Upload Files

1. Login cPanel → **File Manager**
2. Upload semua file ke `public_html/warurejo/` **kecuali:**
   - `.git/`, `node_modules/`, `tests/`, `.env`

3. Set **Document Root** ke `public_html/warurejo/public`

   Atau buat `.htaccess` di `public_html/`:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ warurejo/public/$1 [L]
   </IfModule>
   ```

### Step 2: Buat Database

1. cPanel → **MySQL® Databases**
2. Buat database: `username_warurejo`
3. Buat user + strong password
4. Add user ke database → **ALL PRIVILEGES**

### Step 3: Konfigurasi `.env`

Buat file `.env` di root project:

```env
APP_NAME="Desa Warurejo"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://warurejo.desa.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_warurejo
DB_USERNAME=username_warurej_user
DB_PASSWORD=your_strong_password

FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
CACHE_PREFIX=warurejo

# Cache TTL (seconds)
CACHE_PROFIL_TTL=86400
CACHE_BERITA_TTL=3600
CACHE_POTENSI_TTL=21600
CACHE_GALERI_TTL=10800
CACHE_SEO_TTL=86400

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@warurejo.desa.id
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Setup Aplikasi

```bash
cd public_html/warurejo

# Generate app key
php artisan key:generate

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Run migrations & seed
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 5: Setup Cron Job

cPanel → **Cron Jobs**, tambahkan:

```
* * * * * cd /home/username/public_html/warurejo && php artisan schedule:run >> /dev/null 2>&1
```

### Step 6: Test

1. Buka `https://yourdomain.com` — homepage harus muncul
2. Buka `https://yourdomain.com/admin/login` — login admin
3. Test CRUD di admin panel
4. Cek `storage/logs/laravel.log` jika ada error

---

## 🖥️ VPS (Ubuntu/Debian)

### Step 1: Install Software

```bash
sudo apt update && sudo apt upgrade -y

# PHP 8.2
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl \
    php8.2-bcmath php8.2-dom php8.2-fileinfo

# Nginx + MySQL
sudo apt install -y nginx mysql-server

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs git
```

### Step 2: Setup Database

```bash
sudo mysql_secure_installation
sudo mysql -u root -p
```

```sql
CREATE DATABASE warurejo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'warurejo_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON warurejo.* TO 'warurejo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Deploy Aplikasi

```bash
sudo mkdir -p /var/www/warurejo
sudo chown -R $USER:$USER /var/www/warurejo
cd /var/www/warurejo

# Clone project
git clone https://github.com/Just-Fajar/web-profil-warurejo.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Setup environment
cp .env.example .env
nano .env  # Edit database credentials, APP_ENV=production, APP_DEBUG=false

# Generate key & migrate
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data /var/www/warurejo
sudo chmod -R 755 /var/www/warurejo
sudo chmod -R 775 /var/www/warurejo/storage
sudo chmod -R 775 /var/www/warurejo/bootstrap/cache

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 4: Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/warurejo
```

```nginx
server {
    listen 80;
    server_name warurejo.desa.id www.warurejo.desa.id;
    root /var/www/warurejo/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript
               application/javascript application/json application/xml+rss;

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

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/warurejo /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 5: Setup Cron Job

```bash
# Tambahkan ke crontab
(crontab -l; echo "* * * * * cd /var/www/warurejo && php artisan schedule:run >> /dev/null 2>&1") | crontab -
```

---

## 🔒 SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d warurejo.desa.id -d www.warurejo.desa.id

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## 🔧 Troubleshooting

### Permission Denied

```bash
sudo chown -R www-data:www-data /var/www/warurejo
sudo chmod -R 775 storage bootstrap/cache
```

### 500 Internal Server Error

```bash
tail -f storage/logs/laravel.log
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Gambar Tidak Muncul

```bash
php artisan storage:link
```

### Database Connection Error

```bash
php artisan tinker
# Lalu ketik: DB::connection()->getPdo();
```

---

## ✅ Post-Deployment Checklist

- [ ] `APP_ENV=production` & `APP_DEBUG=false`
- [ ] APP_KEY sudah di-generate
- [ ] Database MySQL terkonfigurasi
- [ ] SSL certificate aktif (HTTPS)
- [ ] Cron job berjalan (`schedule:run`)
- [ ] Homepage load dengan benar
- [ ] Admin login berfungsi
- [ ] CRUD operations berfungsi
- [ ] Upload gambar berfungsi
- [ ] Storage link sudah dibuat
- [ ] Error logging aktif (`storage/logs/laravel.log`)
