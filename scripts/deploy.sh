#!/bin/bash

# PRIMEAXIS INVESTMENT PLATFORM
# Manual Deployment Script
# Usage: bash scripts/deploy.sh [ftp-host] [username] [password]

echo "=================================="
echo "PRIMEAXIS DEPLOYMENT SCRIPT"
echo "=================================="
echo ""

FTP_HOST=${1:-"your-ftp-host.com"}
FTP_USER=${2:-"cpanel-user"}
FTP_PASS=${3:-"cpanel-password"}
REMOTE_PATH="/public_html"
LOCAL_PATH="./src"

echo "FTP Host: $FTP_HOST"
echo "FTP User: $FTP_USER"
echo "Remote Path: $REMOTE_PATH"
echo "Local Path: $LOCAL_PATH"
echo ""

# 1. Check if files exist
if [ ! -d "$LOCAL_PATH" ]; then
    echo "Error: Local path $LOCAL_PATH not found"
    exit 1
fi

echo "Creating backup of existing installation..."
echo "✓ Backup instructions: Use cPanel File Manager or SSH"
echo ""

echo "Upload files via FTP/SFTP:"
echo "- Host: $FTP_HOST"
echo "- User: $FTP_USER"
echo "- Local: $LOCAL_PATH"
echo "- Remote: $REMOTE_PATH"
echo ""

echo "After upload, SSH into the server and run:"
echo "chmod -R 755 $REMOTE_PATH"
echo "chmod -R 777 $REMOTE_PATH/assets/uploads"
echo "chmod -R 777 $REMOTE_PATH/logs"
echo ""

echo "Then run migrations and restart cron jobs."
echo ""
