# 📊 Production Monitoring Setup - Website Desa Warurejo

**Version:** 1.0  
**Last Updated:** 28 November 2025

---

## 📋 Table of Contents

1. [Monitoring Overview](#monitoring-overview)
2. [Uptime Monitoring](#uptime-monitoring)
3. [Application Performance Monitoring](#application-performance-monitoring)
4. [Log Monitoring](#log-monitoring)
5. [Error Tracking](#error-tracking)
6. [Server Resource Monitoring](#server-resource-monitoring)
7. [Database Monitoring](#database-monitoring)
8. [Security Monitoring](#security-monitoring)
9. [Alerting System](#alerting-system)

---

## 🎯 Monitoring Overview

### **What We Monitor**

| Category | Metrics | Tools | Frequency |
|----------|---------|-------|-----------|
| **Uptime** | HTTP status, response time | UptimeRobot, Pingdom | 1-5 min |
| **Performance** | Page load, queries, memory | Laravel Telescope, APM | Real-time |
| **Logs** | Errors, warnings, info | Logrotate, ELK | Continuous |
| **Errors** | Exceptions, 500s, 404s | Sentry, Bugsnag | Real-time |
| **Resources** | CPU, RAM, disk, network | Netdata, Prometheus | 10 sec |
| **Database** | Queries, connections, slow logs | MySQL status, pt-query-digest | 1 min |
| **Security** | Failed logins, brute force | Fail2ban, ModSecurity | Real-time |

---

## 🌐 Uptime Monitoring

### **1. UptimeRobot (Free - Recommended)**

#### **Setup Steps:**

1. **Sign up:** https://uptimerobot.com
2. **Create monitors:**

```
Monitor 1: Main Website
- Type: HTTP(s)
- URL: https://warurejo.desa.id
- Monitoring Interval: 5 minutes
- Alert Contacts: Your email/WhatsApp

Monitor 2: Admin Panel
- Type: HTTP(s)
- URL: https://warurejo.desa.id/admin/login
- Monitoring Interval: 5 minutes

Monitor 3: API Health Check
- Type: HTTP(s)
- URL: https://warurejo.desa.id/health
- Expected Status: 200
- Monitoring Interval: 5 minutes
```

#### **Create Health Check Endpoint**

Add to `routes/web.php`:

```php
// Health check endpoint for monitoring
Route::get('/health', function () {
    $checks = [
        'app' => 'ok',
        'database' => 'ok',
        'cache' => 'ok',
        'storage' => 'ok',
    ];
    
    try {
        // Check database
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $checks['database'] = 'error';
    }
    
    try {
        // Check cache
        Cache::put('health_check', true, 10);
        Cache::get('health_check');
    } catch (\Exception $e) {
        $checks['cache'] = 'error';
    }
    
    try {
        // Check storage
        Storage::disk('public')->exists('test.txt');
    } catch (\Exception $e) {
        $checks['storage'] = 'error';
    }
    
    $status = in_array('error', $checks) ? 500 : 200;
    
    return response()->json([
        'status' => $status === 200 ? 'healthy' : 'unhealthy',
        'timestamp' => now()->toIso8601String(),
        'checks' => $checks,
    ], $status);
});
```

#### **Alert Notifications:**

Configure in UptimeRobot:
- Email alerts
- SMS alerts (paid)
- WhatsApp via integration
- Slack/Discord webhooks

---

### **2. Self-Hosted Monitoring (Uptime Kuma)**

#### **Install on VPS:**

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Run Uptime Kuma
docker run -d --restart=always \
  -p 3001:3001 \
  -v uptime-kuma:/app/data \
  --name uptime-kuma \
  louislam/uptime-kuma:1
```

#### **Access:**
```
http://your-server-ip:3001
```

#### **Configure Monitors:**
- Same as UptimeRobot setup
- Add SSL certificate expiry monitoring
- Add port monitoring (MySQL 3306, Redis 6379)

---

## ⚡ Application Performance Monitoring (APM)

### **1. Laravel Telescope (Development/Staging)**

#### **Install:**

```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

#### **Configure** `.env`:

```env
TELESCOPE_ENABLED=true
TELESCOPE_DRIVER=database
```

#### **Protect in Production**

`app/Providers/TelescopeServiceProvider.php`:

```php
protected function gate()
{
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@warurejo.desa.id',
        ]);
    });
}
```

#### **Access:**
```
https://warurejo.desa.id/telescope
```

#### **What to Monitor:**
- Slow queries (>100ms)
- N+1 query problems
- Memory usage per request
- Request duration
- Failed jobs
- Exception logs

---

### **2. Laravel Debugbar (Local Development Only)**

Already installed. Configure:

`.env.local`:
```env
DEBUGBAR_ENABLED=true
```

`.env.production`:
```env
DEBUGBAR_ENABLED=false
```

---

### **3. Custom Performance Logger**

Create `app/Http/Middleware/PerformanceMonitor.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerformanceMonitor
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $response = $next($request);
        
        $duration = (microtime(true) - $startTime) * 1000; // ms
        $memoryUsage = (memory_get_usage() - $startMemory) / 1024 / 1024; // MB
        
        // Log slow requests (>1 second)
        if ($duration > 1000) {
            Log::warning('Slow Request Detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration_ms' => round($duration, 2),
                'memory_mb' => round($memoryUsage, 2),
                'ip' => $request->ip(),
            ]);
        }
        
        // Add headers for monitoring
        $response->headers->set('X-Response-Time', round($duration, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', round($memoryUsage, 2) . 'MB');
        
        return $response;
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\PerformanceMonitor::class);
})
```

---

## 📝 Log Monitoring

### **1. Log Rotation Setup**

Create `/etc/logrotate.d/warurejo`:

```bash
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
        # Optional: notify log rotation
        echo "Warurejo logs rotated on $(date)" >> /var/log/warurejo-logrotate.log
    endscript
}
```

**Test:**
```bash
sudo logrotate -f /etc/logrotate.d/warurejo
```

---

### **2. Real-time Log Monitoring**

#### **GoAccess (Web Server Logs)**

```bash
# Install
sudo apt install goaccess -y

# Analyze Nginx logs
goaccess /var/log/nginx/access.log -o /var/www/html/logs-report.html \
  --log-format=COMBINED --real-time-html

# Access report
https://warurejo.desa.id/logs-report.html
```

#### **Laravel Log Viewer (Package)**

```bash
composer require rap2hpoutre/laravel-log-viewer
```

Add route in `routes/web.php`:

```php
Route::middleware(['auth:admin', 'verified'])->group(function () {
    Route::get('admin/logs', [\Rap2hpoutre\LaravelLogViewer\LaravelLogViewerController::class, 'index'])
        ->name('admin.logs');
});
```

Access: `https://warurejo.desa.id/admin/logs`

---

### **3. Custom Log Analysis Script**

Create `/usr/local/bin/analyze-warurejo-logs.sh`:

```bash
#!/bin/bash

LOG_FILE="/var/www/warurejo/storage/logs/laravel.log"
OUTPUT="/var/www/warurejo/storage/logs/analysis-$(date +%Y%m%d).txt"

echo "=== Warurejo Log Analysis - $(date) ===" > "$OUTPUT"
echo "" >> "$OUTPUT"

# Count errors by type
echo "Errors by Type:" >> "$OUTPUT"
grep -i "ERROR" "$LOG_FILE" | awk '{print $NF}' | sort | uniq -c | sort -rn | head -10 >> "$OUTPUT"
echo "" >> "$OUTPUT"

# Most common exceptions
echo "Top 10 Exceptions:" >> "$OUTPUT"
grep -i "exception" "$LOG_FILE" | awk -F': ' '{print $2}' | sort | uniq -c | sort -rn | head -10 >> "$OUTPUT"
echo "" >> "$OUTPUT"

# Slow queries
echo "Slow Queries (>100ms):" >> "$OUTPUT"
grep -i "slow query" "$LOG_FILE" | tail -20 >> "$OUTPUT"
echo "" >> "$OUTPUT"

# Failed logins
echo "Failed Login Attempts:" >> "$OUTPUT"
grep -i "failed login" "$LOG_FILE" | wc -l >> "$OUTPUT"

echo "Analysis saved to: $OUTPUT"
```

**Add to cron (daily):**

```cron
0 1 * * * /usr/local/bin/analyze-warurejo-logs.sh
```

---

## 🐛 Error Tracking

### **1. Sentry Integration (Recommended)**

#### **Sign up:** https://sentry.io (Free tier: 5,000 errors/month)

#### **Install:**

```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn
```

#### **Configure** `.env`:

```env
SENTRY_LARAVEL_DSN=your-sentry-dsn
SENTRY_TRACES_SAMPLE_RATE=0.2
SENTRY_ENVIRONMENT=production
```

#### **Configure** `config/sentry.php`:

```php
return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'environment' => env('SENTRY_ENVIRONMENT', 'production'),
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.0),
    
    // Ignore specific exceptions
    'ignore_exceptions' => [
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ],
    
    // Before send callback
    'before_send' => function (\Sentry\Event $event): ?\Sentry\Event {
        // Filter sensitive data
        return $event;
    },
];
```

#### **Test:**

```php
// In any controller
throw new \Exception('Test Sentry Integration');
```

Check Sentry dashboard for error.

---

### **2. Custom Error Reporting**

Create `app/Services/ErrorReportingService.php`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorReportingService
{
    public static function reportCriticalError(\Throwable $exception, array $context = [])
    {
        // Log error
        Log::critical($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
        ]);
        
        // Send email notification for critical errors
        if (config('app.env') === 'production') {
            try {
                Mail::raw(
                    "Critical error occurred:\n\n" .
                    "Message: {$exception->getMessage()}\n" .
                    "File: {$exception->getFile()}:{$exception->getLine()}\n" .
                    "Time: " . now()->toDateTimeString(),
                    function ($message) {
                        $message->to(config('mail.admin_email'))
                            ->subject('[CRITICAL] Warurejo Error Alert');
                    }
                );
            } catch (\Exception $e) {
                Log::error('Failed to send error email', ['error' => $e->getMessage()]);
            }
        }
    }
}
```

Use in `app/Exceptions/Handler.php`:

```php
public function report(Throwable $exception)
{
    // Report critical database errors
    if ($exception instanceof \Illuminate\Database\QueryException) {
        \App\Services\ErrorReportingService::reportCriticalError($exception);
    }
    
    parent::report($exception);
}
```

---

## 💻 Server Resource Monitoring

### **1. Netdata (Real-time Monitoring)**

#### **Install:**

```bash
bash <(curl -Ss https://my-netdata.io/kickstart.sh)
```

#### **Access:**
```
http://your-server-ip:19999
```

#### **Secure Access:**

```bash
# Create auth file
sudo apt install apache2-utils -y
sudo htpasswd -c /etc/netdata/htpasswd admin

# Configure Netdata
sudo nano /etc/netdata/netdata.conf
```

Add:
```ini
[web]
    bind to = 127.0.0.1
```

#### **Nginx Reverse Proxy:**

```nginx
location /netdata/ {
    proxy_pass http://127.0.0.1:19999/;
    auth_basic "Restricted";
    auth_basic_user_file /etc/netdata/htpasswd;
}
```

#### **What Netdata Monitors:**
- CPU usage per core
- RAM usage
- Disk I/O
- Network traffic
- PHP-FPM processes
- Nginx connections
- MySQL queries
- System load

---

### **2. Custom Resource Monitor Script**

Create `/usr/local/bin/monitor-warurejo-resources.sh`:

```bash
#!/bin/bash

LOG_FILE="/var/log/warurejo-resources.log"
THRESHOLD_CPU=80
THRESHOLD_MEM=85
THRESHOLD_DISK=90

# Get metrics
CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | awk '{print $2}' | cut -d'%' -f1)
MEM_USAGE=$(free | grep Mem | awk '{printf("%.0f"), $3/$2 * 100}')
DISK_USAGE=$(df -h / | tail -1 | awk '{print $5}' | sed 's/%//')

# Log
echo "[$(date '+%Y-%m-%d %H:%M:%S')] CPU: ${CPU_USAGE}% | MEM: ${MEM_USAGE}% | DISK: ${DISK_USAGE}%" >> "$LOG_FILE"

# Alert if threshold exceeded
if (( $(echo "$CPU_USAGE > $THRESHOLD_CPU" | bc -l) )); then
    echo "ALERT: High CPU usage: ${CPU_USAGE}%" | mail -s "Warurejo Resource Alert" admin@warurejo.desa.id
fi

if [ "$MEM_USAGE" -gt "$THRESHOLD_MEM" ]; then
    echo "ALERT: High memory usage: ${MEM_USAGE}%" | mail -s "Warurejo Resource Alert" admin@warurejo.desa.id
fi

if [ "$DISK_USAGE" -gt "$THRESHOLD_DISK" ]; then
    echo "ALERT: High disk usage: ${DISK_USAGE}%" | mail -s "Warurejo Resource Alert" admin@warurejo.desa.id
fi
```

**Add to cron (every 5 minutes):**

```cron
*/5 * * * * /usr/local/bin/monitor-warurejo-resources.sh
```

---

## 🗄️ Database Monitoring

### **1. MySQL Slow Query Log**

Enable in `/etc/mysql/my.cnf`:

```ini
[mysqld]
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-queries.log
long_query_time = 1
log_queries_not_using_indexes = 1
```

**Restart MySQL:**
```bash
sudo systemctl restart mysql
```

**Analyze slow queries:**

```bash
# Install Percona Toolkit
sudo apt install percona-toolkit -y

# Analyze
pt-query-digest /var/log/mysql/slow-queries.log > /tmp/slow-query-report.txt
```

---

### **2. Database Health Check Script**

Create `/usr/local/bin/check-warurejo-db.sh`:

```bash
#!/bin/bash

DB_NAME="warurejo"
DB_USER="warurejo_user"
DB_PASS="your_password"

echo "=== Warurejo Database Health Check - $(date) ==="
echo ""

# Connection count
echo "Active Connections:"
mysql -u "$DB_USER" -p"$DB_PASS" -e "SHOW STATUS LIKE 'Threads_connected';"
echo ""

# Database size
echo "Database Size:"
mysql -u "$DB_USER" -p"$DB_PASS" -e "
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE table_schema = '$DB_NAME'
GROUP BY table_schema;
"
echo ""

# Largest tables
echo "Top 5 Largest Tables:"
mysql -u "$DB_USER" -p"$DB_PASS" -e "
SELECT 
    table_name AS 'Table',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE table_schema = '$DB_NAME'
ORDER BY (data_length + index_length) DESC
LIMIT 5;
"
```

**Add to cron (daily):**

```cron
0 8 * * * /usr/local/bin/check-warurejo-db.sh >> /var/log/warurejo-db-health.log
```

---

## 🔒 Security Monitoring

### **1. Fail2ban Setup**

```bash
# Install
sudo apt install fail2ban -y

# Create Nginx jail
sudo nano /etc/fail2ban/jail.local
```

Add:

```ini
[nginx-limit-req]
enabled = true
filter = nginx-limit-req
port = http,https
logpath = /var/log/nginx/error.log
maxretry = 5
findtime = 60
bantime = 3600

[nginx-noscript]
enabled = true
port = http,https
filter = nginx-noscript
logpath = /var/log/nginx/access.log
maxretry = 6
bantime = 3600

[nginx-badbots]
enabled = true
port = http,https
filter = nginx-badbots
logpath = /var/log/nginx/access.log
maxretry = 2
bantime = 86400

[laravel-auth]
enabled = true
port = http,https
filter = laravel-auth
logpath = /var/www/warurejo/storage/logs/laravel.log
maxretry = 5
bantime = 3600
```

Create filter `/etc/fail2ban/filter.d/laravel-auth.conf`:

```ini
[Definition]
failregex = .*Failed login attempt.*IP: <HOST>
ignoreregex =
```

**Log failed logins in Laravel:**

In `app/Http/Controllers/Auth/LoginController.php`:

```php
protected function sendFailedLoginResponse(Request $request)
{
    Log::warning('Failed login attempt', [
        'email' => $request->email,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
    
    throw ValidationException::withMessages([
        'email' => [trans('auth.failed')],
    ]);
}
```

**Start Fail2ban:**

```bash
sudo systemctl enable fail2ban
sudo systemctl start fail2ban

# Check status
sudo fail2ban-client status
```

---

### **2. Security Audit Script**

Create `/usr/local/bin/security-audit-warurejo.sh`:

```bash
#!/bin/bash

REPORT="/var/log/warurejo-security-$(date +%Y%m%d).txt"

echo "=== Warurejo Security Audit - $(date) ===" > "$REPORT"
echo "" >> "$REPORT"

# Check failed login attempts (last 24 hours)
echo "Failed Login Attempts (Last 24h):" >> "$REPORT"
grep "Failed login" /var/www/warurejo/storage/logs/laravel.log | grep "$(date +%Y-%m-%d)" | wc -l >> "$REPORT"
echo "" >> "$REPORT"

# Check banned IPs
echo "Currently Banned IPs:" >> "$REPORT"
sudo fail2ban-client status laravel-auth >> "$REPORT"
echo "" >> "$REPORT"

# Check file permissions
echo "Checking Critical File Permissions:" >> "$REPORT"
PERMS=$(stat -c '%a' /var/www/warurejo/.env)
if [ "$PERMS" != "600" ]; then
    echo "WARNING: .env permissions are $PERMS (should be 600)" >> "$REPORT"
fi

# Check for suspicious files
echo "Checking for Suspicious PHP Files:" >> "$REPORT"
find /var/www/warurejo/public -name "*.php" ! -name "index.php" >> "$REPORT"

echo "" >> "$REPORT"
echo "Audit completed." >> "$REPORT"

# Send report if issues found
if grep -q "WARNING" "$REPORT"; then
    mail -s "Warurejo Security Alert" admin@warurejo.desa.id < "$REPORT"
fi
```

**Add to cron (daily):**

```cron
0 9 * * * /usr/local/bin/security-audit-warurejo.sh
```

---

## 🔔 Alerting System

### **1. Email Alerts**

Configure in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@warurejo.desa.id
MAIL_FROM_NAME="Warurejo Monitoring"

# Admin email for alerts
ADMIN_EMAIL=admin@warurejo.desa.id
```

---

### **2. WhatsApp Alerts (Using Fonnte)**

Create `app/Services/WhatsAppAlertService.php`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppAlertService
{
    protected $apiUrl = 'https://api.fonnte.com/send';
    protected $token;
    protected $adminNumber;
    
    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->adminNumber = config('services.fonnte.admin_number');
    }
    
    public function sendAlert(string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $this->adminNumber,
                'message' => "🚨 *Warurejo Alert*\n\n" . $message,
                'countryCode' => '62',
            ]);
            
            return $response->successful();
            
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp alert', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
```

Add to `config/services.php`:

```php
'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    'admin_number' => env('FONNTE_ADMIN_NUMBER', '6281234567890'),
],
```

**Usage:**

```php
use App\Services\WhatsAppAlertService;

$whatsapp = new WhatsAppAlertService();
$whatsapp->sendAlert('Database connection failed!');
```

---

### **3. Monitoring Dashboard**

Create simple status page at `resources/views/admin/monitoring.blade.php`:

```blade
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">System Monitoring</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Server Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm mb-2">Server Status</h3>
            <p class="text-3xl font-bold text-green-600">Online</p>
            <p class="text-sm text-gray-600 mt-2">Uptime: {{ $uptime }}</p>
        </div>
        
        <!-- CPU Usage -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm mb-2">CPU Usage</h3>
            <p class="text-3xl font-bold">{{ $cpuUsage }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $cpuUsage }}%"></div>
            </div>
        </div>
        
        <!-- Memory Usage -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm mb-2">Memory Usage</h3>
            <p class="text-3xl font-bold">{{ $memoryUsage }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $memoryUsage }}%"></div>
            </div>
        </div>
        
        <!-- Disk Usage -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm mb-2">Disk Usage</h3>
            <p class="text-3xl font-bold">{{ $diskUsage }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $diskUsage }}%"></div>
            </div>
        </div>
    </div>
    
    <!-- Recent Errors -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Recent Errors (Last 24h)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Time</th>
                        <th class="px-4 py-2 text-left">Level</th>
                        <th class="px-4 py-2 text-left">Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentErrors as $error)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $error->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded 
                                @if($error->level === 'error') bg-red-100 text-red-800
                                @elseif($error->level === 'warning') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $error->level }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ Str::limit($error->message, 80) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.logs') }}" class="btn btn-primary">View Full Logs</a>
            <button onclick="clearCache()" class="btn btn-secondary">Clear Cache</button>
            <a href="/telescope" target="_blank" class="btn btn-secondary">Open Telescope</a>
            <a href="/netdata/" target="_blank" class="btn btn-secondary">Server Metrics</a>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Clear application cache?')) {
        fetch('/admin/cache/clear', { method: 'POST' })
            .then(response => response.json())
            .then(data => alert(data.message));
    }
}

// Auto-refresh every 30 seconds
setTimeout(() => location.reload(), 30000);
</script>
@endsection
```

---

## ✅ Monitoring Checklist

- [x] Uptime monitoring (UptimeRobot/Uptime Kuma)
- [x] Health check endpoint
- [x] Laravel Telescope for APM
- [x] Performance monitoring middleware
- [x] Log rotation configured
- [x] Real-time log analysis
- [x] Error tracking (Sentry)
- [x] Custom error reporting
- [x] Resource monitoring (Netdata)
- [x] Database monitoring
- [x] Security monitoring (Fail2ban)
- [x] Security audit script
- [x] Email alerts configured
- [x] WhatsApp alerts (optional)
- [x] Monitoring dashboard

---

## 📊 Monitoring Summary

**What You'll Monitor:**
- ✅ Website uptime (99.9% target)
- ✅ Response times (<500ms target)
- ✅ Error rates (<0.1% target)
- ✅ Server resources (CPU, RAM, Disk)
- ✅ Database performance
- ✅ Security threats
- ✅ Application exceptions

**Alert Channels:**
- 📧 Email (critical errors)
- 📱 WhatsApp (downtime)
- 🔔 Slack/Discord (optional)
- 📊 Dashboard (real-time)

**Next Steps:**
1. Setup UptimeRobot monitoring
2. Install Netdata for server metrics
3. Configure Sentry error tracking
4. Setup automated alerts
5. Review monitoring dashboard daily

---

**Monitoring Setup Complete! 📊**

For backup procedures, see: [BACKUP_SCRIPTS.md](BACKUP_SCRIPTS.md)
For deployment guide, see: [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
