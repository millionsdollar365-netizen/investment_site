# DATABASE DOCUMENTATION

## Overview

The Primeaxis Investment Platform uses MySQL 8.0+ with a carefully designed relational schema. All financial values use `DECIMAL(20,8)` for precision.

## Database Connection

**Configuration File:** `includes/config.php`

```php
Database::getInstance(); // Returns PDO connection
$db = Database::getInstance();
```

## Tables

### 1. `users` - User Accounts

Stores user profile and account information.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| first_name | VARCHAR(100) | User's first name |
| last_name | VARCHAR(100) | User's last name |
| email | VARCHAR(255) | Unique email address |
| phone | VARCHAR(20) | Optional phone number |
| password_hash | VARCHAR(255) | Bcrypt hashed password |
| balance | DECIMAL(20,8) | Main account balance |
| interest_balance | DECIMAL(20,8) | Earned interest/profits |
| referral_code | VARCHAR(20) | Unique referral code for user |
| referred_by | INT UNSIGNED | ID of referrer (foreign key) |
| status | ENUM | active, suspended, banned |
| email_verified | BOOLEAN | Email verification status |
| email_verification_token | VARCHAR(255) | Email verification token |
| password_reset_token | VARCHAR(255) | Password reset token |
| password_reset_expires | DATETIME | Token expiration time |
| profile_picture | VARCHAR(255) | Path to profile picture |
| bio | TEXT | User bio |
| kyc_status | ENUM | pending, verified, rejected |
| kyc_document | VARCHAR(255) | KYC document path |
| created_at | TIMESTAMP | Account creation time |
| updated_at | TIMESTAMP | Last update time |

**Indexes:**
- `email` (UNIQUE)
- `referral_code` (UNIQUE)
- `status`
- `created_at`

### 2. `admin_users` - Admin Accounts

Stores admin/staff user credentials.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| username | VARCHAR(100) | Unique username |
| email | VARCHAR(255) | Unique email |
| password_hash | VARCHAR(255) | Bcrypt hashed password |
| role | ENUM | super_admin, admin, moderator |
| status | ENUM | active, inactive, suspended |
| last_login | DATETIME | Last login timestamp |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |

**Roles:**
- `super_admin` - Full access to all functions
- `admin` - Can manage deposits, withdrawals, users
- `moderator` - Limited approval rights

### 3. `investment_plans` - Investment Plans

Defines available investment plans with their terms.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| name | VARCHAR(100) | Plan name |
| description | TEXT | Plan description |
| min_amount | DECIMAL(20,8) | Minimum investment amount |
| max_amount | DECIMAL(20,8) | Maximum investment amount (NULL = no limit) |
| duration_days | INT | Investment duration in days |
| daily_roi | DECIMAL(5,2) | Daily ROI percentage |
| total_return | DECIMAL(20,8) | Total return percentage |
| status | ENUM | active, inactive |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |

**Example Plans:**
- Starter Plan: 100-999, 30 days, 2.5% daily ROI
- Silver Plan: 1000-4999, 30 days, 3.0% daily ROI
- Gold Plan: 5000-19999, 30 days, 3.5% daily ROI

### 4. `investments` - Active Investments

Tracks user investments and their performance.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| user_id | INT UNSIGNED | Foreign key to users |
| plan_id | INT UNSIGNED | Foreign key to investment_plans |
| amount | DECIMAL(20,8) | Investment amount |
| daily_roi | DECIMAL(20,8) | Daily profit amount |
| total_profit | DECIMAL(20,8) | Total profit earned to date |
| status | ENUM | active, completed, cancelled |
| start_date | DATETIME | Investment start date |
| end_date | DATETIME | Investment maturity date |
| completed_date | DATETIME | Actual completion date |
| created_at | TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | Last update time |

**Status Flow:**
- `active` → `completed` (on maturity)
- `active` → `cancelled` (user or admin request)

### 5. `deposits` - Deposit Requests

Tracks user deposit requests and their approval status.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| user_id | INT UNSIGNED | Foreign key to users |
| amount | DECIMAL(20,8) | Deposit amount |
| payment_method | VARCHAR(50) | Payment method (bank, crypto, etc.) |
| transaction_ref | VARCHAR(255) | Transaction reference ID |
| status | ENUM | pending, approved, rejected |
| approved_by | INT UNSIGNED | Admin ID who approved |
| approval_date | DATETIME | Approval timestamp |
| rejection_reason | TEXT | Reason for rejection |
| created_at | TIMESTAMP | Request creation time |
| updated_at | TIMESTAMP | Last update time |

**Approval Workflow:**
1. User creates deposit → status = `pending`
2. Admin approves → status = `approved`, balance updated
3. Or admin rejects → status = `rejected`

### 6. `withdrawals` - Withdrawal Requests

Tracks user withdrawal requests.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| user_id | INT UNSIGNED | Foreign key to users |
| amount | DECIMAL(20,8) | Withdrawal amount |
| bank_name | VARCHAR(100) | Bank name |
| account_number | VARCHAR(50) | Bank account number |
| account_holder_name | VARCHAR(100) | Account holder name |
| status | ENUM | pending, approved, rejected, completed |
| approved_by | INT UNSIGNED | Admin ID who approved |
| approval_date | DATETIME | Approval timestamp |
| completed_date | DATETIME | Completion timestamp |
| rejection_reason | TEXT | Reason for rejection |
| created_at | TIMESTAMP | Request creation time |
| updated_at | TIMESTAMP | Last update time |

**Status Flow:**
1. User creates withdrawal → `pending`
2. Admin approves → `approved`
3. Admin marks as paid → `completed`
4. Or admin rejects → `rejected`

### 7. `transactions` - Immutable Transaction Ledger

Records all financial transactions (immutable audit trail).

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| user_id | INT UNSIGNED | Foreign key to users |
| type | ENUM | deposit, withdrawal, investment, profit, referral, adjustment |
| amount | DECIMAL(20,8) | Transaction amount |
| old_balance | DECIMAL(20,8) | Balance before transaction |
| new_balance | DECIMAL(20,8) | Balance after transaction |
| reference_id | INT UNSIGNED | ID of related record (deposit ID, investment ID, etc.) |
| reference_table | VARCHAR(50) | Table name of related record |
| description | TEXT | Transaction description |
| created_at | TIMESTAMP | Transaction timestamp |

**Transaction Types:**
- `deposit` - Money added by user/admin
- `withdrawal` - Money paid out to user
- `investment` - Money invested in a plan
- `profit` - Daily ROI payment
- `referral` - Referral commission
- `adjustment` - Admin balance adjustment

### 8. `referrals` - Referral Relationships

Tracks referral relationships between users.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| referrer_id | INT UNSIGNED | User making the referral |
| referred_id | INT UNSIGNED | Referred user ID |
| commission_percentage | DECIMAL(5,2) | Referral commission % |
| commission_amount | DECIMAL(20,8) | Total commission earned |
| status | ENUM | active, inactive |
| created_at | TIMESTAMP | Creation time |

### 9. `settings` - Platform Configuration

Stores platform-wide settings.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| setting_key | VARCHAR(100) | Setting name (UNIQUE) |
| setting_value | LONGTEXT | Setting value |
| description | TEXT | Setting description |
| updated_at | TIMESTAMP | Last update time |

**Common Settings:**
- `referral_percentage` - Referral commission percentage
- `withdrawal_fee` - Withdrawal transaction fee
- `min_withdrawal` - Minimum withdrawal amount
- `max_withdrawal` - Maximum withdrawal amount
- `kyc_required` - Whether KYC is required

### 10. `audit_logs` - Admin Actions Log

Records all admin actions for compliance and auditing.

**Columns:**
| Column | Type | Notes |
|--------|------|-------|
| id | INT UNSIGNED | Primary key |
| admin_id | INT UNSIGNED | Admin who performed action |
| user_id | INT UNSIGNED | User affected (if applicable) |
| action | VARCHAR(100) | Action name |
| entity_type | VARCHAR(50) | Entity type (user, deposit, investment, etc.) |
| entity_id | INT UNSIGNED | Entity ID |
| old_values | JSON | Previous values |
| new_values | JSON | New values |
| ip_address | VARCHAR(50) | Admin's IP address |
| user_agent | VARCHAR(500) | Admin's user agent |
| created_at | TIMESTAMP | Action timestamp |

## Financial Data Integrity

### Balance Calculation

```
User Total Balance = balance + interest_balance
```

Where:
- `balance` - Main account balance (from deposits)
- `interest_balance` - Earned interest and profits

### Immutable Transaction Ledger

All financial changes are recorded in `transactions` table. Balances can be recalculated from this ledger:

```sql
SELECT SUM(CASE 
    WHEN type IN ('deposit', 'profit', 'referral') THEN amount 
    WHEN type IN ('withdrawal', 'investment') THEN -amount
    ELSE 0
END) as calculated_balance
FROM transactions
WHERE user_id = ? AND created_at <= NOW()
```

### Daily ROI Calculation

Daily profits are calculated and added via cron job:

```php
// For each active investment
daily_profit = investment_amount * (daily_roi / 100)

// Update interest_balance
user.interest_balance += daily_profit

// Create transaction record
createTransaction($user_id, 'profit', daily_profit, 'investment_' . $investment_id)
```

## Database Migrations

Migrations are in `database/migrations/`:

```bash
001_init_schema.sql     # Create all tables
002_add_indexes.sql     # Add performance indexes
```

Run migrations:

```bash
bash scripts/run-migrations.sh
```

## Database Seeders

Seeders are in `database/seeders/`:

```bash
admin-user.sql          # Create default admin
plans.sql              # Create investment plans
settings.sql           # Create platform settings
```

## Backup & Restore

**Backup database:**
```bash
bash scripts/backup-database.sh
```

**Restore from backup:**
```bash
bash scripts/restore-database.sh backups/database_backup_20260514_143022.sql.gz
```

**Reset database (CAUTION):**
```bash
bash scripts/reset-database.sh
```

## Connection Pool

The application uses PDO with a single connection instance (Singleton pattern):

```php
$db = Database::getInstance();
$result = $db->fetchOne($sql, $params);
```

Always use prepared statements to prevent SQL injection.

## Performance Optimization

### Indexes Created

- `users(email)` - Fast email lookups
- `users(referral_code)` - Fast referral lookups
- `investments(user_id, status)` - Fast user investment queries
- `deposits(status, created_at)` - Fast pending approval queries
- `withdrawals(status, created_at)` - Fast pending approval queries
- `transactions(user_id, type)` - Fast transaction queries

### Query Best Practices

1. Always filter by `user_id` when accessing user data
2. Use `status` index for approval workflows
3. Use composite indexes for multi-column WHERE clauses
4. Partition audit logs by date if they grow very large

## Character Encoding

- **Charset:** utf8mb4 (supports emoji and international characters)
- **Collation:** utf8mb4_unicode_ci (case-insensitive, accent-insensitive)

## Financial Column Precision

All financial columns use `DECIMAL(20,8)`:
- Total digits: 20
- Decimal places: 8
- Max value: 99,999,999.99999999
- Min value: -99,999,999.99999999

This ensures no floating-point rounding errors in calculations.

---

**Last Updated:** May 14, 2026  
**Version:** 1.0
