# MASTER AI BUILD SPECIFICATION
## Primeaxis Investment — PHP Investment Platform — Deterministic Architecture Specification
## Version 2.0 — Complete & Gap-Resolved

---

# 1. PROJECT OVERVIEW

**Platform Name:** Primeaxis Investment
**Domain:** primeaxisinv.com

This document defines the complete architecture, conventions, logic, folder structure, database schema, security model, financial engine, dashboard structure, admin system, and coding rules for a fully functional investment platform.

This specification is intended for AI-assisted autonomous development.

The system must be:

- deterministic
- modular
- financially consistent
- cPanel deployable
- secure by default
- maintainable
- auditable
- API-driven

---

# 2. CORE SYSTEM PHILOSOPHY

## Frontend Philosophy

Frontend pages are protected PHP shells.

Example:

```text
/dashboard/index.php
/admin/index.php
```

PHP is NOT used for rendering application data.

PHP is ONLY used for:

- session protection
- redirects
- bootstrapping

All application data must be loaded dynamically using AJAX/FETCH requests to backend PHP API endpoints.

Frontend stack:

- HTML5
- TailwindCSS (CDN)
- Vanilla JavaScript
- Optional AlpineJS

---

## Backend Philosophy

Backend is API-only.

Every backend endpoint:

- validates session
- validates request method
- sanitizes input
- executes database logic
- returns JSON only

Backend stack:

- PHP 8+
- PDO only
- MySQL 8
- Apache

No framework required.

---

## Financial Philosophy

The system uses:

- cached wallet balances
- immutable transaction ledger
- recomputable accounting

Meaning:

- balances are stored for performance
- transactions are the source of financial truth
- balances can be rebuilt entirely from transactions

---

# 3. GLOBAL RULES

## Naming Convention

ALL naming must use snake_case.

Examples:

```text
interest_balance
total_profit
created_at
investment_return
```

Never use:

```text
InterestBalance
totalProfit
CreatedAt
```

---

## File Naming Convention

All backend files use kebab-case:

```text
create-investment.php
approve-deposit.php
get-dashboard.php
```

---

## JSON Response Standard

ALL API endpoints MUST return:

### Success

```json
{
  "success": true,
  "message": "Investment created successfully.",
  "data": {}
}
```

### Error

```json
{
  "success": false,
  "message": "Insufficient balance."
}
```

No HTML responses.
No mixed output.
No echo debugging.

---

## Database Rules

- Use InnoDB only
- Use UTF8MB4 charset and utf8mb4_unicode_ci collation
- Use foreign keys where applicable
- Use DECIMAL(20,8) for all financial values
- Never use FLOAT for money

---

# 4. PROJECT FOLDER STRUCTURE

```text
/
│
├── index.php                        ← Public landing/redirect page
├── login.php
├── register.php
├── logout.php
├── forgot-password.php
├── reset-password.php
│
├── dashboard/
│   ├── index.php                    ← Main dashboard
│   ├── investments.php
│   ├── deposits.php
│   ├── withdrawals.php
│   ├── referrals.php
│   ├── transactions.php
│   ├── profile.php
│   └── settings.php                 ← User settings (password change, etc.)
│
├── admin/
│   ├── index.php                    ← Admin dashboard
│   ├── login.php                    ← Separate admin login page
│   ├── logout.php
│   ├── users.php
│   ├── deposits.php
│   ├── withdrawals.php
│   ├── investments.php
│   ├── transactions.php
│   ├── plans.php
│   ├── referrals.php
│   ├── settings.php                 ← Platform settings (wallets, referral %, etc.)
│   ├── logs.php
│   └── adjustments.php
│
├── api/
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── check-session.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   │
│   ├── user/
│   │   ├── get-dashboard.php
│   │   ├── get-profile.php
│   │   ├── update-profile.php
│   │   ├── upload-picture.php
│   │   ├── change-password.php
│   │   └── transfer-to-balance.php  ← Move interest_balance → balance
│   │
│   ├── investments/
│   │   ├── create.php
│   │   └── list.php
│   │
│   ├── deposits/
│   │   ├── create.php
│   │   └── list.php
│   │
│   ├── withdrawals/
│   │   ├── create.php
│   │   └── list.php
│   │
│   ├── referrals/
│   │   └── get-referrals.php
│   │
│   ├── admin/
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── check-session.php
│   │   ├── get-dashboard.php
│   │   ├── get-users.php
│   │   ├── get-user.php
│   │   ├── suspend-user.php
│   │   ├── activate-user.php
│   │   ├── adjust-balance.php
│   │   ├── get-deposits.php
│   │   ├── approve-deposit.php
│   │   ├── reject-deposit.php
│   │   ├── get-withdrawals.php
│   │   ├── approve-withdrawal.php
│   │   ├── reject-withdrawal.php
│   │   ├── get-investments.php
│   │   ├── force-complete-investment.php
│   │   ├── get-transactions.php
│   │   ├── get-plans.php
│   │   ├── create-plan.php
│   │   ├── edit-plan.php
│   │   ├── disable-plan.php
│   │   ├── get-settings.php
│   │   └── update-settings.php
│   │
│   └── cron/
│       └── (cron scripts call directly, not via HTTP)
│
├── assets/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── dashboard.js
│   │   └── admin.js
│   ├── images/
│   └── uploads/
│       └── profile_pictures/
│
├── includes/
│   ├── config.php                   ← All environment config (DB, SMTP, site)
│   ├── db.php                       ← PDO singleton
│   ├── session.php                  ← Session start + user auth check
│   ├── admin-session.php            ← Session start + admin auth check
│   ├── auth.php                     ← Auth helpers
│   ├── functions.php                ← General helpers
│   ├── security.php                 ← Sanitization, CSRF, etc.
│   ├── mail.php                     ← PHPMailer wrapper
│   ├── validation.php               ← Input validation helpers
│   └── response.php                 ← json_response() helper
│
├── cron/
│   ├── process-profits.php          ← Pay daily ROI to active investments
│   ├── complete-investments.php     ← Return principal on completed investments
│   └── cleanup.php                  ← Expire password reset tokens + compress logs
│
├── storage/
│   └── logs/
│       ├── api_errors.log
│       ├── cron.log
│       ├── security.log
│       └── withdrawals.log
│
├── vendor/                          ← Composer dependencies (PHPMailer)
│
└── .htaccess
```

---

# 5. CONFIGURATION FILE

## /includes/config.php

This is the single source of all environment configuration.

```php
<?php

// ─── Site ────────────────────────────────────────────────────────────────────
define('SITE_NAME',  'Primeaxis Investment');
define('SITE_URL',   'https://primeaxisinv.com');
define('SITE_EMAIL', 'noreply@primeaxisinv.com');

// ─── Database ─────────────────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'primeaxis_db');       // UPDATE on deployment
define('DB_USER', 'primeaxis_user');     // UPDATE on deployment
define('DB_PASS', 'CHANGE_ME');          // UPDATE on deployment
define('DB_CHARSET', 'utf8mb4');

// ─── SMTP ─────────────────────────────────────────────────────────────────────
define('SMTP_HOST',     'mail.primeaxisinv.com');  // UPDATE on deployment
define('SMTP_PORT',     465);
define('SMTP_USER',     'noreply@primeaxisinv.com'); // UPDATE on deployment
define('SMTP_PASS',     'CHANGE_ME');               // UPDATE on deployment
define('SMTP_SECURE',   'ssl');
define('SMTP_FROM',     'noreply@primeaxisinv.com');
define('SMTP_FROM_NAME', 'Primeaxis Investment');

// ─── Financial ────────────────────────────────────────────────────────────────
define('REFERRAL_PERCENT', 5.0);   // 5% of investment amount — configurable via admin settings table

// ─── File Uploads ─────────────────────────────────────────────────────────────
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);  // 5MB
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/profile_pictures/');

// ─── Pagination ───────────────────────────────────────────────────────────────
define('PAGE_SIZE', 20);  // Records per page for all paginated lists

// ─── Password Reset ───────────────────────────────────────────────────────────
define('RESET_TOKEN_EXPIRY', 3600);  // 1 hour in seconds
```

---

# 6. DATABASE SCHEMA

## users

```sql
CREATE TABLE users (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email            VARCHAR(255) NOT NULL UNIQUE,
    password         VARCHAR(255) NOT NULL,
    full_name        VARCHAR(255) NULL,
    phone            VARCHAR(50)  NULL,
    profile_picture  VARCHAR(255) NULL,
    referral_code    VARCHAR(100) UNIQUE NULL,
    referred_by      BIGINT UNSIGNED NULL,
    referral_paid    TINYINT(1) DEFAULT 0,    -- 1 = referral bonus already paid to referrer
    status           ENUM('active','suspended') DEFAULT 'active',

    balance          DECIMAL(20,8) DEFAULT 0,
    interest_balance DECIMAL(20,8) DEFAULT 0,

    total_deposit    DECIMAL(20,8) DEFAULT 0,
    total_invested   DECIMAL(20,8) DEFAULT 0,
    total_withdrawal DECIMAL(20,8) DEFAULT 0,
    total_profit     DECIMAL(20,8) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## transactions

```sql
CREATE TABLE transactions (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      BIGINT UNSIGNED NOT NULL,

    type ENUM(
        'deposit',
        'withdrawal',
        'profit',
        'investment',
        'investment_return',
        'referral',
        'transfer',
        'admin_adjustment'
    ) NOT NULL,

    wallet_type ENUM(
        'balance',
        'interest_balance'
    ) NOT NULL,

    amount       DECIMAL(20,8) NOT NULL,   -- positive = credit, negative = debit

    status ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'approved',

    reference_id BIGINT UNSIGNED NULL,     -- links to deposits.id / withdrawals.id / investments.id
    description  TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## investments

```sql
CREATE TABLE investments (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        BIGINT UNSIGNED NOT NULL,
    plan_id        BIGINT UNSIGNED NULL,
    plan_name      VARCHAR(255) NOT NULL,   -- snapshot of plan name at time of investment

    funding_wallet ENUM('balance','interest_balance') NOT NULL,

    amount         DECIMAL(20,8) NOT NULL,  -- principal invested
    daily_roi      DECIMAL(20,8) NOT NULL,  -- daily payout amount (amount * daily_percent / 100)
    daily_percent  DECIMAL(10,4) NOT NULL,  -- daily % rate snapshotted from plan
    total_roi      DECIMAL(20,8) NOT NULL,  -- daily_roi * duration_days (total expected earnings)

    duration_days  INT NOT NULL,            -- original plan duration
    days_remaining INT NOT NULL,            -- countdown: decrements each cron cycle
    days_received  INT DEFAULT 0,           -- how many payouts have been made

    status ENUM('active','completed') DEFAULT 'active',

    start_date      DATE NOT NULL,
    end_date        DATE NOT NULL,
    next_profit_at  DATETIME NOT NULL,       -- cron pays when NOW() >= next_profit_at

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## plans

```sql
CREATE TABLE plans (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(255) NOT NULL,
    daily_percent  DECIMAL(10,4) NOT NULL,   -- e.g. 10.0000 means 10% per day
    duration_days  INT NOT NULL,              -- e.g. 1 = paid once, 3 = paid over 3 days
    minimum_amount DECIMAL(20,8) NOT NULL,
    maximum_amount DECIMAL(20,8) NOT NULL,
    status         ENUM('active','disabled') DEFAULT 'active',
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Plan Seed Data (dummy — update from admin panel)

```sql
INSERT INTO plans (name, daily_percent, duration_days, minimum_amount, maximum_amount, status) VALUES
('Plan 1', 5.0000,  7,  100.00000000, 999.00000000,     'active'),
('Plan 2', 7.0000,  14, 1000.00000000, 4999.00000000,   'active'),
('Plan 3', 10.0000, 30, 5000.00000000, 19999.00000000,  'active'),
('Plan 4', 15.0000, 60, 20000.00000000, 99999.00000000, 'active');
```

---

## deposits

```sql
CREATE TABLE deposits (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          BIGINT UNSIGNED NOT NULL,

    method           ENUM('btc','usdt','ethereum') NOT NULL,
    amount_usd       DECIMAL(20,8) NOT NULL,
    wallet_address   TEXT NOT NULL,          -- platform receiving address shown to user
    transaction_hash VARCHAR(255) NULL,      -- user-supplied payment proof

    status ENUM('pending','approved','rejected') DEFAULT 'pending',

    approved_by  BIGINT UNSIGNED NULL,
    approved_at  DATETIME NULL,
    rejected_at  DATETIME NULL,
    note         TEXT NULL,                  -- admin rejection reason

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## withdrawals

```sql
CREATE TABLE withdrawals (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        BIGINT UNSIGNED NOT NULL,

    method         ENUM('btc','usdt','ethereum') NOT NULL,
    wallet_address TEXT NOT NULL,           -- user's receiving address
    amount_usd     DECIMAL(20,8) NOT NULL,

    status ENUM('pending','approved','rejected') DEFAULT 'pending',

    approved_by BIGINT UNSIGNED NULL,
    approved_at DATETIME NULL,
    rejected_at DATETIME NULL,
    note        TEXT NULL,                  -- admin rejection reason

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## referrals

```sql
CREATE TABLE referrals (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id  BIGINT UNSIGNED NOT NULL,   -- user who referred
    referred_id  BIGINT UNSIGNED NOT NULL,   -- user who was referred
    investment_id BIGINT UNSIGNED NOT NULL,  -- the first investment that triggered the bonus
    bonus_amount DECIMAL(20,8) NOT NULL,     -- actual amount paid
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (referrer_id)   REFERENCES users(id),
    FOREIGN KEY (referred_id)   REFERENCES users(id),
    FOREIGN KEY (investment_id) REFERENCES investments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## password_resets

```sql
CREATE TABLE password_resets (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NOT NULL,
    token      VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used       TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## settings

```sql
CREATE TABLE settings (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name   VARCHAR(100) NOT NULL UNIQUE,
    value      TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Settings Seed Data (dummy — update from admin panel)

```sql
INSERT INTO settings (key_name, value) VALUES
('site_name',           'Primeaxis Investment'),
('site_email',          'noreply@primeaxisinv.com'),
('referral_percent',    '5'),
('btc_wallet',          '1A1zP1eP5QGefi2DMPTfTL5SLmv7Divf8X'),
('usdt_wallet',         'TN3W4H6rK2ce4vX9YnFQHwKx6nBGTFmAAW'),
('ethereum_wallet',     '0x742d35Cc6634C0532925a3b844Bc454e4438f44e'),
('min_withdrawal',      '10'),
('maintenance_mode',    '0');
```

---

## admins

```sql
CREATE TABLE admins (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL DEFAULT 'Admin',
    email      VARCHAR(255) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Admin Seed Data

```sql
INSERT INTO admins (name, email, password) VALUES
('Admin', 'admin@primeaxisinv.com', PASSWORD_HASH_OF('Chubooy000@123'));
```

> **NOTE FOR AI:** Do NOT insert the plain text password. Generate the bcrypt hash using PHP's `password_hash('Chubooy000@123', PASSWORD_BCRYPT)` and insert the resulting hash string. This must be done via a one-time setup script, not hardcoded as plain text.

---

# 7. REQUIRED DATABASE INDEXES

```sql
CREATE INDEX idx_users_referral_code      ON users(referral_code);
CREATE INDEX idx_users_referred_by        ON users(referred_by);
CREATE INDEX idx_transactions_user_id     ON transactions(user_id);
CREATE INDEX idx_transactions_type        ON transactions(type);
CREATE INDEX idx_transactions_created_at  ON transactions(created_at);
CREATE INDEX idx_investments_user_id      ON investments(user_id);
CREATE INDEX idx_investments_status       ON investments(status);
CREATE INDEX idx_investments_next_profit  ON investments(next_profit_at);
CREATE INDEX idx_deposits_user_id         ON deposits(user_id);
CREATE INDEX idx_deposits_status          ON deposits(status);
CREATE INDEX idx_withdrawals_user_id      ON withdrawals(user_id);
CREATE INDEX idx_withdrawals_status       ON withdrawals(status);
CREATE INDEX idx_password_resets_token    ON password_resets(token);
CREATE INDEX idx_referrals_referrer_id    ON referrals(referrer_id);
CREATE INDEX idx_referrals_referred_id    ON referrals(referred_id);
```

---

# 8. AUTHENTICATION SYSTEM

## User vs Admin Sessions — STRICTLY SEPARATE

User sessions and admin sessions use DIFFERENT session keys and DIFFERENT session files.

### User Session Key

```php
$_SESSION['user_id']
```

### Admin Session Key

```php
$_SESSION['admin_id']
```

These must NEVER be confused. An authenticated user cannot access admin endpoints. An authenticated admin cannot access user endpoints.

---

## User Auth Check (includes/session.php)

```php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}
```

Include at the top of every `/dashboard/*.php` page.

---

## Admin Auth Check (includes/admin-session.php)

```php
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin/login.php");
    exit;
}
```

Include at the top of every `/admin/*.php` page (except `/admin/login.php`).

---

## API Admin Auth Check

Every `/api/admin/*.php` endpoint MUST check:

```php
session_start();
if (!isset($_SESSION['admin_id'])) {
    json_response(false, 'Unauthorized.');
}
```

---

## Login Rules

- Use `password_hash()` with `PASSWORD_BCRYPT`
- Use `password_verify()`
- Regenerate session ID after successful login
- Destroy session completely on logout

```php
session_regenerate_id(true);
```

---

## Password Reset Flow

1. User submits email to `/api/auth/forgot-password.php`
2. System generates a cryptographically secure token: `bin2hex(random_bytes(32))`
3. Token stored in `password_resets` table with `expires_at = NOW() + 3600 seconds`
4. Email sent with link: `https://primeaxisinv.com/reset-password.php?token=TOKEN`
5. User clicks link → `/reset-password.php` validates token via `/api/auth/reset-password.php`
6. If token is valid, not used, and not expired: update `users.password`, mark token `used = 1`
7. If token is expired or used: return error "This link has expired or already been used."

---

# 9. SECURITY STANDARDS

## REQUIRED SECURITY RULES

ALL API endpoints MUST:

- check request method (reject wrong methods immediately)
- validate authentication (user or admin session)
- validate and sanitize all inputs
- use prepared statements exclusively
- return JSON only
- never expose raw PHP errors or stack traces

---

## Prepared Statements — Mandatory

NEVER:

```php
$query = "SELECT * FROM users WHERE email = '$email'";
```

ALWAYS:

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

---

## XSS Prevention

All user-supplied content rendered in HTML:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
```

Never trust frontend input.

---

## CSRF Protection

All state-changing POST endpoints (non-GET) must validate a CSRF token.

Implementation:

- Generate token on page load: `$_SESSION['csrf_token'] = bin2hex(random_bytes(32))`
- Include in all forms/AJAX as `X-CSRF-Token` header
- Validate on every POST endpoint:

```php
if ($_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
    json_response(false, 'Invalid CSRF token.');
}
```

---

## Password Rules

- Minimum 8 characters
- Hashed with `PASSWORD_BCRYPT` only
- Never logged
- Never returned in any API response

---

## File Upload Security

Profile pictures only.

Allowed extensions: `jpg`, `jpeg`, `png`, `webp`

Validation:

- Validate MIME type using `finfo_file()`
- Validate file extension
- Validate file size: maximum 5MB (`MAX_UPLOAD_SIZE = 5242880`)
- Rename to UUID: `bin2hex(random_bytes(16)) . '.' . $ext`
- Never trust original filename
- Store in `/assets/uploads/profile_pictures/`

---

# 10. FINANCIAL SYSTEM RULES

## IMPORTANT PRINCIPLE

Transactions are immutable financial logs.

Transactions must NEVER be deleted or updated.

---

## Financial Transaction Flow

ALL balance changes MUST follow this exact sequence:

1. Begin database transaction (`$pdo->beginTransaction()`)
2. Lock user row with `SELECT ... FOR UPDATE`
3. Re-read balance from locked row (do not trust previously-read value)
4. Validate balance is sufficient (if deducting)
5. Update balance on `users` table
6. Insert row into `transactions` table
7. Commit (`$pdo->commit()`)
8. On any error: rollback (`$pdo->rollBack()`) and return error

---

## Row Locking Standard

ALL financial operations must lock the user row before mutation:

```sql
SELECT * FROM users WHERE id = ? FOR UPDATE
```

This prevents race conditions when multiple requests hit simultaneously.

---

## Wallet Rules

| Wallet            | Can deposit into | Can invest from | Can withdraw from | Can transfer to balance |
|-------------------|-----------------|-----------------|-------------------|-------------------------|
| `balance`         | ✅ (deposits)    | ✅               | ✅                 | N/A                     |
| `interest_balance`| ✅ (ROI payouts) | ✅               | ❌                 | ✅                       |

Users CANNOT withdraw directly from `interest_balance`.
To access `interest_balance` funds as cash, users must first transfer to `balance` via the transfer feature.

---

# 11. DEPOSIT FLOW

## User Flow

1. User opens deposit page
2. Selects method (BTC / USDT / ETH)
3. System displays the platform's receiving wallet address (fetched from `settings` table)
4. User enters amount in USD
5. User optionally provides transaction hash as payment proof
6. Request stored in `deposits` table as `status = pending`
7. No balance change at this point

---

## Admin Approval Flow

Upon admin approval:

```text
BEGIN TRANSACTION
  SELECT user FOR UPDATE
  users.balance        += amount_usd
  users.total_deposit  += amount_usd
  INSERT INTO transactions:
    type        = 'deposit'
    wallet_type = 'balance'
    amount      = +amount_usd
    status      = 'approved'
    reference_id = deposits.id
  UPDATE deposits SET status='approved', approved_by=admin_id, approved_at=NOW()
COMMIT
```

Send email notification to user: deposit approved.

---

## Admin Rejection Flow

```text
UPDATE deposits SET status='rejected', approved_by=admin_id, rejected_at=NOW(), note='reason'
```

No balance change. Send email notification to user: deposit rejected.

---

# 12. WITHDRAWAL FLOW

## Rules

- Withdrawals come from `balance` ONLY.
- `interest_balance` cannot be withdrawn directly.
- Balance is NOT deducted when request is submitted.
- Balance is deducted ONLY when admin approves.
- There is no system-level lock on pending withdrawal amounts. Admin must review balance at time of approval.

---

## User Flow

1. User enters withdrawal amount
2. Selects method (BTC / USDT / ETH)
3. Enters their personal wallet address
4. Validates: `amount >= settings.min_withdrawal` AND `users.balance >= amount`
5. Request stored in `withdrawals` table as `status = pending`
6. No balance change at this point

---

## Withdrawal Approval Flow

Upon admin approval:

```text
BEGIN TRANSACTION
  SELECT user FOR UPDATE
  Re-validate: users.balance >= amount_usd (reject if insufficient)
  users.balance          -= amount_usd
  users.total_withdrawal += amount_usd
  INSERT INTO transactions:
    type        = 'withdrawal'
    wallet_type = 'balance'
    amount      = -amount_usd
    status      = 'approved'
    reference_id = withdrawals.id
  UPDATE withdrawals SET status='approved', approved_by=admin_id, approved_at=NOW()
COMMIT
```

Send email notification to user: withdrawal approved.

---

## Withdrawal Rejection Flow

```text
UPDATE withdrawals SET status='rejected', approved_by=admin_id, rejected_at=NOW(), note='reason'
```

No balance change. Send email notification to user: withdrawal rejected.

---

# 13. INTEREST BALANCE TRANSFER

Users can move funds from `interest_balance` to `balance` at any time.

## Transfer Flow

```text
BEGIN TRANSACTION
  SELECT user FOR UPDATE
  Validate: interest_balance >= transfer_amount
  users.interest_balance -= transfer_amount
  users.balance          += transfer_amount
  INSERT INTO transactions (debit leg):
    type        = 'transfer'
    wallet_type = 'interest_balance'
    amount      = -transfer_amount
  INSERT INTO transactions (credit leg):
    type        = 'transfer'
    wallet_type = 'balance'
    amount      = +transfer_amount
COMMIT
```

No email required for this action.

---

# 14. INVESTMENT ENGINE

## Multiple Investments Allowed

Users can hold unlimited simultaneous active investments.

---

## ROI Calculation

```text
daily_roi     = amount * (daily_percent / 100)
total_roi     = daily_roi * duration_days
end_date      = start_date + duration_days
next_profit_at = NOW() + 24 hours
```

Example — Plan with 10% total over 3 days:
- `daily_percent = 3.3333`
- `duration_days = 3`
- Invest $1000 → `daily_roi = $33.33`, `total_roi = $99.99`

Example — Plan with 10% daily for 7 days:
- `daily_percent = 10.0`
- `duration_days = 7`
- Invest $1000 → `daily_roi = $100`, `total_roi = $700`

The `daily_percent` stored in the plan is always the daily rate.
When creating a plan where the advertised return is a total return over N days, divide accordingly.

---

## Investment Creation Flow

Validation:
- Amount >= `plan.minimum_amount`
- Amount <= `plan.maximum_amount`
- Selected wallet has sufficient balance
- Plan `status = 'active'`

```text
BEGIN TRANSACTION
  SELECT user FOR UPDATE
  Validate balance
  Deduct selected wallet:
    IF funding_wallet = 'balance':        users.balance          -= amount
    IF funding_wallet = 'interest_balance': users.interest_balance -= amount
  users.total_invested += amount
  INSERT INTO investments:
    plan_name      = plan.name (snapshot)
    daily_roi      = calculated
    daily_percent  = plan.daily_percent (snapshot)
    total_roi      = calculated
    duration_days  = plan.duration_days
    days_remaining = plan.duration_days
    start_date     = TODAY
    end_date       = TODAY + duration_days
    next_profit_at = NOW() + 24 hours
  INSERT INTO transactions:
    type         = 'investment'
    wallet_type  = funding_wallet
    amount       = -amount
    reference_id = investment.id
COMMIT
```

Check referral bonus trigger (see Section 16).

---

# 15. DAILY PROFIT CRON ENGINE

## Cron Schedule

Run every minute via cPanel:

```bash
* * * * * /usr/local/bin/php /home/USERNAME/public_html/cron/process-profits.php >> /dev/null 2>&1
```

The script uses `next_profit_at` as the gate, so running every minute is safe — investments only pay when due.

---

## process-profits.php Logic

```text
Find all investments WHERE status = 'active' AND next_profit_at <= NOW()
Process each one individually:

  BEGIN TRANSACTION
    SELECT investment FOR UPDATE
    SELECT user FOR UPDATE
    users.interest_balance += investment.daily_roi
    users.total_profit     += investment.daily_roi
    INSERT INTO transactions:
      type        = 'profit'
      wallet_type = 'interest_balance'
      amount      = +daily_roi
      reference_id = investment.id
    investments.days_remaining -= 1
    investments.days_received  += 1
    investments.next_profit_at  = next_profit_at + INTERVAL 24 HOUR
    IF days_remaining <= 0:
      investments.status = 'completed'
      [trigger completion flow below]
  COMMIT

  Log each processed investment to cron.log
```

---

## complete-investments.php Logic

This script handles the principal return on completion. It can be called from within `process-profits.php` OR run as a separate cron. Recommended: called inline when `days_remaining <= 0`.

```text
When investment.days_remaining <= 0 (triggered inside process-profits.php):

  users.balance += investment.amount   (return principal to balance)
  investments.status = 'completed'
  INSERT INTO transactions:
    type        = 'investment_return'
    wallet_type = 'balance'
    amount      = +investment.amount
    reference_id = investment.id

  Send email: investment completed
```

---

# 16. REFERRAL SYSTEM

## Rules

- Single-level referrals only (direct referrals, no multi-level)
- Referral bonus is paid ONCE per referred user — on their FIRST investment only
- Referral percentage: **5%** of the first investment amount
- This is configurable via the `settings` table (`key_name = 'referral_percent'`)

---

## Referral Code Assignment

On registration, generate a unique referral code for every user:

```php
$referral_code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
```

Ensure uniqueness by checking the database before saving.

---

## Referral Bonus Trigger

Triggered inside investment creation flow, AFTER the investment row is successfully committed.

```text
IF investment is the user's FIRST investment (check users.referral_paid = 0)
  AND users.referred_by IS NOT NULL:

  referral_percent = fetch from settings table
  bonus = investment.amount * (referral_percent / 100)

  BEGIN TRANSACTION
    SELECT referrer user FOR UPDATE
    referrer.balance += bonus
    INSERT INTO transactions (for referrer):
      type        = 'referral'
      wallet_type = 'balance'
      amount      = +bonus
      reference_id = investment.id
    INSERT INTO referrals:
      referrer_id   = referred_by user id
      referred_id   = current user id
      investment_id = investment.id
      bonus_amount  = bonus
    UPDATE users SET referral_paid = 1 WHERE id = current user id
  COMMIT

DO NOT increment referrer's total_deposit.
Referral bonuses are NOT deposits.
```

---

# 17. ADMIN ADJUSTMENTS

Admin can manually credit or debit any user's `balance` or `interest_balance`.

```text
BEGIN TRANSACTION
  SELECT user FOR UPDATE
  Apply adjustment (positive = credit, negative = debit)
  Validate: resulting balance must not go below 0
  INSERT INTO transactions:
    type        = 'admin_adjustment'
    wallet_type = chosen wallet
    amount      = adjustment amount (signed)
    description = admin's note
COMMIT
```

Never modify `total_deposit` via admin adjustment unless it is an actual deposit approval.

---

# 18. CRON — cleanup.php

Run daily via cPanel:

```bash
0 2 * * * /usr/local/bin/php /home/USERNAME/public_html/cron/cleanup.php >> /dev/null 2>&1
```

Tasks:

1. **Expire password reset tokens** — delete rows in `password_resets` where `expires_at < NOW()` OR `used = 1`
2. **Compress old logs** — any log file in `/storage/logs/` older than 30 days gets gzipped and archived
3. Log cleanup run to `cron.log`

---

# 19. EMAIL SYSTEM

## Library

Use **PHPMailer** via Composer.

```bash
composer require phpmailer/phpmailer
```

---

## Mail Wrapper (includes/mail.php)

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function send_mail(string $to_email, string $to_name, string $subject, string $html_body): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to_email, $to_name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail failed: " . $e->getMessage());
        return false;
    }
}
```

---

## Email Triggers

| Event                    | Recipient | Subject                             |
|--------------------------|-----------|-------------------------------------|
| Registration             | User      | Welcome to Primeaxis Investment     |
| Deposit approved         | User      | Your deposit has been approved      |
| Deposit rejected         | User      | Your deposit was not approved       |
| Withdrawal approved      | User      | Your withdrawal has been processed  |
| Withdrawal rejected      | User      | Your withdrawal was not approved    |
| Investment created       | User      | Investment confirmed                |
| Investment completed     | User      | Your investment has matured         |
| Password reset           | User      | Reset your password                 |

---

# 20. DASHBOARD REQUIREMENTS

## User Dashboard Layout

### Layout Structure (Desktop)

```text
┌─────────────────────────────────────────────────────┐
│  Sidebar (collapsible)  │  Main Content Area         │
│  - Dashboard            │  - Stats Cards             │
│  - Investments          │  - Active Investments      │
│  - Deposit              │  - Recent Transactions     │
│  - Withdraw             │                            │
│  - Referrals            │                            │
│  - Transactions         │                            │
│  - Profile              │                            │
│  - Settings             │                            │
│  [Logout]               │                            │
└─────────────────────────────────────────────────────┘
```

### Layout Structure (Mobile)

```text
┌──────────────────────┐
│  Topbar (hamburger)  │
├──────────────────────┤
│  Main Content Area   │
│                      │
│                      │
├──────────────────────┤
│  Bottom Nav Bar      │
│  Home │ Invest │ ... │
└──────────────────────┘
```

Mobile sidebar slides in from the left when hamburger is tapped.
Bottom navigation bar shows: Dashboard, Invest, Deposit, Withdraw, Profile.

---

## User Dashboard Stat Cards

```text
- Total Balance           (users.balance)
- Interest Balance        (users.interest_balance)
- Total Profit Earned     (users.total_profit)
- Total Invested          (users.total_invested)
- Total Deposited         (users.total_deposit)
- Total Withdrawn         (users.total_withdrawal)
- Active Investments      (COUNT of active investments)
- Referral Earnings       (SUM of referral transactions)
```

---

## Active Investments Table

Columns:

```text
- Plan Name
- Amount Invested
- Daily ROI
- Days Remaining
- Days Received
- Total Expected ROI
- Status
- Start Date / End Date
```

---

## Deposit Section

- User selects method (BTC / USDT / ETH)
- System displays the platform's wallet address for that method (from `settings` table)
- User enters amount in USD
- User pastes their transaction hash
- Submits → stored as pending

---

## Withdrawal Section

- User enters amount
- Selects method
- Enters their personal wallet address
- Validates minimum withdrawal amount (from `settings.min_withdrawal`)
- Validates sufficient `balance`
- Submits → stored as pending

---

## Transfer Section (Interest → Balance)

- User enters amount to transfer from `interest_balance` to `balance`
- Confirms transfer
- Instant — no admin approval required

---

## Referral Section

```text
- Referral link: https://primeaxisinv.com/register.php?ref=USER_CODE
- Total referred users
- Total referral earnings
- List of referred users (username/email masked, join date, bonus earned)
```

---

## get-dashboard.php JSON Response Shape

```json
{
  "success": true,
  "message": "Dashboard loaded.",
  "data": {
    "user": {
      "id": 1,
      "full_name": "John Doe",
      "email": "john@example.com",
      "profile_picture": "/assets/uploads/profile_pictures/abc123.jpg",
      "referral_code": "PRIMEABC",
      "referral_link": "https://primeaxisinv.com/register.php?ref=PRIMEABC"
    },
    "wallets": {
      "balance": "1500.00000000",
      "interest_balance": "320.50000000"
    },
    "stats": {
      "total_deposit": "5000.00000000",
      "total_invested": "3000.00000000",
      "total_withdrawal": "200.00000000",
      "total_profit": "450.00000000",
      "active_investments": 2,
      "referral_earnings": "75.00000000"
    },
    "active_investments": [
      {
        "id": 12,
        "plan_name": "Plan 2",
        "amount": "1000.00000000",
        "daily_roi": "70.00000000",
        "daily_percent": "7.0000",
        "days_remaining": 8,
        "days_received": 6,
        "duration_days": 14,
        "total_roi": "980.00000000",
        "start_date": "2025-01-01",
        "end_date": "2025-01-15",
        "status": "active"
      }
    ],
    "recent_transactions": [
      {
        "id": 55,
        "type": "profit",
        "wallet_type": "interest_balance",
        "amount": "70.00000000",
        "description": "Daily ROI — Plan 2",
        "created_at": "2025-01-07 08:00:01"
      }
    ]
  }
}
```

---

# 21. ADMIN DASHBOARD REQUIREMENTS

## Admin Dashboard Layout

Same collapsible sidebar pattern as user dashboard.

Admin sidebar links:

```text
- Dashboard
- Users
- Deposits
- Withdrawals
- Investments
- Transactions
- Plans
- Referrals
- Settings
- Logs
- Adjustments
[Logout]
```

---

## Admin Dashboard Stat Cards

```text
- Total Users
- Total Deposits (approved)
- Total Withdrawals (approved)
- Total Active Investments
- Total Profit Paid Out
- Pending Deposits (count)
- Pending Withdrawals (count)
- Platform Total Revenue (sum of all deposits)
```

---

## Admin Modules

### Users
- List with search and filter
- View user detail (balances, investments, transactions)
- Suspend / Activate
- Adjust balance (credit or debit any wallet)

### Deposits
- List all (filter: pending / approved / rejected)
- Approve with one click
- Reject with note/reason

### Withdrawals
- List all (filter: pending / approved / rejected)
- Approve with one click
- Reject with note/reason

### Investments
- List all investments (filter by user, status)
- View detail
- Force-complete (triggers principal return + completion email)

### Plans
- List all plans
- Create new plan
- Edit existing plan (name, percent, duration, min/max)
- Disable plan (soft disable — no deletion)

### Transactions
- List all (filter by type, user, date range)
- No deletion allowed

### Referrals
- List all referral events
- Filter by referrer

### Settings
- Edit platform settings:
  - Site name
  - Site email
  - BTC wallet address
  - USDT wallet address
  - ETH wallet address
  - Referral percentage
  - Minimum withdrawal amount
  - Maintenance mode toggle

### Logs
- View `api_errors.log`
- View `cron.log`
- View `security.log`
- View `withdrawals.log`

### Adjustments
- Admin balance adjustment form
- Select user, wallet, amount (positive or negative), reason

---

# 22. FRONTEND RULES

## Responsive Layout — Mobile First

All pages must be designed mobile-first using TailwindCSS utility classes.

### Breakpoints

```text
Mobile:  default (< 768px)
Tablet:  md: (768px+)
Desktop: lg: (1024px+)
```

### Dashboard Layout Rules

- Sidebar is **hidden by default on mobile**, shown on `lg:` screens
- On mobile: hamburger button in topbar toggles sidebar overlay
- Sidebar slides in from the left with a dark overlay backdrop
- Bottom navigation bar is visible on mobile only (`lg:hidden`)
- Bottom nav items: Dashboard, Invest, Deposit, Withdraw, Profile

### Sidebar Collapse (Desktop)

- Sidebar can be collapsed to icon-only mode on desktop
- State stored in `localStorage` key: `sidebar_collapsed`
- Collapsed sidebar shows only icons; expanded shows icons + labels

---

## TailwindCSS

Use Tailwind CDN or build pipeline.
Avoid all inline styles.
Use only Tailwind utility classes.

---

## JavaScript Rules

Use native `fetch()` for all API calls.
No jQuery.

All fetch calls follow this pattern:

```javascript
async function apiPost(endpoint, body = {}) {
  const response = await fetch(endpoint, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-Token': window.CSRF_TOKEN
    },
    body: JSON.stringify(body)
  });
  return await response.json();
}
```

`window.CSRF_TOKEN` is set in the PHP shell page before page scripts load:

```php
<script>window.CSRF_TOKEN = "<?= $_SESSION['csrf_token'] ?>";</script>
```

---

## Loading States

ALL async actions must:

- Disable the triggering button during request
- Show a spinner or loading text on the button
- Re-enable button after response
- Show success toast or error message based on `data.success`

---

## Pagination

All list endpoints accept:

```text
?page=1
```

Default page size: **20 records per page**

API response for paginated endpoints includes:

```json
{
  "success": true,
  "data": {
    "items": [...],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total_items": 143,
      "total_pages": 8
    }
  }
}
```

---

# 23. COMPLETE API ENDPOINT LIST

## Auth

```text
POST /api/auth/login.php
POST /api/auth/register.php
POST /api/auth/logout.php
GET  /api/auth/check-session.php
POST /api/auth/forgot-password.php
POST /api/auth/reset-password.php
```

## User

```text
GET  /api/user/get-dashboard.php
GET  /api/user/get-profile.php
POST /api/user/update-profile.php
POST /api/user/upload-picture.php
POST /api/user/change-password.php
POST /api/user/transfer-to-balance.php
```

## Investments

```text
GET  /api/investments/list.php
POST /api/investments/create.php
```

## Deposits

```text
GET  /api/deposits/list.php
POST /api/deposits/create.php
```

## Withdrawals

```text
GET  /api/withdrawals/list.php
POST /api/withdrawals/create.php
```

## Referrals

```text
GET  /api/referrals/get-referrals.php
```

## Plans (public read)

```text
GET  /api/plans/list.php
```

## Admin

```text
POST /api/admin/login.php
POST /api/admin/logout.php
GET  /api/admin/check-session.php
GET  /api/admin/get-dashboard.php

GET  /api/admin/get-users.php
GET  /api/admin/get-user.php?id=
POST /api/admin/suspend-user.php
POST /api/admin/activate-user.php
POST /api/admin/adjust-balance.php

GET  /api/admin/get-deposits.php
POST /api/admin/approve-deposit.php
POST /api/admin/reject-deposit.php

GET  /api/admin/get-withdrawals.php
POST /api/admin/approve-withdrawal.php
POST /api/admin/reject-withdrawal.php

GET  /api/admin/get-investments.php
POST /api/admin/force-complete-investment.php

GET  /api/admin/get-transactions.php

GET  /api/admin/get-plans.php
POST /api/admin/create-plan.php
POST /api/admin/edit-plan.php
POST /api/admin/disable-plan.php

GET  /api/admin/get-settings.php
POST /api/admin/update-settings.php

GET  /api/admin/get-referrals.php
```

---

# 24. RESPONSE HELPERS

## includes/response.php

```php
<?php
function json_response(bool $success, string $message, array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ]);
    exit;
}
```

---

# 25. DATABASE CONNECTION STANDARD

## includes/db.php — PDO Singleton

```php
<?php
require_once __DIR__ . '/config.php';

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
```

Usage in every endpoint:

```php
$pdo = get_pdo();
```

---

# 26. LOGGING SYSTEM

## Log Files

```text
/storage/logs/api_errors.log
/storage/logs/cron.log
/storage/logs/security.log
/storage/logs/withdrawals.log
```

## Log Helper

```php
function write_log(string $file, string $message): void {
    $path = __DIR__ . '/../storage/logs/' . $file;
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
}
```

Usage:

```php
write_log('cron.log', 'Processed investment ID 42 — ROI $70.00 paid.');
write_log('api_errors.log', 'Error in create-investment.php: ' . $e->getMessage());
write_log('security.log', 'Failed login attempt for email: user@example.com');
write_log('withdrawals.log', 'Withdrawal ID 18 approved for user ID 5 — $200.00');
```

## Log Rules

NEVER log:
- passwords
- session IDs
- API secrets
- private keys

---

# 27. IMPORTANT ACCOUNTING RULES

## total_deposit

Incremented ONLY when a deposit is admin-approved.
Never incremented by referrals, adjustments, or ROI.

## total_profit

Incremented ONLY when ROI is paid by cron.
Never incremented by deposits or referrals.

## total_invested

Incremented ONLY when an investment is created.
Never decremented on completion (principal return does NOT reduce total_invested).

## total_withdrawal

Incremented ONLY when a withdrawal is admin-approved.

## interest_balance

Credited by: ROI payouts (`profit` type)
Debited by: reinvestment from interest_balance, transfer to balance

## balance

Credited by: approved deposits, investment principal return, referral bonuses, admin credit adjustments, transfers from interest_balance
Debited by: investment creation (from balance), approved withdrawals, admin debit adjustments

---

# 28. RECOMPUTATION PHILOSOPHY

Balances must be fully rebuildable from the transactions table.

```sql
-- Rebuild balance
SELECT SUM(amount) FROM transactions
WHERE user_id = ? AND wallet_type = 'balance' AND status = 'approved';

-- Rebuild interest_balance
SELECT SUM(amount) FROM transactions
WHERE user_id = ? AND wallet_type = 'interest_balance' AND status = 'approved';
```

The SUM of all signed amounts per wallet must equal the cached balance on the users table.
This is the auditing guarantee.

---

# 29. PERFORMANCE RULES

- Paginate ALL list endpoints (20 records per page)
- Lazy-load all dashboard data via AJAX on page load
- Index all foreign keys and frequently-queried columns (see Section 7)
- Cache financial totals in `users` table — do not SUM transactions on every request
- Cron scripts must process investments one at a time inside individual transactions to prevent partial failures

---

# 30. DEPLOYMENT INSTRUCTIONS

## cPanel Deployment Steps

1. Upload all files to `public_html/`

2. Create MySQL database and user in cPanel

3. Import full SQL schema (all CREATE TABLE statements in order)

4. Run seed INSERT statements (plans, settings, admin)

5. Run admin password seed script:
```php
// run once then delete
echo password_hash('Chubooy000@123', PASSWORD_BCRYPT);
```
Insert the output hash into `admins.password`.

6. Edit `/includes/config.php`:
   - Set `DB_NAME`, `DB_USER`, `DB_PASS`
   - Set `SMTP_HOST`, `SMTP_USER`, `SMTP_PASS`

7. Install PHPMailer via Composer:
```bash
composer install
```
Or upload the `vendor/` folder directly.

8. Set cPanel cron jobs:
```bash
# Profit cron — every minute
* * * * * /usr/local/bin/php /home/USERNAME/public_html/cron/process-profits.php

# Cleanup cron — daily at 2am
0 2 * * * /usr/local/bin/php /home/USERNAME/public_html/cron/cleanup.php
```

9. Set writable directory permissions:
```bash
chmod 755 /storage/logs/
chmod 755 /assets/uploads/profile_pictures/
```

10. Verify `.htaccess` blocks direct access to:
```text
/includes/
/storage/
/cron/
/vendor/
```

---

# 31. .htaccess RULES

## Root .htaccess

```apache
Options -Indexes

# Block direct access to sensitive directories
RewriteEngine On
RewriteRule ^includes/ - [F,L]
RewriteRule ^storage/  - [F,L]
RewriteRule ^cron/     - [F,L]
RewriteRule ^vendor/   - [F,L]

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

# 32. DEVELOPMENT RULES

## NEVER:

- Mix SQL into frontend pages
- Echo raw database errors to the browser
- Use `mysqli` — use PDO only
- Use string concatenation in SQL queries
- Store plain text passwords
- Trust frontend validation alone
- Delete or modify transaction records
- Use `FLOAT` for any monetary value
- Return passwords in any API response
- Log sensitive data (passwords, tokens, session IDs)

---

## ALWAYS:

- Validate and sanitize all input server-side
- Use database transactions for every money movement
- Use `SELECT ... FOR UPDATE` before balance mutations
- Use prepared statements
- Return uniform JSON from all endpoints
- Log important actions to appropriate log files
- Snapshot plan name and percent into the investment row at creation time
- Re-read balance from locked row before deducting (never trust pre-read value)

---

# 33. FINAL ARCHITECTURE SUMMARY

| Layer           | Technology                              |
|-----------------|-----------------------------------------|
| System Type     | Hybrid API-driven PHP investment platform |
| Frontend        | Protected PHP shells + Vanilla JS fetch |
| Styling         | TailwindCSS (mobile-first, responsive)  |
| Backend         | Raw PHP JSON APIs, no framework         |
| Database        | MySQL 8, InnoDB, UTF8MB4                |
| Auth            | PHP native sessions (user + admin separate) |
| Financial Engine| Cron-driven, immutable transaction ledger |
| Email           | PHPMailer via SMTP                      |
| File Uploads    | Local filesystem, UUID-renamed, 5MB max |
| Deployment      | cPanel compatible                       |
| Security        | PDO prepared statements, CSRF, XSS prevention, session hardening |

---

END OF SPECIFICATION — VERSION 2.0
