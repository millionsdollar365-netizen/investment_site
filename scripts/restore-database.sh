#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Database Restore Script
# Usage: bash scripts/restore-database.sh [backup-file]

if [ -z "$1" ]; then
    echo "Usage: bash scripts/restore-database.sh [backup-file.sql.gz]"
    exit 1
fi

BACKUP_FILE=$1

if [ ! -f "$BACKUP_FILE" ]; then
    echo "Error: Backup file not found: $BACKUP_FILE"
    exit 1
fi

echo "=================================="
echo "PRIMEAXIS DATABASE RESTORE"
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

echo "WARNING: This will overwrite the current database!"
read -p "Are you sure? (yes/no): " confirmation

if [ "$confirmation" != "yes" ]; then
    echo "Restore cancelled"
    exit 0
fi

echo ""
echo "Restoring database from: $BACKUP_FILE"
echo ""

# Decompress if needed
if [[ $BACKUP_FILE == *.gz ]]; then
    TEMP_FILE="${BACKUP_FILE%.gz}"
    gunzip -c "$BACKUP_FILE" > "$TEMP_FILE"
    RESTORE_FILE=$TEMP_FILE
else
    RESTORE_FILE=$BACKUP_FILE
fi

# Restore backup
if [ -z "$DB_PASS" ]; then
    mysql -h $DB_HOST -u $DB_USER $DB_NAME < "$RESTORE_FILE"
else
    mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} $DB_NAME < "$RESTORE_FILE"
fi

# Clean up temp file if we decompressed
if [[ $BACKUP_FILE == *.gz ]]; then
    rm "$RESTORE_FILE"
fi

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ Database restore completed successfully"
else
    echo ""
    echo "✗ Database restore failed"
    exit 1
fi

echo ""
echo "=================================="
echo "RESTORE COMPLETE"
echo "=================================="
