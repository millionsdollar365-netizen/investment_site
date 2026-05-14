#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Create First Admin User Script
# Usage: bash scripts/create-admin.sh

echo "=================================="
echo "CREATE ADMIN USER"
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

read -p "Enter admin username: " admin_username
read -p "Enter admin email: " admin_email
read -sp "Enter admin password: " admin_password
echo ""

# Hash password using PHP
php -r "
require_once './src/includes/config.php';
require_once './src/includes/security.php';

\$username = '$admin_username';
\$email = '$admin_email';
\$password = '$admin_password';
\$hashed = Security::hashPassword(\$password);

// Insert admin user
\$conn = new mysqli('$DB_HOST', '$DB_USER', '$DB_PASS', '$DB_NAME');
if (\$conn->connect_error) {
    die('Connection failed: ' . \$conn->connect_error);
}

\$stmt = \$conn->prepare('INSERT INTO admin_users (username, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)');
\$role = 'super_admin';
\$status = 'active';
\$stmt->bind_param('sssss', \$username, \$email, \$hashed, \$role, \$status);

if (\$stmt->execute()) {
    echo \"✓ Admin user created successfully\n\";
    echo \"  Username: \$username\n\";
    echo \"  Email: \$email\n\";
    echo \"  Role: \$role\n\";
} else {
    echo \"✗ Error creating admin user: \" . \$stmt->error . \"\n\";
}

\$conn->close();
"

echo ""
echo "=================================="
echo "Admin user created successfully!"
echo "=================================="
