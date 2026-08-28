# Panduan Deployment & Operasional Platform MASJID INDONESIA

Dokumen ini berisi spesifikasi teknis dan panduan deployment aplikasi **MASJID INDONESIA** ke lingkungan *Production* (VPS / PaaS / Serverless).

---

## 1. Spesifikasi Server & Infrastruktur Rekomendasi

| Komponen | Spesifikasi Minimum | Rekomendasi Production |
|---|---|---|
| **OS** | Ubuntu 24.04 LTS / Debian 12 | Ubuntu 24.04 LTS |
| **PHP Runtime** | PHP 8.3 / PHP 8.4 CLI + FPM | PHP 8.3/8.4 (JIT Enabled, Opcache) |
| **Web Server** | Nginx 1.26+ | Nginx + Cloudflare Enterprise / Free WAF |
| **Database** | PostgreSQL 16+ | Serverless Neon PostgreSQL / AWS RDS Aurora PostgreSQL |
| **Cache & Queue** | Redis 7+ / DB Cache | Redis Cluster / Valkey 7 |
| **File Storage** | Local Public Storage | Cloudflare R2 / AWS S3 / MinIO S3 |
| **SSL / TLS** | Let's Encrypt SSL | Strict TLS 1.3 |

---

## 2. Environment Variables (.env) Production Template

```dotenv
APP_NAME="MASJID INDONESIA"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://masjidindonesia.id
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

# Neon PostgreSQL Database Connection
DB_CONNECTION=pgsql
DB_HOST=ep-frosty-resonance-auu6llq1-pooler.c-10.us-east-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=your_neon_password
DB_SSLMODE="require;options='endpoint=ep-frosty-resonance-auu6llq1'"

# Queue & Cache
QUEUE_CONNECTION=database # or redis
CACHE_STORE=database # or redis
SESSION_DRIVER=database # or redis

# File Storage
FILESYSTEM_DISK=public # or s3 / r2

# Security Headers
SESSION_SECURE_COOKIE=true
```

---

## 3. Nginx VirtualHost Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name masjidindonesia.id *.masjidindonesia.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name masjidindonesia.id *.masjidindonesia.id;
    root /var/www/masjid/public;

    ssl_certificate /etc/letsencrypt/live/masjidindonesia.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/masjidindonesia.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Deployment Steps via CI/CD / Shell

```bash
# 1. Masuk ke direktori aplikasi
cd /var/www/masjid

# 2. Pull branch release
git pull origin main

# 3. Install composer dependencies (optimized for production)
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Run database migrations
php artisan migrate --force

# 5. Optimize Laravel caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Restart PHP-FPM & Supervisor Queue Workers
sudo systemctl restart php8.3-fpm
sudo supervisorctl restart all
```

---

## 5. Supervisor Worker Configuration (Queues & PDF Generation)

`/etc/supervisor/conf.d/masjid-worker.conf`:

```ini
[program:masjid-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/masjid/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/masjid/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 6. Backup & Disaster Recovery Runbook

1. **Database Automated Backups**: Neon PostgreSQL menyediakan *Point-In-Time Restore (PITR)* otomatis hingga 30 hari ke belakang.
2. **Manual PG Dump**:
   ```bash
   pg_dump "postgresql://neondb_owner:PASSWORD@ep-frosty-resonance-auu6llq1-pooler.c-10.us-east-1.aws.neon.tech/neondb?sslmode=require" > backup_$(date +%F).sql
   ```
3. **Audit Log Inspection**: Super Admin dapat melihat riwayat setiap transaksi dan mutasi di dashboard `/superadmin/audit`.
