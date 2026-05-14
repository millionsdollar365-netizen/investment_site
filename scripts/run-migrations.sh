#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Database Migration Runner
# Usage: bash scripts/run-migrations.sh

echo "=================================="
echo "PRIMEAXIS DATABASE MIGRATION"
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

echo "Database: $DB_NAME"
echo "Host: $DB_HOST"
echo "User: $DB_USER"
echo ""

# Check if database exists
echo "Checking database connection..."
mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} -e "SELECT 1" > /dev/null 2>&1

if [ $? -ne 0 ]; then
    echo "Error: Cannot connect to database"
    exit 1
fi

echo "✓ Database connection successful"
echo ""

# Run initial schema
echo "Running schema.sql..."
mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} $DB_NAME < database/schema.sql

if [ $? -eq 0 ]; then
    echo "✓ Schema created successfully"
else
    echo "✗ Error creating schema"
    exit 1
fi

echo ""

# Run migrations
if [ -d "database/migrations" ]; then
    echo "Running migrations..."
    
    for migration in database/migrations/*.sql; do
        if [ -f "$migration" ]; then
            echo "  Running: $(basename $migration)"
            mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} $DB_NAME < "$migration"
        fi
    done
    
    echo "✓ Migrations completed"
fi

echo ""

# Run seeders
if [ -d "database/seeders" ]; then
    echo "Running seeders..."
    
    for seeder in database/seeders/*.sql; do
        if [ -f "$seeder" ]; then
            echo "  Running: $(basename $seeder)"
            mysql -h $DB_HOST -u $DB_USER -p${DB_PASS} $DB_NAME < "$seeder"
        fi
    done
    
    echo "✓ Seeders completed"
fi

echo ""
echo "=================================="
echo "✓ MIGRATION COMPLETE"
echo "=================================="
