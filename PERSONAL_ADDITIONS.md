# Personal additions — build changelog (explicit)

This file records **exactly** what was added or changed in a single Cursor-assisted iteration, so you can trace behavior, regressions, or merge conflicts without guessing from diffs alone.

---

## 1. Iteration metadata

| Field | Value |
|--------|--------|
| **Tool / assistant** | Cursor (agent: Auto) |
| **Human-readable tag** | `CURSOR-2026-05-14-AUTH-API` |
| **Calendar date (authoritative in chat)** | May 14, 2026 |
| **Phases addressed (per `PROGRESS.md`)** | **Phase 4** — API endpoints, auth subset only. **Phase 5** — authentication system checklist items that were still open (API parity + forgot/reset shells + login link). |
| **Phases not addressed** | Phase 6+ (user/finance/admin/cron APIs, extra dashboard pages, assets, CI, etc.). |
| **Repository root** | `investment_site/` (sibling to workspace `chino` as laid out on disk). |

Use the tag **`CURSOR-2026-05-14-AUTH-API`** when searching git history, tickets, or chat logs.

---

## 2. Phase 4 — what “solved” means here

`PROGRESS.md` Phase 4 originally listed six **user** auth API scripts under `api/auth/`. Those files **did not exist** before this iteration; the UI already referenced some of them (e.g. `register.php` used `fetch('/api/auth/register.php')`, nav used `/api/auth/logout.php`), which would have produced **404** until this work.

### 2.1 New directory

- **`src/api/auth/`** — created. No `src/api/` tree existed before.

### 2.2 New file: `src/api/auth/register.php`

- **HTTP method:** rejects non-`POST` with `error(..., 405)` via `includes/response.php`.
- **Input source:** `$_POST` (matches existing `register.php` which sends `FormData` from fields `first_name`, `last_name`, `email`, `password`, `referral_code`).
- **Normalization:** trims string fields; email lowercased with `strtolower(trim(...))`; optional `referral_code` becomes `null` when empty string after trim.
- **Validation:** `Validator::required` on first/last name; `Validator::email` on email; `Validator::minLength($password, 8)`; if referral present, `Validator::maxLength($referral_code, 32)`.
- **Business logic:** single call to existing **`registerUser($first_name, $last_name, $email, $password, $referral_code)`** in `includes/auth.php` (no duplicated SQL).
- **Success JSON:** `success($message, ['user_id' => (int) ...])` — only exposes new user id, not password or hash.
- **Failure JSON:** `error($message)` from `registerUser` return array (e.g. duplicate email).
- **Bootstrap order:** `config.php` → `response.php` → `validation.php` → `auth.php` (`auth.php` already pulls `db.php` and `security.php`).

### 2.3 New file: `src/api/auth/login.php`

- **HTTP method:** `POST` only; otherwise 405.
- **Input:** `email`, `password` from `$_POST` (matches `login.php` form).
- **Validation:** valid email shape + non-empty password.
- **Business logic:** `authenticateUser($email, $password)` from `includes/auth.php`.
- **On failure:** `error(..., 401)` with the same user-facing message as `authenticateUser` (e.g. wrong password / inactive account).
- **On success:** calls **`loginUser((int) $user['id'], $result['user'])`** from `includes/session.php` to set `$_SESSION['user_id']`, etc.
- **JSON user payload:** passes row through new **`sanitizeUserForClient()`** (see section 3.2) so `password_hash` and reset-token columns are never echoed.

### 2.4 New file: `src/api/auth/logout.php`

- **Dual behavior by design** (because the app used a **GET** link to logout in HTML, not only `fetch`):
  - **`POST`:** loads `response.php`, calls `logoutUser()`, returns JSON `success('Logged out successfully')`.
  - **`GET`:** calls `logoutUser()`, then **`Location:`** redirect to `{SITE_URL trimmed}/login.php` with 302 — no JSON (browser navigation).
- **Non-GET/POST:** returns 405 with minimal JSON body (does not load `response.php` helpers to avoid double headers; small inline JSON only).

### 2.5 New file: `src/api/auth/check-session.php`

- **HTTP method:** `GET` only; otherwise 405.
- **Logic order:** `isLoggedIn()` first; if false → `success('', ['logged_in' => false])`. Then `isSessionValid()` (may destroy session on timeout / IP mismatch per existing `session.php`); if false → same logged-out payload. Then `getCurrentUser()`; if null → logged out. Else `success('', ['logged_in' => true, 'user' => sanitizeUserForClient($user)])`.
- **Rationale:** avoids calling `isSessionValid()` before confirming a session exists, matching the control flow in `session.php`.

### 2.6 New file: `src/api/auth/forgot-password.php`

- **HTTP method:** `POST` only.
- **Input:** `email` from `$_POST`, lowercased/trimmed, validated with `Validator::email`.
- **Enumeration resistance:** always ends with the **same generic success message** whether or not an account exists (string stored in `$generic` in source).
- **Active users only:** selects `id, first_name, email` from `users` with `status = 'active'`. If no row, **no token** is generated and **no email** is sent, but response is still generic success.
- **When user exists:** calls **`generatePasswordResetToken($email)`** from `auth.php`, builds reset URL: `rtrim(SITE_URL,'/') . '/reset-password.php?token=' . urlencode($token)`, sends via **`Mail::sendPasswordReset`** from `includes/mail.php`.
- **Bootstrap:** includes `mail.php` (which itself `require_once`’s `config.php`; second include is harmless).

### 2.7 New file: `src/api/auth/reset-password.php`

- **HTTP method:** `POST` only.
- **Input:** `token`, `password`, `password_confirm` from `$_POST` (matches `reset-password.php` shell form field names).
- **Validation:** non-empty token; password min length 8; equality of password and confirm.
- **Business logic:** **`resetPassword($token, $password)`** from `auth.php` (which internally uses `verifyPasswordResetToken` and clears reset columns on success).
- **Responses:** `success()` on OK; `error(..., 400)` on failure with message from helper (e.g. invalid/expired token).

---

## 3. Supporting / cross-cutting code changes

### 3.1 New file: `src/includes/config.php` (committed bootstrap)

**Problem before:** Many pages did `require_once __DIR__ . '/includes/config.php'`, but that path pointed to **nothing in repo** (only `config/config.example.php` existed at project level). `session.php` called `Database::getInstance()` without guaranteeing `db.php` was loaded.

**What this file does:**

1. Resolves **`investment_site/config/`** directory: `dirname(__DIR__, 2) . '/config/'`.
2. If **`config.php`** exists there → `require_once` it (production overrides; gitignored per project convention).
3. Else → `require_once config.example.php` (dev default).
4. Then **`require_once __DIR__ . '/db.php'`** so `Database` is always defined after config.
5. Then **`require_once __DIR__ . '/functions.php'`** so helpers like **`formatCurrency()`** exist when only `config.php` + `session.php` are loaded (e.g. `dashboard/index.php` previously referenced `formatCurrency` without including `functions.php`).

**Important:** This is **not** a duplicate of secrets; secrets remain in `.env` and optional `config/config.php`.

### 3.2 Modified file: `src/includes/auth.php`

- **Added function:** `sanitizeUserForClient(array $user)` — `unset` on keys: `password_hash`, `password_reset_token`, `password_reset_expires`, returns the array.
- **Placement:** immediately after `resetPassword()` and before `changeUserPassword()` to keep password-related helpers grouped.

### 3.3 Modified file: `src/login.php`

- **UI only:** added a centered link **“Forgot password?”** pointing to **`/forgot-password.php`** between the submit button and the “Register here” line.

---

## 4. Phase 5 — shell pages and checklist alignment

These items were marked incomplete in an older `PROGRESS.md` even though `register.php` / `login.php` already existed. This iteration **finished** the missing shells and aligned checkboxes.

### 4.1 New file: `src/forgot-password.php`

- Requires `includes/config.php`, `includes/session.php`, calls **`requireLogout()`** (guest-only page).
- Single email field; `fetch` **POST** to **`/api/auth/forgot-password.php`** with `FormData`.
- Uses Tailwind CDN like other auth shells.
- Shows generic result via `alert()` using `data.message`.

### 4.2 New file: `src/reset-password.php`

- Same bootstrap + **`requireLogout()`**.
- Reads **`$_GET['token']`**; if missing/empty, shows static “Invalid or missing reset link” and link to forgot page (no form).
- If token present: hidden input `token`, fields `password` and `password_confirm`, POST to **`/api/auth/reset-password.php`**; on success redirects to **`/login.php`**.

---

## 5. `PROGRESS.md` edits (documentation only)

- Phase 4 block: all six auth API lines marked **`[x]`**, heading notes auth-complete.
- Phase 5 block: duplicate API lines + four frontend lines marked **`[x]`**; heading set to COMPLETE.
- **Git table:** row for “Commit #4” filled with date May 14, 2026 and description referencing `src/api/auth/*` and `includes/config.php` bootstrap.
- **Notes & blockers:** expanded “Completed” bullets to list auth endpoints, new shells, and bootstrap rationale.
- **Current task / next steps / last updated:** repointed to Phase 6 and later phases.

**Attribution comments in `PROGRESS.md` (HTML, invisible in most Markdown previews; visible in raw editor / git diff):**

| Location | Comment purpose |
|----------|------------------|
| Immediately under **Status: In Progress** | Global iteration tag `CURSOR-2026-05-14-AUTH-API`, pointer to this file |
| Immediately above **Phase 4** heading | Phase 4 auth API work |
| Immediately above **Phase 5** heading | Phase 5 shells + checklist |
| Immediately above **NOTES & BLOCKERS** → **Completed** | Notes section expansion |
| Between git table rows 4 and 5 | Pin commit log row #4 to this iteration |
| Immediately above **Last Updated** footer | Footer timestamp attribution |

Search the repo for **`CURSOR-2026-05-14`** or **`Cursor | Auto`** to find all anchors.

---

## 6. What was explicitly *not* done

- No **`api/user/*`**, **`api/investments/*`**, **`api/admin/*`**, **`api/cron/*`** files.
- No CSRF tokens wired into auth forms (pre-existing gap vs security checklist).
- No rate limiting on auth endpoints.
- No change to **`config/config.example.php`** session `cookie_secure` comparison (pre-existing oddity: compares full URL to literal `'https://'`).
- PHP **`php -l`** was not run successfully in the Cursor Windows shell because **`php` was not on PATH** in that environment; local verification is recommended.

---

## 7. Suggested verification commands (local)

From `investment_site` after configuring `.env` and database:

```bash
php -S localhost:8000 -t src/
```

Then manually or with HTTP client:

- `POST /api/auth/register.php` with form fields matching `register.php`.
- `POST /api/auth/login.php` with valid credentials.
- `GET /api/auth/check-session.php` while cookie session active.
- `GET /api/auth/logout.php` from browser (expect redirect).
- `POST /api/auth/logout.php` with `fetch` (expect JSON).

---

## 8. File manifest (this iteration)

| Path | Action |
|------|--------|
| `src/includes/config.php` | **Created** |
| `src/includes/auth.php` | **Modified** (added `sanitizeUserForClient`) |
| `src/api/auth/register.php` | **Created** |
| `src/api/auth/login.php` | **Created** |
| `src/api/auth/logout.php` | **Created** |
| `src/api/auth/check-session.php` | **Created** |
| `src/api/auth/forgot-password.php` | **Created** |
| `src/api/auth/reset-password.php` | **Created** |
| `src/forgot-password.php` | **Created** |
| `src/reset-password.php` | **Created** |
| `src/login.php` | **Modified** (forgot link) |
| `PROGRESS.md` | **Modified** |
| `PERSONAL_ADDITIONS.md` | **Created** (this file) |

---

*End of personal additions log for tag `CURSOR-2026-05-14-AUTH-API`.*
