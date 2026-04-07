# 💾 Automated Backup Scripts - Website Desa Warurejo

**Version:** 1.0  
**Last Updated:** 28 November 2025

---

## 📋 Table of Contents

1. [Database Backup Scripts](#database-backup-scripts)
2. [Files Backup Scripts](#files-backup-scripts)
3. [Full System Backup](#full-system-backup)
4. [Automated Backup with Cron](#automated-backup-with-cron)
5. [Backup Restoration](#backup-restoration)
6. [Backup Verification](#backup-verification)
7. [Cloud Backup Integration](#cloud-backup-integration)

---

## 🗄️ Database Backup Scripts

### **Daily Database Backup Script**

Create file: `/usr/local/bin/backup-warurejo-db.sh`

```bash
#!/bin/bash

#######################################
# Warurejo Database Backup Script
# Author: Development Team
# Version: 1.0
#######################################

# Configuration
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/warurejo/database"
DB_NAME="warurejo"
DB_USER="warurejo_user"
DB_PASSWORD="YOUR_DB_PASSWORD"
DB_HOST="localhost"

# Retention (days)
RETENTION_DAYS=30

# Log file
LOG_FILE="/var/log/warurejo-backup.log"

# Email notification (optional)
EMAIL_TO="admin@warurejo.desa.id"
SEND_EMAIL=false

#######################################
# Functions
#######################################

log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

send_notification() {
    if [ "$SEND_EMAIL" = true ]; then
        echo "$1" | mail -s "Warurejo Backup Notification" "$EMAIL_TO"
    fi
}

#######################################
# Main Backup Process
#######################################

log_message "=== Database Backup Started ==="

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Backup filename
BACKUP_FILE="$BACKUP_DIR/warurejo_db_$DATE.sql.gz"

# Perform backup
log_message "Backing up database: $DB_NAME"

if mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    "$DB_NAME" | gzip > "$BACKUP_FILE"; then
    
    # Get file size
    FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    
    log_message "Backup successful: $BACKUP_FILE ($FILE_SIZE)"
    
    # Calculate checksum
    CHECKSUM=$(md5sum "$BACKUP_FILE" | awk '{print $1}')
    echo "$CHECKSUM  $BACKUP_FILE" > "$BACKUP_FILE.md5"
    log_message "Checksum: $CHECKSUM"
    
    # Test backup integrity
    if gunzip -t "$BACKUP_FILE" 2>/dev/null; then
        log_message "Backup integrity verified"
        STATUS="SUCCESS"
    else
        log_message "ERROR: Backup integrity check failed"
        STATUS="FAILED"
        send_notification "Database backup failed integrity check!"
    fi
    
else
    log_message "ERROR: Backup failed"
    STATUS="FAILED"
    send_notification "Database backup failed!"
fi

# Cleanup old backups
log_message "Cleaning up backups older than $RETENTION_DAYS days"
find "$BACKUP_DIR" -name "warurejo_db_*.sql.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "warurejo_db_*.sql.gz.md5" -mtime +$RETENTION_DAYS -delete

DELETED_COUNT=$(find "$BACKUP_DIR" -name "warurejo_db_*.sql.gz" -mtime +$RETENTION_DAYS | wc -l)
log_message "Deleted $DELETED_COUNT old backup(s)"

# Count remaining backups
BACKUP_COUNT=$(find "$BACKUP_DIR" -name "warurejo_db_*.sql.gz" | wc -l)
log_message "Total backups: $BACKUP_COUNT"

log_message "=== Database Backup Completed: $STATUS ==="
echo "" >> "$LOG_FILE"

# Exit with appropriate code
if [ "$STATUS" = "SUCCESS" ]; then
    exit 0
else
    exit 1
fi
```

**Make executable:**

```bash
sudo chmod +x /usr/local/bin/backup-warurejo-db.sh
```

**Test run:**

```bash
sudo /usr/local/bin/backup-warurejo-db.sh
```

---

## 📁 Files Backup Scripts

### **Weekly Files Backup Script**

Create file: `/usr/local/bin/backup-warurejo-files.sh`

```bash
#!/bin/bash

#######################################
# Warurejo Files Backup Script
# Author: Development Team
# Version: 1.0
#######################################

# Configuration
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/warurejo/files"
APP_DIR="/var/www/warurejo"

# What to backup
BACKUP_ITEMS=(
    "storage/app/public"
    ".env"
)

# Retention (weeks)
RETENTION_WEEKS=8
RETENTION_DAYS=$((RETENTION_WEEKS * 7))

# Log file
LOG_FILE="/var/log/warurejo-backup.log"

# Email notification (optional)
EMAIL_TO="admin@warurejo.desa.id"
SEND_EMAIL=false

#######################################
# Functions
#######################################

log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

send_notification() {
    if [ "$SEND_EMAIL" = true ]; then
        echo "$1" | mail -s "Warurejo Backup Notification" "$EMAIL_TO"
    fi
}

#######################################
# Main Backup Process
#######################################

log_message "=== Files Backup Started ==="

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Backup filename
BACKUP_FILE="$BACKUP_DIR/warurejo_files_$DATE.tar.gz"

# Navigate to app directory
cd "$APP_DIR" || exit 1

# Create backup
log_message "Creating files backup..."

if tar -czf "$BACKUP_FILE" \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    "${BACKUP_ITEMS[@]}" 2>&1 | tee -a "$LOG_FILE"; then
    
    # Get file size
    FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    
    log_message "Backup successful: $BACKUP_FILE ($FILE_SIZE)"
    
    # Calculate checksum
    CHECKSUM=$(md5sum "$BACKUP_FILE" | awk '{print $1}')
    echo "$CHECKSUM  $BACKUP_FILE" > "$BACKUP_FILE.md5"
    log_message "Checksum: $CHECKSUM"
    
    # Test backup integrity
    if tar -tzf "$BACKUP_FILE" > /dev/null 2>&1; then
        log_message "Backup integrity verified"
        STATUS="SUCCESS"
    else
        log_message "ERROR: Backup integrity check failed"
        STATUS="FAILED"
        send_notification "Files backup failed integrity check!"
    fi
    
else
    log_message "ERROR: Backup failed"
    STATUS="FAILED"
    send_notification "Files backup failed!"
fi

# Cleanup old backups
log_message "Cleaning up backups older than $RETENTION_WEEKS weeks ($RETENTION_DAYS days)"
find "$BACKUP_DIR" -name "warurejo_files_*.tar.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "warurejo_files_*.tar.gz.md5" -mtime +$RETENTION_DAYS -delete

DELETED_COUNT=$(find "$BACKUP_DIR" -name "warurejo_files_*.tar.gz" -mtime +$RETENTION_DAYS | wc -l)
log_message "Deleted $DELETED_COUNT old backup(s)"

# Count remaining backups
BACKUP_COUNT=$(find "$BACKUP_DIR" -name "warurejo_files_*.tar.gz" | wc -l)
log_message "Total backups: $BACKUP_COUNT"

log_message "=== Files Backup Completed: $STATUS ==="
echo "" >> "$LOG_FILE"

# Exit with appropriate code
if [ "$STATUS" = "SUCCESS" ]; then
    exit 0
else
    exit 1
fi
```

**Make executable:**

```bash
sudo chmod +x /usr/local/bin/backup-warurejo-files.sh
```

---

## 🔄 Full System Backup

### **Complete Backup Script**

Create file: `/usr/local/bin/backup-warurejo-full.sh`

```bash
#!/bin/bash

#######################################
# Warurejo Full System Backup Script
# Author: Development Team
# Version: 1.0
#######################################

# Configuration
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_ROOT="/backup/warurejo/full"
BACKUP_DIR="$BACKUP_ROOT/$DATE"
LOG_FILE="/var/log/warurejo-backup.log"

#######################################
# Functions
#######################################

log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

#######################################
# Main Process
#######################################

log_message "=== Full System Backup Started ==="

# Create backup directory
mkdir -p "$BACKUP_DIR"

# 1. Database Backup
log_message "Step 1: Database backup"
/usr/local/bin/backup-warurejo-db.sh
if [ $? -eq 0 ]; then
    cp /backup/warurejo/database/warurejo_db_*.sql.gz "$BACKUP_DIR/" 2>/dev/null | tail -1
    log_message "Database backup copied"
else
    log_message "ERROR: Database backup failed"
fi

# 2. Files Backup
log_message "Step 2: Files backup"
/usr/local/bin/backup-warurejo-files.sh
if [ $? -eq 0 ]; then
    cp /backup/warurejo/files/warurejo_files_*.tar.gz "$BACKUP_DIR/" 2>/dev/null | tail -1
    log_message "Files backup copied"
else
    log_message "ERROR: Files backup failed"
fi

# 3. Configuration Backup
log_message "Step 3: Configuration backup"
cp /etc/nginx/sites-available/warurejo "$BACKUP_DIR/nginx.conf" 2>/dev/null
cp /etc/php/8.2/fpm/php.ini "$BACKUP_DIR/php.ini" 2>/dev/null
log_message "Configuration files backed up"

# 4. Create manifest
log_message "Step 4: Creating manifest"
cat > "$BACKUP_DIR/MANIFEST.txt" << EOF
Warurejo Full System Backup
============================
Date: $(date '+%Y-%m-%d %H:%M:%S')
Backup ID: $DATE

Contents:
---------
EOF

ls -lh "$BACKUP_DIR" >> "$BACKUP_DIR/MANIFEST.txt"

# 5. Compress everything
log_message "Step 5: Compressing backup"
cd "$BACKUP_ROOT"
tar -czf "warurejo_full_$DATE.tar.gz" "$DATE/"

if [ $? -eq 0 ]; then
    FILE_SIZE=$(du -h "warurejo_full_$DATE.tar.gz" | cut -f1)
    log_message "Full backup created: warurejo_full_$DATE.tar.gz ($FILE_SIZE)"
    
    # Remove temporary directory
    rm -rf "$DATE/"
    
    STATUS="SUCCESS"
else
    log_message "ERROR: Compression failed"
    STATUS="FAILED"
fi

log_message "=== Full System Backup Completed: $STATUS ==="

exit 0
```

**Make executable:**

```bash
sudo chmod +x /usr/local/bin/backup-warurejo-full.sh
```

---

## ⏰ Automated Backup with Cron

### **Setup Cron Jobs**

```bash
sudo crontab -e
```

**Add these lines:**

```cron
# Warurejo Automated Backups

# Daily database backup at 2 AM
0 2 * * * /usr/local/bin/backup-warurejo-db.sh >> /var/log/warurejo-backup.log 2>&1

# Weekly files backup on Sunday at 3 AM
0 3 * * 0 /usr/local/bin/backup-warurejo-files.sh >> /var/log/warurejo-backup.log 2>&1

# Monthly full backup on 1st day at 4 AM
0 4 1 * * /usr/local/bin/backup-warurejo-full.sh >> /var/log/warurejo-backup.log 2>&1

# Weekly log cleanup (keep 30 days)
0 5 * * 0 find /var/log -name "warurejo-backup.log.*" -mtime +30 -delete
```

**Verify cron jobs:**

```bash
sudo crontab -l
```

---

## 🔧 Backup Restoration

### **Restore Database**

```bash
#!/bin/bash

# Find latest backup
LATEST_DB_BACKUP=$(ls -t /backup/warurejo/database/warurejo_db_*.sql.gz | head -1)

echo "Restoring database from: $LATEST_DB_BACKUP"

# Verify checksum
if md5sum -c "$LATEST_DB_BACKUP.md5"; then
    echo "Checksum verified"
    
    # Restore database
    gunzip < "$LATEST_DB_BACKUP" | mysql -u warurejo_user -p warurejo
    
    if [ $? -eq 0 ]; then
        echo "Database restored successfully"
    else
        echo "ERROR: Database restoration failed"
        exit 1
    fi
else
    echo "ERROR: Checksum verification failed"
    exit 1
fi
```

### **Restore Files**

```bash
#!/bin/bash

# Find latest backup
LATEST_FILES_BACKUP=$(ls -t /backup/warurejo/files/warurejo_files_*.tar.gz | head -1)

echo "Restoring files from: $LATEST_FILES_BACKUP"

# Verify checksum
if md5sum -c "$LATEST_FILES_BACKUP.md5"; then
    echo "Checksum verified"
    
    # Restore files
    cd /var/www/warurejo
    tar -xzf "$LATEST_FILES_BACKUP"
    
    if [ $? -eq 0 ]; then
        echo "Files restored successfully"
        
        # Fix permissions
        sudo chown -R www-data:www-data storage
        sudo chmod -R 775 storage
        
        # Recreate storage link
        php artisan storage:link
        
    else
        echo "ERROR: Files restoration failed"
        exit 1
    fi
else
    echo "ERROR: Checksum verification failed"
    exit 1
fi
```

---

## ✅ Backup Verification

### **Verify Backup Script**

Create file: `/usr/local/bin/verify-warurejo-backup.sh`

```bash
#!/bin/bash

#######################################
# Warurejo Backup Verification Script
#######################################

LOG_FILE="/var/log/warurejo-backup-verify.log"

log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

log_message "=== Backup Verification Started ==="

# Check database backups
DB_BACKUP_DIR="/backup/warurejo/database"
DB_BACKUP_COUNT=$(find "$DB_BACKUP_DIR" -name "warurejo_db_*.sql.gz" -mtime -1 | wc -l)

if [ $DB_BACKUP_COUNT -gt 0 ]; then
    log_message "✓ Database backup exists (last 24 hours)"
    
    # Verify latest backup
    LATEST_DB=$(ls -t "$DB_BACKUP_DIR"/warurejo_db_*.sql.gz | head -1)
    
    if md5sum -c "$LATEST_DB.md5" &> /dev/null; then
        log_message "✓ Database backup checksum verified"
    else
        log_message "✗ Database backup checksum FAILED"
    fi
    
    if gunzip -t "$LATEST_DB" 2>/dev/null; then
        log_message "✓ Database backup integrity verified"
    else
        log_message "✗ Database backup integrity FAILED"
    fi
else
    log_message "✗ No recent database backup found!"
fi

# Check files backups
FILES_BACKUP_DIR="/backup/warurejo/files"
FILES_BACKUP_COUNT=$(find "$FILES_BACKUP_DIR" -name "warurejo_files_*.tar.gz" -mtime -7 | wc -l)

if [ $FILES_BACKUP_COUNT -gt 0 ]; then
    log_message "✓ Files backup exists (last 7 days)"
    
    # Verify latest backup
    LATEST_FILES=$(ls -t "$FILES_BACKUP_DIR"/warurejo_files_*.tar.gz | head -1)
    
    if md5sum -c "$LATEST_FILES.md5" &> /dev/null; then
        log_message "✓ Files backup checksum verified"
    else
        log_message "✗ Files backup checksum FAILED"
    fi
    
    if tar -tzf "$LATEST_FILES" > /dev/null 2>&1; then
        log_message "✓ Files backup integrity verified"
    else
        log_message "✗ Files backup integrity FAILED"
    fi
else
    log_message "✗ No recent files backup found!"
fi

# Check backup disk space
BACKUP_DISK_USAGE=$(df -h /backup | tail -1 | awk '{print $5}' | sed 's/%//')

if [ $BACKUP_DISK_USAGE -lt 80 ]; then
    log_message "✓ Backup disk usage OK ($BACKUP_DISK_USAGE%)"
else
    log_message "⚠ Backup disk usage high: $BACKUP_DISK_USAGE%"
fi

log_message "=== Backup Verification Completed ==="
```

**Make executable and add to cron:**

```bash
sudo chmod +x /usr/local/bin/verify-warurejo-backup.sh

# Add to cron (daily at 6 AM)
sudo crontab -e
```

```cron
0 6 * * * /usr/local/bin/verify-warurejo-backup.sh
```

---

## ☁️ Cloud Backup Integration

### **Google Drive Backup (Using rclone)**

#### **Install rclone:**

```bash
curl https://rclone.org/install.sh | sudo bash
```

#### **Configure rclone:**

```bash
rclone config
```

Follow prompts to setup Google Drive.

#### **Cloud Sync Script:**

Create file: `/usr/local/bin/backup-warurejo-cloud.sh`

```bash
#!/bin/bash

# Configuration
REMOTE_NAME="gdrive"  # Name from rclone config
REMOTE_PATH="Backups/Warurejo"
LOCAL_DB_DIR="/backup/warurejo/database"
LOCAL_FILES_DIR="/backup/warurejo/files"

# Sync database backups (last 7 days)
find "$LOCAL_DB_DIR" -name "warurejo_db_*.sql.gz" -mtime -7 -exec \
    rclone copy {} "$REMOTE_NAME:$REMOTE_PATH/database/" \;

# Sync files backups (last 30 days)
find "$LOCAL_FILES_DIR" -name "warurejo_files_*.tar.gz" -mtime -30 -exec \
    rclone copy {} "$REMOTE_NAME:$REMOTE_PATH/files/" \;

echo "Cloud backup completed: $(date)"
```

**Add to cron (daily at 5 AM):**

```cron
0 5 * * * /usr/local/bin/backup-warurejo-cloud.sh >> /var/log/warurejo-cloud-backup.log 2>&1
```

---

## 📊 Backup Status Dashboard

Create simple status page:

```bash
#!/bin/bash

echo "==========================================="
echo "  Warurejo Backup Status"
echo "==========================================="
echo ""

# Database backups
echo "📊 Database Backups:"
echo "  Total: $(find /backup/warurejo/database -name "warurejo_db_*.sql.gz" | wc -l)"
echo "  Latest: $(ls -t /backup/warurejo/database/warurejo_db_*.sql.gz 2>/dev/null | head -1 | xargs basename 2>/dev/null || echo 'None')"
echo ""

# Files backups
echo "📁 Files Backups:"
echo "  Total: $(find /backup/warurejo/files -name "warurejo_files_*.tar.gz" | wc -l)"
echo "  Latest: $(ls -t /backup/warurejo/files/warurejo_files_*.tar.gz 2>/dev/null | head -1 | xargs basename 2>/dev/null || echo 'None')"
echo ""

# Disk usage
echo "💾 Backup Storage:"
df -h /backup | tail -1 | awk '{print "  Used: "$3" / "$2" ("$5")"}'
echo ""

echo "==========================================="
```

---

## ✅ Backup Checklist

- [x] Database backup script created
- [x] Files backup script created
- [x] Full backup script created
- [x] Cron jobs configured
- [x] Backup verification script
- [x] Restoration procedures documented
- [x] Retention policy implemented
- [x] Checksum verification
- [x] Integrity testing
- [x] Cloud backup (optional)
- [x] Status monitoring

---

**Backup Scripts Complete! 💾**

**Next Steps:**
1. Test all scripts manually
2. Verify cron jobs running
3. Test restoration procedure
4. Monitor backup logs
5. Setup cloud backup (optional)

**For monitoring, see:** [MONITORING_SETUP.md](MONITORING_SETUP.md)
