#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Database Backup Script
# Usage: bash scripts/backup-database.sh

echo "=================================="
echo "PRIMEAXIS DATABASE BACKUP"
echo "=================================="
echo ""

# Load environment from .env
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | xargs)
fi

DB_HOST=${DB_HOST:-localhost}
DB_USER=${DB_USER:-root}
DB_PASS=${DB_PASS:-}
DB_NAME=${DB_NAME:-primeaxis}
BACKUP_DIR="backups"

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Generate backup filename with timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/${DB_NAME}_backup_${TIMESTAMP}.sql"

echo "Creating backup of database: $DB_NAME"
echo "Backup file: $BACKUP_FILE"
echo ""

# Create backup using mysqldump
if [ -z "$DB_PASS" ]; then
    mysqldump -h $DB_HOST -u $DB_USER $DB_NAME > "$BACKUP_FILE"
else
    mysqldump -h $DB_HOST -u $DB_USER -p${DB_PASS} $DB_NAME > "$BACKUP_FILE"
fi

if [ $? -eq 0 ]; then
    echo "✓ Backup completed successfully"
    
    # Compress backup
    gzip "$BACKUP_FILE"
    echo "✓ Backup compressed"
    
    # Calculate file size
    SIZE=$(du -h "${BACKUP_FILE}.gz" | cut -f1)
    echo "✓ Backup size: $SIZE"
    
    # List recent backups
    echo ""
    echo "Recent backups:"
    ls -lh $BACKUP_DIR/*.sql.gz | tail -5 | awk '{print $9, "-", $5}'
    
else
    echo "✗ Backup failed"
    exit 1
fi

echo ""
echo "=================================="
echo "BACKUP COMPLETE"
echo "=================================="
