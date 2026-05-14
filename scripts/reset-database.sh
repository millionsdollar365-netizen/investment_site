#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Database Reset Script (CAUTION: Deletes all data)
# Usage: bash scripts/reset-database.sh

echo "=================================="
echo "PRIMEAXIS DATABASE RESET"
echo "=================================="
echo ""
echo "WARNING: This will DELETE all data from the database!"
echo ""

read -p "Are you sure? Type 'reset-all' to confirm: " confirmation

if [ "$confirmation" != "reset-all" ]; then
    echo "Reset cancelled"
    exit 0
fi

# Load environment from .env
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | xargs)
fi

DB_HOST=${DB_HOST:-localhost}
DB_USER=${DB_USER:-root}
DB_PASS=${DB_PASS:-}
DB_NAME=${DB_NAME:-primeaxis}

echo ""
echo "Resetting database: $DB_NAME"
echo ""

# Drop database
if [ -z "$DB_PASS" ]; then
    mysql -h $DB_HOST -u $DB_USER -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
else
    mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
fi

if [ $? -eq 0 ]; then
    echo "✓ Database dropped and recreated"
    
    # Run migrations
    echo ""
    echo "Running migrations..."
    bash scripts/run-migrations.sh
    
else
    echo "✗ Database reset failed"
    exit 1
fi

echo ""
echo "=================================="
echo "DATABASE RESET COMPLETE"
echo "=================================="
