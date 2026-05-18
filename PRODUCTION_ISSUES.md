# PRODUCTION ISSUES & FIXES TRACKER

**Status:** Live Deployment (May 18, 2026)  
**Current Build:** Phase 8 Complete, Site Online  
**Priority:** Fix critical issues before Phase 10 launch

---

## EXECUTIVE SUMMARY

The Primeaxis Investment Platform is now **LIVE in production**. This document tracks:
- 🔴 **Critical Issues** (must fix before stability)
- 🟡 **High Priority** (significant functionality gaps)
- 🟢 **Medium Priority** (nice-to-have features)
- 🔵 **Low Priority** (future enhancements)

**Total Issues:** 45+  
**Critical:** 8  
**High:** 12  
**Medium:** 15  
**Low:** 10+

---

## 🔴 CRITICAL ISSUES (FIX IMMEDIATELY)

### Issue #1: Admin Password Change Broken
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** Admin cannot change password via `/api/admin/change-password.php`

**Root Cause:** 
- Endpoint doesn't exist (not created in Phase 6)
- Admin session uses different table (`admin_users` vs `users`)

**Fix:**
1. Create `/api/admin/change-password.php`
2. Use `changeAdminPassword()` from `includes/auth.php` (if exists, or create it)
3. Add admin page UI: `/admin/change-password.php`

**Estimated Time:** 1-2 hours  
**Dependencies:** None  
**Priority:** 🔴 CRITICAL

---

### Issue #2: User Dashboard API Returns Errors
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** Dashboard doesn't load, JavaScript console shows 404 or 500 errors

**Root Cause:**
- One of the dashboard API endpoints failing
- Likely `/api/user/dashboard.php` or referrals endpoint

**Fix:**
1. Check `/logs/` for PHP errors
2. Test each API endpoint individually:
   ```bash
   curl https://yourdomain.com/api/user/dashboard.php
   curl https://yourdomain.com/api/user/profile.php
   curl https://yourdomain.com/api/investments/plans.php
   ```
3. Fix the failing endpoint

**Estimated Time:** 30 min - 1 hour  
**Dependencies:** Database must be connected  
**Priority:** 🔴 CRITICAL

---

### Issue #3: Email Notifications Not Sending
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** Password reset emails, deposit confirmation emails not reaching users

**Root Cause:**
- SMTP credentials not configured correctly
- Mail server blocked or port closed

**Fix:**
1. Verify `.env` has correct SMTP settings
2. Test email manually via SSH:
   ```bash
   php -r "
   require 'includes/config.php';
   require 'includes/mail.php';
   \$result = Mail::sendPasswordReset('test@example.com', 'https://...');
   var_dump(\$result);
   "
   ```
3. If fails, update MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD in `.env`
4. Restart PHP-FPM: `systemctl restart php8.0-fpm` (via SSH)

**Estimated Time:** 30 min  
**Dependencies:** Email service (Gmail, SendGrid, etc.)  
**Priority:** 🔴 CRITICAL

---

### Issue #4: Admin User Management Pages Show No Data
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** `/admin/users.php` loads but table is empty, no pagination works

**Root Cause:**
- `/api/admin/users.php` endpoint not working
- Likely incorrect table joins or missing admin session validation

**Fix:**
1. Debug `/api/admin/users.php`:
   ```bash
   curl -H "Cookie: PHPSESSID=..." https://yourdomain.com/api/admin/users.php
   ```
2. Check logs for SQL errors
3. Verify `users` table has data (check via phpMyAdmin)
4. Fix endpoint if query is broken

**Estimated Time:** 1-2 hours  
**Dependencies:** Database, admin session  
**Priority:** 🔴 CRITICAL

---

### Issue #5: Login/Registration Form Validation Not Working
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** Invalid emails accepted, weak passwords allowed, no error messages shown

**Root Cause:**
- JavaScript validation not running
- `app.js` not loaded or `setupInputValidation()` not called

**Fix:**
1. Check if `assets/js/app.js` linked in HTML (check page source)
2. Verify JavaScript console for errors (press F12)
3. Add to page head if missing:
   ```html
   <script src="/assets/js/app.js"></script>
   ```
4. Test validation on `/register.php`

**Estimated Time:** 30 min  
**Dependencies:** None  
**Priority:** 🔴 CRITICAL

---

### Issue #6: Investment Creation Shows No Error Messages
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** User clicks "Invest" but nothing happens, no feedback

**Root Cause:**
- `/api/investments/create.php` failing silently
- Error not returned to JavaScript
- `dashboard.js` not handling API errors properly

**Fix:**
1. Test endpoint manually:
   ```bash
   curl -X POST https://yourdomain.com/api/investments/create.php \
     -d "plan_id=1&amount=100" \
     -H "Cookie: PHPSESSID=..."
   ```
2. Check response (should be JSON with success: true/false)
3. If endpoint broken, fix SQL or validation
4. Update `dashboard.js` to show error alerts on failure

**Estimated Time:** 1-2 hours  
**Dependencies:** Database, user session  
**Priority:** 🔴 CRITICAL

---

### Issue #7: Cron Jobs Not Running (No Profits Processed)
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** 24 hours passed, no profits credited to users

**Root Cause:**
- Cron jobs not configured in cPanel
- OR cron jobs failing silently
- Cron PHP path incorrect

**Fix:**
1. Check cPanel → Advanced → Cron Jobs:
   - Verify 3 jobs exist (process-profits, complete-investments, cleanup)
   - Check "Last Run" and "Last Output" columns
2. If missing, add them (see MANUAL_CPANEL_DEPLOYMENT.md section 10)
3. If failing, check output for errors
4. Correct PHP path if needed (might be `/usr/bin/php7.4` not `/usr/bin/php`)

**Estimated Time:** 30 min  
**Dependencies:** cPanel access  
**Priority:** 🔴 CRITICAL

---

### Issue #8: 404 Errors on Some Pages
**Status:** 🔴 CRITICAL  
**Reported:** May 18, 2026  
**Description:** Some pages 404, others work fine

**Root Cause:**
- `.htaccess` not working or not uploaded
- Apache mod_rewrite not enabled
- Wrong DocumentRoot configured

**Fix:**
1. Verify `.htaccess` exists in `/public_html/` via File Manager
2. If missing, upload it (from `src/.htaccess`)
3. If exists, check cPanel → Select PHP Version → Apache Modules:
   - Verify `rewrite_module` (mod_rewrite) is checked
4. If still broken, check cPanel error logs: `/var/log/apache2/error_log`

**Estimated Time:** 30 min  
**Dependencies:** .htaccess file, Apache configuration  
**Priority:** 🔴 CRITICAL

---

## 🟡 HIGH PRIORITY ISSUES (Fix This Week)

### Issue #9: Admin Password Change Feature Missing
**Status:** 🟡 HIGH  
**Description:** Admin page doesn't have "Change Password" option

**Fix:**
- Create `/admin/settings.php` with password change form
- Create `/api/admin/change-password.php` endpoint
- Test authentication before allowing change

**Estimated Time:** 2 hours

---

### Issue #10: User Profile Picture Upload Not Working
**Status:** 🟡 HIGH  
**Description:** `/dashboard/profile.php` has upload button but files don't save

**Root Cause:**
- `/api/user/update-profile.php` doesn't handle file uploads
- `assets/uploads/profile_pictures/` not writable

**Fix:**
1. Update `/api/user/update-profile.php` to handle `$_FILES['profile_picture']`
2. Verify `assets/uploads/` is writable (777 permissions)
3. Add file validation (size, type)
4. Return file URL in response

**Estimated Time:** 2-3 hours

---

### Issue #11: Referral System Not Tracking
**Status:** 🟡 HIGH  
**Description:** Users create accounts with referral codes, but referrals not credited

**Root Cause:**
- `registerUser()` function doesn't credit referrer
- Commission calculation not done during registration

**Fix:**
1. Update `registerUser()` in `includes/auth.php` to:
   - Find referrer by referral_code
   - If valid, credit commission to referrer
   - Create referral relationship in database
2. Verify `referrals` table has correct schema

**Estimated Time:** 2-3 hours

---

### Issue #12: Admin Can't Update Investment Plans
**Status:** 🟡 HIGH  
**Description:** `/admin/plans.php` shows plans but "Edit" button doesn't work

**Root Cause:**
- No plan edit endpoint exists
- Plan update UI not implemented

**Fix:**
1. Create `/api/admin/update-plan.php` endpoint
2. Add edit form modal to `/admin/plans.php`
3. Test plan update (name, duration, ROI, min amount)

**Estimated Time:** 2-3 hours

---

### Issue #13: Deposit/Withdrawal History Shows Empty
**Status:** 🟡 HIGH  
**Description:** `/dashboard/deposits.php` and `/dashboard/withdrawals.php` show no records

**Root Cause:**
- `/api/deposits/list.php` or `/api/withdrawals/list.php` not returning data
- Missing user filter in SQL query

**Fix:**
1. Debug endpoints to ensure they filter by current user
2. Test SQL query: `SELECT * FROM deposits WHERE user_id = ?`
3. Verify data exists in database via phpMyAdmin

**Estimated Time:** 1-2 hours

---

### Issue #14: Admin Dashboard Stats Show Zeros
**Status:** 🟡 HIGH  
**Description:** Admin dashboard shows "0" for all metrics

**Root Cause:**
- `/api/admin/dashboard.php` not calculating stats correctly
- Joins failing, returns empty data

**Fix:**
1. Debug `/api/admin/dashboard.php`
2. Test individual SQL queries:
   ```sql
   SELECT COUNT(*) as total FROM users WHERE status = 'active';
   SELECT SUM(amount) as pending FROM deposits WHERE status = 'pending';
   ```
3. Fix queries if broken

**Estimated Time:** 1-2 hours

---

### Issue #15: Search Function Not Working
**Status:** 🟡 HIGH  
**Description:** Admin search for users returns no results

**Root Cause:**
- `/api/admin/users.php` doesn't handle search parameter
- Missing `WHERE name LIKE ?` clause

**Fix:**
1. Update `/api/admin/users.php` to handle `?search=` parameter
2. Add `WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?`
3. Test search with partial matches

**Estimated Time:** 1 hour

---

### Issue #16: Pagination Not Working on Admin Pages
**Status:** 🟡 HIGH  
**Description:** "Next" and "Previous" buttons don't navigate between pages

**Root Cause:**
- JavaScript pagination callback not working
- API doesn't return pagination metadata properly

**Fix:**
1. Verify API response includes `pagination` object with `current_page`, `total_pages`
2. Test `/api/admin/users.php?page=2`
3. Fix JavaScript pagination in `admin.js`

**Estimated Time:** 1-2 hours

---

### Issue #17: Admin Can't Approve/Reject Deposits
**Status:** 🟡 HIGH  
**Description:** Approve/Reject buttons on `/admin/deposits.php` don't work

**Root Cause:**
- `/api/admin/approve-deposit.php` or `/api/admin/reject-deposit.php` endpoints failing
- Missing admin session validation

**Fix:**
1. Debug endpoints with curl
2. Check logs for errors
3. Verify endpoints exist and are called correctly from JavaScript

**Estimated Time:** 1-2 hours

---

### Issue #18: Mobile Responsive Design Broken
**Status:** 🟡 HIGH  
**Description:** Site looks awful on mobile (text overlapping, buttons unclickable)

**Root Cause:**
- Tailwind CSS viewport meta tag missing or misconfigured
- No media queries in custom CSS

**Fix:**
1. Verify `<meta name="viewport" content="width=device-width, initial-scale=1.0">` in all pages
2. Test on mobile browser (or DevTools mobile view)
3. Adjust CSS for smaller screens (add media queries to `app.css`)

**Estimated Time:** 2-3 hours

---

### Issue #19: Session Timeout Not Working
**Status:** 🟡 HIGH  
**Description:** Users stay logged in indefinitely, should timeout after 1 hour

**Root Cause:**
- `SESSION_TIMEOUT` constant not enforced
- Session validation not checking activity time

**Fix:**
1. Update `includes/session.php` to check last activity time
2. If `time() - $_SESSION['last_activity'] > SESSION_TIMEOUT`, logout user
3. Update `last_activity` on every page load

**Estimated Time:** 1-2 hours

---

### Issue #20: HTTPS/SSL Mixed Content Warnings
**Status:** 🟡 HIGH  
**Description:** Browser console shows "Mixed Content" warnings, CSS/JS may not load

**Root Cause:**
- Some assets loaded via http:// instead of https://
- SITE_URL configured with http:// instead of https://

**Fix:**
1. Update `.env`: `SITE_URL=https://yourdomain.com`
2. Update all hardcoded URLs to use SITE_URL constant
3. Verify `.htaccess` redirects http → https

**Estimated Time:** 1 hour

---

## 🟢 MEDIUM PRIORITY (This Month)

### Issue #21: Rate Limiting Not Implemented
**Status:** 🟢 MEDIUM  
**Description:** Users can brute-force login attempts, spam API endpoints

**Fix:** Implement rate limiting in `includes/response.php`

**Estimated Time:** 3-4 hours

---

### Issue #22: CSRF Protection Missing
**Status:** 🟢 MEDIUM  
**Description:** Forms vulnerable to CSRF attacks

**Fix:** 
1. Generate CSRF tokens in session
2. Validate on form submission
3. Add to all POST endpoints

**Estimated Time:** 3-4 hours

---

### Issue #23: User Export Feature Missing
**Status:** 🟢 MEDIUM  
**Description:** Admin can't export user list to CSV

**Fix:** Create `/api/admin/export-users.php` that returns CSV

**Estimated Time:** 2 hours

---

### Issue #24: Investment Calculator Not Visible
**Status:** 🟢 MEDIUM  
**Description:** Users can't calculate expected returns before investing

**Fix:** Add calculator widget to investment pages

**Estimated Time:** 2 hours

---

### Issue #25: Admin Audit Logs Not Stored
**Status:** 🟢 MEDIUM  
**Description:** No record of who approved/rejected deposits, changed settings

**Fix:** Implement audit logging in admin endpoints

**Estimated Time:** 2-3 hours

---

### Issue #26: Bank Transfer Instructions Missing
**Status:** 🟢 MEDIUM  
**Description:** Deposit page doesn't show bank account details for transfers

**Fix:** Add payment method details to settings, display on deposit page

**Estimated Time:** 1-2 hours

---

### Issue #27: Email Template Customization
**Status:** 🟢 MEDIUM  
**Description:** All emails use plain text, should have HTML templates

**Fix:** Create HTML email templates in `includes/mail.php`

**Estimated Time:** 2-3 hours

---

### Issue #28: Two-Factor Authentication Missing
**Status:** 🟢 MEDIUM  
**Description:** Admin accounts vulnerable, should require 2FA

**Fix:** Implement TOTP (Time-based One-Time Password) for admin login

**Estimated Time:** 4-5 hours

---

### Issue #29: Dashboard Charts Not Rendering
**Status:** 🟢 MEDIUM  
**Description:** Admin dashboard shows empty chart container

**Root Cause:** No charting library (Chart.js, ApexCharts) included

**Fix:**
1. Add Chart.js CDN to admin template
2. Create chart visualization in `admin.js`
3. Show investment breakdown, profit trends

**Estimated Time:** 2-3 hours

---

### Issue #30: User Notifications Not Displayed
**Status:** 🟢 MEDIUM  
**Description:** Users don't see in-app notifications for deposits, withdrawals, profits

**Fix:** Implement notification system in `/api/user/notifications.php`

**Estimated Time:** 2-3 hours

---

---

## 🔵 LOW PRIORITY (Future Enhancements)

### Issue #31-45: Low Priority Features

- [ ] Multi-language support (i18n)
- [ ] Dark mode theme toggle
- [ ] User dashboard customization
- [ ] Transaction filters/date range
- [ ] Investment early withdrawal option
- [ ] API documentation/Swagger
- [ ] Admin analytics dashboard
- [ ] User KYC verification system
- [ ] Referral bonus auto-payout
- [ ] SMS notifications
- [ ] Mobile app native version
- [ ] Performance optimization (caching, CDN)
- [ ] Database query optimization
- [ ] Advanced fraud detection
- [ ] Multi-admin role support

**Estimated Total Time:** 20+ hours

---

## TRACKING SPREADSHEET

| Issue # | Title | Status | Priority | Est. Time | Assigned To | Due Date | Notes |
|---------|-------|--------|----------|-----------|------------|----------|-------|
| #1 | Admin Password Change | 🔴 | CRITICAL | 1-2h | Claude | May 18 | Missing endpoint |
| #2 | Dashboard API Errors | 🔴 | CRITICAL | 30m-1h | Debug | May 18 | Check logs |
| #3 | Email Not Sending | 🔴 | CRITICAL | 30m | Claude | May 18 | SMTP config |
| #4 | Admin Users Page Empty | 🔴 | CRITICAL | 1-2h | Claude | May 18 | Endpoint broken |
| #5 | Form Validation Missing | 🔴 | CRITICAL | 30m | Claude | May 18 | JS not loaded |
| #6 | Investment Errors Silent | 🔴 | CRITICAL | 1-2h | Claude | May 18 | Error handling |
| #7 | Cron Jobs Not Running | 🔴 | CRITICAL | 30m | Admin | May 18 | Check cPanel |
| #8 | 404 Errors on Pages | 🔴 | CRITICAL | 30m | Admin | May 18 | .htaccess |
| #9 | Admin Settings Page | 🟡 | HIGH | 2h | Claude | May 19 | New feature |
| #10 | Profile Picture Upload | 🟡 | HIGH | 2-3h | Claude | May 20 | File handling |
| #11 | Referral System | 🟡 | HIGH | 2-3h | Claude | May 21 | Tracking |
| #12 | Plan Editing | 🟡 | HIGH | 2-3h | Claude | May 22 | Admin feature |
| #13 | Deposit/Withdrawal History | 🟡 | HIGH | 1-2h | Claude | May 23 | API debugging |
| #14 | Admin Stats | 🟡 | HIGH | 1-2h | Claude | May 24 | Dashboard |
| #15 | Search Function | 🟡 | HIGH | 1h | Claude | May 25 | Admin feature |
| #16 | Pagination | 🟡 | HIGH | 1-2h | Claude | May 26 | Navigation |
| #17 | Approve/Reject | 🟡 | HIGH | 1-2h | Claude | May 27 | Admin workflows |
| #18 | Mobile Responsive | 🟡 | HIGH | 2-3h | Claude | May 28 | CSS |
| #19 | Session Timeout | 🟡 | HIGH | 1-2h | Claude | May 29 | Security |
| #20 | SSL/HTTPS | 🟡 | HIGH | 1h | Admin | May 30 | Config |

---

## CUMULATIVE WORK ESTIMATE

| Priority | Count | Est. Time | Person |
|----------|-------|-----------|--------|
| 🔴 CRITICAL | 8 | 8-12 hours | Claude (dev) + Admin (ops) |
| 🟡 HIGH | 12 | 18-24 hours | Claude |
| 🟢 MEDIUM | 15 | 30-40 hours | Claude (future sprints) |
| 🔵 LOW | 10+ | 20+ hours | Team (backlog) |
| **TOTAL** | **45+** | **76-96 hours** | **1-2 weeks for critical+high** |

---

## IMMEDIATE ACTION PLAN (Next 48 Hours)

### Thursday (May 18) - Critical Issues
- [ ] Fix admin password change (Issue #1)
- [ ] Debug dashboard API (Issue #2)
- [ ] Test email sending (Issue #3)
- [ ] Fix admin users page (Issue #4)
- **Time:** 6-8 hours

### Friday (May 19) - Critical + High Priority Start
- [ ] Fix form validation (Issue #5)
- [ ] Fix investment creation errors (Issue #6)
- [ ] Setup cron jobs (Issue #7)
- [ ] Upload .htaccess (Issue #8)
- [ ] Create admin password change UI (Issue #9)
- **Time:** 6-8 hours

### Next Week - Finish High Priority
- [ ] Profile picture upload (Issue #10)
- [ ] Referral system (Issue #11)
- [ ] Plan editing (Issue #12)
- [ ] Deposit/withdrawal history (Issue #13-14)
- [ ] Admin workflows (Issue #15-17)
- [ ] Mobile responsive (Issue #18)
- [ ] Session timeout (Issue #19)
- **Time:** 20-24 hours

---

## PROGRESS TRACKING

**Last Updated:** May 18, 2026  
**Issues Identified:** 45+  
**Issues In Progress:** 0  
**Issues Completed:** 0  
**Blocker Issues:** 8 (CRITICAL)

---

## HOW TO USE THIS DOCUMENT

1. **Daily Standup:** Check "Immediate Action Plan" section
2. **Fix an Issue:** Update Status column (🔴 → 🟡 → 🟢 → ✅)
3. **Report New Issue:** Add to appropriate section with Priority
4. **Weekly Review:** Count completed issues, estimate remaining time
5. **Sprint Planning:** Pick 5-8 issues for the week from HIGH priority

---

## NOTES FOR DEVELOPER (Claude)

Each issue should be tackled with:
1. **Understand** — Read the issue description
2. **Diagnose** — Check logs, test endpoint, reproduce locally
3. **Fix** — Write code or config change
4. **Test** — Verify fix works
5. **Document** — Update this file with status

---

**Created:** May 18, 2026  
**Version:** 1.0  
**Next Review:** May 20, 2026  
**Target:** All CRITICAL issues fixed by May 20, All HIGH by May 25
