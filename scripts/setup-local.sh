#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Local Development Setup Script
# Usage: bash scripts/setup-local.sh

set -e

echo "=================================="
echo "PRIMEAXIS LOCAL DEVELOPMENT SETUP"
echo "=================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# 1. Check prerequisites
echo -e "${YELLOW}1. Checking prerequisites...${NC}"
command -v php >/dev/null 2>&1 || { echo "PHP is required but not installed. Aborting."; exit 1; }
command -v mysql >/dev/null 2>&1 || { echo "MySQL is required but not installed. Aborting."; exit 1; }
echo -e "${GREEN}✓ PHP and MySQL found${NC}"
echo ""

# 2. Copy config files
echo -e "${YELLOW}2. Creating configuration files...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ Created .env${NC}"
else
    echo "✓ .env already exists"
fi

if [ ! -f src/includes/config.php ]; then
    cp config/config.example.php src/includes/config.php
    echo -e "${GREEN}✓ Created src/includes/config.php${NC}"
else
    echo "✓ src/includes/config.php already exists"
fi
echo ""

# 3. Ask for database credentials
echo -e "${YELLOW}3. Database configuration...${NC}"
read -p "MySQL username (default: root): " db_user
db_user=${db_user:-root}

read -sp "MySQL password: " db_pass
echo ""

read -p "Database name (default: primeaxis_dev): " db_name
db_name=${db_name:-primeaxis_dev}

echo -p "MySQL host (default: localhost): " db_host
db_host=${db_host:-localhost}

# Update .env file
sed -i "s/DB_HOST=.*/DB_HOST=${db_host}/" .env
sed -i "s/DB_USER=.*/DB_USER=${db_user}/" .env
sed -i "s/DB_PASS=.*/DB_PASS=${db_pass}/" .env
sed -i "s/DB_NAME=.*/DB_NAME=${db_name}/" .env

echo -e "${GREEN}✓ Configuration updated${NC}"
echo ""

# 4. Create database
echo -e "${YELLOW}4. Creating database...${NC}"
mysql -h $db_host -u $db_user -p${db_pass} -e "CREATE DATABASE IF NOT EXISTS ${db_name} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
echo -e "${GREEN}✓ Database created${NC}"
echo ""

# 5. Run migrations
echo -e "${YELLOW}5. Running migrations...${NC}"
# This will be implemented when schema.sql is created
echo "⚠ Migrations will be run in next phase"
echo ""

# 6. Create directories
echo -e "${YELLOW}6. Creating required directories...${NC}"
mkdir -p src/assets/uploads/profile_pictures
mkdir -p logs
chmod 755 src/assets/uploads
chmod 777 src/assets/uploads/profile_pictures
chmod 777 logs
echo -e "${GREEN}✓ Directories created${NC}"
echo ""

# 7. Summary
echo "=================================="
echo -e "${GREEN}SETUP COMPLETE!${NC}"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Start PHP dev server:"
echo "   php -S localhost:8000 -t src/"
echo ""
echo "2. Visit: http://localhost:8000"
echo ""
echo "3. Create an admin user:"
echo "   bash scripts/create-admin.sh"
echo ""
