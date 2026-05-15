# MANUAL CPANEL DEPLOYMENT — STEP-BY-STEP GUIDE

**Purpose:** Deploy the Primeaxis Investment Platform to cPanel manually

**Time Required:** 30-60 minutes (depending on upload speed)

**Recommended for:** First-time deployment, or if you prefer not to use GitHub Actions

---

## PREREQUISITES CHECKLIST

Before starting, make sure you have:

- [ ] cPanel username and password
- [ ] FTP credentials (username & password) OR SSH access
- [ ] MySQL database credentials
- [ ] Domain name configured in cPanel
- [ ] This repository downloaded as ZIP or access to Git

---

## PART 1: PREPARE YOUR LOCAL FILES

### Option A: Download Repository as ZIP

1. Go to: `https://github.com/your-username/investment_site`
2. Click **Code** (green button)
3. Click **Download ZIP**
4. Extract ZIP file to a folder on your computer

### Option B: Clone Repository

```bash
git clone https://github.com/your-username/investment_site.git
cd investment_site
```

---

## PART 2: LOGIN TO cPANEL

### Step 1: Open cPanel

Go to: `https://yourdomain.com:2083/`

Or find the link in your hosting provider's welcome email.

### Step 2: Login

- **Username:** Your cPanel username
- **Password:** Your cPanel password
- Click **Login**

---

## PART 3: CREATE DATABASE

### Step 1: Go to MySQL Databases

In cPanel, find: **Databases → MySQL Databases**

### Step 2: Create New Database

**Database Name:** `primeaxis_prod` (or `username_primeaxis`)

Note the prefix — cPanel usually adds your username, so it might become:
```
cpanel_username_primeaxis_prod
```

Click **Create Database**

### Step 3: Create Database User

In the same section, look for **MySQL Users**

**Username:** `primeaxis_user` (or `username_primeaxis_user`)

**Password:** Enter a STRONG password (20+ chars, mixed case, numbers, symbols)

**Confirm Password:** Repeat the password

Click **Create User**

### Step 4: Grant Privileges

You should see a section: **Add User to Database**

- Select your new user: `primeaxis_user`
- Select your new database: `primeaxis_prod`
- Click **Add**

A popup will appear asking for privileges. Click:
- **ALL PRIVILEGES**

Click **Make Changes**

### Note Down These Credentials:

```
Database Name: cpanel_username_primeaxis_prod
Database User: cpanel_username_primeaxis_user
Database Password: [the strong password you entered]
Database Host: localhost
```

You'll need these in a moment.

---

## PART 4: UPLOAD FILES VIA FTP OR FILE MANAGER

### Method A: Using cPanel File Manager (Easier, No Software Needed)

#### Step 1: Open File Manager

In cPanel: **Files → File Manager**

#### Step 2: Navigate to public_html

Left sidebar should show: `/public_html`

Click on it. This is where your website files go.

#### Step 3: Upload Files

You need to upload the contents of the `src/` folder from your downloaded/cloned repository.

**Option 1: Upload Individual Files**
- Click **Upload**
- Select all files from `src/` folder
- Upload

**Option 2: Upload as Archive**
- Go back to your computer
- Zip the entire `src/` folder
- In File Manager, click **Upload**
- Upload the ZIP file
- Extract it
- Delete the ZIP file

#### Step 4: Verify Upload

After upload, navigate to `/public_html/` and you should see:
```
public_html/
├── index.php
├── login.php
├── register.php
├── api/
├── dashboard/
├── admin/
├── assets/
├── includes/
└── .htaccess
```

✅ All these files should be present.

---

### Method B: Using FTP Client (For Experienced Users)

If you prefer FTP:

1. Get FTP credentials from cPanel (**Accounts → FTP Accounts**)
2. Use FileZilla, WinSCP, or your favorite FTP client
3. Connect to your FTP server
4. Navigate to `/public_html/`
5. Drag and drop `src/` folder contents
6. Upload

---

## PART 5: CREATE PRODUCTION CONFIGURATION FILES

### Step 1: Create .env File

Via File Manager in cPanel:

1. In `/public_html/` folder (or the folder above public_html)
2. Right-click → **Create New File**
3. Name it: `.env` (starts with a dot)
4. Open it for editing
5. Paste this content:

```env
# Database
DB_HOST=localhost
DB_USER=cpanel_username_primeaxis_user
DB_PASS=YOUR_STRONG_PASSWORD_HERE
DB_NAME=cpanel_username_primeaxis_prod

# Site
SITE_URL=https://yourdomain.com
SITE_NAME=Primeaxis Investment
SITE_TIMEZONE=UTC

# Admin Email
ADMIN_EMAIL=admin@yourdomain.com

# Mail (SMTP) - Use your email provider
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_EMAIL=noreply@yourdomain.com
MAIL_FROM_NAME=Primeaxis Investment

# Security
JWT_SECRET=GENERATE_A_RANDOM_STRING_HERE
SESSION_TIMEOUT=3600
PASSWORD_RESET_TIMEOUT=1800

# Payment Gateway (Optional)
PAYMENT_GATEWAY=paystack
PAYSTACK_PUBLIC_KEY=pk_live_xxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxx
```

**Replace these with your actual values:**
- `cpanel_username_primeaxis_user` → your DB user
- `YOUR_STRONG_PASSWORD_HERE` → your DB password
- `cpanel_username_primeaxis_prod` → your DB name
- `yourdomain.com` → your actual domain
- `admin@yourdomain.com` → admin email
- `smtp.gmail.com`, `your-email@gmail.com` → your email service
- `GENERATE_A_RANDOM_STRING_HERE` → any random string (or use online UUID generator)

Click **Save**

### Step 2: Create config.php in includes Folder

1. Navigate to: `/public_html/includes/`
2. Right-click → **Create New File**
3. Name it: `config.php`
4. Copy contents from `config.example.php` (you can view it on GitHub)
5. The file will auto-load from `.env` (no changes needed)

**Note:** If `.env` exists, `config.php` will read from it automatically. You typically don't need to edit `config.php`.

---

## PART 6: SET FILE PERMISSIONS

### Via File Manager:

This is important for security and functionality!

#### Step 1: Select All Files

In `/public_html/`:
- Click **Select All** (if option exists)
- OR select important folders

#### Step 2: Change Permissions

Right-click selected files → **Change Permissions**

**Set these permissions:**

| Path | Permissions | Note |
|------|-------------|------|
| `/public_html/` (all) | 755 | Standard for directories |
| `assets/uploads/` | 777 | Writable for user uploads |
| `logs/` | 777 | Writable for log files |
| `config/` | 777 | Writable for config updates |
| `.php` files | 644 | Standard for files |

In the dialog:
- **Owner:** Read, Write, Execute (7)
- **Group:** Read, Execute (5)
- **Others:** Read, Execute (5)
- Click **Change**

Repeat for `assets/uploads/`, `logs/`, `config/` (set to 777).

---

## PART 7: RUN DATABASE MIGRATIONS

### Via SSH (Recommended):

1. In cPanel: **Terminal** (or use PuTTY if on Windows)

2. Connect:
   ```bash
   ssh cpanel_username@yourdomain.com
   ```

3. Navigate to public_html:
   ```bash
   cd ~/public_html
   ```

4. Run migrations:
   ```bash
   mysql -u cpanel_username_primeaxis_user -p cpanel_username_primeaxis_prod < database/schema.sql
   ```
   
   When prompted, enter your database password.

5. Seed initial data:
   ```bash
   mysql -u cpanel_username_primeaxis_user -p cpanel_username_primeaxis_prod < database/seeders/plans.sql
   mysql -u cpanel_username_primeaxis_user -p cpanel_username_primeaxis_prod < database/seeders/settings.sql
   ```

### Via File Manager (If No SSH):

1. In cPanel: **Databases → phpMyAdmin**
2. Select your database: `cpanel_username_primeaxis_prod`
3. Click **Import**
4. Upload: `database/schema.sql`
5. Click **Import**
6. Repeat for `database/seeders/plans.sql` and `database/seeders/settings.sql`

---

## PART 8: CREATE ADMIN USER

### Via SSH:

```bash
cd ~/public_html
bash scripts/create-admin.sh
```

Follow the prompts:
- **Username:** (choose one, e.g., `admin`)
- **Password:** (enter strong password)
- **Email:** (admin email)

### Via Manual SQL (If Script Doesn't Work):

In phpMyAdmin or SSH MySQL client:

```sql
INSERT INTO admin_users (username, email, password_hash, status)
VALUES (
  'admin',
  'admin@yourdomain.com',
  '$2y$10$YOUR_BCRYPT_HASH_HERE',
  'active'
);
```

To generate bcrypt hash, use: https://bcrypt-generator.com/

---

## PART 9: TEST THE SITE

### Step 1: Visit Homepage

Open browser:
```
https://yourdomain.com
```

You should see the Primeaxis homepage with:
- Navigation bar
- Welcome text
- Get Started & Sign In buttons

If you see errors or blank page:
- Check error logs: `/logs/` folder
- Verify `.env` file exists and has correct values
- Verify database connected (check `DB_HOST`, `DB_USER`, `DB_PASS`)

### Step 2: Test Registration

1. Click **Register**
2. Fill in form:
   - First Name: Test
   - Last Name: User
   - Email: testuser@example.com
   - Password: TestPassword123!
3. Click **Register**

Expected: Account created, confirmation message

### Step 3: Test Login

1. Click **Login**
2. Enter:
   - Email: testuser@example.com
   - Password: TestPassword123!
3. Click **Login**

Expected: Redirected to dashboard, can see balance stats

### Step 4: Test Admin Login

1. Navigate to: `https://yourdomain.com/admin/`
2. Enter:
   - Username: admin (or what you created)
   - Password: (admin password)
3. Click **Login**

Expected: Admin dashboard with stats

### Step 5: Test API Endpoints

Open browser console (F12) and try:

```javascript
fetch('/api/user/dashboard.php')
  .then(r => r.json())
  .then(d => console.log(d))
```

Should see JSON response with user data.

---

## PART 10: SETUP CRON JOBS (Optional but Recommended)

### In cPanel: Go to Advanced → Cron Jobs

Click **Add New Cron Job**

**Add 3 cron jobs:**

#### Cron Job 1 — Process Profits (Every Hour)

```
Minute: 0
Hour: *
Day: *
Month: *
Weekday: *
Command: /usr/bin/php /home/cpanel_username/public_html/src/api/cron/process-profits.php
```

Click **Add Cron Job**

#### Cron Job 2 — Complete Investments (2 AM Daily)

```
Minute: 0
Hour: 2
Day: *
Month: *
Weekday: *
Command: /usr/bin/php /home/cpanel_username/public_html/src/api/cron/complete-investments.php
```

Click **Add Cron Job**

#### Cron Job 3 — Cleanup (3 AM Daily)

```
Minute: 0
Hour: 3
Day: *
Month: *
Weekday: *
Command: /usr/bin/php /home/cpanel_username/public_html/src/api/cron/cleanup.php
```

Click **Add Cron Job**

### Monitor Cron Jobs:

After 1 hour, check the cron output:
- **Logs:** cPanel → Advanced → Cron Jobs → Check the output column

Look for any errors.

---

## PART 11: MONITOR AND MAINTAIN

### Monitor Logs

In `/logs/` folder via File Manager:
- Check for PHP errors
- Check for database connection issues
- Look for suspicious activity

### Regular Tasks

- **Weekly:** Backup database (cPanel → Backups)
- **Weekly:** Check error logs
- **Monthly:** Review user registrations
- **Monthly:** Verify cron jobs ran successfully
- **Daily (first week):** Check site stability after going live

---

## TROUBLESHOOTING

### Problem: "Connection refused" error

**Solution:**
- Verify database credentials in `.env`
- Verify database host is `localhost`
- Check database user has privileges on database

### Problem: Blank homepage

**Solution:**
- Check `/logs/` folder for PHP errors
- Verify `includes/config.php` exists
- Verify `.env` file exists
- Check file permissions (should be readable)

### Problem: Registration doesn't work

**Solution:**
- Check if database tables created (via phpMyAdmin)
- Verify email configuration (SMTP settings)
- Check logs for SQL errors

### Problem: Admin login fails

**Solution:**
- Verify admin user created (check `admin_users` table in phpMyAdmin)
- Verify password is correct
- Check logs for authentication errors

### Problem: Cron jobs don't run

**Solution:**
- Verify cron command is correct (correct path, correct file)
- Check cron job output in cPanel → Cron Jobs
- Verify PHP executable path: `/usr/bin/php` (may differ on some servers)

---

## CHECKLIST: Before Declaring Live

- [x] Homepage loads without errors
- [x] Registration works, creates user
- [x] Login works, shows dashboard
- [x] Admin login works, shows admin dashboard
- [x] Can create investment
- [x] Can create deposit request
- [x] Logs are clean (no errors)
- [x] Database backups working
- [x] Cron jobs scheduled and running
- [x] SSL certificate active (HTTPS works)
- [x] Email notifications working (password reset email sent)

---

## QUICK REFERENCE

**cPanel Sections Needed:**
- Databases → MySQL Databases (create DB & user)
- Databases → phpMyAdmin (import schema)
- Files → File Manager (upload files)
- Advanced → Cron Jobs (setup cron tasks)
- Terminal (run SSH commands)

**Files You Created:**
- `.env` (in home directory or public_html)
- `config.php` (in `public_html/includes/`)

**Commands (if using SSH):**
```bash
mysql -u user -p database < database/schema.sql
bash scripts/create-admin.sh
bash scripts/backup-database.sh
```

**Important Folders:**
- `/public_html/` — website files
- `/public_html/assets/uploads/` — user uploads (777)
- `/public_html/logs/` — error logs (777)
- `database/` — schema and migration files

---

**Estimated Time:** 30-60 minutes  
**Difficulty:** Intermediate  
**Next Step:** After verifying everything works, setup GitHub Actions for automatic deployments  
**Support:** See DEPLOY_CHECKLIST.md for 67-item verification list
