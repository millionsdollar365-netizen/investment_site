# PRE-DEPLOYMENT CHECKLIST

**For going live on cPanel or via automated deployment**

---

## 1. CODE & REPOSITORY READY

- [x] All source files committed to GitHub
- [x] `.gitignore` includes sensitive files (config.php, .env, uploads, logs)
- [x] No credentials in committed code
- [x] Phases 1-8 complete (65+ files deployed)
- [x] README.md has clear instructions
- [ ] CHANGELOG.md documents version history (optional)

**Actions:**
```bash
# Verify no .env or config.php are tracked
git ls-files | grep -E "(\.env|config\.php|password)"

# Should return nothing if clean
```

---

## 2. PRODUCTION CONFIGURATION

### 2.1 Environment Variables
- [ ] `.env` file created in project root (NOT in repo, use .env.example as template)
- [ ] `DB_HOST` set to cPanel database host
- [ ] `DB_USER` set to cPanel database user
- [ ] `DB_PASS` set to cPanel database password
- [ ] `DB_NAME` set to cPanel database name
- [ ] `SITE_URL` set to production domain (https://primeaxisinv.com)
- [ ] `SITE_NAME` set correctly
- [ ] `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` configured
- [ ] `MAIL_FROM_EMAIL`, `MAIL_FROM_NAME` set
- [ ] `JWT_SECRET` set to strong random string
- [ ] `SESSION_TIMEOUT` appropriate for production
- [ ] Optional: `PAYMENT_GATEWAY` credentials if needed

**Template (.env file to create manually on cPanel):**
```
DB_HOST=localhost
DB_USER=primeaxis_user
DB_PASS=STRONG_PASSWORD_HERE
DB_NAME=primeaxis_prod
SITE_URL=https://primeaxisinv.com
SITE_NAME=Primeaxis Investment
SITE_TIMEZONE=UTC
ADMIN_EMAIL=admin@primeaxisinv.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_EMAIL=noreply@primeaxisinv.com
MAIL_FROM_NAME=Primeaxis Investment
JWT_SECRET=$(openssl rand -base64 32)
SESSION_TIMEOUT=3600
PASSWORD_RESET_TIMEOUT=1800
```

### 2.2 Production Config
- [ ] `includes/config.php` created on cPanel from `config.example.php`
- [ ] All environment variables properly sourced
- [ ] Database connection tested (connection string validated)

---

## 3. DATABASE SETUP

### 3.1 Create Database on cPanel
- [ ] Login to cPanel → Databases → MySQL Databases
- [ ] Create database: `primeaxis_prod`
- [ ] Create user: `primeaxis_user` with strong password
- [ ] Grant all privileges to user on database
- [ ] Note: hostname is usually `localhost`

### 3.2 Run Migrations
- [ ] SSH into cPanel
- [ ] Navigate to `/home/username/public_html`
- [ ] Run: `mysql -u primeaxis_user -p primeaxis_prod < database/schema.sql`
- [ ] Verify all tables created: Check `information_schema` or run `SHOW TABLES;`

### 3.3 Seed Data
- [ ] Run: `mysql -u primeaxis_user -p primeaxis_prod < database/seeders/plans.sql`
- [ ] Run: `mysql -u primeaxis_user -p primeaxis_prod < database/seeders/settings.sql`
- [ ] Verify plans exist: Check `investments_plans` table
- [ ] Create first admin user via script or manually

**Create Admin User (SSH):**
```bash
bash scripts/create-admin.sh
# Enter username, password, email when prompted
```

---

## 4. FILE PERMISSIONS & STRUCTURE

### 4.1 Directory Permissions
```bash
# Set proper permissions via SSH or cPanel File Manager
chmod -R 755 .              # All directories readable/executable
chmod 644 *.php             # PHP files readable
chmod -R 777 assets/uploads # Writable for user uploads
chmod -R 777 logs           # Writable for logs
chmod -R 777 config         # Writable for config files
```

### 4.2 Sensitive Directory Protection
- [ ] `config/` directory restricted from web access (.htaccess in place)
- [ ] `database/` directory restricted from web access
- [ ] `includes/` directory restricted from web access
- [ ] `scripts/` directory restricted from web access
- [ ] `tests/` directory restricted from web access
- [ ] `.env` file not accessible via web

**.htaccess deployment:**
- [x] `.htaccess` file created in `src/` directory (blocks sensitive paths)
- [ ] Apache `mod_rewrite` enabled on cPanel (check in cPanel → Select PHP Version → Extensions)
- [ ] `.htaccess` not blocked by cPanel AllowOverride setting

---

## 5. APACHE CONFIGURATION

### 5.1 Enable Required Modules
- [ ] `mod_rewrite` enabled (for URL routing)
- [ ] `mod_deflate` enabled (for compression)
- [ ] `mod_expires` enabled (for caching headers)
- [ ] `mod_headers` enabled (for security headers)

**Check via SSH:**
```bash
apache2ctl -M | grep rewrite  # Should show rewrite_module (mod_rewrite)
```

### 5.2 Public HTML Configuration
- [ ] Verify `DocumentRoot` points to `/home/cpanel_user/public_html`
- [ ] Verify domain DNS points to cPanel server IP
- [ ] SSL certificate installed (Let's Encrypt recommended)
- [ ] Force HTTPS (uncomment in .htaccess when ready)

---

## 6. CRON JOBS SETUP

### 6.1 Configure in cPanel

**Go to: cPanel → Advanced → Cron Jobs**

Add these three cron jobs:

**Cron Job 1 — Process Profits (hourly)**
```
0 * * * * /usr/bin/php /home/cpanel_user/public_html/src/api/cron/process-profits.php
```

**Cron Job 2 — Complete Investments (daily at 2 AM)**
```
0 2 * * * /usr/bin/php /home/cpanel_user/public_html/src/api/cron/complete-investments.php
```

**Cron Job 3 — Cleanup (daily at 3 AM)**
```
0 3 * * * /usr/bin/php /home/cpanel_user/public_html/src/api/cron/cleanup.php
```

### 6.2 Cron Log Monitoring
- [ ] Check cron logs in cPanel after 24 hours
- [ ] Verify no errors in output
- [ ] Monitor database for successful profit processing

---

## 7. EMAIL & NOTIFICATION SETUP

### 7.1 SMTP Configuration
- [ ] SMTP credentials working (test via `includes/mail.php`)
- [ ] `MAIL_FROM_EMAIL` is valid mailbox
- [ ] Password reset emails send correctly
- [ ] Deposit/withdrawal notifications working

**Test email send (SSH):**
```bash
php -r "
require 'includes/config.php';
require 'includes/mail.php';
\$result = Mail::sendPasswordReset('test@example.com', 'Test Token URL');
var_dump(\$result);
"
```

### 7.2 Alternative: SendGrid/Mailgun
- [ ] If using SendGrid/Mailgun, credentials stored in .env
- [ ] SMTP host and port configured correctly
- [ ] Test email delivery before going live

---

## 8. SECURITY HARDENING

### 8.1 HTTPS & SSL
- [ ] SSL certificate installed (Let's Encrypt via cPanel)
- [ ] Force HTTPS enabled in .htaccess
- [ ] Browser shows secure lock icon
- [ ] Mixed content warnings resolved

### 8.2 Security Headers
- [ ] X-Content-Type-Options: nosniff
- [ ] X-Frame-Options: SAMEORIGIN (already in .htaccess)
- [ ] X-XSS-Protection enabled
- [ ] Referrer-Policy set

**Verify via browser DevTools → Network → Response Headers**

### 8.3 PHP Configuration
- [ ] PHP version 8.0+ confirmed in cPanel
- [ ] `display_errors = Off` in php.ini (production safety)
- [ ] `log_errors = On` in php.ini (log to file)
- [ ] `memory_limit` sufficient (128MB minimum)
- [ ] `max_execution_time` sufficient (30 seconds minimum)
- [ ] `upload_max_filesize` sufficient (10MB for profile pictures)

**Check in cPanel → Select PHP Version → Options**

### 8.4 Database Security
- [ ] Database user has minimal required privileges
- [ ] Database password is strong (20+ chars, mixed case, numbers, symbols)
- [ ] Remote database access disabled if possible (local connection only)
- [ ] Regular backups configured

---

## 9. TESTING & VALIDATION

### 9.1 Functionality Tests
- [ ] Visit homepage (https://primeaxisinv.com) → No errors
- [ ] User registration works → Email sent, account created
- [ ] User login works → Session created, dashboard loads
- [ ] Password reset works → Email sent, token valid
- [ ] Admin login works → Admin dashboard loads
- [ ] Deposit creation works → Request marked pending
- [ ] Withdrawal creation works → Request marked pending
- [ ] Investment creation works → Amount deducted, investment created
- [ ] Transactions load correctly → History visible
- [ ] Referral system works → Link shares, referrals tracked

### 9.2 Performance Tests
- [ ] Homepage loads in < 3 seconds
- [ ] Dashboard loads in < 2 seconds
- [ ] Admin dashboard loads data via API (no console errors)
- [ ] Pagination works (load 5+ pages of transactions)
- [ ] Search filters work (admin user search)

### 9.3 Error Handling
- [ ] Invalid login shows appropriate error message (not SQL error)
- [ ] Invalid email in password reset shows generic message
- [ ] API errors return proper JSON format
- [ ] 404 pages don't expose server paths
- [ ] No unhandled PHP warnings/errors in logs

### 9.4 Security Tests
- [ ] Cannot access `config/` directory via browser → 403/404
- [ ] Cannot access `.env` file via browser
- [ ] Cannot access `includes/` directory via browser
- [ ] SQL injection attempts blocked → Prepared statements working
- [ ] XSS attempts blocked → HTML sanitization working

**Try in browser:**
```
https://primeaxisinv.com/config/
https://primeaxisinv.com/.env
https://primeaxisinv.com/includes/
https://primeaxisinv.com/api/admin/users.php?id=1' OR '1'='1
```

---

## 10. MONITORING & LOGGING

### 10.1 Log Setup
- [ ] `logs/` directory created and writable (777)
- [ ] Error logging configured in php.ini
- [ ] Logs not publicly accessible (check .htaccess)
- [ ] Monitor logs daily for errors: `tail -f logs/*.log`

### 10.2 Backup Strategy
- [ ] Create database backup before going live
- [ ] Setup automated daily backups via cPanel → Backup
- [ ] Test restore process to verify backups work
- [ ] Store offsite backup copies (AWS S3, DropBox, etc.)

**Manual backup (SSH):**
```bash
bash scripts/backup-database.sh
```

### 10.3 Health Checks
- [ ] Setup monitoring (optional: UptimeRobot, Pingdom)
- [ ] Monitor cron job execution (check logs daily)
- [ ] Monitor database size growth (should be predictable)
- [ ] Monitor user registrations (watch for spam)

---

## 11. GITHUB ACTIONS SETUP (Optional - For Automated Deployment)

### 11.1 GitHub Secrets Configuration
Add these secrets to your GitHub repository (Settings → Secrets and variables → Actions):

- `FTP_SERVER` — FTP hostname (ftp.your-server.com)
- `FTP_USERNAME` — FTP username
- `FTP_PASSWORD` — FTP password
- `SSH_HOST` — SSH hostname (your-server.com)
- `SSH_USERNAME` — SSH username
- `SSH_PRIVATE_KEY` — SSH private key (use ssh-keygen to generate)
- `SLACK_WEBHOOK` — (Optional) Slack webhook for deployment notifications

### 11.2 Deploy Workflow
- [x] `.github/workflows/deploy-to-cpanel.yml` file created
- [ ] Secrets configured in GitHub
- [ ] Test workflow on test branch first
- [ ] Monitor first deployment to main branch

**To trigger deployment:**
```bash
git push origin main
# GitHub Actions will automatically deploy
```

---

## 12. DOMAIN & DNS

- [ ] Domain registered (primeaxisinv.com)
- [ ] Domain pointing to cPanel IP (A record)
- [ ] DNS propagated (check via whatsmydns.net)
- [ ] www and non-www both resolve (add www CNAME record if needed)
- [ ] SSL certificate installed for domain

---

## 13. FINAL LAUNCH CHECKLIST

### Before Going Live:
- [ ] All items above completed
- [ ] Database tested and populated with test data
- [ ] Admin account created
- [ ] Test user created and tested
- [ ] Cron jobs verified running
- [ ] Email notifications tested
- [ ] Backups configured and tested
- [ ] Monitoring set up
- [ ] Team trained on admin operations

### After Going Live:
- [ ] Monitor error logs closely (first 24 hours)
- [ ] Check cron job execution (verify profits processed)
- [ ] Test live deposit/withdrawal flow
- [ ] Verify email notifications reach inbox (not spam)
- [ ] Monitor database size and performance
- [ ] Have rollback plan ready in case of issues

---

## 14. ROLLBACK PROCEDURE (If Issues Occur)

```bash
# Via SSH - Restore previous backup
cd ~/public_html
git revert HEAD~1
# Or restore from backup:
bash scripts/restore-database.sh backups/backup-2026-05-15.sql.gz
```

---

## QUICK REFERENCE: COMMANDS

**SSH into cPanel:**
```bash
ssh cpanel_user@your-server.com
cd ~/public_html
```

**Deploy manually:**
```bash
bash scripts/deploy.sh ftp.server.com ftpuser ftppass
```

**Run migrations:**
```bash
mysql -u primeaxis_user -p primeaxis_prod < database/schema.sql
```

**Seed data:**
```bash
mysql -u primeaxis_user -p primeaxis_prod < database/seeders/plans.sql
mysql -u primeaxis_user -p primeaxis_prod < database/seeders/settings.sql
```

**Create admin:**
```bash
bash scripts/create-admin.sh
```

**View logs:**
```bash
tail -f logs/*.log
```

**Backup database:**
```bash
bash scripts/backup-database.sh
```

---

**Checklist Version:** 1.0  
**Date:** May 15, 2026  
**Target Deployment:** Phase 9 (Automated Deployment)
