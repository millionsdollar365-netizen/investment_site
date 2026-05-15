# GITHUB ACTIONS SECRETS SETUP GUIDE

**Purpose:** Configure GitHub to automatically deploy to cPanel on every push to `main` branch

**Time Required:** 10-15 minutes (once you have SSH key & cPanel credentials)

---

## PREREQUISITES

You'll need:
- [ ] GitHub account access to repository
- [ ] cPanel hostname/domain
- [ ] cPanel FTP username & password (or SSH credentials)
- [ ] SSH private key (we'll generate one if you don't have it)

---

## PART 1: GENERATE SSH KEY (If You Don't Have One)

### On Windows PowerShell:

```powershell
# Generate RSA key pair (no passphrase)
ssh-keygen -t rsa -b 4096 -f "$env:USERPROFILE\.ssh\id_rsa" -N ""

# View the private key
type $env:USERPROFILE\.ssh\id_rsa

# View the public key
type $env:USERPROFILE\.ssh\id_rsa.pub
```

### On macOS/Linux Terminal:

```bash
# Generate RSA key pair (no passphrase)
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa -N ""

# View the private key
cat ~/.ssh/id_rsa

# View the public key
cat ~/.ssh/id_rsa.pub
```

**Keep the private key secret! You'll paste it into GitHub in a moment.**

### Alternative: Use cPanel SSH Key Manager

If you prefer to let cPanel generate the key:
1. Login to cPanel
2. Go to **Security → SSH Access Keys**
3. Click **Generate a New Key**
4. Download the private key (.pem or .key file)
5. Use that private key below

---

## PART 2: ADD GITHUB SECRETS

### Step 1: Go to Your GitHub Repository

```
https://github.com/your-username/investment_site
```

### Step 2: Open Settings

Click **Settings** (top menu bar of repository)

### Step 3: Navigate to Secrets

Left sidebar → **Secrets and variables** → **Actions**

### Step 4: Click "New repository secret"

You'll add 6 secrets total (one at a time). Here's the order:

---

## STEP-BY-STEP: Adding Each Secret

### Secret 1: FTP_SERVER

**Name:** `FTP_SERVER`

**Value:** Your FTP hostname (example: `ftp.primeaxisinv.com` or `ftp.your-cpanel-server.com`)

Check cPanel Welcome Email or:
- Login to cPanel → Account Information → Server IP Address or Hostname
- Usually looks like: `ftp.yourdomain.com` or `yourdomain.com`

**Click "Add secret"**

---

### Secret 2: FTP_USERNAME

**Name:** `FTP_USERNAME`

**Value:** Your cPanel username (example: `cpaneluser`)

This is the username you use to login to cPanel.

**Click "Add secret"**

---

### Secret 3: FTP_PASSWORD

**Name:** `FTP_PASSWORD`

**Value:** Your cPanel/FTP password

This is the password you use to login to cPanel.

**Click "Add secret"**

---

### Secret 4: SSH_HOST

**Name:** `SSH_HOST`

**Value:** Your SSH hostname (example: `primeaxisinv.com` or `yourdomain.com`)

Usually the same as your domain name or cPanel hostname.

**Click "Add secret"**

---

### Secret 5: SSH_USERNAME

**Name:** `SSH_USERNAME`

**Value:** Your cPanel username (same as FTP_USERNAME, example: `cpaneluser`)

**Click "Add secret"**

---

### Secret 6: SSH_PRIVATE_KEY

**Name:** `SSH_PRIVATE_KEY`

**Value:** Your SSH private key (entire multi-line key)

**Important:** Copy the ENTIRE private key including:
```
-----BEGIN RSA PRIVATE KEY-----
[many lines of characters]
-----END RSA PRIVATE KEY-----
```

From earlier, you can copy it via:

**PowerShell:**
```powershell
type $env:USERPROFILE\.ssh\id_rsa | Set-Clipboard
```

**macOS/Linux:**
```bash
cat ~/.ssh/id_rsa | pbcopy   # macOS
cat ~/.ssh/id_rsa | xclip -i # Linux
```

Or just open the file in Notepad and copy manually.

Paste the ENTIRE key into the GitHub secret field.

**Click "Add secret"**

---

## (OPTIONAL) Secret 7: SLACK_WEBHOOK

If you want Slack notifications when deployments succeed/fail:

**Name:** `SLACK_WEBHOOK`

**Value:** Your Slack webhook URL (get from Slack API settings)

This is optional. If you skip it, deployments will still work—you just won't get Slack notifications.

---

## VERIFICATION

After adding all 6 secrets, you should see them listed:

```
Repository secrets
├── FTP_PASSWORD
├── FTP_SERVER
├── FTP_USERNAME
├── SSH_HOST
├── SSH_USERNAME
└── SSH_PRIVATE_KEY
```

✅ All green checkmarks = Ready!

---

## TEST THE WORKFLOW

### Option 1: Test on a Test Branch First (RECOMMENDED)

```bash
# Create test branch
git checkout -b test-deploy

# Make a small change
echo "# Test deploy" >> test.txt

# Commit and push
git add .
git commit -m "test deploy"
git push origin test-deploy
```

Go to: **GitHub → Your Repo → Actions**

You should see the workflow running. Click on it to see logs.

**Expected output:**
```
✓ Checkout code
✓ Sync files to cPanel via FTP
✓ SSH: Run migrations & restart services
```

### Option 2: Deploy to Production Directly

Once tested, deploy to main:

```bash
git checkout main
git pull origin main
git merge test-deploy
git push origin main
```

GitHub Actions automatically deploys!

---

## TROUBLESHOOTING

### Workflow fails with "FTP connection refused"

- [ ] Verify FTP credentials are correct
- [ ] Verify FTP server hostname is correct
- [ ] Check if FTP is enabled in cPanel (usually is by default)

### Workflow fails with "SSH authentication failed"

- [ ] Verify SSH_PRIVATE_KEY is the complete key (with BEGIN/END lines)
- [ ] Verify SSH_USERNAME and SSH_HOST are correct
- [ ] Check that SSH key is authorized on cPanel (add public key to cPanel SSH Key Manager)

### Workflow runs but files don't update on cPanel

- [ ] Check FTP path (should be `/public_html/` for main site)
- [ ] Check file permissions on cPanel (should be 755 for dirs, 644 for files)
- [ ] Verify FTP user has write permissions

---

## HOW IT WORKS (Behind the Scenes)

1. You `git push origin main`
2. GitHub detects the push
3. GitHub Actions workflow starts (`.github/workflows/deploy-to-cpanel.yml`)
4. Workflow:
   - Checks out your code
   - Syncs `src/` folder to cPanel `/public_html/` via FTP
   - Connects via SSH to run post-deployment commands
   - Sets correct file permissions (755/777)
   - Clears caches
5. Deployment complete ✅

---

## IMPORTANT NOTES

### Security:
- ⚠️ GitHub secrets are encrypted and only visible to you
- ⚠️ Secrets are never printed in logs
- ⚠️ Anyone with write access to repo can see secrets exist (but not the values)

### Workflow:
- ⚠️ Only deploys when you push to `main` branch (not `test-deploy` or other branches)
- ⚠️ If workflow fails, your `main` branch is still updated (only deployment fails)
- ⚠️ Manual rollback might be needed if deployment breaks something

### Costs:
- GitHub Actions is free for public repos
- Free for private repos too (2000 minutes/month)
- Your deploy takes ~2-3 minutes, so plenty of free quota

---

## NEXT STEPS

1. ✅ Add all 6 GitHub secrets above
2. ✅ Test workflow on test branch
3. ✅ If test passes, deploy to main: `git push origin main`
4. ✅ Monitor deployment in GitHub → Actions tab
5. ✅ Verify site updated on cPanel (check file modification times)

---

## QUICK REFERENCE

**Where to add secrets:**  
GitHub → Repository Settings → Secrets and variables → Actions → New repository secret

**Secrets needed:**
```
FTP_SERVER (ftp.yourdomain.com)
FTP_USERNAME (cpanel_user)
FTP_PASSWORD (your_password)
SSH_HOST (yourdomain.com)
SSH_USERNAME (cpanel_user)
SSH_PRIVATE_KEY (-----BEGIN RSA PRIVATE KEY----- ... -----END RSA PRIVATE KEY-----)
```

**Workflow file:**  
`.github/workflows/deploy-to-cpanel.yml` (already created and committed)

**Monitor deployment:**  
GitHub → Your Repo → Actions → Latest workflow run

---

**Setup Time:** 10-15 minutes  
**Status:** Ready for deployment automation  
**Next:** Manual deployment OR automated deployment (choose one)
