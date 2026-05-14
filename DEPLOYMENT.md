# DEPLOYMENT GUIDE

## Prerequisites

- cPanel hosting
- PHP 8.0+
- MySQL 8.0+
- SSH or FTP access
- Domain configured in cPanel

## Deployment Methods

### Method 1: Automated (GitHub Actions)

1. Configure GitHub secrets:
   - `FTP_SERVER`
   - `FTP_USERNAME`
   - `FTP_PASSWORD`
   - `SSH_HOST`
   - `SSH_USERNAME`
   - `SSH_PRIVATE_KEY`

2. Push to `main` branch
3. GitHub Actions automatically deploys

### Method 2: Manual via cPanel

1. Login to cPanel
2. Use File Manager to upload files from `src/` to `/public_html`
3. Set permissions: 755 for directories, 644 for files
4. Create MySQL database
5. Run migrations
6. Configure cron jobs

### Method 3: SSH/Terminal

```bash
# Connect to server
ssh cpanel_user@primeaxisinv.com

# Clone repository
cd /home/cpanel_user/public_html
git clone https://github.com/yourname/primeaxis-investment.git .

# Copy config
cp config/config.example.php includes/config.php
nano includes/config.php  # Edit with production values

# Set permissions
chmod -R 755 .
chmod -R 777 assets/uploads logs

# Run migrations
mysql -u cpanel_db_user -p primeaxis_prod < database/schema.sql
```

## Cron Jobs

Configure in cPanel → Cron Jobs:

```
0 * * * * /usr/bin/php /home/username/public_html/src/api/cron/process-profits.php
0 2 * * * /usr/bin/php /home/username/public_html/src/api/cron/complete-investments.php
0 3 * * * /usr/bin/php /home/username/public_html/src/api/cron/cleanup.php
```

## Post-Deployment

- [ ] Test user registration
- [ ] Test user login
- [ ] Create admin user
- [ ] Test admin dashboard
- [ ] Monitor logs
- [ ] Test email notifications
- [ ] Verify cron jobs run

## Troubleshooting

See [TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md) for common issues.
