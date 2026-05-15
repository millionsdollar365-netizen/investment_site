# PRIMEAXIS INVESTMENT PLATFORM — DEPLOYMENT & BUILD PLAN

**Version:** 1.1  
**Date:** May 15, 2026  
**Project:** Primeaxis Investment Platform  
**Status:** 8 of 14 Phases Complete (57%) — Phases 1-8 Deployed

---

## EXECUTIVE SUMMARY

This document outlines the complete deployment and build plan for the Primeaxis Investment platform. It covers:
- Git repository structure (✅ Complete)
- Development environment setup (✅ Complete)
- Local testing workflow (✅ Complete)
- Automated deployment to cPanel (⏳ In Progress)
- Manual deployment fallback (⏳ In Progress)

**Current Status:** 8 phases deployed:
- ✅ Repository & initialization (Phase 1)
- ✅ Local development setup (Phase 2)
- ✅ Database & migrations (Phase 3)
- ✅ Authentication API (Phases 4-5)
- ✅ All API endpoints (Phase 6)
- ✅ Frontend pages (Phase 7)
- ✅ Static assets (Phase 8)

**Deployment Methods:**
- ⏳ Automated: GitHub Actions → cPanel (FTP/SSH) — Phase 9
- ⏳ Manual: CLI script or direct SSH — Phase 10

---

## PHASE 1: REPOSITORY STRUCTURE & INITIALIZATION

### 1.1 Git Repo Setup

```bash
# Initialize repo
git init primeaxis-investment
cd primeaxis-investment

# Create main branches
git checkout -b main
git checkout -b develop
git checkout -b staging
```

### 1.2 Directory Structure

```text
primeaxis-investment/
│
├── .github/
│   └── workflows/
│       ├── deploy-to-cpanel.yml       ← Auto-deploy on push to main
│       └── test-suite.yml             ← Run tests on push
│
├── .gitignore                          ← Ignore: config, uploads, logs
├── README.md
├── DEPLOYMENT.md                       ← Deployment instructions
├── DATABASE_SETUP.sql                  ← Full DB schema
│
├── scripts/
│   ├── deploy.sh                       ← Manual deploy script
│   ├── setup-local.sh                  ← Local dev setup
│   ├── run-migrations.sh               ← Database migrations
│   ├── create-admin.sh                 ← Create first admin user
│   ├── backup-database.sh              ← Database backup
│   └── restore-database.sh             ← Database restore
│
├── config/
│   ├── config.example.php              ← Template config (commit to repo)
│   ├── env.example                     ← .env template
│   └── htaccess.example                ← .htaccess template
│
├── database/
│   ├── schema.sql                      ← Complete schema
│   ├── migrations/
│   │   ├── 001_init_schema.sql
│   │   ├── 002_add_admin_roles.sql
│   │   ├── 003_add_audit_logs.sql
│   │   └── ...migration_files
│   └── seeders/
│       ├── plans.sql                   ← Default investment plans
│       ├── admin-user.sql              ← Default admin
│       └── settings.sql                ← Default platform settings
│
├── src/
│   ├── index.php                       ← Public entry
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── forgot-password.php
│   ├── reset-password.php
│   │
│   ├── dashboard/
│   │   ├── index.php
│   │   ├── investments.php
│   │   ├── deposits.php
│   │   ├── withdrawals.php
│   │   ├── referrals.php
│   │   ├── transactions.php
│   │   ├── profile.php
│   │   └── settings.php
│   │
│   ├── admin/
│   │   ├── index.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── users.php
│   │   ├── deposits.php
│   │   ├── withdrawals.php
│   │   ├── investments.php
│   │   ├── transactions.php
│   │   ├── plans.php
│   │   ├── referrals.php
│   │   ├── settings.php
│   │   ├── logs.php
│   │   └── adjustments.php
│   │
│   ├── api/
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   ├── logout.php
│   │   │   ├── check-session.php
│   │   │   ├── forgot-password.php
│   │   │   └── reset-password.php
│   │   │
│   │   ├── user/
│   │   │   ├── get-dashboard.php
│   │   │   ├── get-profile.php
│   │   │   ├── update-profile.php
│   │   │   ├── upload-picture.php
│   │   │   ├── change-password.php
│   │   │   └── transfer-to-balance.php
│   │   │
│   │   ├── investments/
│   │   │   ├── create.php
│   │   │   └── list.php
│   │   │
│   │   ├── deposits/
│   │   │   ├── create.php
│   │   │   └── list.php
│   │   │
│   │   ├── withdrawals/
│   │   │   ├── create.php
│   │   │   └── list.php
│   │   │
│   │   ├── referrals/
│   │   │   └── get-referrals.php
│   │   │
│   │   ├── admin/
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   ├── check-session.php
│   │   │   ├── get-dashboard.php
│   │   │   ├── get-users.php
│   │   │   ├── get-user.php
│   │   │   ├── suspend-user.php
│   │   │   ├── activate-user.php
│   │   │   ├── adjust-balance.php
│   │   │   ├── get-deposits.php
│   │   │   ├── approve-deposit.php
│   │   │   ├── reject-deposit.php
│   │   │   ├── get-withdrawals.php
│   │   │   ├── approve-withdrawal.php
│   │   │   ├── reject-withdrawal.php
│   │   │   ├── get-investments.php
│   │   │   ├── force-complete-investment.php
│   │   │   ├── get-transactions.php
│   │   │   ├── get-plans.php
│   │   │   ├── create-plan.php
│   │   │   ├── edit-plan.php
│   │   │   ├── disable-plan.php
│   │   │   ├── get-settings.php
│   │   │   └── update-settings.php
│   │   │
│   │   └── cron/
│   │       ├── process-profits.php
│   │       ├── complete-investments.php
│   │       └── cleanup.php
│   │
│   ├── includes/
│   │   ├── config.php
│   │   ├── db.php
│   │   ├── session.php
│   │   ├── admin-session.php
│   │   ├── auth.php
│   │   ├── functions.php
│   │   ├── security.php
│   │   ├── mail.php
│   │   ├── validation.php
│   │   └── response.php
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   └── app.css
│   │   ├── js/
│   │   │   ├── app.js
│   │   │   ├── dashboard.js
│   │   │   └── admin.js
│   │   └── images/
│   │
│   └── cron/
│       ├── process-profits.php
│       ├── complete-investments.php
│       └── cleanup.php
│
├── tests/
│   ├── unit/
│   │   ├── AuthTest.php
│   │   ├── ValidationTest.php
│   │   └── FinanceTest.php
│   ├── integration/
│   │   ├── InvestmentFlowTest.php
│   │   ├── DepositFlowTest.php
│   │   └── WithdrawalFlowTest.php
│   └── bootstrap.php
│
├── docker/
│   ├── Dockerfile                      ← For containerized deployment
│   ├── docker-compose.yml
│   └── nginx.conf
│
├── docs/
│   ├── API.md                          ← API endpoint documentation
│   ├── DATABASE.md                     ← Database schema docs
│   ├── SECURITY.md                     ← Security guidelines
│   ├── DEPLOYMENT.md                   ← Deployment guide
│   └── TROUBLESHOOTING.md              ← Common issues
│
└── .env.example                        ← Environment template

```

### 1.3 .gitignore File

```
# Environment
.env
config/config.php
includes/config.php

# Uploads & Media
/src/assets/uploads/
/src/assets/images/user-uploads/

# Logs
logs/
*.log

# Cache
cache/
*.tmp

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Database backups
backups/*.sql
backups/*.zip

# Temp files
tmp/
temp/

# Node (if using build tools)
node_modules/
package-lock.json
```

---

## PHASE 2: LOCAL DEVELOPMENT SETUP

### 2.1 Prerequisites

- PHP 8.0+
- MySQL 8.0+
- Git
- Composer (optional, for automated tools)
- Docker (optional)

### 2.2 Local Setup Script (`scripts/setup-local.sh`)

```bash
#!/bin/bash

echo "=== PRIMEAXIS LOCAL SETUP ==="

# 1. Clone repo (if not already)
# git clone <repo-url>
# cd primeaxis-investment

# 2. Copy config files
cp config/config.example.php includes/config.php
cp .env.example .env

# 3. Edit .env with local settings
echo "Please edit .env with your local database credentials"
read -p "Press ENTER when done..."

# 4. Create MySQL database
mysql -u root -p -e "CREATE DATABASE primeaxis_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Run migrations
bash scripts/run-migrations.sh

# 6. Seed default data
mysql -u root -p primeaxis_dev < database/seeders/plans.sql
mysql -u root -p primeaxis_dev < database/seeders/settings.sql

# 7. Create uploads directory
mkdir -p src/assets/uploads
mkdir -p src/assets/uploads/profile_pictures
chmod 755 src/assets/uploads
chmod 755 src/assets/uploads/profile_pictures

# 8. Create logs directory
mkdir -p logs
chmod 755 logs

echo "=== LOCAL SETUP COMPLETE ==="
echo "Start PHP server with: php -S localhost:8000 -t src/"
```

### 2.3 Local Development Workflow

```bash
# Start PHP dev server
php -S localhost:8000 -t src/

# Or with Docker
docker-compose up

# Run tests
php -r "include 'tests/bootstrap.php'; phpunit tests/"

# Create admin user
bash scripts/create-admin.sh
```

---

## PHASE 3: DATABASE SETUP

### 3.1 Database Schema (`database/schema.sql`)

See `DATABASE_SETUP.sql` for complete schema including:
- `users` table
- `admin_users` table
- `investments` table
- `deposits` table
- `withdrawals` table
- `transactions` table
- `referrals` table
- `plans` table
- `settings` table
- `audit_logs` table

### 3.2 Migration System

```
database/migrations/
├── 001_init_schema.sql      ← Initial schema
├── 002_add_admin_roles.sql
├── 003_add_audit_logs.sql
└── ...
```

**Run migrations:**
```bash
bash scripts/run-migrations.sh
```

### 3.3 Seeding

```
database/seeders/
├── plans.sql                ← Investment plans
├── settings.sql             ← Platform settings
└── admin-user.sql           ← First admin
```

---

## PHASE 4: CORE BACKEND IMPLEMENTATION

### 4.1 Core Includes (Priority Order)

**1. `includes/config.php`** — Environment configuration
```php
<?php
// Database
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'primeaxis');

// Site
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost:8000');
define('SITE_NAME', 'Primeaxis Investment');

// Mail
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
// ... more settings
?>
```

**2. `includes/db.php`** — PDO Singleton
```php
<?php
class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$instance;
    }
}
?>
```

**3. `includes/session.php`** — User session handler
**4. `includes/admin-session.php`** — Admin session handler
**5. `includes/auth.php`** — Authentication helpers
**6. `includes/validation.php`** — Input validation
**7. `includes/security.php`** — Sanitization & CSRF
**8. `includes/response.php`** — JSON response helper
**9. `includes/mail.php`** — Email wrapper (PHPMailer)
**10. `includes/functions.php`** — General utilities

---

## PHASE 5: AUTHENTICATION SYSTEM

### 5.1 Registration Flow
- `api/auth/register.php` — Validate & create user
- `api/auth/check-session.php` — Check if logged in
- `register.php` — Frontend shell

### 5.2 Login Flow
- `api/auth/login.php` — Authenticate & create session
- `login.php` — Frontend shell

### 5.3 Password Reset Flow
- `api/auth/forgot-password.php` — Send reset email
- `api/auth/reset-password.php` — Validate token & reset
- `forgot-password.php`, `reset-password.php` — Frontend shells

---

## PHASE 6: API ENDPOINTS IMPLEMENTATION

### 6.1 User Endpoints
- `GET api/user/get-dashboard.php` — User dashboard data
- `GET api/user/get-profile.php` — User profile
- `POST api/user/update-profile.php` — Update profile
- `POST api/user/upload-picture.php` — Profile picture
- `POST api/user/change-password.php` — Change password

### 6.2 Investment Endpoints
- `POST api/investments/create.php` — Create investment
- `GET api/investments/list.php` — List user investments

### 6.3 Deposit Endpoints
- `POST api/deposits/create.php` — Request deposit
- `GET api/deposits/list.php` — List deposits

### 6.4 Withdrawal Endpoints
- `POST api/withdrawals/create.php` — Request withdrawal
- `GET api/withdrawals/list.php` — List withdrawals

### 6.5 Admin Endpoints
- `POST api/admin/login.php` — Admin login
- `GET api/admin/get-dashboard.php` — Admin dashboard
- `GET api/admin/get-users.php` — List users
- `POST api/admin/approve-deposit.php` — Approve deposit
- `POST api/admin/approve-withdrawal.php` — Approve withdrawal
- `POST api/admin/adjust-balance.php` — Adjust user balance
- `POST api/admin/create-plan.php` — Create investment plan
- `GET api/admin/get-settings.php` — Get platform settings
- `POST api/admin/update-settings.php` — Update settings

### 6.6 Cron Endpoints
- `api/cron/process-profits.php` — Daily ROI processing
- `api/cron/complete-investments.php` — Complete matured investments
- `api/cron/cleanup.php` — Cleanup expired tokens, compress logs

---

## PHASE 7: FRONTEND PAGES

### 7.1 Public Pages
- `index.php` — Landing/home
- `login.php` — User login
- `register.php` — User registration
- `forgot-password.php` — Forgot password form
- `reset-password.php` — Reset password form

### 7.2 User Dashboard Pages
- `dashboard/index.php` — Main dashboard
- `dashboard/investments.php` — Investments page
- `dashboard/deposits.php` — Deposits page
- `dashboard/withdrawals.php` — Withdrawals page
- `dashboard/referrals.php` — Referrals page
- `dashboard/transactions.php` — Transaction history
- `dashboard/profile.php` — User profile
- `dashboard/settings.php` — Settings

### 7.3 Admin Pages
- `admin/index.php` — Admin dashboard
- `admin/login.php` — Admin login
- `admin/users.php` — User management
- `admin/deposits.php` — Deposit approvals
- `admin/withdrawals.php` — Withdrawal approvals
- `admin/investments.php` — Investment management
- `admin/plans.php` — Investment plans
- `admin/settings.php` — Platform settings
- `admin/logs.php` — Audit logs

---

## PHASE 8: STATIC ASSETS

### 8.1 CSS
- `assets/css/app.css` — TailwindCSS + custom styles

### 8.2 JavaScript
- `assets/js/app.js` — Core app logic (API calls, forms)
- `assets/js/dashboard.js` — Dashboard-specific logic
- `assets/js/admin.js` — Admin-specific logic

---

## PHASE 9: AUTOMATED DEPLOYMENT (GitHub Actions)

### 9.1 Deployment Workflow (`.github/workflows/deploy-to-cpanel.yml`)

```yaml
name: Deploy to cPanel

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v2
      
      - name: Deploy via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.0
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          local-dir: './src/'
          server-dir: '/public_html/'
          dangerous-clean-slate: false
          exclude: |
            **/.git*
            **/.env
            **/config.php
            
      - name: Run database migrations
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USERNAME }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /home/username/public_html
            bash scripts/run-migrations.sh
            bash scripts/restart-cron.sh
```

### 9.2 Manual Deploy Script (`scripts/deploy.sh`)

```bash
#!/bin/bash

# Manual deployment script for cPanel

echo "=== PRIMEAXIS DEPLOYMENT ==="

# Configuration
FTP_HOST=${1:-"your-ftp-host.com"}
FTP_USER=${2:-"cpanel-user"}
FTP_PASS=${3:-"cpanel-password"}
REMOTE_PATH="/public_html"
LOCAL_PATH="./src"

# 1. Backup existing
echo "Creating backup..."
ssh ${FTP_USER}@${FTP_HOST} "cd ${REMOTE_PATH} && tar czf backup-$(date +%Y%m%d-%H%M%S).tar.gz ."

# 2. Upload files via SFTP
echo "Uploading files..."
lftp -u ${FTP_USER},${FTP_PASS} ${FTP_HOST} <<EOF
cd ${REMOTE_PATH}
mirror -R --delete ${LOCAL_PATH}/ ./
quit
EOF

# 3. Set permissions
ssh ${FTP_USER}@${FTP_HOST} "chmod -R 755 ${REMOTE_PATH}; chmod -R 777 ${REMOTE_PATH}/assets/uploads; chmod -R 777 ${REMOTE_PATH}/logs"

# 4. Run migrations
ssh ${FTP_USER}@${FTP_HOST} "cd ${REMOTE_PATH} && bash scripts/run-migrations.sh"

# 5. Restart cron
ssh ${FTP_USER}@${FTP_HOST} "cd ${REMOTE_PATH} && bash scripts/restart-cron.sh"

echo "=== DEPLOYMENT COMPLETE ==="
```

---

## PHASE 10: MANUAL DEPLOYMENT

### 10.1 Using FTP/SFTP

1. **Connect to cPanel via FTP**
   ```
   Host: primeaxisinv.com (or FTP server)
   User: cpanel_username
   Pass: cpanel_password
   ```

2. **Upload `src/` contents to `/public_html`**
   - Upload all files from `src/`
   - Set permissions: 755 for directories, 644 for files
   - Set 777 for `/assets/uploads` and `/logs`

3. **Create `config.php` on server**
   - Copy `config/config.example.php` → `/public_html/includes/config.php`
   - Update database credentials for production

4. **Run migrations**
   ```bash
   mysql -u cpanel_db_user -p cpanel_db_name < database/schema.sql
   ```

5. **Set up cron jobs in cPanel**
   ```
   0 * * * * /usr/bin/php /home/username/public_html/cron/process-profits.php
   0 2 * * * /usr/bin/php /home/username/public_html/cron/complete-investments.php
   0 3 * * * /usr/bin/php /home/username/public_html/cron/cleanup.php
   ```

### 10.2 Using SSH/Terminal

```bash
# Connect to server
ssh cpanel_user@primeaxisinv.com

# Navigate to web root
cd /home/cpanel_user/public_html

# Clone repository
git clone https://github.com/yourname/primeaxis-investment.git .

# Copy config
cp config/config.example.php includes/config.php
nano includes/config.php  # Edit with production values

# Set permissions
chmod -R 755 .
chmod -R 777 assets/uploads logs

# Create database
mysql -u cpanel_db_user -p -e "CREATE DATABASE primeaxis_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
mysql -u cpanel_db_user -p primeaxis_prod < database/schema.sql

# Load seed data
mysql -u cpanel_db_user -p primeaxis_prod < database/seeders/plans.sql
mysql -u cpanel_db_user -p primeaxis_prod < database/seeders/settings.sql

# Set up cron jobs (via cPanel or directly)
# ... add cron commands ...
```

---

## PHASE 11: ENVIRONMENT CONFIGURATION

### 11.1 Environment Template (`.env.example`)

```
# Database
DB_HOST=localhost
DB_USER=root
DB_PASS=password
DB_NAME=primeaxis

# Site
SITE_URL=https://primeaxisinv.com
SITE_NAME=Primeaxis Investment
SITE_TIMEZONE=UTC

# Admin Email
ADMIN_EMAIL=admin@primeaxisinv.com

# Mail (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_EMAIL=noreply@primeaxisinv.com
MAIL_FROM_NAME=Primeaxis Investment

# Security
JWT_SECRET=your-super-secret-jwt-key-here
SESSION_TIMEOUT=3600
PASSWORD_RESET_TIMEOUT=1800

# Payment Gateway (Optional)
PAYMENT_GATEWAY=paystack
PAYSTACK_PUBLIC_KEY=your-paystack-key
PAYSTACK_SECRET_KEY=your-paystack-secret
```

### 11.2 Config File (`includes/config.php`)

```php
<?php
// Load environment
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env');
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos(trim($line), '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Database
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'primeaxis');

// Site
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost:8000');
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Primeaxis Investment');
define('SITE_TIMEZONE', $_ENV['SITE_TIMEZONE'] ?? 'UTC');

// Admin
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'admin@primeaxisinv.com');

// Mail
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME'] ?? '');
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD'] ?? '');
define('MAIL_FROM_EMAIL', $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@primeaxisinv.com');
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME'] ?? 'Primeaxis Investment');

// Security
define('JWT_SECRET', $_ENV['JWT_SECRET'] ?? 'your-secret-key');
define('SESSION_TIMEOUT', $_ENV['SESSION_TIMEOUT'] ?? 3600);
define('PASSWORD_RESET_TIMEOUT', $_ENV['PASSWORD_RESET_TIMEOUT'] ?? 1800);

// Set timezone
date_default_timezone_set(SITE_TIMEZONE);
?>
```

---

## PHASE 12: TESTING & VALIDATION

### 12.1 Unit Tests

```
tests/unit/
├── AuthTest.php           ← Test auth functions
├── ValidationTest.php     ← Test validation rules
└── FinanceTest.php        ← Test financial calculations
```

### 12.2 Integration Tests

```
tests/integration/
├── InvestmentFlowTest.php ← Test full investment flow
├── DepositFlowTest.php    ← Test deposit approval flow
└── WithdrawalFlowTest.php ← Test withdrawal flow
```

### 12.3 Manual Testing Checklist

- [ ] User registration & email verification
- [ ] User login & password reset
- [ ] Investment creation & list
- [ ] Deposit request & approval
- [ ] Withdrawal request & approval
- [ ] Referral system
- [ ] Admin dashboard & user management
- [ ] Balance calculations
- [ ] Cron jobs (daily ROI, completion, cleanup)
- [ ] Email notifications

---

## PHASE 13: SECURITY CHECKLIST

- [ ] SQL injection prevention (use PDO prepared statements)
- [ ] XSS prevention (sanitize all output)
- [ ] CSRF token on all forms
- [ ] Session fixation protection
- [ ] Password hashing (bcrypt)
- [ ] Rate limiting on auth endpoints
- [ ] Audit logging for admin actions
- [ ] HTTPS enforced (.htaccess redirect)
- [ ] API endpoints return JSON only
- [ ] No sensitive data in logs
- [ ] Regular security audits

---

## PHASE 14: MONITORING & MAINTENANCE

### 14.1 Logs to Monitor

- `logs/error.log` — PHP errors
- `logs/access.log` — API access
- `logs/audit.log` — Admin actions (from database)
- `logs/cron.log` — Cron job execution

### 14.2 Backup Strategy

```bash
# Daily automated backup
0 2 * * * bash /home/user/public_html/scripts/backup-database.sh

# Weekly full backup
0 3 * * 0 bash /home/user/public_html/scripts/backup-full.sh
```

### 14.3 Health Checks

- Database connectivity
- Cron job execution
- Disk space
- File permissions
- Certificate expiration

---

## DEPLOYMENT WORKFLOW SUMMARY

### Development → Staging → Production

```
1. Local Development
   └─ Commit to `develop` branch
   
2. Code Review
   └─ Create pull request to `staging`
   
3. Staging Deployment
   └─ Automatic via GitHub Actions to staging server
   └─ Manual testing on staging
   
4. Production Deployment
   └─ Create pull request to `main`
   └─ Automatic via GitHub Actions to production
   └─ Run migrations
   └─ Restart cron jobs
   └─ Monitor logs
```

---

## QUICK START COMMANDS

```bash
# Local setup
bash scripts/setup-local.sh

# Create admin user
bash scripts/create-admin.sh

# Run migrations
bash scripts/run-migrations.sh

# Backup database
bash scripts/backup-database.sh

# Deploy to cPanel (manual)
bash scripts/deploy.sh ftp.host.com username password

# Start dev server
php -S localhost:8000 -t src/

# Run tests
php -r "include 'tests/bootstrap.php'; phpunit tests/"
```

---

## IMPORTANT NOTES

1. **Never commit `.env` or `config.php`** — Use `.env.example` and `config.example.php` as templates
2. **Always backup before deployment** — The deploy script creates automatic backups
3. **Test migrations locally first** — Before running on production
4. **Monitor cron jobs** — Check logs to ensure they run successfully
5. **Use prepared statements** — Always use PDO prepared statements to prevent SQL injection
6. **Sanitize all input** — Never trust user input
7. **HTTPS only** — Enforce HTTPS in production
8. **Regular audits** — Review admin actions and access logs regularly

---

## BUILD COMPLETION STATUS

### ✅ COMPLETED PHASES (Tracked in PROGRESS.md)

| Phase | Name | Status | Commits | Lead |
|-------|------|--------|---------|------|
| 1 | Repository Structure & Initialization | ✅ | #1 | Auto |
| 2 | Local Development Setup | ✅ | #2 | Auto |
| 3 | Database Setup & Migrations | ✅ | #3 | Auto |
| 4 | Authentication API | ✅ | #4 | Cursor |
| 5 | Authentication System (Shells) | ✅ | #4 | Cursor |
| 6 | API Endpoints Implementation | ✅ | #5 | Claude |
| 7 | Frontend Pages | ✅ | #6 | Claude |
| 8 | Static Assets (CSS, JS) | ✅ | #7 | Claude |

### ⏳ PENDING PHASES

| Phase | Name | Status | Target |
|-------|------|--------|--------|
| 9 | Automated Deployment (GitHub Actions) | ⏳ | Phase 9 |
| 10 | Manual Deployment & SSH Setup | ⏳ | Phase 10 |
| 11 | Environment Configuration Finalization | ⏳ | Phase 11 |
| 12 | Testing & Validation | ⏳ | Phase 12 |
| 13 | Security Implementation & Hardening | ⏳ | Phase 13 |
| 14 | Monitoring & Maintenance Setup | ⏳ | Phase 14 |

### FILE COUNT SUMMARY

**Committed Files:**
- PHP API Endpoints: 27 files (user, investments, deposits, withdrawals, admin, cron)
- Frontend Pages: 17 files (public, dashboard, admin)
- Static Assets: 4 files (CSS, JS)
- Support Files: 11 files (includes, config, scripts)
- Database Files: 6 files (schema, migrations, seeders)
- **Total: 65+ files**

### KEY RESOURCES

- **Architecture Reference:** `blueprint.md` (complete spec)
- **Build Progress:** `PROGRESS.md` (detailed checklist)
- **Deployment Plan:** `DEPLOYMENT.md` (infrastructure setup)
- **Build Changelog:** `PERSONAL_ADDITIONS.md` (explicit iteration notes)
- **Database Schema:** `docs/DATABASE.md` (schema documentation)

---

**Document Version:** 1.1  
**Last Updated:** May 15, 2026  
**Maintained By:** Development Team (Claude, Cursor Auto)
