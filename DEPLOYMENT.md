# DEPLOYMENT GUIDE

**Last updated:** June 1, 2026
**Current server:** Namecheap cPanel — `primbtqy@198.54.119.205:21098`
**Web root:** `~/public_html/`
**Database:** `primbtqy_primeaxis2`

## Prerequisites

- cPanel hosting (Namecheap, Hostinger, or similar)
- PHP 8.0+ (current: 8.2.31)
- MariaDB/MySQL (current: MariaDB 11.4)
- SSH access (key-based recommended)
- Domain pointed to server

## Current Production Server

```bash
ssh -p 21098 primbtqy@198.54.119.205
```

SSH key: `~/.ssh/id_ed25519` already authorized.

## Deployment Method (Manual via SCP)

This is how we deploy — SCP all `src/` files to the server:

```bash
# Upload all source files
scp -P 21098 -r src/* primbtqy@198.54.119.205:~/public_html/

# Upload individual directories (preserves structure)
scp -P 21098 -r src/api/auth/* primbtqy@198.54.119.205:~/public_html/api/auth/
scp -P 21098 -r src/api/admin/* primbtqy@198.54.119.205:~/public_html/api/admin/
# ... etc for each api/ subdirectory

# Upload config
scp -P 21098 config/config.example.php primbtqy@198.54.119.205:~/config/config.example.php

# Upload migrations
scp -P 21098 database/schema.sql primbtqy@198.54.119.205:~/schema.sql
scp -P 21098 database/migrations/*.sql primbtqy@198.54.119.205:~/
```

**Important:** Always use individual subdirectory SCP, not a single recursive upload. SCP with multiple source files dumps everything into the target directory, destroying the folder structure.

## Post-Upload Steps

```bash
# Set permissions
chmod 755 ~/public_html
chmod 755 ~/public_html/api/*/
find ~/public_html -type d -exec chmod 755 {} \;

# Copy config
cp ~/config/config.example.php ~/config/config.php

# Create .env
cat > ~/.env << 'EOF'
DB_HOST=localhost
DB_USER=primbtqy_primeaxis
DB_PASS=your-db-password
DB_NAME=primbtqy_primeaxis2
SITE_URL=http://primeaxisinv.com
SITE_NAME=Primeaxis Investment
MAIL_HOST=server405.web-hosting.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@primeaxisinv.com
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_EMAIL=no-reply@primeaxisinv.com
MAIL_FROM_NAME=Primeaxis Investment
EOF

# Run migrations
mariadb -u primbtqy_primeaxis -p primbtqy_primeaxis2 < ~/schema.sql
mariadb -u primbtqy_primeaxis -p primbtqy_primeaxis2 -e "ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT NULL AFTER bio;"
# ... run each migration file

# Create admin user
cd ~/public_html && php -r "
require_once 'includes/config.php';
\$hash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 12]);
\$db = Database::getInstance();
\$db->query(\"INSERT INTO admin_users (username, email, password_hash, role, status) VALUES ('admin', 'admin@primeaxisinv.com', \$hash, 'super_admin', 'active')\");
echo 'Admin created\n';
"
```

## Database Migrations

In order:
1. `database/schema.sql` — Core tables (10 tables)
2. `database/migrations/003_add_crypto_wallets.sql` — Admin wallet settings
3. `database/migrations/004_add_avatar.sql` — User avatar column
4. `database/migrations/005_add_user_wallets.sql` — User BTC/USDT/ETH wallet addresses + withdrawal coin/wallet columns

## Cron Jobs

Configure in cPanel → Cron Jobs:

```
0 * * * * /usr/bin/php /home/primbtqy/public_html/api/cron/process-profits.php
0 2 * * * /usr/bin/php /home/primbtqy/public_html/api/cron/complete-investments.php
0 3 * * * /usr/bin/php /home/primbtqy/public_html/api/cron/cleanup.php
```

## Post-Deployment

- [ ] Test user registration + welcome email
- [ ] Test user login
- [ ] Test admin login
- [ ] Test investment creation + confirmation email
- [ ] Test deposit flow
- [ ] Test withdrawal flow
- [ ] Test password reset email
- [ ] Verify cron jobs run
- [ ] Check SSL certificate (AutoSSL)

## Troubleshooting

- **404 on API endpoints** → Check directory permissions (`chmod 755` on all `api/` subdirs)
- **Login CSRF error** → Check SITE_URL matches protocol (http vs https)
- **Email not sending** → Verify MAIL_* credentials in `.env`
- **Plans not appearing** → Check `api/investments/` directory has execute permissions
- **Files not found** → SCP flattened directories — re-upload subdirectories individually
