# AI Coding Conventions — PrimeAxis Investment

**Last updated:** July 9, 2026
**Purpose:** Single source of truth for any AI or developer joining this project. Read this before writing any code.

---

## 1. PROJECT TYPE

**Vanilla PHP** — No framework, no Composer, no npm build step. Just PHP files served by Apache/LiteSpeed.

- PHP 8.2+, MariaDB 11.4
- Deployed to shared cPanel hosting (Namecheap)
- Domain: `primeaxisinv.com`
- All pages are standalone `.php` files

---

## 2. DIRECTORY STRUCTURE

```
/
├── AI_CONVENTIONS.md          ← THIS FILE
├── PROGRESS.md                ← Project progress tracker
├── PERSONAL_ADDITIONS.md      ← Detailed changelog
├── DEPLOYMENT.md              ← How to deploy
│
├── config/
│   ├── config.example.php     ← Template for production config
│   └── config.php             ← Production config (gitignored, created on server)
│
├── database/
│   ├── schema.sql             ← Full database schema
│   └── migrations/            ← Numbered migration files (run in order)
│
├── src/                       ← ALL application code
│   ├── .htaccess              ← Apache rewrite rules + security headers
│   │
│   ├── includes/              ← PHP classes and functions (included by pages)
│   │   ├── config.php         ← Bootstrap: loads config + db + functions
│   │   ├── db.php             ← Database singleton (PDO)
│   │   ├── auth.php           ← registerUser(), authenticateUser(), sanitizeUserForClient()
│   │   ├── session.php        ← User session: loginUser(), logoutUser(), isLoggedIn(), requireLogin()
│   │   ├── admin-session.php  ← Admin session: same pattern, separate from user
│   │   ├── response.php       ← success($message, $data) and error($message, $code)
│   │   ├── validation.php     ← Validator::email(), required(), minLength(), positive(), regex()
│   │   ├── security.php       ← hashPassword(), verifyPassword(), requireCsrf(), getCsrfToken()
│   │   ├── functions.php      ← formatCurrency(), getUserBalance(), createTransaction(), auditLog()
│   │   ├── mail.php           ← Mail::send(), Mail::sendWelcome(), Mail::sendPasswordReset(), etc.
│   │   ├── argon-header.php   ← Dashboard/admin sidebar + topbar + page header (shared)
│   │   └── argon-footer.php   ← Dashboard/admin footer + scripts (shared)
│   │
│   ├── api/                   ← All API endpoints (each file returns JSON via response.php)
│   │   ├── auth/              ← login.php, register.php, logout.php, forgot-password.php, etc.
│   │   ├── user/              ← dashboard.php, profile.php, update-profile.php, transactions.php, etc.
│   │   ├── investments/       ← plans.php, create.php, list.php
│   │   ├── deposits/          ← create.php, list.php
│   │   ├── withdrawals/       ← create.php, list.php
│   │   ├── admin/             ← dashboard.php, users.php, deposits.php, withdrawals.php, plans.php, settings.php, etc.
│   │   └── cron/              ← process-profits.php, complete-investments.php, cleanup.php
│   │
│   ├── dashboard/             ← User dashboard pages (requireLogin())
│   │   ├── index.php          ← Main dashboard with stats
│   │   ├── investments.php    ← Active investments table
│   │   ├── plans.php          ← Investment plans pricing cards (where users invest)
│   │   ├── deposits.php       ← Crypto deposit flow
│   │   ├── withdrawals.php    ← Crypto withdrawal flow
│   │   ├── transactions.php   ← Paginated transaction history
│   │   ├── earnings.php       ← Profit/interest tracker
│   │   ├── referrals.php      ← Referral link + commission tracking
│   │   ├── profile.php        ← Personal info + avatar upload
│   │   └── settings.php       ← Wallet addresses + password change
│   │
│   ├── admin/                 ← Admin pages (requireAdminLogin())
│   │   ├── index.php          ← Admin dashboard with stats
│   │   ├── login.php          ← Admin login (separate from user login)
│   │   ├── users.php          ← User management with search/filter
│   │   ├── deposits.php       ← Deposit approval/rejection
│   │   ├── withdrawals.php    ← Withdrawal approval/rejection
│   │   ├── investments.php    ← All investments view
│   │   ├── plans.php          ← Create/edit/toggle investment plans
│   │   ├── settings.php       ← Platform settings editor
│   │   └── change-password.php
│   │
│   ├── assets/                ← Static files
│   │   ├── css/               ← argon.css, app.css
│   │   ├── js/                ← app.js, argon.js, dashboard.js, admin.js
│   │   └── img/               ← logo-v2.svg, favicon.svg, uploads/avatars/
│   │
│   ├── messages/              ← HTML email templates ({{placeholder}} syntax)
│   │   ├── welcome.html
│   │   ├── password-reset.html
│   │   ├── investment-confirmation.html
│   │   ├── deposit-approved.html
│   │   ├── withdrawal-update.html
│   │   ├── roi-payout.html
│   │   ├── investment-completed.html
│   │   └── admin-notification.html
│   │
│   └── [root pages]           ← Public pages (no login required)
│       ├── index.php          ← Landing page
│       ├── login.php          ← User login
│       ├── register.php       ← User registration
│       ├── forgot-password.php
│       └── reset-password.php
│
├── scratch/                   ← Experimental landing (cyan/purple)
├── scratch2/                  ← Experimental landing (luxury gold/void)
├── springstone/               ← Experimental landing (Springstone clone)
│
├── landing.php                ← Glassmorphism cyan/purple variant
├── landing1.php               ← Curricula-inspired warm editorial variant
├── landing2.php               ← OrgChart-inspired corporate variant
└── old_index.php              ← Original Argon landing (backup)
```

---

## 3. HOW PAGES CONNECT

### Every PHP file starts with a bootstrap
```php
require_once __DIR__ . '/includes/config.php';  // Loads config, db, functions
require_once __DIR__ . '/includes/session.php';  // Session handling
require_once __DIR__ . '/includes/auth.php';     // User authentication
```

**For files one level deep (e.g., `/dashboard/`, `/admin/`):**
```php
require_once __DIR__ . '/../includes/config.php';
```

**For API files (e.g., `/api/auth/`):**
```php
require_once __DIR__ . '/../../includes/config.php';
```

### Page types and their requirements
| Page Type | Directory | Requires | Session Check |
|-----------|-----------|----------|---------------|
| Public | `src/*.php` | None | `requireLogout()` for auth pages |
| User Dashboard | `src/dashboard/*.php` | `requireLogin()` | Must be logged in |
| Admin | `src/admin/*.php` | `requireAdminLogin()` | Must be admin |
| API | `src/api/**/*.php` | Varies | `isSessionValid()` or `isAdminSessionValid()` |

### API conventions
- Every API file returns JSON via `response.php`: `success('message', ['data' => $val])` or `error('reason', null, 400)`
- Every API file validates HTTP method: `if ($_SERVER['REQUEST_METHOD'] !== 'POST') error(...)`
- Input via `$_POST`, validated with `Validator::` static methods
- Database access via `Database::getInstance()->query/fetchOne/fetchAll()`

### Frontend JS conventions
- **Always use direct `fetch()` for data loading**, NOT `apiCall()`:
  ```javascript
  const r = await fetch('/api/endpoint.php');
  const d = await r.json();
  if (d.success) { /* d.data.something */ }
  ```
- `apiCall()` is only for POST form submissions where error handling is needed
- `showAlert(message, type)` for SweetAlert popups (green success, red error)
- `showToast(message, type)` for Notyf corner toasts (auto-dismiss)

### Database schema (10 core tables)
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `users` | User accounts | id, email, password_hash, balance, referral_code, wallet_btc/usdt/ethereum |
| `admin_users` | Admin accounts | id, username, password_hash, role |
| `investment_plans` | Plan offerings | id, name, min_amount, max_amount, duration_days, daily_roi, sort_order, is_popular |
| `investments` | Active/completed investments | id, user_id, plan_id, amount, daily_roi, total_profit, status, start_date, end_date |
| `deposits` | Deposit requests | id, user_id, amount, payment_method, status |
| `withdrawals` | Withdrawal requests | id, user_id, amount, coin, wallet_address, status |
| `transactions` | All financial events | id, user_id, type, amount, old_balance, new_balance, description |
| `referrals` | Referral tracking | id, referrer_id, referred_id, status |
| `settings` | Platform configuration | setting_key, setting_value |
| `audit_logs` | Admin actions | admin_id, action, entity_type, entity_id |

---

## 4. NAMING CONVENTIONS

### Files
- **kebab-case.php** for all PHP files: `forgot-password.php`, `change-password.php`
- **kebab-case.html** for templates: `investment-confirmation.html`
- **Numbers for migrations**: `001_init.sql`, `002_indexes.sql`, `003_add_wallets.sql`

### PHP
- **Classes**: `PascalCase` — `Database`, `Mail`, `Security`, `Validator`
- **Functions**: `camelCase` — `getUserBalance()`, `createTransaction()`, `isLoggedIn()`, `formatCurrency()`
- **Variables**: `snake_case` — `$user_id`, `$first_name`, `$old_balance`
- **Constants**: `UPPER_SNAKE` — `SITE_NAME`, `DB_HOST`, `SESSION_TIMEOUT`, `CRON_SECRET`

### JavaScript
- **Functions**: `camelCase` — `loadDashboard()`, `showCreateModal()`, `toggleMenu()`
- **Variables**: `camelCase` — `currentPage`, `plansData`, `userBalance`
- **DOM IDs**: `camelCase` — `depositsList`, `createModal`, `planStatus`

### CSS classes
- **BEM-ish**: `pricing-card`, `pricing-card.popular`, `mobile-overlay`, `mobile-overlay.open`
- **Argon uses**: `.card`, `.stat-card`, `.badge`, `.btn`, `.nav-link`

### API endpoints
- `api/[area]/[action].php` — `api/auth/login.php`, `api/admin/approve-deposit.php`
- GET for reading, POST for creating/updating
- Each endpoint is a standalone file

---

## 5. SERVER & DEPLOYMENT

### Connection
```bash
ssh -p 21098 primbtqy@198.54.119.205
```

### Web root
```
/home/primbtqy/public_html/
```

### Deploy: SCP individual subdirectories
```bash
# NEVER: scp -r src/* server:~/public_html/  (destroys subdirectory structure)
# ALWAYS:
scp -P 21098 -r src/api/auth/* primbtqy@198.54.119.205:~/public_html/api/auth/
scp -P 21098 -r src/api/admin/* primbtqy@198.54.119.205:~/public_html/api/admin/
scp -P 21098 src/*.php primbtqy@198.54.119.205:~/public_html/
# etc.
```

### Deploy checklist
1. Upload files
2. Run migrations: `mariadb -u primbtqy_primeaxis -p primbtqy_primeaxis2 < migration.sql`
3. Copy config: `cp ~/config/config.example.php ~/config/config.php`
4. Update .env if new constants added
5. Set permissions: `chmod 755` on all new directories

### Environment (.env at ~/.env)
```
DB_HOST=localhost
DB_USER=primbtqy_primeaxis
DB_PASS=Chubooy000@123
DB_NAME=primbtqy_primeaxis2
SITE_URL=https://primeaxisinv.com
SITE_NAME=Primeaxis Investment
MAIL_HOST=server405.web-hosting.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@primeaxisinv.com
MAIL_PASSWORD=Colour@123@123
CRON_SECRET=29c22e7a31176639256084b75d7b715b
SESSION_TIMEOUT=2592000
```

### Cron jobs (crontab -l)
```
0 0 * * * /usr/local/bin/php /home/primbtqy/public_html/api/cron/process-profits.php
0 2 * * * /usr/local/bin/php /home/primbtqy/public_html/api/cron/complete-investments.php
0 3 * * * /usr/local/bin/php /home/primbtqy/public_html/api/cron/cleanup.php
```

---

## 6. COMMON MISTAKES TO AVOID

1. **Wrong require_once paths** — Files in subdirectories need `../includes/` not `includes/`
2. **SCP flattening** — `scp file1 file2 server:dir/` dumps all files into `dir/`, losing subdirectory structure
3. **Directory permissions** — New directories must be `chmod 755` or Apache returns 403
4. **`apiCall()` vs `fetch()`** — Use direct `fetch()` for GET data loading, `apiCall()` only for POST submissions
5. **Session timeout** — Set to 30 days (2592000s), refreshes on every request
6. **CSRF tokens** — Generated in session, verified via `Security::requireCsrf()`. Auth forms need meta tag + JS append
7. **Old/new balance** — `createTransaction()` accepts optional `$old_balance` parameter. Capture balance BEFORE the update
8. **cPanel shared hosting** — Always access via domain name (not IP) for HTTP requests. The IP serves multiple sites

---

## 7. KEY URLS

| Page | URL |
|------|-----|
| Landing (production) | `https://primeaxisinv.com/` |
| Landing (scratch2) | `https://primeaxisinv.com/scratch2/` |
| Landing (springstone) | `https://primeaxisinv.com/springstone/` |
| Login | `https://primeaxisinv.com/login.php` |
| Register | `https://primeaxisinv.com/register.php` |
| Dashboard | `https://primeaxisinv.com/dashboard/` |
| Plans (invest) | `https://primeaxisinv.com/dashboard/plans.php` |
| Admin | `https://primeaxisinv.com/admin/` |
| Admin login | `https://primeaxisinv.com/admin/login.php` |
| Logo | `https://primeaxisinv.com/assets/img/logo-v2.svg` |
| Favicon | `https://primeaxisinv.com/assets/img/favicon.svg` |
